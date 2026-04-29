# 🚀 Clean Installation Guide - MSWDO Analysis

## Complete Fresh Start - Step by Step

This guide will help you run the project from scratch with all fixes applied.

## Prerequisites

Before starting, ensure you have:
- ✅ PHP 8.2+ installed
- ✅ MySQL/MariaDB running
- ✅ Composer installed
- ✅ Node.js and NPM installed (for frontend assets)
- ✅ XAMPP running (Apache and MySQL)

## Step-by-Step Clean Installation

### Step 1: Clear Everything

```bash
# Navigate to project directory
cd c:\xampp8.2\htdocs\MSWDO_Analysis-main

# Clear all Laravel caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Clear compiled files
php artisan clear-compiled

# Clear bootstrap cache
del bootstrap\cache\*.php
```

### Step 2: Configure Environment

1. **Check your `.env` file:**
```bash
# Open .env file and verify these settings:
```

```env
APP_NAME="MSWDO Analysis"
APP_ENV=local
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mswdo_analysis
DB_USERNAME=root
DB_PASSWORD=

# Mail settings (optional for development)
MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

2. **Generate application key if needed:**
```bash
php artisan key:generate
```

### Step 3: Database Setup

#### Option A: Create Fresh Database (Recommended)

```bash
# Open MySQL command line or phpMyAdmin
# Run these SQL commands:
```

```sql
-- Drop existing database if it exists
DROP DATABASE IF EXISTS mswdo_analysis;

-- Create fresh database
CREATE DATABASE mswdo_analysis CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Verify database was created
SHOW DATABASES LIKE 'mswdo_analysis';
```

#### Option B: Using phpMyAdmin
1. Open http://localhost/phpmyadmin
2. Click "Databases" tab
3. If `mswdo_analysis` exists, select it and click "Drop"
4. Create new database named `mswdo_analysis`
5. Set Collation to `utf8mb4_unicode_ci`

### Step 4: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies (if you have package.json)
npm install

# Build frontend assets (if applicable)
npm run build
# OR for development
npm run dev
```

### Step 5: Run Fresh Migrations with Seeders

```bash
# This will:
# 1. Drop all tables
# 2. Run all migrations (with fixes)
# 3. Seed the database with initial data

php artisan migrate:fresh --seed
```

**Expected Output:**
```
Dropping all tables ........................... DONE
Migration table created successfully.
Migrating: 2026_03_01_000000_create_users_table
Migrated:  2026_03_01_000000_create_users_table (XX.XXms)
Migrating: 2026_03_01_000001_create_cache_table
Migrated:  2026_03_01_000001_create_cache_table (XX.XXms)
...
Migrating: 2026_03_01_000003_create_applications_table
Migrated:  2026_03_01_000003_create_applications_table (XX.XXms)
Migrating: 2026_03_01_000005_create_social_welfare_programs_table
Migrated:  2026_03_01_000005_create_social_welfare_programs_table (XX.XXms)
...
Database seeding completed successfully.
```

### Step 6: Verify Database Structure

```bash
php artisan tinker
```

```php
// Check applications table
Schema::getColumnListing('applications');
// Should show: id, user_id, program_type, municipality, barangay, full_name, age, gender, contact_number, status, application_date, year, form_data, stage, completed_at, admin_remarks, aics_subtype, proof_photo_path, id_status, id_ready_at, deleted_at

// Check social_welfare_programs table
Schema::getColumnListing('social_welfare_programs');
// Should show: id, municipality, barangay, program_type, beneficiary_count, year, month, deleted_at

exit;
```

### Step 7: Create Storage Link

```bash
# Create symbolic link for public storage
php artisan storage:link
```

### Step 8: Set Permissions (if on Linux/Mac)

```bash
# Skip this on Windows
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Step 9: Start the Development Server

```bash
# Start Laravel development server
php artisan serve
```

**Expected Output:**
```
INFO  Server running on [http://127.0.0.1:8000].

Press Ctrl+C to stop the server
```

### Step 10: Test the Application

Open your browser and test these URLs:

1. **Homepage:**
   - http://127.0.0.1:8000
   - Should load without errors

2. **Analysis Page:**
   - http://127.0.0.1:8000/analysis
   - Should display program statistics

3. **Mobile API Dashboard:**
   - http://127.0.0.1:8000/api/mobile/dashboard
   - Should return JSON (may require authentication)

4. **Login Page:**
   - http://127.0.0.1:8000/login
   - Should display login form

### Step 11: Check for Errors

```bash
# Monitor the log file in real-time
tail -f storage/logs/laravel.log
```

**What to look for:**
- ✅ No "Column not found" errors
- ✅ No SQL errors
- ✅ No 500 Internal Server errors

## Default Login Credentials

After seeding, you should have these default accounts:

### Super Admin
- **Username:** `superadmin`
- **Password:** Check `database/seeders/SuperAdminSeeder.php`

### Admin (per municipality)
- **Username:** `Admin_[municipality_name]`
- **Password:** Check the seeder file

### Regular User
- Create via registration or check seeder

## Troubleshooting

### Error: "No application encryption key has been specified"
```bash
php artisan key:generate
```

### Error: "SQLSTATE[HY000] [1049] Unknown database"
- Create the database: `CREATE DATABASE mswdo_analysis;`
- Check `.env` file for correct database name

### Error: "SQLSTATE[HY000] [2002] Connection refused"
- Make sure MySQL is running in XAMPP
- Check DB_HOST and DB_PORT in `.env`

### Error: "Class 'X' not found"
```bash
composer dump-autoload
php artisan clear-compiled
```

### Error: "The stream or file could not be opened"
```bash
# On Windows, run as Administrator:
mkdir storage\logs
echo. > storage\logs\laravel.log
```

### Migration fails with "Table already exists"
```bash
# Drop all tables and start fresh
php artisan migrate:fresh --seed
```

### Still seeing "Column not found" errors
```bash
# Verify migrations ran correctly
php artisan migrate:status

# If needed, run specific fix migrations
php artisan migrate --path=database/migrations/2026_04_30_000001_fix_applications_table_structure.php
php artisan migrate --path=database/migrations/2026_04_30_000002_fix_social_welfare_programs_table_structure.php
```

## Quick Commands Reference

```bash
# Clear everything
php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear

# Fresh install
php artisan migrate:fresh --seed

# Start server
php artisan serve

# Check logs
tail -f storage/logs/laravel.log

# Access tinker
php artisan tinker

# List all routes
php artisan route:list

# Check migration status
php artisan migrate:status
```

## Verification Checklist

After installation, verify:

- [ ] Database `mswdo_analysis` exists
- [ ] All migrations ran successfully
- [ ] Seeders completed without errors
- [ ] Storage link created
- [ ] Server starts without errors
- [ ] Homepage loads (http://127.0.0.1:8000)
- [ ] Analysis page loads (http://127.0.0.1:8000/analysis)
- [ ] Login page loads (http://127.0.0.1:8000/login)
- [ ] No errors in `storage/logs/laravel.log`
- [ ] Can login with seeded credentials
- [ ] Mobile API responds (if testing)

## Next Steps

After successful installation:

1. **Test all features:**
   - User registration
   - Login/Logout
   - Application submission
   - Admin dashboard
   - Analysis pages

2. **Configure mail settings** (if needed):
   - Update `.env` with real SMTP settings
   - Test email notifications

3. **Set up production environment** (when ready):
   - Change `APP_ENV=production`
   - Change `APP_DEBUG=false`
   - Use real database credentials
   - Configure proper mail settings

## Success!

If all steps completed without errors, your application is ready to use! 🎉

### Quick Test
```bash
# Open browser to:
http://127.0.0.1:8000

# You should see the homepage without any errors
```

---

**Need Help?**
- Check `storage/logs/laravel.log` for errors
- Review `ALL_FIXES_SUMMARY.md` for database fixes
- See `TROUBLESHOOTING.md` for common issues

**Last Updated:** 2024
**Status:** ✅ Complete installation guide
