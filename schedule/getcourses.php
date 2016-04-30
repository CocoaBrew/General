<?php
  // Dan Coleman
  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  require_once('../../capstone/dblogin_sched.php');

  $db = new PDO("mysql:host=$db_hostname;dbname=$db_name;charset=utf8",
    $db_username, $db_password,
    array(PDO::ATTR_EMULATE_PREPARES => false,
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

  # retrieve names of all courses
  $query = "select title from courses";
  $stmt = $db->prepare($query);
  $stmt->execute();
  $courses = $stmt->fetchAll();

  echo json_encode($courses);

?>
