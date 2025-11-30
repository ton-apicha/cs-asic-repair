# ⚡ คำสั่งแก้ไขด่วน - คัดลอกวางใน SSH

## 🎯 คำสั่งเดียวจบ (แนะนำ)

คัดลอกและวางคำสั่งนี้ใน SSH PowerShell:

```bash
cd /var/www/cs-asic-repair && cat > app/Views/errors/html/production.php << 'EOF'
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
docker compose exec app chown -R www-data:www-data /var/www/html/writable && docker compose exec app chmod -R 775 /var/www/html/writable && docker compose exec app php spark cache:clear && docker compose restart app nginx && sleep 5 && echo "✓ Done! Check your website now."
```

---

## 📖 เอกสารเพิ่มเติม

- **SERVER-COMMANDS.sh** - คำสั่งทั้งหมดแบบละเอียด
- **TROUBLESHOOTING.md** - คู่มือแก้ไขปัญหา
- **FIX-SUMMARY.md** - สรุปการแก้ไข
