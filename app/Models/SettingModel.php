<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table            = 'settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'key',
        'value',
        'type',
        'description',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get a setting value by key
     */
    public function get(string $key, $default = null)
    {
        $setting = $this->where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return $this->castValue($setting['value'], $setting['type']);
    }

    /**
     * Set a setting value
     */
    public function setValue(string $key, $value, ?string $type = null): bool
    {
        $existing = $this->where('key', $key)->first();

        if ($existing) {
            return $this->update($existing['id'], [
                'value' => $this->prepareValue($value, $type ?? $existing['type']),
            ]);
        }

        return $this->insert([
            'key'   => $key,
            'value' => $this->prepareValue($value, $type ?? 'string'),
            'type'  => $type ?? 'string',
        ]);
    }

    /**
     * Get all settings as key-value array
     */
    public function getAll(): array
    {
        $settings = $this->findAll();
        $result = [];

        foreach ($settings as $setting) {
            $result[$setting['key']] = $this->castValue($setting['value'], $setting['type']);
        }

        return $result;
    }

    /**
     * Get settings grouped by prefix
     */
    public function getByPrefix(string $prefix): array
    {
        $settings = $this->like('key', $prefix, 'after')->findAll();
        $result = [];

        foreach ($settings as $setting) {
            $key = str_replace($prefix . '_', '', $setting['key']);
            $result[$key] = $this->castValue($setting['value'], $setting['type']);
        }

        return $result;
    }

    /**
     * Cast value based on type
     */
    protected function castValue($value, string $type)
    {
        switch ($type) {
            case 'number':
                return is_numeric($value) ? (strpos($value, '.') !== false ? (float)$value : (int)$value) : 0;
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    /**
     * Prepare value for storage
     */
    protected function prepareValue($value, string $type): string
    {
        switch ($type) {
            case 'json':
                return json_encode($value, JSON_UNESCAPED_UNICODE);
            case 'boolean':
                return $value ? '1' : '0';
            default:
                return (string) $value;
        }
    }

    /**
     * Get VAT settings
     */
    public function getVatSettings(): array
    {
        return [
            'type' => $this->get('vat_type', 'inclusive'),
            'rate' => (float) $this->get('vat_rate', 7),
        ];
    }

    /**
     * Get company settings
     */
    public function getCompanySettings(): array
    {
        return [
            'name'    => $this->get('company_name', 'ASIC Repair Center'),
            'address' => $this->get('company_address', ''),
            'phone'   => $this->get('company_phone', ''),
            'email'   => $this->get('company_email', ''),
            'tax_id'  => $this->get('company_tax_id', ''),
        ];
    }
}

