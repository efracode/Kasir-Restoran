<?php
require 'config/database.php';

$aksi = $_POST['aksi'] ?? '';

if ($aksi === 'tambah') {
    $kd = trim($_POST['kd_menu'] ?? '');
    $nama = trim($_POST['nama_menu'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $hargaRaw = $_POST['harga'] ?? '';
    $stockRaw = $_POST['stock'] ?? '';
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    $saveOldInput = function () use ($kd, $nama, $kategori, $hargaRaw, $stockRaw, $deskripsi) {
        $_SESSION['form_data'] = [
            'kd_menu' => $kd,
            'nama_menu' => $nama,
            'kategori' => $kategori,
            'harga' => $hargaRaw,
            'stock' => $stockRaw,
            'deskripsi' => $deskripsi
        ];
    };

    if ($kd === '' || $nama === '' || $kategori === '' || $hargaRaw === '' || $stockRaw === '') {
        $saveOldInput();
        $_SESSION['pesan'] = "Gagal: Semua kolom bertanda bintang (*) wajib diisi.";
        $_SESSION['tipe'] = "error";
        header("Location: tambah.php");
        exit;
    } elseif (!is_numeric($hargaRaw) || !is_numeric($stockRaw) || $hargaRaw < 0 || $stockRaw < 0) {
        $saveOldInput();
        $_SESSION['pesan'] = "Gagal: Harga dan Stok harus berupa angka lebih atau sama dengan 0.";
        $_SESSION['tipe'] = "error";
        header("Location: tambah.php");
        exit;
    }

    $stmtCheck = mysqli_prepare($koneksi, "SELECT id FROM menu WHERE kd_menu = ?");
    if ($stmtCheck) {
        mysqli_stmt_bind_param($stmtCheck, "s", $kd);
        mysqli_stmt_execute($stmtCheck);
        mysqli_stmt_store_result($stmtCheck);
        if (mysqli_stmt_num_rows($stmtCheck) > 0) {
            mysqli_stmt_close($stmtCheck);
            $saveOldInput();
            $_SESSION['pesan'] = "Gagal: Kode menu \"" . htmlspecialchars($kd) . "\" sudah digunakan. Silakan gunakan kode unik lain.";
            $_SESSION['tipe'] = "error";
            header("Location: tambah.php");
            exit;
        }
        mysqli_stmt_close($stmtCheck);
    }

    $harga = (int) $hargaRaw;
    $stock = (int) $stockRaw;

    $stmt = mysqli_prepare($koneksi, "INSERT INTO menu (kd_menu, nama_menu, kategori, harga, stock, deskripsi) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssiis", $kd, $nama, $kategori, $harga, $stock, $deskripsi);
        try {
            mysqli_stmt_execute($stmt);
            unset($_SESSION['form_data']);
            $_SESSION['pesan'] = "Menu \"" . htmlspecialchars($nama) . "\" berhasil ditambahkan!";
            $_SESSION['tipe'] = "sukses";
            mysqli_stmt_close($stmt);
            header("Location: index.php");
            exit;
        } catch (mysqli_sql_exception $e) {
            mysqli_stmt_close($stmt);
            $saveOldInput();
            if ($e->getCode() === 1062) {
                $_SESSION['pesan'] = "Gagal: Kode menu \"" . htmlspecialchars($kd) . "\" sudah terdaftar di sistem.";
            } else {
                $_SESSION['pesan'] = "Gagal menambahkan data menu: " . $e->getMessage();
            }
            $_SESSION['tipe'] = "error";
            header("Location: tambah.php");
            exit;
        }
    } else {
        $saveOldInput();
        $_SESSION['pesan'] = "Gagal memproses query database.";
        $_SESSION['tipe'] = "error";
        header("Location: tambah.php");
        exit;
    }

} elseif ($aksi === 'edit') {
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    $kd = trim($_POST['kd_menu'] ?? '');
    $nama = trim($_POST['nama_menu'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $hargaRaw = $_POST['harga'] ?? '';
    $stockRaw = $_POST['stock'] ?? '';
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if (!$id) {
        $_SESSION['pesan'] = "Gagal: ID menu tidak valid.";
        $_SESSION['tipe'] = "error";
        header("Location: index.php");
        exit;
    } elseif ($kd === '' || $nama === '' || $kategori === '' || $hargaRaw === '' || $stockRaw === '') {
        $_SESSION['pesan'] = "Gagal: Semua kolom bertanda bintang (*) wajib diisi.";
        $_SESSION['tipe'] = "error";
        header("Location: edit.php?id=" . $id);
        exit;
    } elseif (!is_numeric($hargaRaw) || !is_numeric($stockRaw) || $hargaRaw < 0 || $stockRaw < 0) {
        $_SESSION['pesan'] = "Gagal: Harga dan Stok harus berupa angka lebih atau sama dengan 0.";
        $_SESSION['tipe'] = "error";
        header("Location: edit.php?id=" . $id);
        exit;
    }

    $stmtCheck = mysqli_prepare($koneksi, "SELECT id FROM menu WHERE kd_menu = ? AND id != ?");
    if ($stmtCheck) {
        mysqli_stmt_bind_param($stmtCheck, "si", $kd, $id);
        mysqli_stmt_execute($stmtCheck);
        mysqli_stmt_store_result($stmtCheck);
        if (mysqli_stmt_num_rows($stmtCheck) > 0) {
            mysqli_stmt_close($stmtCheck);
            $_SESSION['pesan'] = "Gagal: Kode menu \"" . htmlspecialchars($kd) . "\" sudah digunakan oleh menu lain.";
            $_SESSION['tipe'] = "error";
            header("Location: edit.php?id=" . $id);
            exit;
        }
        mysqli_stmt_close($stmtCheck);
    }

    $harga = (int) $hargaRaw;
    $stock = (int) $stockRaw;

    $stmt = mysqli_prepare($koneksi, "UPDATE menu SET kd_menu = ?, nama_menu = ?, kategori = ?, harga = ?, stock = ?, deskripsi = ? WHERE id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssiisi", $kd, $nama, $kategori, $harga, $stock, $deskripsi, $id);
        try {
            mysqli_stmt_execute($stmt);
            $_SESSION['pesan'] = "Menu \"" . htmlspecialchars($nama) . "\" berhasil diperbarui!";
            $_SESSION['tipe'] = "sukses";
            mysqli_stmt_close($stmt);
            header("Location: index.php");
            exit;
        } catch (mysqli_sql_exception $e) {
            mysqli_stmt_close($stmt);
            if ($e->getCode() === 1062) {
                $_SESSION['pesan'] = "Gagal: Kode menu \"" . htmlspecialchars($kd) . "\" sudah digunakan oleh menu lain.";
            } else {
                $_SESSION['pesan'] = "Gagal mengupdate data menu: " . $e->getMessage();
            }
            $_SESSION['tipe'] = "error";
            header("Location: edit.php?id=" . $id);
            exit;
        }
    } else {
        $_SESSION['pesan'] = "Gagal memproses query database.";
        $_SESSION['tipe'] = "error";
        header("Location: edit.php?id=" . $id);
        exit;
    }
}

header("Location: index.php");
exit;
?>
