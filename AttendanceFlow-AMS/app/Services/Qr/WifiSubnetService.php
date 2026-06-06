<?php

namespace App\Services\Qr;

use App\Models\CampusLocation;
use App\Services\BaseService;
use Illuminate\Http\Request;

/**
 * WifiSubnetService
 * =================
 * Detects whether a client request originates from the campus network
 * based on its IP address (browsers cannot expose SSID/BSSID to JS).
 *
 * Supports X-Forwarded-For when behind a configured trusted proxy.
 */
class WifiSubnetService extends BaseService
{
    /**
     * Extract the originating client IP from a request.
     */
    public function getClientIp(?Request $request = null): ?string
    {
        $request ??= request();
        if (! $request instanceof Request) {
            return null;
        }

        $trusted = (array) config('qr_attendance.wifi.trusted_proxies', []);

        if (! empty($trusted)) {
            $forwarded = $request->header('X-Forwarded-For');
            if ($forwarded) {
                $candidates = array_map('trim', explode(',', $forwarded));
                foreach ($candidates as $candidate) {
                    if (filter_var($candidate, FILTER_VALIDATE_IP)) {
                        return $candidate;
                    }
                }
            }
            $realIp = $request->header('X-Real-IP');
            if ($realIp && filter_var($realIp, FILTER_VALIDATE_IP)) {
                return $realIp;
            }
        }

        return $request->ip();
    }

    /**
     * Is the given IP in any of the allowed CIDR subnets?
     */
    public function isOnCampusNetwork(?string $ip, ?Request $request = null): bool
    {
        if (! config('qr_attendance.wifi.enabled', true)) {
            return true; // feature disabled
        }

        $ip ??= $this->getClientIp($request);
        if ($ip === null) {
            return false;
        }

        $campus = CampusLocation::active();
        if ($campus !== null) {
            return $campus->ipAllowed($ip);
        }

        // Fallback to global config subnets
        return $this->ipInAnySubnet($ip, (array) config('qr_attendance.wifi.allowed_subnets', []));
    }

    /**
     * @param  string[]  $subnets  Array of CIDR strings.
     */
    public function ipInAnySubnet(string $ip, array $subnets): bool
    {
        foreach ($subnets as $cidr) {
            if (CampusLocation::ipInCidr($ip, $cidr)) {
                return true;
            }
        }
        return false;
    }

    public function ipInRange(string $ip, string $cidr): bool
    {
        return CampusLocation::ipInCidr($ip, $cidr);
    }
}
