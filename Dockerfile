FROM php:8.1-cli

COPY build/noip.phar /usr/local/bin
ENTRYPOINT ["php", "-dmemory_limit=384M", "/usr/local/bin/noip.phar"]
CMD []
USER nobody
