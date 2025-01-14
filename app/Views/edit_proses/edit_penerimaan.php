<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Penerimaan</h6>
    </div>
    <div class="card-body">
        <h1 class="h4 text-gray-900 mb-4">Edit Data Penerimaan</h1>

        <a href="<?= base_url('exam/index_exam') ?>" class="btn btn-primary mb-3">Kembali</a>
        <form action="<?= base_url('penerimaan/update_penerimaan') ?>" method="POST">
            <input type="hidden" name="id_penerimaan" value="<?= $penerimaanData['id_penerimaan'] ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="id_user_penerimaan">User penerimaan:</label>
                    <select class="form-control" id="id_user_penerimaan" name="id_user_penerimaan">
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['status_user'] === 'Analis'): ?>
                                <option value="<?= $user['id_user'] ?>" <?= $user['id_user'] == $penerimaanData['id_user_penerimaan'] ? 'selected' : '' ?>>
                                    <?= $user['nama_user'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="status_penerimaan">Status penerimaan:</label>
                    <!-- Dropdown for status_penerimaan -->
                    <select name="status_penerimaan" id="status_penerimaan" class="form-control">
                        <option value="Belum Pemeriksaan" <?= old('status_penerimaan', esc($penerimaanData['status_penerimaan'])) == 'Belum Pemeriksaan' ? 'selected' : '' ?>>Belum Pemeriksaan</option>
                        <option value="Proses Pemeriksaan" <?= old('status_penerimaan', esc($penerimaanData['status_penerimaan'])) == 'Proses Pemeriksaan' ? 'selected' : '' ?>>Proses Pemeriksaan</option>
                        <option value="Selesai Pemeriksaan" <?= old('status_penerimaan', esc($penerimaanData['status_penerimaan'])) == 'Selesai Pemeriksaan' ? 'selected' : '' ?>>Selesai Pemeriksaan</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="mulai_penerimaan">Mulai penerimaan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="mulai_penerimaan_time" name="mulai_penerimaan_time" value="<?= date('H:i', strtotime($penerimaanData['mulai_penerimaan'])) ?>">
                        <input type="date" class="form-control" id="mulai_penerimaan_date" name="mulai_penerimaan_date" value="<?= date('Y-m-d', strtotime($penerimaanData['mulai_penerimaan'])) ?>">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="selesai_penerimaan">Selesai penerimaan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="selesai_penerimaan_time" name="selesai_penerimaan_time" value="<?= date('H:i', strtotime($penerimaanData['selesai_penerimaan'])) ?>">
                        <input type="date" class="form-control" id="selesai_penerimaan_date" name="selesai_penerimaan_date" value="<?= date('Y-m-d', strtotime($penerimaanData['selesai_penerimaan'])) ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</div>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>