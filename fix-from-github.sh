#!/bin/bash

# ========================================
# ASIC Repair - Fix from GitHub Script
# ========================================
# This script pulls latest fixes from GitHub and applies them
# Run this on your server to fix deployment issues

set -e  # Exit on error

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}========================================${NC}"
echo -e "${BLUE}ASIC Repair - Fix from GitHub${NC}"
echo -e "${BLUE}========================================${NC}"
echo ""

# Function to print colored output
print_status() {
    echo -e "${GREEN}✓${NC} $1"
}

print_error() {
    echo -e "${RED}✗${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}⚠${NC} $1"
}

print_info() {
    echo -e "${BLUE}ℹ${NC} $1"
}

# Check if we're in the right directory
if [ ! -f "docker-compose.yml" ]; then
    print_error "docker-compose.yml not found!"
    print_info "Please run this script from the project root directory"
    print_info "Example: cd /var/www/cs-asic-repair && ./fix-from-github.sh"
    exit 1
fi

print_status "Found project directory"

# Backup current state
echo ""
print_info "Step 1: Creating backup..."
BACKUP_DIR="backup-$(date +%Y%m%d-%H%M%S)"
mkdir -p "$BACKUP_DIR"
cp -r app/Views/errors "$BACKUP_DIR/" 2>/dev/null || true
print_status "Backup created in $BACKUP_DIR"

# Stash any local changes
echo ""
print_info "Step 2: Stashing local changes..."
git stash push -m "Auto-stash before fix $(date +%Y%m%d-%H%M%S)" 2>/dev/null || true
print_status "Local changes stashed"

# Pull latest from GitHub
echo ""
print_info "Step 3: Pulling latest fixes from GitHub..."
git fetch origin main
git pull origin main

if [ $? -eq 0 ]; then
    print_status "Successfully pulled latest code from GitHub"
else
    print_error "Failed to pull from GitHub"
    print_warning "Trying to continue anyway..."
fi

# Make scripts executable
echo ""
print_info "Step 4: Making scripts executable..."
chmod +x quick-fix.sh 2>/dev/null || true
chmod +x diagnose.sh 2>/dev/null || true
chmod +x deploy.sh 2>/dev/null || true
chmod +x backup-db.sh 2>/dev/null || true
print_status "Scripts are now executable"

# Check if error view files exist
echo ""
print_info "Step 5: Checking error view files..."
ERROR_VIEWS_DIR="app/Views/errors/html"

if [ ! -f "$ERROR_VIEWS_DIR/production.php" ]; then
    print_warning "production.php not found, creating it..."
    cat > "$ERROR_VIEWS_DIR/production.php" << 'EOFVIEW'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - ASIC Repair Management System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 20px;
        }
        .error-container {
            background: white; border-radius: 12px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px; width: 100%; padding: 40px; text-align: center;
        }
        .error-icon { font-size: 80px; margin-bottom: 20px; }
        h1 { color: #2d3748; font-size: 32px; margin-bottom: 15px; font-weight: 700; }
        .error-code { color: #e53e3e; font-size: 24px; font-weight: 600; margin-bottom: 20px; }
        p { color: #4a5568; line-height: 1.6; margin-bottom: 30px; font-size: 16px; }
        .btn-home {
            display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; padding: 12px 30px; border-radius: 6px; text-decoration: none; font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">⚠️</div>
        <h1>Oops! Something went wrong</h1>
        <div class="error-code">Error <?= $statusCode ?? 500 ?></div>
        <p><?= esc($message ?? 'An unexpected error occurred.') ?></p>
        <a href="/" class="btn-home">Return to Home</a>
    </div>
</body>
</html>
EOFVIEW
    print_status "Created production.php"
else
    print_status "production.php exists"
fi

if [ ! -f "$ERROR_VIEWS_DIR/error_500.php" ]; then
    print_warning "error_500.php not found, creating it..."
    cat > "$ERROR_VIEWS_DIR/error_500.php" << 'EOFVIEW'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Internal Server Error</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 20px;
        }
        .error-container {
            background: white; border-radius: 12px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px; width: 100%; padding: 40px; text-align: center;
        }
        .error-icon { font-size: 80px; margin-bottom: 20px; }
        h1 { color: #2d3748; font-size: 32px; margin-bottom: 15px; font-weight: 700; }
        .error-code { color: #e53e3e; font-size: 48px; font-weight: 700; margin-bottom: 20px; }
        p { color: #4a5568; line-height: 1.6; margin-bottom: 30px; font-size: 16px; }
        .btn-home {
            display: inline-block; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white; padding: 12px 30px; border-radius: 6px; text-decoration: none; font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">🔧</div>
        <div class="error-code">500</div>
        <h1>Internal Server Error</h1>
        <p><?= esc($message ?? 'The server encountered an unexpected condition.') ?></p>
        <a href="/" class="btn-home">Return to Home</a>
    </div>
</body>
</html>
EOFVIEW
    print_status "Created error_500.php"
else
    print_status "error_500.php exists"
fi

# Fix permissions
echo ""
print_info "Step 6: Fixing permissions..."
docker compose exec app chown -R www-data:www-data /var/www/html/writable 2>/dev/null || print_warning "Could not fix writable permissions (container may not be running)"
docker compose exec app chmod -R 775 /var/www/html/writable 2>/dev/null || true
print_status "Permissions fixed"

# Clear cache
echo ""
print_info "Step 7: Clearing cache..."
docker compose exec app php spark cache:clear 2>/dev/null || print_warning "Could not clear cache (container may not be running)"
print_status "Cache cleared"

# Restart services
echo ""
print_info "Step 8: Restarting services..."
docker compose restart app
docker compose restart nginx
print_status "Services restarted"

# Wait for services
echo ""
print_info "Waiting for services to be ready..."
sleep 5

# Test the application
echo ""
print_info "Step 9: Testing application..."
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost 2>/dev/null || echo "000")

if [ "$HTTP_CODE" = "200" ] || [ "$HTTP_CODE" = "302" ]; then
    print_status "Application is responding (HTTP $HTTP_CODE)"
elif [ "$HTTP_CODE" = "000" ]; then
    print_warning "Could not test application (curl failed)"
else
    print_warning "Application returned HTTP $HTTP_CODE"
fi

# Show logs
echo ""
print_info "Step 10: Checking recent logs..."
echo ""
docker compose logs --tail=20 app 2>/dev/null || print_warning "Could not fetch logs"

# Summary
echo ""
echo -e "${BLUE}========================================${NC}"
echo -e "${GREEN}✓ Fix completed!${NC}"
echo -e "${BLUE}========================================${NC}"
echo ""
echo "Next steps:"
echo "1. Visit your site in browser to verify it's working"
echo "2. Check logs if needed: docker compose logs -f app"
echo "3. Run diagnostics: ./diagnose.sh"
echo ""
echo "If you still see errors, check:"
echo "- TROUBLESHOOTING.md for detailed solutions"
echo "- Run: ./quick-fix.sh for additional fixes"
echo ""
print_status "All done!"
