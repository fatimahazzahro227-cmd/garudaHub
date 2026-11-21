<?php

namespace Database\Seeders;

use App\Models\Bid;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $pw = [
            'admin' => Hash::make('admin123'),
            'klien' => Hash::make('klien123'),
            'developer' => Hash::make('dev123'),
        ];

        $users = [
            [1, 'Admin Garuda', 'admin@example.com', 'admin'],
            [2, 'Budi Santoso', 'budi@example.com', 'klien'],
            [3, 'Siti Rahayu', 'siti@example.com', 'klien'],
            [4, 'Agus Wijaya', 'agus@example.com', 'klien'],
            [5, 'Dewi Lestari', 'dewi@example.com', 'developer'],
            [6, 'Eko Prasetyo', 'eko@example.com', 'developer'],
            [7, 'Fitri Handayani', 'fitri@example.com', 'developer'],
            [8, 'Gilang Ramadhan', 'gilang@example.com', 'developer'],
        ];
        foreach ($users as [$id, $nama, $email, $role]) {
            User::forceCreate(['id' => $id, 'nama' => $nama, 'email' => $email, 'password' => $pw[$role], 'role' => $role]);
        }

        $projects = [
            [1, 2, 'Sistem Booking Lapangan Futsal', 'Pelanggan bisa melihat jadwal kosong, memesan lapangan per jam, dan membayar DP secara online.', 'Web', 5000000, 10000000, '2027-03-20', 'terbuka'],
            [2, 3, 'Aplikasi Jadwal Pengajian', 'Aplikasi pengingat jadwal kajian masjid sekitar, lengkap dengan peta lokasi dan notifikasi.', 'Mobile', 4000000, 9000000, '2027-02-15', 'terbuka'],
            [3, 4, 'Desain Feed Instagram UMKM', 'Template 30 konten feed dan story untuk usaha kue rumahan, file Canva dan PNG.', 'Desain', 1000000, 2500000, '2026-10-10', 'ditutup'],
            [4, 2, 'Visualisasi Data Curah Hujan', 'Peta dan grafik curah hujan per kabupaten dari data BMKG lima tahun terakhir.', 'Data', 4500000, 9000000, '2027-04-05', 'terbuka'],
            [5, 3, 'Chatbot Admisi Kampus', 'Chatbot yang menjawab pertanyaan calon mahasiswa seputar jalur masuk, biaya, dan jadwal.', 'AI', 6000000, 12000000, '2027-01-20', 'terbuka'],
            [6, 4, 'Sistem Antrian Bengkel', 'Nomor antrean digital, estimasi waktu tunggu, dan riwayat servis kendaraan pelanggan.', 'Web', 3000000, 6500000, '2026-09-25', 'ditutup'],
            [7, 2, 'Aplikasi Kasir Kedai Kopi', 'Kasir tablet dengan menu bergambar, diskon member, dan laporan penjualan harian.', 'Mobile', 6500000, 13000000, '2026-08-15', 'ditutup'],
            [8, 3, 'Landing Page Kursus Bahasa Inggris', 'Satu halaman promosi kelas, testimoni, dan formulir pendaftaran ke Google Sheets.', 'Web', 1200000, 3000000, '2027-05-10', 'terbuka'],
        ];
        foreach ($projects as [$id, $client, $judul, $desk, $kat, $min, $max, $batas, $status]) {
            Project::forceCreate([
                'id' => $id, 'client_id' => $client, 'judul' => $judul, 'deskripsi' => $desk, 'kategori' => $kat,
                'budget_min' => $min, 'budget_max' => $max, 'batas_lelang' => $batas, 'status' => $status,
            ]);
        }

        $bids = [
            [1, 1, 6, 7500000, 30, 'Laravel dengan kalender jadwal interaktif dan konfirmasi via WhatsApp.', false],
            [2, 1, 8, 9000000, 25, 'Termasuk integrasi pembayaran QRIS untuk DP.', false],
            [3, 1, 5, 6500000, 35, 'Pernah membuat sistem booking studio musik, siap dikerjakan bertahap.', false],
            [4, 2, 7, 6000000, 30, 'Flutter dengan notifikasi pengingat 30 menit sebelum kajian.', false],
            [5, 2, 6, 8000000, 28, 'Termasuk panel takmir masjid untuk mengisi jadwal sendiri.', false],
            [6, 3, 5, 1800000, 10, 'Konsep warna pastel, revisi tiga kali, file Canva bisa diedit.', true],
            [7, 3, 8, 2200000, 12, 'Termasuk lima template Reels sederhana.', false],
            [8, 4, 7, 7000000, 30, 'Python dan Leaflet untuk peta interaktif per kabupaten.', false],
            [9, 4, 5, 5500000, 35, 'Dashboard dengan filter tahun dan ekspor grafik ke PNG.', false],
            [10, 5, 6, 9500000, 40, 'Chatbot berbasis dokumen panduan PMB, bisa dipasang di website kampus.', false],
            [11, 6, 8, 4500000, 18, 'Tampilan layar antrean untuk TV bengkel dan notifikasi SMS.', true],
            [12, 6, 7, 5500000, 21, 'Termasuk pengingat servis berkala ke pelanggan.', false],
            [13, 7, 6, 9000000, 35, 'Aplikasi Android tablet, bisa cetak struk Bluetooth.', true],
            [14, 7, 5, 11500000, 30, 'Termasuk sinkronisasi stok bahan baku.', false],
            [15, 8, 7, 2500000, 7, 'Desain responsif, formulir langsung tersimpan ke Google Sheets.', false],
        ];
        foreach ($bids as [$id, $project, $dev, $harga, $durasi, $proposal, $win]) {
            Bid::forceCreate([
                'id' => $id, 'project_id' => $project, 'developer_id' => $dev, 'harga' => $harga,
                'durasi' => $durasi, 'proposal' => $proposal, 'is_winner' => $win,
            ]);
        }

        if (DB::getDriverName() === 'pgsql') {
            foreach (['users', 'projects', 'bids'] as $table) {
                DB::statement("SELECT setval(pg_get_serial_sequence('$table', 'id'), (SELECT MAX(id) FROM $table))");
            }
        }
    }
}