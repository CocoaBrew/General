<?php
  //Dan Coleman
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  
  session_start();

  require_once('../../capstone/dblogin_sched.php');
  
  // check admin status
  if (!isset($_SESSION['name']) || !isset($_SESSION['admin']) || 
    $_SESSION['admin'] != 'true'):
    header('Location: login.php');
  endif;

  $message = '';

  if (isset($_POST['change'])):
    $message = "Invalid Submission. Please Retry.";
    if (isset($_POST['fname']) && isset($_POST['lname']) &&
      isset($_POST['pscd'])):
      $fname = trim(htmlspecialchars($_POST['fname']));
      $lname = trim(htmlspecialchars($_POST['lname']));
      $passcode = trim(htmlspecialchars($_POST['pscd']));
      $fullname = $fname . '+' . $lname;

      $db = new PDO("mysql:host=$db_hostname;dbname=$db_name;charset=utf8",
      $db_username, $db_password,
      array(PDO::ATTR_EMULATE_PREPARES => false,
           PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

      // count number of entries with name of $fullname
      $query = "select count(id) from admin where name = :name";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':name', $fullname, PDO::PARAM_STR);
      $stmt->execute();
      $retVal = $stmt->fetchAll();
      if ($retVal[0]['count(id)'] > 0):
        // update existing entry
        $query = "update admin set id = :pid where name = :name";
      else:
        // create new entry
        $query = "insert into admin(id, name) values (:pid, :name)";
      endif;
      $stmt = $db->prepare($query);
      $stmt->bindParam(':name', $fullname, PDO::PARAM_STR);
      $stmt->bindParam(':pid', $passcode, PDO::PARAM_STR);
      $stmt->execute();
      $message = "Successful Submission.";
    endif;
  endif;

  $name_list = explode('+', $_SESSION["name"]);
  $fname = $name_list[0];
  $lname = $name_list[1];
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"/>
    <meta name="author" content="Dan Coleman"/>
    <link rel="stylesheet" href="tutor.css" />
    <script src="passcode.js" async="async"></script>
    <title>Change Passcode</title>
  </head>
  <body>
    <section id="pswdchange">
      <form action="changepasscode.php" method="post">
        <div id="pswdresetinput">
          <p>
            <label for="fname">Admin First Name: </label>
            <input type="text" id="fname" name="fname" value="<?= $fname ?>"
             size="15" required="required"/>
          </p>
          <p>
            <label for="lname">Admin Last Name: </label>
            <input type="text" id="lname" name="lname" value="<?= $lname ?>"
              size="15" required="required" />
          </p>
          <p>
            <label for="pscd">New Passcode: </label>
            <input type="password" id="pscd" name="pscd" size="15" 
              required="required" />
          </p>
          <p>
            <label for="pscd2">Re-type Passcode: </label>
            <input type="password" id="pscd2" name="pscd2" size="15" 
              required="required" />
          </p>
        </div>
        
        <button type="button" id="nochangepscd">Cancel</button>
        <button type="submit" name="change" id="makechange">
          Submit Change
        </button>
      </form>

      <?php if ($message != ''): ?>
        <p><?= $message ?></p>
      <?php endif; ?>

    </section>
  </body>
</html>
