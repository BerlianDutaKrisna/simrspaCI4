<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Pemotongan Tipis</h6>
    </div>
    <div class="card-body">
        <h1 class="h4 text-gray-900 mb-4">Edit Data Pemotongan Tipis</h1>

        <a href="<?= base_url('hpa/index') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>
        <form action="<?= base_url('pemotongan_tipis_hpa/update') ?>" method="POST">
            <input type="hidden" name="id_pemotongan_tipis_hpa" value="<?= $pemotongan_tipisData['id_pemotongan_tipis_hpa'] ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="id_user_pemotongan_tipis_hpa">User Pemotongan Tipis:</label>
                    <select class="form-control" id="id_user_pemotongan_tipis_hpa" name="id_user_pemotongan_tipis_hpa">
                        <option value="" <?= empty($pemotongan_tipisData['id_user_pemotongan_tipis_hpa']) ? 'selected' : '' ?>>-</option>
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['status_user'] === 'Analis'): ?>
                                <option value="<?= $user['id_user'] ?>" <?= $user['id_user'] == $pemotongan_tipisData['id_user_pemotongan_tipis_hpa'] ? 'selected' : '' ?>>
                                    <?= $user['nama_user'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="status_pemotongan_tipis_hpa">Status Pemotongan Tipis:</label>
                    <!-- Dropdown for status_pemotongan_tipis_hpa -->
                    <select name="status_pemotongan_tipis_hpa" id="status_pemotongan_tipis_hpa" class="form-control">
                        <option value="Belum Pemotongan Tipis" <?= old('status_pemotongan_tipis_hpa', esc($pemotongan_tipisData['status_pemotongan_tipis_hpa'])) == 'Belum Pemotongan Tipis' ? 'selected' : '' ?>>Belum Pemotongan Tipis</option>
                        <option value="Proses Pemotongan Tipis" <?= old('status_pemotongan_tipis_hpa', esc($pemotongan_tipisData['status_pemotongan_tipis_hpa'])) == 'Proses Pemotongan Tipis' ? 'selected' : '' ?>>Proses Pemotongan Tipis</option>
                        <option value="Selesai Pemotongan Tipis" <?= old('status_pemotongan_tipis_hpa', esc($pemotongan_tipisData['status_pemotongan_tipis_hpa'])) == 'Selesai Pemotongan Tipis' ? 'selected' : '' ?>>Selesai Pemotongan Tipis</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="mulai_pemotongan_tipis_hpa">Mulai Pemotongan Tipis:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="mulai_pemotongan_tipis_hpa_time" name="mulai_pemotongan_tipis_hpa_time" value="<?= date('H:i', strtotime($pemotongan_tipisData['mulai_pemotongan_tipis_hpa'])) ?>">
                        <input type="date" class="form-control" id="mulai_pemotongan_tipis_hpa_date" name="mulai_pemotongan_tipis_hpa_date" value="<?= date('Y-m-d', strtotime($pemotongan_tipisData['mulai_pemotongan_tipis_hpa'])) ?>">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="selesai_pemotongan_tipis_hpa">Selesai Pemotongan Tipis:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="selesai_pemotongan_tipis_hpa_time" name="selesai_pemotongan_tipis_hpa_time" value="<?= date('H:i', strtotime($pemotongan_tipisData['selesai_pemotongan_tipis_hpa'])) ?>">
                        <input type="date" class="form-control" id="selesai_pemotongan_tipis_hpa_date" name="selesai_pemotongan_tipis_hpa_date" value="<?= date('Y-m-d', strtotime($pemotongan_tipisData['selesai_pemotongan_tipis_hpa'])) ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
        </form>
    </div>
</div>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>