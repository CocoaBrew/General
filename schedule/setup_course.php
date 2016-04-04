<?php
  //Dan Coleman
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  
  require('../dblogin_sched.php');
  require('contact.php');
  
  # Functions for checking the validity and security
  # of an email address
  function isInjected($email_str)
  {
    $injections = array('(\n+)', '(\r+)', '(\t+)', '(%0A+)', '(%0D+)',
      '(%08+)', '(%09+)');
      
    $inject = join('|', $injections);
    $inject = "/$inject/i";
    
    if (preg_match($inject, $email_str)):
      return true;
    else:
      return false;
    endif;
  }
  
  function isFiltered($email_str)
  {
    $filtered_email = filter_var($email_str, FILTER_VALIDATE_EMAIL);
    if ($filtered_email):
      return true;
    else:
      return false;
    endif;
  }  
  
  $message = '';
  $success = false;
  
  if (isset($_POST['coursename']) && $_POST['coursename'] != ''):
    if ((isset($_POST['sunstart']) && isset($_POST['sunend'])) ||
      (isset($_POST['monstart']) && isset($_POST['monend'])) || 
        (isset($_POST['tuestart']) && isset($_POST['tueend'])) ||
          (isset($_POST['wedstart']) && isset($_POST['wedend'])) ||
            (isset($_POST['thustart']) && isset($_POST['thuend'])) ||
              (isset($_POST['fristart']) && isset($_POST['friend']))):
      $su_beg = trim(htmlspecialchars($_POST['sunstart']));
      $su_end = trim(htmlspecialchars($_POST['sunend']));
      $sun = $su_beg . '/' . $su_end;
  
      $mo_beg = trim(htmlspecialchars($_POST['monstart']));
      $mo_end = trim(htmlspecialchars($_POST['monend']));
      $mon = $mo_beg . '/' . $mo_end;
  
      $tu_beg = trim(htmlspecialchars($_POST['tuestart']));
      $tu_end = trim(htmlspecialchars($_POST['tueend']));
      $tue = $tu_beg . '/' . $tu_end;
  
      $we_beg = trim(htmlspecialchars($_POST['wedstart']));
      $we_end = trim(htmlspecialchars($_POST['wedend']));
      $wed = $we_beg . '/' . $we_end;
  
      $th_beg = trim(htmlspecialchars($_POST['thustart']));
      $th_end = trim(htmlspecialchars($_POST['thuend']));
      $thu = $th_beg . '/' . $th_end;
  
      $fr_beg = trim(htmlspecialchars($_POST['fristart']));
      $fr_end = trim(htmlspecialchars($_POST['friend']));
      $fri = $fr_beg . '/' . $fr_end;
    
      $db = new PDO("mysql:host=$db_hostname;dbname=schedule;charset=utf8",
        $db_username, $db_password,
        array(PDO::ATTR_EMULATE_PREPARES => false,
              PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            
      $title = trim(htmlspecialchars($_POST['coursename']));
    
      $query = "insert into courses (title, sun, mon, tue, wed, thu, fri)
        values (:title, :sun, :mon, :tue, :wed, :thu, :fri)";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':title', $title, PDO::PARAM_STR);
      $stmt->bindParam(':sun', $sun, PDO::PARAM_STR);
      $stmt->bindParam(':mon', $mon, PDO::PARAM_STR);
      $stmt->bindParam(':tue', $tue, PDO::PARAM_STR);
      $stmt->bindParam(':wed', $wed, PDO::PARAM_STR);
      $stmt->bindParam(':thu', $thu, PDO::PARAM_STR);
      $stmt->bindParam(':fri', $fri, PDO::PARAM_STR);
      $stmt->execute();
      
      # Send emails with survey link
      $query = "select name, email from tutors";
      $stmt = $db->prepare($query);
      $stmt->execute();
      $tutorinfo = $stmt->fetchAll();
      foreach ($tutorinfo as $tutor):
        $emailadd = $tutor[1];
        $survey_addr = $survey_url + "?c=$title";
        $namelist = explode('+', $tutor[0]);
        $subject = "Availability Survey";
        $content = "
          <html>
          <head>
            <title>Availability Survey</title>
          </head>
          <body>
            <p>
              $namelist[0] $namelist[1], <br /><br />
              You are currently listed as a tutor for the upcoming 
              semester. <br />
              Please take a few minutes and fill out this survey regarding
              your schedule and times you are available for tutoring.
            </p>
            <br />
            <p>
              <a href=$survey_addr>Tutoring Survey</a>
            </p>
            <br />
            <p>
              $contact_name
            </p>
          </body>
          </html>
        ";
        $headers = "From: $contact_name <$contact_email>" . "\r\n";
        $headers .= "Content-type: text/html; charset=utf-8" . "\r\n";
        if (isFiltered($emailadd) && !isInjected($emailadd)):
          mail($emailadd, $subject, $content, $headers);
        else:
          $subj = "Availability Survey: Email Issue";
          $alt_content = "There was an issue with $namelist[0] $namelist[1]'s
            email.";
          $alt_headers = "From: Scheduling System <$contact_email>";
          mail($contact_email, $subj, $alt_content, $alt_headers);
        endif;
      endforeach;
      
      $message = "Setup Complete.";
      $success = true;
    else:
      $message = "Course Setup Failed. Please Try Again.";
    endif;
  endif;
    
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"/>
    <meta name="author" content="Dan Coleman"/>
    <link rel="stylesheet" href="tutor.css" />
    <title>Course</title>
  </head>

  <body>
    <h1>Course</h1>
    
    <p class="message">
      <?= $message ?>
    </p>
    
    <?php if (!$success): ?>
    <section id="courseinfo">
      <form id="makecourse" action="setup_course.php" method="post">        
        <p>
          <label for="coursename">Course Name: </label>
          <input type="text" name="coursename" id="coursename" 
            required="required" placeholder="e.g., Math200, Calc2" />
        </p>
        
        <p>
          <label>Times for Tutoring: </label>
        </p>
        
        <div id="times">
          <p>
            <label>Sunday &mdash; </label>
            <label for="s1">From: </label>
            <input type="time" name="sunstart" id="s1" placeholder="0900"/>
            <label for="s2">To: </label>
            <input type="time" name="sunend" id="s2" placeholder="1800"/>
          </p>
          
          <p>
            <label>Monday &mdash; </label>
            <label for="m1">From: </label>
            <input type="time" name="monstart" id="m1" placeholder="0900"/>
            <label for="m2">To: </label>
            <input type="time" name="monend" id="m2" placeholder="1800"/>
          </p>
          
          <p>
            <label>Tuesday &mdash; </label>
            <label for="t1">From: </label>
            <input type="time" name="tuestart" id="t1" placeholder="0900"/>
            <label for="t2">To: </label>
            <input type="time" name="tueend" id="t2" placeholder="1800"/>
          </p>
          
          <p>
            <label>Wednesday &mdash; </label>
            <label for="w1">From: </label>
            <input type="time" name="wedstart" id="w1" placeholder="0900"/>
            <label for="w2">To: </label>
            <input type="time" name="wedend" id="w2" placeholder="1800"/>
          </p>
          
          <p>
            <label>Thursday &mdash; </label>
            <label for="r1">From: </label>
            <input type="time" name="thustart" id="r1" placeholder="0900"/>
            <label for="r2">To: </label>
            <input type="time" name="thuend" id="r2" placeholder="1800"/>
          </p>
          
          <p>
            <label>Friday &mdash; </label>
            <label for="f1">From: </label>
            <input type="time" name="fristart" id="f1" placeholder="0900"/>
            <label for="f2">To: </label>
            <input type="time" name="friend" id="f2" placeholder="1800"/>
          </p>
          
        <p>
          <input type="checkbox" name="verify" value="true" id="verify"
            required="required" /> 
          <label for="verify">
            The above tutoring times are correct.
          </label>
        </p>
        
        <button type="submit" id="sendsurvey">
          Send Survey
        </button>
      </form>
    </section>
    
    <?php else: ?>
    
    <p>
      Return <a href="manager.php">home</a>, 
      or <a href="logout.php">logout</a>.
    </p>
    
    <?php endif; ?>
  </body>
</html>
