{{--
    resources/views/user/home.blade.php
    "Take and Go" — Halaman Utama dengan Tampilan Peminjaman Full Screen
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take and Go</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .maroon { background-color: #8C1F2F; }
        .maroon-text { color: #8C1F2F; }
        .accent { background-color: #F4A825; }
        .scrollbar-none::-webkit-scrollbar { display: none; }
        .scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-white text-neutral-800">

    {{-- ================= HEADER / NAVBAR ================= --}}
    <header class="sticky top-0 z-30 bg-white/95 backdrop-blur border-b border-neutral-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-10 py-4 flex items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="font-extrabold text-xl tracking-tight shrink-0 text-neutral-900">
                TAKE AND GO
            </a>

            <div class="flex-1 flex items-center gap-3">
                <div class="flex-1 flex items-center gap-2 bg-neutral-100 rounded-full px-5 py-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-neutral-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="7"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input
                        type="text"
                        placeholder="pinjem apa yaa"
                        class="bg-transparent outline-none text-sm text-neutral-600 placeholder-neutral-400 w-full"
                    >
                </div>

                <a href="{{ route('riwayat') }}"
                   aria-label="Riwayat pencarian"
                   class="shrink-0 p-2.5 text-neutral-500 hover:text-neutral-800 bg-neutral-100 hover:bg-neutral-200 rounded-full transition inline-flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="9"></circle>
                        <polyline points="12 7 12 12 15 14"></polyline>
                    </svg>
                </a>
            </div>

            <button type="button" class="md:hidden shrink-0" aria-label="Menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-neutral-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 lg:px-10">

        {{-- ================= HERO BANNER ================= --}}
        <section class="mt-6">
            <div class="maroon relative rounded-3xl overflow-hidden px-8 lg:px-16 py-14 lg:py-20 min-h-[240px] lg:min-h-[320px] flex items-center justify-center">
                <div class="accent absolute -left-10 lg:left-0 -bottom-16 w-48 h-48 lg:w-64 lg:h-64 rounded-full z-0 pointer-events-none"></div>
                <div class="accent absolute -right-10 lg:right-4 -top-16 w-52 h-52 lg:w-72 lg:h-72 rounded-full z-0 pointer-events-none"></div>

                <img src="{{ asset('images/speaker.png') }}" alt="speaker" class="absolute left-0 lg:left-8 bottom-0 w-40 lg:w-64 h-40 lg:h-64 object-contain drop-shadow-2xl select-none pointer-events-none hidden sm:block">
                <img src="{{ asset('images/kamera.png') }}" alt="Kamera" class="absolute right-0 lg:right-8 top-0 w-44 lg:w-72 h-44 lg:h-72 object-contain drop-shadow-2xl select-none pointer-events-none hidden sm:block">

                <div class="relative z-10 text-center px-4">
                    <h2 class="text-white font-extrabold text-3xl lg:text-5xl leading-tight tracking-wide">
                        TAKE IT. USE IT.<br>RETURN IT.
                    </h2>
                </div>
            </div>
        </section>

        {{-- ================= FILTER KATEGORI ================= --}}
        <section class="mt-8">
            <div class="flex justify-center items-center gap-3 overflow-x-auto scrollbar-none pb-1">
                <a
                    href="{{ route('home') }}"
                    class="shrink-0 px-6 py-2.5 rounded-full text-sm font-medium whitespace-nowrap transition shadow-sm
                    {{ $activeCategory === 'All item' ? 'accent text-neutral-900 font-semibold' : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200' }}"
                >
                    All item
                </a>
                @foreach ($categories as $category)
                    <a
                        href="{{ route('home', ['category' => $category->nama_kategori]) }}"
                        class="shrink-0 px-6 py-2.5 rounded-full text-sm font-medium whitespace-nowrap transition shadow-sm
                        {{ $category->nama_kategori === $activeCategory
                            ? 'accent text-neutral-900 font-semibold'
                            : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200' }}"
                    >
                        {{ $category->nama_kategori }}
                    </a>
                @endforeach
            </div>
        </section>

        {{-- ================= GRID PRODUK ================= --}}
        <section class="mt-6 pb-14">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 lg:gap-5">
                @forelse ($items as $item)
                    @php
                        $image = $item->foto
                            ? asset('storage/'.$item->foto)
                            : 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 300%22%3E%3Crect width=%22400%22 height=%22300%22 fill=%22%23f5f5f5%22/%3E%3Ctext x=%22200%22 y=%22155%22 text-anchor=%22middle%22 fill=%22%23999999%22 font-family=%22Arial%22 font-size=%2220%22%3ENo%20photo%3C/text%3E%3C/svg%3E';
                    @endphp
                    <div class="group bg-white rounded-2xl border border-neutral-100 shadow-sm hover:shadow-md transition overflow-hidden flex flex-col">
                        <div class="aspect-[4/3] bg-neutral-100 overflow-hidden">
                            <img src="{{ $image }}" alt="{{ $item->nama_item }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </div>
                        <div class="p-3 flex flex-col gap-0.5">
                            <span class="text-[11px] text-neutral-400">{{ $item->category?->nama_kategori ?? 'Tanpa kategori' }}</span>
                            <h3 class="maroon-text font-semibold text-sm leading-snug line-clamp-2">
                                {{ $item->nama_item }}
                            </h3>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-[11px] text-neutral-400">{{ $item->stok_tersedia }} tersedia</span>
                                
                                {{-- Tombol Membuka Tampilan Full Screen --}}
                                <button
                                    type="button"
                                    onclick='openModalPinjam(@json($item->nama_item), @json($image))'
                                    @if ($item->stok_tersedia < 1) disabled @endif
                                    class="accent text-xs font-semibold text-neutral-900 px-4 py-1.5 rounded-full hover:brightness-95 transition"
                                >
                                    Pinjam
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="col-span-full text-center text-sm text-neutral-400 py-10">Belum ada item tersedia.</p>
                @endforelse
            </div>
        </section>

    </main>

    {{-- ================= TAMPILAN JADWAL FULL SCREEN ================= --}}
    <div id="modalPinjam" class="fixed inset-0 bg-white hidden z-50 overflow-y-auto">
        <div class="w-full min-h-screen bg-white p-6 lg:p-12 flex flex-col justify-between max-w-6xl mx-auto">
            
            <div>
                {{-- Header --}}
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-neutral-100">
                    <button onclick="closeModalPinjam()" type="button" class="text-neutral-400 hover:text-neutral-800 transition p-2 rounded-full hover:bg-neutral-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <h2 class="maroon-text font-black text-2xl lg:text-3xl uppercase tracking-wide text-center">
                        PILIH JADWAL PEMINJAMAN
                    </h2>

                    <div class="w-9"></div>
                </div>

                {{-- Slider Tanggal --}}
                <div class="flex items-center gap-3 overflow-x-auto scrollbar-none mb-10 pb-2 justify-start md:justify-center">
                    <button type="button" class="bg-red-600 text-white px-6 py-3 rounded-2xl text-center shrink-0 shadow-md">
                        <div class="text-xs font-medium uppercase opacity-90">Jum</div>
                        <div class="text-sm font-bold">31 JULI</div>
                    </button>
                    <button type="button" class="bg-neutral-100 text-neutral-700 hover:bg-neutral-200 px-6 py-3 rounded-2xl text-center shrink-0 transition">
                        <div class="text-xs text-neutral-400 font-medium uppercase">Sen</div>
                        <div class="text-sm font-bold">3 AGS</div>
                    </button>
                    <button type="button" class="bg-neutral-100 text-neutral-700 hover:bg-neutral-200 px-6 py-3 rounded-2xl text-center shrink-0 transition">
                        <div class="text-xs text-neutral-400 font-medium uppercase">Sel</div>
                        <div class="text-sm font-bold">4 AGS</div>
                    </button>
                    <button type="button" class="bg-neutral-100 text-neutral-700 hover:bg-neutral-200 px-6 py-3 rounded-2xl text-center shrink-0 transition">
                        <div class="text-xs text-neutral-400 font-medium uppercase">Rab</div>
                        <div class="text-sm font-bold">5 AGS</div>
                    </button>
                    <button type="button" class="bg-neutral-100 text-neutral-700 hover:bg-neutral-200 px-6 py-3 rounded-2xl text-center shrink-0 transition">
                        <div class="text-xs text-neutral-400 font-medium uppercase">Kam</div>
                        <div class="text-sm font-bold">6 AGS</div>
                    </button>
                    <button type="button" class="bg-neutral-100 text-neutral-700 hover:bg-neutral-200 px-6 py-3 rounded-2xl text-center shrink-0 transition">
                        <div class="text-xs text-neutral-400 font-medium uppercase">Jum</div>
                        <div class="text-sm font-bold">7 AGS</div>
                    </button>
                </div>

                {{-- Body Content --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 items-center">
                    <div class="bg-neutral-100 rounded-3xl overflow-hidden aspect-[4/3] flex items-center justify-center max-h-[400px] w-full shadow-inner">
                        <img id="modalGambarBarang" src="" alt="Produk" class="w-full h-full object-cover">
                    </div>

                    <div>
                        <div class="accent text-neutral-900 font-bold text-center py-3.5 rounded-2xl text-sm uppercase mb-6 tracking-wide shadow-sm">
                            JADWAL YANG TERSEDIA
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-center">
                            <button type="button" class="border-2 border-neutral-200 hover:border-amber-500 rounded-2xl p-4 transition group">
                                <span class="block text-xs text-neutral-400 font-medium mb-1">60 Menit</span>
                                <span class="block text-base font-bold text-neutral-800 group-hover:text-amber-600">08.00 - 09.00</span>
                            </button>
                            <button type="button" class="border-2 border-neutral-200 hover:border-amber-500 rounded-2xl p-4 transition group">
                                <span class="block text-xs text-neutral-400 font-medium mb-1">60 Menit</span>
                                <span class="block text-base font-bold text-neutral-800 group-hover:text-amber-600">09.00 - 10.00</span>
                            </button>
                            <button type="button" class="border-2 border-neutral-200 hover:border-amber-500 rounded-2xl p-4 transition group">
                                <span class="block text-xs text-neutral-400 font-medium mb-1">60 Menit</span>
                                <span class="block text-base font-bold text-neutral-800 group-hover:text-amber-600">10.00 - 11.00</span>
                            </button>
                            <button type="button" class="border-2 border-neutral-200 hover:border-amber-500 rounded-2xl p-4 transition group">
                                <span class="block text-xs text-neutral-400 font-medium mb-1">60 Menit</span>
                                <span class="block text-base font-bold text-neutral-800 group-hover:text-amber-600">11.00 - 12.00</span>
                            </button>
                            <button type="button" class="border-2 border-neutral-200 hover:border-amber-500 rounded-2xl p-4 transition group">
                                <span class="block text-xs text-neutral-400 font-medium mb-1">60 Menit</span>
                                <span class="block text-base font-bold text-neutral-800 group-hover:text-amber-600">12.00 - 13.00</span>
                            </button>
                            <button type="button" class="border-2 border-neutral-200 hover:border-amber-500 rounded-2xl p-4 transition group">
                                <span class="block text-xs text-neutral-400 font-medium mb-1">60 Menit</span>
                                <span class="block text-base font-bold text-neutral-800 group-hover:text-amber-600">13.00 - 14.00</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tombol Booking --}}
            <div class="mt-10">
                <button type="button" class="accent text-neutral-900 font-extrabold w-full py-4 rounded-2xl text-center uppercase tracking-wider text-base hover:brightness-95 transition shadow-lg">
                    BOOKING SEKARANG
                </button>
            </div>
        </div>
    </div>

    {{-- ================= FOOTER BANNER ================= --}}
    <footer class="maroon py-5">
        <p class="text-center text-white font-bold text-base tracking-wide">
            TAKE IT. USE IT. RETURN IT.
        </p>
    </footer>

    {{-- ================= JAVASCRIPT ================= --}}
    <script>
        function openModalPinjam(nama, gambar) {
            const modal = document.getElementById('modalPinjam');
            const imgElement = document.getElementById('modalGambarBarang');
            
            imgElement.src = gambar;
            imgElement.alt = nama;

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeModalPinjam() {
            const modal = document.getElementById('modalPinjam');
            
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('modalPinjam').classList.contains('hidden')) {
                closeModalPinjam();
            }
        });
    </script>
</body>
</html>