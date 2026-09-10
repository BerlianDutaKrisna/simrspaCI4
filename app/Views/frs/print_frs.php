<?php
$tanggalTerima = $penerimaan['mulai_penerimaan_frs'] ?? ($frs['mulai_penerimaan_frs'] ?? '');
$tanggalHasil  = $frs['tanggal_hasil'] ?? '';
$tanggalLahir  = $frs['tanggal_lahir_pasien'] ?? '';

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
$timestampSurabaya = !empty($tanggalHasil) ? strtotime($tanggalHasil) : time();
$tanggalSurabaya = date('d', $timestampSurabaya) . ' ' . $bulan[(int) date('n', $timestampSurabaya) - 1] . ' ' . date('Y', $timestampSurabaya);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Hasil Pemeriksaan FRS - <?= esc($frs['kode_frs'] ?? '') ?></title>
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
<body onload="window.print();">
    <table width="900">
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
                    <tr>
                        <td nowrap>Jenis & Tgl Lahir</td>
                        <td>:</td>
                        <td nowrap>&nbsp; <?= esc($frs['jenis_kelamin_pasien'] ?? '') ?> / <?= !empty($tanggalLahir) ? esc(date('d-m-Y', strtotime($tanggalLahir))) : '' ?></td>
                    </tr>
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
                    <tr>
                        <td nowrap>Tanggal Terima</td>
                        <td>:</td>
                        <td>&nbsp; <?= !empty($tanggalTerima) ? date('d-m-Y H:i:s', strtotime($tanggalTerima)) : '' ?></td>
                    </tr>
                    <tr>
                        <td nowrap>Tanggal Hasil</td>
                        <td>:</td>
                        <td>&nbsp; <?= !empty($tanggalHasil) ? date('d-m-Y', strtotime($tanggalHasil)) : '' ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" colspan="2">&nbsp;<b>HASIL PEMERIKSAAN</b></td>
        </tr>
        <tr height="500">
            <td colspan="2" height="400" valign="top">
                <?= $frs['print_frs'] ?? '' ?>
            </td>
        </tr>
        <tr>
            <td width="650">&nbsp;</td>
            <td>
                <table>
                    <tr>
                        <td style="padding-bottom: 10px;"></td>
                        <td style="padding-bottom: 10px;">
                            Surabaya, <?= $tanggalSurabaya ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="170" align="center" style="padding-right: 10px;">
                            <img src="<?= base_url('img/ttd_dr_ayu.png') ?>" alt="Tanda Tangan" style="width: 200px;">
                        </td>
                        <td style="vertical-align: middle;">
                            <p style="margin: 0; font-size: 14px;">Hasil lab ini telah ditandatangani secara elektronik oleh:</p>
                            <p style="margin: 0; font-size: 20px;">Dokter Spesialis Patologi Anatomi,</p>
                            <br>
                            <br>
                            <p style="margin: 0; font-size: 20px; font-weight: bold;">dr. AYU TYASMARA PRATIWI, Sp.PA</p>
                            <p style="margin: 5px 0 0;">Penata</p>
                            <p style="margin: 0;">NIP. 198407022009022014</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
