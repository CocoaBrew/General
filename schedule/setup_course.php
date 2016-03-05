<?php
  //Dan Coleman
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  
  print_r($_POST);
  
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
          <input type="radio" name="termsem" id="fall" />
          <label for="spring">Spring: </label>
          <input type="radio" name="termsem" id="spring" />
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
            <input type="time" name="sunstart" id="s1" />
            <label for="s2">To: </label>
            <input type="time" name="sunend" id="s2" />
          </p>
          
          <p>
            <label>Monday &mdash; </label>
            <label for="m1">From: </label>
            <input type="time" name="monstart" id="m1" />
            <label for="m2">To: </label>
            <input type="time" name="monend" id="m2" />
          </p>
          
          <p>
            <label>Tuesday &mdash; </label>
            <label for="t1">From: </label>
            <input type="time" name="tuestart" id="t1" />
            <label for="t2">To: </label>
            <input type="time" name="tueend" id="t2" />
          </p>
          
          <p>
            <label>Wednesday &mdash; </label>
            <label for="w1">From: </label>
            <input type="time" name="wedstart" id="w1" />
            <label for="w2">To: </label>
            <input type="time" name="wedend" id="w2" />
          </p>
          
          <p>
            <label>Thursday &mdash; </label>
            <label for="r1">From: </label>
            <input type="time" name="thustart" id="r1" />
            <label for="r2">To: </label>
            <input type="time" name="thuend" id="r2" />
          </p>
          
          <p>
            <label>Friday &mdash; </label>
            <label for="f1">From: </label>
            <input type="time" name="fristart" id="f1" />
            <label for="f2">To: </label>
            <input type="time" name="friend" id="f2" />
          </p>
          
          <p>
            <label>Saturday &mdash; </label>
            <label for="sa1">From: </label>
            <input type="time" name="satstart" id="sa1" />
            <label for="sa2">To: </label>
            <input type="time" name="satend" id="sa2" />
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
    
    <script src="course.js"></script>
  </body>
</html>
