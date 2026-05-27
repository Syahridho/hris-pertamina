<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        // Redirect kalau sudah login
        if (auth('superadmin')->check()) return redirect('/admin');
        if (auth('supervisor')->check()) return redirect()->route('supervisor.dashboard');
        if (auth('manpower')->check()) return redirect()->route('manpower.dashboard');

        return view('landing');
    }
}
