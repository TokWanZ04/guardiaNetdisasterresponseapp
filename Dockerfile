FROM node:20 as node-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

FROM serversideup/php:8.2-fpm-nginx

# Set environment variables for production
ENV WEB_DOCUMENT_ROOT=/var/www/html/public
ENV PHP_OPCACHE_ENABLE=1

# Tell Render what port the serversideup image uses
EXPOSE 8080

# Switch to root to copy files with correct permissions
USER root

# Copy application files
COPY --chown=www-data:www-data . /var/www/html

# Copy compiled frontend assets from node-builder
COPY --from=node-builder --chown=www-data:www-data /app/public/build /var/www/html/public/build

# Switch back to web user for composer install
USER www-data

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Switch back to root so s6-overlay can start correctly
USER root
