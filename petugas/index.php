<?php
include "../koneksi.php";
session_start();
if (!isset($_SESSION['username'])) {
    header("location:cd ../../..");
}
$level = $_SESSION['level'];
?>
<!DOCTYPE html>
<html>

<head>
    <title>Aplikasi Pembayaran SPP</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/dashboard/">
    <link rel="stylesheet" href="../style/css/bootstrap.min.css">
    <link href="../style/css/dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/css/adminlte.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

</head>

<body>
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 px-3 fs-6" href="index.php">Aplikasi Pembayaran SPP</a>
        <div class="navbar-nav">
            <div class="nav-item text-nowrap">
                <span class="nav-link px-3"><?= $_SESSION['nama_petugas'] ?> | <a class="text-light" href="logout.php" onClick="return confirm('Anda yakin akan mau Log out??')"> Sign out</a></span>
            </div>
        </div>
    </header>
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3 sidebar-sticky">
                    <ul class="nav flex-column" aria-current="page">
                        <li>
                            <b class="nav-link">
                                <h5> Option for <?php echo $_SESSION['level']; ?> </h5>
                            </b>
                        </li>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="index.php">
                                    <span data-feather="home" class="align-text-bottom"></span>
                                    H O M E
                                </a>
                            </li>
                            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-2 mb-1 text-muted text-uppercase">
                                <span>Manajemen</span>
                            </h6>
                            <?php
                            if ($level == "admin") { ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="index.php?url=td_spp">
                                        <span data-feather="file" class="align-text-bottom"></span>
                                        Manajemen SPP
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="index.php?url=td_kelas">
                                        <span data-feather="file" class="align-text-bottom"></span>
                                        Manajemen Kelas
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="index.php?url=td_siswa">
                                        <span data-feather="users" class="align-text-bottom"></span>
                                        Manajemen Siswa
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="index.php?url=td_petugas">
                                        <span data-feather="users" class="align-text-bottom"></span>
                                        Manajemen Petugas
                                    </a>
                                </li>
                        </ul>
                    <?php } ?>
                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
                        <span>Pembayaran</span>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?url=td_pembayaran">
                                <span data-feather="file-text" class="align-text-bottom"></span>
                                Pembayaran
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?url=laporan">
                                <span data-feather="file-text" class="align-text-bottom"></span>
                                Laporan
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-10 ms-sm-auto px-md-2">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-sm btn-outline-secondary ">
                            <span data-feather="calendar" class="align-text-bottom"></span>
                            <?= date('Y-M-D') ?>
                        </button>
                    </div>
                </div>
                <div class="container mt-4">
                    <div class="container-body">
                        <?php
                        $file = @$_GET['url'];
                        if (empty($file)) { ?>

                            <div class="row">
                                <?php
                                $sql1 = mysqli_query($koneksi, "SELECT * FROM spp");
                                $jmlspp = mysqli_num_rows($sql1);

                                $sql2 = mysqli_query($koneksi, "SELECT * FROM siswa");
                                $jmlsiswa = mysqli_num_rows($sql2);

                                $sql3 = mysqli_query($koneksi, "SELECT * FROM kelas");
                                $jmlkelas = mysqli_num_rows($sql3);

                                $sql4 = mysqli_query($koneksi, "SELECT * FROM petugas");
                                $jmlpetugas = mysqli_num_rows($sql4);

                                ?>
                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-blue">
                                        <div class="inner">
                                            <h3><?= $jmlspp; ?></h3>

                                            <p>Data SPP</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-stats-bars"></i>
                                        </div>
                                        <a href="?url=td_spp" class="small-box-footer">More info</a>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-yellow">
                                        <div class="inner">
                                            <h3><?= $jmlkelas; ?></h3>
                                            <p>Data Kelas</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-stats-bars"></i>
                                        </div>
                                        <a href="?url=td_kelas" class="small-box-footer">More info</a>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-pink">
                                        <div class="inner">
                                            <h3><?= $jmlsiswa ?></h3>

                                            <p>Data Siswa</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-person"></i>
                                        </div>
                                        <a href="?url=td_siswa" class="small-box-footer">More info</a>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-red">
                                        <div class="inner">
                                            <h3><?= $jmlpetugas ?></h3>
                                            <p>Data Petugas</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-person"></i>
                                        </div>
                                        <a href="?url=td_petugas" class="small-box-footer">More info</a>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 p-2 p-md-2 mb-4 rounded text-bg-dark">
                                <div class="col-md-6 px-2">
                                    <h1 class="display-4 fst-italic">Aplikasi Pembayaran SPP</h1>
                                    <p class="lead my-3">Adalah suatu sistem yang dapat anda gunakan untuk melakukan transaksi pembayaran SPP(Surat Pembinaan Pendidikan).</p>
                                </div>
                            </div>

                        <?php } else {
                            include  $file . '.php';
                        }

                        ?>
                    </div>
                </div>
            </main>
            <script src="../style/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
            <script src="../style/js/dashboard.js"></script>
            <script src="../style/js/adminlte.js"></script>

</body>

</html>