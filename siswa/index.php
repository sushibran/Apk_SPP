<?php
include "../koneksi.php";
session_start();
if (!isset($_SESSION['nisn'])) {
    header("location:../index.php");
}
$nisn = $_SESSION['nisn'];
?>

<head>
    <title>SPP Siswa</title>
    <link rel="stylesheet" href="../style/css/bootstrap.min.css">
</head>

<body>
    <header class="p-3 text-bg-dark">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <h2 class="text-light">History Pembayaran</h2>
                </ul>
                <div class="text-end">
                    <a href="logout.php" onClick="return confirm('Anda yakin akan mau Log out??')" class="btn btn-outline-light me-2">Logout</a>
                </div>
            </div>
        </div>
    </header>
    <?php
    include '../koneksi.php';
    $no = 1;
    $sql = "SELECT * FROM pembayaran,siswa,kelas,spp,petugas WHERE pembayaran.nisn=siswa.nisn AND siswa.id_kelas=kelas.id_kelas
            AND pembayaran.id_spp=spp.id_spp AND pembayaran.id_petugas=petugas.id_petugas AND pembayaran.nisn='$nisn' 
            ORDER BY tgl_bayar ASC";
    $query = mysqli_query($koneksi, $sql);
    $cek = mysqli_num_rows($query);
    
    if ($cek > 0) {
        $data = mysqli_fetch_array($query);
        $data_pembayaran = mysqli_query($koneksi, "SELECT SUM(jumlah_bayar) as jumlah_bayar 
             FROM pembayaran WHERE nisn='$nisn'");
        $data_pembayaran = mysqli_fetch_array($data_pembayaran);
        $sudah_bayar = $data_pembayaran['jumlah_bayar'];
        $kekurangan = $data['nominal'] - $data_pembayaran['jumlah_bayar'];
    }
    if ($cek > 0) {
        mysqli_data_seek($query, 0);
    ?>
        <div class="container mt-5">
            <div class="text-end mb-2">
                <a href="cetak_laporan.php?nisn=<?= $data['nisn'] ?>" class="btn btn-success">Cetak Laporan</a>
            </div>
            
            <!-- API Status Alert -->
            <div id="apiAlert" class="alert alert-info" style="display: none;">
                <strong>API Status:</strong> <span id="apiStatus"></span>
            </div>
            
            <!-- API Button -->
            <div class="mb-3">
                <button type="button" class="btn btn-info btn-sm" onclick="loadDataFromAPI()">
                    <i class="fas fa-sync"></i> Load via API
                </button>
            </div>
            
            <table class="table table-striped table-bordered">
                <tr class="fw-bold">
                    <th>No</th>
                    <th>NISN</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Tahun SPP</th>
                    <th>Nominal Dibayar</th>
                    <th>Sudah Dibayar</th>
                    <th>Tanggal Bayar</th>
                    <th>Bulan Dibayar</th>
                    <th>Petugas</th>
                </tr>

                <?php foreach ($query as $data) {

                ?>
                    <tr>
                        <td> <?= $no++; ?> </td>
                        <td> <?= $data['nisn'] ?> </td>
                        <td><?= $data['nama'] ?></td>
                        <td><?= $data['nama_kelas'] ?></td>
                        <td><?= $data['tahun'] ?></td>
                        <td><?= number_format($data['nominal'], 2, ',', '.'); ?></td>
                        <td><?= number_format($data['jumlah_bayar'], 2, ',', '.'); ?></td>
                        <td><?= $data['tgl_bayar'] ?></td>
                        <td><?= $data['bulan_dibayar'] ?></td>
                        <td><?= $data['nama_petugas'] ?></td>
                    </tr>
                <?php
                } ?>
            </table>
            <?php
            if ($kekurangan == 0) {
                echo "SPP Anda Sudah Lunas!";
                echo "";
            } else { ?>
                <ul>
                    <li>Total : <?= number_format($sudah_bayar, 2, ',', '.'); ?></li>
                    <li>Kekurangan : <?= number_format($kekurangan, 2, ',', '.'); ?></li>
                </ul>
            <?php } ?>
        <?php } else {
        echo "
        <div class='container mt-5'>
        <div class='text-secondary'>
        <div class='text-center'>
                    <b>Anda Belum Melakukan Transaksi Pembayaran</b>
        </div>                
        </div>
        </div>";
    }
        ?>

        </div>
        
        <script>
        /**
         * Load payment data from API
         * This demonstrates how to use the API from the frontend
         */
        async function loadDataFromAPI() {
            const apiAlert = document.getElementById('apiAlert');
            const apiStatus = document.getElementById('apiStatus');
            
            try {
                // Show loading state
                apiStatus.textContent = 'Loading...';
                apiAlert.style.display = 'block';
                
                // Get NISN from session
                const nisn = '<?= $nisn ?>';
                
                // Call API endpoint
                const response = await fetch('../api/pembayaran', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'include' // Include session cookies
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    apiStatus.textContent = 'Successfully loaded ' + data.data.length + ' payment records from API';
                    apiAlert.className = 'alert alert-success';
                    
                    // Log the API response
                    console.log('API Response:', data);
                    
                    // You can now use data.data to update the table
                    // Example: updateTableWithAPIData(data.data);
                } else {
                    apiStatus.textContent = 'API Error: ' + (data.message || 'Unknown error');
                    apiAlert.className = 'alert alert-danger';
                }
            } catch (error) {
                apiStatus.textContent = 'Connection Error: ' + error.message;
                apiAlert.className = 'alert alert-danger';
                console.error('API Error:', error);
            }
        }
        
        /**
         * Get user profile from API
         */
        async function getUserProfile() {
            try {
                const response = await fetch('../api/auth/me', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'include'
                });
                
                const data = await response.json();
                console.log('User Profile:', data);
                return data.data;
            } catch (error) {
                console.error('Error fetching profile:', error);
            }
        }
        
        /**
         * Update table with API data
         */
        function updateTableWithAPIData(payments) {
            const tbody = document.querySelector('table tbody');
            if (!tbody) return;
            
            tbody.innerHTML = '';
            let no = 1;
            
            payments.forEach(payment => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${no++}</td>
                    <td>${payment.nisn}</td>
                    <td>${payment.nama}</td>
                    <td>${payment.nama_kelas}</td>
                    <td>${payment.tahun}</td>
                    <td>${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(payment.nominal)}</td>
                    <td>${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(payment.jumlah_bayar)}</td>
                    <td>${payment.tgl_bayar}</td>
                    <td>${payment.bulan_dibayar}</td>
                    <td>${payment.nama_petugas}</td>
                `;
                tbody.appendChild(row);
            });
        }
        
        // Uncomment to auto-load API data on page load
        // window.addEventListener('load', loadDataFromAPI);
        </script>
</body>