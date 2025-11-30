#!/bin/bash

#=============================================================================
# SSL Setup Script with Let's Encrypt
# 
# This script automates SSL certificate installation using Certbot
#=============================================================================

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}"
echo "╔══════════════════════════════════════════════════════════╗"
echo "║         SSL Certificate Setup with Let's Encrypt         ║"
echo "╚══════════════════════════════════════════════════════════╝"
echo -e "${NC}"

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
    echo -e "${RED}❌ Please run as root (use sudo)${NC}"
    exit 1
fi

# Check if certbot is installed
if ! command -v certbot &> /dev/null; then
    echo -e "${YELLOW}📦 Installing Certbot...${NC}"
    apt-get update
    apt-get install -y certbot python3-certbot-nginx
fi

# Get domain from user
echo -e "${YELLOW}Enter your domain name (e.g., example.com):${NC}"
read -p "Domain: " DOMAIN

if [ -z "$DOMAIN" ]; then
    echo -e "${RED}❌ Domain cannot be empty!${NC}"
    exit 1
fi

echo -e "${YELLOW}Enter your email for SSL notifications:${NC}"
read -p "Email: " EMAIL

if [ -z "$EMAIL" ]; then
    echo -e "${RED}❌ Email cannot be empty!${NC}"
    exit 1
fi

# Stop nginx temporarily
echo -e "${YELLOW}🛑 Stopping nginx...${NC}"
docker-compose stop nginx

# Get certificate
echo -e "${YELLOW}🔐 Obtaining SSL certificate...${NC}"
certbot certonly --standalone \
    -d $DOMAIN \
    -d www.$DOMAIN \
    --non-interactive \
    --agree-tos \
    --email $EMAIL \
    --preferred-challenges http

if [ $? -ne 0 ]; then
    echo -e "${RED}❌ Failed to obtain SSL certificate!${NC}"
    echo "Please check:"
    echo "  1. Domain DNS is pointing to this server"
    echo "  2. Port 80 is accessible from the internet"
    echo "  3. No firewall blocking the connection"
    docker-compose start nginx
    exit 1
fi

# Create SSL directory if not exists
mkdir -p docker/nginx/ssl

# Copy certificates
echo -e "${YELLOW}📋 Copying certificates...${NC}"
cp /etc/letsencrypt/live/$DOMAIN/fullchain.pem docker/nginx/ssl/
cp /etc/letsencrypt/live/$DOMAIN/privkey.pem docker/nginx/ssl/

# Update nginx config
echo -e "${YELLOW}⚙️  Updating nginx configuration...${NC}"
sed -i "s/your-domain.com/$DOMAIN/g" docker/nginx/conf.d/default.conf

# Uncomment HTTPS server blocks
sed -i 's/# server {/server {/g' docker/nginx/conf.d/default.conf
sed -i 's/#     listen 443/    listen 443/g' docker/nginx/conf.d/default.conf
sed -i 's/#     server_name/    server_name/g' docker/nginx/conf.d/default.conf
sed -i 's/#     root/    root/g' docker/nginx/conf.d/default.conf
sed -i 's/#     index/    index/g' docker/nginx/conf.d/default.conf
sed -i 's/#     ssl_/    ssl_/g' docker/nginx/conf.d/default.conf
sed -i 's/#     add_header/    add_header/g' docker/nginx/conf.d/default.conf
sed -i 's/# }/}/g' docker/nginx/conf.d/default.conf

# Update .env base URL
echo -e "${YELLOW}🔧 Updating .env with HTTPS URL...${NC}"
sed -i "s|app.baseURL = 'http://.*'|app.baseURL = 'https://$DOMAIN/'|g" .env
sed -i "s|app.forceGlobalSecureRequests = false|app.forceGlobalSecureRequests = true|g" .env

# Restart nginx
echo -e "${YELLOW}🔄 Restarting nginx...${NC}"
docker-compose start nginx

# Test nginx configuration
echo -e "${YELLOW}🧪 Testing nginx configuration...${NC}"
docker-compose exec nginx nginx -t

if [ $? -eq 0 ]; then
    docker-compose restart nginx
fi

# Setup auto-renewal
echo -e "${YELLOW}⏰ Setting up automatic renewal...${NC}"
(crontab -l 2>/dev/null | grep -v "certbot renew"; echo "0 3 * * * certbot renew --quiet --post-hook 'cd $(pwd) && docker-compose exec nginx nginx -s reload'") | crontab -

echo -e "${GREEN}"
echo "╔══════════════════════════════════════════════════════════╗"
echo "║           ✅ SSL Certificate Installed Successfully!     ║"
echo "╚══════════════════════════════════════════════════════════╝"
echo -e "${NC}"

echo -e "${BLUE}🎉 Your site is now accessible at:${NC}"
echo -e "   ${GREEN}https://$DOMAIN${NC}"
echo -e "   ${GREEN}https://www.$DOMAIN${NC}"
echo ""
echo -e "${YELLOW}ℹ️  Certificate will auto-renew every 90 days${NC}"
echo ""

