# Oracle Cloud Deployment - Step-by-Step Visual Guide

## Phase 1: Account & Infrastructure Setup (30 minutes)

### Step 1: Create Oracle Cloud Account
```
1. Go to https://www.oracle.com/cloud/free/
2. Click "Start for free"
3. Fill in your details
4. Verify email and phone
5. Add payment method (won't charge for free tier)
6. Choose region (closest to your location)
7. Complete setup
```

**Result:** ✅ Oracle Cloud account created

---

### Step 2: Create Compute Instance
```
Oracle Cloud Console
    ↓
Compute → Instances
    ↓
Create Instance
    ↓
Configuration:
  • Name: laravel-app
  • Image: Ubuntu 22.04 LTS
  • Shape: VM.Standard.A1.Flex (ARM)
  • OCPU: 1, Memory: 6GB
  • Public IP: Assign
  • SSH Key: Generate & Download
    ↓
Create
    ↓
Wait 2-3 minutes
```

**Result:** ✅ Compute instance running
**Save:** Public IP address, SSH key file

---

### Step 3: Create MySQL Database
```
Oracle Cloud Console
    ↓
Databases → MySQL Database Service
    ↓
Create DB System
    ↓
Configuration:
  • Name: laravel-db
  • Admin Username: admin
  • Admin Password: (create strong password)
  • VCN: Same as compute instance
  • Storage: 20GB
    ↓
Create
    ↓
Wait 5-10 minutes
```

**Result:** ✅ MySQL database running
**Save:** Database hostname, admin password

---

## Phase 2: Server Setup (15 minutes)

### Step 4: Connect via SSH
```
Local Machine:
  chmod 600 oracle-key.key
  ssh -i oracle-key.key ubuntu@YOUR_PUBLIC_IP
    ↓
Connected to Instance ✅
```

---

### Step 5: Install Software
```
On Instance:
  sudo apt update && sudo apt upgrade -y
    ↓
  Install PHP 8.2
  Install Composer
  Install Node.js
  Install Nginx
  Install Git
  Install MySQL client
    ↓
All software installed ✅
```

**Quick command:**
```bash
bash <(curl -s https://raw.githubusercontent.com/YOUR_USERNAME/YOUR_REPO/main/setup-oracle.sh)
```

---

## Phase 3: Application Deployment (20 minutes)

### Step 6: Clone & Setup Application
```
On Instance:
  mkdir -p /var/www/laravel-app
  cd /var/www/laravel-app
  git clone https://github.com/YOUR_USERNAME/YOUR_REPO.git .
    ↓
  composer install --no-dev --optimize-autoloader
  npm install
  npm run build
    ↓
Application files ready ✅
```

---

### Step 7: Configure Environment
```
On Instance:
  cp .env.example .env
  php artisan key:generate
  nano .env
    ↓
Update:
  APP_ENV=production
  APP_DEBUG=false
  APP_URL=http://YOUR_PUBLIC_IP
  DB_HOST=YOUR_DATABASE_HOSTNAME
  DB_USERNAME=admin
  DB_PASSWORD=YOUR_DATABASE_PASSWORD
    ↓
Save and exit ✅
```

---

### Step 8: Setup Database
```
On Instance:
  php artisan migrate --force
  php artisan db:seed (optional)
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
    ↓
Database ready ✅
```

---

### Step 9: Set Permissions
```
On Instance:
  sudo chown -R www-data:www-data /var/www/laravel-app/storage
  sudo chown -R www-data:www-data /var/www/laravel-app/bootstrap/cache
  sudo chmod -R 775 /var/www/laravel-app/storage
  sudo chmod -R 775 /var/www/laravel-app/bootstrap/cache
    ↓
Permissions set ✅
```

---

## Phase 4: Web Server Configuration (10 minutes)

### Step 10: Configure Nginx
```
On Instance:
  sudo cp nginx-laravel-oracle.conf /etc/nginx/sites-available/laravel-app
  sudo nano /etc/nginx/sites-available/laravel-app
    ↓
Update:
  server_name YOUR_PUBLIC_IP;
    ↓
  sudo ln -s /etc/nginx/sites-available/laravel-app /etc/nginx/sites-enabled/
  sudo rm /etc/nginx/sites-enabled/default
  sudo nginx -t
  sudo systemctl restart nginx
    ↓
Nginx configured ✅
```

---

### Step 11: Start Services
```
On Instance:
  sudo systemctl start php8.2-fpm
  sudo systemctl enable php8.2-fpm
  sudo systemctl enable nginx
    ↓
Services running ✅
```

---

### Step 12: Configure Firewall
```
On Instance:
  sudo ufw allow 22/tcp
  sudo ufw allow 80/tcp
  sudo ufw allow 443/tcp
  sudo ufw enable
    ↓
Firewall configured ✅
```

---

## Phase 5: Testing & Verification (5 minutes)

### Step 13: Test Application
```
Local Machine:
  Open browser
  Go to: http://YOUR_PUBLIC_IP
    ↓
Application loads ✅
    ↓
Test:
  • Login page appears
  • Can create account
  • Can create project
  • Can add time entry
  • Database stores data
    ↓
All tests pass ✅
```

---

## Phase 6: Optional - SSL Certificate (10 minutes)

### Step 14: Setup SSL (Optional)
```
On Instance:
  sudo apt install -y certbot python3-certbot-nginx
  sudo certbot certonly --nginx -d YOUR_DOMAIN
    ↓
Update Nginx config with SSL paths
    ↓
  sudo nginx -t
  sudo systemctl restart nginx
    ↓
SSL configured ✅
```

---

## Complete Architecture

```
┌─────────────────────────────────────────��───────────────────┐
│                    Your Local Machine                        │
│  (Browser: http://YOUR_PUBLIC_IP or https://YOUR_DOMAIN)   │
└────────────────────────┬────────────────────────────────────┘
                         │ HTTPS/HTTP
                         ↓
┌─────────────────────────────────────────────────────────────┐
│              Oracle Cloud - Always Free Tier                 │
│                                                               │
│  ┌──────────────────────────────────────────────────────┐   │
│  │  Compute Instance (Ubuntu 22.04 ARM)                │   │
│  │  • Public IP: YOUR_PUBLIC_IP                        │   │
│  │  • 1 OCPU, 6GB RAM (Free)                          │   │
│  │                                                      │   │
│  │  ┌───────────────────────��────────────────────────┐ │   │
│  │  │  Nginx (Web Server)                            │ │   │
│  │  │  Port: 80 (HTTP) / 443 (HTTPS)                │ │   │
│  │  └────────────────────────────────────────────────┘ │   │
│  │                      ↓                               │   │
│  │  ┌────────────────────────────────────────────────┐ │   │
│  │  │  PHP-FPM 8.2                                   │ │   │
│  │  │  Processes Laravel Application                 │ │   │
│  │  └────────────────────────────────────────────────┘ │   │
│  │                      ↓                               │   │
│  │  ┌────────────────────────────────────────────────┐ │   │
│  │  │  /var/www/laravel-app                         │ │   │
│  │  │  • Application Code                           │ │   │
│  │  │  • Storage & Logs                             │ │   │
│  │  │  • Public Assets                              │ │   │
│  │  └────────────────────────────────────────────────┘ │   │
│  └──────────────────────────────────────────────────────┘   │
│                                                               │
│  ┌──────────────────────────────────────────────────────┐   │
│  │  MySQL Database Service                             │   │
│  │  • Hostname: YOUR_DATABASE_HOSTNAME                │   │
│  │  • Port: 3306                                       │   │
│  │  • Storage: 20GB (Free)                            │   │
│  │  • Database: laravel                               │   │
│  │  • User: admin                                      │   │
│  └──────────────────────────────────────────────────────┘   │
│                                                               │
│  ┌──────────────────────────────────────────────────────┐   │
│  │  GitHub Integration (Optional)                      │   │
│  │  • Auto-deploy on git push                         │   │
│  │  • Webhook: http://YOUR_PUBLIC_IP/webhook         │   │
│  └──────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
```

---

## Timeline Summary

| Phase | Task | Time | Status |
|-------|------|------|--------|
| 1 | Create account & infrastructure | 30 min | ⏳ |
| 2 | Install software | 15 min | ⏳ |
| 3 | Deploy application | 20 min | ⏳ |
| 4 | Configure web server | 10 min | ⏳ |
| 5 | Test & verify | 5 min | ⏳ |
| 6 | SSL setup (optional) | 10 min | ⏳ |
| **Total** | **Complete deployment** | **~90 min** | ✅ |

---

## Success Checklist

After deployment, verify:

- [ ] Application accessible at `http://YOUR_PUBLIC_IP`
- [ ] Login page loads correctly
- [ ] Can create user account
- [ ] Can create project
- [ ] Can add time entry
- [ ] Database stores data
- [ ] No errors in Laravel logs
- [ ] Nginx serving requests
- [ ] PHP-FPM running
- [ ] MySQL database connected
- [ ] All services auto-start on reboot

---

## What You Get (Free Forever)

✅ **Compute:** 1 ARM instance (4 OCPUs, 24GB RAM available)
✅ **Database:** 1 MySQL database (20GB)
✅ **Storage:** 10GB object storage
✅ **Bandwidth:** Limited but sufficient
✅ **Uptime:** 24/7/365
✅ **Cost:** $0/month forever

---

## Next Steps After Deployment

1. **Monitor Application**
   - Check logs regularly
   - Monitor system resources
   - Set up alerts (optional)

2. **Maintain Application**
   - Keep dependencies updated
   - Run regular backups
   - Monitor database size

3. **Scale (if needed)**
   - Upgrade compute instance (paid)
   - Add more database storage (paid)
   - Use CDN for static files (optional)

4. **Security**
   - Set up SSL certificate
   - Configure firewall rules
   - Regular security updates

---

## Troubleshooting Quick Links

| Issue | Solution |
|-------|----------|
| Can't SSH | Check security list, key permissions |
| 502 Bad Gateway | Restart PHP-FPM |
| Database error | Check credentials, VCN |
| Blank page | Check Laravel logs |
| Slow app | Clear cache, check resources |
| File upload fails | Check storage permissions |

---

**You're ready to deploy! Follow the phases above step-by-step.** 🚀
