<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComandaSeeder extends Seeder
{
    public function run()
    {
        DB::table('comanda')->insert([
            [
                'idUsuari' => 2,
                'name' => 'Juan Pérez',
                'address' => 'Calle Mayor 123',
                'city' => 'Madrid',
                'provincia' => 'Madrid',
                'codigo_postal' => '28001',
                'date' => '2023-10-15'
            ],
            [
                'idUsuari' => 3,
                'name' => 'Ana García',
                'address' => 'Avenida Diagonal 456',
                'city' => 'Barcelona',
                'provincia' => 'Barcelona',
                'codigo_postal' => '08001',
                'date' => '2023-10-16'
            ]
        ]);
    }
}