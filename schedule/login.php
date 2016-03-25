<?php
  //Dan Coleman
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  
  session_start();
  
  require_once('../dblogin.php');
  
  db = new PDO("mysql:host=$db_hostname;dbname=dac3251;charset=utf8",
    $db_username, $db_password,
    array(PDO::ATTR_EMULATE_PREPARES => false,
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  
  $loggedin = false;
  $isadmin = false;
  $name = '';
  
  if(!(isset($_SESSION['name']))):
    if(isset($_POST['pid'])):
      $pid = trim(htmlspecialchars($_POST['pid']));
      $query = "select name from tutors where id = :pid";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':id', $pid, PDO::PARAM_STR);
      $stmt->execute();
      $ret_vals = $stmt->fetchAll();
      if (count($ret_vals) == 1):
        $namelist = $ret_vals[0];
      endif;
      $_SESSION['name'] = $name;
    else:
      header('login.php');
    endif;
  else:
    $loggedin = true;
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
    
    <?php if (!$loggedin): ?>
      <p>
        <form id="loginframe" action="login.php" method="post">
          <label for="identifier">Personal Identifier: </label>
          <input type="password" name="pid" id="identifier" pattern="(\w|\d)+"
            autofocus="autofocus" />
          <button type="submit">Login</button>
        </form>
      </p>
    <?php else: ?>
      <?php if ($isadmin): ?>
        <p>
          You are already logged in. Return <a href="manager.php">home</a>.
        </p>
      <?php else: ?>
        <p>
          You are already logged in. Please <a href="logout.php">logout</a>.
        </p>
      <?php endif;
    endif; ?>
        
  </body>
</html>
