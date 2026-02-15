<?php
include 'config/koneksi.php';

/* ================= SIMPAN ================= */
if (isset($_POST['simpan'])) {
    $nama = $_POST['nama_lokasi'];
    mysqli_query($koneksi, "INSERT INTO lokasi_barang VALUES (NULL, '$nama')");
    echo "<script>location='index.php?page=lokasi_barang'</script>";
}

/* ================= UPDATE ================= */
if (isset($_POST['update'])) {
    $id = $_POST['id_lokasi'];
    $nama = $_POST['nama_lokasi'];

    mysqli_query($koneksi, "
        UPDATE lokasi_barang 
        SET nama_lokasi='$nama'
        WHERE id_lokasi='$id'
    ");

    echo "<script>
        alert('Data berhasil diupdate');
        location='index.php?page=lokasi_barang';
    </script>";
}

/* ================= HAPUS ================= */
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    mysqli_query($koneksi, "
        DELETE FROM lokasi_barang 
        WHERE id_lokasi='$id'
    ");

    echo "<script>
        alert('Data berhasil dihapus');
        location='index.php?page=lokasi_barang';
    </script>";
}

/* ================= AMBIL DATA EDIT ================= */
$edit = null;

if (isset($_GET['edit'])) {
    $id_edit = $_GET['edit'];

    $ambil = mysqli_query($koneksi, "
        SELECT * FROM lokasi_barang 
        WHERE id_lokasi='$id_edit'
    ");

    $edit = mysqli_fetch_assoc($ambil);
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<div class="card mb-4">
    <div class="card-body">

        <h4 class="card-title mb-3">Lokasi Barang</h4>

        <form method="POST">
            <div class="mb-3">
                <input type="text" name="nama_lokasi" class="form-control" placeholder="Nama Lokasi" required>
            </div>

            <button type="submit" name="simpan" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
            </button>
        </form>

    </div>
</div>

<div class="card">
    <div class="card-body">

        <h4 class="card-title mb-3">Data Lokasi</h4>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="60">No</th>
                        <th>Nama Lokasi</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $no = 1;
                    $data = mysqli_query($koneksi, "SELECT * FROM lokasi_barang");

                    while ($d = mysqli_fetch_assoc($data)) {
                        ?>

                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $d['nama_lokasi'] ?></td>
                            <td>

                                <!-- EDIT BUTTON -->
                                <a href="index.php?page=lokasi_barang&edit=<?= $d['id_lokasi'] ?>"
                                    class="btn btn-info btn-sm me-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                <!-- HAPUS -->
                                <a href="index.php?page=lokasi_barang&hapus=<?= $d['id_lokasi'] ?>"
                                    class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </a>

                            </td>
                        </tr>

                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>


    <!-- ================= MODAL EDIT ================= -->
    <div class="modal fade <?= isset($_GET['edit']) ? 'show' : '' ?>" id="modalEdit"
        style="<?= isset($_GET['edit']) ? 'display:block;' : '' ?>">

        <div class="modal-dialog">
            <div class="modal-content">

                <form method="POST">

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Lokasi</h5>
                        <a href="index.php?page=lokasi_barang" class="btn-close"></a>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="id_lokasi" value="<?= $edit['id_lokasi'] ?? '' ?>">

                        <input type="text" name="nama_lokasi" class="form-control"
                            value="<?= $edit['nama_lokasi'] ?? '' ?>" required>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" name="update" class="btn btn-primary">
                            Update
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>