<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Admin Master (Root)
        User::factory()->create([
            'name' => 'Admin Diskominfo',
            'email' => 'admin@gmail.com', // Gunakan email resmi untuk testing
            'password' => Hash::make('12345678'), // Password yang kuat untuk admin
            'role' => 'admin'
        ]);

        User::factory()->create([
            'name' => 'Staff Jaringan',
            'email' => 'user@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'staff'
        ]);

        $tahunSaatIni = date('Y'); 

        $siteDevice = Category::create(['name' => 'Site/Device']);
        Category::create(['name' => 'Core', 'parent_id' => $siteDevice->id]);
        Category::create(['name' => 'Metro Reguler', 'parent_id' => $siteDevice->id]);
        Category::create(['name' => 'Metro Lokal', 'parent_id' => $siteDevice->id]);
        Category::create(['name' => 'Free Wifi', 'parent_id' => $siteDevice->id]);
        Category::create(['name' => 'Internet Masyarakat', 'parent_id' => $siteDevice->id]);

        // --- TOPOLOGI ---
        $topologi = Category::create(['name' => 'Topologi']);
        Category::create(['name' => 'Core', 'parent_id' => $topologi->id]);
        Category::create(['name' => 'Metro', 'parent_id' => $topologi->id]);
        Category::create(['name' => 'Fiber Optik', 'parent_id' => $topologi->id]);
        Category::create(['name' => 'OPD', 'parent_id' => $topologi->id]);

        // --- KONTRAK ---
        $kontrak = Category::create(['name' => 'Kontrak']);
        Category::create(['name' => 'Metro Reguler', 'parent_id' => $kontrak->id]);
        Category::create(['name' => 'Free Wifi', 'parent_id' => $kontrak->id]);
        Category::create(['name' => 'Internet Masyarakat', 'parent_id' => $kontrak->id]);

        // --- LAPORAN ---
        $laporan = Category::create(['name' => 'Laporan']);
        Category::create(['name' => 'Metro Reguler', 'parent_id' => $laporan->id]);
        Category::create(['name' => 'Metro Lokal', 'parent_id' => $laporan->id]);
        Category::create(['name' => 'Free Wifi', 'parent_id' => $laporan->id]);
        Category::create(['name' => 'Internet Masyarakat', 'parent_id' => $laporan->id]);

        // --- BERITA ACARA ---
        $ba = Category::create(['name' => 'Berita Acara']);
        Category::create(['name' => 'BA Rapat', 'parent_id' => $ba->id]);
        Category::create(['name' => 'BA Instalasi', 'parent_id' => $ba->id]);
        Category::create(['name' => 'BA Migrasi', 'parent_id' => $ba->id]);
        Category::create(['name' => 'BA Dismantle', 'parent_id' => $ba->id]);

        // --- SURAT & NOTULEN ---
        Category::create(['name' => 'Surat']);
        Category::create(['name' => 'Notulen']);

        $this->command->info('Struktur Kategori Diskominfo berhasil di-generate!');
    }
}