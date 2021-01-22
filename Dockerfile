FROM php:7.4-apache
WORKDIR /app
COPY . /app
RUN chmod -R a+r /var/www/html/
RUN a2enmod rewrite
EXPOSE 80