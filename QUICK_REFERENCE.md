# Oracle Cloud Deployment - Quick Reference

## Essential Commands

### SSH Connection
```bash
ssh -i oracle-key.key ubuntu@YOUR_PUBLIC_IP
```

### System Updates
```bash
sudo apt update && sudo apt upgrade -y
```

### Application Deployment
```bash
cd /var/www/laravel-app
git pull origin main
composer install --no-dev --optimize-autoloader
npm install && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Service Management
```bash
# Nginx
sudo systemctl start nginx
sudo systemctl stop nginx
sudo systemctl restart nginx
sudo systemctl status nginx

# PHP-FPM
sudo systemctl start php8.2-fpm
sudo systemctl stop php8.2-fpm
sudo systemctl restart php8.2-fpm
sudo systemctl status php8.2-fpm
```

### Permissions
```bash
sudo chown -R www-data:www-data /var/www/laravel-app/storage
sudo chown -R www-data:www-data /var/www/laravel-app/bootstrap/cache
sudo chmod -R 775 /var/www/laravel-app/storage
sudo chmod -R 775 /var/www/laravel-app/bootstrap/cache
```

### Logs
```bash
# Laravel logs
tail -f /var/www/laravel-app/storage/logs/laravel.log

# Nginx error logs
sudo tail -f /var/log/nginx/error.log

# Nginx access logs
sudo tail -f /var/log/nginx/access.log

# PHP-FPM logs
sudo tail -f /var/log/php8.2-fpm.log
```

### Database
```bash
# Connect to database
mysql -h YOUR_DB_HOST -u admin -p

# Test connection
mysql -h YOUR_DB_HOST -u admin -p -e "SELECT 1;"
```

### Cache Clearing
```bash
cd /var/www/laravel-app
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

---

## Important Files & Locations

| Item | Location |
|------|----------|
| Application | `/var/www/laravel-app` |
| .env file | `/var/www/laravel-app/.env` |
| Nginx config | `/etc/nginx/sites-available/laravel-app` |
| Nginx logs | `/var/log/nginx/` |
| Laravel logs | `/var/www/laravel-app/storage/logs/` |
| PHP-FPM config | `/etc/php/8.2/fpm/php.ini` |
| SSH key | `oracle-key.key` (on your local machine) |

---

## Environment Variables (.env)

```env
APP_NAME=MyTime
APP_ENV=production
APP_DEBUG=false
APP_URL=http://YOUR_PUBLIC_IP

DB_CONNECTION=mysql
DB_HOST=YOUR_DATABASE_HOSTNAME
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=admin
DB_PASSWORD=YOUR_DATABASE_PASSWORD

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

---

## Firewall Rules

```bash
# Allow SSH
sudo ufw allow 22/tcp

# Allow HTTP
sudo ufw allow 80/tcp

# Allow HTTPS
sudo ufw allow 443/tcp

# Enable firewall
sudo ufw enable

# Check status
sudo ufw status
```

---

## Database Connection Test

```bash
# From your instance
mysql -h YOUR_DB_HOST -u admin -p

# In MySQL prompt
SHOW DATABASES;
USE laravel;
SHOW TABLES;
SELECT COUNT(*) FROM users;
EXIT;
```

---

## Nginx Configuration Test

```bash
# Test syntax
sudo nginx -t

# Reload configuration
sudo nginx -s reload

# Restart service
sudo systemctl restart nginx
```

---

## PHP-FPM Socket Check

```bash
# Verify socket exists
ls -la /var/run/php/php8.2-fpm.sock

# Should show something like:
# srw-rw---- 1 www-data www-data 0 Dec 10 12:34 /var/run/php/php8.2-fpm.sock
```

---

## Disk Space Check

```bash
# Check disk usage
df -h

# Check directory size
du -sh /var/www/laravel-app
du -sh /var/www/laravel-app/storage
```

---

## Memory & CPU Check

```bash
# Check memory
free -h

# Check CPU
top

# Check processes
ps aux | grep php
ps aux | grep nginx
```

---

## Git Operations

```bash
# Clone repository
git clone https://github.com/YOUR_USERNAME/YOUR_REPO.git

# Pull latest changes
git pull origin main

# Check status
git status

# View logs
git log --oneline -10
```

---

## Laravel Artisan Commands

```bash
# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# Run migrations with force (production)
php artisan migrate --force

# Seed database
php artisan db:seed

# Clear all caches
php artisan cache:clear

# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# List routes
php artisan route:list

# Tinker (interactive shell)
php artisan tinker
```

---

## SSL Certificate Management

```bash
# Get certificate
sudo certbot certonly --nginx -d YOUR_DOMAIN

# List certificates
sudo certbot certificates

# Renew certificate
sudo certbot renew

# Test renewal
sudo certbot renew --dry-run

# Auto-renewal status
sudo systemctl status certbot.timer
```

---

## Deployment Script

```bash
#!/bin/bash
cd /var/www/laravel-app
git pull origin main
composer install --no-dev --optimize-autoloader
npm install && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx
```

---

## Monitoring Commands

```bash
# Real-time system monitoring
top

# Disk usage
df -h

# Memory usage
free -h

# Network connections
netstat -tuln

# Open ports
sudo lsof -i -P -n

# Process list
ps aux

# System uptime
uptime

# System load
cat /proc/loadavg
```

---

## Backup Commands

```bash
# Backup application
tar -czf laravel-app-backup.tar.gz /var/www/laravel-app

# Backup database
mysqldump -h YOUR_DB_HOST -u admin -p laravel > laravel-backup.sql

# Restore database
mysql -h YOUR_DB_HOST -u admin -p laravel < laravel-backup.sql
```

---

## Useful Links

- **Oracle Cloud Console:** https://www.oracle.com/cloud/
- **Oracle Cloud Docs:** https://docs.oracle.com/en-us/iaas/
- **Laravel Docs:** https://laravel.com/docs
- **Nginx Docs:** https://nginx.org/en/docs/
- **MySQL Docs:** https://dev.mysql.com/doc/
- **PHP Docs:** https://www.php.net/docs.php

---

## Troubleshooting Quick Links

- **Can't SSH?** → Check security list, key permissions
- **502 Bad Gateway?** → Restart PHP-FPM
- **Database error?** → Check DB_HOST, credentials, VCN
- **Blank page?** → Check Laravel logs, enable debug
- **Slow app?** → Clear cache, check resources
- **File upload fails?** → Check storage permissions

---

## Cost Verification

```bash
# Check what's running
sudo systemctl list-units --type=service --state=running

# Verify free tier resources
# - 1 ARM compute instance (free)
# - 1 MySQL database 20GB (free)
# - 10GB storage (free)
```

**Expected monthly cost: $0** ✅

---

## Emergency Contacts

- **Oracle Support:** https://support.oracle.com/
- **Laravel Community:** https://laravel.com/community
- **Stack Overflow:** https://stackoverflow.com/questions/tagged/laravel

---

**Save this file for quick reference during deployment!** 📌
