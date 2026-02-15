<?php
include 'config/koneksi.php';

/* ================= SIMPAN BARANG KELUAR ================= */
if (isset($_POST['simpan'])) {

    $id_barang = $_POST['id_barang'];
    $tanggal   = $_POST['tanggal'];
    $jumlah    = $_POST['jumlah'];

    // ambil stok
    $cek = mysqli_query($koneksi, "
        SELECT banyak_stok 
        FROM stok 
        WHERE barang_id='$id_barang'
    ");

    $data = mysqli_fetch_assoc($cek);
    $stok = $data['banyak_stok'] ?? 0;

    if ($stok < $jumlah) {

        echo "<script>
                alert('Stok tidak mencukupi!');
                location='index.php?page=barang_keluar';
              </script>";

    } else {

        mysqli_query($koneksi, "
            INSERT INTO barang_keluar (id_barang, tanggal, jumlah)
            VALUES ('$id_barang', '$tanggal', '$jumlah')
        ");

        mysqli_query($koneksi, "
            UPDATE stok 
            SET banyak_stok = banyak_stok - $jumlah
            WHERE barang_id='$id_barang'
        ");

        echo "<script>
                alert('Barang keluar berhasil & stok diperbarui');
                location='index.php?page=barang_keluar';
              </script>";
    }
}
?>
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Barang Keluar</h4>
            </div>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">

                    <h5 class="card-title mb-3">Tambah Barang Keluar</h5>

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">Nama Barang</label>
                            <select name="id_barang" class="form-select" required>
                                <option value="">-- Pilih Barang --</option>

                                <?php
                                $barang = mysqli_query($koneksi, "
                                    SELECT b.id_barang, b.nama_barang, st.banyak_stok
                                    FROM barang b
                                    LEFT JOIN stok st ON st.barang_id = b.id_barang
                                ");

                                while ($b = mysqli_fetch_assoc($barang)) {
                                ?>
                                    <option value="<?= $b['id_barang'] ?>">
                                        <?= $b['nama_barang'] ?> 
                                        (Stok: <?= $b['banyak_stok'] ?? 0 ?>)
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Keluar</label>
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jumlah Keluar</label>
                            <input type="number" name="jumlah" class="form-control" min="1" required>
                        </div>

                        <button type="submit" name="simpan" class="btn btn-danger">
                            Simpan
                        </button>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- TABLE CARD -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h5 class="card-title mb-3">Riwayat Barang Keluar</h5>

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

                                $data = mysqli_query($koneksi, "
                                    SELECT bk.*, b.nama_barang 
                                    FROM barang_keluar bk
                                    JOIN barang b ON bk.id_barang = b.id_barang
                                    ORDER BY bk.id_keluar DESC
                                ");

                                while ($d = mysqli_fetch_assoc($data)) {
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $d['nama_barang'] ?></td>
                                        <td><?= $d['tanggal'] ?></td>
                                        <td>
                                            <span class="badge bg-danger">
                                                <?= $d['jumlah'] ?>
                                            </span>
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

</div>
</div>
