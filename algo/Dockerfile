FROM php:8.4-cli

# Install composer
RUN apt update \
    && apt install -y curl zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


# Install sources
COPY . /usr/src/fiz-buz
WORKDIR /usr/src/fiz-buz

# Update composer
RUN cd /usr/src/fiz-buz && \
    composer install

# Stay up for invocation
ENTRYPOINT sleep infinity