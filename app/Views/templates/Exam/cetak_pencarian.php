<script>
    function cetakPencarian() {
        var tableContent = document.getElementById('dataTableButtons').outerHTML; // Ambil tabel dari halaman
        
        var printWindow = window.open('', '', 'height=400,width=1000');
        printWindow.document.write(`
            <html>
            <head>
                <title>Cetak Laporan</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 35px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 10px;
                    }
                    th, td {
                        border: 1px solid black;
                        padding: 8px;
                        text-align: left;
                        font-size: 14px; /* Pastikan ukuran font tetap */
                    }
                    th {
                        background-color: #f2f2f2;
                        font-weight: bold;
                    }
                    .bg-light {
                        background-color: #f8f9fa !important; /* Pastikan tetap terlihat saat dicetak */
                    }
                    .font-weight-bold {
                        font-weight: bold !important; /* Pastikan tetap bold saat dicetak */
                    }
                    .text-center {
                        text-align: center !important;
                    }
                    @media print {
                        body {
                            font-size: 14px;
                        }
                        .bg-light {
                            background-color: #ddd !important; /* Warna lebih jelas untuk cetak */
                        }
                    }
                </style>
            </head>
            <body>
                ${tableContent}
            </body>
            </html>
        `);

        printWindow.document.close();
        printWindow.print();
    }
</script>
