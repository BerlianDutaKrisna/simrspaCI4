<!-- Modal Penerima -->
<div class="modal fade" id="penerimaModal" tabindex="-1" role="dialog" aria-labelledby="penerimaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="penerimaModalLabel">Penerima Hasil HPA</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="<?= base_url('exam/update_buku_penerima') ?>" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="id_hpa" name="id_hpa" value="">
                    <div class="form-group">
                        <label for="penerima_hpa">Nama Penerima / Hubungan dengan Pasien</label>
                        <input type="text" class="form-control" id="penerima_hpa" name="penerima_hpa" required>
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

<!-- Modal Status Hpa -->
<div class="modal fade" id="statusHpaModal" tabindex="-1" role="dialog" aria-labelledby="statusHpaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusHpaModalLabel">Edit Status Hpa</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="<?= base_url('exam/update_status_hpa') ?>" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="id_hpa" name="id_hpa" value="">
                    <div class="form-group">
                        <label for="status_hpa">Status Hpa</label>
                        <select class="form-control" id="status_hpa" name="status_hpa">
                            <option value="Belum Diproses" <?= old('status_hpa') == 'Belum Diproses' ? 'selected' : '' ?>>Belum Diproses</option>
                            <option value="Terdaftar" <?= old('status_hpa') == 'Terdaftar' ? 'selected' : '' ?>>Terdaftar</option>
                            <option value="Penerimaan" <?= old('status_hpa') == 'Penerimaan' ? 'selected' : '' ?>>Penerimaan</option>
                            <option value="Pengirisan" <?= old('status_hpa') == 'Pengirisan' ? 'selected' : '' ?>>Pengirisan</option>
                            <option value="Pemotongan" <?= old('status_hpa') == 'Pemotongan' ? 'selected' : '' ?>>Pemotongan</option>
                            <option value="Pemprosesan" <?= old('status_hpa') == 'Pemprosesan' ? 'selected' : '' ?>>Pemprosesan</option>
                            <option value="Penanaman" <?= old('status_hpa') == 'Penanaman' ? 'selected' : '' ?>>Penanaman</option>
                            <option value="Pemotongan Tipis" <?= old('status_hpa') == 'Pemotongan Tipis' ? 'selected' : '' ?>>Pemotongan Tipis</option>
                            <option value="Pewarnaan" <?= old('status_hpa') == 'Pewarnaan' ? 'selected' : '' ?>>Pewarnaan</option>
                            <option value="Pembacaan" <?= old('status_hpa') == 'Pembacaan' ? 'selected' : '' ?>>Pembacaan</option>
                            <option value="Penulisan" <?= old('status_hpa') == 'Penulisan' ? 'selected' : '' ?>>Penulisan</option>
                            <option value="Pemverifikasi" <?= old('status_hpa') == 'Pemverifikasi' ? 'selected' : '' ?>>Pemverifikasi</option>
                            <option value="Authorized" <?= old('status_hpa') == 'Authorized' ? 'selected' : '' ?>>Authorized</option>
                            <option value="Pencetakan" <?= old('status_hpa') == 'Pencetakan' ? 'selected' : '' ?>>Pencetakan</option>
                            <option value="Selesai Diproses" <?= old('status_hpa') == 'Selesai Diproses' ? 'selected' : '' ?>>Selesai Diproses</option>
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