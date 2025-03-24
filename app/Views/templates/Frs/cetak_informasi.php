<script>
    function cetakProses() {
        var dokter_pemeriksa = document.getElementById('dokter_pemeriksa') ? document.getElementById('dokter_pemeriksa').value : '';
        var nama_hubungan_pasien = document.getElementById('nama_hubungan_pasien') ?
            (document.getElementById('nama_hubungan_pasien').value === 'lainnya' ?
                document.getElementById('nama_lainnya').value :
                document.getElementById('nama_hubungan_pasien').value) :
            '';
        var hubungan_dengan_pasien = document.getElementById('hubungan_dengan_pasien') ? document.getElementById('hubungan_dengan_pasien').value : '';
        var jenis_kelamin_hubungann_pasien = document.getElementById('jenis_kelamin_hubungann_pasien') ? document.getElementById('jenis_kelamin_hubungann_pasien').value : '';
        var usia_hubungan_pasien = document.getElementById('usia_hubungan_pasien') ? document.getElementById('usia_hubungan_pasien').value : '';
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
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }
        .header td {
            border-right: none;
            border-left: none;
        }
        .signature {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        .signature div {
            width: 30%;
            text-align: center;
        }
        .logo-img {
            width: 60px;
            height: auto;
        }
        .header-table {
            width: 100%;
            font-size: 12px;
            margin: auto;
        }
        .header-table td {
            padding: 3px;
        }
        .rm-label {
            font-size: 14px;
            font-weight: bold;
            text-align: right;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <p class="rm-label">RM 09 PA</p>
        <table class="header-table">
            <tr class="header">
                <td style="width: 20%; text-align: center; border-right: none;">
                    <img src="<?= base_url('img/LogoPemkot.png') ?>" class="logo-img">
                </td>
                <td style="width: 50%; text-align: center; font-weight: bold; border-left: none;">
                    PEMERINTAH KOTA SURABAYA<br>
                    RUMAH SAKIT UMUM DAERAH<br>
                    dr. MOHAMAD SOEWANDHIE SURABAYA<br>
                    Jl. Tambakrejo No. 45 - 47, KOTA SURABAYA<br>
                    TELP. 0313717141, 3725905<br><br>
                    <b>INFORMED CONSENT TINDAKAN FNAB</b>
                </td>
                <td style="width: 50%; font-size: 16px; text-align: left;">
                    Nama (<?= esc($frs['jenis_kelamin_pasien'] ?? '') ?>): <?= esc($frs['nama_pasien'] ?? '') ?><br>
                    No. RM: <?= esc($frs['norm_pasien'] ?? '') ?><br>
                    Tgl. Lahir: <?= isset($frs['tanggal_lahir_pasien']) ? date('d-m-Y', strtotime($frs['tanggal_lahir_pasien'])) : '' ?><br>
                    Alamat: <?= esc($frs['alamat_pasien'] ?? '') ?><br>
                </td>
            </tr>
        </table>
                
        <table>
            <tr>
                <th colspan="2" style="text-align: center;">PEMBERIAN INFORMASI</th>
            </tr>
            <tr>
                <td>Dokter Pelaksana Tindakan</td>
                <td>${dokter_pemeriksa}</td>
            </tr>
            <tr>
                <td>Pemberi Informasi</td>
                <td>${dokter_pemeriksa}</td>
            </tr>
            <tr>
                <td>Penerima Informasi/Pemberi Persetujuan</td>
                <td>Nama: ${nama_hubungan_pasien} | Hubungan: ${hubungan_dengan_pasien}</td>
            </tr>
        </table>
        
        <table>
            <tr>
                <td>1</td>
                <td>Diagnosis Kerja</td>
                <td>1. <?= esc($frs['diagnosa_klinik'] ?? '') ?><br>2. ____________<br>3. ____________</td>
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
                ${dokter_pemeriksa}
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
                ${nama_hubungan_pasien}
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <th colspan="2" style="text-align: center;">PERSETUJUAN TINDAKAN KEDOKTERAN</th>
            </tr>
            <tr>
                <td colspan="2">Yang bertanda tangan di bawah ini, saya, nama ${nama_hubungan_pasien}</td>
            </tr>
            <tr>
                <td>Hubungan dengan pasien</td>
                <td>${hubungan_dengan_pasien}</td>
            </tr>
            <tr>
                <td>Umur</td>
                <td>${usia_hubungan_pasien} tahun, ${jenis_kelamin_hubungann_pasien}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td><?= esc($frs['alamat_pasien'] ?? '') ?></td>
            </tr>
            <tr>
                <td colspan="2">Dengan ini menyatakan setuju untuk dilakukan tindakan <?= esc($frs['tindakan_spesimen'] ?? '') ?> terhadap saya/ _______________ saya*</td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>
                    <?php

                    use CodeIgniter\I18n\Time;
                    // Ambil nama pasien
                    $nama_pasien = esc($frs['nama_pasien'] ?? 'Nama tidak tersedia');
                    // Hitung usia pasien jika tanggal lahir tersedia
                    $usia = '___'; // Default jika tanggal lahir kosong
                    if (!empty($frs['tanggal_lahir_pasien'])) {
                        $tgl_lahir = Time::parse($frs['tanggal_lahir_pasien']);
                        $usia = $tgl_lahir->difference(Time::now())->getYears();
                    }
                    // Tentukan jenis kelamin
                    $jk = $frs['jenis_kelamin_pasien'] ?? '';
                    $jk_laki = ($jk == 'L') ? '☑' : '☐';
                    $jk_perempuan = ($jk == 'P') ? '☑' : '☐';
                    echo "$nama_pasien, Umur $usia tahun, $jk_laki Laki-laki $jk_perempuan Perempuan";
                    ?>
                </td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td><?= esc($frs['alamat_pasien'] ?? '') ?></td>
            </tr>
            <tr>
                <td colspan="2">Komplikasi yang mungkin timbul apabila tindakan tersebut tidak dilakukan. <br>
                Saya bertanggung jawab atas segala akibat yang mungkin timbul sebagai akibat dilakukan tindakan kedokteran tersebut.</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    Surabaya, Tanggal <?= date('d-m-Y') ?>, Jam <?= date('H:i') ?>
                </td>
            </tr>
        </table>

        <div class="signature">
            <div>
                <p>Yang menyatakan</p>
                <br>
                <br>
                <p>(${nama_hubungan_pasien})</p>
            </div>
            <div>
                <p>Saksi I (Dokter)</p>
                <br>
                <br>
                <p>(${dokter_pemeriksa})</p>
            </div>
            <div>
                <p>Saksi II (Analis)</p>
                <br>
                <br>
                <p>(<?= esc($nama_user) ?? ""; ?>)</p>
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