<head>
  <meta charset="utf-8">
  <?php
session_start();
?>
</head>

<body>
<div class="dashboard-header">
  <nav class="navbar navbar-expand-lg bg-white fixed-top">
    <a class="navbar-brand" href="../index.php">UCRM</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse " id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto navbar-right-top">
        <li class="nav-item dropdown nav-user">
          <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="../assets/images/avatar-1.jpg" alt="" class="user-avatar-md rounded-circle"></a>
          <div class="dropdown-menu dropdown-menu-right nav-user-dropdown" aria-labelledby="navbarDropdownMenuLink2">
            <div class="nav-user-info">
              <h5 class="mb-0 text-white nav-user-name"><?=$_SESSION['user_id']?></h5>
              <span class="status"></span><span class="ml-2">권한 : <?=$_SESSION['user_root']?></span>
            </div>
            <a class="dropdown-item" href="../sessions/logout_check.php"><i class="fas fa-power-off mr-2"></i>로그아웃</a>
          </div>
        </li>
      </ul>
    </div>
  </nav>
</div>
</body>
