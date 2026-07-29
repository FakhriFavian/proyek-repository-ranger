<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="{{ asset('assets/css/main/app.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/main/kinetic-theme.css') }}">
        <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.svg') }}" type="image/x-icon">
    </head>
    <body class="kt-auth-body d-flex align-items-center justify-content-center min-vh-100 py-4">
        <div class="kt-auth-card card overflow-hidden">
            <div class="row g-0 h-100">
                <div class="col-lg-5 kt-auth-panel d-none d-lg-flex flex-column justify-content-between p-5">
                    <div class="d-flex align-items-center gap-2">
                        <span class="kt-logo-mark d-inline-flex align-items-center justify-content-center"><i class="bi bi-tornado"></i></span>
                        <h5 class="mb-0 fw-bold text-white">{{ config('app.name') }}</h5>
                    </div>
                    <div>
                        <h2 class="fw-bold text-white mb-3">Kelola operasional Anda dengan percaya diri.</h2>
                        <p class="mb-0 text-white-50">Satu dashboard untuk semua data, pengguna, dan modul aplikasi Anda.</p>
                    </div>
                    <p class="mb-0 text-white-50 small">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
                </div>
                <div class="col-lg-7">
                    <div class="p-4 p-md-5">
                        <a href="/" class="d-inline-flex d-lg-none align-items-center gap-2 text-decoration-none mb-4">
                            <span class="kt-logo-mark d-inline-flex align-items-center justify-content-center"><i class="bi bi-tornado"></i></span>
                            <h5 class="mb-0 fw-bold">{{ config('app.name') }}</h5>
                        </a>
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
