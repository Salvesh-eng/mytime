# Oracle Cloud Deployment Checklist

## Pre-Deployment (Before Starting)

- [ ] GitHub repository is public or you have access
- [ ] All code is committed and pushed to GitHub
- [ ] `.env.example` is up to date
- [ ] Database migrations are created
- [ ] Seeders are ready (optional)
- [ ] You have a credit card (won't be charged for free tier)

---

## Account Setup

- [ ] Create Oracle Cloud account at https://www.oracle.com/cloud/free/
- [ ] Verify email and phone
- [ ] Add payment method
- [ ] Choose region (preferably closest to your location)
- [ ] Complete account setup

---

## Infrastructure Setup

### Compute Instance
- [ ] Create Ubuntu 22.04 LTS instance
- [ ] Select ARM (Ampere) shape: VM.Standard.A1.Flex
- [ ] Set OCPU: 1, Memory: 6GB
- [ ] Assign public IPv4 address
- [ ] Generate and download SSH key pair
- [ ] Save SSH key securely (oracle-key.key)
- [ ] Note the public IP address
- [ ] Wait for instance to start (2-3 minutes)

### MySQL Database
- [ ] Create MySQL Database Service
- [ ] Set admin username: `admin`
- [ ] Create strong admin password (save it!)
- [ ] Select same VCN as compute instance
- [ ] Set storage: 20GB
- [ ] Wait for database to be ready (5-10 minutes)
- [ ] Note the database hostname/endpoint

### Networking
- [ ] Verify security list allows port 22 (SSH)
- [ ] Verify security list allows port 80 (HTTP)
- [ ] Verify security list allows port 443 (HTTPS)
- [ ] Verify compute and database are in same VCN

---

## SSH Connection

- [ ] Set SSH key permissions: `chmod 600 oracle-key.key`
- [ ] Connect via SSH: `ssh -i oracle-key.key ubuntu@YOUR_PUBLIC_IP`
- [ ] Verify connection successful

---

## Server Setup

- [ ] Run system updates: `sudo apt update && sudo apt upgrade -y`
- [ ] Install PHP 8.2 and extensions
- [ ] Install Composer
- [ ] Install Node.js and npm
- [ ] Install Nginx
- [ ] Install Git
- [ ] Install MySQL client
- [ ] Verify all installations

**Quick command:**
```bash
bash <(curl -s https://raw.githubusercontent.com/YOUR_USERNAME/YOUR_REPO/main/setup-oracle.sh)
```

---

## Application Deployment

- [ ] Create app directory: `/var/www/laravel-app`
- [ ] Clone GitHub repository
- [ ] Install Composer dependencies: `composer install --no-dev --optimize-autoloader`
- [ ] Install npm dependencies: `npm install`
- [ ] Build frontend: `npm run build`
- [ ] Copy `.env.example` to `.env`
- [ ] Generate app key: `php artisan key:generate`
- [ ] Update `.env` with database credentials
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Run seeders (optional): `php artisan db:seed`
- [ ] Cache configuration: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Cache views: `php artisan view:cache`

---

## Permissions & Ownership

- [ ] Set storage permissions: `sudo chown -R www-data:www-data /var/www/laravel-app/storage`
- [ ] Set bootstrap permissions: `sudo chown -R www-data:www-data /var/www/laravel-app/bootstrap/cache`
- [ ] Set storage writable: `sudo chmod -R 775 /var/www/laravel-app/storage`
- [ ] Set bootstrap writable: `sudo chmod -R 775 /var/www/laravel-app/bootstrap/cache`

---

## Web Server Configuration

### Nginx Setup
- [ ] Copy nginx config: `sudo cp nginx-laravel-oracle.conf /etc/nginx/sites-available/laravel-app`
- [ ] Update server_name in config (replace YOUR_PUBLIC_IP)
- [ ] Enable site: `sudo ln -s /etc/nginx/sites-available/laravel-app /etc/nginx/sites-enabled/`
- [ ] Disable default site: `sudo rm /etc/nginx/sites-enabled/default`
- [ ] Test config: `sudo nginx -t`
- [ ] Restart Nginx: `sudo systemctl restart nginx`
- [ ] Enable Nginx on boot: `sudo systemctl enable nginx`

### PHP-FPM Setup
- [ ] Start PHP-FPM: `sudo systemctl start php8.2-fpm`
- [ ] Enable PHP-FPM on boot: `sudo systemctl enable php8.2-fpm`

---

## Firewall Configuration

- [ ] Enable UFW: `sudo ufw enable`
- [ ] Allow SSH: `sudo ufw allow 22/tcp`
- [ ] Allow HTTP: `sudo ufw allow 80/tcp`
- [ ] Allow HTTPS: `sudo ufw allow 443/tcp`
- [ ] Verify rules: `sudo ufw status`

---

## Testing

- [ ] Open browser and navigate to `http://YOUR_PUBLIC_IP`
- [ ] Verify application loads
- [ ] Test login functionality
- [ ] Test creating a project
- [ ] Test time entry creation
- [ ] Check database connection
- [ ] Verify logs: `tail -f /var/www/laravel-app/storage/logs/laravel.log`

---

## SSL Certificate (Optional but Recommended)

- [ ] Install Certbot: `sudo apt install -y certbot python3-certbot-nginx`
- [ ] Get certificate: `sudo certbot certonly --nginx -d YOUR_DOMAIN`
- [ ] Update Nginx config with SSL paths
- [ ] Test SSL: `sudo nginx -t`
- [ ] Restart Nginx: `sudo systemctl restart nginx`
- [ ] Set up auto-renewal: `sudo systemctl enable certbot.timer`

---

## GitHub Integration (Optional)

- [ ] Create deployment script: `/var/www/laravel-app/deploy.sh`
- [ ] Make script executable: `sudo chmod +x /var/www/laravel-app/deploy.sh`
- [ ] Set up GitHub webhook (optional)
- [ ] Test deployment script

---

## Monitoring & Maintenance

- [ ] Set up log rotation (optional)
- [ ] Create backup strategy
- [ ] Document database credentials securely
- [ ] Set up monitoring alerts (optional)
- [ ] Schedule regular updates

---

## Post-Deployment

- [ ] Document your setup
- [ ] Save all credentials securely
- [ ] Create backup of SSH key
- [ ] Test application thoroughly
- [ ] Monitor logs for errors
- [ ] Set up email notifications (optional)

---

## Troubleshooting Checklist

If something doesn't work:

- [ ] Check SSH connection: `ssh -i oracle-key.key ubuntu@YOUR_PUBLIC_IP`
- [ ] Check Nginx status: `sudo systemctl status nginx`
- [ ] Check PHP-FPM status: `sudo systemctl status php8.2-fpm`
- [ ] Check Nginx logs: `sudo tail -f /var/log/nginx/error.log`
- [ ] Check Laravel logs: `tail -f /var/www/laravel-app/storage/logs/laravel.log`
- [ ] Check database connection: `mysql -h YOUR_DB_HOST -u admin -p`
- [ ] Verify permissions: `ls -la /var/www/laravel-app/storage`
- [ ] Test PHP: `php -v` and `php -m`
- [ ] Verify Nginx config: `sudo nginx -t`

---

## Success Indicators

✅ Application is accessible at `http://YOUR_PUBLIC_IP`
✅ Login page loads correctly
✅ Can create projects and time entries
✅ Database is storing data
✅ No errors in Laravel logs
✅ Nginx is serving requests
✅ PHP-FPM is running
✅ SSL certificate is valid (if configured)

---

## Cost Verification

- [ ] Verify you're using only free tier resources
- [ ] Check Oracle Cloud Console for any paid services
- [ ] Confirm: 1 ARM compute instance (free)
- [ ] Confirm: 1 MySQL database 20GB (free)
- [ ] Confirm: No additional charges

**Expected Cost: $0/month** ✅

---

## Support Resources

- Oracle Cloud Docs: https://docs.oracle.com/en-us/iaas/
- Laravel Docs: https://laravel.com/docs
- Nginx Docs: https://nginx.org/en/docs/
- MySQL Docs: https://dev.mysql.com/doc/

---

**Congratulations! Your Laravel app is now live on Oracle Cloud's free tier!** 🚀
