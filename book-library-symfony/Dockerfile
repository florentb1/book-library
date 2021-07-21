FROM php:8-apache-buster

# Mise à jour et installation de dépendances
RUN apt-get -yqq update && apt-get install -yqq \
    openssl \
    libpng-dev \
    zlib1g-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libpq-dev \
    wget
RUN docker-php-ext-install pdo_mysql pdo_pgsql opcache mbstring zip gd exif mbstring intl \
	&& a2enmod rewrite negotiation

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

# Copie des configurations
COPY docker/php.ini /usr/local/etc/php/php.ini
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY docker/composer.sh /usr/local/bin/composer-installer
COPY docker/openssl.cnf /etc/ssl/openssl.cnf

# Installation de composer
RUN apt-get -yqq update \
	&& apt-get install -yqq --no-install-recommends zip unzip \
	&& sleep 1 \
	&& chmod +x /usr/local/bin/composer-installer \
	&& sleep 1 \
	&& composer-installer \
	&& mv composer.phar /usr/local/bin/composer \
	&& sleep 1 \
	&& chmod +x /usr/local/bin/composer \
	&& sleep 1 \
	&& composer --version

# Dossier de travail
WORKDIR /srv/app/book-library-symfony

# Ports
EXPOSE 80