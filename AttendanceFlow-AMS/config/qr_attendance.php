<?php

/*
|--------------------------------------------------------------------------
| QR Code Attendance System Configuration
|--------------------------------------------------------------------------
|
| Centralized configuration for the HMAC-SHA256 signed QR token system
| with multi-factor validation (GPS, Wi-Fi subnet, device fingerprint).
|
| All thresholds, weights, and campus parameters are tunable from here.
| Override sensitive values (HMAC secret) via .env
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | HMAC Token Security
    |--------------------------------------------------------------------------
    |
    | The HMAC secret is used to sign every QR token. It MUST be set in
    | .env as QR_HMAC_SECRET with at least 32 random bytes. The clock_skew
    | is the tolerance (in seconds) for clock drift between the server
    | generating the token and the student device scanning it.
    |
    */
    'hmac' => [
        'secret'     => env('QR_HMAC_SECRET'),
        'algorithm'  => 'sha256',
        'clock_skew' => (int) env('QR_CLOCK_SKEW', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | Token Lifecycle
    |--------------------------------------------------------------------------
    |
    | ttl_seconds : lifetime of each generated token before it expires.
    | cleanup_after_days : cron job may delete tokens older than this.
    | prefix : human-readable prefix embedded in the token payload.
    |
    */
    'token' => [
        'ttl_seconds'        => (int) env('QR_TOKEN_TTL', 30),
        'cleanup_after_days' => 7,
        'prefix'             => 'AMS-QR',
    ],

    /*
    |--------------------------------------------------------------------------
    | Geolocation / Geofencing
    |--------------------------------------------------------------------------
    |
    | enabled : master switch. If false, geolocation signal is skipped.
    | campus_latitude / campus_longitude : default campus coordinates
    |   (override via CAMPUS_LATITUDE / CAMPUS_LONGITUDE env vars).
    | max_distance_meters : Haversine distance threshold for "on campus".
    | accuracy_required : reject scans with GPS accuracy worse than this
    |   (in meters). 100m is a sensible indoor threshold.
    |
    */
    'geolocation' => [
        'enabled'             => (bool) env('QR_GEOLOCATION_ENABLED', true),
        'campus_latitude'     => (float) env('CAMPUS_LATITUDE', 33.5731),
        'campus_longitude'    => (float) env('CAMPUS_LONGITUDE', -7.5898),
        'max_distance_meters' => (int) env('QR_MAX_DISTANCE_METERS', 50),
        'accuracy_required'   => (int) env('QR_GPS_ACCURACY_MAX', 100),
    ],

    /*
    |--------------------------------------------------------------------------
    | Wi-Fi Subnet Validation
    |--------------------------------------------------------------------------
    |
    | enabled : master switch.
    | allowed_subnets : CIDR ranges that count as "on campus network".
    | strict : if true, missing subnet match = hard reject; if false, it's
    |   a soft signal (bonus points only).
    |
    | Browser-side Wi-Fi SSID detection is NOT possible, so the check is
    | server-side based on the client IP (REMOTE_ADDR or X-Forwarded-For).
    |
    */
    'wifi' => [
        'enabled'        => (bool) env('QR_WIFI_ENABLED', true),
        'allowed_subnets' => [
            '192.168.10.0/24',
            '10.190.0.0/16',
        ],
        'strict'         => (bool) env('QR_WIFI_STRICT', false),
        'trusted_proxies' => array_filter(explode(',', (string) env('QR_TRUSTED_PROXIES', ''))),
    ],

    /*
    |--------------------------------------------------------------------------
    | Device Fingerprint
    |--------------------------------------------------------------------------
    |
    | enabled : master switch.
    | max_fingerprints_per_user : cap the number of trusted devices.
    | trust_new_device : if false, first scan from a new device is flagged
    |   for manual review.
    |
    | The fingerprint is a SHA-256 hash of (user-agent, screen size,
    | timezone, canvas hash) sent by the client.
    |
    */
    'device' => [
        'enabled'                   => (bool) env('QR_DEVICE_ENABLED', true),
        'max_fingerprints_per_user' => (int) env('QR_MAX_DEVICES', 3),
        'trust_new_device'          => (bool) env('QR_TRUST_NEW_DEVICE', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Multi-Factor Validation Scoring
    |--------------------------------------------------------------------------
    |
    | weights.thresholds define the decision boundaries:
    |   score >= present_min → status = 'present'
    |   late_min <= score < present_min → status = 'late'
    |   score < late_min → rejected (no AttendanceRecord created)
    |
    | HMAC validity is binary: invalid signature = immediate rejection
    | regardless of other signals.
    |
    */
    'validation' => [
        'weights' => [
            'hmac'        => 100, // must-pass (binary)
            'geolocation' => 40,
            'wifi'        => 30,
            'device'      => 30,
        ],
        'thresholds' => [
            'present_min' => (int) env('QR_SCORE_PRESENT', 70),
            'late_min'    => (int) env('QR_SCORE_LATE', 40),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Manual Override
    |--------------------------------------------------------------------------
    |
    | When enabled, teachers/admins can force a status on the existing
    | attendance/show page after the session. The override sets
    | check_in_method = 'manual' and preserves the original validation_score
    | in the audit trail.
    |
    */
    'manual_override' => [
        'enabled'     => true,
        'allowed_for' => ['admin', 'teacher'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Offline Queue
    |--------------------------------------------------------------------------
    |
    | enabled : master switch for the mobile offline → server sync flow.
    | max_batch_size : maximum number of scans accepted per sync request.
    | max_age_hours : scans older than this are rejected on sync.
    |
    | Idempotency: (session_id, student_id, nonce) unique key prevents
    | double-processing.
    |
    */
    'offline' => [
        'enabled'        => (bool) env('QR_OFFLINE_ENABLED', true),
        'max_batch_size' => (int) env('QR_OFFLINE_MAX_BATCH', 50),
        'max_age_hours'  => (int) env('QR_OFFLINE_MAX_AGE_HOURS', 24),
    ],

    /*
    |--------------------------------------------------------------------------
    | API Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Per-user throttling on the QR scan endpoint to prevent brute-force
    | token guessing. Aligned with Laravel's RateLimiter facade.
    |
    */
    'rate_limit' => [
        'scan_per_minute' => (int) env('QR_SCAN_RATE_PER_MIN', 10),
    ],
];
