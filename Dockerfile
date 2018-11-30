FROM phpstorm/php-56-cli-xdebug-25

# Install system packages
ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update && \
    apt-get -y install \
            git \
            zip \
        --no-install-recommends && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN echo "phar.readonly=0" > /usr/local/etc/php/conf.d/99-custom.ini