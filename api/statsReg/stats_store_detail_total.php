<?php
require_once('../../assets/PHPExcel/Classes/PHPExcel.php');
include '../common.php';
include '../dbconn.php';


$filename = iconv("UTF-8", "EUC-KR", "현장 지원팀 운영현황 (".$date_from." - ".$date_to.")");


$conn = new DBC();
$conn->DBI();

$objPHPExcel = new PHPExcel();



$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);




                $objPHPExcel->getActiveSheet()->getStyle("A1:J1")->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(0)
                          ->setCellValue("A1", "NO.")
                          ->setCellValue("B1", "영업담당")
                          ->setCellValue("C1", "지원팀")
                          ->setCellValue("D1", "POS코드")
                          ->setCellValue("E1", "매장명")
                          ->setCellValue("F1", "설치수")
                          ->setCellValue("G1", "설치율")
                          ->setCellValue("H1", "실행수")
                          ->setCellValue("I1", "실행율")
                          ->setCellValue("J1", "총 사용횟수");


      $sql = "
      SELECT
        A.CHANNEL,
        A.bg_code,
        A.pos_code AS 'POS코드',
        A.pos_name AS '매장명',
        B.exe_cnt AS '설치수',
        B.exe_cnt / COUNT(A.pos_code) * 100 AS '설치율',
        C.touch_cnt / COUNT(A.pos_code) * 100 AS '사용율',
        C.touch_cnt AS '실행수',
        D.start_cnt AS '사용횟수'
       FROM did_pos_code AS A
       LEFT JOIN
        (
       SELECT
                  did_pos_code.CHANNEL,
                  did_pos_code.bg_code,
                  did_pos_code.pos_code,
                  COUNT(did_pos_code.pos_code) AS 'exe_cnt'
       FROM did_pos_code
       LEFT JOIN
                  (
                   SELECT pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                   FROM did_log_type_1
                   WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
                   GROUP BY pos_id
                 ) AS A ON
                          did_pos_code.pos_code = A.pos_id
       WHERE A.pos_id IS NOT NULL
       GROUP BY pos_code
       ) AS B ON
        A.pos_code = B.pos_code
       LEFT JOIN
        (
          SELECT
                      did_pos_code.channel,
                      did_pos_code.bg_code,
                      did_pos_code.pos_code
                      ,COUNT(did_pos_code.pos_code) AS 'touch_cnt'
                     FROM did_pos_code
                    LEFT JOIN
                    (
                    SELECT pos_id
                       FROM did_log_type_3
                    WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                     AND page_id = 'p900005'
                    GROUP BY pos_id
                        UNION
                      SELECT pos_id
                      FROM did_log_type_4
                      WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                      GROUP BY pos_id
                    )AS A
                    ON
                     did_pos_code.pos_code = A.pos_id
                     WHERE A.pos_id IS NOT NULL
                     GROUP BY pos_code

       ) AS C ON
        A.pos_code = C.pos_code
       LEFT JOIN
        (
       SELECT
                B.CHANNEL
               ,B.bg_code
               , B.pos_code
               , SUM(B.start_cnt) AS 'start_cnt'
       FROM
                (
       SELECT
                did_pos_code.channel,
                did_pos_code.bg_code,
                did_pos_code.pos_code
               , SUM(A.cnt) AS 'start_cnt'
       FROM did_pos_code
       LEFT JOIN
                (
       SELECT pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')), COUNT(pos_id) AS `cnt`
       FROM did_log_type_3
       WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."' AND page_id = 'p900005'
       GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) UNION ALL
       SELECT pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')), COUNT(pos_id) AS `cnt`
       FROM did_log_type_4
       WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
       GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
               ) AS A ON
                did_pos_code.pos_code = A.pos_id
       WHERE A.pos_id IS NOT NULL
       GROUP BY did_pos_code.pos_code
               ) AS B
       GROUP BY B.pos_code

       ) AS D ON
        A.pos_code = D.pos_code
       GROUP BY A.pos_code
       HAVING A.CHANNEL = '".$_GET['channel']."'
       order by A.CHANNEL, A.bg_code
      ";
      $conn->DBQ($sql);
      $conn->DBE();
      $j = 1;
      $count = 2;
      while($row=$conn->DBF()){
        if($row['디바이스수'] >= 1){ $temp = 1; }


        if($row['설치수'] == null)
        {
          $data3 = '-';
        }
        else
        {
          $data3 = number_format($row['설치수']);
        }

        if($row['설치율'] == null)
        {
          $data4 = '-';
        }
        else
        {
          $data4 = number_format($row['설치율'], 2).'%';
        }

        if($row['실행수'] == null)
        {
          $data5 = '-';
        }
        else
        {
          $data5 = number_format($row['실행수']);
        }

        if(number_format(($row['실행수']/$row['설치수'])*100,2) == nan)
        {
          $data6 = '-';
        }
        else
        {
          $data6 = number_format(($row['실행수']/$row['설치수'])*100,2).'%';
        }

        if($row['사용횟수']==null)
        {
          $data7 = '-';
        }
        else
        {
          $data7 = $row['사용횟수'];
        }

        if($row['CHANNEL'] == '홈/미디어')
        {
          $data8 = '스마트홈';
        }
        else
        {
          $data8 = $row['CHANNEL'];
        }

        $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue("A$count", $j)
                  ->setCellValue("B$count", $data8)
                  ->setCellValue("C$count", $row['bg_code'])
                  ->setCellValue("D$count", $row['POS코드'])
                  ->setCellValue("E$count", $row['매장명'])
                  ->setCellValue("F$count", $data3)
                  ->setCellValue("G$count", $data4)
                  ->setCellValue("H$count", $data5)
                  ->setCellValue("I$count", $data6)
                  ->setCellValue("J$count", $data7);


      $j++;
      $count++;
    }




$objPHPExcel->getActiveSheet()->getStyle("A1:J$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A1:J$count")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);



$objPHPExcel->setActiveSheetIndex(0)->setTitle('현장지원팀 운영 현황 ('.$data8.')');











header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename='.$filename.'.xlsx');


// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified

header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>
