<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAKE AND GO - Detail Peminjaman</title>
    <link rel="icon" href="{{ asset('images/logo-ng.png') }}" type="image/png">
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

        <div class="flex items-center gap-4">
            <a href="{{ route('home', ['tanggal' => $tanggal, 'jam' => $jam]) }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-neutral-800 shadow-sm border border-neutral-200/60 hover:bg-neutral-50 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="font-extrabold text-2xl sm:text-3xl text-neutral-900 tracking-wider">TAKE AND GO</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">

            <div class="lg:col-span-8 bg-white rounded-3xl p-6 sm:p-8 shadow-[0_4px_25px_rgba(0,0,0,0.05)] flex flex-col justify-between space-y-6">
                <div>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                        <h2 class="text-maroon font-extrabold text-2xl sm:text-3xl uppercase tracking-tight">
                            DETAIL & KONFIRMASI PEMINJAMAN
                        </h2>

                        <a href="{{ route('home', ['tanggal' => $tanggal, 'jam' => $jam]) }}" class="inline-flex items-center justify-center rounded-full border border-[#8B2635] text-[#8B2635] px-4 py-2 text-sm font-bold hover:bg-[#8B2635] hover:text-white transition">
                            + Tambah Pesanan
                        </a>
                    </div>

                    <div class="space-y-4">
                        <h3 class="font-extrabold text-base text-neutral-900 uppercase tracking-wide">
                            Daftar Pesanan ({{ $totalKinds }})
                        </h3>

                        @forelse ($cart as $item)
                            @php
                                $itemId = (string) ($item['id'] ?? '');
                                $stock = (int) ($item['stock'] ?? 1);
                                $quantity = max(1, (int) ($item['quantity'] ?? 1));
                            @endphp

                            <div class="rounded-2xl border border-neutral-200 bg-[#F9FAFB] p-4 shadow-sm">
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <div class="w-full sm:w-28 h-24 bg-neutral-100 rounded-2xl overflow-hidden shrink-0">
                                        <img src="{{ $item['img'] ?? asset('images/vacuum.jpg') }}" alt="{{ $item['name'] ?? 'Barang' }}" class="w-full h-full object-cover">
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0">
                                                <span class="text-[11px] font-semibold text-slate-400 block">{{ $item['category'] ?? 'Tanpa kategori' }}</span>
                                                <h4 class="font-extrabold text-lg sm:text-xl text-neutral-900 truncate">
                                                    {{ $item['name'] ?? 'Barang' }}
                                                </h4>
                                            </div>

                                            <form action="{{ route('peminjaman.cart.update') }}" method="POST" class="inline-block">
                                                @csrf
                                                <input type="hidden" name="item_id" value="{{ $itemId }}">
                                                <input type="hidden" name="action" value="remove">
                                                <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                                                <input type="hidden" name="jam" value="{{ $jam }}">
                                                <button type="submit" class="w-10 h-10 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center hover:bg-rose-200 transition" aria-label="Hapus barang">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M3 7h18M8 7V4a1 1 0 011-1h6a1 1 0 011 1v3" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>

                                        <div class="mt-3 flex flex-wrap items-center gap-3 text-xs text-slate-600">
                                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 text-emerald-700 px-2.5 py-1 font-bold">
                                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                                {{ $stock > 0 ? 'Tersedia' : 'Tidak tersedia' }}
                                            </span>
                                            <span class="font-semibold text-slate-500">NIS: {{ $user->identitas }}</span>
                                            <span class="font-semibold text-slate-500">Kelas: {{ $user->kelas ?? '-' }}</span>
                                        </div>

                                        <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                            <div class="text-sm font-bold text-neutral-800">
                                                Jumlah barang
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <form action="{{ route('peminjaman.cart.update') }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <input type="hidden" name="item_id" value="{{ $itemId }}">
                                                    <input type="hidden" name="action" value="decrement">
                                                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                                                    <input type="hidden" name="jam" value="{{ $jam }}">
                                                    <button type="submit" {{ $quantity <= 1 ? 'disabled' : '' }} class="w-8 h-8 rounded-full bg-neutral-200 text-neutral-700 font-bold disabled:opacity-40 disabled:cursor-not-allowed">-</button>
                                                </form>

                                                <input type="number" value="{{ $quantity }}" min="1" max="{{ $stock }}" class="w-14 text-center font-extrabold text-neutral-800 border border-neutral-200 rounded-lg py-1.5 bg-white" readonly>

                                                <form action="{{ route('peminjaman.cart.update') }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <input type="hidden" name="item_id" value="{{ $itemId }}">
                                                    <input type="hidden" name="action" value="increment">
                                                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                                                    <input type="hidden" name="jam" value="{{ $jam }}">
                                                    <button type="submit" {{ $quantity >= $stock ? 'disabled' : '' }} class="w-8 h-8 rounded-full bg-neutral-200 text-neutral-700 font-bold disabled:opacity-40 disabled:cursor-not-allowed">+</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-neutral-300 bg-neutral-50 p-6 text-center text-sm text-neutral-500">
                                Belum ada barang dalam daftar pesanan.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 bg-white rounded-3xl p-6 shadow-[0_4px_25px_rgba(0,0,0,0.05)] flex flex-col justify-between">
                <div class="space-y-4">
                    <h3 class="font-extrabold text-base text-neutral-900 uppercase tracking-wide border-b border-neutral-100 pb-2">
                        RINGKASAN PEMINJAMAN
                    </h3>

                    <div class="space-y-3 text-xs sm:text-sm">
                        <div>
                            <span class="text-slate-400 block font-medium">Nama Peminjam</span>
                            <span class="font-extrabold text-neutral-900">{{ $user->name }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Jumlah Jenis Barang</span>
                            <span class="font-bold text-neutral-800">{{ $totalKinds }} item</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Total Jumlah Barang</span>
                            <span class="font-bold text-neutral-800">{{ $totalItems }} item</span>
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
                                {{ $totalItems }} item siap dipinjam
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-[#FFF3E0] rounded-2xl p-3.5 flex items-start gap-2.5 border border-amber-100/80 mt-6">
                    <div class="w-4 h-4 rounded-full bg-[#FF9F0A] text-white flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">i</div>
                    <div>
                        <h5 class="font-extrabold text-xs text-neutral-900">Informasi</h5>
                        <p class="text-[11px] text-neutral-700 leading-tight mt-0.5">Pastikan semua barang dan jadwal sudah sesuai sebelum mengkonfirmasi peminjaman.</p>
                    </div>
                </div>
            </div>

        </div>

        <form action="{{ route('peminjaman.store') }}" method="POST" class="mt-6">
            @csrf
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">
            <input type="hidden" name="jam" value="{{ $jam }}">
            <button type="submit" class="bg-orange-accent text-white font-extrabold w-full py-4 rounded-2xl text-center uppercase tracking-wider text-base hover:brightness-95 transition shadow-[0_4px_20px_rgba(255,153,0,0.35)]">
                MULAI MEMINJAM
            </button>
        </form>

    </div>

</body>
</html>
