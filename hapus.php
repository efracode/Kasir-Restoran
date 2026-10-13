<?php
require 'config/database.php';

$id = $_GET['id'];
mysqli_query($koneksi, "DELETE FROM menu WHERE id = $id");

header("Location: index.php?pesan=Data berhasil dihapus");
?>