FROM php:8.3-cli

# install swoole
RUN apt update \
    && apt-get install -y libbrotli-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*
RUN pecl install swoole-5.1.6 && docker-php-ext-enable swoole

# install pdo mysql
RUN docker-php-ext-install pdo pdo_mysql

# Install composer
RUN apt update \
    && apt install -y curl zip git \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install sources
COPY . /usr/src/backoffice
WORKDIR /usr/src/backoffice

# Update composer
RUN cd /usr/src/backoffice && \
    composer install

ENTRYPOINT sleep infinity