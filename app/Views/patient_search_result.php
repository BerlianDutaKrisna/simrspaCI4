<!-- Modal untuk Menampilkan Hasil -->
<div class="modal fade <?= isset($patient) || isset($error) ? 'show d-block' : '' ?>" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resultModalLabel">Hasil Pencarian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="window.location.href='<?= base_url() ?>'">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if (isset($patient)): ?>
                    <p><strong>NoRM:</strong> <?= $patient['norm_pasien'] ?></p>
                    <p><strong>Nama:</strong> <?= $patient['nama_pasien'] ?></p>
                    <p><strong>Alamat:</strong> <?= $patient['alamat_pasien'] ?></p>
                    <p><strong>Tanggal Lahir:</strong> <?= $patient['tanggal_lahir_pasien'] ?></p>
                    <p><strong>Jenis Kelamin:</strong> <?= $patient['jenis_kelamin_pasien'] ?></p>
                    <p><strong>Status:</strong> <?= $patient['status_pasien'] ?></p>
                <?php elseif (isset($error)): ?>
                    <p><?= $error ?></p>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='<?= base_url() ?>'">Tutup</button>
            </div>
        </div>
    </div>
</div>