<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComandaProdSeeder extends Seeder
{
    public function run()
    {
        DB::table('comanda_prod')->insert([
            ['idComanda' => 1, 'idProducte' => 1, 'cant' => 2, 'has_to_comment' => false, 'size' => 'M'],
            ['idComanda' => 1, 'idProducte' => 3, 'cant' => 1, 'has_to_comment' => true, 'size' => 'L'],
            ['idComanda' => 2, 'idProducte' => 2, 'cant' => 1, 'has_to_comment' => false, 'size' => 'S'],
            ['idComanda' => 2, 'idProducte' => 4, 'cant' => 1, 'has_to_comment' => true, 'size' => 'XL']
        ]);
    }
}