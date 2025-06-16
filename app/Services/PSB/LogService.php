<?php

namespace App\Services\PSB;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use ZipArchive;

class LogService
{
    private $logChannels = [
        'auth' => 'auth.log',
        'cache' => 'cache.log',
        'payment' => 'payment.log',
        'notification' => 'notification.log',
        'error' => 'error.log',
        'activity' => 'activity.log'
    ];

    private $logLevels = [
        'emergency' => 0,
        'alert' => 1,
        'critical' => 2,
        'error' => 3,
        'warning' => 4,
        'notice' => 5,
        'info' => 6,
        'debug' => 7
    ];

    public function log($channel, $message, $level = 'info', $context = [])
    {
        try {
            if (!isset($this->logChannels[$channel])) {
                throw new \Exception('Invalid log channel');
            }

            if (!isset($this->logLevels[$level])) {
                throw new \Exception('Invalid log level');
            }

            // Tambahkan timestamp
            $context['timestamp'] = Carbon::now();

            // Log ke file
            Log::channel($channel)->$level($message, $context);

            // Simpan ke database jika diperlukan
            $this->saveToDatabase($channel, $message, $level, $context);

            return true;
        } catch (\Exception $e) {
            Log::error('Error logging: ' . $e->getMessage());
            return false;
        }
    }

    private function saveToDatabase($channel, $message, $level, $context)
    {
        // Implementasi penyimpanan log ke database
        // Contoh:
        // LogEntry::create([
        //     'channel' => $channel,
        //     'message' => $message,
        //     'level' => $level,
        //     'context' => json_encode($context),
        //     'created_at' => Carbon::now()
        // ]);
    }

    public function rotateLogs()
    {
        try {
            foreach ($this->logChannels as $channel => $filename) {
                $this->rotateLogFile($channel, $filename);
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Error rotating logs: ' . $e->getMessage());
            return false;
        }
    }

    private function rotateLogFile($channel, $filename)
    {
        $logPath = storage_path('logs/' . $filename);
        
        if (!file_exists($logPath)) {
            return;
        }

        // Buat backup
        $backupPath = storage_path('logs/backup/' . $filename . '.' . Carbon::now()->format('Y-m-d-H-i-s'));
        
        // Pindahkan file log ke backup
        rename($logPath, $backupPath);

        // Buat file log baru
        touch($logPath);
        chmod($logPath, 0664);

        // Kompres backup
        $this->compressLogFile($backupPath);
    }

    private function compressLogFile($filePath)
    {
        $zip = new ZipArchive();
        $zipPath = $filePath . '.zip';

        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            $zip->addFile($filePath, basename($filePath));
            $zip->close();

            // Hapus file asli
            unlink($filePath);
        }
    }

    public function cleanupOldLogs($days = 30)
    {
        try {
            $backupPath = storage_path('logs/backup');
            $files = glob($backupPath . '/*');

            foreach ($files as $file) {
                if (is_file($file)) {
                    $fileTime = filemtime($file);
                    if (Carbon::createFromTimestamp($fileTime)->addDays($days)->isPast()) {
                        unlink($file);
                    }
                }
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Error cleaning up old logs: ' . $e->getMessage());
            return false;
        }
    }

    public function getLogStats()
    {
        $stats = [];
        
        foreach ($this->logChannels as $channel => $filename) {
            $stats[$channel] = [
                'size' => $this->getLogFileSize($filename),
                'last_modified' => $this->getLogFileLastModified($filename),
                'entries' => $this->countLogEntries($channel)
            ];
        }

        return $stats;
    }

    private function getLogFileSize($filename)
    {
        $path = storage_path('logs/' . $filename);
        return file_exists($path) ? filesize($path) : 0;
    }

    private function getLogFileLastModified($filename)
    {
        $path = storage_path('logs/' . $filename);
        return file_exists($path) ? Carbon::createFromTimestamp(filemtime($path)) : null;
    }

    private function countLogEntries($channel)
    {
        // Implementasi penghitungan entri log
        // Bisa dari file atau database
        return 0;
    }

    public function searchLogs($query, $channel = null, $level = null, $startDate = null, $endDate = null)
    {
        try {
            $results = [];

            if ($channel) {
                $channels = [$channel];
            } else {
                $channels = array_keys($this->logChannels);
            }

            foreach ($channels as $ch) {
                $filename = $this->logChannels[$ch];
                $path = storage_path('logs/' . $filename);

                if (!file_exists($path)) {
                    continue;
                }

                $lines = file($path);
                foreach ($lines as $line) {
                    if ($this->matchLogEntry($line, $query, $level, $startDate, $endDate)) {
                        $results[] = $this->parseLogEntry($line);
                    }
                }
            }

            return $results;
        } catch (\Exception $e) {
            Log::error('Error searching logs: ' . $e->getMessage());
            return [];
        }
    }

    private function matchLogEntry($line, $query, $level, $startDate, $endDate)
    {
        // Implementasi pencocokan log entry
        return true;
    }

    private function parseLogEntry($line)
    {
        // Implementasi parsing log entry
        return [];
    }
} 