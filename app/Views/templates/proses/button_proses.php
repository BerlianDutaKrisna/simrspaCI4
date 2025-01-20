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
                                statuses.status_pemotongan === "Belum Pemotongan";
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
                                statuses.status_pemotongan === "Proses Pemotongan";
                        }
                        return false;
                    });

                    btnSelesai.disabled = !validForSelesai;

                    // Periksa khusus untuk tombol "Lanjutkan"
                    const validForLanjut = Array.from(checkboxes).some(checkbox => {
                        if (checkbox.checked) {
                            const statuses = JSON.parse(checkbox.dataset.status); // Parse JSON
                            return statuses.status_penerimaan === "Selesai Pemeriksaan" ||
                                statuses.status_pengirisan === "Selesai Pengirisan" ||
                                statuses.status_pemotongan === "Selesai Pemotongan";
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
                    document.getElementById('mainForm').submit();
                }
            </script>