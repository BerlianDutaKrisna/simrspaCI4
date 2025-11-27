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
                        <th>Diagnosa Klinik</th>
                        <th>Pemeriksaan</th>
                        <th>Aksi</th>
                    </tr>
                </thead><tbody>
            `;

                data.data.forEach((row, i) => {

                    const hasilEscaped = row.hasil ?
                        row.hasil
                        .replace(/&/g, "&amp;")
                        .replace(/</g, "&lt;")
                        .replace(/>/g, "&gt;")
                        .replace(/"/g, "&quot;")
                        .replace(/'/g, "&#039;")
                        .replace(/\r?\n/g, "<br>") :
                        "Tidak ada hasil";

                    const tgl = row.tanggal ?
                        new Date(row.tanggal).toLocaleDateString("id-ID") :
                        "-";

                    html += `
                    <tr>
                        <td>${i+1}</td>
                        <td>${tgl}</td>
                        <td>${row.noregister ?? '-'}</td>
                        <td>${row.dokterpa ?? '-'}</td>
                        <td>${row.diagnosaklinik ?? '-'}</td>
                        <td>${row.pemeriksaan ?? '-'}</td>
                        <td>
                            <button class="btn btn-info btn-sm"
                                onclick="tampilkanModal('${hasilEscaped}')">
                                Lihat Detail
                            </button>
                        </td>
                    </tr>
                `;
                });

                html += "</tbody></table></div>";

                container.innerHTML = html;

            })
            .catch(() => {
                container.innerHTML = `<p class="text-danger text-center">Terjadi kesalahan saat mengambil data.</p>`;
            });
    });
</script>


<!-- MODAL DETAIL -->
<div class="modal fade" id="detailHasilModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Detail Hasil Pemeriksaan</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body" id="detailHasilContent"></div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>

        </div>
    </div>
</div>

<script>
    function tampilkanModal(isi) {
        document.getElementById("detailHasilContent").innerHTML = isi;
        $('#detailHasilModal').modal('show');
    }
</script>