# Use official PHP image with Apache
FROM php:8.2-cli

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    && docker-php-ext-install curl \
    && apt-get clean

# Set working directory
WORKDIR /app

# Copy application files
COPY . /app

# Copy production files
RUN cp config_production.php config.php && \
    cp submit_production.php submit.php

# Expose port
EXPOSE 8080

# Start PHP built-in server
CMD php -S 0.0.0.0:${PORT:-8080}
