
<?php
include 'api/dbconn.php';

$conn = new DBC();
$conn->DBI();

if(isset($_POST["employee_id"]))
{
$output = '';
$sql =  "SELECT chanel, bg_code, count(chanel) as store FROM did_pos_code WHERE chanel = '$_GET["employee_id"]' group by bg_code order by count(chanel) desc" ;
$conn->DBQ($sql);
$conn->DBE();
$output .='
<div class="table-responsive">
<table class="table table-bordered">';
while($row = $conn->DBF());
{
$output .='
<div class="input-group">
  <div class="col-lg-3">
    <form name="frm" method="post" action="">
      <select class="col-lg-12" name="search">
        <option value="선택">선택</option>
        <option value="강남">강남</option>
        <option value="강동">강동</option>
        <option value="강북">강북</option>
        <option value="동부">동부</option>
        <option value="서부">서부</option>
        <option value="미디어">홈/미디어</option>
      </select>
    </div>
    <div class="col-lg-6">
      <button class="btn btn-primary btn btn-xs" style="text-align:center;" onClick="fn_s()" type="submit">확인</button>
    </div>
  </form>
</div>
<table class="table table-bordered text-center">
  <thead class="text-uppercase">
    <tr>
      <th>NO.</th>
      <th>영업담당</th>
      <th>매장수</th>
      <th>디바이스 수</th>
      <th>APP 설치수</th>
      <th>APP 설치율</th>
      <th>APP 실행수</th>
      <th>APP 실행율</th>
      <th>상세보기</th>
    </tr>
  </thead>
  <tbody>
  <tr>
    <td><?echo $i+1;?></td>
    <td><?echo $pg[chanel];?></td>
    <td><?echo $pg[bg_code];?></td>
    <td><?echo $pg[store];?></td>
    <td></td>
    <td></td>
    <td>26.4%</td>
    <td>744</td>
    <td>89%</td>
  </tr>';
}
$output .= "</table></div>";
echo $output;
}


?>
