# -------------------
# Stage 1 - Build Vue Frontend
# -------------------
FROM node:22 AS frontend-build


WORKDIR /app

# Install frontend dependencies based on package.json & package-lock.json
COPY package*.json ./
RUN npm ci

# Copy the entire project source into the container
COPY . .

# Build the Vue.js frontend assets
RUN npm run build

# -------------------
# Stage 2 - Install Laravel Backend (with dev dependencies)
# -------------------
FROM composer:2 AS backend-build
WORKDIR /app

# Install PHP MongoDB extension (needed for composer install)
RUN apk add --no-cache libressl-dev pkgconfig autoconf g++ make \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# Copy all project files (backend source)
COPY . .

# # Install PHP dependencies (for production, use --no-dev)
RUN composer install

# Copy built frontend from Stage 1 into Laravel's public folder
COPY --from=frontend-build /app/public /app/public

# Optimize Laravel autoloader
RUN composer dump-autoload --optimize


# -------------------
# Stage 3 - Final Container (PHP runtime)
# -------------------
# FROM php:8.2-cli-bookworm AS final
# WORKDIR /var/www/html

# RUN apt-get update && apt-get install -y \
#     bash \
#     libzip-dev \
#     zip \
#     unzip \
#     openssl \
#     pkg-config \
#     autoconf \
#     g++ \
#     make \
#     curl \
#     default-mysql-client \
#     && pecl install mongodb \
#     && docker-php-ext-enable mongodb \
#     && docker-php-ext-install pdo pdo_mysql zip \
#     && apt-get clean && rm -rf /var/lib/apt/lists/*

# -------------------
# Stage 3 - Final Runtime
# -------------------
FROM thecodingmachine/php:8.2-v4-cli
WORKDIR /var/www/html

# Switch to root so we can install OS packages
USER root

# Install dependencies (Debian-based image)
RUN apt-get update && apt-get install -y \
    libssl-dev pkg-config autoconf g++ make unzip git \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && docker-php-ext-install pdo pdo_mysql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable required PHP extensions
ENV PHP_EXTENSIONS="mongodb pdo_mysql zip"

# Copy backend app (with vendor) from Stage 2
COPY --from=backend-build /app /var/www/html

# Copy frontend public assets from Stage 1 again
COPY --from=frontend-build /app/public /var/www/html/public

# Copy and set permissions for entrypoint
COPY entrypoint.sh /var/www/html/entrypoint.sh
RUN chmod +x /var/www/html/entrypoint.sh \
    && chown -R docker:docker storage bootstrap/cache

# Switch back to the default non-root user
USER docker

EXPOSE 8000
ENTRYPOINT ["/bin/sh", "./entrypoint.sh"]








# COPY entrypoint.sh /var/www/html/entrypoint.sh
# RUN chmod +x /var/www/html/entrypoint.sh
# ENTRYPOINT ["/var/www/html/entrypoint.sh"]




# Copy example env for dev
# COPY .env.example /var/www/html/.env

# ENTRYPOINT ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]