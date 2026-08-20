<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::view('/', 'welcome')->name('frontend.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/role/set/{id_role}', [DashboardController::class, 'changeRole'])->name('dashboard.change.role');
    Route::get('/forcelogout', [DashboardController::class, 'forceLogout'])->name('dashboard.force.logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route User (Ubah name jadi 'home')
    Route::get('/home', function () {
        return view('user.home');
    })->name('home');

    Route::get('/riwayat', function () {
        return view('user.riwayat');
    })->name('riwayat');

    Route::get('/peminjaman/konfirmasi', function (Request $request) {
        $item = [
            'id'       => $request->query('item_id'),
            'name'     => $request->query('item_name', 'Nama Barang'),
            'category' => $request->query('item_category', 'Kategori'),
            'stock'    => $request->query('item_stock', 'Tersedia'),
            'img'      => $request->query('item_img', asset('images/vacuum.jpg')),
        ];

        $tanggal = $request->query('tanggal', '03 Agustus 2026');
        $jam     = $request->query('jam', '11.00 - 12.00');

        return view('user.confirm', compact('item', 'tanggal', 'jam'));
    })->name('peminjaman.confirm');

    // 2. Route Simpan Peminjaman (Saat tombol MULAI MEMINJAM diklik)
    Route::post('/peminjaman/store', function (Request $request) {
        // Langsung redirect ke halaman riwayat
        return redirect()->route('riwayat');
    })->name('peminjaman.store');
 
});

require __DIR__ . '/auth.php';