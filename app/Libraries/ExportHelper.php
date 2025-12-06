<?php

namespace App\Libraries;

/**
 * Export Helper Library
 * 
 * Export data to various formats (CSV, Excel-compatible CSV, PDF)
 */
class ExportHelper
{
    /**
     * Export data to CSV
     *
     * @param array $data Array of associative arrays
     * @param string $filename Filename without extension
     * @param array $headers Optional custom headers (column names)
     * @return \CodeIgniter\HTTP\DownloadResponse
     */
    public static function toCSV(array $data, string $filename, array $headers = []): \CodeIgniter\HTTP\DownloadResponse
    {
        $output = fopen('php://temp', 'r+');

        // Add BOM for Excel UTF-8 compatibility
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Write headers
        if (!empty($data)) {
            if (empty($headers)) {
                $headers = array_keys($data[0]);
            }
            fputcsv($output, $headers);
        }

        // Write data rows
        foreach ($data as $row) {
            $values = [];
            foreach ($headers as $header) {
                $key = array_search($header, $headers);
                if (is_numeric($key)) {
                    $key = array_keys($data[0])[$key] ?? $header;
                }
                $values[] = $row[$key] ?? '';
            }
            fputcsv($output, $values);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return service('response')
            ->download($filename . '.csv', $csv)
            ->setContentType('text/csv; charset=utf-8');
    }

    /**
     * Export data to Excel-compatible CSV with Thai support
     *
     * @param array $data Data array
     * @param string $filename Filename
     * @param array $columnConfig Column configuration [key => label]
     * @return \CodeIgniter\HTTP\DownloadResponse
     */
    public static function toExcel(array $data, string $filename, array $columnConfig = []): \CodeIgniter\HTTP\DownloadResponse
    {
        // If no column config, use data keys
        if (empty($columnConfig) && !empty($data)) {
            $columnConfig = array_combine(
                array_keys($data[0]),
                array_keys($data[0])
            );
        }

        $output = fopen('php://temp', 'r+');

        // Add BOM for Excel UTF-8 compatibility
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Write header row
        fputcsv($output, array_values($columnConfig));

        // Write data rows
        foreach ($data as $row) {
            $values = [];
            foreach ($columnConfig as $key => $label) {
                $value = $row[$key] ?? '';

                // Format numbers
                if (is_numeric($value) && strpos($key, 'id') === false) {
                    $value = number_format((float) $value, 2, '.', '');
                }

                // Format dates
                if (strpos($key, 'date') !== false || strpos($key, '_at') !== false) {
                    if (!empty($value)) {
                        $value = date('d/m/Y H:i', strtotime($value));
                    }
                }

                $values[] = $value;
            }
            fputcsv($output, $values);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return service('response')
            ->download($filename . '.csv', $csv)
            ->setContentType('application/vnd.ms-excel; charset=utf-8');
    }

    /**
     * Generate PDF from HTML
     *
     * @param string $html HTML content
     * @param string $filename Filename without extension
     * @param string $orientation 'portrait' or 'landscape'
     * @param string $size Paper size ('A4', 'Letter', etc.)
     * @return \CodeIgniter\HTTP\DownloadResponse|string
     */
    public static function toPDF(string $html, string $filename, string $orientation = 'portrait', string $size = 'A4'): \CodeIgniter\HTTP\DownloadResponse
    {
        // Use Dompdf
        $dompdf = new \Dompdf\Dompdf();

        // Set options
        $dompdf->getOptions()->setIsRemoteEnabled(true);
        $dompdf->getOptions()->setIsHtml5ParserEnabled(true);
        $dompdf->getOptions()->setDefaultFont('DejaVu Sans');

        // Load HTML
        $dompdf->loadHtml($html, 'UTF-8');

        // Set paper size and orientation
        $dompdf->setPaper($size, $orientation);

        // Render PDF
        $dompdf->render();

        // Output
        $pdfContent = $dompdf->output();

        return service('response')
            ->download($filename . '.pdf', $pdfContent)
            ->setContentType('application/pdf');
    }

    /**
     * Format job data for export
     *
     * @param array $jobs Job cards array
     * @return array
     */
    public static function formatJobsForExport(array $jobs): array
    {
        $formatted = [];

        foreach ($jobs as $job) {
            $formatted[] = [
                'job_id' => $job['job_id'],
                'customer_name' => $job['customer_name'] ?? '',
                'serial_number' => $job['serial_number'] ?? ($job['asset']['serial_number'] ?? ''),
                'symptom' => $job['symptom'],
                'status' => self::translateStatus($job['status']),
                'technician' => $job['technician_name'] ?? '',
                'checkin_date' => $job['checkin_date'],
                'delivery_date' => $job['delivery_date'] ?? '',
                'labor_cost' => $job['labor_cost'],
                'parts_cost' => $job['parts_cost'],
                'grand_total' => $job['grand_total'],
            ];
        }

        return $formatted;
    }

    /**
     * Format payments for export
     *
     * @param array $payments Payment array
     * @return array
     */
    public static function formatPaymentsForExport(array $payments): array
    {
        $formatted = [];

        foreach ($payments as $payment) {
            $formatted[] = [
                'payment_date' => $payment['payment_date'],
                'job_id' => $payment['job_id'] ?? '',
                'customer_name' => $payment['customer_name'] ?? '',
                'amount' => $payment['amount'],
                'payment_method' => self::translatePaymentMethod($payment['payment_method']),
                'reference' => $payment['reference'] ?? '',
            ];
        }

        return $formatted;
    }

    /**
     * Format inventory for export
     *
     * @param array $parts Parts array
     * @return array
     */
    public static function formatInventoryForExport(array $parts): array
    {
        $formatted = [];

        foreach ($parts as $part) {
            $formatted[] = [
                'part_code' => $part['part_code'],
                'name' => $part['name'],
                'category' => $part['category'] ?? '',
                'quantity' => $part['quantity'],
                'min_stock' => $part['min_stock'],
                'cost_price' => $part['cost_price'],
                'sell_price' => $part['sell_price'],
                'location' => $part['location'] ?? '',
            ];
        }

        return $formatted;
    }

    /**
     * Translate status to Thai
     */
    private static function translateStatus(string $status): string
    {
        $statuses = [
            'new_checkin' => 'รับเครื่องใหม่',
            'pending_repair' => 'รอซ่อม',
            'in_progress' => 'กำลังซ่อม',
            'repair_done' => 'ซ่อมเสร็จ',
            'ready_handover' => 'พร้อมส่งมอบ',
            'delivered' => 'ส่งมอบแล้ว',
            'cancelled' => 'ยกเลิก',
        ];

        return $statuses[$status] ?? $status;
    }

    /**
     * Translate payment method to Thai
     */
    private static function translatePaymentMethod(string $method): string
    {
        $methods = [
            'cash' => 'เงินสด',
            'transfer' => 'โอนเงิน',
            'card' => 'บัตรเครดิต/เดบิต',
            'qr' => 'QR Payment',
            'credit' => 'เครดิต',
        ];

        return $methods[$method] ?? $method;
    }

    /**
     * Get column configuration for job export
     */
    public static function getJobExportColumns(): array
    {
        return [
            'job_id' => 'เลขที่ใบงาน',
            'customer_name' => 'ชื่อลูกค้า',
            'serial_number' => 'หมายเลขเครื่อง',
            'symptom' => 'อาการเสีย',
            'status' => 'สถานะ',
            'technician' => 'ช่างซ่อม',
            'checkin_date' => 'วันที่รับเครื่อง',
            'delivery_date' => 'วันที่ส่งมอบ',
            'labor_cost' => 'ค่าแรง',
            'parts_cost' => 'ค่าอะไหล่',
            'grand_total' => 'รวมทั้งหมด',
        ];
    }

    /**
     * Get column configuration for payment export
     */
    public static function getPaymentExportColumns(): array
    {
        return [
            'payment_date' => 'วันที่ชำระ',
            'job_id' => 'เลขที่ใบงาน',
            'customer_name' => 'ชื่อลูกค้า',
            'amount' => 'จำนวนเงิน',
            'payment_method' => 'วิธีชำระ',
            'reference' => 'อ้างอิง',
        ];
    }

    /**
     * Get column configuration for inventory export
     */
    public static function getInventoryExportColumns(): array
    {
        return [
            'part_code' => 'รหัสอะไหล่',
            'name' => 'ชื่ออะไหล่',
            'category' => 'หมวดหมู่',
            'quantity' => 'จำนวน',
            'min_stock' => 'สต็อกขั้นต่ำ',
            'cost_price' => 'ราคาทุน',
            'sell_price' => 'ราคาขาย',
            'location' => 'ตำแหน่งเก็บ',
        ];
    }
}
