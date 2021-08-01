FROM php:7.4-apache

ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_DOCUMENT_ROOT /var/www/public
ENV APP_ENV prod

VOLUME /var/log

RUN { \
    echo 'ServerTokens Prod'; \
    echo 'ServerSignature Off'; \
} >> /etc/apache2/apache2.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN echo "date.timezone = \"Europe/Berlin\"" > /usr/local/etc/php/conf.d/timezone.ini
RUN echo "memory_limit = 256M" > /usr/local/etc/php/conf.d/memory_limit.ini

RUN apt update && apt install -y mariadb-client unzip libzip-dev libc-client-dev libkrb5-dev zlib1g-dev libpng-dev && apt clean \
 && docker-php-source extract \
 && docker-php-ext-install -j$(nproc) \
    pdo_mysql zip gd \
 && a2enmod rewrite \
 && docker-php-source delete

RUN apt install -y gnupg \
 && curl -sL https://deb.nodesource.com/setup_14.x | bash \
 && apt-get install -y nodejs \
 && npm install --global yarn

RUN curl -o /usr/local/bin/wait-for-it https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh && \
  chmod +x /usr/local/bin/wait-for-it

COPY ./ /var/www

RUN cd /var/www ; curl -s https://getcomposer.org/installer | php ; mv composer.phar /usr/local/bin/composer ; composer clear-cache ; APP_ENV=prod composer install --no-progress --no-dev --optimize-autoloader ; yarn ; yarn run encore production ; rm -Rf node_modules/

WORKDIR /var/www/public

COPY ./docker/entrypoint.sh /usr/local/bin/entrypoint.sh
ENTRYPOINT ["entrypoint.sh"]
