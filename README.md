# Проект "Автоматизация бизнес-процессов школы барабанов Samba de Primeira"
Студент: Ефимов Сергей Евгеньевич

Организация: OTUS

Курс: Symfony Framework. Период обучения 27 февраля — 25 июля 2024

Репозиторий курса:
https://otus.ru/lessons/symfony/?utm_source=github&utm_medium=free&utm_campaign=otus

# Проверка работоспособности проекта
Liveness probe: http://localhost:7777/probe/liveness
Проверяет, что корректно завёлся PHP-FPM

Readyness probe: http://localhost:7777/probe/readyness
Проверяет, что корректно завёлся PHP-FPM и оттуда можем подключиться к PostgreSQL

# Форма работы с тарифом
Создание http://localhost:7777/api/v1/billing/tariff/create

Правка http://localhost:7777/api/v1/billing/tariff/update/{tariffId}
