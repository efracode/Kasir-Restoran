<?php
require 'config/database.php';

$id = $_GET['id'];
$query = "SELECT * FROM menu WHERE id = $id";
$result = mysqli_query($koneksi, $query);
$menu = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Menu</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, select, textarea { width: 300px; padding: 8px; margin-top: 5px; }
        button { margin-top: 15px; padding: 10px 20px; background: orange; color: white; border: none; cursor: pointer; }
        a { margin-left: 10px; }
    </style>
</head>
<body>

<h2>Edit Menu</h2>

<form action="process.php" method="POST">
    <input type="hidden" name="aksi" value="edit">
    <input type="hidden" name="id" value="<?= $menu['id'] ?>">
    
    <label>Kode Menu:</label>
    <input type="text" name="kd_menu" value="<?= $menu['kd_menu'] ?>" required>
    
    <label>Nama Menu:</label>
    <input type="text" name="nama_menu" value="<?= $menu['nama_menu'] ?>" required>
    
    <label>Kategori:</label>
    <select name="kategori" required>
        <option value="Makanan" <?= $menu['kategori'] == 'Makanan' ? 'selected' : '' ?>>Makanan</option>
        <option value="Minuman" <?= $menu['kategori'] == 'Minuman' ? 'selected' : '' ?>>Minuman</option>
        <option value="Snack" <?= $menu['kategori'] == 'Snack' ? 'selected' : '' ?>>Snack</option>
        <option value="Dessert" <?= $menu['kategori'] == 'Dessert' ? 'selected' : '' ?>>Dessert</option>
    </select>
    
    <label>Harga:</label>
    <input type="number" name="harga" value="<?= $menu['harga'] ?>" required>
    
    <label>Stock:</label>
    <input type="number" name="stock" value="<?= $menu['stock'] ?>" required>
    
    <label>Deskripsi:</label>
    <textarea name="deskripsi" rows="3"><?= $menu['deskripsi'] ?></textarea>
    
    <br>
    <button type="submit">Update</button>
    <a href="index.php">Batal</a>
</form>

</body>
</html>