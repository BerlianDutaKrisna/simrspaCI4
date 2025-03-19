<!-- Modal Penerima -->
<div class="modal fade" id="penerimaModal" tabindex="-1" role="dialog" aria-labelledby="penerimaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="penerimaModalLabel">Penerima Hasil frs</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="<?= base_url('frs/update_buku_penerima') ?>" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="id_frs" name="id_frs" value="">
                    <div class="form-group">
                        <label for="penerima_frs">Nama Penerima / Hubungan dengan Pasien</label>
                        <input type="text" class="form-control" id="penerima_frs" name="penerima_frs" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Status frs -->
<div class="modal fade" id="statusfrsModal" tabindex="-1" role="dialog" aria-labelledby="statusfrsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusfrsModalLabel">Edit Status frs</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="<?= base_url('frs/update_status') ?>" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="id_frs" name="id_frs" value="">
                    <div class="form-group">
                        <label for="status_frs">Status frs</label>
                        <select class="form-control" id="status_frs" name="status_frs">
                            <option value="Penerimaan" <?= old('status_frs') == 'Penerimaan' ? 'selected' : '' ?>>Penerimaan</option>
                            <option value="Pembacaan" <?= old('status_frs') == 'Pembacaan' ? 'selected' : '' ?>>Pembacaan</option>
                            <option value="Penulisan" <?= old('status_frs') == 'Penulisan' ? 'selected' : '' ?>>Penulisan</option>
                            <option value="Pemverifikasi" <?= old('status_frs') == 'Pemverifikasi' ? 'selected' : '' ?>>Pemverifikasi</option>
                            <option value="Authorized" <?= old('status_frs') == 'Authorized' ? 'selected' : '' ?>>Authorized</option>
                            <option value="Pencetakan" <?= old('status_frs') == 'Pencetakan' ? 'selected' : '' ?>>Pencetakan</option>
                            <option value="Selesai" <?= old('status_frs') == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal untuk menampilkan detail -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">Detail Proses</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="modalBody">
                    <!-- Data akan dimuat di sini melalui AJAX -->
                </div>
            </div>
            <div class="modal-footer" id="modalFooter">
                <!-- Footer content dynamically inserted -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
            </div>
        </div>
    </div>
</div>