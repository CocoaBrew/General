// Dan Coleman

"use strict";

function gebi(item)
{
  return document.getElementById(item);
}

window.onload = function() 
{
  this.placeButtons();
  
}

function placeButtons()
{
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "getcourses.php", false);
  xhr.send();
  var courses = JSON.parse(xhr.responseText);
  var btnParent = document.getElementById("schedules");

  for (var course = 0; course < courses.length; course++)
  {
    var title = courses[course][0].trim();
    var schedButton = document.createElement("button");
    var btnId = document.createAttribute("id");
    btnId.value = title;
    schedButton.attributes.setNamedItem(btnId);
    var btnVal = document.createAttribute("value");
    btnVal.value = title;
    schedButton.attributes.setNamedItem(btnVal);
    var attr = document.createAttribute("type");
    attr.value = "button";
    schedButton.attributes.setNamedItem(attr);
    schedButton.innerHTML = title;
    schedButton.classList.add("creator");
    schedButton.addEventListener("click", function()
    {
      makeSchedule(this.id);
    });
    btnParent.appendChild(schedButton);
    setDisabled(title);
  }
}

function setDisabled(id)
{
  gebi(id).disabled = true;
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "checkcount.php", false);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  var param = "course=" + id;
  xhr.send(param);
  var response = xhr.responseText;
  if (response == "ready")
  {
    gebi(id).disabled = false;
  }
}

function makeSchedule(course) 
{
  gebi(course).disabled = true;
  if (toCSV(course))
  {
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
  //var response = xhr.responseText;
 
  gebi('response').innerHTML = '(' + xhr.responseText + ')';
  
  if (response == "written")
  {
    var schedButton = document.createElement("button");
    var btnVal = document.createAttribute("value");
    btnVal.value = course;
    schedButton.attributes.setNamedItem(btnVal);
    
    var attr = document.createAttribute("type");
    attr.value = "button";
    schedButton.attributes.setNamedItem(attr);
    
    schedButton.addEventListener("click", function()
    {
      openSched(this);
    });
    
    var linkParent = gebi('schedLinks');
    linkParent.appendChild(schedButton);
  }
  
  /*
    //htmlDoc w draggable entries?.....scriptaculous?....
    save on change?????
  */
}

function openSched(btn)
{
  var course = btn.value.trim();
  var href = "schedules/" + course + ".html";
  window.open(href, "_blank");
}

