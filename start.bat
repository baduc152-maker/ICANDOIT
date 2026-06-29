@echo off
rem Khoi dong ICANDOIT tren Windows.
rem Lan dau se tu tao cau hinh, co so du lieu SQLite va du lieu mau.

cd /d "%~dp0"

where php >nul 2>nul
if errorlevel 1 (
    echo [X] Chua cai PHP. Can PHP 8.3 tro len - xem HUONG-DAN-CHAY.md
    pause
    exit /b 1
)

if not exist .env (
    echo - Tao file cau hinh .env
    copy .env.example .env >nul
)

findstr /b "APP_KEY=base64:" .env >nul
if errorlevel 1 (
    echo - Sinh khoa ung dung
    php artisan key:generate --force
)

if not exist database\database.sqlite (
    echo - Tao co so du lieu SQLite va du lieu mau
    type nul > database\database.sqlite
    php artisan migrate --force
    php artisan db:seed --force
) else (
    php artisan migrate --force
)

echo.
echo ============================================================
echo   ICANDOIT dang chay tai:  http://127.0.0.1:8000
echo   Dang nhap quan tri:      admin@icandoit.test / password
echo   Nhan Ctrl + C de dung.
echo ============================================================
echo.

php artisan serve
