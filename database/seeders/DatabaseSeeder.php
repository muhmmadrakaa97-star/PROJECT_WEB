<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pengaduan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@unsri.ac.id'],
            [
                'name' => 'Admin UNSRI',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'nim_nip' => '198501012010121001',
                'fakultas' => 'BAK / ICT Center',
            ]
        );

        $mahasiswa = User::updateOrCreate(
            ['email' => 'mahasiswa@unsri.ac.id'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'nim_nip' => '09021282126001',
                'fakultas' => 'Fakultas Ilmu Komputer',
            ]
        );

        Pengaduan::firstOrCreate(
            ['nomor_tiket' => 'UNSRI-EX001'],
            [
                'user_id' => $mahasiswa->id,
                'judul' => 'AC Ruang Kuliah 3.1 Rusak',
                'deskripsi' => 'Pendingin ruangan (AC) di lantai 3 ruang 3.1 Gedung Perkuliahan Fasilkom tidak berfungsi sejak kemarin dan mengeluarkan bunyi bising.',
                'kategori' => 'Sarana dan Prasarana',
                'lokasi' => 'Gedung Kuliah Fasilkom Lantai 3',
                'urgensi' => 'Sedang',
                'is_anonim' => false,
                'status' => 'Menunggu Verifikasi',
            ]
        );

        Pengaduan::firstOrCreate(
            ['nomor_tiket' => 'UNSRI-EX002'],
            [
                'user_id' => $mahasiswa->id,
                'judul' => 'Koneksi WiFi Unsri Hotspot Sering Terputus',
                'deskripsi' => 'Koneksi WiFi di perpustakaan pusat sering mengalami time out dan tidak bisa login akun SSO.',
                'kategori' => 'Sarana dan Prasarana',
                'lokasi' => 'Perpustakaan Pusat Indralaya',
                'urgensi' => 'Tinggi',
                'is_anonim' => true,
                'status' => 'Sedang Diproses',
                'unit_terkait' => 'ICT Center UNSRI',
            ]
        );
    }
}
