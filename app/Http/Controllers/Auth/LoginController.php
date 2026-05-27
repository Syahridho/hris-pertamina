<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Manpower;
use App\Models\Superadmin;
use App\Models\Supervisor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        // Redirect jika sudah login
        if (auth('superadmin')->check()) {
            return redirect()->route('superadmin.dashboard');
        }
        if (auth('supervisor')->check()) {
            return redirect()->route('supervisor.dashboard');
        }
        if (auth('manpower')->check()) {
            return redirect()->route('manpower.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Proses login — deteksi role secara otomatis dari email.
     * Urutan pengecekan: superadmin → supervisor → manpower
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $email    = $request->email;
        $password = $request->password;
        $remember = $request->boolean('remember');

        // 1. Cek Superadmin
        $superadmin = Superadmin::where('email', $email)->where('status', 'active')->first();
        if ($superadmin && Hash::check($password, $superadmin->password)) {
            Auth::guard('superadmin')->login($superadmin, $remember);
            $request->session()->regenerate();
            return redirect()->intended(route('superadmin.dashboard'))
                ->with('success', 'Selamat datang, ' . $superadmin->username . '!');
        }

        // 2. Cek Supervisor
        $supervisor = Supervisor::where('email', $email)->where('status', 'active')->first();
        if ($supervisor && Hash::check($password, $supervisor->password)) {
            Auth::guard('supervisor')->login($supervisor, $remember);
            $request->session()->regenerate();
            return redirect()->intended(route('supervisor.dashboard'))
                ->with('success', 'Selamat datang, ' . $supervisor->username . '!');
        }

        // 3. Cek Manpower
        $manpower = Manpower::where('email', $email)->where('status', 'active')->first();
        if ($manpower && Hash::check($password, $manpower->password)) {
            Auth::guard('manpower')->login($manpower, $remember);
            $request->session()->regenerate();
            return redirect()->intended(route('manpower.dashboard'))
                ->with('success', 'Selamat datang, ' . $manpower->username . '!');
        }

        // Tidak ditemukan di semua tabel
        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'Email atau password tidak sesuai, atau akun tidak aktif.']);
    }

    /**
     * Logout dari semua guard yang aktif.
     */
    public function logout(Request $request): RedirectResponse
    {
        // Logout semua guard
        if (auth('superadmin')->check()) {
            Auth::guard('superadmin')->logout();
        }
        if (auth('supervisor')->check()) {
            Auth::guard('supervisor')->logout();
        }
        if (auth('manpower')->check()) {
            Auth::guard('manpower')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil logout.');
    }
}
