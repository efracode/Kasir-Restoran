<?php require 'config/database.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Menu</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, select, textarea { width: 300px; padding: 8px; margin-top: 5px; }
        button { margin-top: 15px; padding: 10px 20px; background: green; color: white; border: none; cursor: pointer; }
        a { margin-left: 10px; }
    </style>
</head>
<body>

<h2>Tambah Menu Baru</h2>

<form action="process.php" method="POST">
    <input type="hidden" name="aksi" value="tambah">
    
    <label>Kode Menu:</label>
    <input type="text" name="kd_menu" required>
    
    <label>Nama Menu:</label>
    <input type="text" name="nama_menu" required>
    
    <label>Kategori:</label>
    <select name="kategori" required>
        <option value="Makanan">Makanan</option>
        <option value="Minuman">Minuman</option>
        <option value="Snack">Snack</option>
        <option value="Dessert">Dessert</option>
    </select>
    
    <label>Harga:</label>
    <input type="number" name="harga" required>
    
    <label>Stock:</label>
    <input type="number" name="stock" required>
    
    <label>Deskripsi:</label>
    <textarea name="deskripsi" rows="3"></textarea>
    
    <br>
    <button type="submit">Simpan</button>
    <a href="index.php">Batal</a>
</form>

</body>
</html>