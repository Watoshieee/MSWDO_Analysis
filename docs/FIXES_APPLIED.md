# Fixes Applied to MSWDO Analysis Project

## Date: 2024

## Issues Fixed

### 1. Database Column Error - Missing `created_at` in Applications Table

**Error:**
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'created_at' in 'order clause'
```

**Root Cause:**
- The `applications` table does not have `created_at` and `updated_at` columns
- The Application model has `public $timestamps = false;` which disables Laravel's automatic timestamp management
- The table uses `application_date` instead of `created_at`
- The MobileApiController was trying to order by `created_at` which doesn't exist
- The migration file had `$table->timestamps()` which was creating columns that weren't being used

### 2. Database Column Error - Missing `program_type` in Social Welfare Programs Table

**Error:**
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'program_type' in 'field list'
```

**Root Cause:**
- The `social_welfare_programs` table migration was creating wrong columns (`name`, `code`, `description`)
- The SocialWelfareProgram model expects different columns (`municipality`, `barangay`, `program_type`, `beneficiary_count`, `year`, `month`)
- The AnalysisController was trying to query `program_type` which doesn't exist in the old structure
- Complete mismatch between migration and model expectations

**Files Modified:**

#### 1. `app/Http/Controllers/Api/MobileApiController.php`
- **Line 319 (dashboard method):** Changed `orderBy('created_at', 'desc')` to `orderBy('application_date', 'desc')`
- **Line 393 (applications method):** Changed `orderBy('created_at', 'desc')` to `orderBy('application_date', 'desc')`
- Added error handling with try-catch blocks to prevent crashes
- Added logging for debugging purposes

#### 2. `app/Models/Application.php`
- Added missing STATUS constants:
  - `const STATUS_PENDING = 'pending';`
  - `const STATUS_APPROVED = 'approved';`
  - `const STATUS_REJECTED = 'rejected';`
- These constants were being used in the controller but were not defined in the model

#### 3. `database/migrations/2026_03_01_000003_create_applications_table.php`
- **Removed:** `$table->timestamps()` - This was creating unused `created_at` and `updated_at` columns
- **Added all missing columns:**
  - `municipality` (string, nullable)
  - `barangay` (string, nullable)
  - `full_name` (string, nullable)
  - `age` (integer, nullable)
  - `gender` (string, nullable)
  - `contact_number` (string, nullable)
  - `application_date` (timestamp, nullable) - Used instead of `created_at`
  - `year` (string, nullable)
  - `proof_photo_path` (string, nullable)
  - `id_status` (string, nullable)
  - `id_ready_at` (timestamp, nullable)
- Added comment explaining why timestamps are not used

#### 4. `database/migrations/2026_03_26_190644_add_proof_photo_to_applications_table.php`
- Updated empty migration with comment explaining columns are now in main migration

#### 5. `database/migrations/2026_03_01_000005_create_social_welfare_programs_table.php`
- **Removed:** `name`, `code`, `description`, `created_at`, `updated_at` columns
- **Added correct columns:**
  - `municipality` (string)
  - `barangay` (string, nullable)
  - `program_type` (string)
  - `beneficiary_count` (integer, default 0)
  - `year` (integer)
  - `month` (integer, nullable)
- Added indexes for better query performance
- Removed timestamps (model doesn't use them)

#### 6. `database/migrations/2026_04_20_200012_add_month_to_social_welfare_programs_table.php`
- Updated to be a no-op since month column is now in main migration

#### 7. `database/migrations/2026_04_30_000002_fix_social_welfare_programs_table_structure.php` (NEW)
- Created helper migration to fix existing databases
- Safely migrates from old structure to new structure
- Preserves existing data

## Migration Instructions

See `DATABASE_MIGRATION_GUIDE.md` for applications table and `SOCIAL_WELFARE_PROGRAMS_FIX.md` for social welfare programs table.

**Quick Start (Development):**
```bash
php artisan migrate:fresh --seed
```

**Production (Run both migrations):**
```bash
# Fix applications table
php artisan migrate --path=database/migrations/2026_04_30_000001_fix_applications_table_structure.php

# Fix social_welfare_programs table
php artisan migrate --path=database/migrations/2026_04_30_000002_fix_social_welfare_programs_table_structure.php
```

## Testing Recommendations

After applying these fixes, test the following endpoints:

1. **Mobile API Dashboard** (`/api/mobile/dashboard`)
   - Should now load without errors
   - Recent applications should be ordered by application_date

2. **Mobile API Applications List** (`/api/mobile/applications`)
   - Should fetch all applications without errors
   - Applications should be ordered by application_date
   - Filter by status should work correctly

3. **Application Detail** (`/api/mobile/applications/{id}`)
   - Should display correct status messages using the new constants

## Additional Notes

- The `announcements` table correctly uses `created_at` and `updated_at` columns
- The fix maintains backward compatibility with existing data
- Error logging has been added to help identify future issues
- The Application model intentionally disables timestamps as the table structure doesn't include them

## Database Schema Note

The `applications` table structure:
- Uses `application_date` instead of `created_at`
- Has `deleted_at` for soft deletes
- Does NOT have `created_at` or `updated_at` columns
- This is by design and the model correctly reflects this with `public $timestamps = false;`
