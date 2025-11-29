# 🔧 ASIC Repair Management System (R-POS)

[![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-blue.svg)](https://www.php.net/)
[![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4.5-orange.svg)](https://codeigniter.com/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

ระบบจัดการงานซ่อมเครื่องขุด Bitcoin (ASIC Miners) แบบครบวงจร พร้อมระบบ CRM และ Inventory Management

> **Repair Point of Sale & CRM System** สำหรับธุรกิจศูนย์ซ่อมเครื่องขุด Cryptocurrency

---

## 📋 สารบัญ

- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Security Features](#-security-features)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [User Roles](#-user-roles)
- [Workflows](#-workflows)
- [Database Schema](#-database-schema)
- [API Endpoints](#-api-endpoints)
- [Testing](#-testing)
- [Troubleshooting](#-troubleshooting)
- [Contributing](#-contributing)
- [License](#-license)

---

## ✨ Features

### 🎯 Core Features

#### 1. **Job Management (งานซ่อม)**
- ✅ สร้างใบงานซ่อมพร้อมข้อมูลครบถ้วน (ลูกค้า, เครื่อง, อาการ)
- ✅ ระบบเลขที่งานอัตโนมัติ (Format: `YYMMDDXXX`)
- ✅ ติดตามสถานะงานแบบ Real-time
- ✅ บันทึกอาการ, วินิจฉัย, วิธีแก้ไข
- ✅ คำนวณค่าใช้จ่ายอัตโนมัติ (ค่าแรง + อะไหล่ + VAT)
- ✅ ระบบ Warranty Claim (อ้างอิงงานเดิม)
- ✅ Lock งานอัตโนมัติเมื่อส่งมอบ (ป้องกันแก้ไข)
- ✅ พิมพ์ใบรับเครื่อง / ใบเสร็จ / Label

#### 2. **Kanban Board (กระดานงาน)**
- ✅ Drag & Drop เปลี่ยนสถานะงาน
- ✅ Visual Pipeline ทั้ง 7 สถานะ
- ✅ Real-time Update
- ✅ Filter by Branch / Technician
- ✅ Color Coding ตามความสำคัญ

#### 3. **Customer Management (จัดการลูกค้า)**
- ✅ ฐานข้อมูลลูกค้าพร้อม Contact Info
- ✅ ประวัติการซ่อมทั้งหมด
- ✅ Credit System (วงเงินเครดิต)
- ✅ Search & Filter ขั้นสูง
- ✅ Export ข้อมูล

#### 4. **Asset Management (จัดการเครื่องขุด)**
- ✅ บันทึก Serial Number, MAC Address, Hash Rate
- ✅ ประวัติการซ่อมของแต่ละเครื่อง
- ✅ External Condition Notes
- ✅ Status Tracking (Stored, Repairing, Repaired, Returned)
- ✅ Quick Job Creation จากเครื่องเดิม

#### 5. **Inventory Management (คลังอะไหล่)**
- ✅ จัดการสต็อกอะไหล่
- ✅ ราคาทุน / ราคาขาย
- ✅ Reorder Point Alert (แจ้งเตือนสต็อกต่ำ)
- ✅ Stock Transaction History
- ✅ Auto Stock Deduction เมื่อส่งงาน
- ✅ Multi-Branch Inventory
- ✅ Central Warehouse Support

#### 6. **Multi-Branch Support (หลายสาขา)**
- ✅ รองรับการทำงานหลายสาขา
- ✅ Data Isolation ตามสาขา
- ✅ Central Warehouse (คลังกลาง)
- ✅ Super Admin เห็นทุกสาขา
- ✅ Branch-specific Reports

#### 7. **Reports & Analytics (รายงาน)**
- ✅ Dashboard สรุปภาพรวม
- ✅ Revenue Reports (รายวัน/รายเดือน)
- ✅ Job Statistics
- ✅ Technician Performance
- ✅ Low Stock Alerts
- ✅ Export to PDF/Excel

#### 8. **Multi-Language (3 ภาษา)**
- 🇺🇸 English
- 🇹🇭 ไทย
- 🇨🇳 中文 (简体)

#### 9. **Audit Trail (บันทึกการเปลี่ยนแปลง)**
- ✅ Log ทุก Create/Update/Delete
- ✅ บันทึก User, IP, Timestamp
- ✅ ข้อมูลก่อน/หลังการแก้ไข
- ✅ Security & Compliance

---

## 🛠 Tech Stack

### Backend
- **PHP 8.1+** - Modern PHP with strong typing
- **CodeIgniter 4.5** - Lightweight MVC Framework
- **SQLite3** (Development) / **MySQL 8.0+** (Production)

### Frontend
- **Bootstrap 5.3** - Responsive UI Framework
- **jQuery 3.7** - DOM Manipulation
- **jQuery UI** - Drag & Drop, Dialogs
- **SortableJS** - Kanban Board
- **Chart.js** - Analytics Charts
- **Select2** - Advanced Select Boxes
- **DataTables** - Table Enhancement

### Development Tools
- **Composer** - PHP Dependency Management
- **PHPUnit** - Testing Framework
- **Git** - Version Control

---

## 🔒 Security Features

ระบบผ่านการ **Security & Quality Audit** ครบถ้วน:

### ✅ Authentication & Authorization
- **CSRF Protection** - ป้องกัน Cross-Site Request Forgery
- **Session Security** - Session regeneration หลัง login
- **Rate Limiting** - จำกัด login attempts (5 ครั้ง / 15 นาที)
- **Password Hashing** - bcrypt with salt
- **Password Strength** - ต้องมี uppercase, lowercase, digit, min 8 ตัว
- **Role-based Access Control** - 3 roles: Super Admin, Admin, Technician

### ✅ Data Protection
- **Mass Assignment Protection** - ป้องกัน field ที่ sensitive
- **Input Validation** - ตรวจสอบข้อมูล input ทั้งหมด
- **SQL Injection Prevention** - Prepared Statements
- **XSS Protection** - Output Escaping
- **File Upload Security** - MIME type validation, size limits

### ✅ Error Handling
- **Generic Error Messages** - ไม่เปิดเผยข้อมูลระบบ
- **Detailed Logging** - Log errors for debugging
- **Exception Handling** - Try-catch wrappers
- **Database Error Protection** - Silent failures with logs

### ✅ Performance & Optimization
- **N+1 Query Prevention** - Eager loading
- **Database Indexes** - Optimized queries
- **Query Optimization** - Efficient joins and filters
- **Caching** - Cache service for rate limiting

---

## 📦 Installation

### Requirements

- **PHP** >= 8.1
- **Composer** >= 2.0
- **Database**: SQLite3 (dev) หรือ MySQL 8.0+ (production)
- **Extensions**: `php-intl`, `php-mbstring`, `php-json`, `php-pdo`

### Quick Start

```bash
# 1. Clone repository
git clone https://github.com/ton-apicha/cs-asic-repair.git
cd cs-asic-repair

# 2. Install dependencies
composer install

# 3. Setup environment
cp env .env
# แก้ไข .env ตามต้องการ

# 4. Run migrations
php spark migrate

# 5. Create Super Admin (optional)
php spark user:create-superadmin
# Username: superadmin
# Password: super123

# 6. Start development server
php spark serve

# 7. เปิดเบราว์เซอร์
http://localhost:8080
```

### Default Login Credentials

| Role | Username | Password |
|------|----------|----------|
| Admin | `admin` | `admin123` |
| Technician | `technician` | `tech123` |
| Super Admin | `superadmin` | `super123` |

> ⚠️ **สำคัญ**: เปลี่ยนรหัสผ่านทันทีหลังติดตั้งใน production!

---

## ⚙️ Configuration

### Database Configuration

แก้ไขไฟล์ `.env`:

```env
# SQLite (Development)
database.default.DBDriver = SQLite3
database.default.database = writable/database.db

# MySQL (Production)
database.default.DBDriver = MySQLi
database.default.hostname = localhost
database.default.database = asic_repair
database.default.username = your_username
database.default.password = your_password
database.default.DBPrefix = 
database.default.port = 3306
```

### Security Settings

```env
# CSRF Protection
security.csrf.protection = 'session'
security.csrf.tokenName = 'csrf_token'
security.csrf.expire = 7200

# Session
session.driver = 'CodeIgniter\Session\Handlers\FileHandler'
session.expiration = 7200
```

### Application Settings

```env
app.baseURL = 'http://localhost:8080/'
app.defaultLocale = 'th'
app.supportedLocales = ['en', 'th', 'zh']
```

---

## 👥 User Roles

### 1. Super Admin (ผู้ดูแลระบบสูงสุด)
- ✅ เข้าถึงได้ **ทุกสาขา**
- ✅ สลับดูข้อมูลแต่ละสาขา
- ✅ จัดการ Users, Branches, Settings
- ✅ ดูรายงานทุกสาขา
- ✅ ไม่มี branch_id (ไม่ได้ประจำสาขาใด)

### 2. Admin (ผู้ดูแลสาขา)
- ✅ จัดการได้ **เฉพาะสาขาของตน**
- ✅ สร้าง/แก้ไข Jobs, Customers, Assets
- ✅ จัดการ Inventory สาขา
- ✅ ดูรายงานสาขา
- ✅ จัดการ Users ในสาขา

### 3. Technician (ช่างซ่อม)
- ✅ ดูได้ **เฉพาะงานที่ assign ให้**
- ✅ อัพเดทสถานะงาน
- ✅ บันทึกข้อมูลการซ่อม
- ✅ เพิ่มอะไหล่ในงาน
- ✅ ไม่สามารถลบหรือยกเลิกงาน

---

## 🔄 Workflows

### Job Workflow (การทำงาน)

```
┌─────────────────┐
│ New Check-in    │ ← รับเครื่องเข้าซ่อม
└────────┬────────┘
         ↓
┌─────────────────┐
│ Pending Repair  │ ← รอคิวซ่อม
└────────┬────────┘
         ↓
┌─────────────────┐
│ In Progress     │ ← กำลังซ่อม (Technician)
└────────┬────────┘
         ↓
┌─────────────────┐
│ Repair Done     │ ← ซ่อมเสร็จ (QC)
└────────┬────────┘
         ↓
┌─────────────────┐
│ Ready Handover  │ ← พร้อมส่งมอบ
└────────┬────────┘
         ↓
┌─────────────────┐
│ Delivered       │ ← ส่งมอบแล้ว (ตัดสต็อก + Lock)
└─────────────────┘

        ⚠️
┌─────────────────┐
│ Cancelled       │ ← ยกเลิกงาน
└─────────────────┘
```

### Payment Workflow (การชำระเงิน)

```
Job Created → Add Parts → Calculate Total → Payment → Deliver
     ↓             ↓            ↓              ↓          ↓
  Labor Cost   Parts Cost   Grand Total   Record Pay  Deduct Stock
```

---

## 🗄 Database Schema

### Core Tables

#### users
```sql
- id, branch_id, username, password
- name, email, phone, role, is_active
- last_login, created_at, updated_at
```

#### branches
```sql
- id, name, address, phone
- is_active, created_at, updated_at
```

#### customers
```sql
- id, branch_id, name, phone, email
- address, tax_id, notes
- credit_limit, credit_used, credit_terms
```

#### assets (เครื่องขุด)
```sql
- id, customer_id, branch_id
- brand_model, serial_number, mac_address
- hash_rate, external_condition, status
```

#### job_cards
```sql
- id, job_id, customer_id, asset_id
- branch_id, technician_id
- symptom, diagnosis, solution, notes
- status, labor_cost, parts_cost, total_cost
- vat_amount, grand_total, amount_paid
- is_locked, is_warranty_claim
- checkin_date, delivery_date
```

#### parts_inventory
```sql
- id, branch_id, part_code, name
- cost_price, sell_price, quantity
- reorder_point, location, category
```

#### audit_logs
```sql
- id, user_id, action, table_name, record_id
- old_values, new_values
- ip_address, user_agent, created_at
```

---

## 🔌 API Endpoints

### Jobs API

```http
GET    /jobs                    # List all jobs
GET    /jobs/view/{id}          # View job details
POST   /jobs/store              # Create new job
POST   /jobs/update/{id}        # Update job
POST   /jobs/updateStatus/{id}  # Update status (Kanban)
POST   /jobs/cancel/{id}        # Cancel job
GET    /jobs/kanban             # Kanban board view
POST   /jobs/addPart/{id}       # Add part to job
POST   /jobs/removePart/{id}    # Remove part
```

### Customers API

```http
GET    /customers               # List customers
GET    /customers/view/{id}     # View customer
POST   /customers/store         # Create customer
POST   /customers/update/{id}   # Update customer
GET    /customers/search        # AJAX search
```

### Inventory API

```http
GET    /inventory               # List parts
POST   /inventory/store         # Add new part
POST   /inventory/update/{id}   # Update part
POST   /inventory/addStock/{id} # Add stock
GET    /inventory/lowStock      # Low stock alert
GET    /inventory/search        # AJAX search
```

---

## 🧪 Testing

### Run Tests

```bash
# Run all tests
./vendor/bin/phpunit

# Run specific test
./vendor/bin/phpunit tests/app/Models/JobCardModelTest.php

# With coverage
./vendor/bin/phpunit --coverage-html coverage/
```

### Test Structure

```
tests/
└── app/
    └── Models/
        ├── JobCardModelTest.php
        └── PartsInventoryModelTest.php
```

---

## 📂 Directory Structure

```
cs-asic-repair/
├── app/
│   ├── Commands/              # CLI Commands
│   │   └── CreateSuperAdmin.php
│   ├── Config/                # Configuration
│   │   ├── Database.php
│   │   ├── Filters.php
│   │   └── Routes.php
│   ├── Controllers/           # HTTP Controllers
│   │   ├── AuthController.php
│   │   ├── JobController.php
│   │   ├── CustomerController.php
│   │   ├── AssetController.php
│   │   ├── InventoryController.php
│   │   ├── DashboardController.php
│   │   ├── SettingController.php
│   │   └── BranchController.php
│   ├── Database/
│   │   └── Migrations/        # Database migrations
│   ├── Filters/               # Request filters
│   │   ├── AuthFilter.php
│   │   └── RoleFilter.php
│   ├── Language/              # i18n translations
│   │   ├── en/
│   │   ├── th/
│   │   └── zh/
│   ├── Models/                # Database models
│   │   ├── UserModel.php
│   │   ├── JobCardModel.php
│   │   ├── CustomerModel.php
│   │   ├── AssetModel.php
│   │   ├── PartsInventoryModel.php
│   │   └── Traits/
│   │       └── AuditTrait.php
│   └── Views/                 # HTML templates
│       ├── layouts/
│       ├── auth/
│       ├── dashboard/
│       ├── jobs/
│       ├── customers/
│       ├── assets/
│       ├── inventory/
│       └── settings/
├── public/                    # Web root
│   ├── index.php
│   └── assets/
│       ├── css/
│       ├── js/
│       └── images/
├── tests/                     # PHPUnit tests
├── writable/                  # Logs, cache, uploads
├── vendor/                    # Composer dependencies
├── .env                       # Environment config
├── composer.json
├── phpunit.xml
└── README.md
```

---

## 🐛 Troubleshooting

### ปัญหาที่พบบ่อย

#### 1. Cannot Login

```bash
# ตรวจสอบว่ามี admin user หรือไม่
php spark user:create-superadmin

# หรือสร้างใหม่ใน database
```

#### 2. CSRF Token Mismatch

```bash
# ลบ session cache
rm -rf writable/session/*

# หรือปิด CSRF ใน development (.env)
security.csrf.protection = false
```

#### 3. Database Connection Error

```bash
# ตรวจสอบ .env
# ตรวจสอบว่า MySQL service ทำงาน
# ตรวจสอบ username/password

# SQLite: ตรวจสอบว่ามี writable/database.db
touch writable/database.db
chmod 777 writable/database.db
```

#### 4. Permission Denied

```bash
# Set permissions
chmod -R 777 writable/
chmod -R 755 public/assets/
```

#### 5. Composer Dependencies

```bash
# Update dependencies
composer update

# Clear cache
php spark cache:clear
```

---

## 🚀 Deployment

### Production Checklist

- [ ] เปลี่ยน `CI_ENVIRONMENT` เป็น `production`
- [ ] เปลี่ยนรหัสผ่าน default ทั้งหมด
- [ ] ตั้งค่า database production
- [ ] เปิด HTTPS / SSL
- [ ] ตั้งค่า Backup อัตโนมัติ
- [ ] ตั้งค่า Error Logging
- [ ] ปิด Debug Mode
- [ ] ตรวจสอบ File Permissions
- [ ] ตั้งค่า Cron Jobs (ถ้ามี)
- [ ] Load Test

### Environment Variables (Production)

```env
CI_ENVIRONMENT = production

app.baseURL = 'https://yourdomain.com/'
app.forceGlobalSecureRequests = true

database.default.DBDebug = false
```

---

## 📊 Performance

- **Response Time**: < 200ms (average)
- **Database Queries**: Optimized with indexes
- **Caching**: Rate limiting cache
- **N+1 Prevention**: Eager loading implemented

---

## 🔄 Version History

### v2.0.0 - Security & Quality Audit (2024-11-29)
- ✅ Complete Security & Quality Audit
- ✅ CSRF Protection enabled
- ✅ Rate Limiting (5 attempts/15 min)
- ✅ Session Regeneration
- ✅ Mass Assignment Protection
- ✅ Input Validation Enhancement
- ✅ N+1 Query Optimization
- ✅ Database Indexes Added
- ✅ Bug Fixes: Job Lock, Branch Filter

### v1.5.0 - Multi-Branch Support (2024-11-28)
- ✅ Super Admin Role
- ✅ Branch Management
- ✅ Branch-specific Data Isolation
- ✅ Central Warehouse Support

### v1.0.0 - Initial Release (2024-11-27)
- ✅ Core Features
- ✅ Job Management
- ✅ Kanban Board
- ✅ Customer & Asset Management
- ✅ Inventory Management
- ✅ Multi-Language Support

---

## 🤝 Contributing

เรายินดีรับ contributions! กรุณาทำตามขั้นตอนนี้:

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Coding Standards

- ใช้ PSR-12 Coding Standard
- เขียน PHPUnit Tests สำหรับ features ใหม่
- อัพเดท Documentation
- Comment code ให้ชัดเจน

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👨‍💻 Author

**CS ASIC Repair Team**

---

## 🙏 Acknowledgments

- CodeIgniter Framework Team
- Bootstrap Team
- All Open Source Contributors

---

## 📞 Support

หากมีปัญหาหรือต้องการความช่วยเหลือ:

- 📧 Email: support@example.com
- 🐛 Issues: [GitHub Issues](https://github.com/ton-apicha/cs-asic-repair/issues)
- 📖 Documentation: [Wiki](https://github.com/ton-apicha/cs-asic-repair/wiki)

---

## 🌟 Star History

[![Star History Chart](https://api.star-history.com/svg?repos=ton-apicha/cs-asic-repair&type=Date)](https://star-history.com/#ton-apicha/cs-asic-repair&Date)

---

<div align="center">

**Made with ❤️ for ASIC Repair Shops**

[⬆ กลับไปด้านบน](#-asic-repair-management-system-r-pos)

</div>
