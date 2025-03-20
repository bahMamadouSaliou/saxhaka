<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class customerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = new Customer();
        $customer->roleId = 3;
        $customer->username = 'Initialisation';
        $customer->email = 'init@mseasybiz.com';
        $customer->password = Hash::make('1111');
        $customer->phone = '625625625';
        $customer->address = 'Conakry';
        $customer->save();
    }
}