<div class="container-fluid">
    <?php
    if (isset($_GET['page'])) {
        $page = $_GET['page'];

        // Define allowed pages for each role
        $public_pages = array('dashboard');
        $admin_pages = array('data_barang', 'kategori_barang', 'hapus_kategori', 'lokasi_barang', 'hapus_lokasi', 'sifat_barang', 'hapus_sifat', 'supplier', 'hapus_supplier', 'barang_masuk', 'barang_keluar', 'laporan');
        $supplier_pages = array('supplier_restok');

        // Check if user has access to this page
        $has_access = false;

        if (in_array($page, $public_pages)) {
            $has_access = true;
        } elseif ($_SESSION['role'] !== 'supplier' && $_SESSION['role'] !== 'pelanggan' && in_array($page, $admin_pages)) {
            $has_access = true;
        } elseif ($_SESSION['role'] === 'supplier' && in_array($page, $supplier_pages)) {
            $has_access = true;
        }

        if (!$has_access) {
            echo "<div class='alert alert-danger'><i class='ri-error-warning-line'></i> Anda tidak memiliki akses ke halaman ini!</div>";
            $page = 'dashboard';
        }


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

            case 'supplier_restok':
                include "pages/supplier_restok.php";
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