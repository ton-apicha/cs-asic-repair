#!/bin/bash

echo "=================================="
echo "Testing Admin Customer Access"
echo "=================================="
echo ""

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Test 1: Check customer data
echo -e "${YELLOW}Test 1: Checking customer ID 2 data...${NC}"
docker compose exec db mysql -u root -pRootc34a3ad25b2107c48f09!Sec asic_repair_db -e "SELECT id, name, branch_id FROM customers WHERE id=2;" 2>/dev/null | grep -v "Warning"
echo ""

# Test 2: Check admin user data
echo -e "${YELLOW}Test 2: Checking admin user data...${NC}"
docker compose exec db mysql -u root -pRootc34a3ad25b2107c48f09!Sec asic_repair_db -e "SELECT id, username, role, branch_id FROM users WHERE username='admin';" 2>/dev/null | grep -v "Warning"
echo ""

# Test 3: Check superadmin user data
echo -e "${YELLOW}Test 3: Checking superadmin user data...${NC}"
docker compose exec db mysql -u root -pRootc34a3ad25b2107c48f09!Sec asic_repair_db -e "SELECT id, username, role, branch_id FROM users WHERE username='superadmin';" 2>/dev/null | grep -v "Warning"
echo ""

# Test 4: Test customer view endpoint
echo -e "${YELLOW}Test 4: Testing customer view endpoint (as guest)...${NC}"
curl -s -o /dev/null -w "HTTP Status: %{http_code}\n" http://152.42.201.200/customers/view/2
echo ""

# Test 5: Check application logs for errors
echo -e "${YELLOW}Test 5: Checking recent application logs...${NC}"
docker compose logs --tail=20 app 2>/dev/null | grep -i "error\|exception\|denied" || echo "No errors found"
echo ""

echo -e "${GREEN}=================================="
echo "Test completed!"
echo "==================================${NC}"
