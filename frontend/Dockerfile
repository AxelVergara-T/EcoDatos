# Imagen base de PHP con Apache
FROM php:8.2-apache

# Instala extensiones necesarias (PostgreSQL + MySQL)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql pgsql mysqli

# Copia el contenido del proyecto al contenedor
COPY . /var/www/html/

# Habilita el módulo de Apache "rewrite" (por si lo necesitas después)
RUN a2enmod rewrite
