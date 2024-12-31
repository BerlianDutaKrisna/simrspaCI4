$(document).ready(function() {
  $('#dataTable').DataTable({
      paging: true,           // Mengaktifkan pagination
      searching: true,        // Mengaktifkan fitur pencarian
      ordering: true,         // Mengaktifkan sorting
      info: true              // Menampilkan informasi baris
  });
});
