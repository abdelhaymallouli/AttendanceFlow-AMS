<?php

namespace Tests\Feature\Services\Qr;

use App\Models\QrAttendanceToken;
use App\Models\Session;
use App\Services\Qr\QrTokenService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QrTokenServiceTest extends TestCase
{
    use RefreshDatabase;

    protected QrTokenService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new QrTokenService();
        config([
            'qr_attendance.hmac.secret' => 'test-secret-key-for-hmac-signing',
            'qr_attendance.token.ttl_seconds' => 30,
            'qr_attendance.token.clock_skew_seconds' => 60,
        ]);
    }

    public function test_generates_a_valid_token(): void
    {
        $session = Session::factory()->create();

        $result = $this->service->generate($session);

        $this->assertArrayHasKey('token', $result);
        $this->assertArrayHasKey('issued_at', $result);
        $this->assertArrayHasKey('expires_at', $result);
        $this->assertStringContainsString('.', $result['token']);
    }

    public function test_verifies_a_freshly_generated_token(): void
    {
        $session = Session::factory()->create();
        $result = $this->service->generate($session);

        $token = $this->service->verify($result['token']);

        $this->assertInstanceOf(QrAttendanceToken::class, $token);
        $this->assertEquals($session->id, $token->session_id);
    }

    public function test_rejects_a_tampered_token(): void
    {
        $session = Session::factory()->create();
        $result = $this->service->generate($session);

        // Tamper the payload (flip a bit in the middle)
        [$payload, $sig] = explode('.', $result['token']);
        $decoded = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
        $decoded['p'] = $session->id + 1; // wrong session
        $tamperedPayload = rtrim(strtr(base64_encode(json_encode($decoded)), '+/', '-_'), '=');

        $this->assertNull($this->service->verify($tamperedPayload . '.' . $sig));
    }

    public function test_rejects_an_expired_token(): void
    {
        $session = Session::factory()->create();
        $result = $this->service->generate($session);
        [, $sig] = explode('.', $result['token']);

        $expiredPayload = json_encode([
            'p' => $session->id,
            's' => 'qr',
            'n' => 'expired-nonce',
            'i' => now()->subMinutes(5)->timestamp,
            'e' => now()->subMinutes(4)->timestamp, // expired
        ]);
        $b64 = rtrim(strtr(base64_encode($expiredPayload), '+/', '-_'), '=');
        $hmac = hash_hmac('sha256', $b64, config('qr_attendance.hmac.secret'), true);
        $sigB64 = rtrim(strtr(base64_encode($hmac), '+/', '-_'), '=');

        $this->assertNull($this->service->verify($b64 . '.' . $sigB64));
    }

    public function test_consume_marks_token_as_used(): void
    {
        $session = Session::factory()->create();
        $result = $this->service->generate($session);
        $token = $this->service->verify($result['token']);

        $consumed = $this->service->consume($token, studentId: 42, ip: '10.0.0.1');

        $this->assertTrue($consumed);
        $this->assertNotNull($token->fresh()->consumed_at);
        $this->assertEquals(42, $token->fresh()->consumed_by_student_id);
        $this->assertEquals('10.0.0.1', $token->fresh()->consumed_ip);
        $this->assertTrue($token->fresh()->is_consumed);
    }

    public function test_consume_cannot_be_called_twice(): void
    {
        $session = Session::factory()->create();
        $result = $this->service->generate($session);
        $token = $this->service->verify($result['token']);

        $this->assertTrue($this->service->consume($token, 1, '10.0.0.1'));

        $token->refresh();
        $this->assertFalse($this->service->consume($token, 2, '10.0.0.2'));
    }
}
