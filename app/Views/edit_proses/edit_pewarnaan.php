<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Pewarnaan</h6>
    </div>
    <div class="card-body">
        <h1 class="h4 text-gray-900 mb-4">Edit Data Pewarnaan</h1>

        <a href="<?= base_url('exam/index_exam') ?>" class="btn btn-primary mb-3">Kembali</a>
        <form action="<?= base_url('pewarnaan/update_pewarnaan') ?>" method="POST">
            <input type="hidden" name="id_pewarnaan" value="<?= $pewarnaanData['id_pewarnaan'] ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="id_user_pewarnaan">User pewarnaan:</label>
                    <select class="form-control" id="id_user_pewarnaan" name="id_user_pewarnaan">
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['status_user'] === 'Analis'): ?>
                                <option value="<?= $user['id_user'] ?>" <?= $user['id_user'] == $pewarnaanData['id_user_pewarnaan'] ? 'selected' : '' ?>>
                                    <?= $user['nama_user'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="status_pewarnaan">Status pewarnaan:</label>
                    <!-- Dropdown for status_pewarnaan -->
                    <select name="status_pewarnaan" id="status_pewarnaan" class="form-control">
                        <option value="Belum Pewarnaan" <?= old('status_pewarnaan', esc($pewarnaanData['status_pewarnaan'])) == 'Belum Pewarnaan' ? 'selected' : '' ?>>Belum Pewarnaan</option>
                        <option value="Proses Pewarnaan" <?= old('status_pewarnaan', esc($pewarnaanData['status_pewarnaan'])) == 'Proses Pewarnaan' ? 'selected' : '' ?>>Proses Pewarnaan</option>
                        <option value="Selesai Pewarnaan" <?= old('status_pewarnaan', esc($pewarnaanData['status_pewarnaan'])) == 'Selesai Pewarnaan' ? 'selected' : '' ?>>Selesai Pewarnaan</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="mulai_pewarnaan">Mulai pewarnaan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="mulai_pewarnaan_time" name="mulai_pewarnaan_time" value="<?= date('H:i', strtotime($pewarnaanData['mulai_pewarnaan'])) ?>">
                        <input type="date" class="form-control" id="mulai_pewarnaan_date" name="mulai_pewarnaan_date" value="<?= date('Y-m-d', strtotime($pewarnaanData['mulai_pewarnaan'])) ?>">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="selesai_pewarnaan">Selesai pewarnaan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="selesai_pewarnaan_time" name="selesai_pewarnaan_time" value="<?= date('H:i', strtotime($pewarnaanData['selesai_pewarnaan'])) ?>">
                        <input type="date" class="form-control" id="selesai_pewarnaan_date" name="selesai_pewarnaan_date" value="<?= date('Y-m-d', strtotime($pewarnaanData['selesai_pewarnaan'])) ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</div>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>