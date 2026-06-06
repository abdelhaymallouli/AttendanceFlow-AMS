<?php

namespace Tests\Feature\Services\Qr;

use App\Models\Session;
use App\Models\StudentProfile;
use App\Models\User;
use App\Services\Qr\GeolocationService;
use App\Services\Qr\ValidationScoreService;
use App\Services\Qr\WifiSubnetService;
use App\Services\Qr\DeviceFingerprintService;
use App\Services\Qr\QrTokenService;
use App\Services\Qr\QrScanContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidationScoreServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ValidationScoreService $service;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'qr_attendance.hmac.secret' => 's',
            'qr_attendance.campus.latitude' => 33.5731,
            'qr_attendance.campus.longitude' => -7.5898,
            'qr_attendance.geolocation.max_distance_meters' => 50,
            'qr_attendance.geolocation.accuracy_max_meters' => 100,
            'qr_attendance.wifi.allowed_subnets' => ['10.0.0.0/24'],
            'qr_attendance.wifi.strict' => false,
            'qr_attendance.device.enabled' => true,
            'qr_attendance.validation.weights' => [
                'hmac' => 100, 'geolocation' => 40, 'wifi' => 30, 'device' => 30,
            ],
            'qr_attendance.validation.thresholds' => [
                'present_min' => 70, 'late_min' => 40,
            ],
        ]);
        $this->service = new ValidationScoreService(
            new GeolocationService(),
            new WifiSubnetService(),
            new DeviceFingerprintService(),
        );
    }

    public function test_present_when_all_signals_pass(): void
    {
        $session = Session::factory()->create();
        $user = User::factory()->create();
        StudentProfile::create(['user_id' => $user->id]);
        $token = (new QrTokenService())->generate($session);
        $tokenModel = (new QrTokenService())->verify($token['token']);

        $ctx = new QrScanContext(
            token: $tokenModel,
            user: $user,
            latitude: 33.57311,
            longitude: -7.58981,
            gpsAccuracy: 20.0,
            clientIp: '10.0.0.42',
            deviceFingerprintHash: null,
        );

        $result = $this->service->evaluate($ctx);

        $this->assertContains($result['status'], ['present', 'late']);
        $this->assertGreaterThanOrEqual(70, $result['score']);
    }

    public function test_rejected_when_token_invalid(): void
    {
        $user = User::factory()->create();
        $ctx = new QrScanContext(
            token: null,
            user: $user,
            latitude: 33.5731,
            longitude: -7.5898,
            gpsAccuracy: 20.0,
            clientIp: '10.0.0.42',
            deviceFingerprintHash: null,
        );

        $result = $this->service->evaluate($ctx);

        $this->assertEquals('rejected', $result['status']);
        $this->assertEquals('invalid_or_expired_qr_token', $result['rejection_reason']);
    }

    public function test_low_score_yields_late_or_rejected(): void
    {
        $session = Session::factory()->create();
        $user = User::factory()->create();
        StudentProfile::create(['user_id' => $user->id]);
        $token = (new QrTokenService())->generate($session);
        $tokenModel = (new QrTokenService())->verify($token['token']);

        // Bad location, bad wifi, no device → only HMAC passes
        $ctx = new QrScanContext(
            token: $tokenModel,
            user: $user,
            latitude: 33.7, // far from campus
            longitude: -7.7,
            gpsAccuracy: 20.0,
            clientIp: '203.0.113.1', // off-campus IP
            deviceFingerprintHash: null,
        );

        $result = $this->service->evaluate($ctx);

        $this->assertEquals('rejected', $result['status']);
        $this->assertLessThan(70, $result['score']);
    }
}
