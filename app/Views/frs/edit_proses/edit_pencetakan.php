<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Pencetakan</h6>
    </div>
    <div class="card-body">
        <h1 class="h4 text-gray-900 mb-4">Edit Data Pencetakan</h1>

        <a href="<?= base_url('frs/index') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>
        <form action="<?= base_url('pencetakan_frs/update') ?>" method="POST">
            <input type="hidden" name="id_pencetakan_frs" value="<?= $pencetakanData['id_pencetakan_frs'] ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="id_user_pencetakan_frs">User Pencetakan:</label>
                    <select class="form-control" id="id_user_pencetakan_frs" name="id_user_pencetakan_frs">
                        <option value="" <?= empty($pencetakanData['id_user_pencetakan_frs']) ? 'selected' : '' ?>>-</option>
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['status_user'] === 'Analis'): ?>
                                <option value="<?= $user['id_user'] ?>" <?= $user['id_user'] == $pencetakanData['id_user_pencetakan_frs'] ? 'selected' : '' ?>>
                                    <?= $user['nama_user'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="status_pencetakan_frs">Status Pencetakan:</label>
                    <!-- Dropdown for status_pencetakan_frs -->
                    <select name="status_pencetakan_frs" id="status_pencetakan_frs" class="form-control">
                        <option value="Belum Pencetakan" <?= old('status_pencetakan_frs', esc($pencetakanData['status_pencetakan_frs'])) == 'Belum Pencetakan' ? 'selected' : '' ?>>Belum Pencetakan</option>
                        <option value="Proses Pencetakan" <?= old('status_pencetakan_frs', esc($pencetakanData['status_pencetakan_frs'])) == 'Proses Pencetakan' ? 'selected' : '' ?>>Proses Pencetakan</option>
                        <option value="Selesai Pencetakan" <?= old('status_pencetakan_frs', esc($pencetakanData['status_pencetakan_frs'])) == 'Selesai Pencetakan' ? 'selected' : '' ?>>Selesai Pencetakan</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="mulai_pencetakan_frs">Mulai Pencetakan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="mulai_pencetakan_frs_time" name="mulai_pencetakan_frs_time" value="<?= date('H:i', strtotime($pencetakanData['mulai_pencetakan_frs'])) ?>">
                        <input type="date" class="form-control" id="mulai_pencetakan_frs_date" name="mulai_pencetakan_frs_date" value="<?= date('Y-m-d', strtotime($pencetakanData['mulai_pencetakan_frs'])) ?>">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="selesai_pencetakan_frs">Selesai Pencetakan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="selesai_pencetakan_frs_time" name="selesai_pencetakan_frs_time" value="<?= date('H:i', strtotime($pencetakanData['selesai_pencetakan_frs'])) ?>">
                        <input type="date" class="form-control" id="selesai_pencetakan_frs_date" name="selesai_pencetakan_frs_date" value="<?= date('Y-m-d', strtotime($pencetakanData['selesai_pencetakan_frs'])) ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
        </form>
    </div>
</div>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>