<?php
require_once('../../assets/PHPExcel/Classes/PHPExcel.php');
//include '../common.php';
include 'layout/layout.php';

class DBC
{
  public $conn; //pdo 객체 생성용 필드
  public $result; //쿼리 실행 결과 필드

  public function DBI() //DB IN (접속)
  {
    $this->conn = new PDO('mysql:host=localhost;dbname=smarthome_test', 'testuser', 'iotest2@');
    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->conn->exec("SET NAMES 'utf8'");
  }

  public function DBQ($q) //DB QUERY IN (쿼리 투척)
  {
    $this->result = $this->conn->prepare($q);
  }

  public function DBE() //DB QUERY Execute (쿼리 실행)
  {
    $this->result->execute();
  }

  public function DBO() //DB OUT (종료)
  {
    $this->conn = null;
    $this->result = null;
  }

  public function DBF() //DB FETCH (결과 출력)
  {
    return $this->result->fetch();
  }

  public function DBN()
  {
    return $this->result->fetch_num();
  }

  public function resultRow() //rowcount (실행 결과 행 개수)
  {
    return $this->result->rowCount();
  }

  public function lastId() //insert 된 마지막 컬럼 PK값을 출력 (AI+PK)
  {
    return $this->conn->lastInsertId();
  }
  public function DBP() // 모든 행 가져오기
  {
    return $this->result->fetchAll();
  }
}

$conn = new DBC();
$conn->DBI();

$objPHPExcel = new PHPExcel();
$filename = "csv_sample.csv";






$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue("A1", "영업담당")
->setCellValue("B1", "지원팀")
->setCellValue("C1", "투자유형")
->setCellValue("D1", "운영자명")
->setCellValue("E1", "운영자코드")
->setCellValue("F1", "매장명")
->setCellValue("G1", "매장코드")
->setCellValue("H1", "매장주소");

echo "\xEF\xBB\xBF";

//header('Content-Type: text/csv');
//header('Content-Disposition: attachment;filename="'.gmdate('Ymd').'.csv"'); header('Cache-Control: max-age=0');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename='.$filename);


// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
$objWriter->save('php://output');
exit;
?>
