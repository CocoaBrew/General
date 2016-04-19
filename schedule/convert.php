<?php
  // Dan Coleman
  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  require_once('../../capstone/dblogin_sched.php');

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

  $db = new PDO("mysql:host=$db_hostname;dbname=$db_name;charset=utf8",
    $db_username, $db_password,
    array(PDO::ATTR_EMULATE_PREPARES => false,
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

  # Value to return on completion
  $result = "nochange";

  # Make CSV dir for course
  $destDir = "CSVs/" . $course;
  if (!file_exists($destDir)):
    mkdir($destDir);
    chmod($destDir, 0733);
  endif;
  
  # Get names of tutors for a course
  $query = "select t.name, t.education, t.work_hrs 
    from tutors as t inner join course_for_tutor as c
    where t.id = c.id and c.course = :course";
  $stmt = $db->prepare($query);
  $stmt->bindParam(':course', $course, PDO::PARAM_STR);
  $stmt->execute();
  $names = $stmt->fetchAll();

  # Loop through all names to create each CSV file
  foreach($names as $name):
    $result = "nochange";
    $nameList = explode('+', $name[0]);
    $fullname = $nameList[0] . $nameList[1];
    $ed = $name[1];
    $hrs = $name[2];
    $destFile = $destDir . '/' . $fullname . $hrs . $ed . '.csv';
    touch($destFile);
    chmod($destFile, 0646);

    $query = "select sbusy, mbusy, tbusy, wbusy, rbusy, fbusy, 
      spref, mpref, tpref, wpref, rpref, fpref 
      from available as a inner join tutors as t
      where a.id = t.id and t.name = :name";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $name[0], PDO::PARAM_STR);
    $stmt->execute();
    $days = $stmt->fetchAll();
    $days = $days[0];

    $sbusy = explode('/', $days['sbusy']);
    $mbusy = explode('/', $days['mbusy']);
    $tbusy = explode('/', $days['tbusy']);
    $wbusy = explode('/', $days['wbusy']);
    $rbusy = explode('/', $days['rbusy']);
    $fbusy = explode('/', $days['fbusy']);
    $spref = explode('/', $days['spref']);
    $mpref = explode('/', $days['mpref']);
    $tpref = explode('/', $days['tpref']);
    $wpref = explode('/', $days['wpref']);
    $rpref = explode('/', $days['rpref']);
    $fpref = explode('/', $days['fpref']);

    $outFile = fopen($destFile, 'w');

    $time = '09:00';
    # Loop through hours 9am to 9pm, including half hours
    for ($i = 9; $i <= 21; $i = $i + .5):
      # For each half hour, if the tutor is busy, that time 
      # has a '-1'. If he prefers that half hour, the time 
      # has a '1'. For all other times, the time is marked '0'.
      $csvRow = array();
      
      $csvRow[0] = $time;
          
      if (in_array($time, $sbusy)):
        $csvRow[1] = -1;
      elseif (in_array($time, $spref)):
        $csvRow[1] = 1;
      else:
        $csvRow[1] = 0;
      endif;
  
      if (in_array($time, $mbusy)):
        $csvRow[2] = -1;
      elseif (in_array($time, $mpref)):
        $csvRow[2] = 1;
      else:
        $csvRow[2] = 0;
      endif;
  
      if (in_array($time, $tbusy)):
        $csvRow[3] = -1;
      elseif (in_array($time, $tpref)):
        $csvRow[3] = 1;
      else:
        $csvRow[3] = 0;
      endif;
  
      if (in_array($time, $wbusy)):
        $csvRow[4] = -1;
      elseif (in_array($time, $wpref)):
        $csvRow[4] = 1;
      else:
        $csvRow[4] = 0;
      endif;
  
      if (in_array($time, $rbusy)):
        $csvRow[5] = -1;
      elseif (in_array($time, $rpref)):
        $csvRow[5] = 1;
      else:
        $csvRow[5] = 0;
      endif;
  
      if (in_array($time, $fbusy)):
        $csvRow[6] = -1;
      elseif (in_array($time, $fpref)):
        $csvRow[6] = 1;
      else:
        $csvRow[6] = 0;
      endif;
  
      # print availability at current time
      fputcsv($outFile, $csvRow);
  
      if (($i / .5) % 2 == 0):
        $time = substr_replace($time, "30", 3, 2);
      else:
        $newHr = strval(substr($time, 0, 2) + 1);
        $time = $newHr . ":00";
        if (strlen($time) < 5):
          $time = str_pad($time, 5, '0', STR_PAD_LEFT);
        endif;
      endif;
  
    endfor;

    fclose($outFile);

    $result = "created";
  endforeach;

  //$result = "created";

  echo $result;

?>
