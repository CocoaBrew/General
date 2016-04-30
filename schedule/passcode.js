/*
  Author: Dan Coleman
  JS for changepasscode.php
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
  gebi('nochangepscd').onclick = endChange;
  gebi('noeditcontact').onclick = endChange;
  gebi('makechange').disabled = true;
  gebi('pscd2').onkeyup = verifyCodesSame;
}

/*
** Closes window when "Cancel" is clicked.
*/
function endChange()
{
  window.close();
}

/*
** Ensures the two new passcodes are equal.
*/
function verifyCodesSame()
{
  gebi('makechange').disabled = true;
  var codeOne = gebi('pscd').value;
  var codeTwo = gebi('pscd2').value;
  if ((codeOne == codeTwo) && codeOne != "")
  {
    gebi('makechange').disabled = false;
  }
}
