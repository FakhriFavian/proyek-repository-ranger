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
        .maroon { background-color: #9c3b3b; }
        .maroon-text { color: #9c3b3b; }
        .cream { background-color: #fbe9e2; }
        .photo-save-panel { display: none; }
        .photo-save-panel.is-visible { display: flex; }
    </style>
</head>
<body class="bg-white min-h-screen">

    <!-- {{-- Top bar hitam --}}
    <div class="px-8 py-3">
        <p class="text-gray-400 text-xs tracking-widest">PROFIL PENGGUNA</p>
    </div> -->

    {{-- Header maroon --}}
    <div class="maroon px-8 py-4 flex items-center justify-between">
        {{-- Logo --}}
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-cube text-white text-2xl"></i>
            <span class="text-white font-extrabold text-lg tracking-wide">
                TAKE AND GO
            </span>
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
    <div class="bg-white p-6 md:p-10">
        <div class="cream rounded-md p-6 md:p-10 relative">

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

            {{-- Judul profil --}}
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Profil Pengguna</h1>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

                {{-- Kolom kiri: Foto profil --}}
                <div>
                    <div class="maroon rounded-md p-8 flex items-center justify-center relative h-64">
                            <img src="{{ $profileImage }}"
                                id="profile-photo-preview"
                                alt="Foto profil {{ $user->name }}"
                             class="w-44 h-44 rounded-full object-cover border-4 border-white/20">

                        <form action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data" id="profile-photo-form">
                            @csrf
                            <input type="file" name="foto_profil" id="foto_profil" accept=".jpg,.jpeg,.png,image/jpeg,image/png" class="hidden">
                            <button type="button" id="choose-photo-button" title="Ganti Foto Profil" class="absolute top-4 right-4 w-9 h-9 bg-white rounded-full flex items-center justify-center shadow hover:bg-gray-100">
                                <i class="fa-solid fa-camera maroon-text"></i>
                            </button>
                            <div id="photo-save-panel" class="photo-save-panel absolute inset-x-4 bottom-4 items-center justify-between gap-3 rounded-md bg-black/60 px-3 py-2">
                                <span class="text-xs font-semibold text-white">Preview foto baru</span>
                                <div class="flex gap-2">
                                    <button type="button" id="cancel-photo-button" class="rounded px-3 py-1 text-xs font-semibold text-white hover:bg-white/20">Batal</button>
                                    <button type="submit" class="rounded bg-white px-3 py-1 text-xs font-bold maroon-text hover:bg-gray-100">Simpan Foto</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- NIS --}}
                    <div class="flex items-center gap-3 mt-6">
                        <i class="fa-solid fa-address-card text-lg"></i>
                        <span class="font-semibold text-gray-800">{{ $user->identitas ?: '-' }}</span>
                    </div>

                    {{-- Kelas --}}
                    <div class="flex items-center gap-3 mt-4">
                        <i class="fa-solid fa-graduation-cap text-lg"></i>
                        <span class="font-semibold text-gray-800">{{ $user->kelas ?: '-' }}</span>
                    </div>
                </div>

                {{-- Kolom kanan: Identitas & Kontak --}}
                <div>
                    {{-- Identitas --}}
                    <div class="mb-8">
                        <h2 class="maroon-text font-bold text-lg mb-1">Identitas</h2>
                        <hr class="border-gray-300 mb-4">

                        <p class="text-gray-400 text-sm">Nama</p>
                        <p class="font-bold text-gray-800 mb-4">{{ $user->name ?: '-' }}</p>

                        <p class="text-gray-400 text-sm">Jenis kelamin</p>
                        <p class="font-bold text-gray-800">{{ $user->jenis_kelamin ?: '-' }}</p>
                    </div>

                    {{-- Kontak --}}
                    <div>
                        <h2 class="maroon-text font-bold text-lg mb-1">Kontak</h2>
                        <hr class="border-gray-300 mb-4">

                        <div class="flex items-start gap-3 mb-4">
                            <i class="fa-solid fa-envelope mt-1"></i>
                            <div>
                                <p class="text-gray-400 text-sm">Alamat Email</p>
                                <p class="font-bold text-gray-800 break-all">{{ $user->email ?: '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-phone mt-1"></i>
                            <div>
                                <p class="text-gray-400 text-sm">No Telp</p>
                                <p class="font-bold text-gray-800">{{ $user->nomor_telepon ?: '-' }}</p>
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