            <!-- Tombol -->
            <div class="row">
                <div class="form-group col-12 col-md-6">
                    <button type="button" class="btn btn-danger btn-user btn-block" onclick="setAction('mulai')">
                        <i class="fas fa-play"></i> Mulai
                    </button>
                </div>
                <div class="form-group col-12 col-md-6">
                    <button type="button" class="btn btn-success btn-user btn-block" onclick="setAction('selesai')">
                        <i class="fas fa-pause"></i> Selesai
                    </button>
                </div>
                <div class="form-group col-12">
                    <button type="button" class="btn btn-info btn-user btn-block" onclick="setAction('lanjut')">
                        <i class="fas fa-step-forward"></i> Lanjutkan
                    </button>
                </div>
                <div class="form-group col-12">
                    <button type="button" class="btn btn-warning btn-user btn-block" onclick="setAction('kembalikan')">
                        <i class="fas fa-undo-alt"></i> Kembalikan
                    </button>
                </div>
            </div>
            </form>
            </div>
            </div>

            <script>
                window.onload = function() {
                    // Scroll otomatis ke tabel dengan ID 'dataTable'
                    document.getElementById('dataTable').scrollIntoView({
                    });
                };

                function setAction(action) {
                    document.getElementById('action').value = action;
                    document.getElementById('mainForm').submit();
                }
            </script>