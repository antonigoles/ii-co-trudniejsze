FROM php:8.3-fpm

# Instalacja zależności systemowych (przydatne do zipowania, gita itp.)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip

# Wyczyszczenie cache apt, żeby obraz był lżejszy
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalacja rozszerzeń PHP (dostosuj do potrzeb projektu)
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# Instalacja Composera (kopiujemy go z oficjalnego obrazu)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Ustawienie katalogu roboczego
WORKDIR /var/www/html