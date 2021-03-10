FROM php:7.4-apache-buster

RUN a2enmod rewrite ssl headers

RUN apt-get update && apt-get install -y \
        nano \
        wget \
        git \
        zlib1g-dev \
        libzip-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Install required php extensions
RUN docker-php-ext-install \
    zip \
    bcmath \
    pdo_mysql \
    mysqli \
    exif 

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Change workdir to apache root
WORKDIR /var/www/html

# Copy source files to the image
COPY . .

# Install dependencies
RUN composer install
