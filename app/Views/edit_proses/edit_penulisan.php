<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Penulisan</h6>
    </div>
    <div class="card-body">
        <h1 class="h4 text-gray-900 mb-4">Edit Data Penulisan</h1>

        <a href="<?= base_url('exam/index_exam') ?>" class="btn btn-primary mb-3">Kembali</a>
        <form action="<?= base_url('penulisan/update_penulisan') ?>" method="POST">
            <input type="hidden" name="id_penulisan" value="<?= $penulisanData['id_penulisan'] ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="id_user_penulisan">User penulisan:</label>
                    <select class="form-control" id="id_user_penulisan" name="id_user_penulisan">
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['status_user'] === 'Analis'): ?>
                                <option value="<?= $user['id_user'] ?>" <?= $user['id_user'] == $penulisanData['id_user_penulisan'] ? 'selected' : '' ?>>
                                    <?= $user['nama_user'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="status_penulisan">Status penulisan:</label>
                    <!-- Dropdown for status_penulisan -->
                    <select name="status_penulisan" id="status_penulisan" class="form-control">
                        <option value="Belum penulisan" <?= old('status_penulisan', esc($penulisanData['status_penulisan'])) == 'Belum penulisan' ? 'selected' : '' ?>>Belum penulisan</option>
                        <option value="Proses penulisan" <?= old('status_penulisan', esc($penulisanData['status_penulisan'])) == 'Proses penulisan' ? 'selected' : '' ?>>Proses penulisan</option>
                        <option value="Selesai penulisan" <?= old('status_penulisan', esc($penulisanData['status_penulisan'])) == 'Selesai penulisan' ? 'selected' : '' ?>>Selesai penulisan</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="mulai_penulisan">Mulai penulisan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="mulai_penulisan_time" name="mulai_penulisan_time" value="<?= date('H:i', strtotime($penulisanData['mulai_penulisan'])) ?>">
                        <input type="date" class="form-control" id="mulai_penulisan_date" name="mulai_penulisan_date" value="<?= date('Y-m-d', strtotime($penulisanData['mulai_penulisan'])) ?>">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="selesai_penulisan">Selesai penulisan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="selesai_penulisan_time" name="selesai_penulisan_time" value="<?= date('H:i', strtotime($penulisanData['selesai_penulisan'])) ?>">
                        <input type="date" class="form-control" id="selesai_penulisan_date" name="selesai_penulisan_date" value="<?= date('Y-m-d', strtotime($penulisanData['selesai_penulisan'])) ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</div>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>