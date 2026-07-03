<div id="sidebar-menu">
    <!-- Left Menu Start -->
    <ul class="metismenu list-unstyled" id="side-menu">
        <li class="menu-title">Menu</li>

        <li>
            <a href="?page=dashboard" class="waves-effect">
                <i class="ri-dashboard-line"></i><span class="badge rounded-pill bg-success float-end">3</span>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Menu untuk Admin/Staff Toko -->
        <?php if ($_SESSION['role'] !== 'supplier' && $_SESSION['role'] !== 'pelanggan') { ?>
            <li>
                <a href="?page=barang_masuk" class=" waves-effect">
                    <i class="ri-download-2-line"></i>
                    <span>Barang Masuk</span>
                </a>
            </li>

            <li>
                <a href="?page=barang_keluar" class=" waves-effect">
                    <i class="ri-upload-2-line"></i>
                    <span>Barang Keluar</span>
                </a>
            </li>

            <li>
                <a href="?page=laporan" class=" waves-effect">
                    <i class="ri-file-chart-line"></i>
                    <span>Laporan</span>
                </a>
            </li>
        <?php } ?>

        <!-- Menu untuk Supplier -->
        <?php if ($_SESSION['role'] === 'supplier') { ?>
            <li>
                <a href="?page=supplier_restok" class=" waves-effect">
                    <i class="ri-truck-line"></i>
                    <span>Masukkan Barang</span>
                </a>
            </li>
        <?php } ?>
    </ul>
</div>

<!-- Master Data - Hanya untuk Admin/Staff -->
<?php if ($_SESSION['role'] !== 'supplier' && $_SESSION['role'] !== 'pelanggan') { ?>
    <div id="sidebar-menu">
        <!-- Left Menu Start -->
        <ul class="metismenu list-unstyled" id="side-menu">
            <li class="menu-title">Master Data</li>

            <li>
                <a href="?page=data_barang" class="waves-effect">
                    <i class="ri-archive-line"></i>
                    <span>Data Barang</span>
                </a>
            </li>

            <li>
                <a href="?page=kategori_barang" class=" waves-effect">
                    <i class="ri-price-tag-3-line"></i>
                    <span>Kategori Barang</span>
                </a>
            </li>

            <li>
                <a href="?page=lokasi_barang" class=" waves-effect">
                    <i class="ri-map-pin-line"></i>
                    <span>Lokasi Barang</span>
                </a>
            </li>

            <li>
                <a href="?page=sifat_barang" class=" waves-effect">
                    <i class="ri-settings-3-line"></i>
                    <span>Sifat Barang</span>
                </a>
            </li>

            <li>
                <a href="?page=supplier" class=" waves-effect">
                    <i class="ri-truck-line"></i>
                    <span>Supplier</span>
                </a>
            </li>
        </ul>
        <!-- Left Menu End -->
    </div>
<?php } ?>