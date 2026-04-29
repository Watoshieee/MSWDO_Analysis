@echo off
echo ========================================
echo MSWDO Analysis - Quick Start
echo ========================================
echo.

REM Navigate to project directory
cd /d c:\xampp8.2\htdocs\MSWDO_Analysis-main

echo Clearing caches...
call php artisan cache:clear >nul 2>&1
call php artisan config:clear >nul 2>&1
call php artisan route:clear >nul 2>&1
call php artisan view:clear >nul 2>&1

echo.
echo ========================================
echo Choose Installation Type:
echo ========================================
echo.
echo 1. Fresh Install (DELETES ALL DATA)
echo 2. Fix Existing Database (KEEPS DATA)
echo 3. Just Start Server
echo 4. Exit
echo.
set /p choice="Enter your choice (1-4): "

if "%choice%"=="1" goto fresh_install
if "%choice%"=="2" goto fix_database
if "%choice%"=="3" goto start_server
if "%choice%"=="4" goto end

:fresh_install
echo.
echo ========================================
echo Fresh Installation
echo ========================================
echo WARNING: This will DELETE ALL DATA!
echo.
set /p confirm="Type YES to continue: "
if /i not "%confirm%"=="YES" goto end

echo.
echo Running migrations...
call php artisan migrate:fresh --seed

if errorlevel 1 (
    echo.
    echo ERROR: Migration failed!
    echo Make sure MySQL is running and database exists.
    pause
    goto end
)

echo.
echo SUCCESS! Database created and seeded.
goto start_server

:fix_database
echo.
echo ========================================
echo Fixing Existing Database
echo ========================================
echo This will add missing columns without deleting data.
echo.
pause

echo.
echo Fixing applications table...
call php artisan migrate --path=database/migrations/2026_04_30_000001_fix_applications_table_structure.php

echo.
echo Fixing social_welfare_programs table...
call php artisan migrate --path=database/migrations/2026_04_30_000002_fix_social_welfare_programs_table_structure.php

echo.
echo SUCCESS! Database structure fixed.
goto start_server

:start_server
echo.
echo ========================================
echo Starting Development Server
echo ========================================
echo.
echo Server will start at: http://127.0.0.1:8000
echo.
echo Press Ctrl+C to stop the server
echo.
echo Opening browser in 3 seconds...
timeout /t 3 >nul
start http://127.0.0.1:8000
echo.

call php artisan serve

:end
echo.
echo Goodbye!
pause
