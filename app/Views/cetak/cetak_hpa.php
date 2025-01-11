<?= $this->include('templates/cetak/header_cetak'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Print Hpa</h6>
        </div>
        <div class="card-body">
            <h1 class="h3 mb-4">Print Hpa</h1>
            <a href="javascript:history.back()" class="btn btn-primary mb-3">Kembali</a>

            <!-- Form -->
            <form action="<?= base_url('exam/update/' . $hpa[0]['id_hpa']) ?>" method="POST">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_hpa" value="<?= $hpa[0]['id_hpa'] ?>">
                <input type="hidden" name="kode_hpa" value="<?= $hpa[0]['kode_hpa'] ?>">

                <!-- Kolom print -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="print_hpa"></label>
                    <div class="col-sm-10">
                        <textarea class="form-control summernote_hpa" name="print_hpa" id="print_hpa" rows="5">
                            <?= $hpa[0]['print_hpa'] ?? '' ?>
                        </textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-6 text-center">
                        <!-- Tombol Simpan -->
                        <button type="submit" class="btn btn-success btn-user w-100">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                    <div class="col-6 text-center">
                        <!-- Tombol Cetak -->
                        <button type="button" class="btn btn-info btn-user w-100 w-md-auto" onclick="cetakHpa()">
                            <i class="fas fa-print"></i> Cetak
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/cetak/footer_cetak'); ?>

<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/summernote/summernote.js') ?>"></script>
<script>
    $(document).ready(function() {
        var height = $(window).height() - 200; // Sesuaikan dengan ukuran yang diinginkan

        $('.summernote_hpa').summernote({
            placeholder: '',
            tabsize: 2,
            height: height, // Menyesuaikan tinggi dengan lebar layar
            enterTag: 'p',
            toolbar: [
                ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                ['font', ['fontsize', 'fontname']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['color', ['color']],
                ['view', ['codeview', 'help']]
            ],
        });
    });

    function cetakHpa() {
        var printContent = document.getElementById('print_hpa').value; // Ambil nilai dari textarea

        // Membuka jendela baru untuk menampilkan hasil cetak
        var printWindow = window.open('', '', 'height=500,width=800');

        // Mulai menulis konten HTML
        printWindow.document.write('<html><head><title>Cetak Hpa</title><style>');
        printWindow.document.write('body { font-family: Arial, sans-serif; font-size: 12pt; }');
        printWindow.document.write('table { width: 100%; }');
        printWindow.document.write('h2 { margin-top: 40px; text-align: center; }');
        printWindow.document.write('hr { margin-top: 30px; margin-bottom: 30px; }');
        printWindow.document.write('</style></head><body>');

        printWindow.document.write('<div>');
        printWindow.document.write('<div>' + printContent + '</div>');

        // Memastikan elemen Terimakasih berada di bawah konten
        printWindow.document.write('<div style="text-align: center; padding-top: 20px; margin-top: 50px;">');
        printWindow.document.write('<p>Terimakasih,</p>');
        printWindow.document.write('<br><br>');
        printWindow.document.write('<p>(' + '<?= $hpa[0]["nama_user_dokter_pemotongan"] ?? "" ?>' + ')</p>');
        printWindow.document.write('</div>');

        printWindow.document.write('</div>');


        // Menutup tag body dan html
        printWindow.document.write('</body></html>');
        printWindow.document.close(); // Selesai menulis konten

        // Fungsi untuk mencetak
        printWindow.print();

    }
</script>