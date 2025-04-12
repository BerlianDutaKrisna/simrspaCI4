<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Penanaman</h6>
    </div>
    <div class="card-body">
        <h1 class="h4 text-gray-900 mb-4">Edit Data Penanaman</h1>

        <a href="<?= base_url('exam/index_exam') ?>" class="btn btn-primary mb-3">Kembali</a>
        <form action="<?= base_url('penanaman/update_penanaman') ?>" method="POST">
            <input type="hidden" name="id_penanaman" value="<?= $penanamanData['id_penanaman'] ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="id_user_penanaman">User penanaman:</label>
                    <select class="form-control" id="id_user_penanaman" name="id_user_penanaman">
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['status_user'] === 'Analis'): ?>
                                <option value="<?= $user['id_user'] ?>" <?= $user['id_user'] == $penanamanData['id_user_penanaman'] ? 'selected' : '' ?>>
                                    <?= $user['nama_user'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="status_penanaman">Status penanaman:</label>
                    <!-- Dropdown for status_penanaman -->
                    <select name="status_penanaman" id="status_penanaman" class="form-control">
                        <option value="Belum Penanaman" <?= old('status_penanaman', esc($penanamanData['status_penanaman'])) == 'Belum Penanaman' ? 'selected' : '' ?>>Belum Penanaman</option>
                        <option value="Proses Penanaman" <?= old('status_penanaman', esc($penanamanData['status_penanaman'])) == 'Proses Penanaman' ? 'selected' : '' ?>>Proses Penanaman</option>
                        <option value="Selesai Penanaman" <?= old('status_penanaman', esc($penanamanData['status_penanaman'])) == 'Selesai Penanaman' ? 'selected' : '' ?>>Selesai Penanaman</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="mulai_penanaman">Mulai penanaman:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="mulai_penanaman_time" name="mulai_penanaman_time" value="<?= date('H:i', strtotime($penanamanData['mulai_penanaman'])) ?>">
                        <input type="date" class="form-control" id="mulai_penanaman_date" name="mulai_penanaman_date" value="<?= date('Y-m-d', strtotime($penanamanData['mulai_penanaman'])) ?>">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="selesai_penanaman">Selesai penanaman:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="selesai_penanaman_time" name="selesai_penanaman_time" value="<?= date('H:i', strtotime($penanamanData['selesai_penanaman'])) ?>">
                        <input type="date" class="form-control" id="selesai_penanaman_date" name="selesai_penanaman_date" value="<?= date('Y-m-d', strtotime($penanamanData['selesai_penanaman'])) ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</div>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>