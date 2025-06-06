<script>
    function cetakProses() {
        var detailMakroskopis = document.getElementById('makroskopis_srs') ? document.getElementById('makroskopis_srs').value : '';
        var detailMikroskopis = document.getElementById('mikroskopis_srs') ? document.getElementById('mikroskopis_srs').value : '';
        var detailHasil = document.getElementById('hasil_srs') ? document.getElementById('hasil_srs').value : '';
        var printWindow = window.open('', '', 'height=500,width=800');
        printWindow.document.write(`
    <html>
    <head>
        <title>Cetak Hpa</title>
        <style>
        @page {
            size: 215mm 350mm;
            margin: 20mm;
        }
            body {
                font-family: Verdana, Arial, sans-serif;
                margin: 10px;
                padding: 0;
                height: 100%;
                display: flex;
                flex-direction: column;
            }
            h5 {
                margin: 5px 0;
            }
            table {
                width: 100%;
                table-layout: fixed; /* Pastikan tabel tidak melebar */
                border-collapse: collapse;
                word-wrap: break-word; /* Pecahkan teks panjang */
            }
            td {
                padding: 10px;
                text-align: left;
                vertical-align: middle;
                border: 1px solid black;
            }
            hr {
                border: 1px solid #000;
                margin: 20px 0;
            }
            .header-table {
                width: 100%;
                border-collapse: collapse;
                margin: 0;
                font-size: 11px;
                font-family: Verdana, Arial, sans-serif;
            }
            .header-table td {
                padding: 3px 4px;
                vertical-align: top;
                border: 1px solid #000;
                line-height: 1.2;
            }
            .makroskopis-content-table td,
            .mikroskopis-content-table td {
                font-size: 12px;
                padding: 5px;
            }
            .makroskopis-content-table td:first-child,
            .mikroskopis-content-table td:first-child {
                width: 30%;
            }
            .makroskopis-content-table td:last-child,
            .mikroskopis-content-table td:last-child {
                width: 70%;
            }
            .makroskopis-content-table td[colspan="2"] {
                height: 150px;
                font-size: 16pt;
                border: 1px solid black;
                border-bottom: none;
                padding: 10px;
                text-align: left;
                vertical-align: top;
            }
            .no-border{
                border-right: none;
                border-top: none;
            }
            .foto-makroskopis{
                border-left: none;
                border-top: none;
                width: 100%;
                text-align: right;
                vertical-align: top;
            }
            .mikroskopis-content-table td[colspan="2"] {
                height: 150px;
                font-size: 16pt;
                border: 1px solid black;
                padding: 10px;
                text-align: left;
                vertical-align: top;
            }
            .mutu-table {
                font-size: 12px;
                font-family: Verdana, Arial, sans-serif;
                border-collapse: collapse;
                width: 100%;
                margin: 0;
            }
            .mutu-table td {
                border: 1px solid black;
                padding: 5px;
                vertical-align: middle;
                text-align: left;
            }
            /* Styling untuk tabel gambar */
            .gambar-table {
                width: 100%;
                border-collapse: collapse;
            }
            .gambar-table td {
                height: 110px;
                width: 12.5%;
                border: 1px dashed black;
                text-align: center;
                vertical-align: middle;
                position: relative;
            }
            .gambar-table th {
                text-align: center;
                font-size: 14px;
                padding: 10px;
                border: 1px solid black;
                font-weight: bold;
            }
            .gambar-table td .romawi {
                position: absolute;
                top: 5px;
                left: 5px;
                font-size: 10pt;
                font-weight: normal;
                color: rgba(0, 0, 0, 0.3);
            }
        </style>
    </head>
    <body>
        <table class="header-table">
            <tr>
                <td rowspan="2" style="width: 20%; font-size: 15px; font-weight: bold; text-align: center;">
                    Kode srs: <?= esc($srs['kode_srs'] ?? '') ?>
                </td>
                <td style="width: 20%;">Nama Pasien: <?= esc($srs['nama_pasien'] ?? '') ?></td>
                <td style="width: 20%;">Dokter Pengirim: <?= esc($srs['dokter_pengirim'] ?? '') ?></td>
                <td style="width: 20%;">Diagnosa Klinik: <?= esc($srs['diagnosa_klinik'] ?? '') ?></td>
                <td style="width: 20%;">Tanggal Permintaan: <?= isset($srs['tanggal_permintaan']) ? date('d-m-Y', strtotime($srs['tanggal_permintaan'])) : ''; ?></td>
            </tr>
            <tr>
                <td>Norm: <?= esc($srs['norm_pasien'] ?? '') ?></td>
                <td>Unit Asal: <?= esc($srs['unit_asal'] ?? '') ?></td>
                <td>Lokasi Spesimen: <?= esc($srs['lokasi_spesimen'] ?? '') ?></td>
                <td>Estimasi Hasil: <?= isset($srs['tanggal_hasil']) ? date('d-m-Y', strtotime($srs['tanggal_hasil'])) : ''; ?></td>
            </tr>
        </table>

        <table class="makroskopis-content-table">
            <thead>
                <tr>
                    <th>Riwayat Pemeriksaan</th>
                    <th>Foto Makroskopis</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $riwayat_pemeriksaan = [
                    ['data' => $riwayat_hpa ?? []],
                    ['data' => $riwayat_frs ?? []],
                    ['data' => $riwayat_srs ?? []],
                    ['data' => $riwayat_ihc ?? []]
                ];

                $totalRiwayat = 0;
                foreach ($riwayat_pemeriksaan as $pemeriksaan) {
                    $totalRiwayat += count($pemeriksaan['data']);
                }

                $firstRow = true;

                if ($totalRiwayat > 0) :
                    foreach ($riwayat_pemeriksaan as $pemeriksaan) :
                        foreach ($pemeriksaan['data'] as $row) : ?>
                            <tr>
                                <td class="no-border" style="white-space: nowrap;">
                                    <strong><?= isset($row['tanggal_permintaan']) ? date('d-m-Y', strtotime($row['tanggal_permintaan'])) : '-' ?></strong>
                                    &nbsp;,&nbsp;<?= esc($row['kode_hpa'] ?? $row['kode_frs'] ?? $row['kode_srs'] ?? $row['kode_ihc'] ?? '-') ?>
                                    &nbsp;,&nbsp;Lokasi: <?= esc($row['lokasi_spesimen'] ?? '-') ?>
                                    &nbsp;,&nbsp;Hasil: <?= esc(strip_tags($row['hasil_hpa'] ?? $row['hasil_frs'] ?? $row['hasil_srs'] ?? $row['hasil_ihc'] ?? '-')) ?>
                                </td>
                                <?php if ($firstRow) : ?>
                                    <td class="foto-makroskopis" rowspan="<?= $totalRiwayat ?>">
                                        <img src="<?= isset($srs['foto_makroskopis_srs']) && $srs['foto_makroskopis_srs'] !== null
                                                        ? base_url('uploads/srs/makroskopis/' . $srs['foto_makroskopis_srs'])
                                                        : base_url('img/no_photo.jpg') ?>"
                                            width="200"
                                            alt="Foto Makroskopis"
                                            class="img-thumbnail"
                                            id="fotoMakroskopis"
                                            data-toggle="modal"
                                            data-target="#fotoModal"
                                            style="object-fit: cover; aspect-ratio: 16 / 9; max-width: 100%; height: auto;">
                                    </td>
                                    <?php $firstRow = false; ?>
                                <?php endif; ?>
                            </tr>
                    <?php
                        endforeach;
                    endforeach;
                else : ?>
                    <!-- Jika tidak ada riwayat, tetap tampilkan foto -->
                    <tr>
                        <td class="no-border">Tidak ada riwayat pemeriksaan.</td>
                        <td class="foto-makroskopis">
                            <img src="<?= isset($srs['foto_makroskopis_srs']) && $srs['foto_makroskopis_srs'] !== null
                                            ? base_url('uploads/srs/makroskopis/' . $srs['foto_makroskopis_srs'])
                                            : base_url('img/no_photo.jpg') ?>"
                                width="200"
                                alt="Foto Makroskopis"
                                class="img-thumbnail"
                                id="fotoMakroskopis"
                                data-toggle="modal"
                                data-target="#fotoModal"
                                style="object-fit: cover; aspect-ratio: 16 / 9; max-width: 100%; height: auto;">
                        </td>
                    </tr>
                <?php endif; ?>

                <tr>
                    <td><strong>Makroskopis</strong></td>
                    <td>Analis PA: <?= esc($penerimaan_srs['analis_nama'] ?? '-') ?> | Waktu Penerimaan: <?= isset($penerimaan_srs['mulai_penerimaan_srs']) ? date('H:i d-m-Y', strtotime($penerimaan_srs['mulai_penerimaan_srs'])) : ''; ?></td>
                </tr>
                <tr>
                    <td colspan="2">${detailMakroskopis}</td>
                </tr>
            </tbody>
        </table>

        <table class="mikroskopis-content-table">
            <tr>
                <td>Mikroskopis</td>
                <td><?= esc($nama_user) ?? ""; ?> | Waktu Screaning/Pembacaan: <?= isset($pembacaan_srs['mulai_pembacaan_srs']) ? date('H:i d-m-Y', strtotime($pembacaan_srs['mulai_pembacaan_srs'])) : ''; ?></td>
            </tr>
            <tr>
                <td colspan="2">${detailMikroskopis}
                <br>
                <br>
                <b>Kesimpulan:</b> <?= esc($srs['lokasi_spesimen'] ?? '-') ?>, <?= esc($srs['tindakan_spesimen'] ?? '-') ?>:
                <br>
                <strong style="text-transform: uppercase;">${detailHasil}</strong>
                </td>
            </tr>
        </table>

        <table class="mutu-table">
            <tr>
                <td>☐ Sediaan tanpa lipatan?</td>
                <td>☐ Sediaan tanpa goresan mata pisau?</td>
                <td>☐ Kontras warna sediaan cukup jelas?</td>
                <td>☐ Sediaan tanpa gelembung udara?</td>
                <td>☐ Sediaan tanpa bercak / sidik jari?</td>
            </tr>
        </table>

    </body>
    </html>
    `);

        printWindow.document.close();
        printWindow.print();
    }
</script>