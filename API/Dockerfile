FROM php:8.1-cli

WORKDIR /API

COPY composer.json .

COPY composer.lock .

COPY index.php .

COPY getEvents.php .

COPY Data.php .

RUN apt-get update
RUN apt install -y git
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --optimize-autoloader --no-dev

ENTRYPOINT ["php", "-S", "0.0.0.0:8000"]

LABEL version="3.0" maintainer="TSE"
