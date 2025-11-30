# 🚀 Quick Fix Guide - วิธีแก้ไขปัญหาบน Server

## ปัญหา: "The error view file was not specified. Cannot display error view."

### วิธีแก้ไขแบบเร็วที่สุด (1 คำสั่ง)

SSH เข้า server แล้วรันคำสั่งนี้:

```bash
cd /var/www/cs-asic-repair && curl -sSL https://raw.githubusercontent.com/ton-apicha/cs-asic-repair/main/fix-from-github.sh | bash
```

---

## หรือทำทีละขั้นตอน:

### ขั้นตอนที่ 1: SSH เข้า Server

```bash
ssh root@YOUR_SERVER_IP
```

### ขั้นตอนที่ 2: ไปที่ Directory โปรเจค

```bash
cd /var/www/cs-asic-repair
```

### ขั้นตอนที่ 3: Pull Code ล่าสุดจาก GitHub

```bash
git pull origin main
```

### ขั้นตอนที่ 4: รัน Fix Script

```bash
# ทำให้ script executable
chmod +x fix-from-github.sh

# รัน script
./fix-from-github.sh
```

---

## Script จะทำอะไรบ้าง?

✅ Backup ไฟล์เดิมก่อนแก้ไข  
✅ Pull code ล่าสุดจาก GitHub  
✅ สร้างไฟล์ error views ที่ขาดหาย  
✅ แก้ไข permissions ของ writable directory  
✅ Clear cache  
✅ Restart services (app, nginx)  
✅ ตรวจสอบว่าระบบทำงานปกติ  

---

## ถ้ายังมีปัญหา

### วิธีที่ 1: รัน Quick Fix

```bash
./quick-fix.sh
```

### วิธีที่ 2: ตรวจสอบระบบ

```bash
./diagnose.sh
```

### วิธีที่ 3: ดู Logs

```bash
# Application logs
docker compose logs -f app

# Nginx logs
docker compose logs -f nginx

# Error logs
docker compose exec nginx tail -f /var/log/nginx/asic-error.log
```

### วิธีที่ 4: Deploy ใหม่ทั้งหมด

```bash
./deploy.sh
```

---

## คำสั่งที่มีประโยชน์

### ตรวจสอบสถานะ Containers

```bash
docker compose ps
```

### Restart Services

```bash
docker compose restart app nginx
```

### เข้าไปใน Container

```bash
# PHP container
docker compose exec app bash

# ตรวจสอบ error views
ls -la /var/www/html/app/Views/errors/html/

# ตรวจสอบ permissions
ls -la /var/www/html/writable/
```

### แก้ไข Permissions (ถ้ายังมีปัญหา)

```bash
docker compose exec app chown -R www-data:www-data /var/www/html/writable
docker compose exec app chmod -R 775 /var/www/html/writable
```

### Clear Cache

```bash
docker compose exec app php spark cache:clear
```

---

## ติดต่อขอความช่วยเหลือ

ถ้ายังแก้ไม่ได้ ให้รวบรวมข้อมูลนี้:

```bash
# รัน diagnostic
./diagnose.sh > diagnostic-output.txt

# ดู logs
docker compose logs --tail=100 app > app-logs.txt
docker compose logs --tail=100 nginx > nginx-logs.txt

# ตรวจสอบ containers
docker compose ps > containers-status.txt
```

แล้วส่งไฟล์เหล่านี้มาเพื่อขอความช่วยเหลือ

---

## 📚 เอกสารเพิ่มเติม

- **TROUBLESHOOTING.md** - คู่มือแก้ไขปัญหาแบบละเอียด
- **DEPLOYMENT.md** - คู่มือการ deploy
- **README.md** - เอกสารหลักของโปรเจค
