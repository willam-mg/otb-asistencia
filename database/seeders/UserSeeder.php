<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'nombre' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
            'cargo_id' => 1,
        ]);
    }
}
