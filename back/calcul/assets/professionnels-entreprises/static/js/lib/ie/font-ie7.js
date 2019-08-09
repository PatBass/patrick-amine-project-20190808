/* To avoid CSS expressions while still supporting IE 7 and IE 6, use this script */
/* The script tag referencing this file must be placed before the ending body tag. */

/* Use conditional comments in order to target IE 7 and older:
  <!--[if lt IE 8]><!-->
  <script src="ie7/ie7.js"></script>
  <!--<![endif]-->
*/

(function() {
  function addIcon(el, entity) {
    var html = el.innerHTML;
    el.innerHTML = '<span style="font-family: \'fonticon\'">' + entity + '</span>' + html;
  }
  var icons = {
    'icon-external-link': '&#xe60c;',
    'icon-download': '&#xe60d;',
    'icon-comment': '&#xe60b;',
    'icon-chevron-up': '&#xe60a;',
    'icon-chevron-down': '&#xe608;',
    'icon-chevron-back': '&#xe609;',
    'icon-chevron': '&#xe607;',
    'icon-search': '&#xe600;',
    'icon-twitter': '&#xe601;',
    'icon-facebook': '&#xe602;',
    'icon-print': '&#xe605;',
    'icon-mail': '&#xe604;',
    'icon-up': '&#xe60c;',
    'icon-help': '&#xe603;',
    'icon-close': '&#xe606;',
    '0': 0
    },
    els = document.getElementsByTagName('*'),
    i, c, el;
  for (i = 0; ; i += 1) {
    el = els[i];
    if(!el) {
      break;
    }
    c = el.className;
    c = c.match(/icon-[^\s'"]+/);
    if (c && icons[c[0]]) {
      addIcon(el, icons[c[0]]);
    }
  }
}());
