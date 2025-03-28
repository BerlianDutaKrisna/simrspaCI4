<script>
    function cetakProses() {
        var detailMakroskopis = document.getElementById('makroskopis_ihc') ? document.getElementById('makroskopis_ihc').value : '';
        var detailMikroskopis = document.getElementById('mikroskopis_ihc') ? document.getElementById('mikroskopis_ihc').value : '';
        var printWindow = window.open('', '', 'height=500,width=800');
        printWindow.document.write(`
    <html>
    <head>
        <title>Cetak ihc</title>
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
                border: none;
            }
            .header-table td {
                padding: 5px;
                vertical-align: top;
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
                height: 400px;
                font-size: 16pt;
                border: 1px solid black;
                padding: 10px;
                text-align: left;
                vertical-align: top;
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
                    Kode ihc: <?= esc($ihc['kode_ihc'] ?? '') ?>
                </td>
                <td style="width: 20%;">Nama Pasien: <?= esc($ihc['nama_pasien'] ?? '') ?></td>
                <td style="width: 20%;">Dokter Pengirim: <?= esc($ihc['dokter_pengirim'] ?? '') ?></td>
                <td style="width: 20%;">Diagnosa Klinik: <?= esc($ihc['diagnosa_klinik'] ?? '') ?></td>
                <td style="width: 20%;">Tanggal Permintaan: <?= isset($ihc['tanggal_permintaan']) ? date('d-m-Y', strtotime($ihc['tanggal_permintaan'])) : ''; ?></td>
            </tr>
            <tr>
                <td>Norm: <?= esc($ihc['norm_pasien'] ?? '') ?></td>
                <td>Unit Asal: <?= esc($ihc['unit_asal'] ?? '') ?></td>
                <td>Lokasi Spesimen: <?= esc($ihc['lokasi_spesimen'] ?? '') ?></td>
                <td>Estimasi Hasil: <?= isset($ihc['tanggal_hasil']) ? date('d-m-Y', strtotime($ihc['tanggal_hasil'])) : ''; ?></td>
            </tr>
        </table>

        <table class="makroskopis-content-table">
            <tr>
                <td>Makroskopis</td>
                <td>Analis PA: <?= $pemotongan['analis_nama'] ?? '' ?> | Waktu Pemotongan: <?= isset($pemotongan['mulai_pemotongan_ihc']) ? date('H:i d-m-Y', strtotime($pemotongan['mulai_pemotongan_ihc'])) : ''; ?></td>
            </tr>
            <tr>
                <td colspan="2">${detailMakroskopis}</td>
            </tr>
            <tr>
            <td class="no-border"></td>
            <td class="foto-makroskopis">
            </td>
            </tr>
        </table>

        <table class="mikroskopis-content-table">
            <tr>
                <td>Mikroskopis</td>
                <td>Dokter PA: <?= $pemotongan['dokter_nama'] ?? '' ?> | Waktu pembacaan: <?= isset($pembacaan['mulai_pembacaan_ihc']) ? date('H:i d-m-Y', strtotime($pembacaan['mulai_pembacaan_ihc'])) : ''; ?></td>
            </tr>
            <tr>
                <td colspan="2">${detailMikroskopis}</td>
            </tr>
        </table>

        <table class="gambar-table">
            <tr>
                <th colspan="8">Gambar</th>
            </tr>
            <?php
            $jumlah_slide = $ihc['jumlah_slide'];
            $kolom_per_baris = 8;
            $max_kolom = 16;
            $angka_romawi = ["I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII", "XIII", "XIV", "XV", "XVI"];
            for ($baris = 0; $baris < ceil($max_kolom / $kolom_per_baris); $baris++) {
                echo '<tr>';
                for ($kolom = 0; $kolom < $kolom_per_baris; $kolom++) {
                    $indeks = ($baris * $kolom_per_baris) + $kolom;
                    if ($indeks < $jumlah_slide && $indeks < $max_kolom) {
                        echo '<td><span class="romawi">' . $angka_romawi[$indeks] . '</span></td>';
                    } else {
                        echo '<td><span class="romawi"></span></td>';
                    }
                }
                echo '</tr>';
            }
            ?>
        </table>
    </body>
    </html>
    `);

        printWindow.document.close();
        printWindow.print();
    }
</script>