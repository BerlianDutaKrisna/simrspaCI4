<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Pembacaan</h6>
    </div>
    <div class="card-body">
        <h1 class="h4 text-gray-900 mb-4">Edit Data Pembacaan</h1>

        <a href="<?= base_url('exam/index_exam') ?>" class="btn btn-primary mb-3">Kembali</a>
        <form action="<?= base_url('pembacaan/update_pembacaan') ?>" method="POST">
            <input type="hidden" name="id_pembacaan" value="<?= $pembacaanData['id_pembacaan'] ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="id_user_pembacaan">User pembacaan:</label>
                    <select class="form-control" id="id_user_pembacaan" name="id_user_pembacaan">
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['status_user'] === 'Analis'): ?>
                                <option value="<?= $user['id_user'] ?>" <?= $user['id_user'] == $pembacaanData['id_user_pembacaan'] ? 'selected' : '' ?>>
                                    <?= $user['nama_user'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="status_pembacaan">Status pembacaan:</label>
                    <!-- Dropdown for status_pembacaan -->
                    <select name="status_pembacaan" id="status_pembacaan" class="form-control">
                        <option value="Belum Pembacaan" <?= old('status_pembacaan', esc($pembacaanData['status_pembacaan'])) == 'Belum Pembacaan' ? 'selected' : '' ?>>Belum Pembacaan</option>
                        <option value="Proses Pembacaan" <?= old('status_pembacaan', esc($pembacaanData['status_pembacaan'])) == 'Proses Pembacaan' ? 'selected' : '' ?>>Proses Pembacaan</option>
                        <option value="Selesai Pembacaan" <?= old('status_pembacaan', esc($pembacaanData['status_pembacaan'])) == 'Selesai Pembacaan' ? 'selected' : '' ?>>Selesai Pembacaan</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="mulai_pembacaan">Mulai pembacaan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="mulai_pembacaan_time" name="mulai_pembacaan_time" value="<?= date('H:i', strtotime($pembacaanData['mulai_pembacaan'])) ?>">
                        <input type="date" class="form-control" id="mulai_pembacaan_date" name="mulai_pembacaan_date" value="<?= date('Y-m-d', strtotime($pembacaanData['mulai_pembacaan'])) ?>">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="selesai_pembacaan">Selesai pembacaan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="selesai_pembacaan_time" name="selesai_pembacaan_time" value="<?= date('H:i', strtotime($pembacaanData['selesai_pembacaan'])) ?>">
                        <input type="date" class="form-control" id="selesai_pembacaan_date" name="selesai_pembacaan_date" value="<?= date('Y-m-d', strtotime($pembacaanData['selesai_pembacaan'])) ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</div>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>