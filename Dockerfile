FROM php:7.4-apache
RUN apt-get update && apt-get install -y libmcrypt-dev mysql-client
&& docker-php-ext-install mcrypt pdo_mysql
COPY . /var/www/html/
RUN chmod -R a+r /var/www/html/
RUN a2enmod rewrite
EXPOSE 80