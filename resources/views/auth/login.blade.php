Sekarang kondisi project sudah normal kembali. JANGAN mengubah atau merusak login admin/petugas bawaan template guru.

Saya ingin menambahkan LOGIN KHUSUS SISWA yang TERPISAH dari login admin.

Aturan WAJIB:

1. Route `/login` milik admin/petugas HARUS tetap persis seperti template guru.
   - Jangan ubah LoginRequest bawaan.
   - Jangan ubah AuthenticatedSessionController.
   - Jangan ubah UI login guru.
   - Jangan mengubah guard `web`.

2. Buat login siswa TERPISAH dengan route:
   GET  /user/login
   POST /user/login

3. UI login siswa nanti akan saya berikan sendiri.
   Untuk sekarang buat logic/backend-nya saja.
   Jangan membuat ulang desain login.

4. Login siswa menggunakan:
   - NIS/NISN dari kolom `users.identitas`
   - password dari kolom `users.password`

5. Setelah siswa berhasil login, ambil data siswa dari database:
   - nama
   - kelas
   - identitas/NIS

6. Saat siswa menekan tombol "Pinjam" dari Home:
   - jika belum login siswa → arahkan ke `/user/login`
   - simpan item yang ingin dipinjam agar setelah login siswa kembali ke item tersebut
   - JANGAN kembali ke Home dan kehilangan item yang dipilih.

7. Setelah login siswa berhasil:
   `/user/login` → kembali ke halaman konfirmasi peminjaman item yang sebelumnya dipilih.

8. Di halaman konfirmasi tampilkan:
   - Nama siswa
   - NIS
   - Kelas
   - Item yang dipilih
   - Jumlah/stok yang ingin dipinjam
   - informasi peminjaman lainnya

9. Nama, NIS, dan kelas HARUS berasal dari database berdasarkan user yang login.
   Jangan menerima nama/kelas dari input browser.

10. Jangan mengubah workflow Borrowing, Borrowing Details, atau BorrowingStockService yang sudah ada kecuali benar-benar diperlukan untuk menghubungkan user login siswa.

11. Riwayat siswa nantinya harus menampilkan peminjaman berdasarkan siswa yang sedang login.

12. JANGAN melakukan rollback, migration rollback, delete database, atau menghapus data.

13. SEBELUM mengubah file:
   - audit dulu route
   - audit middleware
   - audit User model
   - audit struktur role
   - audit controller peminjaman
   - audit tombol Pinjam di Home
   - audit route konfirmasi dan riwayat

14. Setelah audit, tampilkan kepada saya:
   - file yang akan diubah
   - file yang tidak akan disentuh
   - alur login siswa
   - cara item yang dipilih dipertahankan setelah login

Jangan langsung coding sebelum audit selesai.<x-guest-layout>
    <p class="kt-eyebrow mb-1">Welcome back</p>
    <h2 class="fw-bold mb-1">Masuk ke akun Anda</h2>
    <p class="text-muted mb-4">Masukkan kredensial Anda untuk mengakses dashboard.</p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="form-check mb-3">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label">{{ __('Remember me') }}</label>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-4">
            @if (Route::has('password.request'))
                <a class="text-decoration-none small" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-auto">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>