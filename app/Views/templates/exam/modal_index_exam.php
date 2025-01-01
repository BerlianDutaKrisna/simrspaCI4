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
            <form action="<?= base_url('exam/update_status_hpa') ?>/<?= esc($row['id_hpa']) ?>" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="id_hpa" name="id_hpa" value="">
                    <div class="form-group">
                        <label for="status_hpa">Status Hpa</label>
                        <select class="form-control" id="status_hpa" name="status_hpa">
                            <option value="Belum Diproses" <?= old('status_hpa', esc($row['status_hpa'])) == 'Belum Diproses' ? 'selected' : '' ?>>Belum Diproses</option>
                            <option value="Terdaftar" <?= old('status_hpa', esc($row['status_hpa'])) == 'Terdaftar' ? 'selected' : '' ?>>Terdaftar</option>
                            <option value="Penerimaan" <?= old('status_hpa', esc($row['status_hpa'])) == 'Penerimaan' ? 'selected' : '' ?>>Penerimaan</option>
                            <option value="Pengirisan" <?= old('status_hpa', esc($row['status_hpa'])) == 'Pengirisan' ? 'selected' : '' ?>>Pengirisan</option>
                            <option value="Pemotongan" <?= old('status_hpa', esc($row['status_hpa'])) == 'Pemotongan' ? 'selected' : '' ?>>Pemotongan</option>
                            <option value="Pemprosesan" <?= old('status_hpa', esc($row['status_hpa'])) == 'Pemprosesan' ? 'selected' : '' ?>>Pemprosesan</option>
                            <option value="Penanaman" <?= old('status_hpa', esc($row['status_hpa'])) == 'Penanaman' ? 'selected' : '' ?>>Penanaman</option>
                            <option value="Pemotongan Tipis" <?= old('status_hpa', esc($row['status_hpa'])) == 'Pemotongan Tipis' ? 'selected' : '' ?>>Terdaftar</option>
                            <option value="Pewarnaan" <?= old('status_hpa', esc($row['status_hpa'])) == 'Pewarnaan' ? 'selected' : '' ?>>Pewarnaan</option>
                            <option value="Pembacaan" <?= old('status_hpa', esc($row['status_hpa'])) == 'Pembacaan' ? 'selected' : '' ?>>Pembacaan</option>
                            <option value="Penulisan" <?= old('status_hpa', esc($row['status_hpa'])) == 'Penulisan' ? 'selected' : '' ?>>Penulisan</option>
                            <option value="Pemverifikasi" <?= old('status_hpa', esc($row['status_hpa'])) == 'Pemverifikasi' ? 'selected' : '' ?>>Pemverifikasi</option>
                            <option value="Pencetakan" <?= old('status_hpa', esc($row['status_hpa'])) == 'Pencetakan' ? 'selected' : '' ?>>Pencetakan</option>
                            <option value="Selesai Diproses" <?= old('status_hpa', esc($row['status_hpa'])) == 'Selesai Diproses' ? 'selected' : '' ?>>Selesai Diproses</option>
                        </select>
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