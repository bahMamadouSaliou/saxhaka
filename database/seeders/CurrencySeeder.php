<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $c = new Currency();
        $c->currencyName= "Franc Guineen";
        $c->currencySymbol= "GNF";
        $c->save();
    }
}