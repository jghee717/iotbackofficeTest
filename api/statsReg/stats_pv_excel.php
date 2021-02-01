<?php
require_once('../../assets/PHPExcel/Classes/PHPExcel.php');
include '../common.php';
include '../dbconn.php';

$filename = iconv("UTF-8", "EUC-KR", "메뉴별 통계 (".$date_from." - ".$date_to.")");




$conn = new DBC();
$conn->DBI();



$objPHPExcel = new PHPExcel();
// $order = $_GET['order'];
if($_GET['order'] == null){
  $order = 'desc';
} else {
  $order = $_GET['order'];
}

if($_GET['selectID'] == null){
  $searchCate = " where 구분 IN ('U+tv','U+IoT') ";
} else if($_GET['selectID'] == 'TV'){
  $searchCate = " where 구분 IN ('U+tv','') ";
} else if($_GET['selectID'] == 'IoT'){
  $searchCate = " where 구분 IN ('','U+IoT') ";
} else if($_GET['selectID'] == '전체'){
  $searchCate = " where 구분 IN ('U+tv','U+IoT') ";
}

if($_GET['space_id'] == null){
  $searchSpace1 = " and space_id IN('s000001', 's000002', 's000003', 's000004') ";
  $searchSpace2 = " ";
}else if($_GET['space_id'] == '전체'){
  $searchSpace1 = " and space_id IN('s000001', 's000002', 's000003', 's000004') ";
  $searchSpace2 = " ";
}else if($_GET['space_id'] == 's000001'){
  $searchSpace1 = " and space_id = 's000001' ";
  $searchSpace2 = " and space_id = 's000001' ";
}else if($_GET['space_id'] == 's000002'){
  $searchSpace1 = " and space_id = 's000002' ";
  $searchSpace2 = " and space_id = 's000002' ";
}else if($_GET['space_id'] == 's000003'){
  $searchSpace1 = " and space_id = 's000003' ";
  $searchSpace2 = " and space_id = 's000003' ";
}else if($_GET['space_id'] == 's000004'){
  $searchSpace1 = " and space_id = 's000004' ";
  $searchSpace2 = " and space_id = 's000004' ";
}



$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(45);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
$objPHPExcel->getActiveSheet()->getStyle("A1:F1")->getFont()->setBold(true);


$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue("A1", "순위")
    ->setCellValue("B1", "구분")
    ->setCellValue("C1", "공간")
    ->setCellValue("D1", "컨텐츠")
    ->setCellValue("E1", "사용 횟수")
    ->setCellValue("F1", "평균체류시간");

             $sql = "
             SELECT a.구분, a.pos_id, a.공간, a.컨텐츠, SUM(a.사용횟수) AS '사용횟수', a.avg_dif
             FROM
             (
             	SELECT 'U+tv' AS '구분', a.pos_id, '-' AS '공간', a.DESC AS '컨텐츠', COUNT(a.pos_id) AS '사용횟수', b.avg_dif
             	FROM did_pos_code
             	INNER JOIN
             	(
             		SELECT pos_id, b.desc
             		FROM
             		(
             			SELECT pos_id, space_id
             			FROM did_log_type_3
             			WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
             			AND space_id NOT IN('s000001', 's000002', 's000003', 's000004')
             		)a LEFT JOIN
             		(
             			SELECT did_bookmark_config.DESC, target_id
             			FROM did_bookmark_config
             		)b ON a.space_id = b.target_id
             	)a ON did_pos_code.pos_code = a.pos_id
             	LEFT JOIN
             	(
             		SELECT *, SEC_TO_TIME(AVG(TIME_TO_SEC(E.diff))) AS avg_dif
                   FROM
                   (
                       SELECT A.pos_id, A.device_id , @var ,CASE WHEN @user = device_id THEN 0 ELSE  @var := 0 END,
                       TIMEDIFF( STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s') , @var) AS diff,
                       @user := device_id,@var := STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')
                       FROM
                       (
                         SELECT *
             	         FROM did_log_type_3
             	         WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
             	         AND space_id NOT IN('s000001', 's000002', 's000003', 's000004')
             	         GROUP BY device_id , TIMESTAMP
             	         ORDER BY device_id,TIMESTAMP
                       )AS A,(SELECT @var := 0, @user := '' )AS t
                     )AS E
                     WHERE E.diff IS NOT NULL AND E.diff < '00:15:00'
                     GROUP BY E.pos_id
             	)b ON a.pos_id = b.pos_id
             	GROUP BY 공간, 컨텐츠
             	UNION ALL
             	SELECT 'U+IoT' AS '구분', a.pos_id, a.DESC AS '공간', '-' AS '컨텐츠', COUNT(a.pos_id) AS '사용횟수', b.avg_dif
             	FROM did_pos_code
             	INNER JOIN
             	(
             		SELECT pos_id, b.desc
             		FROM
             		(
             			SELECT pos_id, space_id
             			FROM did_log_type_3
             			WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
             			$searchSpace1
             		)a LEFT JOIN
             		(
             			SELECT did_space_config.DESC, space_id
             			FROM did_space_config
             		)b ON a.space_id = b.space_id
             	)a ON did_pos_code.pos_code = a.pos_id
             	LEFT JOIN
             	(
             		SELECT *, SEC_TO_TIME(AVG(TIME_TO_SEC(E.diff))) AS avg_dif
                   FROM
                   (
                       SELECT A.pos_id, A.device_id , @var ,CASE WHEN @user = device_id THEN 0 ELSE  @var := 0 END,
                       TIMEDIFF( STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s') , @var) AS diff,
                       @user := device_id,@var := STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')
                       FROM
                       (
                         SELECT *
             	         FROM did_log_type_3
             	         WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                        $searchSpace1
             	         GROUP BY device_id , TIMESTAMP
             	         ORDER BY device_id,TIMESTAMP
                       )AS A,(SELECT @var := 0, @user := '' )AS t
                     )AS E
                     WHERE E.diff IS NOT NULL AND E.diff < '00:15:00'
                     GROUP BY E.pos_id
             	)b ON a.pos_id = b.pos_id
             	GROUP BY 공간, 컨텐츠
             	UNION ALL
             	SELECT 'U+IoT' AS '구분', a.pos_id, a.DESC AS '공간', IFNULL(a.contents,'-') AS '컨텐츠', COUNT(a.pos_id) AS '사용횟수', b.avg_dif
             	FROM did_pos_code
             	INNER JOIN
             	(
             		SELECT pos_id, b.DESC, IFNULL(c.TEXT,d.TEXT) AS 'contents'
             		FROM
             		(
             			SELECT pos_id, page_id, space_id, target_id
             			FROM did_log_type_4
             			WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
                   $searchSpace2
             		)a
             		LEFT JOIN
             		(
             			SELECT did_space_config.DESC, space_id
             			FROM did_space_config
             		)b ON a.space_id = b.space_id
             		LEFT JOIN
             		(
             			SELECT did_usecase_config.TEXT, target_id, usescene_id
             			FROM did_usecase_config
             		)c ON a.space_id = c.target_id AND a.target_id = c.usescene_id
             		LEFT JOIN
             		(
                  SELECT did_product_config.TEXT, product_id
  			          FROM did_product_config
             		)d ON a.target_id = d.product_id
             	)a ON did_pos_code.pos_code = a.pos_id
             	LEFT JOIN
             	(
             		SELECT *, SEC_TO_TIME(AVG(TIME_TO_SEC(E.diff))) AS avg_dif
                   FROM
                   (
                       SELECT A.pos_id, A.device_id , @var ,CASE WHEN @user = device_id THEN 0 ELSE  @var := 0 END,
                       TIMEDIFF( STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s') , @var) AS diff,
                       @user := device_id,@var := STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')
                       FROM
                       (
                         SELECT *
             	         FROM did_log_type_4
             	         WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                        $searchSpace2
             	         GROUP BY device_id , TIMESTAMP
             	         ORDER BY device_id,TIMESTAMP
                       )AS A,(SELECT @var := 0, @user := '' )AS t
                     )AS E
                     WHERE E.diff IS NOT NULL AND E.diff < '00:15:00'
                     GROUP BY E.pos_id
             	)b ON a.pos_id = b.pos_id
             	GROUP BY 공간, 컨텐츠
             )a
             $searchCate
             GROUP BY 공간, 컨텐츠
             ORDER BY 사용횟수 $order, pos_id, 공간, 컨텐츠, avg_dif
             ";



      $conn->DBQ($sql);
      $conn->DBE();
         $i = 0;
         $j = 2;

      while($row=$conn->DBF()){

        if($row['avg_dif'] == null){
          $data = '00분 01초';
        } else {
          $data = substr($row['avg_dif'],3,2).'분 '.substr($row['avg_dif'],6,2).'초';
        }


          $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue("A$j", $i+1)
                        ->setCellValue("B$j", $row['구분'])
                        ->setCellValue("C$j", $row['공간'])
                        ->setCellValue("D$j", $row['컨텐츠'])
                        ->setCellValue("E$j", number_format($row['사용횟수']))
                        ->setCellValue("F$j", $data);
                        $j++;
                        $i++;
                     }




                     $objPHPExcel->getActiveSheet()->getStyle("A1:I$j")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                     $objPHPExcel->getActiveSheet()->getStyle("A1:I$j")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

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
