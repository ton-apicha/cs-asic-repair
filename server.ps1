# ============================================
# ASIC Repair System - Server Management Script
# PowerShell Script for Windows
# ============================================

param(
    [Parameter(Position=0)]
    [string]$Command = "help",
    [string]$Port = "8080",
    [string]$Host = "localhost"
)

$ErrorActionPreference = "Stop"
$ProjectRoot = $PSScriptRoot

function Write-ColorText($Text, $Color = "White") {
    Write-Host $Text -ForegroundColor $Color
}

function Show-Banner {
    Write-ColorText ""
    Write-ColorText "  ╔══════════════════════════════════════════════╗" "Cyan"
    Write-ColorText "  ║       ASIC Repair System - Server CLI        ║" "Cyan"
    Write-ColorText "  ╚══════════════════════════════════════════════╝" "Cyan"
    Write-ColorText ""
}

function Show-Help {
    Show-Banner
    Write-ColorText "  Available Commands:" "Yellow"
    Write-ColorText ""
    Write-ColorText "  Server Management:" "Green"
    Write-ColorText "    start        - Start development server"
    Write-ColorText "    stop         - Stop development server"
    Write-ColorText "    restart      - Restart development server"
    Write-ColorText "    status       - Check server status"
    Write-ColorText ""
    Write-ColorText "  Database:" "Green"
    Write-ColorText "    migrate      - Run database migrations"
    Write-ColorText "    seed         - Run database seeders"
    Write-ColorText "    reset        - Reset database (migrate:refresh + seed)"
    Write-ColorText "    backup       - Create database backup"
    Write-ColorText ""
    Write-ColorText "  Maintenance:" "Green"
    Write-ColorText "    cache:clear  - Clear all caches"
    Write-ColorText "    logs:clear   - Clear log files"
    Write-ColorText "    clean        - Clean all temp files"
    Write-ColorText ""
    Write-ColorText "  Development:" "Green"
    Write-ColorText "    routes       - List all routes"
    Write-ColorText "    env          - Show environment info"
    Write-ColorText "    test         - Run tests"
    Write-ColorText ""
    Write-ColorText "  Options:" "Yellow"
    Write-ColorText "    -Port <port>   Default: 8080"
    Write-ColorText "    -Host <host>   Default: localhost"
    Write-ColorText ""
    Write-ColorText "  Examples:" "Magenta"
    Write-ColorText "    .\server.ps1 start"
    Write-ColorText "    .\server.ps1 start -Port 9000"
    Write-ColorText "    .\server.ps1 migrate"
    Write-ColorText ""
}

function Get-ServerProcess {
    Get-Process -Name "php" -ErrorAction SilentlyContinue | 
        Where-Object { $_.CommandLine -like "*spark serve*" }
}

function Start-Server {
    Write-ColorText "[*] Starting development server..." "Yellow"
    
    $existingProcess = Get-ServerProcess
    if ($existingProcess) {
        Write-ColorText "[!] Server is already running (PID: $($existingProcess.Id))" "Red"
        return
    }
    
    Set-Location $ProjectRoot
    
    # Start server in background
    $job = Start-Job -ScriptBlock {
        param($root, $host, $port)
        Set-Location $root
        php spark serve --host=$host --port=$port
    } -ArgumentList $ProjectRoot, $Host, $Port
    
    Start-Sleep -Seconds 2
    
    Write-ColorText "[✓] Server started successfully!" "Green"
    Write-ColorText "    URL: http://${Host}:${Port}" "Cyan"
    Write-ColorText "    Job ID: $($job.Id)" "Gray"
    Write-ColorText ""
    Write-ColorText "    To stop: .\server.ps1 stop" "Yellow"
}

function Stop-Server {
    Write-ColorText "[*] Stopping development server..." "Yellow"
    
    $processes = Get-Process -Name "php" -ErrorAction SilentlyContinue
    if ($processes) {
        $processes | Stop-Process -Force
        Write-ColorText "[✓] Server stopped successfully!" "Green"
    } else {
        Write-ColorText "[!] No server process found" "Yellow"
    }
    
    # Clean up background jobs
    Get-Job | Where-Object { $_.State -eq "Running" } | Stop-Job
    Get-Job | Remove-Job -Force -ErrorAction SilentlyContinue
}

function Restart-Server {
    Stop-Server
    Start-Sleep -Seconds 1
    Start-Server
}

function Get-Status {
    Show-Banner
    
    $processes = Get-Process -Name "php" -ErrorAction SilentlyContinue
    
    if ($processes) {
        Write-ColorText "  Server Status: RUNNING" "Green"
        Write-ColorText ""
        foreach ($p in $processes) {
            Write-ColorText "    PID: $($p.Id)" "Cyan"
            Write-ColorText "    Memory: $([math]::Round($p.WorkingSet64 / 1MB, 2)) MB" "Gray"
        }
    } else {
        Write-ColorText "  Server Status: STOPPED" "Red"
    }
    
    Write-ColorText ""
    Write-ColorText "  Environment: $(if (Test-Path '.env') { 'Configured' } else { 'Not Found' })" "Yellow"
    Write-ColorText "  PHP Version: $(php -v | Select-Object -First 1)" "Gray"
    Write-ColorText ""
}

function Run-Migrate {
    Write-ColorText "[*] Running database migrations..." "Yellow"
    Set-Location $ProjectRoot
    php spark migrate
    Write-ColorText "[✓] Migrations completed!" "Green"
}

function Run-Seed {
    Write-ColorText "[*] Running database seeders..." "Yellow"
    Set-Location $ProjectRoot
    php spark db:seed DatabaseSeeder
    Write-ColorText "[✓] Seeding completed!" "Green"
}

function Reset-Database {
    Write-ColorText "[!] WARNING: This will reset all data!" "Red"
    $confirm = Read-Host "Are you sure? (yes/no)"
    
    if ($confirm -eq "yes") {
        Write-ColorText "[*] Resetting database..." "Yellow"
        Set-Location $ProjectRoot
        php spark migrate:refresh
        php spark db:seed DatabaseSeeder
        Write-ColorText "[✓] Database reset completed!" "Green"
    } else {
        Write-ColorText "[*] Operation cancelled" "Yellow"
    }
}

function Backup-Database {
    $timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
    $backupDir = Join-Path $ProjectRoot "writable\backups"
    $dbPath = Join-Path $ProjectRoot "writable\database\database.db"
    
    if (-not (Test-Path $backupDir)) {
        New-Item -ItemType Directory -Path $backupDir | Out-Null
    }
    
    $backupFile = Join-Path $backupDir "backup_$timestamp.db"
    
    Write-ColorText "[*] Creating database backup..." "Yellow"
    Copy-Item $dbPath $backupFile
    Write-ColorText "[✓] Backup created: $backupFile" "Green"
}

function Clear-Cache {
    Write-ColorText "[*] Clearing caches..." "Yellow"
    Set-Location $ProjectRoot
    
    # Clear CI4 cache
    $cachePath = Join-Path $ProjectRoot "writable\cache"
    if (Test-Path $cachePath) {
        Get-ChildItem $cachePath -Exclude ".gitkeep" | Remove-Item -Recurse -Force
    }
    
    Write-ColorText "[✓] Cache cleared!" "Green"
}

function Clear-Logs {
    Write-ColorText "[*] Clearing log files..." "Yellow"
    
    $logsPath = Join-Path $ProjectRoot "writable\logs"
    if (Test-Path $logsPath) {
        Get-ChildItem $logsPath -Exclude ".gitkeep" | Remove-Item -Force
    }
    
    Write-ColorText "[✓] Logs cleared!" "Green"
}

function Clean-All {
    Clear-Cache
    Clear-Logs
    
    # Clear session files
    $sessionPath = Join-Path $ProjectRoot "writable\session"
    if (Test-Path $sessionPath) {
        Get-ChildItem $sessionPath -Exclude ".gitkeep" | Remove-Item -Force
    }
    
    Write-ColorText "[✓] All temp files cleaned!" "Green"
}

function Show-Routes {
    Write-ColorText "[*] Listing all routes..." "Yellow"
    Set-Location $ProjectRoot
    php spark routes
}

function Show-Environment {
    Show-Banner
    Write-ColorText "  Environment Information:" "Yellow"
    Write-ColorText ""
    Write-ColorText "  PHP Version:     $(php -r 'echo PHP_VERSION;')" "Cyan"
    Write-ColorText "  Project Root:    $ProjectRoot" "Cyan"
    Write-ColorText "  Database:        SQLite" "Cyan"
    
    if (Test-Path (Join-Path $ProjectRoot ".env")) {
        $env = Get-Content (Join-Path $ProjectRoot ".env") | Where-Object { $_ -match "^CI_ENVIRONMENT" }
        Write-ColorText "  CI Environment:  $($env -replace 'CI_ENVIRONMENT\s*=\s*', '')" "Cyan"
    }
    
    Write-ColorText ""
}

function Run-Tests {
    Write-ColorText "[*] Running tests..." "Yellow"
    Set-Location $ProjectRoot
    
    if (Test-Path "vendor\bin\phpunit") {
        .\vendor\bin\phpunit
    } else {
        Write-ColorText "[!] PHPUnit not found. Run: composer require --dev phpunit/phpunit" "Red"
    }
}

# Main execution
switch ($Command.ToLower()) {
    "start"       { Start-Server }
    "stop"        { Stop-Server }
    "restart"     { Restart-Server }
    "status"      { Get-Status }
    "migrate"     { Run-Migrate }
    "seed"        { Run-Seed }
    "reset"       { Reset-Database }
    "backup"      { Backup-Database }
    "cache:clear" { Clear-Cache }
    "logs:clear"  { Clear-Logs }
    "clean"       { Clean-All }
    "routes"      { Show-Routes }
    "env"         { Show-Environment }
    "test"        { Run-Tests }
    "help"        { Show-Help }
    default       { 
        Write-ColorText "[!] Unknown command: $Command" "Red"
        Write-ColorText ""
        Show-Help 
    }
}

