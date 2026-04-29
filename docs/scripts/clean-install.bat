@echo off
echo ========================================
echo MSWDO Analysis - Clean Installation
echo ========================================
echo.

REM Check if running in correct directory
if not exist "artisan" (
    echo ERROR: Please run this script from the project root directory!
    echo Current directory: %CD%
    pause
    exit /b 1
)

echo Step 1: Clearing all caches...
echo ----------------------------------------
call php artisan cache:clear
call php artisan config:clear
call php artisan route:clear
call php artisan view:clear
call php artisan clear-compiled
echo.

echo Step 2: Clearing bootstrap cache...
echo ----------------------------------------
if exist "bootstrap\cache\*.php" (
    del /Q bootstrap\cache\*.php
    echo Bootstrap cache cleared
) else (
    echo No bootstrap cache files found
)
echo.

echo Step 3: Checking .env file...
echo ----------------------------------------
if not exist ".env" (
    echo WARNING: .env file not found!
    if exist ".env.example" (
        echo Copying .env.example to .env...
        copy .env.example .env
        echo Please edit .env file with your database credentials
        pause
    ) else (
        echo ERROR: .env.example not found!
        pause
        exit /b 1
    )
)
echo .env file exists
echo.

echo Step 4: Generating application key...
echo ----------------------------------------
call php artisan key:generate
echo.

echo Step 5: Installing Composer dependencies...
echo ----------------------------------------
call composer install --no-interaction
if errorlevel 1 (
    echo ERROR: Composer install failed!
    pause
    exit /b 1
)
echo.

echo Step 6: Creating storage directories...
echo ----------------------------------------
if not exist "storage\logs" mkdir storage\logs
if not exist "storage\framework\cache" mkdir storage\framework\cache
if not exist "storage\framework\sessions" mkdir storage\framework\sessions
if not exist "storage\framework\views" mkdir storage\framework\views
echo Storage directories created
echo.

echo ========================================
echo IMPORTANT: Database Setup Required
echo ========================================
echo.
echo Before continuing, please ensure:
echo 1. MySQL is running in XAMPP
echo 2. Database 'mswdo_analysis' exists
echo 3. .env file has correct database credentials
echo.
echo To create database, run in MySQL:
echo   DROP DATABASE IF EXISTS mswdo_analysis;
echo   CREATE DATABASE mswdo_analysis CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
echo.
set /p continue="Press Y to continue with migrations, N to exit: "
if /i not "%continue%"=="Y" (
    echo Installation paused. Run this script again when ready.
    pause
    exit /b 0
)
echo.

echo Step 7: Running fresh migrations with seeders...
echo ----------------------------------------
echo WARNING: This will DROP ALL TABLES and recreate them!
echo.
set /p confirm="Are you sure? Type YES to continue: "
if /i not "%confirm%"=="YES" (
    echo Migration cancelled.
    pause
    exit /b 0
)
echo.

call php artisan migrate:fresh --seed
if errorlevel 1 (
    echo.
    echo ERROR: Migration failed!
    echo Please check:
    echo 1. Database exists
    echo 2. Database credentials in .env are correct
    echo 3. MySQL is running
    echo.
    pause
    exit /b 1
)
echo.

echo Step 8: Creating storage link...
echo ----------------------------------------
call php artisan storage:link
echo.

echo Step 9: Verifying installation...
echo ----------------------------------------
call php artisan tinker --execute="echo 'Applications columns: ' . implode(', ', Schema::getColumnListing('applications')) . PHP_EOL; echo 'Social Welfare Programs columns: ' . implode(', ', Schema::getColumnListing('social_welfare_programs')) . PHP_EOL;"
echo.

echo ========================================
echo Installation Complete!
echo ========================================
echo.
echo Your application is ready to use!
echo.
echo To start the development server, run:
echo   php artisan serve
echo.
echo Then open your browser to:
echo   http://127.0.0.1:8000
echo.
echo To check logs:
echo   tail -f storage/logs/laravel.log
echo   OR open: storage\logs\laravel.log
echo.
echo Default credentials should be in your seeders.
echo Check database/seeders/ for login information.
echo.
pause
