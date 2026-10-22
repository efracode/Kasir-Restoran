<?php
require 'config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'pesan' => 'Metode request tidak diizinkan.']);
    exit;
}

$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);

if (!$data || empty($data['items'])) {
    echo json_encode(['success' => false, 'pesan' => 'Keranjang pesanan masih kosong.']);
    exit;
}

$items = $data['items'];
$totalHarga = 0;

foreach ($items as $it) {
    $menuId = (int) ($it['id'] ?? 0);
    $qty = (int) ($it['qty'] ?? 0);

    if ($menuId <= 0 || $qty <= 0) {
        echo json_encode(['success' => false, 'pesan' => 'Data item pesanan tidak valid.']);
        exit;
    }

    $stmtCheck = mysqli_prepare($koneksi, "SELECT nama_menu, harga, stock FROM menu WHERE id = ?");
    mysqli_stmt_bind_param($stmtCheck, "i", $menuId);
    mysqli_stmt_execute($stmtCheck);
    $resCheck = mysqli_stmt_get_result($stmtCheck);
    $dbMenu = mysqli_fetch_assoc($resCheck);
    mysqli_stmt_close($stmtCheck);

    if (!$dbMenu) {
        echo json_encode(['success' => false, 'pesan' => 'Menu tidak ditemukan di database.']);
        exit;
    }

    if ($dbMenu['stock'] < $qty) {
        echo json_encode([
            'success' => false,
            'pesan' => 'Stok menu "' . $dbMenu['nama_menu'] . '" tidak mencukupi (sisa ' . $dbMenu['stock'] . ' unit).'
        ]);
        exit;
    }

    $totalHarga += ($dbMenu['harga'] * $qty);
}

$totalBayar = (int) ($data['total_bayar'] ?? 0);
if ($totalBayar < $totalHarga) {
    echo json_encode([
        'success' => false,
        'pesan' => 'Nominal pembayaran kurang Rp ' . number_format($totalHarga - $totalBayar, 0, ',', '.') . '.'
    ]);
    exit;
}

$kembalian = $totalBayar - $totalHarga;
$noTransaksi = 'TRX-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
$tanggal = date('Y-m-d H:i:s');
$namaKasir = !empty($data['nama_kasir']) ? trim($data['nama_kasir']) : 'Kasir Resto';
$metodeBayar = !empty($data['metode_bayar']) ? trim($data['metode_bayar']) : 'Tunai';

mysqli_begin_transaction($koneksi);

try {
    $stmtTrx = mysqli_prepare($koneksi, "INSERT INTO transaksi (no_transaksi, tanggal, total_harga, total_bayar, kembalian, metode_bayar, nama_kasir) VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmtTrx, "ssiiiss", $noTransaksi, $tanggal, $totalHarga, $totalBayar, $kembalian, $metodeBayar, $namaKasir);
    mysqli_stmt_execute($stmtTrx);
    $transaksiId = mysqli_insert_id($koneksi);
    mysqli_stmt_close($stmtTrx);

    $stmtDetail = mysqli_prepare($koneksi, "INSERT INTO transaksi_detail (transaksi_id, menu_id, nama_menu, harga, jumlah, subtotal) VALUES (?, ?, ?, ?, ?, ?)");
    $stmtUpdateStock = mysqli_prepare($koneksi, "UPDATE menu SET stock = stock - ? WHERE id = ?");

    $receiptItems = [];

    foreach ($items as $it) {
        $menuId = (int) $it['id'];
        $qty = (int) $it['qty'];

        $stmtGet = mysqli_prepare($koneksi, "SELECT nama_menu, harga FROM menu WHERE id = ?");
        mysqli_stmt_bind_param($stmtGet, "i", $menuId);
        mysqli_stmt_execute($stmtGet);
        $resGet = mysqli_stmt_get_result($stmtGet);
        $itemRow = mysqli_fetch_assoc($resGet);
        mysqli_stmt_close($stmtGet);

        $subtotal = $itemRow['harga'] * $qty;

        mysqli_stmt_bind_param($stmtDetail, "iisiii", $transaksiId, $menuId, $itemRow['nama_menu'], $itemRow['harga'], $qty, $subtotal);
        mysqli_stmt_execute($stmtDetail);

        mysqli_stmt_bind_param($stmtUpdateStock, "ii", $qty, $menuId);
        mysqli_stmt_execute($stmtUpdateStock);

        $receiptItems[] = [
            'nama_menu' => $itemRow['nama_menu'],
            'harga' => $itemRow['harga'],
            'jumlah' => $qty,
            'subtotal' => $subtotal
        ];
    }

    mysqli_stmt_close($stmtDetail);
    mysqli_stmt_close($stmtUpdateStock);

    mysqli_commit($koneksi);

    echo json_encode([
        'success' => true,
        'pesan' => 'Transaksi berhasil diproses!',
        'transaksi' => [
            'id' => $transaksiId,
            'no_transaksi' => $noTransaksi,
            'tanggal' => date('d/m/Y H:i', strtotime($tanggal)),
            'total_harga' => $totalHarga,
            'total_bayar' => $totalBayar,
            'kembalian' => $kembalian,
            'nama_kasir' => $namaKasir,
            'metode_bayar' => $metodeBayar,
            'items' => $receiptItems
        ]
    ]);
} catch (Exception $e) {
    mysqli_rollback($koneksi);
    echo json_encode([
        'success' => false,
        'pesan' => 'Gagal memproses transaksi: ' . $e->getMessage()
    ]);
}
?>
