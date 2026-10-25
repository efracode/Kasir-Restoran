<?php
require 'config/database.php';

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

if ($id) {
    $namaMenu = '';
    $stmtFind = mysqli_prepare($koneksi, "SELECT nama_menu FROM menu WHERE id = ?");
    if ($stmtFind) {
        mysqli_stmt_bind_param($stmtFind, "i", $id);
        mysqli_stmt_execute($stmtFind);
        mysqli_stmt_bind_result($stmtFind, $namaMenu);
        mysqli_stmt_fetch($stmtFind);
        mysqli_stmt_close($stmtFind);
    }

    $stmt = mysqli_prepare($koneksi, "DELETE FROM menu WHERE id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            $pesan = $namaMenu !== ''
                ? "Menu \"" . htmlspecialchars($namaMenu) . "\" berhasil dihapus!"
                : "Menu berhasil dihapus!";
            $_SESSION['pesan'] = $pesan;
            $_SESSION['tipe'] = "sukses";
        } else {
            $_SESSION['pesan'] = "Gagal menghapus data menu: " . mysqli_error($koneksi);
            $_SESSION['tipe'] = "error";
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['pesan'] = "Gagal memproses penghapusan data.";
        $_SESSION['tipe'] = "error";
    }
} else {
    $_SESSION['pesan'] = "ID menu tidak valid untuk dihapus.";
    $_SESSION['tipe'] = "error";
}

header("Location: index.php");
exit;
?>