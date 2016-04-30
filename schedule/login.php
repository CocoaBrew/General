<?php
  //Dan Coleman
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  
  session_start();
    
  require_once('../../capstone/dblogin_sched.php');
  
  $db = new PDO("mysql:host=$db_hostname;dbname=$db_name;charset=utf8",
    $db_username, $db_password,
    array(PDO::ATTR_EMULATE_PREPARES => false,
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  
  $loggedin = false;
  $name = '';
  
  if(!(isset($_SESSION["name"]))):
    if(isset($_POST['pid'])):
      $pid = trim(htmlspecialchars($_POST['pid']));
      $query = "select name from admin where id = :pid";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
      $stmt->execute();
      $ret_vals = $stmt->fetchAll();
      if (count($ret_vals) == 1):
        $name = $ret_vals[0]["name"];
        $_SESSION["admin"] = 'true';
        $_SESSION["name"] = $name;
        $isadmin = true;
        header('Location: manager.php');
      else:
        $query = "select t.name as name, c.course as course
          from tutors as t inner join course_for_tutor as c 
          where t.id = c.id and t.id = :pid";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
        $stmt->execute();
        $ret_vals = $stmt->fetchAll();
        if (count($ret_vals) == 1):
          $name = $ret_vals[0]["name"];
          $_SESSION["name"] = $name;
          $_SESSION['course'] = $ret_vals[0]['course'];
          if (isset($_SESSION['course'])):
            header("Location: survey.php");
          else:
            header('Location: logout.php');
          endif;
        endif;
      endif;
    endif;
  else:
    $loggedin = true;
  endif;
  
  if ($loggedin):
    $isadmin = isset($_SESSION["admin"]);
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
          
          <button type="submit" id="loginbutton">Login</button>
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
