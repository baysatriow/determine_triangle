# Gunakan image PHP bawaan yang ringan
FROM php:8.2-apache

# Copy semua file project ke dalam container
COPY . /var/www/html/

# Ekspos port web server
EXPOSE 80
