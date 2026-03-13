<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alumni;

class AlumniSeeder extends Seeder
{
    public function run(): void
    {
        $dataDosen = [
            [
                'nama_lengkap' => 'Galih Wasis Wicaksono',
                'nim' => '10370311001',
                'prodi' => 'Informatika',
                'tahun_lulus' => 2009,
                'status_pelacakan' => 'Belum Dilacak'
            ],
            [
                'nama_lengkap' => 'Nadzar Secario',
                'nim' => '10370311002',
                'prodi' => 'Informatika',
                'tahun_lulus' => 2026,
                'status_pelacakan' => 'Belum Dilacak'
            ],
            [
                'nama_lengkap' => 'Gita Indah',
                'nim' => '10370311003',
                'prodi' => 'Informatika',
                'tahun_lulus' => 2007,
                'status_pelacakan' => 'Belum Dilacak'
            ],
            [
                'nama_lengkap' => 'Yufis Azhar',
                'nim' => '10370311004',
                'prodi' => 'Informatika',
                'tahun_lulus' => 2009,
                'status_pelacakan' => 'Belum Dilacak'
            ],
            [
                'nama_lengkap' => 'Galih Indrawan',
                'nim' => '10370311005',
                'prodi' => 'Informatika',
                'tahun_lulus' => 2020,
                'status_pelacakan' => 'Belum Dilacak'
            ]
        ];

        foreach ($dataDosen as $dosen) {
            Alumni::create($dosen);
        }
    }
}
