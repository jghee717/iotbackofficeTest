<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/3.2.5/js/dataTables.fixedColumns.min.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>

<script>
var remote_add = switch_add = multi_add = open_add = light_add = sleep_add = cctv_add = gas_add = plug_add = air_add = button_add = elec_add = all_add = -1;
var remote_del = switch_del = multi_del = open_del = light_del = sleep_del = cctv_del = gas_del = plug_del = air_del = button_del = elec_del = all_del = -1;

$.fn.DataTable.ext.pager.numbers_no_ellipses = function(page, pages){
   var numbers = [];
   var buttons = $.fn.DataTable.ext.pager.numbers_length;
   var half = Math.floor( buttons / 2 );

   var _range = function ( len, start ){
      var end;

      if ( typeof start === "undefined" ){
         start = 0;
         end = len;

      } else {
         end = start;
         start = len;
      }

      var out = [];
      for ( var i = start ; i < end; i++ ){ out.push(i); }

      return out;
   };


   if ( pages <= buttons ) {
      numbers = _range( 0, pages );


   } else if ( page <= half ) {
      numbers = _range( 0, buttons);

   } else if ( page >= pages - 1 - half ) {
      numbers = _range( pages - buttons, pages );

   } else {
      numbers = _range( page - half, page + half + 1);
   }

   numbers.DT_el = 'span';
   if(page == 0){
     return [numbers, "next", "last" ];
   }
   else if(page == 1){
     return ["previous",numbers, "next", "last" ];
   }
   else{
   return [ "first","previous",numbers, "next", "last" ];
 }

};

  $(document).ready(function(){
    $.fn.dataTable.ext.pager.numbers_length=5;
    // $.fn.dataTable.ext.classes.sPageButton = 'button primary_button';
    var table = $('#sample').DataTable({
     "dom": '<"pull-left"f>i<"pull-right"l>tp',
    // "bStateSave": true,
    "lengthMenu" : [ 10 , 20 , 30 ],
    "filter" : false,
    "info" : true,
    "ordering" : true,
    "scrollX": true,
    "scrollY": false,
    "fixedHeader" : false,
    "scrollCollapse" : true,
    "autoWidth" : false ,
    "pagingType": "numbers_no_ellipses",
    "lengthChange" : true,
    "fixedColumns" :  {
      "leftColumns" : 6,
      "heightMatch" : "none"
      // "terader-collapse" : "separate"
    },
    "language" : {
      "lengthMenu" : "_MENU_",
      "zeroRecords" : "기간 내 데이터가 없습니다.",
      "info" : "total:_TOTAL_",
      "infoEmpty" : "total: 0",
      "paginate": {
            "first": '<<',
            "last": '>>',
            "previous": "<",
            "next": ">"
          }
    }
  });
  for(var i=6; i<30; i++){
    table.columns([i]).visible(false);
  }
  /* --------------------------------------- */
  $("#view0").click(function(){
    if(remote_add == -1){
      table.columns([6]).visible(true);
      remote_add *= -1;
    }else{
      table.columns([6]).visible(false);
      remote_add *= -1;
    }
  });
  $("#unview0").click(function(){
    if(remote_del == -1){
      table.columns([7]).visible(true);
      remote_del *= -1;
    }else{
      table.columns([7]).visible(false);
      remote_del *= -1;
    }
  });
  /* --------------------------------------- */
  $("#view1").click(function(){
    if(switch_add == -1){
      table.columns([8]).visible(true);
      switch_add *= -1;
    }else{
      table.columns([8]).visible(false);
      switch_add *= -1;
    }
  });
  $("#unview1").click(function(){
    if(switch_del == -1){
      table.columns([9]).visible(true);
      switch_del *= -1;
    }else{
      table.columns([9]).visible(false);
      switch_del *= -1;
    }
  });
  /* --------------------------------------- */
  $("#view2").click(function(){
    if(multi_add == -1){
      table.columns([10]).visible(true);
      multi_add *= -1;
    }else{
      table.columns([10]).visible(false);
      multi_add *= -1;
    }
  });
  $("#unview2").click(function(){
    if(multi_del == -1){
      table.columns([11]).visible(true);
      multi_del *= -1;
    }else{
      table.columns([11]).visible(false);
      multi_del *= -1;
    }
  });
  /* --------------------------------------- */
  $("#view3").click(function(){
    if(open_add == -1){
      table.columns([12]).visible(true);
      open_add *= -1;
    }else{
      table.columns([12]).visible(false);
      open_add *= -1;
    }
  });
  $("#unview3").click(function(){
    if(open_del == -1){
      table.columns([13]).visible(true);
      open_del *= -1;
    }else{
      table.columns([13]).visible(false);
      open_del *= -1;
    }
  });
  /* --------------------------------------- */
  $("#view4").click(function(){
    if(light_add == -1){
      table.columns([14]).visible(true);
      light_add *= -1;
    }else{
      table.columns([14]).visible(false);
      light_add *= -1;
    }
  });
  $("#unview4").click(function(){
    if(light_del == -1){
      table.columns([15]).visible(true);
      light_del *= -1;
    }else{
      table.columns([15]).visible(false);
      light_del *= -1;
    }
  });
  /* --------------------------------------- */
  $("#view5").click(function(){
    if(sleep_add == -1){
      table.columns([16]).visible(true);
      sleep_add *= -1;
    }else{
      table.columns([16]).visible(false);
      sleep_add *= -1;
    }
  });
  $("#unview5").click(function(){
    if(sleep_del == -1){
      table.columns([17]).visible(true);
      sleep_del *= -1;
    }else{
      table.columns([17]).visible(false);
      sleep_del *= -1;
    }
  });
  /* --------------------------------------- */
  $("#view6").click(function(){
    if(cctv_add == -1){
      table.columns([18]).visible(true);
      cctv_add *= -1;
    }else{
      table.columns([18]).visible(false);
      cctv_add *= -1;
    }
  });
  $("#unview6").click(function(){
    if(cctv_del == -1){
      table.columns([19]).visible(true);
      cctv_del *= -1;
    }else{
      table.columns([19]).visible(false);
      cctv_del *= -1;
    }
  });
  /* --------------------------------------- */
  $("#view7").click(function(){
    if(gas_add == -1){
      table.columns([20]).visible(true);
      gas_add *= -1;
    }else{
      table.columns([20]).visible(false);
      gas_add *= -1;
    }
  });
  $("#unview7").click(function(){
    if(gas_del == -1){
      table.columns([21]).visible(true);
      gas_del *= -1;
    }else{
      table.columns([21]).visible(false);
      gas_del *= -1;
    }
  });
  /* --------------------------------------- */
  $("#view8").click(function(){
    if(plug_add == -1){
      table.columns([22]).visible(true);
      plug_add *= -1;
    }else{
      table.columns([22]).visible(false);
      plug_add *= -1;
    }
  });
  $("#unview8").click(function(){
    if(plug_del == -1){
      table.columns([23]).visible(true);
      plug_del *= -1;
    }else{
      table.columns([23]).visible(false);
      plug_del *= -1;
    }
  });
  /* --------------------------------------- */
  $("#view9").click(function(){
    if(air_add == -1){
      table.columns([24]).visible(true);
      air_add *= -1;
    }else{
      table.columns([24]).visible(false);
      air_add *= -1;
    }
  });
  $("#unview9").click(function(){
    if(air_del == -1){
      table.columns([25]).visible(true);
      air_del *= -1;
    }else{
      table.columns([25]).visible(false);
      air_del *= -1;
    }
  });
  /* --------------------------------------- */
  $("#view10").click(function(){
    if(button_add == -1){
      table.columns([26]).visible(true);
      button_add *= -1;
    }else{
      table.columns([26]).visible(false);
      button_add *= -1;
    }
  });
  $("#unview10").click(function(){
    if(button_del == -1){
      table.columns([27]).visible(true);
      button_del *= -1;
    }else{
      table.columns([27]).visible(false);
      button_del *= -1;
    }
  });
  /* --------------------------------------- */
  $("#view11").click(function(){
    if(elec_add == -1){
      table.columns([28]).visible(true);
      elec_add *= -1;
    }else{
      table.columns([28]).visible(false);
      elec_add *= -1;
    }
  });
  $("#unview11").click(function(){
    if(elec_del == -1){
      table.columns([29]).visible(true);
      elec_del *= -1;
    }else{
      table.columns([29]).visible(false);
      elec_del *= -1;
    }
  });
  /* --------------------------------------- */
  $("#view12").click(function(){
    if(all_add == -1){
      for(var i=6; i<30; i+=2){
        table.columns([i]).visible(true);
      }
      all_add *= -1;
      remote_add = switch_add = multi_add = open_add = light_add = sleep_add = cctv_add = gas_add = plug_add = air_add = button_add = elec_add = 1;
    }else{
      for(var i=6; i<30; i+=2){
        table.columns([i]).visible(false);
      }
      all_add *= -1;
      remote_add = switch_add = multi_add = open_add = light_add = sleep_add = cctv_add = gas_add = plug_add = air_add = button_add = elec_add = -1;
    }
  });
  $("#unview12").click(function(){
    if(all_del == -1){
      for(var i=7; i<30; i+=2){
        table.columns([i]).visible(true);
      }
      all_del *= -1;
      remote_del = switch_del = multi_del = open_del = light_del = sleep_del = cctv_del = gas_del = plug_del = air_del = button_del = elec_del = 1;
    }else{
      for(var i=7; i<30; i+=2){
        table.columns([i]).visible(false);
      }
      all_del *= -1;
      remote_del = switch_del = multi_del = open_del = light_del = sleep_del = cctv_del = gas_del = plug_del = air_del = button_del = elec_del = -1;
    }
  });
});
</script>
