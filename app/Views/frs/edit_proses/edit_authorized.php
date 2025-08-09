<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Authorized</h6>
    </div>
    <div class="card-body">
        <h1 class="h4 text-gray-900 mb-4">Edit Data Authorized</h1>

        <a href="<?= base_url('frs/index') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>
        <form action="<?= base_url('authorized_frs/update') ?>" method="POST">
            <input type="hidden" name="id_authorized_frs" value="<?= $authorizedData['id_authorized_frs'] ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="id_user_authorized_frs">User Authorized:</label>
                    <select class="form-control" id="id_user_dokter_authorized_frs" name="id_user_dokter_authorized_frs">
                        <option value="" <?= empty($authorizedData['id_user_dokter_authorized_frs']) ? 'selected' : '' ?>>-</option>
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['status_user'] === 'Dokter'): ?>
                                <option value="<?= $user['id_user'] ?>" <?= $user['id_user'] == $authorizedData['id_user_dokter_authorized_frs'] ? 'selected' : '' ?>>
                                    <?= $user['nama_user'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="status_authorized_frs">Status Authorized:</label>
                    <!-- Dropdown for status_authorized_frs -->
                    <select name="status_authorized_frs" id="status_authorized_frs" class="form-control">
                        <option value="Belum Authorized" <?= old('status_authorized_frs', esc($authorizedData['status_authorized_frs'])) == 'Belum Authorized' ? 'selected' : '' ?>>Belum Authorized</option>
                        <option value="Proses Authorized" <?= old('status_authorized_frs', esc($authorizedData['status_authorized_frs'])) == 'Proses Authorized' ? 'selected' : '' ?>>Proses Authorized</option>
                        <option value="Selesai Authorized" <?= old('status_authorized_frs', esc($authorizedData['status_authorized_frs'])) == 'Selesai Authorized' ? 'selected' : '' ?>>Selesai authorized</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="mulai_authorized_frs">Mulai Authorized:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="mulai_authorized_frs_time" name="mulai_authorized_frs_time" value="<?= date('H:i', strtotime($authorizedData['mulai_authorized_frs'])) ?>">
                        <input type="date" class="form-control" id="mulai_authorized_frs_date" name="mulai_authorized_frs_date" value="<?= date('Y-m-d', strtotime($authorizedData['mulai_authorized_frs'])) ?>">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="selesai_authorized_frs">Selesai Authorized:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="selesai_authorized_frs_time" name="selesai_authorized_frs_time" value="<?= date('H:i', strtotime($authorizedData['selesai_authorized_frs'])) ?>">
                        <input type="date" class="form-control" id="selesai_authorized_frs_date" name="selesai_authorized_frs_date" value="<?= date('Y-m-d', strtotime($authorizedData['selesai_authorized_frs'])) ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
        </form>
    </div>
</div>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>