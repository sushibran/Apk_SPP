<h1>Data Siswa</h1>
<a href="?url=tambah_siswa" class="btn btn-primary mt-2"><span data-feather="user-plus"></span> Tambah Siswa</a>
<hr>
<table id="table" class="table table-striped table-bordered">
    <thead>
        <tr class="fw-bold">
            <th>NISN</th>
            <th>NIS</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>SPP</th>
            <th>Password</th>
            <th>Alamat</th>
            <th>No Telpon</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        require 'functions_query.php';
        $siswa = query("SELECT * FROM siswa,kelas,spp WHERE siswa.id_kelas = kelas.id_kelas AND siswa.id_spp = spp.id_spp ORDER BY nisn ASC");
        foreach ($siswa as $data) :
        ?>
            <tr>
                <td><?php echo $data['nisn']; ?></td>
                <td><?php echo $data['nis']; ?></td>
                <td><?php echo $data['nama']; ?></td>
                <td><?php echo $data['nama_kelas']; ?></td>
                <td><?php echo $data['tahun']; ?> - <?= number_format($data['nominal'], 2, ',', '.'); ?></td>
                <td><?php echo $data['password']; ?></td>
                <td><?php echo $data['alamat']; ?></td>
                <td><?php echo $data['no_telp']; ?></td>
                <td>
                    <a href="?url=edit_siswa&nisn=<?php echo $data['nisn']; ?>" class="btn btn-primary mb-1"><span data-feather="edit"></span> Edit</a>
                    <a href="?url=hapus_siswa&nisn=<?php echo $data['nisn']; ?>" class="btn btn-danger" onClick="return confirm('Anda yakin akan menghapus data ini?')"><span data-feather="trash-2"></span> Hapus</a>
                </td>
            </tr>
        <?php
        endforeach;
        ?>
    </tbody>
    <script src="https://code.jquery.com/jquery-3.5.1.js
    "></script>
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js
    "></script>
    <script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap4.min.js
    "></script>
    <script src="../style/js/paginate.js"></script>
</table>