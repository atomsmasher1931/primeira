# Критичные баги из ревью /src: 500-ка, неверный подсчёт, кириллица в команде

**Статус:** ✅ Готово (смёржено, PR #2) \
**Начато:** 2026-09-08 \
**Обновлено:** 2026-09-17

> Запись восстановлена задним числом по просьбе пользователя (2026-09-17) — на момент этой работы `docs/features/` ещё не существовал. Реконструирована из истории разговора и `git log`, не велась вживую.

## Цель

Закрыть три конкретных бага, найденных при ревью `/src`. Плюс закрыть самое мелкое замечание того же ревью (`declare(strict_types=1)` в `Kernel.php`), раз уж правки рядом.

### Баг №1 — `Musician.contracts` в ответе `GET /api/person/v1/musician/{id}` и `/by-phone` — 500, если у музыканта есть договор

Три независимых дефекта в одной цепочке:
- `src/Person/Presentation/Http/Rest/Musician/Common/Output/ContractDto.php:11-18` — параметры конструктора не промотированы (`string $id` вместо `public string $id`, как в аналоге `Billing`). У класса нет ни одного свойства — сериализация даёт `{}`.
- `src/Person/Presentation/Http/Rest/Musician/Common/Factory/ContractDtoFactory.php:36` — `$contractsDto = $this->createFromContract($contract);` вместо `$contractsDto[] = ...`. Метод `createFromContracts()` перезаписывает переменную на каждой итерации и возвращает один `ContractDto`, не массив.
- Из-за этого `MusicianDtoFactory::createFromMusician()` передаёт в `MusicianDto` объект `ContractDto` туда, где типизирован `public array $contracts` — как только у музыканта есть хотя бы один договор, конструктор бросает `TypeError`, и эндпоинт отдаёт 500.

Тестами не было покрыто (существовал только `tests/Functional/Person/Repository/MusicianRepositoryCest.php` — репозиторий, не HTTP-слой).

### Баг №2 — `SendInvoiceToAcquireUseCase::__invoke` — неверный подсчёт неотправленных счетов

`src/Billing/Application/UseCase/SendInvoiceToAcquireUseCase.php:33,37`:
```php
$count = +count($issuedInvoices);   // унарный + — не суммирует, просто присваивает
...
$count = +count($sentInvoices);     // перезаписывает предыдущее значение
```
Должно быть `$count += count(...)`. `didnt_sent` в ответе команды `billing:invoice:send_to_aсquire` считался только от второй пачки счетов, первая терялась.

### Баг №3 — имя консольной команды содержит кириллицу

`src/Billing/Presentation/Console/Command/SendInvoiceToAcquireCommand.php:22` — `'billing:invoice:send_to_aсquire'`, где «с» кириллическая, а не латинская `c`. Визуально неотличимо, но `php bin/console billing:invoice:send_to_acquire` (интуитивный ввод) команду не находил. Ошибка была унаследована и в `CLAUDE.md`.

## План

1. Промотировать поля `Person\...\Musician\Common\Output\ContractDto` (баг №1, причина 500-ки).
2. Исправить append-баг в `Person\...\ContractDtoFactory::createFromContracts` (баг №1, вторая причина).
3. Исправить `$count += count(...)` в `SendInvoiceToAcquireUseCase` (баг №2).
4. Убрать кириллицу из имени команды `billing:invoice:send_to_aсquire` (баг №3).
5. Добавить `declare(strict_types=1)` в `Kernel.php`.

## Сделано

- `src/Person/Presentation/Http/Rest/Musician/Common/Output/ContractDto.php` — параметры конструктора промотированы (`public string $id` и т.д.); без этого класс сериализовался в `{}`.
- `src/Person/Presentation/Http/Rest/Musician/Common/Factory/ContractDtoFactory.php` — `$contractsDto[] = ...` вместо перезаписи переменной на каждой итерации.
- `src/Billing/Application/UseCase/SendInvoiceToAcquireUseCase.php` — `$count += count(...)` вместо `$count = +count(...)`.
- `src/Billing/Presentation/Console/Command/SendInvoiceToAcquireCommand.php` — кириллическая «с» заменена на латинскую `c`.
- `src/Kernel.php` — добавлен `declare(strict_types=1)`.
- Упоминание команды в `CLAUDE.md` тоже было поправлено на латиницу — но этот конкретный коммит уехал в параллельную ветку `feature/claude-start` (`f6557c8`), не в эту; см. [`2026-09-08-claude-code-bootstrap.md`](2026-09-08-claude-code-bootstrap.md).

## Не стали делать

- Дублирование DTO между `Billing`/`Person` (баг №4 из ревью) — не трогали здесь, это отдельная задача, см. [ADR-0002](../adr/0002-person-billing-acl.md) и [`2026-09-09-person-billing-acl.md`](2026-09-09-person-billing-acl.md). Логирование (баг №5) и `flush()` в цикле (баг №6) на момент этой задачи оставались открытыми.
- **Честный пробел, вскрывшийся при восстановлении этой записи**: Unit-тест `tests/Unit/.../MusicianDtoFactoryCest.php`, который должен был закрыть регрессию по багу №1, был написан в ходе этой же работы, но **не попал в этот коммит** — он остался незакоммиченным в рабочем дереве и уехал только в более поздний коммит `fad29c7` на ветке `feature/acl-refactoring-person-billing`. То есть в момент мёржа этого PR баг был исправлен, но без регрессионного теста в истории — тест появился позже, вместе с несвязанной по смыслу задачей.

## Изменённые файлы

```
src/Billing/Application/UseCase/SendInvoiceToAcquireUseCase.php               |  4 ++--
src/Billing/Presentation/Console/Command/SendInvoiceToAcquireCommand.php      |  2 +-
src/Kernel.php                                                                |  2 ++
src/Person/Presentation/Http/Rest/Musician/Common/Factory/ContractDtoFactory.php |  2 +-
src/Person/Presentation/Http/Rest/Musician/Common/Output/ContractDto.php      | 10 +++++-----
5 files changed, 11 insertions(+), 9 deletions(-)
```
(`git diff --stat` между началом ветки `fix/person-billing-kernel` и её единственным коммитом `17a652a`, смёржено в `master` как `043b5c8`)

## Ссылки

- [`2026-09-08-claude-code-bootstrap.md`](2026-09-08-claude-code-bootstrap.md) — куда уехала правка `CLAUDE.md`.
- PR #2 (`fix/person-billing-kernel` → `master`)
