<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?> 

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Laporan Jumlah Pasien</h6>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari kode HPA...">
            </div>
            <div class="col-md-6">
                <select id="filterStatus" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Selesai">Selesai</option>
                </select>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Kode HPA</th>
                        <th>ID Pasien</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($hpaData as $hpa) : ?>
                        <tr>
                            <td><?= esc($hpa['kode_hpa']) ?></td>
                            <td><?= esc($hpa['id_pasien']) ?></td>
                            <td><?= esc($hpa['status_hpa']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        filterTable();
    });

    document.getElementById('filterStatus').addEventListener('change', function() {
        filterTable();
    });

    function filterTable() {
        let searchValue = document.getElementById('searchInput').value.toLowerCase();
        let statusValue = document.getElementById('filterStatus').value;
        let rows = document.querySelectorAll('#dataTable tbody tr');

        rows.forEach(row => {
            let kodeHPA = row.cells[0].textContent.toLowerCase();
            let status = row.cells[2].textContent;
            
            if (kodeHPA.includes(searchValue) && (statusValue === '' || status === statusValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>

<?= $this->include('templates/notifikasi'); ?>
<?= $this->include('templates/dashboard/footer_dashboard'); ?>
