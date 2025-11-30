# Fix MySQLi Extension Missing

## สาเหตุ
PHP extension "mysqli" ไม่ได้ถูก install ใน Docker container

## การแก้ไข
เพิ่ม `mysqli` ใน Dockerfile

## วิธี Deploy

### 1. Push code ขึ้น GitHub (ทำบนเครื่อง Local)
```powershell
cd e:\VSCODE\cs-asic-repair
git add Dockerfile rebuild-docker.sh
git commit -m "Fix: Add mysqli PHP extension to Dockerfile"
git push origin main
```

### 2. Rebuild Docker บน Server
```bash
cd /var/www/cs-asic-repair
git stash
git pull origin main
chmod +x rebuild-docker.sh
./rebuild-docker.sh
```

## คำสั่งสำหรับ Server (Copy-Paste)

```bash
cd /var/www/cs-asic-repair && git stash && git pull origin main && chmod +x rebuild-docker.sh && ./rebuild-docker.sh
```

---

**หมายเหตุ:** การ rebuild จะใช้เวลาประมาณ 2-3 นาที
