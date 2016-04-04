<?php
  //Dan Coleman
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  
  session_start();
  //print_r($_SESSION);
  
  require_once('../dblogin_sched.php');
  
  if (!isset($_SESSION['name']) || !isset($_SESSION['admin']) || 
    $_SESSION['admin'] != 'true'):
    header('Location: login.php');
  endif;
  
  $db = new PDO("mysql:host=$db_hostname;dbname=schedule;charset=utf8",
    $db_username, $db_password,
    array(PDO::ATTR_EMULATE_PREPARES => false,
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
          
  if (isset($_POST['clear'])):
    $query = "drop table courses";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $query = "drop table tutors";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $query = "drop table available";
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
  endif;
  
  # count number of tutors who completed surveys
  $query = "select count(id) from tutors";
  $stmt = $db->prepare($query);
  $stmt->execute();
  $curr_total = $stmt->fetchAll();
  $curr_total = $curr_total[0]['count(id)'];

  # get number of tutors from text file
  

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
      This page is used to manage tutoring lists. 
    </p>
    
    <p>
      Clear current course and tutor information from record? 
      <form id="dataclear" action="manager.php" method="post">
        <input type="submit" form="dataclear" name="clear" id="clear"
          value="Clear Data" /> 
          <span class="comment">*Only use at the end of the semester*</span>
      </form>
    </p>
    
    <p>
      Set up a new course <a href="tutors.php">here</a>.
    </p>
    
    <p>
      <?php //if ($curr_total == $totaltutors)?>
      <!-- make schedule button w associated logic, maybe conditionally
        disabled, comment class paragraph w description/info -->
      <?php //endif; ?>
    </p>
    
    <p>
      <a href="logout.php">Logout</a>.
    </p>
  </body>
</html>
