// Dan Coleman

"use strict";

function gebi(item)
{
  return document.getElementById(item);
}

window.onload = function() 
{
  var createButtons = document.getElementsByClassName("creator");
  var schedIds = [];
  for (creator in createButtons)
  {
    document.getElementById('response').innerHTML = "mozzstix";
    var courseName = creator.id.trim();
    creator.onclick = makeSchedule(courseName);
  }
  document.querySelectorAll(schedIds);
  document.getElementById('response').innerHTML = "brownies";
}

function makeSchedule(course) 
{
  gebi(course).disabled = true;
  document.getElementById('response').innerHTML = "cookies";
  if (toCSV(course))
  {
    document.getElementById('response').innerHTML = "more and more brownies";
    writeSchedule(course);
  }
}

function toCSV(course) 
{ 
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "convert.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  var param = "coursename=" + course;
  xhr.send(param);
  var response = xhr.responseText.trim();
  document.getElementById('response').innerHTML = "888GETPIES";
  var success = false;
  if (response == "created")
  {
    success = true;
  }
  
  return success;
}

function writeSchedule(course) 
{
  document.getElementById('response').innerHTML = "cocoaBerryLimefrosting";
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "schedule.py", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  var param = "course=" + course;
  xhr.send(param);
  
  gebi('response').innerHTML = xhr.responseText;
  
  
  /*if (!fileExists)
  {
    scheduleMakingCode
  }
  else
  {
    redirectToExistingSchedule
  }*/
  
  /* for each tutor
  {
    tutorThread = new Thread(tutorName, hours, edLevel = PRIORITY)
    tutorThread.run() -> schedule()
  }*/
  
  /* finalSched[12hrs][6days] each with '' 
  sendBackSchedArray (or sendBackSchedPage)
  finalSchedValuesOutputToFormattedHtmlDoc() //htmlDoc w draggable entries?
  */
}

