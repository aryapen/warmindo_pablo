<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "", "warmindo_pablo");

if ($conn->connect_error) {
    die(json_encode(["error" => "Koneksi database gagal"]));
}

$action = $_GET['action'] ?? '';

// 1. Ambil Semua Data Menu & Meja
if ($action == 'get_data') {
    $menu = $conn->query("SELECT * FROM menu")->fetch_all(MYSQLI_ASSOC);
    $meja = $conn->query("SELECT * FROM meja ORDER BY nomor ASC")->fetch_all(MYSQLI_ASSOC);
    $riwayat = $conn->query("SELECT * FROM riwayat ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
    echo json_encode(["menu" => $menu, "meja" => $meja, "riwayat" => $riwayat]);
}

// 2. Proses Pesanan Pelanggan
if ($action == 'buat_pesanan') {
    $data = json_decode(file_get_contents("php://input"), true);
    $meja_no = $data['meja'];
    $keranjang = $data['keranjang']; // Array isi id => qty
    
    $detail_item = [];
    $total_jual = 0;
    $total_modal = 0;

    foreach ($keranjang as $id => $qty) {
        $menu = $conn->query("SELECT * FROM menu WHERE id = $id")->fetch_assoc();
        if ($menu && $menu['stok'] >= $qty) {
            $sisa_stok = $menu['stok'] - $qty;
            $conn->query("UPDATE menu SET stok = $sisa_stok WHERE id = $id");
            
            $total_jual += ($menu['harga'] * $qty);
            $total_modal += ($menu['modal'] * $qty);
            $detail_item[] = $menu['nama'] . " (x" . $qty . ")";
        }
    }

    if ($total_jual > 0) {
        $conn->query("UPDATE meja SET isi = 1 WHERE nomor = $meja_no");
        $items_string = implode(", ", $detail_item);
        $waktu = date("H:i:s");
        $conn->query("INSERT INTO riwayat (meja, detail_item, total_jual, total_modal, waktu) VALUES ($meja_no, '$items_string', $total_jual, $total_modal, '$waktu')");
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal memproses pesanan"]);
    }
}

// 3. Admin: Tambah Menu Baru
if ($action == 'tambah_menu') {
    $nama = $_POST['nama']; $modal = $_POST['modal']; $harga = $_POST['harga']; $stok = $_POST['stok'];
    $conn->query("INSERT INTO menu (nama, modal, harga, stok) VALUES ('$nama', $modal, $harga, $stok)");
    echo json_encode(["status" => "success"]);
}

// 4. Admin: Tambah Stok Cepat atau Ketik Langsung
if ($action == 'update_stok') {
    $id = $_GET['id']; $stok = $_GET['stok'];
    $conn->query("UPDATE menu SET stok = $stok WHERE id = $id");
    echo json_encode(["status" => "success"]);
}

// 5. Admin: Kosongkan Meja (Pelanggan selesai)
if ($action == 'kosongkan_meja') {
    $nomor = $_GET['nomor'];
    $conn->query("UPDATE meja SET isi = 0 WHERE nomor = $nomor");
    echo json_encode(["status" => "success"]);
}

// 6. Admin: Hapus Menu
if ($action == 'hapus_menu') {
    $id = $_GET['id'];
    $conn->query("DELETE FROM menu WHERE id = $id");
    echo json_encode(["status" => "success"]);
}
?>