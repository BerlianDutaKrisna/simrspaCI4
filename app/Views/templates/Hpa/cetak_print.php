<?php
$src = '';
$pangkat = '-';
$nip = '-';

if ($pembacaan_hpa['dokter_nama'] === "dr. Ayu Tyasmara Pratiwi, Sp.PA") {
    $src = base_url('img/ttd_dr_ayu.png');
    $nip = '198407022009022014';
} elseif ($pembacaan_hpa['dokter_nama'] === "dr. Vinna Chrisdianti, Sp.PA") {
    $src = base_url('img/ttd_dr_vinna.png');
    $nip = '198303152023212002';
}
?>
<script>
    function cetakPrintHpa() {
        var detailPrinthpa = document.getElementById('print_hpa') ? document.getElementById('print_hpa').value : '';
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
                    <tr><td width="100" nowrap>No RM</td><td>:</td><td nowrap>&nbsp;&nbsp;<?= esc($hpa['norm_pasien'] ?? '') ?></td></tr>
                    <tr><td nowrap>No Register</td><td>:</td><td nowrap>&nbsp;&nbsp;<?= esc($hpa['kode_hpa'] ?? '') ?></td></tr>
                    <tr><td nowrap>Nama</td><td>:</td><td>&nbsp; <?= esc($hpa['nama_pasien'] ?? '') ?></td></tr>
                    <tr><td>Alamat</td><td>:</td><td>&nbsp; <?= esc($hpa['alamat_pasien'] ?? '') ?>&nbsp;</td></tr>
                    <tr><td nowrap>Jenis & Tgl Lahir</td><td>:</td><td nowrap>&nbsp; <?= esc($hpa['jenis_kelamin_pasien'] ?? '') ?> / <?= esc($hpa['tanggal_lahir_pasien'] ?? '') ?></td></tr>
                    <tr><td nowrap>Permintaan</td><td>:</td><td nowrap>&nbsp; <?= esc($hpa['tindakan_spesimen'] ?? '') ?></td></tr>
                    <tr><td nowrap>Unit Asal</td><td>:</td><td nowrap>&nbsp; <?= esc($hpa['unit_asal'] ?? '') ?></td></tr>
                    <tr><td nowrap>Dokter Pengirim</td><td>:</td><td nowrap>&nbsp; <?= esc($hpa['dokter_pengirim'] ?? '') ?></td></tr>
                </table>
            </td>
            <td>
                <table width="500">
                    <tr>
                        <td align="center" width="500" nowrap colspan="3"><h3>UNIT PATOLOGI ANATOMI</h3></td>
                    </tr>
                    <tr><td align="center" width="500" colspan="3">&nbsp;</td></tr>
                    <tr><td nowrap>Tanggal Terima</td><td>:</td><td>&nbsp; <?= isset($hpa['mulai_penerimaan_hpa']) ? date('d-m-Y H:i:s', strtotime($hpa['mulai_penerimaan_hpa'])) : '' ?></td></tr>
                    <tr><td nowrap>Tanggal Hasil</td><td>:</td><td>&nbsp; <?= isset($hpa['selesai_authorized_hpa']) ? date('d-m-Y H:i:s', strtotime($hpa['selesai_authorized_hpa'])) : '' ?></td></tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" colspan="2">&nbsp;<b>HASIL PEMERIKSAAN</b></td>
        </tr>
        <tr height="500">
            <td colspan="2" height="400" valign="top">
                ${detailPrinthpa}
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

                // Cek apakah tanggal selesai_penulisan ada dan valid
                if (!empty($hpa['selesai_authorized_hpa'])) {
                    $timestamp = strtotime($hpa['selesai_authorized_hpa']);
                    $tanggal = date('d', $timestamp);
                    $bulanIndo = $bulan[date('n', $timestamp) - 1];
                    $tahun = date('Y', $timestamp);
                    echo "Surabaya, {$tanggal} {$bulanIndo} {$tahun}";
                } else {
                    echo "Surabaya, -";
                }
                ?>
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
                    <p style="margin: 0; font-size: 20px; font-weight: bold;"><?= esc($pembacaan_hpa['dokter_nama'] ?? '____________________') ?></p>
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