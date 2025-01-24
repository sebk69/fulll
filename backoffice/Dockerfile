FROM php:8.4-cli

# Install composer
RUN apt update \
    && apt install -y curl zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install mbstring
RUN apt-get update && apt-get install -y \
    libonig-dev \
    && docker-php-ext-install mbstring \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install DomDocument
RUN apt-get update && apt-get install -y \
    libxml2-dev \
    && docker-php-ext-install dom \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install sources
COPY . /usr/src/backoffice
WORKDIR /usr/src/backoffice

# Update composer
RUN cd /usr/src/backoffice && \
    composer install

ENTRYPOINT sleep infinity