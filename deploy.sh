#!/bin/bash

#=============================================================================
# ASIC Repair Management System - Deployment Script
# 
# This script automates the deployment process for Digital Ocean
#=============================================================================

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Stop on error
set -e

echo -e "${BLUE}"
echo "╔══════════════════════════════════════════════════════════╗"
echo "║   ASIC Repair Management System - Deployment Script     ║"
echo "╚══════════════════════════════════════════════════════════╝"
echo -e "${NC}"

# Check if .env exists
if [ ! -f .env ]; then
    echo -e "${RED}❌ Error: .env file not found!${NC}"
    echo -e "${YELLOW}Please copy .env.production to .env and configure it first:${NC}"
    echo "  cp .env.production .env"
    echo "  nano .env"
    echo ""
    echo "Don't forget to set:"
    echo "  - Database passwords"
    echo "  - Encryption key (run: php spark key:generate)"
    echo "  - Base URL"
    exit 1
fi

echo -e "${YELLOW}📦 Step 1: Pulling latest code...${NC}"
git pull origin main || echo "Warning: Could not pull latest code. Continuing..."

echo -e "${YELLOW}🐳 Step 2: Building Docker containers...${NC}"
docker compose down --remove-orphans
docker compose build --no-cache

echo -e "${YELLOW}🚀 Step 3: Starting containers...${NC}"
docker compose up -d

echo -e "${YELLOW}⏳ Waiting for services to be ready...${NC}"
sleep 10

echo -e "${YELLOW}📚 Step 4: Installing Composer dependencies...${NC}"
docker compose exec -T app composer install --no-dev --optimize-autoloader

echo -e "${YELLOW}🗄️  Step 5: Running database migrations...${NC}"
docker compose exec -T app php spark migrate || echo "Warning: Migrations may have already been run"

echo -e "${YELLOW}🔐 Step 6: Setting permissions...${NC}"
docker compose exec -T app chown -R www-data:www-data /var/www/html/writable
docker compose exec -T app chmod -R 775 /var/www/html/writable

echo -e "${YELLOW}👤 Step 7: Creating/Resetting Super Admin account...${NC}"
docker compose exec -T app php spark user:create-superadmin

echo -e "${YELLOW}🧹 Step 8: Clearing cache...${NC}"
docker compose exec -T app php spark cache:clear

echo -e "${GREEN}"
echo "╔══════════════════════════════════════════════════════════╗"
echo "║              ✅ Deployment Completed Successfully!       ║"
echo "╚══════════════════════════════════════════════════════════╝"
echo -e "${NC}"

# Get server IP
SERVER_IP=$(hostname -I | awk '{print $1}')

echo -e "${BLUE}📱 Application Information:${NC}"
echo -e "   URL: ${GREEN}http://$SERVER_IP${NC}"
echo -e "   Login: ${GREEN}superadmin${NC}"
echo -e "   Password: ${GREEN}super123${NC}"
echo ""
echo -e "${YELLOW}⚠️  Important Next Steps:${NC}"
echo "   1. Change Super Admin password immediately"
echo "   2. Setup SSL certificate (run: ./setup-ssl.sh)"
echo "   3. Configure your domain DNS to point to: $SERVER_IP"
echo "   4. Review and update .env file for production settings"
echo ""
echo -e "${BLUE}📊 Useful Commands:${NC}"
echo "   View logs:        docker compose logs -f app"
echo "   Restart:          docker compose restart"
echo "   Stop:             docker compose down"
echo "   Backup database:  ./backup-db.sh"
echo ""

