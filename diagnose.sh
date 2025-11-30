#!/bin/bash

echo "======================================"
echo "ASIC Repair System - Diagnostic Tool"
echo "======================================"
echo ""

# Check if running in Docker
if [ -f /.dockerenv ]; then
    echo "✓ Running inside Docker container"
else
    echo "⚠ Not running in Docker container"
fi

echo ""
echo "1. Checking PHP Version..."
php -v | head -n 1

echo ""
echo "2. Checking Environment..."
if [ -f /var/www/html/.env ]; then
    echo "✓ .env file exists"
    echo "Environment: $(grep CI_ENVIRONMENT /var/www/html/.env | cut -d'=' -f2 | tr -d ' ')"
else
    echo "✗ .env file NOT found!"
fi

echo ""
echo "3. Checking Directory Permissions..."
ls -la /var/www/html/writable/ | head -n 5

echo ""
echo "4. Checking Error View Files..."
if [ -d /var/www/html/app/Views/errors/html ]; then
    echo "Error view directory exists:"
    ls -la /var/www/html/app/Views/errors/html/
else
    echo "✗ Error view directory NOT found!"
fi

echo ""
echo "5. Checking Nginx Configuration..."
if command -v nginx &> /dev/null; then
    nginx -t 2>&1
else
    echo "Nginx not found in this container"
fi

echo ""
echo "6. Checking PHP-FPM Status..."
if command -v php-fpm &> /dev/null; then
    ps aux | grep php-fpm | grep -v grep | head -n 3
else
    echo "PHP-FPM not found"
fi

echo ""
echo "7. Checking Database Connection..."
php -r "
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
    echo \"✓ Database connection successful\n\";
} catch (PDOException \$e) {
    echo \"✗ Database connection failed: \" . \$e->getMessage() . \"\n\";
}
"

echo ""
echo "8. Checking Recent Logs..."
if [ -f /var/www/html/writable/logs/log-$(date +%Y-%m-%d).log ]; then
    echo "Recent errors:"
    tail -n 10 /var/www/html/writable/logs/log-$(date +%Y-%m-%d).log
else
    echo "No log file found for today"
    echo "Available logs:"
    ls -lh /var/www/html/writable/logs/ 2>/dev/null || echo "No logs directory"
fi

echo ""
echo "9. Testing CodeIgniter Bootstrap..."
php -r "
define('FCPATH', '/var/www/html/public/');
chdir(FCPATH);
require FCPATH . '../app/Config/Paths.php';
\$paths = new Config\Paths();
echo 'App Directory: ' . \$paths->appDirectory . \"\n\";
echo 'View Directory: ' . \$paths->viewDirectory . \"\n\";
echo 'Writable Directory: ' . \$paths->writableDirectory . \"\n\";
"

echo ""
echo "======================================"
echo "Diagnostic Complete"
echo "======================================"
