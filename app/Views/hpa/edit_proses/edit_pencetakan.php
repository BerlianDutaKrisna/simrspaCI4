<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Pencetakan</h6>
    </div>
    <div class="card-body">
        <h1 class="h4 text-gray-900 mb-4">Edit Data Pencetakan</h1>

        <a href="<?= base_url('exam/index_exam') ?>" class="btn btn-primary mb-3">Kembali</a>
        <form action="<?= base_url('pencetakan/update_pencetakan') ?>" method="POST">
            <input type="hidden" name="id_pencetakan" value="<?= $pencetakanData['id_pencetakan'] ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="id_user_pencetakan">User pencetakan:</label>
                    <select class="form-control" id="id_user_pencetakan" name="id_user_pencetakan">
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['status_user'] === 'Analis'): ?>
                                <option value="<?= $user['id_user'] ?>" <?= $user['id_user'] == $pencetakanData['id_user_pencetakan'] ? 'selected' : '' ?>>
                                    <?= $user['nama_user'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="status_pencetakan">Status pencetakan:</label>
                    <!-- Dropdown for status_pencetakan -->
                    <select name="status_pencetakan" id="status_pencetakan" class="form-control">
                        <option value="Belum Pencetakan" <?= old('status_pencetakan', esc($pencetakanData['status_pencetakan'])) == 'Belum Pencetakan' ? 'selected' : '' ?>>Belum Pencetakan</option>
                        <option value="Proses Pencetakan" <?= old('status_pencetakan', esc($pencetakanData['status_pencetakan'])) == 'Proses Pencetakan' ? 'selected' : '' ?>>Proses Pencetakan</option>
                        <option value="Selesai Pencetakan" <?= old('status_pencetakan', esc($pencetakanData['status_pencetakan'])) == 'Selesai Pencetakan' ? 'selected' : '' ?>>Selesai Pencetakan</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="mulai_pencetakan">Mulai pencetakan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="mulai_pencetakan_time" name="mulai_pencetakan_time" value="<?= date('H:i', strtotime($pencetakanData['mulai_pencetakan'])) ?>">
                        <input type="date" class="form-control" id="mulai_pencetakan_date" name="mulai_pencetakan_date" value="<?= date('Y-m-d', strtotime($pencetakanData['mulai_pencetakan'])) ?>">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="selesai_pencetakan">Selesai pencetakan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="selesai_pencetakan_time" name="selesai_pencetakan_time" value="<?= date('H:i', strtotime($pencetakanData['selesai_pencetakan'])) ?>">
                        <input type="date" class="form-control" id="selesai_pencetakan_date" name="selesai_pencetakan_date" value="<?= date('Y-m-d', strtotime($pencetakanData['selesai_pencetakan'])) ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</div>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>