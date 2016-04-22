<?php
  // Dan Coleman

  require_once('../../capstone/dblogin_sched.php');

  $db = new PDO("mysql:host=$db_hostname;dbname=$db_name;charset=utf8",
    $db_username, $db_password,
    array(PDO::ATTR_EMULATE_PREPARES => false,
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

  $coursename = trim(htmlspecialchars($_POST['course']));

  # count number of tutors who completed surveys
  $query = "select count(a.id) from available as a 
    inner join course_for_tutor as ct
    where a.id = ct.id and ct.course = :course";
  $stmt = $db->prepare($query);
  $stmt->bindParam(':course', $coursename, PDO::PARAM_STR);
  $stmt->execute();
  $curr_total = $stmt->fetchAll();
  $curr_total = $curr_total[0]['count(a.id)'];

  # get number of tutors from text file
  $filename = 'counts/' . $coursename . 'tutorcount.txt';
  $total_tutors = trim(file_get_contents($filename));

  $status = "incomplete";
  if ($curr_total == $total_tutors):
    $status = "ready";
  endif;

  echo $status;
?>
