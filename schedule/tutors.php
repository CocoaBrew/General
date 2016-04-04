<?php
  //Dan Coleman
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  
  session_start();
  
  require('../dblogin_sched.php');
  
  $tutorcount = 0;
  if (ISSET($_POST['numtutors'])):
    $tutorcount = trim(htmlspecialchars($_POST['numtutors']));
  elseif (isset($_POST['tutorcount'])):
    $tutorcount = trim(htmlspecialchars($_POST['tutorcount']));
  endif;
  
  $db = new PDO("mysql:host=$db_hostname;dbname=schedule;charset=utf8",
    $db_username, $db_password,
    array(PDO::ATTR_EMULATE_PREPARES => false,
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  
  $message = '';
  
  # verify there is at least one tutor
  if (isset($_POST['submitinfo']) && $tutorcount != 0):
    if (isset($_POST['lname0']) && isset($_POST['idnum0']) && 
      isset($_POST['email0']) && isset($_POST['tuttype0']) && 
        isset($_POST['hrscleared0'])):
      for ($i = 0; $i < $tutorcount; $i++):
        $id_val = 'idnum' . $i;
        $fname_val = 'fname' . $i;
        $lname_val = 'lname' . $i;
        $tutor_id = trim(htmlspecialchars($_POST[$id_val]));
        $fname = trim(htmlspecialchars($_POST[$fname_val]));
        $lname = trim(htmlspecialchars($_POST[$lname_val]));
        $tut_name = $fname . '+' . $lname;
        $email_id = 'email' . $i;
        $tut_email = trim(htmlspecialchars($_POST[$email_id]));
        $ed_val = 'tuttype' . $i;
        $ed_level = trim(htmlspecialchars($_POST[$ed_val]));
        $hrs_val = 'hrscleared' . $i;
        $cleared_hrs = trim(htmlspecialchars($_POST[$hrs_val]));
        $query = "insert into tutors (id, name, email, education, work_hrs) 
          values (:id, :name, :email, :edu, :hrs)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $tutor_id, PDO::PARAM_STR);
        $stmt->bindParam(':name', $tut_name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $tut_email, PDO::PARAM_STR);
        $stmt->bindParam(':edu', $ed_level, PDO::PARAM_STR);
        $stmt->bindParam(':hrs', $cleared_hrs, PDO::PARAM_STR);
        $stmt->execute();
      endfor;
      
      header('Location: setup_course.php');
    else:
      # failure message
      $message = "Submission Failed. Please Try Again.";
    endif;
  endif;
  
  //print_r($_POST);
  
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
    
    <p class="message">
      <?= $message ?>
    </p>
    
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
                required="required" placeholder="9 digits"/>
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
        
        <?php if (isset($_POST['numtutors'])): ?>
          <p>
            <input type="submit" form="addtutors" name="submitinfo" 
              id="addinfo" value="Submit Info" />
          </p>
        <?php endif; ?>
      </form>
    </section>
    
    <script src="tutors.js"></script>
  </body>
</html>
