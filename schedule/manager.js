/*
  Author: Dan Coleman
  JS for manager.php
*/

"use strict";

/*
** Returns an element by a given ID.
*/
function gebi(item)
{
  return document.getElementById(item);
}

window.onload = function() 
{
  this.placeButtons();
  gebi('clear').onclick = resetData;
  gebi('adminpswd').onclick = passcodeDialog;
  gebi('editcontact').onclick = contactDialog;
}

/*
** Clears all data from system.
*/
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

/*
** Opens window to edit admin passcode.
*/
function passcodeDialog()
{
  var h = 275;
  var w = 375;
  var left = (window.screen.width/2) - (w/2);
  var top = (window.screen.height/3) - (h/2);
  var pscdWindow = open("changepasscode.php", "_blank", "height="+h+
    ",width="+w+",location=no,menubar=no,scrollbars=no,status=no,titlebar=no"+
    ",toolbar=no,left="+left+",top="+top);
}

/*
** Opens window to edit main contact info.
*/
function contactDialog()
{
  // height and width
  var h = 275;
  var w = 450;

  // center in screen
  var left = (window.screen.width/2) - (w/2);
  var top = (window.screen.height/3) - (h/2);
  var pscdWindow = open("editcontact.php", "_blank", "height="+h+
    ",width="+w+",location=no,menubar=no,scrollbars=no,status=no,titlebar=no"+
    ",toolbar=no,left="+left+",top="+top);
}

/*
** Formats and inserts buttons into the page.
*/
function placeButtons()
{
  writeCreatorButtons();
  placeCurrSchedLinks();
}

/*
** Inserts links to schedules that have already been made.
*/
function placeCurrSchedLinks()
{
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "currschedules.php", false);
  xhr.send();
  var schedules = JSON.parse(xhr.responseText);

  if (schedules != "")
  {
    // if an array
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

/*
** Prompts user to verify the rewrite request.
*/
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

/*
** Inserts buttons used to create schedules.
*/
function writeCreatorButtons(courses)
{
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "getcourses.php", false);
  xhr.send();
  var courses = JSON.parse(xhr.responseText);
  var btnParent = document.getElementById("schedules");

  if (courses.length > 0)
  {
    // enable display
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

/*
** Assign correct disabled status to element.
** Used to verify all tutors for a given course have 
** completed their surveys.
*/
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

/*
** Runs process to make schedule.
*/
function makeSchedule(course) 
{
  gebi(course).disabled = true;
  if (toCSV(course))
  {
    writeSchedule(course);
  }
  gebi(course).disabled = false;
}

/*
** Converts availability times to formatted CSV files.
*/
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

/*
** Writes schedule output file.
*/
function writeSchedule(course) 
{
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "sched_writer.php", false);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  var param = "course=" + course;
  xhr.send(param);
  var response = xhr.responseText.trim();

  if (response == "written")
  {
    // link to open schedule for 'course'
    writeSchedLink(course);
  }
}

/*
** Inserts the button to access the schedule for 'course'.
*/
function writeSchedLink(course)
{
  // ensures no buttons for 'course' exist
  removeExistBtn(course);

  // creates and adds button
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
  // enable display
  linkParent.classList.remove("noScheds");
}

/*
** Removes link to schedule for 'course'.
*/
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

/*
** Opens the selected schedule in a new window element.
*/
function openSched(btnVal)
{
  var course = btnVal.trim();
  var href = "sched_files/" + course + ".html";
  window.open(href, "_blank");
}

