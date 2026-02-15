<?php
include 'config/koneksi.php';

$tgl1 = $_GET['tgl1'] ?? '';
$tgl2 = $_GET['tgl2'] ?? '';
?>

<!-- Page Title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="mb-0">Laporan Inventaris</h4>
        </div>
    </div>
</div>

<!-- FILTER TANGGAL -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <input type="hidden" name="page" value="laporan">

            <div class="col-md-4">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="tgl1" value="<?= $tgl1 ?>" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="tgl2" value="<?= $tgl2 ?>" class="form-control">
            </div>

            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">
                    Filter
                </button>
                <a href="index.php?page=laporan" class="btn btn-secondary">
                    Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- ================= BARANG MASUK ================= -->
<div class="card mb-4">
    <div class="card-body">

        <h5 class="card-title mb-3">⬇ Barang Masuk</h5>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Barang</th>
                        <th>Tanggal</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $qMasuk = "
                            SELECT bm.*, b.nama_barang 
                            FROM barang_masuk bm
                            JOIN barang b ON bm.id_barang = b.id_barang
                        ";

                    if ($tgl1 && $tgl2) {
                        $qMasuk .= " WHERE bm.tanggal BETWEEN '$tgl1' AND '$tgl2'";
                    }

                    $qMasuk .= " ORDER BY bm.tanggal DESC";

                    $dataMasuk = mysqli_query($koneksi, $qMasuk);

                    while ($m = mysqli_fetch_assoc($dataMasuk)) {
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $m['nama_barang'] ?></td>
                            <td><?= $m['tanggal'] ?></td>
                            <td>
                                <span class="badge bg-success">
                                    <?= $m['jumlah'] ?>
                                </span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- ================= BARANG KELUAR ================= -->
<div class="card">
    <div class="card-body">

        <h5 class="card-title mb-3">⬆ Barang Keluar</h5>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Barang</th>
                        <th>Tanggal</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $qKeluar = "
                            SELECT bk.*, b.nama_barang 
                            FROM barang_keluar bk
                            JOIN barang b ON bk.id_barang = b.id_barang
                        ";

                    if ($tgl1 && $tgl2) {
                        $qKeluar .= " WHERE bk.tanggal BETWEEN '$tgl1' AND '$tgl2'";
                    }

                    $qKeluar .= " ORDER BY bk.tanggal DESC";

                    $dataKeluar = mysqli_query($koneksi, $qKeluar);

                    while ($k = mysqli_fetch_assoc($dataKeluar)) {
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $k['nama_barang'] ?></td>
                            <td><?= $k['tanggal'] ?></td>
                            <td>
                                <span class="badge bg-danger">
                                    <?= $k['jumlah'] ?>
                                </span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>
</div>