<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table F4 Size</title>
    <style>
        /* Mengatur ukuran default untuk kertas A4 */
        @page {
            size: A4; /* Ukuran default A4 */
            margin: 10mm;
        }

        /* Mengatur ukuran kertas Letter */
        @media print and (max-width: 600px) {
            @page {
                size: Letter;
                margin: 10mm;
            }
        }

        /* Mengatur ukuran kertas Legal */
        @media print and (max-width: 800px) {
            @page {
                size: Legal;
                margin: 10mm;
            }
        }

        /* Mengatur margin dan padding untuk halaman */
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 9pt;
            margin: 20px;
            padding: 0px;
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
            padding: 1px;
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

        .makroskopis{
            display: inline-block;
            width: 205mm;
            min-height: 330px;
            margin: 0;
        }
        .mikroskopis{
            display: inline-block;
            width: 205mm;
            min-height: 400px;
            margin: 0;
        }

        .gambar {
            height: 110px;
        }

        /* Pengaturan cetak */
        @media print {
            header, footer, title {
                display: none; /* Menyembunyikan header dan footer saat print */
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
        }
    </style>
</head>
<body>
    <table>
        <tbody>
            <tr>
                <td rowspan="2" style="width: 25%;">Kode HPA :</td>
                <td style="width: 25%;">Nama Pasien :</td>
                <td style="width: 25%;">Tanggal Mengerjakan :</td>
                <td rowspan="2" style="width: 25%;">APD :</td>
            </tr>
            <tr>
            <td style="width: 25%;">Norm Pasien :</td>
            <td style="width: 25%;">Tanggal Hasil :</td>
            </tr>
            <tr class="judul">
                <td colspan="2">Makroskopis</td>
                <td colspan="2">Analis PA :</td>
            </tr>
            <tr>
                <td colspan="4" class="makroskopis"></td>
            </tr>
            <tr class="judul">
                <td colspan="2">Mikroskopis</td>
                <td colspan="2">Dokter PA :</td>
            </tr>
            <tr>
                <td colspan="4" class="mikroskopis"></td>
            </tr>
            <tr class="judul">
                <td colspan="2">Traking Spesimen</td>
                <td colspan="2">Kualitas Sediaan</td>
            </tr>
            <tr>
                <td colspan="2" style="width: 50%;">1. Bahan Diterima :</td>
                <td colspan="2" style="width: 50%;">1. Volume cairan fiksasi sesuai?</td>
            </tr>
            <tr>
                <td colspan="2">2. Makroskopis :</td>
                <td colspan="2">2. Jaringan terfiksasi merata?</td>
            </tr>
            <tr>
                <td colspan="2">3. Prosessing :</td>
                <td colspan="2">3. Blok parafin tidak ada fragmentasi?</td>
            </tr>
            <tr>
                <td colspan="2">4. Embeding (HPA) :</td>
                <td colspan="2">4. Sediaan tanpa lipatan?</td>
            </tr>
            <tr>
                <td colspan="2">5. Mikrotomi (HPA) :</td>
                <td colspan="2">5. Sediaan tanpa goresan mata pisau?</td>
            </tr>
            <tr>
                <td colspan="2">6. Pewarnaan :</td>
                <td colspan="2">6. Kontras warna sediaan cukup jelas?</td>
            </tr>
            <tr>
                <td colspan="2">7. Entelan :</td>
                <td colspan="2">7. Sediaan tanpa gelembung?</td>
            </tr>
            <tr>
                <td colspan="2">8. Selesai :</td>
                <td colspan="2">8. Sediaan tanpa bercak/sidik jari?</td>
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
