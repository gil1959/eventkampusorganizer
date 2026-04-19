FROM php:8.2-apache

# Install dependencies yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    zip \
    unzip \
    curl \
    nodejs \
    npm

# Bersihkan cache installer
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install ekstensi PHP (termasuk MySQL)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Aktifkan Apache mod_rewrite agar routing Laravel bekerja
RUN a2enmod rewrite

# Ubah root direktori Apache ke folder /public Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set tempat kerja
WORKDIR /var/www/html

# Copy seluruh source code Anda ke dalam server container
COPY . /var/www/html

# Install Composer (Package Manager PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install semua framework dependencies
RUN composer install --no-dev --optimize-autoloader

# Install NPM & Build CSS/JS Frontend
RUN npm install
RUN npm run build

# Pastikan folder penyimpanan file memiliki izin penulisan
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Buka port 80 untuk akses web Render
EXPOSE 80

# Script otomatis saat container berjalan (link storage & jalankan web)
CMD php artisan storage:link && apache2-foreground
