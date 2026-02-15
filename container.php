<div class="container-fluid">
    <?php
    if (isset($_GET['page'])) {
        $page = $_GET['page'];

        switch ($page) {
            case 'dashboard':
                include "pages/dashboard.php";
                break;
            case 'data_barang':
                include "pages/data_barang.php";
                break;
                
            case 'kategori_barang':
                include 'pages/kategori_barang.php';
                break;
            case 'hapus_kategori':
                include 'pages/proses/hapus_kategori.php';
                break;

            case 'lokasi_barang':
                include "pages/lokasi_barang.php";
                break;
            case 'hapus_lokasi':
                include "pages/proses/hapus_lokasi.php";
                break;

            case 'sifat_barang':
                include "pages/sifat_barang.php";
                break;
            case 'hapus_sifat':
                include "pages/proses/hapus_sifat.php";
                break;

            case 'supplier':
                include "pages/supplier.php";
                break;
            case 'hapus_supplier':
                include "pages/proses/hapus_supplier.php";
                break;

            case 'barang_masuk':
                include "pages/barang_masuk.php";
                break;
            case 'barang_keluar':
                include "pages/barang_keluar.php";
                break;
            case 'laporan':
                include "pages/laporan.php";
                break;
            
            default:
                echo "<h3>Maaf. Halaman tidak di temukan !</h3>";
                break;
        }
    } else {
        include "pages/dashboard.php";
    }
    ?>
</div>