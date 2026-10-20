<?php
require 'config/database.php';

$aksi = $_POST['aksi'] ?? '';

if ($aksi == 'tambah') {
    $kd = $_POST['kd_menu'];
    $nama = $_POST['nama_menu'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $stock = $_POST['stock'];
    $deskripsi = $_POST['deskripsi'];
    
    if($harga < 0 || $stock < 0){
            $_SESSION['pesan'] = "Gagal: Harga dan Stock harus lebih atau sama dengan 0.";
            $_SESSION['tipe'] = "error";
    }else{
        $query = "INSERT INTO menu (kd_menu, nama_menu, kategori, harga, stock, deskripsi) 
                VALUES ('$kd', '$nama', '$kategori', $harga, $stock, '$deskripsi')";
        
        if (mysqli_query($koneksi, $query)) {
            $_SESSION['pesan'] = "Data berhasil ditambahkan";
            $_SESSION['tipe'] = "sukses";
        } else {
            $_SESSION['pesan'] = "Gagal menambahkan data";
            $_SESSION['tipe'] = "error";
        }
    }
    header("Location: index.php");
    exit;

    
} elseif ($aksi == 'edit') {
    $id = $_POST['id'];
    $kd = $_POST['kd_menu'];
    $nama = $_POST['nama_menu'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $stock = $_POST['stock'];
    $deskripsi = $_POST['deskripsi'];

    if($harga < 0 || $stock < 0){
        $_SESSION['pesan'] = "Gagal: Harga dan Sock harus lebih atau sama dengan 0.";
        $_SESSION['tipe'] = "error";
    }else{
        $query = "UPDATE menu SET 
                kd_menu='$kd', 
                nama_menu='$nama', 
                kategori='$kategori', 
                harga=$harga, 
                stock=$stock, 
                deskripsi='$deskripsi' 
                WHERE id=$id";

        if (mysqli_query($koneksi, $query)) {
            $_SESSION['pesan'] = "Data berhasil diupdate";
            $_SESSION['tipe'] = "sukses";
        } else {
            $_SESSION['pesan'] = "Gagal mengupdate data";
            $_SESSION['tipe'] = "error";
        }
    }

    header("Location: index.php");
    exit;
}
?>
