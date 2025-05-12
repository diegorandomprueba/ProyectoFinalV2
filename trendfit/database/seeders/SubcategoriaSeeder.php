<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubcategoriaSeeder extends Seeder
{
    public function run()
    {
        DB::table('subcategoria')->insert([
            ['name' => 'Camisetas', 'descr' => 'Camisetas casuales y deportivas', 'idCategoria' => 1],
            ['name' => 'Pantalones', 'descr' => 'Pantalones y jeans', 'idCategoria' => 1],
            ['name' => 'Vestidos', 'descr' => 'Vestidos de temporada', 'idCategoria' => 2],
            ['name' => 'Faldas', 'descr' => 'Faldas elegantes', 'idCategoria' => 2],
            ['name' => 'Jerseys', 'descr' => 'Jerseys infantiles', 'idCategoria' => 3]
        ]);
    }
}