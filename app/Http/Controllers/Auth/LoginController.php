<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginField = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$loginField => $request->username, 'password' => $request->password, 'is_active' => true], $request->boolean('remember'))) {
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

        return redirect()->route('portal-publik');
    }

    public function portalPublik()
    {
        $kecamatan = collect();

        try {
            if (Schema::hasTable('kecamatan')) {
                $kecamatan = Kecamatan::where('is_active', true)
                    ->select('id', 'nama_kecamatan')
                    ->orderBy('nama_kecamatan')
                    ->get();
            }
        } catch (\Throwable $e) {
            // Abaikan error (DB belum ready / migration belum jalan)
        }

        return view('portal-publik', compact('kecamatan'));
    }
}
