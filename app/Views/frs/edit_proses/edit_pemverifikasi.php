<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Pemverifikasi</h6>
    </div>
    <div class="card-body">
        <h1 class="h4 text-gray-900 mb-4">Edit Data Pemverifikasi</h1>

        <a href="<?= base_url('frs/index') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>
        <form action="<?= base_url('pemverifikasi_frs/update') ?>" method="POST">
            <input type="hidden" name="id_pemverifikasi_frs" value="<?= $pemverifikasiData['id_pemverifikasi_frs'] ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="id_user_pemverifikasi_frs">User Pemverifikasi:</label>
                    <select class="form-control" id="id_user_pemverifikasi_frs" name="id_user_pemverifikasi_frs">
                        <option value="" <?= empty($pemverifikasiData['id_user_pemverifikasi_frs']) ? 'selected' : '' ?>>-</option>
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['status_user'] === 'Analis'): ?>
                                <option value="<?= $user['id_user'] ?>" <?= $user['id_user'] == $pemverifikasiData['id_user_pemverifikasi_frs'] ? 'selected' : '' ?>>
                                    <?= $user['nama_user'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="status_pemverifikasi_frs">Status Pemverifikasi:</label>
                    <!-- Dropdown for status_pemverifikasi_frs -->
                    <select name="status_pemverifikasi_frs" id="status_pemverifikasi_frs" class="form-control">
                        <option value="Belum Pemverifikasi" <?= old('status_pemverifikasi_frs', esc($pemverifikasiData['status_pemverifikasi_frs'])) == 'Belum Pemverifikasi' ? 'selected' : '' ?>>Belum Pemverifikasi</option>
                        <option value="Proses Pemverifikasi" <?= old('status_pemverifikasi_frs', esc($pemverifikasiData['status_pemverifikasi_frs'])) == 'Proses Pemverifikasi' ? 'selected' : '' ?>>Proses Pemverifikasi</option>
                        <option value="Selesai Pemverifikasi" <?= old('status_pemverifikasi_frs', esc($pemverifikasiData['status_pemverifikasi_frs'])) == 'Selesai Pemverifikasi' ? 'selected' : '' ?>>Selesai Pemverifikasi</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="mulai_pemverifikasi_frs">Mulai Pemverifikasi:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="mulai_pemverifikasi_frs_time" name="mulai_pemverifikasi_frs_time" value="<?= date('H:i', strtotime($pemverifikasiData['mulai_pemverifikasi_frs'])) ?>">
                        <input type="date" class="form-control" id="mulai_pemverifikasi_frs_date" name="mulai_pemverifikasi_frs_date" value="<?= date('Y-m-d', strtotime($pemverifikasiData['mulai_pemverifikasi_frs'])) ?>">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="selesai_pemverifikasi_frs">Selesai Pemverifikasi:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="selesai_pemverifikasi_frs_time" name="selesai_pemverifikasi_frs_time" value="<?= date('H:i', strtotime($pemverifikasiData['selesai_pemverifikasi_frs'])) ?>">
                        <input type="date" class="form-control" id="selesai_pemverifikasi_frs_date" name="selesai_pemverifikasi_frs_date" value="<?= date('Y-m-d', strtotime($pemverifikasiData['selesai_pemverifikasi_frs'])) ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
        </form>
    </div>
</div>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>