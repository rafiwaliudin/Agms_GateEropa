FROM php:7.3-fpm-alpine

LABEL Maintainer="Alfabeta" \
      Description="Container with PHP-FPM 7.3 based on Alpine Linux with Nginx"

RUN apk --no-cache add \
    nginx supervisor curl \
    zlib-dev libzip-dev \
    libjpeg-turbo-dev libpng-dev freetype-dev openssl vim

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-install gd

# Configure nginx
COPY docker-config/nginx.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY docker-config/fpm-pool.conf /etc/php7/php-fpm.d/www.conf
COPY docker-config/php.ini /etc/php7/conf.d/php.ini

# Configure supervisord
COPY docker-config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
# RUN chown -R nobody.nobody /run && \
#  chown -R nobody.nobody /var/lib/nginx && \
#  chown -R nobody.nobody /var/tmp/nginx && \
#  chown -R nobody.nobody /var/log/nginx

# Setup document root
RUN mkdir -p /var/www/html

# Add application
WORKDIR /var/www/html
# COPY --chown=nobody . /var/www/html/
# RUN chown -R nobody.nobody /var/www/html/
COPY . /var/www/html/
RUN cp -u .env.example .env

# Switch to use a non-root user from here on
# USER nobody

# Expose the port nginx is reachable on
EXPOSE 8081

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
