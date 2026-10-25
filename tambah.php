<?php
require 'config/database.php';

$old = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);

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
    <title>Tambah Menu | Kasir Restoran</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
<div class="dashboard-layout">

    <aside class="sidebar">
        <a href="index.php" class="brand">
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
        </a>

        <div class="nav-label">MENU UTAMA</div>

        <a href="kasir.php" class="nav-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="20" height="14" x="2" y="5" rx="2"/>
                <line x1="2" x2="22" y1="10" y2="10"/>
            </svg>
            <span>Kasir (POS)</span>
            <span class="nav-link-badge">LIVE</span>
        </a>

        <a href="index.php" class="nav-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="7" height="9" x="3" y="3" rx="1.5"/>
                <rect width="7" height="5" x="14" y="3" rx="1.5"/>
                <rect width="7" height="9" x="14" y="12" rx="1.5"/>
                <rect width="7" height="5" x="3" y="16" rx="1.5"/>
            </svg>
            <span>Dashboard & Riwayat</span>
        </a>

        <a href="index.php#menuTable" class="nav-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="4" x2="20" y1="6" y2="6"/>
                <line x1="4" x2="20" y1="12" y2="12"/>
                <line x1="4" x2="20" y1="18" y2="18"/>
            </svg>
            <span>Kelola Menu</span>
        </a>

        <a href="tambah.php" class="nav-link active">
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
                <a href="index.php" style="color: inherit;">Halaman</a> / <strong>Tambah Menu Restoran</strong>
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

            <div class="page-header">
                <div>
                    <h1 class="page-title">Tambah Menu Baru</h1>
                    <p class="welcome-text">
                        Tambahkan item makanan, minuman, snack, atau dessert ke dalam daftar menu kasir restoran.
                    </p>
                </div>

                <a href="index.php" class="back-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" x2="5" y1="12" y2="12"/>
                        <polyline points="12 19 5 12 12 5"/>
                    </svg>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>

            <?php if (isset($_SESSION['pesan'])): ?>
                <?php
                $tipePesan = $_SESSION['tipe'] ?? 'error';
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

            <section class="form-card">

                <div class="card-heading">
                    <h2>Informasi Menu</h2>
                    <p>
                        Lengkapi informasi menu di bawah ini. Kolom dengan tanda <span class="required">*</span> wajib diisi.
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
                                    value="<?= e($old['kd_menu'] ?? '') ?>"
                                    placeholder="Contoh: MN001"
                                    maxlength="20"
                                    required
                                >
                                <small class="helper">
                                    Gunakan kode unik untuk membedakan tiap menu di kasir.
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
                                    value="<?= e($old['nama_menu'] ?? '') ?>"
                                    placeholder="Contoh: Nasi Goreng Spesial"
                                    maxlength="100"
                                    required
                                >
                            </div>

                            <div class="field">
                                <label for="kategori">
                                    Kategori <span class="required">*</span>
                                </label>
                                <?php $selectedKat = $old['kategori'] ?? ''; ?>
                                <select id="kategori" name="kategori" required>
                                    <option value="" disabled <?= empty($selectedKat) ? 'selected' : '' ?>>Pilih Kategori</option>
                                    <option value="Makanan" <?= $selectedKat === 'Makanan' ? 'selected' : '' ?>>Makanan</option>
                                    <option value="Minuman" <?= $selectedKat === 'Minuman' ? 'selected' : '' ?>>Minuman</option>
                                    <option value="Snack" <?= $selectedKat === 'Snack' ? 'selected' : '' ?>>Snack</option>
                                    <option value="Dessert" <?= $selectedKat === 'Dessert' ? 'selected' : '' ?>>Dessert</option>
                                </select>
                            </div>

                            <div class="field">
                                <label for="harga">
                                    Harga Satuan (Rp) <span class="required">*</span>
                                </label>
                                <input
                                    class="input"
                                    type="number"
                                    id="harga"
                                    name="harga"
                                    value="<?= e($old['harga'] ?? '') ?>"
                                    placeholder="Contoh: 25000"
                                    min="0"
                                    step="1"
                                    required
                                >
                                <small class="helper">
                                    Masukkan nominal harga tanpa titik atau koma.
                                </small>
                            </div>

                            <div class="field">
                                <label for="stock">
                                    Stok Awal di Kasir <span class="required">*</span>
                                </label>
                                <input
                                    class="input"
                                    type="number"
                                    id="stock"
                                    name="stock"
                                    value="<?= e($old['stock'] ?? '') ?>"
                                    placeholder="Contoh: 20"
                                    min="0"
                                    step="1"
                                    required
                                >
                                <small class="helper">
                                    Jumlah porsi atau unit yang siap disajikan.
                                </small>
                            </div>

                            <div class="field full">
                                <label for="deskripsi">Deskripsi Menu</label>
                                <textarea
                                    id="deskripsi"
                                    name="deskripsi"
                                    placeholder="Tuliskan deskripsi singkat menu, bahan utama, atau tingkat kepedasan..."
                                ><?= e($old['deskripsi'] ?? '') ?></textarea>
                                <small class="helper">
                                    Opsional. Informasi tambahan untuk staf kasir atau pelanggan.
                                </small>
                            </div>

                        </div>
                    </div>

                    <div class="form-footer">
                        <div class="footer-note">
                            Pastikan informasi menu sudah tepat sebelum menyimpan data.
                        </div>

                        <div class="form-actions">
                            <a href="index.php" class="btn btn-cancel">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-save">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                <span>Simpan Menu</span>
                            </button>
                        </div>
                    </div>
                </form>

            </section>

            <div class="site-footer">
                Kasir Restoran &copy; <?= date('Y') ?> — Sistem POS & Manajemen Menu Restoran
            </div>

        </main>

    </div>

</div>
</body>

</html>
