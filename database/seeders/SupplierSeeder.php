<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => "Initialisation",
                'phone' => "625625625",
                'address' => "Conakry",
            ],
        ];

        foreach ($suppliers as $item) {
            $supplier = new Supplier();
            $supplier->name = $item['name'];
            $supplier->phone = $item['phone'];
            $supplier->address = $item['address'];
            $supplier->save();
        }
    }
}