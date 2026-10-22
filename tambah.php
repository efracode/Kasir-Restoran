
<?php require 'config/database.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu | MenuHub</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --green: #16a34a;
            --green-dark: #15803d;
            --green-light: #dcfce7;
            --text: #172033;
            --muted: #64748b;
            --border: #e2e8f0;
            --background: #f6f8fb;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', Arial, sans-serif;
            color: var(--text);
            background: var(--background);
            font-size: 14px;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .layout {
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 210px;
            background: var(--white);
            border-right: 1px solid var(--border);
            position: fixed;
            inset: 0 auto 0 0;
            display: flex;
            flex-direction: column;
            padding: 24px 15px;
            z-index: 10;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 9px;
            margin-bottom: 42px;
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            border-radius: 11px;
            background: var(--green);
            color: white;
            display: grid;
            place-items: center;
            font-size: 19px;
            font-weight: 800;
        }

        .brand-name {
            font-size: 17px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .brand-subtitle {
            color: var(--muted);
            font-size: 9px;
            margin-top: 4px;
        }

        .nav-label {
            font-size: 10px;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 0.6px;
            margin: 0 9px 12px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 13px 12px;
            margin-bottom: 5px;
            border-radius: 10px;
            color: #526178;
            font-weight: 500;
            transition: 0.2s;
        }

        .nav-link:hover {
            background: #f1f5f9;
        }

        .nav-link.active {
            background: var(--green-light);
            color: var(--green-dark);
            font-weight: 700;
        }

        .nav-icon {
            width: 15px;
            text-align: center;
        }

        .sidebar-bottom {
            margin-top: auto;
            padding: 17px 9px 0;
            border-top: 1px solid var(--border);
            line-height: 1.7;
            color: var(--muted);
            font-size: 11px;
        }

        .sidebar-bottom strong {
            color: #475569;
        }

        /* MAIN CONTENT */
        .main {
            margin-left: 210px;
            min-height: 100vh;
        }

        .topbar {
            min-height: 68px;
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .breadcrumb {
            color: var(--muted);
            font-size: 12px;
        }

        .breadcrumb strong {
            color: var(--text);
        }

        .admin {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar {
            height: 34px;
            width: 34px;
            border-radius: 50%;
            background: var(--green-light);
            color: var(--green-dark);
            display: grid;
            place-items: center;
            font-weight: 800;
        }

        .admin-name {
            font-size: 11px;
            font-weight: 700;
        }

        .admin-role {
            font-size: 10px;
            color: var(--muted);
            margin-top: 4px;
        }

        .content {
            max-width: 1120px;
            padding: 32px;
            margin: 0 auto;
        }

        .page-heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 25px;
        }

        .page-heading h1 {
            font-size: 25px;
            letter-spacing: -0.8px;
            margin: 0 0 9px;
        }

        .page-heading p {
            margin: 0;
            color: var(--muted);
            line-height: 1.7;
            font-size: 13px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: white;
            border: 1px solid var(--border);
            padding: 11px 15px;
            border-radius: 9px;
            color: #475569;
            font-weight: 600;
            white-space: nowrap;
            transition: 0.2s;
        }

        .back-link:hover {
            border-color: #86efac;
            color: var(--green-dark);
        }

        /* FORM CARD */
        .form-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(15, 23, 42, 0.025);
        }

        .card-heading {
            padding: 22px 25px;
            border-bottom: 1px solid var(--border);
        }

        .card-heading h2 {
            font-size: 16px;
            margin: 0 0 7px;
        }

        .card-heading p {
            font-size: 12px;
            color: var(--muted);
            margin: 0;
            line-height: 1.6;
        }

        .form-body {
            padding: 26px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px 24px;
        }

        .field {
            min-width: 0;
        }

        .field.full {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 9px;
        }

        .required {
            color: #dc2626;
        }

        .input,
        select,
        textarea {
            display: block;
            width: 100%;
            border: 1px solid #cbd5e1;
            background: white;
            color: var(--text);
            padding: 12px 13px;
            border-radius: 9px;
            font: inherit;
            font-size: 13px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .input,
        select {
            min-height: 44px;
        }

        .input:focus,
        select:focus,
        textarea:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
        }

        textarea {
            min-height: 110px;
            resize: vertical;
            line-height: 1.6;
        }

        .helper {
            display: block;
            color: #94a3b8;
            font-size: 11px;
            margin-top: 7px;
            line-height: 1.5;
        }

        .form-footer {
            padding: 19px 26px;
            background: #fafbfc;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .footer-note {
            color: var(--muted);
            font-size: 11px;
            line-height: 1.6;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            border-radius: 9px;
            padding: 12px 17px;
            border: 1px solid transparent;
            font: inherit;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-cancel {
            background: white;
            border-color: var(--border);
            color: #475569;
        }

        .btn-cancel:hover {
            background: #f1f5f9;
        }

        .btn-save {
            background: var(--green);
            color: white;
            box-shadow: 0 3px 8px rgba(22, 163, 74, 0.14);
        }

        .btn-save:hover {
            background: var(--green-dark);
            transform: translateY(-1px);
        }

        .footer {
            text-align: center;
            padding: 25px 15px;
            color: var(--muted);
            font-size: 11px;
        }

        @media (max-width: 800px) {
            .sidebar {
                width: 68px;
                padding: 20px 9px;
            }

            .brand {
                padding: 0;
                justify-content: center;
                margin-bottom: 35px;
            }

            .brand-text,
            .nav-label,
            .nav-text,
            .sidebar-bottom {
                display: none;
            }

            .nav-link {
                justify-content: center;
                padding: 14px 5px;
            }

            .nav-icon {
                font-size: 16px;
            }

            .main {
                margin-left: 68px;
            }

            .topbar {
                padding: 0 18px;
            }

            .content {
                padding: 25px 18px;
            }
        }

        @media (max-width: 560px) {
            .content {
                padding: 22px 12px;
            }

            .page-heading {
                flex-direction: column;
            }

            .page-heading h1 {
                font-size: 22px;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 19px;
            }

            .field.full {
                grid-column: auto;
            }

            .form-body {
                padding: 20px 16px;
            }

            .card-heading {
                padding: 19px 16px;
            }

            .form-footer {
                padding: 17px 16px;
                align-items: stretch;
                flex-direction: column;
            }

            .form-actions {
                width: 100%;
            }

            .form-actions .btn {
                flex: 1;
            }

            .admin-role {
                display: none;
            }
        }
    </style>
</head>

<body>
<div class="layout">

    <aside class="sidebar">
        <a href="index.php" class="brand">
            <div class="brand-icon">M</div>
            <div class="brand-text">
                <div class="brand-name">MenuHub</div>
                <div class="brand-subtitle">Restaurant Management</div>
            </div>
        </a>

        <div class="nav-label">MENU UTAMA</div>

        <a href="index.php" class="nav-link">
            <span class="nav-icon">▦</span>
            <span class="nav-text">Dashboard</span>
        </a>

        <a href="index.php" class="nav-link">
            <span class="nav-icon">☷</span>
            <span class="nav-text">Daftar Menu</span>
        </a>

        <a href="tambah.php" class="nav-link active">
            <span class="nav-icon">＋</span>
            <span class="nav-text">Tambah Menu</span>
        </a>

        <div class="sidebar-bottom">
            <strong>MenuHub Admin</strong><br>
            Pengelolaan menu restoran
        </div>
    </aside>

    <main class="main">

        <header class="topbar">
            <div class="breadcrumb">
                Halaman / <strong>Tambah Menu</strong>
            </div>

            <div class="admin">
                <div class="avatar">A</div>
                <div>
                    <div class="admin-name">Administrator</div>
                    <div class="admin-role">Pengelola Menu</div>
                </div>
            </div>
        </header>

        <div class="content">

            <div class="page-heading">
                <div>
                    <h1>Tambah Menu Baru</h1>
                    <p>
                        Tambahkan data makanan atau minuman baru
                        ke dalam menu restoranmu.
                    </p>
                </div>

                <a href="index.php" class="back-link">
                    ← Kembali ke Dashboard
                </a>
            </div>

            <section class="form-card">

                <div class="card-heading">
                    <h2>Informasi Menu</h2>
                    <p>
                        Lengkapi informasi di bawah ini.
                        Kolom bertanda <span class="required">*</span>
                        wajib diisi.
                    </p>
                </div>

                <form action="process.php" method="POST">
                    <input type="hidden" name="aksi" value="tambah">

                    <div class="form-body">
                        <div class="form-grid">

                            <div class="field">
                                <label for="kd_menu">
                                    Kode Menu <span class="required">*</span>
                                </label>
                                <input
                                    class="input"
                                    type="text"
                                    id="kd_menu"
                                    name="kd_menu"
                                    placeholder="Contoh: MN001"
                                    maxlength="20"
                                    required
                                >
                                <small class="helper">
                                    Gunakan kode unik untuk setiap menu.
                                </small>
                            </div>

                            <div class="field">
                                <label for="nama_menu">
                                    Nama Menu <span class="required">*</span>
                                </label>
                                <input
                                    class="input"
                                    type="text"
                                    id="nama_menu"
                                    name="nama_menu"
                                    placeholder="Contoh: Nasi Goreng Spesial"
                                    maxlength="100"
                                    required
                                >
                            </div>

                            <div class="field">
                                <label for="kategori">
                                    Kategori <span class="required">*</span>
                                </label>
                                <select id="kategori" name="kategori" required>
                                    <option value="Makanan">Makanan</option>
                                    <option value="Minuman">Minuman</option>
                                    <option value="Snack">Snack</option>
                                    <option value="Dessert">Dessert</option>
                                </select>
                            </div>

                            <div class="field">
                                <label for="harga">
                                    Harga (Rp) <span class="required">*</span>
                                </label>
                                <input
                                    class="input"
                                    type="number"
                                    id="harga"
                                    name="harga"
                                    placeholder="Contoh: 25000"
                                    min="0"
                                    step="1"
                                    required
                                >
                                <small class="helper">
                                    Masukkan harga dalam rupiah tanpa tanda titik.
                                </small>
                            </div>

                            <div class="field">
                                <label for="stock">
                                    Stok Menu <span class="required">*</span>
                                </label>
                                <input
                                    class="input"
                                    type="number"
                                    id="stock"
                                    name="stock"
                                    placeholder="Contoh: 20"
                                    min="0"
                                    step="1"
                                    required
                                >
                                <small class="helper">
                                    Masukkan jumlah stok yang tersedia.
                                </small>
                            </div>

                            <div class="field full">
                                <label for="deskripsi">Deskripsi Menu</label>
                                <textarea
                                    id="deskripsi"
                                    name="deskripsi"
                                    placeholder="Tuliskan deskripsi singkat menu..."
                                ></textarea>
                                <small class="helper">
                                    Opsional. Jelaskan bahan, rasa, atau keunikan menu.
                                </small>
                            </div>

                        </div>
                    </div>

                    <div class="form-footer">
                        <div class="footer-note">
                            Pastikan informasi menu sudah benar<br>
                            sebelum menyimpan data.
                        </div>

                        <div class="form-actions">
                            <a href="index.php" class="btn btn-cancel">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-save">
                                ✓ Simpan Menu
                            </button>
                        </div>
                    </div>
                </form>

            </section>

            <footer class="footer">
                MenuHub © 2026 — Restaurant Management System
            </footer>

        </div>
    </main>

</div>
</body>
</html>
