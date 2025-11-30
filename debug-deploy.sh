#!/bin/bash

echo "🔍 Debugging ASIC Repair Deployment..."
echo ""

cd /var/www/cs-asic-repair

echo "1️⃣ Checking containers status..."
docker compose ps

echo ""
echo "2️⃣ Testing PHP-FPM connection..."
docker compose exec -T nginx wget -qO- --timeout=2 http://app:9000 2>&1 | head -3 || echo "✅ PHP-FPM is reachable on port 9000"

echo ""
echo "3️⃣ Checking application logs (last 30 lines)..."
docker compose logs app --tail=30

echo ""
echo "4️⃣ Checking nginx error logs..."
docker compose exec -T nginx tail -20 /var/log/nginx/asic-error.log 2>/dev/null || echo "No error log yet"

echo ""
echo "5️⃣ Testing direct PHP execution..."
docker compose exec -T app php -r "require '/var/www/html/public/index.php';" 2>&1 | head -20

echo ""
echo "6️⃣ Checking file permissions..."
docker compose exec -T app ls -la /var/www/html/public/index.php
docker compose exec -T app ls -ld /var/www/html/writable

echo ""
echo "✅ Debug complete! Check output above for errors."
echo ""
echo "🌐 Try accessing: http://152.42.201.200"

