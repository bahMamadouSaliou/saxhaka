<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\AppSetting;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $setting = new AppSetting();
        $setting->companyName = 'Alpha MsEasyBiz';
        $setting->dashboardType = 'inventory';
        $setting->tagLine = 'Gros t&#39;as ta chance, alors saisie-la maintenant!';
        $setting->address = 'Lavage, à côté de l&#39;imeuble Turquoise';
        $setting->phone = '+224 611 88 98 20';
        $setting->email = 'contact@mseasybiz.com';
        $setting->website = 'https://mseasybiz.com';
        $setting->footer = 'MsEasyBiz copyright by SaliouXhaka';
        $setting->logo = 'os-inventory-logo.png';
        $setting->currencyId = 1;

        $setting->save();
    }
}