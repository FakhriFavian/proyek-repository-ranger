{{--
    resources/views/riwayat.blade.php
    "Take and Go" — Halaman Riwayat Peminjaman (Pagination Support)
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman - Take and Go</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-neutral-800 min-h-screen pb-12">

    {{-- ================= HEADER ================= --}}
    <header class="bg-white border-b border-neutral-200 py-4 px-6 mb-8">
        <div class="max-w-5xl mx-auto flex items-center gap-4">
           <a href="{{ route('user.home') }}" class="w-8 h-8 rounded-full border border-neutral-300 flex items-center justify-center text-neutral-600 hover:bg-neutral-100 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="font-extrabold text-xl tracking-wider text-neutral-800 uppercase">TAKE AND GO</h1>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6">

        {{-- ================= RINGKASAN STATISTIK ================= --}}
        <div class="bg-white rounded-2xl border border-amber-200 shadow-sm p-4 sm:p-5 mb-8">
            <div class="grid grid-cols-2 sm:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-neutral-200 gap-y-4 sm:gap-y-0">

                {{-- Total Peminjaman --}}
                <div class="flex items-center justify-center gap-3 px-2">
                    <div class="w-10 h-10 rounded-xl bg-cyan-100 flex items-center justify-center text-cyan-700 shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium">Total peminjaman</p>
                        <p class="text-sm font-bold text-slate-700">2 kali</p>
                    </div>
                </div>

                {{-- Disetujui --}}
                <div class="flex items-center justify-center gap-3 px-2 pt-3 sm:pt-0">
                    <div class="text-center">
                        <p class="text-xs text-slate-400 font-medium">Disetujui</p>
                        <p class="text-base font-extrabold text-emerald-500">2</p>
                    </div>
                </div>

                {{-- Ditolak --}}
                <div class="flex items-center justify-center gap-3 px-2 pt-3 sm:pt-0">
                    <div class="text-center">
                        <p class="text-xs text-slate-400 font-medium">Ditolak</p>
                        <p class="text-base font-extrabold text-rose-500">0</p>
                    </div>
                </div>

                {{-- Dikembalikan --}}
                <div class="flex items-center justify-center gap-3 px-2 pt-3 sm:pt-0">
                    <div class="text-center">
                        <p class="text-xs text-slate-400 font-medium">Dikembalikan</p>
                        <p class="text-base font-extrabold text-slate-700">2</p>
                    </div>
                </div>

            </div>
        </div>

        {{-- ================= HEADER TABEL / COLUMNS ================= --}}
        <div class="hidden md:grid grid-cols-12 px-6 mb-3 text-sm font-semibold text-slate-400 text-center">
            <div class="col-span-2 text-left">Waktu</div>
            <div class="col-span-3 text-left pl-6">Status</div>
            <div class="col-span-4 text-left">Keterangan</div>
            <div class="col-span-3 text-right">Barang</div>
        </div>

        {{-- Logika Penanganan Halaman (Page 1 vs Page 2) --}}
        @php
            $currentPage = request('page', 1);

            $page1 = [
                ['date' => '30 Agu 2026', 'time' => '9.00', 'status' => 'Booking', 'status_bg' => 'bg-cyan-100 text-cyan-600', 'icon_bg' => 'bg-cyan-100 text-cyan-500', 'icon' => 'clipboard', 'title' => 'Permintaan peminjaman dibuat', 'desc' => 'Permintaan peminjaman berhasil dibuat', 'item' => 'Cordless Vacuum Cleaner', 'category' => 'Cleaning', 'cat_bg' => 'bg-cyan-100 text-cyan-600'],
                ['date' => '30 Agu 2026', 'time' => '9.00', 'status' => 'Ditinjau', 'status_bg' => 'bg-amber-100 text-amber-600', 'icon_bg' => 'bg-amber-100 text-amber-500', 'icon' => 'search', 'title' => 'Sedang ditinjau', 'desc' => 'Admin sedang memeriksa permintaan peminjaman anda', 'item' => 'Cordless Vacuum Cleaner', 'category' => 'Cleaning', 'cat_bg' => 'bg-cyan-100 text-cyan-600'],
                ['date' => '30 Agu 2026', 'time' => '9.00', 'status' => 'Disetujui', 'status_bg' => 'bg-emerald-100 text-emerald-600', 'icon_bg' => 'bg-emerald-100 text-emerald-500', 'icon' => 'check', 'title' => 'Permintaan disetujui', 'desc' => 'Permintaan peminjaman telah disetujui oleh admin', 'item' => 'Cordless Vacuum Cleaner', 'category' => 'Cleaning', 'cat_bg' => 'bg-cyan-100 text-cyan-600'],
                ['date' => '30 Agu 2026', 'time' => '9.00', 'status' => 'Dipinjam', 'status_bg' => 'bg-purple-100 text-purple-600', 'icon_bg' => 'bg-purple-100 text-purple-500', 'icon' => 'bag', 'title' => 'Barang dipinjam', 'desc' => 'Barang telah diambil oleh peminjam', 'item' => 'Cordless Vacuum Cleaner', 'category' => 'Cleaning', 'cat_bg' => 'bg-cyan-100 text-cyan-600'],
                ['date' => '30 Agu 2026', 'time' => '9.00', 'status' => 'Dikembalikan', 'status_bg' => 'bg-slate-200 text-slate-600', 'icon_bg' => 'bg-slate-200 text-slate-500', 'icon' => 'download', 'title' => 'Barang dikembalikan', 'desc' => 'Barang telah dikembalikan dalam kondisi baik.', 'item' => 'Cordless Vacuum Cleaner', 'category' => 'Cleaning', 'cat_bg' => 'bg-cyan-100 text-cyan-600'],
                ['date' => '30 Agu 2026', 'time' => '9.00', 'status' => 'Selesai', 'status_bg' => 'bg-lime-100 text-lime-700', 'icon_bg' => 'bg-lime-100 text-lime-600', 'icon' => 'check-double', 'title' => 'Peminjaman Selesai', 'desc' => 'Proses peminjaman telah selesai', 'item' => 'Cordless Vacuum Cleaner', 'category' => 'Cleaning', 'cat_bg' => 'bg-cyan-100 text-cyan-600'],
            ];

            $page2 = [
                ['date' => '3 Sep 2026', 'time' => '13.00', 'status' => 'Booking', 'status_bg' => 'bg-cyan-100 text-cyan-600', 'icon_bg' => 'bg-cyan-100 text-cyan-500', 'icon' => 'clipboard', 'title' => 'Permintaan peminjaman dibuat', 'desc' => 'Permintaan peminjaman berhasil dibuat', 'item' => 'JBL speaker blends', 'category' => 'Elektronik', 'cat_bg' => 'bg-pink-100 text-pink-600'],
                ['date' => '3 Sep 2026', 'time' => '13.00', 'status' => 'Ditinjau', 'status_bg' => 'bg-amber-100 text-amber-600', 'icon_bg' => 'bg-amber-100 text-amber-500', 'icon' => 'search', 'title' => 'Sedang ditinjau', 'desc' => 'Admin sedang memeriksa permintaan peminjaman anda', 'item' => 'JBL speaker blends', 'category' => 'Elektronik', 'cat_bg' => 'bg-pink-100 text-pink-600'],
                ['date' => '3 Sep 2026', 'time' => '13.00', 'status' => 'Disetujui', 'status_bg' => 'bg-emerald-100 text-emerald-600', 'icon_bg' => 'bg-emerald-100 text-emerald-500', 'icon' => 'check', 'title' => 'Permintaan disetujui', 'desc' => 'Permintaan peminjaman telah disetujui oleh admin', 'item' => 'JBL speaker blends', 'category' => 'Elektronik', 'cat_bg' => 'bg-pink-100 text-pink-600'],
                ['date' => '30 Agu 2026', 'time' => '13.00', 'status' => 'Dipinjam', 'status_bg' => 'bg-purple-100 text-purple-600', 'icon_bg' => 'bg-purple-100 text-purple-500', 'icon' => 'bag', 'title' => 'Barang dipinjam', 'desc' => 'Barang telah diambil oleh peminjam', 'item' => 'JBL speaker blends', 'category' => 'Elektronik', 'cat_bg' => 'bg-pink-100 text-pink-600'],
                ['date' => '3 Sep 2026', 'time' => '13.00', 'status' => 'Dikembalikan', 'status_bg' => 'bg-slate-200 text-slate-600', 'icon_bg' => 'bg-slate-200 text-slate-500', 'icon' => 'download', 'title' => 'Barang dikembalikan', 'desc' => 'Barang telah dikembalikan dalam kondisi baik.', 'item' => 'JBL speaker blends', 'category' => 'Elektronik', 'cat_bg' => 'bg-pink-100 text-pink-600'],
                ['date' => '3 Sep 2026', 'time' => '13.00', 'status' => 'Selesai', 'status_bg' => 'bg-lime-100 text-lime-700', 'icon_bg' => 'bg-lime-100 text-lime-600', 'icon' => 'check-double', 'title' => 'Peminjaman Selesai', 'desc' => 'Proses peminjaman telah selesai', 'item' => 'JBL speaker blends', 'category' => 'Elektronik', 'cat_bg' => 'bg-pink-100 text-pink-600'],
            ];

            $timeline = ($currentPage == 2) ? $page2 : $page1;
        @endphp

        {{-- ================= TIMELINE CONTAINER ================= --}}
        <div class="relative space-y-4">

            {{-- Garis Putus-putus Vertical Timeline --}}
            <div class="hidden md:block absolute left-[21.5%] top-6 bottom-6 w-0 border-l-2 border-dashed border-slate-200 z-0"></div>

            @foreach ($timeline as $step)
                <div class="relative z-10 bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-sm hover:shadow-md transition">
                    <div class="grid grid-cols-1 md:grid-cols-12 items-center gap-4">

                        {{-- Waktu --}}
                        <div class="md:col-span-2">
                            <p class="font-bold text-slate-800 text-sm sm:text-base">{{ $step['date'] }}</p>
                            <p class="text-xs font-semibold text-slate-400 mt-0.5">{{ $step['time'] }}</p>
                        </div>

                        {{-- Status Badge + Icon Timeline --}}
                        <div class="md:col-span-3 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full {{ $step['icon_bg'] }} flex items-center justify-center shrink-0">
                                @if ($step['icon'] === 'clipboard')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                @elseif ($step['icon'] === 'search')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                @elseif ($step['icon'] === 'check' || $step['icon'] === 'check-double')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                @elseif ($step['icon'] === 'bag')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                @elseif ($step['icon'] === 'download')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                @endif
                            </div>

                            <span class="px-5 py-1.5 rounded-full text-xs font-semibold {{ $step['status_bg'] }}">
                                {{ $step['status'] }}
                            </span>
                        </div>

                        {{-- Keterangan --}}
                        <div class="md:col-span-4">
                            <h4 class="font-bold text-slate-800 text-sm">{{ $step['title'] }}</h4>
                            <p class="text-[11px] text-slate-400 font-medium leading-relaxed mt-0.5">{{ $step['desc'] }}</p>
                        </div>

                        {{-- Barang & Kategori --}}
                        <div class="md:col-span-3 md:text-right border-t md:border-t-0 md:border-l border-slate-100 pt-3 md:pt-0 md:pl-4">
                            <p class="font-bold text-slate-800 text-xs sm:text-sm">{{ $step['item'] }}</p>
                            <span class="inline-block mt-1 px-3 py-0.5 rounded-full {{ $step['cat_bg'] }} text-[10px] font-semibold">
                                {{ $step['category'] }}
                            </span>
                        </div>

                    </div>
                </div>
            @endforeach

        </div>

        {{-- ================= TOMBOL NAVIGASI / PAGINATION ================= --}}
        <div class="flex justify-end gap-2 mt-6">
            {{-- Panah Kiri (Halaman Sebelumnya) --}}
            <a
                href="{{ url('/riwayat?page=1') }}"
                class="w-10 h-10 rounded-xl flex items-center justify-center transition shadow-sm
                {{ $currentPage == 1 ? 'bg-slate-200 text-slate-400 cursor-not-allowed pointer-events-none' : 'bg-slate-200 hover:bg-slate-300 text-slate-700' }}"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>

            {{-- Panah Kanan (Halaman Selanjutnya) --}}
            <a
                href="{{ url('/riwayat?page=2') }}"
                class="w-10 h-10 rounded-xl flex items-center justify-center transition shadow-sm
                {{ $currentPage == 2 ? 'bg-slate-200 text-slate-400 cursor-not-allowed pointer-events-none' : 'bg-slate-200 hover:bg-slate-300 text-slate-700' }}"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>

    </main>

</body>
</html>
