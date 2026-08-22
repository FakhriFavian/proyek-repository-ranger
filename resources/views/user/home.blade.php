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
                        <polyline points="12 7 12 15 14"></polyline>
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
                                    onclick='openModalPinjam(@json($item->id), @json($item->nama_item), @json($item->category?->nama_kategori ?? "Tanpa kategori"), @json($item->stok_tersedia), @json($image))'
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

    {{-- ================= POP-UP MODAL JADWAL ================= --}}
    <div id="modalPinjam" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 sm:p-6">
        <!-- Backdrop Overlay Gelap Transparan -->
        <div onclick="closeModalPinjam()" class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>

        <!-- Box Container Modal dibungkus FORM -->
        <form id="formBooking" action="{{ route('peminjaman.confirm') }}" method="GET" class="relative bg-[#FAF9F5] w-full max-w-2xl rounded-3xl p-6 sm:p-8 shadow-2xl z-10 max-h-[90vh] overflow-y-auto scrollbar-none">

            {{-- Input Hidden Data Peminjaman --}}
            <input type="hidden" name="item_id" id="modalItemId">
            <input type="hidden" name="item_name" id="modalItemName">
            <input type="hidden" name="item_category" id="modalItemCategory">
            <input type="hidden" name="item_stock" id="modalItemStock">
            <input type="hidden" name="item_img" id="modalItemImgInput">
            <input type="hidden" name="tanggal" id="selectedTanggal" value="{{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}">
            <input type="hidden" name="jam" id="selectedJam" value="11.00 - 12.00">
            <input type="hidden" name="jumlah" value="1">

            {{-- Header Pop-up --}}
            <div class="flex items-center justify-between mb-6">
                <div class="w-9"></div>
                <h2 class="maroon-text font-extrabold text-lg sm:text-xl uppercase tracking-wide text-center">
                    PILIH JADWAL PEMINJAMAN
                </h2>
                <button onclick="closeModalPinjam()" type="button" class="w-9 h-9 flex items-center justify-center bg-neutral-200/60 hover:bg-neutral-300 text-neutral-600 rounded-full transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Slider Tanggal (30 Hari Ke Depan) --}}
            <div class="relative flex items-center gap-2 mb-6">
                <button type="button" onclick="scrollDate('left')" class="shrink-0 p-2 bg-neutral-200/70 hover:bg-neutral-300 text-neutral-700 rounded-full transition shadow-sm z-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <div id="dateContainer" class="flex items-center gap-2 sm:gap-3 overflow-x-auto scrollbar-none pb-1 scroll-smooth w-full">
                    @php
                        use Carbon\Carbon;
                        Carbon::setLocale('id');
                        $today = Carbon::today();
                    @endphp

                    @for ($i = 0; $i < 30; $i++)
                        @php
                            $currentDate = $today->copy()->addDays($i);
                            $formattedValue = $currentDate->translatedFormat('d F Y');
                            $isFirst = $i === 0;
                        @endphp

                        <button
                            type="button"
                            onclick="selectTanggal(this, '{{ $formattedValue }}')"
                            class="date-btn {{ $isFirst ? 'bg-[#8C1F2F] text-white' : 'bg-neutral-200/70 text-neutral-700 hover:bg-neutral-300' }} px-4 py-2.5 rounded-xl text-center shrink-0 transition shadow-sm"
                        >
                            <div class="text-[10px] uppercase font-medium {{ $isFirst ? 'opacity-90' : 'text-neutral-500' }}">
                                {{ $currentDate->translatedFormat('D') }}
                            </div>
                            <div class="text-xs font-bold whitespace-nowrap">
                                {{ $currentDate->format('j') }} {{ strtoupper($currentDate->translatedFormat('M')) }}
                            </div>
                        </button>
                    @endfor
                </div>

                <button type="button" onclick="scrollDate('right')" class="shrink-0 p-2 bg-neutral-200/70 hover:bg-neutral-300 text-neutral-700 rounded-full transition shadow-sm z-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
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
                        <button type="button" onclick="selectJam(this, '08.00 - 09.00')" class="jam-btn bg-white border border-neutral-200 hover:border-amber-500 rounded-xl p-2.5 transition group shadow-sm">
                            <span class="block text-[10px] text-neutral-400 font-medium mb-0.5">60 Menit</span>
                            <span class="block text-xs font-bold text-neutral-800 group-hover:text-amber-600">08.00 - 09.00</span>
                        </button>
                        <button type="button" onclick="selectJam(this, '09.00 - 10.00')" class="jam-btn bg-white border border-neutral-200 hover:border-amber-500 rounded-xl p-2.5 transition group shadow-sm">
                            <span class="block text-[10px] text-neutral-400 font-medium mb-0.5">60 Menit</span>
                            <span class="block text-xs font-bold text-neutral-800 group-hover:text-amber-600">09.00 - 10.00</span>
                        </button>
                        <button type="button" onclick="selectJam(this, '10.00 - 11.00')" class="jam-btn bg-white border border-neutral-200 hover:border-amber-500 rounded-xl p-2.5 transition group shadow-sm">
                            <span class="block text-[10px] text-neutral-400 font-medium mb-0.5">60 Menit</span>
                            <span class="block text-xs font-bold text-neutral-800 group-hover:text-amber-600">10.00 - 11.00</span>
                        </button>
                        <button type="button" onclick="selectJam(this, '11.00 - 12.00')" class="jam-btn bg-white border-2 border-amber-500 text-amber-600 rounded-xl p-2.5 transition group shadow-sm">
                            <span class="block text-[10px] text-neutral-400 font-medium mb-0.5">60 Menit</span>
                            <span class="block text-xs font-bold text-amber-600">11.00 - 12.00</span>
                        </button>
                        <button type="button" onclick="selectJam(this, '12.00 - 13.00')" class="jam-btn bg-white border border-neutral-200 hover:border-amber-500 rounded-xl p-2.5 transition group shadow-sm">
                            <span class="block text-[10px] text-neutral-400 font-medium mb-0.5">60 Menit</span>
                            <span class="block text-xs font-bold text-neutral-800 group-hover:text-amber-600">12.00 - 13.00</span>
                        </button>
                        <button type="button" onclick="selectJam(this, '13.00 - 14.00')" class="jam-btn bg-white border border-neutral-200 hover:border-amber-500 rounded-xl p-2.5 transition group shadow-sm">
                            <span class="block text-[10px] text-neutral-400 font-medium mb-0.5">60 Menit</span>
                            <span class="block text-xs font-bold text-neutral-800 group-hover:text-amber-600">13.00 - 14.00</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Tombol Booking --}}
            <div class="mt-6">
                <button type="submit" class="accent text-neutral-900 font-bold w-full py-3.5 rounded-xl text-center uppercase tracking-wider text-xs sm:text-sm hover:brightness-95 transition shadow-md">
                    BOOKING SEKARANG
                </button>
            </div>
        </form>
    </div>

    {{-- ================= FOOTER BANNER ================= --}}
    <footer class="maroon py-5">
        <p class="text-center text-white font-bold text-base tracking-wide">
            TAKE IT. USE IT. RETURN IT.
        </p>
    </footer>

    {{-- ================= JAVASCRIPT ================= --}}
    <script>
        function openModalPinjam(id, nama, kategori, stok, gambar) {
            const modal = document.getElementById('modalPinjam');
            const imgElement = document.getElementById('modalGambarBarang');

            document.getElementById('modalItemId').value = id;
            document.getElementById('modalItemName').value = nama;
            document.getElementById('modalItemCategory').value = kategori;
            document.getElementById('modalItemStock').value = stok;
            document.getElementById('modalItemImgInput').value = gambar;

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

        function selectTanggal(btn, val) {
            document.getElementById('selectedTanggal').value = val;
            document.querySelectorAll('.date-btn').forEach(b => {
                b.className = "date-btn bg-neutral-200/70 text-neutral-700 hover:bg-neutral-300 px-4 py-2.5 rounded-xl text-center shrink-0 transition shadow-sm";
                if(b.querySelector('div')) {
                    b.querySelector('div').className = "text-[10px] uppercase font-medium text-neutral-500";
                }
            });
            btn.className = "date-btn bg-[#8C1F2F] text-white px-4 py-2.5 rounded-xl text-center shrink-0 transition shadow-sm";
            if(btn.querySelector('div')) {
                btn.querySelector('div').className = "text-[10px] uppercase font-medium opacity-90";
            }
        }

        function selectJam(btn, val) {
            document.getElementById('selectedJam').value = val;
            document.querySelectorAll('.jam-btn').forEach(b => {
                b.className = "jam-btn bg-white border border-neutral-200 hover:border-amber-500 rounded-xl p-2.5 transition group shadow-sm";
            });
            btn.className = "jam-btn bg-white border-2 border-amber-500 text-amber-600 rounded-xl p-2.5 transition group shadow-sm";
        }

        function scrollDate(direction) {
            const container = document.getElementById('dateContainer');
            const scrollAmount = 280;
            if (direction === 'left') {
                container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            } else {
                container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('modalPinjam').classList.contains('hidden')) {
                closeModalPinjam();
            }
        });
    </script>
</body>
</html>
