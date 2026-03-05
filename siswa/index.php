<?php
// siswa/index.php
// This view is now fully driven by the JSON API.  Instead of querying the
// database with PHP, the frontend fetches payment records via the
// `/api/pembayaran` endpoint and renders them dynamically.  A colour-coded
// status badge in the header indicates whether the student's SPP is complete.
// Session is still used for authentication only.
session_start();
if (!isset($_SESSION['nisn'])) {
    header("location:../index.php");
    exit;
}
$nisn = $_SESSION['nisn'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SPP Siswa</title>
<link rel="stylesheet" href="../style/css/bootstrap.min.css">
<style>
/* tambahan styling untuk badge status */
.badge-sudah-lunas {background-color: #198754;}
.badge-belum-lunas {background-color: #dc3545;}
.badge-warning {background-color: #ffc107; color: #212529;}
</style>
</head>
<body>
<header class="p-3 text-bg-dark">
    <div class="container d-flex flex-wrap align-items-center justify-content-between">
        <h2 class="text-light mb-0">History Pembayaran</h2>
        <div class="d-flex align-items-center">
            <span class="text-light me-2">Status SPP: <span id="paymentStatusBadge" class="badge bg-secondary">...</span></span>
            <a href="logout.php" onclick="return confirm('Anda yakin akan mau Log out??')" class="btn btn-outline-light">Logout</a>
        </div>
    </div>
</header>

<main class="container mt-4">
    <div id="contentArea">
        <p class="text-center">Memuat data...</p>
    </div>
</main>

<script>
async function fetchPayments() {
    try {
        const response = await fetch('../api/pembayaran', {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'include'
        });

        // ensure we have JSON before parsing
        const contentType = response.headers.get('content-type') || '';
        let result;
        if (contentType.includes('application/json')) {
            result = await response.json();
        } else {
            // unexpected HTML or text (often a redirect to login page)
            const text = await response.text();
            throw new Error('Non-JSON response from server: ' + text.substring(0, 200));
        }

        if (!response.ok) {
            showError('API Error: ' + (result.message || 'Unknown'));
            return;
        }
        renderPayments(result.data, result.meta || null);
    } catch (err) {
        // special case: parse error may reveal HTML
        showError('API Status: Connection Error: ' + err.message);
        console.error(err);
    }
}

function showError(msg) {
    const area = document.getElementById('contentArea');
    area.innerHTML = '<div class="alert alert-danger">' + msg + '</div>';
}

function renderPayments(payments, meta) {
    const area = document.getElementById('contentArea');
    if (!payments || payments.length === 0) {
        area.innerHTML = '<p class="text-center text-secondary"><strong>Anda Belum Melakukan Transaksi Pembayaran</strong></p>';
        updateStatusBadge(meta);
        return;
    }
    let html = '';
    html += '<div class="text-end mb-2"><a href="cetak_laporan.php?nisn=<?php echo $nisn;?>" class="btn btn-success btn-sm">Cetak Laporan</a></div>';
    html += '<div class="table-responsive"><table class="table table-striped table-bordered">';
    html += '<thead><tr class="fw-bold"><th>No</th><th>NISN</th><th>Nama</th><th>Kelas</th><th>Tahun SPP</th><th>Nominal Dibayar</th><th>Sudah Dibayar</th><th>Tanggal Bayar</th><th>Bulan Dibayar</th><th>Petugas</th></tr></thead><tbody>';

    let no = 1;
    payments.forEach(p => {
        html += '<tr>';
        html += '<td>' + (no++) + '</td>';
        html += '<td>' + p.nisn + '</td>';
        html += '<td>' + p.nama + '</td>';
        html += '<td>' + p.nama_kelas + '</td>';
        html += '<td>' + p.tahun + '</td>';
        html += '<td>' + formatCurrency(p.nominal) + '</td>';
        html += '<td>' + formatCurrency(p.jumlah_bayar) + '</td>';
        html += '<td>' + p.tgl_bayar + '</td>';
        html += '<td>' + p.bulan_dibayar + '</td>';
        html += '<td>' + p.nama_petugas + '</td>';
        html += '</tr>';
    });
    html += '</tbody></table></div>';
    area.innerHTML = html;
    updateStatusBadge(meta, payments);
}

function formatCurrency(val) {
    if (val === null || val === undefined) return '';
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(val);
}

function updateStatusBadge(meta, payments) {
    const badge = document.getElementById('paymentStatusBadge');
    let status = 'loading';
    if (meta && meta.status) {
        status = meta.status;
    } else if (payments && payments.length > 0) {
        let total = payments.reduce((sum, p) => sum + parseFloat(p.jumlah_bayar), 0);
        let nominal = parseFloat(payments[0].nominal || 0);
        status = (nominal - total <= 0) ? 'lunas' : 'belum_lunas';
    } else {
        status = 'belum_lunas';
    }

    badge.textContent = (status === 'lunas') ? 'LUNAS' : 'BELUM LUNAS';
    if (status === 'lunas') {
        badge.className = 'badge bg-success';
    } else {
        // use red to draw attention when payment is not complete
        badge.className = 'badge bg-danger';
    }
}

window.addEventListener('load', fetchPayments);
</script>
</body>
</html>
