<?php

namespace Database\Seeders;

use App\Models\Kolam;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class KolamSeeder extends Seeder
{
    /**
     * Seed sample kolam data for testing.
     */
    public function run(): void
    {
        // Ambil user pertama, atau buat jika belum ada
        $user = User::first();

        if (!$user) {
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@smpl.test',
                'password' => bcrypt('password'),
            ]);
        }

        $kolams = [
            [
                'user_id' => $user->id,
                'nama_kolam' => 'Kolam A1',
                'volume_kolam' => 5.00,
                'tgl_tebar' => Carbon::now()->subDays(60),
                'metode_budidaya' => 'bioflok',
            ],
            [
                'user_id' => $user->id,
                'nama_kolam' => 'Kolam A2',
                'volume_kolam' => 8.00,
                'tgl_tebar' => Carbon::now()->subDays(90),
                'metode_budidaya' => 'bioflok',
            ],
            [
                'user_id' => $user->id,
                'nama_kolam' => 'Kolam B1',
                'volume_kolam' => 10.00,
                'tgl_tebar' => Carbon::now()->subDays(110),
                'metode_budidaya' => 'konvensional',
            ],
        ];

        foreach ($kolams as $kolam) {
            Kolam::updateOrCreate(
                ['user_id' => $kolam['user_id'], 'nama_kolam' => $kolam['nama_kolam']],
                $kolam
            );
        }
    }
}
