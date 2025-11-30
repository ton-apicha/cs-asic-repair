# 🚀 คำสั่งสำหรับแก้ไขปัญหาบน Server

## คัดลอกและวางคำสั่งเหล่านี้ใน SSH PowerShell

---

## ⚡ วิธีที่ 1: แก้ไขเร็วที่สุด (แนะนำ)

คัดลอกและวางคำสั่งนี้ทั้งหมดในครั้งเดียว:

```bash
cd /var/www/cs-asic-repair && cat > app/Views/errors/html/production.php << 'EOF'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - ASIC Repair</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 20px;
        }
        .error-container {
            background: white; border-radius: 12px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px; width: 100%; padding: 40px; text-align: center;
        }
        .error-icon { font-size: 80px; margin-bottom: 20px; }
        h1 { color: #2d3748; font-size: 32px; margin-bottom: 15px; font-weight: 700; }
        .error-code { color: #e53e3e; font-size: 24px; font-weight: 600; margin-bottom: 20px; }
        p { color: #4a5568; line-height: 1.6; margin-bottom: 30px; font-size: 16px; }
        .btn-home {
            display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; padding: 12px 30px; border-radius: 6px; text-decoration: none; font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">⚠️</div>
        <h1>Oops! Something went wrong</h1>
        <div class="error-code">Error <?= $statusCode ?? 500 ?></div>
        <p><?= esc($message ?? 'An unexpected error occurred. Please try again later.') ?></p>
        <a href="/" class="btn-home">Return to Home</a>
    </div>
</body>
</html>
EOF
docker compose exec app chown -R www-data:www-data /var/www/html/writable && docker compose exec app chmod -R 775 /var/www/html/writable && docker compose exec app php spark cache:clear && docker compose restart app nginx && echo "✓ Done! Check your website now."
```

---

## 🔄 วิธีที่ 2: ดึง Script จาก GitHub (ถ้า Push แล้ว)

```bash
cd /var/www/cs-asic-repair && git pull origin main && chmod +x fix-from-github.sh && ./fix-from-github.sh
```

---

## 📋 วิธีที่ 3: ทีละขั้นตอน (ถ้าต้องการดูผลแต่ละขั้น)

### ขั้นที่ 1: ไปที่ directory
```bash
cd /var/www/cs-asic-repair
```

### ขั้นที่ 2: สร้างไฟล์ error view
```bash
cat > app/Views/errors/html/production.php << 'EOF'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); margin: 0; }
        .container { background: white; padding: 40px; border-radius: 12px; text-align: center; max-width: 600px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        h1 { color: #2d3748; margin-bottom: 20px; font-size: 32px; }
        .error-code { color: #e53e3e; font-size: 24px; font-weight: 600; margin-bottom: 20px; }
        p { color: #4a5568; line-height: 1.6; margin-bottom: 30px; }
        a { display: inline-block; background: #667eea; color: white; padding: 12px 30px; border-radius: 6px; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <div class="container">
        <h1>⚠️ Oops! Something went wrong</h1>
        <div class="error-code">Error <?= $statusCode ?? 500 ?></div>
        <p><?= esc($message ?? 'An unexpected error occurred.') ?></p>
        <a href="/">Return to Home</a>
    </div>
</body>
</html>
EOF
```

### ขั้นที่ 3: แก้ไข permissions
```bash
docker compose exec app chown -R www-data:www-data /var/www/html/writable
docker compose exec app chmod -R 775 /var/www/html/writable
```

### ขั้นที่ 4: Clear cache
```bash
docker compose exec app php spark cache:clear
```

### ขั้นที่ 5: Restart services
```bash
docker compose restart app nginx
```

### ขั้นที่ 6: รอให้ services พร้อม
```bash
sleep 5
echo "✓ Done! Check your website now."
```

---

## 🔍 ตรวจสอบว่าแก้ไขสำเร็จ

```bash
# ตรวจสอบว่าไฟล์ถูกสร้าง
ls -la app/Views/errors/html/

# ตรวจสอบ containers
docker compose ps

# ดู logs
docker compose logs --tail=30 app
```

---

## ❌ ถ้ายังไม่หาย

### ดู error logs
```bash
docker compose logs --tail=50 app
docker compose exec nginx tail -f /var/log/nginx/asic-error.log
```

### Deploy ใหม่ทั้งหมด
```bash
cd /var/www/cs-asic-repair
./deploy.sh
```

---

## 💡 Tips

- ใช้ **วิธีที่ 1** ถ้าต้องการแก้ไขเร็วที่สุด (คัดลอกวางครั้งเดียวเสร็จ)
- ใช้ **วิธีที่ 2** ถ้า push ไฟล์ขึ้น GitHub แล้ว
- ใช้ **วิธีที่ 3** ถ้าต้องการดูผลแต่ละขั้นตอน

---

**หมายเหตุ:** คำสั่งเหล่านี้ออกแบบมาให้คัดลอกวางใน SSH PowerShell ที่เชื่อมต่อกับ server อยู่แล้ว
