<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    public function run()
    {
        DB::table('categoria')->insert([
            ['name' => 'Hombre', 'descr' => 'Ropa para hombres'],
            ['name' => 'Mujer', 'descr' => 'Ropa para mujeres'],
            ['name' => 'Niños', 'descr' => 'Ropa para niños y bebés']
        ]);
    }
}