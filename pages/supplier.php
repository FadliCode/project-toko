<?php
include 'config/koneksi.php';

/* ================= TAMBAH ================= */
if (isset($_POST['simpan'])) {
    $nama = $_POST['nama_supplier'];
    $hp = $_POST['no_hp_supp'];
    $alamat = $_POST['alamat_supp'];

    mysqli_query($koneksi, "
        INSERT INTO supplier 
        VALUES (NULL, '$nama', '$hp', '$alamat')
    ");

    echo "<script>
        alert('Supplier berhasil ditambahkan');
        location='index.php?page=supplier';
    </script>";
}

/* ================= UPDATE ================= */
if (isset($_POST['update'])) {
    $id = $_POST['id_supplier'];
    $nama = $_POST['nama_supplier'];
    $hp = $_POST['no_hp_supp'];
    $alamat = $_POST['alamat_supp'];

    mysqli_query($koneksi, "
        UPDATE supplier SET
        nama_supplier='$nama',
        no_hp_supp='$hp',
        alamat_supp='$alamat'
        WHERE id_supplier='$id'
    ");

    echo "<script>
        alert('Supplier berhasil diupdate');
        location='index.php?page=supplier';
    </script>";
}

/* ================= HAPUS ================= */
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    mysqli_query($koneksi, "
        DELETE FROM supplier
        WHERE id_supplier='$id'
    ");

    echo "<script>
        alert('Supplier berhasil dihapus');
        location='index.php?page=supplier';
    </script>";
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="row">
    <div class="col-12">

        <!-- ================= FORM TAMBAH ================= -->
        <div class="card mb-4">
            <div class="card-body">

                <h4 class="card-title mb-3">Tambah Supplier</h4>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Supplier</label>
                        <input type="text" name="nama_supplier" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">No HP Supplier</label>
                        <input type="text" name="no_hp_supp" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat Supplier</label>
                        <textarea name="alamat_supp" class="form-control" rows="3"></textarea>
                    </div>

                    <button type="submit" name="simpan" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </form>

            </div>
        </div>

        <!-- ================= TABLE ================= -->
        <div class="card">
            <div class="card-body">

                <h4 class="card-title mb-3">Data Supplier</h4>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="60">No</th>
                                <th>Nama Supplier</th>
                                <th>No HP</th>
                                <th>Alamat</th>
                                <th width="200">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $no = 1;
                            $data = mysqli_query($koneksi, "SELECT * FROM supplier");

                            while ($d = mysqli_fetch_assoc($data)) {
                                ?>

                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $d['nama_supplier'] ?></td>
                                    <td><?= $d['no_hp_supp'] ?></td>
                                    <td><?= $d['alamat_supp'] ?></td>
                                    <td>

                                        <!-- EDIT BUTTON -->
                                        <button class="btn btn-info btn-sm me-1" data-bs-toggle="modal"
                                            data-bs-target="#editModal<?= $d['id_supplier'] ?>">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>

                                        <!-- HAPUS -->
                                        <a href="index.php?page=supplier&hapus=<?= $d['id_supplier'] ?>"
                                            class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>

                                    </td>
                                </tr>

                                <!-- ================= MODAL EDIT ================= -->
                                <div class="modal fade" id="editModal<?= $d['id_supplier'] ?>" tabindex="-1">

                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                            <form method="POST">

                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Supplier</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">

                                                    <input type="hidden" name="id_supplier"
                                                        value="<?= $d['id_supplier'] ?>">

                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Supplier</label>
                                                        <input type="text" name="nama_supplier" class="form-control"
                                                            value="<?= $d['nama_supplier'] ?>" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">No HP Supplier</label>
                                                        <input type="text" name="no_hp_supp" class="form-control"
                                                            value="<?= $d['no_hp_supp'] ?>">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Alamat Supplier</label>
                                                        <textarea name="alamat_supp" class="form-control"
                                                            rows="3"><?= $d['alamat_supp'] ?></textarea>
                                                    </div>

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

                            <?php } ?>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</div>