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
        console.log("FULL RESPONSE:", data);

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
                        Lihat detail / Cetak
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

            <!-- 🔥 HASIL LANGSUNG DARI API -->
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
    currentRow = row; // 🔥 simpan untuk print

    let isi = row.hasil ?? '<i>Tidak ada hasil</i>';

    // 🔥 LANGSUNG tampilkan HTML dari API
    document.getElementById("detailHasilContent").innerHTML = isi;

    // 🔥 simpan untuk print
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

    let src = '';
    let nip = '-';

    if (currentRow.dokterpa === "dr. Ayu Tyasmara Pratiwi, Sp.PA") {
        src = "<?= base_url('img/ttd_dr_ayu.png') ?>";
        nip = "198407022009022014";
    } else if (currentRow.dokterpa === "dr. Vinna Chrisdianti, Sp.PA") {
        src = "<?= base_url('img/ttd_dr_vinna.png') ?>";
        nip = "198303152023212002";
    }

    const bulan = [
        'Januari','Februari','Maret','April','Mei','Juni',
        'Juli','Agustus','September','Oktober','November','Desember'
    ];

    let tgl = "-";
    if (currentRow.tanggal) {
        let d = new Date(currentRow.tanggal);
        tgl = `${d.getDate()} ${bulan[d.getMonth()]} ${d.getFullYear()}`;
    }

    let printWindow = window.open('', '', 'height=700,width=900');

    printWindow.document.write(`
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Laporan Patologi Anatomi</title>
<style>
    body {
        width: 900px;
        font-size: 22px;
    }
    table, tr, td {
        border: none;
        margin: 10px;
        font-size: 20px;
    }
    td {
        padding: 0;
        vertical-align: top;
    }
</style>
</head>

<body onload="window.print()">

<table width="900">
<!-- HEADER -->
<tr>
    <td align="center" colspan="2" style="font-size:24px;">
        <b>RSUD DR. M. SOEWANDHIE</b><br/>
        Jl. Tambakrejo No. 45 - 47, KOTA SURABAYA
    </td>
</tr>

<!-- IDENTITAS -->
<tr>
    <td>
        <table width="100%">
            <tr><td width="150">No RM</td><td>:</td><td>${currentRow.norm ?? ''}</td></tr>
            <tr><td>No Register</td><td>:</td><td>${currentRow.noregister ?? ''}</td></tr>
            <tr><td>Nama</td><td>:</td><td>${currentRow.nama ?? ''}</td></tr>
            <tr><td>Alamat</td><td>:</td><td>${currentRow.alamat ?? ''}</td></tr>
            <tr><td>Jenis / Tgl Lahir</td><td>:</td>
                <td>${currentRow.jenispasien ?? ''} / ${currentRow.tanggal ?? ''}</td></tr>
            <tr><td>Permintaan</td><td>:</td><td>${currentRow.pemeriksaan ?? ''}</td></tr>
            <tr><td>Unit Asal</td><td>:</td><td>${currentRow.unitasal ?? ''}</td></tr>
            <tr><td>Dokter Pengirim</td><td>:</td><td>${currentRow.dokterperujuk ?? ''}</td></tr>
        </table>
    </td>

    <td>
        <table width="100%">
            <tr>
                <td align="center" colspan="3"><b>UNIT PATOLOGI ANATOMI</b></td>
            </tr>
            <tr><td colspan="3">&nbsp;</td></tr>
            <tr>
                <td width="150">Tanggal Terima</td><td>:</td>
                <td>${currentRow.tanggal ?? ''}</td>
            </tr>
            <tr>
                <td>Tanggal Hasil</td><td>:</td>
                <td>${currentRow.tanggal ?? ''}</td>
            </tr>
        </table>
    </td>
</tr>

<!-- JUDUL HASIL -->
<tr>
    <td align="center" colspan="2"><b>HASIL PEMERIKSAAN</b></td>
</tr>

<!-- ISI HASIL DARI API -->
<tr>
    <td colspan="2">
        ${currentRow.hasil ?? ''}
    </td>
</tr>

<tr>
<td></td>
<td>
Surabaya, ${tgl}<br><br>
<img src="${src}" style="width:150px;"><br>
<b>${currentRow.dokterpa ?? ''}</b><br>
NIP. ${nip}
</td>
</tr>

</table>

</body>
</html>
    `);

    printWindow.document.close();
}
</script>