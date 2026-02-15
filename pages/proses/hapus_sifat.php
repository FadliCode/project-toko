<?php
include 'config/koneksi.php';

$id = $_GET['id_sifat'];
mysqli_query($koneksi, "DELETE FROM sifat_barang WHERE id_sifat='$id'");
?>

<script>
    alert('Sifat barang berhasil dihapus');
    window.location.href = 'index.php?page=sifat_barang';
</script>
