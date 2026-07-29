# Laralag v2

Migrasi `laralagv1` (Laravel 10 + module generator custom) ke Laravel versi terbaru
(Laravel 13, PHP 8.3+). Dokumen ini merangkum apa yang **berbeda** dari v1 dan
keputusan yang diambil selama migrasi, supaya reviewer/junior programmer paham
konteksnya tanpa harus membaca ulang seluruh diff.

## Ringkasan versi

| | v1 | v2 |
|---|---|---|
| Laravel | 10 | 13 |
| PHP | ^8.1 | ^8.3 |
| Build tool frontend | Laravel Mix | Vite |
| Auth scaffolding | Custom (hand-rolled Auth controllers) | Laravel Breeze (stack Blade) |
| Form HTML | `laravelcollective/html` (`Form::text()`, dst) | Blade component `<x-dynamic-field>` |
| Media | `spatie/laravel-medialibrary` v10 | `spatie/laravel-medialibrary` v11 |
| Struktur "Modules" (`app/Modules/{Nama}/{Controllers,Models,Views}`) | dipertahankan | dipertahankan, tidak berubah |
| Primary key | UUID `string(36)` + kolom audit + soft delete | sama persis |

## Apa yang berubah secara arsitektural

### 1. Form facade → Blade component
`laravelcollective/html` sudah tidak didukung untuk Laravel versi baru, jadi
dihapus total (cek: tidak ada di `composer.json`, tidak ada pemakaian `Form::`
di manapun). Sebagai gantinya:

- Controller mengirim array data field polos ke view:
  ```php
  $data['forms'] = [
      'name' => ['label' => 'Name', 'type' => 'text', 'value' => old('name'), 'required' => true],
  ];
  ```
- View merender lewat komponen `<x-dynamic-field :name="$key" :field="$field" />`
  (lihat `resources/views/components/dynamic-field.blade.php`), yang mendukung
  tipe `text`, `textarea`, `select` (single & multiple), `password`, `hidden`,
  `number`.
- `@method('patch')` dan `@csrf` ditulis manual di form HTML biasa (tidak perlu
  `Form::open()/Form::close()`).

### 2. Auth: Breeze, bukan hand-rolled
`laravel/breeze` (stack Blade) di-install fresh, lalu ditambahkan logic custom
di atasnya: middleware `AuthorizeRequest`, listener `LogSuccessfullLogin`,
`DashboardController` (ganti role, force logout). Halaman login/register/reset
password/verify email tetap pakai Tailwind bawaan Breeze — sengaja **tidak**
disatukan gayanya dengan tema admin Bootstrap, sama seperti v1.

Karena Breeze menambahkan beberapa route yang tidak ada di v1 (`profile.*`),
route-route ini dimasukkan ke `module_exception` di `config/laralag.php` supaya
tidak diblokir middleware permission (v1 tidak punya masalah ini karena semua
routenya custom dan sudah diberi nama modul yang exempted).

### 3. Registrasi provider/middleware/event (perubahan skeleton Laravel baru)
Laravel 11+ menghapus `config/app.php` providers array, `app/Http/Kernel.php`,
dan `app/Providers/EventServiceProvider.php` dari skeleton default. Penyesuaian:
- `ModuleServiceProvider` didaftarkan di `bootstrap/providers.php`.
- Middleware `AuthorizeRequest` didaftarkan di `bootstrap/app.php` lewat
  `->withMiddleware(fn ($m) => $m->web(append: [...]))`.
- Event `Login` → `LogSuccessfullLogin` didaftarkan manual di
  `AppServiceProvider::boot()` pakai `Event::listen(...)`.

### 4. Base `Controller` perlu trait `ValidatesRequests` manual
Laravel 11+ menyederhanakan `App\Http\Controllers\Controller` jadi kelas
abstract kosong (dulu otomatis membawa `ValidatesRequests`/`AuthorizesRequests`).
Karena semua controller modul (hand-written maupun hasil `make:module`) memakai
`$this->validate(...)`, trait `ValidatesRequests` ditambahkan kembali secara
eksplisit di `app/Http/Controllers/Controller.php`.

### 5. Session serialization JSON (default baru Laravel)
Laravel versi baru menyimpan session sebagai JSON (bukan `serialize()` PHP)
demi keamanan (mencegah gadget-chain deserialization). Konsekuensinya, objek
Eloquent yang disimpan ke session (`session(['menus' => $menus])`) akan kembali
sebagai **array asosiatif**, bukan objek, saat dibaca ulang di request
berikutnya. `parts/sidebar.blade.php` di-sesuaikan supaya meng-cast tiap item
balik ke `(object)` sebelum dipakai dengan sintaks `->`. Tidak ada perubahan di
`Permission.php`/`DashboardController` karena keduanya sudah bekerja dengan
array biasa.

## Bug v1 yang ditemukan & diperbaiki saat porting

Beberapa hal berikut **bukan** keputusan desain baru, melainkan bug nyata di
v1 yang baru ketahuan saat alur di-uji end-to-end (v1 sendiri kemungkinan besar
juga akan gagal dengan cara yang sama jika dijalankan):

- `menu.parent_id` dibuat `nullable()` (v1: `NOT NULL`). `Menu::createByModule()`
  mengisi `parent_id` dari `config('laralag.dict.menu_referensi')` yang **tidak
  pernah didefinisikan** di `config/laralag.php` manapun (termasuk di v1) —
  sehingga `make:module --menu` akan selalu gagal insert kalau kolom ini wajib.
- Generator (`GenerateModule::buildClassModel` & `buildClassDetailView`) di v1
  menebak nama model relasi FK dari nama kolom (`id_jenis` → kelas `Jenis`),
  padahal tabel referensi sebenarnya bisa bernama lain (`jenis_file` →
  `JenisFile`). Di v2, kedua method ini memakai info referensi FK yang sudah
  terdeteksi oleh `getTableInfo()` (`information_schema.KEY_COLUMN_USAGE`),
  bukan menebak dari nama kolom.

## Yang di-skip

- **`--withApi`** pada `make:module`: di v1 opsi ini memanggil
  `Artisan::call('make:api ' . $module)`. Command `make:api` bawaan tidak ada
  secara default di instalasi Laravel yang dipakai untuk v2 (butuh
  `laravel/sanctum` + API scaffolding tambahan yang tidak termasuk permintaan
  fitur v1). Kode tetap mengecek ketersediaan command tersebut sebelum
  memanggilnya dan menampilkan warning jika tidak ada, alih-alih memaksakan
  command custom pengganti.

## Cara menjalankan

```bash
composer install
npm install && npm run build
cp .env.example .env   # sesuaikan DB_* ke database MySQL kosong
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

User default hasil seeder: `superadmin@mail.com` / `12345678` (role Super Admin
+ Admin).

## Membuat modul baru

```bash
php artisan make:module NamaModul --menu
```

Sama seperti v1: butuh tabel MySQL bernama `nama_modul` (snake_case) yang
sudah ada lebih dulu (generator membaca strukturnya lewat
`information_schema`). Command ini MySQL-only, sesuai keputusan arsitektur v1.
