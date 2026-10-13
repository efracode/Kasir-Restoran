<?php
require 'config/database.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $query = "DELETE FROM menu WHERE id = $id";
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['pesan'] = "Data berhasil dihapus";
        $_SESSION['tipe'] = "sukses";
    } else {
        $_SESSION['pesan'] = "Gagal menghapus data";
        $_SESSION['tipe'] = "error";
    }
}

header("Location: index.php");
exit;
?>