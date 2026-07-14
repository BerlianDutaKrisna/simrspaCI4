<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Pemprosesan</h6>
    </div>
    <div class="card-body">
        <h1 class="h4 text-gray-900 mb-4">Edit Data Pemprosesan</h1>

        <a href="<?= base_url('exam/index_exam') ?>" class="btn btn-primary mb-3">Kembali</a>
        <form action="<?= base_url('pemprosesan/update_pemprosesan') ?>" method="POST">
            <input type="hidden" name="id_pemprosesan" value="<?= $pemprosesanData['id_pemprosesan'] ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="id_user_pemprosesan">User pemprosesan:</label>
                    <select class="form-control" id="id_user_pemprosesan" name="id_user_pemprosesan">
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['status_user'] === 'Analis'): ?>
                                <option value="<?= $user['id_user'] ?>" <?= $user['id_user'] == $pemprosesanData['id_user_pemprosesan'] ? 'selected' : '' ?>>
                                    <?= $user['nama_user'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="status_pemprosesan">Status pemprosesan:</label>
                    <!-- Dropdown for status_pemprosesan -->
                    <select name="status_pemprosesan" id="status_pemprosesan" class="form-control">
                        <option value="Belum Pemprosesan" <?= old('status_pemprosesan', esc($pemprosesanData['status_pemprosesan'])) == 'Belum Pemprosesan' ? 'selected' : '' ?>>Belum Pemprosesan</option>
                        <option value="Proses Pemprosesan" <?= old('status_pemprosesan', esc($pemprosesanData['status_pemprosesan'])) == 'Proses Pemprosesan' ? 'selected' : '' ?>>Proses Pemprosesan</option>
                        <option value="Selesai Pemprosesan" <?= old('status_pemprosesan', esc($pemprosesanData['status_pemprosesan'])) == 'Selesai Pemprosesan' ? 'selected' : '' ?>>Selesai Pemprosesan</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="mulai_pemprosesan">Mulai pemprosesan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="mulai_pemprosesan_time" name="mulai_pemprosesan_time" value="<?= date('H:i', strtotime($pemprosesanData['mulai_pemprosesan'])) ?>">
                        <input type="date" class="form-control" id="mulai_pemprosesan_date" name="mulai_pemprosesan_date" value="<?= date('Y-m-d', strtotime($pemprosesanData['mulai_pemprosesan'])) ?>">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="selesai_pemprosesan">Selesai pemprosesan:</label>
                    <!-- Separate date and time inputs -->
                    <div class="input-group">
                        <input type="time" class="form-control" id="selesai_pemprosesan_time" name="selesai_pemprosesan_time" value="<?= date('H:i', strtotime($pemprosesanData['selesai_pemprosesan'])) ?>">
                        <input type="date" class="form-control" id="selesai_pemprosesan_date" name="selesai_pemprosesan_date" value="<?= date('Y-m-d', strtotime($pemprosesanData['selesai_pemprosesan'])) ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</div>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>