<?php

namespace App\Services\Qr;

use App\Models\CampusLocation;
use App\Services\BaseService;

/**
 * GeolocationService
 * ==================
 * Computes Haversine distance between a point and the campus.
 * Returns a structured result used by ValidationScoreService.
 */
class GeolocationService extends BaseService
{
    private const EARTH_RADIUS_METERS = 6371000;

    /**
     * Great-circle distance in meters between two coordinates.
     */
    public function haversine(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lng1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lng2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2)
            + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
        ));

        return self::EARTH_RADIUS_METERS * $angle;
    }

    /**
     * Evaluate whether the given (lat, lng) point is within the campus.
     *
     * @return array{ok: bool, distance_meters: float, max_meters: int, reason: ?string}
     */
    public function isWithinCampus(?float $latitude, ?float $longitude, ?float $accuracy = null): array
    {
        if (! config('qr_attendance.geolocation.enabled', true)) {
            return [
                'ok' => true,
                'distance_meters' => 0.0,
                'max_meters' => (int) config('qr_attendance.geolocation.max_distance_meters', 50),
                'reason' => 'geolocation_disabled',
            ];
        }

        if ($latitude === null || $longitude === null) {
            return [
                'ok' => false,
                'distance_meters' => PHP_FLOAT_MAX,
                'max_meters' => (int) config('qr_attendance.geolocation.max_distance_meters', 50),
                'reason' => 'gps_missing',
            ];
        }

        $campus = CampusLocation::active() ?? $this->fallbackCampus();

        $maxMeters = (int) config('qr_attendance.geolocation.max_distance_meters', 50);
        $accuracyMax = (int) config('qr_attendance.geolocation.accuracy_required', 100);

        $distance = $campus->distanceMeters($latitude, $longitude);

        if ($accuracy !== null && $accuracy > $accuracyMax) {
            return [
                'ok' => false,
                'distance_meters' => $distance,
                'max_meters' => $maxMeters,
                'reason' => 'gps_accuracy_too_low',
            ];
        }

        if ($distance > $maxMeters) {
            return [
                'ok' => false,
                'distance_meters' => $distance,
                'max_meters' => $maxMeters,
                'reason' => 'outside_campus_radius',
            ];
        }

        return [
            'ok' => true,
            'distance_meters' => $distance,
            'max_meters' => $maxMeters,
            'reason' => null,
        ];
    }

    public function getDistanceToCampus(?float $latitude, ?float $longitude): float
    {
        if ($latitude === null || $longitude === null) {
            return PHP_FLOAT_MAX;
        }
        $campus = CampusLocation::active() ?? $this->fallbackCampus();
        return $campus->distanceMeters($latitude, $longitude);
    }

    private function fallbackCampus(): CampusLocation
    {
        $c = new CampusLocation();
        $c->latitude = (float) config('qr_attendance.geolocation.campus_latitude');
        $c->longitude = (float) config('qr_attendance.geolocation.campus_longitude');
        $c->radius_meters = (int) config('qr_attendance.geolocation.max_distance_meters', 50);
        return $c;
    }
}
