FROM app_base

RUN apt-get update && apt-get install -y \
    autoconf \
    automake \
    gcc \
    make \
    libtool \
    wget \
    pkg-config \
    && rm -rf /var/lib/apt/lists/*


WORKDIR /tmp

RUN wget https://xdebug.org/files/xdebug-3.4.5.tgz \
    && tar -xvzf xdebug-3.4.5.tgz \
    && cd xdebug-3.4.5 \
    && phpize \
    && ./configure \
    && make \
    && cp modules/xdebug.so /usr/local/lib/php/extensions/no-debug-non-zts-20230831/ \
    && cd .. \
    && rm -rf xdebug-3.4.5 xdebug-3.4.5.tgz

RUN mkdir -p /usr/local/etc/php/conf.d \
    && echo "zend_extension=/usr/local/lib/php/extensions/no-debug-non-zts-20230831/xdebug.so" > /usr/local/etc/php/conf.d/99-xdebug.ini \
    && echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/99-xdebug.ini

RUN rm -rf /tmp/xdebug-3.4.4 /tmp/xdebug-3.4.4.tgz

WORKDIR /app
