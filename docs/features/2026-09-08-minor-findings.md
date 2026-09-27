# Мелкие замечания по типизации и связности Contract

**Статус:** 🟡 В работе \
**Начато:** 2026-09-08 \
**Обновлено:** 2026-09-27

## Цель

Два мелких замечания из ревью `/src`, не влияющих на поведение, но снижающих строгость типизации и повышающих связность между `Billing`-сущностями:

- `AcquiringClient::checkPayment()` — без возвращаемого типа, в отличие от остального клиента.
- `Contract` entity — `generateNumber()`, `activate()`, `changeTariff()` без return type; много delegate-геттеров (`getTariffId/Value/TypeName/...`, `getMusicianId/Email/Phone`) — Law of Demeter наружу из `Contract`.

По ходу работы объём вырос: к исходным замечаниям добавились найденные при их исправлении (пункты 4–6 плана).

Третье замечание из того же списка («`Kernel.php` без `declare(strict_types=1)`») уже закрыто — см. [`2026-09-08-person-billing-critical-bugfixes.md`](2026-09-08-person-billing-critical-bugfixes.md), сюда не переносится.

## План

1. `src/Core/Client/Acquiring/AcquiringClient.php` — дать `checkPayment()` явный возвращаемый тип (уточнить, что реально возвращает `AcquiringFacade`, не оставлять неявный `mixed`).
2. `src/Billing/Domain/Entity/Contract.php` — добавить return type `generateNumber()`, `activate()`, `changeTariff()`.
3. ~~`Contract` — оценить delegate-геттеры~~ — вынесено в отдельную задачу [`2026-09-27-contract-delegate-getters.md`](2026-09-27-contract-delegate-getters.md) (другая по смыслу правка, в ветку `feature/strict-return-types` не входит).

Ветка: `feature/strict-return-types` — пункты 1–2 и 4–6.

Добавлено по ходу работы (замечания, найденные при выполнении пунктов 1–2):

4. `AcquiringClient` и `FiscalDataOperatorClient` пропускают наружу исключения бандлов — транслировать в исключения `Core`.
5. `CLAUDE.md` — имена сьютов Codeception указаны в нижнем регистре и не работают.
6. Правило изоляции бандлов нигде не зафиксировано как решение — оформить ADR.

## Сделано

- `AcquiringClient::checkPayment()` → `DateTimeImmutable` — ровно то, что возвращает `AcquiringFacade::checkPayment()` (дата оплаты). Это не DTO бандла, трансляция в DTO `Core` не требуется.
- `Contract::generateNumber()` → `string`; `activate()`, `changeTariff()` → `void`, `return $this` убран. Сначала был `static` (сохранял fluent-контракт как есть), но возвращаемое значение нигде не использовалось — ни в `src/`, ни в тестах, ни в моках, — а мутирующие методы остальных сущностей проекта (`Person`, `Billing`) fluent-интерфейс не используют. Правка вызывающего кода не понадобилась; `doctrine:schema:validate` — OK.
- **Утечка исключений бандлов.** `AcquiringClient` и `FiscalDataOperatorClient` пробрасывали `AcquiringException`/`FiscalDataOperatorException` как есть, и юзкейсы `Billing` неявно зависели от классов бандлов. Заведены `AcquiringClientException` и `FiscalDataOperatorClientException` в `Core\Client\*` (по образцу `UnitOfWorkException`), клиенты оборачивают любое исключение из вызова фасада (`Throwable`), исходное — в `previous`. Параметр `$code` из конструкторов этих исключений убран: сначала код исходного копировался через `(int)$exception->getCode()`, но у `PDOException` это строка SQLSTATE (`'42P01'` → `42`), и код обёртки терял смысл. Код исходного доступен через `getPrevious()`. Сначала ловили только исключения бандла, но `FiscalDataOperatorFacade::sendReceipt()` сам делает `persist`/`flush`, и ошибка сохранения уходила наружу мимо клиента — найдено на ревью ветки. Юзкейсы не менялись: они ловят `Throwable` и заворачивают в `InvoiceManageException`, поведение то же. Правило — [ADR-0003](../adr/0003-bundle-integrations-via-core-client.md).
- Добавлены `tests/Unit/Core/Client/{Acquiring/AcquiringClientCest,FiscalDataOperator/FiscalDataOperatorClientCest}.php` — успешные вызовы, трансляция исключений бандла и прочих исключений фасада (падение `generate()` у эквайринга, `flush()` у ОФД) (до этого у клиентов тестов не было). Фасады не мокаются (`final`), используются настоящие заглушки бандлов с их «магическими» значениями, при которых они кидают ошибку. После ревью оба Cest приведены к одному стилю: хелпер `catchException()`, `getClient()` принимает исключение для мока.
- Оформлен [ADR-0003](../adr/0003-bundle-integrations-via-core-client.md) — правило изоляции бандлов задним числом плюс трансляция исключений. В `CLAUDE.md` раздел о бандлах дополнен правилом для исключений (синхронизирован с ADR: транслируется любое исключение фасада, не только исключения бандла) и уточнено, что у `NotifierBundle` нет фасада (интеграция через AMQP-команду) — раньше было написано, что фасад есть у каждого бандла.
- **`CLAUDE.md`, раздел «Тесты».** Сьюты регистрозависимы (`Unit`/`Functional`/`Acceptance`, по именам `tests/*.suite.yml`), `codecept run unit` падал с «Suite 'unit' could not be found» — примеры исправлены. Добавлена команда миграции тестовой БД: на свежем окружении `primeira_test` пустая, и `Functional` падал с `relation "musician" does not exist` (9 ошибок из 11) независимо от кода.
- Проверено: `php -l`, `lint:container`, `codecept run Unit` (17 тестов) и `Functional` (11 тестов) — зелёные. `Acceptance` не запускался.

## Не стали делать

- `NotifyPersonCommand` (`Core\Ampq\Event`) и `Billing\...\ContractCreated\Handler` импортируют `NotifierBundle\Enum\PersonTypeEnum` — отступление от ADR-0003 для асинхронной интеграции, зафиксировано в нём как известное. Не исправлено: другая граница (AMQP-сообщение, а не клиент), кандидат на отдельную задачу.
## Изменённые файлы

Заполнить перед мёржем: `git diff --stat master...feature/strict-return-types` и ссылка на PR.

## Ссылки

- Находка «🟢 Мелкие замечания» исходного ревью `/src` — перенесена сюда.
- [`2026-09-27-contract-delegate-getters.md`](2026-09-27-contract-delegate-getters.md) — вынесенный пункт 3.
- [ADR-0003](../adr/0003-bundle-integrations-via-core-client.md) — правило изоляции бандлов, оформленное по ходу этой задачи.
