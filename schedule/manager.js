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
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "sched_writer.php", false);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  var param = "course=" + course;
  xhr.send(param);
  var response = xhr.responseText;
  gebi('response').innerHTML = response;
  
  if (response == "written")
  {
    var schedButton = document.createElement("input");
    var btnVal = document.createAttribute("value");
    btnVal.value = course;
    schedButton.attributes.setNamedItem(btnVal);
    
    var attr = document.createAttribute("type");
    attr.value = "button";
    schedButton.attributes.setNamedItem(attr);
    
    schedButton.addEventListener("onclick", function()
    {
      openSched(this);
    });
    
    var linkParent = gebi('schedLinks');
    linkParent.appendChild(schedButton);
  }
  
  /*
    //htmlDoc w draggable entries?.....scriptaculous? prototype?....
    save on change?????
  */
}

function openSched(btn)
{
  var course = btn.value.trim();
  var href = "schedules/" + course + ".html";
  window.open(href, "_blank");
}

