jQuery(document).ready(function($) {
    'use strict';

    if ($("#datetimepicker1").length) {
        $('#datetimepicker1').datetimepicker();

    }

    /* Calender jQuery **/

    if ($("datetimepicker2").length) {

        $('#datetimepicker2').datetimepicker({
            locale: 'ru',
            icons: {
                time: "far fa-clock",
                date: "fa fa-calendar-alt",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
            }
        });
    }


    if ($("#datetimepicker3").length) {

        $('#datetimepicker3').datetimepicker({
            format: 'LT'
        });
    }

    if ($("#datetimepicker4").length) {
        $('#datetimepicker4').datetimepicker({
          showMonthAfterYear:true,
          showButtonPanel: true,
          currentText: '오늘 날짜',
          yearSuffix: "년",
          closeText: '닫기',
          minDate: "-2Y",
          maxDate: "+D",
          changeYear: true, //콤보박스에서 년 선택 가능
          changeMonth: true,//콤보박스에서 월 선택 가능
          dateFormat: "yy-mm-dd",
          dayNames: ['일요일','월요일', '화요일', '수요일', '목요일', '금요일', '토요일'],
          dayNamesMin: [ '일','월', '화', '수', '목', '금', '토'],
          monthNamesShort: ['1','2','3','4','5','6','7','8','9','10','11','12'],
          monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월']
        });
    }
    if ($("#datetimepicker5").length) {
        $('#datetimepicker5').datetimepicker();

    }

    if ($("#datetimepicker6").length) {
        $('#datetimepicker6').datetimepicker({
            defaultDate: "11/1/2013",
            disabledDates: [
                moment("12/25/2013"),
                new Date(2013, 11 - 1, 21),
                "11/22/2013 00:53"
            ],
            icons: {
                time: "far fa-clock",
                date: "fa fa-calendar-alt",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
            }
        });
    }

    if ($("#datetimepicker7").length) {
        $(function() {
            $('#datetimepicker7').datetimepicker({
              format: 'YYYY-MM-DD',
                icons: {
                    time: "far fa-clock",
                    date: "fa fa-calendar-alt",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down"
                }
            });
            $('#datetimepicker8').datetimepicker({
              format: 'YYYY-MM-DD',
                useCurrent: false,
                icons: {
                    time: "far fa-clock",
                    date: "fa fa-calendar-alt",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down"
                }
            });
            $("#datetimepicker7").on("change.datetimepicker", function(e) {
                $('#datetimepicker8').datetimepicker('minDate', e.date);
            });
            $("#datetimepicker8").on("change.datetimepicker", function(e) {
                $('#datetimepicker7').datetimepicker('maxDate', e.date);
            });
        });
    }



    if ($("#datetimepicker10").length) {
        $('#datetimepicker10').datetimepicker({
            viewMode: 'years',
            icons: {
                time: "far fa-clock",
                date: "fa fa-calendar-alt",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
            }
        });
    }

    if ($("#datetimepicker11").length) {
        $('#datetimepicker11').datetimepicker({
            viewMode: 'years',
            format: 'MM/YYYY'
        });
    }

if ($("#datetimepicker13").length) {
     $('#datetimepicker13').datetimepicker({
            inline: true,
            sideBySide: true
        });

}
});
