# 🚀 ASIC Repair Management System - Deployment Guide

Complete guide for deploying to Digital Ocean using Docker.

---

## 📋 Prerequisites

- Digital Ocean account
- Domain name (optional but recommended)
- Basic knowledge of SSH and command line

---

## 🖥️ Step 1: Create Digital Ocean Droplet

### Option A: Using Docker Marketplace Image (Recommended)

1. Go to Digital Ocean → Create → Droplets
2. Choose: **Docker on Ubuntu 22.04 LTS** from Marketplace
3. Select plan:
   - **Basic**: $12/month (2GB RAM, 1 vCPU) - Minimum
   - **Recommended**: $24/month (4GB RAM, 2 vCPU) - Better performance
4. Choose datacenter: **Singapore** (closest to Thailand)
5. Add your SSH key
6. Create Droplet

### Option B: Manual Setup

```bash
# Create Ubuntu 22.04 Droplet, then SSH in and run:
curl -fsSL https://get.docker.com -o get-docker.sh
sh get-docker.sh
apt-get install -y docker compose git
```

---

## 🔧 Step 2: Initial Server Setup

### Connect to your server:

```bash
ssh root@YOUR_SERVER_IP
```

### Update system:

```bash
apt-get update && apt-get upgrade -y
```

### Install required packages:

```bash
apt-get install -y git curl nano ufw
```

### Setup firewall:

```bash
# Allow SSH
ufw allow 22/tcp

# Allow HTTP & HTTPS
ufw allow 80/tcp
ufw allow 443/tcp

# Enable firewall
ufw --force enable
```

---

## 📦 Step 3: Clone and Setup Application

### Clone repository:

```bash
cd /var/www
git clone https://github.com/ton-apicha/cs-asic-repair.git
cd cs-asic-repair
```

### Setup environment file:

```bash
# Copy template
cp env.production.example .env

# Edit configuration
nano .env
```

**Important settings to change in `.env`:**

```env
# Change domain
app.baseURL = 'https://your-domain.com/'

# Generate strong passwords
database.default.password = YOUR_STRONG_DB_PASSWORD
DB_ROOT_PASSWORD = YOUR_STRONG_ROOT_PASSWORD

# Generate encryption key (after deployment):
# docker compose exec app php spark key:generate
encryption.key = YOUR_32_CHAR_ENCRYPTION_KEY
```

### Make scripts executable:

```bash
chmod +x deploy.sh
chmod +x setup-ssl.sh
chmod +x backup-db.sh
```

---

## 🚀 Step 4: Deploy!

### Run deployment:

```bash
./deploy.sh
```

This script will:
1. ✅ Pull latest code
2. ✅ Build Docker containers
3. ✅ Start all services (PHP, MySQL, Nginx)
4. ✅ Install dependencies
5. ✅ Run database migrations
6. ✅ Create Super Admin account
7. ✅ Set proper permissions

### Default login credentials:

```
Username: superadmin
Password: super123
```

**⚠️ Change this password immediately after first login!**

---

## 🔐 Step 5: Setup SSL Certificate (Recommended)

### Prerequisites:

- Domain DNS must point to your server IP
- Wait 5-10 minutes for DNS propagation

### Run SSL setup:

```bash
sudo ./setup-ssl.sh
```

Follow the prompts to:
- Enter your domain name
- Enter your email for SSL notifications
- Certificates will be automatically installed and configured

### Manual SSL configuration (if needed):

```bash
# Install Certbot
apt-get install -y certbot python3-certbot-nginx

# Stop nginx
docker compose stop nginx

# Get certificate
certbot certonly --standalone \
    -d yourdomain.com \
    -d www.yourdomain.com \
    --email your@email.com \
    --agree-tos

# Copy certificates
mkdir -p docker/nginx/ssl
cp /etc/letsencrypt/live/yourdomain.com/fullchain.pem docker/nginx/ssl/
cp /etc/letsencrypt/live/yourdomain.com/privkey.pem docker/nginx/ssl/

# Update nginx config (uncomment HTTPS blocks)
nano docker/nginx/conf.d/default.conf

# Restart nginx
docker compose start nginx
```

---

## 🎯 Step 6: Configure Domain

### Update DNS records:

| Type  | Name | Value           | TTL  |
|-------|------|-----------------|------|
| A     | @    | YOUR_SERVER_IP  | 3600 |
| A     | www  | YOUR_SERVER_IP  | 3600 |

Wait 5-30 minutes for DNS propagation.

---

## 🔧 Post-Deployment Configuration

### 1. Change Super Admin Password

```bash
# Access application
docker compose exec app bash

# Run password reset
php spark user:create-superadmin
```

### 2. Configure Application Settings

Login and go to **Settings** to configure:
- Company logo
- Invoice prefix
- Currency
- Branch information
- User accounts

### 3. Backup Database

```bash
# Create first backup
./backup-db.sh

# Setup automatic daily backups (3 AM)
(crontab -l 2>/dev/null; echo "0 3 * * * cd /var/www/cs-asic-repair && ./backup-db.sh") | crontab -
```

---

## 📊 Useful Commands

### View logs:

```bash
# All logs
docker compose logs -f

# Specific service
docker compose logs -f app
docker compose logs -f nginx
docker compose logs -f db
```

### Restart services:

```bash
# All services
docker compose restart

# Specific service
docker compose restart app
docker compose restart nginx
```

### Stop/Start application:

```bash
# Stop
docker compose down

# Start
docker compose up -d
```

### Access container shell:

```bash
# PHP application
docker compose exec app bash

# MySQL database
docker compose exec db mysql -u root -p

# Nginx
docker compose exec nginx sh
```

### Update application:

```bash
# Pull latest code
git pull origin main

# Rebuild and restart
docker compose down
docker compose build --no-cache
docker compose up -d

# Or use deploy script
./deploy.sh
```

### Database operations:

```bash
# Backup
./backup-db.sh

# Restore from backup
gunzip backup_file.sql.gz
docker compose exec -T db mysql -u root -p asic_repair_db < backup_file.sql

# Run migrations
docker compose exec app php spark migrate

# Rollback migration
docker compose exec app php spark migrate:rollback
```

### Clear cache:

```bash
docker compose exec app php spark cache:clear
```

---

## 🐛 Troubleshooting

### Application not accessible:

```bash
# Check if containers are running
docker compose ps

# Check nginx logs
docker compose logs nginx

# Restart all services
docker compose restart
```

### Database connection errors:

```bash
# Check database logs
docker compose logs db

# Verify database is running
docker compose exec db mysql -u root -p -e "SHOW DATABASES;"

# Check .env database settings
cat .env | grep database
```

### Permission errors:

```bash
# Fix writable directory permissions
docker compose exec app chown -R www-data:www-data /var/www/html/writable
docker compose exec app chmod -R 775 /var/www/html/writable
```

### SSL certificate errors:

```bash
# Check certificate files
ls -la docker/nginx/ssl/

# Test nginx configuration
docker compose exec nginx nginx -t

# Renew certificate manually
certbot renew --force-renewal
```

### Out of disk space:

```bash
# Check disk usage
df -h

# Clean Docker
docker system prune -a

# Clean old backups
ls -lh backups/
rm backups/old_backup_file.sql.gz
```

---

## 🔄 Updating the Application

### Regular updates:

```bash
cd /var/www/cs-asic-repair

# Pull latest code
git pull origin main

# Run deployment script
./deploy.sh
```

### Major version updates:

```bash
# Backup first!
./backup-db.sh

# Pull updates
git pull origin main

# Rebuild containers
docker compose down
docker compose build --no-cache
docker compose up -d

# Run migrations
docker compose exec app php spark migrate
```

---

## 📈 Performance Optimization

### Enable OPcache (already configured):

Configured in `docker/php/local.ini`

### MySQL optimization:

Edit `docker/mysql/my.cnf` and adjust:
```ini
innodb_buffer_pool_size = 512M  # Increase for more RAM
max_connections = 200
```

### Enable Gzip compression:

Already configured in nginx config.

### Setup Redis cache (optional):

```yaml
# Add to docker compose.yml
redis:
  image: redis:alpine
  container_name: asic-repair-redis
  restart: unless-stopped
  networks:
    - asic-network
```

---

## 🔒 Security Best Practices

1. ✅ Change default Super Admin password
2. ✅ Enable SSL/HTTPS
3. ✅ Keep system updated: `apt-get update && apt-get upgrade`
4. ✅ Use strong database passwords
5. ✅ Enable firewall (UFW)
6. ✅ Regular database backups
7. ✅ Monitor logs regularly
8. ✅ Limit SSH access (use SSH keys only)
9. ✅ Setup automatic security updates:
   ```bash
   apt-get install -y unattended-upgrades
   dpkg-reconfigure -plow unattended-upgrades
   ```

---

## 📞 Support

For issues and questions:
- GitHub: https://github.com/ton-apicha/cs-asic-repair
- Documentation: See README.md

---

## 📝 License

Copyright © 2024 ASIC Repair Management System

