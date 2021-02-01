<?
session_start();

if($_SESSION['user_root']!=2 && $_SESSION['user_root']!=3) {
  if(!$_SESSION['user_root']){
    echo "<script type='text/javascript'>alert('로그인이 필요한 페이지 입니다.');
    window.location.href='../index.php';</script>";
  }else{
    echo "<script type='text/javascript'>alert('권한이 없습니다.');
    history.back();</script>";
  }
}?>
