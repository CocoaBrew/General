<?php
  //Dan Coleman
  //error_reporting(E_ALL);
  //ini_set('display_errors', '1');
  
  session_start();
  
  require_once('../../capstone/dblogin_sched.php');
  
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
  
  # Functions to store the desired tutoring hrs in a file
  function getDayHrs($week_hours)
  {
    $hour_data = array();
    $sunhrs = array();
    if ($week_hours[0] != '/'):
      $sunhrs = explode('/', $week_hours[0]);
    endif;
    
    $monhrs = array();
    if ($week_hours[1] != '/'):
      $monhrs = explode('/', $week_hours[1]);
    endif;
    
    $tuehrs = array();
    if ($week_hours[2] != '/'):
      $tuehrs = explode('/', $week_hours[2]);
    endif;
    
    $wedhrs = array();
    if ($week_hours[3] != '/'):
      $wedhrs = explode('/', $week_hours[3]);
    endif;
    
    $thuhrs = array();
    if ($week_hours[4] != '/'):
      $thuhrs = explode('/', $week_hours[4]);
    endif;
    
    $frihrs = array();
    if ($week_hours[5] != '/'):
      $frihrs = explode('/', $week_hours[5]);
    endif;
    
    $hour_data = array($sunhrs, $monhrs, $tuehrs, $wedhrs, $thuhrs, $frihrs);
  
    return $hour_data;
  }
  function makeHrsList($hour_info)
  {
    $hour_list = array();
    $m = 0;
    foreach ($hour_info as $time):
      $hrs_for_day = array();
      $k = 0;
      if (!empty($time)):
        $i = $time[0];
        while (substr($i, 0, 2) < substr($time[1], 0, 2))
        {
          $hrs_for_day[$k] = $i;
        
          # add half-hour increments
          if (substr($i, 0, 2) < substr($time[1], 0, 2)):
            $k++;
            $iHalfHr = substr_replace($i, "30", 3, 2);
            $hrs_for_day[$k] = $iHalfHr;
          endif;
        
          # set new hour value
          $newHr = substr_replace($i, substr($i, 0, 2) + 1, 0, 2);
          if (strlen($newHr) < 5):
            $newHr = str_pad($newHr, 5, '0', STR_PAD_LEFT);
          endif;
          $i = $newHr;
        
          $k++;
        }
      endif;
      $hour_list[$m] = $hrs_for_day;
      $m++;
    endforeach;
    
    return $hour_list;
  }
  function storeHrs($week_hours, $filename)
  { 
    touch($filename);
    chmod($filename, 0746);
    $outFile = fopen($filename, 'w');
    $hrs = makeHrsList(getDayHrs($week_hours));
    foreach ($hrs as $hr):
      fputcsv($outFile, $hr);
    endforeach;
    fclose($outFile);
  }
  
  $message = '';
  $success = false;
  
  $db = new PDO("mysql:host=$db_hostname;dbname=$db_name;charset=utf8",
    $db_username, $db_password,
    array(PDO::ATTR_EMULATE_PREPARES => false,
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
          
  $title = '';
  if (isset($_SESSION['title'])):
    $title = trim(htmlspecialchars($_SESSION['title']));
  endif;
  
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
    
      # put course information into db
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
      
      # write hours to CSV
      $filename = 'CSVs/' . $title; 
      mkdir($filename);
      chmod($filename, 0733);
      $filename = $filename . '/' . $title . '.csv';
      $hours = array($sun, $mon, $tue, $wed, $thu, $fri);
      storeHrs($hours, $filename);
      
      # Retrieves contact information.
      $infoParts = file('contact.txt', FILE_IGNORE_NEW_LINES);
      $contact_name = $infoParts[0];
      $contact_email = $infoParts[1];
      $survey_url = $infoParts[2];

      # Send emails with survey link
      $query = "select t.name, t.email, t.education, t.work_hrs 
        from tutors as t inner join course_for_tutor as c
        where t.id = c.id and c.course = :title";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':title', $title, PDO::PARAM_STR);
      $stmt->execute();
      $tutorinfo = $stmt->fetchAll();
      foreach ($tutorinfo as $tutor):
        # Add tutor info to course_tutors csv
        $filename = 'CSVs/' . $title . '/' . $title . 'tutors.csv';
        touch($filename);
        chmod($filename, 0746);
        $outFile = fopen($filename, 'a');
        $namelist = explode('+', $tutor[0]);
        $ed = $tutor[2];
        $hrs = $tutor[3];
        $entry = array($namelist[0] . $namelist[1] . $hrs . $ed . '.csv');
        fputcsv($outFile, $entry);
        fclose($outFile);
        
        # Email
        $emailadd = $tutor[1];
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
              your schedule and times you are available for tutoring. <br />
              You must be on the campus network to access the survey. <br />
              Also, from personal experience, the survey works best when
              completed using Google Chrome.
            </p>
            <br />
            <p>
              <a href=$survey_url>Tutoring Survey</a>
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
      
      # Put number of tutors into text file
      $tutorcount = count($tutorinfo); // count of tutors in db
      $filename = 'counts/' . $title . 'tutorcount.txt';
      touch($filename);
      chmod($filename, 0606);
      file_put_contents($filename, $tutorcount);
      file_put_contents($filename, "\n", FILE_APPEND); 
      
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
    <section id="courseinfo" class="maincontent">
      <form id="makecourse" action="setup_course.php" method="post">
        <?php if ($title != ''): ?>
          <h2>Course: <?= $title ?></h2>
        <?php else: ?>
          <h2>Course: (None)</h2>
        <?php endif; ?>
        <p>
          <label>Times for Tutoring: </label>
        </p>
        
        <div id="times">
          <p>
            <label>Sunday &mdash; </label>
            <label for="s1">From: </label>
            <input type="time" name="sunstart" id="s1"/>
            <label for="s2">To: </label>
            <input type="time" name="sunend" id="s2"/>
          </p>
          
          <p>
            <label>Monday &mdash; </label>
            <label for="m1">From: </label>
            <input type="time" name="monstart" id="m1"/>
            <label for="m2">To: </label>
            <input type="time" name="monend" id="m2"/>
          </p>
          
          <p>
            <label>Tuesday &mdash; </label>
            <label for="t1">From: </label>
            <input type="time" name="tuestart" id="t1"/>
            <label for="t2">To: </label>
            <input type="time" name="tueend" id="t2"/>
          </p>
          
          <p>
            <label>Wednesday &mdash; </label>
            <label for="w1">From: </label>
            <input type="time" name="wedstart" id="w1"/>
            <label for="w2">To: </label>
            <input type="time" name="wedend" id="w2"/>
          </p>
          
          <p>
            <label>Thursday &mdash; </label>
            <label for="r1">From: </label>
            <input type="time" name="thustart" id="r1"/>
            <label for="r2">To: </label>
            <input type="time" name="thuend" id="r2"/>
          </p>
          
          <p>
            <label>Friday &mdash; </label>
            <label for="f1">From: </label>
            <input type="time" name="fristart" id="f1"/>
            <label for="f2">To: </label>
            <input type="time" name="friend" id="f2"/>
          </p>
        </div>
          
        <p>
          <input type="checkbox" name="verify" value="true" id="verify"
            required="required" /> 
          <label for="verify">
            The above tutoring times are correct.
          </label>
        </p>
        
        <input type="hidden" name="coursename" value="<?= $title ?>" />
        
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
