<h1>Data Kelas</h1>
<a href="?url=tambah_kelas" class="btn btn-primary mt-2"><span data-feather="plus-circle"></span> Tambah Kelas</a>
<hr>

<table id="table" class="table table-striped table-bordered">
    <thead>
        <tr class="fw-bold">
            <th>No</th>
            <th>ID</th>
            <th>Nama Kelas</th>
            <th>Kompetensi Keahlian</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        require 'functions_query.php';
        $i = 1;
        $kelas = query("SELECT * FROM kelas ORDER BY id_kelas ASC");
        foreach ($kelas as $data) :
        ?>
            <tr>
                <td><?= $i; ?></td>
                <td><?= $data['id_kelas']; ?></td>
                <td><?= $data['nama_kelas']; ?></td>
                <td><?= $data['kompetensi_keahlian']; ?></td>
                <td>
                    <a href="?url=edit_kelas&id=<?= $data['id_kelas']; ?>" class="btn btn-primary"><span data-feather="edit"></span> Edit</a>

                    <a href="?url=hapus_kelas&id=<?= $data['id_kelas']; ?>" class="btn btn-danger" onClick="return confirm('Anda yakin akan menghapus data ini?')"><span data-feather="trash-2"></span> Hapus</a>


                </td>
            </tr>
        <?php
            $i++;
        endforeach;
        ?>
</table>
</tbody>
<script src="https://code.jquery.com/jquery-3.5.1.js
"></script>
<script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js
"></script>
<script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap4.min.js
"></script>
<script src="../style/js/paginate.js"></script>