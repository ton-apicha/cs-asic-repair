<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginHistoryModel extends Model
{
    protected $table            = 'login_history';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'user_id',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'platform',
        'login_at',
        'status',
        'failure_reason'
    ];

    protected $useTimestamps = false;

    /**
     * Record successful login
     */
    public function recordLogin(int $userId, string $ipAddress, string $userAgent): bool
    {
        $parsedAgent = $this->parseUserAgent($userAgent);

        return $this->insert([
            'user_id'     => $userId,
            'ip_address'  => $ipAddress,
            'user_agent'  => $userAgent,
            'device_type' => $parsedAgent['device'],
            'browser'     => $parsedAgent['browser'],
            'platform'    => $parsedAgent['platform'],
            'login_at'    => date('Y-m-d H:i:s'),
            'status'      => 'success'
        ]);
    }

    /**
     * Record failed login attempt
     */
    public function recordFailedLogin(int $userId, string $ipAddress, string $userAgent, string $reason): bool
    {
        $parsedAgent = $this->parseUserAgent($userAgent);

        return $this->insert([
            'user_id'        => $userId,
            'ip_address'     => $ipAddress,
            'user_agent'     => $userAgent,
            'device_type'    => $parsedAgent['device'],
            'browser'        => $parsedAgent['browser'],
            'platform'       => $parsedAgent['platform'],
            'login_at'       => date('Y-m-d H:i:s'),
            'status'         => 'failed',
            'failure_reason' => $reason
        ]);
    }

    /**
     * Get login history for a user
     */
    public function getUserHistory(int $userId, int $limit = 20): array
    {
        return $this->where('user_id', $userId)
            ->orderBy('login_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get recent successful logins
     */
    public function getRecentLogins(int $userId, int $limit = 5): array
    {
        return $this->where('user_id', $userId)
            ->where('status', 'success')
            ->orderBy('login_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Count failed login attempts in the last X minutes
     */
    public function countRecentFailedAttempts(int $userId, int $minutes = 30): int
    {
        $since = date('Y-m-d H:i:s', strtotime("-{$minutes} minutes"));

        return $this->where('user_id', $userId)
            ->where('status', 'failed')
            ->where('login_at >=', $since)
            ->countAllResults();
    }

    /**
     * Parse user agent string to extract device, browser, and platform
     */
    protected function parseUserAgent(string $userAgent): array
    {
        $result = [
            'device'   => 'Unknown',
            'browser'  => 'Unknown',
            'platform' => 'Unknown'
        ];

        // Detect device type
        if (preg_match('/Mobile|Android|iPhone|iPad|iPod/i', $userAgent)) {
            if (preg_match('/iPad/i', $userAgent)) {
                $result['device'] = 'Tablet';
            } else {
                $result['device'] = 'Mobile';
            }
        } else {
            $result['device'] = 'Desktop';
        }

        // Detect browser
        if (preg_match('/Firefox\/(\d+)/i', $userAgent, $matches)) {
            $result['browser'] = 'Firefox ' . $matches[1];
        } elseif (preg_match('/Edg\/(\d+)/i', $userAgent, $matches)) {
            $result['browser'] = 'Edge ' . $matches[1];
        } elseif (preg_match('/Chrome\/(\d+)/i', $userAgent, $matches)) {
            $result['browser'] = 'Chrome ' . $matches[1];
        } elseif (preg_match('/Safari\/(\d+)/i', $userAgent, $matches) && !preg_match('/Chrome/i', $userAgent)) {
            $result['browser'] = 'Safari';
        } elseif (preg_match('/MSIE (\d+)/i', $userAgent, $matches) || preg_match('/Trident.*rv:(\d+)/i', $userAgent, $matches)) {
            $result['browser'] = 'Internet Explorer ' . $matches[1];
        }

        // Detect platform/OS
        if (preg_match('/Windows NT 10/i', $userAgent)) {
            $result['platform'] = 'Windows 10/11';
        } elseif (preg_match('/Windows NT 6.3/i', $userAgent)) {
            $result['platform'] = 'Windows 8.1';
        } elseif (preg_match('/Windows NT 6.2/i', $userAgent)) {
            $result['platform'] = 'Windows 8';
        } elseif (preg_match('/Windows NT 6.1/i', $userAgent)) {
            $result['platform'] = 'Windows 7';
        } elseif (preg_match('/Mac OS X (\d+[._]\d+)/i', $userAgent, $matches)) {
            $result['platform'] = 'macOS ' . str_replace('_', '.', $matches[1]);
        } elseif (preg_match('/Android (\d+)/i', $userAgent, $matches)) {
            $result['platform'] = 'Android ' . $matches[1];
        } elseif (preg_match('/iOS (\d+)/i', $userAgent, $matches)) {
            $result['platform'] = 'iOS ' . $matches[1];
        } elseif (preg_match('/iPhone OS (\d+)/i', $userAgent, $matches)) {
            $result['platform'] = 'iOS ' . $matches[1];
        } elseif (preg_match('/Linux/i', $userAgent)) {
            $result['platform'] = 'Linux';
        }

        return $result;
    }

    /**
     * Clean old login history (keep last X days)
     */
    public function cleanOldHistory(int $daysToKeep = 90): int
    {
        $cutoff = date('Y-m-d H:i:s', strtotime("-{$daysToKeep} days"));

        return $this->where('login_at <', $cutoff)->delete();
    }
}
