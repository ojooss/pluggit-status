FROM php:7.4-apache


# COMPOSER
COPY --from=composer /usr/bin/composer /usr/bin/composer


# add php extensions
RUN apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y git libsodium-dev unzip && \
    docker-php-ext-install sodium sockets pdo_mysql mysqli


# configure apache
COPY apache.conf /etc/apache2/sites-available/000-default.conf


# add application
ADD . /var/www/html
RUN composer install


CMD ["apache2-foreground"]
