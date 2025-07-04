# Use the official PHP Apache image
FROM php:8.2-apache

# Enable Apache mod_rewrite (optional but often useful)
RUN a2enmod rewrite

# Copy your app's files into the container
COPY . /var/www/html/

# Set ownership and permissions
RUN chown -R www-data:www-data /var/www/html

# Install common PHP extensions if needed
RUN docker-php-ext-install pdo pdo_mysql

# Expose port 80 (Render uses this for HTTP)
EXPOSE 80
