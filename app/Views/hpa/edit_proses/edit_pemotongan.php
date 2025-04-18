<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Pemotongan</h6>
    </div>
    <div class="card-body">
        <h1 class="h4 text-gray-900 mb-4">Edit Data Pemotongan</h1>

        <a href="<?= base_url('hpa/index') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>
        <form action="<?= base_url('pemotongan_hpa/update') ?>" method="POST">
            <input type="hidden" name="id_pemotongan_hpa" value="<?= $pemotonganData['id_pemotongan_hpa'] ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="id_user_pemotongan_hpa">User Pemotongan:</label>
                    <select class="form-control" id="id_user_pemotongan_hpa" name="id_user_pemotongan_hpa">
                        <option value="" <?= empty($pemotonganData['id_user_pemotongan_hpa']) ? 'selected' : '' ?>>-</option>
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['status_user'] === 'Analis'): ?>
                                <option value="<?= $user['id_user'] ?>" <?= $user['id_user'] == $pemotonganData['id_user_pemotongan_hpa'] ? 'selected' : '' ?>>
                                    <?= $user['nama_user'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="status_pemotongan_hpa">Status Pemotongan:</label>
                    <!-- Dropdown for status_pemotongan_hpa -->
                    <select name="status_pemotongan_hpa" id="status_pemotongan_hpa" class="form-control">
                        <option value="Belum Pemotongan" <?= old('status_pemotongan_hpa', esc($pemotonganData['status_pemotongan_hpa'])) == 'Belum Pemotongan' ? 'selected' : '' ?>>Belum Pemotongan</option>
                        <option value="Proses Pemotongan" <?= old('status_pemotongan_hpa', esc($pemotonganData['status_pemotongan_hpa'])) == 'Proses Pemotongan' ? 'selected' : '' ?>>Proses Pemotongan</option>
                        <option value="Selesai Pemotongan" <?= old('status_pemotongan_hpa', esc($pemotonganData['status_pemotongan_hpa'])) == 'Selesai Pemotongan' ? 'selected' : '' ?>>Selesai Pemotongan</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="mulai_pemotongan_hpa">Mulai Pemotongan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="mulai_pemotongan_hpa_time" name="mulai_pemotongan_hpa_time" value="<?= date('H:i', strtotime($pemotonganData['mulai_pemotongan_hpa'])) ?>">
                        <input type="date" class="form-control" id="mulai_pemotongan_hpa_date" name="mulai_pemotongan_hpa_date" value="<?= date('Y-m-d', strtotime($pemotonganData['mulai_pemotongan_hpa'])) ?>">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="selesai_pemotongan_hpa">Selesai Pemotongan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="selesai_pemotongan_hpa_time" name="selesai_pemotongan_hpa_time" value="<?= date('H:i', strtotime($pemotonganData['selesai_pemotongan_hpa'])) ?>">
                        <input type="date" class="form-control" id="selesai_pemotongan_hpa_date" name="selesai_pemotongan_hpa_date" value="<?= date('Y-m-d', strtotime($pemotonganData['selesai_pemotongan_hpa'])) ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
        </form>
    </div>
</div>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>