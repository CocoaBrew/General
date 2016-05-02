<?php
  // Dan Coleman
  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  session_start();
  require_once('../../capstone/dblogin_sched.php');

  // check admin status
  if (!isset($_SESSION['name']) || !isset($_SESSION['admin']) || 
    $_SESSION['admin'] != 'true'):
    header('Location: login.php');
  endif;
  
  $db = new PDO("mysql:host=$db_hostname;dbname=$db_name;charset=utf8",
    $db_username, $db_password,
    array(PDO::ATTR_EMULATE_PREPARES => false,
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  
  # get names of all courses
  $query = "select title from courses";
  $stmt = $db->prepare($query);
  $stmt->execute();
  $courses = $stmt->fetchAll();

  # Reset file info
  foreach ($courses as $course):
    $fileDir = 'CSVs/' . $course['title'];
    $query = "select t.name, t.education, t.work_hrs from tutors as t 
      inner join course_for_tutor as c
      where t.id = c.id and c.course = :course";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':course', $course['title'], PDO::PARAM_STR);
    $stmt->execute();
    $names = $stmt->fetchAll();
    # remove tutor CSV files
    foreach ($names as $name):
      $nameList = explode('+', $name[0]);
      $fullname = $nameList[0] . $nameList[1];
      $filename = $fileDir . '/' . $fullname . $name[2] . $name[1] . '.csv';
      if (file_exists($filename)):
        unlink($filename);
      endif;
    endforeach;
    unlink($fileDir . '/' . $course['title'] . '.csv');
    unlink($fileDir . '/' . $course['title'] . 'tutors.csv');
    rmdir($fileDir);
    $countFilePath = 'counts/' . $course['title'] . 'tutorcount.txt';
    unlink($countFilePath);
  endforeach;

  # Remove all schedules
  $schedDir = "sched_files/*";
  foreach (glob($schedDir) as $file):
    unlink($file);
  endforeach;

  
  # Reset db info
  $query = "drop table courses";
  $stmt = $db->prepare($query);
  $stmt->execute();
  $query = "drop table tutors";
  $stmt = $db->prepare($query);
  $stmt->execute();
  $query = "drop table available";
  $stmt = $db->prepare($query);
  $stmt->execute();
  $query = "drop table course_for_tutor";
  $stmt = $db->prepare($query);
  $stmt->execute();
  $query = "create table courses(
    title varchar(255) not null,
    sun varchar(255),
    mon varchar(255),
    tue varchar(255),
    wed varchar(255),
    thu varchar(255),
    fri varchar(255),
    primary key (title));";
  $stmt = $db->prepare($query);
  $stmt->execute();
  $query = "create table tutors(
    id varchar(255) not null,
    name varchar(255),
    email varchar(255),
    education varchar(255),
    work_hrs varchar(255),
    primary key (id));";
  $stmt = $db->prepare($query);
  $stmt->execute();
  $query = "create table available(
    id varchar(255) not null,
    sbusy varchar(255),
    mbusy varchar(255),
    tbusy varchar(255),
    wbusy varchar(255),
    rbusy varchar(255),
    fbusy varchar(255),
    spref varchar(255),
    mpref varchar(255),
    tpref varchar(255),
    wpref varchar(255),
    rpref varchar(255),
    fpref varchar(255),
    primary key (id));";
  $stmt = $db->prepare($query);
  $stmt->execute();
  $query = "create table course_for_tutor(
    id varchar(255) not null,
    course varchar(255),
    primary key (id));";
  $stmt = $db->prepare($query);
  $stmt->execute();

  echo "cleared";

?>
