<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'company_name', 'value' => 'CineStudio Indonesia'],
            ['key' => 'timezone', 'value' => 'WIB (UTC+7)'],
            ['key' => 'currency', 'value' => 'IDR - Rupiah'],
            ['key' => 'date_format', 'value' => 'DD/MM/YYYY'],
            ['key' => 'maintenance_mode', 'value' => 'false'],
            ['key' => 'theme_dark_mode', 'value' => 'true'],
            ['key' => 'theme_accent_color', 'value' => '#f97316'],
            ['key' => 'sidebar_collapsed', 'value' => 'false'],
            ['key' => 'app_name', 'value' => 'CinePanel'],
            ['key' => 'app_version', 'value' => '1.0.0'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->insert([
                'key' => $setting['key'],
                'value' => $setting['value'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
