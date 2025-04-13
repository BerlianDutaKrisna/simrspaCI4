<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Authorized</h6>
    </div>
    <div class="card-body">
        <h1 class="h4 text-gray-900 mb-4">Edit Data Authorized</h1>

        <a href="<?= base_url('hpa/index') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>
        <form action="<?= base_url('authorized_hpa/update') ?>" method="POST">
            <input type="hidden" name="id_authorized_hpa" value="<?= $authorizedData['id_authorized_hpa'] ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="id_user_authorized_hpa">User Authorized:</label>
                    <select class="form-control" id="id_user_authorized_hpa" name="id_user_authorized_hpa">
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['status_user'] === 'Analis'): ?>
                                <option value="<?= $user['id_user'] ?>" <?= $user['id_user'] == $authorizedData['id_user_authorized_hpa'] ? 'selected' : '' ?>>
                                    <?= $user['nama_user'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="status_authorized_hpa">Status Authorized:</label>
                    <!-- Dropdown for status_authorized_hpa -->
                    <select name="status_authorized_hpa" id="status_authorized_hpa" class="form-control">
                        <option value="Belum Authorized" <?= old('status_authorized_hpa', esc($authorizedData['status_authorized_hpa'])) == 'Belum Authorized' ? 'selected' : '' ?>>Belum Authorized</option>
                        <option value="Proses Authorized" <?= old('status_authorized_hpa', esc($authorizedData['status_authorized_hpa'])) == 'Proses Authorized' ? 'selected' : '' ?>>Proses Authorized</option>
                        <option value="Selesai Authorized" <?= old('status_authorized_hpa', esc($authorizedData['status_authorized_hpa'])) == 'Selesai Authorized' ? 'selected' : '' ?>>Selesai authorized</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="mulai_authorized_hpa">Mulai Authorized:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="mulai_authorized_hpa_time" name="mulai_authorized_hpa_time" value="<?= date('H:i', strtotime($authorizedData['mulai_authorized_hpa'])) ?>">
                        <input type="date" class="form-control" id="mulai_authorized_hpa_date" name="mulai_authorized_hpa_date" value="<?= date('Y-m-d', strtotime($authorizedData['mulai_authorized_hpa'])) ?>">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="selesai_authorized_hpa">Selesai Authorized:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="selesai_authorized_hpa_time" name="selesai_authorized_hpa_time" value="<?= date('H:i', strtotime($authorizedData['selesai_authorized_hpa'])) ?>">
                        <input type="date" class="form-control" id="selesai_authorized_hpa_date" name="selesai_authorized_hpa_date" value="<?= date('Y-m-d', strtotime($authorizedData['selesai_authorized_hpa'])) ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
        </form>
    </div>
</div>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>