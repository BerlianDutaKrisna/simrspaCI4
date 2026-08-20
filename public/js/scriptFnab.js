/**
 * scriptFnab.js
 * Script untuk pengelolaan form, canvas pemeriksaan fisik anatomi tubuh,
 * dan tanda tangan digital FNAB
 * View: frs/edit_makroskopis.php
 */

// ==========================================
// 1. VARIABEL GLOBAL SIGNATURE & ANATOMI
// ==========================================
let signaturePad = null;
let canvas = null;

let anatomyCanvas = null;
let anatomyCtx = null;
let anatomyBgImg = new Image();
let currentAnatomyGender = 'L';
let anatomyStrokes = [];
let currentStroke = null;
let isDrawingAnatomy = false;
let anatomyColor = '#dc3545';
let anatomyLineWidth = 4;

// ==========================================
// 2. FUNGSI HELPER UMUM
// ==========================================

/**
 * Hitung usia berdasarkan tanggal lahir (format YYYY-MM-DD atau date string)
 */
function hitungUsia(tanggalLahir) {
    if (!tanggalLahir) return "";
    let birthDate = new Date(tanggalLahir);
    if (isNaN(birthDate.getTime())) return "";
    let today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    let monthDiff = today.getMonth() - birthDate.getMonth();
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    return age >= 0 ? age : "";
}

/**
 * Helper: pilih opsi dropdown select berdasarkan value atau teks (case-insensitive)
 */
function setSelect(selectId, value) {
    if (!value) return false;
    var el = document.getElementById(selectId);
    if (!el) return false;
    var valLower = value.toString().trim().toLowerCase();
    for (var i = 0; i < el.options.length; i++) {
        var optVal = el.options[i].value.trim().toLowerCase();
        var optText = el.options[i].text.trim().toLowerCase();
        if (optVal === valLower || optText === valLower) {
            el.selectedIndex = i;
            return true;
        }
    }
    return false;
}

/**
 * Ambil nama hubungan pasien (dari select atau input nama lainnya jika dipilih 'lainnya')
 */
function getNamaHubunganPasien() {
    let selectElement = document.getElementById("nama_hubungan_pasien");
    let val = selectElement ? selectElement.value : "";
    let inputNamaLainnya = document.getElementById("nama_lainnya");
    let valLainnya = inputNamaLainnya ? inputNamaLainnya.value : "";
    return val === "lainnya" ? valLainnya : val;
}

/**
 * Sinkronisasi dropdown nama hubungan dengan data pasien
 */
function toggleSearchValue() {
    let namaHubungan = document.getElementById("nama_hubungan_pasien") ? document.getElementById("nama_hubungan_pasien").value : "";
    let inputNamaLainnya = document.getElementById("nama_lainnya");
    let hubunganPasien = document.getElementById("hubungan_dengan_pasien");
    let jenisKelamin = document.getElementById("jenis_kelamin_hubungan_pasien");
    let usiaPasien = document.getElementById("usia_hubungan_pasien");

    let patientName = (window.fnabConfig && window.fnabConfig.namaPasien) ? window.fnabConfig.namaPasien : (document.querySelector('input[name="nama_pasien"]')?.value || "");
    let patientJk = (window.fnabConfig && window.fnabConfig.jenisKelaminPasien) ? window.fnabConfig.jenisKelaminPasien : (document.querySelector('input[name="jenis_kelamin_pasien"]')?.value || "");
    let patientBirth = (window.fnabConfig && window.fnabConfig.tanggalLahirPasien) ? window.fnabConfig.tanggalLahirPasien : (document.querySelector('input[name="tanggal_lahir_pasien"]')?.value || "");

    if (namaHubungan && patientName && namaHubungan === patientName) {
        if (hubunganPasien) hubunganPasien.value = "Pasien";
        let jkPasien = patientJk;
        if (jenisKelamin) {
            jenisKelamin.value = (jkPasien.startsWith('L') || jkPasien === 'Laki-laki') ? 'L' : ((jkPasien.startsWith('P') || jkPasien === 'Perempuan') ? 'P' : jkPasien);
        }
        if (usiaPasien) {
            usiaPasien.value = hitungUsia(patientBirth);
        }
    } else {
        if (hubunganPasien) hubunganPasien.value = "";
        if (jenisKelamin) jenisKelamin.value = "";
        if (usiaPasien) usiaPasien.value = "";
    }

    // Tampilkan atau sembunyikan input nama lainnya
    if (inputNamaLainnya) {
        if (namaHubungan === "lainnya") {
            inputNamaLainnya.classList.remove("d-none");
            inputNamaLainnya.focus();
        } else {
            inputNamaLainnya.classList.add("d-none");
            inputNamaLainnya.value = "";
        }
    }
}

// ==========================================
// 3. CANVAS ANATOMI PEMERIKSAAN FISIK
// ==========================================

/**
 * Inisialisasi canvas anatomi tubuh
 */
function initAnatomyCanvas() {
    anatomyCanvas = document.getElementById('anatomy-canvas');
    if (!anatomyCanvas) return;

    anatomyCtx = anatomyCanvas.getContext('2d');

    // Tentukan gender awal berdasarkan data pasien
    let patientJk = (window.fnabConfig && window.fnabConfig.jenisKelaminPasien) ? window.fnabConfig.jenisKelaminPasien : (document.querySelector('input[name="jenis_kelamin_pasien"]')?.value || "");
    let isPerempuan = patientJk.trim().toUpperCase().startsWith('P') || patientJk.trim().toUpperCase() === 'PEREMPUAN';
    currentAnatomyGender = isPerempuan ? 'P' : 'L';

    // Set tombol radio toggle
    let labelLaki = document.getElementById('label-bg-laki');
    let labelPerempuan = document.getElementById('label-bg-perempuan');
    let radioLaki = document.getElementById('bg_laki');
    let radioPerempuan = document.getElementById('bg_perempuan');
    if (currentAnatomyGender === 'P') {
        if (labelPerempuan) labelPerempuan.classList.add('active');
        if (labelLaki) labelLaki.classList.remove('active');
        if (radioPerempuan) radioPerempuan.checked = true;
    } else {
        if (labelLaki) labelLaki.classList.add('active');
        if (labelPerempuan) labelPerempuan.classList.remove('active');
        if (radioLaki) radioLaki.checked = true;
    }

    // Pasang event listener mouse & touch
    anatomyCanvas.addEventListener('mousedown', startAnatomyDraw);
    anatomyCanvas.addEventListener('mousemove', moveAnatomyDraw);
    window.addEventListener('mouseup', stopAnatomyDraw);

    anatomyCanvas.addEventListener('touchstart', startAnatomyDraw, { passive: false });
    anatomyCanvas.addEventListener('touchmove', moveAnatomyDraw, { passive: false });
    window.addEventListener('touchend', stopAnatomyDraw, { passive: false });
    window.addEventListener('touchcancel', stopAnatomyDraw, { passive: false });

    // Load background awal
    changeAnatomyBg(currentAnatomyGender);
}

/**
 * Ambil koordinat kursor/sentuhan yang disesuaikan dengan skala canvas
 */
function getAnatomyPos(e) {
    if (!anatomyCanvas) return { x: 0, y: 0 };
    const rect = anatomyCanvas.getBoundingClientRect();
    const scaleX = anatomyCanvas.width / rect.width;
    const scaleY = anatomyCanvas.height / rect.height;
    let clientX = e.clientX;
    let clientY = e.clientY;
    if (e.touches && e.touches.length > 0) {
        clientX = e.touches[0].clientX;
        clientY = e.touches[0].clientY;
    } else if (e.changedTouches && e.changedTouches.length > 0) {
        clientX = e.changedTouches[0].clientX;
        clientY = e.changedTouches[0].clientY;
    }
    return {
        x: (clientX - rect.left) * scaleX,
        y: (clientY - rect.top) * scaleY
    };
}

function startAnatomyDraw(e) {
    if (e.cancelable) e.preventDefault();
    isDrawingAnatomy = true;
    let pos = getAnatomyPos(e);
    currentStroke = {
        color: anatomyColor,
        width: anatomyLineWidth,
        points: [pos]
    };
    anatomyStrokes.push(currentStroke);
    redrawAnatomyCanvas();
}

function moveAnatomyDraw(e) {
    if (!isDrawingAnatomy || !currentStroke) return;
    if (e.cancelable) e.preventDefault();
    let pos = getAnatomyPos(e);
    currentStroke.points.push(pos);
    redrawAnatomyCanvas();
}

function stopAnatomyDraw(e) {
    if (!isDrawingAnatomy) return;
    if (e && e.cancelable) e.preventDefault();
    isDrawingAnatomy = false;
    currentStroke = null;
    updateAnatomyHiddenInput();
}

/**
 * Gambar ulang background dan seluruh goresan/tanda
 */
function redrawAnatomyCanvas() {
    if (!anatomyCanvas || !anatomyCtx) return;

    // Bersihkan canvas
    anatomyCtx.clearRect(0, 0, anatomyCanvas.width, anatomyCanvas.height);

    // 1. Gambar Background Anatomi
    if (anatomyBgImg.complete && anatomyBgImg.naturalWidth !== 0) {
        anatomyCtx.drawImage(anatomyBgImg, 0, 0, anatomyCanvas.width, anatomyCanvas.height);
    } else {
        anatomyCtx.fillStyle = '#ffffff';
        anatomyCtx.fillRect(0, 0, anatomyCanvas.width, anatomyCanvas.height);
    }

    // 2. Gambar semua goresan
    for (let i = 0; i < anatomyStrokes.length; i++) {
        let stroke = anatomyStrokes[i];
        if (!stroke.points || stroke.points.length === 0) continue;

        anatomyCtx.strokeStyle = stroke.color;
        anatomyCtx.fillStyle = stroke.color;
        anatomyCtx.lineWidth = stroke.width;
        anatomyCtx.lineCap = 'round';
        anatomyCtx.lineJoin = 'round';

        if (stroke.points.length === 1) {
            anatomyCtx.beginPath();
            anatomyCtx.arc(stroke.points[0].x, stroke.points[0].y, stroke.width / 2, 0, Math.PI * 2);
            anatomyCtx.fill();
        } else {
            anatomyCtx.beginPath();
            anatomyCtx.moveTo(stroke.points[0].x, stroke.points[0].y);
            for (let j = 1; j < stroke.points.length; j++) {
                anatomyCtx.lineTo(stroke.points[j].x, stroke.points[j].y);
            }
            anatomyCtx.stroke();
        }
    }

    updateAnatomyHiddenInput();
}

/**
 * Update nilai input hidden dengan data base64 canvas
 */
function updateAnatomyHiddenInput() {
    let hidden = document.getElementById('pemeriksaan_fisik_gambar');
    if (hidden && anatomyCanvas) {
        hidden.value = anatomyCanvas.toDataURL('image/png');
    }
}

/**
 * Ganti background anatomi antara Laki-laki / Perempuan
 */
function changeAnatomyBg(gender) {
    currentAnatomyGender = gender;
    let imgUrl = gender === 'P' ?
        (window.fnabConfig?.imgPerempuan || '/img/GambarPerempuan.png') :
        (window.fnabConfig?.imgLaki || '/img/GambarLakiLaki.png');

    anatomyBgImg = new Image();
    anatomyBgImg.crossOrigin = 'Anonymous';
    anatomyBgImg.onload = function() {
        redrawAnatomyCanvas();
    };
    anatomyBgImg.onerror = function() {
        console.error("Gagal memuat gambar anatomi:", imgUrl);
        redrawAnatomyCanvas();
    };
    anatomyBgImg.src = imgUrl;

    let labelLaki = document.getElementById('label-bg-laki');
    let labelPerempuan = document.getElementById('label-bg-perempuan');
    if (gender === 'P') {
        if (labelPerempuan) labelPerempuan.classList.add('active');
        if (labelLaki) labelLaki.classList.remove('active');
    } else {
        if (labelLaki) labelLaki.classList.add('active');
        if (labelPerempuan) labelPerempuan.classList.remove('active');
    }
}

/**
 * Pilih warna pen
 */
function setAnatomyColor(color, btn) {
    anatomyColor = color;
    document.querySelectorAll('#btn-color-red, #btn-color-blue, #btn-color-black, #btn-color-yellow').forEach(b => {
        b.classList.remove('active');
        b.style.boxShadow = 'none';
    });
    if (btn) {
        btn.classList.add('active');
        btn.style.boxShadow = '0 0 0 2px rgba(0,0,0,0.35)';
    }
}

/**
 * Pilih ketebalan pen
 */
function setAnatomyLineWidth(width, btn) {
    anatomyLineWidth = width;
    if (btn && btn.parentElement) {
        btn.parentElement.querySelectorAll('button').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }
}

/**
 * Batalkan goresan terakhir (Undo)
 */
function undoAnatomyDraw() {
    if (anatomyStrokes.length > 0) {
        anatomyStrokes.pop();
        redrawAnatomyCanvas();
    }
}

/**
 * Reset seluruh goresan canvas anatomi
 */
function resetAnatomyCanvas() {
    if (confirm("Hapus semua tanda/coretan pada gambar anatomi?")) {
        anatomyStrokes = [];
        redrawAnatomyCanvas();
    }
}

// ==========================================
// 4. SIGNATURE PAD PASIEN (MODAL)
// ==========================================

/**
 * Buka modal tanda tangan dan inisialisasi signature pad
 */
function openSignatureModal() {
    $('#signatureModal').modal('show');

    setTimeout(() => {
        canvas = document.getElementById('signature-pad');
        if (!canvas) {
            console.error("Canvas tidak ditemukan!");
            return;
        }
        resizeCanvas();
        if (typeof SignaturePad !== 'undefined') {
            signaturePad = new SignaturePad(canvas);
        } else {
            console.error("SignaturePad library tidak ditemukan!");
        }
    }, 200);
}

/**
 * Sesuaikan ukuran canvas signature
 */
function resizeCanvas() {
    if (!canvas) return;
    const ratio = 1;
    canvas.style.width = "100%";
    canvas.style.height = "220px";
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = 220 * ratio;
    let ctx = canvas.getContext("2d");
    if (ctx) {
        ctx.scale(ratio, ratio);
    }
}

/**
 * Bersihkan canvas tanda tangan
 */
function clearSignature() {
    if (signaturePad) {
        signaturePad.clear();
    }
}

/**
 * Simpan tanda tangan dari modal dan tampilkan preview
 */
function saveSignature() {
    if (!signaturePad || signaturePad.isEmpty()) {
        alert("Tanda tangan masih kosong!");
        return;
    }

    // 1. Ambil data URL tanda tangan
    let dataURL = signaturePad.toDataURL();

    let previewImg = document.getElementById('signaturePreview');
    if (previewImg) {
        previewImg.src = dataURL;
        previewImg.style.display = 'block';
    }
    let noSigText = document.getElementById('noSignatureText');
    if (noSigText) {
        noSigText.style.display = 'none';
    }
    let hiddenInput = document.getElementById('concentSignaturePasien');
    if (hiddenInput) {
        hiddenInput.value = dataURL;
    }

    // 2. Kirim payload tanda tangan via AJAX
    buildAndSendSignaturePayload(dataURL)
        .then(res => {
            console.log("Signature save response:", res);
            $('#signatureModal').modal('hide');
        })
        .catch(err => {
            console.error("Error Fetch:", err);
            $('#signatureModal').modal('hide');
        });
}

// ==========================================
// 5. PENGIRIMAN & SUBMISI PAYLOAD LENGKAP
// ==========================================

/**
 * Bangun payload form & informed consent & pengkajian medis, kemudian kirim ke endpoint penyimpanan signature
 */
function buildAndSendSignaturePayload(dataURL) {
    const form = document.getElementById('form-frs') || document.querySelector('form');
    const formData = new FormData(form);
    let data = {};
    formData.forEach((value, key) => {
        data[key] = value;
    });

    let dokterId = data.dokter_pemeriksa || "";
    let analisId = data.analis_priksa || "";
    let dokterNama = (window.dokterMap && dokterMap[dokterId]) ? dokterMap[dokterId].nama : "";
    let concentSignatureDokter = (window.dokterMap && dokterMap[dokterId]) ? dokterMap[dokterId].ttd : "";
    let analisNama = (window.analisMap && analisMap[analisId]) ? analisMap[analisId].nama : "";
    let concentSignaturePetugas = (window.analisMap && analisMap[analisId]) ? analisMap[analisId].ttd : "";

    let dateTimeSignature = new Date().toLocaleString('sv-SE', {
        timeZone: 'Asia/Jakarta'
    }).replace('T', ' ');

    let namaHubunganVal = getNamaHubunganPasien();
    let hubunganVal = data.hubungan_dengan_pasien || "";
    let jkHubunganEl = document.getElementById('jenis_kelamin_hubungan_pasien');
    let jkHubunganVal = jkHubunganEl ? jkHubunganEl.value : (data.jenis_kelamin_hubungan_pasien || "");
    let jkHubunganText = jkHubunganVal === 'L' ? 'Laki-laki' : (jkHubunganVal === 'P' ? 'Perempuan' : '');
    let usiaHubunganEl = document.getElementById('usia_hubungan_pasien');
    let usiaHubunganVal = usiaHubunganEl ? usiaHubunganEl.value : (data.usia_hubungan_pasien || "");

    // Gambar anatomi pemeriksaan fisik
    let gambarPemeriksaanFisik = document.getElementById('pemeriksaan_fisik_gambar')?.value || (anatomyCanvas ? anatomyCanvas.toDataURL('image/png') : '');

    const informedData = {
        dpjp_awal: document.querySelector('input[name="dokter_pengirim"]')?.value || "",
        inform_nama: dokterNama,
        informed_hubungan: hubunganVal,
        informed_nama: namaHubunganVal,
        diagnosis_kerja: data.diagnosa_klinik || "",
        diagnosis_field2: "",
        diagnosis_field3: "",
        dasar_diagnosis: "Surat rujukan SMF lain",
        indikasi: "Nodul / massa",
        tata_cara: "SWAB dengan kapas alkohol, suntik dengan jarum 25G / 27G atau Spinal 25G",
        tujuan: "Untuk memastikan diagnosis",
        risiko: "Terjadi pneumothorax saat FNAB dengan CT SCAN Guiding",
        komplikasi: "Infeksi, perdarahan ditempat suntikan",
        lain_lain: "",
        usia_hubungan_pasien: usiaHubunganVal,
        consentDatePersetujuan: dateTimeSignature.replace(' ', 'T'),
        consentSignatureInformed: dataURL,
        consentGiverSignatureNameInformed: data.nama_pasien,
        dokterSignatureInformed: concentSignatureDokter,
        consentDokterSignatureName: dokterNama,
        perawatSignaturePersetujuan: concentSignaturePetugas,
        pegawai_nama: analisNama,
    };

    const consentData = {
        persetujuan_hubungan: hubunganVal,
        persetujuan_nama: namaHubunganVal,
        persetujuan_tgl_lahir: data.tanggal,
        usia_hubungan_pasien: usiaHubunganVal,
        persetujuan_jk: jkHubunganText,
        persetujuan_alamat: data.alamat_pasien,
        pasien_nama: data.nama_pasien,
        pasien_jk: data.jenis_kelamin_hubungan_pasien === "L" ? "Laki-laki" : (data.jenis_kelamin_hubungan_pasien === "P" ? "Perempuan" : ""),
        no_rm: data.norm_pasien,
        alamat: data.alamat_pasien,
        pasien_tgl_lahir: data.tanggal_lahir_pasien,
        consentDatePersetujuan: dateTimeSignature.replace(' ', 'T'),
        consentSignaturePersetujuan: dataURL,
        consentGiverSignatureNamePersetujuan: data.nama_pasien,
        dokterSignaturePersetujuan: concentSignatureDokter,
        dokterSignatureNamePersetujuan: dokterNama,
        perawatSignaturePersetujuan: concentSignaturePetugas,
        pegawai_nama: analisNama,
    };

    const payload = {
        formrm_jenis: "INFORMED FNAB",
        formrm_kode: "09PA",
        m_pasien_id: null,
        t_pendaftaran_id: data.idtransaksi,
        id_transaksi: data.idtransaksi,
        formrm_norm: data.norm_pasien,
        tanggal: data.tanggal,
        register: data.register,
        noregister: data.kode_frs,
        formrm_created_by: analisNama,
        formrm_created_date: dateTimeSignature.replace(' ', 'T'),
        dokter_pelaksana: dokterNama,
        petugas_pelaksana: analisNama,
        pemberi_informasi: dokterNama,
        hubungan_dengan_pasien: hubunganVal,
        nama_hubungan_pasien: namaHubunganVal,
        jenis_kelamin_hubungan_pasien: jkHubunganVal,
        usia_hubungan_pasien: usiaHubunganVal,
        diagnosis_kerja: data.diagnosa_klinik || "",
        concentSignaturePasien: dataURL,
        concentSignatureDokter: concentSignatureDokter,
        concentSignaturePetugas: concentSignaturePetugas,
        dateTimeSignature: dateTimeSignature,
        pemeriksaan_fisik_gambar: gambarPemeriksaanFisik,
        formrm_json: {
            nama: data.nama_pasien,
            jenis_kelamin: data.jenis_kelamin_hubungan_pasien === "L" ? "Laki-laki" : (data.jenis_kelamin_hubungan_pasien === "P" ? "Perempuan" : ""),
            no_rm: data.norm_pasien,
            alamat: data.alamat_pasien,
            tgl_lahir: data.tanggal_lahir_pasien,
            informed_data: informedData,
            persetujuan_data: consentData,
            keluhan_utama: data.keluhan_utama || "",
            riwayat_penyakit: data.riwayat_penyakit || "",
            riwayat_pengobatan: data.riwayat_pengobatan || "",
            pemeriksaan_fisik: data.pemeriksaan_fisik || "",
            pemeriksaan_fisik_gambar: gambarPemeriksaanFisik,
            diagnosis: data.diagnosis || "",
            terapi_tindakan: data.terapi_tindakan || "",
            rencana_kerja: data.rencana_kerja || "",
            catatan_penting: data.catatan_penting || ""
        }
    };

    let saveUrl = (window.fnabConfig && window.fnabConfig.saveSignatureUrl) ? window.fnabConfig.saveSignatureUrl : "/signature/save";
    let csrfInput = document.querySelector('[name=csrf_test_name]');
    let csrfToken = csrfInput ? csrfInput.value : "";

    return fetch(saveUrl, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken
        },
        body: JSON.stringify(payload)
    }).then(response => response.json());
}

/**
 * Handle tombol Simpan utama: simpan signature terlebih dahulu lalu submit form ke frs/update_print
 */
function submitWithSignature() {
    let updateUrl = (window.fnabConfig && window.fnabConfig.updatePrintUrl) ? window.fnabConfig.updatePrintUrl : "";
    try {
        var dataURL = document.getElementById('concentSignaturePasien')?.value || '';
        buildAndSendSignaturePayload(dataURL)
            .then(res => {
                console.log('Signature save response', res);
                var form = document.getElementById('form-frs') || document.querySelector('form');
                if (updateUrl && form) form.action = updateUrl;
                if (form) form.submit();
            })
            .catch(err => {
                console.error('Error saving signature:', err);
                var form = document.getElementById('form-frs') || document.querySelector('form');
                if (updateUrl && form) form.action = updateUrl;
                if (form) form.submit();
            });
    } catch (e) {
        console.error(e);
        var form = document.getElementById('form-frs') || document.querySelector('form');
        if (updateUrl && form) form.action = updateUrl;
        if (form) form.submit();
    }
}

// ==========================================
// 6. PEMULIHAN DATA YANG TERSIMPAN
// ==========================================

/**
 * Pulihkan data signature dan isian form yang tersimpan sebelumnya
 */
function restoreSignatureData(sigObj) {
    if (!sigObj) return;
    try {
        var parsedJson = {};
        if (sigObj.formrm_json) {
            if (typeof sigObj.formrm_json === 'string') {
                try {
                    parsedJson = JSON.parse(sigObj.formrm_json) || {};
                } catch (e) {
                    parsedJson = {};
                }
            } else if (typeof sigObj.formrm_json === 'object') {
                parsedJson = sigObj.formrm_json || {};
            }
        }
        var informedData = parsedJson.informed_data || {};
        var persetujuanData = parsedJson.persetujuan_data || {};

        // 1. Preview signature
        var sigImg = sigObj.concentSignaturePasien || persetujuanData.consentSignaturePersetujuan || informedData.consentSignaturePersetujuan || null;
        if (sigImg && sigImg.trim() !== '') {
            var img = document.getElementById('signaturePreview');
            var noSig = document.getElementById('noSignatureText');
            if (img) {
                img.src = sigImg;
                img.style.display = 'block';
            }
            if (noSig) {
                noSig.style.display = 'none';
            }
            var hidden = document.getElementById('concentSignaturePasien');
            if (hidden) {
                hidden.value = sigImg;
            }
        }

        // 2. Nama Hubungan Pasien
        var namaHub = sigObj.nama_hubungan_pasien || persetujuanData.persetujuan_nama || informedData.informed_nama || '';
        var patientName = (window.fnabConfig && window.fnabConfig.namaPasien) ? window.fnabConfig.namaPasien : (document.querySelector('input[name="nama_pasien"]')?.value || '');

        if (namaHub && namaHub.trim() !== '') {
            if (namaHub.trim().toLowerCase() === patientName.toLowerCase() || namaHub.trim().toLowerCase() === 'pasien') {
                var sel = document.getElementById('nama_hubungan_pasien');
                if (sel) {
                    setSelect('nama_hubungan_pasien', patientName);
                }
                var inputNama = document.getElementById('nama_lainnya');
                if (inputNama) {
                    inputNama.classList.add('d-none');
                    inputNama.value = '';
                }
            } else {
                var sel = document.getElementById('nama_hubungan_pasien');
                if (sel) sel.value = 'lainnya';
                var inputNama = document.getElementById('nama_lainnya');
                if (inputNama) {
                    inputNama.classList.remove('d-none');
                    inputNama.value = namaHub;
                }
            }
        }

        // 3. Hubungan dengan Pasien
        var hub = sigObj.hubungan_dengan_pasien || persetujuanData.persetujuan_hubungan || informedData.informed_hubungan || '';
        if (hub && hub.trim() !== '') {
            setSelect('hubungan_dengan_pasien', hub);
        }

        // 4. Jenis Kelamin Hubungan Pasien
        var jk = sigObj.jenis_kelamin_hubungan_pasien || persetujuanData.persetujuan_jk || parsedJson.jenis_kelamin || sigObj.jenis_kelamin || '';
        if (jk && jk.trim() !== '') {
            var selJk = document.getElementById('jenis_kelamin_hubungan_pasien');
            if (selJk) {
                var jkUpper = jk.trim().toUpperCase();
                if (jkUpper.startsWith('L') || jkUpper === 'LAKI-LAKI') {
                    selJk.value = 'L';
                } else if (jkUpper.startsWith('P') || jkUpper === 'PEREMPUAN') {
                    selJk.value = 'P';
                }
            }
        }

        // 5. Usia Hubungan Pasien
        var usiaVal = sigObj.usia_hubungan_pasien || persetujuanData.usia_hubungan_pasien || persetujuanData.persetujuan_usia || informedData.usia_hubungan_pasien || null;
        var tglTrans = (window.fnabConfig && window.fnabConfig.tanggalTransaksi) ? window.fnabConfig.tanggalTransaksi : (document.querySelector('input[name="tanggal"]')?.value || '');

        if (!usiaVal) {
            var tglLahirHub = sigObj.tgl_lahir_hubungan_pasien || persetujuanData.persetujuan_tgl_lahir || null;
            if (tglLahirHub && tglLahirHub !== tglTrans) {
                var computedAge = hitungUsia(tglLahirHub);
                if (computedAge !== "" && computedAge >= 0) {
                    usiaVal = computedAge;
                }
            }
        }
        if (!usiaVal && (hub.toLowerCase() === 'pasien' || namaHub.toLowerCase() === 'pasien' || namaHub.toLowerCase() === patientName.toLowerCase())) {
            var patientBirth = (window.fnabConfig && window.fnabConfig.tanggalLahirPasien) ? window.fnabConfig.tanggalLahirPasien : (document.querySelector('input[name="tanggal_lahir_pasien"]')?.value || '');
            if (patientBirth) {
                usiaVal = hitungUsia(patientBirth);
            }
        }
        if (usiaVal !== null && usiaVal !== "") {
            var usiaInput = document.getElementById('usia_hubungan_pasien');
            if (usiaInput) {
                usiaInput.value = usiaVal;
            }
        }

        // 6. Dokter & Analis
        var dok = sigObj.dokter_pelaksana || informedData.inform_nama || null;
        if (dok) setSelect('dokter_pemeriksa', dok);

        var petugas = sigObj.petugas_pelaksana || informedData.pegawai_nama || null;
        if (petugas) setSelect('analis_priksa', petugas);

        // 7. Pengkajian Awal Medis Anamnesis & Diagnosis
        const fields = [
            'keluhan_utama', 'riwayat_penyakit', 'riwayat_pengobatan',
            'pemeriksaan_fisik', 'diagnosis', 'terapi_tindakan',
            'rencana_kerja', 'catatan_penting'
        ];
        fields.forEach(f => {
            let val = parsedJson[f] || sigObj[f] || '';
            if (val) {
                let el = document.querySelector(`[name="${f}"]`);
                if (el) el.value = val;
            }
        });

        // 8. Restore gambar anatomi jika ada
        let savedAnatomyImg = parsedJson.pemeriksaan_fisik_gambar || sigObj.pemeriksaan_fisik_gambar || '';
        if (savedAnatomyImg && savedAnatomyImg.trim() !== '') {
            let hiddenAnatomy = document.getElementById('pemeriksaan_fisik_gambar');
            if (hiddenAnatomy) hiddenAnatomy.value = savedAnatomyImg;

            let restoredImg = new Image();
            restoredImg.crossOrigin = 'Anonymous';
            restoredImg.onload = function() {
                if (anatomyCanvas && anatomyCtx) {
                    anatomyCtx.clearRect(0, 0, anatomyCanvas.width, anatomyCanvas.height);
                    anatomyCtx.drawImage(restoredImg, 0, 0, anatomyCanvas.width, anatomyCanvas.height);
                }
            };
            restoredImg.src = savedAnatomyImg;
        }

    } catch (e) {
        console.error('Error restoring signature preview', e);
    }
}

// ==========================================
// 7. EVENT LISTENER SAAT DOKUMEN SIAP
// ==========================================
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi Canvas Anatomi Pemeriksaan Fisik
    initAnatomyCanvas();

    // Restore data signature dan pengkajian medis jika tersedia
    if (window.fnabConfig && window.fnabConfig.signature) {
        restoreSignatureData(window.fnabConfig.signature);
    }

    let elNamaHub = document.getElementById("nama_hubungan_pasien");
    if (elNamaHub) {
        elNamaHub.addEventListener("change", function() {
            console.log("Nama Hubungan Pasien:", getNamaHubunganPasien());
        });
    }

    let elNamaLainnya = document.getElementById("nama_lainnya");
    if (elNamaLainnya) {
        elNamaLainnya.addEventListener("input", function() {
            console.log("Nama Hubungan Pasien (input lainnya):", getNamaHubunganPasien());
        });
    }
});
