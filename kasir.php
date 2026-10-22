<?php
require 'config/database.php';

$query = "SELECT * FROM menu ORDER BY nama_menu ASC";
$result = mysqli_query($koneksi, $query);

$menus = [];
while ($row = mysqli_fetch_assoc($result)) {
    $menus[] = $row;
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
    <title>Kasir (POS) | Kasir Restoran</title>
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

            <a href="kasir.php" class="nav-link active">
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
                    <a href="index.php" style="color: inherit;">Halaman</a> / <strong>Transaksi Kasir (POS)</strong>
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

                <div class="pos-layout">

                    <div class="pos-catalog-panel">

                        <div class="pos-filter-bar">
                            <div class="pos-chips">
                                <button type="button" class="pos-chip active" onclick="setPosFilter('', this)">Semua</button>
                                <button type="button" class="pos-chip" onclick="setPosFilter('makanan', this)">Makanan</button>
                                <button type="button" class="pos-chip" onclick="setPosFilter('minuman', this)">Minuman</button>
                                <button type="button" class="pos-chip" onclick="setPosFilter('snack', this)">Snack</button>
                                <button type="button" class="pos-chip" onclick="setPosFilter('dessert', this)">Dessert</button>
                            </div>

                            <div class="search-box">
                                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"/>
                                    <line x1="21" x2="16.65" y1="21" y2="16.65"/>
                                </svg>
                                <input
                                    type="search"
                                    id="posSearchInput"
                                    class="search-input"
                                    placeholder="Cari menu pesanan..."
                                    oninput="filterPosMenu()"
                                    style="width: 200px;">
                            </div>
                        </div>

                        <div class="pos-menu-grid" id="posGrid">
                            <?php foreach ($menus as $m): ?>
                                <?php
                                $kat = strtolower($m['kategori']);
                                $stock = (int) $m['stock'];
                                $isOut = $stock <= 0;
                                $kelasBadge = match ($kat) {
                                    'makanan' => 'badge-food',
                                    'minuman' => 'badge-drink',
                                    'snack' => 'badge-snack',
                                    'dessert' => 'badge-dessert',
                                    default => ''
                                };
                                ?>
                                <div class="pos-menu-card <?= $isOut ? 'out-of-stock' : '' ?>"
                                     data-id="<?= (int) $m['id'] ?>"
                                     data-kd="<?= e($m['kd_menu']) ?>"
                                     data-nama="<?= e($m['nama_menu']) ?>"
                                     data-harga="<?= (int) $m['harga'] ?>"
                                     data-stock="<?= $stock ?>"
                                     data-kategori="<?= e($kat) ?>"
                                     onclick="addToCart(<?= (int) $m['id'] ?>)">

                                    <div>
                                        <div class="pos-card-top">
                                            <span class="badge <?= $kelasBadge ?>"><?= e($m['kategori']) ?></span>
                                            <span class="pos-card-code"><?= e($m['kd_menu']) ?></span>
                                        </div>
                                        <div class="pos-card-name"><?= e($m['nama_menu']) ?></div>
                                    </div>

                                    <div class="pos-card-bottom">
                                        <div class="pos-card-price">Rp <?= number_format((float) $m['harga'], 0, ',', '.') ?></div>
                                        <div class="pos-card-stock">
                                            <?php if ($isOut): ?>
                                                <span style="color: var(--danger); font-weight: 700;">Habis</span>
                                            <?php else: ?>
                                                Stok: <strong><?= $stock ?></strong>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div id="posEmptySearch" style="display: none; padding: 40px; text-align: center; color: var(--text-muted); background: white; border-radius: var(--radius); border: 1px solid var(--border);">
                            Tidak ada menu yang sesuai dengan pencarian atau kategori ini.
                        </div>

                    </div>

                    <div class="pos-billing-panel">

                        <div class="billing-header">
                            <div class="billing-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="8" cy="21" r="1"/>
                                    <circle cx="19" cy="21" r="1"/>
                                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                                </svg>
                                <span>Pesanan Pelanggan</span>
                            </div>
                            <span class="billing-trx-badge" id="liveClock"><?= date('H:i') ?></span>
                        </div>

                        <div class="billing-items" id="cartContainer">
                            <div class="cart-empty" id="cartEmptyState">
                                <div class="cart-empty-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="8" cy="21" r="1"/>
                                        <circle cx="19" cy="21" r="1"/>
                                        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                                    </svg>
                                </div>
                                <div style="font-weight: 600; color: var(--text); font-size: 13.5px; margin-bottom: 4px;">Keranjang Masih Kosong</div>
                                <div style="font-size: 12px;">Klik kartu menu di samping untuk menambahkan ke pesanan.</div>
                            </div>
                            <div id="cartList"></div>
                        </div>

                        <div class="billing-summary">

                            <div class="summary-row">
                                <span style="color: var(--text-muted);">Jumlah Item</span>
                                <strong id="totalItemCount">0 item</strong>
                            </div>

                            <div class="summary-row total">
                                <span>Total Tagihan</span>
                                <span class="summary-total-val" id="totalTagihanDisplay">Rp 0</span>
                            </div>

                            <div class="payment-input-group">
                                <label for="inputUangBayar">Uang Diterima (Rp)</label>
                                <input
                                    type="number"
                                    id="inputUangBayar"
                                    class="input"
                                    placeholder="Masukkan nominal uang..."
                                    min="0"
                                    step="1000"
                                    oninput="calculateChange()">

                                <div class="quick-cash-row">
                                    <button type="button" class="btn-quick-cash" onclick="setQuickCash('exact')">Uang Pas</button>
                                    <button type="button" class="btn-quick-cash" onclick="setQuickCash(20000)">20.000</button>
                                    <button type="button" class="btn-quick-cash" onclick="setQuickCash(50000)">50.000</button>
                                    <button type="button" class="btn-quick-cash" onclick="setQuickCash(100000)">100.000</button>
                                </div>
                            </div>

                            <div class="change-box" id="changeBox" style="display: none;">
                                <span class="change-label">Kembalian</span>
                                <span class="change-value" id="changeDisplay">Rp 0</span>
                            </div>

                            <div id="paymentWarning" style="display: none; font-size: 11.5px; color: var(--danger); font-weight: 600; margin-top: 8px;">
                                Uang diterima masih kurang dari total tagihan.
                            </div>

                        </div>

                        <div class="billing-actions">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="resetCart()">
                                Reset
                            </button>
                            <button type="button" id="btnCheckout" class="btn btn-primary btn-checkout" onclick="processCheckout()" disabled>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                <span>Proses Bayar</span>
                            </button>
                        </div>

                    </div>

                </div>

            </main>

        </div>
    </div>

    <div class="modal-backdrop" id="modalReceipt" onclick="handleBackdropClick(event, 'modalReceipt')">
        <div class="modal" role="dialog" aria-modal="true" style="max-width: 380px;">
            <div class="modal-header">
                <div class="modal-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <span>Transaksi Berhasil</span>
                </div>
                <button type="button" class="modal-close" onclick="closeReceiptModal()" aria-label="Tutup nota">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" x2="6" y1="6" y2="18"/>
                        <line x1="6" x2="18" y1="6" y2="18"/>
                    </svg>
                </button>
            </div>

            <div class="modal-body" style="background: #f1f5f9; padding: 18px;">
                <div class="receipt-paper" id="receiptPrintArea">
                    <div class="receipt-header">
                        <div class="receipt-title">KASIR RESTORAN</div>
                        <div>Jl. Pemrograman Web No. 1</div>
                        <div>Telp: 0812-3456-7890</div>
                    </div>

                    <div class="receipt-divider"></div>

                    <div class="receipt-row">
                        <span>No:</span>
                        <span id="recNoTrx">-</span>
                    </div>
                    <div class="receipt-row">
                        <span>Tanggal:</span>
                        <span id="recTanggal">-</span>
                    </div>
                    <div class="receipt-row">
                        <span>Kasir:</span>
                        <span id="recKasir">Kasir 1</span>
                    </div>

                    <div class="receipt-divider"></div>

                    <div id="recItemsList"></div>

                    <div class="receipt-divider"></div>

                    <div class="receipt-row receipt-total">
                        <span>TOTAL</span>
                        <span id="recTotal">-</span>
                    </div>
                    <div class="receipt-row">
                        <span>TUNAI</span>
                        <span id="recBayar">-</span>
                    </div>
                    <div class="receipt-row">
                        <span>KEMBALI</span>
                        <span id="recKembali">-</span>
                    </div>

                    <div class="receipt-divider"></div>

                    <div class="receipt-footer">
                        Terima Kasih Atas Kunjungan Anda!<br>
                        Selamat Menikmati Hidangan.
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="justify-content: center; gap: 10px;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeReceiptModal()">
                    Transaksi Baru
                </button>
                <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 6 2 18 2 18 9"/>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                        <rect width="12" height="8" x="6" y="14"/>
                    </svg>
                    Cetak Nota
                </button>
            </div>
        </div>
    </div>

    <script>
        const cart = {};
        let activePosCategory = '';

        function formatRupiah(num) {
            return 'Rp ' + Number(num).toLocaleString('id-ID');
        }

        function setPosFilter(cat, btn) {
            activePosCategory = cat.toLowerCase();
            document.querySelectorAll('.pos-chip').forEach(c => c.classList.remove('active'));
            if (btn) btn.classList.add('active');
            filterPosMenu();
        }

        function filterPosMenu() {
            const query = document.getElementById('posSearchInput').value.toLowerCase().trim();
            const cards = document.querySelectorAll('.pos-menu-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const nama = card.dataset.nama.toLowerCase();
                const kd = card.dataset.kd.toLowerCase();
                const kat = card.dataset.kategori.toLowerCase();

                const cocokQuery = !query || nama.includes(query) || kd.includes(query);
                const cocokKat = !activePosCategory || kat === activePosCategory;

                if (cocokQuery && cocokKat) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            document.getElementById('posEmptySearch').style.display = visibleCount === 0 ? 'block' : 'none';
        }

        function addToCart(id) {
            const card = document.querySelector(`.pos-menu-card[data-id="${id}"]`);
            if (!card) return;

            const stock = parseInt(card.dataset.stock) || 0;
            if (stock <= 0) {
                alert('Maaf, stok menu ini sudah habis.');
                return;
            }

            const currentQty = cart[id] ? cart[id].qty : 0;
            if (currentQty + 1 > stock) {
                alert(`Maksimal pesanan mencapai batas stok tersedia (${stock} unit).`);
                return;
            }

            if (!cart[id]) {
                cart[id] = {
                    id: id,
                    nama: card.dataset.nama,
                    harga: parseInt(card.dataset.harga),
                    stock: stock,
                    qty: 1
                };
            } else {
                cart[id].qty++;
            }

            renderCart();
        }

        function updateQty(id, delta) {
            if (!cart[id]) return;

            const newQty = cart[id].qty + delta;
            if (newQty <= 0) {
                delete cart[id];
            } else if (newQty > cart[id].stock) {
                alert(`Stok tidak mencukupi. Maksimal ${cart[id].stock} unit.`);
                return;
            } else {
                cart[id].qty = newQty;
            }

            renderCart();
        }

        function removeFromCart(id) {
            if (cart[id]) {
                delete cart[id];
                renderCart();
            }
        }

        function resetCart() {
            for (let k in cart) delete cart[k];
            document.getElementById('inputUangBayar').value = '';
            renderCart();
        }

        function getTotalHarga() {
            let total = 0;
            for (let k in cart) {
                total += (cart[k].harga * cart[k].qty);
            }
            return total;
        }

        function getTotalItems() {
            let count = 0;
            for (let k in cart) {
                count += cart[k].qty;
            }
            return count;
        }

        function renderCart() {
            const cartList = document.getElementById('cartList');
            const emptyState = document.getElementById('cartEmptyState');
            const totalDisplay = document.getElementById('totalTagihanDisplay');
            const itemCount = document.getElementById('totalItemCount');
            const btnCheckout = document.getElementById('btnCheckout');

            const keys = Object.keys(cart);
            cartList.innerHTML = '';

            if (keys.length === 0) {
                emptyState.style.display = 'block';
                totalDisplay.textContent = 'Rp 0';
                itemCount.textContent = '0 item';
                btnCheckout.disabled = true;
                calculateChange();
                return;
            }

            emptyState.style.display = 'none';

            keys.forEach(k => {
                const item = cart[k];
                const subtotal = item.harga * item.qty;

                const row = document.createElement('div');
                row.className = 'cart-item';
                row.innerHTML = `
                    <div class="cart-item-info">
                        <div class="cart-item-name">${item.nama}</div>
                        <div class="cart-item-price">${formatRupiah(item.harga)}</div>
                    </div>
                    <div class="cart-stepper">
                        <button type="button" class="btn-stepper" onclick="updateQty(${item.id}, -1)">&minus;</button>
                        <span class="cart-qty">${item.qty}</span>
                        <button type="button" class="btn-stepper" onclick="updateQty(${item.id}, 1)">&plus;</button>
                    </div>
                    <div class="cart-subtotal">${formatRupiah(subtotal)}</div>
                    <button type="button" class="btn-cart-del" onclick="removeFromCart(${item.id})" title="Hapus item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" x2="6" y1="6" y2="18"/>
                            <line x1="6" x2="18" y1="6" y2="18"/>
                        </svg>
                    </button>
                `;
                cartList.appendChild(row);
            });

            totalDisplay.textContent = formatRupiah(getTotalHarga());
            itemCount.textContent = getTotalItems() + ' item';
            calculateChange();
        }

        function setQuickCash(val) {
            const total = getTotalHarga();
            const input = document.getElementById('inputUangBayar');
            if (val === 'exact') {
                input.value = total;
            } else {
                input.value = val;
            }
            calculateChange();
        }

        function calculateChange() {
            const total = getTotalHarga();
            const inputVal = parseInt(document.getElementById('inputUangBayar').value) || 0;
            const changeBox = document.getElementById('changeBox');
            const changeDisplay = document.getElementById('changeDisplay');
            const warning = document.getElementById('paymentWarning');
            const btnCheckout = document.getElementById('btnCheckout');

            if (total === 0) {
                changeBox.style.display = 'none';
                warning.style.display = 'none';
                btnCheckout.disabled = true;
                return;
            }

            if (inputVal >= total) {
                const diff = inputVal - total;
                changeDisplay.textContent = formatRupiah(diff);
                changeBox.style.display = 'flex';
                warning.style.display = 'none';
                btnCheckout.disabled = false;
            } else if (inputVal > 0) {
                changeBox.style.display = 'none';
                warning.style.display = 'block';
                btnCheckout.disabled = true;
            } else {
                changeBox.style.display = 'none';
                warning.style.display = 'none';
                btnCheckout.disabled = true;
            }
        }

        async function processCheckout() {
            const total = getTotalHarga();
            const bayar = parseInt(document.getElementById('inputUangBayar').value) || 0;

            if (total <= 0) {
                alert('Keranjang pesanan masih kosong.');
                return;
            }

            if (bayar < total) {
                alert('Uang pembayaran masih kurang.');
                return;
            }

            const itemsPayload = Object.values(cart).map(it => ({
                id: it.id,
                qty: it.qty
            }));

            const payload = {
                total_bayar: bayar,
                nama_kasir: 'Kasir 1',
                metode_bayar: 'Tunai',
                items: itemsPayload
            };

            const btn = document.getElementById('btnCheckout');
            btn.disabled = true;
            btn.textContent = 'Memproses...';

            try {
                const res = await fetch('transaksi_proses.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();

                if (data.success) {
                    showReceipt(data.transaksi);

                    itemsPayload.forEach(it => {
                        const card = document.querySelector(`.pos-menu-card[data-id="${it.id}"]`);
                        if (card) {
                            const newStock = Math.max(0, parseInt(card.dataset.stock) - it.qty);
                            card.dataset.stock = newStock;
                            const stockLabel = card.querySelector('.pos-card-stock');
                            if (newStock === 0) {
                                card.classList.add('out-of-stock');
                                if (stockLabel) stockLabel.innerHTML = '<span style="color: var(--danger); font-weight: 700;">Habis</span>';
                            } else {
                                if (stockLabel) stockLabel.innerHTML = `Stok: <strong>${newStock}</strong>`;
                            }
                        }
                    });

                    resetCart();
                } else {
                    alert('Gagal: ' + data.pesan);
                }
            } catch (err) {
                alert('Terjadi kesalahan saat memproses pembayaran.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <span>Proses Bayar</span>
                `;
            }
        }

        function showReceipt(trx) {
            document.getElementById('recNoTrx').textContent = trx.no_transaksi;
            document.getElementById('recTanggal').textContent = trx.tanggal;
            document.getElementById('recKasir').textContent = trx.nama_kasir;
            document.getElementById('recTotal').textContent = formatRupiah(trx.total_harga);
            document.getElementById('recBayar').textContent = formatRupiah(trx.total_bayar);
            document.getElementById('recKembali').textContent = formatRupiah(trx.kembalian);

            const itemsContainer = document.getElementById('recItemsList');
            itemsContainer.innerHTML = '';

            trx.items.forEach(it => {
                const div = document.createElement('div');
                div.innerHTML = `
                    <div style="font-weight: 600;">${it.nama_menu}</div>
                    <div class="receipt-row" style="color: #444; font-size: 11px;">
                        <span>${it.jumlah} x ${formatRupiah(it.harga)}</span>
                        <span>${formatRupiah(it.subtotal)}</span>
                    </div>
                `;
                itemsContainer.appendChild(div);
            });

            document.getElementById('modalReceipt').classList.add('active');
        }

        function closeReceiptModal() {
            document.getElementById('modalReceipt').classList.remove('active');
        }

        function handleBackdropClick(e, id) {
            if (e.target.id === id) {
                closeReceiptModal();
            }
        }

        setInterval(() => {
            const now = new Date();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            document.getElementById('liveClock').textContent = `${h}:${m}`;
        }, 1000);
    </script>

</body>

</html>
