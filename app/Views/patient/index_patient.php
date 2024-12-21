<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Pasien</h6>
    </div>
    <div class="card-body">
        <h1>Daftar Pasien</h1>

        <!-- Tombol Kembali ke Dashboard -->
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>

        <!-- Tombol Tambah Pasien -->
        <a href="<?= base_url('patient/register_patient') ?>" class="btn btn-success mb-3">Tambah Pasien</a>

        <!-- Tabel Data Pasien -->
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Norm Pasien</th>
                    <th>Nama Pasien</th>
                    <th>Jenis Kelamin</th>
                    <th>Tanggal Lahir</th>
                    <th>Alamat</th>
                    <th>Status Pasien</th>
                    <th class="text-center" style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($patients)) : ?>
                    <?php foreach ($patients as $patient) : ?>
                        <tr>
                            <td><?= esc($patient['norm_pasien']) ?></td>
                            <td><?= esc($patient['nama_pasien']) ?></td>
                            <td><?= esc($patient['jenis_kelamin_pasien']) ?></td>
                            <td><?= esc(date('d-m-Y', strtotime($patient['tanggal_lahir_pasien']))) ?></td>
                            <td><?= esc($patient['alamat_pasien']) ?></td>
                            <td><?= esc($patient['status_pasien']) ?></td>
                            <td class="text-center">
                                <!-- Tombol Edit -->
                                <a href="/patients/edit/<?= esc($patient['id_pasien']) ?>" class="btn btn-warning btn-sm">Edit</a>

                                <!-- Tombol Hapus -->
                                <a href="/patients/delete/<?= esc($patient['id_pasien']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pasien ini?');">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5">Tidak ada data pasien.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>
