// Dan Coleman

"use strict";

function gebi(item)
{
  return document.getElementById(item);
}

window.onload = function() 
{
  /*var createButtons = document.getElementsByClassName("creator");
  var schedIds = [];
  for (creator in createButtons)
  {
    var courseName = creator.id.trim();
    schedIds.push(courseName);
  }*/
  document.getElementById('response').innerHTML = "brownies";
  
  document.getElementById('HowardMath').addEventListener("click", function() 
    {
      makeSchedule(this.id);
    });
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
  var success = false;
  xhr.open("POST", "convert.php", false);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  var param = "coursename=" + course;
  xhr.send(param);
  
  var response = xhr.responseText.trim();
  document.getElementById('response').innerHTML = response;
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
  xhr.open("POST", "schedule.py", false);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  var param = "course=" + course;
  xhr.send(param);
  
  gebi('response').innerHTML = xhr.responseText;
  
  
  /*
  finalSchedValuesOutputToFormattedHtmlDoc() //htmlDoc w draggable entries?
  */
}

