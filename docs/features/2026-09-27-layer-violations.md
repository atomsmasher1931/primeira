# Нарушения слоёв и направления зависимостей

**Статус:** 🟡 В работе (не начато) \
**Начато:** 2026-09-27 \
**Обновлено:** 2026-09-27

## Цель

Архитектурное ревью `src/` на ветке `feature/strict-return-types` (2026-09-27) нашло места, где зависимости идут против слоёв: shared kernel `Core` зависит от контекста `Person`, домен `Person` — от Symfony, консольные команды `Presentation` работают с Doctrine напрямую в обход `Application`. Здесь собраны находки и план исправления. Делаем позже, отдельной веткой.

Связи `Person` ↔ `Billing` между сущностями сюда не входят — это [ACL уровня 3](2026-09-27-person-billing-acl-level-3.md).

## Находки

**1. `Core` зависит от `Person`** — нарушение правила зависимостей Clean Architecture (ядро не должно знать о бизнес-контекстах) и DIP:
- `Core/PasswordHasher/PasswordHasherInterface.php`, `PasswordHasher.php` — методы принимают `Person\Domain\Entity\Employee`. Интерфейсом пользуется `Person\Application` (`CreateEmployeeUseCase`, `GetEmployeeByLoginAndPasswordUseCase`), то есть по DIP он должен принадлежать `Person`.
- `Core/Security/TokenGenerator/JwtTokenGenerator.php` — принимает `Employee`, из него читает только `getUserIdentifier()` и `getRoles()`.
- `Core/Security/Voter/UserSelfDeleteVoter.php` — вызывает `Person\Application\UseCase\GetEmployeeByIdUseCase`; неиспользуемый импорт `GetEmployeeByLoginAndPasswordUseCase`.

**2. `Person\Domain` зависит от Symfony** — `Employee` реализует `Symfony\...\UserInterface` и `PasswordAuthenticatedUserInterface`. Используется в `PasswordHasher` (передаёт `Employee` в `UserPasswordHasherInterface`) и в `UserSelfDeleteVoter` / `JwtTokenGenerator` (`getUserIdentifier()`).

**3. Консольные команды обходят `Application`:**
- `Billing/Presentation/Console/Command/AddTariffsCommand.php`, `Person/Presentation/Console/Command/AddViewerEmployeeCommand.php` — удаляют записи через `EntityManagerInterface`, сами создают сущности и сохраняют их через `UnitOfWork`.
- `AddViewerEmployeeCommand` повторяет логику `CreateEmployeeUseCase` (DRY) и зависит от конкретного `PasswordHasher`, а не от `PasswordHasherInterface` (DIP).

**4. Юзкейсы зависят от конкретных `final`-клиентов** — `CheckInvoicesPaidUseCase`, `SendInvoiceToAcquireUseCase` получают `AcquiringClient` / `FiscalDataOperatorClient`, а не интерфейсы (DIP). Из-за `final` их нельзя замокать, unit-тестов на эти юзкейсы нет. Конкретный `final readonly` клиент закреплён в [ADR-0003](../adr/0003-bundle-integrations-via-core-client.md), поэтому это вопрос пересмотра решения, а не исправление реализации.

## План

1. **`PasswordHasherInterface` → `Person`** (DIP: интерфейс принадлежит клиенту). Перенести интерфейс в `Person/Domain/`, реализацию — в `Person/Infrastructure/`. Обновить `CreateEmployeeUseCase`, `GetEmployeeByLoginAndPasswordUseCase`, `AddViewerEmployeeCommand`, `config/services.yaml` при необходимости.
2. **`JwtTokenGenerator` без `Employee`** — принимать логин и роли (скаляры) вместо сущности; `LoginController` передаёт их из `Employee`.
3. **`UserSelfDeleteVoter` → `Person`** — перенести в `Person/Presentation/` (правило удаления сотрудника — правило контекста `Person`), убрать неиспользуемый импорт. Обновить импорт в `DeleteEmployeeController`.
4. **`Employee` без Symfony** — убрать `UserInterface`/`PasswordAuthenticatedUserInterface` из сущности; в `Person/Infrastructure/` завести Adapter (GoF), который оборачивает `Employee` и реализует `PasswordAuthenticatedUserInterface` для `UserPasswordHasherInterface`. Делать после шагов 1–3, когда `Employee` останется нужен только хешеру.
5. **Консольные команды** — зависит от ответа на открытый вопрос ниже: либо перевести на юзкейсы (создание — `CreateTariffUseCase` / `CreateEmployeeUseCase`, удаление — через репозиторий), либо заменить на Doctrine Fixtures. В любом случае убрать `EntityManagerInterface` из `Presentation` и заменить `PasswordHasher` на интерфейс.
6. **Интерфейсы клиентов** — только после решения по ADR (см. ниже).
7. Проверка: `lint:container`, `codecept run Unit`, `codecept run Functional`, логин и удаление сотрудника вручную или через `Acceptance`.

## Открытые вопросы

- **Назначение `AddTariffsCommand` и `AddViewerEmployeeCommand`.** Обе скрытые (`setHidden`), с зашитыми ID и данными — похожи на заполнение тестовыми данными, но это не подтверждено. От ответа зависит шаг 5.
- **Интерфейсы для клиентов `Core\Client\*`** (находка 4) — пересматривает ADR-0003; решить, нужен ли новый ADR.
- **ADR для правила «`Core` не зависит от бизнес-контекстов»** — это правило направления зависимостей между модулями (критерий из [`docs/adr/README.md`](../adr/README.md)). Решить до начала шагов 1–3.

## Сделано

-

## Не стали делать

-

## Изменённые файлы

Пока нет диффа — работа не начата.

## Ссылки

- [ADR-0003](../adr/0003-bundle-integrations-via-core-client.md) — клиенты интеграций (находка 4).
- [`2026-09-27-person-billing-acl-level-3.md`](2026-09-27-person-billing-acl-level-3.md) — связи сущностей `Person` ↔ `Billing`, в эту задачу не входят.
