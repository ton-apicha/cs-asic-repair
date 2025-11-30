# Development Handoff - Quick Start Guide

## 🎯 Purpose
This document helps you (or another AI assistant) quickly understand and continue development on this project without needing extensive explanations.

## 📚 Essential Reading Order
1. **SERVER-INFO.md** - Server credentials and infrastructure
2. **PROJECT-INFO.md** - Project architecture and features
3. **DEPLOYMENT.md** - Deployment procedures
4. **TROUBLESHOOTING.md** - Common issues (Thai language)

## 🚀 Quick Setup on New Machine

### Prerequisites
- Git installed
- VS Code (recommended)
- Docker Desktop (for local development)
- SSH client

### Clone & Setup
```bash
# Clone repository
git clone <your-repo-url>
cd cs-asic-repair

# Copy environment file
cp .env.example .env

# Start local development
docker compose up -d

# Run migrations
docker compose exec app php spark migrate

# Create admin user
docker compose exec app php spark user:create-superadmin
```

### Access Local
- URL: http://localhost
- Admin: admin / admin123

## 🔑 Critical Information

### Server Access
```bash
ssh root@152.42.201.200
cd /var/www/cs-asic-repair
```

### Database Credentials
```
Host: db
Database: asic_repair_db
User: asic_user
Password: AsicRepair2024
Root Password: Rootc34a3ad25b2107c48f09!Sec
```

### User Accounts
- **Super Admin:** superadmin / super123
- **Admin:** admin / admin123
- **Technician:** technician / tech123

## 🏗️ Architecture Summary

### Tech Stack
- **Backend:** PHP 8.2 + CodeIgniter 4.6.3
- **Database:** MySQL 8.0
- **Frontend:** Bootstrap 5.3 + jQuery + jQuery UI
- **Deployment:** Docker + Nginx

### User Roles
1. **super_admin** - See all branches, manage everything
2. **admin** - See own branch only, manage users
3. **technician** - See assigned jobs only

### Key Tables
- `users` - User accounts
- `branches` - Store locations
- `customers` - Customer database
- `assets` - ASIC machines
- `job_cards` - Repair jobs
- `parts_inventory` - Parts stock
- `quotations` - Price quotes

## 🔧 Common Development Tasks

### Making Changes
```bash
# 1. Edit code locally
# 2. Test locally with Docker
docker compose up -d

# 3. Commit changes
git add .
git commit -m "Description"
git push origin main

# 4. Deploy to server
ssh root@152.42.201.200
cd /var/www/cs-asic-repair
git pull origin main
./deploy.sh
```

### Adding New Feature
1. Create migration: `app/Database/Migrations/YYYY-MM-DD-HHMMSS_FeatureName.php`
2. Create model: `app/Models/FeatureModel.php`
3. Create controller: `app/Controllers/FeatureController.php`
4. Add routes: `app/Config/Routes.php`
5. Create views: `app/Views/feature/`
6. Add translations: `app/Language/{th,en,zh}/App.php`

### Database Changes
```bash
# Create migration
docker compose exec app php spark make:migration AddColumnToTable

# Run migration
docker compose exec app php spark migrate

# Rollback
docker compose exec app php spark migrate:rollback
```

## 🐛 Recent Issues Fixed

### ✅ mysqli Extension Missing
**Fixed in:** `Dockerfile` line 20
```dockerfile
RUN docker-php-ext-install \
    pdo_mysql \
    mysqli \    # Added this
    mbstring \
    ...
```

### ✅ Missing branch_id Column
**Fixed:** Added to all tables via SQL
```sql
ALTER TABLE customers ADD COLUMN branch_id INT UNSIGNED NULL;
ALTER TABLE assets ADD COLUMN branch_id INT UNSIGNED NULL;
-- etc...
```

### ✅ Autocomplete Not Working
**Fixed:** Added API routes and jQuery UI initialization
- Routes: `app/Config/Routes.php` lines 189-190
- JS: `app/Views/jobs/create.php` lines 211-247

### ✅ Super Admin Role Missing
**Fixed:** Added to ENUM
```sql
ALTER TABLE users MODIFY COLUMN role 
ENUM('admin', 'technician', 'super_admin') DEFAULT 'technician';
```

## 📝 Code Style & Conventions

### PHP
- Follow PSR-12 coding standards
- Use type hints
- Document with PHPDoc
- Use CodeIgniter helpers: `esc()`, `lang()`, `base_url()`

### Database
- Use Query Builder (not raw SQL)
- Always use parameter binding
- Follow naming: `table_name`, `column_name`

### Views
- Use CodeIgniter template syntax: `<?= ?>`
- Escape output: `<?= esc($var) ?>`
- Use language files: `<?= lang('App.key') ?>`

### JavaScript
- Use jQuery for DOM manipulation
- Use Bootstrap for UI components
- Follow existing patterns for AJAX

## 🔍 Debugging Tips

### Enable Debug Mode
```ini
# .env
CI_ENVIRONMENT = development
```

### Check Logs
```bash
# Application logs
docker compose exec app tail -f /var/www/html/writable/logs/log-$(date +%Y-%m-%d).log

# Container logs
docker compose logs -f app

# Nginx logs
docker compose logs nginx
```

### Common Errors

#### "Unknown column 'branch_id'"
**Solution:** Run migrations or add column manually

#### "mysqli extension not loaded"
**Solution:** Rebuild Docker image with `./rebuild-docker.sh`

#### "404 Not Found" on API routes
**Solution:** Check `app/Config/Routes.php` for route definition

#### "Permission denied" on writable
**Solution:**
```bash
docker compose exec app chown -R www-data:www-data /var/www/html/writable
docker compose exec app chmod -R 775 /var/www/html/writable
```

## 🎨 UI/UX Guidelines

### Design System
- **Colors:** Bootstrap theme colors
- **Icons:** Bootstrap Icons
- **Fonts:** Google Fonts (Inter)
- **Components:** Bootstrap 5.3 components

### Responsive Design
- Mobile-first approach
- Breakpoints: sm(576px), md(768px), lg(992px), xl(1200px)
- Test on mobile, tablet, desktop

### Accessibility
- Use semantic HTML
- Add ARIA labels
- Ensure keyboard navigation
- Maintain color contrast

## 🚢 Deployment Checklist

### Before Deploying
- [ ] Test locally with Docker
- [ ] Check for console errors
- [ ] Verify database migrations
- [ ] Update version number
- [ ] Commit and push to Git

### Deployment Steps
```bash
# 1. SSH to server
ssh root@152.42.201.200

# 2. Navigate to project
cd /var/www/cs-asic-repair

# 3. Pull latest code
git pull origin main

# 4. Run deployment script
./deploy.sh

# 5. Verify
# - Check website loads
# - Test critical features
# - Check logs for errors
```

### Post-Deployment
- [ ] Test login
- [ ] Create test job card
- [ ] Check reports
- [ ] Monitor logs for 10 minutes

## 📞 Emergency Contacts

### If Something Breaks
1. **Check logs first:** `docker compose logs -f app`
2. **Rollback if needed:** `git reset --hard HEAD~1`
3. **Restart services:** `docker compose restart`
4. **Full reset:** `./deploy.sh`

### Recovery Commands
```bash
# Quick restart
docker compose restart app nginx

# Full rebuild
docker compose down
docker compose build --no-cache
docker compose up -d

# Database restore
docker compose exec -T db mysql -u root -p < backup.sql
```

## 🎓 Learning Resources

### CodeIgniter 4
- Docs: https://codeigniter.com/user_guide/
- Forums: https://forum.codeigniter.com/

### Docker
- Docs: https://docs.docker.com/
- Compose: https://docs.docker.com/compose/

### Bootstrap
- Docs: https://getbootstrap.com/docs/5.3/

## 📊 Project Status

### Completed Features
- ✅ Multi-branch support
- ✅ User role management
- ✅ Job card system
- ✅ Customer management
- ✅ Asset tracking
- ✅ Inventory management
- ✅ Quotation system
- ✅ Basic reporting

### In Progress
- 🔄 Advanced reporting
- 🔄 Email notifications
- 🔄 SMS integration

### Planned
- 📋 Mobile app
- 📋 API for third-party integration
- 📋 Advanced analytics

## 🔐 Security Reminders

### Never Commit
- `.env` file
- Database backups
- SSH keys
- Passwords in code

### Always
- Use environment variables
- Validate user input
- Escape output
- Use CSRF protection
- Keep dependencies updated

## 💡 Pro Tips

### Speed Up Development
```bash
# Watch logs in real-time
docker compose logs -f app | grep -i error

# Quick cache clear
docker compose exec app php spark cache:clear

# Database query testing
docker compose exec db mysql -u root -p asic_repair_db
```

### VS Code Extensions
- PHP Intelephense
- Docker
- GitLens
- Bootstrap 5 Quick Snippets

### Useful Aliases
```bash
# Add to ~/.bashrc or ~/.zshrc
alias dc='docker compose'
alias dcl='docker compose logs -f'
alias dce='docker compose exec app'
alias deploy='cd /var/www/cs-asic-repair && ./deploy.sh'
```

---

## ✅ Ready to Start?

1. Read **SERVER-INFO.md** for credentials
2. Read **PROJECT-INFO.md** for architecture
3. Clone repository and run `docker compose up -d`
4. Access http://localhost and login
5. Start coding!

**Good luck! 🚀**

---

**Created:** 2025-12-01  
**For:** Seamless development handoff  
**Contact:** Check project repository for team contacts
