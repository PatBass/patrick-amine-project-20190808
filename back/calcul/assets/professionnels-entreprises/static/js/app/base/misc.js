// misc

$(document).ready(function(){

  $('.prevent-default').click(function (e) {
    e.preventDefault()
  });


/*!
add btn print
*/
// dans .toolbar-share > .btn-print

  $('.toolbar-share ul').prepend('<li><button class="btn-print" title="Imprimer"><span aria-hidden="true" class="icon icon-print"></span><span class="blank">Imprimer</span></button></li>\n');

  var print = $('.toolbar-share .btn-print');

  print.click(function() {
    window.print();
  });

  // audio player
  if ($('audio').length) {
    $('audio').acornMediaPlayer({
      theme: 'darkglass',
      nativeSliders: 'false',
      volumeSlider: 'horizontal',
      tooltipsOn: 'false',
    });
  }
});


// nav main, le menu se referme lorsque l'on clique en dehors de celui ci
$(document).click(function(event){

  var innav = false;
  var element = event.target;
  var parent = element;
  while (parent) {
    if (parent.nodeType == 1 && parent.getAttribute('id') == 'nav-main') {
      innav = true;
      break;
    }
    parent = parent.parentNode;
  }
  if (!innav) {
   var navitem = $('.nav-main-item[aria-expanded="true"]');
   if (navitem) {
    navitem.click();
   }
  }

});

// activation de jquery tooltip
// $(function() {
  // $(document).tooltip();
// });
