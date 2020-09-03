#
# HELPER IMAGE - generate certs
#
FROM registry.area21.lan:5000/certificationauthority:2021-07-12 as certificationauthority
ARG SSL_EXPIRE=365
ARG SSL_KEY=pluggit.area21.lan.key
ARG SSL_CSR=pluggit.area21.lan.csr
ARG SSL_CERT=pluggit.area21.lan.cert.pem
ARG SSL_SUBJECT=pluggit.area21.lan
# set alternative workdir and run generator
# (by default cert-dir is a volume)
WORKDIR /generator
RUN /usr/local/bin/generate-certs.sh


####################################################################

#
# Main Image
#
FROM php:7.4-apache


# COMPOSER
COPY --from=composer /usr/bin/composer /usr/bin/composer


# copy certs
COPY --from=certificationauthority /generator /certs


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
