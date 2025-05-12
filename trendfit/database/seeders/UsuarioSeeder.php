<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@tienda.com',
                'password' => Hash::make('admin123'),
                'isAdmin' => true
            ],
            [
                'name' => 'Cliente1',
                'email' => 'cliente1@email.com',
                'password' => Hash::make('cliente1pass'),
                'isAdmin' => false
            ],
            [
                'name' => 'Cliente2',
                'email' => 'cliente2@email.com',
                'password' => Hash::make('cliente2pass'),
                'isAdmin' => false
            ]
        ]);
    }
}