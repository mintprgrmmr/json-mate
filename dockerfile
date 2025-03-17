# Используем официальный PHP-образ с поддержкой FPM
FROM php:8.2-fpm

# Устанавливаем нужные пакеты (расширения PHP)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Указываем рабочую директорию внутри контейнера
WORKDIR /var/www

# Копируем файлы проекта
COPY . .

# Устанавливаем зависимости Laravel
RUN composer install --no-dev --optimize-autoloader

# Устанавливаем права доступа к storage и bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# Открываем порт для работы с контейнером
EXPOSE 9000

# Запускаем PHP-FPM
CMD ["php-fpm"]
