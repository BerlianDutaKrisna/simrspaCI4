<footer class="sticky-footer bg-danger text-white">
    <div class="container my-auto">
        <div class="row">
            <div class="col-md-6 text-center text-md-left">
                <p class="mb-0">© 2025 SIMRSPA - Developed by <strong>Berlian Duta Krisna S.Tr.Kes</strong></p>
            </div>
            <div class="col-md-6 text-center text-md-right">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <a href="https://www.linkedin.com/in/berliandutakrisna/" class="text-white" target="_blank">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="https://www.instagram.com/berliandutakrisna/" class="text-white" target="_blank">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="mailto:berliandutakrisna@gmail.com" class="text-white">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<!-- CDN Bootstrap 5 JS (bundle, menyertakan Popper.js) -->
<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<!-- CDN Summernote JS (Versi terbaru) -->
<script src="<?= base_url('assets/summernote/summernote.js') ?>"></script>
<!-- Inisialisasi Summernote -->
<script>
    function handleJumlahSlideChange(selectElement) {
        const customInput = document.getElementById('jumlah_slide_custom');
        if (selectElement.value === 'lainnya') {
            customInput.classList.remove('d-none');
        } else {
            customInput.classList.add('d-none');
            customInput.value = '';
        }
    }

    $(document).ready(function() {
        // Inisialisasi Summernote
        $('.summernote').summernote({
            placeholder: '',
            tabsize: 2,
            height: 200,
            emptyPara: '',
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']], // Gaya teks
                ['font', ['fontsize', 'fontname']], // Font dan ukuran font
                ['para', ['ul', 'ol', 'paragraph']], // Format paragraf
                ['color', ['color']], // Pilihan warna
                ['view', ['codeview', 'help']] // Menampilkan kode HTML dan bantuan
            ],
        });

        // Ambil nilai tindakan_spesimen dari elemen input
        var tindakanSpesimen = $('input[name="tindakan_spesimen"]').val().trim();

        // Cek apakah textarea kosong atau hanya berisi tag default kosong dari Summernote
        var currentContent = $('#makroskopis_srs').summernote('code').trim();

        if (!currentContent || currentContent === '<p><br></p>') {
            if (tindakanSpesimen === "Pap Smear") {
                // Jika tindakan spesimen adalah "Pap Smear"
                $('#makroskopis_srs').summernote('code', `
                <b>I. BAHAN PEMERIKSAAN :</b><br>
                    &nbsp;&nbsp;&nbsp; Cervical Smear.<br>

                    <b>II. KETERANGAN KLINIS :</b><br>
                    &nbsp; a. Keluhan : - , Keputihan, Gatatal, Lain-lain:.....<br>
                    &nbsp; b. Pemeriksaan Fisik : - , Tenang, Erosi, Mencurigakan Keganasan.<br>
            `);
                $('#mikroskopis_srs').summernote('code', `
                    <b>III. HASIL PEMERIKSAAN :</b><br>
                    &nbsp;&nbsp; a. Makroskopis : Diterima 1 smear dari cervix/Vaginal swab.<br>
                    &nbsp;&nbsp; b. Mikroskopis : <br>
                    &nbsp;&nbsp;&nbsp;&nbsp; - Sel Epitel: Sel Superfisial, Intermediate, Parabasal/basal, Endocervix/metaplastik.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp; - Sel Inflamasi: Sel radang PMN, Monokuler, Histocyte.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp; - Mikroorganisme: Tidak ditemukan, Ditemukan .....<br>
                    &nbsp;&nbsp;&nbsp;&nbsp; - Perubahan Seluler: Tidak ada perubahan, Displasia sedang-Berat, Perubahan Keradangan, Displasia ringan, Sel malignancy.<br>
            `);
                $('#hasil_srs').summernote('code', `
                    <b>IV. KESIMPULAN :</b><br>
                    &nbsp; a. Spesimen: Adekuat (Sel silinder + metaplastik), Cukup adekuat, kurang adekuat.<br>
                    &nbsp; b. Hasil: 
                    <b><br>
                    - NORMAL SMEAR / NILM / PAPANICOLAOU CLASS I <br>
                    - RADANG NON SPESIFIK / NILM / PAPANICOLOU CLASS II <br>
                    - TRICHOMONAS VAGINALIS / NILM / PAPANICOLAU CLASS II <br>
                    - BACTERIAL BAGINOSIS / NILM / PAPANICOLAOU CLASS II <br>
                    - MONIALIS / NILM PAPANICOLAOU CLASS II <br>
                    - DISPLASIA RINGAN / CIN 1 / LSIL / PAPANICOLAOU CLASS III <br>
                    - DISPLASIA SEDANG-BERAT / CIN 2-3 / HSIL / PAPANICOLAOU CLASS IV <br>
                    - SEL MALIGNANCY / PAPANICOLAOU CLASS V <br>
                    - ....  
                    </b>
            `);
            } else {
                // Default untuk kondisi lain
                $('#makroskopis_srs').summernote('code', `
                    Diterima bahan cairan _ berwarna _ dengan volume _ ml.<br>
                    Dibuat sediaan sebanyak _ slide dan _ cell block.
            `);
            }
        }
    });



    $(document).ready(function() {
        $('.summernote_hasil').summernote({
            placeholder: '',
            tabsize: 2,
            height: 750,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']], // tombol gaya teks
                ['font', ['fontsize', 'fontname']], // font dan ukuran font
                ['para', ['ul', 'ol', 'paragraph']], // format paragraf
                ['color', ['color']], // pilihan warna
                ['view', ['codeview', 'help']] // menampilkan kode HTML dan bantuan
            ],
        });
    });

    $(document).ready(function() {
        $('.summernote_print').summernote({
            placeholder: '',
            tabsize: 2,
            height: 200,
            airMode: true
        });

        // Cek status penulisan
        var statusPenulisan = "<?=
                                $penulisan['status_penulisan_hpa'] ??
                                    $penulisan['status_penulisan_frs'] ??
                                    $penulisan['status_penulisan_srs'] ??
                                    $penulisan['status_penulisan_ihc'] ?? '' ?>";

        if (statusPenulisan !== "Selesai Penulisan") {
            $('.summernote_print').summernote('disable');
        }
    });
</script>
</body>

</html>