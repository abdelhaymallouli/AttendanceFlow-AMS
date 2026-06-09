<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('mobile.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $apiUrl = config('services.ams.url') . '/login';

        try {
            $response = Http::post($apiUrl, [
                'email' => $request->email,
                'password' => $request->password,
                'device_name' => 'mobile_app',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $token = $data['token'];
                $user = $data['user'];
                $role = $user['roles'][0]['name'] ?? 'student';

                session([
                    'mobile_token' => $token,
                    'mobile_user' => $user,
                    'mobile_role' => $role,
                ]);

                if ($role !== 'student') {
                    return back()->withErrors([
                        'email' => 'Cette application est réservée aux étudiants.',
                    ]);
                }

                $profileData = app(ApiService::class)->getMeProfile($token);
                if ($profileData && $profileData['profile']) {
                    session([
                        'mobile_student_profile_id' => $profileData['profile']['id'],
                        'mobile_student_group_id' => $profileData['profile']['group_id'] ?? null,
                        'mobile_student_matricule' => $profileData['profile']['matricule'] ?? null,
                    ]);
                }
                return redirect()->route('mobile.home');
            }

            return back()->withErrors([
                'email' => $response->json('message') ?? 'Identifiants incorrects.',
            ]);
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Impossible de se connecter au serveur AMS.',
            ]);
        }
    }

    public function logout()
    {
        $token = session('mobile_token');
        if ($token) {
            try {
                Http::withToken($token)
                    ->post(config('services.ams.url') . '/logout');
            } catch (\Exception $e) {
                // ignore
            }
        }

        session()->forget([
            'mobile_token',
            'mobile_user',
            'mobile_role',
            'mobile_student_profile_id',
            'mobile_student_group_id',
            'mobile_student_matricule',
        ]);

        return redirect()->route('mobile.login');
    }
}
