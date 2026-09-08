# 0002. Изолировать `Person` и `Billing` через Anti-Corruption Layer вместо дублирования кода

**Статус:** ✅ Принято (уровни 1–2 реализованы; уровень 3 сознательно отложен — см. «Отклонённые варианты») \
**Дата:** 2026-09-09

## Контекст

При ревью `/src` (см. `docs/refactoring-plan.md`) нашлись два реальных бага в `Person\Presentation\Http\Rest\Musician\Common\{Output\ContractDto,Factory\ContractDtoFactory}`, из-за которых `GET /api/person/v1/musician/{id}` и `/by-phone` падали `TypeError`, если у музыканта был хотя бы один договор. Причина — `TariffDto`/`TariffDtoFactory`/`ContractDto`/`ContractDtoFactory` были продублированы почти 1-в-1 между `Billing\Presentation\Http\Rest\Common\*` и `Person\Presentation\Http\Rest\Musician\Common\*`: одну копию починили раньше, вторая осталась сломанной.

Первая попытка исправить — удалить дубликаты в `Person` и переиспользовать версии из `Billing` напрямую (DRY). Это было оспорено: удаление дубликатов ценой прямой зависимости `Person` от DTO/enum'ов `Billing` разрушает независимость доменов, которую дублирование как будто обеспечивало.

При проверке аргумента выяснилось, что дублирование и не давало независимости: удалённый код `Person\...\TariffDtoFactory` читал `Billing\Domain\Enum\{TariffTypeEnum,TariffStatusEnum,MusicianDegreeTariffEnum}` напрямую — то же связывание с доменом `Billing`, просто продублированное текстом, без единой точки для починки. Более того, тот же паттерн уже был в коде симметрично и без дублирования: `Billing\Presentation\Http\Rest\Common\Output\MusicianDto` использовал `Person\Domain\Enum\{PersonDegreeEnum,PersonStatusEnum}` напрямую. А на уровне `Application`/`Domain`: `Person\Application\UseCase\{GetMusicianByIdUseCase,GetMusicianByPhoneUseCase}` импортировали `Billing\Domain\Repository\ContractRepositoryInterface` напрямую, `Billing\Application\UseCase\CreateContractUseCase` — `Person\Domain\Repository\MusicianRepositoryInterface` напрямую. Плюс сама Doctrine-схема: `Contract` хранит `#[ORM\ManyToOne(targetEntity: Musician::class)]`, `Musician` — `#[ORM\OneToMany(mappedBy: 'musician', targetEntity: Contract::class)]` — двусторонняя связь напрямую между чужими сущностями.

То есть «независимость `Person` и `Billing`» как архитектурное свойство в проекте не выполнялась вообще — требовалось решить, до какого уровня её реально имеет смысл выстраивать, а не просто вернуть дубликаты.

## Решение

Ступенчатый Anti-Corruption Layer (ACL), от дешёвого к дорогому — не всё сразу, а по мере оправданности цены:

### Уровень 1 — свои enum'ы, трансляция через нативный `Enum::from()`, без отдельного класса

- `Person` завёл собственные `Person\Domain\Enum\{TariffTypeEnum,TariffStatusEnum,MusicianDegreeTariffEnum}`, `Billing` — `Billing\Domain\Enum\{MusicianDegreeEnum,MusicianStatusEnum}`. **Полностью чистые**, без единого `use` на другой контекст, даже в докблоке нет ссылки на чужой класс.
- Трансляция — без отдельного класса-обёртки, прямо в фабриках (`Person\...\TariffDtoFactory`, `Billing\...\MusicianDtoFactory`): `TariffTypeEnum::from($tariff->type->value)`. Берётся `->value` (backing value чужого enum'а — доступ к нему не требует знать конкретный класс, работает для любого `BackedEnum`) и по нему нативно конструируется свой enum. `Enum::from()` кидает `\ValueError`, если значения нет среди кейсов — то же свойство «явная поломка вместо тихого рассогласования», что и в предыдущих итерациях, только без дополнительного кода.
- `MusicianDto`/`ContractDto` между контекстами **не объединялись** — это разные проекции (у `Billing` — облегчённая сводка для счёта/договора, у `Person` — полный профиль музыканта); объединение изменило бы контракт API.

**Как пришли к этой форме (три итерации до финальной):**
1. Сначала трансляция (`fromBilling()`/`fromPerson()`) была статическим методом прямо на самом enum'е в `Domain/` — работало, но непоследовательно с Уровнем 2 (там реализация ACL вынесена в `Infrastructure/`, а `Domain/` содержит только интерфейс): enum, импортирующий чужой `Domain/Enum` — чужеродный код внутри Domain-слоя.
2. Перенесли в `Infrastructure/Acl/*Translator` — Domain стал чистым, но папка `Acl/` названа по паттерну (Anti-Corruption Layer), а не по бизнес-смыслу, и класс принимал объект чужого enum'а, то есть всё равно `use`-ил чужой класс, просто в другом файле.
3. Переименовали в `Presentation/.../Gateway/*Gateway`, стали принимать сырое `int`-значение вместо объекта — синтаксическая связь на чужой класс снята полностью.
4. **Финально** — по итогам обсуждения: раз сравнение всё равно идёт по сырому значению, отдельный класс с ручным `match` не даёт ничего сверх того, что даёт нативный `Enum::from()` — тот же `\ValueError` при несовпадении. Класс убрали совсем, трансляция — однострочный вызов прямо в фабрике.

**Честно про цену этого шага**: начиная с итерации 3 (сравнение по значению, не по имени кейса) исчезла защита от одного конкретного сценария — если `Billing` молча переномерует существующие значения местами (например, поменяет местами коды `FREE` и `CHILDISH`, не переименовывая кейсы), `Person` получит валидный, но **семантически неверный** кейс, а не ошибку: `Enum::from()` не отличает «правильное значение» от «случайно попавшего в диапазон чужого». Защититься от этого можно только сравнением по имени кейса, а оно требует `use` на чужой enum-класс — то есть ровно то связывание, ради снятия которого всё это затевалось. Осознанно выбрали не платить за такую защиту: переномерация уже присвоенных значений backed-enum'а — редкое и обычно намеренное изменение, а не то, что происходит незаметно при обычной разработке (в отличие от добавления нового кейса, от чего `\ValueError` защищает как и раньше).

### Уровень 2 — инверсия зависимостей на границе Application/Domain

По тому же паттерну, что уже применялся в проекте для внешних интеграций (`Core\Client\Acquiring\AcquiringClient` поверх `AcquiringBundle`), развёрнутому между своими контекстами:

- `Person\Domain\Repository\MusicianContractsProviderInterface` — интерфейс, сформулированный `Person`-ом («дай мне договоры музыканта»). Реализация — `Billing\Infrastructure\Gateway\PersonMusicianContractsGateway` (внутри использует `Billing`'овский `ContractRepositoryInterface` как раньше). `GetMusicianByIdUseCase`/`GetMusicianByPhoneUseCase` зависят только от своего интерфейса.
- `Billing\Domain\Repository\MusicianLookupInterface` — интерфейс, сформулированный `Billing`-ом («найди музыканта по id»). Реализация — `Person\Infrastructure\Gateway\BillingMusicianLookupGateway` (внутри использует `Person`'овский `MusicianRepositoryInterface`). `CreateContractUseCase` зависит только от своего интерфейса.

Направление импорта в обоих случаях развёрнуто: контекст-потребитель объявляет интерфейс у себя в `Domain/`, контекст-поставщик реализует его у себя в `Infrastructure/Gateway/` и знает о чужом интерфейсе — а не наоборот.

**Почему не `Acl`, но и не `Presentation` (в отличие от Уровня 1)**: изначально папка называлась `Infrastructure/Acl/` — тот же паттерн-нейминг, что и на Уровне 1, и по той же причине переименована в `Infrastructure/Gateway/` (класс тоже переименован, `*Adapter` → `*Gateway`, для единообразия с Уровнем 1). Но **слой остался `Infrastructure`, не `Presentation`**: эти классы реализуют интерфейсы, от которых зависят `Application`-юзкейсы (`GetMusicianByIdUseCase` и т.д.) через DI. Перенос в `Presentation` означал бы, что `Application` зависит (через резолвинг интерфейса) от класса в самом внешнем слое — разворачивает направление зависимости и противоречит правилу `CLAUDE.md` («`Infrastructure/` — реализации репозиториев»). Уровень 1 мог позволить себе `Presentation`, потому что его код вызывается только другим Presentation-кодом (DTO-фабриками), никогда — из `Application`.

**Осознанное ограничение**, задокументированное прямо в самих интерфейсах: возвращаемый тип — по-прежнему чужая Doctrine-сущность (`Contract`/`Musician`), а не DTO. `Musician::addContracts()` наполняет ORM-коллекцию, `Contract::create()` требует реальный управляемый Doctrine-объект для `ManyToOne`-связи — DTO для этого не годится. Полное стирание типа потребовало бы уровня 3.

### Уровень 3 — разрыв прямой Doctrine-связи

Рассмотрен, **не реализован** — см. «Отклонённые варианты».

## Последствия

**Плюсы:**
- Баг, вызвавший весь пересмотр (расхождение копий `ContractDto`/`ContractDtoFactory`), структурно не может повториться — общего enum'а/DTO для рассинхронизации больше нет, а несовпадающий кейс `Billing`-enum'а явно падает вместо того, чтобы тихо разъехаться.
- `Person` и `Billing` могут независимо переименовывать/расширять свои enum'ы тарифов/статусов, не задевая друг друга — раньше переименование `Billing\Domain\Enum\TariffTypeEnum` кейса меняло бы (не всегда синхронно) и код `Person`.
- Направление зависимости на границе `Application`/`Domain` соответствует тому, кто в реальности является потребителем/владельцем данных, а не тому, у кого раньше был удобный прямой импорт.

**Минусы / цена:**
- 11 новых файлов (5 enum'ов, 2 интерфейса, 2 Gateway-класса Уровня 2, пересозданные `TariffDto`/`TariffDtoFactory`) и правки в 7 существующих ради того же поведения, что было раньше — цена ACL всегда в добавленных файлах ради явной границы.
- Возвращаемый тип на границе Уровня 2 всё ещё «чужой» (`Contract`/`Musician`) — изоляция неполная, задокументированная, но не полная; при поверхностном взгляде может показаться, что дело сделано до конца.
- Симметричная работа с обеих сторон (`Person`→`Billing` и `Billing`→`Person`) — если в будущем появится третий похожий кросс-контекстный вызов, его тоже придётся оформлять этим же паттерном, а не забыть и сделать прямой импорт по старой памяти.

## Отклонённые варианты

- **Просто вернуть дублирование `TariffDto`/`TariffDtoFactory` как было** — не даёт независимости, только неявно: копия всё равно читала `Billing`-enum'ы напрямую, и именно рассинхронизация копий породила баг №1 в ревью. Возврат к этому состоянию воспроизводит тот же класс риска.
- **Удалить дубликаты и оставить `Person` переиспользовать DTO/enum `Billing` напрямую** (первая правка в этой сессии) — более явная связанность, чем дублирование, а не меньшая; отклонено по итогам обсуждения с пользователем.
- **Уровень 3 (заменить `ManyToOne`/`OneToMany` на скалярный `musicianId: string` + получение через интерфейс)** — не отклонён навсегда, отложен: цена (миграция схемы, отказ от удобства Doctrine-коллекций и каскадов, правки во всех местах, которые сейчас полагаются на прямую связь) оправдана только при реальном плане на разделение сервисов/команд. Для учебного проекта на курсе — дороже, чем даёт пользы прямо сейчас. Переоценить, если появится конкретный драйвер.
- **Не строить ACL вообще, оставить прямые кросс-контекстные импорты (как большая часть кода была до этой сессии)** — рабочий вариант для маленького одиночного проекта, но именно он и привёл к необнаруженному расхождению копий; отклонён в пользу структурной защиты от повторения.

## Ссылки

- `docs/refactoring-plan.md` — исходное ревью `/src`, где найдены баги, приведшие к этому решению.
- [ADR-0001](0001-claude-md-and-adr-process.md) — правила ведения ADR.
- Затронутые файлы: `src/Person/Domain/Enum/{TariffTypeEnum,TariffStatusEnum,MusicianDegreeTariffEnum}.php`, `src/Billing/Domain/Enum/{MusicianDegreeEnum,MusicianStatusEnum}.php`, `src/Person/Presentation/Http/Rest/Musician/Common/{Output/TariffDto,Factory/TariffDtoFactory}.php`, `src/Billing/Presentation/Http/Rest/Common/{Output/MusicianDto,Factory/MusicianDtoFactory}.php`, `src/Person/Domain/Repository/MusicianContractsProviderInterface.php`, `src/Billing/Infrastructure/Gateway/PersonMusicianContractsGateway.php`, `src/Billing/Domain/Repository/MusicianLookupInterface.php`, `src/Person/Infrastructure/Gateway/BillingMusicianLookupGateway.php`, `src/Person/Application/UseCase/{GetMusicianByIdUseCase,GetMusicianByPhoneUseCase}.php`, `src/Billing/Application/UseCase/CreateContractUseCase.php`.
