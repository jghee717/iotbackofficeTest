<?
require_once str_replace('/statsReg','',__DIR__.'/dbconn.php');
require_once str_replace('/statsReg','',__DIR__.'/common.php');




$conn = new DBC();
$conn->DBI();

$sql = "TRUNCATE TABLE pre_period";
$conn->DBQ($sql);
$conn->DBE();

$today_count = $date_from;
$week_count = 1;
while($today_count != '2018-12-31')
{
  $today_count = date("Y-m-d", strtotime("-7 days",  strtotime($today_count)));
  $week_count += 1;
}



for($p=0; $p<$week_count; $p++)
{
  $date_from2 = str_replace('-', '/', $date_from);
  $date_to2 = str_replace('-', '/', $date_to);

  $first_day1 = date("Y-m-d", strtotime("-7 week",  strtotime($date_from)));
  $end_day1 = date("Y-m-d", strtotime("-7 week",  strtotime($date_to)));

  $first_day2 = date("Y/m/d", strtotime("-7 week",  strtotime($date_from2)));
  $end_day2 = date("Y/m/d", strtotime("-7 week",  strtotime($date_to2)));

  $first_month1 = date("Y-".substr($date_from,5,2)."-01");
  $end_month1 = date("Y-".substr($date_from,5,2)."-31");
  $first_month2 = date("Y/".substr($date_from2,5,2)."/01");
  $end_month2 = date("Y/".substr($date_from2,5,2)."/31");

  $fourWeek_first1 = $date_from;
  $fourWeek_end1 = $date_to;

  $fourWeek_first2 = str_replace('-', '/', $date_from);
  $fourWeek_end2 = str_replace('-','/', $date_to);


//기간별 예외처리
  $today_month = explode('-', $date_from);
  switch($today_month[1])
  {
    case 01:
    $today2 = strtotime("-31 days", strtotime($date_from));
    $today3 = strtotime("-0 days", strtotime($date_from));
    $date_condition = date("Y-m", $today2).'-01';
    $date_condition2 = date("Y-m", $today2).'-30';
    $date_condition3 = date("Y-m", $today3).'-01';
    $date_condition4 = date("Y-m", $today3).'-31';
    break;

    case 02:
    $today2 = strtotime("-28 days", strtotime($date_from));
    $today3 = strtotime("-0 days", strtotime($date_from));
    $date_condition = date("Y-m", $today2).'-01';
    $date_condition2 = date("Y-m", $today2).'-31';
    $date_condition3 = date("Y-m", $today3).'-01';
    $date_condition4 = date("Y-m", $today3).'-28';
    break;

    case 03:
    $today2 = strtotime("-31 days", strtotime($date_from));
    $today3 = strtotime("-0 days", strtotime($date_from));
    $date_condition = date("Y-m", $today2).'-01';
    $date_condition2 = date("Y-m", $today2).'-28';
    $date_condition3 = date("Y-m", $today3).'-01';
    $date_condition4 = date("Y-m", $today3).'-31';
    break;

    case 04:
    $today2 = strtotime("-30 days", strtotime($date_from));
    $today3 = strtotime("-0 days", strtotime($date_from));

    $date_condition = date("Y-m", $today2).'-01';
    $date_condition2 = date("Y-m", $today2).'-31';
    $date_condition3 = date("Y-m", $today3).'-01';
    $date_condition4 = date("Y-m", $today3).'-30';
    break;

    case 05:
    $today2 = strtotime("-31 days", strtotime($date_from));
    $today3 = strtotime("-0 days", strtotime($date_from));

    $date_condition = date("Y-m", $today2).'-01';
    $date_condition2 = date("Y-m", $today2).'-30';
    $date_condition3 = date("Y-m", $today3).'-01';
    $date_condition4 = date("Y-m", $today3).'-31';
    break;

    case 06:
    $today2 = strtotime("-30 days", strtotime($date_from));
    $today3 = strtotime("-0 days", strtotime($date_from));
    $date_condition = date("Y-m", $today2).'-01';
    $date_condition2 = date("Y-m", $today2).'-31';
    $date_condition3 = date("Y-m", $today3).'-01';
    $date_condition4 = date("Y-m", $today3).'-30';
    break;

    case 07:
    $today2 = strtotime("-31 days", strtotime($date_from));
    $today3 = strtotime("-0 days", strtotime($date_from));
    $date_condition = date("Y-m", $today2).'-01';
    $date_condition2 = date("Y-m", $today2).'-30';
    $date_condition3 = date("Y-m", $today3).'-01';
    $date_condition4 = date("Y-m", $today3).'-31';
    break;

    case 10:
    $today2 = strtotime("-31 days", strtotime($date_from));
    $today3 = strtotime("-0 days", strtotime($date_from));
    $date_condition = date("Y-m", $today2).'-01';
    $date_condition2 = date("Y-m", $today2).'-30';
    $date_condition3 = date("Y-m", $today3).'-01';
    $date_condition4 = date("Y-m", $today3).'-31';
    break;

    case 11:
    $today2 = strtotime("-30 days", strtotime($date_from));
    $today3 = strtotime("-0 days", strtotime($date_from));
    $date_condition = date("Y-m", $today2).'-01';
    $date_condition2 = date("Y-m", $today2).'-31';
    $date_condition3 = date("Y-m", $today3).'-01';
    $date_condition4 = date("Y-m", $today3).'-30';
    break;

    case 12:
    $today2 = strtotime("-31 days", strtotime($date_from));
    $today3 = strtotime("-0 days", strtotime($date_from));
    $date_condition = date("Y-m", $today2).'-01';
    $date_condition2 = date("Y-m", $today2).'-30';
    $date_condition3 = date("Y-m", $today3).'-01';
    $date_condition4 = date("Y-m", $today3).'-31';
    break;

    default:
    $today2 = strtotime("-30 days", strtotime($date_from));
    $today3 = strtotime("-0 days", strtotime($date_from));
    $date_condition = date("Y-m", $today2).'-01';
    $date_condition2 = date("Y-m", $today2).'-31';
    $date_condition3 = date("Y-m", $today3).'-01';
    $date_condition4 = date("Y-m", $today3).'-30';
    break;
  }



$sql = "SELECT count(d.pos_id)
FROM
(
SELECT UPPER(pos_exec.pos_id) AS `pos_id`, MIN(pos_exec.TIMESTAMP) AS `time`,
MAX(pos_exec.TIMESTAMP) AS `time2`
FROM did_log_type_1 AS `pos_exec`
WHERE pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
GROUP BY pos_exec.pos_id
) AS `d`
INNER
JOIN did_pos_code AS `c` ON d.pos_id = c.pos_code
WHERE c.pos_code IS NOT NULL";
$conn->DBQ($sql);
$conn->DBE();
$certificate_store = $conn->DBF();

$sql = "SELECT count(c.pos_code)
FROM did_pos_code AS `c`
LEFT OUTER JOIN
(
SELECT UPPER(pos_exec.pos_id) AS `pos_id`, MIN(pos_exec.TIMESTAMP) AS `time`, MAX(pos_exec.TIMESTAMP) AS `time2`
FROM did_log_type_1 AS `pos_exec`
WHERE pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
GROUP BY pos_exec.pos_id
)AS `d`
ON d.pos_id = c.pos_code
WHERE d.pos_id IS NULL";
$conn->DBQ($sql);
$conn->DBE();
$un_use_store = $conn->DBF();

$total_store = $certificate_store[0] + $un_use_store[0];



$sql = "SELECT count(d.pos_id)
FROM
(
SELECT UPPER(pos_exec.pos_id) AS `pos_id`, MIN(pos_exec.TIMESTAMP) AS `time`,
MAX(pos_exec.TIMESTAMP) AS `time2`
FROM did_log_type_1 AS `pos_exec`
WHERE pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
GROUP BY pos_exec.pos_id
) AS `d`
INNER
JOIN did_pos_code AS `c` ON d.pos_id = c.pos_code
WHERE c.pos_code IS NOT NULL";
$conn->DBQ($sql);
$conn->DBE();
$installed_store = $conn->DBF();

//총 앱실행
$sql = "
SELECT COUNT(a.pos_id)
FROM
(
	SELECT a.pos_id
	FROM
	(
	   SELECT a.pos_id
	   FROM did_pos_code
	   INNER JOIN
	   (
	      SELECT pos_id
	      FROM did_log_type_3
	      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
	      AND pos_id IS NOT NULL AND pos_id != ''
	      AND DATE(TIMESTAMP) <= '".$date_to."'
	   )a ON did_pos_code.pos_code = a.pos_id
	   WHERE pos_code IS NOT NULL AND pos_code != ''
	   GROUP BY pos_id
	   UNION ALL
	   SELECT a.pos_id
	   FROM did_pos_code
	   INNER JOIN
	   (
	      SELECT pos_id
	      FROM did_log_type_3
	      WHERE space_id IN('s000001','s000002','s000003','s000004')
	      AND pos_id IS NOT NULL AND pos_id != ''
	      AND DATE(TIMESTAMP) <= '".$date_to."'
	   )a ON did_pos_code.pos_code = a.pos_id
	   WHERE pos_code IS NOT NULL AND pos_code != ''
	   GROUP BY pos_id
	   UNION ALL
	   SELECT a.pos_id
	   FROM did_pos_code
	   INNER JOIN
	   (
	      SELECT pos_id
	      FROM did_log_type_4
	      WHERE DATE(TIMESTAMP) <= '".$date_to."'
	      AND pos_id IS NOT NULL AND pos_id != ''
	   )a ON did_pos_code.pos_code = a.pos_id
	   WHERE pos_code IS NOT NULL AND pos_code != ''
	   GROUP BY pos_id
	)a
	GROUP BY a.pos_id
)a
";
$conn->DBQ($sql);
$conn->DBE();
$exec_app_total = $conn->DBF();



$i = 0;
$prev_cnt = 0;
$app_array = array();
for($j=0; $j<8; $j++) {

  switch($j) {
    case 0:
    break;

    default:
    $first_day1 = date("Y-m-d", strtotime("+7 days",  strtotime($first_day1)));
    $end_day1 = date("Y-m-d", strtotime("+7 days",  strtotime($end_day1)));

    $first_day2 = date("Y/m/d", strtotime("+7 days",  strtotime($first_day2)));
    $end_day2 = date("Y/m/d", strtotime("+7 days",  strtotime($end_day2)));
    break;
  }

  // 기간별 총 실행 매장 수
$sql = "
SELECT COUNT(a.pos_id)
FROM
(
	SELECT a.pos_id
	FROM
	(
	   SELECT a.pos_id
	   FROM did_pos_code
	   INNER JOIN
	   (
	      SELECT pos_id
	      FROM did_log_type_3
	      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
	      AND pos_id IS NOT NULL AND pos_id != ''
	      AND DATE(TIMESTAMP) BETWEEN '".$first_day1."' AND '".$end_day1."'
	   )a ON did_pos_code.pos_code = a.pos_id
	   WHERE pos_code IS NOT NULL AND pos_code != ''
	   GROUP BY pos_id
	   UNION ALL
	   SELECT a.pos_id
	   FROM did_pos_code
	   INNER JOIN
	   (
	      SELECT pos_id
	      FROM did_log_type_3
	      WHERE space_id IN('s000001','s000002','s000003','s000004')
	      AND pos_id IS NOT NULL AND pos_id != ''
	      AND DATE(TIMESTAMP) BETWEEN '".$first_day1."' AND '".$end_day1."'
	   )a ON did_pos_code.pos_code = a.pos_id
	   WHERE pos_code IS NOT NULL AND pos_code != ''
	   GROUP BY pos_id
	   UNION ALL
	   SELECT a.pos_id
	   FROM did_pos_code
	   INNER JOIN
	   (
	      SELECT pos_id
	      FROM did_log_type_4
	      WHERE DATE(TIMESTAMP) BETWEEN '".$first_day1."' AND '".$end_day1."'
	      AND pos_id IS NOT NULL AND pos_id != ''
	   )a ON did_pos_code.pos_code = a.pos_id
	   WHERE pos_code IS NOT NULL AND pos_code != ''
	   GROUP BY pos_id
	)a
	GROUP BY a.pos_id
)a
";
  $conn->DBQ($sql);
  $conn->DBE();
  $row=$conn->DBF();

  // 기간별 신규등록 매장 수
  $sql = "
  SELECT COUNT(a.cnt)
  FROM
  (
    SELECT LOG1.pos_id, MIN(LOG1.TIMESTAMP) AS 'cnt'
    FROM did_log_type_1 LOG1 INNER JOIN did_pos_code pos ON LOG1.pos_id = pos.pos_code
    GROUP BY LOG1.pos_id
  )a
  WHERE DATE(a.cnt) BETWEEN '".$first_day1."' AND '".$end_day1."'
  ";
  $conn->DBQ($sql);
  $conn->DBE();
  $row2=$conn->DBF();


  $app_array[$j][0] = $first_day1.' ~ '.$end_day1;
  if($row[0] == null)
  { $app_array[$j][1] = '0'; }
  else
  { $app_array[$j][1] = $row[0]; }

  if($row2[0] == null)
  { $app_array[$j][2] = '0'; }
  else
  { $app_array[$j][2] = $row2[0]; }
  $app_array[$j][3] = number_format(($row[0]/$installed_store[0])*100,2).'%';
  if($prev_cnt == 0)
  { $app_array[$j][4] = '-'; }
  else
  { $app_array[$j][4] = number_format((($row[0] - $prev_cnt)/$prev_cnt)*100,2).'%'; }
  $i++;
  $prev_cnt = $row[0];
}



if(number_format(($exec_app_total[0]/$installed_store[0])*100,2) == nan)
{ $total_app_percent = '0%'; }
else
{ $total_app_percent = number_format(($exec_app_total[0]/$installed_store[0])*100,2).'%'; }



#------------------------UV 공간 이동 전체-------------------------#
// 홈 -> 공간 전체 저번달 uv
$sql = "
SELECT a.cnt + b.cnt AS 'cnt'
FROM
(
	SELECT COUNT(a.TIMESTAMP) AS 'cnt'
	FROM
	(
		SELECT pos_id, device_id, TIMESTAMP, page_id,
		DATE_FORMAT(DATE_ADD(TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
		FROM did_log_type_2
		WHERE page_id = 'p900005' AND is_enter = '1'
		AND DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
    GROUP BY pos_id, device_id, TIMESTAMP
	)a
	INNER JOIN
	(
		SELECT log_key, pos_id, device_id, TIMESTAMP, page_id, space_id
		FROM did_log_type_3
		WHERE space_id IS NOT NULL AND page_id = 'p900005'
		AND DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
		GROUP BY TIMESTAMP
	)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
	AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
)a,
(
	SELECT COUNT(b.TIMESTAMP) AS 'cnt'
	FROM
	(
		SELECT pos_id, device_id, TIMESTAMP, space_id,
		DATE_FORMAT(DATE_ADD(TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
		FROM did_log_type_3
		WHERE space_id BETWEEN 's000001' AND 's000004' AND page_id = 'p900002'
		AND DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
    GROUP BY pos_id, device_id, TIMESTAMP
	)b
	INNER JOIN
	(
		SELECT log_seq, pos_id, device_id, TIMESTAMP, space_id, target_id
		FROM did_log_type_4
		WHERE DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
		AND page_id = 'p900003' AND target_id IS NOT NULL
		GROUP BY TIMESTAMP
	)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
		AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
)b
";
$conn->DBQ($sql);
$conn->DBE();
$uv_last_month_all_total = $conn->DBF();

// 홈 -> 공간 전체 이번달 uv
$sql = "
SELECT a.cnt + b.cnt AS 'cnt'
FROM
(
	SELECT COUNT(a.TIMESTAMP) AS 'cnt'
	FROM
	(
		SELECT pos_id, device_id, TIMESTAMP, page_id,
		DATE_FORMAT(DATE_ADD(TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
		FROM did_log_type_2
		WHERE page_id = 'p900005' AND is_enter = '1'
		AND DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
    GROUP BY pos_id, device_id, TIMESTAMP
	)a
	INNER JOIN
	(
		SELECT log_key, pos_id, device_id, TIMESTAMP, page_id, space_id
		FROM did_log_type_3
		WHERE space_id IS NOT NULL AND page_id = 'p900005'
		AND DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
		GROUP BY TIMESTAMP
	)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
	AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
)a,
(
	SELECT COUNT(b.TIMESTAMP) AS 'cnt'
	FROM
	(
		SELECT pos_id, device_id, TIMESTAMP, space_id,
		DATE_FORMAT(DATE_ADD(TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
		FROM did_log_type_3
		WHERE space_id BETWEEN 's000001' AND 's000004' AND page_id = 'p900002'
		AND DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
    GROUP BY pos_id, device_id, TIMESTAMP
	)b
	INNER JOIN
	(
		SELECT log_seq, pos_id, device_id, TIMESTAMP, space_id, target_id
		FROM did_log_type_4
		WHERE DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
		AND page_id = 'p900003' AND target_id IS NOT NULL
		GROUP BY TIMESTAMP
	)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
		AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
)b
";
$conn->DBQ($sql);
$conn->DBE();
$uv_this_month_all_total = $conn->DBF();

// 홈 -> 공간 전체 이번주 uv
$sql = "
SELECT a.cnt + b.cnt AS 'cnt'
FROM
(
	SELECT COUNT(a.TIMESTAMP) AS 'cnt'
	FROM
	(
		SELECT pos_id, device_id, TIMESTAMP, page_id,
		DATE_FORMAT(DATE_ADD(TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
		FROM did_log_type_2
		WHERE page_id = 'p900005' AND is_enter = '1'
		AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
    GROUP BY pos_id, device_id, TIMESTAMP
	)a
	INNER JOIN
	(
		SELECT log_key, pos_id, device_id, TIMESTAMP, page_id, space_id
		FROM did_log_type_3
		WHERE space_id IS NOT NULL AND page_id = 'p900005'
		AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
		GROUP BY TIMESTAMP
	)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
	AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
)a,
(
	SELECT COUNT(b.TIMESTAMP) AS 'cnt'
	FROM
	(
		SELECT pos_id, device_id, TIMESTAMP, space_id,
		DATE_FORMAT(DATE_ADD(TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
		FROM did_log_type_3
		WHERE space_id BETWEEN 's000001' AND 's000004' AND page_id = 'p900002'
		AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
    GROUP BY pos_id, device_id, TIMESTAMP
	)b
	INNER JOIN
	(
		SELECT log_seq, pos_id, device_id, TIMESTAMP, space_id, target_id
		FROM did_log_type_4
		WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
		AND page_id = 'p900003' AND target_id IS NOT NULL
		GROUP BY TIMESTAMP
	)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
		AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
)b
";
$conn->DBQ($sql);
$conn->DBE();
$uv_this_week_all_total = $conn->DBF();

// 홈 -> 공간 u+tv 저번달 uv
$sql = "
SELECT COUNT(a.TIMESTAMP) AS 'cnt'
FROM
(
	SELECT pos_id, device_id, TIMESTAMP, page_id,
	DATE_FORMAT(DATE_ADD(TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
	FROM did_log_type_2
	WHERE page_id = 'p900005' AND is_enter = '1'
	AND DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
  GROUP BY pos_id, device_id, TIMESTAMP
)a
INNER JOIN
(
	SELECT log_key, pos_id, device_id, TIMESTAMP, page_id, space_id
	FROM did_log_type_3
	WHERE space_id IS NOT NULL AND page_id = 'p900005'
	AND DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
	GROUP BY TIMESTAMP
)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
";
$conn->DBQ($sql);
$conn->DBE();
$uv_last_month_all_tv = $conn->DBF();

// 홈 -> 공간 u+tv 이번달 uv
$sql = "
SELECT COUNT(a.TIMESTAMP) AS 'cnt'
FROM
(
	SELECT pos_id, device_id, TIMESTAMP, page_id,
	DATE_FORMAT(DATE_ADD(TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
	FROM did_log_type_2
	WHERE page_id = 'p900005' AND is_enter = '1'
	AND DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
  GROUP BY pos_id, device_id, TIMESTAMP
)a
INNER JOIN
(
	SELECT log_key, pos_id, device_id, TIMESTAMP, page_id, space_id
	FROM did_log_type_3
	WHERE space_id IS NOT NULL AND page_id = 'p900005'
	AND DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
	GROUP BY TIMESTAMP
)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
";
$conn->DBQ($sql);
$conn->DBE();
$uv_this_month_all_tv = $conn->DBF();

// 홈 -> 공간 u+tv 이번주 uv
$sql = "
SELECT COUNT(a.TIMESTAMP) AS 'cnt'
FROM
(
	SELECT pos_id, device_id, TIMESTAMP, page_id,
	DATE_FORMAT(DATE_ADD(TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
	FROM did_log_type_2
	WHERE page_id = 'p900005' AND is_enter = '1'
	AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
  GROUP BY pos_id, device_id, TIMESTAMP
)a
INNER JOIN
(
	SELECT log_key, pos_id, device_id, TIMESTAMP, page_id, space_id
	FROM did_log_type_3
	WHERE space_id IS NOT NULL AND page_id = 'p900005'
	AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
	GROUP BY TIMESTAMP
)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
";
$conn->DBQ($sql);
$conn->DBE();
$uv_this_week_all_tv = $conn->DBF();

// 홈 -> 공간 u+iot 저번달 uv
$sql = "
SELECT COUNT(b.TIMESTAMP) AS 'cnt'
FROM
(
	SELECT pos_id, device_id, TIMESTAMP, space_id,
	DATE_FORMAT(DATE_ADD(TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
	FROM did_log_type_3
	WHERE space_id BETWEEN 's000001' AND 's000004' AND page_id = 'p900002'
	AND DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
  GROUP BY pos_id, device_id, TIMESTAMP
)b
INNER JOIN
(
	SELECT log_seq, pos_id, device_id, TIMESTAMP, space_id, target_id
	FROM did_log_type_4
	WHERE DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
	AND page_id = 'p900003' AND target_id IS NOT NULL
	GROUP BY TIMESTAMP
)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
	AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
";
$conn->DBQ($sql);
$conn->DBE();
$uv_last_month_all_iot = $conn->DBF();

// 홈 -> 공간 u+iot 이번달 uv
$sql = "
SELECT COUNT(b.TIMESTAMP) AS 'cnt'
FROM
(
	SELECT pos_id, device_id, TIMESTAMP, space_id,
	DATE_FORMAT(DATE_ADD(TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
	FROM did_log_type_3
	WHERE space_id BETWEEN 's000001' AND 's000004' AND page_id = 'p900002'
	AND DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
  GROUP BY pos_id, device_id, TIMESTAMP
)b
INNER JOIN
(
	SELECT log_seq, pos_id, device_id, TIMESTAMP, space_id, target_id
	FROM did_log_type_4
	WHERE DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
	AND page_id = 'p900003' AND target_id IS NOT NULL
	GROUP BY TIMESTAMP
)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
	AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
";
$conn->DBQ($sql);
$conn->DBE();
$uv_this_month_all_iot = $conn->DBF();

// 홈 -> 공간 u+iot 이번주 uv
$sql = "
SELECT COUNT(b.TIMESTAMP) AS 'cnt'
FROM
(
	SELECT pos_id, device_id, TIMESTAMP, space_id,
	DATE_FORMAT(DATE_ADD(TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
	FROM did_log_type_3
	WHERE space_id BETWEEN 's000001' AND 's000004' AND page_id = 'p900002'
	AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
  GROUP BY pos_id, device_id, TIMESTAMP
)b
INNER JOIN
(
	SELECT log_seq, pos_id, device_id, TIMESTAMP, space_id, target_id
	FROM did_log_type_4
	WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
	AND page_id = 'p900003' AND target_id IS NOT NULL
	GROUP BY TIMESTAMP
)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
	AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
";
$conn->DBQ($sql);
$conn->DBE();
$uv_this_week_all_iot = $conn->DBF();

#------------------------ UV 공간 이동 P코드점 -------------------------#
// 홈 -> 공간 전체 P코드 저번달 UV
$sql = "
SELECT a.cnt + b.cnt AS 'cnt'
FROM
(
	SELECT COUNT(a.TIMESTAMP) AS 'cnt'
	FROM
	(
		SELECT a.pos_id, a.device_id, a.TIMESTAMP, a.page_id,
			DATE_FORMAT(DATE_ADD(a.TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
		FROM did_pos_code
		INNER JOIN
		(
			SELECT pos_id, device_id, TIMESTAMP, page_id
			FROM did_log_type_2
			WHERE page_id = 'p900005' AND is_enter = '1'
			AND DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
      GROUP BY pos_id, device_id, TIMESTAMP
		)a ON did_pos_code.pos_code = a.pos_id
	)a
	INNER JOIN
	(
		SELECT a.pos_id, a.device_id, a.TIMESTAMP, a.page_id, a.space_id
		FROM did_pos_code
		INNER JOIN
		(
			SELECT log_key, pos_id, device_id, TIMESTAMP, page_id, space_id
			FROM did_log_type_3
			WHERE space_id IS NOT NULL AND page_id = 'p900005'
			AND DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
			GROUP BY TIMESTAMP
		)a ON did_pos_code.pos_code = a.pos_id
	)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
	AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
)a,
(
	SELECT COUNT(b.TIMESTAMP) AS 'cnt'
	FROM
	(
		SELECT a.pos_id, a.device_id, a.TIMESTAMP, a.space_id,
			DATE_FORMAT(DATE_ADD(a.TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
		FROM did_pos_code
		INNER JOIN
		(
			SELECT pos_id, device_id, TIMESTAMP, space_id
			FROM did_log_type_3
			WHERE space_id BETWEEN 's000001' AND 's000004' AND page_id = 'p900002'
			AND DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
      GROUP BY pos_id, device_id, TIMESTAMP
		)a ON did_pos_code.pos_code = a.pos_id
	)b
	INNER JOIN
	(
		SELECT a.log_seq, a.pos_id, a.device_id, a.TIMESTAMP, a.space_id, a.target_id
		FROM did_pos_code
		INNER JOIN
		(
			SELECT log_seq, pos_id, device_id, TIMESTAMP, space_id, target_id
			FROM did_log_type_4
			WHERE DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
			AND page_id = 'p900003' AND target_id IS NOT NULL
      GROUP BY TIMESTAMP
		)a ON did_pos_code.pos_code = a.pos_id
	)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
		AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
)b
";
$conn->DBQ($sql);
$conn->DBE();
$uv_last_month_pcode_total = $conn->DBF();

// 홈 -> 공간 전체 P코드 이번달 UV
$sql = "
SELECT a.cnt + b.cnt AS 'cnt'
FROM
(
	SELECT COUNT(a.TIMESTAMP) AS 'cnt'
	FROM
	(
		SELECT a.pos_id, a.device_id, a.TIMESTAMP, a.page_id,
			DATE_FORMAT(DATE_ADD(a.TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
		FROM did_pos_code
		INNER JOIN
		(
			SELECT pos_id, device_id, TIMESTAMP, page_id
			FROM did_log_type_2
			WHERE page_id = 'p900005' AND is_enter = '1'
			AND DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
      GROUP BY pos_id, device_id, TIMESTAMP
		)a ON did_pos_code.pos_code = a.pos_id
	)a
	INNER JOIN
	(
		SELECT a.pos_id, a.device_id, a.TIMESTAMP, a.page_id, a.space_id
		FROM did_pos_code
		INNER JOIN
		(
			SELECT log_key, pos_id, device_id, TIMESTAMP, page_id, space_id
			FROM did_log_type_3
			WHERE space_id IS NOT NULL AND page_id = 'p900005'
			AND DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
			GROUP BY TIMESTAMP
		)a ON did_pos_code.pos_code = a.pos_id
	)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
	AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
)a,
(
	SELECT COUNT(b.TIMESTAMP) AS 'cnt'
	FROM
	(
		SELECT a.pos_id, a.device_id, a.TIMESTAMP, a.space_id,
			DATE_FORMAT(DATE_ADD(a.TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
		FROM did_pos_code
		INNER JOIN
		(
			SELECT pos_id, device_id, TIMESTAMP, space_id
			FROM did_log_type_3
			WHERE space_id BETWEEN 's000001' AND 's000004' AND page_id = 'p900002'
			AND DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
      GROUP BY pos_id, device_id, TIMESTAMP
		)a ON did_pos_code.pos_code = a.pos_id
	)b
	INNER JOIN
	(
		SELECT a.log_seq, a.pos_id, a.device_id, a.TIMESTAMP, a.space_id, a.target_id
		FROM did_pos_code
		INNER JOIN
		(
			SELECT log_seq, pos_id, device_id, TIMESTAMP, space_id, target_id
			FROM did_log_type_4
			WHERE DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
			AND page_id = 'p900003' AND target_id IS NOT NULL
      GROUP BY TIMESTAMP
		)a ON did_pos_code.pos_code = a.pos_id
	)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
		AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
)b
";
$conn->DBQ($sql);
$conn->DBE();
$uv_this_month_pcode_total = $conn->DBF();

// 홈 -> 공간 전체 P코드 이번주 UV
$sql = "
SELECT a.cnt + b.cnt AS 'cnt'
FROM
(
	SELECT COUNT(a.TIMESTAMP) AS 'cnt'
	FROM
	(
		SELECT a.pos_id, a.device_id, a.TIMESTAMP, a.page_id,
			DATE_FORMAT(DATE_ADD(a.TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
		FROM did_pos_code
		INNER JOIN
		(
			SELECT pos_id, device_id, TIMESTAMP, page_id
			FROM did_log_type_2
			WHERE page_id = 'p900005' AND is_enter = '1'
			AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
      GROUP BY pos_id, device_id, TIMESTAMP
		)a ON did_pos_code.pos_code = a.pos_id
	)a
	INNER JOIN
	(
		SELECT a.pos_id, a.device_id, a.TIMESTAMP, a.page_id, a.space_id
		FROM did_pos_code
		INNER JOIN
		(
			SELECT log_key, pos_id, device_id, TIMESTAMP, page_id, space_id
			FROM did_log_type_3
			WHERE space_id IS NOT NULL AND page_id = 'p900005'
			AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
			GROUP BY TIMESTAMP
		)a ON did_pos_code.pos_code = a.pos_id
	)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
	AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
)a,
(
	SELECT COUNT(b.TIMESTAMP) AS 'cnt'
	FROM
	(
		SELECT a.pos_id, a.device_id, a.TIMESTAMP, a.space_id,
			DATE_FORMAT(DATE_ADD(a.TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
		FROM did_pos_code
		INNER JOIN
		(
			SELECT pos_id, device_id, TIMESTAMP, space_id
			FROM did_log_type_3
			WHERE space_id BETWEEN 's000001' AND 's000004' AND page_id = 'p900002'
			AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
      GROUP BY pos_id, device_id, TIMESTAMP
		)a ON did_pos_code.pos_code = a.pos_id
	)b
	INNER JOIN
	(
		SELECT a.log_seq, a.pos_id, a.device_id, a.TIMESTAMP, a.space_id, a.target_id
		FROM did_pos_code
		INNER JOIN
		(
			SELECT log_seq, pos_id, device_id, TIMESTAMP, space_id, target_id
			FROM did_log_type_4
			WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
			AND page_id = 'p900003' AND target_id IS NOT NULL
      GROUP BY TIMESTAMP
		)a ON did_pos_code.pos_code = a.pos_id
	)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
		AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
)b
";
$conn->DBQ($sql);
$conn->DBE();
$uv_this_week_pcode_total = $conn->DBF();

// 홈 -> 공간 u+tv P코드 저번달 UV
$sql = "
SELECT COUNT(a.TIMESTAMP) AS 'cnt'
FROM
(
	SELECT a.pos_id, a.device_id, a.TIMESTAMP, a.page_id,
		DATE_FORMAT(DATE_ADD(a.TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
	FROM did_pos_code
	INNER JOIN
	(
		SELECT pos_id, device_id, TIMESTAMP, page_id
		FROM did_log_type_2
		WHERE page_id = 'p900005' AND is_enter = '1'
		AND DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
    GROUP BY pos_id, device_id, TIMESTAMP
	)a ON did_pos_code.pos_code = a.pos_id
)a
INNER JOIN
(
	SELECT a.pos_id, a.device_id, a.TIMESTAMP, a.page_id, a.space_id
	FROM did_pos_code
	INNER JOIN
	(
		SELECT log_key, pos_id, device_id, TIMESTAMP, page_id, space_id
		FROM did_log_type_3
		WHERE space_id IS NOT NULL AND page_id = 'p900005'
		AND DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
		GROUP BY TIMESTAMP
	)a ON did_pos_code.pos_code = a.pos_id
)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
";
$conn->DBQ($sql);
$conn->DBE();
$uv_last_month_pcode_tv = $conn->DBF();

// 홈 -> 공간 u+tv P코드 이번달 UV
$sql = "
SELECT COUNT(a.TIMESTAMP) AS 'cnt'
FROM
(
	SELECT a.pos_id, a.device_id, a.TIMESTAMP, a.page_id,
		DATE_FORMAT(DATE_ADD(a.TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
	FROM did_pos_code
	INNER JOIN
	(
		SELECT pos_id, device_id, TIMESTAMP, page_id
		FROM did_log_type_2
		WHERE page_id = 'p900005' AND is_enter = '1'
		AND DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
    GROUP BY pos_id, device_id, TIMESTAMP
	)a ON did_pos_code.pos_code = a.pos_id
)a
INNER JOIN
(
	SELECT a.pos_id, a.device_id, a.TIMESTAMP, a.page_id, a.space_id
	FROM did_pos_code
	INNER JOIN
	(
		SELECT log_key, pos_id, device_id, TIMESTAMP, page_id, space_id
		FROM did_log_type_3
		WHERE space_id IS NOT NULL AND page_id = 'p900005'
		AND DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
		GROUP BY TIMESTAMP
	)a ON did_pos_code.pos_code = a.pos_id
)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
";
$conn->DBQ($sql);
$conn->DBE();
$uv_this_month_pcode_tv = $conn->DBF();

// 홈 -> 공간 u+tv P코드 이번주 UV
$sql = "
SELECT COUNT(a.TIMESTAMP) AS 'cnt'
FROM
(
	SELECT a.pos_id, a.device_id, a.TIMESTAMP, a.page_id,
		DATE_FORMAT(DATE_ADD(a.TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
	FROM did_pos_code
	INNER JOIN
	(
		SELECT pos_id, device_id, TIMESTAMP, page_id
		FROM did_log_type_2
		WHERE page_id = 'p900005' AND is_enter = '1'
		AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
    GROUP BY pos_id, device_id, TIMESTAMP
	)a ON did_pos_code.pos_code = a.pos_id
)a
INNER JOIN
(
	SELECT a.pos_id, a.device_id, a.TIMESTAMP, a.page_id, a.space_id
	FROM did_pos_code
	INNER JOIN
	(
		SELECT log_key, pos_id, device_id, TIMESTAMP, page_id, space_id
		FROM did_log_type_3
		WHERE space_id IS NOT NULL AND page_id = 'p900005'
		AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
		GROUP BY TIMESTAMP
	)a ON did_pos_code.pos_code = a.pos_id
)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
";
$conn->DBQ($sql);
$conn->DBE();
$uv_this_week_pcode_tv = $conn->DBF();

// 홈 -> 공간 u+iot P코드 저번달 UV
$sql = "
SELECT COUNT(b.TIMESTAMP) AS 'cnt'
FROM
(
	SELECT a.pos_id, a.device_id, a.TIMESTAMP, a.space_id,
		DATE_FORMAT(DATE_ADD(a.TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
	FROM did_pos_code
	INNER JOIN
	(
		SELECT pos_id, device_id, TIMESTAMP, space_id
		FROM did_log_type_3
		WHERE space_id BETWEEN 's000001' AND 's000004' AND page_id = 'p900002'
		AND DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
    GROUP BY pos_id, device_id, TIMESTAMP
	)a ON did_pos_code.pos_code = a.pos_id
)b
INNER JOIN
(
	SELECT a.log_seq, a.pos_id, a.device_id, a.TIMESTAMP, a.space_id, a.target_id
	FROM did_pos_code
	INNER JOIN
	(
		SELECT log_seq, pos_id, device_id, TIMESTAMP, space_id, target_id
		FROM did_log_type_4
		WHERE DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
		AND page_id = 'p900003' AND target_id IS NOT NULL
    GROUP BY TIMESTAMP
	)a ON did_pos_code.pos_code = a.pos_id
)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
	AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
";
$conn->DBQ($sql);
$conn->DBE();
$uv_last_month_pcode_iot = $conn->DBF();

// 홈 -> 공간 u+iot P코드 이번달 UV
$sql = "
SELECT COUNT(b.TIMESTAMP) AS 'cnt'
FROM
(
	SELECT a.pos_id, a.device_id, a.TIMESTAMP, a.space_id,
		DATE_FORMAT(DATE_ADD(a.TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
	FROM did_pos_code
	INNER JOIN
	(
		SELECT pos_id, device_id, TIMESTAMP, space_id
		FROM did_log_type_3
		WHERE space_id BETWEEN 's000001' AND 's000004' AND page_id = 'p900002'
		AND DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
    GROUP BY pos_id, device_id, TIMESTAMP
	)a ON did_pos_code.pos_code = a.pos_id
)b
INNER JOIN
(
	SELECT a.log_seq, a.pos_id, a.device_id, a.TIMESTAMP, a.space_id, a.target_id
	FROM did_pos_code
	INNER JOIN
	(
		SELECT log_seq, pos_id, device_id, TIMESTAMP, space_id, target_id
		FROM did_log_type_4
		WHERE DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
		AND page_id = 'p900003' AND target_id IS NOT NULL
    GROUP BY TIMESTAMP
	)a ON did_pos_code.pos_code = a.pos_id
)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
	AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
";
$conn->DBQ($sql);
$conn->DBE();
$uv_this_month_pcode_iot = $conn->DBF();

// 홈 -> 공간 u+iot P코드 이번주 UV
$sql = "
SELECT COUNT(b.TIMESTAMP) AS 'cnt'
FROM
(
	SELECT a.pos_id, a.device_id, a.TIMESTAMP, a.space_id,
		DATE_FORMAT(DATE_ADD(a.TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
	FROM did_pos_code
	INNER JOIN
	(
		SELECT pos_id, device_id, TIMESTAMP, space_id
		FROM did_log_type_3
		WHERE space_id BETWEEN 's000001' AND 's000004' AND page_id = 'p900002'
		AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
    GROUP BY pos_id, device_id, TIMESTAMP
	)a ON did_pos_code.pos_code = a.pos_id
)b
INNER JOIN
(
	SELECT a.log_seq, a.pos_id, a.device_id, a.TIMESTAMP, a.space_id, a.target_id
	FROM did_pos_code
	INNER JOIN
	(
		SELECT log_seq, pos_id, device_id, TIMESTAMP, space_id, target_id
		FROM did_log_type_4
		WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
		AND page_id = 'p900003' AND target_id IS NOT NULL
    GROUP BY TIMESTAMP
	)a ON did_pos_code.pos_code = a.pos_id
)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
	AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
";
$conn->DBQ($sql);
$conn->DBE();
$uv_this_week_pcode_iot = $conn->DBF();


#------------------------PV 공간 이동 전체-------------------------#
// 홈 -> 공간 전체 저번달 pv
$sql = "
SELECT a.cnt + b.cnt + c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
     SELECT pos_id
      FROM did_log_type_2
      WHERE page_id != 'p900006'
      AND DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
   )a
)a,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
      (
        SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
   )a
)b,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
   )a
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
   )a
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_last_month_all_total = $conn->DBF();


// 홈 -> 공간 전체 이번달 pv
$sql = "
SELECT a.cnt + b.cnt + c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_2
      WHERE page_id != 'p900006'
      AND DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
   )a
)a,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
   )a
)b,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
   )a
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
   )a
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_this_month_all_total = $conn->DBF();


// 홈 -> 공간 전체 이번주 pv
$sql = "
SELECT a.cnt + b.cnt + c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_2
      WHERE page_id != 'p900006'
      AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a
)a,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a
)b,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_this_week_all_total = $conn->DBF();

// 홈 -> 공간 u+tv 저번달 pv
$sql = "
SELECT b.cnt  AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
   )a
)b";
$conn->DBQ($sql);
$conn->DBE();
$pv_last_month_all_tv = $conn->DBF();

// 홈 -> 공간 u+tv 이번달 pv
$sql = "
SELECT b.cnt  AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
   )a
)b";
$conn->DBQ($sql);
$conn->DBE();
$pv_this_month_all_tv = $conn->DBF();

// 홈 -> 공간 u+tv 이번주 pv
$sql = "
SELECT b.cnt  AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a
)b";
$conn->DBQ($sql);
$conn->DBE();
$pv_this_week_all_tv = $conn->DBF();

// 홈 -> 공간 u+iot 저번달 pv
$sql = "
SELECT c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
   )a
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
   )a
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_last_month_all_iot = $conn->DBF();

// 홈 -> 공간 u+iot 이번달 pv
$sql = "
SELECT c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
   )a
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
   )a
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_this_month_all_iot = $conn->DBF();

// 홈 -> 공간 u+iot 이번주 pv
$sql = "
SELECT c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_this_week_all_iot = $conn->DBF();

#------------------------ PV 공간 이동 P코드점 -------------------------#
// 홈 -> 공간 전체 P코드 저번달 pv
$sql = "
SELECT a.cnt + b.cnt + c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_2
      WHERE page_id != 'p900006'
      AND DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
   )a ON did_pos_code.pos_code = a.pos_id
)a,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
   )a ON did_pos_code.pos_code = a.pos_id
)b,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
   )a ON did_pos_code.pos_code = a.pos_id
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
   )a ON did_pos_code.pos_code = a.pos_id
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_last_month_pcode_total = $conn->DBF();

// 홈 -> 공간 전체 P코드 이번달 pv
$sql = "
SELECT a.cnt + b.cnt + c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_2
      WHERE page_id != 'p900006'
      AND DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
   )a ON did_pos_code.pos_code = a.pos_id
)a,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
   )a ON did_pos_code.pos_code = a.pos_id
)b,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
   )a ON did_pos_code.pos_code = a.pos_id
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
   )a ON did_pos_code.pos_code = a.pos_id
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_this_month_pcode_total = $conn->DBF();

// 홈 -> 공간 전체 P코드 이번주 pv
$sql = "
SELECT a.cnt + b.cnt + c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_2
      WHERE page_id != 'p900006'
      AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a ON did_pos_code.pos_code = a.pos_id
)a,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a ON did_pos_code.pos_code = a.pos_id
)b,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a ON did_pos_code.pos_code = a.pos_id
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a ON did_pos_code.pos_code = a.pos_id
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_this_week_pcode_total = $conn->DBF();

// 홈 -> 공간 u+tv P코드 저번달 pv
$sql = "
SELECT  b.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
   )a ON did_pos_code.pos_code = a.pos_id
)b";
$conn->DBQ($sql);
$conn->DBE();
$pv_last_month_pcode_tv = $conn->DBF();

// 홈 -> 공간 u+tv P코드 이번달 pv
$sql = "
SELECT  b.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
   )a ON did_pos_code.pos_code = a.pos_id
)b";
$conn->DBQ($sql);
$conn->DBE();
$pv_this_month_pcode_tv = $conn->DBF();

// 홈 -> 공간 u+tv P코드 이번주 pv
$sql = "
SELECT  b.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a ON did_pos_code.pos_code = a.pos_id
)b";
$conn->DBQ($sql);
$conn->DBE();
$pv_this_week_pcode_tv = $conn->DBF();

// 홈 -> 공간 u+iot P코드 저번달 pv
$sql = "
SELECT c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
   )a ON did_pos_code.pos_code = a.pos_id
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".$date_condition."' AND '".$date_condition2."'
   )a ON did_pos_code.pos_code = a.pos_id
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_last_month_pcode_iot = $conn->DBF();

// 홈 -> 공간 u+iot P코드 이번달 pv
$sql = "
SELECT c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
   )a ON did_pos_code.pos_code = a.pos_id
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".$date_condition3."' AND '".$date_condition4."'
   )a ON did_pos_code.pos_code = a.pos_id
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_this_month_pcode_iot = $conn->DBF();

// 홈 -> 공간 u+iot P코드 이번주 pv
$sql = "
SELECT c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN  '".$date_from."' AND '".$date_to."'
   )a ON did_pos_code.pos_code = a.pos_id
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a ON did_pos_code.pos_code = a.pos_id
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_this_week_pcode_iot = $conn->DBF();







$sql = "
insert into pre_period(install_table, app_util, app_use, timestamp)
values('".$total_store."|".$installed_store[0]."|".number_format(($installed_store[0]/$total_store)*100,2)."%"."|".
          $installed_store[0]."|".$exec_app_total[0]."|".$total_app_percent."'
,'".$app_array[0][0]."|".$app_array[0][1]."|".$app_array[0][2]."|".$app_array[0][3]."|".$app_array[0][4]."|".
    $app_array[1][0]."|".$app_array[1][1]."|".$app_array[1][2]."|".$app_array[1][3]."|".$app_array[1][4]."|".
    $app_array[2][0]."|".$app_array[2][1]."|".$app_array[2][2]."|".$app_array[2][3]."|".$app_array[2][4]."|".
    $app_array[3][0]."|".$app_array[3][1]."|".$app_array[3][2]."|".$app_array[3][3]."|".$app_array[3][4]."|".
    $app_array[4][0]."|".$app_array[4][1]."|".$app_array[4][2]."|".$app_array[4][3]."|".$app_array[4][4]."|".
    $app_array[5][0]."|".$app_array[5][1]."|".$app_array[5][2]."|".$app_array[5][3]."|".$app_array[5][4]."|".
    $app_array[6][0]."|".$app_array[6][1]."|".$app_array[6][2]."|".$app_array[6][3]."|".$app_array[6][4]."|".
    $app_array[7][0]."|".$app_array[7][1]."|".$app_array[7][2]."|".$app_array[7][3]."|".$app_array[7][4]."'
,'".$uv_last_month_all_total[0]."|".$uv_this_month_all_total[0]."|".$uv_this_week_all_total[0]."|".$uv_last_month_all_tv[0]."|".
    $uv_this_month_all_tv[0]."|".$uv_this_week_all_tv[0]."|".$uv_last_month_all_iot[0]."|".$uv_this_month_all_iot[0]."|".
    $uv_this_week_all_iot[0]."|".$uv_last_month_pcode_total[0]."|".$uv_this_month_pcode_total[0]."|".$uv_this_week_pcode_total[0]."|".
    $uv_last_month_pcode_tv[0]."|".$uv_this_month_pcode_tv[0]."|".$uv_this_week_pcode_tv[0]."|".$uv_last_month_pcode_iot[0]."|".
    $uv_this_month_pcode_iot[0]."|".$uv_this_week_pcode_iot[0]."|".
    $pv_last_month_all_total[0]."|".$pv_this_month_all_total[0]."|".$pv_this_week_all_total[0]."|".$pv_last_month_all_tv[0]."|".
    $pv_this_month_all_tv[0]."|".$pv_this_week_all_tv[0]."|".$pv_last_month_all_iot[0]."|".$pv_this_month_all_iot[0]."|".
    $pv_this_week_all_iot[0]."|".$pv_last_month_pcode_total[0]."|".$pv_this_month_pcode_total[0]."|".$pv_this_week_pcode_total[0]."|".
    $pv_last_month_pcode_tv[0]."|".$pv_this_month_pcode_tv[0]."|".$pv_this_week_pcode_tv[0]."|".$pv_last_month_pcode_iot[0]."|".
    $pv_this_month_pcode_iot[0]."|".$pv_this_week_pcode_iot[0]."'
,'".$date_from."')
";

$conn->DBQ($sql);
$conn->DBE();


$date_from = date("Y-m-d", strtotime("-7 days",  strtotime($date_from)));
$date_to = date("Y-m-d", strtotime("-7 days",  strtotime($date_to)));
}


?>
