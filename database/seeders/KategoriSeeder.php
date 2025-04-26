<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = [
            'Makanan',
            'Minuman',
            'Kebutuhan Rumah Tangga',
            'Peralatan Dapur',
            'Bumbu Dapur',
            'Sembako',
            'Perawatan Tubuh',
        ];

        foreach ($kategori as $nama) {
            DB::table('kategori')->insert([
                'nama' => $nama,
                'slug' => Str::slug($nama),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
