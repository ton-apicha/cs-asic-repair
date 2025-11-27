<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Job Card - <?= esc($job['job_id']) ?></title>
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
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #3b82f6;
        }
        
        .logo-subtitle {
            font-size: 10px;
            color: #666;
        }
        
        .job-id {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
        }
        
        .section {
            margin-bottom: 20px;
        }
        
        .section-title {
            background: #f8fafc;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            color: #64748b;
            border-left: 3px solid #3b82f6;
            margin-bottom: 10px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            width: 140px;
            padding: 5px 10px 5px 0;
            color: #666;
            font-size: 11px;
        }
        
        .info-value {
            display: table-cell;
            padding: 5px 0;
            font-weight: 500;
        }
        
        .two-col {
            width: 100%;
        }
        
        .two-col td {
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }
        
        table.parts-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .parts-table th {
            background: #f1f5f9;
            padding: 10px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .parts-table td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .parts-table .text-right {
            text-align: right;
        }
        
        .totals {
            margin-top: 20px;
            margin-left: auto;
            width: 250px;
        }
        
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .totals-row.grand {
            font-weight: bold;
            font-size: 14px;
            border-bottom: 2px solid #3b82f6;
            color: #3b82f6;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-new { background: #e0e7ff; color: #4338ca; }
        .status-pending { background: #fef3c7; color: #d97706; }
        .status-progress { background: #dbeafe; color: #2563eb; }
        .status-done { background: #d1fae5; color: #059669; }
        .status-ready { background: #cffafe; color: #0891b2; }
        .status-delivered { background: #e0e7ff; color: #7c3aed; }
        
        .signature-section {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        
        .signature-box {
            display: table-cell;
            width: 45%;
            text-align: center;
            padding: 20px;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 10px;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
        
        .qr-section {
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <table style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td style="width: 50%;">
                <div class="logo">ASIC Repair</div>
                <div class="logo-subtitle">Professional Mining Hardware Service</div>
            </td>
            <td style="width: 50%; text-align: right;">
                <div class="job-id"><?= esc($job['job_id']) ?></div>
                <div style="font-size: 10px; color: #666; margin-top: 5px;">
                    Check-in: <?= date('d/m/Y H:i', strtotime($job['checkin_date'])) ?>
                </div>
                <?php
                $statusClass = match($job['status']) {
                    'new_checkin' => 'status-new',
                    'pending_repair' => 'status-pending',
                    'in_progress' => 'status-progress',
                    'repair_done' => 'status-done',
                    'ready_handover' => 'status-ready',
                    'delivered' => 'status-delivered',
                    default => 'status-pending'
                };
                ?>
                <span class="status-badge <?= $statusClass ?>" style="margin-top: 10px;">
                    <?= ucfirst(str_replace('_', ' ', $job['status'])) ?>
                </span>
            </td>
        </tr>
    </table>
    
    <!-- Customer & Asset Info -->
    <table class="two-col">
        <tr>
            <td>
                <div class="section">
                    <div class="section-title">Customer Information</div>
                    <div class="info-grid">
                        <div class="info-row">
                            <span class="info-label">Name:</span>
                            <span class="info-value"><?= esc($customer['name']) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Phone:</span>
                            <span class="info-value"><?= esc($customer['phone']) ?></span>
                        </div>
                        <?php if (!empty($customer['email'])): ?>
                        <div class="info-row">
                            <span class="info-label">Email:</span>
                            <span class="info-value"><?= esc($customer['email']) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </td>
            <td>
                <div class="section">
                    <div class="section-title">Asset Information</div>
                    <div class="info-grid">
                        <div class="info-row">
                            <span class="info-label">Model:</span>
                            <span class="info-value"><?= esc($asset['brand_model']) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Serial Number:</span>
                            <span class="info-value"><?= esc($asset['serial_number']) ?></span>
                        </div>
                        <?php if (!empty($asset['mac_address'])): ?>
                        <div class="info-row">
                            <span class="info-label">MAC Address:</span>
                            <span class="info-value"><?= esc($asset['mac_address']) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </td>
        </tr>
    </table>
    
    <!-- Job Details -->
    <div class="section">
        <div class="section-title">Job Details</div>
        <div class="info-grid">
            <div class="info-row">
                <span class="info-label">Symptom:</span>
                <span class="info-value"><?= esc($job['symptom']) ?></span>
            </div>
            <?php if (!empty($job['diagnosis'])): ?>
            <div class="info-row">
                <span class="info-label">Diagnosis:</span>
                <span class="info-value"><?= esc($job['diagnosis']) ?></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($job['solution'])): ?>
            <div class="info-row">
                <span class="info-label">Solution:</span>
                <span class="info-value"><?= esc($job['solution']) ?></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($job['notes'])): ?>
            <div class="info-row">
                <span class="info-label">Notes:</span>
                <span class="info-value"><?= esc($job['notes']) ?></span>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Parts Used -->
    <?php if (!empty($parts)): ?>
    <div class="section">
        <div class="section-title">Parts Used</div>
        <table class="parts-table">
            <thead>
                <tr>
                    <th>Part Code</th>
                    <th>Description</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($parts as $part): ?>
                <tr>
                    <td><?= esc($part['part_code']) ?></td>
                    <td><?= esc($part['name']) ?></td>
                    <td class="text-right"><?= $part['quantity'] ?></td>
                    <td class="text-right">฿<?= number_format($part['sell_price'], 2) ?></td>
                    <td class="text-right">฿<?= number_format($part['quantity'] * $part['sell_price'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    
    <!-- Totals -->
    <table style="width: 100%;">
        <tr>
            <td style="width: 50%;"></td>
            <td style="width: 50%;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">Labor Cost:</td>
                        <td style="padding: 8px 0; text-align: right; border-bottom: 1px solid #e2e8f0;">฿<?= number_format($job['labor_cost'], 2) ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">Parts Cost:</td>
                        <td style="padding: 8px 0; text-align: right; border-bottom: 1px solid #e2e8f0;">฿<?= number_format($job['parts_cost'], 2) ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">Subtotal:</td>
                        <td style="padding: 8px 0; text-align: right; border-bottom: 1px solid #e2e8f0;">฿<?= number_format($job['subtotal'], 2) ?></td>
                    </tr>
                    <?php if ($job['vat_amount'] > 0): ?>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">VAT (7%):</td>
                        <td style="padding: 8px 0; text-align: right; border-bottom: 1px solid #e2e8f0;">฿<?= number_format($job['vat_amount'], 2) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td style="padding: 12px 0; font-weight: bold; font-size: 14px; color: #3b82f6;">Grand Total:</td>
                        <td style="padding: 12px 0; text-align: right; font-weight: bold; font-size: 14px; color: #3b82f6;">฿<?= number_format($job['grand_total'], 2) ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    
    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line">Customer Signature</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">Authorized Signature</div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <p>Thank you for choosing ASIC Repair. For inquiries, please contact us.</p>
        <p>Generated on <?= date('d/m/Y H:i') ?></p>
    </div>
</body>
</html>

