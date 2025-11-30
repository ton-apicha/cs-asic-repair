# 🔧 TROUBLESHOOTING GUIDE - ASIC Repair Management System

## ปัญหา: "The error view file was not specified. Cannot display error view."

### สาเหตุที่เป็นไปได้

1. **ไฟล์ error view ไม่ครบ** - CodeIgniter 4 ต้องการไฟล์ error view หลายไฟล์
2. **Permission ไม่ถูกต้อง** - ไดเรกทอรี writable ไม่มีสิทธิ์เขียน
3. **Environment configuration ผิดพลาด** - ไฟล์ .env ไม่ถูกต้อง
4. **Database connection ล้มเหลว** - ไม่สามารถเชื่อมต่อฐานข้อมูล

---

## ✅ วิธีแก้ไขแบบเร็ว (Quick Fix)

### วิธีที่ 1: ใช้ Quick Fix Script

```bash
# SSH เข้า server
ssh root@YOUR_SERVER_IP

# ไปที่ directory โปรเจค
cd /var/www/cs-asic-repair

# ทำให้ script executable
chmod +x quick-fix.sh

# รัน quick fix
./quick-fix.sh
```

### วิธีที่ 2: แก้ไขด้วยมือ

#### ขั้นตอนที่ 1: ตรวจสอบว่า containers กำลังทำงาน

```bash
docker compose ps
```

ควรเห็น 3 containers: `app`, `db`, `nginx` ทั้งหมดต้อง status เป็น `Up`

#### ขั้นตอนที่ 2: ตรวจสอบ .env file

```bash
# ตรวจสอบว่ามีไฟล์ .env
ls -la .env

# ถ้าไม่มี ให้สร้างจาก template
cp env.production.example .env

# แก้ไข .env
nano .env
```

**ตั้งค่าที่สำคัญใน .env:**

```env
# Environment
CI_ENVIRONMENT = production

# Base URL (เปลี่ยนเป็น domain ของคุณ)
app.baseURL = 'http://YOUR_SERVER_IP/'

# Database
database.default.hostname = db
database.default.database = asic_repair_db
database.default.username = asic_user
database.default.password = YOUR_PASSWORD
database.default.DBDriver = MySQLi

# Database root password
DB_ROOT_PASSWORD = YOUR_ROOT_PASSWORD
```

#### ขั้นตอนที่ 3: แก้ไข Permissions

```bash
# แก้ไข permissions ของ writable directory
docker compose exec app chown -R www-data:www-data /var/www/html/writable
docker compose exec app chmod -R 775 /var/www/html/writable

# ตรวจสอบ permissions
docker compose exec app ls -la /var/www/html/writable/
```

#### ขั้นตอนที่ 4: ตรวจสอบ Error View Files

```bash
# ตรวจสอบว่ามีไฟล์ error views
docker compose exec app ls -la /var/www/html/app/Views/errors/html/
```

ควรมีไฟล์เหล่านี้:
- `error_404.php`
- `error_500.php`
- `error_exception.php`
- `production.php`

#### ขั้นตอนที่ 5: Clear Cache

```bash
docker compose exec app php spark cache:clear
```

#### ขั้นตอนที่ 6: Restart Services

```bash
docker compose restart app
docker compose restart nginx
```

#### ขั้นตอนที่ 7: ตรวจสอบ Logs

```bash
# ดู application logs
docker compose logs -f app

# ดู nginx logs
docker compose logs -f nginx

# ดู nginx error logs
docker compose exec nginx tail -f /var/log/nginx/asic-error.log
```

---

## 🔍 การ Debug เพิ่มเติม

### ตรวจสอบ Database Connection

```bash
docker compose exec app php -r "
\$mysqli = new mysqli('db', 'asic_user', 'YOUR_PASSWORD', 'asic_repair_db');
if (\$mysqli->connect_error) {
    die('Connection failed: ' . \$mysqli->connect_error);
}
echo 'Database connected successfully!';
"
```

### ตรวจสอบ PHP Configuration

```bash
docker compose exec app php -i | grep -i error
```

### ตรวจสอบ Nginx Configuration

```bash
docker compose exec nginx nginx -t
```

### เข้าไปใน Container เพื่อ Debug

```bash
# เข้า PHP container
docker compose exec app bash

# ตรวจสอบ environment
env | grep CI_

# ตรวจสอบ PHP version
php -v

# ทดสอบ CodeIgniter
cd /var/www/html
php spark
```

---

## 🐛 ปัญหาที่พบบ่อยและวิธีแก้

### 1. Database Connection Failed

**อาการ:** ไม่สามารถเชื่อมต่อฐานข้อมูล

**วิธีแก้:**

```bash
# ตรวจสอบว่า MySQL container ทำงาน
docker compose ps db

# ถ้าไม่ทำงาน ให้ start
docker compose up -d db

# รอให้ MySQL พร้อม
sleep 10

# ทดสอบการเชื่อมต่อ
docker compose exec db mysql -u root -p -e "SHOW DATABASES;"
```

### 2. Permission Denied

**อาการ:** ไม่สามารถเขียนไฟล์ใน writable directory

**วิธีแก้:**

```bash
# แก้ไข owner และ permissions
docker compose exec app chown -R www-data:www-data /var/www/html/writable
docker compose exec app chmod -R 775 /var/www/html/writable

# สร้าง directories ที่จำเป็น
docker compose exec app mkdir -p /var/www/html/writable/cache
docker compose exec app mkdir -p /var/www/html/writable/logs
docker compose exec app mkdir -p /var/www/html/writable/session
docker compose exec app mkdir -p /var/www/html/writable/uploads
docker compose exec app mkdir -p /var/www/html/writable/debugbar

# ตั้งค่า permissions อีกครั้ง
docker compose exec app chown -R www-data:www-data /var/www/html/writable
docker compose exec app chmod -R 775 /var/www/html/writable
```

### 3. 502 Bad Gateway

**อาการ:** Nginx แสดง 502 Bad Gateway

**วิธีแก้:**

```bash
# ตรวจสอบว่า PHP-FPM ทำงาน
docker compose exec app ps aux | grep php-fpm

# ถ้าไม่ทำงาน restart container
docker compose restart app

# ตรวจสอบ logs
docker compose logs app
```

### 4. Blank White Page

**อาการ:** หน้าจอขาวเปล่า ไม่มี error

**วิธีแก้:**

```bash
# เปิด error reporting ใน .env
# เปลี่ยน CI_ENVIRONMENT เป็น development ชั่วคราว
docker compose exec app sed -i 's/CI_ENVIRONMENT = production/CI_ENVIRONMENT = development/' /var/www/html/.env

# Restart
docker compose restart app

# ลองเข้าเว็บอีกครั้งเพื่อดู error message
# หลังจากแก้ไขแล้ว เปลี่ยนกลับเป็น production
```

### 5. Composer Dependencies Missing

**อาการ:** Class not found errors

**วิธีแก้:**

```bash
# Install dependencies
docker compose exec app composer install --no-dev --optimize-autoloader

# Dump autoload
docker compose exec app composer dump-autoload --optimize
```

---

## 📊 การตรวจสอบสถานะระบบ

### ใช้ Diagnostic Script

```bash
# ทำให้ script executable
chmod +x diagnose.sh

# รัน diagnostic
docker compose exec app bash /var/www/html/diagnose.sh
```

### ตรวจสอบ Resources

```bash
# ตรวจสอบ disk space
df -h

# ตรวจสอบ memory
free -h

# ตรวจสอบ CPU
top -bn1 | head -20

# ตรวจสอบ Docker resources
docker stats --no-stream
```

---

## 🔄 การ Deploy ใหม่ทั้งหมด

ถ้าปัญหายังไม่หาย ลอง deploy ใหม่:

```bash
# Backup database ก่อน
./backup-db.sh

# Stop และลบ containers
docker compose down

# ลบ volumes (ระวัง! จะลบข้อมูลทั้งหมด)
# docker compose down -v

# Pull code ล่าสุด
git pull origin main

# Build ใหม่
docker compose build --no-cache

# Start services
docker compose up -d

# รอให้ services พร้อม
sleep 15

# Run migrations
docker compose exec app php spark migrate

# สร้าง super admin
docker compose exec app php spark user:create-superadmin

# แก้ไข permissions
docker compose exec app chown -R www-data:www-data /var/www/html/writable
docker compose exec app chmod -R 775 /var/www/html/writable
```

---

## 📝 Logs ที่ควรตรวจสอบ

### Application Logs

```bash
# ดู logs วันนี้
docker compose exec app tail -f /var/www/html/writable/logs/log-$(date +%Y-%m-%d).log

# ดู logs ทั้งหมด
docker compose exec app ls -lh /var/www/html/writable/logs/
```

### Nginx Logs

```bash
# Access logs
docker compose exec nginx tail -f /var/log/nginx/asic-access.log

# Error logs
docker compose exec nginx tail -f /var/log/nginx/asic-error.log
```

### Docker Logs

```bash
# All services
docker compose logs -f

# Specific service
docker compose logs -f app
docker compose logs -f nginx
docker compose logs -f db

# Last 100 lines
docker compose logs --tail=100 app
```

---

## 🆘 ติดต่อขอความช่วยเหลือ

ถ้ายังแก้ไขไม่ได้ ให้รวบรวมข้อมูลเหล่านี้:

```bash
# 1. System info
uname -a
docker --version
docker compose version

# 2. Container status
docker compose ps

# 3. Recent logs
docker compose logs --tail=50 app > app-logs.txt
docker compose logs --tail=50 nginx > nginx-logs.txt

# 4. Environment (ซ่อน passwords)
cat .env | grep -v password

# 5. Diagnostic output
docker compose exec app bash /var/www/html/diagnose.sh > diagnostic.txt
```

แล้วส่งไฟล์เหล่านี้มาเพื่อขอความช่วยเหลือ
