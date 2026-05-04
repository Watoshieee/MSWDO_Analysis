@echo off
echo ========================================
echo  MSWDO Laravel Server - Hotspot Mode
echo ========================================
echo.

:: Clear all caches first
echo Clearing caches...
php artisan config:clear
php artisan cache:clear
php artisan route:clear
echo.

:: Show hotspot IP
echo Your Hotspot IP: 192.168.137.1
echo Flutter app should use: http://192.168.137.1:8000/mobile-api
echo.
echo Make sure your phone is connected to THIS laptop's hotspot!
echo.

:: Start server bound to all interfaces
echo Starting Laravel server...
php artisan serve --host=0.0.0.0 --port=8000
