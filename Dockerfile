FROM php:7.2-fpm

# Install modules
RUN apt-get update && yes|apt-get install -y \
	apt-utils \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libicu-dev \
	libzip-dev \
	libonig-dev \
	libpq-dev \
	zlib1g-dev \
        wget \
        git \
    libxml2-dev
#            --no-install-recommends

RUN apt-get update && yes|apt-get install -y libpq-dev

RUN docker-php-ext-install pdo pgsql pdo_mysql pdo_pgsql zip exif xml xmlrpc \
	&& docker-php-ext-configure gd \
	&& docker-php-ext-install gd 

RUN rm -rf /tmp/pear

COPY ./install-composer.sh /

RUN apt-get purge -y g++ \
    && apt-get autoremove -y \
    && rm -r /var/lib/apt/lists/* \
    && rm -rf /tmp/* \
    && sh /install-composer.sh \
    && rm /install-composer.sh

RUN usermod -u 1000 www-data

VOLUME /root/.composer

WORKDIR /home/easyAdmin/symfony/

EXPOSE 9000
CMD ["php-fpm"]








