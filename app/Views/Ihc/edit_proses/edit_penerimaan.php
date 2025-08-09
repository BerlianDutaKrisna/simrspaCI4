<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Penerimaan</h6>
    </div>
    <div class="card-body">
        <h1 class="h4 text-gray-900 mb-4">Edit Data Penerimaan</h1>

        <a href="<?= base_url('ihc/index') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>
        <form action="<?= base_url('penerimaan_ihc/update') ?>" method="POST">
            <input type="hidden" name="id_penerimaan_ihc" value="<?= $penerimaanData['id_penerimaan_ihc'] ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="id_user_penerimaan_ihc">User penerimaan:</label>
                    <select class="form-control" id="id_user_penerimaan_ihc" name="id_user_penerimaan_ihc">
                        <option value="" <?= empty($penerimaanData['id_user_penerimaan_ihc']) ? 'selected' : '' ?>>-</option>
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['status_user'] === 'Analis'): ?>
                                <option value="<?= $user['id_user'] ?>" <?= $user['id_user'] == $penerimaanData['id_user_penerimaan_ihc'] ? 'selected' : '' ?>>
                                    <?= $user['nama_user'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="status_penerimaan_ihc">Status penerimaan:</label>
                    <!-- Dropdown for status_penerimaan_ihc -->
                    <select name="status_penerimaan_ihc" id="status_penerimaan_ihc" class="form-control">
                        <option value="Belum Penerimaan" <?= old('status_penerimaan_ihc', esc($penerimaanData['status_penerimaan_ihc'])) == 'Belum Penerimaan' ? 'selected' : '' ?>>Belum Penerimaan</option>
                        <option value="Proses Penerimaan" <?= old('status_penerimaan_ihc', esc($penerimaanData['status_penerimaan_ihc'])) == 'Proses Penerimaan' ? 'selected' : '' ?>>Proses Penerimaan</option>
                        <option value="Selesai Penerimaan" <?= old('status_penerimaan_ihc', esc($penerimaanData['status_penerimaan_ihc'])) == 'Selesai Penerimaan' ? 'selected' : '' ?>>Selesai Penerimaan</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="mulai_penerimaan_ihc">Mulai penerimaan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="mulai_penerimaan_ihc_time" name="mulai_penerimaan_ihc_time" value="<?= date('H:i', strtotime($penerimaanData['mulai_penerimaan_ihc'])) ?>">
                        <input type="date" class="form-control" id="mulai_penerimaan_ihc_date" name="mulai_penerimaan_ihc_date" value="<?= date('Y-m-d', strtotime($penerimaanData['mulai_penerimaan_ihc'])) ?>">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="selesai_penerimaan_ihc">Selesai penerimaan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="selesai_penerimaan_ihc_time" name="selesai_penerimaan_ihc_time" value="<?= date('H:i', strtotime($penerimaanData['selesai_penerimaan_ihc'])) ?>">
                        <input type="date" class="form-control" id="selesai_penerimaan_ihc_date" name="selesai_penerimaan_ihc_date" value="<?= date('Y-m-d', strtotime($penerimaanData['selesai_penerimaan_ihc'])) ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
        </form>
    </div>
</div>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>