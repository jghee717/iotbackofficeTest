<?
header('Content-Type: text/html; charset=utf-8');
$today = date("Y-m-d");
$path = explode('?', $_SERVER['REQUEST_URI']);

$month_first1 = date("Y-m-"."01");
$month_last1 = date("Y-m-"."31");
$month_first2 = date("Y/m/"."01");
$month_last2 = date("Y/m/"."31");
if($path[0] == '/stats_period.php' && $_GET['date_to'] != null && $_GET['date_set'] != null)
{
  $temp = date('w', strtotime($_GET['date_set']));

  switch ($temp) {
    // 일요일
    case 0:
    $date_from = date("Y-m-d", strtotime("-6 days",  strtotime($_GET['date_set'])));
    $date_to = date("Y-m-d", strtotime("+0 days",  strtotime($_GET['date_set'])));
    break;

    // 월요일
    case 1:
    $date_from = date("Y-m-d", strtotime("-0 days",  strtotime($_GET['date_set'])));
    $date_to = date("Y-m-d", strtotime("+6 days",  strtotime($_GET['date_set'])));
    break;

    // 화요일
    case 2:
    $date_from = date("Y-m-d", strtotime("-1 days",  strtotime($_GET['date_set'])));
    $date_to = date("Y-m-d", strtotime("+5 days",  strtotime($_GET['date_set'])));
    break;

    // 수요일
    case 3:
    $date_from = date("Y-m-d", strtotime("-2 days",  strtotime($_GET['date_set'])));
    $date_to = date("Y-m-d", strtotime("+4 days",  strtotime($_GET['date_set'])));
    break;

    // 목요일
    case 4:
    $date_from = date("Y-m-d", strtotime("-3 days",  strtotime($_GET['date_set'])));
    $date_to = date("Y-m-d", strtotime("+3 days",  strtotime($_GET['date_set'])));
    break;

    // 금요일
    case 5:
    $date_from = date("Y-m-d", strtotime("-4 days",  strtotime($_GET['date_set'])));
    $date_to = date("Y-m-d", strtotime("+2 days",  strtotime($_GET['date_set'])));
    break;

    // 토요일
    case 6:
    $date_from = date("Y-m-d", strtotime("-5 days",  strtotime($_GET['date_set'])));
    $date_to = date("Y-m-d", strtotime("+1 days",  strtotime($_GET['date_set'])));
    break;
  }
}
else if($_GET['date_from'] == null && $_GET['date_to'] == null)
{
  $temp = date('w', strtotime($today));

  switch ($temp) {
    // 일요일
    case 0:
    $date_from = date("Y-m-d", strtotime("-6 days",  strtotime($today)));
    $date_to = date("Y-m-d", strtotime("+0 days",  strtotime($today)));
    break;

    // 월요일
    case 1:
    $date_from = date("Y-m-d", strtotime("-0 days",  strtotime($today)));
    $date_to = date("Y-m-d", strtotime("+6 days",  strtotime($today)));
    break;

    // 화요일
    case 2:
    $date_from = date("Y-m-d", strtotime("-1 days",  strtotime($today)));
    $date_to = date("Y-m-d", strtotime("+5 days",  strtotime($today)));
    break;

    // 수요일
    case 3:
    $date_from = date("Y-m-d", strtotime("-2 days",  strtotime($today)));
    $date_to = date("Y-m-d", strtotime("+4 days",  strtotime($today)));
    break;

    // 목요일
    case 4:
    $date_from = date("Y-m-d", strtotime("-3 days",  strtotime($today)));
    $date_to = date("Y-m-d", strtotime("+3 days",  strtotime($today)));
    break;

    // 금요일
    case 5:
    $date_from = date("Y-m-d", strtotime("-4 days",  strtotime($today)));
    $date_to = date("Y-m-d", strtotime("+2 days",  strtotime($today)));
    break;

    // 토요일
    case 6:
    $date_from = date("Y-m-d", strtotime("-5 days",  strtotime($today)));
    $date_to = date("Y-m-d", strtotime("+1 days",  strtotime($today)));
    break;
  }
}
else if($_GET['date_from'] != null && $_GET['date_to'] != null && $_GET['date_set'] != null)
{
  $temp = date('w', strtotime($_GET['date_set']));

  switch ($temp) {
    // 일요일
    case 0:
    $date_from = date("Y-m-d", strtotime("-6 days",  strtotime($_GET['date_set'])));
    $date_to = date("Y-m-d", strtotime("+0 days",  strtotime($_GET['date_set'])));
    break;

    // 월요일
    case 1:
    $date_from = date("Y-m-d", strtotime("-0 days",  strtotime($_GET['date_set'])));
    $date_to = date("Y-m-d", strtotime("+6 days",  strtotime($_GET['date_set'])));
    break;

    // 화요일
    case 2:
    $date_from = date("Y-m-d", strtotime("-1 days",  strtotime($_GET['date_set'])));
    $date_to = date("Y-m-d", strtotime("+5 days",  strtotime($_GET['date_set'])));
    break;

    // 수요일
    case 3:
    $date_from = date("Y-m-d", strtotime("-2 days",  strtotime($_GET['date_set'])));
    $date_to = date("Y-m-d", strtotime("+4 days",  strtotime($_GET['date_set'])));
    break;

    // 목요일
    case 4:
    $date_from = date("Y-m-d", strtotime("-3 days",  strtotime($_GET['date_set'])));
    $date_to = date("Y-m-d", strtotime("+3 days",  strtotime($_GET['date_set'])));
    break;

    // 금요일
    case 5:
    $date_from = date("Y-m-d", strtotime("-4 days",  strtotime($_GET['date_set'])));
    $date_to = date("Y-m-d", strtotime("+2 days",  strtotime($_GET['date_set'])));
    break;

    // 토요일
    case 6:
    $date_from = date("Y-m-d", strtotime("-5 days",  strtotime($_GET['date_set'])));
    $date_to = date("Y-m-d", strtotime("+1 days",  strtotime($_GET['date_set'])));
    break;
  }
}
else if($_GET['date_from'] != null && $_GET['date_to'] != null)
{
  $date_from = $_GET['date_from'];
  $date_to = $_GET['date_to'];
}
?>
