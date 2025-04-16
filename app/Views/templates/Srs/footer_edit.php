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
                    &nbsp; a. Keluhan : - &#x2610; Keputihan &#x2610; Gatatal &#x2610; Lain-lain:.....<br>
                    &nbsp; b. Pemeriksaan Fisik : - &#x2610; Tenang &#x2610; Erosi &#x2610; Mencurigakan Keganasan.<br>

                    <b>III. HASIL PEMERIKSAAN :</b><br>
                    &nbsp;&nbsp; a. Makroskopis : Diterima ..... smear dari cervix/Vaginal swab.<br>
                    &nbsp;&nbsp; b. Mikroskopis : <br>
                    &nbsp;&nbsp;&nbsp;&nbsp; - Sel Epitel: &#x2610; Sel Superfisial &#x2610; Intermediate &#x2610; Parabasal/basal &#x2610; Endocervix/metaplastik.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp; - Sel Inflamasi: &#x2610; Sel radang PMN &#x2610; Monokuler &#x2610; Histocyte.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp; - Mikroorganisme: &#x2610; Tidak ditemukan &#x2610; Ditemukan .....<br>
                    &nbsp;&nbsp;&nbsp;&nbsp; - Perubahan Seluler: &#x2610; Tidak ada perubahan &#x2610; Displasia sedang-Berat &#x2610; Perubahan Keradangan &#x2610; Displasia ringan &#x2610; Sel malignancy.<br>

                    <b>IV. KESIMPULAN :</b><br>
                    &nbsp; a. Spesimen: &#x2610; Adekuat (Sel silinder + metaplastik) &#x2610; Cukup adekuat &#x2610; kurang adekuat.<br>
                    &nbsp; b. Hasil: 
                    <b>
                    &#x2610; NORMAL SMEAR / NILM / PAPANICOLAOU CLASS I 
                    &#x2610; RADANG NON SPESIFIK / NILM / PAPANICOLOU CLASS II 
                    &#x2610; TRICHOMONAS VAGINALIS / NILM / PAPANICOLAU CLASS II 
                    &#x2610; BACTERIAL BAGINOSIS / NILM / PAPANICOLAOU CLASS II 
                    &#x2610; MONIALIS / NILM PAPANICOLAOU CLASS II 
                    &#x2610; DISPLASIA RINGAN / CIN 1 / LSIL / PAPANICOLAOU CLASS III 
                    &#x2610; DISPLASIA SEDANG-BERAT / CIN 2-3 / HSIL / PAPANICOLAOU CLASS IV 
                    &#x2610; SEL MALIGNANCY / PAPANICOLAOU CLASS V 
                    &#x2610; ....  
                    </b><br>
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