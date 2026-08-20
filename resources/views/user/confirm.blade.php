<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAKE AND GO - Detail Peminjaman</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .text-maroon { color: #8B2635; }
        .bg-orange-accent { background: linear-gradient(180deg, #FFAA2C 0%, #FF9900 100%); }
    </style>
</head>
<body class="bg-[#F8F9FA] min-h-screen py-6 px-4 sm:px-8">

    <div class="max-w-6xl mx-auto space-y-6">
        
        {{-- Header Navigation --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('home') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-neutral-800 shadow-sm border border-neutral-200/60 hover:bg-neutral-50 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="font-extrabold text-2xl sm:text-3xl text-neutral-900 tracking-wider">TAKE AND GO</h1>
        </div>

        <form action="{{ route('peminjaman.store') }}" method="POST">
            @csrf
            
            <input type="hidden" name="item_id" value="{{ $item['id'] ?? '' }}">
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">
            <input type="hidden" name="jam" value="{{ $jam }}">

            {{-- Card Grid Wrapper --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
                
                {{-- Kiri: Detail & Jadwal Peminjaman (8 cols) --}}
                <div class="lg:col-span-8 bg-white rounded-3xl p-6 sm:p-8 shadow-[0_4px_25px_rgba(0,0,0,0.05)] flex flex-col justify-between space-y-6">
                    <div>
                        <h2 class="text-maroon font-extrabold text-2xl sm:text-3xl uppercase tracking-tight mb-6">
                            DETAIL & KONFIRMASI PEMINJAMAN
                        </h2>

                        <div class="flex flex-col sm:flex-row gap-6 items-start">
                            {{-- Gambar Barang --}}
                            <div class="w-full sm:w-72 h-44 bg-neutral-100 rounded-2xl overflow-hidden shrink-0">
                                <img src="{{ $item['img'] ?? asset('images/vacuum.jpg') }}" alt="{{ $item['name'] ?? 'Barang' }}" class="w-full h-full object-cover">
                            </div>

                            {{-- Info Barang --}}
                            <div class="space-y-2">
                                <span class="text-sm font-semibold text-slate-400 block">{{ $item['category'] ?? 'Cleaning' }}</span>
                                <h3 class="font-extrabold text-2xl sm:text-3xl text-neutral-900 leading-tight">
                                    {{ $item['name'] ?? 'Cordless Vacuum Cleaner' }}
                                </h3>
                                
                                <div class="flex items-center gap-3 pt-2">
                                    <span class="bg-[#34C759] text-white text-sm font-bold px-4 py-1.5 rounded-full flex items-center gap-1.5 shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Tersedia
                                    </span>
                                    <span class="bg-[#FF9F0A] text-white text-sm font-bold px-4 py-1.5 rounded-full shadow-sm">
                                        {{ $item['stock'] ?? '9/12' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Jadwal Peminjaman Box --}}
                    <div>
                        <h4 class="font-bold text-base text-neutral-900 mb-3">Jadwal Peminjaman</h4>
                        <div class="border border-neutral-300/80 rounded-2xl p-4 grid grid-cols-1 sm:grid-cols-2 divide-y sm:divide-y-0 sm:divide-x divide-neutral-200">
                            <div class="flex items-center gap-4 pb-3 sm:pb-0 sm:pr-4">
                                <div class="text-neutral-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-xs text-neutral-800 font-bold block">Tanggal Peminjaman</span>
                                    <span class="text-red-600 font-extrabold text-sm block mt-0.5">{{ $tanggal }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 pt-3 sm:pt-0 sm:pl-6">
                                <div class="text-neutral-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-xs text-neutral-800 font-bold block">Waktu Peminjaman</span>
                                    <span class="text-red-600 font-extrabold text-sm block mt-0.5">{{ $jam }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kanan: Ringkasan Peminjaman (4 cols) --}}
                <div class="lg:col-span-4 bg-white rounded-3xl p-6 shadow-[0_4px_25px_rgba(0,0,0,0.05)] flex flex-col justify-between">
                    <div class="space-y-4">
                        <h3 class="font-extrabold text-base text-neutral-900 uppercase tracking-wide border-b border-neutral-100 pb-2">
                            RINGKASAN PEMINJAMAN
                        </h3>

                        <div class="space-y-3 text-xs sm:text-sm">
                            <div>
                                <span class="text-slate-400 block font-medium">Barang</span>
                                <span class="font-extrabold text-neutral-900">{{ $item['name'] ?? 'Cordless Vacuum Cleaner' }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block font-medium">Kategori</span>
                                <span class="font-bold text-neutral-800">{{ $item['category'] ?? 'Cleaning' }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block font-medium">Tanggal</span>
                                <span class="font-bold text-neutral-800">{{ $tanggal }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block font-medium">Waktu</span>
                                <span class="font-bold text-neutral-800">{{ $jam }} (1 Jam)</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block font-medium">Ketersediaan</span>
                                <span class="text-[#34C759] font-extrabold flex items-center gap-1.5 mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Tersedia
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- Informasi Box --}}
                    <div class="bg-[#FFF3E0] rounded-2xl p-3.5 flex items-start gap-2.5 border border-amber-100/80 mt-6">
                        <div class="w-4 h-4 rounded-full bg-[#FF9F0A] text-white flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">i</div>
                        <div>
                            <h5 class="font-extrabold text-xs text-neutral-900">Informasi</h5>
                            <p class="text-[11px] text-neutral-700 leading-tight mt-0.5">Pastikan jadwal sudah sesuai sebelum mengkonfirmasi peminjaman.</p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Tombol Mulai Meminjam Full Width --}}
            <div class="mt-6">
                <button type="submit" class="bg-orange-accent text-white font-extrabold w-full py-4 rounded-2xl text-center uppercase tracking-wider text-base hover:brightness-95 transition shadow-[0_4px_20px_rgba(255,153,0,0.35)]">
                    MULAI MEMINJAM
                </button>
            </div>
        </form>

    </div>

</body>
</html>