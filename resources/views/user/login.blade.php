<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - School Borrowing System</title>
    <link rel="icon" href="{{ asset('images/logo-ng.png') }}" type="image/png">
    {{-- Kalau Tailwind udah di-setup lewat Vite/Mix, hapus baris CDN ini --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-5xl grid grid-cols-1 md:grid-cols-2 gap-10 items-center">

           {{-- KIRI: panel gradient merah + logo + badge "Take and go" --}}
        <div class="relative hidden md:block rounded-[2rem] overflow-hidden h-[560px] shadow-2xl"
               style="background: linear-gradient(135deg, #4B1619 0%, #B1343C 100%);">

            <img
                src="{{ asset('images/flea market-amico.png') }}"
                alt="Flea market illustration"
                class="absolute inset-0 z-0 h-full w-full object-contain p-10"
            >

            {{-- Logo --}}
            <div class="relative z-10 p-6">
                <img src="{{ asset('images/logo-ng-white.png') }}" alt="NG Logo" class="w-11 h-11 object-contain">
            </div>

            {{-- Badge "Take and go" --}}
            <div class="absolute bottom-6 left-6 z-10">
                <span class="inline-flex items-center px-4 py-2 rounded-full bg-white/25 backdrop-blur-sm text-white text-sm font-medium shadow-sm">
                    Take and go
                </span>
            </div>
        </div>

        {{-- KANAN: form login --}}
        <div class="w-full max-w-sm mx-auto">

            {{-- Logo kecil --}}
            <div class="mb-6">
                <img src="{{ asset('images/logo-ng.png') }}" alt="NG Logo" class="w-9 h-9 object-contain">
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back!</h1>
            <p class="text-gray-500 text-sm mb-8 leading-relaxed">
                Sign in to access the school borrowing system and manage your borrowed items.
            </p>

            @if ($errors->any())
                <div class="mb-4 text-sm text-red-600">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('user.login.store') }}" class="space-y-5">
                @csrf

                {{-- NIS --}}
                <div>
                    <label for="nis" class="block text-sm font-semibold text-gray-900 mb-2">Your Nis</label>
                    <input
                        type="text"
                        id="nis"
                        name="nis"
                        value="{{ old('nis') }}"
                        placeholder="Masukkan NIS anda"
                        class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-200 focus:border-red-300"
                        required
                        autofocus
                    >
                    @error('nis')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-900 mb-2">Your Password</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password anda"
                            class="w-full rounded-lg border border-gray-200 px-4 py-2.5 pr-10 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-200 focus:border-red-300"
                            required
                        >
                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600"
                        >
                            <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Forgot password --}}
                <div class="text-right">
                    <a href="{{ route('password.request') ?? '#' }}" class="text-xs text-gray-400 hover:text-gray-600">
                        Forgot Your Password?
                    </a>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full mt-2 py-3 rounded-lg text-white font-semibold text-sm shadow-lg shadow-red-900/20 transition-transform hover:scale-[1.01] active:scale-[0.99]"
                    style="background: linear-gradient(180deg, #b23b3b 0%, #a02f2f 100%);"
                >
                    Log in
                </button>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
        }
    </script>
</body>
</html>
