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
    
    $query = "INSERT INTO menu (kd_menu, nama_menu, kategori, harga, stock, deskripsi) 
              VALUES ('$kd', '$nama', '$kategori', $harga, $stock, '$deskripsi')";
    mysqli_query($koneksi, $query);
    
    header("Location: index.php?pesan=Data berhasil ditambahkan");
    
} elseif ($aksi == 'edit') {
    $id = $_POST['id'];
    $kd = $_POST['kd_menu'];
    $nama = $_POST['nama_menu'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $stock = $_POST['stock'];
    $deskripsi = $_POST['deskripsi'];
    
    $query = "UPDATE menu SET 
              kd_menu='$kd', 
              nama_menu='$nama', 
              kategori='$kategori', 
              harga=$harga, 
              stock=$stock, 
              deskripsi='$deskripsi' 
              WHERE id=$id";
    mysqli_query($koneksi, $query);
    
    header("Location: index.php?pesan=Data berhasil diupdate");
}
?>