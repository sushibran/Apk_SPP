<?php
function query($query)
{
    include '../koneksi.php';
    $result = mysqli_query($koneksi, $query);
    $rows = [];
    while ($row = mysqli_fetch_array($result)) {
        $rows[] = $row;
    }

    return $rows;
}

function sortirperbulan($from_date, $to_date)
{
    $query2 = "SELECT * FROM pembayaran 
    INNER JOIN siswa ON pembayaran.nisn=siswa.nisn
    INNER JOIN kelas ON siswa.id_kelas=kelas.id_kelas
    INNER JOIN spp ON pembayaran.id_spp=spp.id_spp
    INNER JOIN petugas ON pembayaran.id_petugas=petugas.id_petugas

    WHERE tgl_bayar BETWEEN '$from_date' AND '$to_date'
    ORDER BY tgl_bayar DESC";

    return query($query2);
}
?>
