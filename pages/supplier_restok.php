<?php
include 'config/koneksi.php';

// Validasi: hanya supplier yang bisa akses halaman ini
if ($_SESSION['role'] !== 'supplier') {
    header("Location: index.php?page=dashboard");
    exit;
}

// Handle form submission untuk menambah barang masuk
if (isset($_POST['tambah_barang'])) {
    $id_barang = $_POST['id_barang'];
    $tanggal = $_POST['tanggal'];
    $jumlah = $_POST['jumlah'];
    
    // Validasi input
    if (empty($id_barang) || empty($tanggal) || empty($jumlah) || $jumlah <= 0) {
        $error = "Semua field harus diisi dengan benar!";
    } else {
        // Insert ke tabel barang_masuk
        $query = mysqli_query($koneksi, "
            INSERT INTO barang_masuk (id_barang, tanggal, jumlah)
            VALUES ('$id_barang', '$tanggal', '$jumlah')
        ");
        
        if ($query) {
            $success = "Barang berhasil ditambahkan!";
            
            // Update stok barang
            $check_stok = mysqli_query($koneksi, "SELECT * FROM stok WHERE barang_id = '$id_barang'");
            if (mysqli_num_rows($check_stok) > 0) {
                mysqli_query($koneksi, "UPDATE stok SET banyak_stok = banyak_stok + $jumlah WHERE barang_id = '$id_barang'");
            } else {
                mysqli_query($koneksi, "INSERT INTO stok (barang_id, banyak_stok) VALUES ('$id_barang', '$jumlah')");
            }
        } else {
            $error = "Gagal menambahkan barang: " . mysqli_error($koneksi);
        }
    }
}

// Ambil data barang untuk dropdown
$barang_list = mysqli_query($koneksi, "
    SELECT b.id_barang, b.nama_barang, k.nama_kategori, COALESCE(s.banyak_stok, 0) as stok
    FROM barang b
    JOIN kategori_barang k ON b.kategori_barang_id = k.id_kategori
    LEFT JOIN stok s ON s.barang_id = b.id_barang
    ORDER BY b.nama_barang ASC
");

// Ambil riwayat pengiriman barang dari supplier
$riwayat_barang = mysqli_query($koneksi, "
    SELECT bm.*, b.nama_barang, k.nama_kategori
    FROM barang_masuk bm
    JOIN barang b ON bm.id_barang = b.id_barang
    JOIN kategori_barang k ON b.kategori_barang_id = k.id_kategori
    ORDER BY bm.tanggal DESC
    LIMIT 10
");
?>

<h3 class="dashboard-title">Masukkan Barang</h3>

<?php if (isset($success)) { ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ri-check-double-line"></i> <?= $success ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php } ?>

<?php if (isset($error)) { ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="ri-error-warning-line"></i> <?= $error ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php } ?>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="ri-file-add-line"></i> Tambah Barang
                </h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label" for="id_barang">Nama Barang <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_barang" name="id_barang" required onchange="updateStok()">
                            <option value="">-- Pilih Barang --</option>
                            <?php while ($barang = mysqli_fetch_assoc($barang_list)) { ?>
                                <option value="<?= $barang['id_barang'] ?>" data-stok="<?= $barang['stok'] ?>">
                                    <?= htmlspecialchars($barang['nama_barang']) ?> 
                                    (<?= htmlspecialchars($barang['nama_kategori']) ?>)
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok Saat Ini</label>
                        <input type="text" class="form-control" id="stok_saat_ini" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="tanggal">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="jumlah">Jumlah <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" 
                               min="1" required placeholder="Masukkan jumlah barang">
                    </div>

                    <button type="submit" name="tambah_barang" class="btn btn-primary w-100">
                        <i class="ri-send-plane-line"></i> Kirim Barang
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="ri-history-line"></i> Riwayat Pengiriman (10 Terakhir)
                </h5>
            </div>
            <div class="card-body">
                <?php if (mysqli_num_rows($riwayat_barang) > 0) { ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Barang</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($riwayat = mysqli_fetch_assoc($riwayat_barang)) { ?>
                                    <tr>
                                        <td>
                                            <small class="d-block text-dark">
                                                <strong><?= htmlspecialchars($riwayat['nama_barang']) ?></strong>
                                            </small>
                                            <small class="text-muted">
                                                <?= htmlspecialchars($riwayat['nama_kategori']) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">
                                                <?= number_format($riwayat['jumlah'], 0, ',', '.') ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?= date('d/m/Y', strtotime($riwayat['tanggal'])) ?>
                                            </small>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                    <div class="text-center text-muted py-4">
                        <i class="ri-inbox-line" style="font-size: 40px;"></i>
                        <p class="mt-2">Belum ada riwayat pengiriman</p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
function updateStok() {
    const select = document.getElementById('id_barang');
    const selectedOption = select.options[select.selectedIndex];
    const stok = selectedOption.getAttribute('data-stok');
    document.getElementById('stok_saat_ini').value = stok ? parseInt(stok).toLocaleString('id-ID') : '0';
}
</script>
