<?php
  // Dan Coleman
  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  require_once('../dblogin_sched.php');

  $course = '';
  if (isset($_POST['coursename'])):
    $course = trim(htmlspecialchars($_POST['coursename']));
  else:
    if (isset($_SESSION['admin'])):
      header('Location: manager.php');
    else:
      header('Location: login.php');
    endif;
  endif;

  $db = new PDO("mysql:host=$db_hostname;dbname=schedule;charset=utf8",
    $db_username, $db_password,
    array(PDO::ATTR_EMULATE_PREPARES => false,
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

  # Value to return on completion
  $result = "nochange";

  # Make CSV dir for course
  $destDir = "CSVs/" . $course;
  if (!file_exists($destDir)):
    mkdir($destDir);
    chmod($destDir, 0606);

    # Get names of tutors for a course
    $query = "select t.name from tutors as t inner join course_for_tutor as c
      where t.id = c.id and c.course = :course";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':course', $course, PDO::PARAM_STR);
    $stmt->execute();
    $names = $stmt->fetchAll();

    # Loop through all names to create each CSV file
    foreach($names as $name):
      $nameList = explode('+', $name[0]);
      $fullname = $nameList[0] . $nameList[1];
      $destFile = $destDir . '/' . $fullname . '.csv';
      touch($destFile);
      chmod($destFile, 0606);
  
      $query = "select sbusy, mbusy, tbusy, wbusy, rbusy, fbusy, 
        spref, mpref, tpref, wpref, rpref, fpref 
        from available as a inner join tutors as t
        where a.id = t.id and t.name = :name";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':name', $name[0], PDO::PARAM_STR);
      $stmt->execute();
      $days = $stmt->fetchAll();
  
      $sbusy = explode('/', $days[0]);
      $mbusy = explode('/', $days[1]);
      $tbusy = explode('/', $days[2]);
      $wbusy = explode('/', $days[3]);
      $rbusy = explode('/', $days[4]);
      $fbusy = explode('/', $days[5]);
      $spref = explode('/', $days[6]);
      $mpref = explode('/', $days[7]);
      $tpref = explode('/', $days[8]);
      $wpref = explode('/', $days[9]);
      $rpref = explode('/', $days[10]);
      $fpref = explode('/', $days[11]);
  
      $outFile = fopen($destFile, 'w');
  
      $time = '09:00';
      # Loop through hours 9am to 9pm, including half hours
      for ($i = 9; $i <= 21; $i = $i + .5):
        # For each half hour, if the tutor is busy, that time 
        # has a '-1'. If he prefers that half hour, the time 
        # has a '1'. For all other times, the time is marked '0'.
        $csvRow = array();
    
        if (in_array($time, $sbusy)):
          $csvRow[0] = -1;
        elseif (in_array($time, $spref)):
          $csvRow[0] = 1;
        else:
          $csvRow[0] = 0;
        endif;
    
        if (in_array($time, $mbusy)):
          $csvRow[1] = -1;
        elseif (in_array($time, $mpref)):
          $csvRow[1] = 1;
        else:
          $csvRow[1] = 0;
        endif;
    
        if (in_array($time, $tbusy)):
          $csvRow[2] = -1;
        elseif (in_array($time, $tpref)):
          $csvRow[2] = 1;
        else:
          $csvRow[2] = 0;
        endif;
    
        if (in_array($time, $wbusy)):
          $csvRow[3] = -1;
        elseif (in_array($time, $wpref)):
          $csvRow[3] = 1;
        else:
          $csvRow[3] = 0;
        endif;
    
        if (in_array($time, $rbusy)):
          $csvRow[4] = -1;
        elseif (in_array($time, $rpref)):
          $csvRow[4] = 1;
        else:
          $csvRow[4] = 0;
        endif;
    
        if (in_array($time, $fbusy)):
          $csvRow[5] = -1;
        elseif (in_array($time, $fpref)):
          $csvRow[5] = 1;
        else:
          $csvRow[5] = 0;
        endif;
    
        # print availability at current time
        fputcsv($outFile, $csvRow);
    
        if (($i / .5) % 2 == 0):
          $time = substr_replace($time, "30", 3, 2);
        else:
          $newHr = $i + 1;
          $time = $newHr . ":00";
          if (strlen($time) < 5):
            $time = str_pad($time, 5, '0', STR_PAD_LEFT);
          endif;
        endif;
    
      endfor;
  
      fclose($outFile);
  
    endforeach;

    $result = "created";
  endif;

  echo $result;

?>