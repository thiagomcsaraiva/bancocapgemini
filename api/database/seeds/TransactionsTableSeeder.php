<?php

use Illuminate\Database\Seeder;
use App\Transaction;

class TransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Transaction::create([
            'valor' => rand(-100000, 300000),
            'conta_id' => rand(1,2),
        ]);
        Transaction::create([
            'valor' => rand(-100000, 300000),
            'conta_id' => rand(1,2),
        ]);
        Transaction::create([
            'valor' => rand(-100000, 300000),
            'conta_id' => rand(1,2),
        ]);
        Transaction::create([
            'valor' => rand(-100000, 300000),
            'conta_id' => rand(1,2),
        ]);
    }
}
