# ASIC Repair Management System (R-POS)

ระบบจัดการงานซ่อมเครื่องขุด Bitcoin (ASIC Miners) - Repair Point of Sale / CRM

## Features

- **Job Management**: ระบบจัดการใบงานซ่อมครบวงจร (Check-in → Repair → Delivery)
- **Kanban Board**: หน้าจอ Drag & Drop สำหรับติดตามสถานะงาน
- **Customer & Asset Management**: จัดการข้อมูลลูกค้าและเครื่องขุด
- **Inventory Management**: ระบบคลังอะไหล่พร้อมการแจ้งเตือนสต็อกต่ำ
- **Multi-Branch Support**: รองรับการใช้งานหลายสาขา
- **Multi-Language**: รองรับ 3 ภาษา (English, 中文, ไทย)
- **Role-based Access**: Admin และ Technician
- **Audit Logging**: บันทึกการเปลี่ยนแปลงข้อมูลทุกครั้ง
- **Reports & KPIs**: รายงานสรุปและตัวชี้วัดหลัก

## Tech Stack

- **Backend**: PHP 8.1+ / CodeIgniter 4.5
- **Database**: SQLite (dev) / MySQL (production)
- **Frontend**: Bootstrap 5.3, jQuery 3.7, jQuery UI
- **Kanban**: SortableJS

## Installation

### Requirements

- PHP 8.1 or higher
- Composer
- SQLite / MySQL

### Setup

1. Clone the repository:
```bash
git clone <repository-url>
cd cs-asic-repair
```

2. Install dependencies:
```bash
composer install
```

3. Copy environment file:
```bash
cp env .env
```

4. Configure `.env` file with your database settings

5. Run migrations:
```bash
php spark migrate
```

6. Start development server:
```bash
php spark serve
```

7. Access the application at `http://localhost:8080`

## Default Login

- **Admin**: username: `admin`, password: `admin123`
- **Technician**: username: `technician`, password: `tech123`

## Directory Structure

```
app/
├── Config/          # Configuration files
├── Controllers/     # HTTP Controllers
├── Database/
│   └── Migrations/  # Database migrations
├── Filters/         # Auth & Role filters
├── Language/        # i18n translations (en, zh, th)
├── Models/          # Eloquent-style models
│   └── Traits/      # AuditTrait for logging
└── Views/           # Blade-like views
    ├── layouts/     # Main layout
    ├── auth/        # Login page
    ├── dashboard/   # Admin & Technician dashboards
    ├── jobs/        # Job management views
    ├── customers/   # Customer views
    ├── assets/      # Asset views
    ├── inventory/   # Inventory views
    ├── reports/     # Report views
    └── settings/    # Settings views

public/
└── assets/
    ├── css/         # Custom styles
    ├── js/          # Custom JavaScript
    └── img/         # Images
```

## Job Status Workflow

1. **New Check-in** - รับเครื่องเข้าใหม่
2. **Pending Repair** - รอซ่อม
3. **In Progress** - กำลังซ่อม
4. **Repair Done** - ซ่อมเสร็จ
5. **Ready for Handover** - พร้อมส่งมอบ
6. **Delivered/Closed** - ส่งมอบแล้ว (ตัดสต็อก)
7. **Cancelled** - ยกเลิก

## Job ID Format

Format: `YYMMDDXXX` (e.g., `251127001`)
- `YY`: ปี 2 หลัก
- `MM`: เดือน 2 หลัก
- `DD`: วัน 2 หลัก
- `XXX`: เลขรันต่อเนื่อง (รีเซ็ตทุกเดือน)

## License

MIT License

## Support

For support, please contact the development team.

