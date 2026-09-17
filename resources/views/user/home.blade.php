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

    <link rel="icon" type="image/png" href="{{ asset('images/logo-ng.png') }}">

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
                            <a
                                @if ($isStudentLoggedIn) href="{{ route('profile') }}" @else aria-disabled="true" tabindex="-1" @endif
                                class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-medium text-neutral-600 {{ $isStudentLoggedIn ? 'hover:bg-neutral-100 transition' : 'cursor-not-allowed opacity-80' }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21a8 8 0 0 0-16 0"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                Profil
                            </a>

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
                                <form method="POST" action="{{ route('user.logout') }}">
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

            <div id="heroCarousel" class="relative aspect-[1920/650] overflow-hidden rounded-3xl">

                <div id="heroTrack" class="flex h-full w-[500%]">

                <div
                    id="heroSlide1"
                    class="hero-slide maroon relative h-full w-1/5 flex-none overflow-hidden px-8 lg:px-16 py-14 lg:py-20 flex items-center justify-center"
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

                <div
                    id="heroSlide2"
                    class="hero-slide relative h-full w-1/5 flex-none overflow-hidden"
                >
                    <img
                        src="{{ asset('images/banner2.jpg') }}"
                        alt="Banner Take and Go"
                        class="w-full h-full object-contain"
                    >
                </div>

                <div
                    id="heroSlide3"
                    class="hero-slide relative h-full w-1/5 flex-none overflow-hidden"
                >
                    <img
                        src="{{ asset('images/banner3.jpg') }}"
                        alt="Banner Take and Go"
                        class="w-full h-full object-contain"
                    >
                </div>

                </div>

            </div>

            <div
                id="heroIndicators"
                class="flex justify-center items-center gap-2 mt-3"
                aria-label="Indikator banner"
            >
                <button
                    type="button"
                    class="hero-indicator w-2.5 h-2.5 rounded-full bg-[#8C1F2F] transition"
                    data-slide="0"
                    aria-label="Tampilkan banner pertama"
                    aria-current="true"
                ></button>
                <button
                    type="button"
                    class="hero-indicator w-2.5 h-2.5 rounded-full bg-neutral-300 transition"
                    data-slide="1"
                    aria-label="Tampilkan banner kedua"
                    aria-current="false"
                ></button>
                <button
                    type="button"
                    class="hero-indicator w-2.5 h-2.5 rounded-full bg-neutral-300 transition"
                    data-slide="2"
                    aria-label="Tampilkan banner ketiga"
                    aria-current="false"
                ></button>
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
                                        @json($image),
                                        @json($item->deskripsi ?? 'Barang ini siap digunakan untuk kegiatan peminjaman.')
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
        POP-UP MODAL PILIH BARANG
    ========================================================== --}}
    <div
        id="modalPinjam"
        class="fixed inset-0 z-50 hidden"
    >

        <div
            id="modalBackdrop"
            onclick="closeModalPinjam()"
            class="absolute inset-0 bg-slate-900/65 backdrop-blur-sm opacity-0 transition-opacity duration-300"
        ></div>

        <div class="relative z-10 flex min-h-full items-center justify-center p-4 sm:p-6">
            <form
                id="formBooking"
                action="{{ route('peminjaman.confirm') }}"
                method="GET"
                class="relative w-full max-w-[940px] overflow-hidden rounded-[28px] border border-neutral-200 bg-white shadow-[0_25px_80px_rgba(15,23,42,0.16)] transition-all duration-300 opacity-0 scale-95"
            >
                <input type="hidden" name="item_id" id="modalItemId">
                <input type="hidden" name="item_name" id="modalItemNameInput">
                <input type="hidden" name="item_category" id="modalItemCategory">
                <input type="hidden" name="item_stock" id="modalItemStock">
                <input type="hidden" name="item_img" id="modalItemImgInput">
                <input type="hidden" name="tanggal" id="selectedTanggal" value="{{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}">
                <input type="hidden" name="jam" id="selectedJam" value="08.00 - 09.00">
                <input type="hidden" name="jumlah" id="modalFormQty" value="1">

                <div class="flex items-center justify-between border-b border-neutral-100 px-5 py-4 sm:px-8">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#8C1F2F]/10 text-[#8C1F2F]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m-6 4h6m-6 4h4M5 7.5A1.5 1.5 0 016.5 6h11A1.5 1.5 0 0119 7.5v9A1.5 1.5 0 0117.5 18h-11A1.5 1.5 0 015 16.5v-9z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-extrabold uppercase tracking-[0.12em] text-[#8C1F2F]">Pilih Barang</h2>
                            <p class="text-xs text-neutral-500">Pilih barang yang ingin kamu pinjam.</p>
                        </div>
                    </div>

                    <button
                        type="button"
                        onclick="closeModalPinjam()"
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-neutral-100 text-neutral-600 transition hover:bg-neutral-200"
                        aria-label="Tutup modal"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-[1.55fr_0.9fr]">
                    <div class="p-5 sm:p-8">
                        <div class="rounded-[28px] bg-neutral-50 p-4 sm:p-5">
                            <div class="flex flex-wrap items-center gap-2">
                                <span id="modalItemCategoryBadge" class="inline-flex items-center rounded-full bg-[#8C1F2F]/10 px-3 py-1 text-[10px] font-bold uppercase tracking-[0.12em] text-[#8C1F2F]">Kategori</span>
                                <span id="modalStockBadge" class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-[10px] font-bold uppercase tracking-[0.08em] text-emerald-700">Stok tersedia</span>
                            </div>

                            <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-[180px_1fr] sm:items-center">
                                <div class="overflow-hidden rounded-[22px] border border-neutral-200 bg-white shadow-sm">
                                    <img
                                        id="modalGambarBarang"
                                        src=""
                                        alt="Produk"
                                        class="h-44 w-full object-cover sm:h-52"
                                    >
                                </div>

                                <div>
                                    <h3 id="modalDisplayName" class="text-2xl font-extrabold text-neutral-900 leading-tight">Nama Barang</h3>
                                    <p id="modalItemDescription" class="mt-3 text-sm leading-6 text-neutral-600">
                                        Deskripsi barang akan muncul di sini.
                                    </p>

                                    <div class="mt-5 flex items-center justify-between rounded-2xl border border-neutral-200 bg-white px-3 py-2.5">
                                        <span class="text-xs font-semibold uppercase tracking-[0.12em] text-neutral-500">Stok tersedia</span>
                                        <span id="modalStockInfo" class="text-sm font-bold text-neutral-900">0 tersedia</span>
                                    </div>

                                    <div class="mt-5">
                                        <label class="text-xs font-bold uppercase tracking-[0.12em] text-neutral-500">Jumlah yang ingin dipinjam</label>
                                        <div class="mt-2 flex items-center justify-between gap-3 rounded-2xl border border-neutral-200 bg-white p-2">
                                            <button id="modalQtyMinus" type="button" class="flex h-11 w-11 items-center justify-center rounded-full bg-neutral-100 text-xl font-bold text-neutral-700 transition hover:bg-neutral-200 disabled:cursor-not-allowed disabled:opacity-45" aria-label="Kurangi jumlah">-</button>
                                            <div class="flex-1 text-center">
                                                <span id="modalQtyValue" class="block text-2xl font-extrabold text-neutral-900">1</span>
                                            </div>
                                            <button id="modalQtyPlus" type="button" class="flex h-11 w-11 items-center justify-center rounded-full bg-neutral-100 text-xl font-bold text-neutral-700 transition hover:bg-neutral-200 disabled:cursor-not-allowed disabled:opacity-45" aria-label="Tambah jumlah">+</button>
                                        </div>

                                        <p id="modalQtyStatus" class="mt-2 text-xs font-medium text-neutral-600">1 dari 0 barang tersedia</p>
                                        <p id="modalQtyWarning" class="mt-2 hidden text-xs font-medium text-red-600">Jumlah melebihi stok tersedia. Kurangi jumlah sebelum melanjutkan.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center justify-end">
                            <button type="button" onclick="closeModalPinjam()" class="rounded-full border border-neutral-200 bg-white px-5 py-2.5 text-sm font-bold text-neutral-700 transition hover:border-neutral-300 hover:bg-neutral-50">Kembali</button>
                        </div>
                    </div>

                    <aside class="border-t border-neutral-100 bg-neutral-50 p-5 sm:p-6 xl:border-l xl:border-t-0">
                        <h3 class="text-base font-extrabold uppercase tracking-[0.12em] text-neutral-900">Ringkasan Pilihan</h3>

                        <div class="mt-4 rounded-[24px] border border-neutral-200 bg-white p-3 shadow-sm">
                            <div class="flex items-center gap-3">
                                <img id="modalSummaryImage" src="" alt="Thumbnail barang" class="h-16 w-16 rounded-2xl object-cover">
                                <div class="min-w-0">
                                    <span id="modalSummaryCategory" class="block text-[10px] font-bold uppercase tracking-[0.12em] text-neutral-400">Kategori</span>
                                    <p id="modalSummaryName" class="truncate text-sm font-extrabold text-neutral-900">Nama Barang</p>
                                </div>
                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-2.5">
                                <div class="rounded-2xl bg-neutral-50 p-3">
                                    <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-neutral-400">Jumlah</p>
                                    <p id="modalSummaryQty" class="mt-1 text-lg font-extrabold text-neutral-900">1</p>
                                </div>
                                <div class="rounded-2xl bg-neutral-50 p-3">
                                    <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-neutral-400">Stok</p>
                                    <p id="modalSummaryStock" class="mt-1 text-lg font-extrabold text-neutral-900">0</p>
                                </div>
                            </div>

                            <div class="mt-4 rounded-2xl border border-amber-100 bg-amber-50 p-3 text-[11px] leading-5 text-amber-700">
                                Pastikan jumlah yang dipilih sesuai dengan stok yang tersedia sebelum melanjutkan.
                            </div>
                        </div>

                        <button
                            id="modalBookingButton"
                            type="submit"
                            class="mt-6 w-full rounded-2xl bg-[#F4A825] px-4 py-3.5 text-sm font-extrabold uppercase tracking-[0.12em] text-neutral-900 transition hover:brightness-95 disabled:cursor-not-allowed disabled:opacity-45"
                        >
                            Booking Sekarang
                        </button>

                        <p class="mt-3 text-center text-[11px] text-neutral-500">Jumlah yang dipilih akan dipindahkan ke proses pemilihan jadwal.</p>
                    </aside>
                </div>
            </form>
        </div>
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

        function updateModalBookingState() {
            const stock = Number(document.getElementById('modalItemStock').value || 0);
            const qtyInput = document.getElementById('modalFormQty');
            const qtyDisplay = document.getElementById('modalQtyValue');
            const qtyStatus = document.getElementById('modalQtyStatus');
            const qtyWarning = document.getElementById('modalQtyWarning');
            const bookingButton = document.getElementById('modalBookingButton');
            const minusButton = document.getElementById('modalQtyMinus');
            const plusButton = document.getElementById('modalQtyPlus');
            const summaryQty = document.getElementById('modalSummaryQty');
            const summaryStock = document.getElementById('modalSummaryStock');
            const stockInfo = document.getElementById('modalStockInfo');
            const stockBadge = document.getElementById('modalStockBadge');
            const stockCategoryBadge = document.getElementById('modalItemCategoryBadge');

            let quantity = Number(qtyInput.value || 1);

            if (stock <= 0) {
                quantity = 1;
                qtyInput.value = '1';
                qtyDisplay.textContent = '1';
                qtyStatus.textContent = 'Stok habis';
                qtyStatus.classList.remove('text-neutral-600');
                qtyStatus.classList.add('text-red-600');
                qtyWarning.classList.add('hidden');
                bookingButton.disabled = true;
                minusButton.disabled = true;
                plusButton.disabled = true;
                summaryQty.textContent = '0';
                summaryStock.textContent = '0';
                stockInfo.textContent = 'Stok habis';
                stockBadge.textContent = 'Stok habis';
                stockBadge.className = 'inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-[10px] font-bold uppercase tracking-[0.08em] text-red-700';
                stockCategoryBadge.classList.remove('text-[#8C1F2F]');
                return;
            }

            quantity = Math.min(Math.max(quantity, 1), stock);
            qtyInput.value = String(quantity);
            qtyDisplay.textContent = String(quantity);
            summaryQty.textContent = String(quantity);
            summaryStock.textContent = String(stock);
            stockInfo.textContent = stock + ' tersedia';
            stockBadge.textContent = stock + ' tersedia';
            stockBadge.className = 'inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-[10px] font-bold uppercase tracking-[0.08em] text-emerald-700';
            qtyStatus.textContent = quantity + ' dari ' + stock + ' barang tersedia';
            qtyStatus.classList.remove('text-red-600');
            qtyStatus.classList.add('text-neutral-600');
            qtyWarning.classList.add('hidden');

            minusButton.disabled = quantity <= 1;
            plusButton.disabled = quantity >= stock;
            bookingButton.disabled = false;

            if (quantity > stock) {
                qtyWarning.classList.remove('hidden');
                bookingButton.disabled = true;
            }
        }

        function changeModalQuantity(delta) {
            const stock = Number(document.getElementById('modalItemStock').value || 0);
            const qtyInput = document.getElementById('modalFormQty');
            const current = Number(qtyInput.value || 1);

            if (stock <= 0) {
                return;
            }

            const next = Math.min(Math.max(current + delta, 1), stock);
            qtyInput.value = String(next);
            updateModalBookingState();
        }

        function openModalPinjam(id, nama, kategori, stok, gambar, deskripsi = '') {
            const modal = document.getElementById('modalPinjam');
            const backdrop = document.getElementById('modalBackdrop');
            const dialog = document.getElementById('formBooking');
            const imgElement = document.getElementById('modalGambarBarang');
            const summaryImg = document.getElementById('modalSummaryImage');

            document.getElementById('modalItemId').value = id;
            document.getElementById('modalItemNameInput').value = nama;
            document.getElementById('modalItemCategory').value = kategori;
            document.getElementById('modalItemStock').value = stok;
            document.getElementById('modalItemImgInput').value = gambar;
            document.getElementById('modalFormQty').value = '1';

            document.getElementById('modalItemCategoryBadge').textContent = kategori;
            document.getElementById('modalSummaryCategory').textContent = kategori;
            document.getElementById('modalDisplayName').textContent = nama;
            document.getElementById('modalSummaryName').textContent = nama;
            document.getElementById('modalSummaryQty').textContent = '1';
            document.getElementById('modalSummaryStock').textContent = stok;
            document.getElementById('modalItemDescription').textContent = deskripsi || 'Barang ini siap digunakan untuk kegiatan peminjaman.';

            imgElement.src = gambar;
            imgElement.alt = nama;
            summaryImg.src = gambar;
            summaryImg.alt = nama;

            document.getElementById('selectedJam').value = '08.00 - 09.00';
            resetJam();

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');

            requestAnimationFrame(() => {
                backdrop.classList.remove('opacity-0');
                backdrop.classList.add('opacity-100');
                dialog.classList.remove('opacity-0', 'scale-95');
                dialog.classList.add('opacity-100', 'scale-100');
            });

            updateModalBookingState();
        }

        function closeModalPinjam() {
            const modal = document.getElementById('modalPinjam');
            const backdrop = document.getElementById('modalBackdrop');
            const dialog = document.getElementById('formBooking');

            backdrop.classList.add('opacity-0');
            backdrop.classList.remove('opacity-100');
            dialog.classList.remove('opacity-100', 'scale-100');
            dialog.classList.add('opacity-0', 'scale-95');

            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }, 180);
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

            const qtyMinus = document.getElementById('modalQtyMinus');
            const qtyPlus = document.getElementById('modalQtyPlus');
            const bookingForm = document.getElementById('formBooking');
            const searchInput = document.getElementById('searchInput');
            const itemCards = document.querySelectorAll('.item-card');
            const searchEmpty = document.getElementById('searchEmpty');

            if (qtyMinus) {
                qtyMinus.addEventListener('click', function () {
                    changeModalQuantity(-1);
                });
            }

            if (qtyPlus) {
                qtyPlus.addEventListener('click', function () {
                    changeModalQuantity(1);
                });
            }

            if (bookingForm) {
                bookingForm.addEventListener('submit', function (event) {
                    const stock = Number(document.getElementById('modalItemStock').value || 0);
                    const quantity = Number(document.getElementById('modalFormQty').value || 1);

                    if (stock <= 0 || quantity < 1 || quantity > stock) {
                        event.preventDefault();
                        updateModalBookingState();
                        return false;
                    }
                });
            }


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
            const track = document.getElementById('heroTrack');
            const indicators = document.querySelectorAll('.hero-indicator');
            const originalSlides = [
                document.getElementById('heroSlide1'),
                document.getElementById('heroSlide2'),
                document.getElementById('heroSlide3')
            ];

            if (!track || originalSlides.some(function (slide) { return !slide; }) || indicators.length !== originalSlides.length) {
                return;
            }

            const previousClone = originalSlides[2].cloneNode(true);
            const nextClone = originalSlides[0].cloneNode(true);

            previousClone.removeAttribute('id');
            nextClone.removeAttribute('id');
            track.insertBefore(previousClone, originalSlides[0]);
            track.appendChild(nextClone);

            const slides = Array.from(track.children);
            let currentIndex = 1;
            let activeSlide = 0;
            let autoplayTimer;
            let pendingResetIndex = null;

            function setTrackPosition(animate) {
                track.style.transition = animate
                    ? 'transform 600ms cubic-bezier(0.22, 1, 0.36, 1)'
                    : 'none';
                track.style.transform = 'translateX(-' + (currentIndex * 20) + '%)';
            }

            function updateIndicators() {
                indicators.forEach(function (indicator, index) {
                    indicator.classList.toggle('bg-[#8C1F2F]', index === activeSlide);
                    indicator.classList.toggle('bg-neutral-300', index !== activeSlide);
                    indicator.setAttribute('aria-current', String(index === activeSlide));
                });
            }

            function showSlide(slideIndex, direction) {
                activeSlide = slideIndex;
                pendingResetIndex = null;

                if (direction === 'backward' && activeSlide === 2 && currentIndex === 1) {
                    currentIndex = 0;
                    pendingResetIndex = 3;
                } else if (direction === 'forward' && activeSlide === 0 && currentIndex === 3) {
                    currentIndex = 4;
                    pendingResetIndex = 1;
                } else {
                    currentIndex = activeSlide + 1;
                }

                setTrackPosition(true);
                updateIndicators();
            }

            function resetAutoplay() {
                window.clearTimeout(autoplayTimer);
                autoplayTimer = window.setTimeout(function () {
                    const nextSlide = (activeSlide + 1) % originalSlides.length;
                    showSlide(nextSlide, 'forward');
                    resetAutoplay();
                }, 5000);
            }

            track.addEventListener('transitionend', function (event) {
                if (event.propertyName !== 'transform' || pendingResetIndex === null) {
                    return;
                }

                currentIndex = pendingResetIndex;
                pendingResetIndex = null;
                setTrackPosition(false);
            });

            indicators.forEach(function (indicator) {
                indicator.addEventListener('click', function () {
                    const targetSlide = Number(indicator.dataset.slide);
                    let direction = targetSlide < activeSlide ? 'backward' : 'forward';

                    if (activeSlide === 0 && targetSlide === 2) {
                        direction = 'backward';
                    } else if (activeSlide === 2 && targetSlide === 0) {
                        direction = 'forward';
                    }

                    showSlide(targetSlide, direction);
                    resetAutoplay();
                });
            });

            setTrackPosition(false);
            updateIndicators();
            resetAutoplay();
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const params = new URLSearchParams(window.location.search);
            const tanggal = params.get('tanggal');
            const jam = params.get('jam');

            if (tanggal) {
                const tanggalInput = document.getElementById('selectedTanggal');
                if (tanggalInput) {
                    tanggalInput.value = tanggal;
                }
            }

            if (jam) {
                const jamInput = document.getElementById('selectedJam');
                if (jamInput) {
                    jamInput.value = jam;
                }

                const jamButton = document.querySelector('.jam-btn');
                if (jamButton) {
                    let matchingButton = null;
                    document.querySelectorAll('.jam-btn').forEach((button) => {
                        const buttonText = button.textContent.replace(/\s+/g, ' ').trim();
                        if (buttonText.includes(jam)) {
                            matchingButton = button;
                        }
                    });

                    if (matchingButton) {
                        selectJam(matchingButton, jam);
                    }
                }
            }
        });
    </script>

</body>

</html>
