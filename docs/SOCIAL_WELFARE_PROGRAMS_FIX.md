# Social Welfare Programs Table Fix

## Error Fixed

```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'program_type' in 'field list'
```

## Problem

The `social_welfare_programs` table migration was creating the wrong columns:

**Old (Wrong) Structure:**
- `id`
- `name`
- `code`
- `description`
- `created_at`
- `updated_at`
- `deleted_at`

**New (Correct) Structure:**
- `id`
- `municipality`
- `barangay`
- `program_type`
- `beneficiary_count`
- `year`
- `month`
- `deleted_at`

## Files Modified

### 1. Migration File
**File:** `database/migrations/2026_03_01_000005_create_social_welfare_programs_table.php`

**Changes:**
- Removed: `name`, `code`, `description`, `created_at`, `updated_at`
- Added: `municipality`, `barangay`, `program_type`, `beneficiary_count`, `year`, `month`
- Added indexes for better query performance

### 2. Helper Migration (NEW)
**File:** `database/migrations/2026_04_30_000002_fix_social_welfare_programs_table_structure.php`

This migration safely updates existing databases without losing data.

### 3. Month Migration
**File:** `database/migrations/2026_04_20_200012_add_month_to_social_welfare_programs_table.php`

Updated to be a no-op since `month` is now in the main migration.

## How to Apply

### Option A: Fresh Start (Development - DELETES ALL DATA)
```bash
php artisan migrate:fresh --seed
```

### Option B: Fix Existing Database (Production - KEEPS DATA)
```bash
php artisan migrate --path=database/migrations/2026_04_30_000002_fix_social_welfare_programs_table_structure.php
```

### Option C: Manual SQL (If migrations don't work)
```sql
-- Check current structure
DESCRIBE social_welfare_programs;

-- If you have the old structure, drop old columns
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

-- Add indexes for performance
CREATE INDEX social_welfare_programs_municipality_year_index 
ON social_welfare_programs(municipality, year);

CREATE INDEX social_welfare_programs_program_type_index 
ON social_welfare_programs(program_type);
```

## Verify the Fix

### 1. Check Table Structure
```bash
php artisan tinker
```
```php
Schema::getColumnListing('social_welfare_programs');
exit;
```

Expected columns:
- id
- municipality
- barangay
- program_type
- beneficiary_count
- year
- month
- deleted_at

### 2. Test the Analysis Page
Visit: `/analysis`

Should load without errors and display:
- Program statistics
- Municipality data
- Year-over-year trends

### 3. Check Logs
```bash
tail -n 50 storage/logs/laravel.log
```

Should see no "Column not found" errors.

## Model Configuration

The `SocialWelfareProgram` model is correctly configured:

```php
protected $fillable = [
    'municipality',
    'barangay',
    'program_type',
    'beneficiary_count',
    'year',
    'month'
];

public $timestamps = true;
const CREATED_AT = null;
const UPDATED_AT = null;
```

This means:
- The model uses soft deletes (`deleted_at`)
- No automatic `created_at` or `updated_at` timestamps
- All data fields are mass-assignable

## Program Types

The system supports these program types:
- `4Ps` - Pantawid Pamilyang Pilipino Program
- `Senior_Citizen_Pension` - Senior Citizen Assistance
- `PWD_Assistance` - Persons with Disability Assistance
- `AICS` - Assistance to Individuals in Crisis Situation
- `AICS_Medical` - Medical AICS
- `AICS_Burial` - Burial AICS
- `AICS_Educational` - Educational AICS
- `SLP` - Sustainable Livelihood Program
- `ESA` - Emergency Shelter Assistance
- `Solo_Parent` - Solo Parent Assistance

## Data Entry

When adding data to this table, ensure:
1. `municipality` matches a municipality name in the `municipalities` table
2. `barangay` matches a barangay name in the `barangays` table (optional)
3. `program_type` is one of the supported types above
4. `beneficiary_count` is a positive integer
5. `year` is a 4-digit year (e.g., 2024)
6. `month` is 1-12 (optional)

## Troubleshooting

### Error: "Column 'program_type' not found"
- Run the fix migration: `php artisan migrate --path=database/migrations/2026_04_30_000002_fix_social_welfare_programs_table_structure.php`
- Or use manual SQL above

### Error: "Column 'name' not found"
- This means the fix was applied but you're trying to use old code
- Make sure all code changes are pulled/applied

### Migration fails
- Check if you have database backup
- Try manual SQL approach
- Verify MySQL user has ALTER TABLE permissions

### Data is lost after migration
- If you used `migrate:fresh`, all data is deleted (expected)
- Use Option B or C to preserve data
- Always backup before running migrations

## Success Indicators

✅ No "Column not found" errors in logs
✅ `/analysis` page loads successfully
✅ Program statistics display correctly
✅ Municipality data shows properly
✅ Year filters work
✅ Charts render without errors

---

**Status:** ✅ Fix applied and documented
**Last Updated:** 2024
