$(function() {
  if (!$.fn.DataTable) {
    return;
  }

  var $standard = $('#datatable');
  if ($standard.length) {
    $standard.DataTable({
      responsive: true,
      autoWidth: false
    });
  }

  var $buttons = $('#datatable-buttons');
  if ($buttons.length) {
    var buttonsTable = $buttons.DataTable({
      lengthChange: false,
      buttons: ['copy', 'excel', 'pdf']
    });
    buttonsTable.buttons().container().appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
  }

  var $keyTable = $('#key-datatable');
  if ($keyTable.length) {
    $keyTable.DataTable({ keys: true });
  }

  var $selectTable = $('#selection-datatable');
  if ($selectTable.length) {
    $selectTable.DataTable({
      select: { style: 'multi' }
    });
  }
});
