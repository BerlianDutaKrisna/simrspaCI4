<?php
$src = '';
$pangkat = '-';
$nip = '-';

if ($pembacaan_frs['dokter_nama'] === "dr. Ayu Tyasmara Pratiwi, Sp.PA") {
    $src = base_url('img/ttd_dr_ayu.png');
    $pangkat = 'Penata';
    $nip = '198407022009022014';
} elseif ($pembacaan_frs['dokter_nama'] === "dr. Vinna Chrisdianti, Sp.PA") {
    $src = base_url('img/ttd_dr_vinna.png');
    $pangkat = '-';
    $nip = '-';
}
?>
<script>
    function cetakPrintfrs() {
        var detailPrintfrs = document.getElementById('print_frs') ? document.getElementById('print_frs').value : '';
        var printWindow = window.open('', '', 'height=500,width=900');
        printWindow.document.write(`
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Radiologi</title>
    <style>
        body {
            width: 346px;
            font-size: 24px;
        }
        table, tr, td {
            border: none;
            margin: 15px;
            font-size: 22px;
        }
        td {
            height: 10px;
            padding: 0;
        }
        .bordered_table, .bordered_table tr, .bordered_table td {
            border: none;
            border-collapse: collapse;
        }
        @media print {
            .header, .hide { visibility: hidden; }
        }
        .break { page-break-before: always; }
    </style>
</head>
<body>
    <table width="900" onload="window.print();">
        <tr>
            <td align="center" nowrap style="font-size: 24px;"><b>RSUD DR. M. SOEWANDHIE</b><br/>
                Jl. Tambakrejo No. 45 - 47, KOTA SURABAYA
            </td>
        </tr>
        <tr>
            <td>
                <table width="500">
                    <tr><td width="100" nowrap>No RM</td><td>:</td><td nowrap>&nbsp;&nbsp;<?= esc($frs['norm_pasien'] ?? '') ?></td></tr>
                    <tr><td nowrap>No Register</td><td>:</td><td nowrap>&nbsp;&nbsp;<?= esc($frs['kode_frs'] ?? '') ?></td></tr>
                    <tr><td nowrap>Nama</td><td>:</td><td>&nbsp; <?= esc($frs['nama_pasien'] ?? '') ?></td></tr>
                    <tr><td>Alamat</td><td>:</td><td>&nbsp; <?= esc($frs['alamat_pasien'] ?? '') ?>&nbsp;</td></tr>
                    <tr><td nowrap>Jenis & Tgl Lahir</td><td>:</td><td nowrap>&nbsp; <?= esc($frs['jenis_kelamin_pasien'] ?? '') ?> / <?= esc($frs['tanggal_lahir_pasien'] ?? '') ?></td></tr>
                    <tr><td nowrap>Permintaan</td><td>:</td><td nowrap>&nbsp; <?= esc($frs['tindakan_spesimen'] ?? '') ?></td></tr>
                    <tr><td nowrap>Unit Asal</td><td>:</td><td nowrap>&nbsp; <?= esc($frs['unit_asal'] ?? '') ?></td></tr>
                    <tr><td nowrap>Dokter Pengirim</td><td>:</td><td nowrap>&nbsp; <?= esc($frs['dokter_pengirim'] ?? '') ?></td></tr>
                </table>
            </td>
            <td>
                <table width="500">
                    <tr>
                        <td align="center" width="500" nowrap colspan="3"><h3>UNIT PATOLOGI ANATOMI</h3></td>
                    </tr>
                    <tr><td align="center" width="500" colspan="3">&nbsp;</td></tr>
                    <tr><td nowrap>Tanggal Terima</td><td>:</td><td>&nbsp; <?= isset($penerimaan['mulai_penerimaan']) ? date('d-m-Y H:i:s', strtotime($penerimaan['mulai_penerimaan'])) : '' ?></td></tr>
                    <tr><td nowrap>Tanggal Hasil</td><td>:</td><td>&nbsp; <?= isset($frs['tanggal_hasil']) ? date('d-m-Y H:i:s', strtotime($frs['tanggal_hasil'])) : '' ?></td></tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" colspan="2">&nbsp;<b>HASIL PEMERIKSAAN</b></td>
        </tr>
        <tr height="500">
            <td colspan="2" height="400" valign="top">
                ${detailPrintfrs}
            </td>
        </tr>
        <tr>
    <td width="650">&nbsp;</td>
    <td>
        <table>
            <tr>
                <td style="padding-bottom: 10px;"></td>
                <td style="padding-bottom: 10px;">
                <?php
                $bulan = [
                    'Januari',
                    'Februari',
                    'Maret',
                    'April',
                    'Mei',
                    'Juni',
                    'Juli',
                    'Agustus',
                    'September',
                    'Oktober',
                    'November',
                    'Desember'
                ];
                $tanggal = date('d');
                $bulanIndo = $bulan[date('n') - 1];
                $tahun = date('Y');
                ?>
                Surabaya, <?= $tanggal . ' ' . $bulanIndo . ' ' . $tahun ?>
                </td>
            </tr>
            <tr>
                <!-- Kolom gambar -->
                <td width="170" align="center" style="padding-right: 10px;">
                    <img src="<?= $src ?>" alt="Tanda Tangan" style="width: 200px;">
                </td>
                <!-- Kolom teks -->
                <td style="vertical-align: middle;">
                    <p style="margin: 0; font-size: 14px;">Hasil lab ini telah ditandatangani secara elektronik oleh:</p>
                    <p style="margin: 0; font-size: 20px;">Dokter Spesialis Patologi Anatomi,</p>
                    <br>
                    <br>
                    <p style="margin: 0; font-size: 20px; font-weight: bold;"><?= esc($pembacaan_frs['dokter_nama'] ?? '____________________') ?></p>
                    <p style="margin: 5px 0 0;"><?= $pangkat ?></p>
                    <p style="margin: 0;">NIP. <?= $nip ?></p>
                </td>
            </tr>
        </table>
    </td>
</tr>
    </table>
</body>
</html>
        `);
        printWindow.document.close();
        printWindow.print();
    }
</script>