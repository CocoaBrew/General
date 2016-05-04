<?php
  //Dan Coleman
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  
  session_start();
  
  require_once('../../capstone/dblogin_sched.php');
  
  $db = new PDO("mysql:host=$db_hostname;dbname=$db_name;charset=utf8",
    $db_username, $db_password,
    array(PDO::ATTR_EMULATE_PREPARES => false,
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  
  if (isset($_SESSION['name'])):
    if (isset($_POST['suprefhrs']) || isset($_POST['moprefhrs']) ||
      isset($_POST['tuprefhrs']) || isset($_POST['weprefhrs']) ||
        isset($_POST['thprefhrs']) || isset($_POST['frprefhrs']) ||
      isset($_POST['subusyhrs']) || isset($_POST['mobusyhrs']) ||
        isset($_POST['tubusyhrs']) || isset($_POST['webusyhrs']) ||
        isset($_POST['thbusyhrs']) || isset($_POST['frbusyhrs'])):
    
      # format data to put in db
      if (isset($_POST['suprefhrs'])):
        $suntimes = array();
        foreach($_POST['suprefhrs'] as $time):
          $suntimes[] = htmlspecialchars($time);
        endforeach;
        $prefsun = implode('/', $suntimes);
      endif;
      
      if (isset($_POST['moprefhrs'])):
        $montimes = array();
        foreach($_POST['moprefhrs'] as $time):
          $montimes[] = htmlspecialchars($time);
        endforeach;
        $prefmon = implode('/', $montimes);
      endif;
      
      if (isset($_POST['tuprefhrs'])):
        $tuetimes = array();
        foreach($_POST['tuprefhrs'] as $time):
          $tuetimes[] = htmlspecialchars($time);
        endforeach;
        $preftue = implode('/', $tuetimes);
      endif;
      
      if (isset($_POST['weprefhrs'])):
        $wedtimes = array();
        foreach($_POST['weprefhrs'] as $time):
          $wedtimes[] = htmlspecialchars($time);
        endforeach;
        $prefwed = implode('/', $wedtimes);
      endif;

      if (isset($_POST['thprefhrs'])):
        $thutimes = array();
        foreach($_POST['thprefhrs'] as $time):
          $thutimes[] = htmlspecialchars($time);
        endforeach;
        $prefthu = implode('/', $thutimes);
      endif;
      
      if (isset($_POST['frprefhrs'])):
        $fritimes = array();
        foreach($_POST['frprefhrs'] as $time):
          $fritimes[] = htmlspecialchars($time);
        endforeach;
        $preffri = implode('/', $fritimes);
      endif;
    
      if (isset($_POST['subusyhrs'])):
        $suntimes = array();
        foreach($_POST['subusyhrs'] as $time):
          $suntimes[] = htmlspecialchars($time);
        endforeach;
        $busysun = implode('/', $suntimes);
      endif;
      
      if (isset($_POST['mobusyhrs'])):
        $montimes = array();
        foreach($_POST['mobusyhrs'] as $time):
          $montimes[] = htmlspecialchars($time);
        endforeach;
        $busymon = implode('/', $montimes);
      endif;
      
      if (isset($_POST['tubusyhrs'])):
        $tuetimes = array();
        foreach($_POST['tubusyhrs'] as $time):
          $tuetimes[] = htmlspecialchars($time);
        endforeach;
        $busytue = implode('/', $tuetimes);
      endif;
      
      if (isset($_POST['webusyhrs'])):
        $wedtimes = array();
        foreach($_POST['webusyhrs'] as $time):
          $wedtimes[] = htmlspecialchars($time);
        endforeach;
        $busywed = implode('/', $wedtimes);
      endif;
      
      if (isset($_POST['thbusyhrs'])):
        $thutimes = array();
        foreach($_POST['thbusyhrs'] as $time):
          $thutimes[] = htmlspecialchars($time);
        endforeach;
        $busythu = implode('/', $thutimes);
      endif;
      
      if (isset($_POST['frbusyhrs'])):
        $fritimes = array();
        foreach($_POST['frbusyhrs'] as $time):
          $fritimes[] = htmlspecialchars($time);
        endforeach;
        $busyfri = implode('/', $fritimes);
      endif;
    
      # put data into db
      $name = $_SESSION['name'];
      $query = "select id from tutors where name = :name";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':name', $name, PDO::PARAM_STR);
      $stmt->execute();
      $ret_val = $stmt->fetchAll();
      if (count($ret_val) == 1):
        $pid = $ret_val[0]['id'];
      endif;
      $query = "insert into available (id, sbusy, mbusy, tbusy, wbusy, rbusy,
        fbusy, spref, mpref, tpref, wpref, rpref, fpref) values (:id, :bsun,
        :bmon, :btue, :bwed, :bthu, :bfri, :psun, :pmon, :ptue, :pwed, :pthu,
        :pfri)";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':id', $pid, PDO::PARAM_STR);
      $stmt->bindParam(':bsun', $busysun, PDO::PARAM_STR);
      $stmt->bindParam(':bmon', $busymon, PDO::PARAM_STR);
      $stmt->bindParam(':btue', $busytue, PDO::PARAM_STR);
      $stmt->bindParam(':bwed', $busywed, PDO::PARAM_STR);
      $stmt->bindParam(':bthu', $busythu, PDO::PARAM_STR);
      $stmt->bindParam(':bfri', $busyfri, PDO::PARAM_STR);
      $stmt->bindParam(':psun', $prefsun, PDO::PARAM_STR);
      $stmt->bindParam(':pmon', $prefmon, PDO::PARAM_STR);
      $stmt->bindParam(':ptue', $preftue, PDO::PARAM_STR);
      $stmt->bindParam(':pwed', $prefwed, PDO::PARAM_STR);
      $stmt->bindParam(':pthu', $prefthu, PDO::PARAM_STR);
      $stmt->bindParam(':pfri', $preffri, PDO::PARAM_STR);
      $stmt->execute();
    endif;
  else:
    header('Location: login.php');
  endif;

  $fname = explode('+', $_SESSION['name'])[0];

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
    <title>Automated Response</title>
  </head>

  <body class="maincontent">
    <h1>Survey Completion</h1>
    
    <p>
      <?= $fname ?>, your survey was submitted.
    </p>
    
    <p>
      Click <a href="logout.php">here</a> to finish the process.
    </p>
    
    <p>
      If you have any questions, or would like to further clarify your
      schedule, please contact <?= $contact_name ?> at <?= $contact_email ?>.
    </p>
    
  </body>
</html>
