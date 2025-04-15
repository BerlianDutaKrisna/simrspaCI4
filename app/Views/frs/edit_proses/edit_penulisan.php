<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Penulisan</h6>
    </div>
    <div class="card-body">
        <h1 class="h4 text-gray-900 mb-4">Edit Data Penulisan</h1>

        <a href="<?= base_url('frs/index') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>
        <form action="<?= base_url('penulisan_frs/update') ?>" method="POST">
            <input type="hidden" name="id_penulisan_frs" value="<?= $penulisanData['id_penulisan_frs'] ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="id_user_penulisan_frs">User Penulisan:</label>
                    <select class="form-control" id="id_user_penulisan_frs" name="id_user_penulisan_frs">
                        <option value="" <?= empty($penulisanData['id_user_penulisan_frs']) ? 'selected' : '' ?>>-</option>
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['status_user'] === 'Analis'): ?>
                                <option value="<?= $user['id_user'] ?>" <?= $user['id_user'] == $penulisanData['id_user_penulisan_frs'] ? 'selected' : '' ?>>
                                    <?= $user['nama_user'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="status_penulisan_frs">Status Penulisan:</label>
                    <!-- Dropdown for status_penulisan_frs -->
                    <select name="status_penulisan_frs" id="status_penulisan_frs" class="form-control">
                        <option value="Belum Penulisan" <?= old('status_penulisan_frs', esc($penulisanData['status_penulisan_frs'])) == 'Belum Penulisan' ? 'selected' : '' ?>>Belum Penulisan</option>
                        <option value="Proses Penulisan" <?= old('status_penulisan_frs', esc($penulisanData['status_penulisan_frs'])) == 'Proses Penulisan' ? 'selected' : '' ?>>Proses Penulisan</option>
                        <option value="Selesai Penulisan" <?= old('status_penulisan_frs', esc($penulisanData['status_penulisan_frs'])) == 'Selesai Penulisan' ? 'selected' : '' ?>>Selesai Penulisan</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="mulai_penulisan_frs">Mulai Penulisan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="mulai_penulisan_frs_time" name="mulai_penulisan_frs_time" value="<?= date('H:i', strtotime($penulisanData['mulai_penulisan_frs'])) ?>">
                        <input type="date" class="form-control" id="mulai_penulisan_frs_date" name="mulai_penulisan_frs_date" value="<?= date('Y-m-d', strtotime($penulisanData['mulai_penulisan_frs'])) ?>">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="selesai_penulisan_frs">Selesai Penulisan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="selesai_penulisan_frs_time" name="selesai_penulisan_frs_time" value="<?= date('H:i', strtotime($penulisanData['selesai_penulisan_frs'])) ?>">
                        <input type="date" class="form-control" id="selesai_penulisan_frs_date" name="selesai_penulisan_frs_date" value="<?= date('Y-m-d', strtotime($penulisanData['selesai_penulisan_frs'])) ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
        </form>
    </div>
</div>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>