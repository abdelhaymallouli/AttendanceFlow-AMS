<?php

namespace Database\Seeders;

use App\Models\CampusLocation;
use Illuminate\Database\Seeder;

class CampusLocationSeeder extends Seeder
{
    public function run(): void
    {
        $campuses = [
            [
                'name' => 'Solicode Casablanca',
                'code' => 'CASA-MAIN',
                'latitude' => 33.5731,
                'longitude' => -7.5898,
                'radius_meters' => 50,
                'allowed_subnets' => ['10.0.0.0/24', '192.168.1.0/24'],
                'is_active' => true,
            ],
        ];

        foreach ($campuses as $data) {
            CampusLocation::updateOrCreate(
                ['code' => $data['code']],
                $data,
            );
        }
    }
}
