<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Pengirisan</h6>
    </div>
    <div class="card-body">
        <h1 class="h4 text-gray-900 mb-4">Edit Data Pengirisan</h1>

        <a href="<?= base_url('exam/index_exam') ?>" class="btn btn-primary mb-3">Kembali</a>
        <form action="<?= base_url('pengirisan/update_pengirisan') ?>" method="POST">
            <input type="hidden" name="id_pengirisan" value="<?= $pengirisanData['id_pengirisan'] ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="id_user_pengirisan">User Pengirisan:</label>
                    <select class="form-control" id="id_user_pengirisan" name="id_user_pengirisan">
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['status_user'] === 'Analis'): ?>
                                <option value="<?= $user['id_user'] ?>" <?= $user['id_user'] == $pengirisanData['id_user_pengirisan'] ? 'selected' : '' ?>>
                                    <?= $user['nama_user'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="status_pengirisan">Status Pengirisan:</label>
                    <!-- Dropdown for status_pengirisan -->
                    <select name="status_pengirisan" id="status_pengirisan" class="form-control">
                        <option value="Belum Pengirisan" <?= old('status_pengirisan', esc($pengirisanData['status_pengirisan'])) == 'Belum Pengirisan' ? 'selected' : '' ?>>Belum Pengirisan</option>
                        <option value="Proses Pengirisan" <?= old('status_pengirisan', esc($pengirisanData['status_pengirisan'])) == 'Proses Pengirisan' ? 'selected' : '' ?>>Proses Pengirisan</option>
                        <option value="Selesai Pengirisan" <?= old('status_pengirisan', esc($pengirisanData['status_pengirisan'])) == 'Selesai Pengirisan' ? 'selected' : '' ?>>Selesai Pengirisan</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="mulai_pengirisan">Mulai Pengirisan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="mulai_pengirisan_time" name="mulai_pengirisan_time" value="<?= date('H:i', strtotime($pengirisanData['mulai_pengirisan'])) ?>">
                        <input type="date" class="form-control" id="mulai_pengirisan_date" name="mulai_pengirisan_date" value="<?= date('Y-m-d', strtotime($pengirisanData['mulai_pengirisan'])) ?>">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="selesai_pengirisan">Selesai Pengirisan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="selesai_pengirisan_time" name="selesai_pengirisan_time" value="<?= date('H:i', strtotime($pengirisanData['selesai_pengirisan'])) ?>">
                        <input type="date" class="form-control" id="selesai_pengirisan_date" name="selesai_pengirisan_date" value="<?= date('Y-m-d', strtotime($pengirisanData['selesai_pengirisan'])) ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</div>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>