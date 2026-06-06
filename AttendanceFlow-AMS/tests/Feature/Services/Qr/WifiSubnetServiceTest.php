<?php

namespace Tests\Feature\Services\Qr;

use App\Services\Qr\WifiSubnetService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class WifiSubnetServiceTest extends TestCase
{
    protected WifiSubnetService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new WifiSubnetService();
    }

    public function test_ip_in_cidr_in_range(): void
    {
        $this->assertTrue(WifiSubnetService::ipInCidr('10.0.0.50', '10.0.0.0/24'));
        $this->assertTrue(WifiSubnetService::ipInCidr('192.168.1.100', '192.168.1.0/24'));
        $this->assertFalse(WifiSubnetService::ipInCidr('10.0.1.1', '10.0.0.0/24'));
    }

    public function test_ip_in_any_subnet(): void
    {
        $this->assertTrue($this->service->ipInAnySubnet('10.0.0.10', ['10.0.0.0/24', '192.168.1.0/24']));
        $this->assertFalse($this->service->ipInAnySubnet('172.16.0.1', ['10.0.0.0/24']));
    }

    public function test_is_on_campus_network_with_matching_subnet(): void
    {
        config(['qr_attendance.wifi.allowed_subnets' => ['10.0.0.0/24']]);
        $request = Request::create('/', 'GET', [], [], [], ['REMOTE_ADDR' => '10.0.0.42']);
        $this->assertTrue($this->service->isOnCampusNetwork(null, $request));
    }

    public function test_is_off_campus_when_ip_does_not_match(): void
    {
        config(['qr_attendance.wifi.allowed_subnets' => ['10.0.0.0/24']]);
        $request = Request::create('/', 'GET', [], [], [], ['REMOTE_ADDR' => '203.0.113.5']);
        $this->assertFalse($this->service->isOnCampusNetwork(null, $request));
    }
}
