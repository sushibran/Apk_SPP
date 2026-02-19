<?php
function query($query)
{
    include '../koneksi.php';
    $result = mysqli_query($koneksi,$query);
    $rows = [];
    while ( $row = mysqli_fetch_array($result)) {
        $rows[] = $row ;
    }

    return $rows;
}
?>