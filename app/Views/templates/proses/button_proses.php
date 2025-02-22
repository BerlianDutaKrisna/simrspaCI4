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
                            return statuses.status_penerimaan === "Belum Pemeriksaan" ||
                                statuses.status_pengirisan === "Belum Pengirisan" ||
                                statuses.status_pemotongan === "Belum Pemotongan" ||
                                statuses.status_pemprosesan === "Belum Pemprosesan" ||
                                statuses.status_penanaman === "Belum Penanaman" ||
                                statuses.status_pemotongan_tipis === "Belum Pemotongan Tipis" ||
                                statuses.status_pewarnaan === "Belum Pewarnaan" ||
                                statuses.status_pembacaan === "Belum Pembacaan" ||
                                statuses.status_penulisan === "Belum Penulisan" ||
                                statuses.status_pemverifikasi === "Belum Pemverifikasi" ||
                                statuses.status_autorized === "Belum Authorized" ||
                                statuses.status_pencetakan === "Belum Pencetakan";
                        }
                        return false;
                    });
                    btnMulai.disabled = !validForMulai;
                    btnKembalikan.disabled = !validForMulai;

                    // Periksa khusus untuk tombol "Selesai"
                    const validForSelesai = Array.from(checkboxes).some(checkbox => {
                        if (checkbox.checked) {
                            const statuses = JSON.parse(checkbox.dataset.status); // Parse JSON
                            return statuses.status_penerimaan === "Proses Pemeriksaan" ||
                                statuses.status_pengirisan === "Proses Pengirisan" ||
                                statuses.status_pemotongan === "Proses Pemotongan" ||
                                statuses.status_pemprosesan === "Proses Pemprosesan" ||
                                statuses.status_penanaman === "Proses Penanaman" ||
                                statuses.status_pemotongan_tipis === "Proses Pemotongan Tipis" ||
                                statuses.status_pewarnaan === "Proses Pewarnaan" ||
                                statuses.status_pembacaan === "Proses Pembacaan" ||
                                statuses.status_penulisan === "Proses Penulisan" ||
                                statuses.status_pemverifikasi === "Proses Pemverifikasi" ||
                                statuses.status_autorized === "Proses Authorized" ||
                                statuses.status_pencetakan === "Proses Pencetakan";
                        }
                        return false;
                    });

                    btnSelesai.disabled = !validForSelesai;

                    // Periksa khusus untuk tombol "Lanjutkan"
                    const validForLanjut = Array.from(checkboxes).some(checkbox => {
                        if (checkbox.checked) {
                            const statuses = JSON.parse(checkbox.dataset.status); // Parse JSON
                            return statuses.status_penerimaan === "Selesai Pemeriksaan" ||
                                statuses.status_pengirisan === "Belum Pengirisan" ||
                                statuses.status_pengirisan === "Proses Pengirisan" ||
                                statuses.status_pengirisan === "Selesai Pengirisan" ||
                                statuses.status_pemotongan === "Selesai Pemotongan" ||
                                statuses.status_pemprosesan === "Selesai Pemprosesan" ||
                                statuses.status_penanaman === "Selesai Penanaman" ||
                                statuses.status_pemotongan_tipis === "Selesai Pemotongan Tipis" ||
                                statuses.status_pewarnaan === "Selesai Pewarnaan" ||
                                statuses.status_pembacaan === "Belum Pembacaan" ||
                                statuses.status_pembacaan === "Proses Pembacaan" ||
                                statuses.status_pembacaan === "Selesai Pembacaan" ||
                                statuses.status_penulisan === "Selesai Penulisan" ||
                                statuses.status_pemverifikasi === "Belum Pemverifikasi" ||
                                statuses.status_pemverifikasi === "Proses Pemverifikasi" ||
                                statuses.status_pemverifikasi === "Selesai Pemverifikasi" ||
                                statuses.status_autorized === "Belum Authorized" ||
                                statuses.status_autorized === "Proses Authorized" ||
                                statuses.status_autorized === "Selesai Authorized" ||
                                statuses.status_pencetakan === "Belum Pencetakan" ||
                                statuses.status_pencetakan === "Proses Pencetakan" ||
                                statuses.status_pencetakan === "Selesai Pencetakan";
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