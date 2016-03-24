<?php
  //Dan Coleman
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  
  /*require_once('../dblogin.php');
  
  $db = new PDO("mysql:host=$db_hostname;dbname=dac3251;charset=utf8",
    $db_username, $db_password,
    array(PDO::ATTR_EMULATE_PREPARES => false,
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  */
  $loggedin = false;
  $pid = 0;
  
  if(!(isset($_SESSION['pid']))):
    if(isset($_POST['pid'])):
      $pid = trim(htmlspecialchars($_POST['pid']));
    else:
      header('login.php');
    endif;
  endif;
  
  
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"/>
    <meta name="author" content="Dan Coleman"/>
    <link rel="stylesheet" href="tutor.css" />
    <title>Login</title>
  </head>

  <body>
    <h1>Login</h1>
    
    <p>
      <form id="loginframe" action="login.php" method="post">
        <label for="identifier">Personal Identifier: </label>
        <input type="password" name="pid" id="identifier" pattern="(\w|\d)+"
          autofocus="autofocus" />
        <button type="submit">Login</button>
      </form>
    </p>
  </body>
</html>
