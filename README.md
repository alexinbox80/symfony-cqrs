# Symfony Messenger — DDD + Docker (PHP-FPM + Nginx + RabbitMQ + Postgres)

CQRS:
- Команды (Commands): Представляют собой операции, которые изменяют состояние системы, например, создание новой записи, обновление профиля или удаление комментария. Команды обычно не возвращают данные, а лишь инициируют изменение.
- Запросы (Queries): Представляют собой операции, которые читают данные из системы, не изменяя их состояние. Их задача — максимально быстро вернуть нужную информацию.
- Разделение моделей: Каждая операция (команда или запрос) обрабатывается своей отдельной моделью данных, которую можно проектировать и оптимизировать независимо от другой.

слои **Application / Domain / Infrastructure / UI**, три шины (command/query/event), async‑события, failure transport, Docker‑окружение.

> ⚠️ Это добавочные файлы/каркас. Предполагается, что у вас уже есть Symfony‑проект (public/, config/, etc). Поместите содержимое поверх вашего репо.

## 1) Установка пакетов
В контейнере `php` или локально (если без Docker):
```bash
composer require symfony/messenger symfony/serializer symfony/validator symfony/uid
composer require symfony/orm-pack doctrine/doctrine-migrations-bundle
# Транспорт на выбор (для async):
composer require symfony/amqp-messenger        # RabbitMQ
# или
composer require symfony/redis-messenger       # Redis
# Для демо без внешнего брокера можно doctrine-messenger, но лучше RabbitMQ/Redis
```

## 2) Конфиги (из этого каркаса)
- `config/packages/messenger.yaml` — 3 шины + routing + failure transport
- `config/packages/doctrine.yaml` — базовая конфигурация Doctrine
- `config/services.yaml` — автоконфигурация модуля `App\Order\...`
- `.env.example` — DSN для брокера и БД

## 3) Docker
Сервисы:
- **php**: PHP 8.3 FPM + Composer
- **nginx**: фронт, слушает `http://localhost:8080`
- **postgresql**: Postgresql 16.0 (порт 5432 наружу)
- **rabbitmq**: RabbitMQ с панелью (`http://localhost:15672`, логин/пароль: guest/guest)

Запуск:
```bash
docker compose up -d --build
docker compose exec php composer install
docker compose exec php bin/console doctrine:database:create --if-not-exists
docker compose exec php bin/console doctrine:migrations:migrate -n
```

Переменные окружения (в `.env.local`):
```
DATABASE_URL="postgresql://cqrs:cqrs-password@symfony-cqrs_postgres:5432/cqrs?serverVersion=16&charset=utf8"
MESSENGER_TRANSPORT_DSN=amqp://guest:guest@symfony-cqrs_rabbitmq:5672/%2f/messages
```

Запуск воркера:
```bash
docker compose exec symfony-cqrs_php bin/console messenger:consume async -vv
```

Проверка HTTP (если в проекте есть контроллеры/роуты):
- POST `http://localhost:8080/api/v1/orders` — создаёт заказ (dispatch команды)
- GET  `http://localhost:8080/api/v1/orders/{id}` — имитирует чтение

Админка брокера: `http://localhost:15672` (guest/guest).

## 4) План лайв‑демо
1) POST /orders → `CreateOrderCommand` → `CreateOrderHandler` → публикует `OrderCreated`
2) Работает воркер → слушатель `OrderCreatedListener` логирует событие (или шлёт email).
3) Ломаем listener (искусственно) → показываем `failed` и `retry`.
4) Добавляем 2‑го listener к тому же событию → одно событие → много реакций.
