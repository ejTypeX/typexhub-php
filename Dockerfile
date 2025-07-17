FROM php:8.2-apache

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli mbstring xml \
    && apt-get clean

RUN a2enmod rewrite \
    && sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

COPY ./src/ /var/www/html/