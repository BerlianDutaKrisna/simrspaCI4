<script>
    function cetakPencarian() {
        var tableContent = document.getElementById('dataTableButtons').outerHTML;
        
        var printWindow = window.open('', '', 'height=1122,width=793');
        printWindow.document.write(`
            <html>
            <head>
                <title>Cetak Laporan</title>
                <style>
                    @page {
                        size: A4 portrait;
                        margin: 15px;
                    }
                    body {
                        font-family: Arial, sans-serif;
                        margin: 15px;
                        font-size: 12px;
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
                        font-size: 12px;
                    }
                    th {
                        background-color: #f2f2f2;
                        font-weight: bold;
                    }
                    .bg-light {
                        background-color: #f8f9fa !important;
                    }
                    .font-weight-bold {
                        font-weight: bold !important;
                    }
                    .text-center {
                        text-align: center !important;
                    }
                    @media print {
                        body {
                            font-size: 12px;
                        }
                        .bg-light {
                            background-color: #ddd !important;
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
