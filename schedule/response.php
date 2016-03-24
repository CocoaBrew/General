<?php
  //Dan Coleman
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  
  require('contact.php');
  require('../dblogin_sched.php');
  
  $db = new PDO("mysql:host=$db_hostname;dbname=schedule;charset=utf8",
    $db_username, $db_password,
    array(PDO::ATTR_EMULATE_PREPARES => false,
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  
  if (isset($_POST['fname']) && isset($_POST['lname'])):
    $fname = trim(htmlspecialchars($_POST['fname']));
    $lname = trim(htmlspecialchars($_POST['lname']));
    $tut_name = $fname . $lname;
    if (isset($_POST['phone'])):
      $phone = trim(htmlspecialchars($_POST['phone']));
    endif;
    $prefsun = trim(htmlspecialchars($_POST['suprefhrs']));
    $prefmon = trim(htmlspecialchars($_POST['moprefhrs']));
    $preftue = trim(htmlspecialchars($_POST['tuprefhrs']));
    $prefwed = trim(htmlspecialchars($_POST['weprefhrs']));
    $prefthu = trim(htmlspecialchars($_POST['thprefhrs']));
    $preffri = trim(htmlspecialchars($_POST['frprefhrs']));
    $prefsat = trim(htmlspecialchars($_POST['saprefhrs']));
    
    $busysun = trim(htmlspecialchars($_POST['subusyhrs']));
    $busymon = trim(htmlspecialchars($_POST['mobusyhrs']));
    $busytue = trim(htmlspecialchars($_POST['tubusyhrs']));
    $busywed = trim(htmlspecialchars($_POST['webusyhrs']));
    $busythu = trim(htmlspecialchars($_POST['thbusyhrs']));
    $busyfri = trim(htmlspecialchars($_POST['frbusyhrs']));
    $busysat = trim(htmlspecialchars($_POST['sabusyhrs']));
  
    //put into db from here....
    $id = $_SESSION['id'];
    $query = "insert into tutors (phone) values (:phone) where id = $id";
    
  
  else:
    header('Location: survey.php');
  endif;

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"/>
    <meta name="author" content="Dan Coleman"/>
    <link rel="stylesheet" href="tutor.css" />
    <title>Automated Response</title>
  </head>

  <body>
    <h1>Survey Completion</h1>
    
    <p>
      <?= $fname ?>, your survey was submitted successfully.
    </p>
    
    <p>
      If you have any questions, or would like to further clarify your
      schedule, please contact <?= $contact_name ?> at <?= $contact_email?>.
    </p>
    
  </body>
</html>
