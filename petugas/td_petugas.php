<h1>Data Petugas</h1>
    <a href="?url=tambah_petugas" class="btn btn-primary mt-2"><span data-feather="user-plus"></span> Tambah Petugas</a>
<hr>
<table id="table" class="table table-striped table-bordered">
    <thead>
        <tr class="fw-bold">
            <th>ID Petugas</th>
            <th>Nama Petugas</th>
            <th>Username</th>
            <th>Password</th>
            <th>Jabatan</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        require 'functions_query.php';
        $no = 1;
        $petugas = query("SELECT * FROM petugas ORDER BY id_petugas ASC");
        foreach ($petugas as $data) :        ?>
            <tr>
                <td><?= $data['id_petugas']; ?></td>
                <td><?= $data['nama_petugas']; ?></td>
                <td><?= $data['username']; ?></td>
                <td><?= $data['password']; ?></td>
                <td><?= $data['level']; ?></td>
                <td>
                    <a href="?url=edit_petugas&id_petugas=<?= $data['id_petugas']; ?>" class="btn btn-primary"><span data-feather="edit"></span> Edit</a>

                    <a href="?url=hapus_petugas&id_petugas=<?= $data['id_petugas']; ?>" class="btn btn-danger" onClick="return confirm('Anda yakin akan menghapus data ini?')"><span data-feather="trash-2"></span> Hapus</a>


                </td>
            </tr>
        <?php
        endforeach;
        ?>
</tbody>
</table>
<script src="https://code.jquery.com/jquery-3.5.1.js
"></script>
<script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js
"></script>
<script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap4.min.js
"></script>
<script src="../style/js/paginate.js"></script>