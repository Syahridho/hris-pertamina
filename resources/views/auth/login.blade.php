<x-guest-layout>
    <!-- Logo & Header -->
    <div class="flex flex-col items-center text-center mb-6">
        <div class="flex items-center justify-center mb-3">
            <img src="{{ asset('logo.png') }}" alt="Logo Pertamina" class="w-16 h-16 object-contain" />
        </div>
        <h1 class="text-xl font-bold tracking-tight text-slate-900">HRIS PMC</h1>
        <p class="text-xs text-slate-500 mt-1">PT. Pertamina Maintenance & Construction</p>
    </div>

    <!-- Card Description -->
    <div class="mb-5 text-center">
        <h2 class="text-sm font-semibold text-slate-900">Masuk ke Akun</h2>
        <p class="text-xs text-slate-500 mt-0.5">Silakan masuk menggunakan email kantor Anda</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-xs font-medium text-green-600" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div class="space-y-1.5">
            <label for="email" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider">Email Kantor</label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autofocus 
                   autocomplete="username" 
                   placeholder="nama@hris-pertamina.com"
                   class="w-full px-3.5 py-2 text-sm bg-white border border-slate-200 rounded-md text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-1 focus:ring-[#E8192C] focus:border-[#E8192C] transition-colors" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-600" />
        </div>

        <!-- Password -->
        <div class="space-y-1.5">
            <div class="flex justify-between items-center">
                <label for="password" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs text-[#003DA5] font-semibold hover:underline">Lupa sandi?</a>
                @endif
            </div>
            <input id="password" 
                   type="password" 
                   name="password" 
                   required 
                   autocomplete="current-password" 
                   placeholder="••••••••"
                   class="w-full px-3.5 py-2 text-sm bg-white border border-slate-200 rounded-md text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-1 focus:ring-[#E8192C] focus:border-[#E8192C] transition-colors" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-red-600" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-[#E8192C] border-slate-300 rounded focus:ring-[#E8192C]" />
                <span class="ml-2 text-xs text-slate-600">Ingat perangkat ini</span>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="pt-1">
            <button type="submit" 
                    class="w-full px-4 py-2.5 bg-[#E8192C] text-white text-sm font-semibold rounded-md hover:bg-[#cf1525] focus:outline-none focus:ring-2 focus:ring-[#E8192C] focus:ring-offset-2 transition-colors duration-150">
                Masuk Ke Sistem
            </button>
        </div>
    </form>
</x-guest-layout>
