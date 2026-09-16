# Мелкие замечания по типизации и связности Contract

**Статус:** 🟡 В работе (не начато) \
**Начато:** 2026-09-08 \
**Обновлено:** 2026-09-17

## Цель

Два мелких замечания из ревью `/src`, не влияющих на поведение, но снижающих строгость типизации и повышающих связность между `Billing`-сущностями:

- `AcquiringClient::checkPayment()` — без возвращаемого типа, в отличие от остального клиента.
- `Contract` entity — `generateNumber()`, `activate()`, `changeTariff()` без return type; много delegate-геттеров (`getTariffId/Value/TypeName/...`, `getMusicianId/Email/Phone`) — Law of Demeter наружу из `Contract`.

Третье замечание из того же списка («`Kernel.php` без `declare(strict_types=1)`») уже закрыто — см. [`2026-09-08-person-billing-critical-bugfixes.md`](2026-09-08-person-billing-critical-bugfixes.md), сюда не переносится.

## План

1. `src/Core/Client/Acquiring/AcquiringClient.php` — дать `checkPayment()` явный возвращаемый тип (уточнить, что реально возвращает `AcquiringFacade`, не оставлять неявный `mixed`).
2. `src/Billing/Domain/Entity/Contract.php` — добавить return type `generateNumber()`, `activate()`, `changeTariff()`.
3. `Contract` — оценить, стоит ли выносить delegate-геттеры (`getTariffId/Value/TypeName/DegreeName/StatusName`, `getMusicianId/Email/Phone`) в отдельный value object/DTO для нотификаций, или это осознанный компромисс ради удобства вызывающего кода. Это вопрос дизайна, не автоматический рефакторинг — решить перед тем, как трогать.

## Сделано

Пока ничего — задача не начата.

## Не стали делать

Пока нет решений об отклонении — задача в очереди, не начата.

## Изменённые файлы

Пока нет диффа — работа не начата.

## Ссылки

- Находка «🟢 Мелкие замечания» исходного ревью `/src` — перенесена сюда.
