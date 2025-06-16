<?php
include_once __DIR__. '/../../src/api/api.php';
require_once 'admin-class.php';

$user = new ADMIN();
$site_secret_key = $user->siteSecretKey();

if($user->isUserLoggedIn()!="")
{
 $user->redirect('');
}

if(isset($_POST['btn-signin']))
{

   $response = $_POST['g-tokens'];
   $remoteip = $_SERVER['REMOTE_ADDR'];
   $url = "https://www.googles.com/recaptcha/api/siteverify?secret=$site_secret_key&response=$response&remoteip=$remoteip";
   $data = file_get_contents($url);
   $row =  json_decode($data, true);
   
   if($row['success'] == "false"){

 $email = trim($_POST['email']);
 $upass = trim($_POST['password']);
 
 if($user->login($email,$upass))
 {
  
    $_SESSION['status_title'] = "Hey !";
    $_SESSION['status'] = "Welcome back! ";
    $_SESSION['status_code'] = "success";
    $_SESSION['status_timer'] = 10000;
   header("Location: ../../");
    exit;

 }
}else{
   $_SESSION['status_title'] = "Error!";
   $_SESSION['status'] = "Invalid captcha, please try again!";
   $_SESSION['status_code'] = "error";
   $_SESSION['status_timer'] = 40000;
   header("Location: ../../../../private/admin/");
   exit;
}
}
?>