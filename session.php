<?php 
	include('mysqlconfig.php');
  session_start();
   
  $user_check = $_SESSION['login_user'];

  $sql = sprintf("SELECT uname FROM Login WHERE uname = '%s';",$user_check);
   
  $ses_sql = mysqli_query($db,$sql);

  $row = mysqli_fetch_row($ses_sql);
   
  $login_session = $row[0];
   
  if((!isset($_SESSION['login_user']))&&(strcmp($user_check,$login_session) !== 0)){
    header("location: login.php");
  }
?>