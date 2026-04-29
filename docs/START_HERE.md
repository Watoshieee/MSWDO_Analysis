# 🚀 START HERE - MSWDO Analysis Setup

## Quick Start (Easiest Way)

### Option 1: Use the Automated Script (Recommended)

**Double-click:** `quick-start.bat`

This will:
1. Clear all caches
2. Let you choose installation type
3. Run migrations
4. Start the server automatically

### Option 2: Manual Commands

Open Command Prompt in this folder and run:

```bash
# For Fresh Install (Deletes all data)
php artisan migrate:fresh --seed
php artisan serve

# For Fixing Existing Database (Keeps data)
php artisan migrate --path=database/migrations/2026_04_30_000001_fix_applications_table_structure.php
php artisan migrate --path=database/migrations/2026_04_30_000002_fix_social_welfare_programs_table_structure.php
php artisan serve
```

Then open: http://127.0.0.1:8000

## Before You Start

### 1. Make Sure XAMPP is Running
- ✅ Apache (green)
- ✅ MySQL (green)

### 2. Create Database (First Time Only)

Open phpMyAdmin: http://localhost/phpmyadmin

Run this SQL:
```sql
DROP DATABASE IF EXISTS mswdo_analysis;
CREATE DATABASE mswdo_analysis CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. Check .env File

Open `.env` file and verify:
```
DB_DATABASE=mswdo_analysis
DB_USERNAME=root
DB_PASSWORD=
```

## Installation Methods

### Method 1: Fresh Install (Recommended for First Time)

**What it does:** Deletes everything and creates fresh database

```bash
# Clear caches
php artisan cache:clear
php artisan config:clear

# Fresh install
php artisan migrate:fresh --seed

# Start server
php artisan serve
```

### Method 2: Fix Existing Database (If you have data)

**What it does:** Adds missing columns without deleting data

```bash
# Fix applications table
php artisan migrate --path=database/migrations/2026_04_30_000001_fix_applications_table_structure.php

# Fix social_welfare_programs table
php artisan migrate --path=database/migrations/2026_04_30_000002_fix_social_welfare_programs_table_structure.php

# Start server
php artisan serve
```

## Verify Installation

After running migrations, check:

1. **No errors in console** ✅
2. **Open:** http://127.0.0.1:8000 ✅
3. **Check logs:** `storage/logs/laravel.log` ✅

## Common Issues

### "No application encryption key"
```bash
php artisan key:generate
```

### "Unknown database 'mswdo_analysis'"
- Create the database in phpMyAdmin
- Check .env file

### "Connection refused"
- Start MySQL in XAMPP
- Check if port 3306 is correct

### "Column not found" errors
- Run the fix migrations (Method 2 above)
- Or run fresh install (Method 1 above)

## File Structure

```
MSWDO_Analysis-main/
├── quick-start.bat          ← Double-click this!
├── clean-install.bat        ← Full installation script
├── START_HERE.md           ← This file
├── CLEAN_INSTALLATION_GUIDE.md  ← Detailed guide
├── ALL_FIXES_SUMMARY.md    ← What was fixed
└── ...
```

## Documentation

- **Quick Start:** This file
- **Detailed Guide:** `CLEAN_INSTALLATION_GUIDE.md`
- **All Fixes:** `ALL_FIXES_SUMMARY.md`
- **Troubleshooting:** `QUICK_FIX_GUIDE.md`

## Default URLs

After starting the server:

- **Homepage:** http://127.0.0.1:8000
- **Login:** http://127.0.0.1:8000/login
- **Analysis:** http://127.0.0.1:8000/analysis
- **Admin:** http://127.0.0.1:8000/admin
- **Super Admin:** http://127.0.0.1:8000/superadmin

## Need Help?

1. Check `storage/logs/laravel.log` for errors
2. Read `CLEAN_INSTALLATION_GUIDE.md` for detailed steps
3. See `QUICK_FIX_GUIDE.md` for troubleshooting

## Success Checklist

- [ ] XAMPP running (Apache + MySQL)
- [ ] Database created
- [ ] .env configured
- [ ] Migrations ran successfully
- [ ] Server started
- [ ] Homepage loads without errors
- [ ] No errors in logs

## Quick Commands

```bash
# Clear everything
php artisan cache:clear && php artisan config:clear

# Fresh install
php artisan migrate:fresh --seed

# Start server
php artisan serve

# Check logs
type storage\logs\laravel.log

# Check database
php artisan tinker
Schema::getColumnListing('applications');
exit;
```

---

**Ready?** Double-click `quick-start.bat` or run the commands above! 🚀

**Last Updated:** 2024
