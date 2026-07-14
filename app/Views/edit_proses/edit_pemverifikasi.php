<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Pemverifikasi</h6>
    </div>
    <div class="card-body">
        <h1 class="h4 text-gray-900 mb-4">Edit Data Pemverifikasi</h1>

        <a href="<?= base_url('exam/index_exam') ?>" class="btn btn-primary mb-3">Kembali</a>
        <form action="<?= base_url('pemverifikasi/update_pemverifikasi') ?>" method="POST">
            <input type="hidden" name="id_pemverifikasi" value="<?= $pemverifikasiData['id_pemverifikasi'] ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="id_user_pemverifikasi">User pemverifikasi:</label>
                    <select class="form-control" id="id_user_pemverifikasi" name="id_user_pemverifikasi">
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['status_user'] === 'Analis'): ?>
                                <option value="<?= $user['id_user'] ?>" <?= $user['id_user'] == $pemverifikasiData['id_user_pemverifikasi'] ? 'selected' : '' ?>>
                                    <?= $user['nama_user'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="status_pemverifikasi">Status pemverifikasi:</label>
                    <!-- Dropdown for status_pemverifikasi -->
                    <select name="status_pemverifikasi" id="status_pemverifikasi" class="form-control">
                        <option value="Belum Pemverifikasi" <?= old('status_pemverifikasi', esc($pemverifikasiData['status_pemverifikasi'])) == 'Belum Pemverifikasi' ? 'selected' : '' ?>>Belum Pemverifikasi</option>
                        <option value="Proses Pemverifikasi" <?= old('status_pemverifikasi', esc($pemverifikasiData['status_pemverifikasi'])) == 'Proses Pemverifikasi' ? 'selected' : '' ?>>Proses Pemverifikasi</option>
                        <option value="Selesai Pemverifikasi" <?= old('status_pemverifikasi', esc($pemverifikasiData['status_pemverifikasi'])) == 'Selesai Pemverifikasi' ? 'selected' : '' ?>>Selesai Pemverifikasi</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="mulai_pemverifikasi">Mulai pemverifikasi:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="mulai_pemverifikasi_time" name="mulai_pemverifikasi_time" value="<?= date('H:i', strtotime($pemverifikasiData['mulai_pemverifikasi'])) ?>">
                        <input type="date" class="form-control" id="mulai_pemverifikasi_date" name="mulai_pemverifikasi_date" value="<?= date('Y-m-d', strtotime($pemverifikasiData['mulai_pemverifikasi'])) ?>">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="selesai_pemverifikasi">Selesai pemverifikasi:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="selesai_pemverifikasi_time" name="selesai_pemverifikasi_time" value="<?= date('H:i', strtotime($pemverifikasiData['selesai_pemverifikasi'])) ?>">
                        <input type="date" class="form-control" id="selesai_pemverifikasi_date" name="selesai_pemverifikasi_date" value="<?= date('Y-m-d', strtotime($pemverifikasiData['selesai_pemverifikasi'])) ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</div>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>