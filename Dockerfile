FROM php:7.4-cli

WORKDIR /API

COPY composer.json .

COPY index.php .

COPY getEvents.php .

COPY sortData.php .

RUN apt update && apt install -y git
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

ENTRYPOINT [ "php", "-S", "0.0.0.0:8000" ]

LABEL version="3.0" maintainer="TSE"