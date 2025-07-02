FROM php:8.2-apache

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli mbstring xml \
    && apt-get clean

RUN sed -i 's|<Directory /var/www/>|<Directory /var/www/html/>\n    AllowOverride All|g' /etc/apache2/apache2.conf
RUN a2enmod rewrite

COPY ./src/ /var/www/html/