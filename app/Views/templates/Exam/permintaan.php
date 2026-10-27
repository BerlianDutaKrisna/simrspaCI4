<button id="permintaanSimrsToggle" class="btn btn-outline-primary mb-3 ml-2" type="button" data-toggle="collapse" data-target="#permintaanSimrs" aria-expanded="false" aria-controls="permintaanSimrs">
    <i class="fas fa-list-alt"></i> Permintaan SIMRS 
    <?php if (!empty($permintaan['data']) && is_array($permintaan['data'])): ?>
        <span class="badge badge-light ml-1"><?= count($permintaan['data']) ?></span>
    <?php endif; ?>
</button>

<div class="collapse mb-4" id="permintaanSimrs">
    <div class="card card-body border-dark">

        <?php 
        $listPermintaan = $permintaan['data'] ?? [];
        $isSinglePermintaan = false;
        $firstPermintaanIndex = 0;
        ?>

        <?php if (!empty($listPermintaan) && is_array($listPermintaan)): ?>
            <?php $isSinglePermintaan = (count($listPermintaan) === 1); ?>
            <?php $firstPermintaanIndex = array_key_first($listPermintaan); ?>

            <!-- BAGIAN 1: TABEL RINGKASAN PILIHAN -->
            <div id="wrapper-tabel-permintaan" class="<?= $isSinglePermintaan ? 'd-none' : '' ?>">
                <h6 class="font-weight-bold text-primary mb-3">
                    <i class="fas fa-info-circle"></i> Silakan pilih salah satu permintaan di bawah ini:
                </h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th style="width: 20%;">Dokter Pengirim (DPJP)</th>
                                <th style="width: 25%;">Status Lokalis</th>
                                <th style="width: 30%;">Diagnosis Klinik</th>
                                <th style="width: 10%;">Ruang Rawat</th>
                                <th class="text-center" style="width: 10%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($listPermintaan as $index => $item): ?>
                                <tr>
                                    <td class="text-center"><?= $index + 1 ?></td>
                                    <td><?= esc($item['dpjp_awal'] ?? '-') ?></td>
                                    <td><?= esc($item['status_lokalis'] ?? '-') ?></td>
                                    <td><?= esc($item['diagnosis_klinik'] ?? '-') ?></td>
                                    <td><?= esc($item['ruang_rawat'] ?? '-') ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm" onclick="pilihPermintaan(<?= $index ?>)">
                                            <i class="fas fa-check-circle"></i> Pilih
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- BAGIAN 2: DETAIL FORMULIR (Awalnya tersembunyi) -->
            <div id="wrapper-detail-permintaan" class="d-none">
                <div class="mb-3">
                    <button type="button" class="btn btn-secondary btn-sm" onclick="kembaliKeTabel()">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Permintaan
                    </button>
                </div>

                <?php foreach ($listPermintaan as $index => $item): ?>
                    <?php
                        $formrm = $item['formrm'] ?? [];
                        $canvas = $item['canvasPatologianatomi'] ?? $item['canvas'] ?? '';
                        $signature = $item['dokterSignatureRequest'] ?? $item['dokterSignature'] ?? '';
                    ?>

                    <!-- Menggunakan struktur markup asli Anda, dengan ID dinamis per item -->
                    <div id="permintaanContent-<?= $index ?>" class="container-fluid bg-white p-4 border rounded detail-item d-none mb-3">
                        <div class="row border-bottom pb-2 mb-3 text-center align-items-center">
                            <div class="col-8">
                                <h6 class="font-weight-bold mb-0">PEMERINTAH KOTA SURABAYA</h6>
                                <h5 class="font-weight-bold mb-0">RUMAH SAKIT UMUM DAERAH dr. MOHAMAD SOEWANDHIE</h5>
                                <small>Jl. Tambak Rejo No. 45-47 - Telp. 031 3717141, 3725905</small>
                            </div>
                            <div class="col-4 text-left">
                                <h1 class="font-weight-bold mb-0"><?= esc($hpa['kode_hpa'] ?? '') ?></h1>
                            </div>
                        </div>

                        <div class="text-center mb-4">
                            <h5 class="font-weight-bold text-uppercase text-decoration-underline">PERMINTAAN PEMERIKSAAN PATOLOGI ANATOMI</h5>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 border-right">
                                <span class="badge badge-secondary mb-2">DATA PASIEN</span>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td style="width: 35%;"><strong>No. RM</strong></td>
                                        <td>: <?= esc($formrm['no_rm'] ?? $item['no_rm'] ?? $item['no_rm_pasien'] ?? $hpa['norm_pasien'] ?? '-') ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nama Pasien</strong></td>
                                        <td>: <?= esc($formrm['nama'] ?? $item['nama_pasien'] ?? $item['nama'] ?? $hpa['nama_pasien'] ?? '-') ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Jenis Kelamin</strong></td>
                                        <td>: <?= esc($formrm['jenis_kelamin'] ?? $item['jenis_kelamin'] ?? $item['gender'] ?? '-') ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tgl. Lahir</strong></td>
                                        <td>: <?= !empty($formrm['tgl_lahir']) ? date('d/m/Y', strtotime($formrm['tgl_lahir'])) : (!empty($item['tanggal_lahir']) ? date('d/m/Y', strtotime($item['tanggal_lahir'])) : (!empty($hpa['tanggal_lahir_pasien']) ? date('d/m/Y', strtotime($hpa['tanggal_lahir_pasien'])) : '-')) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Alamat</strong></td>
                                        <td>: <?= esc($formrm['alamat'] ?? $item['alamat'] ?? '-') ?></td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <span class="badge badge-secondary mb-2">DOKTER PENGIRIM</span>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td style="width: 35%;"><strong>Dokter Pengirim</strong></td>
                                        <td>: <?= esc($item['dpjp_awal'] ?? '-') ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>SMF</strong></td>
                                        <td>: <?= esc($item['ruang_rawat'] ?? '-') ?></td>
                                    </tr>
                                </table>
                                <span class="badge badge-secondary mb-2">SPESIMEN</span>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td style="width: 35%;"><strong>Lokasi Tindakan</strong></td>
                                        <td>: <?= esc($item['spesimen_lokasi'] ?? '-') ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Pengambilan Specimen</strong></td>
                                        <td>: <?= esc($item['spesimen_tindakan'] ?? '-') ?></td>
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
                                            <?= esc(is_array($item['spesimen_histopatologi'] ?? null) ? implode(', ', $item['spesimen_histopatologi']) : ($item['spesimen_histopatologi'] ?? '-')) ?>
                                        </span>
                                    </div>
                                    <div class="col">
                                        SITOLOGI<br>
                                        <span class="font-weight-normal text-muted" style="font-size: 0.9rem;">
                                            <?= esc(is_array($item['spesimen_sitologi'] ?? null) ? implode(', ', $item['spesimen_sitologi']) : ($item['spesimen_sitologi'] ?? '-')) ?>
                                        </span>
                                    </div>
                                    <div class="col">
                                        IMUNOHISTOKIMIA<br>
                                        <span class="font-weight-normal text-muted" style="font-size: 0.9rem;">
                                            <?= esc(is_array($item['spesimen_imunohistokimia'] ?? null) ? implode(', ', $item['spesimen_imunohistokimia']) : ($item['spesimen_imunohistokimia'] ?? '-')) ?>
                                        </span>
                                    </div>
                                    <div class="col">
                                        PATOLOGI MOLEKULER<br>
                                        <span class="font-weight-normal text-muted" style="font-size: 0.9rem;">
                                            <?= esc(is_array($item['spesimen_patologi_molekuler'] ?? null) ? implode(', ', $item['spesimen_patologi_molekuler']) : ($item['spesimen_patologi_molekuler'] ?? '-')) ?>
                                        </span>
                                    </div>
                                    <div class="col">
                                        PEWARNAAN LAIN<br>
                                        <span class="font-weight-normal text-muted" style="font-size: 0.9rem;">
                                            <?= esc(is_array($item['spesimen_pewarnaan_lain'] ?? null) ? implode(', ', $item['spesimen_pewarnaan_lain']) : ($item['spesimen_pewarnaan_lain'] ?? '-')) ?>
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
                                            <?= esc($item['status_lokalis'] ?? $formrm['status_lokalis'] ?? '-') ?>
                                        </div>
                                        <p class="mb-1"><strong>Diagnosis Klinik:</strong></p>
                                        <div class="p-2 bg-light rounded border mb-2" style="min-height: 60px;">
                                            <?= esc($item['diagnosis_klinik'] ?? $formrm['diagnosis_klinik'] ?? '-') ?>
                                        </div>
                                        <p class="mb-1"><strong>Lain-lain:</strong></p>
                                        <div class="p-2 bg-light rounded border mb-2" style="min-height: 60px;">
                                            <?= esc($item['lain_lain_lab_radiologi'] ?? $formrm['lain_lain_lab_radiologi'] ?? '-') ?>
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
                        $signature = $item['dokterSignatureRequest'] ?? $item['dokterSignature'] ?? '';
                        ?>

                        <div class="row mt-3">
                            <div class="col-md-6 offset-md-6 text-right">
                                <p class="mb-1">Surabaya, <?= esc($item['formatted_consent_date'] ?? '-') ?></p>
                                <p class="mb-1">Dokter Pengirim,</p>

                                <?php if (!empty($signature)) : ?>
                                    <div class="my-1">
                                        <img src="<?= $signature ?>" alt="Tanda Tangan Dokter" style="max-width:180px;height:auto;" />
                                    </div>
                                <?php else : ?>
                                    <div style="height: 70px;"></div>
                                <?php endif; ?>

                                <p class="font-weight-bold mb-0"><u><?= esc($item['dpjp_awal'] ?? '-') ?></u></p>
                            </div>
                        </div>

                        <!-- Tombol Cetak Asli Anda -->
                        <div class="text-right mt-3">
                            <button class="btn btn-outline-info" type="button" onclick="cetakPermintaan()">
                                <i class="fas fa-print"></i> Cetak Permintaan
                            </button>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <div class="text-center py-3 text-muted">
                <em>Tidak ada data permintaan SIMRS untuk pasien ini.</em>
            </div>
        <?php endif; ?>

    </div>
</div>

<script>
    function tampilkanModal(isi) {
        document.getElementById("modal-body-content").innerHTML = isi;
        var myModal = new bootstrap.Modal(document.getElementById("modalDetail"));
        myModal.show();
    }

    function pilihPermintaan(index) {
        // Sembunyikan tabel ringkasan
        document.getElementById('wrapper-tabel-permintaan').classList.add('d-none');
        
        // Tampilkan kontainer detail
        document.getElementById('wrapper-detail-permintaan').classList.remove('d-none');

        // Reset semua item detail ke ID aslinya
        var items = document.querySelectorAll('.detail-item');
        items.forEach(function(el, idx) {
            el.classList.add('d-none');
            el.id = 'permintaanContent-' + idx;
        });

        // Ubah ID item yang dipilih menjadi 'permintaanContent' agar fungsi cetakPermintaan() asli mendeteksinya
        var selectedItem = document.getElementById('permintaanContent-' + index);
        if (selectedItem) {
            selectedItem.classList.remove('d-none');
            selectedItem.id = 'permintaanContent';
            selectedItem.setAttribute('data-index', index);
        }
    }

    function kembaliKeTabel() {
        // Kembalikan ID elemen aktif ke format semula
        var activeItem = document.getElementById('permintaanContent');
        if (activeItem) {
            var idx = activeItem.getAttribute('data-index');
            if (idx !== null) {
                activeItem.id = 'permintaanContent-' + idx;
            }
        }

        // Sembunyikan kontainer detail, tampilkan kembali tabel ringkasan
        document.getElementById('wrapper-detail-permintaan').classList.add('d-none');
        document.getElementById('wrapper-tabel-permintaan').classList.remove('d-none');
    }

    <?php if (!empty($listPermintaan) && is_array($listPermintaan)): ?>
        document.addEventListener('DOMContentLoaded', function () {
            var isSinglePermintaan = <?= (count($listPermintaan) === 1) ? 'true' : 'false' ?>;
            var toggleBtn = document.getElementById('permintaanSimrsToggle');
            var collapseEl = document.getElementById('permintaanSimrs');

            if (!toggleBtn || !collapseEl) {
                return;
            }

            toggleBtn.addEventListener('click', function () {
                if (isSinglePermintaan) {
                    pilihPermintaan(0);
                    return;
                }

                var items = document.querySelectorAll('.detail-item');
                items.forEach(function (el, idx) {
                    el.classList.add('d-none');
                    el.id = 'permintaanContent-' + idx;
                });
            });
        });
    <?php endif; ?>

    // Fungsi cetakPermintaan ASLI milik Anda
    function cetakPermintaan() {
        var content = document.getElementById('permintaanContent');
        if (!content) {
            alert('Konten permintaan tidak ditemukan.');
            return;
        }

        var printWindow = window.open('', '', 'height=800,width=900');
        printWindow.document.open();
        printWindow.document.write(`
<!DOCTYPE html>
<html>
<head>
    <title>Cetak Permintaan PA</title>
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <style>
        @page { size: A4; margin: 10mm; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #000; }
        .badge { border: 1px solid #333; color: #000 !important; background: #eee !important; }
        img { max-width: 100%; height: auto; }
        button { display: none !important; }
    </style>
</head>
<body>
    <div id="halaman-permintaan"></div>
</body>
</html>`);
        printWindow.document.close();
        printWindow.document.getElementById('halaman-permintaan').innerHTML = content.innerHTML;
        printWindow.addEventListener('afterprint', function() {
            printWindow.close();
        });
        setTimeout(function() {
            printWindow.focus();
            printWindow.print();
        }, 300);
    }
</script>