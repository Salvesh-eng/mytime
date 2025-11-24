# Mytime - Time Tracking System Setup

## Overview
Mytime is a comprehensive time tracking application with admin and user management features. It allows admins to manage users and review time entries, while users can log their work hours.

## Features

### Admin Features
- **Dashboard**: View statistics (total users, time entries, hours logged today)
- **User Management**: Create, edit, deactivate, and reset user passwords
- **Time Entry Management**: Review, approve, or reject time entries with comments
- **Reports**: Generate reports by user and date, export to CSV

### User Features
- **Dashboard**: View weekly hours and recent entries
- **Add Time Entry**: Log work hours with start/end times and descriptions
- **View Logs**: Filter and view all personal time entries
- **Profile Settings**: Update name and change password

## Installation

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm
- SQLite or MySQL

### Setup Steps

1. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

4. **Build Assets**
   ```bash
   npm run build
   ```

5. **Start Development Server**
   ```bash
   php artisan serve
   ```

   The application will be available at `http://localhost:8000`

## Default Credentials

After seeding, you can login with:

**Admin Account:**
- Email: `admin@mytime.local`
- Password: `password`

**Test User Account:**
- Email: `user@mytime.local`
- Password: `password`

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── Admin/
│   │   │   ├── DashboardController.php
│   │   │   ├── UserController.php
│   │   │   ├── TimeEntryController.php
│   │   │   └── ReportController.php
│   │   └── User/
│   │       ├── DashboardController.php
│   │       ├── TimeEntryController.php
│   │       └── ProfileController.php
│   └── Middleware/
│       └── AdminMiddleware.php
├── Models/
│   ├── User.php
│   └── TimeEntry.php
└── Providers/

database/
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php
│   ├── 2024_01_01_000001_add_role_and_status_to_users.php
│   └── 2024_01_01_000002_create_time_entries_table.php
├── seeders/
│   ├── DatabaseSeeder.php
│   └── AdminSeeder.php
└── factories/

resources/
└── views/
    ├── layouts/
    │   └── app.blade.php
    ├── auth/
    │   └── login.blade.php
    ├── admin/
    │   ├── dashboard.blade.php
    │   ├── users/
    │   │   ├── index.blade.php
    │   │   ├── create.blade.php
    │   │   └── edit.blade.php
    │   ├── time-entries/
    │   │   ├── index.blade.php
    │   │   └── show.blade.php
    │   └── reports/
    │       └── index.blade.php
    └── user/
        ├── dashboard.blade.php
        ├── time-entries/
        │   ├── create.blade.php
        │   ├── index.blade.php
        │   ├── show.blade.php
        │   └── edit.blade.php
        └── profile/
            └── show.blade.php

routes/
└── web.php
```

## Routes

### Public Routes
- `GET /login` - Login page
- `POST /login` - Login submission

### User Routes (Authenticated)
- `GET /dashboard` - User dashboard
- `GET /add-time` - Add time entry form
- `POST /add-time` - Store time entry
- `GET /my-logs` - View all time entries
- `GET /my-logs/{entry}` - View time entry details
- `GET /my-logs/{entry}/edit` - Edit time entry form
- `PUT /my-logs/{entry}` - Update time entry
- `DELETE /my-logs/{entry}` - Delete time entry
- `GET /profile` - Profile settings
- `PUT /profile` - Update profile
- `GET /profile/change-password` - Change password form
- `PUT /profile/change-password` - Update password

### Admin Routes (Authenticated + Admin)
- `GET /admin/dashboard` - Admin dashboard
- `GET /admin/users` - User list
- `GET /admin/users/create` - Create user form
- `POST /admin/users` - Store user
- `GET /admin/users/{user}/edit` - Edit user form
- `PUT /admin/users/{user}` - Update user
- `POST /admin/users/{user}/deactivate` - Deactivate user
- `POST /admin/users/{user}/activate` - Activate user
- `POST /admin/users/{user}/reset-password` - Reset user password
- `GET /admin/time-entries` - Time entries list
- `GET /admin/time-entries/{entry}` - Time entry details
- `POST /admin/time-entries/{entry}/approve` - Approve entry
- `POST /admin/time-entries/{entry}/reject` - Reject entry
- `POST /admin/time-entries/{entry}/comment` - Add comment
- `GET /admin/reports` - Reports page
- `GET /admin/reports/export-csv` - Export CSV

## Database Schema

### Users Table
- `id` - Primary key
- `name` - User name
- `email` - Email address (unique)
- `password` - Hashed password
- `role` - 'user' or 'admin'
- `status` - 'active' or 'inactive'
- `email_verified_at` - Email verification timestamp
- `remember_token` - Remember me token
- `created_at` - Creation timestamp
- `updated_at` - Update timestamp

### Time Entries Table
- `id` - Primary key
- `user_id` - Foreign key to users
- `entry_date` - Date of the entry
- `start_time` - Start time
- `end_time` - End time
- `hours` - Calculated hours
- `description` - Optional description
- `status` - 'pending', 'approved', or 'rejected'
- `admin_comment` - Optional admin comment
- `created_at` - Creation timestamp
- `updated_at` - Update timestamp

## Key Features

### Authentication
- Email and password-based login
- Role-based access control (User/Admin)
- Session management

### User Management
- Create users with auto-generated or custom passwords
- Edit user details and roles
- Activate/deactivate users
- Reset user passwords

### Time Tracking
- Log work hours with start and end times
- Auto-calculate hours between times
- Add descriptions to entries
- Edit pending entries
- Delete pending entries

### Admin Review
- View all time entries
- Filter by user, date, and status
- Approve or reject entries
- Add comments to entries

### Reporting
- Hours by user report
- Hours by date report
- CSV export functionality
- Date range filtering

## Development

### Running Tests
```bash
php artisan test
```

### Code Quality
```bash
php artisan pint
```

### Database Migrations
```bash
# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Reset database
php artisan migrate:reset
```

## Troubleshooting

### Database Connection Issues
- Ensure `.env` file has correct database configuration
- For SQLite, ensure `database/database.sqlite` exists
- Run `php artisan migrate` to create tables

### Permission Issues
- Ensure `storage/` and `bootstrap/cache/` directories are writable
- Run `chmod -R 775 storage bootstrap/cache`

### Session Issues
- Clear session cache: `php artisan session:table` then `php artisan migrate`
- Clear application cache: `php artisan cache:clear`

## Support

For issues or questions, please refer to the Laravel documentation at https://laravel.com/docs
