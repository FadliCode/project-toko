<?php
include 'config/koneksi.php';

/* ================= TAMBAH ================= */
if (isset($_POST['simpan'])) {
    $nama = $_POST['nama_sifat'];

    mysqli_query($koneksi, "
        INSERT INTO sifat_barang 
        VALUES (NULL, '$nama')
    ");

    echo "<script>
        alert('Sifat barang berhasil ditambahkan');
        location='index.php?page=sifat_barang';
    </script>";
}

/* ================= UPDATE ================= */
if (isset($_POST['update'])) {
    $id = $_POST['id_sifat'];
    $nama = $_POST['nama_sifat'];

    mysqli_query($koneksi, "
        UPDATE sifat_barang 
        SET nama_sifat='$nama'
        WHERE id_sifat='$id'
    ");

    echo "<script>
        alert('Data berhasil diupdate');
        location='index.php?page=sifat_barang';
    </script>";
}

/* ================= HAPUS ================= */
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    mysqli_query($koneksi, "
        DELETE FROM sifat_barang
        WHERE id_sifat='$id'
    ");

    echo "<script>
        alert('Data berhasil dihapus');
        location='index.php?page=sifat_barang';
    </script>";
}
?>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="row">
<div class="col-12">

<!-- ================= FORM TAMBAH ================= -->
<div class="card mb-4">
<div class="card-body">


<form method="POST">
    <div class="mb-3">
        <label class="form-label">Sifat Barang</label>
        <input type="text"
               name="nama_sifat"
               class="form-control"
               placeholder="Masukkan nama sifat"
               required>
    </div>

    <button type="submit"
            name="simpan"
            class="btn btn-primary">
        <i class="fas fa-save"></i> Simpan
    </button>
</form>

</div>
</div>

<!-- ================= TABLE ================= -->
<div class="card">
<div class="card-body">

<h4 class="card-title mb-3">Data Sifat Barang</h4>

<div class="table-responsive">
<table class="table table-hover align-middle">
    <thead class="table-light">
        <tr>
            <th width="60">No</th>
            <th>Nama Sifat</th>
            <th width="180">Aksi</th>
        </tr>
    </thead>
    <tbody>

<?php
$no = 1;
$data = mysqli_query($koneksi, "SELECT * FROM sifat_barang");

while ($d = mysqli_fetch_assoc($data)) {
?>

<tr>
    <td><?= $no++ ?></td>
    <td><?= $d['nama_sifat'] ?></td>
    <td>

        <!-- EDIT MODAL BUTTON -->
        <button class="btn btn-info btn-sm me-1"
                data-bs-toggle="modal"
                data-bs-target="#editModal<?= $d['id_sifat'] ?>">
            <i class="fas fa-edit"></i> Edit
        </button>

        <!-- HAPUS -->
        <a href="index.php?page=sifat_barang&hapus=<?= $d['id_sifat'] ?>"
           class="btn btn-danger btn-sm"
           onclick="return confirm('Yakin hapus data?')">
            <i class="fas fa-trash"></i> Hapus
        </a>

    </td>
</tr>

<!-- ================= MODAL EDIT ================= -->
<div class="modal fade"
     id="editModal<?= $d['id_sifat'] ?>"
     tabindex="-1">

<div class="modal-dialog">
<div class="modal-content">

<form method="POST">

<div class="modal-header">
    <h5 class="modal-title">Edit Sifat Barang</h5>
    <button type="button"
            class="btn-close"
            data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

    <input type="hidden"
           name="id_sifat"
           value="<?= $d['id_sifat'] ?>">

    <div class="mb-3">
        <label class="form-label">Nama Sifat</label>
        <input type="text"
               name="nama_sifat"
               class="form-control"
               value="<?= $d['nama_sifat'] ?>"
               required>
    </div>

</div>

<div class="modal-footer">
    <button type="submit"
            name="update"
            class="btn btn-primary">
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
