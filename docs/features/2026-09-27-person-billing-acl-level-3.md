# ACL Person ↔ Billing, уровень 3: убрать сущности `Person` из домена `Billing` (и наоборот)

**Статус:** 🟡 В работе (анализ; решение о реализации не принято) \
**Начато:** 2026-09-27 \
**Обновлено:** 2026-09-27

## Цель

При разборе delegate-геттеров ([`2026-09-27-contract-delegate-getters.md`](2026-09-27-contract-delegate-getters.md)) замечено: `src/Billing/Domain/Entity/Contract.php` импортирует `App\Person\Domain\Entity\Musician`. Проверка показала, что это не новая находка, а ровно **уровень 3** из [ADR-0002](../adr/0002-person-billing-acl.md) — разрыв прямой Doctrine-связи между сущностями, который там **сознательно отложен** «до появления конкретного драйвера» (цена — миграция схемы, отказ от Doctrine-связей, правки во всех местах, полагающихся на прямую связь).

Этот файл фиксирует текущий масштаб связности, как бы я делал уровень 3, и что нужно решить перед стартом. Сам факт «делаем/не делаем» — архитектурное решение, см. раздел «ADR».

## Текущее состояние (на 2026-09-27)

Уровни 1–2 ADR-0002 сняли прямые импорты на границе `Application` и в enum'ах. Оставшиеся кросс-контекстные импорты:

**`Billing` → `Person`:**
- `Billing/Domain/Entity/Contract.php` — `ManyToOne(Musician::class, inversedBy: 'contracts')`.
- `Billing/Domain/Entity/Invoice.php` — `ManyToOne(Musician::class)`; `getPayerFullName/Phone/Email()` читают `Musician`.
- `Billing/Domain/Repository/MusicianLookupInterface.php` — возвращает `Person\...\Musician`, кидает `Person\...\MusicianNotFoundException` (ограничение задокументировано в ADR-0002).
- `Billing/Presentation/Http/Rest/Common/Factory/MusicianDtoFactory.php` — принимает `Musician`.

**`Person` → `Billing`:**
- `Person/Domain/Entity/Musician.php` — `OneToMany(Contract::class)`, `addContracts()/getContracts()`.
- `Person/Domain/Repository/MusicianContractsProviderInterface.php` — возвращает `Billing\...\Contract`.
- `Person/Presentation/.../Factory/{ContractDtoFactory,TariffDtoFactory}.php` — принимают `Contract`/`Tariff`.

**Попутно:** `Core/Ampq/Event/ContractCreatedEvent.php` импортирует `Billing\Domain\Enum\ContractStatusEnum` — shared kernel зависит от бизнес-контекста. К уровню 3 напрямую не относится, но это то же нарушение направления зависимостей; учесть в том же ADR или отдельной мелкой задачей.

Наблюдение: инверсная сторона `Musician::$contracts` фактически не используется как ORM-связь — `GetMusicianById/ByPhoneUseCase` сами наполняют коллекцию через `addContracts($provider->findByMusicianId(...))`. То есть `Person` уже живёт по модели «спросить договоры у `Billing` через интерфейс», а Doctrine-коллекция служит просто контейнером.

## Как бы я делал

Принцип: **каждый контекст хранит у себя только идентификатор чужой сущности, а нужные данные получает через интерфейс, сформулированный им самим, в виде своего объекта** (read model / snapshot), а не чужой сущности. Gateway-классы в `Infrastructure/Gateway/` (уже существующие по ADR-0002) остаются единственным местом, где один контекст знает о другом.

### Шаг 1. `Person` перестаёт знать о `Contract` (дешевле, начать с него)

- Убрать `OneToMany $contracts`, `addContracts()`, `getContracts()` из `Musician`. Схема БД не меняется — у инверсной стороны нет колонки.
- `Person\Domain\Repository\MusicianContractsProviderInterface::findByMusicianId()` возвращает не `Contract[]`, а `Person`-овскую проекцию — например `Person\Application\Dto\MusicianContractDto` (номер, даты, статус, тариф плоскими полями).
- `Billing\Infrastructure\Gateway\PersonMusicianContractsGateway` мапит `Contract → MusicianContractDto` (единственное место со знанием обеих сторон).
- `GetMusicianById/ByPhoneUseCase` возвращают музыканта вместе со списком договоров (DTO-пара или отдельный Application-DTO) вместо того, чтобы класть договоры в сущность.
- `Person\...\ContractDtoFactory`/`TariffDtoFactory` работают с `MusicianContractDto` — импорт `Billing` из `Person` исчезает полностью.

### Шаг 2. `Billing` перестаёт знать о `Musician`

- `Contract` и `Invoice`: заменить `ManyToOne Musician` на скалярное `string $musicianId` **в той же колонке `musician_id`** — данные не мигрируются, меняется только маппинг.
- FK-констрейнт в БД: монолит с одной базой, поэтому я бы **сохранил** FK на уровне БД ради целостности (добавив его руками в миграцию). Цена — `doctrine:schema:validate` будет показывать расхождение; альтернатива — отказаться от FK и полагаться на проверку существования в `CreateContractUseCase` через `MusicianLookupInterface`. Это как раз пункт для ADR.
- `MusicianLookupInterface::getById()` возвращает `Billing`-овский `Billing\Domain\ValueObject\MusicianSnapshot` (id, ФИО, email, телефон, статус, степень) и кидает свой `Billing\Domain\Exception\MusicianNotFoundException`. Реализация — `Person\Infrastructure\Gateway\BillingMusicianLookupGateway`.
- **`Invoice` — копировать данные плательщика в сам счёт** (`payer_full_name`, `payer_email`, `payer_phone`) в момент выставления. Это не только снятие зависимости, но и более правильная доменная модель: счёт фиксирует плательщика на дату выставления и не должен меняться, если музыкант позже сменил почту. Требует миграции с заполнением новых колонок из `musician`.
- `Contract` данные музыканта не копирует — где они нужны (`ContractCreatedEvent`, `ContractDtoFactory`, `IssueInvoicesUseCase`), получать через `MusicianLookupInterface`. После [delegate-getters](2026-09-27-contract-delegate-getters.md) для события это одна фабрика.
- `Billing\...\MusicianDtoFactory` принимает `MusicianSnapshot`.
- Цена: N+1 при выдаче списка договоров с музыкантами (сейчас Doctrine джойнит). Решение — метод `getByIds(array $ids)` в `MusicianLookupInterface`.

### Шаг 3. Закрепить границу автоматически

Без проверки граница снова размоется «по старой памяти» (минус, отмеченный в ADR-0002). Я бы добавил [deptrac](https://github.com/qossmic/deptrac) с правилом: `App\Person\*` и `App\Billing\*` не импортируют друг друга, кроме `*/Infrastructure/Gateway/*`; `App\Core\*` не импортирует бизнес-контексты. Запуск — в CI рядом с Codeception. Это выбор инструмента — тоже в ADR.

### Порядок и проверка

1. Сначала [delegate-getters](2026-09-27-contract-delegate-getters.md) — сократит точки, где нужен `Musician`.
2. Шаг 1 (`Person`) — отдельный PR, без миграций.
3. Шаг 2 (`Billing`) — отдельный PR с миграцией для `Invoice`.
4. Шаг 3 (deptrac) — отдельный PR, после которого проверка зелёная.

На каждом шаге: `vendor/bin/codecept run` (unit/functional/acceptance), ручная проверка `GET /api/person/v1/musician/{id}` (с договорами — именно тут был исходный баг ADR-0002), создание договора, `billing:invoice:issue` → `send_to_acquire` → `check_paid_status`.

## ADR

**Нужен, и до начала реализации.** Уровень 3 — это изменение правила зависимостей между контекстами, прямой критерий из [`docs/adr/README.md`](../adr/README.md). Кроме того, ADR-0002 явно отложил его с условием «переоценить, если появится конкретный драйвер» — значит, реализация должна начинаться с фиксации этого драйвера.

Как оформить: **новый ADR-0003** («Уровень 3 ACL: связь `Person`/`Billing` только по идентификатору»), а не правка ADR-0002 — по правилам ADR не редактируется задним числом. В ADR-0002 меняется только строка статуса со ссылкой на ADR-0003. В ADR-0003 решить:
- драйвер (для учебного проекта честный драйвер — «отработать разделение контекстов на практике»; это допустимо, но должно быть названо);
- FK в БД: сохранить или нет;
- snapshot плательщика в `Invoice` vs запрос через lookup;
- deptrac как средство контроля;
- что делать с `Core` → `Billing` (`ContractCreatedEvent`).

Если решим **не делать** — этот файл перевести в ❌ со ссылкой на ADR-0002, где обоснование уже есть; новый ADR тогда не нужен.

## План

1. Решить, делать ли уровень 3 (за пользователем).
2. Если да — ADR-0003 со статусом 🟡 Предложено, обсудить открытые вопросы выше.
3. Реализация по шагам из раздела «Как бы я делал», каждый шаг — отдельный PR.

## Сделано

- Проанализирована текущая кросс-контекстная связность, составлены рекомендации (этот файл).

## Не стали делать

Пока нет решений.

## Изменённые файлы

Пока нет диффа — работа не начата.

## Ссылки

- [ADR-0002](../adr/0002-person-billing-acl.md) — уровни 1–2, уровень 3 отложен («Отклонённые варианты»).
- [`2026-09-09-person-billing-acl.md`](2026-09-09-person-billing-acl.md) — дневник уровней 1–2.
- [`2026-09-27-contract-delegate-getters.md`](2026-09-27-contract-delegate-getters.md) — связанная задача, делать раньше.
