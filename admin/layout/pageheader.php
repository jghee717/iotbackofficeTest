<?php
$mc=$_GET['mc']; //대분류
$sc=$_GET['sc']; //소분류
?>

<div class="row">
  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
    <div class="page-header">
      <h2 class="pageheader-title"><?=$sc?></h2>
      <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
      <div class="page-breadcrumb">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><?=$mc?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$sc?></li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
</div>
