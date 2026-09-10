<button class="btn btn-outline-primary mb-3 ml-2" type="button" data-toggle="collapse" data-target="#permintaanSimrs" aria-expanded="false" aria-controls="permintaanSimrs">
    <i class="fas fa-list-alt"></i> Permintaan SIMRS
</button>

<div class="collapse mb-4" id="permintaanSimrs">
    <div class="card card-body border-dark">
        <div id="permintaanContent" class="container-fluid bg-white p-4 border rounded">
            <div class="row border-bottom pb-2 mb-3 text-center align-items-center">
                <div class="col-12">
                    <h6 class="font-weight-bold mb-0">PEMERINTAH KOTA SURABAYA</h6>
                    <h5 class="font-weight-bold mb-0">RUMAH SAKIT UMUM DAERAH dr. MOHAMAD SOEWANDHIE</h5>
                    <small>Jl. Tambak Rejo No. 45-47 - Telp. 031 3717141, 3725905</small>
                </div>
            </div>

            <div class="text-center mb-4">
                <h5 class="font-weight-bold text-uppercase text-decoration-underline">PERMINTAAN PEMERIKSAAN PATOLOGI ANATOMI</h5>
            </div>

            <?php
            // Data sudah dinormalisasi penuh oleh Model (normalizePermintaanEntry):
            // - formrm_json  → $permintaan['formrm']
            // - permintaanpatologianatomi_data (nested) → dipromote ke top-level
            // - canvas & signature → sudah berupa Data URI siap pakai di top-level
            $formrm = $permintaan['formrm'] ?? [];
            $canvas = $permintaan['canvasPatologianatomi'] ?? $permintaan['canvas'] ?? '';
            ?>

            <div class="row mb-3">
                <div class="col-md-6 border-right">
                    <span class="badge badge-secondary mb-2">DATA PASIEN</span>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td style="width: 35%;"><strong>No. RM</strong></td>
                            <td>: <?= esc($formrm['no_rm'] ?? $permintaan['no_rm'] ?? $permintaan['no_rm_pasien'] ?? $hpa['norm_pasien'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Nama Pasien</strong></td>
                            <td>: <?= esc($formrm['nama'] ?? $permintaan['nama_pasien'] ?? $permintaan['nama'] ?? $hpa['nama_pasien'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Jenis Kelamin</strong></td>
                            <td>: <?= esc($formrm['jenis_kelamin'] ?? $permintaan['jenis_kelamin'] ?? $permintaan['gender'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tgl. Lahir</strong></td>
                            <td>: <?= !empty($formrm['tgl_lahir']) ? date('d/m/Y', strtotime($formrm['tgl_lahir'])) : (!empty($permintaan['tanggal_lahir']) ? date('d/m/Y', strtotime($permintaan['tanggal_lahir'])) : (!empty($hpa['tanggal_lahir_pasien']) ? date('d/m/Y', strtotime($hpa['tanggal_lahir_pasien'])) : '-')) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Alamat</strong></td>
                            <td>: <?= esc($formrm['alamat'] ?? $permintaan['alamat'] ?? '-') ?></td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <span class="badge badge-secondary mb-2">DOKTER PENGIRIM</span>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td style="width: 35%;"><strong>Dokter Pengirim</strong></td>
                            <td>: <?= esc($permintaan['dpjp_awal'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td><strong>SMF</strong></td>
                            <td>: <?= esc($permintaan['ruang_rawat'] ?? '-') ?></td>
                        </tr>
                    </table>
                    <span class="badge badge-secondary mb-2">SPESIMEN</span>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td style="width: 35%;"><strong>Lokasi Tindakan</strong></td>
                            <td>: <?= esc($permintaan['spesimen_lokasi'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Pengambilan Specimen</strong></td>
                            <td>: <?= esc($permintaan['spesimen_tindakan'] ?? '-') ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-light py-1 font-weight-bold">PERMINTAAN SPESIMEN</div>
                <div class="card-body py-2">
                    <div class="row text-center border-bottom pb-2 mb-2 font-weight-bold">
                        <div class="col">
                            HISTOPATOLOGI<br>
                            <span class="font-weight-normal text-muted" style="font-size: 0.9rem;">
                                <?= esc(is_array($permintaan['spesimen_histopatologi'] ?? null) ? implode(', ', $permintaan['spesimen_histopatologi']) : ($permintaan['spesimen_histopatologi'] ?? '-')) ?>
                            </span>
                        </div>
                        <div class="col">
                            SITOLOGI<br>
                            <span class="font-weight-normal text-muted" style="font-size: 0.9rem;">
                                <?= esc(is_array($permintaan['spesimen_sitologi'] ?? null) ? implode(', ', $permintaan['spesimen_sitologi']) : ($permintaan['spesimen_sitologi'] ?? '-')) ?>
                            </span>
                        </div>
                        <div class="col">
                            IMUNOHISTOKIMIA<br>
                            <span class="font-weight-normal text-muted" style="font-size: 0.9rem;">
                                <?= esc(is_array($permintaan['spesimen_imunohistokimia'] ?? null) ? implode(', ', $permintaan['spesimen_imunohistokimia']) : ($permintaan['spesimen_imunohistokimia'] ?? '-')) ?>
                            </span>
                        </div>
                        <div class="col">
                            PATOLOGI MOLEKULER<br>
                            <span class="font-weight-normal text-muted" style="font-size: 0.9rem;">
                                <?= esc(is_array($permintaan['spesimen_patologi_molekuler'] ?? null) ? implode(', ', $permintaan['spesimen_patologi_molekuler']) : ($permintaan['spesimen_patologi_molekuler'] ?? '-')) ?>
                            </span>
                        </div>
                        <div class="col">
                            PEWARNAAN LAIN<br>
                            <span class="font-weight-normal text-muted" style="font-size: 0.9rem;">
                                <?= esc(is_array($permintaan['spesimen_pewarnaan_lain'] ?? null) ? implode(', ', $permintaan['spesimen_pewarnaan_lain']) : ($permintaan['spesimen_pewarnaan_lain'] ?? '-')) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-light py-1 font-weight-bold">KETERANGAN KLINIS</div>
                <div class="card-body py-2">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Status Lokalis:</strong></p>
                            <div class="p-2 bg-light rounded border mb-3" style="min-height: 60px;">
                                <?= esc($permintaan['status_lokalis'] ?? $formrm['status_lokalis'] ?? '-') ?>
                            </div>
                            <p class="mb-1"><strong>Diagnosis Klinik:</strong></p>
                            <div class="p-2 bg-light rounded border mb-2" style="min-height: 60px;">
                                <?= esc($permintaan['diagnosis_klinik'] ?? $formrm['diagnosis_klinik'] ?? '-') ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center p-2 border rounded bg-light h-100 d-flex flex-column">
                                <small class="text-muted d-block mb-2">Diagram Lokasi Anatomis Pasien</small>
                                <?php if (!empty($canvas)) : ?>
                                    <div class="d-flex justify-content-center align-items-center flex-grow-1" style="min-height: 140px;">
                                        <img src="<?= $canvas ?>" alt="Diagram Lokasi" style="max-width:100%;max-height:300px;object-fit:contain;" />
                                    </div>
                                <?php else: ?>
                                    <div class="d-flex justify-content-center align-items-center flex-grow-1" style="min-height: 140px;">
                                        <small class="text-muted"><em>Diagram tidak tersedia</em></small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            // Ambil data signature yang sudah dipromote ke top-level oleh Model
            $signature = $permintaan['dokterSignatureRequest'] ?? $permintaan['dokterSignature'] ?? '';
            ?>

            <div class="row mt-3">
                <div class="col-md-6 offset-md-6 text-right">
                    <p class="mb-1">Surabaya, <?= esc($permintaan['formatted_consent_date'] ?? '-') ?></p>
                    <p class="mb-1">Dokter Pengirim,</p>

                    <!-- Tampilkan Tanda Tangan jika ada -->
                    <?php if (!empty($signature)) : ?>
                        <div class="my-1">
                            <img src="<?= $signature ?>" alt="Tanda Tangan Dokter" style="max-width:180px;height:auto;" />
                        </div>
                    <?php else : ?>
                        <div style="height: 70px;"></div>
                    <?php endif; ?>

                    <p class="font-weight-bold mb-0"><u><?= esc($permintaan['dpjp_awal'] ?? '-') ?></u></p>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function tampilkanModal(isi) {
        document.getElementById("modal-body-content").innerHTML = isi;
        var myModal = new bootstrap.Modal(document.getElementById("modalDetail"));
        myModal.show();
    }
</script>