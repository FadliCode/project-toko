<?php
include 'config/koneksi.php';

// Total Barang
$total_barang = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT COUNT(*) as total FROM barang
"))['total'];

// Total Stok
$total_stok = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT SUM(banyak_stok) as total FROM stok
"))['total'];

// Total Barang Masuk
$total_masuk = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT SUM(jumlah) as total FROM barang_masuk
"))['total'];

// Total Barang Keluar
$total_keluar = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT SUM(jumlah) as total FROM barang_keluar
"))['total'];
?>

<h3 class="mb-4">Dashboard</h3>

<div class="row">

    <!-- Total Barang -->
    <div class="row">
        <div class="col-sm-6 col-lg-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="card-title text-muted">Total Barang</h4>
                     <h3 class="default">
                        <?= $total_barang ?? 0 ?>
                </div>
            </div>
        </div>

        <!-- Total Stok -->
        <div class="col-sm-6 col-lg-3">
            <div class="card shadow border-0">
                <div class="card-body text-center">
                    <h4 class="card-title text-muted">Total Stok</h4>
                    <h3 class="text-success">
                        <?= $total_stok ?? 0 ?>
                    </h3>
                </div>
            </div>
        </div>

        <!-- Barang Masuk -->
        <div class="col-md-3">
            <div class="card shadow border-0">
                <div class="card-body text-center">
                    <h4 class="card-title text-muted">Total Barang Masuk</h4>
                    <h3 class="text-info">
                        <?= $total_masuk ?? 0 ?>
                    </h3>
                </div>
            </div>
        </div>

        <!-- Barang Keluar -->
        <div class="col-md-3">
            <div class="card shadow border-0">
                <div class="card-body text-center">
                    <h4 class="card-title text-muted">Total Barang Keluar</h4>
                    <h3 class="text-danger">
                        <?= $total_keluar ?? 0 ?>
                    </h3>
                </div>
            </div>
        </div>

    </div>