<?php
  //Dan Coleman
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  
  session_start();
  
  // checks admin status
  if (!isset($_SESSION['name']) || !isset($_SESSION['admin']) || 
    $_SESSION['admin'] != 'true'):
    header('Location: login.php');
  endif;

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

  # if change requested
  if (isset($_POST['contactedit'])):
    $message = "Invalid Submission. Please Retry.";
    if (isset($_POST['contactname']) && isset($_POST['contactemail']) && 
      isset($_POST['url'])):
      $contact_name = trim(htmlspecialchars($_POST['contactname']));
      $survey_url = trim(htmlspecialchars($_POST['url']));
      $emailEdit = trim(htmlspecialchars($_POST['contactemail']));
      if (isFiltered($emailEdit) && !isInjected($emailEdit)):
        $contact_email = $emailEdit;
        $message = "Information Successfully Changed.";
        $contactInfo = array($contact_name, $contact_email, $survey_url);
        file_put_contents('contact.txt', implode("\n", $contactInfo));
      else:
        $message = "Change Unsuccessful.\nInvalid Email.";
      endif;
    endif;
  endif;

  # Retrieves contact information.
  $infoParts = file('contact.txt', FILE_IGNORE_NEW_LINES);
  $contact_name = $infoParts[0];
  $contact_email = $infoParts[1];
  $survey_url = $infoParts[2];

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"/>
    <meta name="author" content="Dan Coleman"/>
    <link rel="stylesheet" href="tutor.css" />
    <script src="contact.js" async="async"></script>
    <title>Edit Contact</title>
  </head>
  <body>
    <section id="contactchange">
      <form action="editcontact.php" method="post">
        <div id="contactinput">
          <p>
            <label for="contactname">Contact Name: </label>
            <input type="text" id="contactname" name="contactname" 
              value="<?= $contact_name ?>" size="20" required="required" />
          </p>
          <p>
            <label for="contactemail">Contact Email: </label>
            <input type="email" id="contactemail" name="contactemail" size="20" 
              required="required" value="<?= $contact_email ?>" />
          </p>
          <p>
            <label for="url">Survey URL: </label>
            <input type="text" id="url" name="url" value="<?= $survey_url ?>"
             size="30" required="required" />
          </p>
        </div>
        
        <button type="button" id="noeditcontact">Cancel</button>
        <button type="submit" name="contactedit" id="submitedit">
          Submit Edit
        </button>
      </form>
      <?php if ($message != ''): ?>
        <p><?= $message ?></p>
      <?php endif; ?>
    </section>
  </body>
</html>
