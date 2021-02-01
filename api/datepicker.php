<script>
$(function() {
  $('[data-toggle = "datepicker"]').datepicker({
    autoHide: true,
    zIndex: 2048,
    language: 'ko-KR',
    startDate: '1980-01-01',
    endDate: '2020-12-31',

  });

  $("#date_from").datepicker('setEndDate', $("#date_to").datepicker('getDate', true));
  $("#date_to").datepicker('setStartDate', $("#date_from").datepicker('getDate', true));
  
});
$('[data-toggle = "datepicker"]').click(function() {
  $('[data-toggle = "datepicker"]').datepicker({
    autoHide: true,
    zIndex: 2048,
    language: 'ko-KR',
    startDate: '1980-01-01',
    endDate: '2020-12-31',

  });
  $("#date_from").datepicker('setEndDate', $("#date_to").datepicker('getDate', true));
  $("#date_to").datepicker('setStartDate', $("#date_from").datepicker('getDate', true));
  $("#date_from").datepicker('setDate', document.getElementById('date_from').value);
  $("#date_to").datepicker('setDate', document.getElementById('date_to').value);

});

</script>
