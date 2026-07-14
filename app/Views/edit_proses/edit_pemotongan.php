<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Pemotongan</h6>
    </div>
    <div class="card-body">
        <h1 class="h4 text-gray-900 mb-4">Edit Data Pemotongan</h1>

        <a href="<?= base_url('exam/index_exam') ?>" class="btn btn-primary mb-3">Kembali</a>
        <form action="<?= base_url('pemotongan/update_pemotongan') ?>" method="POST">
            <input type="hidden" name="id_pemotongan" value="<?= $pemotonganData['id_pemotongan'] ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="id_user_pemotongan">User pemotongan:</label>
                    <select class="form-control" id="id_user_pemotongan" name="id_user_pemotongan">
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['status_user'] === 'Analis'): ?>
                                <option value="<?= $user['id_user'] ?>" <?= $user['id_user'] == $pemotonganData['id_user_pemotongan'] ? 'selected' : '' ?>>
                                    <?= $user['nama_user'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="status_pemotongan">Status pemotongan:</label>
                    <!-- Dropdown for status_pemotongan -->
                    <select name="status_pemotongan" id="status_pemotongan" class="form-control">
                        <option value="Belum Pemotongan" <?= old('status_pemotongan', esc($pemotonganData['status_pemotongan'])) == 'Belum Pemotongan' ? 'selected' : '' ?>>Belum Pemotongan</option>
                        <option value="Proses Pemotongan" <?= old('status_pemotongan', esc($pemotonganData['status_pemotongan'])) == 'Proses Pemotongan' ? 'selected' : '' ?>>Proses Pemotongan</option>
                        <option value="Selesai Pemotongan" <?= old('status_pemotongan', esc($pemotonganData['status_pemotongan'])) == 'Selesai Pemotongan' ? 'selected' : '' ?>>Selesai Pemotongan</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="mulai_pemotongan">Mulai pemotongan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="mulai_pemotongan_time" name="mulai_pemotongan_time" value="<?= date('H:i', strtotime($pemotonganData['mulai_pemotongan'])) ?>">
                        <input type="date" class="form-control" id="mulai_pemotongan_date" name="mulai_pemotongan_date" value="<?= date('Y-m-d', strtotime($pemotonganData['mulai_pemotongan'])) ?>">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="selesai_pemotongan">Selesai pemotongan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="selesai_pemotongan_time" name="selesai_pemotongan_time" value="<?= date('H:i', strtotime($pemotonganData['selesai_pemotongan'])) ?>">
                        <input type="date" class="form-control" id="selesai_pemotongan_date" name="selesai_pemotongan_date" value="<?= date('Y-m-d', strtotime($pemotonganData['selesai_pemotongan'])) ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</div>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>