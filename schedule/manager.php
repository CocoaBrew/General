<?php
  //Dan Coleman
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  
  session_start();
  
  if (!isset($_SESSION['name']) || !isset($_SESSION['admin']) || 
    $_SESSION['admin'] != 'true'):
    header('Location: login.php');
  endif;

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"/>
    <meta name="author" content="Dan Coleman"/>
    <link rel="stylesheet" href="tutor.css" />
    <!--<script src="jquery-1.12.3.min.js" async="async"></script>
    <script src="makesched.js" async="async"></script>-->
    <script src="manager.js" async="async"></script>
    <title>Tutor Manager</title>
  </head>

  <body>
    <h1>Tutor Manager</h1>
    
    <p>
      <a href="logout.php">Logout</a>.
    </p>
    
    <h2>New Course</h2>
    <p>
      Set up a new course <a href="tutors.php">here</a>.
    </p>
    
    <div id="schedules" class="noScheds">
      <h2>Make Schedule</h2>
      <p class="comment">
        When all tutors for a particular course have completed their surveys,
        the button for that course can be clicked to create the associated
        schedule.
      </p>
    </div>
    
    <!-- 
      hidden Schedules section with existing schedules when available
    -->
    <section id="schedLinks" class="noScheds">
      <h2>Schedules</h2>
    </section>
    
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

  </body>
</html>
