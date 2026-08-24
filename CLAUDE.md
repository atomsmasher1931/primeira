# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## О проекте

Учебный проект (курс OTUS) — автоматизация бизнес-процессов школы барабанов "Samba de Primeira" на Symfony 6.4 / PHP 8.2. REST API + консольные команды + асинхронная обработка через RabbitMQ.

## Инфраструктура и команды

Стек поднимается через docker-compose: `php-fpm` (контейнер `primeira-php`), `nginx` (`primeira-nginx`, порт `77`), `postgres` (порт `25432`, БД `primeira`), `postgres_test` (порт `35432`, БД `primeira_test`), `rabbitmq` (AMQP `25672`, management UI `35672`).

```bash
docker-compose up -d                       # поднять окружение
composer install                            # зависимости (внутри контейнера php-fpm)
php bin/console doctrine:migrations:migrate # применить миграции
php bin/console messenger:consume async     # запустить консьюмер очереди
```

Пробы работоспособности (модуль `System`): `GET /probe/liveness`, `GET /probe/readyness` (последняя проверяет соединение с PostgreSQL).

Коллекция Postman лежит в `docs/`.

### Тесты

Тесты написаны на **Codeception** (стиль Cest), а не «сырой» PHPUnit — `phpunit.xml.dist` присутствует, но фактический раннер тестов — Codeception (см. `codeception.yml`, `tests/*.suite.yml`).

```bash
vendor/bin/codecept run                         # все сьюты
vendor/bin/codecept run unit                    # Unit
vendor/bin/codecept run functional              # Functional (требует БД test)
vendor/bin/codecept run acceptance              # Acceptance (требует поднятый nginx-контейнер)
vendor/bin/codecept run unit tests/Unit/Person/Entity/EmployeeCest.php  # один файл
vendor/bin/codecept run unit tests/Unit/Person/Entity/EmployeeCest.php:testSomething  # один тест
```

- `Unit` — изолированные тесты (актор `UnitTester`, только модуль `Asserts`).
- `Functional` — поднимают ядро Symfony через модуль `Symfony`, работают с БД через модуль `Doctrine` (`cleanup: true`) и фабрики данных `DataFactory`.
- `Acceptance` — HTTP-запросы к `http://primeira-nginx:80/` (модуль `REST` поверх `PhpBrowser`), т.е. должны выполняться против поднятого docker-compose стека.

Консольные команды приложения именуются `<контекст>:<сущность>:<действие>`, например `billing:invoice:issue`, `billing:invoice:send_to_aсquire`, `billing:invoice:check_paid_status`, `billing:tariff:add`.

## Архитектура

### Границы модулей (`src/`)

Приложение разбито на бизнес-контексты, каждый в своей папке под `App\` со слоистой архитектурой (Domain → Application → Infrastructure → Presentation):

- **`Person`** — сотрудники/музыканты, авторизация.
- **`Billing`** — тарифы, договоры (`Contract`), счета (`Invoice`).
- **`System`** — сквозные вещи уровня приложения (пробы работоспособности).
- **`Core`** — общее ядро (shared kernel), не бизнес-контекст.

Внутри каждого бизнес-контекста:
- `Domain/` — сущности Doctrine (`Entity/`), enum'ы, доменные исключения, **интерфейсы** репозиториев (`Repository/*Interface.php`).
- `Application/` — юзкейсы (`UseCase/*UseCase.php`, обычно с `__invoke()`) и DTO приложения (`Dto/`).
- `Infrastructure/` — реализации репозиториев (`Infrastructure/Repository/`), доступ к Doctrine.
- `Presentation/` — точки входа: `Http/Rest/...`, `Console/Command/`, `Amqp/Consumer/`, `Form/`, `Template/`.

Юзкейсы не знают о HTTP/консоли/AMQP — presentation-слой их только вызывает и мапит DTO.

### Три отдельных "бандла" — имитация внешних интеграций

`AcquiringBundle`, `FiscalDataOperatorBundle`, `NotifierBundle` (в `src/*Bundle/`) — самостоятельные Symfony-бандлы **вне** namespace `App\`, со своим PSR-4-неймспейсом (`composer.json` → `autoload.psr-4`), своим `config/services.yaml` и классом бандла (`extends AbstractBundle`), зарегистрированные в `config/bundles.php`. Каждый имитирует внешнюю интеграцию, которая по-настоящему пока не реализована (см. `README.md` внутри каждого бандла):
- `AcquiringBundle` — отправка счетов эквайеру и проверка оплаты (заглушка).
- `FiscalDataOperatorBundle` — отправка отчётов в ОФД и получение чеков (заглушка).
- `NotifierBundle` — отправка email-уведомлений (заглушка).

Каждый бандл предоставляет `Facade` (например `AcquiringBundle\Facade\AcquiringFacade`) как единственную публичную точку входа. Приложение обращается к бандлу не напрямую, а через тонкий клиент-обёртку в `src/Core/Client/<Integration>/` (например `App\Core\Client\Acquiring\AcquiringClient`), который транслирует DTO ядра (`Core\Client\...\*Dto`) в DTO бандла и обратно. Юзкейсы в `Billing`/`Person` зависят только от `Core\Client\*`, никогда напрямую от классов бандлов.

`config/services.yaml` явно исключает `Domain/Entity` каждого контекста (это Doctrine-сущности, не сервисы), `Kernel.php` и папки трёх бандлов (они регистрируют себя сами через собственные `services.yaml`).

### `Core` (shared kernel)

- `Ampq/` — обёртка над Symfony Messenger для работы с RabbitMQ: `MessageBus` (тонкая обёртка `dispatch()`), `Consumer/AbstractHandler`, события/команды (`Event/*`). Консьюмеры конкретных сообщений лежат в `Presentation/Amqp/Consumer/<Событие>/{Handler.php,Input/Message.php}` соответствующего бизнес-контекста.
- `Client/` — обёртки над бандлами (см. выше).
- `Doctrine/` — подписчики (`Subscriber/`) и трейты (`Trait/`) для сущностей.
- `Http/Rest/Response/` — единый формат ошибок API: `ErrorResponse`, `ValidationErrorResponse`, `ErrorResponseInterface`/`ErrorResponseTrait`.
- `Security/` — JWT-аутентификация: `Authenticator/JWTTokenAuthenticator`, `TokenGenerator/JwtTokenGenerator`, `User/AuthUser`, `Voter/`. Поверх `lexik/jwt-authentication-bundle`.
- `UnitOfWork/` — интерфейс (`UnitOfWorkInterface`) поверх Doctrine `EntityManager` (`persist`/`flush`), которым пользуются юзкейсы вместо прямой зависимости от Doctrine.
- `UuidGenerator/` — генерация ID сущностей (`EntityIdGeneratorInterface`).
- `Environment/`, `EventListener/`, `Exception/`, `PasswordHasher/`, `Utils/` — прочие сквозные утилиты.

### HTTP-слой

Один контроллер = одно действие: `Presentation/Http/Rest/<Сущность>/<Действие>/V1/<Действие>Controller.php` с `__invoke()`, версионированием через `V1` в пути и колокацией `Input/`-DTO рядом с контроллером. Общие DTO/фабрики для нескольких действий одного контекста — в `Presentation/Http/Rest/Common/{Output,Factory}/`. Маршруты подключаются через `config/routes.yaml` — по одному разделу на контекст, `type: attribute` (роуты объявлены атрибутом `#[Route]` прямо на методе `__invoke`).

### Стиль кода

Табы для отступов (не пробелы), `declare(strict_types=1)` в каждом файле, `final readonly class` для сервисов без наследования (юзкейсы, клиенты, фасады).
