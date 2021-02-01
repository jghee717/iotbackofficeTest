<head>
  <meta charset="utf-8"/>
</head>
<body>
  <?php
  session_start();
  session_destroy();
  echo "<script type='text/javascript'>alert('로그아웃되었습니다.');
  location.href='../index.php';</script>";
  ?>
</body>
