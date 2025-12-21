# Oracle Cloud Deployment - Troubleshooting Guide

## Connection Issues

### Can't SSH into Instance

**Problem:** `Permission denied (publickey)` or connection timeout

**Solutions:**

1. **Check key permissions:**
   ```bash
   chmod 600 oracle-key.key
   ```

2. **Verify correct IP address:**
   - Go to Oracle Cloud Console → Instances
   - Click your instance
   - Copy the exact "Public IP Address"

3. **Check security list:**
   - Go to Oracle Cloud Console → Networking → Security Lists
   - Verify port 22 (SSH) is open
   - Add rule if needed: Ingress, Protocol: TCP, Port: 22, Source: 0.0.0.0/0

4. **Try with verbose output:**
   ```bash
   ssh -vvv -i oracle-key.key ubuntu@YOUR_PUBLIC_IP
   ```

5. **Verify instance is running:**
   - Check Oracle Cloud Console
   - Instance should show "RUNNING" status

---

## Database Connection Issues

### Can't Connect to MySQL Database

**Problem:** `SQLSTATE[HY000]: General error: 2006 MySQL server has gone away`

**Solutions:**

1. **Verify database is running:**
   - Go to Oracle Cloud Console → Databases → MySQL Database Service
   - Check status is "ACTIVE"

2. **Check database hostname:**
   ```bash
   # In Oracle Cloud Console, click your database
   # Copy the "Endpoint" hostname
   # Update in .env: DB_HOST=YOUR_ENDPOINT
   ```

3. **Verify credentials:**
   ```bash
   # Test connection from instance
   mysql -h YOUR_DB_HOST -u admin -p
   # Enter password when prompted
   ```

4. **Check VCN and subnet:**
   - Compute instance and database must be in same VCN
   - Verify in Oracle Cloud Console

5. **Check security list:**
   - Database security list must allow port 3306
   - Add rule if needed: Ingress, Protocol: TCP, Port: 3306

6. **Verify .env file:**
   ```bash
   cat /var/www/laravel-app/.env | grep DB_
   ```

---

## Web Server Issues

### Nginx Not Starting

**Problem:** `sudo systemctl start nginx` fails

**Solutions:**

1. **Check Nginx syntax:**
   ```bash
   sudo nginx -t
   ```

2. **Check error logs:**
   ```bash
   sudo tail -f /var/log/nginx/error.log
   ```

3. **Check if port 80 is in use:**
   ```bash
   sudo lsof -i :80
   ```

4. **Verify Nginx config:**
   ```bash
   sudo cat /etc/nginx/sites-enabled/laravel-app
   ```

5. **Restart Nginx:**
   ```bash
   sudo systemctl restart nginx
   sudo systemctl status nginx
   ```

### Nginx Returns 502 Bad Gateway

**Problem:** Browser shows "502 Bad Gateway"

**Solutions:**

1. **Check PHP-FPM is running:**
   ```bash
   sudo systemctl status php8.2-fpm
   ```

2. **Restart PHP-FPM:**
   ```bash
   sudo systemctl restart php8.2-fpm
   ```

3. **Check PHP-FPM socket:**
   ```bash
   ls -la /var/run/php/php8.2-fpm.sock
   ```

4. **Check Nginx error logs:**
   ```bash
   sudo tail -f /var/log/nginx/error.log
   ```

5. **Verify Nginx config points to correct socket:**
   ```bash
   grep fastcgi_pass /etc/nginx/sites-enabled/laravel-app
   # Should show: fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
   ```

### Nginx Returns 404 Not Found

**Problem:** All routes return 404

**Solutions:**

1. **Check Laravel is in public directory:**
   ```bash
   ls -la /var/www/laravel-app/public/
   ```

2. **Verify Nginx root path:**
   ```bash
   grep "root " /etc/nginx/sites-enabled/laravel-app
   # Should show: root /var/www/laravel-app/public;
   ```

3. **Check Laravel routes:**
   ```bash
   cd /var/www/laravel-app
   php artisan route:list
   ```

4. **Verify index.php exists:**
   ```bash
   ls -la /var/www/laravel-app/public/index.php
   ```

---

## PHP Issues

### PHP-FPM Not Starting

**Problem:** `sudo systemctl start php8.2-fpm` fails

**Solutions:**

1. **Check PHP-FPM status:**
   ```bash
   sudo systemctl status php8.2-fpm
   ```

2. **Check PHP-FPM logs:**
   ```bash
   sudo tail -f /var/log/php8.2-fpm.log
   ```

3. **Verify PHP installation:**
   ```bash
   php -v
   php -m
   ```

4. **Restart PHP-FPM:**
   ```bash
   sudo systemctl restart php8.2-fpm
   ```

### PHP Extensions Missing

**Problem:** `Call to undefined function` or missing extension errors

**Solutions:**

1. **Check installed extensions:**
   ```bash
   php -m
   ```

2. **Install missing extensions:**
   ```bash
   sudo apt install -y php8.2-EXTENSION_NAME
   ```

3. **Restart PHP-FPM:**
   ```bash
   sudo systemctl restart php8.2-fpm
   ```

---

## Laravel Application Issues

### Migrations Fail

**Problem:** `php artisan migrate` returns errors

**Solutions:**

1. **Check database connection:**
   ```bash
   php artisan tinker
   # In tinker: DB::connection()->getPdo()
   ```

2. **Check migration files:**
   ```bash
   ls -la /var/www/laravel-app/database/migrations/
   ```

3. **Run with verbose output:**
   ```bash
   php artisan migrate --verbose
   ```

4. **Check Laravel logs:**
   ```bash
   tail -f /var/www/laravel-app/storage/logs/laravel.log
   ```

5. **Force migration (use with caution):**
   ```bash
   php artisan migrate --force
   ```

### Storage/Cache Permissions Error

**Problem:** `The stream or file "/var/www/laravel-app/storage/logs/laravel.log" could not be opened`

**Solutions:**

1. **Fix storage permissions:**
   ```bash
   sudo chown -R www-data:www-data /var/www/laravel-app/storage
   sudo chmod -R 775 /var/www/laravel-app/storage
   ```

2. **Fix bootstrap permissions:**
   ```bash
   sudo chown -R www-data:www-data /var/www/laravel-app/bootstrap/cache
   sudo chmod -R 775 /var/www/laravel-app/bootstrap/cache
   ```

3. **Create log file if missing:**
   ```bash
   sudo touch /var/www/laravel-app/storage/logs/laravel.log
   sudo chown www-data:www-data /var/www/laravel-app/storage/logs/laravel.log
   ```

### Blank Page or 500 Error

**Problem:** Browser shows blank page or 500 error

**Solutions:**

1. **Check Laravel logs:**
   ```bash
   tail -f /var/www/laravel-app/storage/logs/laravel.log
   ```

2. **Enable debug mode (temporarily):**
   ```bash
   # Edit .env
   nano /var/www/laravel-app/.env
   # Change: APP_DEBUG=true
   # Restart: sudo systemctl restart php8.2-fpm
   ```

3. **Check .env file:**
   ```bash
   cat /var/www/laravel-app/.env
   ```

4. **Clear cache:**
   ```bash
   cd /var/www/laravel-app
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

5. **Check file permissions:**
   ```bash
   ls -la /var/www/laravel-app/
   ```

### Application Slow or Timing Out

**Problem:** Application takes long time to load or times out

**Solutions:**

1. **Check system resources:**
   ```bash
   free -h
   df -h
   top
   ```

2. **Check database performance:**
   ```bash
   mysql -h YOUR_DB_HOST -u admin -p
   # In MySQL: SHOW PROCESSLIST;
   ```

3. **Enable query logging:**
   ```bash
   # Edit .env: APP_DEBUG=true
   # Check storage/logs/laravel.log for slow queries
   ```

4. **Optimize Laravel:**
   ```bash
   cd /var/www/laravel-app
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

---

## File Upload Issues

### Can't Upload Files

**Problem:** File upload fails or files not saved

**Solutions:**

1. **Check storage directory permissions:**
   ```bash
   ls -la /var/www/laravel-app/storage/app/
   sudo chown -R www-data:www-data /var/www/laravel-app/storage/app/
   sudo chmod -R 775 /var/www/laravel-app/storage/app/
   ```

2. **Check disk space:**
   ```bash
   df -h
   ```

3. **Check upload size limits in .env:**
   ```bash
   # Verify FILESYSTEM_DISK=local
   ```

4. **Check Nginx upload size limit:**
   ```bash
   # Add to Nginx config:
   client_max_body_size 100M;
   ```

---

## SSL Certificate Issues

### SSL Certificate Not Working

**Problem:** HTTPS not working or certificate errors

**Solutions:**

1. **Check certificate exists:**
   ```bash
   ls -la /etc/letsencrypt/live/YOUR_DOMAIN/
   ```

2. **Verify Nginx config:**
   ```bash
   grep ssl_certificate /etc/nginx/sites-enabled/laravel-app
   ```

3. **Test SSL:**
   ```bash
   sudo nginx -t
   ```

4. **Renew certificate:**
   ```bash
   sudo certbot renew --dry-run
   sudo certbot renew
   ```

5. **Check certificate expiry:**
   ```bash
   sudo certbot certificates
   ```

---

## Performance Optimization

### Application Running Slow

**Solutions:**

1. **Enable caching:**
   ```bash
   cd /var/www/laravel-app
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Optimize Composer:**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

3. **Enable Gzip in Nginx:**
   ```bash
   # Already included in nginx-laravel-oracle.conf
   ```

4. **Check database indexes:**
   ```bash
   # Review migration files for proper indexing
   ```

5. **Monitor system resources:**
   ```bash
   top
   free -h
   df -h
   ```

---

## Debugging Commands

### Check All Services Status

```bash
sudo systemctl status nginx
sudo systemctl status php8.2-fpm
sudo systemctl status mysql
```

### View All Logs

```bash
# Nginx access log
sudo tail -f /var/log/nginx/access.log

# Nginx error log
sudo tail -f /var/log/nginx/error.log

# Laravel log
tail -f /var/www/laravel-app/storage/logs/laravel.log

# PHP-FPM log
sudo tail -f /var/log/php8.2-fpm.log
```

### Test Database Connection

```bash
mysql -h YOUR_DB_HOST -u admin -p
# Enter password
# Type: SELECT 1;
# Should return: 1
```

### Test PHP

```bash
php -v
php -m
php -i
```

### Test Nginx

```bash
sudo nginx -t
sudo systemctl restart nginx
```

---

## Getting Help

If you're still stuck:

1. **Check Oracle Cloud documentation:** https://docs.oracle.com/en-us/iaas/
2. **Check Laravel documentation:** https://laravel.com/docs
3. **Check Nginx documentation:** https://nginx.org/en/docs/
4. **Search error messages on Google**
5. **Check Laravel logs for detailed error messages**

---

## Common Error Messages

### "Connection refused"
- Database not running or wrong host
- Check database status in Oracle Cloud Console

### "Permission denied"
- File permissions issue
- Run: `sudo chown -R www-data:www-data /var/www/laravel-app`

### "No such file or directory"
- File path incorrect
- Check file exists: `ls -la /path/to/file`

### "SQLSTATE[HY000]"
- Database connection error
- Check DB_HOST, DB_USERNAME, DB_PASSWORD in .env

### "502 Bad Gateway"
- PHP-FPM not running
- Run: `sudo systemctl restart php8.2-fpm`

### "404 Not Found"
- Route not found or Nginx misconfigured
- Check Nginx config and Laravel routes

---

**Still having issues? Check the logs first!** 📋
