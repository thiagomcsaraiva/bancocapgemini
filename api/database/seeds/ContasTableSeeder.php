<?php

use Illuminate\Database\Seeder;
use App\Conta;

class ContasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Conta::create([
            'nome_titular' => 'Thiago',
        ]);

        Conta::create([
            'nome_titular' => 'Saraiva',
        ]);
    }
}
