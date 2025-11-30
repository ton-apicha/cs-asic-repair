# Server Information - ASIC Repair System

## 🖥️ Server Details

### Hosting Provider
**Provider:** DigitalOcean  
**Plan:** Basic Droplet  
**Specs:** 1 vCPU, 2GB RAM  
**Region:** Singapore (sgp1)  
**OS:** Ubuntu 22.04 LTS

### Server Access
**IP Address:** `152.42.201.200`  
**SSH User:** `root`  
**SSH Port:** `22` (default)

### SSH Connection
```bash
ssh root@152.42.201.200
```

## 🌐 Domain & DNS
**Domain:** (Not configured yet - using IP)  
**URL:** http://152.42.201.200

### Future SSL Setup
```bash
# Install Certbot
sudo apt-get install certbot python3-certbot-nginx

# Get SSL certificate
sudo certbot --nginx -d yourdomain.com
```

## 🐳 Docker Setup

### Installed Services
- **Docker Engine:** Latest
- **Docker Compose:** Latest

### Running Containers
```bash
# List containers
docker ps

# Expected containers:
# - asic-repair-app (PHP-FPM)
# - asic-repair-db (MySQL)
# - asic-repair-nginx (Nginx)
```

### Container Details

#### PHP-FPM Container (app)
- **Image:** Custom (built from Dockerfile)
- **Base:** php:8.2-fpm
- **Port:** 9000 (internal)
- **Volume:** `/var/www/cs-asic-repair`
- **Extensions:** mysqli, pdo_mysql, mbstring, gd, intl, zip

#### MySQL Container (db)
- **Image:** mysql:8.0
- **Port:** 3306 (internal only)
- **Database:** `asic_repair_db`
- **User:** `asic_user`
- **Root Password:** `Rootc34a3ad25b2107c48f09!Sec`
- **User Password:** `AsicRepair2024`

#### Nginx Container (nginx)
- **Image:** nginx:alpine
- **Ports:** 80, 443
- **Config:** `/var/www/cs-asic-repair/docker/nginx/conf.d/`

## 📂 File System Structure

### Project Location
```
/var/www/cs-asic-repair/
```

### Important Directories
```
/var/www/cs-asic-repair/
├── app/                 # Application code
├── public/              # Web root
├── writable/            # Logs, cache, uploads
│   ├── cache/
│   ├── logs/
│   ├── session/
│   └── uploads/
├── docker/              # Docker configs
└── vendor/              # Composer dependencies
```

### Permissions
```bash
# Writable directory
chown -R www-data:www-data /var/www/cs-asic-repair/writable
chmod -R 775 /var/www/cs-asic-repair/writable
```

## 🔐 Credentials

### Database
```
Host: db (Docker network)
Port: 3306
Database: asic_repair_db
Username: asic_user
Password: AsicRepair2024
Root Password: Rootc34a3ad25b2107c48f09!Sec
```

### Application Users

#### Super Admin
```
Username: superadmin
Password: super123
Role: super_admin
Branch: NULL (all branches)
```

#### Default Admin
```
Username: admin
Password: admin123
Role: admin
Branch: 1 (Main Branch)
```

#### Default Technician
```
Username: technician
Password: tech123
Role: technician
Branch: 1 (Main Branch)
```

## 🔧 Environment Variables

### .env File Location
```
/var/www/cs-asic-repair/.env
```

### Key Settings
```ini
CI_ENVIRONMENT = production
app.baseURL = 'http://152.42.201.200/'

database.default.hostname = db
database.default.database = asic_repair_db
database.default.username = asic_user
database.default.password = AsicRepair2024
database.default.DBDriver = MySQLi
database.default.port = 3306
```

## 🚀 Deployment Process

### Git Repository
```bash
# Repository location on server
cd /var/www/cs-asic-repair

# Check current branch
git branch

# Pull latest changes
git pull origin main
```

### Deployment Commands
```bash
# Full deployment
cd /var/www/cs-asic-repair
./deploy.sh

# Quick restart
docker compose restart app nginx

# Rebuild containers
./rebuild-docker.sh
```

## 🖥️ Desktop Environment (VNC)

### VNC Server
**Installed:** Yes  
**Desktop:** XFCE4  
**VNC Port:** 5901  
**Auto-start:** Enabled (systemd service)

### VNC Access
```
VNC Address: 152.42.201.200:5901
VNC Password: (Set during installation)
```

### VNC Commands
```bash
# Start VNC
vncserver -geometry 1920x1080 -depth 24

# Stop VNC
vncserver -kill :1

# Check status
systemctl status vncserver@1.service
```

### Installed Desktop Apps
- Firefox
- XFCE Terminal
- Thunar File Manager

## 📊 Monitoring & Logs

### Application Logs
```bash
# View live logs
docker compose logs -f app

# View specific date
docker compose exec app cat /var/www/html/writable/logs/log-$(date +%Y-%m-%d).log

# Last 100 lines
docker compose exec app tail -n 100 /var/www/html/writable/logs/log-$(date +%Y-%m-%d).log
```

### Nginx Logs
```bash
# Access logs
docker compose logs nginx | grep -i "GET\|POST"

# Error logs
docker compose logs nginx | grep -i error
```

### MySQL Logs
```bash
docker compose logs db
```

### System Resources
```bash
# Check disk space
df -h

# Check memory
free -h

# Check CPU
top

# Docker stats
docker stats
```

## 🔄 Backup & Restore

### Database Backup
```bash
# Manual backup
docker compose exec db mysqldump -u root -p'Rootc34a3ad25b2107c48f09!Sec' asic_repair_db > backup_$(date +%Y%m%d).sql

# Restore
docker compose exec -T db mysql -u root -p'Rootc34a3ad25b2107c48f09!Sec' asic_repair_db < backup.sql
```

### File Backup
```bash
# Backup uploads
tar -czf uploads_backup.tar.gz /var/www/cs-asic-repair/writable/uploads/

# Backup entire project
tar -czf project_backup.tar.gz /var/www/cs-asic-repair/
```

## 🛠️ Maintenance Tasks

### Regular Maintenance
```bash
# Update system packages
apt-get update && apt-get upgrade -y

# Clean Docker
docker system prune -a

# Clear application cache
docker compose exec app php spark cache:clear

# Restart services
docker compose restart
```

### Database Maintenance
```bash
# Optimize tables
docker compose exec db mysql -u root -p'Rootc34a3ad25b2107c48f09!Sec' -e "OPTIMIZE TABLE asic_repair_db.*;"

# Check tables
docker compose exec db mysql -u root -p'Rootc34a3ad25b2107c48f09!Sec' -e "CHECK TABLE asic_repair_db.*;"
```

## 🐛 Troubleshooting

### Common Issues

#### Container Won't Start
```bash
# Check logs
docker compose logs app

# Rebuild
docker compose down
docker compose build --no-cache
docker compose up -d
```

#### Database Connection Failed
```bash
# Check MySQL is running
docker compose ps db

# Test connection
docker compose exec app php -r "new mysqli('db', 'asic_user', 'AsicRepair2024', 'asic_repair_db');"
```

#### Permission Errors
```bash
# Fix writable permissions
docker compose exec app chown -R www-data:www-data /var/www/html/writable
docker compose exec app chmod -R 775 /var/www/html/writable
```

#### 500 Internal Server Error
```bash
# Check PHP errors
docker compose exec app tail -f /var/www/html/writable/logs/log-$(date +%Y-%m-%d).log

# Check Nginx errors
docker compose logs nginx | grep error
```

## 🔒 Security

### Firewall (UFW)
```bash
# Check status
ufw status

# Allow ports
ufw allow 22/tcp    # SSH
ufw allow 80/tcp    # HTTP
ufw allow 443/tcp   # HTTPS
ufw allow 5901/tcp  # VNC
```

### SSH Security
```bash
# Disable root login (recommended)
nano /etc/ssh/sshd_config
# Set: PermitRootLogin no

# Restart SSH
systemctl restart sshd
```

## 📞 Quick Reference

### Essential Commands
```bash
# SSH to server
ssh root@152.42.201.200

# Navigate to project
cd /var/www/cs-asic-repair

# Pull latest code
git pull origin main

# Deploy
./deploy.sh

# Restart app
docker compose restart app

# View logs
docker compose logs -f app

# Access MySQL
docker compose exec db mysql -u root -p
```

### Emergency Recovery
```bash
# Stop all containers
docker compose down

# Remove volumes (CAUTION: Data loss!)
docker compose down -v

# Fresh start
docker compose up -d
docker compose exec app php spark migrate
docker compose exec app php spark user:create-superadmin
```

## 📝 Notes

### Recent Changes
- ✅ Fixed mysqli extension issue
- ✅ Added super_admin role to database
- ✅ Fixed autocomplete for customer search
- ✅ Added branch_id to all necessary tables
- ✅ Installed desktop environment (XFCE + VNC)

### Pending Tasks
- [ ] Configure domain name
- [ ] Setup SSL certificate
- [ ] Configure automated backups
- [ ] Setup monitoring (optional)
- [ ] Configure email notifications (optional)

---

**Server Setup Date:** 2025-11-30  
**Last Updated:** 2025-12-01  
**Maintained By:** Development Team
