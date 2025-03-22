<script>
    function cetakPencarian() {
        var printWindow = window.open('', '', 'height=700,width=1000');
        printWindow.document.write(`
            <html>
            <head>
                <title>Cetak Data Pencarian</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                        text-align: center;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 10px;
                    }
                    th, td {
                        border: 1px solid black;
                        padding: 8px;
                        text-align: center;
                    }
                    th {
                        background-color: #f2f2f2;
                    }
                    .header {
                        font-size: 18px;
                        font-weight: bold;
                        margin-bottom: 10px;
                    }
                </style>
            </head>
            <body>
                <div class="header">Laporan Data Pencarian</div>
                <table>
                    
                    <tbody>
        `);

        var tableContent = document.getElementById('dataTableButtons').innerHTML; // Ambil tabel dari halaman
        printWindow.document.write(tableContent);

        printWindow.document.write(`
                    </tbody>
                </table>
            </body>
            </html>
        `);

        printWindow.document.close();
        printWindow.print();
    }
</script>