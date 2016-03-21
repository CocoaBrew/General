<?php
  //Dan Coleman
  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  $hourlist = array('test', 2, 37, 7897);
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
          <label for="prefhrs">
            Select preferred tutoring hours: 
          </label>
          <select form="getinfo" name="prefhrs" id="prefhrs"
            multiple="multiple" size="3" required="required">
              <?php foreach ($hourlist as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
          <span class="comment">(CTRL+click for multiple)</span>
        </p>
        
        <p>
          <label for="busyhrs">
            Select any tutoring hours you would be absolutely unavailable: 
          </label>
          <select form="getinfo" name="busyhrs" id="busyhrs"
            multiple="multiple" size="3" required="required">
              <?php foreach ($hourlist as $hour): ?>
                <option value=<?= $hour ?>><?= $hour ?></option>
              <?php endforeach; ?>
          </select>
          <span class="comment">(CTRL+click for multiple)</span>
        </p>
        
        <button type="submit" id="finish" disabled="true">
          Finish
        </button>
      </form>
    </section>
    
    <script src="tutorinfo.js"></script>
  </body>
</html>
