<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class ApiService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.ams.url');
    }

    public function getSession($id)
    {
        try {
            $response = Http::get($this->baseUrl . "/academic/session/{$id}");
            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getSessions()
    {
        try {
            $response = Http::get($this->baseUrl . '/academic/sessions');
            return $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getSessionAttendance($id)
    {
        try {
            $response = Http::get($this->baseUrl . "/attendance/session/{$id}");
            return $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getStudentAttendance($id)
    {
        try {
            $response = Http::get($this->baseUrl . "/attendance/student/{$id}");
            return $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function recordAttendance($data)
    {
        try {
            $response = Http::post($this->baseUrl . '/attendance/record', $data);
            return $response->successful() ? $response->json() : ['success' => false];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Connection error'];
        }
    }

    public function getFilieres()
    {
        try {
            $response = Http::get($this->baseUrl . '/academic/filieres');
            return $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getGroups()
    {
        try {
            $response = Http::get($this->baseUrl . '/academic/groups');
            return $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getModules()
    {
        try {
            $response = Http::get($this->baseUrl . '/academic/modules');
            return $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getPendingJustifications()
    {
        try {
            $response = Http::get($this->baseUrl . '/justifications/pending');
            return $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function submitJustification($data)
    {
        try {
            $response = Http::post($this->baseUrl . '/justifications/submit', $data);
            return $response->successful() ? $response->json() : ['success' => false];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Connection error'];
        }
    }

    public function getAdminStats()
    {
        try {
            $response = Http::get($this->baseUrl . "/stats/admin");
            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getStudentStats($id)
    {
        try {
            $response = Http::get($this->baseUrl . "/stats/student/{$id}");
            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getMeProfile(?string $token = null)
    {
        try {
            $response = Http::withToken($token ?? session('mobile_token'))
                ->get($this->baseUrl . '/me/profile');
            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getStudentJustifications(int $studentProfileId)
    {
        try {
            $response = Http::get($this->baseUrl . "/justifications/student/{$studentProfileId}");
            return $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getGroupSessions(int $groupId)
    {
        try {
            $response = Http::get($this->baseUrl . "/academic/sessions/group/{$groupId}");
            return $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function submitJustificationWithFile(array $data, ?string $filePath = null, ?string $token = null)
    {
        try {
            $http = Http::withToken($token ?? session('mobile_token'));
            if ($filePath && file_exists($filePath)) {
                $response = $http->attach('document', file_get_contents($filePath), basename($filePath))
                    ->post($this->baseUrl . '/justifications/submit', $data);
            } else {
                $response = $http->post($this->baseUrl . '/justifications/submit', $data);
            }
            return $response->json() ?? ['success' => false];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Connection error'];
        }
    }

    public function getUserNotifications($userId)
    {
        try {
            $response = Http::get($this->baseUrl . "/notifications/user/{$userId}");
            return $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function markNotificationAsRead($id)
    {
        try {
            $response = Http::post($this->baseUrl . "/notifications/{$id}/read");
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function qrScan(array $payload, ?string $token = null)
    {
        try {
            $response = Http::withToken($token ?? session('mobile_token'))
                ->post($this->baseUrl . '/attendance/qr/scan', $payload);
            return $response->json() ?? ['status' => 'rejected', 'rejection_reason' => 'no_response'];
        } catch (\Exception $e) {
            return ['status' => 'rejected', 'rejection_reason' => 'network_error', 'message' => $e->getMessage()];
        }
    }

    public function qrSyncOffline(array $entries, ?string $token = null)
    {
        try {
            $response = Http::withToken($token ?? session('mobile_token'))
                ->post($this->baseUrl . '/attendance/qr/sync-offline', ['entries' => $entries]);
            return $response->json() ?? ['processed' => 0, 'accepted' => 0, 'rejected' => count($entries), 'results' => []];
        } catch (\Exception $e) {
            return ['processed' => 0, 'accepted' => 0, 'rejected' => count($entries), 'results' => [], 'message' => $e->getMessage()];
        }
    }
}