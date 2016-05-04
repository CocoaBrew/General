<?php
  //Dan Coleman
  //error_reporting(E_ALL);
  //ini_set('display_errors', '1');
  
  session_start();
  
  // checks admin status
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
    <script src="manager.js" async="async"></script>
    <title>Tutor Manager</title>
  </head>

  <body>
    <h1>Tutor Manager</h1>
    
    <aside id="optpanel">
      <p>
        <a href="logout.php">Logout</a>
      </p>
      <p>
        <button type="button" id="adminpswd">
          Reset Admin Passcode
        </button>
      </p>
      <p>
        <button type="button" id="editcontact">
          Edit Main Contact
        </button>
      </p>
    </aside>
    
    <div class="maincontent">
      <h2>New Course</h2>
      <p>
        Set up a new course <a href="tutors.php">here</a>.
      </p>
    
      <div id="schedules" class="noScheds">
        <h2>Make Schedule</h2>
        <p>
          <label for="">Tutors per Shift: </label>
          <input type="number" id="tutpershift" value="2" />
        </p>
        <p class="comment">
          When all tutors for a particular course have completed their surveys,
          <br />the button for that course can be clicked to create the 
          associated schedule.
        </p>
      </div>
    
      <section id="schedLinks" class="noScheds">
        <h2>Schedules</h2>
      </section>
    
      <p>
        <h2>Reset</h2>
        <p class="comment">
          This will clear all current course and tutor information. 
        </p>
        <form id="dataclear" action="manager.php" method="post">
          <input type="submit" form="dataclear" name="clear" id="clear"
            value="Clear Data" /> 
          <span class="comment">*Only use at the end of the semester*</span>
        </form>
      </p>
    </div>

  </body>
</html>
