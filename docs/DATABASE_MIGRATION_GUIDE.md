# Database Migration Guide

## Applications Table Migration Fixed

The `applications` table migration has been updated to match the actual table structure used in the application.

### Changes Made

#### 1. Main Migration File: `2026_03_01_000003_create_applications_table.php`

**Removed:**
- `$table->timestamps()` - This was creating `created_at` and `updated_at` columns that were not being used

**Added:**
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

**Kept:**
- `id` (primary key)
- `user_id` (foreign key to users table)
- `program_type` (string)
- `status` (string, default: 'pending')
- `form_data` (json, nullable)
- `stage` (string, nullable)
- `completed_at` (timestamp, nullable)
- `admin_remarks` (text, nullable)
- `aics_subtype` (string, nullable)
- `deleted_at` (timestamp, nullable) - For soft deletes

### How to Apply These Changes

#### Option 1: Fresh Migration (Recommended for Development)
If you're in development and can afford to lose data:

```bash
# Drop all tables and re-run migrations
php artisan migrate:fresh

# Or with seeding
php artisan migrate:fresh --seed
```

#### Option 2: Manual Database Update (For Production)
If you have existing data you need to keep:

```sql
-- Add missing columns to applications table
ALTER TABLE applications 
ADD COLUMN municipality VARCHAR(255) NULL AFTER program_type,
ADD COLUMN barangay VARCHAR(255) NULL AFTER municipality,
ADD COLUMN full_name VARCHAR(255) NULL AFTER barangay,
ADD COLUMN age INT NULL AFTER full_name,
ADD COLUMN gender VARCHAR(255) NULL AFTER age,
ADD COLUMN contact_number VARCHAR(255) NULL AFTER gender,
ADD COLUMN application_date TIMESTAMP NULL AFTER status,
ADD COLUMN year VARCHAR(255) NULL AFTER application_date,
ADD COLUMN proof_photo_path VARCHAR(255) NULL AFTER aics_subtype,
ADD COLUMN id_status VARCHAR(255) NULL AFTER proof_photo_path,
ADD COLUMN id_ready_at TIMESTAMP NULL AFTER id_status;

-- Remove created_at and updated_at if they exist
ALTER TABLE applications 
DROP COLUMN IF EXISTS created_at,
DROP COLUMN IF EXISTS updated_at;
```

#### Option 3: Create a New Migration (Safest for Production)
Create a new migration to add missing columns:

```bash
php artisan make:migration add_missing_columns_to_applications_table
```

Then add this code to the migration:

```php
public function up(): void
{
    Schema::table('applications', function (Blueprint $table) {
        if (!Schema::hasColumn('applications', 'municipality')) {
            $table->string('municipality')->nullable()->after('program_type');
        }
        if (!Schema::hasColumn('applications', 'barangay')) {
            $table->string('barangay')->nullable()->after('municipality');
        }
        if (!Schema::hasColumn('applications', 'full_name')) {
            $table->string('full_name')->nullable()->after('barangay');
        }
        if (!Schema::hasColumn('applications', 'age')) {
            $table->integer('age')->nullable()->after('full_name');
        }
        if (!Schema::hasColumn('applications', 'gender')) {
            $table->string('gender')->nullable()->after('age');
        }
        if (!Schema::hasColumn('applications', 'contact_number')) {
            $table->string('contact_number')->nullable()->after('gender');
        }
        if (!Schema::hasColumn('applications', 'application_date')) {
            $table->timestamp('application_date')->nullable()->after('status');
        }
        if (!Schema::hasColumn('applications', 'year')) {
            $table->string('year')->nullable()->after('application_date');
        }
        if (!Schema::hasColumn('applications', 'proof_photo_path')) {
            $table->string('proof_photo_path')->nullable()->after('aics_subtype');
        }
        if (!Schema::hasColumn('applications', 'id_status')) {
            $table->string('id_status')->nullable()->after('proof_photo_path');
        }
        if (!Schema::hasColumn('applications', 'id_ready_at')) {
            $table->timestamp('id_ready_at')->nullable()->after('id_status');
        }
        
        // Drop created_at and updated_at if they exist
        if (Schema::hasColumn('applications', 'created_at')) {
            $table->dropColumn('created_at');
        }
        if (Schema::hasColumn('applications', 'updated_at')) {
            $table->dropColumn('updated_at');
        }
    });
}

public function down(): void
{
    Schema::table('applications', function (Blueprint $table) {
        $table->dropColumn([
            'municipality',
            'barangay',
            'full_name',
            'age',
            'gender',
            'contact_number',
            'application_date',
            'year',
            'proof_photo_path',
            'id_status',
            'id_ready_at'
        ]);
        $table->timestamps();
    });
}
```

Then run:
```bash
php artisan migrate
```

### Verification

After applying the migration, verify the table structure:

```bash
php artisan tinker
```

Then run:
```php
Schema::getColumnListing('applications');
```

Expected columns:
- id
- user_id
- program_type
- municipality
- barangay
- full_name
- age
- gender
- contact_number
- status
- application_date
- year
- form_data
- stage
- completed_at
- admin_remarks
- aics_subtype
- proof_photo_path
- id_status
- id_ready_at
- deleted_at

### Important Notes

1. **No Timestamps**: The `applications` table intentionally does NOT have `created_at` and `updated_at` columns. The Application model has `public $timestamps = false;` to reflect this.

2. **Application Date**: The table uses `application_date` instead of `created_at` for tracking when applications were submitted.

3. **Soft Deletes**: The table supports soft deletes with the `deleted_at` column.

4. **Model Configuration**: The Application model is correctly configured with:
   - `public $timestamps = false;`
   - `application_date` cast to datetime
   - All necessary fillable fields

### Testing After Migration

Test these endpoints to ensure everything works:

1. Create a new application
2. List all applications
3. View application details
4. Update application status
5. Soft delete an application

All operations should work without any "Column not found" errors.
