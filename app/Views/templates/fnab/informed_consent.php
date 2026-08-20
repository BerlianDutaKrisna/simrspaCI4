<!-- Informed Consent -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Informed Consent Tindakan FNAB</h6>
    </div>
    <div class="card-body">
        <?= csrf_field(); ?>
        <input type="hidden" name="idtransaksi" value="<?= isset($frs['id_transaksi']) ? (int) $frs['id_transaksi'] : '' ?>">
        <input type="hidden" name="tanggal" value="<?= !empty($frs['tanggal_transaksi']) ? esc($frs['tanggal_transaksi']) : '' ?>">
        <input type="hidden" name="register" value="<?= isset($frs['no_register']) ? esc($frs['no_register']) : '' ?>">

        <input type="hidden" name="norm_pasien" value="<?= esc($frs['norm_pasien'] ?? ''); ?>">
        <input type="hidden" name="nama_pasien" value="<?= esc($frs['nama_pasien'] ?? ''); ?>">
        <input type="hidden" name="tanggal_lahir_pasien" value="<?= esc($frs['tanggal_lahir_pasien'] ?? ''); ?>">
        <input type="hidden" name="jenis_kelamin_pasien" value="<?= esc($frs['jenis_kelamin_pasien'] ?? ''); ?>">
        <input type="hidden" name="alamat_pasien" value="<?= esc($frs['alamat_pasien'] ?? ''); ?>">

        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="dokter_pemeriksa">Dokter Pemeriksa</label>
                <select class="form-control" id="dokter_pemeriksa" name="dokter_pemeriksa">
                    <option value="____________________"
                        <?= empty($frs['id_user_dokter_penerimaan_frs']) ? 'selected' : '' ?>>
                        -- Pilih Dokter --
                    </option>
                    <option value="1"
                        <?= ($frs['id_user_dokter_penerimaan_frs'] ?? '') === "1" ? 'selected' : '' ?>>
                        dr. Vinna Chrisdianti, Sp.PA
                    </option>
                    <option value="2"
                        <?= ($frs['id_user_dokter_penerimaan_frs'] ?? '') === "2" ? 'selected' : '' ?>>
                        dr. Ayu Tyasmara Pratiwi, Sp.PA
                    </option>
                </select>
            </div>

            <div class="form-group col-md-3">
                <label for="nama_hubungan_pasien">Nama Hubungan Pasien</label>
                <select class="form-control" id="nama_hubungan_pasien" name="nama_hubungan_pasien" onchange="toggleSearchValue()">
                    <option value="____________________">-- Pilih Penandatangan --</option>
                    <option value="<?= esc($frs['nama_pasien'] ?? '') ?>">Pasien</option>
                    <option value="lainnya">Lainnya</option>
                </select>
                <input type="text" class="form-control mt-2 d-none" id="nama_lainnya" name="nama_lainnya" placeholder="Masukkan Nama Lainnya">
            </div>

            <div class="form-group col-md-3">
                <label for="hubungan_dengan_pasien">Hubungan dengan Pasien</label>
                <select class="form-control" id="hubungan_dengan_pasien" name="hubungan_dengan_pasien">
                    <option value="____________________">-- Pilih Hubungan --</option>
                    <option value="Pasien">Pasien</option>
                    <option value="Orang tua">Orang Tua</option>
                    <option value="Anak">Anak</option>
                    <option value="Istri">Istri</option>
                    <option value="Suami">Suami</option>
                    <option value="Saudara">Saudara</option>
                    <option value="Wali">Wali</option>
                    <option value="Pengantar">Pengantar</option>
                </select>
            </div>

            <div class="form-group col-md-3">
                <label for="jenis_kelamin_hubungan_pasien">Jenis Kelamin</label>
                <select class="form-control" id="jenis_kelamin_hubungan_pasien" name="jenis_kelamin_hubungan_pasien">
                    <option value="____________________">-- Pilih Jenis Kelamin --</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>

            <div class="form-group col-md-3">
                <label for="analis_priksa">Analis Pemeriksa</label>
                <select class="form-control" id="analis_priksa" name="analis_priksa">
                    <option value="____________________"
                        <?= empty($frs['id_user_penerimaan_frs']) ? 'selected' : '' ?>>
                        -- Pilih Analis --
                    </option>
                    <option value="3"
                        <?= ($frs['id_user_penerimaan_frs'] ?? '') === "3" ? 'selected' : '' ?>>
                        Endar Pratiwi, S.Si
                    </option>
                    <option value="4"
                        <?= ($frs['id_user_penerimaan_frs'] ?? '') === "4" ? 'selected' : '' ?>>
                        Arlina Kartika, A.Md.AK
                    </option>
                    <option value="5"
                        <?= ($frs['id_user_penerimaan_frs'] ?? '') === "5" ? 'selected' : '' ?>>
                        Ilham Tyas Ismadi, A.Md.Kes
                    </option>
                    <option value="6"
                        <?= ($frs['id_user_penerimaan_frs'] ?? '') === "6" ? 'selected' : '' ?>>
                        Berlian Duta Krisna, S.Tr.Kes
                    </option>
                </select>
            </div>

            <div class="form-group col-md-3">
                <label for="usia_hubungan_pasien">Usia Hubungan Pasien</label>
                <input type="number" class="form-control" id="usia_hubungan_pasien" name="usia_hubungan_pasien" value="">
            </div>

            <div class="form-group col-md-3">
                <label for="signature"> Tanda tangan digital Pasien</label>

                <button type="button"
                    class="btn btn-primary btn-user w-100 w-md-auto mb-2"
                    onclick="openSignatureModal()">
                    <i class="fas fa-signature"></i> Tanda tangan digital Pasien
                </button>
            </div>
            <div class="form-group col-md-3">
                <label for="signature" class="font-weight-bold">Tampilan digital Pasien</label>
                <!-- Preview Signature -->
                <div class="border rounded text-center p-3 d-flex flex-column justify-content-center align-items-center"
                    style="border: 2px dashed #4e73df !important; min-height: 120px; background-color: #f8faff !important; box-shadow: inset 0 1px 3px rgba(0,0,0,0.04);">
                    <img id="signaturePreview"
                        src=""
                        alt="Preview Tanda Tangan"
                        style="max-width:100%; max-height:100px; display:none; object-fit:contain;">
                    <small id="noSignatureText" class="text-muted">
                        <i class="fas fa-signature mr-1 text-secondary"></i>Belum ada tanda tangan
                    </small>
                </div>

                <!-- Hidden input untuk simpan base64 -->
                <input type="hidden" name="concentSignaturePasien" id="concentSignaturePasien">
            </div>
        </div>
    </div>
</div>
