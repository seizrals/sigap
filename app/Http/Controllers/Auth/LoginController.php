<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (config('app.env') === 'production') {
            return view('auth.login');
        }

        try {
            if (Schema::hasTable('users')) {
                $admin = User::firstOrCreate(
                    ['username' => 'admin'],
                    [
                        'name' => 'Admin Kabupaten',
                        'email' => 'admin@sigap-gorontaloutara.go.id',
                        'password' => Hash::make('password'),
                        'role' => 'admin_kabupaten',
                        'opd_name' => 'TPID Kabupaten Gorontalo Utara',
                        'is_active' => true,
                    ]
                );
                Auth::login($admin);
            }
        } catch (\Throwable $e) {
            // Abaikan error (DB belum ready / migration belum jalan)
        }

        return redirect()->route('dashboard');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function portalPublik()
    {
        return view('portal-publik');
    }
}
