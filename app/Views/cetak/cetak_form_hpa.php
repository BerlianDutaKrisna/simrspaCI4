<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table F4 Size</title>
    <style>
        @page {
            size: 215mm 330mm; /* F4 size */
            margin: 0; /* Remove default margin */
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td {
            border: 1px solid #000;
            text-align: left;
            padding: 5px;
            box-sizing: border-box; /* Include padding and border in element’s total width and height */
            overflow: hidden; /* Hide overflowing content */
            white-space: nowrap; /* Prevent text from wrapping */
            text-overflow: ellipsis; /* Show ellipsis for overflowing text */
            height: 30px; /* Ensure consistent row height */
        }

        .judul {
            font-weight: bold;
        }

        .text {
            display: inline-block;
            width: 215mm;
            min-height: 275px;
        }

        .gambar {
            height: 100px; /* Consistent height for image rows */
        }

        /* Remove header and footer during printing */
        @media print {
            header, footer, title {
                display: none;
            }

            body::after {
                content: "";
            }

            @page {
                margin-top: 20px;
                margin-bottom: 0;
            }

            table {
                page-break-before: auto;
            }

            /* Hide unnecessary elements */
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
                <td style="width: 25%;">Tanggal Mengerjakan :</td>
                <td style="width: 25%;">Tanggal Janji Hasil :</td>
                <td rowspan="2" style="width: 25%;">APD :</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
            <tr class="judul">
                <td colspan="2">Makroskopis</td>
                <td colspan="2">Analis PA :</td>
            </tr>
            <tr>
                <td colspan="4" class="text"></td>
            </tr>
            <tr class="judul">
                <td colspan="2">Mikroskopis</td>
                <td colspan="2">Dokter PA :</td>
            </tr>
            <tr>
                <td colspan="4" class="text"></td>
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
