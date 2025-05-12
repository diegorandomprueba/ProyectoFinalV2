<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProducteSeeder extends Seeder
{
    public function run()
    {
        DB::table('producte')->insert([
            [
                'name' => 'Camiseta básica negra',
                'descr' => 'Camiseta de algodón 100%',
                'price' => 19.99,
                'stock' => 50,
                'image' => 'img/camiseta-negra.jpg',
                'idCategoria' => 1
            ],
            [
                'name' => 'Jeans slim fit',
                'descr' => 'Jeans ajustados color azul',
                'price' => 59.95,
                'stock' => 30,
                'image' => 'img/jeans-slim.jpg',
                'idCategoria' => 2
            ],
            [
                'name' => 'Vestido floral',
                'descr' => 'Vestido veraniego con estampado',
                'price' => 39.99,
                'stock' => 25,
                'image' => 'img/vestido-floral.jpg',
                'idCategoria' => 3
            ],
            [
                'name' => 'Falda plisada',
                'descr' => 'Falda midi color beige',
                'price' => 45.50,
                'stock' => 20,
                'image' => 'img/falda-plisada.jpg',
                'idCategoria' => 4
            ],
            [
                'name' => 'Jersey navideño',
                'descr' => 'Jersey infantil con motivo de renos',
                'price' => 29.99,
                'stock' => 15,
                'image' => 'img/jersey-reno.jpg',
                'idCategoria' => 5
            ]
        ]);
    }
}