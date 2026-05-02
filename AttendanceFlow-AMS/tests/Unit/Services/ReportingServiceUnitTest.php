<?php

namespace Tests\Unit\Services;

use App\Services\ReportingService;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class ReportingServiceUnitTest extends TestCase
{
    protected ReportingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        // We don't need RefreshDatabase because we won't touch the DB
        $this->service = new ReportingService();
    }

    /**
     * Test calculation logic with a mocked collection.
     * This is a "Pure" Unit test (no DB, very fast).
     */
    public function test_it_calculates_correct_attendance_rate(): void
    {
        $records = collect([
            (object)['status' => 'present'],
            (object)['status' => 'present'],
            (object)['status' => 'absent'],
            (object)['status' => 'justified'],
        ]);

        $stats = $this->service->calculateStats($records);

        $this->assertEquals(4, $stats['total_records']);
        $this->assertEquals(2, $stats['present']);
        $this->assertEquals(2, $stats['absent_total']);
        $this->assertEquals('50%', $stats['attendance_rate']);
    }

    public function test_it_handles_empty_collection_gracefully(): void
    {
        $stats = $this->service->calculateStats(collect([]));

        $this->assertEquals(0, $stats['total_records']);
        $this->assertEquals('0%', $stats['attendance_rate']);
    }
}
