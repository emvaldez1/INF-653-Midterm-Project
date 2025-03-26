# Use an official PHP runtime as a parent image
FROM php:7.4-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the current directory contents into the container at /var/www/html
ADD . /var/www/html

# Install PostgreSQL development libraries and the PHP extension for PDO
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Fix Apache DirectoryIndex and AllowOverride
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
    sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Make port 80 available to the world outside this container
EXPOSE 80

# Run Apache server in the foreground
CMD ["apache2-foreground"]
