<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Penanaman</h6>
    </div>
    <div class="card-body">
        <h1 class="h4 text-gray-900 mb-4">Edit Data Penanaman</h1>

        <a href="<?= base_url('hpa/index') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>
        <form action="<?= base_url('penanaman_hpa/update') ?>" method="POST">
            <input type="hidden" name="id_penanaman_hpa" value="<?= $penanamanData['id_penanaman_hpa'] ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="id_user_penanaman_hpa">User Penanaman:</label>
                    <select class="form-control" id="id_user_penanaman_hpa" name="id_user_penanaman_hpa">
                        <option value="" <?= empty($penanamanData['id_user_penanaman_hpa']) ? 'selected' : '' ?>>-</option>
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['status_user'] === 'Analis'): ?>
                                <option value="<?= $user['id_user'] ?>" <?= $user['id_user'] == $penanamanData['id_user_penanaman_hpa'] ? 'selected' : '' ?>>
                                    <?= $user['nama_user'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="status_penanaman_hpa">Status Penanaman:</label>
                    <!-- Dropdown for status_penanaman_hpa -->
                    <select name="status_penanaman_hpa" id="status_penanaman_hpa" class="form-control">
                        <option value="Belum Penanaman" <?= old('status_penanaman_hpa', esc($penanamanData['status_penanaman_hpa'])) == 'Belum Penanaman' ? 'selected' : '' ?>>Belum Penanaman</option>
                        <option value="Proses Penanaman" <?= old('status_penanaman_hpa', esc($penanamanData['status_penanaman_hpa'])) == 'Proses Penanaman' ? 'selected' : '' ?>>Proses Penanaman</option>
                        <option value="Selesai Penanaman" <?= old('status_penanaman_hpa', esc($penanamanData['status_penanaman_hpa'])) == 'Selesai Penanaman' ? 'selected' : '' ?>>Selesai Penanaman</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="mulai_penanaman_hpa">Mulai Penanaman:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="mulai_penanaman_hpa_time" name="mulai_penanaman_hpa_time" value="<?= date('H:i', strtotime($penanamanData['mulai_penanaman_hpa'])) ?>">
                        <input type="date" class="form-control" id="mulai_penanaman_hpa_date" name="mulai_penanaman_hpa_date" value="<?= date('Y-m-d', strtotime($penanamanData['mulai_penanaman_hpa'])) ?>">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="selesai_penanaman_hpa">Selesai Penanaman:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="selesai_penanaman_hpa_time" name="selesai_penanaman_hpa_time" value="<?= date('H:i', strtotime($penanamanData['selesai_penanaman_hpa'])) ?>">
                        <input type="date" class="form-control" id="selesai_penanaman_hpa_date" name="selesai_penanaman_hpa_date" value="<?= date('Y-m-d', strtotime($penanamanData['selesai_penanaman_hpa'])) ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
        </form>
    </div>
</div>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>