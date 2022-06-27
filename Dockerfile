FROM php:8.0-fpm-alpine

RUN apk add --no-cache nginx wget sqlite

RUN mkdir /db
RUN touch /db/database.sqlite

RUN mkdir -p /run/nginx

COPY docker/nginx.conf /etc/nginx/nginx.conf

RUN mkdir -p /app
COPY . /app

RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"
RUN cd /app && \
    /usr/local/bin/composer install --no-dev

RUN chown -R www-data: /app

WORKDIR /app

RUN php artisan migrate --force
RUN php artisan db:seed --force

CMD sh /app/docker/startup.sh
