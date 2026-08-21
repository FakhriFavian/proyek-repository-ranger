<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Modules\Items\Models\Items;
use App\Modules\borrowings\Models\borrowings;
use App\Modules\categories\Models\categories;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
        $activeCategory = request('category', 'All item');
        $categories = categories::where('is_active', 1)->orderBy('nama_kategori')->get();
        $items = Items::with('category')
            ->where('is_active', 1)
            ->when($activeCategory !== 'All item', function ($query) use ($activeCategory) {
                $query->whereHas('category', function ($categoryQuery) use ($activeCategory) {
                    $categoryQuery->where('nama_kategori', $activeCategory);
                });
            })
            ->orderBy('nama_item')
            ->get();
        $borrowings = borrowings::with('details.item')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.home', compact('categories', 'items', 'borrowings', 'activeCategory'));
    })->name('home');

    Route::get('/riwayat', function () {
        return view('user.riwayat');
    })->name('riwayat');
});

require __DIR__ . '/auth.php';