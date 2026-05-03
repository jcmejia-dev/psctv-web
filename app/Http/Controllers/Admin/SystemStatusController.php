<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Log;

class SystemStatusController extends Controller
{
    /**
     * Get system status for all components
     */
    public function status()
    {
        $status = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'queue' => $this->checkQueue(),
            'timestamp' => now()->toISOString(),
        ];

        return response()->json($status);
    }

    /**
     * Check database connectivity
     */
    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();
            $result = DB::select('SELECT 1');
            return [
                'status' => 'healthy',
                'message' => 'Database connection is healthy',
                'response_time' => microtime(true) - LARAVEL_START,
            ];
        } catch (\Exception $e) {
            Log::error('Database health check failed: ' . $e->getMessage());
            return [
                'status' => 'unhealthy',
                'message' => 'Database connection failed',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check cache connectivity
     */
    private function checkCache(): array
    {
        try {
            $start = microtime(true);
            Cache::store()->put('health_check', 'ok', 10);
            $value = Cache::store()->get('health_check');
            $responseTime = microtime(true) - $start;

            if ($value === 'ok') {
                return [
                    'status' => 'healthy',
                    'message' => 'Cache is healthy',
                    'response_time' => $responseTime,
                ];
            } else {
                return [
                    'status' => 'unhealthy',
                    'message' => 'Cache read/write failed',
                ];
            }
        } catch (\Exception $e) {
            Log::error('Cache health check failed: ' . $e->getMessage());
            return [
                'status' => 'unhealthy',
                'message' => 'Cache connection failed',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check queue connectivity
     */
    private function checkQueue(): array
    {
        try {
            // For database queue, check if we can push a test job
            $start = microtime(true);
            Queue::push(function () {
                // Test job - do nothing
            });
            $responseTime = microtime(true) - $start;

            return [
                'status' => 'healthy',
                'message' => 'Queue is healthy',
                'response_time' => $responseTime,
            ];
        } catch (\Exception $e) {
            Log::error('Queue health check failed: ' . $e->getMessage());
            return [
                'status' => 'unhealthy',
                'message' => 'Queue connection failed',
                'error' => $e->getMessage(),
            ];
        }
    }
}
