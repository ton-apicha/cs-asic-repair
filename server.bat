@echo off
REM ============================================
REM ASIC Repair System - Quick Server Launcher
REM Windows Batch File
REM ============================================

setlocal enabledelayedexpansion

if "%1"=="" goto help
if "%1"=="start" goto start
if "%1"=="stop" goto stop
if "%1"=="restart" goto restart
if "%1"=="status" goto status
if "%1"=="migrate" goto migrate
if "%1"=="seed" goto seed
if "%1"=="backup" goto backup
if "%1"=="cache" goto cache
if "%1"=="logs" goto logs
if "%1"=="routes" goto routes
if "%1"=="help" goto help
goto unknown

:start
echo [*] Starting development server...
start "ASIC Repair Server" /B php spark serve --host=localhost --port=8080
echo [OK] Server started at http://localhost:8080
echo.
echo Press Ctrl+C or run 'server stop' to stop the server
goto end

:stop
echo [*] Stopping development server...
taskkill /F /IM php.exe 2>nul
echo [OK] Server stopped
goto end

:restart
call :stop
timeout /t 2 /nobreak >nul
call :start
goto end

:status
echo.
echo === ASIC Repair System Status ===
echo.
tasklist /FI "IMAGENAME eq php.exe" 2>nul | find /I "php.exe" >nul
if %errorlevel%==0 (
    echo Server Status: RUNNING
    tasklist /FI "IMAGENAME eq php.exe"
) else (
    echo Server Status: STOPPED
)
echo.
php -v | findstr /B "PHP"
goto end

:migrate
echo [*] Running database migrations...
php spark migrate
echo [OK] Migrations completed
goto end

:seed
echo [*] Running database seeders...
php spark db:seed DatabaseSeeder
echo [OK] Seeding completed
goto end

:backup
echo [*] Creating database backup...
set timestamp=%date:~-4%%date:~-7,2%%date:~-10,2%_%time:~0,2%%time:~3,2%%time:~6,2%
set timestamp=%timestamp: =0%
if not exist "writable\backups" mkdir "writable\backups"
copy "writable\database\database.db" "writable\backups\backup_%timestamp%.db"
echo [OK] Backup created
goto end

:cache
echo [*] Clearing cache...
del /Q "writable\cache\*" 2>nul
echo [OK] Cache cleared
goto end

:logs
echo [*] Clearing logs...
del /Q "writable\logs\*.log" 2>nul
echo [OK] Logs cleared
goto end

:routes
php spark routes
goto end

:unknown
echo [!] Unknown command: %1
echo.

:help
echo.
echo ============================================
echo   ASIC Repair System - Server CLI
echo ============================================
echo.
echo Usage: server [command]
echo.
echo Commands:
echo   start     Start development server
echo   stop      Stop development server
echo   restart   Restart development server
echo   status    Check server status
echo   migrate   Run database migrations
echo   seed      Run database seeders
echo   backup    Create database backup
echo   cache     Clear cache
echo   logs      Clear log files
echo   routes    List all routes
echo   help      Show this help
echo.
echo Examples:
echo   server start
echo   server migrate
echo   server backup
echo.
goto end

:end
endlocal

