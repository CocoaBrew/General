// Dan Coleman

"use strict";

function gebi(item)
{
  return document.getElementById(item);
}

window.onload = function() 
{
  this.placeButtons();

  gebi('clear').onclick = resetData;
}

function resetData()
{
  var eraseData = confirm("Do you want to erase all the tutor, course, " +
    "and scheduling information?\nThis action is not recoverable.");
  if (eraseData == true)
  {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "reset.php", false);
    xhr.send();
    var response = xhr.responseText;

    if (response == "cleared")
    {
      gebi('schedLinks').classList.add("noScheds");
      gebi('schedules').classList.add("noScheds");
      this.placeButtons();
    }
  }
}

function placeButtons()
{
  writeCreatorButtons();
  placeCurrSchedLinks();
}

function placeCurrSchedLinks()
{
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "currschedules.php", false);
  xhr.send();
  var schedules = JSON.parse(xhr.responseText);
  //if (count(schedules) == 1), wSL(schedules); else for loop thru
  // count may be a type check
  if (schedules != "")
  {
    if (typeof schedules === 'object')
    {
      for (var i = 0; i < schedules.length; i++)
      {
        var course = schedules[i];
        writeSchedLink(course);
        gebi(course).addEventListener("mouseover", function()
        {
          confirmRewrite(this.id);
        });
      }
    }
  }
}

function confirmRewrite(course)
{
  gebi(course).disabled = true;
  var rewrite = window.confirm("You already have a schedule for " + 
    course.trim() + ".\nWould you like to rewrite this schedule?");
  if (rewrite == true)
  {
    makeSchedule(course);
  }
  gebi(course).disabled = false;
}

function writeCreatorButtons(courses)
{
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "getcourses.php", false);
  xhr.send();
  var courses = JSON.parse(xhr.responseText);
  var btnParent = document.getElementById("schedules");

  if (courses.length > 0)
  {
    btnParent.classList.remove("noScheds");
  }

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
  gebi(course).disabled = false;
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
  var response = xhr.responseText.trim();
 
  gebi('response').innerHTML = '(' + response + ')';
  if (response == "written")
  {
    writeSchedLink(course);
  }
  
  /*
    Updates for the Future: 
    htmlSchedDoc w draggable entries?.....scriptaculous?
    .............save on change?????
  */
}

function writeSchedLink(course)
{
  removeExistBtn(course);
  var schedButton = document.createElement("button");
  var btnVal = document.createAttribute("value");
  btnVal.value = course;
  schedButton.attributes.setNamedItem(btnVal);
  
  var attr = document.createAttribute("type");
  attr.value = "button";
  schedButton.attributes.setNamedItem(attr);
  schedButton.classList.add("creator");
  schedButton.innerHTML = course;
    
  schedButton.addEventListener("click", function()
  {
    openSched(this.value);
  });
    
  var linkParent = gebi('schedLinks');
  linkParent.appendChild(schedButton);
  linkParent.classList.remove("noScheds");
}

function removeExistBtn(coursename)
{
  var schedules = gebi("schedLinks").getElementsByTagName("button");
  for (var i = 0; i < schedules.length; i++)
  {
    if (schedules[i].value == coursename)
    {
      schedules[i].parentNode.removeChild(schedules[i]);
    }
  }
}

function openSched(btnVal)
{
  var course = btnVal.trim();
  var href = "sched_files/" + course + ".html";
  window.open(href, "_blank");
}

