{{--
    resources/views/user/home.blade.php
    "Take and Go" — Halaman Utama dengan Tampilan Pop-up Modal Peminjaman
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
                {{-- Form Search --}}
                <form action="{{ route('home') }}" method="GET" class="flex-1 flex items-center gap-2 bg-neutral-100 rounded-full px-5 py-2.5">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-neutral-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="7"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="pinjem apa yaa"
                        class="bg-transparent outline-none text-sm text-neutral-600 placeholder-neutral-400 w-full"
                    >
                </form>

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
            @php
                $categories = ['All item', 'Sports', 'Laboratorium', 'Electronics', 'Cleaning'];
            @endphp

            <div class="flex justify-center items-center gap-3 overflow-x-auto scrollbar-none pb-1">
                @foreach ($categories as $category)
                    <a
                        href="/home?category={{ urlencode($category) }}{{ request('search') ? '&search='.urlencode(request('search')) : '' }}"
                        class="shrink-0 px-6 py-2.5 rounded-full text-sm font-medium whitespace-nowrap transition shadow-sm
                        {{ $category === request('category', 'All item')
                            ? 'accent text-neutral-900 font-semibold'
                            : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200' }}"
                    >
                        {{ $category }}
                    </a>
                @endforeach
            </div>
        </section>

        {{-- ================= GRID PRODUK ================= --}}
        <section class="mt-6 pb-14">
            @php
                $products = [
                    ['category' => 'Electronics', 'name' => 'JBL speaker blends', 'stock' => '67 tersedia', 'img' => asset('images/jblspeaker.jpg')],
                    ['category' => 'Electronics', 'name' => 'Sony headphones', 'stock' => '21 tersedia', 'img' => asset('images/headphone.jpg')],
                    ['category' => 'Electronics', 'name' => 'iPhone 17 Pro Max', 'stock' => '32 tersedia', 'img' => asset('images/iphone.jpg')],
                    ['category' => 'Electronics', 'name' => 'Samsung galaxy S25 Ultra', 'stock' => '7 tersedia', 'img' => asset('images/samsung.jpg')],
                    ['category' => 'Cleaning', 'name' => 'Cordless Vacuum Cleaner', 'stock' => '9 tersedia', 'img' => asset('images/vacuum.jpg')],
                    ['category' => 'Sports', 'name' => 'Real Madrid Home Jersey', 'stock' => '20 tersedia', 'img' => asset('images/jersey.jpg')],
                    ['category' => 'Sports', 'name' => 'Adidas Tiro Pro', 'stock' => '147 tersedia', 'img' => asset('images/adidas.jpg')],
                    ['category' => 'Laboratorium', 'name' => 'Mikroskop Bk', 'stock' => '6 tersedia', 'img' => asset('images/mikroskop.jpg')],
                ];

                $activeCategory = request('category', 'All item');
                $searchKeyword = strtolower(trim(request('search', '')));

                // Filter berdasarkan Kategori
                if ($activeCategory !== 'All item') {
                    $products = array_filter($products, function ($product) use ($activeCategory) {
                        return $product['category'] === $activeCategory;
                    });
                }

                // Filter berdasarkan Keyword Search
                if (!empty($searchKeyword)) {
                    $products = array_filter($products, function ($product) use ($searchKeyword) {
                        return str_contains(strtolower($product['name']), $searchKeyword) ||
                               str_contains(strtolower($product['category']), $searchKeyword);
                    });
                }
            @endphp

            @if(count($products) > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 lg:gap-5">
                    @foreach ($products as $product)
                        <div class="group bg-white rounded-2xl border border-neutral-100 shadow-sm hover:shadow-md transition overflow-hidden flex flex-col">
                            <div class="aspect-[4/3] bg-neutral-100 overflow-hidden">
                                <img src="{{ $product['img'] }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            </div>
                            <div class="p-3 flex flex-col gap-0.5">
                                <span class="text-[11px] text-neutral-400">{{ $product['category'] }}</span>
                                <h3 class="maroon-text font-semibold text-sm leading-snug line-clamp-2">
                                    {{ $product['name'] }}
                                </h3>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-[11px] text-neutral-400">{{ $product['stock'] }}</span>
                                    
                                    {{-- Tombol Membuka POP UP --}}
                                    <button
                                        type="button"
                                        onclick="openModalPinjam('{{ addslashes($product['name']) }}', '{{ $product['img'] }}')"
                                        class="accent text-xs font-semibold text-neutral-900 px-4 py-1.5 rounded-full hover:brightness-95 transition"
                                    >
                                        Pinjam
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-neutral-500 font-medium text-base">Barang yang kamu cari tidak ditemukan.</p>
                </div>
            @endif
        </section>

    </main>

    {{-- ================= POP-UP MODAL JADWAL ================= --}}
    <div id="modalPinjam" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 sm:p-6">
        <!-- Backdrop Overlay Gelap Transparan -->
        <div onclick="closeModalPinjam()" class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>

        <!-- Box Container Modal -->
        <div class="relative bg-[#FAF9F5] w-full max-w-2xl rounded-3xl p-6 sm:p-8 shadow-2xl z-10 max-h-[90vh] overflow-y-auto scrollbar-none">
            
            {{-- Header Pop-up --}}
            <div class="flex items-center justify-between mb-6">
                <button onclick="closeModalPinjam()" type="button" class="w-9 h-9 flex items-center justify-center bg-neutral-200/60 hover:bg-neutral-300 text-neutral-600 rounded-full transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <h2 class="maroon-text font-extrabold text-lg sm:text-xl uppercase tracking-wide text-center">
                    PILIH JADWAL PEMINJAMAN
                </h2>

                <div class="w-9"></div>
            </div>

            {{-- Slider Tanggal --}}
            <div class="flex items-center gap-2 sm:gap-3 overflow-x-auto scrollbar-none mb-6 pb-2 justify-start sm:justify-center">
                <button type="button" class="bg-[#8C1F2F] text-white px-4 py-2.5 rounded-xl text-center shrink-0 shadow-sm">
                    <div class="text-[10px] font-medium uppercase opacity-90">Jum</div>
                    <div class="text-xs font-bold">31 JULI</div>
                </button>
                <button type="button" class="bg-neutral-200/70 text-neutral-700 hover:bg-neutral-300 px-4 py-2.5 rounded-xl text-center shrink-0 transition">
                    <div class="text-[10px] text-neutral-500 font-medium uppercase">Sen</div>
                    <div class="text-xs font-bold">3 AGS</div>
                </button>
                <button type="button" class="bg-neutral-200/70 text-neutral-700 hover:bg-neutral-300 px-4 py-2.5 rounded-xl text-center shrink-0 transition">
                    <div class="text-[10px] text-neutral-500 font-medium uppercase">Sel</div>
                    <div class="text-xs font-bold">4 AGS</div>
                </button>
                <button type="button" class="bg-neutral-200/70 text-neutral-700 hover:bg-neutral-300 px-4 py-2.5 rounded-xl text-center shrink-0 transition">
                    <div class="text-[10px] text-neutral-500 font-medium uppercase">Rab</div>
                    <div class="text-xs font-bold">5 AGS</div>
                </button>
                <button type="button" class="bg-neutral-200/70 text-neutral-700 hover:bg-neutral-300 px-4 py-2.5 rounded-xl text-center shrink-0 transition">
                    <div class="text-[10px] text-neutral-500 font-medium uppercase">Kam</div>
                    <div class="text-xs font-bold">6 AGS</div>
                </button>
                <button type="button" class="bg-neutral-200/70 text-neutral-700 hover:bg-neutral-300 px-4 py-2.5 rounded-xl text-center shrink-0 transition">
                    <div class="text-[10px] text-neutral-500 font-medium uppercase">Jum</div>
                    <div class="text-xs font-bold">7 AGS</div>
                </button>
            </div>

            {{-- Body Content --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 items-center">
                <div class="bg-white rounded-2xl overflow-hidden aspect-[4/3] flex items-center justify-center w-full shadow-inner border border-neutral-200/60">
                    <img id="modalGambarBarang" src="" alt="Produk" class="w-full h-full object-cover">
                </div>

                <div>
                    <div class="bg-[#D97706] text-white font-bold text-center py-2.5 rounded-xl text-xs uppercase mb-4 tracking-wide shadow-sm">
                        JADWAL YANG TERSEDIA
                    </div>

                    <div class="grid grid-cols-2 gap-2.5 text-center">
                        <button type="button" class="bg-white border border-neutral-200 hover:border-amber-500 rounded-xl p-2.5 transition group shadow-sm">
                            <span class="block text-[10px] text-neutral-400 font-medium mb-0.5">60 Menit</span>
                            <span class="block text-xs font-bold text-neutral-800 group-hover:text-amber-600">08.00 - 09.00</span>
                        </button>
                        <button type="button" class="bg-white border border-neutral-200 hover:border-amber-500 rounded-xl p-2.5 transition group shadow-sm">
                            <span class="block text-[10px] text-neutral-400 font-medium mb-0.5">60 Menit</span>
                            <span class="block text-xs font-bold text-neutral-800 group-hover:text-amber-600">09.00 - 10.00</span>
                        </button>
                        <button type="button" class="bg-white border border-neutral-200 hover:border-amber-500 rounded-xl p-2.5 transition group shadow-sm">
                            <span class="block text-[10px] text-neutral-400 font-medium mb-0.5">60 Menit</span>
                            <span class="block text-xs font-bold text-neutral-800 group-hover:text-amber-600">10.00 - 11.00</span>
                        </button>
                        <button type="button" class="bg-white border border-neutral-200 hover:border-amber-500 rounded-xl p-2.5 transition group shadow-sm">
                            <span class="block text-[10px] text-neutral-400 font-medium mb-0.5">60 Menit</span>
                            <span class="block text-xs font-bold text-neutral-800 group-hover:text-amber-600">11.00 - 12.00</span>
                        </button>
                        <button type="button" class="bg-white border border-neutral-200 hover:border-amber-500 rounded-xl p-2.5 transition group shadow-sm">
                            <span class="block text-[10px] text-neutral-400 font-medium mb-0.5">60 Menit</span>
                            <span class="block text-xs font-bold text-neutral-800 group-hover:text-amber-600">12.00 - 13.00</span>
                        </button>
                        <button type="button" class="bg-white border border-neutral-200 hover:border-amber-500 rounded-xl p-2.5 transition group shadow-sm">
                            <span class="block text-[10px] text-neutral-400 font-medium mb-0.5">60 Menit</span>
                            <span class="block text-xs font-bold text-neutral-800 group-hover:text-amber-600">13.00 - 14.00</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Tombol Booking --}}
            <div class="mt-6">
                <button type="button" class="accent text-neutral-900 font-bold w-full py-3.5 rounded-xl text-center uppercase tracking-wider text-xs sm:text-sm hover:brightness-95 transition shadow-md">
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