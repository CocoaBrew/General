<?php
  //Dan Coleman
  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  session_start();
  
  require('../dblogin_sched.php');
  
  if (!isset($_SESSION['name'])):
    header('Location: login.php');
  endif;
  
  # Functions to enumerate the hours for a given course
  function getDayHrs($week_hours)
  {
    $hour_data = array();
    foreach ($week_hours as $day):
      $sunhrs = explode('/', $day[0]);
      $monhrs = explode('/', $day[1]);
      $tuehrs = explode('/', $day[2]);
      $wedhrs = explode('/', $day[3]);
      $thuhrs = explode('/', $day[4]);
      $frihrs = explode('/', $day[5]);
      $hour_data = array($sunhrs, $monhrs, $tuehrs, $wedhrs, $thuhrs, $frihrs);
    endforeach;
    
    return $hour_data;
  }
  
  function makeHrsList($hour_info)
  {
    $hour_list = array();
    $m = 0;
    foreach ($hour_info as $time):
      $hrs_for_day = array();
      $k = 0;
      $i = $time[0];
      while (substr($i, 0, 2) <= substr($time[1], 0, 2))
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
          str_pad($newHr, 5, '0', STR_PAD_LEFT);
        endif;
        $i = $newHr;
        
        $k++;
      }
      $hour_list[$m] = $hrs_for_day;
      $m++;
    endforeach;
    
    return $hour_list;
  }
  
  function findHrs($week_hours)
  { 
    return (makeHrsList(getDayHrs($week_hours)));
  }
  
  $db = new PDO("mysql:host=$db_hostname;dbname=schedule;charset=utf8",
    $db_username, $db_password,
    array(PDO::ATTR_EMULATE_PREPARES => false,
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  
  $coursename = $_SESSION['course'];
  print($coursename);

  $query = "select sun, mon, tue, wed, thu, fri from courses 
    where title = :coursename";
  $stmt = $db->prepare($query);
  $stmt->bindParam(':coursename', $coursename, PDO::PARAM_STR);
  $stmt->execute();
  $week_array = $stmt->fetchAll();

  $hourlist = findHrs($week_array);
  
  # get name from db/session for on-screen display
  $name_list = explode('+', $_SESSION["name"]);
  $name = $name_list[0] . ' ' . $name_list[1];
  
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"/>
    <meta name="author" content="Dan Coleman"/>
    <link rel="stylesheet" href="tutor.css" />
    <title>Tutoring Survey</title>
  </head>

  <body>
    <h1>Survey for Tutoring Schedule</h1>

    <section id="info">
      <form action="response.php" method="post" id="getinfo">
        <h2>Name: <?= $name ?></h2>
        
        <p>
          <label class="biglabel">
            Select preferred hours to tutor: 
            <span class="comment">(CTRL+click for multiple)</span>
          </label>
        </p>
        
        <p>
          <label for="suprefhrs">Preferred Sunday Hrs: </label>
          <select form="getinfo" name="suprefhrs[]" id="suprefhrs"
            multiple="multiple" size="3">
              <?php foreach ($hourlist[0] as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        
        <p>
          <label for="moprefhrs">Preferred Monday Hrs: </label>
          <select form="getinfo" name="moprefhrs[]" id="moprefhrs"
            multiple="multiple" size="3">
              <?php foreach ($hourlist[1] as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        
        <p>
          <label for="tuprefhrs">Preferred Tuesday Hrs: </label>
          <select form="getinfo" name="tuprefhrs[]" id="tuprefhrs"
            multiple="multiple" size="3">
              <?php foreach ($hourlist[2] as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        
        <p>
          <label for="weprefhrs">Preferred Wednesday Hrs: </label>
          <select form="getinfo" name="weprefhrs[]" id="weprefhrs"
            multiple="multiple" size="3">
              <?php foreach ($hourlist[3] as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        
        <p>
          <label for="thprefhrs">Preferred Thursday Hrs: </label>
          <select form="getinfo" name="thprefhrs[]" id="thprefhrs"
            multiple="multiple" size="3">
              <?php foreach ($hourlist[4] as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        
        <p>
          <label for="saprefhrs">Preferred Friday Hrs: </label>
          <select form="getinfo" name="saprefhrs[]" id="saprefhrs"
            multiple="multiple" size="3">
              <?php foreach ($hourlist[5] as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        
        <p>
          <label class="biglabel">
            Select the hours you are in class: 
            <span class="comment">(CTRL+click for multiple)</span>
          </label>
        </p>
        
        <p>
          <label for="subusyhrs">Sunday Class Times: </label>
          <select form="getinfo" name="subusyhrs[]" id="subusyhrs"
            multiple="multiple" size="3">
              <?php foreach ($hourlist[0] as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        
        <p>
          <label for="mobusyhrs">Monday Class Times: </label>
          <select form="getinfo" name="mobusyhrs[]" id="mobusyhrs"
            multiple="multiple" size="3">
              <?php foreach ($hourlist[1] as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        
        <p>
          <label for="tubusyhrs">Tuesday Class Times: </label>
          <select form="getinfo" name="tubusyhrs[]" id="tubusyhrs"
            multiple="multiple" size="3">
              <?php foreach ($hourlist[2] as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        
        <p>
          <label for="webusyhrs">Wednesday Class Times: </label>
          <select form="getinfo" name="webusyhrs[]" id="webusyhrs"
            multiple="multiple" size="3">
              <?php foreach ($hourlist[3] as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        
        <p>
          <label for="thbusyhrs">Thursday Class Times: </label>
          <select form="getinfo" name="thbusyhrs[]" id="thbusyhrs"
            multiple="multiple" size="3">
              <?php foreach ($hourlist[4] as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        
        <p>
          <label for="frbusyhrs">Friday Class Times: </label>
          <select form="getinfo" name="frbusyhrs[]" id="frbusyhrs"
            multiple="multiple" size="3">
              <?php foreach ($hourlist[5] as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        
        <button type="submit" id="finish">
          Finish
        </button>
      </form>
    </section>
    
  </body>
</html>
