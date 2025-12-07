# Database Connection Debugging Guide

## Issue: Data Not Saving to Database

### Step 1: Check Browser Console
1. Open your browser (Chrome, Firefox, Edge)
2. Press `F12` or `Ctrl+Shift+I` to open Developer Tools
3. Go to the **Console** tab
4. Try adding a savings goal or donation
5. Look for any error messages in the console
6. Check the **Network** tab to see if the API request is being sent

### Step 2: Check if Migrations Have Run
Run this command in your terminal:
```bash
cd d:\Mytime
php artisan migrate:status
```

If you see "Pending" migrations, run:
```bash
php artisan migrate
```

### Step 3: Verify Database Tables Exist
Run this command:
```bash
php artisan tinker
```

Then in the tinker shell:
```php
Schema::hasTable('financial_budgets')
Schema::hasTable('financial_transactions')
```

Both should return `true`.

### Step 4: Check API Routes
Run:
```bash
php artisan route:list | grep financial
```

You should see routes like:
- `POST /admin/api/financial/savings-goal`
- `GET /admin/api/financial/savings-goals`
- `POST /admin/api/financial/donation`
- `GET /admin/api/financial/donations`

### Step 5: Test API Directly
Use Postman or curl to test the API:

```bash
curl -X POST http://localhost:8000/admin/api/financial/savings-goal \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: YOUR_CSRF_TOKEN" \
  -d '{
    "goal_name": "Test Goal",
    "category": "emergency",
    "target_amount": 1000,
    "current_amount": 500,
    "target_date": "2025-12-31",
    "monthly_contribution": 100,
    "notes": "Test"
  }'
```

### Step 6: Check Laravel Logs
Look at the log file:
```bash
tail -f d:\Mytime\storage\logs\laravel.log
```

Watch for any errors when you try to save data.

### Step 7: Verify CSRF Token
In the browser console, run:
```javascript
document.querySelector('meta[name="csrf-token"]').content
```

This should return a long token string. If it's empty, the CSRF token is not being set.

### Step 8: Check Database File (SQLite)
If using SQLite, the database file should be at:
```
d:\Mytime\database\database.sqlite
```

Make sure this file exists and is readable/writable.

### Step 9: Run Database Check Script
```bash
cd d:\Mytime
php database_check.php
```

This will show:
- Database connection status
- Which tables exist
- Column names in each table

## Common Issues and Solutions

### Issue: "CSRF token mismatch"
**Solution:** Make sure the CSRF token meta tag is in the layout:
```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

### Issue: "Table not found"
**Solution:** Run migrations:
```bash
php artisan migrate
```

### Issue: "API route not found"
**Solution:** Clear route cache:
```bash
php artisan route:clear
php artisan cache:clear
```

### Issue: "500 Internal Server Error"
**Solution:** Check Laravel logs:
```bash
tail -f storage/logs/laravel.log
```

### Issue: "Connection refused"
**Solution:** Make sure Laravel is running:
```bash
php artisan serve
```

## Quick Checklist

- [ ] Database migrations have been run (`php artisan migrate`)
- [ ] `financial_budgets` table exists
- [ ] `financial_transactions` table exists
- [ ] CSRF token is present in HTML meta tag
- [ ] API routes are registered in `routes/web.php`
- [ ] FinancialBudget and FinancialTransaction models exist
- [ ] Laravel is running (`php artisan serve`)
- [ ] Browser console shows no errors
- [ ] Network tab shows successful API responses (200 status)

## Testing the Fix

1. Open browser Developer Tools (F12)
2. Go to Console tab
3. Add a savings goal
4. Check console for logs like:
   - "CSRF Token: [token]"
   - "Saving goal: {...}"
   - "Response status: 200"
   - "Response data: {...}"
5. If successful, you should see the goal in the table
6. Refresh the page - the goal should still be there

If the goal disappears after refresh, the data is not being saved to the database.
