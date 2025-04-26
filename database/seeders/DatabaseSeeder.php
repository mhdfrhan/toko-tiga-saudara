<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Pemilik Toko',
            'username' => 'owner',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        User::create([
            'name' => 'Admin Toko',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Kasir Toko',
            'username' => 'kasir',
            'password' => Hash::make('password'),
            'role' => 'kasir',
        ]);

        $this->call([
            UnitSeeder::class,
            KategoriSeeder::class,
        ]);
    }
}
