#!/bin/bash

# Oracle Cloud Laravel Deployment Script
# Run this on your Oracle Cloud instance after SSH connection

set -e

echo "=========================================="
echo "Oracle Cloud Laravel Setup Script"
echo "=========================================="

# Update system
echo "Updating system packages..."
sudo apt update && sudo apt upgrade -y

# Install PHP and extensions
echo "Installing PHP 8.2 and extensions..."
sudo apt install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-mbstring \
  php8.2-xml php8.2-bcmath php8.2-curl php8.2-zip php8.2-gd php8.2-intl

# Install Composer
echo "Installing Composer..."
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Install Node.js
echo "Installing Node.js..."
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Install Nginx
echo "Installing Nginx..."
sudo apt install -y nginx

# Install Git
echo "Installing Git..."
sudo apt install -y git

# Install MySQL client
echo "Installing MySQL client..."
sudo apt install -y mysql-client

# Install Certbot for SSL
echo "Installing Certbot..."
sudo apt install -y certbot python3-certbot-nginx

# Create app directory
echo "Creating application directory..."
sudo mkdir -p /var/www/laravel-app
sudo chown -R ubuntu:ubuntu /var/www/laravel-app

echo "=========================================="
echo "Installation Complete!"
echo "=========================================="
echo ""
echo "Next steps:"
echo "1. Clone your repository: cd /var/www/laravel-app && git clone YOUR_REPO ."
echo "2. Install dependencies: composer install --no-dev && npm install && npm run build"
echo "3. Copy .env file and configure database"
echo "4. Run migrations: php artisan migrate --force"
echo "5. Set permissions: sudo chown -R www-data:www-data /var/www/laravel-app/storage"
echo "6. Configure Nginx (see ORACLE_DEPLOYMENT_GUIDE.md)"
echo "7. Start services: sudo systemctl start php8.2-fpm && sudo systemctl start nginx"
echo ""
echo "Verify installations:"
php -v
composer --version
node -v
npm -v
