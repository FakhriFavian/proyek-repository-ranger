{{--
    resources/views/home.blade.php
    "Take and Go" — halaman utama (rental marketplace)
    Layout WEB/DESKTOP-FIRST — tetap responsive turun ke tablet & mobile.
    Menggunakan Tailwind CSS via CDN — ganti dengan build pipeline (Vite) sesuai setup project-mu.
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
        .accent-text { color: #F4A825; }
        .scrollbar-none::-webkit-scrollbar { display: none; }
        .scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-white text-neutral-800">

    {{-- ================= HEADER ================= --}}
    <header class="sticky top-0 z-30 bg-white/95 backdrop-blur border-b border-neutral-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-10 py-4 flex items-center justify-between gap-4">
            {{-- Logo --}}
            <h1 class="font-extrabold text-xl tracking-tight shrink-0">TAKE AND GO</h1>

            {{-- Container Search Bar (Full Lebar) + Tombol Jam --}}
            <div class="flex-1 flex items-center gap-3">
                {{-- Kotak Pencarian --}}
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

                {{-- Tombol Jam Terpisah di Samping Kanan Kotak --}}
                <a href="{{ route('riwayat') }}"
   aria-label="Riwayat pencarian"
   class="shrink-0 p-2.5 text-neutral-500 hover:text-neutral-800 bg-neutral-100 hover:bg-neutral-200 rounded-full transition inline-flex items-center justify-center">

    <svg xmlns="http://www.w3.org/2000/svg"
         class="w-5 h-5"
         fill="none"
         viewBox="0 0 24 24"
         stroke="currentColor"
         stroke-width="2">

        <circle cx="12" cy="12" r="9"></circle>
        <polyline points="12 7 12 12 15 14"></polyline>

    </svg>

</a>
            </div>

            {{-- Icon menu buat mobile --}}
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
                {{-- Lingkaran kuning di belakang kamera (kiri) --}}
                <div class="accent absolute -left-10 lg:left-0 -bottom-16 w-48 h-48 lg:w-64 lg:h-64 rounded-full z-0 pointer-events-none"></div>

                {{-- Lingkaran kuning di belakang speaker (kanan) --}}
                <div class="accent absolute -right-10 lg:right-4 -top-16 w-52 h-52 lg:w-72 lg:h-72 rounded-full z-0 pointer-events-none"></div>

                {{-- Dekorasi speaker kiri --}}
                <img
                    src="images/speaker.png"
                    alt="speaker"
                    class="absolute left-0 lg:left-8 bottom-0 w-40 lg:w-64 h-40 lg:h-64 object-contain drop-shadow-2xl select-none pointer-events-none hidden sm:block"
                >
                {{-- Dekorasi kamera kanan --}}
                <img
                    src="images/kamera.png"
                    alt="Kamera"
                    class="absolute right-0 lg:right-8 top-0 w-44 lg:w-72 h-44 lg:h-72 object-contain drop-shadow-2xl select-none pointer-events-none hidden sm:block"
                >

                <div class="relative z-10 text-center px-4">
                    <h2 class="text-white font-extrabold text-3xl lg:text-5xl leading-tight tracking-wide">
                        TAKE IT. USE IT.<br>RETURN IT.
                    </h2>
                    {{-- <a href="{{ url('/mulai') }}" class="accent inline-block mt-6 px-10 py-3 rounded-full font-semibold text-sm lg:text-base text-neutral-900 shadow hover:brightness-95 transition">
                        MULAI
                    </a> --}}
                </div>
            </div>
        </section>

        {{-- ================= FILTER KATEGORI (PILL - POSITION CENTER) ================= --}}
        <section class="mt-8">
            <div class="flex justify-center items-center gap-3 overflow-x-auto scrollbar-none pb-1">
                @php
    $categories = ['All item', 'Sports', 'Laboratorium', 'Electronics', 'Cleaning'];
@endphp

<div class="flex justify-center items-center gap-3 overflow-x-auto scrollbar-none pb-1">
    @foreach ($categories as $category)
        <a
            href="/home?category={{ urlencode($category) }}"
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
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 lg:gap-5">
                @php
    $products = [
    // ELECTRONICS
    [
        'category' => 'Electronics',
        'name' => 'JBL speaker blends',
        'stock' => '67 tersedia',
        'img' => asset('images/jblspeaker.jpg')
    ],

    [
        'category' => 'Electronics',
        'name' => 'Sony headphones',
        'stock' => '21 tersedia',
        'img' => asset('images/headphone.jpg')
    ],

    [
        'category' => 'Electronics',
        'name' => 'iPhone 17 Pro Max',
        'stock' => '32 tersedia',
        'img' => asset('images/iphone.jpg')
    ],

    [
        'category' => 'Electronics',
        'name' => 'Samsung galaxy S25 Ultra',
        'stock' => '7 tersedia',
        'img' => asset('images/samsung.jpg')
    ],

    // CLEANING
    [
        'category' => 'Cleaning',
        'name' => 'Cordless Vacuum Cleaner',
        'stock' => '9 tersedia',
        'img' => asset('images/vacuum.jpg')
    ],

    // SPORTS
    [
        'category' => 'Sports',
        'name' => 'Real Madrid Home Jersey',
        'stock' => '20 tersedia',
        'img' => asset('images/jersey.jpg')
    ],

    [
        'category' => 'Sports',
        'name' => 'Adidas Tiro Pro',
        'stock' => '147 tersedia',
        'img' => asset('images/adidas.jpg')
    ],

    // LABORATORIUM
    [
        'category' => 'Laboratorium',
        'name' => 'Mikroskop Bk',
        'stock' => '6 tersedia',
        'img' => asset('images/mikroskop.jpg')
    ],
];

    $active = request('category', 'All item');

    if ($active !== 'All item') {
        $products = array_filter($products, function ($product) use ($active) {
            return $product['category'] === $active;
        });
    }
@endphp

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
                                <a
                                    href="{{ url('/produk/' . \Illuminate\Support\Str::slug($product['name'])) }}"
                                    class="accent text-xs font-semibold text-neutral-900 px-4 py-1.5 rounded-full hover:brightness-95 transition"
                                >
                                    Pinjam
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

    </main>

    {{-- ================= FOOTER BANNER ================= --}}
    <footer class="maroon py-5">
        <p class="text-center text-white font-bold text-base tracking-wide">
            TAKE IT. USE IT. RETURN IT.
        </p>
    </footer>

</body>
</html>
