<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quotation - <?= esc($quotation['quotation_no'] ?? 'DRAFT') ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            padding: 20px;
        }
        
        .header {
            border-bottom: 3px solid #6366f1;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        
        .logo {
            font-size: 26px;
            font-weight: bold;
            color: #6366f1;
        }
        
        .logo-subtitle {
            font-size: 10px;
            color: #666;
        }
        
        .quotation-title {
            font-size: 24px;
            text-align: right;
            color: #6366f1;
            font-weight: bold;
        }
        
        .quotation-no {
            font-size: 12px;
            color: #666;
            text-align: right;
            margin-top: 5px;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            color: #6366f1;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .info-grid {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
        }
        
        .info-row {
            margin-bottom: 5px;
        }
        
        .info-label {
            color: #666;
            font-size: 10px;
            text-transform: uppercase;
        }
        
        .info-value {
            font-weight: 500;
            margin-top: 2px;
        }
        
        table.items-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .items-table th {
            background: #eef2ff;
            padding: 12px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            color: #4338ca;
            border-bottom: 2px solid #6366f1;
        }
        
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }
        
        .items-table .text-right {
            text-align: right;
        }
        
        .items-table .text-center {
            text-align: center;
        }
        
        .item-desc {
            font-size: 10px;
            color: #666;
            margin-top: 3px;
        }
        
        .totals-section {
            margin-top: 30px;
        }
        
        .totals-table {
            width: 280px;
            margin-left: auto;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .totals-table td {
            padding: 10px 15px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .totals-table tr:last-child td {
            border-bottom: none;
        }
        
        .totals-table .label {
            color: #666;
        }
        
        .totals-table .value {
            text-align: right;
            font-weight: 500;
        }
        
        .grand-total {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
        }
        
        .grand-total td {
            font-size: 14px;
            font-weight: bold;
            padding: 15px;
        }
        
        .terms-section {
            margin-top: 30px;
            padding: 15px;
            background: #fef3c7;
            border-radius: 8px;
            border-left: 4px solid #f59e0b;
        }
        
        .terms-title {
            font-weight: bold;
            color: #d97706;
            margin-bottom: 10px;
        }
        
        .terms-list {
            font-size: 11px;
            color: #92400e;
        }
        
        .terms-list li {
            margin-bottom: 5px;
        }
        
        .validity {
            margin-top: 20px;
            padding: 15px;
            background: #fee2e2;
            border-radius: 8px;
            text-align: center;
        }
        
        .validity-text {
            color: #dc2626;
            font-weight: bold;
        }
        
        .signature-section {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        
        .signature-box {
            display: table-cell;
            width: 45%;
            padding: 20px;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 10px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        .signature-name {
            margin-top: 5px;
            font-size: 10px;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(99, 102, 241, 0.05);
            font-weight: bold;
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="watermark">QUOTATION</div>
    
    <!-- Header -->
    <table style="width: 100%;" class="header">
        <tr>
            <td style="width: 50%;">
                <div class="logo">ASIC Repair</div>
                <div class="logo-subtitle">Professional Mining Hardware Service</div>
                <div style="margin-top: 10px; font-size: 10px; color: #666;">
                    <p>123 Repair Street, Tech District</p>
                    <p>Tel: 02-XXX-XXXX | Email: info@asic-repair.com</p>
                </div>
            </td>
            <td style="width: 50%; text-align: right;">
                <div class="quotation-title">QUOTATION</div>
                <div class="quotation-no">
                    No: <?= esc($quotation['quotation_no'] ?? 'DRAFT') ?><br>
                    Date: <?= date('d/m/Y', strtotime($quotation['created_at'] ?? 'now')) ?>
                </div>
            </td>
        </tr>
    </table>
    
    <!-- Customer Info -->
    <div class="section">
        <div class="section-title">Quote For</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Customer Name</div>
                <div class="info-value"><?= esc($customer['name']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Phone</div>
                <div class="info-value"><?= esc($customer['phone']) ?></div>
            </div>
            <?php if (!empty($customer['email'])): ?>
            <div class="info-row">
                <div class="info-label">Email</div>
                <div class="info-value"><?= esc($customer['email']) ?></div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Items -->
    <div class="section">
        <div class="section-title">Service & Parts</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 45%;">Description</th>
                    <th class="text-center" style="width: 10%;">Qty</th>
                    <th class="text-right" style="width: 20%;">Unit Price</th>
                    <th class="text-right" style="width: 20%;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $subtotal = 0;
                $itemNo = 1;
                foreach ($items as $item): 
                    $lineTotal = ($item['quantity'] ?? 1) * ($item['unit_price'] ?? 0);
                    $subtotal += $lineTotal;
                ?>
                <tr>
                    <td class="text-center"><?= $itemNo++ ?></td>
                    <td>
                        <strong><?= esc($item['name']) ?></strong>
                        <?php if (!empty($item['description'])): ?>
                        <div class="item-desc"><?= esc($item['description']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="text-center"><?= $item['quantity'] ?? 1 ?></td>
                    <td class="text-right">฿<?= number_format($item['unit_price'] ?? 0, 2) ?></td>
                    <td class="text-right">฿<?= number_format($lineTotal, 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Totals -->
    <div class="totals-section">
        <table class="totals-table">
            <tr>
                <td class="label">Subtotal:</td>
                <td class="value">฿<?= number_format($subtotal, 2) ?></td>
            </tr>
            <?php 
            $vat = ($quotation['include_vat'] ?? false) ? $subtotal * 0.07 : 0;
            if ($vat > 0):
            ?>
            <tr>
                <td class="label">VAT (7%):</td>
                <td class="value">฿<?= number_format($vat, 2) ?></td>
            </tr>
            <?php endif; ?>
            <tr class="grand-total">
                <td>Grand Total:</td>
                <td class="value">฿<?= number_format($subtotal + $vat, 2) ?></td>
            </tr>
        </table>
    </div>
    
    <!-- Terms -->
    <div class="terms-section">
        <div class="terms-title">Terms & Conditions</div>
        <ul class="terms-list">
            <li>This quotation is valid for 30 days from the date of issue.</li>
            <li>50% deposit required to proceed with the service.</li>
            <li>Final price may vary based on actual parts used.</li>
            <li>Warranty: 30 days for labor, parts warranty as per manufacturer.</li>
        </ul>
    </div>
    
    <!-- Validity -->
    <div class="validity">
        <span class="validity-text">This quotation is valid until: <?= date('d/m/Y', strtotime('+30 days')) ?></span>
    </div>
    
    <!-- Signature -->
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line">
                Customer Acceptance
                <div class="signature-name">Date: _______________</div>
            </div>
        </div>
        <div class="signature-box">
            <div class="signature-line">
                Authorized Signature
                <div class="signature-name">ASIC Repair</div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <p>Thank you for considering ASIC Repair for your service needs.</p>
        <p>Generated on <?= date('d/m/Y H:i') ?></p>
    </div>
</body>
</html>

