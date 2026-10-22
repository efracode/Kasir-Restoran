<?php
require 'config/database.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $query = "DELETE FROM menu WHERE id = $id";
     if (mysqli_query($koneksi, $query)) {
        header("Location: hpsPopup.php");
        exit();
    } else {
        $_SESSION['pesan'] = "Gagal menghapus data";
        $_SESSION['tipe'] = "error";
        header("Location: index.php");
        exit();
    }
}

header("Location: index.php");
exit;
?>