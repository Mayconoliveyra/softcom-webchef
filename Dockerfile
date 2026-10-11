FROM php:7.3-cli-buster

RUN sed -i \
        -e 's|deb.debian.org/debian|archive.debian.org/debian|g' \
        -e 's|security.debian.org/debian-security|archive.debian.org/debian-security|g' \
        -e '/buster-updates/d' /etc/apt/sources.list \
    && apt-get update -o Acquire::Check-Valid-Until=false \
    && apt-get install -y --no-install-recommends git unzip \
    && docker-php-ext-install pdo_mysql opcache \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

RUN { \
        echo 'opcache.enable=1'; \
        echo 'opcache.enable_cli=1'; \
        echo 'opcache.validate_timestamps=1'; \
        echo 'opcache.revalidate_freq=0'; \
    } > /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
