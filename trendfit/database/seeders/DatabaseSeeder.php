<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CategoriaSeeder::class,
            SubcategoriaSeeder::class,
            UsuarioSeeder::class,
            ProducteSeeder::class,
            ComandaSeeder::class,
            ComandaProdSeeder::class,
            OpinionSeeder::class,
        ]);
    }
}