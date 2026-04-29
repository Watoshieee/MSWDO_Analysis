# Quick Fix Guide - MSWDO Analysis

## Problems
1. Application was crashing with error: "Column not found: 1054 Unknown column 'created_at'"
2. Analysis page crashing with error: "Column not found: 1054 Unknown column 'program_type'"

## Solution Applied

### 1. Code Fixes (Already Applied)
✅ Fixed MobileApiController.php - Changed `created_at` to `application_date`
✅ Added STATUS constants to Application model
✅ Added error handling and logging
✅ Fixed both table migrations

### 2. Database Migration Fix

#### Option A: Fresh Start (Development Only - WILL DELETE ALL DATA)
```bash
php artisan migrate:fresh --seed
```

#### Option B: Fix Existing Database (Recommended for Production)
```bash
# Fix applications table
php artisan migrate --path=database/migrations/2026_04_30_000001_fix_applications_table_structure.php

# Fix social_welfare_programs table
php artisan migrate --path=database/migrations/2026_04_30_000002_fix_social_welfare_programs_table_structure.php
```

#### Option C: Manual SQL (If migrations don't work)

**For Applications Table:**
```sql
-- Add missing columns
ALTER TABLE applications 
ADD COLUMN IF NOT EXISTS municipality VARCHAR(255) NULL,
ADD COLUMN IF NOT EXISTS barangay VARCHAR(255) NULL,
ADD COLUMN IF NOT EXISTS full_name VARCHAR(255) NULL,
ADD COLUMN IF NOT EXISTS age INT NULL,
ADD COLUMN IF NOT EXISTS gender VARCHAR(255) NULL,
ADD COLUMN IF NOT EXISTS contact_number VARCHAR(255) NULL,
ADD COLUMN IF NOT EXISTS application_date TIMESTAMP NULL,
ADD COLUMN IF NOT EXISTS year VARCHAR(255) NULL,
ADD COLUMN IF NOT EXISTS proof_photo_path VARCHAR(255) NULL,
ADD COLUMN IF NOT EXISTS id_status VARCHAR(255) NULL,
ADD COLUMN IF NOT EXISTS id_ready_at TIMESTAMP NULL;

-- Remove unused columns
ALTER TABLE applications 
DROP COLUMN IF EXISTS created_at,
DROP COLUMN IF EXISTS updated_at;
```

**For Social Welfare Programs Table:**
```sql
-- Drop old columns if they exist
ALTER TABLE social_welfare_programs 
DROP COLUMN IF EXISTS name,
DROP COLUMN IF EXISTS code,
DROP COLUMN IF EXISTS description,
DROP COLUMN IF EXISTS created_at,
DROP COLUMN IF EXISTS updated_at;

-- Add new columns
ALTER TABLE social_welfare_programs 
ADD COLUMN IF NOT EXISTS municipality VARCHAR(255) NOT NULL AFTER id,
ADD COLUMN IF NOT EXISTS barangay VARCHAR(255) NULL AFTER municipality,
ADD COLUMN IF NOT EXISTS program_type VARCHAR(255) NOT NULL AFTER barangay,
ADD COLUMN IF NOT EXISTS beneficiary_count INT DEFAULT 0 AFTER program_type,
ADD COLUMN IF NOT EXISTS year INT NOT NULL AFTER beneficiary_count,
ADD COLUMN IF NOT EXISTS month INT NULL AFTER year;

-- Add indexes
CREATE INDEX IF NOT EXISTS social_welfare_programs_municipality_year_index 
ON social_welfare_programs(municipality, year);

CREATE INDEX IF NOT EXISTS social_welfare_programs_program_type_index 
ON social_welfare_programs(program_type);
```

## Verify the Fix

### 1. Check Table Structures
```bash
php artisan tinker
```
```php
// Check applications table
Schema::getColumnListing('applications');

// Check social_welfare_programs table
Schema::getColumnListing('social_welfare_programs');

exit;
```

### 2. Test the APIs
- Mobile API Dashboard: `/api/mobile/dashboard`
- Applications List: `/api/mobile/applications`
- Analysis Page: `/analysis`

All should load without errors.

### 3. Check Logs
```bash
# View the last 50 lines of the log
tail -n 50 storage/logs/laravel.log
```
- Should see no more "Column not found" errors

## Files Modified

1. ✅ `app/Http/Controllers/Api/MobileApiController.php`
2. ✅ `app/Models/Application.php`
3. ✅ `database/migrations/2026_03_01_000003_create_applications_table.php`
4. ✅ `database/migrations/2026_03_01_000005_create_social_welfare_programs_table.php`
5. ✅ `database/migrations/2026_04_30_000001_fix_applications_table_structure.php` (NEW)
6. ✅ `database/migrations/2026_04_30_000002_fix_social_welfare_programs_table_structure.php` (NEW)

## Important Notes

### Applications Table
- The `applications` table does NOT use Laravel's automatic timestamps
- It uses `application_date` instead of `created_at`
- The Application model has `public $timestamps = false;`
- This is intentional and correct for this project

### Social Welfare Programs Table
- The `social_welfare_programs` table does NOT use automatic timestamps
- The model has `const CREATED_AT = null; const UPDATED_AT = null;`
- Stores program statistics by municipality, program type, and year
- Supports optional month-level granularity

## Need Help?

If you still see errors:
1. Clear cache: `php artisan cache:clear`
2. Clear config: `php artisan config:clear`
3. Check database connection in `.env`
4. Verify MySQL is running
5. Check the error logs in `storage/logs/laravel.log`

## Success Indicators

✅ No "Column not found" errors in logs
✅ Mobile API dashboard loads successfully
✅ Applications list displays correctly
✅ Analysis page loads without errors
✅ Program statistics display properly
✅ Can create new applications
✅ Can view application details
✅ Year filters work on analysis page
✅ Charts render correctly
