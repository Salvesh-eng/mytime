# Oracle Cloud Deployment Guide for Laravel App

## Overview
This guide will help you deploy your Laravel time-tracking and personal finance application to Oracle Cloud's Always Free tier.

**What you'll get:**
- ✅ Always-running compute instance (ARM-based, free forever)
- ✅ MySQL database (20GB, free forever)
- ✅ 10GB object storage (free forever)
- ✅ No credit card charges if you stay in free tier
- ✅ GitHub integration for automatic deployments

---

## Step 1: Create Oracle Cloud Account

1. Go to https://www.oracle.com/cloud/free/
2. Click **"Start for free"**
3. Fill in your details (email, password, region)
4. **Choose region closest to you** (e.g., if in Fiji, choose Asia Pacific)
5. Verify email and phone
6. Add payment method (won't be charged for free tier)
7. Complete account setup

**Important:** Select a region close to your location for better performance.

---

## Step 2: Create Compute Instance (VM)

### 2.1 Access Oracle Cloud Console
1. Log in to https://www.oracle.com/cloud/
2. Click **"Compute"** → **"Instances"**
3. Click **"Create Instance"**

### 2.2 Configure Instance
Fill in the following:

**Name:** `laravel-app` (or any name)

**Image and shape:**
- Click **"Change image"**
- Select **"Ubuntu 22.04"** (or latest LTS)
- Click **"Change shape"**
- Select **"Ampere (ARM)"** → **"VM.Standard.A1.Flex"**
- Set **OCPU: 1** and **Memory: 6 GB** (free tier allows up to 4 OCPUs and 24GB)

**Networking:**
- VCN: Create new or use default
- Subnet: Create new or use default
- Public IP: **Assign a public IPv4 address** ✅

**SSH Key:**
- Select **"Generate a key pair for me"**
- Download the private key (save it safely as `oracle-key.key`)
- **IMPORTANT:** Keep this key secure!

**Boot volume:**
- Size: **50 GB** (free tier allows up to 200GB total)

### 2.3 Create Instance
Click **"Create"** and wait 2-3 minutes for the instance to start.

---

## Step 3: Create MySQL Database

### 3.1 Access Database Service
1. In Oracle Cloud Console, click **"Databases"** → **"MySQL Database Service"**
2. Click **"Create DB System"**

### 3.2 Configure Database
Fill in the following:

**Name:** `laravel-db`

**MySQL Version:** Latest available (8.0+)

**Administrator Credentials:**
- Username: `admin`
- Password: Create a strong password (save it!)

**Networking:**
- VCN: Select the same VCN as your compute instance
- Subnet: Select the same subnet

**Backup:**
- Backup retention: 7 days (free tier)

**Storage:**
- Data Storage Size: **20 GB** (free tier limit)

### 3.3 Create Database
Click **"Create"** and wait 5-10 minutes for the database to be ready.

---

## Step 4: Connect to Your Instance via SSH

### 4.1 On Windows (using PowerShell or WSL)
```bash
# Set correct permissions on the key
chmod 600 oracle-key.key

# Connect to your instance
ssh -i oracle-key.key ubuntu@YOUR_PUBLIC_IP
```

### 4.2 On Mac/Linux
```bash
chmod 600 oracle-key.key
ssh -i oracle-key.key ubuntu@YOUR_PUBLIC_IP
```

**Find YOUR_PUBLIC_IP:**
- Go to Oracle Cloud Console → Instances
- Click your instance name
- Copy the "Public IP Address"

---

## Step 5: Install Required Software on Instance

Once connected via SSH, run these commands:

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP and extensions
sudo apt install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-mbstring \
  php8.2-xml php8.2-bcmath php8.2-curl php8.2-zip php8.2-gd

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js and npm
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Install Nginx
sudo apt install -y nginx

# Install Git
sudo apt install -y git

# Install MySQL client
sudo apt install -y mysql-client

# Verify installations
php -v
composer --version
node -v
npm -v
```

---

## Step 6: Clone Your Repository

```bash
# Create app directory
sudo mkdir -p /var/www/laravel-app
sudo chown -R ubuntu:ubuntu /var/www/laravel-app

# Clone your GitHub repository
cd /var/www/laravel-app
git clone https://github.com/YOUR_USERNAME/YOUR_REPO.git .

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

---

## Step 7: Configure Laravel Environment

```bash
# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Edit .env file
nano .env
```

**Update these values in .env:**

```env
APP_NAME="MyTime"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://YOUR_PUBLIC_IP

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=YOUR_DATABASE_HOSTNAME
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=admin
DB_PASSWORD=YOUR_DATABASE_PASSWORD

# Session & Cache
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# Mail (optional, use log for now)
MAIL_MAILER=log
```

**To find DATABASE_HOSTNAME:**
- Go to Oracle Cloud Console → Databases → MySQL Database Service
- Click your database name
- Copy the "Endpoint" hostname

---

## Step 8: Set Up Database

```bash
# Run migrations
php artisan migrate --force

# Seed database (optional)
php artisan db:seed --class=DatabaseSeeder

# Clear cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Step 9: Configure Nginx

```bash
# Create Nginx configuration
sudo nano /etc/nginx/sites-available/laravel-app
```

**Paste this configuration:**

```nginx
server {
    listen 80;
    server_name YOUR_PUBLIC_IP;
    root /var/www/laravel-app/public;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

**Enable the site:**

```bash
sudo ln -s /etc/nginx/sites-available/laravel-app /etc/nginx/sites-enabled/
sudo rm /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl restart nginx
```

---

## Step 10: Set Up PHP-FPM

```bash
# Start PHP-FPM
sudo systemctl start php8.2-fpm
sudo systemctl enable php8.2-fpm

# Set correct permissions
sudo chown -R www-data:www-data /var/www/laravel-app/storage
sudo chown -R www-data:www-data /var/www/laravel-app/bootstrap/cache
sudo chmod -R 775 /var/www/laravel-app/storage
sudo chmod -R 775 /var/www/laravel-app/bootstrap/cache
```

---

## Step 11: Configure Firewall

```bash
# Allow HTTP and HTTPS
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

---

## Step 12: Set Up SSL Certificate (Optional but Recommended)

```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-nginx

# Get SSL certificate
sudo certbot certonly --nginx -d YOUR_DOMAIN

# Update Nginx config to use SSL
sudo nano /etc/nginx/sites-available/laravel-app
```

**Add SSL configuration:**

```nginx
server {
    listen 443 ssl http2;
    server_name YOUR_DOMAIN;
    
    ssl_certificate /etc/letsencrypt/live/YOUR_DOMAIN/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/YOUR_DOMAIN/privkey.pem;
    
    # ... rest of config
}

server {
    listen 80;
    server_name YOUR_DOMAIN;
    return 301 https://$server_name$request_uri;
}
```

---

## Step 13: Set Up GitHub Auto-Deployment (Optional)

### 13.1 Create Deployment Script

```bash
sudo nano /var/www/laravel-app/deploy.sh
```

**Paste:**

```bash
#!/bin/bash
cd /var/www/laravel-app
git pull origin main
composer install --no-dev --optimize-autoloader
npm install
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx
```

**Make it executable:**

```bash
sudo chmod +x /var/www/laravel-app/deploy.sh
sudo chown www-data:www-data /var/www/laravel-app/deploy.sh
```

### 13.2 Set Up GitHub Webhook

1. Go to your GitHub repository
2. Settings → Webhooks → Add webhook
3. Payload URL: `http://YOUR_PUBLIC_IP/webhook`
4. Content type: `application/json`
5. Secret: Create a random string (save it)

### 13.3 Create Webhook Handler (Optional)

Create a simple webhook handler in your Laravel app to trigger deployments.

---

## Step 14: Access Your Application

Open your browser and go to:
```
http://YOUR_PUBLIC_IP
```

Or if you set up a domain:
```
https://YOUR_DOMAIN
```

---

## Troubleshooting

### Can't connect via SSH
- Check security list rules in Oracle Cloud Console
- Ensure port 22 is open
- Verify key permissions: `chmod 600 oracle-key.key`

### Database connection error
- Verify database is running in Oracle Cloud Console
- Check DB_HOST, DB_USERNAME, DB_PASSWORD in .env
- Ensure compute instance and database are in same VCN

### Nginx not working
- Check logs: `sudo tail -f /var/log/nginx/error.log`
- Verify config: `sudo nginx -t`

### PHP-FPM not working
- Check logs: `sudo tail -f /var/log/php8.2-fpm.log`
- Restart: `sudo systemctl restart php8.2-fpm`

### Storage/Cache permissions
```bash
sudo chown -R www-data:www-data /var/www/laravel-app/storage
sudo chmod -R 775 /var/www/laravel-app/storage
```

---

## Monitoring and Maintenance

### Check Application Logs
```bash
tail -f /var/www/laravel-app/storage/logs/laravel.log
```

### Check Nginx Logs
```bash
sudo tail -f /var/log/nginx/access.log
sudo tail -f /var/log/nginx/error.log
```

### Restart Services
```bash
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
```

### Update Application
```bash
cd /var/www/laravel-app
git pull origin main
composer install --no-dev
npm install && npm run build
php artisan migrate --force
php artisan config:cache
```

---

## Cost Summary

**Oracle Cloud Always Free Tier:**
- Compute: 2 ARM instances (4 OCPUs, 24GB RAM) - **FREE**
- Database: 1 MySQL DB (20GB) - **FREE**
- Storage: 10GB - **FREE**
- Bandwidth: Limited but sufficient - **FREE**

**Total Cost: $0/month forever** ✅

---

## Next Steps

1. Create Oracle Cloud account
2. Create compute instance (Ubuntu 22.04)
3. Create MySQL database
4. Follow steps 4-12 above
5. Access your app at `http://YOUR_PUBLIC_IP`
6. (Optional) Set up custom domain and SSL

---

## Support Resources

- Oracle Cloud Documentation: https://docs.oracle.com/en-us/iaas/
- Laravel Documentation: https://laravel.com/docs
- Nginx Documentation: https://nginx.org/en/docs/
- MySQL Documentation: https://dev.mysql.com/doc/

---

**Your app is now live on Oracle Cloud's free tier, running 24/7 forever!** 🚀
