<?php
require 'config/database.php';

$queryMenu = "SELECT * FROM menu ORDER BY id DESC";
$resultMenu = mysqli_query($koneksi, $queryMenu);

$menus = [];
$totalMakanan = 0;
$totalMinuman = 0;
$totalSnack = 0;
$totalDessert = 0;
$totalStok = 0;

if ($resultMenu) {
    while ($row = mysqli_fetch_assoc($resultMenu)) {
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
}
$totalMenu = count($menus);

$queryTrxSummary = "SELECT COUNT(id) as total_trx, COALESCE(SUM(total_harga), 0) as total_omset FROM transaksi WHERE DATE(tanggal) = CURDATE()";
$resTrxSummary = mysqli_query($koneksi, $queryTrxSummary);
$summaryData = mysqli_fetch_assoc($resTrxSummary);
$totalTrxHariIni = (int) ($summaryData['total_trx'] ?? 0);
$totalOmsetHariIni = (int) ($summaryData['total_omset'] ?? 0);

$queryRecentTrx = "SELECT * FROM transaksi ORDER BY id DESC LIMIT 5";
$resRecentTrx = mysqli_query($koneksi, $queryRecentTrx);
$recentTransactions = [];
if ($resRecentTrx) {
    while ($trx = mysqli_fetch_assoc($resRecentTrx)) {
        $recentTransactions[] = $trx;
    }
}

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
    <title>Kasir Restoran - Dashboard & POS</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <div class="dashboard-layout">

        <aside class="sidebar">

            <div class="brand">
                <div class="brand-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/>
                        <path d="M7 2v20"/>
                        <path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>
                    </svg>
                </div>
                <div>
                    <div class="brand-name">Kasir Restoran</div>
                    <div class="brand-caption">Sistem POS & Penjualan</div>
                </div>
            </div>

            <div class="nav-label">MENU UTAMA</div>

            <a href="kasir.php" class="nav-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="20" height="14" x="2" y="5" rx="2"/>
                    <line x1="2" x2="22" y1="10" y2="10"/>
                </svg>
                <span>Kasir (POS)</span>
                <span class="nav-link-badge">LIVE</span>
            </a>

            <a href="index.php" class="nav-link active">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="7" height="9" x="3" y="3" rx="1.5"/>
                    <rect width="7" height="5" x="14" y="3" rx="1.5"/>
                    <rect width="7" height="9" x="14" y="12" rx="1.5"/>
                    <rect width="7" height="5" x="3" y="16" rx="1.5"/>
                </svg>
                <span>Dashboard & Riwayat</span>
            </a>

            <a href="#menuTable" class="nav-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" x2="20" y1="6" y2="6"/>
                    <line x1="4" x2="20" y1="12" y2="12"/>
                    <line x1="4" x2="20" y1="18" y2="18"/>
                </svg>
                <span>Kelola Menu</span>
            </a>

            <a href="tambah.php" class="nav-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" x2="12" y1="8" y2="16"/>
                    <line x1="8" x2="16" y1="12" y2="12"/>
                </svg>
                <span>Tambah Menu</span>
            </a>

            <div class="sidebar-footer">
                <strong>Kasir Restoran</strong>
                <p>Aplikasi Kasir & Pemesanan</p>
            </div>

        </aside>

        <div class="main-area">

            <header class="topbar">
                <div class="breadcrumb">
                    <span>Halaman</span> / <strong>Dashboard & Riwayat Kasir</strong>
                </div>

                <div class="profile">
                    <div class="profile-avatar">K</div>
                    <div>
                        <div class="profile-name">Kasir 1</div>
                        <div class="profile-role">Petugas Transaksi</div>
                    </div>
                </div>
            </header>

            <main class="dashboard-content">

                <section class="page-header">
                    <div>
                        <h1 class="page-title">Kasir Restoran</h1>
                        <p class="welcome-text">
                            Kelola pesanan pelanggan di kasir, pantau omset harian, dan sesuaikan ketersediaan menu.
                        </p>
                    </div>

                    <div class="header-actions">
                        <a href="kasir.php" class="btn btn-primary" style="padding: 11px 18px; font-size: 14px; font-weight: 700;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="20" height="14" x="2" y="5" rx="2"/>
                                <line x1="2" x2="22" y1="10" y2="10"/>
                            </svg>
                            <span>Buka Kasir (POS)</span>
                        </a>

                        <a href="tambah.php" class="btn btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" x2="12" y1="5" y2="19"/>
                                <line x1="5" x2="19" y1="12" y2="12"/>
                            </svg>
                            <span>Tambah Menu</span>
                        </a>
                    </div>
                </section>

                <?php if (isset($_SESSION['pesan'])): ?>
                    <?php
                    $tipePesan = $_SESSION['tipe'] ?? 'sukses';
                    $kelasPesan = $tipePesan === 'error' ? 'alert-error' : 'alert-sukses';
                    ?>
                    <div class="alert <?= e($kelasPesan) ?>" role="alert">
                        <div class="alert-body">
                            <?php if ($tipePesan === 'error'): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" x2="12" y1="8" y2="12"/>
                                    <line x1="12" x2="12.01" y1="16" y2="16"/>
                                </svg>
                            <?php else: ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                    <polyline points="22 4 12 14.01 9 11.01"/>
                                </svg>
                            <?php endif; ?>
                            <span><?= e($_SESSION['pesan']) ?></span>
                        </div>
                        <button
                            type="button"
                            class="alert-close"
                            onclick="this.closest('.alert').remove()"
                            aria-label="Tutup notifikasi">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" x2="6" y1="6" y2="18"/>
                                <line x1="6" x2="18" y1="6" y2="18"/>
                            </svg>
                        </button>
                    </div>
                    <?php unset($_SESSION['pesan'], $_SESSION['tipe']); ?>
                <?php endif; ?>

                <section class="stats-grid">

                    <article class="card stat-card">
                        <div class="stat-top">
                            <div class="stat-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" x2="12" y1="2" y2="22"/>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                                </svg>
                            </div>
                            <span class="stat-chip">Hari Ini</span>
                        </div>
                        <div class="stat-label">Penjualan Hari Ini</div>
                        <div class="stat-value" style="font-size: 21px;">Rp <?= number_format((float) $totalOmsetHariIni, 0, ',', '.') ?></div>
                        <div class="stat-note"><?= $totalTrxHariIni ?> transaksi berhasil hari ini</div>
                    </article>

                    <article class="card stat-card">
                        <div class="stat-top">
                            <div class="stat-icon blue">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="20" height="14" x="2" y="5" rx="2"/>
                                    <line x1="2" x2="22" y1="10" y2="10"/>
                                </svg>
                            </div>
                            <span class="stat-chip" style="background: #dbeafe; color: #1d4ed8;">Kasir</span>
                        </div>
                        <div class="stat-label">Transaksi Selesai</div>
                        <div class="stat-value"><?= $totalTrxHariIni ?></div>
                        <div class="stat-note">Total struk terbit hari ini</div>
                    </article>

                    <article class="card stat-card">
                        <div class="stat-top">
                            <div class="stat-icon orange">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/>
                                    <path d="M7 2v20"/>
                                    <path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>
                                </svg>
                            </div>
                            <span class="stat-chip" style="background: #ffedd5; color: #c2410c;">Menu</span>
                        </div>
                        <div class="stat-label">Total Menu Restoran</div>
                        <div class="stat-value"><?= $totalMenu ?></div>
                        <div class="stat-note"><?= $totalMakanan ?> Makanan &bull; <?= $totalMinuman ?> Minuman</div>
                    </article>

                    <article class="card stat-card">
                        <div class="stat-top">
                            <div class="stat-icon purple">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m7.5 4.27 9 5.15"/>
                                    <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                                    <path d="m3.3 7 8.7 5 8.7-5"/>
                                    <path d="M12 22V12"/>
                                </svg>
                            </div>
                            <span class="stat-chip" style="background: #f3e8ff; color: #7e22ce;">Unit</span>
                        </div>
                        <div class="stat-label">Total Stok Tersedia</div>
                        <div class="stat-value"><?= $totalStok ?></div>
                        <div class="stat-note">Total unit porsi siap jual</div>
                    </article>

                </section>

                <section class="card table-card">
                    <div class="table-toolbar">
                        <div>
                            <h2 class="section-title">Transaksi Kasir Terakhir</h2>
                            <p class="stat-note">Riwayat transaksi penjualan terbaru di kasir.</p>
                        </div>
                        <a href="kasir.php" class="btn btn-primary btn-sm">
                            + Transaksi Baru
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>No. Transaksi</th>
                                    <th>Waktu</th>
                                    <th>Kasir</th>
                                    <th>Metode</th>
                                    <th>Total Bayar</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recentTransactions)): ?>
                                    <?php foreach ($recentTransactions as $t): ?>
                                        <tr>
                                            <td><strong><?= e($t['no_transaksi']) ?></strong></td>
                                            <td style="color: var(--text-muted); font-size: 12.5px;"><?= date('d/m/Y H:i', strtotime($t['tanggal'])) ?></td>
                                            <td><?= e($t['nama_kasir']) ?></td>
                                            <td><span class="badge" style="background: #f1f5f9; color: #475569;"><?= e($t['metode_bayar']) ?></span></td>
                                            <td class="price">Rp <?= number_format((float) $t['total_harga'], 0, ',', '.') ?></td>
                                            <td><span class="badge-stock badge-stock-ok">Selesai</span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 24px; color: var(--text-muted);">
                                            Belum ada transaksi hari ini. Buka menu <strong>Kasir (POS)</strong> untuk memulai transaksi pertama.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="card table-card" id="menuTable">

                    <div class="table-toolbar">
                        <div>
                            <h2 class="section-title">Katalog Menu Restoran</h2>
                            <p class="stat-note">
                                Kelola data menu, periksa stok tersedia, atau sesuaikan harga.
                            </p>
                        </div>

                        <div class="toolbar-controls">
                            <div class="search-box">
                                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"/>
                                    <line x1="21" x2="16.65" y1="21" y2="16.65"/>
                                </svg>
                                <input
                                    type="search"
                                    id="searchMenu"
                                    class="search-input"
                                    placeholder="Cari nama atau kode menu..."
                                    aria-label="Cari menu">
                            </div>

                            <select id="filterKategori" class="select-control" aria-label="Filter kategori">
                                <option value="">Semua Kategori</option>
                                <option value="makanan">Makanan</option>
                                <option value="minuman">Minuman</option>
                                <option value="snack">Snack</option>
                                <option value="dessert">Dessert</option>
                            </select>

                            <select id="sortMenu" class="select-control" aria-label="Urutkan menu">
                                <option value="default">Urutkan: ID Terbaru</option>
                                <option value="name-asc">Nama (A-Z)</option>
                                <option value="name-desc">Nama (Z-A)</option>
                                <option value="price-asc">Harga Terendah</option>
                                <option value="price-desc">Harga Tertinggi</option>
                                <option value="stock-asc">Stok Tersedikit</option>
                                <option value="stock-desc">Stok Terbanyak</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Informasi Menu</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Status Stok</th>
                                    <th>Deskripsi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody id="menuTableBody">
                                <?php if ($totalMenu > 0): ?>
                                    <?php foreach ($menus as $index => $menu): ?>
                                        <?php
                                        $kategori = strtolower($menu['kategori']);
                                        $kelasKategori = match ($kategori) {
                                            'makanan' => 'badge-food',
                                            'minuman' => 'badge-drink',
                                            'snack' => 'badge-snack',
                                            'dessert' => 'badge-dessert',
                                            default => ''
                                        };

                                        $stokVal = (int) $menu['stock'];
                                        if ($stokVal === 0) {
                                            $kelasStok = 'badge-stock-empty';
                                            $labelStok = 'Habis (0)';
                                        } elseif ($stokVal <= 5) {
                                            $kelasStok = 'badge-stock-low';
                                            $labelStok = 'Menipis (' . $stokVal . ')';
                                        } else {
                                            $kelasStok = 'badge-stock-ok';
                                            $labelStok = $stokVal . ' unit';
                                        }
                                        ?>

                                        <tr class="menu-row"
                                            data-id="<?= (int) $menu['id'] ?>"
                                            data-kd="<?= e($menu['kd_menu']) ?>"
                                            data-nama="<?= e($menu['nama_menu']) ?>"
                                            data-kategori="<?= e($kategori) ?>"
                                            data-harga="<?= (float) $menu['harga'] ?>"
                                            data-stock="<?= $stokVal ?>"
                                            data-deskripsi="<?= e($menu['deskripsi']) ?>">

                                            <td class="row-index"><?= $index + 1 ?></td>

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
                                                Rp <?= number_format((float) $menu['harga'], 0, ',', '.') ?>
                                            </td>

                                            <td>
                                                <span class="badge-stock <?= $kelasStok ?>">
                                                    <?= $labelStok ?>
                                                </span>
                                            </td>

                                            <td>
                                                <div class="description">
                                                    <?= e($menu['deskripsi'] ?: '-') ?>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="action-buttons">
                                                    <button type="button"
                                                            class="btn btn-detail btn-sm"
                                                            onclick="openDetailModal(this.closest('.menu-row'))"
                                                            title="Lihat Detail Menu">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <circle cx="12" cy="12" r="10"/>
                                                            <line x1="12" x2="12" y1="16" y2="12"/>
                                                            <line x1="12" x2="12.01" y1="8" y2="8"/>
                                                        </svg>
                                                        Detail
                                                    </button>

                                                    <a href="edit.php?id=<?= (int) $menu['id'] ?>" class="btn btn-edit btn-sm" title="Edit Menu">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M12 20h9"/>
                                                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                                                        </svg>
                                                        Edit
                                                    </a>

                                                    <button type="button"
                                                            class="btn btn-delete btn-sm"
                                                            onclick="openDeleteModal(<?= (int) $menu['id'] ?>, '<?= e(addslashes($menu['nama_menu'])) ?>')"
                                                            title="Hapus Menu">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M3 6h18"/>
                                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                                            <line x1="10" x2="10" y1="11" y2="17"/>
                                                            <line x1="14" x2="14" y1="11" y2="17"/>
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="empty-state">
                                            <div class="empty-notice">
                                                <div class="empty-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/>
                                                        <path d="M7 2v20"/>
                                                        <path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>
                                                    </svg>
                                                </div>

                                                <h3>Menu Belum Tersedia</h3>

                                                <p>
                                                    Saat ini belum ada menu yang terdaftar.
                                                    Tambahkan menu pertamamu sekarang untuk mulai mengelola katalog menu restoran!
                                                </p>

                                                <a href="tambah.php" class="btn btn-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                        <line x1="12" x2="12" y1="5" y2="19"/>
                                                        <line x1="5" x2="19" y1="12" y2="12"/>
                                                    </svg>
                                                    Tambah Menu Pertama
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>

                                <tr id="searchEmptyRow" style="display: none;">
                                    <td colspan="7" class="empty-state">
                                        <div class="empty-notice" style="padding: 36px 20px;">
                                            <div class="empty-icon" style="background: #f1f5f9; color: #64748b;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="11" cy="11" r="8"/>
                                                    <line x1="21" x2="16.65" y1="21" y2="16.65"/>
                                                </svg>
                                            </div>
                                            <h3>Menu Tidak Ditemukan</h3>
                                            <p>Tidak ada data menu yang cocok dengan kata kunci atau filter yang Anda pilih.</p>
                                            <button type="button" class="btn btn-secondary btn-sm" onclick="resetFilter()">
                                                Reset Filter
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-footer">
                        <span id="tableFooterText">Menampilkan <?= $totalMenu ?> dari <?= $totalMenu ?> menu terdaftar.</span>
                        <span>Total Stok: <strong><?= $totalStok ?> unit</strong></span>
                    </div>

                </section>

                <div class="site-footer">
                    Kasir Restoran &copy; <?= date('Y') ?> — Sistem POS & Manajemen Menu Restoran
                </div>

            </main>

        </div>
    </div>

    <div class="modal-backdrop" id="modalDetail" onclick="handleBackdropClick(event, 'modalDetail')">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalDetailTitle">
            <div class="modal-header">
                <div class="modal-title" id="modalDetailTitle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" x2="12" y1="16" y2="12"/>
                        <line x1="12" x2="12.01" y1="8" y2="8"/>
                    </svg>
                    <span>Detail Informasi Menu</span>
                </div>
                <button type="button" class="modal-close" onclick="closeModal('modalDetail')" aria-label="Tutup modal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" x2="6" y1="6" y2="18"/>
                        <line x1="6" x2="18" y1="6" y2="18"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Kode Menu</div>
                        <div class="detail-value" id="detailKd">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Kategori</div>
                        <div class="detail-value" id="detailKategori">-</div>
                    </div>
                    <div class="detail-item full">
                        <div class="detail-label">Nama Menu</div>
                        <div class="detail-value" id="detailNama" style="font-size: 15px;">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Harga Satuan</div>
                        <div class="detail-value" id="detailHarga" style="color: var(--primary-dark); font-weight: 700;">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Stok Tersedia</div>
                        <div class="detail-value" id="detailStock">-</div>
                    </div>
                    <div class="detail-item full">
                        <div class="detail-label">Deskripsi Lengkap</div>
                        <div class="detail-value" id="detailDeskripsi" style="font-weight: 400; color: #475569; font-size: 13px; line-height: 1.6;">-</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="detailEditBtn" class="btn btn-edit btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20h9"/>
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                    </svg>
                    Edit Menu
                </a>
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('modalDetail')">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <div class="modal-backdrop" id="modalDelete" onclick="handleBackdropClick(event, 'modalDelete')">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalDeleteTitle" style="max-width: 420px;">
            <div class="modal-body" style="text-align: center; padding: 28px 24px 20px;">
                <div class="modal-danger-icon" style="margin: 0 auto 16px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6h18"/>
                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                        <line x1="10" x2="10" y1="11" y2="17"/>
                        <line x1="14" x2="14" y1="11" y2="17"/>
                    </svg>
                </div>
                <h3 id="modalDeleteTitle" style="font-size: 17px; margin-bottom: 8px; color: var(--text);">Hapus Menu?</h3>
                <p style="color: var(--text-muted); font-size: 13px; line-height: 1.6; margin-bottom: 6px;">
                    Apakah Anda yakin ingin menghapus menu <strong id="deleteTargetNama" style="color: var(--text);">-</strong>?
                </p>
                <p style="color: var(--danger); font-size: 12px; font-weight: 500;">
                    Tindakan ini permanen dan tidak dapat dibatalkan.
                </p>
            </div>
            <div class="modal-footer" style="justify-content: center; background: #fff; padding-bottom: 22px; border-top: none;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalDelete')">
                    Batal
                </button>
                <a href="#" id="confirmDeleteLink" class="btn btn-delete">
                    Ya, Hapus Menu
                </a>
            </div>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('searchMenu');
        const filterKategori = document.getElementById('filterKategori');
        const sortMenu = document.getElementById('sortMenu');
        const tableBody = document.getElementById('menuTableBody');
        const rows = Array.from(document.querySelectorAll('.menu-row'));
        const searchEmptyRow = document.getElementById('searchEmptyRow');
        const tableFooterText = document.getElementById('tableFooterText');

        function formatRupiah(num) {
            return 'Rp ' + Number(num).toLocaleString('id-ID');
        }

        function filterAndSortMenu() {
            const keyword = searchInput.value.toLowerCase().trim();
            const kategori = filterKategori.value.toLowerCase();
            const sortVal = sortMenu.value;

            let visibleCount = 0;
            const visibleRows = [];

            rows.forEach(row => {
                const nama = (row.dataset.nama || '').toLowerCase();
                const kd = (row.dataset.kd || '').toLowerCase();
                const kat = (row.dataset.kategori || '').toLowerCase();

                const cocokKeyword = !keyword || nama.includes(keyword) || kd.includes(keyword);
                const cocokKategori = !kategori || kat === kategori;

                if (cocokKeyword && cocokKategori) {
                    row.style.display = '';
                    visibleCount++;
                    visibleRows.push(row);
                } else {
                    row.style.display = 'none';
                }
            });

            if (sortVal !== 'default') {
                visibleRows.sort((a, b) => {
                    if (sortVal === 'name-asc') {
                        return a.dataset.nama.localeCompare(b.dataset.nama);
                    } else if (sortVal === 'name-desc') {
                        return b.dataset.nama.localeCompare(a.dataset.nama);
                    } else if (sortVal === 'price-asc') {
                        return parseFloat(a.dataset.harga) - parseFloat(b.dataset.harga);
                    } else if (sortVal === 'price-desc') {
                        return parseFloat(b.dataset.harga) - parseFloat(a.dataset.harga);
                    } else if (sortVal === 'stock-asc') {
                        return parseInt(a.dataset.stock) - parseInt(b.dataset.stock);
                    } else if (sortVal === 'stock-desc') {
                        return parseInt(b.dataset.stock) - parseInt(a.dataset.stock);
                    }
                    return 0;
                });

                visibleRows.forEach(row => tableBody.appendChild(row));
            }

            let visibleIndex = 1;
            visibleRows.forEach(row => {
                const idxCell = row.querySelector('.row-index');
                if (idxCell) idxCell.textContent = visibleIndex++;
            });

            if (searchEmptyRow) {
                if (visibleCount === 0 && rows.length > 0) {
                    searchEmptyRow.style.display = '';
                } else {
                    searchEmptyRow.style.display = 'none';
                }
            }

            if (tableFooterText) {
                tableFooterText.textContent = `Menampilkan ${visibleCount} dari ${rows.length} menu terdaftar.`;
            }
        }

        function resetFilter() {
            searchInput.value = '';
            filterKategori.value = '';
            sortMenu.value = 'default';
            filterAndSortMenu();
        }

        searchInput.addEventListener('input', filterAndSortMenu);
        filterKategori.addEventListener('change', filterAndSortMenu);
        sortMenu.addEventListener('change', filterAndSortMenu);

        function openDetailModal(row) {
            if (!row) return;
            document.getElementById('detailKd').textContent = row.dataset.kd || '-';
            document.getElementById('detailNama').textContent = row.dataset.nama || '-';
            document.getElementById('detailKategori').textContent = row.dataset.kategori ? row.dataset.kategori.toUpperCase() : '-';
            document.getElementById('detailHarga').textContent = formatRupiah(row.dataset.harga || 0);

            const stock = parseInt(row.dataset.stock) || 0;
            let stockHtml = '';
            if (stock === 0) {
                stockHtml = '<span class="badge-stock badge-stock-empty">Habis (0 unit)</span>';
            } else if (stock <= 5) {
                stockHtml = '<span class="badge-stock badge-stock-low">Menipis (' + stock + ' unit)</span>';
            } else {
                stockHtml = '<span class="badge-stock badge-stock-ok">' + stock + ' unit tersedia</span>';
            }
            document.getElementById('detailStock').innerHTML = stockHtml;
            document.getElementById('detailDeskripsi').textContent = row.dataset.deskripsi || 'Tidak ada deskripsi.';

            document.getElementById('detailEditBtn').href = 'edit.php?id=' + row.dataset.id;
            openModal('modalDetail');
        }

        function openDeleteModal(id, nama) {
            document.getElementById('deleteTargetNama').textContent = '"' + nama + '"';
            document.getElementById('confirmDeleteLink').href = 'hapus.php?id=' + id;
            openModal('modalDelete');
        }

        function openModal(id) {
            const el = document.getElementById(id);
            if (el) el.classList.add('active');
        }

        function closeModal(id) {
            const el = document.getElementById(id);
            if (el) el.classList.remove('active');
        }

        function handleBackdropClick(e, id) {
            if (e.target.id === id) {
                closeModal(id);
            }
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeModal('modalDetail');
                closeModal('modalDelete');
            }
        });
    </script>

</body>

</html>