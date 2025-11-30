#!/bin/bash

# ========================================
# Rebuild Docker Image - Fix MySQLi Extension
# ========================================

set -e

echo "======================================"
echo "Rebuilding Docker Image"
echo "======================================"
echo ""

# Stop containers
echo "1. Stopping containers..."
docker compose down

# Rebuild without cache
echo ""
echo "2. Rebuilding image (this may take a few minutes)..."
docker compose build --no-cache app

# Start containers
echo ""
echo "3. Starting containers..."
docker compose up -d

# Wait for services
echo ""
echo "4. Waiting for services to be ready..."
sleep 10

# Verify mysqli extension
echo ""
echo "5. Verifying mysqli extension..."
docker compose exec app php -m | grep mysqli

if [ $? -eq 0 ]; then
    echo "✓ mysqli extension is loaded!"
else
    echo "✗ mysqli extension is NOT loaded!"
    exit 1
fi

# Test database connection
echo ""
echo "6. Testing database connection..."
docker compose exec app php -r "
\$mysqli = new mysqli('db', 'asic_user', getenv('DB_PASSWORD') ?: 'password', 'asic_repair_db');
if (\$mysqli->connect_error) {
    die('Connection failed: ' . \$mysqli->connect_error);
}
echo 'Database connected successfully!';
\$mysqli->close();
"

echo ""
echo "======================================"
echo "✓ Rebuild completed!"
echo "======================================"
echo ""
echo "Next steps:"
echo "1. Visit your site in browser"
echo "2. Check logs: docker compose logs -f app"
echo ""
