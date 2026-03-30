<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">

        <!-- CARD 1 -->
        <div class="col mb-4">
            <a href="<?= base_url('exam/index') ?>" class="stretched-link" style="text-decoration: none;">
                <div class="card border-left-secondary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-s font-weight-bold text-secondary text-uppercase mb-1">
                                    Buku Registrasi Laboratorrium Patologi Anatomi
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-book fa-4x text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- CARD 2 -->
        <div class="col mb-4">
            <a href="<?= base_url('/api/kunjungan/index') ?>" class="stretched-link" style="text-decoration: none;">
                <div class="card border-left-secondary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-s font-weight-bold text-danger text-uppercase mb-1">
                                    Sampel Terdaftar Hari ini
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation fa-4x text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- CARD 3 -->
        <div class="col mb-4">
            <a href="#" onclick="return false;" style="text-decoration: none;">
                <div class="card border-left-secondary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-s font-weight-bold text-secondary text-uppercase mb-1">
                                    Buku Ekspedisi Sampel
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-comments fa-4x text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- CARD 4 -->
        <div class="col mb-4">
            <a href="#" onclick="return false;" class="stretched-link" style="text-decoration: none;">
                <div class="card border-left-secondary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-s font-weight-bold text-secondary text-uppercase mb-1">
                                    Buku Peminjaman Blok dan Slides
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fab fa-hive fa-4x text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- CARD 5 -->
        <div class="col mb-4">
            <a href="#" onclick="return false;" class="stretched-link" style="text-decoration: none;">
                <div class="card border-left-secondary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-s font-weight-bold text-secondary text-uppercase mb-1">
                                    Buku Pemusnahan Jaringan
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dumpster fa-4x text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- CARD RIWAYAT -->
        <div class="col mb-4">
            <a href="#" class="stretched-link" style="text-decoration:none;" data-toggle="modal" data-target="#riwayatModal">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-s font-weight-bold text-info text-uppercase mb-1">
                                    Riwayat Pemeriksaan
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-notes-medical fa-4x text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Modal Riwayat -->
        <div class="modal fade" id="riwayatModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-notes-medical"></i> Riwayat Pemeriksaan</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>

                    <div class="modal-body">

                        <form id="formCariRiwayat" class="form-inline mb-3">
                            <input type="text" id="inputNormPasien" class="form-control mr-2" placeholder="Masukkan Norm Pasien" maxlength="6" pattern="\d{6}" required>
                            <button type="submit" class="btn btn-info">Cari</button>
                        </form>

                        <div id="hasilRiwayatContainer">
                            <p class="text-muted">Silakan masukkan Norm Pasien dan klik Cari.</p>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <!-- CARD FOTO -->
        <div class="col mb-4">
            <a href="#" class="stretched-link" data-toggle="modal" data-target="#fotoModal">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-s font-weight-bold text-info text-uppercase mb-1">
                                    Foto Makroskopis / Mikroskopis
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-camera-retro fa-4x text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- CARD CEK KONEKSI -->
        <div class="col mb-4">
            <a href="#" id="cekKoneksiLink" class="stretched-link" style="text-decoration:none;">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div id="cekKoneksiText" class="text-s font-weight-bold text-secondary text-uppercase mb-1">
                                    Cek Koneksi <strong class="text-primary">SIMRS</strong>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-wifi fa-4x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

    </div>
</div>


<!-- SCRIPT CEK KONEKSI -->
<script>
    document.getElementById('cekKoneksiLink').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('cekKoneksiText').textContent = "Memeriksa koneksi...";

        fetch('<?= base_url("api/koneksi") ?>')
            .then(res => res.json())
            .then(data => alert(data.status + " : " + data.message))
            .catch(() => alert("Terjadi kesalahan saat cek koneksi."));
    });
</script>


<!-- SCRIPT RIWAYAT -->
<script>
document.getElementById('formCariRiwayat').addEventListener('submit', function(e) {
    e.preventDefault();

    const norm = document.getElementById('inputNormPasien').value.trim();
    const container = document.getElementById('hasilRiwayatContainer');

    if (!/^\d{6}$/.test(norm)) {
        alert("Norm Pasien harus 6 digit angka.");
        return;
    }

    container.innerHTML = `<p class="text-center text-primary">Memuat data...</p>`;

    fetch(`<?= base_url('api/pemeriksaan/norm_pasien') ?>/${norm}`)
    .then(res => res.json())
    .then(data => {

        if (data.status !== "success" || data.data.length === 0) {
            container.innerHTML = `<p class="text-muted">Tidak ada data tersedia.</p>`;
            return;
        }

        let html = `
        <div class="table-responsive">
        <table class="table table-bordered table-striped table-sm text-center">
        <thead class="thead-dark">
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Kode</th>
            <th>Dokter</th>
            <th>Pemeriksaan</th>
            <th>Aksi</th>
        </tr>
        </thead><tbody>`;

        data.data.forEach((row, i) => {

            const tgl = row.tanggal 
                ? new Date(row.tanggal).toLocaleDateString("id-ID") 
                : "-";

            html += `
            <tr>
                <td>${i+1}</td>
                <td>${tgl}</td>
                <td>${row.noregister ?? '-'}</td>
                <td>${row.dokterpa ?? '-'}</td>
                <td>${row.pemeriksaan ?? '-'}</td>
                <td>
                    <button class="btn btn-info btn-sm"
                        onclick='tampilkanModal(${JSON.stringify(row)})'>
                        <i class="fas fa-eye"></i>
                    </button>
                </td>
            </tr>`;
        });

        html += "</tbody></table></div>";
        container.innerHTML = html;
    });
});
</script>


<!-- MODAL DETAIL -->
<div class="modal fade" id="detailHasilModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Detail Hasil Pemeriksaan</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body" id="detailHasilContent"></div>

            <div class="modal-footer">
                <button class="btn btn-success" onclick="cetakPrintHpa()">
                    <i class="fas fa-print"></i> Cetak
                </button>
                <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>

        </div>
    </div>
</div>

<script>
let currentRow = null;

function tampilkanModal(row) {
    currentRow = row;

    let isi = row.hasil ?? 'Tidak ada hasil';

    isi = isi
        .replace(/\n\s*:\s*\n/g, ' : ')
        .replace(/\n{2,}/g, '\n')
        .replace(/[ \t]+/g, ' ')
        .replace(/\n/g, "<br>");

    document.getElementById("detailHasilContent").innerHTML =
        '<div style="white-space:pre-line; line-height:1.4;">' + isi + '</div>';

    // 🔥 inject ke hidden input untuk cetak
    if (!document.getElementById('print_hpa')) {
        let input = document.createElement("input");
        input.type = "hidden";
        input.id = "print_hpa";
        document.body.appendChild(input);
    }

    document.getElementById('print_hpa').value = isi;

    $('#detailHasilModal').modal('show');
}
</script>

<script>
function cetakPrintHpa() {
    if (!currentRow) return;

    // =======================
    // FORMAT HASIL
    // =======================
    let isi = currentRow.hasil ?? 'Tidak ada hasil';

    isi = isi
        .replace(/\r/g, '')
        .replace(/\n+\s*:\s*\n+/g, ' : ')
        .replace(/[ \t]+/g, ' ')
        .replace(/\n{2,}/g, '\n')
        .split('\n').map(line => line.trim()).join('\n')
        .replace(/\n/g, "<br>");

    // =======================
    // TTD DOKTER
    // =======================
    let src = '';
    let nip = '-';

    if (currentRow.dokterpa === "dr. Ayu Tyasmara Pratiwi, Sp.PA") {
        src = "<?= base_url('img/ttd_dr_ayu.png') ?>";
        nip = "198407022009022014";
    } else if (currentRow.dokterpa === "dr. Vinna Chrisdianti, Sp.PA") {
        src = "<?= base_url('img/ttd_dr_vinna.png') ?>";
        nip = "198303152023212002";
    }

    // =======================
    // TANGGAL INDONESIA
    // =======================
    const bulan = [
        'Januari','Februari','Maret','April','Mei','Juni',
        'Juli','Agustus','September','Oktober','November','Desember'
    ];

    let tgl = "-";
    if (currentRow.tanggal) {
        let d = new Date(currentRow.tanggal);
        tgl = `${d.getDate()} ${bulan[d.getMonth()]} ${d.getFullYear()}`;
    }

    // =======================
    // PRINT WINDOW
    // =======================
    let printWindow = window.open('', '', 'height=700,width=900');

    printWindow.document.write(`
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Patologi Anatomi</title>
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
</style>
</head>

<body onload="window.print()">

<table width="900">

<!-- HEADER -->
<tr>
    <td align="center" nowrap style="font-size:24px;">
        <b>RSUD DR. M. SOEWANDHIE</b><br/>
        Jl. Tambakrejo No. 45 - 47, KOTA SURABAYA
    </td>
</tr>

<!-- IDENTITAS -->
<tr>
    <td>
        <table width="500">
            <tr><td width="100">No RM</td><td>:</td><td>${currentRow.norm_pasien ?? ''}</td></tr>
            <tr><td>No Register</td><td>:</td><td>${currentRow.noregister ?? ''}</td></tr>
            <tr><td>Nama</td><td>:</td><td>${currentRow.nama_pasien ?? ''}</td></tr>
            <tr><td>Alamat</td><td>:</td><td>${currentRow.alamat ?? ''}</td></tr>
            <tr><td>Jenis / Tgl Lahir</td><td>:</td><td>${currentRow.jenis_kelamin ?? ''} / ${currentRow.tanggal_lahir ?? ''}</td></tr>
            <tr><td>Permintaan</td><td>:</td><td>${currentRow.pemeriksaan ?? ''}</td></tr>
            <tr><td>Unit Asal</td><td>:</td><td>${currentRow.unit_asal ?? ''}</td></tr>
            <tr><td>Dokter Pengirim</td><td>:</td><td>${currentRow.dokter_pengirim ?? ''}</td></tr>
        </table>
    </td>

    <td>
        <table width="500">
            <tr>
                <td align="center" colspan="3"><h3>UNIT PATOLOGI ANATOMI</h3></td>
            </tr>
            <tr><td colspan="3">&nbsp;</td></tr>
            <tr>
                <td>Tanggal Terima</td><td>:</td>
                <td>${currentRow.tanggal ?? ''}</td>
            </tr>
            <tr>
                <td>Tanggal Hasil</td><td>:</td>
                <td>${currentRow.tanggal ?? ''}</td>
            </tr>
        </table>
    </td>
</tr>

<!-- HASIL -->
<tr>
    <td align="center" colspan="2"><b>HASIL PEMERIKSAAN</b></td>
</tr>

<tr>
    <td colspan="2" valign="top">
        ${isi}
    </td>
</tr>

<!-- TTD -->
<tr>
<td width="650">&nbsp;</td>
<td>
    <table>
        <tr>
            <td></td>
            <td>Surabaya, ${tgl}</td>
        </tr>
        <tr>
            <td width="170" align="center">
                <img src="${src}" style="width:150px;">
            </td>
            <td>
                <p style="margin:0;font-size:14px;">Hasil lab ini telah ditandatangani secara elektronik oleh:</p>
                <p style="margin:0;font-size:20px;">Dokter Spesialis Patologi Anatomi,</p>
                <br><br>
                <p style="margin:0;font-size:20px;font-weight:bold;">
                    ${currentRow.dokterpa ?? '____________________'}
                </p>
                <p style="margin:0;">NIP. ${nip}</p>
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
}
</script>