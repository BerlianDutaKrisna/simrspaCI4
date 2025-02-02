<!-- Modal untuk Menampilkan Pesan Error atau Success -->
<?php if (session()->getFlashdata('error') || session()->getFlashdata('success')): ?>
    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel">Notifikasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                    $errors = session()->getFlashdata('error');
                    if (!empty($errors)):
                    ?>
                        <?php if (is_array($errors)): ?>
                            <?php foreach ($errors as $error): ?>
                                <div class="alert alert-danger"><?= esc($error); ?></div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-danger"><?= esc($errors); ?></div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if ($success = session()->getFlashdata('success')): ?>
                        <div class="alert alert-success"><?= esc($success); ?></div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    // Menunggu hingga halaman selesai dimuat
    window.onload = function() {
        // Menampilkan modal jika ada pesan flash
        <?php if (session()->getFlashdata('error') || session()->getFlashdata('success')): ?>
            $('#messageModal').modal('show');
        <?php endif; ?>
    };
</script>