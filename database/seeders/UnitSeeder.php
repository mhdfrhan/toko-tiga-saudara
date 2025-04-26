<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            [
                'nama' => 'Kilogram',
                'simbol' => 'kg',
            ],
            [
                'nama' => 'Gram',
                'simbol' => 'g',
            ],
            [
                'nama' => 'Liter',
                'simbol' => 'L',
            ],
            [
                'nama' => 'Mililiter',
                'simbol' => 'ml',
            ],
            [
                'nama' => 'Buah',
                'simbol' => 'pcs',
            ],
            [
                'nama' => 'Bungkus',
                'simbol' => 'bks',
            ],
            [
                'nama' => 'Lusin',
                'simbol' => 'lsn',
            ],
            [
                'nama' => 'Kotak',
                'simbol' => 'box',
            ],
            [
                'nama' => 'Sachet',
                'simbol' => 'sct',
            ],
            [
                'nama' => 'Botol',
                'simbol' => 'btl',
            ],
            [
                'nama' => 'Karton',
                'simbol' => 'krt',
            ],
            [
                'nama' => 'Renteng',
                'simbol' => 'rtg',
            ],
            [
                'nama' => 'Pack',
                'simbol' => 'pak',
            ],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}