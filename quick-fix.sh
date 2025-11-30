#!/bin/bash

echo "======================================"
echo "ASIC Repair - Quick Fix Script"
echo "======================================"
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

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

# Check if .env exists
if [ ! -f .env ]; then
    print_error ".env file not found!"
    echo "Creating .env from template..."
    cp env.production.example .env
    print_warning "Please edit .env file with your settings!"
    exit 1
fi

print_status "Found .env file"

# Fix permissions
echo ""
echo "1. Fixing file permissions..."
docker compose exec app chown -R www-data:www-data /var/www/html/writable
docker compose exec app chmod -R 775 /var/www/html/writable
print_status "Permissions fixed"

# Clear cache
echo ""
echo "2. Clearing cache..."
docker compose exec app php spark cache:clear
print_status "Cache cleared"

# Check if error views exist
echo ""
echo "3. Checking error view files..."
docker compose exec app ls -la /var/www/html/app/Views/errors/html/
print_status "Error views checked"

# Restart services
echo ""
echo "4. Restarting services..."
docker compose restart app
docker compose restart nginx
print_status "Services restarted"

# Wait for services to be ready
echo ""
echo "5. Waiting for services to be ready..."
sleep 5

# Test database connection
echo ""
echo "6. Testing database connection..."
docker compose exec app php -r "
\$env = file_get_contents('/var/www/html/.env');
preg_match('/database\.default\.hostname\s*=\s*(.+)/', \$env, \$host);
preg_match('/database\.default\.database\s*=\s*(.+)/', \$env, \$db);
preg_match('/database\.default\.username\s*=\s*(.+)/', \$env, \$user);
preg_match('/database\.default\.password\s*=\s*(.+)/', \$env, \$pass);

\$host = trim(\$host[1] ?? 'db');
\$db = trim(\$db[1] ?? 'asic_repair_db');
\$user = trim(\$user[1] ?? 'asic_user');
\$pass = trim(\$pass[1] ?? '');

try {
    \$pdo = new PDO(\"mysql:host=\$host;dbname=\$db\", \$user, \$pass);
    echo \"Database connection successful\n\";
    exit(0);
} catch (PDOException \$e) {
    echo \"Database connection failed: \" . \$e->getMessage() . \"\n\";
    exit(1);
}
"

if [ $? -eq 0 ]; then
    print_status "Database connection OK"
else
    print_error "Database connection failed"
fi

# Check logs
echo ""
echo "7. Checking recent logs..."
docker compose exec app tail -n 20 /var/www/html/writable/logs/log-$(date +%Y-%m-%d).log 2>/dev/null || echo "No logs for today"

echo ""
echo "======================================"
print_status "Quick fix completed!"
echo "======================================"
echo ""
echo "Next steps:"
echo "1. Visit your site in browser"
echo "2. Check logs: docker compose logs -f app"
echo "3. Check nginx logs: docker compose logs -f nginx"
echo ""
