<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PasswordSantriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $santris = DB::table('santris')->get();

        foreach ($santris as $santri) {
            DB::table('santris')
                ->where('id', $santri->id)
                ->update([
                    'password' => Hash::make($santri->nisn)
                ]);
        }
    }
}
