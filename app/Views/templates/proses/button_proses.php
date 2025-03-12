            <!-- Tombol -->
            <div class="row">
                <div class="form-group col-12 col-md-6">
                    <button id="btnMulai" type="button" class="btn btn-primary btn-user btn-block" onclick="setAction('mulai')" disabled>
                        <i class="fas fa-play"></i> Mulai
                    </button>
                </div>
                <div class="form-group col-12 col-md-6">
                    <button id="btnSelesai" type="button" class="btn btn-success btn-user btn-block" onclick="setAction('selesai')" disabled>
                        <i class="fas fa-pause"></i> Selesai
                    </button>
                </div>
                <div class="form-group col-12">
                    <button id="btnLanjut" type="button" class="btn btn-info btn-user btn-block" onclick="setAction('lanjut')" disabled>
                        <i class="fas fa-step-forward"></i> Lanjutkan
                    </button>
                </div>
                <div class="form-group col-12">
                    <button id="btnReset" type="button" class="btn btn-warning btn-user btn-block" onclick="setAction('reset')" disabled>
                        <i class="fas fa-undo-alt"></i> Reset
                    </button>
                </div>
                <div class="form-group col-12">
                    <button id="btnKembalikan" type="button" class="btn btn-danger btn-user btn-block" onclick="setAction('kembalikan')" disabled>
                        <i class="fas fa-fast-backward"></i></i> Kembalikan
                    </button>
                </div>
            </div>
            </form>
            </div>
            </div>

            <script>
                function toggleButtons() {
                    const checkboxes = document.querySelectorAll('.checkbox-item');
                    const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

                    // Ambil tombol
                    const btnMulai = document.getElementById('btnMulai');
                    const btnSelesai = document.getElementById('btnSelesai');
                    const btnLanjut = document.getElementById('btnLanjut');
                    const btnReset = document.getElementById('btnReset');
                    const btnKembalikan = document.getElementById('btnKembalikan');

                    // Aktifkan atau nonaktifkan tombol berdasarkan status checkbox
                    btnReset.disabled = !isChecked;
                    btnKembalikan.disabled = !isChecked;

                    // Periksa khusus untuk tombol "Mulai"
                    const validForMulai = Array.from(checkboxes).some(checkbox => {
                        if (checkbox.checked) {
                            const statuses = JSON.parse(checkbox.dataset.status); // Parse JSON
                            return statuses.status_penerimaan_hpa === "Belum Penerimaan" ||
                                statuses.status_penerimaan_frs === "Belum Penerimaan" ||
                                statuses.status_penerimaan_srs === "Belum Penerimaan" ||
                                statuses.status_penerimaan_ihc === "Belum Penerimaan" ||
                                statuses.status_pengirisan_hpa === "Belum Pengirisan" ||
                                statuses.status_pemotongan_hpa === "Belum Pemotongan" ||
                                statuses.status_pemprosesan_hpa === "Belum Pemprosesan" ||
                                statuses.status_penanaman_hpa === "Belum Penanaman" ||
                                statuses.status_pemotongan_tipis_hpa === "Belum Pemotongan Tipis" ||
                                statuses.status_pewarnaan_hpa === "Belum Pewarnaan" ||
                                statuses.status_pembacaan_hpa === "Belum Pembacaan" ||
                                statuses.status_pembacaan_frs === "Belum Pembacaan" ||
                                statuses.status_pembacaan_srs === "Belum Pembacaan" ||
                                statuses.status_pembacaan_ihc === "Belum Pembacaan" ||
                                statuses.status_penulisan_hpa === "Belum Penulisan" ||
                                statuses.status_penulisan_frs === "Belum Penulisan" ||
                                statuses.status_penulisan_srs === "Belum Penulisan" ||
                                statuses.status_penulisan_ihc === "Belum Penulisan" ||
                                statuses.status_pemverifikasi_hpa === "Belum Pemverifikasi" ||
                                statuses.status_pemverifikasi_frs === "Belum Pemverifikasi" ||
                                statuses.status_pemverifikasi_srs === "Belum Pemverifikasi" ||
                                statuses.status_pemverifikasi_ihc === "Belum Pemverifikasi" ||
                                statuses.status_authorized_hpa === "Belum Authorized" ||
                                statuses.status_authorized_frs === "Belum Authorized" ||
                                statuses.status_authorized_srs === "Belum Authorized" ||
                                statuses.status_authorized_ihc === "Belum Authorized" ||
                                statuses.status_pencetakan_hpa === "Belum Pencetakan" ||
                                statuses.status_pencetakan_frs === "Belum Pencetakan" ||
                                statuses.status_pencetakan_srs === "Belum Pencetakan" ||
                                statuses.status_pencetakan_ihc === "Belum Pencetakan" ;
                        }
                        return false;
                    });
                    btnMulai.disabled = !validForMulai;
                    btnKembalikan.disabled = !validForMulai;

                    // Periksa khusus untuk tombol "Selesai"
                    const validForSelesai = Array.from(checkboxes).some(checkbox => {
                        if (checkbox.checked) {
                            const statuses = JSON.parse(checkbox.dataset.status); // Parse JSON
                            return statuses.status_penerimaan_hpa === "Proses Penerimaan" ||
                                statuses.status_penerimaan_frs === "Proses Penerimaan" ||
                                statuses.status_penerimaan_srs === "Proses Penerimaan" ||
                                statuses.status_penerimaan_ihc === "Proses Penerimaan" ||
                                statuses.status_pengirisan_hpa === "Proses Pengirisan" ||
                                statuses.status_pemotongan_hpa === "Proses Pemotongan" ||
                                statuses.status_pemprosesan_hpa === "Proses Pemprosesan" ||
                                statuses.status_penanaman_hpa === "Proses Penanaman" ||
                                statuses.status_pemotongan_tipis_hpa === "Proses Pemotongan Tipis" ||
                                statuses.status_pewarnaan_hpa === "Proses Pewarnaan" ||
                                statuses.status_pembacaan_hpa === "Proses Pembacaan" ||
                                statuses.status_pembacaan_frs === "Proses Pembacaan" ||
                                statuses.status_pembacaan_srs === "Proses Pembacaan" ||
                                statuses.status_pembacaan_ihc === "Proses Pembacaan" ||
                                statuses.status_penulisan_hpa === "Proses Penulisan" ||
                                statuses.status_penulisan_frs === "Proses Penulisan" ||
                                statuses.status_penulisan_srs === "Proses Penulisan" ||
                                statuses.status_penulisan_ihc === "Proses Penulisan" ||
                                statuses.status_pemverifikasi_hpa === "Proses Pemverifikasi" ||
                                statuses.status_pemverifikasi_frs === "Proses Pemverifikasi" ||
                                statuses.status_pemverifikasi_srs === "Proses Pemverifikasi" ||
                                statuses.status_pemverifikasi_ihc === "Proses Pemverifikasi" ||
                                statuses.status_authorized_hpa === "Proses Authorized" ||
                                statuses.status_authorized_frs === "Proses Authorized" ||
                                statuses.status_authorized_srs === "Proses Authorized" ||
                                statuses.status_authorized_ihc === "Proses Authorized" ||
                                statuses.status_pencetakan_hpa === "Proses Pencetakan" ||
                                statuses.status_pencetakan_frs === "Proses Pencetakan" ||
                                statuses.status_pencetakan_srs === "Proses Pencetakan" ||
                                statuses.status_pencetakan_ihc === "Proses Pencetakan" ||
                                statuses.status_pencetakan_ihc === "Proses Pencetakan";
                        }
                        return false;
                    });

                    btnSelesai.disabled = !validForSelesai;

                    // Periksa khusus untuk tombol "Lanjutkan"
                    const validForLanjut = Array.from(checkboxes).some(checkbox => {
                        if (checkbox.checked) {
                            const statuses = JSON.parse(checkbox.dataset.status); // Parse JSON
                            return statuses.status_penerimaan_hpa === "Selesai Penerimaan" ||
                                statuses.status_penerimaan_frs === "Selesai Penerimaan" ||
                                statuses.status_penerimaan_srs === "Selesai Penerimaan" ||
                                statuses.status_penerimaan_ihc === "Selesai Penerimaan" ||
                                statuses.status_pengirisan_hpa === "Belum Pengirisan" ||
                                statuses.status_pengirisan_hpa === "Proses Pengirisan" ||
                                statuses.status_pengirisan_hpa === "Selesai Pengirisan" ||
                                statuses.status_pemotongan_hpa === "Selesai Pemotongan" ||
                                statuses.status_pemprosesan_hpa === "Selesai Pemprosesan" ||
                                statuses.status_penanaman_hpa === "Selesai Penanaman" ||
                                statuses.status_pemotongan_tipis_hpa === "Selesai Pemotongan Tipis" ||
                                statuses.status_pewarnaan_hpa === "Selesai Pewarnaan" ||
                                statuses.status_pembacaan_hpa === "Belum Pembacaan" ||
                                statuses.status_pembacaan_hpa === "Proses Pembacaan" ||
                                statuses.status_pembacaan_hpa === "Selesai Pembacaan" ||
                                statuses.status_pembacaan_frs === "Belum Pembacaan" ||
                                statuses.status_pembacaan_frs === "Proses Pembacaan" ||
                                statuses.status_pembacaan_frs === "Selesai Pembacaan" ||
                                statuses.status_pembacaan_srs === "Belum Pembacaan" ||
                                statuses.status_pembacaan_srs === "Proses Pembacaan" ||
                                statuses.status_pembacaan_srs === "Selesai Pembacaan" ||
                                statuses.status_pembacaan_ihc === "Belum Pembacaan" ||
                                statuses.status_pembacaan_ihc === "Proses Pembacaan" ||
                                statuses.status_pembacaan_ihc === "Selesai Pembacaan" ||
                                statuses.status_penulisan_hpa === "Selesai Penulisan" ||
                                statuses.status_penulisan_frs === "Selesai Penulisan" ||
                                statuses.status_penulisan_srs === "Selesai Penulisan" ||
                                statuses.status_penulisan_ihc === "Selesai Penulisan" ||
                                statuses.status_pemverifikasi_hpa === "Belum Pemverifikasi" ||
                                statuses.status_pemverifikasi_hpa === "Proses Pemverifikasi" ||
                                statuses.status_pemverifikasi_hpa === "Selesai Pemverifikasi" ||
                                statuses.status_pemverifikasi_frs === "Belum Pemverifikasi" ||
                                statuses.status_pemverifikasi_frs === "Proses Pemverifikasi" ||
                                statuses.status_pemverifikasi_frs === "Selesai Pemverifikasi" ||
                                statuses.status_pemverifikasi_srs === "Belum Pemverifikasi" ||
                                statuses.status_pemverifikasi_srs === "Proses Pemverifikasi" ||
                                statuses.status_pemverifikasi_srs === "Selesai Pemverifikasi" ||
                                statuses.status_pemverifikasi_ihc === "Belum Pemverifikasi" ||
                                statuses.status_pemverifikasi_ihc === "Proses Pemverifikasi" ||
                                statuses.status_pemverifikasi_ihc === "Selesai Pemverifikasi" ||
                                statuses.status_authorized_hpa === "Belum Authorized" ||
                                statuses.status_authorized_hpa === "Proses Authorized" ||
                                statuses.status_authorized_hpa === "Selesai Authorized" ||
                                statuses.status_authorized_frs === "Belum Authorized" ||
                                statuses.status_authorized_frs === "Proses Authorized" ||
                                statuses.status_authorized_frs === "Selesai Authorized" ||
                                statuses.status_authorized_srs === "Belum Authorized" ||
                                statuses.status_authorized_srs === "Proses Authorized" ||
                                statuses.status_authorized_srs === "Selesai Authorized" ||
                                statuses.status_authorized_ihc === "Belum Authorized" ||
                                statuses.status_authorized_ihc === "Proses Authorized" ||
                                statuses.status_authorized_ihc === "Selesai Authorized" ||
                                statuses.status_pencetakan_hpa === "Belum Pencetakan" ||
                                statuses.status_pencetakan_hpa === "Proses Pencetakan" ||
                                statuses.status_pencetakan_hpa === "Selesai Pencetakan"||
                                statuses.status_pencetakan_frs === "Belum Pencetakan" ||
                                statuses.status_pencetakan_frs === "Proses Pencetakan" ||
                                statuses.status_pencetakan_frs === "Selesai Pencetakan"||
                                statuses.status_pencetakan_srs === "Belum Pencetakan" ||
                                statuses.status_pencetakan_srs === "Proses Pencetakan" ||
                                statuses.status_pencetakan_srs === "Selesai Pencetakan"||
                                statuses.status_pencetakan_ihc === "Belum Pencetakan" ||
                                statuses.status_pencetakan_ihc === "Proses Pencetakan" ||
                                statuses.status_pencetakan_ihc === "Selesai Pencetakan";
                        }
                        return false;
                    });

                    btnLanjut.disabled = !validForLanjut;
                }


                // Tambahkan event listener pada setiap checkbox
                document.querySelectorAll('.checkbox-item').forEach(checkbox => {
                    checkbox.addEventListener('change', toggleButtons);
                });

                window.onload = function() {
                    toggleButtons(); // Pastikan tombol dalam kondisi benar saat halaman dimuat

                    // Scroll otomatis ke tabel dengan ID 'dataTable'
                    document.getElementById('dataTable').scrollIntoView({});
                };


                function setAction(action) {
                    document.getElementById('action').value = action;

                    // Buat overlay loading
                    const loadingOverlay = document.createElement('div');
                    loadingOverlay.style.position = 'fixed';
                    loadingOverlay.style.top = '0';
                    loadingOverlay.style.left = '0';
                    loadingOverlay.style.width = '100%';
                    loadingOverlay.style.height = '100%';
                    loadingOverlay.style.background = 'rgba(0, 0, 0, 0.5)';
                    loadingOverlay.style.zIndex = '9999';

                    // Buat spinner
                    const spinner = document.createElement('div');
                    spinner.style.position = 'absolute';
                    spinner.style.top = '50%';
                    spinner.style.left = '50%';
                    spinner.style.transform = 'translate(-50%, -50%)';
                    spinner.style.border = '8px solid #f3f3f3'; // Warna latar belakang
                    spinner.style.borderTop = '8px solid #3498db'; // Warna spinner
                    spinner.style.borderRadius = '50%';
                    spinner.style.width = '60px';
                    spinner.style.height = '60px';
                    spinner.style.animation = 'spin 1s linear infinite'; // Animasi berputar

                    // Tambahkan spinner ke overlay
                    loadingOverlay.appendChild(spinner);
                    document.body.appendChild(loadingOverlay);

                    // Submit form
                    document.getElementById('mainForm').submit();
                }

                // Tambahkan keyframes untuk animasi spin
                const style = document.createElement('style');
                style.innerHTML = `
                @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
                }
                `;
                document.head.appendChild(style);
            </script>