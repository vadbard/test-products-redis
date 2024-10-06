## Запуск проекта:

    docker compose up -d
    docker compose exec app sh

### Создать заказ:

    php artisan order:create --created_at="{datetime}" --product="{name}:{unit_price}:{quantity}"

--product можно указывать несколько раз

### Получить список заказов:

    php artisan order:list

### Получить заказ по id:

    php artisan order:show {order_id}