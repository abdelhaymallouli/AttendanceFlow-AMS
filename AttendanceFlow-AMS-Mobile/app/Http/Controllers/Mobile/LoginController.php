<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
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
                
                // Store user and token in session
                session([
                    'mobile_token' => $data['token'],
                    'mobile_user' => $data['user'],
                    'mobile_role' => $data['user']['roles'][0]['name'] ?? 'student',
                ]);

                // Redirect based on role
                $role = session('mobile_role');
                if ($role === 'admin') {
                    return redirect()->route('mobile.admin.dashboard');
                } elseif ($role === 'teacher') {
                    return redirect()->route('mobile.sessions');
                } else {
                    // Fetch student profile ID if student
                    // In a production app we'd fetch profile from API, 
                    // for demo we assume profile ID matches or defaults to 1
                    $profileId = 1; // Default fallback
                    return redirect()->route('mobile.student.dashboard', ['id' => $profileId]);
                }
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
        session()->forget(['mobile_token', 'mobile_user', 'mobile_role']);
        return redirect()->route('mobile.login');
    }
}
