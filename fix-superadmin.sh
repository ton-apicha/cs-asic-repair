#!/bin/bash

echo "=================================="
echo "Fix Superadmin Role on Production"
echo "=================================="
echo ""

# SSH to server and run the command
ssh root@152.42.201.200 << 'EOF'
cd /var/www/cs-asic-repair
docker compose exec -T app php spark user:create-superadmin
EOF

echo ""
echo "=================================="
echo "Done! Please logout and login again as superadmin"
echo "=================================="
