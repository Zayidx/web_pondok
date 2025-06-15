<?php

namespace App\Services\PSB;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CacheService
{
    private $defaultTtl = 3600; // 1 jam
    private $monitoringEnabled = true;

    public function remember($key, $callback, $ttl = null)
    {
        try {
            $ttl = $ttl ?? $this->defaultTtl;
            
            return Cache::remember($key, $ttl, function () use ($callback, $key) {
                $result = $callback();
                $this->logCacheOperation('hit', $key);
                return $result;
            });
        } catch (\Exception $e) {
            $this->logCacheOperation('error', $key, $e->getMessage());
            return $callback();
        }
    }

    public function forget($key)
    {
        try {
            Cache::forget($key);
            $this->logCacheOperation('forget', $key);
            return true;
        } catch (\Exception $e) {
            $this->logCacheOperation('error', $key, $e->getMessage());
            return false;
        }
    }

    public function flush()
    {
        try {
            Cache::flush();
            $this->logCacheOperation('flush', 'all');
            return true;
        } catch (\Exception $e) {
            $this->logCacheOperation('error', 'all', $e->getMessage());
            return false;
        }
    }

    public function warmCache($keys)
    {
        try {
            foreach ($keys as $key => $callback) {
                $this->remember($key, $callback);
            }
            $this->logCacheOperation('warm', 'multiple');
            return true;
        } catch (\Exception $e) {
            $this->logCacheOperation('error', 'warm', $e->getMessage());
            return false;
        }
    }

    public function getCacheStats()
    {
        if (!$this->monitoringEnabled) {
            return null;
        }

        try {
            $stats = Cache::get('cache_stats', [
                'hits' => 0,
                'misses' => 0,
                'errors' => 0,
                'last_cleanup' => null
            ]);

            return $stats;
        } catch (\Exception $e) {
            Log::error('Error getting cache stats: ' . $e->getMessage());
            return null;
        }
    }

    public function cleanup()
    {
        try {
            // Hapus cache yang expired
            $this->removeExpiredCache();

            // Update stats
            $this->updateCacheStats('cleanup');

            // Log cleanup
            $this->logCacheOperation('cleanup', 'all');

            return true;
        } catch (\Exception $e) {
            $this->logCacheOperation('error', 'cleanup', $e->getMessage());
            return false;
        }
    }

    private function removeExpiredCache()
    {
        // Implementasi sesuai dengan driver cache yang digunakan
        // Contoh untuk Redis:
        // Redis::command('SCAN', [0, 'MATCH', '*', 'COUNT', 100]);
    }

    private function updateCacheStats($operation)
    {
        if (!$this->monitoringEnabled) {
            return;
        }

        $stats = Cache::get('cache_stats', [
            'hits' => 0,
            'misses' => 0,
            'errors' => 0,
            'last_cleanup' => null
        ]);

        switch ($operation) {
            case 'hit':
                $stats['hits']++;
                break;
            case 'miss':
                $stats['misses']++;
                break;
            case 'error':
                $stats['errors']++;
                break;
            case 'cleanup':
                $stats['last_cleanup'] = Carbon::now();
                break;
        }

        Cache::put('cache_stats', $stats, Carbon::now()->addDay());
    }

    private function logCacheOperation($operation, $key, $error = null)
    {
        if (!$this->monitoringEnabled) {
            return;
        }

        Log::channel('cache')->info('Cache operation', [
            'operation' => $operation,
            'key' => $key,
            'error' => $error,
            'timestamp' => Carbon::now()
        ]);

        $this->updateCacheStats($operation);
    }

    public function setMonitoring($enabled)
    {
        $this->monitoringEnabled = $enabled;
    }

    public function getCacheSize()
    {
        // Implementasi sesuai dengan driver cache yang digunakan
        // Contoh untuk Redis:
        // return Redis::command('DBSIZE');
        return 0;
    }

    public function getCacheKeys($pattern = '*')
    {
        // Implementasi sesuai dengan driver cache yang digunakan
        // Contoh untuk Redis:
        // return Redis::command('KEYS', [$pattern]);
        return [];
    }
} 