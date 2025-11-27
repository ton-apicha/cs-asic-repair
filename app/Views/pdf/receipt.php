<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Receipt - <?= esc($job['job_id']) ?></title>
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
            text-align: center;
            border-bottom: 2px solid #10b981;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #10b981;
        }
        
        .receipt-title {
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: #666;
            margin-top: 10px;
        }
        
        .receipt-no {
            font-size: 14px;
            color: #333;
            margin-top: 5px;
        }
        
        .section {
            margin-bottom: 20px;
        }
        
        .section-title {
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            color: #10b981;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        
        .info-row {
            display: flex;
            padding: 5px 0;
        }
        
        .info-label {
            width: 120px;
            color: #666;
        }
        
        .info-value {
            font-weight: 500;
        }
        
        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .items-table th {
            background: #f0fdf4;
            padding: 12px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            color: #059669;
            border-bottom: 2px solid #10b981;
        }
        
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .items-table .text-right {
            text-align: right;
        }
        
        .totals-section {
            margin: 30px 0;
        }
        
        .totals-table {
            width: 300px;
            margin-left: auto;
        }
        
        .totals-table td {
            padding: 8px 0;
        }
        
        .totals-table .label {
            color: #666;
        }
        
        .totals-table .value {
            text-align: right;
            font-weight: 500;
        }
        
        .grand-total {
            background: #10b981;
            color: white;
            font-size: 16px;
            font-weight: bold;
        }
        
        .grand-total td {
            padding: 15px 10px;
        }
        
        .payment-info {
            background: #f0fdf4;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .payment-status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 11px;
        }
        
        .status-paid {
            background: #d1fae5;
            color: #059669;
        }
        
        .status-partial {
            background: #fef3c7;
            color: #d97706;
        }
        
        .status-unpaid {
            background: #fee2e2;
            color: #dc2626;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 10px;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }
        
        .thank-you {
            font-size: 16px;
            color: #10b981;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo">ASIC Repair</div>
        <div class="receipt-title">Payment Receipt</div>
        <div class="receipt-no">Receipt No: <?= esc($job['job_id']) ?></div>
        <div style="font-size: 11px; color: #666; margin-top: 5px;">
            Date: <?= date('d/m/Y H:i') ?>
        </div>
    </div>
    
    <!-- Customer Info -->
    <table style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <div class="section">
                    <div class="section-title">Bill To</div>
                    <p><strong><?= esc($customer['name']) ?></strong></p>
                    <p><?= esc($customer['phone']) ?></p>
                    <?php if (!empty($customer['email'])): ?>
                    <p><?= esc($customer['email']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($customer['address'])): ?>
                    <p><?= esc($customer['address']) ?></p>
                    <?php endif; ?>
                </div>
            </td>
            <td style="width: 50%; vertical-align: top;">
                <div class="section">
                    <div class="section-title">Job Reference</div>
                    <p><strong>Job ID:</strong> <?= esc($job['job_id']) ?></p>
                    <p><strong>Check-in:</strong> <?= date('d/m/Y', strtotime($job['checkin_date'])) ?></p>
                    <?php if (!empty($job['delivery_date'])): ?>
                    <p><strong>Delivery:</strong> <?= date('d/m/Y', strtotime($job['delivery_date'])) ?></p>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
    </table>
    
    <!-- Items -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 50%;">Description</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>Repair Service</strong><br>
                    <span style="font-size: 11px; color: #666;"><?= esc($job['symptom']) ?></span>
                </td>
                <td class="text-right">฿<?= number_format($job['labor_cost'], 2) ?></td>
            </tr>
            <?php if ($job['parts_cost'] > 0): ?>
            <tr>
                <td>
                    <strong>Parts & Materials</strong><br>
                    <span style="font-size: 11px; color: #666;">Replacement parts used</span>
                </td>
                <td class="text-right">฿<?= number_format($job['parts_cost'], 2) ?></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <!-- Totals -->
    <div class="totals-section">
        <table class="totals-table">
            <tr>
                <td class="label">Subtotal:</td>
                <td class="value">฿<?= number_format($job['subtotal'], 2) ?></td>
            </tr>
            <?php if ($job['vat_amount'] > 0): ?>
            <tr>
                <td class="label">VAT (7%):</td>
                <td class="value">฿<?= number_format($job['vat_amount'], 2) ?></td>
            </tr>
            <?php endif; ?>
            <tr class="grand-total">
                <td>Grand Total:</td>
                <td class="value">฿<?= number_format($job['grand_total'], 2) ?></td>
            </tr>
        </table>
    </div>
    
    <!-- Payment History -->
    <?php if (!empty($payments)): ?>
    <div class="section">
        <div class="section-title">Payment History</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Method</th>
                    <th>Reference</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalPaid = 0;
                foreach ($payments as $payment): 
                    $totalPaid += $payment['amount'];
                ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($payment['payment_date'])) ?></td>
                    <td><?= ucfirst($payment['payment_method']) ?></td>
                    <td><?= esc($payment['reference_no'] ?? '-') ?></td>
                    <td class="text-right">฿<?= number_format($payment['amount'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="payment-info">
        <table style="width: 100%;">
            <tr>
                <td><strong>Total Paid:</strong></td>
                <td style="text-align: right;">฿<?= number_format($totalPaid, 2) ?></td>
            </tr>
            <tr>
                <td><strong>Balance Due:</strong></td>
                <td style="text-align: right;">฿<?= number_format($job['grand_total'] - $totalPaid, 2) ?></td>
            </tr>
            <tr>
                <td><strong>Status:</strong></td>
                <td style="text-align: right;">
                    <?php
                    $balance = $job['grand_total'] - $totalPaid;
                    if ($balance <= 0) {
                        echo '<span class="payment-status status-paid">PAID IN FULL</span>';
                    } elseif ($totalPaid > 0) {
                        echo '<span class="payment-status status-partial">PARTIAL</span>';
                    } else {
                        echo '<span class="payment-status status-unpaid">UNPAID</span>';
                    }
                    ?>
                </td>
            </tr>
        </table>
    </div>
    <?php endif; ?>
    
    <!-- Footer -->
    <div class="footer">
        <div class="thank-you">Thank you for your business!</div>
        <p>This receipt is computer generated and valid without signature.</p>
        <p>For any questions, please contact us.</p>
    </div>
</body>
</html>

