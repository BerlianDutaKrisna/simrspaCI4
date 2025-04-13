<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Pemprosesan</h6>
    </div>
    <div class="card-body">
        <h1 class="h4 text-gray-900 mb-4">Edit Data Pemprosesan</h1>

        <a href="<?= base_url('hpa/index') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>
        <form action="<?= base_url('pemprosesan_hpa/update') ?>" method="POST">
            <input type="hidden" name="id_pemprosesan_hpa" value="<?= $pemprosesanData['id_pemprosesan_hpa'] ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="id_user_pemprosesan_hpa">User Pemprosesan:</label>
                    <select class="form-control" id="id_user_pemprosesan_hpa" name="id_user_pemprosesan_hpa">
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['status_user'] === 'Analis'): ?>
                                <option value="<?= $user['id_user'] ?>" <?= $user['id_user'] == $pemprosesanData['id_user_pemprosesan_hpa'] ? 'selected' : '' ?>>
                                    <?= $user['nama_user'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="status_pemprosesan_hpa">Status Pemprosesan:</label>
                    <!-- Dropdown for status_pemprosesan_hpa -->
                    <select name="status_pemprosesan_hpa" id="status_pemprosesan_hpa" class="form-control">
                        <option value="Belum Pemprosesan" <?= old('status_pemprosesan_hpa', esc($pemprosesanData['status_pemprosesan_hpa'])) == 'Belum Pemprosesan' ? 'selected' : '' ?>>Belum Pemprosesan</option>
                        <option value="Proses Pemprosesan" <?= old('status_pemprosesan_hpa', esc($pemprosesanData['status_pemprosesan_hpa'])) == 'Proses Pemprosesan' ? 'selected' : '' ?>>Proses Pemprosesan</option>
                        <option value="Selesai Pemprosesan" <?= old('status_pemprosesan_hpa', esc($pemprosesanData['status_pemprosesan_hpa'])) == 'Selesai Pemprosesan' ? 'selected' : '' ?>>Selesai Pemprosesan</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="mulai_pemprosesan_hpa">Mulai Pemprosesan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="mulai_pemprosesan_hpa_time" name="mulai_pemprosesan_hpa_time" value="<?= date('H:i', strtotime($pemprosesanData['mulai_pemprosesan_hpa'])) ?>">
                        <input type="date" class="form-control" id="mulai_pemprosesan_hpa_date" name="mulai_pemprosesan_hpa_date" value="<?= date('Y-m-d', strtotime($pemprosesanData['mulai_pemprosesan_hpa'])) ?>">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="selesai_pemprosesan_hpa">Selesai Pemprosesan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="selesai_pemprosesan_hpa_time" name="selesai_pemprosesan_hpa_time" value="<?= date('H:i', strtotime($pemprosesanData['selesai_pemprosesan_hpa'])) ?>">
                        <input type="date" class="form-control" id="selesai_pemprosesan_hpa_date" name="selesai_pemprosesan_hpa_date" value="<?= date('Y-m-d', strtotime($pemprosesanData['selesai_pemprosesan_hpa'])) ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
        </form>
    </div>
</div>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>