<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Pemverifikasi</h6>
    </div>
    <div class="card-body">
        <h1 class="h4 text-gray-900 mb-4">Edit Data Pemverifikasi</h1>

        <a href="<?= base_url('ihc/index') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>
        <form action="<?= base_url('pemverifikasi_ihc/update') ?>" method="POST">
            <input type="hidden" name="id_pemverifikasi_ihc" value="<?= $pemverifikasiData['id_pemverifikasi_ihc'] ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="id_user_pemverifikasi_ihc">User Pemverifikasi:</label>
                    <select class="form-control" id="id_user_pemverifikasi_ihc" name="id_user_pemverifikasi_ihc">
                        <option value="" <?= empty($pemverifikasiData['id_user_pemverifikasi_ihc']) ? 'selected' : '' ?>>-</option>
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['status_user'] === 'Analis'): ?>
                                <option value="<?= $user['id_user'] ?>" <?= $user['id_user'] == $pemverifikasiData['id_user_pemverifikasi_ihc'] ? 'selected' : '' ?>>
                                    <?= $user['nama_user'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="status_pemverifikasi_ihc">Status Pemverifikasi:</label>
                    <!-- Dropdown for status_pemverifikasi_ihc -->
                    <select name="status_pemverifikasi_ihc" id="status_pemverifikasi_ihc" class="form-control">
                        <option value="Belum Pemverifikasi" <?= old('status_pemverifikasi_ihc', esc($pemverifikasiData['status_pemverifikasi_ihc'])) == 'Belum Pemverifikasi' ? 'selected' : '' ?>>Belum Pemverifikasi</option>
                        <option value="Proses Pemverifikasi" <?= old('status_pemverifikasi_ihc', esc($pemverifikasiData['status_pemverifikasi_ihc'])) == 'Proses Pemverifikasi' ? 'selected' : '' ?>>Proses Pemverifikasi</option>
                        <option value="Selesai Pemverifikasi" <?= old('status_pemverifikasi_ihc', esc($pemverifikasiData['status_pemverifikasi_ihc'])) == 'Selesai Pemverifikasi' ? 'selected' : '' ?>>Selesai Pemverifikasi</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="mulai_pemverifikasi_ihc">Mulai Pemverifikasi:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="mulai_pemverifikasi_ihc_time" name="mulai_pemverifikasi_ihc_time" value="<?= date('H:i', strtotime($pemverifikasiData['mulai_pemverifikasi_ihc'])) ?>">
                        <input type="date" class="form-control" id="mulai_pemverifikasi_ihc_date" name="mulai_pemverifikasi_ihc_date" value="<?= date('Y-m-d', strtotime($pemverifikasiData['mulai_pemverifikasi_ihc'])) ?>">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="selesai_pemverifikasi_ihc">Selesai Pemverifikasi:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="selesai_pemverifikasi_ihc_time" name="selesai_pemverifikasi_ihc_time" value="<?= date('H:i', strtotime($pemverifikasiData['selesai_pemverifikasi_ihc'])) ?>">
                        <input type="date" class="form-control" id="selesai_pemverifikasi_ihc_date" name="selesai_pemverifikasi_ihc_date" value="<?= date('Y-m-d', strtotime($pemverifikasiData['selesai_pemverifikasi_ihc'])) ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
        </form>
    </div>
</div>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>