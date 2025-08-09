<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<?= $this->include('dashboard/jumlah_sampel_belum_selesai'); ?>
<?= $this->include('dashboard/pencarian_pemeriksaan'); ?>
<?= $this->include('dashboard/jenis_tindakan'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table live proses</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Kode HPA</th>
                        <th>Nama Pasien</th>
                        <th>Norm Pasien</th>
                        <th>Diagnosa</th>
                        <th>Tindakan Spesimen</th>
                        <th>Status Proses</th>
                        <th>Proses Status</th>
                        <th>Dikerjakan Oleh</th>
                        <th>Mulai Pengerjaan</th>
                        <th>Selesai Pengerjaan</th>
                        <th>Deadline Hasil</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->include('dashboard/grafik_pemeriksaan'); ?>
<?= $this->include('templates/notifikasi'); ?>
<?= $this->include('templates/dashboard/footer_dashboard'); ?>

<script>
    window.onload = () => {
    const inputNormSimrs = document.getElementById('norm_simrs');
    if (inputNormSimrs) {
        inputNormSimrs.focus();
    }
};

// Fokuskan input saat modal ditutup supaya user bisa langsung ketik ulang
$('#resultModal').on('hidden.bs.modal', () => {
    const inputNormSimrs = document.getElementById('norm_simrs');
    if (inputNormSimrs) {
        inputNormSimrs.focus();
    }
});
</script>