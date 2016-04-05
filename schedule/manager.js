// Dan Coleman

"use strict";

function gebi(item)
{
  return document.getElementById(item);
}

window.onload = function() 
{
  var createButtons = document.getElementsByClassName("creator");
  for (creator in createButtons)
  {
    var courseName = creator.id.trim();
    creator.onclick = makeSchedule(courseName);
  }
}

function makeSchedule(course) 
{
  gebi(course).disabled = true;
  if (toCSV(course))
  {
    writeSchedule(course);
  }
  gebi(course).disabled = false;
}

function toCSV(course) 
{ 
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "convert.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  var param = "coursename=" + course;
  xhr.send(param);
  var response = xhr.responseText.trim();
  var success = false;
  if (response == "created")
  {
    success = true;
  }
  
  return success;
}

function writeSchedule(course) 
{
  /*if (!fileExists)
  {
    scheduleMakingCode
  }
  else
  {
    redirectToExistingSchedule
  }*/
}

