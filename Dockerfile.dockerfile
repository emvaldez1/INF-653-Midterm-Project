# Use an official PHP runtime as a parent image
FROM php:7.4-apache

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the current directory contents into the container at /var/www/html
ADD . /var/www/html

# Install any needed packages specified in requirements.txt
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql

# Make port 80 available to the world outside this container
EXPOSE 80

# Define environment variable
ENV NAME World

# Run app.py when the container launches
CMD ["apache2-foreground"]
