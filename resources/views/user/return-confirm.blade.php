<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pengembalian - Take and Go</title>
    <link rel="icon" href="{{ asset('images/logo-ng.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        html, body { width: 100%; min-width: 0; overflow-x: hidden; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-100 text-slate-800">
    <header class="bg-orange-500 px-4 py-2.5 text-sm font-bold text-slate-950 shadow-sm">
        <div class="mx-auto flex max-w-6xl items-center gap-2">
            <a href="{{ route('riwayat') }}" aria-label="Kembali ke riwayat" class="flex h-7 w-7 items-center justify-center rounded-full bg-white text-slate-700 hover:bg-orange-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m15 19-7-7 7-7" /></svg>
            </a>
            Konfirmasi Pengembalian
        </div>
    </header>

    <main class="mx-auto max-w-6xl space-y-4 px-3 py-4 sm:px-6">
        <div class="grid w-full grid-cols-1 gap-4 xl:grid-cols-7">
            <section class="min-w-0 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm xl:col-span-4">
                <div class="border-b border-slate-100 px-4 py-3 text-xs font-bold text-slate-700">Detail Peminjaman</div>
                <div class="flex gap-4 p-4">
                    <div class="h-32 w-32 shrink-0 overflow-hidden rounded-lg bg-slate-100 sm:h-40 sm:w-40">
                        <img src="{{ $item['img'] }}" alt="{{ $item['name'] }}" class="h-full w-full object-cover">
                    </div>
                    <div class="min-w-0 flex-1 divide-y divide-slate-100 text-[11px]">
                        <div class="flex justify-between gap-3 py-2 first:pt-0"><span class="font-semibold text-slate-500">Kode Barang</span><span class="font-bold text-slate-700">{{ $detail->item_id }}</span></div>
                        <div class="flex justify-between gap-3 py-2"><span class="font-semibold text-slate-500">Tanggal Peminjaman</span><span class="font-bold text-slate-700">{{ $borrowing->jam_mulai->translatedFormat('d M Y, H:i') }}</span></div>
                        <div class="flex justify-between gap-3 py-2"><span class="font-semibold text-slate-500">Batas Pengembalian</span><span class="font-bold {{ $isLate ? 'text-rose-500' : 'text-slate-700' }}">{{ $deadline?->translatedFormat('d M Y, H:i') ?? '-' }}</span></div>
                        <div class="flex justify-between gap-3 py-2"><span class="font-semibold text-slate-500">Peminjam</span><span class="font-bold text-slate-700">{{ $borrowing->user?->name ?? '-' }}</span></div>
                        <div class="flex justify-between gap-3 py-2 last:pb-0"><span class="font-semibold text-slate-500">Kelas</span><span class="font-bold text-slate-700">{{ $borrowing->user?->kelas ?? '-' }}</span></div>
                    </div>
                </div>
            </section>

            <section class="min-w-0 rounded-xl border border-slate-200 bg-white p-4 shadow-sm xl:col-span-3">
                <h2 class="mb-3 text-sm font-extrabold text-slate-800">Konfirmasi Pengembalian</h2>
                <div class="mb-4 flex gap-2 rounded-lg border border-cyan-200 bg-cyan-50 p-3 text-[10px] leading-relaxed text-slate-600">
                    <span class="flex h-4 w-4 shrink-0 items-center justify-center rounded-full bg-cyan-500 font-bold text-white">i</span>
                    Pastikan barang sudah dikembalikan dengan kondisi yang baik.
                </div>
                @if ($isReturned)
                    <div class="rounded-xl border border-emerald-300 bg-emerald-50 p-4 text-center">
                        <span class="mx-auto mb-2 flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500 text-lg text-white">✓</span>
                        <span class="block text-[11px] font-extrabold text-emerald-700">Barang sudah dikembalikan</span>
                        <span class="mt-1 block text-[9px] text-slate-500">Pengembalian telah dikonfirmasi sebelumnya.</span>
                    </div>
                @else
                    <p class="mb-3 text-center text-xs font-bold text-slate-700">Apakah barang ini sudah dikembalikan?</p>
                    <form action="{{ route('pengembalian.store', $borrowing) }}" method="POST" class="grid grid-cols-2 gap-2">
                        @csrf
                        <button name="confirmation" value="yes" type="submit" class="rounded-xl border border-emerald-300 bg-emerald-50 p-4 text-center transition hover:bg-emerald-100">
                            <span class="mx-auto mb-2 flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500 text-lg text-white">✓</span>
                            <span class="block text-[11px] font-extrabold text-emerald-700">Ya, Sudah Dikembalikan</span>
                            <span class="mt-1 block text-[9px] text-slate-500">Barang {{ $item['name'] }} telah dikembalikan</span>
                        </button>
                        <button name="confirmation" value="no" type="submit" class="rounded-xl border border-slate-200 bg-slate-100 p-4 text-center transition hover:bg-slate-200">
                            <span class="mx-auto mb-2 flex h-8 w-8 items-center justify-center rounded-full bg-slate-500 text-lg text-white">×</span>
                            <span class="block text-[11px] font-extrabold text-slate-700">Belum, dikembalikan</span>
                            <span class="mt-1 block text-[9px] text-slate-500">Kembali ke riwayat peminjaman</span>
                        </button>
                    </form>
                @endif
            </section>
        </div>

        <section class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <h3 class="mb-3 text-xs font-extrabold text-slate-700">Informasi Tambahan</h3>
                <div class="flex justify-between border-b border-slate-100 py-2 text-[11px]"><span>Durasi Peminjaman</span><strong>1 Jam</strong></div>
                <div class="flex justify-between py-2 text-[11px]"><span>Sisa Waktu Pengembalian</span><strong class="{{ $isLate ? 'text-rose-500' : 'text-emerald-600' }}">{{ $isLate ? 'Terlambat' : 'Sesuai jadwal' }}</strong></div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <h3 class="mb-2 text-xs font-extrabold text-slate-700">Ringkasan Barang</h3>
                <p class="text-sm font-extrabold text-slate-800">{{ $item['name'] }}</p>
                <p class="mt-1 text-[11px] text-slate-500">{{ $item['category'] }} · Jumlah {{ $detail->jumlah }}</p>
                @if ($isLate)
                    <p class="mt-3 text-[11px] font-bold text-rose-500">Perkiraan denda saat ini: Rp{{ number_format($fine, 0, ',', '.') }}</p>
                @endif
            </div>
        </section>

        <p class="rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-[10px] text-emerald-700">Pastikan kondisi barang sesuai sebelum mengkonfirmasi pengembalian.</p>
    </main>
</body>
</html>
