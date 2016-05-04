<?php
  // Dan Coleman
  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  session_start();

  $course = '';
  $tutPerShift = 0;
  if (isset($_POST['course']) && isset($_POST['tps'])):
    $course = trim(htmlspecialchars($_POST['course']));
    $tutPerShift = trim(htmlspecialchars($_POST['tps']));
  else:
    // verify admin
    if (isset($_SESSION['admin'])):
      header('Location: manager.php');
    else:
      header('Location: login.php');
    endif;
  endif;
  
  $retVal = "failed";
  
  # create file
  $dir = "sched_files/";
  if (!file_exists($dir)):
    mkdir($dir, 0707);
    chmod($dir, 0707);
  endif;
  $filename = $dir . $course . ".html";
  touch($filename);
  chmod($filename, 0606);

  # run scheduling program
  if (exec("python schedule.py $course $tutPerShift") == "successful"):
    $retVal = "written";
  endif;

  # return success status
  echo $retVal; 

?>
