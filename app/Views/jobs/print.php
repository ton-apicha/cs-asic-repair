<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ใบรับสินค้า #<?= esc($job['job_id']) ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Sarabun', 'Tahoma', sans-serif; 
            font-size: 14px; 
            line-height: 1.6;
            padding: 20px;
        }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { font-size: 24px; margin-bottom: 5px; }
        .header p { font-size: 12px; color: #666; }
        .job-info { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .job-info .job-id { font-size: 20px; font-weight: bold; }
        .section { margin-bottom: 15px; }
        .section-title { font-weight: bold; background: #f0f0f0; padding: 5px 10px; margin-bottom: 10px; }
        .info-row { display: flex; margin-bottom: 5px; }
        .info-label { width: 150px; font-weight: bold; }
        .info-value { flex: 1; }
        .symptom-box { border: 1px solid #ccc; padding: 10px; min-height: 60px; background: #fafafa; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
        .signature-area { display: flex; justify-content: space-between; margin-top: 50px; }
        .signature-box { width: 200px; text-align: center; }
        .signature-line { border-top: 1px solid #000; margin-top: 50px; padding-top: 5px; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 30px; font-size: 16px; cursor: pointer;">
            🖨️ พิมพ์
        </button>
    </div>

    <div class="header">
        <h1>ASIC Repair Center</h1>
        <p>ใบรับสินค้าเพื่อซ่อม (Check-in Slip)</p>
    </div>

    <div class="job-info">
        <div>
            <div class="job-id">Job #<?= esc($job['job_id']) ?></div>
            <div>วันที่รับ: <?= date('d/m/Y H:i', strtotime($job['checkin_date'])) ?></div>
        </div>
        <div style="text-align: right;">
            <div>สาขา: <?= esc($job['branch_name']) ?></div>
            <div>ผู้รับ: <?= esc($job['created_by_name'] ?? '-') ?></div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">ข้อมูลลูกค้า</div>
        <div class="info-row">
            <div class="info-label">ชื่อ:</div>
            <div class="info-value"><?= esc($job['customer_name']) ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">เบอร์โทร:</div>
            <div class="info-value"><?= esc($job['customer_phone']) ?></div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">ข้อมูลเครื่อง</div>
        <div class="info-row">
            <div class="info-label">ยี่ห้อ/รุ่น:</div>
            <div class="info-value"><?= esc($job['brand_model']) ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Serial Number:</div>
            <div class="info-value"><?= esc($job['serial_number']) ?></div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">อาการเสียเบื้องต้น</div>
        <div class="symptom-box"><?= nl2br(esc($job['symptom'])) ?></div>
    </div>

    <div class="section">
        <div class="section-title">เงื่อนไขการซ่อม</div>
        <ul style="padding-left: 20px; font-size: 12px;">
            <li>การซ่อมจะเริ่มหลังจากลูกค้าอนุมัติใบเสนอราคา</li>
            <li>ระยะเวลาการซ่อมประมาณ 3-7 วันทำการ (อาจมากกว่านี้หากต้องรออะไหล่)</li>
            <li>รับประกันการซ่อม 30 วัน (เฉพาะอะไหล่ที่เปลี่ยน)</li>
            <li>กรุณาเก็บใบรับสินค้านี้เพื่อใช้ในการรับเครื่องคืน</li>
        </ul>
    </div>

    <div class="signature-area">
        <div class="signature-box">
            <div class="signature-line">ลายเซ็นผู้รับเครื่อง</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">ลายเซ็นลูกค้า</div>
        </div>
    </div>

    <div class="footer">
        <p>ขอบคุณที่ใช้บริการ ASIC Repair Center</p>
        <p>หากมีข้อสงสัย กรุณาติดต่อ 02-xxx-xxxx</p>
    </div>
</body>
</html>

