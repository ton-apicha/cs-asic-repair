<?php

namespace App\Models;

use CodeIgniter\Model;

class SymptomHistoryModel extends Model
{
    protected $table            = 'symptom_history';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'symptom',
        'frequency',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Search symptoms by term, ordered by frequency
     */
    public function search(string $term, int $limit = 10): array
    {
        return $this->like('symptom', $term)
            ->orderBy('frequency', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get top symptoms by frequency
     */
    public function getTopSymptoms(int $limit = 20): array
    {
        return $this->orderBy('frequency', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Record a symptom (increment frequency if exists, create if not)
     */
    public function recordSymptom(string $symptom): void
    {
        $symptom = trim($symptom);
        if (empty($symptom)) {
            return;
        }

        // Check if symptom exists (case-insensitive)
        $existing = $this->where('LOWER(symptom)', strtolower($symptom))->first();

        if ($existing) {
            // Increment frequency
            $this->update($existing['id'], [
                'frequency' => $existing['frequency'] + 1,
            ]);
        } else {
            // Create new symptom
            $this->insert([
                'symptom'   => $symptom,
                'frequency' => 1,
            ]);
        }
    }
}

