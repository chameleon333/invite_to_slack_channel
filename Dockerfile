FROM php:7.3-fpm


COPY ./docker/php/php.ini /usr/local/etc/php/

RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

RUN curl -sL https://deb.nodesource.com/setup_13.x | bash -
