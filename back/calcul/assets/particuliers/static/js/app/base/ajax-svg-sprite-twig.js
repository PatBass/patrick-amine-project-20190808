/*!
ajax-svg-sprite
*/
// http://css-tricks.com/ajaxing-svg-sprite/

  var ajax = new XMLHttpRequest();
  ajax.open("GET", "../bundles/eurekag6k/particuliers/static/img/ico-sp.svg", true);
  ajax.send();
  ajax.onload = function(e) {
    var div = document.createElement("div");
    div.innerHTML = ajax.responseText;
    document.body.insertBefore(div, document.body.childNodes[0]);
  }

