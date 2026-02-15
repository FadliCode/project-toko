<?php
$id = $_GET['id'];

// hapus stok dulu
mysqli_query($koneksi, "DELETE FROM stok WHERE barang_id='$id'");

// lalu hapus barang
mysqli_query($koneksi, "DELETE FROM barang WHERE id_barang='$id'");

echo "<script>
    alert('Data barang berhasil dihapus');
    location='index.php?page=data_barang';
</script>";
