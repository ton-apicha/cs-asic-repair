#!/bin/bash

#=============================================================================
# ASIC Repair - Quick Deployment Script for Digital Ocean
# 
# Usage: curl -sSL https://raw.githubusercontent.com/ton-apicha/cs-asic-repair/main/quick-deploy.sh | bash
# Or: bash <(curl -sSL https://raw.githubusercontent.com/ton-apicha/cs-asic-repair/main/quick-deploy.sh)
#=============================================================================

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
BOLD='\033[1m'
NC='\033[0m' # No Color

# Stop on error
set -e

clear

echo -e "${BLUE}${BOLD}"
echo "╔════════════════════════════════════════════════════════════════╗"
echo "║                                                                ║"
echo "║        🔧 ASIC Repair Management System                        ║"
echo "║        Quick Deployment for Digital Ocean                      ║"
echo "║                                                                ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo -e "${NC}"
echo ""

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo -e "${RED}❌ Docker is not installed!${NC}"
    echo -e "${YELLOW}Installing Docker...${NC}"
    curl -fsSL https://get.docker.com -o get-docker.sh
    sh get-docker.sh
    apt-get install -y docker-compose
    rm get-docker.sh
fi

# Check if Git is installed
if ! command -v git &> /dev/null; then
    echo -e "${YELLOW}Installing Git...${NC}"
    apt-get update && apt-get install -y git
fi

# Get server IP
SERVER_IP=$(hostname -I | awk '{print $1}')
echo -e "${CYAN}📡 Server IP detected: ${GREEN}${SERVER_IP}${NC}"
echo ""

# Setup directory
INSTALL_DIR="/var/www/cs-asic-repair"

# Remove old installation if exists
if [ -d "$INSTALL_DIR" ]; then
    echo -e "${YELLOW}⚠️  Previous installation found. Removing...${NC}"
    rm -rf $INSTALL_DIR
fi

# Create directory
mkdir -p /var/www
cd /var/www

# Clone repository
echo -e "${YELLOW}📦 Cloning repository...${NC}"
git clone https://github.com/ton-apicha/cs-asic-repair.git
cd cs-asic-repair

# Copy environment file
cp env.production.example .env

echo -e "${BLUE}${BOLD}"
echo "╔════════════════════════════════════════════════════════════════╗"
echo "║                  ⚙️  Configuration Setup                       ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo -e "${NC}"
echo ""

# Generate random strong passwords
DB_PASS=$(openssl rand -base64 24 | tr -d "=+/" | cut -c1-24)
ROOT_PASS=$(openssl rand -base64 24 | tr -d "=+/" | cut -c1-24)
ENC_KEY=$(openssl rand -hex 32)

echo -e "${GREEN}🔐 Auto-generated secure passwords:${NC}"
echo -e "   Database Password: ${CYAN}${DB_PASS}${NC}"
echo -e "   Root Password: ${CYAN}${ROOT_PASS}${NC}"
echo ""
echo -e "${YELLOW}💡 These will be saved in .env file${NC}"
echo ""

# Ask for domain or use IP
echo -e "${YELLOW}🌐 Domain Setup:${NC}"
echo "   Do you have a domain name? (leave empty to use IP address)"
read -p "   Domain (or press Enter): " DOMAIN

if [ -z "$DOMAIN" ]; then
    DOMAIN="http://${SERVER_IP}"
    echo -e "${CYAN}   Using IP: ${GREEN}${DOMAIN}${NC}"
else
    DOMAIN="http://${DOMAIN}"
    echo -e "${CYAN}   Using domain: ${GREEN}${DOMAIN}${NC}"
fi

# Update .env file
echo -e "${YELLOW}📝 Configuring .env file...${NC}"
sed -i "s|app.baseURL = 'https://your-domain.com/'|app.baseURL = '${DOMAIN}/'|g" .env
sed -i "s|database.default.password = CHANGE_THIS_STRONG_PASSWORD|database.default.password = ${DB_PASS}|g" .env
sed -i "s|DB_ROOT_PASSWORD = CHANGE_THIS_ROOT_PASSWORD|DB_ROOT_PASSWORD = ${ROOT_PASS}|g" .env
sed -i "s|encryption.key = CHANGE_THIS_32_CHARACTER_KEY_HERE|encryption.key = hex2bin:${ENC_KEY}|g" .env

# If using IP, disable force HTTPS
if [[ $DOMAIN == http://* ]]; then
    sed -i "s|app.forceGlobalSecureRequests = true|app.forceGlobalSecureRequests = false|g" .env
fi

# Make scripts executable
chmod +x deploy.sh setup-ssl.sh backup-db.sh

echo ""
echo -e "${BLUE}${BOLD}"
echo "╔════════════════════════════════════════════════════════════════╗"
echo "║                  🚀 Starting Deployment...                     ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo -e "${NC}"
echo ""

# Run deployment
./deploy.sh

echo ""
echo -e "${GREEN}${BOLD}"
echo "╔════════════════════════════════════════════════════════════════╗"
echo "║                                                                ║"
echo "║              ✅ DEPLOYMENT SUCCESSFUL! 🎉                      ║"
echo "║                                                                ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo -e "${NC}"
echo ""

echo -e "${BLUE}${BOLD}📱 Application Information:${NC}"
echo -e "   ${BOLD}URL:${NC}      ${GREEN}${DOMAIN}${NC}"
echo -e "   ${BOLD}Login:${NC}    ${GREEN}superadmin${NC}"
echo -e "   ${BOLD}Password:${NC} ${GREEN}super123${NC}"
echo ""

echo -e "${YELLOW}${BOLD}⚠️  IMPORTANT - Do These Now:${NC}"
echo -e "   1. ${BOLD}Login and change password immediately!${NC}"
echo -e "   2. Test the application"
echo -e "   3. Go to Settings and configure your company info"
echo ""

if [[ $DOMAIN == http://* ]]; then
    echo -e "${YELLOW}🔒 Setup SSL Certificate (Recommended):${NC}"
    echo -e "   ${BOLD}After configuring your domain DNS:${NC}"
    echo -e "   ${CYAN}sudo ./setup-ssl.sh${NC}"
    echo ""
fi

echo -e "${BLUE}${BOLD}🛠️  Useful Commands:${NC}"
echo -e "   View logs:        ${CYAN}docker-compose logs -f app${NC}"
echo -e "   Restart:          ${CYAN}docker-compose restart${NC}"
echo -e "   Backup database:  ${CYAN}./backup-db.sh${NC}"
echo -e "   Stop:             ${CYAN}docker-compose down${NC}"
echo ""

echo -e "${GREEN}${BOLD}🎊 Your ASIC Repair System is now live!${NC}"
echo ""

# Save credentials to file
cat > /root/CREDENTIALS.txt << EOF
ASIC Repair Management System - Deployment Info
================================================

Server IP: ${SERVER_IP}
URL: ${DOMAIN}

Application Login:
  Username: superadmin
  Password: super123

Database Credentials:
  Host: localhost:3306
  Database: asic_repair_db
  Username: asic_user
  Password: ${DB_PASS}
  Root Password: ${ROOT_PASS}

Encryption Key: hex2bin:${ENC_KEY}

Deployment Date: $(date)

IMPORTANT: Change Super Admin password immediately after first login!
EOF

echo -e "${YELLOW}💾 Credentials saved to: ${CYAN}/root/CREDENTIALS.txt${NC}"
echo -e "${YELLOW}   View anytime: ${CYAN}cat /root/CREDENTIALS.txt${NC}"
echo ""

