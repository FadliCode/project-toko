<?php
include 'config/koneksi.php';

// Total Barang
$total_barang = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT COUNT(*) AS total FROM barang
"))['total'] ?? 0;

// Total Stok
$total_stok = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT COALESCE(SUM(banyak_stok), 0) AS total FROM stok
"))['total'] ?? 0;

// Total Barang Masuk
$total_masuk = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT COALESCE(SUM(jumlah), 0) AS total FROM barang_masuk
"))['total'] ?? 0;

// Total Barang Keluar
$total_keluar = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT COALESCE(SUM(jumlah), 0) AS total FROM barang_keluar
"))['total'] ?? 0;

$stok_per_barang = mysqli_query($koneksi, "
    SELECT
        b.nama_barang,
        k.nama_kategori,
        COALESCE(st.banyak_stok, 0) AS total_stok
    FROM barang b
    JOIN kategori_barang k ON b.kategori_barang_id = k.id_kategori
    LEFT JOIN stok st ON st.barang_id = b.id_barang
    ORDER BY total_stok DESC, b.nama_barang ASC
");

$detail_stok = mysqli_query($koneksi, "
    SELECT
        b.nama_barang,
        k.nama_kategori,
        COALESCE(st.banyak_stok, 0) AS banyak_stok
    FROM barang b
    JOIN kategori_barang k ON b.kategori_barang_id = k.id_kategori
    LEFT JOIN stok st ON st.barang_id = b.id_barang
    ORDER BY k.nama_kategori ASC, b.nama_barang ASC
");
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

    .stock-card {
        border: 0;
        border-radius: 12px;
        box-shadow: 0 10px 28px rgba(21, 35, 52, .08);
    }

    .stock-card-title {
        color: #2b3035;
        font-size: 18px;
        font-weight: 800;
        margin: 0;
    }

    .category-stock-item {
        border: 1px solid #edf0f3;
        border-radius: 10px;
        padding: 14px;
    }

    .category-stock-head {
        align-items: center;
        display: flex;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 10px;
    }

    .category-name {
        color: #2b3035;
        font-weight: 800;
    }

    .category-meta {
        color: #7b7f86;
        font-size: 13px;
        font-weight: 600;
    }

    .stock-progress {
        background: #eef3f7;
        border-radius: 999px;
        height: 10px;
        overflow: hidden;
    }

    .stock-progress-bar {
        background: #42b49d;
        border-radius: 999px;
        height: 100%;
        min-width: 6px;
    }

    .stock-table thead th {
        background: #ea4d6c;
        border-color: #f08398;
        color: #fff;
        font-weight: 800;
        text-align: center;
    }

    .stock-table tbody td {
        vertical-align: middle;
    }
</style>

<h3 class="dashboard-title">Dashboard</h3>

<div class="row">
    <div class="col-sm-6 col-lg-3">
        <div class="card metric-card text-center">
            <div class="card-body">
                <div class="metric-icon bg-primary bg-opacity-10 text-primary">
                    <i class="ri-archive-line"></i>
                </div>
                <div class="metric-label">Total Barang</div>
                <h3 class="metric-value"><?= number_format((int) $total_barang, 0, ',', '.') ?></h3>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card metric-card text-center">
            <div class="card-body">
                <div class="metric-icon bg-success bg-opacity-10 text-success">
                    <i class="ri-stack-line"></i>
                </div>
                <div class="metric-label">Total Stok</div>
                <h3 class="metric-value"><?= number_format((int) $total_stok, 0, ',', '.') ?></h3>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card metric-card text-center">
            <div class="card-body">
                <div class="metric-icon bg-info bg-opacity-10 text-info">
                    <i class="ri-download-2-line"></i>
                </div>
                <div class="metric-label">Total Barang Masuk</div>
                <h3 class="metric-value"><?= number_format((int) $total_masuk, 0, ',', '.') ?></h3>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card metric-card text-center">
            <div class="card-body">
                <div class="metric-icon bg-danger bg-opacity-10 text-danger">
                    <i class="ri-upload-2-line"></i>
                </div>
                <div class="metric-label">Total Barang Keluar</div>
                <h3 class="metric-value"><?= number_format((int) $total_keluar, 0, ',', '.') ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-5">
        <div class="card stock-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="stock-card-title">Stok per Jenis Barang</h4>
                    <span class="badge bg-success">Total <?= number_format((int) $total_stok, 0, ',', '.') ?></span>
                </div>

                <div class="d-grid gap-3">
                    <?php if ($stok_per_barang && mysqli_num_rows($stok_per_barang) > 0) { ?>
                        <?php while ($barangStok = mysqli_fetch_assoc($stok_per_barang)) {
                            $jumlahStok = (int) $barangStok['total_stok'];
                            $persen = $total_stok > 0 ? round(($jumlahStok / $total_stok) * 100) : 0;
                            ?>
                            <div class="category-stock-item">
                                <div class="category-stock-head">
                                    <div>
                                        <div class="category-name"><?= htmlspecialchars($barangStok['nama_barang']) ?></div>
                                        <div class="category-meta"><?= htmlspecialchars($barangStok['nama_kategori']) ?></div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold"><?= number_format($jumlahStok, 0, ',', '.') ?></div>
                                        <div class="category-meta"><?= $persen ?>%</div>
                                    </div>
                                </div>
                                <div class="stock-progress">
                                    <div class="stock-progress-bar" style="width: <?= $persen ?>%;"></div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="text-center text-muted py-4">Data barang belum tersedia.</div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-7">
        <div class="card stock-card">
            <div class="card-body">
                <h4 class="stock-card-title mb-3">Detail Stok Barang</h4>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover stock-table mb-0">
                        <thead>
                            <tr>
                                <th width="60">No</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th width="110">Stok</th>
                                <th width="120">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            if ($detail_stok && mysqli_num_rows($detail_stok) > 0) {
                                while ($barang = mysqli_fetch_assoc($detail_stok)) {
                                    $stok = (int) $barang['banyak_stok'];
                                    $statusClass = $stok <= 10 ? 'bg-danger' : 'bg-success';
                                    $statusText = $stok <= 10 ? 'Menipis' : 'Aman';
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($barang['nama_barang']) ?></td>
                                        <td><?= htmlspecialchars($barang['nama_kategori']) ?></td>
                                        <td class="text-center fw-bold"><?= number_format($stok, 0, ',', '.') ?></td>
                                        <td class="text-center"><span
                                                class="badge <?= $statusClass ?>"><?= $statusText ?></span></td>
                                    </tr>
                                <?php }
                            } else { ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Data stok barang belum tersedia.
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>