# Database Column Fix - Registration System

## Problem Identified

**Error:** `SQLSTATE[42S22]: Column not found: 1054 Unknown column 'first_name' in 'field list'`

**Root Cause:** Backend code was trying to insert `first_name`, `middle_name`, `last_name` columns that don't exist in the database. The actual database uses a single `full_name` column.

---

## Database Schema (Actual)

### Users Table Columns
```
✅ Existing columns in database:
- id
- username
- password
- email
- mobile_number
- full_name          ← Single column for full name
- birthdate
- age
- gender
- role
- municipality
- barangay
- status
- deleted_at
- archived_by
- created_at
- email_verified_at
- otp_code
- otp_expires_at
- reset_token
- reset_token_expires_at

❌ Non-existent columns (causing error):
- first_name
- middle_name
- last_name
```

---

## Solution Applied

### 1. Updated User Model

**File:** `app/Models/User.php`

**Before:**
```php
protected $fillable = [
    'username',
    'password',
    'email',
    'full_name',
    'first_name',      // ❌ Doesn't exist
    'middle_name',     // ❌ Doesn't exist
    'last_name',       // ❌ Doesn't exist
    'birthdate',
    // ...
];
```

**After:**
```php
protected $fillable = [
    'username',
    'password',
    'email',
    'full_name',       // ✅ Only existing column
    'birthdate',
    'age',
    'gender',
    'role',
    'municipality',
    'barangay',
    'mobile_number',
    'status',
    'email_verified_at',
    'otp_code',
    'otp_expires_at',
    'reset_token',
    'reset_token_expires_at',
    'archived_by',
];
```

---

### 2. Updated Registration Controller

**File:** `app/Http/Controllers/Api/MobileApiController.php`

**Key Changes:**

#### A. Validation (Still validates separate fields)
```php
// Frontend sends: first_name, middle_name, last_name
// Backend validates them separately
$validator = Validator::make($request->all(), [
    'first_name' => 'required|string|max:50',
    'middle_name' => 'nullable|string|max:50',  // Optional
    'last_name' => 'required|string|max:50',
    // ... other fields
]);
```

#### B. Combine Names Before Saving
```php
// Combine first, middle, last names into full_name
$fullName = trim(
    $request->first_name . ' ' . 
    ($request->middle_name ? $request->middle_name . ' ' : '') . 
    $request->last_name
);

// Examples:
// Input: first="Juan", middle="Santos", last="Cruz"
// Output: "Juan Santos Cruz"

// Input: first="Maria", middle="", last="Reyes"
// Output: "Maria Reyes"
```

#### C. Insert Only Existing Columns
```php
$user = User::create([
    'full_name' => $fullName,           // ✅ Combined name
    'username' => trim($request->username),
    'email' => strtolower(trim($request->email)),
    'mobile_number' => trim($request->mobile_number),
    'birthdate' => $request->birthdate,
    'age' => $age,
    'gender' => $request->gender,
    'municipality' => $request->municipality,
    'barangay' => $request->barangay,
    'password' => Hash::make($generatedPassword),
    'role' => User::ROLE_USER,
    'status' => 'inactive',
]);

// ❌ Removed: first_name, middle_name, last_name
```

---

## Data Flow

### Frontend → Backend → Database

```
┌─────────────────────────────────────────────────────────────┐
│ FRONTEND (Flutter)                                          │
├─────────────────────────────────────────────────────────────┤
│ User fills form:                                            │
│   First Name: Juan                                          │
│   Middle Name: Santos                                       │
│   Last Name: Cruz                                           │
└─────────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────────┐
│ API REQUEST (JSON)                                          │
├─────────────────────────────────────────────────────────────┤
│ {                                                           │
│   "first_name": "Juan",                                     │
│   "middle_name": "Santos",                                  │
│   "last_name": "Cruz",                                      │
│   "username": "juan_cruz",                                  │
│   "email": "juan@gmail.com",                                │
│   ...                                                       │
│ }                                                           │
└─────────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────────┐
│ BACKEND VALIDATION                                          │
├─────────────────────────────────────────────────────────────┤
│ ✅ Validate first_name (required, 1-50 chars, letters)     │
│ ✅ Validate middle_name (optional, 1-50 chars, letters)    │
│ ✅ Validate last_name (required, 1-50 chars, letters)      │
└─────────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────────┐
│ BACKEND PROCESSING                                          │
├─────────────────────────────────────────────────────────────┤
│ Combine names:                                              │
│   $fullName = "Juan Santos Cruz"                            │
│                                                             │
│ Generate password:                                          │
│   $password = "K7m@Qp3$Hn9z"                                │
└─────────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────────┐
│ DATABASE INSERT                                             │
├─────────────────────────────────────────────────────────────┤
│ INSERT INTO users (                                         │
│   full_name,        ← "Juan Santos Cruz"                   │
│   username,         ← "juan_cruz"                           │
│   email,            ← "juan@gmail.com"                      │
│   password,         ← hashed password                       │
│   mobile_number,    ← "+639123456789"                       │
│   birthdate,        ← "2000-01-01"                          │
│   age,              ← 24                                    │
│   gender,           ← "Male"                                │
│   municipality,     ← "Majayjay"                            │
│   barangay,         ← "Poblacion"                           │
│   role,             ← "user"                                │
│   status            ← "inactive"                            │
│ )                                                           │
└─────────────────────────────────────────────────────────────┘
```

---

## Examples

### Example 1: With Middle Name
```
Input:
  first_name: "Juan"
  middle_name: "Santos"
  last_name: "Dela Cruz"

Processing:
  $fullName = trim("Juan" . " " . "Santos" . " " . "Dela Cruz")
  $fullName = "Juan Santos Dela Cruz"

Database:
  full_name = "Juan Santos Dela Cruz"
```

### Example 2: Without Middle Name
```
Input:
  first_name: "Maria"
  middle_name: ""
  last_name: "Reyes"

Processing:
  $fullName = trim("Maria" . " " . "" . "Reyes")
  $fullName = "Maria Reyes"

Database:
  full_name = "Maria Reyes"
```

### Example 3: Single Letter Names
```
Input:
  first_name: "O"
  middle_name: "P"
  last_name: "Cruz"

Processing:
  $fullName = trim("O" . " " . "P" . " " . "Cruz")
  $fullName = "O P Cruz"

Database:
  full_name = "O P Cruz"
```

---

## Validation Rules

### Frontend Validation (Flutter)
```dart
// Validates individual fields before sending to API
first_name:  1-50 chars, letters only, required
middle_name: 1-50 chars, letters only, optional
last_name:   1-50 chars, letters only, required
```

### Backend Validation (Laravel)
```php
// Validates individual fields from API request
'first_name' => 'required|string|max:50',
'middle_name' => 'nullable|string|max:50',
'last_name' => 'required|string|max:50',

// Custom validation (RegistrationValidationService)
- No numbers in names
- Only letters, spaces, hyphens, apostrophes
- Proper character validation
```

### Database Storage
```sql
-- Stores combined full_name
full_name VARCHAR(255) NOT NULL
```

---

## Backward Compatibility

### ✅ Maintains Compatibility With:

1. **Existing Database Structure**
   - No schema changes required
   - Uses existing `full_name` column
   - No migration needed

2. **Existing User Data**
   - Old users with `full_name` remain unchanged
   - New users get properly formatted `full_name`

3. **Frontend Form**
   - Frontend still sends separate name fields
   - Backend combines them transparently
   - No frontend changes needed

4. **API Contract**
   - API still accepts `first_name`, `middle_name`, `last_name`
   - Response format unchanged
   - No breaking changes

---

## Testing

### Test Case 1: Registration with Middle Name
```
POST /api/register
{
  "first_name": "Juan",
  "middle_name": "Santos",
  "last_name": "Cruz",
  "username": "juan_cruz",
  "email": "juan.cruz@gmail.com",
  "mobile_number": "+639123456789",
  "birthdate": "2000-01-01",
  "gender": "Male",
  "municipality": "Majayjay",
  "barangay": "Poblacion"
}

Expected Result:
✅ User created successfully
✅ full_name = "Juan Santos Cruz"
✅ No database error
```

### Test Case 2: Registration without Middle Name
```
POST /api/register
{
  "first_name": "Maria",
  "middle_name": "",
  "last_name": "Reyes",
  ...
}

Expected Result:
✅ User created successfully
✅ full_name = "Maria Reyes"
✅ No extra spaces
```

### Test Case 3: Single Letter Names
```
POST /api/register
{
  "first_name": "O",
  "middle_name": "",
  "last_name": "Cruz",
  ...
}

Expected Result:
✅ User created successfully
✅ full_name = "O Cruz"
✅ Validation passes
```

---

## Verification Queries

### Check User Creation
```sql
SELECT id, full_name, username, email, gender, created_at 
FROM users 
ORDER BY id DESC 
LIMIT 5;
```

### Verify Full Name Format
```sql
SELECT 
  id,
  full_name,
  LENGTH(full_name) as name_length,
  CASE 
    WHEN full_name LIKE '% % %' THEN 'Has middle name'
    WHEN full_name LIKE '% %' THEN 'No middle name'
    ELSE 'Single name'
  END as name_format
FROM users
WHERE created_at > NOW() - INTERVAL 1 DAY;
```

---

## Error Resolution

### Before Fix
```
❌ Error: SQLSTATE[42S22]: Column not found: 1054 Unknown column 'first_name' in 'field list'

Cause: Trying to insert non-existent columns
INSERT INTO users (first_name, middle_name, last_name, ...)
```

### After Fix
```
✅ Success: User created successfully

Solution: Insert only existing columns
INSERT INTO users (full_name, username, email, ...)
```

---

## Summary

### ✅ Fixed Issues

1. **Database Column Mismatch**
   - Removed non-existent columns from fillable array
   - Only use existing `full_name` column

2. **Registration Logic**
   - Validate separate name fields (frontend UX)
   - Combine into `full_name` before saving
   - Insert only existing database columns

3. **Backward Compatibility**
   - No database changes required
   - No frontend changes required
   - Existing data unaffected

### ✅ Validation Flow

```
Frontend Fields → Backend Validation → Combine Names → Database Insert
(first, middle, last) → (validate each) → (full_name) → (single column)
```

### ✅ Result

- ✅ No more database errors
- ✅ Names properly validated
- ✅ Full name correctly stored
- ✅ System working as expected

---

**Status:** ✅ Fixed and Production Ready
**Version:** 1.2
**Last Updated:** 2024
