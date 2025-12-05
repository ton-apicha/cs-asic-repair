<?php

namespace App\Libraries;

/**
 * Error Helper Library
 * 
 * Provides user-friendly error messages in Thai
 * Hides technical details from end users
 */
class ErrorHelper
{
    /**
     * Get a user-friendly error message
     *
     * @param string $key Error message key
     * @param array $params Parameters to replace in message
     * @return string
     */
    public static function getMessage(string $key, array $params = []): string
    {
        $message = lang('Errors.' . $key);

        // If language key not found, return generic error
        if ($message === 'Errors.' . $key) {
            return lang('Errors.generalError');
        }

        // Replace parameters in message
        foreach ($params as $param => $value) {
            $message = str_replace('{' . $param . '}', $value, $message);
        }

        // Also support numeric params like {0}, {1}
        foreach (array_values($params) as $index => $value) {
            $message = str_replace('{' . $index . '}', $value, $message);
        }

        return $message;
    }

    /**
     * Get field-specific validation error
     *
     * @param string $field Field name
     * @param string $rule Validation rule that failed
     * @param array $params Additional parameters
     * @return string
     */
    public static function getValidationError(string $field, string $rule, array $params = []): string
    {
        // Field name translations
        $fieldNames = [
            'username'      => 'ชื่อผู้ใช้',
            'password'      => 'รหัสผ่าน',
            'email'         => 'อีเมล',
            'phone'         => 'เบอร์โทรศัพท์',
            'name'          => 'ชื่อ',
            'customer_name' => 'ชื่อลูกค้า',
            'serial_number' => 'หมายเลขเครื่อง',
            'brand_model'   => 'ยี่ห้อ/รุ่น',
            'symptom'       => 'อาการเสีย',
            'part_code'     => 'รหัสอะไหล่',
            'part_name'     => 'ชื่ออะไหล่',
            'quantity'      => 'จำนวน',
            'price'         => 'ราคา',
            'amount'        => 'จำนวนเงิน',
            'branch_id'     => 'สาขา',
            'address'       => 'ที่อยู่',
            'tax_id'        => 'เลขประจำตัวผู้เสียภาษี',
        ];

        $fieldLabel = $fieldNames[$field] ?? $field;

        // Rule to message mapping
        $ruleMessages = [
            'required'        => 'กรุณากรอก' . $fieldLabel,
            'min_length'      => $fieldLabel . 'ต้องมีอย่างน้อย {0} ตัวอักษร',
            'max_length'      => $fieldLabel . 'ต้องไม่เกิน {0} ตัวอักษร',
            'valid_email'     => 'กรุณากรอกอีเมลที่ถูกต้อง',
            'numeric'         => 'กรุณากรอกตัวเลขใน' . $fieldLabel,
            'integer'         => 'กรุณากรอกจำนวนเต็มใน' . $fieldLabel,
            'decimal'         => 'กรุณากรอกตัวเลขที่ถูกต้องใน' . $fieldLabel,
            'greater_than'    => $fieldLabel . 'ต้องมากกว่า {0}',
            'less_than'       => $fieldLabel . 'ต้องน้อยกว่า {0}',
            'matches'         => $fieldLabel . 'ไม่ตรงกัน',
            'is_unique'       => $fieldLabel . 'นี้ถูกใช้งานแล้ว',
            'is_not_unique'   => 'ไม่พบ' . $fieldLabel . 'ในระบบ',
            'valid_date'      => 'กรุณาเลือกวันที่ที่ถูกต้อง',
            'uploaded'        => 'กรุณาเลือกไฟล์ที่ต้องการอัปโหลด',
            'max_size'        => 'ไฟล์มีขนาดใหญ่เกินไป',
            'is_image'        => 'กรุณาอัปโหลดไฟล์รูปภาพ',
            'mime_in'         => 'ประเภทไฟล์ไม่รองรับ',
            'regex_match'     => $fieldLabel . 'มีรูปแบบไม่ถูกต้อง',
        ];

        $message = $ruleMessages[$rule] ?? 'กรุณาตรวจสอบ' . $fieldLabel;

        // Replace parameters
        foreach ($params as $index => $value) {
            $message = str_replace('{' . $index . '}', $value, $message);
        }

        return $message;
    }

    /**
     * Convert database/system errors to user-friendly messages
     *
     * @param \Throwable $exception
     * @return string
     */
    public static function translateException(\Throwable $exception): string
    {
        $message = $exception->getMessage();
        $code = $exception->getCode();

        // Database connection errors
        if (stripos($message, 'connection') !== false || $code === 2002) {
            return lang('Errors.connectionFailed');
        }

        // Duplicate entry errors
        if (stripos($message, 'duplicate') !== false || $code === 1062) {
            return lang('Errors.dataAlreadyExists');
        }

        // Foreign key constraint errors
        if (stripos($message, 'foreign key') !== false || $code === 1451) {
            return lang('Errors.cannotDeleteInUse');
        }

        // Timeout errors
        if (stripos($message, 'timeout') !== false) {
            return lang('Errors.timeout');
        }

        // File upload errors
        if (stripos($message, 'upload') !== false) {
            return lang('Errors.uploadFailed');
        }

        // Default: generic error
        return lang('Errors.generalError');
    }

    /**
     * Format validation errors array for display
     *
     * @param array $errors Validation errors from CodeIgniter
     * @return array User-friendly error messages
     */
    public static function formatValidationErrors(array $errors): array
    {
        $formatted = [];

        foreach ($errors as $field => $rules) {
            if (is_array($rules)) {
                foreach ($rules as $rule => $message) {
                    $formatted[$field] = self::getValidationError($field, $rule);
                }
            } else {
                // Already a message string
                $formatted[$field] = $rules;
            }
        }

        return $formatted;
    }

    /**
     * Get appropriate HTTP error message
     *
     * @param int $statusCode HTTP status code
     * @return string
     */
    public static function getHttpError(int $statusCode): string
    {
        $httpErrors = [
            400 => lang('Errors.checkInput'),
            401 => lang('Errors.unauthorized'),
            403 => lang('Errors.accessDenied'),
            404 => lang('Errors.pageNotFound'),
            405 => lang('Errors.permissionDenied'),
            408 => lang('Errors.timeout'),
            422 => lang('Errors.checkInput'),
            429 => lang('Errors.tooManyRequests'),
            500 => lang('Errors.serverError'),
            502 => lang('Errors.serviceUnavailable'),
            503 => lang('Errors.maintenanceMode'),
            504 => lang('Errors.timeout'),
        ];

        return $httpErrors[$statusCode] ?? lang('Errors.generalError');
    }

    /**
     * Log error for debugging while returning safe message to user
     *
     * @param \Throwable $exception
     * @param string $context Additional context for logging
     * @return string Safe user message
     */
    public static function logAndGetMessage(\Throwable $exception, string $context = ''): string
    {
        // Log the technical details
        log_message('error', '[' . $context . '] ' . $exception->getMessage());
        log_message('debug', $exception->getTraceAsString());

        // Return user-friendly message
        return self::translateException($exception);
    }
}
