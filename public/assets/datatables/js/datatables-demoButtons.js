$(document).ready(function() {
        $('#dataTableButtons').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Jadikan File Excel',
                className: 'buttons-excel'
            }, {
                extend: 'print',
                text: '<i class="fas fa-file-pdf"></i> Print/PDF'
            }]
        });
    });