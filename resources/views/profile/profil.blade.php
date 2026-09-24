{{--
    resources/views/profile/profil.blade.php

    Halaman Profil Pengguna.
--}}

@php
    $profileImage = $user->foto_profil
        ? asset('storage/' . $user->foto_profil)
        : asset('images/logo-ng.png');
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna - TAKE AND GO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; }
        .maroon { background-color: #8C1F2F; }
        .maroon-text { color: #8C1F2F; }
        .cream { background-color: #fbe9e2; }
        .photo-save-panel { display: none; }
        .photo-save-panel.is-visible { display: flex; }
        .profile-photo-card { position: relative; overflow: hidden; }
        .profile-photo-mini-action {
            width: 2.4rem;
            height: 2.4rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.9);
            color: #8C1F2F;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease, background 0.2s ease;
        }
        .profile-photo-mini-action:hover {
            transform: translateY(-1px);
            background: #ffffff;
        }
        .profile-action-btn {
            width: 2.4rem;
            height: 2.4rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.9);
            color: #8C1F2F;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease, background 0.2s ease;
        }
        .profile-action-btn:hover {
            transform: translateY(-1px);
            background: #ffffff;
        }
        .identity-card {
            border: 1px solid rgba(140, 31, 47, 0.08);
            box-shadow: 0 10px 24px rgba(31, 41, 55, 0.04);
        }
        .identity-row {
            border-bottom: 1px solid #e5e7eb;
        }
        .identity-row:last-child {
            border-bottom: 0;
        }
        @media (max-width: 767px) {
            .profile-photo-card {
                min-height: 22rem;
            }
        }
    </style>
</head>
<body class="bg-white min-h-screen">

    <!-- {{-- Top bar hitam --}}
    <div class="px-8 py-3">
        <p class="text-gray-400 text-xs tracking-widest">PROFIL PENGGUNA</p>
    </div> -->

    {{-- Header maroon --}}
    <div class="maroon px-8 py-4 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <button type="button"
                    onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '{{ route('home') }}'; }"
                    class="text-white text-3xl font-semibold leading-none hover:text-white/80 transition"
                    title="Kembali ke halaman sebelumnya"
                    aria-label="Kembali ke halaman sebelumnya">
                &lt;
            </button>

            {{-- Logo --}}
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo-ng-white.png') }}"
                     alt="NG Logo"
                     class="h-10 w-10 object-contain">
                <span class="text-white font-extrabold text-lg tracking-wide">
                    TAKE AND GO
                </span>
            </div>
        </div>

        {{-- Foto + nama user di pojok kanan --}}
        <div class="flex items-center gap-3">
                            <img src="{{ $profileImage }}"
                                    alt="Foto {{ $user->name }}"
                 class="w-10 h-10 rounded-full object-cover border-2 border-white">
                        <span class="text-white font-semibold">{{ $user->name }}</span>
        </div>
    </div>

    {{-- Konten utama --}}
    <div class="bg-white p-4 sm:p-6 lg:p-10">
        <div class="cream rounded-2xl p-4 sm:p-6 lg:p-8 relative">

            @if (session('status') === 'photo-updated')
                <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">
                    Foto profil berhasil diperbarui.
                </div>
            @endif

            @if ($errors->has('foto_profil'))
                <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                    {{ $errors->first('foto_profil') }}
                </div>
            @endif

            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Profil Pengguna</h1>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-[500px_minmax(0,1fr)] gap-8 lg:gap-10 items-stretch">

                {{-- Kolom kiri: Foto profil --}}
                <div class="w-full h-full">
                    <div class="profile-photo-card maroon rounded-2xl p-6 sm:p-8 flex items-center justify-center relative min-h-[19rem] shadow-sm h-full">
                        <form action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data" id="profile-photo-form" class="w-full h-full flex items-center justify-center">
                            @csrf
                            <input type="file" name="foto_profil" id="foto_profil" accept=".jpg,.jpeg,.png,image/jpeg,image/png" class="hidden">

                            <div class="absolute top-4 right-4 z-10">
                                <button type="button" id="choose-photo-button" title="Ganti Foto Profil" class="profile-photo-mini-action" aria-label="Ganti Foto Profil">
                                    <i class="fa-solid fa-camera text-sm"></i>
                                </button>
                            </div>

                            <img src="{{ $profileImage }}"
                                id="profile-photo-preview"
                                alt="Foto profil {{ $user->name }}"
                                class="w-40 h-40 sm:w-48 sm:h-48 rounded-full object-cover border-4 border-white/20 shadow-lg">

                            <div id="photo-save-panel" class="photo-save-panel absolute inset-x-4 bottom-4 items-center justify-between gap-3 rounded-md bg-black/60 px-3 py-2">
                                <span class="text-xs font-semibold text-white">Preview foto baru</span>
                                <div class="flex gap-2">
                                    <button type="button" id="cancel-photo-button" class="rounded px-3 py-1 text-xs font-semibold text-white hover:bg-white/20">Batal</button>
                                    <button type="submit" class="rounded bg-white px-3 py-1 text-xs font-bold maroon-text hover:bg-gray-100">Simpan Foto</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Kolom kanan: Identitas --}}
                <div class="w-full h-full">
                    <div class="identity-card h-full rounded-2xl bg-white p-5 sm:p-6">
                        <div class="mb-5">
                            <h2 class="maroon-text font-bold text-xl">Identitas</h2>
                        </div>

                        <div class="space-y-0">
                            <div class="identity-row py-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Nama</p>
                                <p class="mt-2 text-lg font-bold text-gray-800">{{ $user->name ?: '-' }}</p>
                            </div>

                            <div class="identity-row py-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">NISN</p>
                                <p class="mt-2 text-lg font-bold text-gray-800">{{ $user->identitas ?: '-' }}</p>
                            </div>

                            <div class="identity-row py-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Kelas</p>
                                <p class="mt-2 text-lg font-bold text-gray-800">{{ $user->kelas ?: '-' }}</p>
                            </div>

                            <div class="pt-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-[#8C1F2F]/10">
                                        <i class="fa-solid fa-envelope text-lg maroon-text"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-gray-500">Alamat Email</p>
                                        <p class="mt-2 text-sm sm:text-base font-semibold text-gray-800 break-all">{{ $user->email ?: '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        const photoInput = document.getElementById('foto_profil');
        const photoPreview = document.getElementById('profile-photo-preview');
        const photoSavePanel = document.getElementById('photo-save-panel');
        const choosePhotoButton = document.getElementById('choose-photo-button');
        const cancelPhotoButton = document.getElementById('cancel-photo-button');
        const originalPhotoSource = photoPreview.src;

        choosePhotoButton.addEventListener('click', () => photoInput.click());

        photoInput.addEventListener('change', () => {
            const file = photoInput.files[0];
            if (!file) return;

            if (!['image/jpeg', 'image/png'].includes(file.type) || file.size > 2 * 1024 * 1024) {
                photoInput.value = '';
                photoPreview.src = originalPhotoSource;
                photoSavePanel.classList.remove('is-visible');
                alert('Pilih foto JPG, JPEG, atau PNG dengan ukuran maksimal 2 MB.');
                return;
            }

            photoPreview.src = URL.createObjectURL(file);
            photoSavePanel.classList.add('is-visible');
        });

        cancelPhotoButton.addEventListener('click', () => {
            photoInput.value = '';
            photoPreview.src = originalPhotoSource;
            photoSavePanel.classList.remove('is-visible');
        });
    </script>

</body>
</html>
