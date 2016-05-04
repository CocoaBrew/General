<?php
  // Dan Coleman
  //error_reporting(E_ALL);
  //ini_set('display_errors', '1');
  
  $schedDir = "sched_files/*";
  $courseNames = array();
  
  # Retrieve names of all courses
  foreach (glob($schedDir) as $file):
    $filename = basename($file);
    $course = str_replace(".html", "", $filename);
    $courseNames[] = $course;
  endforeach;

  echo json_encode($courseNames);

?>
