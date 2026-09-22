<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Berhasil</title>
    <link rel="icon" href="{{ asset('images/logo-ng.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        @keyframes popIn {
            0% {
                opacity: 0;
                transform: scale(0.82) translateY(16px);
            }

            55% {
                opacity: 1;
                transform: scale(1.03) translateY(-4px);
            }

            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        @keyframes pulseGlow {

            0%,
            100% {
                box-shadow: 0 0 0 rgba(16, 185, 129, 0.15);
            }

            50% {
                box-shadow: 0 0 32px rgba(16, 185, 129, 0.25);
            }
        }

        .success-pop {
            animation: popIn 0.45s ease-out;
        }

        .success-badge {
            animation: pulseGlow 1.4s ease-in-out infinite;
        }
    </style>
</head>

<body
    class="min-h-screen bg-[radial-gradient(circle_at_top,_#fff7ed,_#fff,_#f8fafc_75%)] flex items-center justify-center p-4">
    <div
        class="success-pop w-full max-w-md rounded-[32px] border border-slate-200 bg-white/90 p-6 text-center shadow-[0_30px_90px_rgba(15,23,42,0.15)] backdrop-blur-sm">
        <div
            class="success-badge mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <p class="text-[10px] font-extrabold uppercase tracking-[0.35em] text-emerald-600">Berhasil</p>
        <h1 class="mt-3 text-3xl font-extrabold text-slate-900">Peminjaman Berhasil</h1>
        <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ session('success', 'Peminjaman berhasil dibuat.') }}
        </p>

        <div class="mt-6 grid gap-3 sm:grid-cols-2">
            <a href="{{ route('riwayat') }}"
                class="inline-flex items-center justify-center rounded-2xl bg-[#F4A825] px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#df9510]">
                Lihat Riwayat
            </a>
            <a href="{{ route('home') }}"
                class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                Kembali ke beranda
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            try {
                const AudioContextClass = window.AudioContext || window.webkitAudioContext;
                if (!AudioContextClass) return;

                const audioCtx = new AudioContextClass();
                const master = audioCtx.createGain();
                master.gain.value = 0.35;
                master.connect(audioCtx.destination);

                function tone(freq, start, duration, type, volume, slideTo = null) {
                    const osc = audioCtx.createOscillator();
                    const gain = audioCtx.createGain();
                    osc.type = type;
                    osc.frequency.setValueAtTime(freq, start);
                    if (slideTo) {
                        osc.frequency.exponentialRampToValueAtTime(slideTo, start + duration);
                    }

                    gain.gain.setValueAtTime(0.0001, start);
                    gain.gain.exponentialRampToValueAtTime(volume, start + 0.03);
                    gain.gain.exponentialRampToValueAtTime(0.0001, start + duration);

                    osc.connect(gain);
                    gain.connect(master);
                    osc.start(start);
                    osc.stop(start + duration + 0.04);
                }

                const now = audioCtx.currentTime;
                tone(440, now, 0.20, 'square', 0.34, 560);
                tone(587.33, now + 0.09, 0.24, 'square', 0.30, 700);
                tone(783.99, now + 0.18, 0.32, 'triangle', 0.26, 900);

                setTimeout(function() {
                    audioCtx.close();
                }, 1200);
            } catch (error) {
                console.log('Efek popup suara tidak didukung browser ini.', error);
            }
        });
    </script>
</body>

</html>
