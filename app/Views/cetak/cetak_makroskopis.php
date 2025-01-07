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
        }
        .judul{
            font-weight: bold;
        }
        .text {
            display: inline-block; /* Ensure the width is maintained even if there's no content */
            width: 194mm; /* Approximately the width of the printable area considering the margin */
            min-height: 180px; /* Maintain height */
        }
        .foto {
            display: inline-block; /* Ensure the width is maintained even if there's no content */
            width: 210px; /* Approximately the width of the printable area considering the margin */
            min-height: 45px; /* Maintain height */
        }
        .gambar {
            padding: 50px;            
        }

        /* Remove header and footer during printing */
        @media print {
            /* Remove page header and footer */
            header, footer {
                display: none;
            }

            /* Remove page numbers */
            body::after {
                content: "";
            }

            /* Hide URL and other elements for print */
            @page {
                margin-top: 0;
                margin-bottom: 0;
            }

            table {
                page-break-before: auto;
            }

            /* Optionally, hide unnecessary page elements */
            .gambar {
                display: none; /* You can hide images or elements as needed */
            }
        }
    </style>
</head>
<body>
    <table>
        <tbody>
            <tr>
                <td colspan="1" rowspan="2">Kode HPA : </td>
                <td colspan="1">Tanggal Mengerjakan :</td>
                <td colspan="1">Tanggal Janji Hasil :</td>
                <td colspan="1" rowspan="2">APD :</td>
            </tr>
            <tr>
                <td colspan="1"></td>
                <td colspan="1"></td>
            </tr>
            <tr class="judul">
                <td colspan="2" class="judul" >Makroskopis</td>
                <td colspan="2" >Analis PA : </td>
            </tr>
            <tr>
                <td colspan="4"class="text"></td>
            </tr>
            <tr class="judul">
                <td colspan="2">Mikroskopis</td>
                <td colspan="2">Dokter PA : </td>
            </tr>
            <tr>
                <td colspan="4" class="text"></td>
            </tr>
            <tr class="judul">
                <td colspan="2">Traking Spesimen</td>
                <td colspan="2">Kualitas Sediaan</td>
            </tr>
            <tr>
                <td colspan="2">1. Bahan Diterima : </td>
                <td colspan="2">1. Volume cairan fiksasi sesuai?</td>
            </tr>
            <tr>
                <td colspan="2">2. Makroskopis : </td>
                <td colspan="2">2. Jaringan terfiksasi merata?</td>
            </tr>
            <tr>
                <td colspan="2">3. Prosessing : </td>
                <td colspan="2">3. Blok parafin tidak ada fragmentasi?</td>
            </tr>
            <tr>
                <td colspan="2">4. Embeding (HPA) : </td>
                <td colspan="2">4. Sediaan tanpa lipatan?</td>
            </tr>
            <tr>
                <td colspan="2">5. Mikrotomi (HPA) : </td>
                <td colspan="2">5. sediaan tanpa goresan mata pisau?</td>
            </tr>
            <tr>
                <td colspan="2">6. Pewarnaan : </td>
                <td colspan="2">6. Kontras warna sediaan cukup jelas?</td>
            </tr>
            <tr>
                <td colspan="2">7. Entelan : </td>
                <td colspan="2">7. Sediaan tanpa gelembung?</td>
            </tr>
            <tr>
                <td colspan="2">8. Selesai : </td>
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
                <td colspan="1" class="gambar"></td>
                <td colspan="1" class="gambar"></td>
                <td colspan="1" class="gambar"></td>
                <td colspan="1" class="gambar"></td>
                <td colspan="1" class="gambar"></td>
                <td colspan="1" class="gambar"></td>
            </tr>
            <tr>
                <td colspan="1" class="gambar"></td>
                <td colspan="1" class="gambar"></td>
                <td colspan="1" class="gambar"></td>
                <td colspan="1" class="gambar"></td>
                <td colspan="1" class="gambar"></td>
                <td colspan="1" class="gambar"></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
