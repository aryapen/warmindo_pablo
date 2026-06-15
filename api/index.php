<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warmindo Pablo - Pesan Online</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-zinc-950 font-sans text-zinc-100 selection:bg-red-600 selection:text-white pb-32">

    <nav class="bg-black border-b border-zinc-800 p-5 shadow-md sticky top-0 z-50">
        <div class="container mx-auto flex flex-col sm:flex-row justify-between items-center gap-3">
            <div>
                <h1 class="text-xl font-black tracking-widest text-center sm:text-left text-white">WARMINDO <span class="text-red-600">PABLO</span></h1>
                <p class="text-[10px] text-zinc-400 mt-1 text-center sm:text-left">Jl. Podosugih No. 24, Pekalongan Barat</p>
            </div>
            
            <div class="bg-zinc-900 border border-zinc-800 px-4 py-2 rounded-xl flex items-center gap-3 text-xs shadow-inner">
                <div class="flex items-center justify-center bg-red-600/10 text-red-400 border border-red-500/20 font-mono font-black w-6 h-6 rounded-md" id="badge-total-item">0</div>
                <div class="text-[10px] font-bold text-zinc-400 tracking-wider uppercase">Item Keranjang</div>
                <div class="h-4 w-px bg-zinc-800"></div>
                <div class="text-[11px] font-black text-white bg-black border border-zinc-800 px-2.5 py-1 rounded-md tracking-wider" id="label-meja-pilihan">BELUM PILIH</div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 mt-8 max-w-5xl">
        <div class="mb-10">
            <div class="flex items-center gap-2 mb-4">
                <span class="w-1.5 h-4 bg-red-600 rounded-full"></span>
                <h2 class="text-sm font-black tracking-widest uppercase text-white">Langkah 1: Pilih Nomor Meja Anda</h2>
            </div>
            <div class="grid grid-cols-3 sm:grid-cols-5 gap-3" id="grid-meja-pablo">
                </div>
        </div>

        <div>
            <div class="flex items-center gap-2 mb-6">
                <span class="w-1.5 h-4 bg-red-600 rounded-full"></span>
                <h2 class="text-sm font-black tracking-widest uppercase text-white">Langkah 2: Pilih Menu Tergacor</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="wadah-menu-makanan">
                </div>
        </div>
    </div>

    <div class="fixed bottom-0 left-0 right-0 bg-black/95 backdrop-blur-md border-t border-zinc-900 p-4 shadow-2xl z-40">
        <div class="container mx-auto max-w-5xl flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4">
            <div class="grid grid-cols-2 gap-3 flex-grow">
                <input type="text" id="catatan-pesanan" placeholder="Catatan: Pedas, gapake sawi, telur setengah mateng..." class="bg-zinc-950 border border-zinc-900 text-xs text-white p-3.5 rounded-xl placeholder-zinc-600 focus:outline-none focus:border-red-600 tracking-wide transition">
                <input type="text" id="request-musik" placeholder="Request Lagu: Denny Caknan - Cundamani..." class="bg-zinc-950 border border-zinc-900 text-xs text-white p-3.5 rounded-xl placeholder-zinc-600 focus:outline-none focus:border-red-600 tracking-wide transition">
            </div>
            
            <div class="flex items-center justify-between md:justify-end gap-6 bg-zinc-950 border border-zinc-900 p-2 pl-4 rounded-xl shrink-0">
                <div class="text-left">
                    <span class="text-[9px] tracking-widest uppercase text-zinc-500 font-bold block">Total Pembayaran</span>
                    <span class="text-sm font-mono font-black text-red-500" id="ringkasan-total-bayar">Rp 0</span>
                </div>
                <button onclick="kirimPesanankeDapur()" class="bg-red-600 hover:bg-red-700 text-white font-black text-xs py-3.5 px-6 rounded-lg tracking-widest uppercase transition duration-200 active:scale-95 shadow-lg shadow-red-900/20 cursor-pointer">
                    KIRIM KE DAPUR →
                </button>
            </div>
        </div>
    </div>

    <script>
        const API_MENU = "https://6a2babdb3e2b60ab038e98af.mockapi.io/menu";
        const API_RIWAYAT = "https://6a2babdb3e2b60ab038e98af.mockapi.io/riwayat";

        let databaseMenu = [];
        let semuaMejaDataLokal = [];
        let keranjang = {};
        let mejaTerpilih = null;

        // 1. Fungsi Sinkronisasi Data dari 2 Endpoint Mock API
        async function sinkronisasiData() {
            try {
                const [resMenu, resRiwayat] = await Promise.all([
                    fetch(API_MENU),
                    fetch(API_RIWAYAT)
                ]);

                const dataGabunganMenu = await resMenu.json();
                
                // Pisahkan data makanan asli dan data tiruan status meja
                databaseMenu = dataGabunganMenu.filter(item => item.nama !== "STATUS_MEJA");
                
                // Petakan ulang struktur data tiruan meja agar sesuai dengan UI asli
                semuaMejaDataLokal = dataGabunganMenu
                    .filter(item => item.nama === "STATUS_MEJA")
                    .map(m => ({
                        id: m.id,
                        nomor: parseInt(m.modal),
                        isi: parseInt(m.stok)
                    }))
                    .sort((a, b) => a.nomor - b.nomor);

                renderMeja(semuaMejaDataLokal);
                renderMenu(databaseMenu);
                updateKeranjangUI();
            } catch (error) {
                console.error("Gagal sinkronisasi data dengan Mock API:", error);
            }
        }

        // 2. Render Grid Meja (UI/UX Asli Dipertahankan)
        function renderMeja(daftarMeja) {
            const wadahGridMeja = document.getElementById('grid-meja-pablo');
            wadahGridMeja.innerHTML = '';

            daftarMeja.forEach(meja => {
                let isTerisi = meja.isi == 1;
                let isDipilih = meja.nomor === mejaTerpilih;

                let styleWarna = '';
                if (isTerisi) {
                    styleWarna = 'bg-zinc-900 border border-zinc-800 text-zinc-600 opacity-50 cursor-not-allowed';
                } else if (isDipilih) {
                    styleWarna = 'bg-red-600 border-2 border-red-500 text-white font-black scale-105 shadow-lg shadow-red-900/30';
                } else {
                    styleWarna = 'bg-zinc-900 border border-zinc-800 hover:border-zinc-700 text-zinc-300 hover:text-white cursor-pointer transition';
                }

                wadahGridMeja.innerHTML += `
                    <div onclick="${isTerisi ? '' : `pilihMeja(${meja.nomor})`}" class="${styleWarna} p-4 rounded-xl text-center flex flex-col justify-center items-center h-20">
                        <span class="text-lg font-black block">${meja.nomor}</span>
                        <span class="text-[9px] tracking-widest uppercase font-bold opacity-70">${isTerisi ? 'TERISI' : (isDipilih ? 'ANDA' : 'KOSONG')}</span>
                    </div>`;
            });
        }

        function pilihMeja(nomor) {
            mejaTerpilih = nomor;
            renderMeja(semuaMejaDataLokal);
            updateKeranjangUI();
        }

        // 3. Render Card Menu Makanan (UI/UX Asli Dipertahankan)
        function renderMenu(daftarMenu) {
            const wadahMenu = document.getElementById('wadah-menu-makanan');
            wadahMenu.innerHTML = '';

            daftarMenu.forEach(item => {
                let sisaStok = item.stok;
                let isHabis = sisaStok <= 0;

                wadahMenu.innerHTML += `
                    <div class="bg-black border border-zinc-900 rounded-2xl overflow-hidden shadow-xl flex flex-col transition hover:border-zinc-800">
                        <div class="p-5 flex-grow">
                            <div class="flex justify-between items-start gap-2">
                                <h3 class="font-bold text-base text-zinc-100 tracking-wide">${item.nama}</h3>
                                <span class="bg-zinc-900 border border-zinc-800 text-red-500 font-mono font-bold text-xs px-2.5 py-1 rounded-lg shrink-0">
                                    Rp ${parseInt(item.harga).toLocaleString('id-ID')}
                                </span>
                            </div>
                            <p class="text-[10px] ${isHabis ? 'text-red-500 font-bold' : 'text-zinc-500'} mt-2">
                                📦 Sisa Stok: ${isHabis ? 'HABIS' : `${sisaStok} Porsi`}
                            </p>
                        </div>
                        <div class="p-4 bg-zinc-950 border-t border-zinc-900 flex justify-between items-center gap-2">
                            <span class="text-[10px] text-zinc-400 font-medium">Jumlah Pesanan:</span>
                            <div class="flex items-center gap-1 bg-black border border-zinc-800 rounded-xl p-1">
                                <button onclick="kurangKeranjang(${item.id})" class="w-7 h-7 bg-zinc-900 hover:bg-zinc-800 text-zinc-300 rounded-lg text-sm font-bold active:scale-90 transition cursor-pointer">-</button>
                                <span class="w-8 text-center font-mono text-xs font-bold text-white">${keranjang[item.id] || 0}</span>
                                <button onclick="tambahKeranjang(${item.id}, ${sisaStok})" class="w-7 h-7 bg-zinc-900 hover:bg-zinc-800 text-zinc-300 rounded-lg text-sm font-bold active:scale-90 transition cursor-pointer" ${isHabis ? 'disabled' : ''}>+</button>
                            </div>
                        </div>
                    </div>`;
            });
        }

        function tambahKeranjang(id, stok) {
            let saatIni = keranjang[id] || 0;
            if (saatIni >= stok) {
                alert("Waduh, tidak bisa memesan melebihi sisa stok yang ada di dapur!");
                return;
            }
            keranjang[id] = saatIni + 1;
            renderMenu(databaseMenu);
            updateKeranjangUI();
        }

        function kurangKeranjang(id) {
            if (keranjang[id]) {
                keranjang[id] -= 1;
                if (keranjang[id] === 0) delete keranjang[id];
            }
            renderMenu(databaseMenu);
            updateKeranjangUI();
        }

        function updateKeranjangUI() {
            let totalItem = 0;
            let totalBayar = 0;

            for (const id in keranjang) {
                let item = databaseMenu.find(m => m.id == id);
                if (item) {
                    totalItem += keranjang[id];
                    totalBayar += (item.harga * keranjang[id]);
                }
            }

            document.getElementById('badge-total-item').innerText = totalItem;
            document.getElementById('label-meja-pilihan').innerText = mejaTerpilih ? `MEJA ${mejaTerpilih}` : 'BELUM PILIH';
            document.getElementById('ringkasan-total-bayar').innerText = 'Rp ' + totalBayar.toLocaleString('id-ID');
        }

        // 4. Proses Submit Pesanan (PUT Pengurangan Stok & Status Meja + POST /riwayat)
        async function kirimPesanankeDapur() {
            if (!mejaTerpilih) return alert("Silakan klik nomor meja Anda!");
            if (Object.keys(keranjang).length === 0) return alert("Keranjang kosong!");

            let detailTeks = [];
            let totalJual = 0;
            let totalModal = 0;

            // Kurangi stok menu makanan satu per satu via PUT
            for (const id of Object.keys(keranjang)) {
                let item = databaseMenu.find(m => m.id == id);
                if (item) {
                    let qty = keranjang[id];
                    detailTeks.push(`${item.nama} (x${qty})`);
                    totalJual += (item.harga * qty);
                    totalModal += (item.modal * qty);

                    await fetch(`${API_MENU}/${id}`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ stok: item.stok - qty })
                    });
                }
            }

            // Membaca input catatan & request musik pelanggan
            let catatan = document.getElementById('catatan-pesanan').value.trim();
            let musik = document.getElementById('request-musik').value.trim();
            let gabunganTeks = detailTeks.join(', ');
            if (catatan) gabunganTeks += ` [Catatan: ${catatan}]`;
            if (musik) gabunganTeks += ` [Lagu: ${musik}]`;

            try {
                // Ubah status meja titipan di dalam endpoint /menu menjadi TERISI (stok = 1)
                await fetch(`${API_MENU}/meja_${mejaTerpilih}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ stok: 1 })
                });

                // Simpan transaksi baru ke endpoint /riwayat via POST
                const waktuJam = new Date().toLocaleTimeString('id-ID', { hour12: false });
                const kirim = await fetch(API_RIWAYAT, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        meja: mejaTerpilih,
                        detail_item: gabunganTeks,
                        total_jual: totalJual,
                        total_modal: totalModal,
                        waktu: waktuJam
                    })
                });

                if (kirim.ok) {
                    alert(`Pesanan Meja ${mejaTerpilih} sukses dikirim ke Dapur! 🚀`);
                    keranjang = {};
                    mejaTerpilih = null;
                    document.getElementById('catatan-pesanan').value = "";
                    document.getElementById('request-musik').value = "";
                    sinkronisasiData();
                } else {
                    alert("Gagal memproses pesanan ke Mock API.");
                }
            } catch (err) {
                console.error("Detail Error:", err);
                alert("Error jaringan database Mock API!");
            }
        }

        // Cek database otomatis setiap 4 detik secara real-time
        setInterval(sinkronisasiData, 4000);
        sinkronisasiData();
    </script>
</body>
</html>