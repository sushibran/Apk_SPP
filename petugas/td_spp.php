<h1>Data SPP</h1>
<a href="?url=tambah_spp" class="btn btn-primary mt-2"><span data-feather="plus-circle"></span> Tambah SPP</a>
<hr>
<table id="table" class="table table-striped table-bordered">
    <thead>
        <tr class="fw-bold">
            <th>No</th>
            <th>Tahun</th>
            <th>Nominal</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        require 'functions_query.php';
        $no = 1;
        $spp = query("SELECT * FROM spp ORDER BY id_spp ASC");
        foreach ($spp as $data) :
        ?>
            <tr>
                <td><?= $no; ?></td>
                <td><?= $data['tahun']; ?></td>
                <td><?= $data['nominal']; ?></td>
                <td>
                    <a href="?url=edit_spp&id=<?= $data['id_spp']; ?>" class="btn btn-primary"><span data-feather="edit"></span> Edit</a>

                    <a href="?url=hapus_spp&id=<?= $data['id_spp']; ?>" class="btn btn-danger" onClick="return confirm('Anda yakin akan menghapus data ini?')"><span data-feather="trash-2"></span> Hapus</a>
                </td>
            </tr>
        <?php
            $no++;
        endforeach;
        ?>
</table>
<script src="https://code.jquery.com/jquery-3.5.1.js
"></script>
<script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js
"></script>
<script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap4.min.js
"></script>
<script src="../style/js/paginate.js"></script>
</tbody>