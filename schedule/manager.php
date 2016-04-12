<?php
  //Dan Coleman
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  
  session_start();
  
  require_once('../dblogin_sched.php');
  
  if (!isset($_SESSION['name']) || !isset($_SESSION['admin']) || 
    $_SESSION['admin'] != 'true'):
    header('Location: login.php');
  endif;
  
  $db = new PDO("mysql:host=$db_hostname;dbname=schedule;charset=utf8",
    $db_username, $db_password,
    array(PDO::ATTR_EMULATE_PREPARES => false,
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
          
  $query = "select title from courses";
  $stmt = $db->prepare($query);
  $stmt->execute();
  $courses = $stmt->fetchAll();
          
  if (isset($_POST['clear'])):
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
      listing int not null auto_increment,
      primary key (listing));";
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
      id varchar(255),
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
  endif;
  
  # count number of tutors who completed surveys
  $query = "select count(id) from available";
  $stmt = $db->prepare($query);
  $stmt->execute();
  $curr_total = $stmt->fetchAll();
  $curr_total = $curr_total[0]['count(id)'];
  
  # Recheck number of courses, in case reset occurred
  $query = "select title from courses";
  $stmt = $db->prepare($query);
  $stmt->execute();
  $coursesAfter = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"/>
    <meta name="author" content="Dan Coleman"/>
    <link rel="stylesheet" href="tutor.css" />
    <title>Tutor Manager</title>
  </head>

  <body>
    <h1>Tutor Manager</h1>
    
    <p>
      <a href="logout.php">Logout</a>.
    </p>
    
    <p>
      This page is used to manage tutoring lists. 
    </p>
    
    <h2>New Course</h2>
    <p>
      Set up a new course <a href="tutors.php">here</a>.
    </p>
    
    <div id="schedules">
      <?php if (count($coursesAfter) != 0): ?>
        <h2>Make Schedule</h2>
        <p class="comment">
          When all tutors for a particular course have completed their surveys,
          the button for that course can be clicked to create the associated
          schedule.
        </p>
        <?php foreach ($coursesAfter as $course): ?>
          <p>
            <button class="creator" id="<?= $course['title'] ?>" type="button"
              <?php 
              # get number of tutors from text file
              $filename = 'counts/' . $course['title'] . 'tutorcount.txt';
              $totaltutors = trim(file_get_contents($filename));
              if ($curr_total != $totaltutors): ?>
                disabled="disabled"
              <?php endif;
              ?>><?= $course['title'] ?></button>
          </p>
        <?php endforeach; 
      endif; ?>
    </div>
    
    <!-- 
      hidden Schedules section with existing schedules when available
    -->
    <section id="schedLinks"></section>
    
    <p id="response"></p>
    
    <h2>Reset</h2>
    <p>
      Clear all current course and tutor information? 
      <form id="dataclear" action="manager.php" method="post">
        <input type="submit" form="dataclear" name="clear" id="clear"
          value="Clear Data" /> 
          <span class="comment">*Only use at the end of the semester*</span>
      </form>
    </p>
    
    <script src="manager.js"></script>
  </body>
</html>
