<?php
include 'config/koneksi.php';

$id = $_GET['id_kategori'];
mysqli_query($koneksi, "DELETE FROM kategori_barang WHERE id_kategori='$id'");
?>

<script>
    alert('Kategori berhasil dihapus');
    window.location.href = 'index.php?page=kategori_barang';
</script>
