<?php
require 'config/database.php';

$query = "SELECT * FROM menu ORDER BY id DESC";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Gagal mengambil data menu.");
}

$menus = [];
$totalMakanan = 0;
$totalMinuman = 0;
$totalSnack = 0;
$totalDessert = 0;
$totalStok = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $menus[] = $row;

    switch ($row['kategori']) {
        case 'Makanan':
            $totalMakanan++;
            break;
        case 'Minuman':
            $totalMinuman++;
            break;
        case 'Snack':
            $totalSnack++;
            break;
        case 'Dessert':
            $totalDessert++;
            break;
    }

    $totalStok += (int) $row['stock'];
}

$totalMenu = count($menus);

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>MenuHub - Manajemen Menu Restoran</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        .dashboard-layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 245px;
            flex-shrink: 0;
            padding: 28px 18px;
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 0 10px;
            margin-bottom: 42px;
        }

        .brand-icon {
            display: grid;
            place-items: center;
            width: 42px;
            height: 42px;
            background: #16a34a;
            color: white;
            border-radius: 12px;
            font-size: 22px;
        }

        .brand-name {
            font-size: 19px;
            font-weight: 800;
            letter-spacing: -0.7px;
        }

        .brand-caption {
            color: #64748b;
            font-size: 11px;
        }

        .nav-label {
            margin: 0 10px 12px;
            color: #94a3b8;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            margin-bottom: 6px;
            border-radius: 10px;
            color: #64748b;
            font-weight: 500;
        }

        .nav-link.active {
            color: #15803d;
            background: #dcfce7;
            font-weight: 700;
        }

        .nav-link:hover {
            background: #f1f5f9;
            color: #15803d;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 18px 10px 0;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 12px;
        }

        .main-area {
            flex: 1;
            min-width: 0;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            padding: 20px 34px;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
        }

        .breadcrumb {
            color: #64748b;
            font-size: 12px;
        }

        .breadcrumb strong {
            color: #0f172a;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .profile-avatar {
            display: grid;
            place-items: center;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #dcfce7;
            color: #15803d;
            font-weight: 800;
        }

        .profile-name {
            font-size: 12px;
            font-weight: 700;
        }

        .profile-role {
            color: #64748b;
            font-size: 11px;
        }

        .dashboard-content {
            width: min(1400px, 100%);
            margin: 0 auto;
            padding: 32px 34px;
        }

        .welcome-text {
            margin-top: 6px;
            color: #64748b;
        }

        .stat-icon {
            display: grid;
            place-items: center;
            width: 42px;
            height: 42px;
            margin-bottom: 16px;
            border-radius: 12px;
            background: #dcfce7;
            font-size: 20px;
        }

        .stat-icon.orange {
            background: #ffedd5;
        }

        .stat-icon.blue {
            background: #dbeafe;
        }

        .stat-icon.purple {
            background: #f3e8ff;
        }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .stat-chip {
            padding: 4px 8px;
            border-radius: 20px;
            color: #15803d;
            background: #dcfce7;
            font-size: 10px;
            font-weight: 700;
        }

        .category-select {
            max-width: 180px;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 9px;
            background: white;
            color: #334155;
        }

        .menu-name {
            font-weight: 700;
            color: #0f172a;
        }

        .menu-code {
            color: #64748b;
            font-size: 11px;
        }

        .price {
            white-space: nowrap;
            font-weight: 600;
        }

        .description {
            max-width: 190px;
            color: #64748b;
            font-size: 12px;
        }

        .table-footer {
            padding: 16px 22px;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 12px;
        }

        .alert {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .alert-close {
            border: none;
            background: transparent;
            font-size: 18px;
            cursor: pointer;
        }

        @media (max-width: 1000px) {
            .sidebar {
                width: 200px;
            }

            .topbar,
            .dashboard-content {
                padding-left: 22px;
                padding-right: 22px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 650px) {
            .dashboard-layout {
                display: block;
            }

            .sidebar {
                width: 100%;
                padding: 15px;
                border-right: none;
                border-bottom: 1px solid #e2e8f0;
            }

            .brand {
                margin-bottom: 16px;
            }

            .nav-label,
            .sidebar-footer {
                display: none;
            }

            .nav-link {
                display: inline-flex;
                padding: 8px 10px;
                margin: 0 3px 0 0;
                font-size: 12px;
            }

            .topbar {
                padding: 15px;
            }

            .dashboard-content {
                padding: 22px 14px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .table-toolbar {
                padding: 16px;
            }

            .profile-name,
            .profile-role {
                display: none;
            }

            .page-header>.btn {
                align-self: flex-start;
            }
        }


        .empty-state {
            padding: 0 !important;
            text-align: center;
        }

        .empty-notice {
            padding: 48px 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .empty-icon {
            width: 64px;
            height: 64px;
            display: grid;
            place-items: center;
            border-radius: 18px;
            background: #dcfce7;
            font-size: 30px;
            margin-bottom: 18px;
        }

        .empty-notice h3 {
            margin: 0 0 10px;
            color: #0f172a;
            font-size: 18px;
            font-weight: 700;
        }

        .empty-notice p {
            max-width: 430px;
            margin: 0 0 22px;
            color: #64748b;
            font-size: 13px;
            line-height: 1.8;
        }

        .empty-notice .btn {
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="dashboard-layout">

        <!-- SIDEBAR -->
        <aside class="sidebar">

            <div class="brand">
                <div class="brand-icon">🍃</div>
                <div>
                    <div class="brand-name">MenuHub</div>
                    <div class="brand-caption">Restaurant Management</div>
                </div>
            </div>

            <div class="nav-label">MENU UTAMA</div>

            <a href="index.php" class="nav-link active">
                <span>▦</span> Dashboard
            </a>

            <a href="index.php" class="nav-link">
                <span>☷</span> Daftar Menu
            </a>

            <a href="tambah.php" class="nav-link">
                <span>＋</span> Tambah Menu
            </a>

            <div class="sidebar-footer">
                <strong>MenuHub Admin</strong>
                <p>Pengelolaan menu restoran</p>
            </div>

        </aside>

        <!-- MAIN AREA -->
        <div class="main-area">

            <!-- TOPBAR -->
            <header class="topbar">
                <div class="breadcrumb">
                    Halaman / <strong>Dashboard</strong>
                </div>

                <div class="profile">
                    <div class="profile-avatar">A</div>
                    <div>
                        <div class="profile-name">Administrator</div>
                        <div class="profile-role">Pengelola Menu</div>
                    </div>
                </div>
            </header>

            <!-- CONTENT -->
            <main class="dashboard-content">

                <section class="page-header">
                    <div>
                        <h1 class="page-title">Dashboard</h1>
                        <p class="welcome-text">
                            Selamat datang! Kelola menu restoranmu dengan mudah.
                        </p>
                    </div>

                    <a href="tambah.php" class="btn btn-primary">
                        <span>＋</span> Tambah Menu Baru
                    </a>
                </section>

                <!-- FLASH MESSAGE -->
                <?php if (isset($_SESSION['pesan'])): ?>
                    <?php
                    $tipePesan = $_SESSION['tipe'] ?? 'sukses';
                    $kelasPesan = $tipePesan === 'error'
                        ? 'alert-error'
                        : 'alert-sukses';
                    ?>

                    <div class="alert <?= e($kelasPesan) ?>">
                        <span><?= e($_SESSION['pesan']) ?></span>
                        <button
                            type="button"
                            class="alert-close"
                            onclick="this.parentElement.remove()"
                            aria-label="Tutup notifikasi">&times;</button>
                    </div>

                    <?php
                    unset($_SESSION['pesan'], $_SESSION['tipe']);
                    ?>
                <?php endif; ?>

                <!-- STATISTICS -->
                <section class="stats-grid">

                    <article class="card stat-card">
                        <div class="stat-top">
                            <div class="stat-icon">🍽️</div>
                            <span class="stat-chip">Total</span>
                        </div>
                        <div class="stat-label">Total Menu</div>
                        <div class="stat-value"><?= $totalMenu ?></div>
                        <div class="stat-note">Semua menu terdaftar</div>
                    </article>

                    <article class="card stat-card">
                        <div class="stat-top">
                            <div class="stat-icon orange">🍛</div>
                        </div>
                        <div class="stat-label">Menu Makanan</div>
                        <div class="stat-value"><?= $totalMakanan ?></div>
                        <div class="stat-note">Jenis makanan tersedia</div>
                    </article>

                    <article class="card stat-card">
                        <div class="stat-top">
                            <div class="stat-icon blue">🥤</div>
                        </div>
                        <div class="stat-label">Menu Minuman</div>
                        <div class="stat-value"><?= $totalMinuman ?></div>
                        <div class="stat-note">Jenis minuman tersedia</div>
                    </article>

                </section>

                <!-- MENU TABLE -->
                <section class="card table-card">

                    <div class="table-toolbar">
                        <div>
                            <h2 class="section-title">Daftar Menu Restoran</h2>
                            <p class="stat-note">
                                Lihat dan kelola data menu restoranmu.
                            </p>
                        </div>

                        <input
                            type="search"
                            id="searchMenu"
                            class="search-input"
                            placeholder="Cari nama atau kode menu..."
                            aria-label="Cari menu">
                    </div>

                    <div style="padding: 14px 22px 0;">
                        <label for="filterKategori" class="stat-label">
                            Filter kategori:
                        </label>

                        <select id="filterKategori" class="category-select">
                            <option value="">Semua kategori</option>
                            <option value="makanan">Makanan</option>
                            <option value="minuman">Minuman</option>
                            <option value="snack">Snack</option>
                            <option value="dessert">Dessert</option>
                        </select>
                    </div>

                    <div class="table-responsive">
                        <table id="menuTable">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Informasi Menu</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Deskripsi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if ($totalMenu > 0): ?>
                                    <?php foreach ($menus as $index => $menu): ?>
                                        <?php
                                        $kategori = strtolower($menu['kategori']);

                                        $kelasKategori = match ($kategori) {
                                            'makanan' => 'badge-food',
                                            'minuman' => 'badge-drink',
                                            'dessert' => 'badge-dessert',
                                            default => ''
                                        };
                                        ?>

                                        <tr class="menu-row"
                                            data-kategori="<?= e($kategori) ?>">

                                            <td><?= $index + 1 ?></td>

                                            <td>
                                                <div class="menu-name">
                                                    <?= e($menu['nama_menu']) ?>
                                                </div>
                                                <div class="menu-code">
                                                    <?= e($menu['kd_menu']) ?>
                                                </div>
                                            </td>

                                            <td>
                                                <span class="badge <?= e($kelasKategori) ?>">
                                                    <?= e($menu['kategori']) ?>
                                                </span>
                                            </td>

                                            <td class="price">
                                                Rp <?= number_format(
                                                        (float) $menu['harga'],
                                                        0,
                                                        ',',
                                                        '.'
                                                    ) ?>
                                            </td>

                                            <td>
                                                <span class="stock <?= (int) $menu['stock'] <= 5 ? 'stock-low' : '' ?>">
                                                    <?= (int) $menu['stock'] ?>
                                                </span>
                                            </td>

                                            <td>
                                                <div class="description">
                                                    <?= e($menu['deskripsi'] ?: '-') ?>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="action-buttons">
                                                    <a
                                                        href="edit.php?id=<?= (int) $menu['id'] ?>"
                                                        class="btn btn-edit">Edit</a>

                                                    <a
                                                        href="hapus.php?id=<?= (int) $menu['id'] ?>"
                                                        class="btn btn-delete"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus menu ini?')">Hapus</a>
                                                </div>
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>

                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="empty-state">
                                            <div class="empty-notice">
                                                <div class="empty-icon">🍽️</div>

                                                <h3>Menu Belum Tersedia</h3>

                                                <p>
                                                    Saat ini belum ada menu yang terdaftar.
                                                    Yuk, tambahkan menu pertama untuk mulai
                                                    mengelola daftar menu restoranmu!
                                                </p>

                                                <a href="tambah.php" class="btn btn-primary">
                                                    ＋ Tambah Menu Pertama
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>

                            </tbody>
                        </table>
                    </div>

                    <div class="table-footer" id="tableFooter">
                        Menampilkan <?= $totalMenu ?> menu.
                        Total stok: <?= $totalStok ?>.
                    </div>

                </section>

                <p class="stat-note" style="margin-top: 24px; text-align: center;">
                    MenuHub &copy; <?= date('Y') ?> — Restaurant Management System
                </p>

            </main>

        </div>
    </div>

    <script>
        const searchInput = document.getElementById('searchMenu');
        const filterKategori = document.getElementById('filterKategori');
        const rows = document.querySelectorAll('.menu-row');
        const tableFooter = document.getElementById('tableFooter');

        function filterMenu() {
            const keyword = searchInput.value.toLowerCase().trim();
            const kategori = filterKategori.value;
            let visible = 0;

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const kategoriRow = row.dataset.kategori;

                const cocokKeyword = text.includes(keyword);
                const cocokKategori = !kategori || kategoriRow === kategori;
                const tampil = cocokKeyword && cocokKategori;

                row.style.display = tampil ? '' : 'none';

                if (tampil) visible++;
            });

            tableFooter.textContent =
                `Menampilkan ${visible} dari ${rows.length} menu.`;
        }

        searchInput.addEventListener('input', filterMenu);
        filterKategori.addEventListener('change', filterMenu);
    </script>

</body>

</html>