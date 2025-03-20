<script>
    function cetakProses() {
        var detailMakroskopis = document.getElementById('makroskopis_frs') ? document.getElementById('makroskopis_frs').value : '';
        var detailMikroskopis = document.getElementById('mikroskopis_frs') ? document.getElementById('mikroskopis_frs').value : '';
        var printWindow = window.open('', '', 'height=500,width=800');
        printWindow.document.write(`
    <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informed Consent FNAB</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 850px;
            margin: 20px auto;
            padding: 20px;
            border: 2px solid black;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        .header td {
            border-right: none;
            border-left: none;
        }
        .signature {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .signature div {
            width: 30%;
            text-align: center;
        }
        .logo-img {
            width: 100px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <table>
            <tr class="header">
                <td style="width: 20%; text-align: center; border-right: none;">
                    <img src="<?= base_url('img/LogoPemkot.png') ?>" class="logo-img">
                </td>
                <td style="width: 50%; text-align: center; font-weight: bold; border-left: none;">
                    PEMERINTAH KOTA SURABAYA<br>
                    RUMAH SAKIT UMUM DAERAH<br>
                    dr. MOHAMAD SOEWANDHIE SURABAYA<br>
                    Jl. Tambakrejo No. 45 - 47, KOTA SURABAYA<br>
                    TELP. 0313717141, 3725905<br>
                    <br>
                    <b>INFORMED CONSENT TINDAKAN FNAB</b>
                </td>
                <td style="width: 30%;">
                    Nama (L/P): _______________________<br>
                    No. RM: _______________________<br>
                    Tgl. Lahir: _______________________<br>
                    Alamat: _______________________<br>
                </td>
            </tr>
        </table>
        
        <table>
            <tr>
                <th colspan="2" style="text-align: center;">PEMBERIAN INFORMASI</th>
            </tr>
            <tr>
                <td>Dokter Pelaksana Tindakan</td>
                <td>dr. Vinna Chrisdianti, Sp.PA / dr. Ayu Tyasmara P, Sp.PA</td>
            </tr>
            <tr>
                <td>Pemberi Informasi</td>
                <td>dr. Vinna Chrisdianti, Sp.PA / dr. Ayu Tyasmara P, Sp.PA</td>
            </tr>
            <tr>
                <td>Penerima Informasi/Pemberi Persetujuan</td>
                <td>Nama: _______________ Hubungan: _______________</td>
            </tr>
        </table>
        
        <table>
            <tr>
                <td>1</td>
                <td>Diagnosis Kerja</td>
                <td>1. ____________<br>2. ____________<br>3. ____________</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Dasar Diagnosis</td>
                <td>Surat rujukan SMF lain</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Indikasi Tindakan</td>
                <td>nodul / massa</td>
            </tr>
            <tr>
                <td>4</td>
                <td>Tata Cara</td>
                <td>Swab dengan kapas alkohol, Suntik dengan jarum 25G / 27G atau spinal 25G</td>
            </tr>
            <tr>
                <td>5</td>
                <td>Tujuan</td>
                <td>Untuk memastikan diagnosis</td>
            </tr>
            <tr>
                <td>6</td>
                <td>Risiko</td>
                <td>Terjadi pneumothorax saat FNAB dengan CT Scan guiding</td>
            </tr>
            <tr>
                <td>7</td>
                <td>Komplikasi</td>
                <td>Infeksi, perdarahan di tempat suntikan</td>
            </tr>
            <tr>
                <td>8</td>
                <td>Lain-lain</td>
                <td>_______________________________</td>
            </tr>
        </table>

        <table>
            <tr>
                <td colspan="2">Dengan ini menyatakan bahwa saya telah menerangkan hal-hal di atas secara benar dan jelas serta memberikan kesempatan untuk bertanya dan/atau berdiskusi</td>
                <td style="text-align: center;">
                Tanda Tangan
                <br>
                <br>
                <br>
                <br>
                </td>
            </tr>
            <tr>
                <td colspan="2">Dengan ini menyatakan bahwa saya telah menerima informasi sebagaimana di atas yang saya beri tanda tangan/paraf di kolom kanannya, dan telah memahaminya</td>
                <td style="text-align: center;">
                Tanda Tangan
                <br>
                <br>
                <br>
                <br>
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <th colspan="2" style="text-align: center;">PERSETUJUAN TINDAKAN KEDOKTERAN</th>
            </tr>
            <tr>
                <td colspan="2">Yang bertanda tangan di bawah ini, saya, nama _______________</td>
            </tr>
            <tr>
                <td>Hubungan dengan pasien</td>
                <td>□ Pasien sendiri □ Orang tua □ Anak □ Istri □ Suami □ Saudara □ Pengantar</td>
            </tr>
            <tr>
                <td>Umur</td>
                <td>________ tahun, □ Laki-laki □ Perempuan</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>__________________________</td>
            </tr>
            <tr>
                <td colspan="2">Dengan ini menyatakan setuju untuk dilakukan tindakan _______________ terhadap saya/ _______________ saya*</td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>__________________________, Umur ______ tahun, □ Laki-laki □ Perempuan</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>__________________________</td>
            </tr>
            <tr>
                <td colspan="2">Komplikasi yang mungkin timbul apabila tindakan tersebut tidak dilakukan. <br>
                Saya bertanggung jawab atas segala akibat yang mungkin timbul sebagai akibat dilakukan tindakan kedokteran tersebut.</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">Surabaya, Tanggal _______________, Jam _______________</td>
            </tr>
        </table>

        <div class="signature">
            <div>
                <p>Yang menyatakan</p>
                <br>
                <br>
                <p>(________________)</p>
            </div>
            <div>
                <p>Saksi I (Dokter)</p>
                <br>
                <br>
                <p>(________________)</p>
            </div>
            <div>
                <p>Saksi II (Analis)</p>
                <br>
                <br>
                <p>(________________)</p>
            </div>
        </div>
    </div>
</body>
</html>
    `);

        printWindow.document.close();
        printWindow.print();
    }
</script>