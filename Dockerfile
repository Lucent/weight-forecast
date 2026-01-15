FROM php:apache

RUN docker-php-ext-install mysqli

WORKDIR /var/www/html

COPY index.php login.php logout.php faq.html sample.html robots.txt ./
COPY server/ ./server/
COPY js/ ./js/

EXPOSE 80
