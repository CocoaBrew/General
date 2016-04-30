/*
  Author: Dan Coleman
  JS for editcontact.php
*/

"use strict";

/*
** Returns an element by a given ID.
*/
function gebi(itemId)
{
  return document.getElementById(itemId);
}

window.onload = function()
{
  gebi('noeditcontact').onclick = endChange;
}

/*
** Closes window when "Cancel" is clicked.
*/
function endChange()
{
  window.close();
}

