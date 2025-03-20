<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $account = new Account();
        $account->name = 'Revenue';
        $account->type = 'Equity';
        $account->save();

        $account = new Account();
        $account->name = 'Dépense';
        $account->type = 'Equity';
        $account->save();
    }
}