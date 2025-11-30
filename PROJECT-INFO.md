# ASIC Repair Management System - Project Information

## 📋 Project Overview
**ชื่อโปรเจค:** ASIC Repair Management System (R-POS)  
**ประเภท:** ระบบจัดการศูนย์ซ่อม ASIC Miner  
**เทคโนโลยี:** PHP (CodeIgniter 4), MySQL, Docker, Nginx  
**ภาษา:** รองรับ 3 ภาษา (Thai, English, Chinese)

## 🎯 ฟีเจอร์หลัก
- ✅ ระบบจัดการใบงานซ่อม (Job Cards)
- ✅ ระบบจัดการลูกค้า (Customers)
- ✅ ระบบจัดการเครื่อง ASIC (Assets/Machines)
- ✅ ระบบคลังอะไหล่ (Inventory)
- ✅ ระบบใบเสนอราคา (Quotations)
- ✅ ระบบรายงาน (Reports)
- ✅ ระบบหลายสาขา (Multi-branch)
- ✅ ระบบสิทธิ์ผู้ใช้ 3 ระดับ (Super Admin, Admin, Technician)

## 🏗️ โครงสร้างระบบ

### User Roles
1. **Super Admin** (`super_admin`)
   - เห็นข้อมูลทุกสาขา
   - จัดการระบบทั้งหมด
   - `branch_id = NULL`

2. **Admin** (`admin`)
   - เห็นข้อมูลเฉพาะสาขาตัวเอง
   - จัดการผู้ใช้และตั้งค่าสาขา
   - `branch_id = [specific branch]`

3. **Technician** (`technician`)
   - เห็นงานที่ได้รับมอบหมาย
   - อัพเดทสถานะงาน
   - `branch_id = [specific branch]`

### Database Tables
- `branches` - ข้อมูลสาขา
- `users` - ผู้ใช้งาน
- `customers` - ลูกค้า
- `assets` - เครื่อง ASIC
- `job_cards` - ใบงานซ่อม
- `quotations` - ใบเสนอราคา
- `parts_inventory` - คลังอะไหล่
- `job_parts` - อะไหล่ที่ใช้ในงาน
- `payments` - การชำระเงิน
- `stock_transactions` - ประวัติสต๊อก
- `symptom_history` - ประวัติอาการ
- `audit_logs` - บันทึกการใช้งาน

## 🔧 Technology Stack

### Backend
- **Framework:** CodeIgniter 4.6.3
- **PHP Version:** 8.2
- **Database:** MySQL 8.0
- **Web Server:** Nginx (Alpine)

### Frontend
- **CSS Framework:** Bootstrap 5.3.3
- **Icons:** Bootstrap Icons 1.11.3
- **JavaScript:** jQuery 3.7.1, jQuery UI 1.13.2
- **Fonts:** Google Fonts (Inter)

### DevOps
- **Containerization:** Docker & Docker Compose
- **Deployment:** Git-based deployment
- **SSL:** Let's Encrypt (Certbot)

## 📁 Project Structure
```
cs-asic-repair/
├── app/
│   ├── Config/          # Configuration files
│   ├── Controllers/     # Application controllers
│   ├── Models/          # Database models
│   ├── Views/           # View templates
│   ├── Database/        # Migrations & seeds
│   ├── Commands/        # CLI commands
│   └── Language/        # Translation files (th, en, zh)
├── public/              # Public assets (CSS, JS, images)
├── writable/            # Logs, cache, uploads
├── docker/              # Docker configurations
│   └── nginx/           # Nginx configs
├── .env                 # Environment variables
├── docker-compose.yml   # Docker services
├── Dockerfile           # PHP-FPM container
└── deploy.sh            # Deployment script
```

## 🔑 Important Files

### Configuration
- `.env` - Environment variables (database, app settings)
- `app/Config/Routes.php` - URL routing
- `app/Config/Database.php` - Database configuration
- `docker-compose.yml` - Docker services setup

### Deployment
- `deploy.sh` - Main deployment script
- `rebuild-docker.sh` - Rebuild Docker images
- `quick-fix.sh` - Quick fixes for common issues
- `diagnose.sh` - System diagnostics

### Documentation
- `DEPLOYMENT.md` - Deployment guide
- `TROUBLESHOOTING.md` - Troubleshooting guide (Thai)
- `README.md` - Project overview

## 🚀 Quick Start

### Local Development
```bash
# Clone repository
git clone <repository-url>
cd cs-asic-repair

# Copy environment file
cp .env.example .env

# Start Docker containers
docker compose up -d

# Run migrations
docker compose exec app php spark migrate

# Create super admin
docker compose exec app php spark user:create-superadmin
```

### Access
- **URL:** http://localhost
- **Default Admin:** admin / admin123
- **Super Admin:** superadmin / super123

## 📝 Common Commands

### Docker
```bash
# Start containers
docker compose up -d

# Stop containers
docker compose down

# Restart specific service
docker compose restart app

# View logs
docker compose logs -f app

# Execute command in container
docker compose exec app php spark migrate
```

### Database
```bash
# Run migrations
docker compose exec app php spark migrate

# Rollback migrations
docker compose exec app php spark migrate:rollback

# Access MySQL
docker compose exec db mysql -u root -p
```

### Deployment
```bash
# Full deployment
./deploy.sh

# Quick fix
./quick-fix.sh

# Rebuild Docker
./rebuild-docker.sh

# Diagnostics
./diagnose.sh
```

## 🔐 Security Notes
- CSRF protection enabled
- Password hashing with PHP `password_hash()`
- Role-based access control (RBAC)
- SQL injection protection via Query Builder
- XSS protection via `esc()` helper

## 🐛 Known Issues & Solutions

### Issue: mysqli extension not loaded
**Solution:** Already fixed in Dockerfile (line 20)

### Issue: Missing branch_id column
**Solution:** Run migrations or manually add via SQL

### Issue: Autocomplete not working
**Solution:** Ensure jQuery UI is loaded and API routes are configured

## 📊 Database Schema Notes

### Important Relationships
- `users.branch_id` → `branches.id` (NULL for super_admin)
- `customers.branch_id` → `branches.id`
- `assets.customer_id` → `customers.id`
- `job_cards.customer_id` → `customers.id`
- `job_cards.asset_id` → `assets.id`
- `job_parts.job_id` → `job_cards.id`
- `job_parts.part_id` → `parts_inventory.id`

### ENUM Fields
- `users.role` → `'admin'`, `'technician'`, `'super_admin'`
- `job_cards.status` → `'pending'`, `'in_progress'`, `'completed'`, `'cancelled'`
- `assets.status` → `'stored'`, `'repairing'`, `'repaired'`, `'returned'`

## 🌐 API Endpoints

### Public
- `GET /` - Login page
- `POST /login` - Authentication
- `GET /logout` - Logout

### Protected (Requires Auth)
- `GET /dashboard` - Dashboard
- `GET /jobs` - Job list
- `GET /customers` - Customer list
- `GET /machines` - Asset list
- `GET /inventory` - Inventory list

### API (AJAX)
- `GET /api/customers/search?term={query}` - Customer autocomplete
- `GET /api/machines/search?term={query}` - Asset autocomplete
- `GET /api/dashboard/stats` - Dashboard statistics
- `GET /api/jobs/by-status` - Jobs grouped by status

## 💡 Development Tips

### Adding New Features
1. Create migration in `app/Database/Migrations/`
2. Create model in `app/Models/`
3. Create controller in `app/Controllers/`
4. Add routes in `app/Config/Routes.php`
5. Create views in `app/Views/`
6. Add translations in `app/Language/{locale}/`

### Testing
```bash
# Run tests (if configured)
docker compose exec app php spark test

# Check PHP syntax
docker compose exec app php -l app/Controllers/YourController.php
```

### Debugging
- Enable debug mode: Set `CI_ENVIRONMENT = development` in `.env`
- Check logs: `writable/logs/log-{date}.log`
- Browser console: Check for JavaScript errors
- Network tab: Check API responses

## 📞 Support & Maintenance

### Regular Maintenance
- Backup database weekly
- Clear cache monthly
- Update dependencies quarterly
- Review logs for errors

### Backup
```bash
# Database backup
docker compose exec db mysqldump -u root -p asic_repair_db > backup.sql

# Full backup (via settings)
Navigate to Settings > Backup & Restore
```

## 🔄 Version History
- **v1.0.0** - Initial release
- **v1.1.0** - Added multi-branch support, super admin role
- **v1.1.1** - Fixed mysqli extension, autocomplete features

---

**Last Updated:** 2025-12-01  
**Maintained By:** Development Team
