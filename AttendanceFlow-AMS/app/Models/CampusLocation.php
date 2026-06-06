<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampusLocation extends Model
{
    use HasFactory;

    protected $table = 'campus_locations';

    protected $fillable = [
        'name',
        'code',
        'latitude',
        'longitude',
        'radius_meters',
        'allowed_subnets',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'radius_meters' => 'integer',
        'allowed_subnets' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Haversine check: is the given point within this campus radius?
     */
    public function contains(float $latitude, float $longitude): bool
    {
        $distance = $this->distanceMeters($latitude, $longitude);

        return $distance <= $this->radius_meters;
    }

    public function distanceMeters(float $latitude, float $longitude): float
    {
        $earthRadius = 6371000; // meters
        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($latitude);
        $lonTo = deg2rad($longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2)
            + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $earthRadius * $angle;
    }

    /**
     * @param  string  $ip
     * @return bool True if IP is in any of the allowed subnets.
     */
    public function ipAllowed(string $ip): bool
    {
        $subnets = $this->allowed_subnets ?? [];

        foreach ($subnets as $cidr) {
            if (self::ipInCidr($ip, $cidr)) {
                return true;
            }
        }

        return false;
    }

    public static function ipInCidr(string $ip, string $cidr): bool
    {
        if (! str_contains($cidr, '/')) {
            return $ip === $cidr;
        }

        [$subnet, $bits] = explode('/', $cidr, 2);
        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnet);

        if ($ipLong === false || $subnetLong === false) {
            return false;
        }

        $mask = -1 << (32 - (int) $bits);

        return ($ipLong & $mask) === ($subnetLong & $mask);
    }

    public static function active(): ?self
    {
        return self::query()->where('is_active', true)->first();
    }
}
