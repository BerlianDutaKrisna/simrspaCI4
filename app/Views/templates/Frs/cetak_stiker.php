<script>
  document.addEventListener('DOMContentLoaded', function() {

    $(".btn-cetak-stiker").on("click", function() {
      let id_frs = $(this).data("id");
      let kode = $(this).data("kode");

      // Ambil jumlah slide terbaru
      let slide = parseInt($(`.jumlah-slide-input[data-id="${id_frs}"]`).val(), 10);
      if (isNaN(slide) || slide < 1) {
        slide = 1;
      }

      console.log("Cetak stiker untuk ID:", id_frs, "Jumlah Slide:", slide);

      const printWindow = window.open('', '', 'width=250,height=150');
      const logoPath = `${window.location.origin}/simrspaCI4/public/img/LogoPemkot.png`;

      let html = `
      <html>
      <head>
        <title>Cetak Stiker</title>
        <style>
          @page { size: 22mm 15mm portrait; margin: 0; }
          body { margin: 0; padding: 0; }
          .label {
            width: 22mm; height: 15mm;
            display: flex; justify-content: center; align-items: center;
            page-break-after: always; box-sizing: border-box;
          }
          .inner-label {
            display: flex; flex-direction: row; align-items: center;
            justify-content: flex-start; width: 100%; padding: 1mm;
            font-family: Arial, sans-serif;
          }
          .logo { width: 5mm; height: 5mm; margin-right: 1mm; flex-shrink: 0; }
          .logo img { width: 100%; height: auto; }
          .teks-label { display: flex; flex-direction: column; justify-content: center; }
          .judul-utama { font-size: 4pt; font-weight: bold; margin: 0.1mm 0; }
          .judul-rs { font-size: 3pt; font-weight: bold; margin: 0.1mm 0; }
          .kode-frs { font-size: 9pt; font-weight: bold; margin: 0.1mm 0; }
          .angka-romawi { font-size: 9pt; font-weight: bold; margin: 0.1mm 0; text-align: center; }
        </style>
      </head>
      <body>`;

      for (let i = 1; i <= slide; i++) {
        html += `
        <div class="label">
          <div class="inner-label">
            <div class="logo"><img src="${logoPath}" alt="Logo"></div>
            <div class="teks-label">
              <div class="judul-utama">Patologi Anatomi</div>
              <div class="judul-rs">RSUD dr.M.Soewandhie</div>
              <div class="kode-frs">${kode}</div>
              <div class="angka-romawi"></div>
            </div>
          </div>
        </div>`;
      }

      html += `
      <script>
        window.onload = function () {
          window.print();
          setTimeout(() => window.close(), 1000);
        };
      <\/script>
      </body>
      </html>`;

      printWindow.document.open();
      printWindow.document.write(html);
      printWindow.document.close();
    });
  });
</script>
