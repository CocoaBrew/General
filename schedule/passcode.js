// Dan Coleman

"use strict";

function gebi(item)
{
  return document.getElementById(item);
}

window.onload = function()
{
  gebi('nochange').onclick = endChange;
  gebi('makechange').disabled = true;
  gebi('pscd2').onkeyup = verifyCodesSame;
}

function endChange()
{
  window.close();
}

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
