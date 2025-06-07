<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\WawancaraSchedule;
use Carbon\Carbon;

class WawancaraScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // Get all santri with status menunggu_wawancara
        $santriList = PendaftaranSantri::where('status_santri', 'menunggu_wawancara')->get();
        
        // Set base date for wawancara (tomorrow)
        $baseDate = Carbon::tomorrow();
        
        // Time slots for wawancara (30 minutes each)
        $timeSlots = [
            '08:00', '08:30', '09:00', '09:30', '10:00', '10:30',
            '11:00', '11:30', '13:00', '13:30', '14:00', '14:30',
            '15:00', '15:30', '16:00', '16:30'
        ];
        
        $currentSlot = 0;
        $currentDay = 0;
        
        foreach ($santriList as $santri) {
            // Create wawancara schedule
            WawancaraSchedule::create([
                'santri_id' => $santri->id,
                'tanggal' => $baseDate->copy()->addDays($currentDay),
                'waktu' => $timeSlots[$currentSlot],
                'status' => 'scheduled',
                'ruangan' => 'Ruang Wawancara ' . (($currentSlot % 3) + 1),
                'pewawancara' => 'Ustadz/Ustadzah ' . (($currentSlot % 5) + 1)
            ]);
            
            // Update santri status
            $santri->update([
                'status_santri' => 'terjadwal_wawancara'
            ]);
            
            // Move to next slot
            $currentSlot++;
            if ($currentSlot >= count($timeSlots)) {
                $currentSlot = 0;
                $currentDay++;
            }
        }
    }
} 
 
 
 
 
 