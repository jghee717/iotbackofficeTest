<?php
require_once('../../assets/PHPExcel/Classes/PHPExcel.php');
include '../common.php';
include '../dbconn.php';

$filename = iconv("UTF-8", "EUC-KR", "가입혜택(견적)사용이력 (".$date_from." - ".$date_to.")");





$conn = new DBC();
$conn->DBI();



$objPHPExcel = new PHPExcel();



$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(29);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(17);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(17);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(17);
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(17);
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(17);
$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(17);
$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(17);
$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(19);
$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(19);
$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(20);
$objPHPExcel->getActiveSheet()->getStyle("A1:AD1")->getFont()->setBold(true);

$objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue("A1", "")
          ->setCellValue("B1", "AI리모컨")
          ->setCellValue("C1", "스위치")
          ->setCellValue("D1", "멀티탭")
          ->setCellValue("E1", "열림알리미")
          ->setCellValue("F1", "숙면등")
          ->setCellValue("G1", "숙면알리미")
          ->setCellValue("H1", "CCTV")
					->setCellValue("I1", "가스잠그미")
          ->setCellValue("J1", "플러그")
          ->setCellValue("K1", "공기질알리미")
          ->setCellValue("L1", "간편버튼")
          ->setCellValue("M1", "전기료알리미"); //테이블


      $sql = "
      SELECT COUNT(target_id),
      (SELECT COUNT(target_id) FROM did_log_type_5 inner JOIN did_pos_code ON did_log_type_5.pos_id = did_pos_code.pos_code WHERE target_id='p000001' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') 'AI리모컨 추가',
      (SELECT COUNT(target_id) FROM did_log_type_5 inner JOIN did_pos_code ON did_log_type_5.pos_id = did_pos_code.pos_code WHERE target_id='p000002' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '스위치 추가',
      (SELECT COUNT(target_id) FROM did_log_type_5 inner JOIN did_pos_code ON did_log_type_5.pos_id = did_pos_code.pos_code WHERE target_id='p000003' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '멀티탭 추가',
      (SELECT COUNT(target_id) FROM did_log_type_5 inner JOIN did_pos_code ON did_log_type_5.pos_id = did_pos_code.pos_code WHERE target_id='p000004' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '열림알리미 추가',
      (SELECT COUNT(target_id) FROM did_log_type_5 inner JOIN did_pos_code ON did_log_type_5.pos_id = did_pos_code.pos_code WHERE target_id='p000005' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '숙면등 추가',
      (SELECT COUNT(target_id) FROM did_log_type_5 inner JOIN did_pos_code ON did_log_type_5.pos_id = did_pos_code.pos_code WHERE target_id='p000006' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '숙면알리미 추가',
      (SELECT COUNT(target_id) FROM did_log_type_5 inner JOIN did_pos_code ON did_log_type_5.pos_id = did_pos_code.pos_code WHERE target_id='p000007' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') 'CCTV 추가',
      (SELECT COUNT(target_id) FROM did_log_type_5 inner JOIN did_pos_code ON did_log_type_5.pos_id = did_pos_code.pos_code WHERE target_id='p000008' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '가스잠그미 추가',
      (SELECT COUNT(target_id) FROM did_log_type_5 inner JOIN did_pos_code ON did_log_type_5.pos_id = did_pos_code.pos_code WHERE target_id='p000009' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '플러그 추가',
      (SELECT COUNT(target_id) FROM did_log_type_5 inner JOIN did_pos_code ON did_log_type_5.pos_id = did_pos_code.pos_code WHERE target_id='p000010' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '공기질알리미 추가',
      (SELECT COUNT(target_id) FROM did_log_type_5 inner JOIN did_pos_code ON did_log_type_5.pos_id = did_pos_code.pos_code WHERE target_id='p000011' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '간편버튼 추가',
      (SELECT COUNT(target_id) FROM did_log_type_5 inner JOIN did_pos_code ON did_log_type_5.pos_id = did_pos_code.pos_code WHERE target_id='p000012' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '전기료알리미 추가'
      FROM did_log_type_5 inner JOIN did_pos_code ON did_log_type_5.pos_id = did_pos_code.pos_code
      WHERE target_id != 'id000001' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
      ";
      $conn->DBQ($sql);
      $conn->DBE();
      $row=$conn->DBF();

        if($row['AI리모컨 추가'] == null)
        {
          $data = '0';
        }
        else
        {
          $data = $row['AI리모컨 추가'];
        }

        if($row['스위치 추가'] == null)
        {
          $data2 = '0';
        }
        else
        {
          $data2 =$row['스위치 추가'];
        }

        if($row['멀티탭 추가'] == null)
        {
          $data3 = '0';
        }
        else
        {
          $data3 = $row['멀티탭 추가'];
        }

        if($row['열림알리미 추가'] == null)
        {
          $data4 = '0';
        }
        else
        {
          $data4 = $row['열림알리미 추가'];
        }

        if($row['숙면등 추가'] == null)
        {
          $data5 = '0';
        }
        else
        {
          $data5 = $row['숙면등 추가'];
        }

        if($row['숙면알리미 추가'] == null)
        {
          $data6 = '0';
        }
        else
        {
          $data6 = $row['숙면알리미 추가'];
        }


        if($row['CCTV 추가'] == null)
        {
          $data7 = '0';
        }
        else
        {
          $data7 = $row['CCTV 추가'];
        }
        if($row['가스잠그미 추가'] == null)
        {
          $data8 = '0';
        }
        else
        {
          $data8 = $row['가스잠그미 추가'];
        }
        if($row['플러그 추가'] == null)
        {
          $data9 = '0';
        }
        else
        {
          $data9= $row['플러그 추가'];
        }
        if($row['공기질알리미 추가'] == null)
        {
          $data10= '0';
        }
        else
        {
          $data10= $row['공기질알리미 추가'];
        }
        if($row['간편버튼 추가'] == null)
        {
          $data11 = '0';
        }
        else
        {
          $data11 = $row['간편버튼 추가'];
        }
        if($row['전기료알리미 추가'] == null)
        {
          $data12 = '0';
        }
        else
        {
          $data12 = $row['전기료알리미 추가'];
        }

        $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue("A2", "추가종합")
                  ->setCellValue("B2", $data)
                  ->setCellValue("C2", $data2)
                  ->setCellValue("D2", $data3)
                  ->setCellValue("E2", $data4)
                  ->setCellValue("F2", $data5)
                  ->setCellValue("G2", $data6)
                  ->setCellValue("H2", $data7)
                  ->setCellValue("I2", $data8)
                  ->setCellValue("J2", $data9)
                  ->setCellValue("K2", $data10)
                  ->setCellValue("L2", $data11)
                  ->setCellValue("M2", $data12);

//------------/추가총합----------------------//

//--------------삭제총합

      $sql = "
      SELECT COUNT(target_id),
      (SELECT COUNT(target_id) FROM did_log_type_6 inner JOIN did_pos_code ON did_log_type_6.pos_id = did_pos_code.pos_code WHERE target_id='p000001' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') 'AI리모컨 삭제',
      (SELECT COUNT(target_id) FROM did_log_type_6 inner JOIN did_pos_code ON did_log_type_6.pos_id = did_pos_code.pos_code WHERE target_id='p000002' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '스위치 삭제',
      (SELECT COUNT(target_id) FROM did_log_type_6 inner JOIN did_pos_code ON did_log_type_6.pos_id = did_pos_code.pos_code WHERE target_id='p000003' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '멀티탭 삭제',
      (SELECT COUNT(target_id) FROM did_log_type_6 inner JOIN did_pos_code ON did_log_type_6.pos_id = did_pos_code.pos_code WHERE target_id='p000004' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '열림알리미 삭제',
      (SELECT COUNT(target_id) FROM did_log_type_6 inner JOIN did_pos_code ON did_log_type_6.pos_id = did_pos_code.pos_code WHERE target_id='p000005' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '숙면등 삭제',
      (SELECT COUNT(target_id) FROM did_log_type_6 inner JOIN did_pos_code ON did_log_type_6.pos_id = did_pos_code.pos_code WHERE target_id='p000006' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '숙면알리미 삭제',
      (SELECT COUNT(target_id) FROM did_log_type_6 inner JOIN did_pos_code ON did_log_type_6.pos_id = did_pos_code.pos_code WHERE target_id='p000007' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') 'CCTV 삭제',
      (SELECT COUNT(target_id) FROM did_log_type_6 inner JOIN did_pos_code ON did_log_type_6.pos_id = did_pos_code.pos_code WHERE target_id='p000008' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '가스잠그미 삭제',
      (SELECT COUNT(target_id) FROM did_log_type_6 inner JOIN did_pos_code ON did_log_type_6.pos_id = did_pos_code.pos_code WHERE target_id='p000009' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '플러그 삭제',
      (SELECT COUNT(target_id) FROM did_log_type_6 inner JOIN did_pos_code ON did_log_type_6.pos_id = did_pos_code.pos_code WHERE target_id='p000010' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '공기질알리미 삭제',
      (SELECT COUNT(target_id) FROM did_log_type_6 inner JOIN did_pos_code ON did_log_type_6.pos_id = did_pos_code.pos_code WHERE target_id='p000011' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '간편버튼 삭제',
      (SELECT COUNT(target_id) FROM did_log_type_6 inner JOIN did_pos_code ON did_log_type_6.pos_id = did_pos_code.pos_code WHERE target_id='p000012' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '전기료알리미 삭제'
      FROM did_log_type_6 inner JOIN did_pos_code ON did_log_type_6.pos_id = did_pos_code.pos_code
      WHERE target_id != 'id000001' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
      ";
      $conn->DBQ($sql);
      $conn->DBE();

      $row=$conn->DBF();

			if($row['AI리모컨 삭제'] == null)
			{
				$data = '0';
			}
			else
			{
				$data = $row['AI리모컨 삭제'];
			}

			if($row['스위치 삭제'] == null)
			{
				$data2 = '0';
			}
			else
			{
				$data2 = $row['스위치 삭제'];
			}

			if($row['멀티탭 삭제'] == null)
			{
				$data3 = '0';
			}
			else
			{
				$data3 = $row['멀티탭 삭제'];
			}

			if($row['열림알리미 삭제'] == null)
			{
				$data4 = '0';
			}
			else
			{
				$data4 = $row['열림알리미 삭제'];
			}

			if($row['숙면등 삭제'] == null)
			{
				$data5 = '0';
			}
			else
			{
				$data5 = $row['숙면등 삭제'];
			}

			if($row['숙면알리미 삭제'] == null)
			{
				$data6 = '0';
			}
			else
			{
				$data6 = $row['숙면알리미 삭제'];
			}


			if($row['CCTV 삭제'] == null)
			{
				$data7 = '0';
			}
			else
			{
				$data7 = $row['CCTV 삭제'];
			}
			if($row['가스잠그미 삭제'] == null)
			{
				$data8 = '0';
			}
			else
			{
				$data8 = $row['가스잠그미 삭제'];
			}
			if($row['플러그 삭제'] == null)
			{
				$data9 = '0';
			}
			else
			{
				$data9= $row['플러그 삭제'];
			}
			if($row['공기질알리미 삭제'] == null)
			{
				$data10= '0';
			}
			else
			{
				$data10= $row['공기질알리미 삭제'];
			}
			if($row['간편버튼 삭제'] == null)
			{
				$data11 = '0';
			}
			else
			{
				$data11 = $row['간편버튼 삭제'];
			}
			if($row['전기료알리미 삭제'] == null)
			{
				$data12 = '0';
			}
			else
			{
				$data12 = $row['전기료알리미 삭제'];
			}

			$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue("A3", "삭제총합")
								->setCellValue("B3", $data)
								->setCellValue("C3", $data2)
								->setCellValue("D3", $data3)
								->setCellValue("E3", $data4)
								->setCellValue("F3", $data5)
								->setCellValue("G3", $data6)
								->setCellValue("H3", $data7)
								->setCellValue("I3", $data8)
								->setCellValue("J3", $data9)
								->setCellValue("K3", $data10)
								->setCellValue("L3", $data11)
								->setCellValue("M3", $data12);


//------------------/삭제총합---------------------------------

//------------------데이터 테이블----------------



$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue("A5", "No")
					->setCellValue("B5", "영업담당")
					->setCellValue("C5", "지원팀")
					->setCellValue("D5", "운영자명")
					->setCellValue("E5", "매장명")
					->setCellValue("F5", "매장코드")
					->setCellValue("G5", "AI리모컨추가")
					->setCellValue("H5", "AI리모컨삭제")
					->setCellValue("I5", "스위치추가")
					->setCellValue("J5", "스위치삭제")
					->setCellValue("K5", "멀티탭추가")
					->setCellValue("L5", "멀티탭삭제")
					->setCellValue("M5", "열림알리미추가")
					->setCellValue("N5", "열림알리미삭제")
					->setCellValue("O5", "숙면등추가")
					->setCellValue("P5", "숙면등삭제")
					->setCellValue("Q5", "숙면알리미추가")
					->setCellValue("R5", "숙면알리미삭제")
					->setCellValue("S5", "CCTV추가")
					->setCellValue("T5", "CCTV삭제")
				  ->setCellValue("U5", "가스잠그미추가")
				  ->setCellValue("V5", "가스잠그미삭제")
					->setCellValue("W5", "플러그추가")
					->setCellValue("X5", "플러그삭제")
					->setCellValue("Y5", "공기질알리미추가")
					->setCellValue("Z5", "공기질알리미삭제")
					->setCellValue("AA5", "간편버튼추가")
					->setCellValue("AB5", "간편버튼삭제")
					->setCellValue("AC5", "전기료알리미추가")
					->setCellValue("AD5", "전기료알리미삭제");


      $sql = "
      SELECT pos.CHANNEL AS '영업담당', pos.bg_code AS '지원팀' , pos.agency_name AS '운영자명', pos.pos_name AS '매장명',
              a.pos_id AS '매장코드', pos.pos_address AS '매장주소',
              (SELECT COUNT(target_id) FROM did_log_type_5 WHERE target_id = 'p000001' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') 'AI리모컨추가',
              (SELECT COUNT(target_id) FROM did_log_type_6 WHERE target_id = 'p000001' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') 'AI리모컨삭제',
              (SELECT COUNT(target_id) FROM did_log_type_5 WHERE target_id = 'p000002' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '스위치추가',
              (SELECT COUNT(target_id) FROM did_log_type_6 WHERE target_id = 'p000002' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '스위치삭제',
              (SELECT COUNT(target_id) FROM did_log_type_5 WHERE target_id = 'p000003' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '멀티탭추가',
              (SELECT COUNT(target_id) FROM did_log_type_6 WHERE target_id = 'p000003' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '멀티탭삭제',
              (SELECT COUNT(target_id) FROM did_log_type_5 WHERE target_id = 'p000004' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '열림알리미추가',
              (SELECT COUNT(target_id) FROM did_log_type_6 WHERE target_id = 'p000004' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '열림알리미삭제',
              (SELECT COUNT(target_id) FROM did_log_type_5 WHERE target_id = 'p000005' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '숙면등추가',
              (SELECT COUNT(target_id) FROM did_log_type_6 WHERE target_id = 'p000005' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '숙면등삭제',
              (SELECT COUNT(target_id) FROM did_log_type_5 WHERE target_id = 'p000006' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '숙면알리미추가',
              (SELECT COUNT(target_id) FROM did_log_type_6 WHERE target_id = 'p000006' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '숙면알리미삭제',
              (SELECT COUNT(target_id) FROM did_log_type_5 WHERE target_id = 'p000007' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') 'CCTV추가',
              (SELECT COUNT(target_id) FROM did_log_type_6 WHERE target_id = 'p000007' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') 'CCTV삭제',
              (SELECT COUNT(target_id) FROM did_log_type_5 WHERE target_id = 'p000008' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '가스잠그미추가',
              (SELECT COUNT(target_id) FROM did_log_type_6 WHERE target_id = 'p000008' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '가스잠그미삭제',
              (SELECT COUNT(target_id) FROM did_log_type_5 WHERE target_id = 'p000009' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '플러그추가',
              (SELECT COUNT(target_id) FROM did_log_type_6 WHERE target_id = 'p000009' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '플러그삭제',
              (SELECT COUNT(target_id) FROM did_log_type_5 WHERE target_id = 'p000010' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '공기질알리미추가',
              (SELECT COUNT(target_id) FROM did_log_type_6 WHERE target_id = 'p000010' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '공기질알리미삭제',
              (SELECT COUNT(target_id) FROM did_log_type_5 WHERE target_id = 'p000011' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '간편버튼추가',
              (SELECT COUNT(target_id) FROM did_log_type_6 WHERE target_id = 'p000011' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '간편버튼삭제',
              (SELECT COUNT(target_id) FROM did_log_type_5 WHERE target_id = 'p000012' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '전기료알리미추가',
              (SELECT COUNT(target_id) FROM did_log_type_6 WHERE target_id = 'p000012' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '전기료알리미삭제',
              (
              SELECT COUNT(target_id)
              FROM (
              SELECT *
              FROM did_log_type_5 UNION ALL
              SELECT *
              FROM did_log_type_6)b
              WHERE b.pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
              )AS '총합'
      FROM(
      SELECT * FROM did_log_type_5
      UNION ALL
      SELECT * FROM did_log_type_6
      )a
      RIGHT JOIN did_pos_code pos ON a.pos_id = pos.pos_code
      WHERE a.target_id != 'id000001' AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
      GROUP BY a.pos_id ORDER BY 총합 desc, 매장코드
      ";
      // echo $sql;
      $conn->DBQ($sql);
      $conn->DBE();
			$i = 0;
			$j = 6;
      while($row=$conn->DBF()){

				if($row['영업담당'] == null)
				{
					$data = '-';
				}
        else if($row['영업담당'] == '홈/미디어')
        {
          $data = '스마트홈';
        }
				else
				{
					$data = $row['영업담당'];
				}

				if($row['지원팀'] == null)
				{
					$data2 = '-';
				}
				else
				{
					$data2 = $row['지원팀'];
				}

				if($row['운영자명'] == null)
				{
					$data3 = '-';
				}
				else
				{
					$data3 = $row['운영자명'];
				}

				if($row['매장명'] == null)
				{
					$data4 = '-';
				}
				else
				{
					$data4 = $row['매장명'];
				}

				if($row['매장코드'] == null)
				{
					$data5 = '-';
				}
				else
				{
					$data5 = $row['매장코드'];
				}

				if($row['AI리모컨추가'] == null)
				{
					$data6 = '0';
				}
				else
				{
					$data6 = $row['AI리모컨추가'];
				}

				if($row['AI리모컨삭제'] == null)
				{
					$data7 = '0';
				}
				else
				{
					$data7 = $row['AI리모컨삭제'];
				}

				if($row['스위치추가'] == null)
				{
					$data8 = '0';
				}
				else
				{
					$data8 = $row['스위치추가'];
				}

				if($row['스위치삭제'] == null)
				{
					$data9 = '0';
				}
				else
				{
					$data9 = $row['스위치삭제'];
				}

				if($row['멀티탭추가'] == null)
				{
					$data10 = '0';
				}
				else
				{
					$data10 = $row['멀티탭추가'];
				}

				if($row['멀티탭삭제'] == null)
				{
					$data11 = '0';
				}
				else
				{
					$data11 = $row['멀티탭삭제'];
				}

				if($row['열림알리미추가'] == null)
				{
					$data12 = '0';
				}
				else
				{
					$data12 = $row['열림알리미추가'];
				}

				if($row['열림알리미삭제'] == null)
				{
					$data13 = '0';
				}
				else
				{
					$data13 = $row['열림알리미삭제'];
				}

				if($row['숙면등추가'] == null)
				{
					$data14 = '0';
				}
				else
				{
					$data14 = $row['숙면등추가'];
				}

				if($row['숙면등삭제'] == null)
				{
					$data15 = '0';
				}
				else
				{
					$data15 = $row['숙면등삭제'];
				}

				if($row['숙면알리미추가'] == null)
				{
					$data16 = '0';
				}
				else
				{
					$data16 = $row['숙면알리미추가'];
				}

				if($row['숙면알리미삭제'] == null)
				{
					$data17 = '0';
				}
				else
				{
					$data17 = $row['숙면알리미삭제'];
				}

				if($row['CCTV추가'] == null)
				{
					$data18 = '0';
				}
				else
				{
					$data18 = $row['CCTV추가'];
				}

				if($row['CCTV삭제'] == null)
				{
					$data19 = '0';
				}
				else
				{
					$data19 = $row['CCTV삭제'];
				}

				if($row['가스잠그미추가'] == null)
				{
					$data20 = '0';
				}
				else
				{
					$data20 = $row['가스잠그미추가'];
				}

				if($row['가스잠그미삭제'] == null)
				{
					$data21 = '0';
				}
				else
				{
					$data21 = $row['가스잠그미삭제'];
				}

				if($row['플러그추가'] == null)
				{
					$data22 = '0';
				}
				else
				{
					$data22 = $row['플러그추가'];
				}

				if($row['플러그삭제'] == null)
				{
					$data23 = '0';
				}
				else
				{
					$data23 = $row['플러그삭제'];
				}

				if($row['공기질알리미추가'] == null)
				{
					$data24 = '0';
				}
				else
				{
					$data24 = $row['공기질알리미추가'];
				}

				if($row['공기질알리미삭제'] == null)
				{
					$data25 = '0';
				}
				else
				{
					$data25 = $row['공기질알리미삭제'];
				}

				if($row['간편버튼추가'] == null)
				{
					$data26 = '0';
				}
				else
				{
					$data26 = $row['간편버튼추가'];
				}

				if($row['간편버튼삭제'] == null)
				{
					$data27 = '0';
				}
				else
				{
					$data27 = $row['간편버튼삭제'];
				}

				if($row['전기료알리미추가'] == null)
				{
					$data28= '0';
				}
				else
				{
					$data28 = $row['전기료알리미추가'];
				}

				if($row['전기료알리미삭제'] == null)
				{
					$data29 = '0';
				}
				else
				{
					$data29 = $row['전기료알리미삭제'];
				}

				$objPHPExcel->setActiveSheetIndex(0)
									->setCellValue("A$j", $i+1)
									->setCellValue("B$j", $data)
									->setCellValue("C$j", $data2)
									->setCellValue("D$j", $data3)
									->setCellValue("E$j", $data4)
									->setCellValue("F$j", $data5)
									->setCellValue("G$j", $data6)
									->setCellValue("H$j", $data7)
									->setCellValue("I$j", $data8)
									->setCellValue("J$j", $data9)
									->setCellValue("K$j", $data10)
									->setCellValue("L$j", $data11)
									->setCellValue("M$j", $data12)
									->setCellValue("N$j", $data13)
									->setCellValue("O$j", $data14)
									->setCellValue("P$j", $data15)
									->setCellValue("Q$j", $data16)
									->setCellValue("R$j", $data17)
									->setCellValue("S$j", $data18)
									->setCellValue("T$j", $data19)
								  ->setCellValue("U$j", $data20)
								  ->setCellValue("V$j", $data21)
									->setCellValue("W$j", $data22)
									->setCellValue("X$j", $data23)
									->setCellValue("Y$j", $data24)
									->setCellValue("Z$j", $data25)
									->setCellValue("AA$j", $data26)
									->setCellValue("AB$j", $data27)
									->setCellValue("AC$j", $data28)
									->setCellValue("AD$j", $data29);
									$i++;
									$j++;
			}


$objPHPExcel->getActiveSheet()->getStyle("A1:AD5$j")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A1:AD5$j")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

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
