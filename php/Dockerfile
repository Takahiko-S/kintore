FROM php:8.2.7-apache

RUN apt-get update && apt-get install -y zlib1g-dev libzip-dev zip unzip

# mysqli インストール
RUN docker-php-ext-install mysqli

# pdo_mysql インストール
RUN docker-php-ext-install pdo_mysql

# zip インストール
RUN docker-php-ext-install zip

# mysqli を有効化
RUN docker-php-ext-enable mysqli

# mod_rewrite を有効化
RUN a2enmod rewrite


# composerのインストール
RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && chmod +x /usr/local/bin/composer

WORKDIR /var/www/html

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
ENV PATH $PATH:/composer/vendor/bin
# タイムゾーン設定
ENV TZ=Asia/Tokyo
