<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warmindo Pablo - Monitor Dapur Chef</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-zinc-950 font-sans text-zinc-100 selection:bg-orange-600 selection:text-white">

    <nav class="bg-black border-b border-orange-500 text-white p-5 shadow-xl sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div>
                <h1 class="text-xl font-black tracking-widest text-orange-500">PABLO <span class="text-white">KITCHEN MONITOR</span></h1>
                <p class="text-[10px] text-zinc-400 mt-1 uppercase tracking-wider">Khusus Layar Monitor Juru Masak / Chef</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-ping"></span>
                <p class="text-xs text-zinc-400 font-medium">Live Mengawasi Dapur...</p>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 mt-8 pb-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="chef-grid-orderan">
            </div>
    </div>

    <script>
        const API_RIWAYAT = "https://6a2babdb3e2b60ab038e98af.mockapi.io/riwayat";

        // 1. Fungsi Ambil Data Orderan Aktif dari Mock API
        async function muatOrderanDapur() {
            try {
                const respon = await fetch(API_RIWAYAT);
                const semuaRiwayat = await respon.json();
                
                // Urutkan berdasarkan ID terkecil (Ascending) agar pesanan lama dimasak duluan
                // Filter pesanan yang belum ditandai selesai dimasak di localStorage
                const orderanBelumMasak = semuaRiwayat
                    .sort((a, b) => a.id - b.id)
                    .filter(order => !localStorage.getItem(`selesai_masak_${order.id}`));

                renderLayarDapur(orderanBelumMasak);
            } catch (err) {
                console.error("Gagal terhubung ke Mock API Dapur:", err);
            }
        }

        // 2. Render Card Antrean Masak (UI/UX Asli Dipertahankan)
        function renderLayarDapur(daftarOrder) {
            const containerGrid = document.getElementById('chef-grid-orderan');
            containerGrid.innerHTML = '';

            if (daftarOrder.length === 0) {
                containerGrid.innerHTML = `
                    <div class="col-span-full bg-zinc-900/30 border border-zinc-900 border-dashed rounded-3xl p-12 text-center">
                        <p class="text-sm text-zinc-500 font-medium">Belum ada pesanan masuk dari pelanggan. Dapur santai! 😎</p>
                    </div>`;
                return;
            }

            daftarOrder.forEach(order => {
                // Memecah string detail_item gabungan menjadi array per menu makanan
                let itemsArray = order.detail_item.split(', ');
                let listHTML = '';
                
                itemsArray.forEach(it => {
                    listHTML += `
                        <li class="bg-black border border-zinc-900 p-3 rounded-xl flex justify-between items-center">
                            <span class="text-xs font-bold text-zinc-200 tracking-wide">${it}</span>
                            <span class="w-2 h-2 bg-orange-500 rounded-full"></span>
                        </li>`;
                });

                containerGrid.innerHTML += `
                    <div class="bg-zinc-900/60 border border-zinc-850 rounded-2xl overflow-hidden shadow-lg flex flex-col transition hover:border-orange-500/30">
                        <div class="p-4 bg-zinc-900/90 border-b border-zinc-850 flex justify-between items-center">
                            <div>
                                <span class="bg-orange-600/10 text-orange-400 border border-orange-500/20 text-[10px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wider">MEJA ${order.meja}</span>
                            </div>
                            <span class="font-mono text-[11px] text-zinc-400 font-bold">${order.waktu}</span>
                        </div>

                        <div class="p-4 flex-grow">
                            <ul class="space-y-2">
                                ${listHTML}
                            </ul>
                        </div>

                        <div class="p-4 bg-zinc-950/40 border-t border-zinc-850">
                            <button onclick="tandaiSelesaiDimasak(${order.id})" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-4 rounded-xl text-sm tracking-wider uppercase transition active:scale-95 shadow-md shadow-orange-900/20 cursor-pointer flex items-center justify-center gap-2">
                                ✅ Selesai Dimasak
                            </button>
                        </div>
                    </div>`;
            });
        }

        // 3. Fungsi ketika Chef klik selesai, data masuk antrean waiter & hilang dari monitor dapur
        function tandaiSelesaiDimasak(id) {
            // Simpan flag di localStorage agar dibaca oleh waiter.php
            localStorage.setItem(`selesai_masak_${id}`, true);
            muatOrderanDapur();
        }

        // Sinkronisasi super cepat: Layar dapur memantau pesanan baru tiap 2 detik sekali
        setInterval(muatOrderanDapur, 2000);

        // Memuat data pertama kali saat monitor dapur diaktifkan
        muatOrderanDapur();
    </script>
</body>
</html>