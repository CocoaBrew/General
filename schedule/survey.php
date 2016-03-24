<?php
  //Dan Coleman
  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  print_r($_POST);

  require('../dblogin_sched.php');
  
  $db = new PDO("mysql:host=$db_hostname;dbname=schedule;charset=utf8",
    $db_username, $db_password,
    array(PDO::ATTR_EMULATE_PREPARES => false,
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  
  $name = "test8Spring1732"; // not a fixed name

  $query = "select sun, mon, tue, wed, thu, fri, sat from courses";
  $stmt = $db->prepare($query);
  $stmt->bindParam(':name', $c_name, PDO::PARAM_STR);
  $stmt->execute();
  $week_array = $stmt->fetchAll();
  
  //$hour_data = 0;
  foreach ($week_array as $day):
    $sunhrs = explode('/', $day[0]);
    $monhrs = explode('/', $day[1]);
    $tuehrs = explode('/', $day[2]);
    $wedhrs = explode('/', $day[3]);
    $thuhrs = explode('/', $day[4]);
    $frihrs = explode('/', $day[5]);
    $sathrs = explode('/', $day[6]);
    $hour_data = array($sunhrs, $monhrs, $tuehrs, $wedhrs, $thuhrs, $frihrs,
      $sathrs);
  endforeach;
  
  $hourlist = array();
  $m = 0;
  foreach ($hour_data as $time):
    $hrs_for_day = array();
    $k = 0;
    for ($i = $time[0]; $i <= $time[1]; $i = $i + 100):
      $hrs_for_day[$k] = $i;
      $k++;        
    endfor;
    $hourlist[$m] = $hrs_for_day;
    $m++;
  endforeach;
  
  print_r($hourlist);
  
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
        <p>
          <label for="fname">First Name: </label>
          <input type="text" name="fname" id="fname" required="required"/>
        </p>
        
        <p>
          <label for="lname">Last Name: </label>
          <input type="text" name="lname" id="lname" required="required"/>
        </p>
        
        <p>
          <label for="phone">Phone Number: </label>
          <input type="tel" name="phone" id="phone" />
        </p>
        
        <p>
          <label class="biglabel">
            Select preferred hours to tutor: 
            <span class="comment">(CTRL+click for multiple)</span>
          </label>
        </p>
        
        <p>
          <label for="suprefhrs">Desired Sunday Hrs: </label>
          <select form="getinfo" name="suprefhrs" id="suprefhrs"
            multiple="multiple" size="3" required="required">
              <?php foreach ($hourlist[0] as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        
        <p>
          <label for="moprefhrs">Desired Monday Hrs: </label>
          <select form="getinfo" name="moprefhrs" id="moprefhrs"
            multiple="multiple" size="3" required="required">
              <?php foreach ($hourlist[1] as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        
        <p>
          <label for="tuprefhrs">Desired Tuesday Hrs: </label>
          <select form="getinfo" name="tuprefhrs" id="tuprefhrs"
            multiple="multiple" size="3" required="required">
              <?php foreach ($hourlist[2] as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        
        <p>
          <label for="weprefhrs">Desired Wednesday Hrs: </label>
          <select form="getinfo" name="weprefhrs" id="weprefhrs"
            multiple="multiple" size="3" required="required">
              <?php foreach ($hourlist[3] as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        
        <p>
          <label for="thprefhrs">Desired Thursday Hrs: </label>
          <select form="getinfo" name="thprefhrs" id="thprefhrs"
            multiple="multiple" size="3" required="required">
              <?php foreach ($hourlist[4] as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        
        <p>
          <label for="saprefhrs">Desired Friday Hrs: </label>
          <select form="getinfo" name="saprefhrs" id="saprefhrs"
            multiple="multiple" size="3" required="required">
              <?php foreach ($hourlist[5] as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        
        <p>
          <label for="saprefhrs">Desired Saturday Hrs: </label>
          <select form="getinfo" name="saprefhrs" id="saprefhrs"
            multiple="multiple" size="3" required="required">
              <?php foreach ($hourlist[6] as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        
        <p>
          <label class="biglabel">
            Select tutoring hours that conflict with your classes: 
            <span class="comment">(CTRL+click for multiple)</span>
          </label>
        </p>
        
        <p>
          <label for="subusyhrs">Sunday Class Times: </label>
          <select form="getinfo" name="subusyhrs" id="subusyhrs"
            multiple="multiple" size="3" required="required">
              <?php foreach ($hourlist[0] as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        
        <p>
          <label for="mobusyhrs">Monday Class Times: </label>
          <select form="getinfo" name="mobusyhrs" id="mobusyhrs"
            multiple="multiple" size="3" required="required">
              <?php foreach ($hourlist[1] as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        
        <p>
          <label for="tubusyhrs">Tuesday Class Times: </label>
          <select form="getinfo" name="tubusyhrs" id="tubusyhrs"
            multiple="multiple" size="3" required="required">
              <?php foreach ($hourlist[2] as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        
        <p>
          <label for="webusyhrs">Wednesday Class Times: </label>
          <select form="getinfo" name="webusyhrs" id="webusyhrs"
            multiple="multiple" size="3" required="required">
              <?php foreach ($hourlist[3] as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        
        <p>
          <label for="thbusyhrs">Thursday Class Times: </label>
          <select form="getinfo" name="thbusyhrs" id="thbusyhrs"
            multiple="multiple" size="3" required="required">
              <?php foreach ($hourlist[4] as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        
        <p>
          <label for="frbusyhrs">Friday Class Times: </label>
          <select form="getinfo" name="frbusyhrs" id="frbusyhrs"
            multiple="multiple" size="3" required="required">
              <?php foreach ($hourlist[5] as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        
        <p>
          <label for="sabusyhrs">Saturday Class Times: </label>
          <select form="getinfo" name="sabusyhrs" id="sabusyhrs"
            multiple="multiple" size="3" required="required">
              <?php foreach ($hourlist[6] as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        
        <button type="submit" id="finish">
          Finish
        </button>
      </form>
    </section>
    
    <script src="tutorinfo.js"></script>
  </body>
</html>
