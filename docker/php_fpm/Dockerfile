FROM php:8.1-fpm

RUN apt-get update \
    && apt-get install -y libxslt1-dev libsodium-dev \
    && docker-php-ext-install xsl \
