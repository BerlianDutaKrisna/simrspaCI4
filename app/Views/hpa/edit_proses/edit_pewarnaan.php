<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Pewarnaan</h6>
    </div>
    <div class="card-body">
        <h1 class="h4 text-gray-900 mb-4">Edit Data Pewarnaan</h1>

        <a href="<?= base_url('hpa/index') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>
        <form action="<?= base_url('pewarnaan_hpa/update') ?>" method="POST">
            <input type="hidden" name="id_pewarnaan_hpa" value="<?= $pewarnaanData['id_pewarnaan_hpa'] ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="id_user_pewarnaan_hpa">User Pewarnaan:</label>
                    <select class="form-control" id="id_user_pewarnaan_hpa" name="id_user_pewarnaan_hpa">
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['status_user'] === 'Analis'): ?>
                                <option value="<?= $user['id_user'] ?>" <?= $user['id_user'] == $pewarnaanData['id_user_pewarnaan_hpa'] ? 'selected' : '' ?>>
                                    <?= $user['nama_user'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="status_pewarnaan_hpa">Status Pewarnaan:</label>
                    <!-- Dropdown for status_pewarnaan_hpa -->
                    <select name="status_pewarnaan_hpa" id="status_pewarnaan_hpa" class="form-control">
                        <option value="Belum Pewarnaan" <?= old('status_pewarnaan_hpa', esc($pewarnaanData['status_pewarnaan_hpa'])) == 'Belum Pewarnaan' ? 'selected' : '' ?>>Belum Pewarnaan</option>
                        <option value="Proses Pewarnaan" <?= old('status_pewarnaan_hpa', esc($pewarnaanData['status_pewarnaan_hpa'])) == 'Proses Pewarnaan' ? 'selected' : '' ?>>Proses Pewarnaan</option>
                        <option value="Selesai Pewarnaan" <?= old('status_pewarnaan_hpa', esc($pewarnaanData['status_pewarnaan_hpa'])) == 'Selesai Pewarnaan' ? 'selected' : '' ?>>Selesai Pewarnaan</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="mulai_pewarnaan_hpa">Mulai Pewarnaan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="mulai_pewarnaan_hpa_time" name="mulai_pewarnaan_hpa_time" value="<?= date('H:i', strtotime($pewarnaanData['mulai_pewarnaan_hpa'])) ?>">
                        <input type="date" class="form-control" id="mulai_pewarnaan_hpa_date" name="mulai_pewarnaan_hpa_date" value="<?= date('Y-m-d', strtotime($pewarnaanData['mulai_pewarnaan_hpa'])) ?>">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="selesai_pewarnaan_hpa">Selesai Pewarnaan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="selesai_pewarnaan_hpa_time" name="selesai_pewarnaan_hpa_time" value="<?= date('H:i', strtotime($pewarnaanData['selesai_pewarnaan_hpa'])) ?>">
                        <input type="date" class="form-control" id="selesai_pewarnaan_hpa_date" name="selesai_pewarnaan_hpa_date" value="<?= date('Y-m-d', strtotime($pewarnaanData['selesai_pewarnaan_hpa'])) ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
        </form>
    </div>
</div>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>