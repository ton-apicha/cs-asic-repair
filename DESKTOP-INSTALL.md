# 🖥️ ติดตั้ง Desktop Environment บน Ubuntu 22.04

## วิธีติดตั้ง

### 1. Push script ขึ้น GitHub
```powershell
cd e:\VSCODE\cs-asic-repair
git add install-desktop.sh
git commit -m "Add: Desktop environment installation script"
git push origin main
```

### 2. รันบน Server
```bash
cd /var/www/cs-asic-repair
git pull origin main
chmod +x install-desktop.sh
sudo ./install-desktop.sh
```

## Setup VNC Server

### 1. Start VNC ครั้งแรก
```bash
vncserver
```
ตั้งรหัสผ่าน VNC เมื่อถูกถาม

### 2. Stop VNC
```bash
vncserver -kill :1
```

### 3. Config VNC Startup
```bash
nano ~/.vnc/xstartup
```

เพิ่มเนื้อหานี้:
```bash
#!/bin/bash
xrdb $HOME/.Xresources
startxfce4 &
```

### 4. Make Executable
```bash
chmod +x ~/.vnc/xstartup
```

### 5. Start VNC Server
```bash
vncserver -geometry 1920x1080 -depth 24
```

### 6. เชื่อมต่อด้วย VNC Client
- ดาวน์โหลด VNC Viewer: https://www.realvnc.com/download/viewer/
- เชื่อมต่อไปที่: `YOUR_SERVER_IP:5901`
- ใส่รหัสผ่าน VNC ที่ตั้งไว้

## คำสั่งเดียวจบ (Copy-Paste บน Server)

```bash
cd /var/www/cs-asic-repair && git pull origin main && chmod +x install-desktop.sh && sudo ./install-desktop.sh
```

## หมายเหตุ

⚠️ **คำเตือน:** 
- Desktop environment จะใช้ RAM ประมาณ 500MB-1GB
- แนะนำให้ server มี RAM อย่างน้อย 4GB
- VNC ไม่ได้เข้ารหัส แนะนำให้ใช้ SSH Tunnel

## SSH Tunnel (ปลอดภัยกว่า)

แทนที่จะเชื่อมต่อ VNC โดยตรง ให้ใช้ SSH Tunnel:

```bash
ssh -L 5901:localhost:5901 root@YOUR_SERVER_IP
```

แล้วเชื่อมต่อ VNC ไปที่: `localhost:5901`
