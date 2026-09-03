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
    <link rel="icon" href="{{ asset('images/logo-ng.png') }}" type="image/png">
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
    <div class="max-w-5xl mx-auto flex items-center justify-between">

        <div class="flex items-center gap-3">
            {{-- KEMBALI --}}
            <a
                href="{{ route('home') }}"
                aria-label="Kembali ke halaman utama"
                title="Kembali"
                class="w-10 h-10 rounded-full border border-neutral-200 flex items-center justify-center text-neutral-700 hover:bg-neutral-100 transition"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>

            {{-- LOGO --}}
            <a
                href="{{ route('home') }}"
                class="font-extrabold text-xl tracking-wider text-neutral-800 uppercase"
            >
                TAKE AND GO
            </a>
        </div>

        {{-- MENU TITIK TIGA --}}
        <div class="relative">

            <button
                id="riwayatMenuButton"
                type="button"
                aria-label="Menu pengguna"
                aria-expanded="false"
                class="p-2.5 text-neutral-500 hover:text-neutral-800 rounded-full hover:bg-neutral-100 transition inline-flex items-center justify-center"
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


            {{-- POP UP MENU --}}
            <div
                id="riwayatMenuPopup"
                class="hidden absolute right-0 top-full z-40 mt-3 w-60 rounded-2xl p-2 bg-white border border-neutral-200 shadow-xl"
            >

                {{-- PROFIL --}}
                <a
                    href="{{ route('profile') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-neutral-700 hover:bg-neutral-100 transition"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M20 21a8 8 0 0 0-16 0"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Profil
                </a>


                {{-- RIWAYAT --}}
                <a
                    href="{{ route('riwayat') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-white bg-[#F4A825] transition"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <circle cx="12" cy="12" r="9"></circle>
                        <polyline points="12 7 12 15 14"></polyline>
                    </svg>
                    Riwayat
                </a>


                {{-- BERANDA --}}
                <a
                    href="{{ route('home') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-neutral-700 hover:bg-neutral-100 transition"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M3 9.5L12 3l9 6.5"></path>
                        <path d="M9 21V12h6v9"></path>
                    </svg>
                    Beranda
                </a>


                <div class="my-1 h-px bg-neutral-200"></div>


                {{-- LOGOUT --}}
                <form
                    method="POST"
                    action="{{ route('user.logout') }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-medium text-red-600 hover:bg-red-50 transition"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <path d="M16 17l5-5-5-5"></path>
                            <path d="M21 12H9"></path>
                        </svg>

                        Log out
                    </button>
                </form>

            </div>
        </div>

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
                        <p class="text-sm font-bold text-slate-700">{{ $stats['total'] }} kali</p>
                    </div>
                </div>

                {{-- Disetujui --}}
                <div class="flex items-center justify-center gap-3 px-2 pt-3 sm:pt-0">
                    <div class="text-center">
                        <p class="text-xs text-slate-400 font-medium">Disetujui</p>
                        <p class="text-base font-extrabold text-emerald-500">{{ $stats['disetujui'] }}</p>
                    </div>
                </div>

                {{-- Ditolak --}}
                <div class="flex items-center justify-center gap-3 px-2 pt-3 sm:pt-0">
                    <div class="text-center">
                        <p class="text-xs text-slate-400 font-medium">Ditolak</p>
                        <p class="text-base font-extrabold text-rose-500">{{ $stats['ditolak'] }}</p>
                    </div>
                </div>

                {{-- Dikembalikan --}}
                <div class="flex items-center justify-center gap-3 px-2 pt-3 sm:pt-0">
                    <div class="text-center">
                        <p class="text-xs text-slate-400 font-medium">Dikembalikan</p>
                        <p class="text-base font-extrabold text-slate-700">{{ $stats['dikembalikan'] }}</p>
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

        {{-- ================= TIMELINE CONTAINER ================= --}}
        <div class="relative space-y-4">

            {{-- Garis Putus-putus Vertical Timeline --}}
            <div class="hidden md:block absolute left-[21.5%] top-6 bottom-6 w-0 border-l-2 border-dashed border-slate-200 z-0"></div>

            @forelse ($timeline as $step)
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

                            {{-- ====== TIMER ====== --}}
                            @if (!empty($step['can_timer']))
                                {{-- Status Dipinjam: elapsed timer (naik) / TERLAMBAT (berbasis server) --}}
                                <div
                                    class="mt-2 flex items-center gap-2"
                                    data-timer
                                    data-start="{{ $step['start_unix'] }}"
                                    data-deadline="{{ $step['deadline_unix'] }}"
                                    data-now="{{ $step['server_now_unix'] }}"
                                >
                                    <span class="timer-late-chip hidden px-2 py-0.5 rounded-full bg-rose-600 text-white text-[10px] font-bold">TERLAMBAT</span>
                                    <span class="timer-text {{ $step['is_late'] ? 'text-rose-600' : 'text-purple-600' }} font-bold text-sm tabular-nums">--:--:--</span>
                                    <span class="timer-fine text-[11px] text-rose-500 font-semibold">@if (!empty($step['is_late']))Denda sementara: Rp{{ number_format($step['temporary_fine'], 0, ',', '.') }}@endif</span>
                                </div>
                            @elseif (($step['raw_status'] ?? '') === 'menunggu')
                                {{-- Status Booking: timer belum aktif --}}
                                <p class="text-[11px] text-slate-400 font-medium mt-1.5">Timer akan muncul saat status berubah menjadi Dipinjam.</p>
                            @endif

                        </div>

                        {{-- Barang & Kategori --}}
                        <div class="md:col-span-3 md:text-right border-t md:border-t-0 md:border-l border-slate-100 pt-3 md:pt-0 md:pl-4">
                            <p class="font-bold text-slate-800 text-xs sm:text-sm">{{ $step['item'] }}</p>
                            <p class="text-xs text-slate-400 mt-1">Jumlah: {{ $step['jumlah'] }}</p>
                            <span class="inline-block mt-1 px-3 py-0.5 rounded-full {{ $step['cat_bg'] }} text-[10px] font-semibold">
                                {{ $step['category'] }}
                            </span>
                        </div>

                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-slate-200 p-6 text-center text-sm text-slate-400">Belum ada riwayat peminjaman.</div>
            @endforelse

        </div>

        {{-- ================= TOMBOL NAVIGASI / PAGINATION ================= --}}
        <div class="flex justify-end gap-2 mt-6">
            {{-- Panah Kiri (Halaman Sebelumnya) --}}
            <a
                href="{{ $borrowings->previousPageUrl() ?? '#' }}"
                class="w-10 h-10 rounded-xl flex items-center justify-center transition shadow-sm
                {{ $borrowings->onFirstPage() ? 'bg-slate-200 text-slate-400 cursor-not-allowed pointer-events-none' : 'bg-slate-200 hover:bg-slate-300 text-slate-700' }}"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>

            {{-- Panah Kanan (Halaman Selanjutnya) --}}
            <a
                href="{{ $borrowings->nextPageUrl() ?? '#' }}"
                class="w-10 h-10 rounded-xl flex items-center justify-center transition shadow-sm
                {{ !$borrowings->hasMorePages() ? 'bg-slate-200 text-slate-400 cursor-not-allowed pointer-events-none' : 'bg-slate-200 hover:bg-slate-300 text-slate-700' }}"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>

    </main>

    {{-- ===== TIMER JAVASCRIPT (berbasis server / tidak reset) ===== --}}
    <script>
        (function () {
            function pad(n) { return String(n).padStart(2, '0'); }

            function formatHMS(totalSeconds) {
                var s = Math.max(0, Math.floor(totalSeconds));
                var h = Math.floor(s / 3600);
                var m = Math.floor((s % 3600) / 60);
                var sec = s % 60;
                return pad(h) + ':' + pad(m) + ':' + pad(sec);
            }

            function initTimer(el) {
                // Sumber kebenaran: server (start/deadline & now saat halaman dirender).
                var start = parseInt(el.getAttribute('data-start'), 10);
                var deadline = parseInt(el.getAttribute('data-deadline'), 10);
                var serverNow = parseInt(el.getAttribute('data-now'), 10);

                // Koreksi selisih jam browser vs jam server (dihitung sekali).
                var offset = serverNow - (Date.now() / 1000);

                var textEl = el.querySelector('.timer-text');
                var lateChip = el.querySelector('.timer-late-chip');
                var fineEl = el.querySelector('.timer-fine');

                function tick() {
                    var nowAdjusted = (Date.now() / 1000) + offset;

                    if (nowAdjusted <= deadline) {
                        // Belum terlambat: elapsed timer NAIK sejak start (00:00:00 -> 00:59:59).
                        lateChip.classList.add('hidden');
                        textEl.classList.remove('text-rose-600');
                        textEl.classList.add('text-purple-600');
                        textEl.textContent = formatHMS(nowAdjusted - start);
                        if (fineEl) fineEl.textContent = '';
                    } else {
                        // Terlambat: timer keterlambatan NAIK sejak deadline (00:00:00 -> ...).
                        lateChip.classList.remove('hidden');
                        textEl.classList.remove('text-purple-600');
                        textEl.classList.add('text-rose-600');
                        textEl.textContent = formatHMS(nowAdjusted - deadline);

                        // Denda sementara (hanya tampilan): floor(menitTerlambat/30)*1000.
                        if (fineEl) {
                            var overdueMinutes = Math.floor((nowAdjusted - deadline) / 60);
                            var fine = Math.floor(overdueMinutes / 30) * 1000;
                            fineEl.textContent = 'Denda sementara: Rp' + fine.toLocaleString('id-ID');
                        }
                    }
                }

                tick();
                setInterval(tick, 1000);
            }

            document.querySelectorAll('[data-timer]').forEach(initTimer);
        })();
    </script>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const menuButton = document.getElementById('riwayatMenuButton');
        const menuPopup = document.getElementById('riwayatMenuPopup');

        if (!menuButton || !menuPopup) {
            return;
        }

        function closeMenu() {
            menuPopup.classList.add('hidden');
            menuButton.setAttribute('aria-expanded', 'false');
        }

        menuButton.addEventListener('click', function (event) {
            event.stopPropagation();

            const isOpen = !menuPopup.classList.contains('hidden');

            menuPopup.classList.toggle('hidden', isOpen);

            menuButton.setAttribute(
                'aria-expanded',
                String(!isOpen)
            );
        });

        document.addEventListener('click', function (event) {

            if (
                !menuButton.contains(event.target) &&
                !menuPopup.contains(event.target)
            ) {
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
