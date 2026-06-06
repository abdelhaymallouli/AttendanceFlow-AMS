<?php

namespace Tests\Feature\Services\Qr;

use App\Models\DeviceFingerprint;
use App\Models\User;
use App\Services\Qr\DeviceFingerprintService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeviceFingerprintServiceTest extends TestCase
{
    use RefreshDatabase;

    protected DeviceFingerprintService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DeviceFingerprintService();
    }

    public function test_generate_is_deterministic(): void
    {
        $a = $this->service->generate(['ua' => 'X', 'screen' => '1', 'tz' => 'UTC', 'canvas' => 'c']);
        $b = $this->service->generate(['ua' => 'X', 'screen' => '1', 'tz' => 'UTC', 'canvas' => 'c']);
        $this->assertEquals($a, $b);
        $this->assertEquals(64, strlen($a)); // sha256 hex
    }

    public function test_generate_differs_for_different_inputs(): void
    {
        $a = $this->service->generate(['ua' => 'A']);
        $b = $this->service->generate(['ua' => 'B']);
        $this->assertNotEquals($a, $b);
    }

    public function test_register_creates_fingerprint(): void
    {
        $user = User::factory()->create();
        $hash = $this->service->generate(['ua' => 'test']);

        $fp = $this->service->register($user, $hash, 'TestAgent');

        $this->assertInstanceOf(DeviceFingerprint::class, $fp);
        $this->assertEquals($user->id, $fp->user_id);
        $this->assertEquals($hash, $fp->fingerprint_hash);
    }

    public function test_register_is_idempotent(): void
    {
        $user = User::factory()->create();
        $hash = $this->service->generate(['ua' => 'test']);

        $a = $this->service->register($user, $hash);
        $b = $this->service->register($user, $hash);

        $this->assertEquals($a->id, $b->id);
    }

    public function test_is_known_returns_true_for_known_fingerprint(): void
    {
        $user = User::factory()->create();
        $hash = $this->service->generate(['ua' => 'test']);
        $this->service->register($user, $hash);

        $this->assertTrue($this->service->isKnown($user, $hash));
    }

    public function test_is_known_returns_false_for_unknown(): void
    {
        $user = User::factory()->create();
        $this->assertFalse($this->service->isKnown($user, 'some-other-hash'));
    }
}
