<?php
  //Dan Coleman
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  
  require('../dblogin_sched.php');
  
  print_r($_POST);
  
  # messaging needs work
  $message = '';
  
  if (isset($_POST['coursename']) && $_POST['coursename'] != ''):
    if ((isset($_POST['sunstart']) && isset($_POST['sunend'])) ||
      (isset($_POST['monstart']) && isset($_POST['monend'])) || 
        (isset($_POST['tuestart']) && isset($_POST['tueend'])) ||
          (isset($_POST['wedstart']) && isset($_POST['wedend'])) ||
            (isset($_POST['thustart']) && isset($_POST['thuend'])) ||
              (isset($_POST['fristart']) && isset($_POST['friend'])) ||
                (isset($_POST['satstart']) && isset($_POST['satend']))):
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
  
      $sa_beg = trim(htmlspecialchars($_POST['satstart']));
      $sa_end = trim(htmlspecialchars($_POST['satend']));
      $sat = $sa_beg . '/' . $sa_end;
    
      $db = new PDO("mysql:host=$db_hostname;dbname=schedule;charset=utf8",
        $db_username, $db_password,
        array(PDO::ATTR_EMULATE_PREPARES => false,
              PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            
      $coursename = trim(htmlspecialchars($_POST['coursename']));
      $year = trim(htmlspecialchars($_POST['termyr']));
      $term = '';
      if (isset($_POST['termsem'])):
        $term = ucfirst(trim(htmlspecialchars($_POST['termsem'])));
      endif;

      $title = $coursename . $term . $year;
    
      $query = "insert into courses (title, sun, mon, tue, wed, thu, fri, sat)
        values (:title, :sun, :mon, :tue, :wed, :thu, :fri, :sat)";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':title', $title, PDO::PARAM_STR);
      $stmt->bindParam(':sun', $sun, PDO::PARAM_STR);
      $stmt->bindParam(':mon', $mon, PDO::PARAM_STR);
      $stmt->bindParam(':tue', $tue, PDO::PARAM_STR);
      $stmt->bindParam(':wed', $wed, PDO::PARAM_STR);
      $stmt->bindParam(':thu', $thu, PDO::PARAM_STR);
      $stmt->bindParam(':fri', $fri, PDO::PARAM_STR);
      $stmt->bindParam(':sat', $sat, PDO::PARAM_STR);
      $stmt->execute();
  
      /*$query = "create table $title (
        id varchar(255),
        sbusy varchar(255),
        mbusy varchar(255),
        tbusy varchar(255),
        wbusy varchar(255),
        rbusy varchar(255),
        fbusy varchar(255),
        qbusy varchar(255),
        spref varchar(255),
        mpref varchar(255),
        tpref varchar(255),
        wpref varchar(255),
        rpref varchar(255),
        fpref varchar(255),
        qpref varchar(255))";
      $stmt = $db->prepare($query);
      $stmt->execute();*/
      
      $message = "Setup Complete.";
    else:
      $message = "Setup Failed. Please Try Again.";
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
    
    <section id="courseinfo">
      <form id="makecourse" action="setup_course.php" method="post">        
        <p>
          <label for="coursename">
            Course Name (or Number): 
          </label>
          <input type="text" name="coursename" id="coursename" 
            required="required" placeholder="e.g., Math200, Calc2" />
        </p>
            
        <p>
          <label for="termsem">Term Semester: </label>
          <label for="fall">Fall: </label>
          <input type="radio" name="termsem" id="fall" value="fall"/>
          <label for="spring">Spring: </label>
          <input type="radio" name="termsem" id="spring" value="spring"/>
        </p>
          
        <p>
          <label for="termyr">Term Year: </label>
          <input type="text" name="termyr" id="termyr" 
            pattern="[0-9]{4}" placeholder="e.g., 1941" 
            required="required"/>
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
            <label>Saturday &mdash; </label>
            <label for="sa1">From: </label>
            <input type="time" name="satstart" id="sa1" placeholder="0900"/>
            <label for="sa2">To: </label>
            <input type="time" name="satend" id="sa2" placeholder="1800"/>
          </p>
        </div>
        
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
    
  </body>
</html>
