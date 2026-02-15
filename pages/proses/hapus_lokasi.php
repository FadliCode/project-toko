<?php
include 'config/koneksi.php';

$id = $_GET['id_lokasi'];
mysqli_query($koneksi, "DELETE FROM lokasi_barang WHERE id_lokasi='$id'");
?>

<script>
    alert('Lokasi berhasil dihapus');
    window.location.href = 'index.php?page=lokasi_barang';
</script>
