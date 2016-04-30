<?php
  // Dan Coleman
  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  session_start();

  $course = '';
  if (isset($_POST['course'])):
    $course = trim(htmlspecialchars($_POST['course']));
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
  if (exec("python schedule.py $course") == "successful"):
    $retVal = "written";
  endif;

  # return success status
  echo $retVal; 

?>
