<?php
require 'config/database.php';

if (isset($_GET['pesan'])) {
    echo "<p style='color:green;'>$_GET[pesan]</p>";
}

$query = "SELECT * FROM menu ORDER BY id DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Menu</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #333; color: white; }
        a { margin-right: 5px; }
        .btn { padding: 5px 10px; text-decoration: none; color: white; border-radius: 3px; }
        .btn-tambah { background: green; }
        .btn-edit { background: orange; }
        .btn-hapus { background: red; }
    </style>
</head>
<body>

<h2>Daftar Menu Restoran</h2>
<a href="tambah.php" class="btn btn-tambah">+ Tambah Menu</a>
<br><br>

<table>
    <tr>
        <th>No</th>
        <th>Kode</th>
        <th>Nama Menu</th>
        <th>Kategori</th>
        <th>Harga</th>
        <th>Stock</th>
        <th>Deskripsi</th>
        <th>Aksi</th>
    </tr>
    <?php
    $no = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>$no</td>";
        echo "<td>$row[kd_menu]</td>";
        echo "<td>$row[nama_menu]</td>";
        echo "<td>$row[kategori]</td>";
        echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
        echo "<td>$row[stock]</td>";
        echo "<td>$row[deskripsi]</td>";
        echo "<td>";
        echo "<a href='edit.php?id=$row[id]' class='btn btn-edit'>Edit</a>";
        echo "<a href='hapus.php?id=$row[id]' class='btn btn-hapus' onclick='return confirm(\"Apakah Anda yakin ingin menghapus?\")'>Hapus</a>";
        echo "</td>";
        echo "</tr>";
        $no++;
    }
    ?>
</table>

</body>
</html>