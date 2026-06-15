<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warmindo Pablo - Navigator Waiter</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-zinc-950 font-sans text-zinc-100 selection:bg-emerald-600 selection:text-white">

    <nav class="bg-black border-b border-emerald-500 text-white p-5 shadow-xl sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div>
                <h1 class="text-xl font-black tracking-widest text-emerald-500">PABLO <span class="text-white">WAITER NAV</span></h1>
                <p class="text-[10px] text-zinc-400 mt-1 uppercase tracking-wider">Layar Antar Makanan & Monitor Meja</p>
            </div>
            <div class="flex items-center gap-2 bg-zinc-900 border border-zinc-800 px-3 py-1.5 rounded-lg">
                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></span>
                <span id="status-waiter-text" class="text-[10px] text-zinc-300 font-bold uppercase tracking-widest">Active Link</span>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 mt-8 pb-12 grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center gap-2 mb-4">
                <span class="w-1.5 h-4 bg-emerald-500 rounded-full"></span>
                <h2 class="text-xs font-black tracking-widest uppercase text-white">Makanan Matang Siap Diantar</h2>
            </div>
            
            <div class="space-y-3" id="waiter-list-antaran">
                </div>
        </div>

        <div class="space-y-4">
            <div class="flex items-center gap-2 mb-4">
                <span class="w-1.5 h-4 bg-emerald-500 rounded-full"></span>
                <h2 class="text-xs font-black tracking-widest uppercase text-white">Status Denah Meja Real-time</h2>
            </div>

            <div class="grid grid-cols-2 gap-3" id="waiter-grid-meja">
                </div>
        </div>

    </div>

    <script>
        const API_MENU = "https://6a2babdb3e2b60ab038e98af.mockapi.io/menu";
        const API_RIWAYAT = "https://6a2babdb3e2b60ab038e98af.mockapi.io/riwayat";

        // 1. Ambil Data Gabungan Meja dan Riwayat dari Mock API
        async function muatDataWaiter() {
            try {
                const [resMenu, resRiwayat] = await Promise.all([
                    fetch(API_MENU),
                    fetch(API_RIWAYAT)
                ]);

                const gabunganMenu = await resMenu.json();
                const semuaRiwayat = await resRiwayat.json();

                // Memisahkan dan memetakan ulang data meja tiruan dari endpoint /menu
                const mejaData = gabunganMenu
                    .filter(item => item.nama === "STATUS_MEJA")
                    .map(m => ({
                        nomor: parseInt(m.modal),
                        isi: parseInt(m.stok)
                    }))
                    .sort((a, b) => a.nomor - b.nomor);

                // Menyaring orderan matang: Sudah diklik Selesai oleh Chef (ada di localStorage),
                // namun belum diklik Selesai Diantar oleh Waiter.
                const orderanMatang = semuaRiwayat.filter(order => 
                    localStorage.getItem(`selesai_masak_${order.id}`) && 
                    !localStorage.getItem(`sudah_diantar_${order.id}`)
                );

                renderLayarWaiter(orderanMatang, mejaData);
            } catch (err) {
                console.error("Gagal sinkronisasi data Waiter dengan Mock API:", err);
            }
        }

        // 2. Render Tampilan List Antaran & Grid Meja (UI/UX Asli Diperkuat)
        function renderLayarWaiter(daftarOrder, semuaMeja) {
            
            // --- A. BAGIAN ANTARAN MAKANAN MATANG ---
            const containerAntar = document.getElementById('waiter-list-antaran');
            containerAntar.innerHTML = '';

            if (daftarOrder.length === 0) {
                containerAntar.innerHTML = `
                    <div class="bg-zinc-900/20 border border-zinc-900 border-dashed rounded-2xl p-8 text-center">
                        <p class="text-xs text-zinc-500 font-medium">Dapur belum merilis makanan matang. Tetap stand by! 🍵</p>
                    </div>`;
            } else {
                daftarOrder.forEach(order => {
                    containerAntar.innerHTML += `
                        <div class="bg-zinc-900/60 border border-zinc-850 p-4 rounded-xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 transition hover:border-emerald-500/20">
                            <div>
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="bg-emerald-600/10 text-emerald-400 border border-emerald-500/20 text-[9px] font-black px-2 py-0.5 rounded-md uppercase tracking-wider">MEJA ${order.meja}</span>
                                    <span class="font-mono text-[9px] text-zinc-500">${order.waktu}</span>
                                </div>
                                <p class="text-xs font-bold text-zinc-200 tracking-wide">${order.detail_item}</p>
                            </div>
                            <button onclick="tandaiSelesaiDiantar(${order.id})" class="w-full sm:w-auto shrink-0 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-xl text-xs uppercase tracking-wider transition active:scale-95 cursor-pointer">
                                🚀 SELESAI DIANTAR
                            </button>
                        </div>`;
                });
            }

            // --- B. BAGIAN MONITOR DENAH MEJA ---
            const wadahGridMeja = document.getElementById('waiter-grid-meja');
            wadahGridMeja.innerHTML = '';
            
            semuaMeja.forEach(meja => {
                let isTerisi = meja.isi == 1;
                let bgStyle = isTerisi 
                    ? 'bg-emerald-600/10 border-2 border-emerald-500 text-emerald-400 font-black' 
                    : 'bg-zinc-950 border border-zinc-850 text-zinc-600';

                wadahGridMeja.innerHTML += `
                    <div class="${bgStyle} p-4 rounded-xl text-center flex flex-col justify-center items-center h-16 transition">
                        <span class="text-sm font-bold block">${meja.nomor}</span>
                        <span class="text-[8px] uppercase tracking-wider opacity-70 font-semibold">${isTerisi ? 'TERISI' : 'KOSONG'}</span>
                    </div>`;
            });
        }

        // 3. Fungsi ketika Pelayan menyerahkan hidangan ke meja pelanggan
        function tandaiSelesaiDiantar(id) {
            localStorage.setItem(`sudah_diantar_${id}`, true);
            muatDataWaiter();
        }

        // Sinkronisasi background otomatis mendeteksi rilis makanan baru setiap 3 detik
        setInterval(muatDataWaiter, 3000);

        // Memuat data pertama kali saat halaman navigator pelayan dibuka
        muatDataWaiter();
    </script>
</body>
</html>