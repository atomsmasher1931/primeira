# Изоляция доменов Person ↔ Billing через ACL

**Статус:** ✅ Готово (смёржено, PR #3) \
**Начато:** 2026-09-08 \
**Обновлено:** 2026-09-17

> Запись восстановлена задним числом по просьбе пользователя (2026-09-17) — на момент этой работы `docs/features/` ещё не существовал. Реконструирована из истории разговора и `git log`, не велась вживую.

## Цель

Устранить корневую причину бага с `Musician.contracts` (дублирование `TariffDto`/`ContractDto`/`MusicianDto` между `Billing` и `Person`) не точечным фиксом, а осознанным решением об уровне изоляции доменов — само решение, рассмотренные и отклонённые варианты, все итерации дизайна — в [ADR-0002](../adr/0002-person-billing-acl.md). Здесь фиксируется только ход работы: что и когда сделано.

## План

1. Реализовать Уровень 1 ACL — собственные enum'ы `Person`/`Billing`, без импорта чужого домена.
2. Реализовать Уровень 2 ACL — инверсия зависимостей на границе `Application`/`Domain`.
3. Добавить regression-тест на исходный баг.
4. Оформить решение как ADR (не пересказывать в этом файле).

## Сделано

- Реализованы Уровень 1 и Уровень 2 ACL — все промежуточные итерации дизайна (enum-метод → `Infrastructure/Acl` → `Presentation/Gateway` → финальный инлайн `Enum::from()`; `Adapter` → `Gateway`) и причины каждого шага — в ADR-0002.
- Добавлен `tests/Unit/.../MusicianDtoFactoryCest.php` — regression-тест на баг с `Musician.contracts`, закрывающий пробел, отмеченный в [`2026-09-08-person-billing-critical-bugfixes.md`](2026-09-08-person-billing-critical-bugfixes.md) (тест был написан раньше, но закоммичен только здесь).
- Создан [ADR-0002](../adr/0002-person-billing-acl.md).
- Проверено: `php bin/console lint:container` → OK, `debug:autowiring` подтвердил однозначное разрешение новых интерфейсов, `vendor/bin/codecept run Unit` — зелёный.

**Особенность этой ветки**: `feature/acl-refactoring-person-billing` была создана от `fix/person-billing-kernel` (коммит `17a652a`) — то есть от точки **до** того, как в параллельной ветке `feature/claude-start` появились `docs/adr/README.md`, `template.md`, ADR-0001 (см. [`2026-09-08-claude-code-bootstrap.md`](2026-09-08-claude-code-bootstrap.md)). На этой линии истории `docs/adr/` физически не существовал до самого мёржа — ADR-0002 какое-то время создавался «в пустоте», без соседних файлов процесса. При мёрже PR #3 в `master` конфликта не возникло: `docs/adr/{README,template,0001-*}.md` пришли туда из уже смёрженной `feature/claude-start` веткой раньше по времени.

## Не стали делать

- Уровень 3 (разрыв прямой Doctrine-связи `Contract`↔`Musician`) — рассмотрен, сознательно отложен. Обоснование — в ADR-0002.
- Не восстанавливали `docs/adr/README.md`/`template.md`/`0001-*.md` вручную в этой ветке несмотря на их физическое отсутствие (см. выше) — работали только с ADR-0002, зная, что при мёрже история сойдётся.

## Изменённые файлы

```
docs/adr/0002-person-billing-acl.md                                          | 74 ++++++++
src/Billing/Application/UseCase/CreateContractUseCase.php                    |  6 +-
src/Billing/Domain/Enum/MusicianDegreeEnum.php                               | 18 ++
src/Billing/Domain/Enum/MusicianStatusEnum.php                               | 19 ++
src/Billing/Domain/Enum/TariffTypeEnum.php                                   |  2 +-
src/Billing/Domain/Repository/MusicianLookupInterface.php                    | 28 +++
src/Billing/Infrastructure/Gateway/PersonMusicianContractsGateway.php        | 24 +++
src/Billing/Presentation/Http/Rest/Common/Factory/MusicianDtoFactory.php     | 12 +-
src/Billing/Presentation/Http/Rest/Common/Output/MusicianDto.php             |  8 +-
src/Person/Application/UseCase/GetMusicianByIdUseCase.php                    |  6 +-
src/Person/Application/UseCase/GetMusicianByPhoneUseCase.php                 |  6 +-
src/Person/Domain/Enum/MusicianDegreeTariffEnum.php                          | 33 +++
src/Person/Domain/Enum/TariffStatusEnum.php                                  | 33 +++
src/Person/Domain/Enum/TariffTypeEnum.php                                    | 48 +++++
src/Person/Domain/Repository/MusicianContractsProviderInterface.php          | 28 +++
src/Person/Infrastructure/Gateway/BillingMusicianLookupGateway.php           | 25 +++
src/Person/Presentation/Http/Rest/Musician/Common/Factory/TariffDtoFactory.php | 16 +-
src/Person/Presentation/Http/Rest/Musician/Common/Output/TariffDto.php       |  6 +-
tests/Unit/.../Factory/MusicianDtoFactoryCest.php                            | 99 ++++++++++
19 files changed, 467 insertions(+), 24 deletions(-)
```
(`git diff --stat` между `17a652a`, реальным родителем `fad29c7`, и самим `fad29c7`; смёржено в `master` как `185ac03`)

## Ссылки

- [ADR-0002](../adr/0002-person-billing-acl.md) — решение, рассмотренные и отклонённые варианты.
- [`2026-09-08-person-billing-critical-bugfixes.md`](2026-09-08-person-billing-critical-bugfixes.md) — баг, из-за которого начата эта работа.
- PR #3 (`feature/acl-refactoring-person-billing` → `master`)
