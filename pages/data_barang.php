<?php
include 'config/koneksi.php';

/* ================= HAPUS ================= */
if (isset($_GET['hapus'])) {

    $id = $_GET['hapus'];

    mysqli_query($koneksi, "DELETE FROM stok WHERE barang_id='$id'");
    mysqli_query($koneksi, "DELETE FROM barang WHERE id_barang='$id'");

    echo "<script>
        alert('Data berhasil dihapus');
        location='index.php?page=data_barang';
    </script>";
}

/* ================= SIMPAN ================= */
if (isset($_POST['simpan'])) {

    $nama = $_POST['nama_barang'];
    $kategori = $_POST['id_kategori'];
    $lokasi = $_POST['id_lokasi'];
    $sifat = $_POST['id_sifat'];
    $supplier = $_POST['id_supplier'];
    $stok = $_POST['stok'];

    mysqli_query($koneksi, "
        INSERT INTO barang 
        (nama_barang, kategori_barang_id, lokasi_barang_id, sifat_barang_id, supplier_id)
        VALUES 
        ('$nama','$kategori','$lokasi','$sifat','$supplier')
    ");

    $barang_id = mysqli_insert_id($koneksi);

    mysqli_query($koneksi, "
        INSERT INTO stok (barang_id, banyak_stok)
        VALUES ('$barang_id','$stok')
    ");

    echo "<script>
        location='index.php?page=data_barang';
    </script>";
}

/* ================= UPDATE ================= */
if (isset($_POST['update'])) {

    $id = $_POST['id_barang'];
    $nama = $_POST['nama_barang'];
    $kategori = $_POST['id_kategori'];
    $lokasi = $_POST['id_lokasi'];
    $sifat = $_POST['id_sifat'];
    $supplier = $_POST['id_supplier'];
    $stok = $_POST['stok'];

    mysqli_query($koneksi, "
        UPDATE barang SET
            nama_barang='$nama',
            kategori_barang_id='$kategori',
            lokasi_barang_id='$lokasi',
            sifat_barang_id='$sifat',
            supplier_id='$supplier'
        WHERE id_barang='$id'
    ");

    mysqli_query($koneksi, "
        UPDATE stok SET banyak_stok='$stok'
        WHERE barang_id='$id'
    ");

    echo "<script>
        alert('Data berhasil diupdate');
        location='index.php?page=data_barang';
    </script>";
}

/* ================= AMBIL DATA EDIT ================= */
$edit_data = null;

if (isset($_GET['edit'])) {

    $id_edit = $_GET['edit'];

    $ambil = mysqli_query($koneksi, "
        SELECT b.*, st.banyak_stok
        FROM barang b
        LEFT JOIN stok st ON st.barang_id = b.id_barang
        WHERE b.id_barang='$id_edit'
    ");

    $edit_data = mysqli_fetch_assoc($ambil);
}
?>

<!-- FontAwesome (WAJIB kalau belum ada di header) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Data Barang</h4>

                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalBarang">
                        + Tambah Barang
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Lokasi</th>
                                <th>Sifat</th>
                                <th>Stok</th>
                                <th>Supplier</th>
                                <th width="170">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $no = 1;

                            $data = mysqli_query($koneksi, "
    SELECT 
        b.id_barang,
        b.nama_barang,
        k.nama_kategori,
        l.nama_lokasi,
        s.nama_sifat,
        sp.nama_supplier,
        st.banyak_stok
    FROM barang b
    JOIN kategori_barang k ON b.kategori_barang_id = k.id_kategori
    JOIN lokasi_barang l ON b.lokasi_barang_id = l.id_lokasi
    JOIN sifat_barang s ON b.sifat_barang_id = s.id_sifat
    JOIN supplier sp ON b.supplier_id = sp.id_supplier
    LEFT JOIN stok st ON st.barang_id = b.id_barang
");

                            while ($d = mysqli_fetch_assoc($data)) {
                                ?>

                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $d['nama_barang'] ?></td>
                                    <td><?= $d['nama_kategori'] ?></td>
                                    <td><?= $d['nama_lokasi'] ?></td>
                                    <td><?= $d['nama_sifat'] ?></td>

                                    <td>
                                        <?php
                                        $stok = $d['banyak_stok'] ?? 0;
                                        if ($stok <= 10) {
                                            echo "<span class='badge bg-danger'>$stok</span>";
                                        } else {
                                            echo "<span class='badge bg-success'>$stok</span>";
                                        }
                                        ?>
                                    </td>

                                    <td><?= $d['nama_supplier'] ?></td>

                                    <td>
                                        <a href="index.php?page=data_barang&edit=<?= $d['id_barang'] ?>"
                                            class="btn btn-info btn-sm me-1">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>

                                        <a href="index.php?page=data_barang&hapus=<?= $d['id_barang'] ?>"
                                            class="btn btn-danger btn-sm" onclick="return confirm('Hapus data?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
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



<!-- ================= MODAL ================= -->
<div class="modal fade <?= isset($_GET['edit']) ? 'show' : '' ?>" id="modalBarang"
    style="<?= isset($_GET['edit']) ? 'display:block;' : '' ?>">

    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form method="POST">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <?= isset($edit_data) ? 'Edit Barang' : 'Tambah Barang' ?>
                    </h5>
                    <a href="index.php?page=data_barang" class="btn-close"></a>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="id_barang" value="<?= $edit_data['id_barang'] ?? '' ?>">

                    <input type="text" name="nama_barang" class="form-control mb-3" placeholder="Nama Barang"
                        value="<?= $edit_data['nama_barang'] ?? '' ?>" required>

                    <!-- KATEGORI -->
                    <select name="id_kategori" class="form-control mb-3" required>
                        <option value="">-- Kategori --</option>
                        <?php
                        $kat = mysqli_query($koneksi, "SELECT * FROM kategori_barang");
                        while ($k = mysqli_fetch_assoc($kat)) {
                            $selected = ($edit_data && $edit_data['kategori_barang_id'] == $k['id_kategori']) ? 'selected' : '';
                            echo "<option value='$k[id_kategori]' $selected>$k[nama_kategori]</option>";
                        }
                        ?>
                    </select>

                    <!-- LOKASI -->
                    <select name="id_lokasi" class="form-control mb-3" required>
                        <option value="">-- Lokasi --</option>
                        <?php
                        $lok = mysqli_query($koneksi, "SELECT * FROM lokasi_barang");
                        while ($l = mysqli_fetch_assoc($lok)) {
                            $selected = ($edit_data && $edit_data['lokasi_barang_id'] == $l['id_lokasi']) ? 'selected' : '';
                            echo "<option value='$l[id_lokasi]' $selected>$l[nama_lokasi]</option>";
                        }
                        ?>
                    </select>

                    <!-- SIFAT -->
                    <select name="id_sifat" class="form-control mb-3" required>
                        <option value="">-- Sifat --</option>
                        <?php
                        $sf = mysqli_query($koneksi, "SELECT * FROM sifat_barang");
                        while ($s = mysqli_fetch_assoc($sf)) {
                            $selected = ($edit_data && $edit_data['sifat_barang_id'] == $s['id_sifat']) ? 'selected' : '';
                            echo "<option value='$s[id_sifat]' $selected>$s[nama_sifat]</option>";
                        }
                        ?>
                    </select>

                    <!-- SUPPLIER -->
                    <select name="id_supplier" class="form-control mb-3" required>
                        <option value="">-- Supplier --</option>
                        <?php
                        $sp = mysqli_query($koneksi, "SELECT * FROM supplier");
                        while ($sup = mysqli_fetch_assoc($sp)) {
                            $selected = ($edit_data && $edit_data['supplier_id'] == $sup['id_supplier']) ? 'selected' : '';
                            echo "<option value='$sup[id_supplier]' $selected>$sup[nama_supplier]</option>";
                        }
                        ?>
                    </select>

                    <input type="number" name="stok" class="form-control" value="<?= $edit_data['banyak_stok'] ?? 0 ?>"
                        min="0">

                </div>

                <div class="modal-footer">
                    <button type="submit" name="<?= isset($edit_data) ? 'update' : 'simpan' ?>" class="btn btn-primary">
                        <?= isset($edit_data) ? 'Update' : 'Simpan' ?>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>