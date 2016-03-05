// Dan Coleman

"use strict";

function gebi(item)
{
  return document.getElementById(item);
}

window.onload = function()
{
  //gebi("sendsurvey").onclick = verifySend;
}

function verifySend()
{
  var check = confirm("Are you sure the information is complete?")
  if (check == true)
  {
    gebi("addtutors").submit();
  }
}