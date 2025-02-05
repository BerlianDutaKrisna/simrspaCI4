<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Authorized</h6>
    </div>
    <div class="card-body">
        <h1 class="h4 text-gray-900 mb-4">Edit Data Authorized</h1>

        <a href="<?= base_url('exam/index_exam') ?>" class="btn btn-primary mb-3">Kembali</a>
        <form action="<?= base_url('autorized/update_autorized') ?>" method="POST">
            <input type="hidden" name="id_autorized" value="<?= $autorizedData['id_autorized'] ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="id_user_autorized">User Authorized:</label>
                    <select class="form-control" id="id_user_autorized" name="id_user_autorized">
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['status_user'] === 'Analis'): ?>
                                <option value="<?= $user['id_user'] ?>" <?= $user['id_user'] == $autorizedData['id_user_autorized'] ? 'selected' : '' ?>>
                                    <?= $user['nama_user'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="status_autorized">Status autorized:</label>
                    <!-- Dropdown for status_autorized -->
                    <select name="status_autorized" id="status_autorized" class="form-control">
                        <option value="Belum Authorized" <?= old('status_autorized', esc($autorizedData['status_autorized'])) == 'Belum Authorized' ? 'selected' : '' ?>>Belum Authorized</option>
                        <option value="Proses Authorized" <?= old('status_autorized', esc($autorizedData['status_autorized'])) == 'Proses Authorized' ? 'selected' : '' ?>>Proses Authorized</option>
                        <option value="Selesai Authorized" <?= old('status_autorized', esc($autorizedData['status_autorized'])) == 'Selesai Authorized' ? 'selected' : '' ?>>Selesai Authorized</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="mulai_autorized">Mulai Authorized:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="mulai_autorized_time" name="mulai_autorized_time" value="<?= date('H:i', strtotime($autorizedData['mulai_autorized'])) ?>">
                        <input type="date" class="form-control" id="mulai_autorized_date" name="mulai_autorized_date" value="<?= date('Y-m-d', strtotime($autorizedData['mulai_autorized'])) ?>">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="selesai_autorized">Selesai Authorized:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="selesai_autorized_time" name="selesai_autorized_time" value="<?= date('H:i', strtotime($autorizedData['selesai_autorized'])) ?>">
                        <input type="date" class="form-control" id="selesai_autorized_date" name="selesai_autorized_date" value="<?= date('Y-m-d', strtotime($autorizedData['selesai_autorized'])) ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</div>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>