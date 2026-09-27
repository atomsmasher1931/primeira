# Мелкие замечания по типизации и связности Contract

**Статус:** ✅ Готово (смёржено, PR #5) \
**Начато:** 2026-09-08 \
**Обновлено:** 2026-09-27 \
**Ветка:** `feature/strict-return-types`

## Цель

Два мелких замечания из ревью `/src`, не влияющих на поведение, но снижающих строгость типизации и повышающих связность между `Billing`-сущностями:

- `AcquiringClient::checkPayment()` — без возвращаемого типа, в отличие от остального клиента.
- `Contract` entity — `generateNumber()`, `activate()`, `changeTariff()` без return type; много delegate-геттеров (`getTariffId/Value/TypeName/...`, `getMusicianId/Email/Phone`) — Law of Demeter наружу из `Contract`.

По ходу работы объём вырос: к исходным замечаниям добавились найденные при их исправлении (пункты 4–6 плана).

Третье замечание из того же списка («`Kernel.php` без `declare(strict_types=1)`») уже закрыто — см. [`2026-09-08-person-billing-critical-bugfixes.md`](2026-09-08-person-billing-critical-bugfixes.md), сюда не переносится.

## План

1. `AcquiringClient::checkPayment()` — явный возвращаемый тип.
2. `Contract` — return type у `generateNumber()`, `activate()`, `changeTariff()`.
3. ~~`Contract` — оценить delegate-геттеры~~ — вынесено в [`2026-09-27-contract-delegate-getters.md`](2026-09-27-contract-delegate-getters.md).
4. *(добавлено по ходу)* `AcquiringClient` и `FiscalDataOperatorClient` пропускают наружу исключения бандлов — транслировать в исключения `Core`.
5. *(добавлено по ходу)* `CLAUDE.md` — имена сьютов Codeception указаны в нижнем регистре и не работают.
6. *(добавлено по ходу)* Правило изоляции бандлов нигде не зафиксировано как решение — оформить ADR.

## Сделано

- `AcquiringClient::checkPayment()` → `DateTimeImmutable` — то, что возвращает `AcquiringFacade::checkPayment()` (дата оплаты); это не DTO бандла, трансляция не нужна.
- `Contract::generateNumber()` → `string`; `activate()`, `changeTariff()` → `void`, `return $this` убран: возвращаемое значение нигде не использовалось, а мутирующие методы остальных сущностей проекта fluent-интерфейс не используют. `doctrine:schema:validate` — OK.
- **Трансляция исключений в клиентах.** Заведены `AcquiringClientException` и `FiscalDataOperatorClientException` в `Core\Client\*`; клиенты оборачивают любое исключение из вызова фасада (`Throwable`, не только исключения бандла — `FiscalDataOperatorFacade::sendReceipt()` сам делает `persist`/`flush`), исходное — в `previous`. Параметра `$code` у исключений нет: у `PDOException` код — строка SQLSTATE, приведение к `int` его портит; код исходного доступен через `getPrevious()`. Юзкейсы не менялись. Правило — [ADR-0003](../adr/0003-bundle-integrations-via-core-client.md).
- Тесты клиентов `tests/Unit/Core/Client/{Acquiring/AcquiringClientCest,FiscalDataOperator/FiscalDataOperatorClientCest}.php` — успешные вызовы и трансляция исключений бандла и прочих исключений фасада; фасады `final`, поэтому используются настоящие заглушки бандлов.
- Оформлен [ADR-0003](../adr/0003-bundle-integrations-via-core-client.md); раздел `CLAUDE.md` о бандлах синхронизирован с ним (трансляция исключений, у `NotifierBundle` нет фасада).
- `CLAUDE.md`, раздел «Тесты»: имена сьютов исправлены на регистрозависимые (`Unit`/`Functional`/`Acceptance`), добавлена команда миграции тестовой БД (без неё `Functional` падает на пустой `primeira_test`).
- Проверено: `php -l`, `lint:container`, `codecept run Unit` (17 тестов) и `Functional` (11 тестов) — зелёные. `Acceptance` не запускался.

## Не стали делать

- `NotifyPersonCommand` (`Core\Ampq\Event`) и `Billing\...\ContractCreated\Handler` импортируют `NotifierBundle\Enum\PersonTypeEnum` — отступление от ADR-0003 для асинхронной интеграции, зафиксировано в нём как известное. Другая граница (AMQP-сообщение, а не клиент), кандидат на отдельную задачу.

## Изменённые файлы

PR #5, `git diff --stat 7ac2c71^1 7ac2c71`:

```
 CLAUDE.md                                          |  21 ++--
 .../0003-bundle-integrations-via-core-client.md    |  48 +++++++++
 docs/adr/README.md                                 |   1 +
 docs/features/2026-09-08-logging.md                |  10 +-
 docs/features/2026-09-08-minor-findings.md         |  31 ++++--
 .../2026-09-27-contract-delegate-getters.md        |  88 ++++++++++++++++
 docs/features/2026-09-27-invoice-receipt-retry.md  |  45 ++++++++
 docs/features/2026-09-27-layer-violations.md       |  59 +++++++++++
 .../2026-09-27-musician-update-response.md         |  32 ++++++
 .../2026-09-27-person-billing-acl-level-3.md       | 102 ++++++++++++++++++
 docs/features/2026-09-27-use-case-cleanup.md       |  34 ++++++
 docs/features/README.md                            |   6 ++
 src/Billing/Domain/Entity/Contract.php             |   8 +-
 src/Core/Client/Acquiring/AcquiringClient.php      |  40 +++++---
 .../Client/Acquiring/AcquiringClientException.php  |  20 ++++
 .../FiscalDataOperatorClient.php                   |  24 +++--
 .../FiscalDataOperatorClientException.php          |  20 ++++
 .../Core/Client/Acquiring/AcquiringClientCest.php  | 114 +++++++++++++++++++++
 .../FiscalDataOperatorClientCest.php               |  95 +++++++++++++++++
 19 files changed, 759 insertions(+), 39 deletions(-)
```

## Ссылки

- Находка «🟢 Мелкие замечания» исходного ревью `/src` — перенесена сюда.
- PR #5 (`feature/strict-return-types`).
- [`2026-09-27-contract-delegate-getters.md`](2026-09-27-contract-delegate-getters.md) — вынесенный пункт 3.
- [ADR-0003](../adr/0003-bundle-integrations-via-core-client.md) — правило изоляции бандлов, оформленное по ходу этой задачи.
