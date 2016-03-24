<?php
  //Dan Coleman
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  
  $tutorcount = 0;
  
  if (ISSET($_POST['numtutors'])):
    $tutorcount = trim(htmlspecialchars($_POST['numtutors']));
  endif;
  
  print_r($_POST);
  
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"/>
    <meta name="author" content="Dan Coleman"/>
    <link rel="stylesheet" href="tutor.css" />
    <title>Tutors</title>
  </head>

  <body>
    <h1>Tutors</h1>
    
    <p>
      <form id="tutortotal" action="tutors.php" method="post">
        <label for="numtutors">Number of Tutors: </label>
        <input type="number" name="numtutors" id="numtutors" 
          pattern="[0-9]+" placeholder="<?= $tutorcount ?>"/>
        
        <button type="submit" form="tutortotal">
          Submit
        </button>
      </form>
    </p>
    
    <section id="tutorlist">
      <form id="addtutors" action="tutors.php" method="post">
        <label for="listname">Name for Tutor Group: </label>
        <input type="text" name="listname" id="listname"
          placeholder="title for this list" required="required"/>
          
        <?php for ($tutor = 0; $tutor < $tutorcount; $tutor++): ?>
          <div class="singletutor">
            <p>
              <label for="fname<?= $tutor ?>">First Name: </label>
              <input type="text" name="fname<?= $tutor ?>" 
                id="fname<?= $tutor ?>" required="required"/>
              
              <label for="lname<?= $tutor ?>">Last Name: </label>
              <input type="text" name="lname<?= $tutor ?>" 
                id="lname<?= $tutor ?>" required="required"/>
            </p>
        
            <p>
              <label for="email<?= $tutor ?>">Full Truman Email: </label>
              <input type="email" name="email<?= $tutor ?>" 
                id="email<?= $tutor ?>" required="required"/>
                
              <label for="idnum<?= $tutor ?>">ID Number: </label>
              <input type="text" name="idnum<?= $tutor ?>" 
                id="idnum<?= $tutor ?>" pattern="[0-9]{9}" 
                required="required"/>
            </p>
            
            <p>
              <label for="tuttype<?= $tutor ?>">
                Tutor's Education Level: 
              </label>
              <label for="undergrad">Undergraduate</label>
              <input type="radio" name="tuttype<?= $tutor ?>"
                id="undergrad" value="ug" />
              <label for="grad">Graduate</label>
              <input type="radio" name="tuttype<?= $tutor ?>"
                id="grad" value="gr" />
            </p>
            
            <p>
              <label for="hrscleared<?= $tutor ?>">
                Number of Hours Cleared to Work Each Week:
              </label>
              <input type="number" name="hrscleared<?= $tutor ?>"
                id="hrscleared<?= $tutor ?>" pattern="[0-9]+" 
                required="required" />
            </p>
          </div>
        <?php endfor; ?>
        
        <input type="hidden" name="tutorcount" value="<?= $tutorcount ?>" />
        
        <p>
          <button type="submit" id="addinfo">
            Submit Info
          </button>
        </p>
      </form>
    </section>
    
    <script src="tutors.js"></script>
  </body>
</html>
