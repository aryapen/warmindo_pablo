<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warmindo Pablo - Dashboard Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-zinc-950 font-sans text-zinc-100 selection:bg-red-600 selection:text-white">

    <nav class="bg-black border-b border-zinc-800 text-white p-5 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <div>
                <h1 class="text-xl font-black tracking-widest text-white">PABLO <span class="text-red-600">CLOUD ADMIN</span></h1>
                <p class="text-[10px] text-zinc-400 mt-1">Jl. Podosugih No. 24, Pekalongan Barat</p>
            </div>
            <a href="index.php" class="text-xs bg-zinc-900 border border-zinc-800 px-4 py-2 rounded-lg text-zinc-300 hover:text-white hover:bg-zinc-800 transition duration-200">
                ← Buka Web Pelanggan
            </a>
        </div>
    </nav>

    <div class="container mx-auto px-4 mt-8 pb-16 max-w-6xl">
        
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-zinc-900 border border-zinc-850 p-5 rounded-2xl shadow-xl">
                <span class="text-[10px] tracking-widest uppercase text-zinc-500 font-bold block mb-1">Total Omzet Penjualan</span>
                <span class="text-2xl font-mono font-black text-white" id="stat-omzet">Rp 0</span>
            </div>
            <div class="bg-zinc-900 border border-zinc-850 p-5 rounded-2xl shadow-xl">
                <span class="text-[10px] tracking-widest uppercase text-zinc-500 font-bold block mb-1">Estimasi Laba Bersih</span>
                <span class="text-2xl font-mono font-black text-red-500" id="stat-laba">Rp 0</span>
            </div>
            <div class="bg-zinc-900 border border-zinc-850 p-5 rounded-2xl shadow-xl">
                <span class="text-[10px] tracking-widest uppercase text-zinc-500 font-bold block mb-1">Total Transaksi Selesai</span>
                <span class="text-2xl font-mono font-black text-zinc-300" id="stat-transaksi">0 Nota</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="space-y-8">
                <div class="bg-zinc-900 border border-zinc-850 p-5 rounded-2xl shadow-xl">
                    <h2 class="text-xs font-black tracking-widest uppercase text-white mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-3 bg-red-600 rounded-full"></span> Master Tambah Menu Baru
                    </h2>
                    <form id="form-menu" class="space-y-4">
                        <div>
                            <label class="block text-[10px] uppercase font-bold text-zinc-400 mb-1.5">Nama Makanan/Minuman</label>
                            <input type="text" name="nama" required placeholder="Contoh: Indomie Kari Ayam" class="w-full bg-black border border-zinc-800 text-xs text-white p-3 rounded-xl focus:outline-none focus:border-red-600 transition">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] uppercase font-bold text-zinc-400 mb-1.5">Harga Modal (Rp)</label>
                                <input type="number" name="modal" required placeholder="3000" class="w-full bg-black border border-zinc-800 text-xs text-white font-mono p-3 rounded-xl focus:outline-none focus:border-red-600 transition">
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase font-bold text-zinc-400 mb-1.5">Harga Jual (Rp)</label>
                                <input type="number" name="harga" required placeholder="6000" class="w-full bg-black border border-zinc-800 text-xs text-white font-mono p-3 rounded-xl focus:outline-none focus:border-red-600 transition">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-bold text-zinc-400 mb-1.5">Stok Awal Porsi</label>
                            <input type="number" name="stok" required placeholder="50" class="w-full bg-black border border-zinc-800 text-xs text-white font-mono p-3 rounded-xl focus:outline-none focus:border-red-600 transition">
                        </div>
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-black text-xs py-3.5 rounded-xl tracking-widest uppercase transition duration-200 active:scale-95 cursor-pointer shadow-lg shadow-red-900/10">
                            SIMPAN KE CLOUD CLUSTER
                        </button>
                    </form>
                </div>

                <div>
                    <h2 class="text-xs font-black tracking-widest uppercase text-white mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-3 bg-red-600 rounded-full"></span> Kasir: Kontrol Meja Makan
                    </h2>
                    <div class="grid grid-cols-1 gap-2.5" id="grid-admin-meja">
                        </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-8">
                <div class="bg-zinc-900 border border-zinc-850 rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-5 border-b border-zinc-850">
                        <h2 class="text-xs font-black tracking-widest uppercase text-white flex items-center gap-2">
                            <span class="w-1.5 h-3 bg-red-600 rounded-full"></span> Manajemen Stok & Katalog Jualan
                        </h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-zinc-950/60 border-b border-zinc-850 text-[10px] font-black uppercase tracking-wider text-zinc-400">
                                    <th class="p-4">Item Menu</th>
                                    <th class="p-4">Modal</th>
                                    <th class="p-4">Harga Jual</th>
                                    <th class="p-4">Sisa Stok</th>
                                    <th class="p-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-menu-pablo">
                                </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-zinc-900 border border-zinc-850 rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-5 border-b border-zinc-850">
                        <h2 class="text-xs font-black tracking-widest uppercase text-white flex items-center gap-2">
                            <span class="w-1.5 h-3 bg-red-600 rounded-full"></span> Jurnal Riwayat Transaksi Masuk
                        </h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-zinc-950/60 border-b border-zinc-850 text-[10px] font-black uppercase tracking-wider text-zinc-400">
                                    <th class="p-4">No. Nota</th>
                                    <th class="p-4">Waktu</th>
                                    <th class="p-4">Meja</th>
                                    <th class="p-4">Rincian Pembelian Item</th>
                                    <th class="p-4">Total Jual</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-riwayat-pablo">
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const API_MENU = "https://6a2babdb3e2b60ab038e98af.mockapi.io/menu";
        const API_RIWAYAT = "https://6a2babdb3e2b60ab038e98af.mockapi.io/riwayat";

        let dataMenu = [];
        let dataMeja = [];
        let dataRiwayat = [];

        // 1. Ambil Seluruh Komponen Data dari Mock API
        async function muatDataAdmin() {
            try {
                const [resMenu, resRiwayat] = await Promise.all([
                    fetch(API_MENU),
                    fetch(API_RIWAYAT)
                ]);

                const gabunganMenu = await resMenu.json();
                dataRiwayat = await resRiwayat.json();

                // Memisahkan data makanan asli dan data meja tiruan
                dataMenu = gabunganMenu.filter(item => item.nama !== "STATUS_MEJA");
                dataMeja = gabunganMenu
                    .filter(item => item.nama === "STATUS_MEJA")
                    .map(m => ({
                        id: m.id,
                        nomor: parseInt(m.modal),
                        isi: parseInt(m.stok)
                    }))
                    .sort((a, b) => a.nomor - b.nomor);

                renderStatistikLaporan();
                renderTabelMenu();
                renderTabelMeja();
                renderTabelRiwayat();
            } catch (err) {
                console.error("Gagal sinkronisasi data Admin:", err);
            }
        }

        // 2. Render Panel Atas Laporan Keuntungan
        function renderStatistikLaporan() {
            let omzet = 0;
            let totalModal = 0;

            dataRiwayat.forEach(row => {
                omzet += parseInt(row.total_jual) || 0;
                totalModal += parseInt(row.total_modal) || 0;
            });

            let labaBersih = omzet - totalModal;

            document.getElementById('stat-omzet').innerText = 'Rp ' + omzet.toLocaleString('id-ID');
            document.getElementById('stat-laba').innerText = 'Rp ' + labaBersih.toLocaleString('id-ID');
            document.getElementById('stat-transaksi').innerText = dataRiwayat.length + ' Nota';
        }

        // 3. Render Baris Tabel Stok Menu Jualan
        function renderTabelMenu() {
            const wadahTable = document.getElementById('tbody-menu-pablo');
            wadahTable.innerHTML = '';

            dataMenu.forEach(item => {
                wadahTable.innerHTML += `
                    <tr class="border-b border-zinc-900 text-xs text-zinc-300 hover:bg-zinc-900/40">
                        <td class="p-4 font-bold text-white">${item.nama}</td>
                        <td class="p-4 font-mono">Rp ${parseInt(item.modal).toLocaleString('id-ID')}</td>
                        <td class="p-4 font-mono text-red-400 font-bold">Rp ${parseInt(item.harga).toLocaleString('id-ID')}</td>
                        <td class="p-4">
                            <input type="number" value="${item.stok}" onchange="updateStok('${item.id}', this.value)" class="w-16 bg-black border border-zinc-800 text-center font-mono rounded-lg p-1 text-white font-bold text-xs focus:border-red-500 focus:outline-none">
                        </td>
                        <td class="p-4">
                            <div class="flex gap-1.5">
                                <button onclick="tambahStokCepat('${item.id}', ${item.stok}, 10)" class="bg-zinc-900 hover:bg-zinc-800 border border-zinc-800 text-[10px] text-zinc-300 px-2 py-1 rounded-md font-bold cursor-pointer transition">+10</button>
                                <button onclick="hapusMenu('${item.id}')" class="bg-red-950/50 hover:bg-red-900 border border-red-900/30 text-[10px] text-red-400 px-2 py-1 rounded-md font-bold cursor-pointer transition">Hapus</button>
                            </div>
                        </td>
                    </tr>`;
            });
        }

        // 4. Render Kartu Monitoring Meja Makan
        function renderTabelMeja() {
            const wadahGrid = document.getElementById('grid-admin-meja');
            wadahGrid.innerHTML = '';

            dataMeja.forEach(meja => {
                let isTerisi = meja.isi == 1;
                let bgStyle = isTerisi 
                    ? 'bg-red-950/20 border border-red-900/50 text-red-400' 
                    : 'bg-zinc-900 border border-zinc-850 text-zinc-500';

                wadahGrid.innerHTML += `
                    <div class="${bgStyle} p-4 rounded-xl flex justify-between items-center transition shadow-md">
                        <div>
                            <span class="text-xs font-black block ${isTerisi ? 'text-red-400' : 'text-zinc-300'}">MEJA ${meja.nomor}</span>
                            <span class="text-[8px] font-bold tracking-wider opacity-80">${isTerisi ? '⚠️ RESTO SEAT FULL' : '✅ AVAILABLE'}</span>
                        </div>
                        ${isTerisi ? `<button onclick="kosongkanMeja('${meja.nomor}')" class="bg-red-600 hover:bg-red-700 text-white font-bold text-[9px] px-2.5 py-1.5 rounded-lg tracking-wider uppercase shadow-md shadow-red-900/20 active:scale-95 cursor-pointer transition">RESET</button>` : ''}
                    </div>`;
            });
        }

        // 5. Render Log Histori Jurnal Pembelian
        function renderTabelRiwayat() {
            const wadahRiwayat = document.getElementById('tbody-riwayat-pablo');
            wadahRiwayat.innerHTML = '';

            // Mengurutkan riwayat dari ID terbesar agar nota terbaru ada di atas
            const riwayatTerbaru = [...dataRiwayat].sort((a, b) => b.id - a.id);

            riwayatTerbaru.forEach(row => {
                wadahRiwayat.innerHTML += `
                    <tr class="border-b border-zinc-900 text-[11px] text-zinc-400 hover:bg-zinc-900/20">
                        <td class="p-4 font-mono font-bold text-zinc-300">#PAB-${row.id}</td>
                        <td class="p-4 font-mono text-zinc-400">${row.waktu}</td>
                        <td class="p-4"><span class="bg-zinc-900 border border-zinc-800 text-zinc-300 font-bold text-[9px] px-2 py-0.5 rounded-md">MEJA ${row.meja}</span></td>
                        <td class="p-4 text-zinc-300 font-medium max-w-xs truncate" title="${row.detail_item}">${row.detail_item}</td>
                        <td class="p-4 font-mono text-white font-bold">Rp ${parseInt(row.total_jual).toLocaleString('id-ID')}</td>
                    </tr>`;
            });
        }

        // 6. Event Submit: Tambah Menu Baru (POST ke /menu)
        document.getElementById('form-menu').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            const payload = {
                nama: formData.get('nama'),
                modal: parseInt(formData.get('modal')) || 0,
                harga: parseInt(formData.get('harga')) || 0,
                stok: parseInt(formData.get('stok')) || 0
            };

            try {
                const kirim = await fetch(API_MENU, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                if (kirim.ok) {
                    this.reset();
                    muatDataAdmin();
                }
            } catch (err) { alert('Gagal menambahkan menu baru ke Cloud!'); }
        });

        // 7. Edit Nilai Input Sisa Stok (PUT ke /menu/:id)
        async function updateStok(id, val) {
            try {
                await fetch(`${API_MENU}/${id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ stok: parseInt(val) || 0 })
                });
                muatDataAdmin();
            } catch (err) { console.error("Gagal update stok:", err); }
        }

        async function tambahStokCepat(id, stokSekarang, jumlah) {
            await updateStok(id, parseInt(stokSekarang) + jumlah);
        }

        // 8. Kasir Reset Meja Kosong Kembali (PUT ke /menu/meja_:nomor)
        async function kosongkanMeja(nomor) {
            if (confirm(`Apakah Meja ${nomor} sudah melakukan pembayaran & siap dikosongkan?`)) {
                try {
                    const kirim = await fetch(`${API_MENU}/meja_${nomor}`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ stok: 0 }) // kembalikan ke 0 (kosong)
                    });
                    if (kirim.ok) muatDataAdmin();
                } catch (err) { alert('Gagal mengosongkan status meja!'); }
            }
        }

        // 9. Hapus Menu Jualan Permanen (DELETE ke /menu/:id)
        async function hapusMenu(id) {
            if (confirm('Hapus menu pablo ini secara permanen dari Mock API Cloud Cluster?')) {
                try {
                    const kirim = await fetch(`${API_MENU}/${id}`, { method: 'DELETE' });
                    if (kirim.ok) muatDataAdmin();
                } catch (err) { alert('Gagal menghapus menu!'); }
            }
        }

        // Sinkronisasi data background berkala setiap 4 detik
        setInterval(muatDataAdmin, 4000);

        // Memuat seluruh metrik dashboard saat pertama kali login dibuka
        muatDataAdmin();
    </script>
</body>
</html>