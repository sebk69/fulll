FROM php:8.4-cli

# Install composer
RUN apt update \
    && apt install -y curl zip \
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