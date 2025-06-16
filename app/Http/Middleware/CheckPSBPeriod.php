<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PSB\Periode;
use Carbon\Carbon;

class CheckPSBPeriod
{
    public function handle(Request $request, Closure $next)
    {
        $currentDate = Carbon::now();
        
        // Cek periode pendaftaran aktif
        $activePeriod = Periode::where('status_periode', 'active')
            ->where('tipe_periode', 'pendaftaran_baru')
            ->where('periode_mulai', '<=', $currentDate)
            ->where('periode_selesai', '>=', $currentDate)
            ->first();

        if (!$activePeriod) {
            return response()->json([
                'status' => 'error',
                'message' => 'Periode pendaftaran tidak aktif'
            ], 403);
        }

        return $next($request);
    }
} 