#!/bin/bash
# ============================================
# ASIC Repair System - Server Management Script
# Bash Script for Linux/macOS
# ============================================

set -e

# Configuration
PORT=${PORT:-8080}
HOST=${HOST:-localhost}
PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PID_FILE="$PROJECT_ROOT/writable/.server.pid"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
MAGENTA='\033[0;35m'
NC='\033[0m' # No Color

# Functions
print_color() {
    echo -e "${2}${1}${NC}"
}

show_banner() {
    echo ""
    print_color "  ╔══════════════════════════════════════════════╗" "$CYAN"
    print_color "  ║       ASIC Repair System - Server CLI        ║" "$CYAN"
    print_color "  ╚══════════════════════════════════════════════╝" "$CYAN"
    echo ""
}

show_help() {
    show_banner
    print_color "  Available Commands:" "$YELLOW"
    echo ""
    print_color "  Server Management:" "$GREEN"
    echo "    start        - Start development server"
    echo "    stop         - Stop development server"
    echo "    restart      - Restart development server"
    echo "    status       - Check server status"
    echo ""
    print_color "  Database:" "$GREEN"
    echo "    migrate      - Run database migrations"
    echo "    seed         - Run database seeders"
    echo "    reset        - Reset database (migrate:refresh + seed)"
    echo "    backup       - Create database backup"
    echo ""
    print_color "  Maintenance:" "$GREEN"
    echo "    cache:clear  - Clear all caches"
    echo "    logs:clear   - Clear log files"
    echo "    clean        - Clean all temp files"
    echo ""
    print_color "  Development:" "$GREEN"
    echo "    routes       - List all routes"
    echo "    env          - Show environment info"
    echo "    test         - Run tests"
    echo ""
    print_color "  Environment Variables:" "$YELLOW"
    echo "    PORT=8080    Server port (default: 8080)"
    echo "    HOST=0.0.0.0 Server host (default: localhost)"
    echo ""
    print_color "  Examples:" "$MAGENTA"
    echo "    ./server.sh start"
    echo "    PORT=9000 ./server.sh start"
    echo "    ./server.sh migrate"
    echo ""
}

is_running() {
    if [ -f "$PID_FILE" ]; then
        PID=$(cat "$PID_FILE")
        if ps -p "$PID" > /dev/null 2>&1; then
            return 0
        fi
    fi
    return 1
}

start_server() {
    print_color "[*] Starting development server..." "$YELLOW"
    
    if is_running; then
        PID=$(cat "$PID_FILE")
        print_color "[!] Server is already running (PID: $PID)" "$RED"
        exit 1
    fi
    
    cd "$PROJECT_ROOT"
    
    # Start server in background
    nohup php spark serve --host="$HOST" --port="$PORT" > "$PROJECT_ROOT/writable/logs/server.log" 2>&1 &
    echo $! > "$PID_FILE"
    
    sleep 2
    
    if is_running; then
        print_color "[✓] Server started successfully!" "$GREEN"
        print_color "    URL: http://${HOST}:${PORT}" "$CYAN"
        print_color "    PID: $(cat $PID_FILE)" "$CYAN"
        print_color "    Log: writable/logs/server.log" "$CYAN"
        echo ""
        print_color "    To stop: ./server.sh stop" "$YELLOW"
    else
        print_color "[!] Failed to start server. Check logs." "$RED"
        exit 1
    fi
}

stop_server() {
    print_color "[*] Stopping development server..." "$YELLOW"
    
    if is_running; then
        PID=$(cat "$PID_FILE")
        kill "$PID" 2>/dev/null || true
        rm -f "$PID_FILE"
        print_color "[✓] Server stopped successfully!" "$GREEN"
    else
        print_color "[!] No server process found" "$YELLOW"
        rm -f "$PID_FILE"
    fi
}

restart_server() {
    stop_server
    sleep 1
    start_server
}

show_status() {
    show_banner
    
    if is_running; then
        PID=$(cat "$PID_FILE")
        print_color "  Server Status: RUNNING" "$GREEN"
        echo ""
        print_color "    PID: $PID" "$CYAN"
        
        # Get memory usage
        if command -v ps &> /dev/null; then
            MEM=$(ps -o rss= -p "$PID" 2>/dev/null || echo "0")
            MEM_MB=$((MEM / 1024))
            print_color "    Memory: ${MEM_MB} MB" "$CYAN"
        fi
    else
        print_color "  Server Status: STOPPED" "$RED"
    fi
    
    echo ""
    
    # Environment check
    if [ -f "$PROJECT_ROOT/.env" ]; then
        print_color "  Environment: Configured" "$YELLOW"
    else
        print_color "  Environment: Not Found" "$RED"
    fi
    
    print_color "  PHP Version: $(php -r 'echo PHP_VERSION;')" "$CYAN"
    echo ""
}

run_migrate() {
    print_color "[*] Running database migrations..." "$YELLOW"
    cd "$PROJECT_ROOT"
    php spark migrate
    print_color "[✓] Migrations completed!" "$GREEN"
}

run_seed() {
    print_color "[*] Running database seeders..." "$YELLOW"
    cd "$PROJECT_ROOT"
    php spark db:seed DatabaseSeeder
    print_color "[✓] Seeding completed!" "$GREEN"
}

reset_database() {
    print_color "[!] WARNING: This will reset all data!" "$RED"
    read -p "Are you sure? (yes/no): " confirm
    
    if [ "$confirm" = "yes" ]; then
        print_color "[*] Resetting database..." "$YELLOW"
        cd "$PROJECT_ROOT"
        php spark migrate:refresh
        php spark db:seed DatabaseSeeder
        print_color "[✓] Database reset completed!" "$GREEN"
    else
        print_color "[*] Operation cancelled" "$YELLOW"
    fi
}

backup_database() {
    TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
    BACKUP_DIR="$PROJECT_ROOT/writable/backups"
    DB_PATH="$PROJECT_ROOT/writable/database/database.db"
    
    mkdir -p "$BACKUP_DIR"
    
    BACKUP_FILE="$BACKUP_DIR/backup_${TIMESTAMP}.db"
    
    print_color "[*] Creating database backup..." "$YELLOW"
    cp "$DB_PATH" "$BACKUP_FILE"
    print_color "[✓] Backup created: $BACKUP_FILE" "$GREEN"
}

clear_cache() {
    print_color "[*] Clearing caches..." "$YELLOW"
    
    CACHE_PATH="$PROJECT_ROOT/writable/cache"
    if [ -d "$CACHE_PATH" ]; then
        find "$CACHE_PATH" -type f ! -name ".gitkeep" -delete
    fi
    
    print_color "[✓] Cache cleared!" "$GREEN"
}

clear_logs() {
    print_color "[*] Clearing log files..." "$YELLOW"
    
    LOGS_PATH="$PROJECT_ROOT/writable/logs"
    if [ -d "$LOGS_PATH" ]; then
        find "$LOGS_PATH" -type f -name "*.log" -delete
    fi
    
    print_color "[✓] Logs cleared!" "$GREEN"
}

clean_all() {
    clear_cache
    clear_logs
    
    # Clear session files
    SESSION_PATH="$PROJECT_ROOT/writable/session"
    if [ -d "$SESSION_PATH" ]; then
        find "$SESSION_PATH" -type f ! -name ".gitkeep" -delete
    fi
    
    print_color "[✓] All temp files cleaned!" "$GREEN"
}

show_routes() {
    print_color "[*] Listing all routes..." "$YELLOW"
    cd "$PROJECT_ROOT"
    php spark routes
}

show_environment() {
    show_banner
    print_color "  Environment Information:" "$YELLOW"
    echo ""
    print_color "  PHP Version:     $(php -r 'echo PHP_VERSION;')" "$CYAN"
    print_color "  Project Root:    $PROJECT_ROOT" "$CYAN"
    print_color "  Database:        SQLite" "$CYAN"
    
    if [ -f "$PROJECT_ROOT/.env" ]; then
        ENV_VALUE=$(grep "^CI_ENVIRONMENT" "$PROJECT_ROOT/.env" | cut -d'=' -f2)
        print_color "  CI Environment:  $ENV_VALUE" "$CYAN"
    fi
    
    echo ""
}

run_tests() {
    print_color "[*] Running tests..." "$YELLOW"
    cd "$PROJECT_ROOT"
    
    if [ -f "vendor/bin/phpunit" ]; then
        ./vendor/bin/phpunit
    else
        print_color "[!] PHPUnit not found. Run: composer require --dev phpunit/phpunit" "$RED"
    fi
}

# Main execution
case "${1:-help}" in
    start)       start_server ;;
    stop)        stop_server ;;
    restart)     restart_server ;;
    status)      show_status ;;
    migrate)     run_migrate ;;
    seed)        run_seed ;;
    reset)       reset_database ;;
    backup)      backup_database ;;
    cache:clear) clear_cache ;;
    logs:clear)  clear_logs ;;
    clean)       clean_all ;;
    routes)      show_routes ;;
    env)         show_environment ;;
    test)        run_tests ;;
    help)        show_help ;;
    *)
        print_color "[!] Unknown command: $1" "$RED"
        echo ""
        show_help
        ;;
esac

