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
    <link rel="icon" href="{{ asset('images/logo-ng.png') }}" type="image/png">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .maroon {
            background-color: #8C1F2F;
        }

        .maroon-text {
            color: #8C1F2F;
        }

        .accent {
            background-color: #F4A825;
        }

        .scrollbar-none::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-none {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .menu-chip {
            background: rgba(140, 31, 47, 0.05);
            border: 1px solid rgba(140, 31, 47, 0.08);
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.04);
        }

        .menu-popup {
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(148, 163, 184, 0.18);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.10);
            backdrop-filter: blur(8px);
        }

        .avatar-badge {
            background: linear-gradient(135deg, #8C1F2F 0%, #A53A4A 100%);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.2);
        }
    </style>
</head>

<body class="bg-white text-neutral-800">

    @php
        $isStudentLoggedIn = Auth::guard('student')->check();
    @endphp

    {{-- =========================================================
        HEADER / NAVBAR
    ========================================================== --}}
    <header class="sticky top-0 z-30 bg-white/95 backdrop-blur border-b border-neutral-100">

        <div class="max-w-7xl mx-auto px-6 lg:px-10 py-4 flex items-center justify-between gap-4">

            {{-- LOGO --}}
            <a
                href="{{ route('home') }}"
                class="font-extrabold text-xl tracking-tight shrink-0 text-neutral-900"
            >
                TAKE AND GO
            </a>

            {{-- SEARCH --}}
            <div class="flex-1 flex items-center gap-3">

                <form
                    action="{{ route('home') }}"
                    method="GET"
                    id="searchForm"
                    class="flex-1 flex items-center gap-2 bg-neutral-100 rounded-full px-5 py-2.5"
                >

                    {{-- Kalau sedang memilih kategori, kategori tetap dibawa --}}
                    @if(request('category'))
                        <input
                            type="hidden"
                            name="category"
                            value="{{ request('category') }}"
                        >
                    @endif

                    {{-- Icon Search --}}
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4 text-neutral-400 shrink-0"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <circle cx="11" cy="11" r="7"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>

                    <input
                        type="text"
                        name="search"
                        id="searchInput"
                        value="{{ request('search') }}"
                        placeholder="pinjem apa yaa"
                        autocomplete="off"
                        class="bg-transparent outline-none text-sm text-neutral-600 placeholder-neutral-400 w-full"
                    >

                </form>

                {{-- MENU USER --}}
                <div class="relative">
                        <button
                            id="userMenuButton"
                            type="button"
                            aria-label="Menu pengguna"
                            aria-expanded="false"
                            class="menu-chip shrink-0 p-2.5 text-neutral-500 hover:text-neutral-800 rounded-full transition inline-flex items-center justify-center"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <circle cx="5" cy="12" r="1.5"></circle>
                                <circle cx="12" cy="12" r="1.5"></circle>
                                <circle cx="19" cy="12" r="1.5"></circle>
                            </svg>
                        </button>

                         
                    {{-- pop up menu --}}
                    <div
                            id="userMenuPopup"
                            class="menu-popup hidden absolute right-0 top-full z-40 mt-3 w-60 rounded-2xl p-2"
                        >
                            <button
                                type="button"
                                @if (!$isStudentLoggedIn) disabled @endif
                                @if (!$isStudentLoggedIn) aria-disabled="true" @endif
                                class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-medium text-neutral-600 {{ !$isStudentLoggedIn ? 'cursor-not-allowed opacity-80' : 'hover:bg-neutral-100 transition' }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21a8 8 0 0 0-16 0"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                Profil
                            </button>

                            <a
                                @if ($isStudentLoggedIn) href="{{ route('riwayat') }}" @else aria-disabled="true" tabindex="-1" @endif
                                class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-neutral-700 {{ $isStudentLoggedIn ? 'hover:bg-neutral-100 transition' : 'cursor-not-allowed opacity-80' }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="9"></circle>
                                    <polyline points="12 7 12 15 14"></polyline>
                                </svg>
                                Riwayat
                            </a>

                                <a
                                href="{{ route('home') }}"
                                class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                                    {{ request()->routeIs('home')
                                        ? 'text-white bg-[#F4A825]'
                                        : 'text-neutral-700 hover:bg-neutral-100'
                                    }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M3 9.5L12 3l9 6.5"></path>
                                    <path d="M9 21V12h6v9"></path>
                                </svg>
                                Beranda
                            </a>
                            <div class="my-1 h-px bg-neutral-200"></div>

                            @if ($isStudentLoggedIn)
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-medium text-red-600 hover:bg-red-50 transition"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                            <path d="M16 17l5-5-5-5"></path>
                                            <path d="M21 12H9"></path>
                                        </svg>
                                        Log out
                                    </button>
                                </form>
                            @else
                                <a
                                    href="{{ route('user.login') }}"
                                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-neutral-700 hover:bg-neutral-100 transition"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                        <polyline points="10 17 15 12 10 7"></polyline>
                                        <line x1="15" y1="12" x2="3" y2="12"></line>
                                    </svg>
                                    Login
                                </a>
                            @endif
                        </div>
                    </div>

            </div>

            {{-- MOBILE MENU --}}
            <button
                type="button"
                class="md:hidden shrink-0"
                aria-label="Menu"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-6 h-6 text-neutral-700"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>

        </div>

    </header>


    {{-- =========================================================
        MAIN
    ========================================================== --}}
    <main class="max-w-7xl mx-auto px-6 lg:px-10">

        {{-- =====================================================
            HERO BANNER
        ====================================================== --}}
        <section class="mt-6">

            <div
                class="maroon relative rounded-3xl overflow-hidden px-8 lg:px-16 py-14 lg:py-20 min-h-[240px] lg:min-h-[320px] flex items-center justify-center"
            >

                {{-- Lingkaran kiri --}}
                <div
                    class="accent absolute -left-10 lg:left-0 -bottom-16 w-48 h-48 lg:w-64 lg:h-64 rounded-full z-0 pointer-events-none"
                ></div>

                {{-- Lingkaran kanan --}}
                <div
                    class="accent absolute -right-10 lg:right-4 -top-16 w-52 h-52 lg:w-72 lg:h-72 rounded-full z-0 pointer-events-none"
                ></div>

                {{-- Speaker --}}
                <img
                    src="{{ asset('images/speaker.png') }}"
                    alt="speaker"
                    class="absolute left-0 lg:left-8 bottom-0 w-40 lg:w-64 h-40 lg:h-64 object-contain drop-shadow-2xl select-none pointer-events-none hidden sm:block"
                >

                {{-- Kamera --}}
                <img
                    src="{{ asset('images/kamera.png') }}"
                    alt="Kamera"
                    class="absolute right-0 lg:right-8 top-0 w-44 lg:w-72 h-44 lg:h-72 object-contain drop-shadow-2xl select-none pointer-events-none hidden sm:block"
                >

                {{-- Tulisan --}}
                <div class="relative z-10 text-center px-4">

                    <h2
                        class="text-white font-extrabold text-3xl lg:text-5xl leading-tight tracking-wide"
                    >
                        TAKE IT. USE IT.<br>
                        RETURN IT.
                    </h2>

                </div>

            </div>

        </section>


        {{-- =====================================================
            FILTER KATEGORI
        ====================================================== --}}
        <section class="mt-8">

            <div
                class="flex justify-center items-center gap-3 overflow-x-auto scrollbar-none pb-1"
            >

                {{-- ALL ITEM --}}
                <a
                    href="{{ route('home') }}"
                    class="shrink-0 px-6 py-2.5 rounded-full text-sm font-medium whitespace-nowrap transition shadow-sm
                    {{ $activeCategory === 'All item'
                        ? 'accent text-neutral-900 font-semibold'
                        : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200'
                    }}"
                >
                    All item
                </a>


                {{-- KATEGORI DARI LARALAG --}}
                @foreach ($categories as $category)

                    <a
                        href="{{ route('home', ['category' => $category->nama_kategori]) }}"
                        class="shrink-0 px-6 py-2.5 rounded-full text-sm font-medium whitespace-nowrap transition shadow-sm
                        {{ $category->nama_kategori === $activeCategory
                            ? 'accent text-neutral-900 font-semibold'
                            : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200'
                        }}"
                    >
                        {{ $category->nama_kategori }}
                    </a>

                @endforeach

            </div>

        </section>


        {{-- =====================================================
            GRID PRODUK
        ====================================================== --}}
        <section class="mt-6 pb-14">

            <div
                id="itemsGrid"
                class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 lg:gap-5"
            >

                @forelse ($items as $item)

                    @php

                        /*
                         * Ambil foto dari storage.
                         * Kalau tidak ada foto, gunakan placeholder.
                         */
                        $image = $item->foto
                            ? asset('storage/' . $item->foto)
                            : 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 300%22%3E%3Crect width=%22400%22 height=%22300%22 fill=%22%23f5f5f5%22/%3E%3Ctext x=%22200%22 y=%22155%22 text-anchor=%22middle%22 fill=%22%23999999%22 font-family=%22Arial%22 font-size=%2220%22%3ENo%20photo%3C/text%3E%3C/svg%3E';

                        /*
                         * Nama kategori.
                         */
                        $kategori = $item->category?->nama_kategori ?? 'Tanpa kategori';

                        /*
                         * Nilai search untuk JavaScript.
                         * Menggabungkan nama barang + kategori.
                         */
                        $searchText = strtolower(
                            trim($item->nama_item . ' ' . $kategori)
                        );

                    @endphp


                    {{-- =================================================
                        CARD BARANG
                    ================================================== --}}
                    <div
                        class="item-card group bg-white rounded-2xl border border-neutral-100 shadow-sm hover:shadow-md transition overflow-hidden flex flex-col"
                        data-search="{{ $searchText }}"
                    >

                        {{-- FOTO --}}
                        <div class="aspect-[4/3] bg-neutral-100 overflow-hidden">

                            <img
                                src="{{ $image }}"
                                alt="{{ $item->nama_item }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                            >

                        </div>


                        {{-- INFORMASI BARANG --}}
                        <div class="p-3 flex flex-col gap-0.5">

                            {{-- KATEGORI --}}
                            <span class="text-[11px] text-neutral-400">
                                {{ $kategori }}
                            </span>


                            {{-- NAMA BARANG --}}
                            <h3
                                class="maroon-text font-semibold text-sm leading-snug line-clamp-2"
                            >
                                {{ $item->nama_item }}
                            </h3>


                            {{-- STOK + PINJAM --}}
                            <div class="flex items-center justify-between mt-2">

                                {{-- STOK DARI DATABASE --}}
                                <span class="text-[11px] text-neutral-400">
                                    {{ $item->stok_tersedia }} tersedia
                                </span>


                                {{-- TOMBOL PINJAM --}}
                                <button
                                    type="button"

                                    onclick='openModalPinjam(
                                        @json($item->id),
                                        @json($item->nama_item),
                                        @json($kategori),
                                        @json($item->stok_tersedia),
                                        @json($image)
                                    )'

                                    @if ($item->stok_tersedia < 1)
                                        disabled
                                    @endif

                                    class="accent text-xs font-semibold text-neutral-900 px-4 py-1.5 rounded-full hover:brightness-95 transition
                                    {{ $item->stok_tersedia < 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                >
                                    {{ $item->stok_tersedia < 1 ? 'Habis' : 'Pinjam' }}
                                </button>

                            </div>

                        </div>

                    </div>

                @empty

                    {{-- DATA KOSONG DARI DATABASE --}}
                    <p
                        class="col-span-full text-center text-sm text-neutral-400 py-10"
                    >
                        Belum ada item tersedia.
                    </p>

                @endforelse


                {{-- PESAN SEARCH TIDAK DITEMUKAN --}}
                <div
                    id="searchEmpty"
                    class="hidden col-span-full text-center py-12"
                >

                    <div class="flex flex-col items-center">

                        <div
                            class="w-14 h-14 rounded-full bg-neutral-100 flex items-center justify-center mb-4"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-7 h-7 text-neutral-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <circle cx="11" cy="11" r="7"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </div>

                        <p class="text-sm font-semibold text-neutral-600">
                            Barang tidak ditemukan
                        </p>

                        <p class="text-xs text-neutral-400 mt-1">
                            Coba cari dengan nama barang yang lain.
                        </p>

                    </div>

                </div>

            </div>

        </section>

    </main>


    {{-- =========================================================
        POP-UP MODAL JADWAL PEMINJAMAN
    ========================================================== --}}
    <div
        id="modalPinjam"
        class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 sm:p-6"
    >

        {{-- BACKDROP --}}
        <div
            onclick="closeModalPinjam()"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"
        ></div>


        {{-- =====================================================
            FORM BOOKING
        ====================================================== --}}
        <form
            id="formBooking"
            action="{{ route('peminjaman.confirm') }}"
            method="GET"
            class="relative bg-[#FAF9F5] w-full max-w-2xl rounded-3xl p-6 sm:p-8 shadow-2xl z-10 max-h-[90vh] overflow-y-auto scrollbar-none"
        >

            {{-- INPUT DATA BARANG --}}
            <input
                type="hidden"
                name="item_id"
                id="modalItemId"
            >

            <input
                type="hidden"
                name="item_name"
                id="modalItemName"
            >

            <input
                type="hidden"
                name="item_category"
                id="modalItemCategory"
            >

            <input
                type="hidden"
                name="item_stock"
                id="modalItemStock"
            >

            <input
                type="hidden"
                name="item_img"
                id="modalItemImgInput"
            >


            {{-- TANGGAL --}}
            <input
                type="hidden"
                name="tanggal"
                id="selectedTanggal"
                value="{{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}"
            >


            {{-- JAM
                 DEFAULT SEKARANG 08.00 - 09.00
            --}}
            <input
                type="hidden"
                name="jam"
                id="selectedJam"
                value="08.00 - 09.00"
            >


            {{-- JUMLAH --}}
            <input
                type="hidden"
                name="jumlah"
                value="1"
            >


            {{-- =================================================
                HEADER MODAL
            ================================================== --}}
            <div class="flex items-center justify-between mb-6">

                <div class="w-9"></div>

                <h2
                    class="maroon-text font-extrabold text-lg sm:text-xl uppercase tracking-wide text-center"
                >
                    PILIH JADWAL PEMINJAMAN
                </h2>


                {{-- CLOSE --}}
                <button
                    onclick="closeModalPinjam()"
                    type="button"
                    class="w-9 h-9 flex items-center justify-center bg-neutral-200/60 hover:bg-neutral-300 text-neutral-600 rounded-full transition"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2.5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>

                </button>

            </div>


            {{-- =================================================
                SLIDER TANGGAL
            ================================================== --}}
            <div class="relative flex items-center gap-2 mb-6">

                {{-- LEFT --}}
                <button
                    type="button"
                    onclick="scrollDate('left')"
                    class="shrink-0 p-2 bg-neutral-200/70 hover:bg-neutral-300 text-neutral-700 rounded-full transition shadow-sm z-10"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2.5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>

                </button>


                {{-- TANGGAL --}}
                <div
                    id="dateContainer"
                    class="flex items-center gap-2 sm:gap-3 overflow-x-auto scrollbar-none pb-1 scroll-smooth w-full"
                >

                    @php

                        use Carbon\Carbon;

                        Carbon::setLocale('id');

                        $today = Carbon::today();

                    @endphp


                    @for ($i = 0; $i < 30; $i++)

                        @php

                            $currentDate = $today->copy()->addDays($i);

                            $formattedValue =
                                $currentDate->translatedFormat('d F Y');

                            $isFirst = $i === 0;

                        @endphp


                        <button
                            type="button"

                            onclick="selectTanggal(
                                this,
                                '{{ $formattedValue }}'
                            )"

                            class="date-btn
                            {{ $isFirst
                                ? 'bg-[#8C1F2F] text-white'
                                : 'bg-neutral-200/70 text-neutral-700 hover:bg-neutral-300'
                            }}
                            px-4 py-2.5 rounded-xl text-center shrink-0 transition shadow-sm"
                        >

                            <div
                                class="text-[10px] uppercase font-medium
                                {{ $isFirst
                                    ? 'opacity-90'
                                    : 'text-neutral-500'
                                }}"
                            >
                                {{ $currentDate->translatedFormat('D') }}
                            </div>

                            <div class="text-xs font-bold whitespace-nowrap">
                                {{ $currentDate->format('j') }}
                                {{ strtoupper($currentDate->translatedFormat('M')) }}
                            </div>

                        </button>

                    @endfor

                </div>


                {{-- RIGHT --}}
                <button
                    type="button"
                    onclick="scrollDate('right')"
                    class="shrink-0 p-2 bg-neutral-200/70 hover:bg-neutral-300 text-neutral-700 rounded-full transition shadow-sm z-10"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2.5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 5l7 7-7 7"
                        />
                    </svg>

                </button>

            </div>


            {{-- =================================================
                BODY MODAL
            ================================================== --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 items-center">

                {{-- FOTO BARANG --}}
                <div
                    class="bg-white rounded-2xl overflow-hidden aspect-[4/3] flex items-center justify-center w-full shadow-inner border border-neutral-200/60"
                >

                    <img
                        id="modalGambarBarang"
                        src=""
                        alt="Produk"
                        class="w-full h-full object-cover"
                    >

                </div>


                {{-- JADWAL --}}
                <div>

                    <div
                        class="bg-[#D97706] text-white font-bold text-center py-2.5 rounded-xl text-xs uppercase mb-4 tracking-wide shadow-sm"
                    >
                        JADWAL YANG TERSEDIA
                    </div>


                    {{-- =================================================
                        JAM
                    ================================================== --}}
                    <div
                        class="grid grid-cols-2 gap-2.5 text-center"
                    >

                        {{-- 08 - 09 --}}
                        <button
                            type="button"
                            onclick="selectJam(this, '08.00 - 09.00')"
                            class="jam-btn selected bg-white border-2 border-amber-500 text-amber-600 rounded-xl p-2.5 transition group shadow-sm"
                        >

                            <span
                                class="block text-[10px] text-neutral-400 font-medium mb-0.5"
                            >
                                60 Menit
                            </span>

                            <span
                                class="jam-text block text-xs font-bold text-amber-600"
                            >
                                08.00 - 09.00
                            </span>

                        </button>


                        {{-- 09 - 10 --}}
                        <button
                            type="button"
                            onclick="selectJam(this, '09.00 - 10.00')"
                            class="jam-btn bg-white border border-neutral-200 hover:border-amber-500 rounded-xl p-2.5 transition group shadow-sm"
                        >

                            <span
                                class="block text-[10px] text-neutral-400 font-medium mb-0.5"
                            >
                                60 Menit
                            </span>

                            <span
                                class="jam-text block text-xs font-bold text-neutral-800 group-hover:text-amber-600"
                            >
                                09.00 - 10.00
                            </span>

                        </button>


                        {{-- 10 - 11 --}}
                        <button
                            type="button"
                            onclick="selectJam(this, '10.00 - 11.00')"
                            class="jam-btn bg-white border border-neutral-200 hover:border-amber-500 rounded-xl p-2.5 transition group shadow-sm"
                        >

                            <span
                                class="block text-[10px] text-neutral-400 font-medium mb-0.5"
                            >
                                60 Menit
                            </span>

                            <span
                                class="jam-text block text-xs font-bold text-neutral-800 group-hover:text-amber-600"
                            >
                                10.00 - 11.00
                            </span>

                        </button>


                        {{-- 11 - 12 --}}
                        <button
                            type="button"
                            onclick="selectJam(this, '11.00 - 12.00')"
                            class="jam-btn bg-white border border-neutral-200 hover:border-amber-500 rounded-xl p-2.5 transition group shadow-sm"
                        >

                            <span
                                class="block text-[10px] text-neutral-400 font-medium mb-0.5"
                            >
                                60 Menit
                            </span>

                            <span
                                class="jam-text block text-xs font-bold text-neutral-800 group-hover:text-amber-600"
                            >
                                11.00 - 12.00
                            </span>

                        </button>


                        {{-- 12 - 13 --}}
                        <button
                            type="button"
                            onclick="selectJam(this, '12.00 - 13.00')"
                            class="jam-btn bg-white border border-neutral-200 hover:border-amber-500 rounded-xl p-2.5 transition group shadow-sm"
                        >

                            <span
                                class="block text-[10px] text-neutral-400 font-medium mb-0.5"
                            >
                                60 Menit
                            </span>

                            <span
                                class="jam-text block text-xs font-bold text-neutral-800 group-hover:text-amber-600"
                            >
                                12.00 - 13.00
                            </span>

                        </button>


                        {{-- 13 - 14 --}}
                        <button
                            type="button"
                            onclick="selectJam(this, '13.00 - 14.00')"
                            class="jam-btn bg-white border border-neutral-200 hover:border-amber-500 rounded-xl p-2.5 transition group shadow-sm"
                        >

                            <span
                                class="block text-[10px] text-neutral-400 font-medium mb-0.5"
                            >
                                60 Menit
                            </span>

                            <span
                                class="jam-text block text-xs font-bold text-neutral-800 group-hover:text-amber-600"
                            >
                                13.00 - 14.00
                            </span>

                        </button>

                    </div>

                </div>

            </div>


            {{-- =================================================
                BUTTON BOOKING
            ================================================== --}}
            <div class="mt-6">

                <button
                    type="submit"
                    class="accent text-neutral-900 font-bold w-full py-3.5 rounded-xl text-center uppercase tracking-wider text-xs sm:text-sm hover:brightness-95 transition shadow-md"
                >
                    BOOKING SEKARANG
                </button>

            </div>

        </form>

    </div>


    {{-- =========================================================
        FOOTER
    ========================================================== --}}
    <footer class="maroon py-5">

        <p
            class="text-center text-white font-bold text-base tracking-wide"
        >
            TAKE IT. USE IT. RETURN IT.
        </p>

    </footer>


    {{-- =========================================================
        JAVASCRIPT
    ========================================================== --}}
    <script>

        /* ========================================================
           MODAL PINJAM
        ======================================================== */

        function openModalPinjam(id, nama, kategori, stok, gambar) {

            const modal = document.getElementById('modalPinjam');

            const imgElement =
                document.getElementById('modalGambarBarang');


            /*
             * Masukkan data barang ke input hidden.
             */
            document.getElementById('modalItemId').value = id;

            document.getElementById('modalItemName').value = nama;

            document.getElementById('modalItemCategory').value =
                kategori;

            document.getElementById('modalItemStock').value =
                stok;

            document.getElementById('modalItemImgInput').value =
                gambar;


            /*
             * Tampilkan foto barang.
             */
            imgElement.src = gambar;

            imgElement.alt = nama;


            /*
             * Reset jam ke 08.00 - 09.00
             * setiap kali modal dibuka.
             */
            document.getElementById('selectedJam').value =
                '08.00 - 09.00';


            /*
             * Reset tampilan tombol jam.
             */
            resetJam();


            /*
             * Tampilkan modal.
             */
            modal.classList.remove('hidden');

            document.body.classList.add('overflow-hidden');
        }


        function closeModalPinjam() {

            const modal =
                document.getElementById('modalPinjam');

            modal.classList.add('hidden');

            document.body.classList.remove('overflow-hidden');
        }



        /* ========================================================
           PILIH TANGGAL
        ======================================================== */

        function selectTanggal(btn, val) {

            document.getElementById('selectedTanggal').value =
                val;


            /*
             * Semua tanggal dikembalikan ke warna normal.
             */
            document.querySelectorAll('.date-btn').forEach(b => {

                b.className =
                    "date-btn bg-neutral-200/70 text-neutral-700 hover:bg-neutral-300 px-4 py-2.5 rounded-xl text-center shrink-0 transition shadow-sm";


                const dayText =
                    b.querySelector('div:first-child');

                if (dayText) {

                    dayText.className =
                        "text-[10px] uppercase font-medium text-neutral-500";
                }

            });


            /*
             * Tombol yang dipilih menjadi maroon.
             */
            btn.className =
                "date-btn bg-[#8C1F2F] text-white px-4 py-2.5 rounded-xl text-center shrink-0 transition shadow-sm";


            const dayText =
                btn.querySelector('div:first-child');

            if (dayText) {

                dayText.className =
                    "text-[10px] uppercase font-medium opacity-90";
            }

        }



        /* ========================================================
           RESET JAM
        ======================================================== */

        function resetJam() {

            const buttons =
                document.querySelectorAll('.jam-btn');


            buttons.forEach((button, index) => {

                /*
                 * Semua tombol dibuat normal.
                 */
                button.className =
                    "jam-btn bg-white border border-neutral-200 hover:border-amber-500 rounded-xl p-2.5 transition group shadow-sm";


                /*
                 * Semua teks jam dibuat abu.
                 */
                const jamText =
                    button.querySelector('.jam-text');

                if (jamText) {

                    jamText.className =
                        "jam-text block text-xs font-bold text-neutral-800 group-hover:text-amber-600";
                }

            });


            /*
             * DEFAULT:
             * 08.00 - 09.00 menjadi kuning.
             */
            const firstButton =
                document.querySelector('.jam-btn');


            if (firstButton) {

                firstButton.className =
                    "jam-btn selected bg-white border-2 border-amber-500 text-amber-600 rounded-xl p-2.5 transition group shadow-sm";


                const firstText =
                    firstButton.querySelector('.jam-text');

                if (firstText) {

                    firstText.className =
                        "jam-text block text-xs font-bold text-amber-600";
                }

            }

        }



        /* ========================================================
           PILIH JAM
        ======================================================== */

        function selectJam(btn, val) {

            /*
             * Simpan jam yang dipilih.
             */
            document.getElementById('selectedJam').value =
                val;


            /*
             * Kembalikan SEMUA tombol jam
             * menjadi warna normal.
             */
            document.querySelectorAll('.jam-btn').forEach(b => {

                b.className =
                    "jam-btn bg-white border border-neutral-200 hover:border-amber-500 rounded-xl p-2.5 transition group shadow-sm";


                /*
                 * Teks jam yang sebelumnya kuning
                 * juga dikembalikan menjadi abu.
                 */
                const jamText =
                    b.querySelector('.jam-text');

                if (jamText) {

                    jamText.className =
                        "jam-text block text-xs font-bold text-neutral-800 group-hover:text-amber-600";
                }

            });


            /*
             * Tombol yang DIKLIK menjadi kuning.
             */
            btn.className =
                "jam-btn selected bg-white border-2 border-amber-500 text-amber-600 rounded-xl p-2.5 transition group shadow-sm";


            /*
             * TEKS jam yang diklik juga menjadi kuning.
             */
            const jamText =
                btn.querySelector('.jam-text');

            if (jamText) {

                jamText.className =
                    "jam-text block text-xs font-bold text-amber-600";
            }

        }



        /* ========================================================
           SCROLL TANGGAL
        ======================================================== */

        function scrollDate(direction) {

            const container =
                document.getElementById('dateContainer');

            const scrollAmount = 280;


            if (direction === 'left') {

                container.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });

            } else {

                container.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });

            }

        }



        /* ========================================================
           SEARCH BAR

           Search dilakukan terhadap barang yang sudah dikirim
           dari Laravel melalui $items.

           Yang dicari:
           - nama barang
           - kategori
        ======================================================== */

        document.addEventListener('DOMContentLoaded', function () {

            const searchInput =
                document.getElementById('searchInput');

            const itemCards =
                document.querySelectorAll('.item-card');

            const searchEmpty =
                document.getElementById('searchEmpty');


            /*
             * Ambil nilai search dari input.
             */
            function filterItems() {

                const keyword =
                    searchInput.value
                        .toLowerCase()
                        .trim();


                let visibleCount = 0;


                itemCards.forEach(card => {

                    const searchText =
                        (card.dataset.search || '')
                            .toLowerCase();


                    /*
                     * Kalau kosong:
                     * tampilkan semua.
                     */
                    if (keyword === '') {

                        card.style.display = '';

                        visibleCount++;

                        return;
                    }


                    /*
                     * Kalau keyword ditemukan:
                     * tampilkan card.
                     */
                    if (searchText.includes(keyword)) {

                        card.style.display = '';

                        visibleCount++;

                    } else {

                        card.style.display = 'none';

                    }

                });


                /*
                 * Kalau tidak ada hasil,
                 * tampilkan pesan.
                 */
                if (keyword !== '' && visibleCount === 0) {

                    searchEmpty.classList.remove('hidden');

                } else {

                    searchEmpty.classList.add('hidden');

                }

            }


            /*
             * Search langsung saat mengetik.
             */
            searchInput.addEventListener(
                'input',
                filterItems
            );


            /*
             * Jalankan sekali ketika halaman dibuka.
             * Ini penting kalau URL sudah mempunyai:
             * ?search=adidas
             */
            filterItems();

        });



        /* ========================================================
           TOMBOL ESC UNTUK MENUTUP MODAL
        ======================================================== */

        document.addEventListener('keydown', function(e) {

            if (
                e.key === 'Escape' &&
                !document
                    .getElementById('modalPinjam')
                    .classList.contains('hidden')
            ) {

                closeModalPinjam();

            }

        });

    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const menuButton = document.getElementById('userMenuButton');
            const menuPopup = document.getElementById('userMenuPopup');

            if (!menuButton || !menuPopup) {
                return;
            }

            const closeMenu = () => {
                menuPopup.classList.add('hidden');
                menuButton.setAttribute('aria-expanded', 'false');
            };

            menuButton.addEventListener('click', function (event) {
                event.stopPropagation();
                const isOpen = !menuPopup.classList.contains('hidden');
                menuPopup.classList.toggle('hidden', isOpen);
                menuButton.setAttribute('aria-expanded', String(!isOpen));
            });

            document.addEventListener('click', function (event) {
                if (!menuButton.contains(event.target) && !menuPopup.contains(event.target)) {
                    closeMenu();
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeMenu();
                }
            });
        });
    </script>

</body>

</html>
