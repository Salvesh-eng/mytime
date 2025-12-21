# Oracle Cloud Deployment - Visual Diagrams & Flowcharts

## 1. Deployment Process Flowchart

```
START
  ↓
┌─────────────────────────────────────┐
│ Create Oracle Cloud Account         │
│ • Email & Password                  │
│ • Verify Phone                      │
│ • Add Payment Method                │
│ • Choose Region                     │
└─────────────────────────────────────┘
  ↓
┌─────────────────────────────────────┐
│ Create Compute Instance             │
│ • Ubuntu 22.04 LTS                  │
│ • ARM (Ampere) Shape                │
│ • 1 OCPU, 6GB RAM                   │
│ • Assign Public IP                  │
│ • Download SSH Key                  │
└─────────────────────────────────────┘
  ↓
┌────────────────────���────────────────┐
│ Create MySQL Database               │
│ • Admin User: admin                 │
│ • Strong Password                   │
│ • Same VCN as Instance              │
│ • 20GB Storage                      │
└─────────────────────────────────────┘
  ↓
┌─────────────────────────────────────┐
│ SSH into Instance                   │
│ • Set Key Permissions               │
│ • Connect via SSH                   │
│ • Verify Connection                 │
└─────────────────────────────────────┘
  ↓
┌─────────────────────────────────────┐
│ Install Software                    │
│ • PHP 8.2 & Extensions              │
│ • Composer                          │
│ • Node.js & npm                     │
│ • Nginx                             │
│ • Git                               │
│ • MySQL Client                      │
└─────────────────────────────────────┘
  ↓
┌─────────────────────────────────────┐
│ Deploy Application                  │
│ • Clone Repository                  │
│ • Install Dependencies              │
│ • Build Frontend                    │
│ • Configure .env                    │
│ • Run Migrations                    │
└─────────────────────────────────────┘
  ↓
┌─────────────────────────────────────┐
│ Configure Web Server                │
│ • Setup Nginx                       │
│ • Configure PHP-FPM                 │
│ • Set Permissions                   │
│ • Start Services                    │
└─────────────────────────────────────┘
  ↓
┌──────���──────────────────────────────┐
│ Configure Firewall                  │
│ • Allow SSH (22)                    │
│ • Allow HTTP (80)                   │
│ • Allow HTTPS (443)                 │
│ • Enable UFW                        │
└─────────────────────────────────────┘
  ↓
┌─────────────────────────────────────┐
│ Test Application                    │
│ • Open Browser                      │
│ • Navigate to IP                    │
│ • Test Login                        │
│ • Test Features                     │
│ • Check Logs                        │
└─────────────────────────────────────┘
  ↓
┌─────────────────────────────────────┐
│ Setup SSL (Optional)                │
│ • Install Certbot                   │
│ • Get Certificate                   │
│ • Configure Nginx                   │
│ • Enable Auto-Renewal               │
└─────���───────────────────────────────┘
  ↓
SUCCESS ✅
Application Running 24/7 on Oracle Cloud
```

---

## 2. Infrastructure Architecture

```
┌──────────────────────────────────────────────────────────────┐
│                     ORACLE CLOUD (Always Free)               │
│                                                               │
│  ┌────────────────────────────────────────────────────────┐  │
│  │         COMPUTE INSTANCE (Ubuntu 22.04 ARM)            │  │
│  │         Public IP: YOUR_PUBLIC_IP                      │  │
│  │         1 OCPU, 6GB RAM (Free)                         │  │
│  │                                                         │  │
│  │  ┌──────────────────────────────────────────────────┐  │  │
│  │  │  NGINX (Port 80/443)                             │  │  │
│  │  │  • Reverse Proxy                                 │  │  │
│  │  │  • SSL Termination                               │  │  │
│  │  │  • Static File Serving                           │  │  │
│  │  └──────────────────────────────────────────────────┘  │  │
│  │                      ↓                                  │  │
│  │  ┌──────────────────────────────────────────────────┐  │  │
│  │  │  PHP-FPM 8.2 (Unix Socket)                       │  │  │
│  │  │  • Process Laravel Requests                      │  │  │
│  │  │  • Execute PHP Code                              │  │  │
│  │  │  • Handle Business Logic                         │  │  │
│  │  └──────────────────────────────────────────────────┘  │  │
│  │                      ↓                                  │  │
│  │  ┌──────────────────────────────────────────────────┐  │  │
│  │  │  LARAVEL APPLICATION                            │  │  │
│  │  │  /var/www/laravel-app/                          │  │  │
│  │  │  • Application Code                             │  │  │
│  │  │  • Routes & Controllers                         │  │  │
│  │  │  • Models & Business Logic                      │  │  │
│  │  │  • Storage & Logs                               │  │  │
│  │  │  • Public Assets (CSS, JS, Images)              │  │  │
│  │  └──────────────────────────────────────────────────┘  │  │
│  │                      ↓                                  │  │
│  │  ┌──────────────────────────────────────────────────┐  │  │
│  │  │  DATABASE CONNECTION (TCP 3306)                 │  │  │
│  │  │  • Query Execution                              │  │  │
│  │  │  • Data Persistence                             │  │  │
│  │  └────────────────────────────────────��─────────────┘  │  │
│  └────────────────────────────────────────────────────────┘  │
│                                                               │
│  ┌────────────────────────────────────────────────────────┐  │
│  │         MYSQL DATABASE SERVICE                         │  │
│  │         Hostname: YOUR_DATABASE_HOSTNAME              │  │
│  │         Port: 3306                                     │  │
│  │         Storage: 20GB (Free)                           │  │
│  │                                                         │  │
│  │  ┌──────────────────────────────────────────────────┐  │  │
│  │  │  Database: laravel                              │  │  │
│  │  │  • Users Table                                  │  │  │
│  │  │  • Projects Table                               │  │  │
│  │  │  • Time Entries Table                           │  ��  │
│  │  │  • Financial Data Tables                        │  │  │
│  │  │  • All Application Data                         │  │  │
│  │  └──────────────────────────────────────────────────┘  │  │
│  └────────────────────────────────────────────────────────┘  │
│                                                               │
│  ┌────────────────────────────────────────────────────────┐  │
│  │         SECURITY & NETWORKING                         │  │
│  │  • VCN (Virtual Cloud Network)                        │  │
│  │  • Security Lists (Firewall)                          │  │
│  │  • UFW (Host Firewall)                                │  │
│  │  • SSL/TLS Certificates                               │  │
│  └────────────────────────────────────────────────────────┘  │
└──────────────────────────────────────────────────────────────┘
                           ↑
                    Internet / Users
```

---

## 3. Request Flow Diagram

```
USER BROWSER
    ↓
    │ HTTP/HTTPS Request
    │ GET /projects
    ↓
INTERNET
    ↓
ORACLE CLOUD FIREWALL
    ↓
SECURITY LIST (Port 80/443 Open)
    ↓
NGINX (Reverse Proxy)
    ├─ Check if static file
    │  ├─ YES → Serve directly (CSS, JS, Images)
    │  └─ NO → Forward to PHP-FPM
    ↓
PHP-FPM 8.2
    ├─ Parse Request
    ├─ Load Laravel Framework
    ├─ Route to Controller
    ├─ Execute Business Logic
    ├─ Query Database
    ↓
MYSQL DATABASE
    ├─ Execute Query
    ├─ Return Results
    ↓
PHP-FPM (continued)
    ├─ Process Data
    ├─ Render Blade Template
    ├─ Generate HTML Response
    ↓
NGINX
    ├─ Add Headers
    ├─ Compress Response (Gzip)
    ├─ Send to Client
    ↓
USER BROWSER
    ├─ Receive HTML
    ├─ Parse & Render
    ├─ Load CSS/JS
    ├─ Display Page
    ↓
USER SEES APPLICATION ✅
```

---

## 4. File Structure on Server

```
/var/www/laravel-app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   ├── User/
│   │   │   └── ...
│   │   └── Middleware/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Project.php
│   │   ├── TimeEntry.php
│   │   └── ...
│   └── ...
├── bootstrap/
│   ├── app.php
│   └── cache/
├── config/
│   ├── app.php
│   ├── database.php
│   ├── auth.php
│   └── ...
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── public/
│   ├── index.php (Entry Point)
│   ├── css/
│   ├── js/
│   ├── pictures/
│   └── ...
├── resources/
│   ├── views/
│   │   ├── admin/
│   │   ├── user/
│   │   ├── auth/
│   │   └── ...
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php
│   └── console.php
├── storage/
│   ├── app/
│   ├── framework/
│   │   ├── cache/
│   │   ├── sessions/
│   │   └── views/
│   └── logs/
│       └── laravel.log (Application Logs)
├── tests/
├── vendor/ (Composer Dependencies)
├── node_modules/ (NPM Dependencies)
├── .env (Environment Configuration)
├── .env.example
├── composer.json
├── package.json
├── artisan (Laravel CLI)
└── ...
```

---

## 5. Database Schema Overview

```
LARAVEL DATABASE
├── users
│   ├── id (PK)
│   ├── name
│   ├── email
│   ├── password
│   ├── role (admin/user)
│   └── ...
├── projects
│   ├── id (PK)
│   ├── user_id (FK)
│   ├── name
│   ├── description
│   ├── status
│   ├── budget
│   └── ...
├── time_entries
│   ├── id (PK)
│   ├── user_id (FK)
│   ├── project_id (FK)
│   ├── hours
│   ├── date
│   └── ...
├── personal_finances
│   ├── personal_incomes
│   ├── personal_expenses
│   ├── personal_loans
│   ├── financial_transactions
│   └── ...
├── notifications
│   ├── id (PK)
│   ├── user_id (FK)
│   ├── message
│   └── ...
└── ... (Other tables)
```

---

## 6. Deployment Timeline

```
TIME    TASK                          DURATION    CUMULATIVE
────────────────────────────────────────────────────────────
0:00    Start                         -           0:00
0:05    Create Oracle Account         5 min       0:05
0:10    Create Compute Instance       5 min       0:10
0:15    Create MySQL Database         5 min       0:15
0:20    SSH Connection                5 min       0:20
0:30    Install Software              10 min      0:30
0:35    Clone Application             5 min       0:35
0:40    Configure Environment         5 min       0:40
0:45    Run Migrations                5 min       0:45
0:50    Configure Nginx               5 min       0:50
0:55    Start Services                5 min       0:55
1:00    Test Application              5 min       1:00
1:10    Setup SSL (Optional)          10 min      1:10
────────────────────────────────────────────────────────────
TOTAL DEPLOYMENT TIME: ~60 minutes (without SSL)
```

---

## 7. Service Dependencies

```
APPLICATION STARTUP SEQUENCE

1. System Boot
   ↓
2. UFW Firewall Starts
   ├─ Allow SSH (22)
   ├─ Allow HTTP (80)
   └─ Allow HTTPS (443)
   ↓
3. Nginx Starts
   ├─ Load Configuration
   ├─ Bind to Port 80/443
   └─ Ready to Accept Connections
   ↓
4. PHP-FPM Starts
   ├─ Create Unix Socket
   ├─ Load PHP Configuration
   └─ Ready to Process Requests
   ↓
5. MySQL Database (Already Running)
   ├─ Accept Connections
   └─ Ready for Queries
   ↓
6. Application Ready
   ├─ Nginx Routes Requests
   ├─ PHP-FPM Processes Requests
   ├─ Laravel Handles Business Logic
   └─ MySQL Stores Data
   ↓
✅ APPLICATION ONLINE
```

---

## 8. Scaling Path (Future)

```
CURRENT STATE (Free Tier)
├── 1 ARM Compute Instance
│   └── 1 OCPU, 6GB RAM
├── 1 MySQL Database
│   └── 20GB Storage
└── Cost: $0/month

                    ↓ (If you need more power)

SCALING OPTIONS (Paid)
├── Upgrade Compute
│   ├── More OCPUs
│   ├── More RAM
│   └── Better Performance
├── Upgrade Database
│   ├── More Storage
│   ├── Better Performance
│   └── High Availability
├── Add Load Balancer
│   └── Distribute Traffic
├── Add CDN
│   └── Faster Static Content
└── Cost: Depends on upgrades
```

---

## 9. Backup & Recovery Flow

```
BACKUP PROCESS
├── Database Backup
│   ├── mysqldump Command
│   ├── Export to SQL File
│   └── Store Securely
├── Application Backup
│   ├── tar Command
│   ├── Compress Files
│   └── Store Securely
└── Schedule (Weekly/Monthly)

                    ↓

RECOVERY PROCESS (If Needed)
├── Restore Database
│   ├── mysql Command
│   ├── Import SQL File
│   └── Verify Data
├── Restore Application
│   ├── Extract tar File
���   ├── Restore Permissions
│   └── Verify Files
└── Restart Services
```

---

## 10. Security Layers

```
SECURITY ARCHITECTURE

Layer 1: Internet
    ↓
Layer 2: Oracle Cloud Firewall
    ├─ DDoS Protection
    └─ Network Filtering
    ↓
Layer 3: Security List (VCN)
    ├─ Port 22 (SSH) - Restricted
    ├─ Port 80 (HTTP) - Open
    └─ Port 443 (HTTPS) - Open
    ↓
Layer 4: UFW (Host Firewall)
    ├─ Additional Port Filtering
    └─ Connection Tracking
    ↓
Layer 5: Nginx
    ├─ SSL/TLS Encryption
    ├─ Security Headers
    └─ Request Validation
    ↓
Layer 6: PHP-FPM
    ├─ Input Validation
    ├─ SQL Injection Prevention
    └─ CSRF Protection
    ↓
Layer 7: Laravel Application
    ├─ Authentication
    ├─ Authorization
    ├─ Encryption
    └─ Logging
    ↓
Layer 8: MySQL Database
    ├─ User Permissions
    ├─ Data Encryption
    └─ Access Control
```

---

## 11. Monitoring Dashboard (What to Watch)

```
SYSTEM METRICS TO MONITOR

CPU Usage
├─ Normal: < 50%
├─ Warning: 50-80%
└─ Critical: > 80%

Memory Usage
├─ Normal: < 60%
├─ Warning: 60-80%
└─ Critical: > 80%

Disk Usage
├─ Normal: < 70%
├─ Warning: 70-85%
└─ Critical: > 85%

Database Size
├─ Normal: < 15GB
├─ Warning: 15-18GB
└─ Critical: > 18GB

Application Errors
├─ Normal: 0 errors
├─ Warning: 1-5 errors/hour
└─ Critical: > 5 errors/hour

Response Time
├─ Normal: < 500ms
├─ Warning: 500-1000ms
└─ Critical: > 1000ms
```

---

## 12. Troubleshooting Decision Tree

```
APPLICATION NOT WORKING?
    ↓
Can you SSH into instance?
├─ NO → Check security list, key permissions
└─ YES ↓
    ↓
Is Nginx running?
├─ NO → sudo systemctl start nginx
└─ YES ↓
    ↓
Is PHP-FPM running?
├─ NO → sudo systemctl start php8.2-fpm
└─ YES ↓
    ↓
Can you access application?
├─ NO → Check Nginx logs
└─ YES ↓
    ↓
Is database connected?
├─ NO → Check DB credentials, VCN
└─ YES ↓
    ↓
Are there errors?
├─ YES → Check Laravel logs
└─ NO ✅ APPLICATION WORKING
```

---

**Use these diagrams as visual references during deployment!** 📊
