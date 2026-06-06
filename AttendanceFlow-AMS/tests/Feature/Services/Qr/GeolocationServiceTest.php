<?php

namespace Tests\Feature\Services\Qr;

use App\Services\Qr\GeolocationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GeolocationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected GeolocationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new GeolocationService();
        config([
            'qr_attendance.campus.latitude' => 33.5731,
            'qr_attendance.campus.longitude' => -7.5898,
            'qr_attendance.geolocation.max_distance_meters' => 50,
            'qr_attendance.geolocation.accuracy_max_meters' => 100,
        ]);
    }

    public function test_haversine_zero_for_same_point(): void
    {
        $d = $this->service->haversine(33.5731, -7.5898, 33.5731, -7.5898);
        $this->assertEquals(0, round($d));
    }

    public function test_haversine_is_symmetric(): void
    {
        $a = $this->service->haversine(33.5731, -7.5898, 33.5732, -7.5899);
        $b = $this->service->haversine(33.5732, -7.5899, 33.5731, -7.5898);
        $this->assertEquals(round($a), round($b));
    }

    public function test_within_campus_acceptance(): void
    {
        $result = $this->service->isWithinCampus(33.57311, -7.58981, 20.0);
        $this->assertTrue($result['ok']);
    }

    public function test_outside_campus_rejection(): void
    {
        // Casablanca center → ~5km away from configured campus
        $result = $this->service->isWithinCampus(33.6, -7.6, 20.0);
        $this->assertFalse($result['ok']);
    }

    public function test_bad_gps_accuracy_rejected(): void
    {
        $result = $this->service->isWithinCampus(33.57311, -7.58981, 500.0);
        $this->assertFalse($result['ok']);
        $this->assertEquals('gps_accuracy_too_low', $result['reason']);
    }
}
