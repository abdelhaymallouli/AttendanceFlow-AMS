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
}