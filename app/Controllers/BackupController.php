<?php

namespace App\Controllers;

/**
 * Backup Controller
 * 
 * Handles database backup and restore operations.
 * Admin only access.
 */
class BackupController extends BaseController
{
    protected string $backupPath;

    public function __construct()
    {
        $this->backupPath = WRITEPATH . 'backups/';
        
        // Create backup directory if not exists
        if (!is_dir($this->backupPath)) {
            mkdir($this->backupPath, 0755, true);
        }
    }

    /**
     * Backup management page
     */
    public function index(): string
    {
        $backups = $this->getBackupList();
        
        return view('settings/backup', $this->getViewData([
            'title'   => 'Backup & Restore',
            'backups' => $backups,
        ]));
    }

    /**
     * Create new backup
     */
    public function create()
    {
        try {
            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            $filepath = $this->backupPath . $filename;
            
            $db = \Config\Database::connect();
            $tables = $db->listTables();
            
            $sql = "-- ASIC Repair System Database Backup\n";
            $sql .= "-- Created: " . date('Y-m-d H:i:s') . "\n";
            $sql .= "-- Version: 1.1.0\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
            
            foreach ($tables as $table) {
                // Skip CI4 migrations table
                if ($table === 'migrations') continue;
                
                $sql .= "-- Table: {$table}\n";
                $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";
                
                // Get create table statement
                $query = $db->query("SHOW CREATE TABLE `{$table}`");
                if ($query) {
                    $row = $query->getRowArray();
                    $sql .= $row['Create Table'] . ";\n\n";
                }
                
                // Get table data
                $data = $db->table($table)->get()->getResultArray();
                
                if (!empty($data)) {
                    $columns = array_keys($data[0]);
                    $columnList = '`' . implode('`, `', $columns) . '`';
                    
                    foreach ($data as $row) {
                        $values = array_map(function($val) use ($db) {
                            return $val === null ? 'NULL' : $db->escape($val);
                        }, $row);
                        
                        $sql .= "INSERT INTO `{$table}` ({$columnList}) VALUES (" . implode(', ', $values) . ");\n";
                    }
                    $sql .= "\n";
                }
            }
            
            $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
            
            // Write backup file
            file_put_contents($filepath, $sql);
            
            // Also create a compressed version
            $gzFilepath = $filepath . '.gz';
            $gz = gzopen($gzFilepath, 'w9');
            gzwrite($gz, $sql);
            gzclose($gz);
            
            return redirect()->to('/settings/backup')
                ->with('success', 'Backup created successfully: ' . $filename);
                
        } catch (\Exception $e) {
            return redirect()->to('/settings/backup')
                ->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    /**
     * Download backup file
     */
    public function download(string $filename)
    {
        $filepath = $this->backupPath . $filename;
        
        if (!file_exists($filepath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        return $this->response->download($filepath, null);
    }

    /**
     * Restore from backup
     */
    public function restore()
    {
        $filename = $this->request->getPost('filename');
        
        if (!$filename) {
            // Check for uploaded file
            $file = $this->request->getFile('backup_file');
            
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $filename = $file->getRandomName();
                $file->move($this->backupPath, $filename);
            } else {
                return redirect()->to('/settings/backup')
                    ->with('error', 'No backup file selected');
            }
        }
        
        $filepath = $this->backupPath . $filename;
        
        if (!file_exists($filepath)) {
            return redirect()->to('/settings/backup')
                ->with('error', 'Backup file not found');
        }
        
        try {
            // Read SQL content (handle .gz files)
            if (str_ends_with($filename, '.gz')) {
                $sql = gzfile($filepath);
                $sql = implode('', $sql);
            } else {
                $sql = file_get_contents($filepath);
            }
            
            if (empty($sql)) {
                throw new \Exception('Backup file is empty');
            }
            
            $db = \Config\Database::connect();
            
            // Split into individual statements
            $statements = array_filter(
                array_map('trim', explode(";\n", $sql)),
                fn($s) => !empty($s) && !str_starts_with($s, '--')
            );
            
            $db->transStart();
            
            foreach ($statements as $statement) {
                if (!empty(trim($statement))) {
                    $db->query($statement);
                }
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }
            
            return redirect()->to('/settings/backup')
                ->with('success', 'Database restored successfully');
                
        } catch (\Exception $e) {
            return redirect()->to('/settings/backup')
                ->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }

    /**
     * Delete backup file
     */
    public function delete(string $filename)
    {
        $filepath = $this->backupPath . $filename;
        
        if (file_exists($filepath)) {
            unlink($filepath);
            
            // Also delete .gz version if exists
            if (file_exists($filepath . '.gz')) {
                unlink($filepath . '.gz');
            }
        }
        
        return redirect()->to('/settings/backup')
            ->with('success', 'Backup deleted');
    }

    /**
     * Get list of backup files
     */
    protected function getBackupList(): array
    {
        $backups = [];
        
        if (is_dir($this->backupPath)) {
            $files = scandir($this->backupPath, SCANDIR_SORT_DESCENDING);
            
            foreach ($files as $file) {
                if (str_starts_with($file, 'backup_') && (str_ends_with($file, '.sql') || str_ends_with($file, '.sql.gz'))) {
                    $filepath = $this->backupPath . $file;
                    $backups[] = [
                        'filename'   => $file,
                        'size'       => filesize($filepath),
                        'created_at' => date('Y-m-d H:i:s', filemtime($filepath)),
                        'compressed' => str_ends_with($file, '.gz'),
                    ];
                }
            }
        }
        
        return $backups;
    }
}

