<!--AJAX-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
$(document).ready(function(){ // html 문서를 다 읽어들인 후
    $('#selectID').on('change', function(){
        if(this.value !== null){
            var optVal = $(this).find(":selected").val();
            //alert(optVal);
            $.post('./api/storeReg/select.php',{optVal:optVal}, function(data) {
                $('#good').html(data);   // data는 ajaxPHP.php 파일에서 ehco 문의 결과 값
            });

        }
    });
});
</script>
