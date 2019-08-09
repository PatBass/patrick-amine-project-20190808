var fconnect = {
  tracesUrl: '/traces'
};

(function init() {
  initCurrentHostnameSource();
  includeFCCss();
  var fconnectHistory = document.getElementById('fconnect-history');
//      if (fconnectProfile) {
//        var fcLogoutUrl = fconnectProfile.getAttribute('data-fc-logout-url');
//        var access = createFCAccessElement(fcLogoutUrl);
//        fconnectProfile.parentNode.appendChild(access);
//        fconnectProfile.onclick = toogleElement.bind(access);
//      }
  fconnectHistory.parentNode.appendChild(createHistoryLink());
  fconnectHistory.remove();
})();

function initCurrentHostnameSource() {
  var currentScript = document.currentScript || (function () {
      var scripts = document.getElementsByTagName('script');
      return scripts[scripts.length - 1];
    })();
  var parseUrl = currentScript.src.split('/');
//      fconnect.currentHost = parseUrl[2];
  fconnect.currentHost = "fcp.integ01.dev-franceconnect.fr";
}

function includeFCCss() {
  var linkCss = document.createElement('link');
  linkCss.rel = 'stylesheet';
  linkCss.href = '//' + fconnect.currentHost + '/stylesheets/franceconnect.css';
  linkCss.type = 'text/css';
  linkCss.media = 'screen';

  document.getElementsByTagName('head')[0].appendChild(linkCss);
}

function toogleElement(event) {
  event.preventDefault();
  if (this.style.display === "block") {
    this.style.display = "none";
  } else {
    this.style.display = "block";
  }
}

function closeFCPopin(event) {
  event.preventDefault();
  document.body.removeChild(fconnect.popin);
}

function openFCPopin() {
  fconnect.popin = document.createElement('div');
  fconnect.popin.id = 'fc-background';

  var closeBtn = createCloseElementBtn();
  var iframe = createFCIframe();

  document.body.appendChild(fconnect.popin);

  fconnect.popin.appendChild(iframe);

  iframe.addEventListener('load', function () {
    fconnect.popin.appendChild(closeBtn);
  });
}

function createCloseElementBtn() {
  var closeBtn = document.createElement('a');
  closeBtn.className = 'fconnect-access-close';
  closeBtn.href = '#';
  closeBtn.onclick = closeFCPopin;
  closeBtn.innerHTML = '&times;';
  return closeBtn;
}

function createFCIframe() {
  var iframe = document.createElement("iframe");
  iframe.setAttribute('id', 'fconnect-iframe');
  iframe.frameBorder = 0;
  iframe.name = 'fconnect-iframe';
  return iframe;
}

function createFCAccessElement(logoutUrl) {
  var access = document.createElement('div');
  access.id = 'fconnect-access';
  access.innerHTML = '<h5>Vous Ãªtes identifiÃ© grÃ¢ce Ã  FranceConnect</h5><a href="">Qu\'est-ce-que France Connect ?</a><hr/>';
  access.appendChild(createHistoryLink());
  access.appendChild(createLogoutElement(logoutUrl));
  return access;
}

function createHistoryLink() {

  var historyLink = document.createElement('a');
  historyLink.className = 'btn btn-lg btn-primary';
  historyLink.target = 'fconnect-iframe';
  historyLink.href = '//' + fconnect.currentHost + fconnect.tracesUrl;
  historyLink.onclick = openFCPopin;
  historyLink.innerHTML = 'Historique des connexions/échanges de données';

  return historyLink;
}

function createLogoutElement(logoutUrl) {
  var elm = document.createElement('div');
  elm.className = 'logout';
  elm.innerHTML = '<a class="btn btn-default" href="' + logoutUrl + '">Se dÃ©connecter</a>'
  return elm;
}
