<?php
include 'config/koneksi.php';

$tgl1 = $_GET['tgl1'] ?? '';
$tgl2 = $_GET['tgl2'] ?? '';

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl1)) {
    $tgl1 = '';
}

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl2)) {
    $tgl2 = '';
}

$filterAktif = $tgl1 && $tgl2;
$whereMasuk = $filterAktif ? "WHERE bm.tanggal BETWEEN '$tgl1' AND '$tgl2'" : '';
$whereKeluar = $filterAktif ? "WHERE bk.tanggal BETWEEN '$tgl1' AND '$tgl2'" : '';
$periodeText = $filterAktif
    ? date('d/m/Y', strtotime($tgl1)) . ' - ' . date('d/m/Y', strtotime($tgl2))
    : 'Semua Periode';

$totalJenisBarang = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM barang"))['total'] ?? 0;
$totalBarangMasuk = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COALESCE(SUM(bm.jumlah), 0) AS total FROM barang_masuk bm $whereMasuk"))['total'] ?? 0;
$totalBarangKeluar = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COALESCE(SUM(bk.jumlah), 0) AS total FROM barang_keluar bk $whereKeluar"))['total'] ?? 0;
$totalTransaksiMasuk = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM barang_masuk bm $whereMasuk"))['total'] ?? 0;
$totalTransaksiKeluar = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM barang_keluar bk $whereKeluar"))['total'] ?? 0;

$totalTransaksi = (int) $totalTransaksiMasuk + (int) $totalTransaksiKeluar;
$totalBarangMasuk = (int) $totalBarangMasuk;
$totalBarangKeluar = (int) $totalBarangKeluar;
$saldoStok = $totalBarangMasuk - $totalBarangKeluar;
$totalMutasi = $totalBarangMasuk + $totalBarangKeluar;
$persenMasuk = $totalMutasi > 0 ? round(($totalBarangMasuk / $totalMutasi) * 100) : 0;
$persenKeluar = $totalMutasi > 0 ? 100 - $persenMasuk : 0;
?>

<style>
    .laporan-page {
        background: #eef3f7;
        margin: -24px;
        min-height: calc(100vh - 70px);
        padding: 24px;
    }

    .laporan-top {
        align-items: center;
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .laporan-title {
        color: #212529;
        font-size: 24px;
        font-weight: 800;
        margin: 0;
    }

    .date-pill {
        align-items: center;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 8px 24px rgba(21, 35, 52, .08);
        color: #495057;
        display: inline-flex;
        font-weight: 600;
        gap: 8px;
        padding: 10px 14px;
    }

    .report-card {
        background: #fff;
        border: 0;
        border-radius: 12px;
        box-shadow: 0 10px 28px rgba(21, 35, 52, .09);
    }

    .filter-box {
        margin-bottom: 22px;
        padding: 20px;
    }

    .filter-box .form-label {
        color: #6c757d;
        font-size: 14px;
        font-weight: 700;
    }

    .filter-box .form-control,
    .filter-box .form-select {
        border-color: #dee2e6;
        border-radius: 4px;
        min-height: 42px;
    }

    .report-btn {
        border: 0;
        border-radius: 8px;
        font-weight: 700;
        min-height: 42px;
        width: 100%;
    }

    .btn-show {
        background: #0ebfff;
        color: #fff;
    }

    .btn-reset-report {
        background: #fb1b7c;
        color: #fff;
    }

    .summary-grid {
        display: grid;
        gap: 22px;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        margin-bottom: 22px;
    }

    .summary-card {
        padding: 24px 18px;
        text-align: center;
    }

    .summary-label {
        color: #7b7f86;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .summary-value {
        color: #16191d;
        font-size: 24px;
        font-weight: 800;
        line-height: 1.1;
    }

    .content-grid {
        display: grid;
        gap: 22px;
        grid-template-columns: minmax(0, 2fr) minmax(280px, .95fr);
    }

    .section-box {
        padding: 22px;
    }

    .section-title {
        color: #2b3035;
        font-size: 18px;
        font-weight: 800;
        margin-bottom: 18px;
    }

    .report-table {
        margin-bottom: 0;
    }

    .report-table thead th {
        background: #fb1b7c;
        border-color: #ff72b0;
        color: #fff;
        font-weight: 800;
        text-align: center;
        vertical-align: middle;
    }

    .report-table tbody td {
        color: #2b3035;
        text-align: center;
        vertical-align: middle;
    }

    .report-table tbody td.text-start {
        text-align: left;
    }

    .inventory-donut {
        align-items: center;
        background: conic-gradient(#0ebfff 0 <?php echo $persenMasuk; ?>%, #fb1b7c <?php echo $persenMasuk; ?>% 100%);
        border-radius: 50%;
        display: flex;
        height: 170px;
        justify-content: center;
        margin: 16px auto 22px;
        width: 170px;
    }

    .inventory-donut-inner {
        align-items: center;
        background: #fff;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        font-size: 13px;
        font-weight: 800;
        height: 92px;
        justify-content: center;
        line-height: 1.35;
        width: 92px;
    }

    .legend-list {
        display: grid;
        gap: 10px;
        justify-content: center;
        margin-top: 12px;
    }

    .legend-item {
        align-items: center;
        color: #495057;
        display: flex;
        font-weight: 600;
        gap: 8px;
    }

    .legend-color {
        border-radius: 4px;
        display: inline-block;
        height: 14px;
        width: 14px;
    }

    @media (max-width: 992px) {
        .summary-grid,
        .content-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 768px) {
        .laporan-page {
            margin: -12px;
            padding: 16px;
        }

        .laporan-top {
            align-items: flex-start;
            flex-direction: column;
            gap: 12px;
        }

        .summary-grid,
        .content-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="laporan-page">
    <div class="laporan-top">
        <h4 class="laporan-title">Laporan Inventaris</h4>
        <div class="date-pill">
            <i class="ri-calendar-line"></i>
            <?= date('d M Y') ?>
        </div>
    </div>

    <div class="report-card filter-box">
        <form method="GET" class="row g-3 align-items-end">
            <input type="hidden" name="page" value="laporan">

            <div class="col-lg-3 col-md-6">
                <label class="form-label">Pilih Periode</label>
                <select class="form-select" disabled>
                    <option><?= htmlspecialchars($periodeText) ?></option>
                </select>
            </div>

            <div class="col-lg-3 col-md-6">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="tgl1" value="<?= htmlspecialchars($tgl1) ?>" class="form-control">
            </div>

            <div class="col-lg-3 col-md-6">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="tgl2" value="<?= htmlspecialchars($tgl2) ?>" class="form-control">
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="row g-2">
                    <div class="col-6">
                        <button type="submit" class="btn report-btn btn-show">
                            <i class="ri-eye-line"></i> Tampilkan
                        </button>
                    </div>
                    <div class="col-6">
                        <a href="index.php?page=laporan" class="btn report-btn btn-reset-report">
                            <i class="ri-refresh-line"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="summary-grid">
        <div class="report-card summary-card">
            <div class="summary-label">Total Transaksi</div>
            <div class="summary-value"><?= number_format($totalTransaksi, 0, ',', '.') ?></div>
        </div>

        <div class="report-card summary-card">
            <div class="summary-label">Total Barang Masuk</div>
            <div class="summary-value"><?= number_format($totalBarangMasuk, 0, ',', '.') ?></div>
        </div>

        <div class="report-card summary-card">
            <div class="summary-label">Total Barang Keluar</div>
            <div class="summary-value"><?= number_format($totalBarangKeluar, 0, ',', '.') ?></div>
        </div>

        <div class="report-card summary-card">
            <div class="summary-label">Saldo Mutasi Stok</div>
            <div class="summary-value"><?= number_format($saldoStok, 0, ',', '.') ?></div>
        </div>
    </div>

    <div class="content-grid">
        <div class="report-card section-box">
            <h5 class="section-title">Rincian Inventaris</h5>

            <div class="table-responsive">
                <table class="table table-bordered table-hover report-table">
                    <thead>
                        <tr>
                            <th width="60">No</th>
                            <th>Jenis</th>
                            <th>Nama Barang</th>
                            <th>Tanggal</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $qMutasi = "
                            SELECT 'Masuk' AS jenis, b.nama_barang, bm.tanggal, bm.jumlah
                            FROM barang_masuk bm
                            JOIN barang b ON bm.id_barang = b.id_barang
                            $whereMasuk
                            UNION ALL
                            SELECT 'Keluar' AS jenis, b.nama_barang, bk.tanggal, bk.jumlah
                            FROM barang_keluar bk
                            JOIN barang b ON bk.id_barang = b.id_barang
                            $whereKeluar
                            ORDER BY tanggal DESC
                        ";

                        $dataMutasi = mysqli_query($koneksi, $qMutasi);

                        if ($dataMutasi && mysqli_num_rows($dataMutasi) > 0) {
                            while ($row = mysqli_fetch_assoc($dataMutasi)) {
                                $badgeClass = $row['jenis'] === 'Masuk' ? 'bg-success' : 'bg-danger';
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><span class="badge <?= $badgeClass ?>"><?= $row['jenis'] ?></span></td>
                                    <td class="text-start"><?= htmlspecialchars($row['nama_barang']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                    <td><?= number_format((int) $row['jumlah'], 0, ',', '.') ?></td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Data inventaris belum tersedia.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="report-card section-box">
            <h5 class="section-title text-center">Komposisi Mutasi</h5>

            <div class="inventory-donut">
                <div class="inventory-donut-inner">
                    <span>Masuk <?= $persenMasuk ?>%</span>
                    <span>Keluar <?= $persenKeluar ?>%</span>
                </div>
            </div>

            <div class="legend-list">
                <div class="legend-item">
                    <span class="legend-color" style="background: #0ebfff;"></span>
                    Barang Masuk
                </div>
                <div class="legend-item">
                    <span class="legend-color" style="background: #fb1b7c;"></span>
                    Barang Keluar
                </div>
            </div>

            <hr>

            <div class="row text-center g-3">
                <div class="col-6">
                    <div class="summary-label">Jenis Barang</div>
                    <div class="summary-value"><?= number_format((int) $totalJenisBarang, 0, ',', '.') ?></div>
                </div>
                <div class="col-6">
                    <div class="summary-label">Periode</div>
                    <div class="fw-bold"><?= htmlspecialchars($periodeText) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
