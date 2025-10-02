<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Judul Halaman -->
    <div class="row">
        <!-- Histopatologi RESUME -->
        <div class="col mb-4">
            <a href="<?= base_url('exam/index') ?>" class="stretched-link" style="text-decoration: none;" disabled>
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
        <div class="col mb-4">
            <a href="<?= base_url('/api/kunjungan/index') ?>" class="stretched-link" style="text-decoration: none;" disabled>
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
        <div class="col mb-4">
            <a href="#" style="text-decoration: none;" disabled>
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
        <div class="col mb-4">
            <a href="#" class="stretched-link" style="text-decoration: none;" disabled>
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
        <div class="col mb-4">
            <a href="#" class="stretched-link" style="text-decoration: none;" disabled>
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
        <div class="modal fade" id="riwayatModal" tabindex="-1" role="dialog" aria-labelledby="riwayatModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-notes-medical"></i> Riwayat Pemeriksaan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <!-- Input norm_pasien dan tombol cari -->
                        <form id="formCariRiwayat" class="form-inline mb-3">
                            <label for="inputNormPasien" class="sr-only">Norm Pasien</label>
                            <input type="text" id="inputNormPasien" class="form-control mr-2" placeholder="Masukkan Norm Pasien" maxlength="6" pattern="\d{6}" required>
                            <button type="submit" class="btn btn-info">Cari</button>
                        </form>

                        <!-- Area hasil pencarian -->
                        <div id="hasilRiwayatContainer">
                            <p class="text-muted">Silakan masukkan Norm Pasien dan klik Cari untuk menampilkan riwayat pemeriksaan.</p>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col mb-4">
            <a href="#" class="stretched-link" style="text-decoration:none;" data-toggle="modal" data-target="#fotoModal">
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

        <div class="col mb-4">
            <a href="#" id="cekKoneksiLink" class="stretched-link" style="text-decoration:none;">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-s font-weight-bold text-secondary text-uppercase mb-1">
                                    Cek Koneksi <strong class="text-primary">SIMRS</strong>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-wifi fa-4x text-primary-500"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<script>
    document.getElementById('cekKoneksiLink').addEventListener('click', function(e) {
        e.preventDefault();
        this.style.pointerEvents = 'none';

        // Tambahkan loading indicator sederhana (opsional)
        this.querySelector('.text-s').textContent = 'Memeriksa koneksi...';

        fetch('<?= base_url('api/koneksi') ?>')
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Koneksi berhasil:\n' + data.message);
                } else {
                    alert('Koneksi gagal:\n' + data.message);
                }
            })
            .catch(() => alert('Terjadi kesalahan saat cek koneksi.'));
    });
</script>

<script>
    document.getElementById('formCariRiwayat').addEventListener('submit', function(e) {
        e.preventDefault();
        const norm = document.getElementById('inputNormPasien').value.trim();

        if (!/^\d{6}$/.test(norm)) {
            alert('Norm Pasien harus 6 digit angka.');
            return;
        }

        // Tampilkan loading sementara
        const container = document.getElementById('hasilRiwayatContainer');
        container.innerHTML = `<p class="text-center text-primary">Memuat data...</p>`;

        // Fetch ke API (ubah url sesuai routes API Anda)
        fetch(`<?= base_url('api/pemeriksaan/norm_pasien') ?>/${norm}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' && data.data.length > 0) {
                    // Bangun tabel hasil
                    let html = `<div class="table-responsive">
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
                        </thead>
                        <tbody>`;

                    data.data.forEach((row, i) => {
                        const tanggal = row.tanggal ? new Date(row.tanggal).toLocaleDateString('id-ID') : '-';
                        // Escape hasil untuk ditampilkan aman di HTML dan JavaScript
                        const hasilEscaped = row.hasil ? row.hasil.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;").replace(/\n/g, "<br>") : 'Tidak ada hasil';
                        const tanggalFormatted = row.tanggal ?
                            new Date(row.tanggal).toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric'
                            }) :
                            '-';
                        html += `<tr>
                    <td>${i + 1}</td>
                    <td>${tanggalFormatted}</td>
                    <td>${row.noregister ?? '-'}</td>
                    <td>${row.dokterpa ?? '-'}</td>
                    <td>${row.diagnosaklinik ?? '-'}</td>
                    <td>${row.pemeriksaan ?? '-'}</td>
                    <td>
                        <button type="button" class="btn btn-info btn-sm"
                        onclick="tampilkanModal('${hasilEscaped}')">
                        Lihat Detail
                        </button>
                    </td>
                    </tr>`;
                    });

                    html += `</tbody></table></div>`;
                    container.innerHTML = html;
                } else {
                    container.innerHTML = `<p class="text-muted">Tidak ada data riwayat pemeriksaan tersedia.</p>`;
                }
            })
            .catch(() => {
                container.innerHTML = `<p class="text-danger text-center">Terjadi kesalahan saat mengambil data.</p>`;
            });
    });

    // Fungsi modal untuk menampilkan hasil detail
    function tampilkanModal(hasilHtml) {
        const container = document.getElementById('detailHasilContent');
        container.innerHTML = hasilHtml;
        $('#detailHasilModal').modal('show');
    }
</script>

<!-- Modal Detail Hasil -->
<div class="modal fade" id="detailHasilModal" tabindex="-1" aria-labelledby="detailHasilModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailHasilModalLabel">Detail Hasil Pemeriksaan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detailHasilContent">
                <!-- Isi hasil akan dimasukkan di sini -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>