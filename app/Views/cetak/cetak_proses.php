<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Halaman Proses</title>
    <style>
        @media print {
            @page {
                width: 215mm;
                margin: 10mm;
            }
        }

        header,
        footer,
        title {
            display: none;
            /* Hilangkan elemen yang tidak perlu dicetak */
        }

        body::after {
            content: "";
        }

        table {
            page-break-before: auto;
        }

        .api-code {
            display: none;
        }

        body {
            margin: 20px;
            padding: 0px;
            font-family: Arial, sans-serif;
            /* Font umum untuk cetak */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin: 0px;
        }

        td {
            border: 1px solid #000;
            text-align: left;
            margin: 0px;
            padding: 5px;
            box-sizing: border-box;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            height: 10px;
        }

        .judul {
            font-weight: bold;
            margin: 0px;
        }

        .makroskopis,
        .mikroskopis {
            display: inline-block;
            width: 205mm;
            padding-left: 10px;
            min-height: 330px;
            margin: 0;
            font-size: 14px;
        }

        .gambar {
            height: 110px;
        }

        /* Gaya dari Summernote */
        .note-editable {
            font-family: inherit;
            /* Menggunakan font default Summernote */
            font-size: inherit;
            /* Sesuaikan ukuran font */
            line-height: inherit;
            /* Tinggi baris */
            color: inherit;
            /* Warna teks */
            overflow: visible !important;
            /* Pastikan konten terlihat */
        }

        /* Tambahkan gaya untuk tag HTML yang umum digunakan */
        p,
        span,
        div {
            font-size: 14px;
            /* Sesuaikan ukuran font */
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }
    </style>
</head>

<body>
    <table>
        <tbody>
            <tr>
                <td rowspan="2" style="width: 25%; font-size: 15px; font-weight: bold; text-align: center;">
                    Kode HPA : <?= esc($data['kode_hpa'] ?? '') ?>
                </td>
                <td style="width: 25%;">Nama Pasien : <?= esc($data['nama_pasien'] ?? '') ?> (<?= esc($data['norm_pasien'] ?? '') ?>)</td>
                <td style="width: 25%;">Dokter pengirim : <?= esc($data['dokter_pengirim'] ?? '') ?></td>
                <td style="width: 25%;">Diagnosa : <?= esc($data['diagnosa_klinik'] ?? '') ?></td>
            </tr>
            <tr>
                <td style="width: 25%;">Tanggal Hasil :
                    <?= isset($data['tanggal_hasil']) ? date('d-m-Y', strtotime($data['tanggal_hasil'])) : 'Tidak tersedia'; ?>
                </td>
                <td style="width: 25%;">Unit Asal : <?= esc($data['unit_asal'] ?? '') ?></td>
                <td style="width: 25%;">Lokasi Spesimen : <?= esc($data['lokasi_spesimen'] ?? '') ?></td>
            </tr>
            <tr class="judul">
                <td colspan="2">Makroskopis</td>
                <td colspan="2">Analis PA : <?= esc($data['nama_user_pemotongan'] ?? '') ?></td>
            </tr>
            <tr>
                <td colspan="1" class="makroskopis">
                    <?= nl2br(html_entity_decode(esc($data['makroskopis_hpa'] ?? ''))); ?>
                </td>
            </tr>
            <tr class="judul">
                <td colspan="2">Mikroskopis</td>
                <td colspan="2">Dokter PA : <?= esc($data['nama_user_dokter_pemotongan'] ?? '') ?></td>
            </tr>
            <tr>
                <td colspan="1" class="mikroskopis">
                    <?= nl2br(html_entity_decode(esc($data['mikroskopis_hpa'] ?? ''))); ?>
                </td>
            </tr>
            <tr class="judul">
                <td colspan="2">Traking Spesimen</td>
                <td colspan="2">Kualitas Sediaan</td>
            </tr>
            <tr>
                <td colspan="2" style="width: 50%;">1. Bahan Diterima : <?= isset($data['mulai_penerimaan']) ? date('H:i, d-m-Y', strtotime($data['mulai_penerimaan'])) : ''; ?></td>
                <td colspan="2" style="width: 50%;">1. Volume cairan fiksasi sesuai? <?= esc($data['indikator_1'] ?? '0') == '0' ? '' : esc($data['indikator_1']) ?></td>
            </tr>
            <tr>
                <td colspan="2">2. Makroskopis : <?= isset($data['mulai_pemotongan']) ? date('H:i, d-m-Y', strtotime($data['mulai_pemotongan'])) : ''; ?></td>
                <td colspan="2">2. Jaringan terfiksasi merata? <?= esc($data['indikator_2'] ?? '0') == '0' ? '' : esc($data['indikator_2']) ?></td>
            </tr>
            <tr>
                <td colspan="2">3. Prosessing : <?= isset($data['mulai_pemprosesan']) ? date('H:i, d-m-Y', strtotime($data['mulai_pemprosesan'])) : ''; ?></td>
                <td colspan="2">3. Blok parafin tidak ada fragmentasi? <?= esc($data['indikator_3'] ?? '0') == '0' ? '' : esc($data['indikator_3']) ?></td>
            </tr>
            <tr>
                <td colspan="2">4. Embeding (HPA) : <?= isset($data['mulai_penanaman']) ? date('H:i, d-m-Y', strtotime($data['mulai_penanaman'])) : ''; ?></td>
                <td colspan="2">4. Sediaan tanpa lipatan? <?= esc($data['indikator_4'] ?? '0') == '0' ? '' : esc($data['indikator_4']) ?></td>
            </tr>
            <tr>
                <td colspan="2">5. Mikrotomi (HPA) : <?= isset($data['mulai_pemotongan_tipis']) ? date('H:i, d-m-Y', strtotime($data['mulai_pemotongan_tipis'])) : ''; ?></td>
                <td colspan="2">5. Sediaan tanpa goresan mata pisau? <?= esc($data['indikator_5'] ?? '0') == '0' ? '' : esc($data['indikator_5']) ?></td>
            </tr>
            <tr>
                <td colspan="2">6. Pewarnaan : <?= isset($data['mulai_pewarnaan']) ? date('H:i, d-m-Y', strtotime($data['mulai_pewarnaan'])) : ''; ?></td>
                <td colspan="2">6. Kontras warna sediaan cukup jelas? <?= esc($data['indikator_6'] ?? '0') == '0' ? '' : esc($data['indikator_6']) ?></td>
            </tr>
            <tr>
                <td colspan="2">7. Pembacaan : <?= isset($data['mulai_pembacaan']) ? date('H:i, d-m-Y', strtotime($data['mulai_pembacaan'])) : ''; ?></td>
                <td colspan="2">7. Sediaan tanpa gelembung? <?= esc($data['indikator_7'] ?? '0') == '0' ? '' : esc($data['indikator_7']) ?></td>
            </tr>
            <tr>
                <td colspan="2">8. Pencetakan : <?= isset($data['mulai_pencetakan']) ? date('H:i, d-m-Y', strtotime($data['mulai_pencetakan'])) : ''; ?></td>
                <td colspan="2">8. Sediaan tanpa bercak/sidik jari? <?= esc($data['indikator_8'] ?? '0') == '0' ? '' : esc($data['indikator_8']) ?></td>
            </tr>
        </tbody>
    </table>

    <table>
        <tbody>
            <tr class="judul">
                <td colspan="6">Gambar</td>
            </tr>
            <tr>
                <td class="gambar"></td>
                <td class="gambar"></td>
                <td class="gambar"></td>
                <td class="gambar"></td>
                <td class="gambar"></td>
                <td class="gambar"></td>
            </tr>
            <tr>
                <td class="gambar"></td>
                <td class="gambar"></td>
                <td class="gambar"></td>
                <td class="gambar"></td>
                <td class="gambar"></td>
                <td class="gambar"></td>
            </tr>
        </tbody>
    </table>
</body>

</html>