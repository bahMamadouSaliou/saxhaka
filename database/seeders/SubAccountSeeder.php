<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\SubAccount;

class SubAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subAccount = new SubAccount();
        $subAccount->name = 'Cash';
        $subAccount->accountId = 1;
        $subAccount->save();

        $subAccount = new SubAccount();
        $subAccount->name = 'Stock';
        $subAccount->accountId = 2;
        $subAccount->save();

        $subAccount = new SubAccount();
        $subAccount->name = 'Dépenses';
        $subAccount->accountId = 3;
        $subAccount->save();
    }
}