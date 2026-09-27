# Мелкая чистка юзкейсов, репозиториев и DTO

**Статус:** 🟡 В работе (не начато) \
**Начато:** 2026-09-27 \
**Обновлено:** 2026-09-27

## Цель

Мелкие замечания архитектурного ревью 2026-09-27: на поведение не влияют, но нарушают единообразие проекта и SOLID. Каждый пункт — независимая правка, можно делать по одному.

## План

1. **`UpdateMusicianUseCase` — два действия в одном классе** (SRP). Разделить на `PatchMusicianUseCase` и `UpdateMusicianUseCase`, по одному на контроллер (`PatchMusicianController`, `PutMusicianController`).
2. **Разные имена методов юзкейсов** — `__invoke`, `create`, `get`, `getById`, `getActive`, `putTariff`, `delete`, `patchMusician`, `updateMusician`. `CLAUDE.md` описывает `__invoke()` как обычный вариант. Привести все юзкейсы к `__invoke()` и обновить вызовы в контроллерах, командах и тестах.
3. **`TariffRepositoryInterface::getByStatus(int $status)`** — доменный интерфейс принимает число, как статус хранится в БД, и юзкейс передаёт `TariffStatusEnum::ACTIVE->value`. Принимать `TariffStatusEnum`; Doctrine принимает enum как параметр (так уже сделано в `ContractRepository::getActiveByMusicianId()`).
4. **`DeleteMusicianUseCase`, `UpdateMusicianUseCase` пропускают наружу `UnitOfWorkException`** (инфраструктура `Core`), остальные юзкейсы оборачивают её в доменное исключение. Завести `Person/Domain/Exception/MusicianManageException` по образцу `EmployeeManageException` и оборачивать; обновить `@throws` в контроллерах. `MusicianNotFoundException` пропускать как есть (как в `DeleteEmployeeUseCase`).
5. **Deprecation в `Person/.../Common/Output/MusicianDto`** — необязательные `$instagram`, `$facebook`, `$VK` (`= null`) объявлены перед обязательным `$contracts`. Убрать значения по умолчанию (тип `?string` оставить) — единственный вызов `MusicianDtoFactory` передаёт все аргументы.
6. Проверить: `lint:container`, `codecept run Unit`, `codecept run Functional`.

## Сделано

-

## Не стали делать

-

## Изменённые файлы

Пока нет диффа — работа не начата.

## Ссылки

- [`2026-09-27-musician-update-response.md`](2026-09-27-musician-update-response.md) — баг в тех же контроллерах; пункт 1 удобно делать после него.
