<?php
include 'config/koneksi.php';

// Ambil data stok barang dari semua supplier
$stok_barang = mysqli_query($koneksi, "
    SELECT
        b.id_barang,
        b.nama_barang,
        k.nama_kategori,
        COALESCE(s.banyak_stok, 0) AS stok,
        b.satuan,
        CASE 
            WHEN COALESCE(s.banyak_stok, 0) > 20 THEN 'bg-success'
            WHEN COALESCE(s.banyak_stok, 0) > 10 THEN 'bg-warning'
            ELSE 'bg-danger'
        END AS status_color,
        CASE 
            WHEN COALESCE(s.banyak_stok, 0) > 20 THEN 'Tersedia'
            WHEN COALESCE(s.banyak_stok, 0) > 10 THEN 'Terbatas'
            ELSE 'Habis'
        END AS status_text
    FROM barang b
    JOIN kategori_barang k ON b.kategori_barang_id = k.id_kategori
    LEFT JOIN stok s ON s.barang_id = b.id_barang
    ORDER BY k.nama_kategori ASC, b.nama_barang ASC
");

// Ambil data restok terbaru dari supplier
$restok_terbaru = mysqli_query($koneksi, "
    SELECT 
        bm.id,
        b.nama_barang,
        k.nama_kategori,
        bm.jumlah,
        u.nama as supplier_nama,
        bm.tanggal_masuk,
        bm.keterangan
    FROM barang_masuk bm
    JOIN barang b ON bm.barang_id = b.id_barang
    JOIN kategori_barang k ON b.kategori_barang_id = k.id_kategori
    JOIN users u ON bm.supplier_id = u.id
    WHERE u.role = 'supplier'
    ORDER BY bm.tanggal_masuk DESC
    LIMIT 10
");

// Statistik umum
$total_barang = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM barang"))['total'] ?? 0;
$barang_tersedia = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM stok WHERE banyak_stok > 20"))['total'] ?? 0;
$barang_terbatas = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM stok WHERE banyak_stok BETWEEN 1 AND 20"))['total'] ?? 0;
$barang_habis = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM stok WHERE banyak_stok = 0"))['total'] ?? 0;
?>

<style>
    .dashboard-title {
        color: #212529;
        font-size: 24px;
        font-weight: 800;
        margin-bottom: 20px;
    }

    .metric-card {
        border: 0;
        border-radius: 12px;
        box-shadow: 0 10px 28px rgba(21, 35, 52, .08);
    }

    .metric-icon {
        align-items: center;
        border-radius: 10px;
        display: inline-flex;
        font-size: 24px;
        height: 46px;
        justify-content: center;
        margin-bottom: 14px;
        width: 46px;
    }

    .metric-label {
        color: #7b7f86;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .metric-value {
        color: #16191d;
        font-size: 28px;
        font-weight: 800;
        line-height: 1.1;
        margin: 0;
    }

    .product-card {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 15px;
        transition: all 0.3s ease;
    }

    .product-card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .product-name {
        font-weight: 700;
        color: #2b3035;
        margin-bottom: 5px;
    }

    .product-category {
        font-size: 12px;
        color: #7b7f86;
        margin-bottom: 10px;
    }

    .stock-badge {
        display: inline-block;
        padding: 8px 12px;
        border-radius: 6px;
        color: white;
        font-weight: 600;
        font-size: 14px;
    }

    .restok-item {
        border-left: 4px solid #4e73df;
        padding: 12px;
        margin-bottom: 10px;
        background: #f8f9fa;
        border-radius: 4px;
    }

    .restok-barang {
        font-weight: 600;
        color: #2b3035;
    }

    .restok-supplier {
        font-size: 12px;
        color: #7b7f86;
    }

    .restok-tanggal {
        font-size: 11px;
        color: #adb5bd;
    }
</style>

<h3 class="dashboard-title">📦 Pantau Ketersediaan Barang & Informasi Restok</h3>

<!-- Statistik -->
<div class="row mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card metric-card text-center">
            <div class="card-body">
                <div class="metric-icon bg-primary bg-opacity-10 text-primary">
                    <i class="ri-archive-line"></i>
                </div>
                <div class="metric-label">Total Barang</div>
                <h3 class="metric-value"><?= number_format($total_barang, 0, ',', '.') ?></h3>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card metric-card text-center">
            <div class="card-body">
                <div class="metric-icon bg-success bg-opacity-10 text-success">
                    <i class="ri-checkbox-circle-line"></i>
                </div>
                <div class="metric-label">Tersedia</div>
                <h3 class="metric-value"><?= number_format($barang_tersedia, 0, ',', '.') ?></h3>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card metric-card text-center">
            <div class="card-body">
                <div class="metric-icon bg-warning bg-opacity-10 text-warning">
                    <i class="ri-alert-line"></i>
                </div>
                <div class="metric-label">Terbatas</div>
                <h3 class="metric-value"><?= number_format($barang_terbatas, 0, ',', '.') ?></h3>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card metric-card text-center">
            <div class="card-body">
                <div class="metric-icon bg-danger bg-opacity-10 text-danger">
                    <i class="ri-close-circle-line"></i>
                </div>
                <div class="metric-label">Habis</div>
                <h3 class="metric-value"><?= number_format($barang_habis, 0, ',', '.') ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Daftar Barang -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="ri-list-check"></i> Daftar Barang Tersedia
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php if ($stok_barang && mysqli_num_rows($stok_barang) > 0) { ?>
                        <?php while ($barang = mysqli_fetch_assoc($stok_barang)) { ?>
                            <div class="col-md-6 mb-3">
                                <div class="product-card">
                                    <div class="product-name">
                                        <i class="ri-box-3-line"></i> <?= htmlspecialchars($barang['nama_barang']) ?>
                                    </div>
                                    <div class="product-category">
                                        <?= htmlspecialchars($barang['nama_kategori']) ?>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted">Stok: </small>
                                            <strong><?= number_format($barang['stok'], 0, ',', '.') ?></strong>
                                            <small><?= htmlspecialchars($barang['satuan']) ?></small>
                                        </div>
                                        <span class="stock-badge <?= $barang['status_color'] ?>">
                                            <?= $barang['status_text'] ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="col-12">
                            <div class="text-center text-muted py-5">
                                <i class="ri-inbox-line" style="font-size: 50px; opacity: 0.3;"></i>
                                <p class="mt-3">Belum ada data barang</p>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Restok Terbaru -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    <i class="ri-truck-line"></i> Restok Terbaru dari Supplier
                </h5>
            </div>
            <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                <?php if ($restok_terbaru && mysqli_num_rows($restok_terbaru) > 0) { ?>
                    <?php while ($restok = mysqli_fetch_assoc($restok_terbaru)) { ?>
                        <div class="restok-item">
                            <div class="restok-barang">
                                <?= htmlspecialchars($restok['nama_barang']) ?>
                            </div>
                            <div class="restok-supplier">
                                <i class="ri-building-line"></i> <?= htmlspecialchars($restok['supplier_nama']) ?>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="badge bg-info">
                                    +<?= number_format($restok['jumlah'], 0, ',', '.') ?>
                                </span>
                                <span class="restok-tanggal">
                                    <?= date('d/m/Y H:i', strtotime($restok['tanggal_masuk'])) ?>
                                </span>
                            </div>
                            <?php if (!empty($restok['keterangan'])) { ?>
                                <small class="text-muted d-block mt-2" style="font-style: italic;">
                                    "<?= htmlspecialchars($restok['keterangan']) ?>"
                                </small>
                            <?php } ?>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="text-center text-muted py-4">
                        <i class="ri-inbox-line" style="font-size: 40px;"></i>
                        <p class="mt-2">Belum ada informasi restok</p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
