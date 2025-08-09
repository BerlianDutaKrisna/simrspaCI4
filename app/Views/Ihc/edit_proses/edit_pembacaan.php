<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Pembacaan</h6>
    </div>
    <div class="card-body">
        <h1 class="h4 text-gray-900 mb-4">Edit Data Pembacaan</h1>

        <a href="<?= base_url('ihc/index') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>
        <form action="<?= base_url('pembacaan_ihc/update') ?>" method="POST">
            <input type="hidden" name="id_pembacaan_ihc" value="<?= $pembacaanData['id_pembacaan_ihc'] ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="id_user_dokter_pembacaan_ihc">User Pembacaan:</label>
                    <select class="form-control" id="id_user_dokter_pembacaan_ihc" name="id_user_dokter_pembacaan_ihc">
                        <option value="" <?= empty($pembacaanData['id_user_dokter_pembacaan_ihc']) ? 'selected' : '' ?>>-</option>
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['status_user'] === 'Dokter'): ?>
                                <option value="<?= $user['id_user'] ?>" <?= $user['id_user'] == $pembacaanData['id_user_dokter_pembacaan_ihc'] ? 'selected' : '' ?>>
                                    <?= $user['nama_user'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="status_pembacaan_ihc">Status Pembacaan:</label>
                    <!-- Dropdown for status_pembacaan_ihc -->
                    <select name="status_pembacaan_ihc" id="status_pembacaan_ihc" class="form-control">
                        <option value="Belum Pembacaan" <?= old('status_pembacaan_ihc', esc($pembacaanData['status_pembacaan_ihc'])) == 'Belum Pembacaan' ? 'selected' : '' ?>>Belum Pembacaan</option>
                        <option value="Proses Pembacaan" <?= old('status_pembacaan_ihc', esc($pembacaanData['status_pembacaan_ihc'])) == 'Proses Pembacaan' ? 'selected' : '' ?>>Proses Pembacaan</option>
                        <option value="Selesai Pembacaan" <?= old('status_pembacaan_ihc', esc($pembacaanData['status_pembacaan_ihc'])) == 'Selesai Pembacaan' ? 'selected' : '' ?>>Selesai Pembacaan</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="mulai_pembacaan_ihc">Mulai Pembacaan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="mulai_pembacaan_ihc_time" name="mulai_pembacaan_ihc_time" value="<?= date('H:i', strtotime($pembacaanData['mulai_pembacaan_ihc'])) ?>">
                        <input type="date" class="form-control" id="mulai_pembacaan_ihc_date" name="mulai_pembacaan_ihc_date" value="<?= date('Y-m-d', strtotime($pembacaanData['mulai_pembacaan_ihc'])) ?>">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="selesai_pembacaan_ihc">Selesai Pembacaan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="selesai_pembacaan_ihc_time" name="selesai_pembacaan_ihc_time" value="<?= date('H:i', strtotime($pembacaanData['selesai_pembacaan_ihc'])) ?>">
                        <input type="date" class="form-control" id="selesai_pembacaan_ihc_date" name="selesai_pembacaan_ihc_date" value="<?= date('Y-m-d', strtotime($pembacaanData['selesai_pembacaan_ihc'])) ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
        </form>
    </div>
</div>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>