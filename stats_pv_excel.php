<?php
require_once('assets/PHPExcel/Classes/PHPExcel.php');
include 'api/common.php';
include 'api/dbconn.php';
$filename = "pv 이용현황 (".$date_from." - ".$date_to.").xlsx";




$conn = new DBC();
$conn->DBI();



$objPHPExcel = new PHPExcel();
// $order = $_GET['order'];
if($_GET['order'] == null){
  $order = 'desc';
} else {
  $order = $_GET['order'];
}

if($_GET['space_id'] == null){
  $searchSpace = "";
} else {
  $searchSpace = " and d.space_id = '".$_GET['space_id']."'";
}

if($_GET['selectID'] == '전체'){
  $union = 'union';
  $uni = "SELECT *
  FROM (
    SELECT did_log_type_3.pos_id AS 'pos_code', 'U+tv' AS '구분',did_space_config.TEXT  AS 'space_id',
    did_bookmark_config.target_id AS 'target_id', did_bookmark_config.DESC AS 'text', did_log_type_3.TIMESTAMP AS 'timestamp', COUNT(did_log_type_3.pos_id) AS 'PV',  a.avg_dif
    FROM
    (
      SELECT
      *, SEC_TO_TIME(AVG(TIME_TO_SEC(E.diff))) AS avg_dif
      FROM
      (
        SELECT
        A.pos_id,
        A.device_id ,
        @var ,
        CASE WHEN @user = device_id THEN 0 ELSE  @var := 0 END,
        TIMEDIFF( STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s') , @var) AS diff,
        @user := device_id,@var := STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')
        FROM
        (
          SELECT *
          FROM did_log_type_3
          WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
          GROUP BY device_id , TIMESTAMP
          ORDER BY device_id,TIMESTAMP
        )AS A,(SELECT @var := 0, @user := '' )AS t

      )AS E
      WHERE E.diff IS NOT NULL AND E.diff < '00:15:00'
      GROUP BY E.pos_id
    )a
    RIGHT JOIN
    did_log_type_3 ON a.pos_id = did_log_type_3.pos_id
    LEFT JOIN
    did_bookmark_config ON
    did_log_type_3.space_id = did_bookmark_config.target_id
    LEFT JOIN
    did_space_config ON
    did_log_type_3.space_id  = did_space_config.space_id
    WHERE  did_log_type_3.page_id = 'p900005' and DATE(STR_TO_DATE(did_log_type_3.TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."' $searchSpace
    GROUP BY did_log_type_3.space_id
  )b";
} else {
  $union = '';
  $uni = '';
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



	 if($_GET['selectID'] == 'TV'){
		 $sql = "SELECT * FROM (
			 SELECT did_log_type_3.pos_id AS 'pos_code', 'U+tv' AS '구분', did_space_config.TEXT  AS 'space_id',
			 did_bookmark_config.target_id AS 'target_id', did_bookmark_config.DESC AS 'text', did_log_type_3.TIMESTAMP AS 'timestamp', COUNT(did_log_type_3.pos_id) AS 'PV',  a.avg_dif
			 FROM
			 (
				 SELECT
				 *, SEC_TO_TIME(AVG(TIME_TO_SEC(E.diff))) AS avg_dif
				 FROM
				 (
					 SELECT
					 A.pos_id,
					 A.device_id ,
					 @var ,
					 CASE WHEN @user = device_id THEN 0 ELSE  @var := 0 END,
					 TIMEDIFF( STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s') , @var) AS diff,
					 @user := device_id,@var := STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')
					 FROM
					 (
						 SELECT *
						 FROM did_log_type_3
						 WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
						 GROUP BY device_id , TIMESTAMP
						 ORDER BY device_id,TIMESTAMP
					 )AS A,(SELECT @var := 0, @user := '' )AS t

				 )AS E
				 WHERE E.diff IS NOT NULL AND E.diff < '00:15:00'
				 GROUP BY E.pos_id
			 )a
			 RIGHT JOIN
			 did_log_type_3 ON a.pos_id = did_log_type_3.pos_id
			 LEFT JOIN
			 did_bookmark_config ON
			 did_log_type_3.space_id = did_bookmark_config.target_id
			 LEFT JOIN
			 did_space_config ON
			 did_log_type_3.space_id COLLATE UTF8MB4_GENERAL_CI = did_space_config.space_id
			 WHERE  did_log_type_3.page_id = 'p900005' and DATE(STR_TO_DATE(did_log_type_3.TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN'".$date_from."' AND '".$date_to."' $searchSpace
			 GROUP BY did_log_type_3.space_id)b
			 ORDER BY PV $order
			 ";}
			 else {
				 $sql =
				 "SELECT *
				 FROM (
					 SELECT g.pos_code AS 'pos_code', 'U+IoT' AS '구분', h.TEXT AS 'space_id', d.target_id AS 'target_id', IFNULL(e.TEXT,f.TEXT) AS 'text', d.TIMESTAMP AS 'timestamp',
					 COUNT(d.log_seq) AS 'PV', a.avg_dif
					 FROM
					 (
						 SELECT
						 *, SEC_TO_TIME(AVG(TIME_TO_SEC(E.diff))) AS avg_dif
						 FROM
						 (
							 SELECT
							 A.pos_id,
							 A.device_id ,
							 @var ,
							 CASE WHEN @user = device_id THEN 0 ELSE  @var := 0 END,
							 TIMEDIFF( STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s') , @var) AS diff,
							 @user := device_id,@var := STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')
							 FROM
							 (
								 SELECT *
								 FROM did_log_type_4
								 WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
								 GROUP BY device_id , TIMESTAMP
								 ORDER BY device_id,TIMESTAMP
							 )AS A,(SELECT @var := 0, @user := '' )AS t

						 )AS E
						 WHERE E.diff IS NOT NULL AND E.diff < '00:15:00'
						 GROUP BY E.pos_id
					 )a
					 LEFT JOIN
					 (
						 SELECT pos_id, device_id, space_id, target_id, TIMESTAMP, log_seq
						 FROM did_log_type_4
					 ) d ON a.pos_id = d.pos_id
					 LEFT JOIN
					 (
						 SELECT product_id, TEXT
						 FROM did_product_config
					 ) f ON d.target_id = f.product_id
					 LEFT JOIN
					 (
						 SELECT usescene_id, TEXT, target_id
						 FROM did_usecase_config
					 ) e ON d.target_id = e.usescene_id AND d.space_id=e.target_id
					 LEFT JOIN
					 (
						 SELECT pos_code
						 FROM did_pos_code
					 ) g ON d.pos_id = g.pos_code
					 LEFT JOIN
					 did_space_config h
					 ON
					 d.space_id COLLATE utf8mb4_general_ci = h.space_id
					 WHERE pos_code IS NOT NULL AND DATE(d.TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."' $searchSpace
					 GROUP BY space_id,d.target_id)a
					 $union
					 $uni
					 ORDER BY PV $order
					 ";}



      $conn->DBQ($sql);
      $conn->DBE();
			$i = 0;
			$j = 2;

      while($row=$conn->DBF()){


			 $objPHPExcel->setActiveSheetIndex(0)
								->setCellValue("A$j", $i+1)
								->setCellValue("B$j", $row['구분'])
								->setCellValue("C$j", $row['space_id'])
								->setCellValue("D$j", $row['text'])
								->setCellValue("E$j", number_format($row['PV']))
								->setCellValue("F$j", substr($row['avg_dif'],3,2).'분 '.substr($row['avg_dif'],6,2).'초');
								$j++;
								$i++;
							}




							$objPHPExcel->getActiveSheet()->getStyle("A1:I$j")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							$objPHPExcel->getActiveSheet()->getStyle("A1:I$j")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

									header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
									header('Content-Disposition: attachment;filename='.$filename);
									header('Cache-Control: max-age=0');
									// If you're serving to IE 9, then the following may be needed
									header('Cache-Control: max-age=1');

									// If you're serving to IE over SSL, then the following may be needed
									header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
									header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
									header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
									header ('Pragma: public'); // HTTP/1.0

									$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
									$objWriter->save('php://output');
									exit;
									?>
