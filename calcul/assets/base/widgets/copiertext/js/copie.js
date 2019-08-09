/**
 * Gère la copie du bloc d'info et l'ouverture et la fermeture des blocs de contenu
 * ========================
 */
function copyClipboard(a, b) {
    document.getElementById(b).style.opacity = "1";
    setTimeout(function () {
        document.getElementById(b).style.opacity = '0';
    }, 7000);

    var elm = document.getElementById(a);
    // for Internet Explorer
    if (document.body.createTextRange) {
        var range = document.body.createTextRange();
        range.moveToElementText(elm);
        range.select();
        document.execCommand("Copy");
    } else if (window.getSelection) {
        // other browsers
        var selection = window.getSelection();
        var range = document.createRange();
        range.selectNodeContents(elm);
        selection.removeAllRanges();
        selection.addRange(range);
        document.execCommand("Copy");
    }


}

function toggle_1a5div(bouton, idtextfocus, text1button, text2button, iddiv1, iddiv2, iddiv3, idmsgchampobligatoire, iddiv4, iddiv5) { // On dÃ©clare la fonction toggle_div qui prend en param le bouton et un id

    var prefixe = "ModelLettre-panel-1-fieldset-";
    var div1 = document.getElementById(prefixe + iddiv1); 
    var div2 = document.getElementById(prefixe + iddiv2);
    var div3 = document.getElementById(prefixe + iddiv3);
    var div4 = document.getElementById(prefixe + iddiv4);
    var div5 = document.getElementById(prefixe + iddiv5);

    //boutton suivant
    var divMsgObligatoir = document.getElementById(idmsgchampobligatoire); //msg obligatoir
    if (div1.style.display == "block") { // Si le div est masquÃ©...
        div1.style.display = "none";
        if (div2 !== null) {
            div2.style.display = "none";
        }
        if (div3 !== null) {
            div3.style.display = "none";
        }
        if (div4 !== null) {
            div4.style.display = "none";
        }
        if (div5 !== null) {
            div5.style.display = "none";
        }
        /* divS.style.display = "none"; */
        divMsgObligatoir.style.display = "none";	// ... on le maque...

        document.getElementById("personnaliser_1").checked = false;
        bouton.innerHTML = text1button.concat(" <span class='glyphicon glyphicon-menu-down'></span>"); // ... et on change le contenu du bouton.
    } else { // S'il est visible...
        div1.style.display = "block"; // ... on l'affiche...
        divMsgObligatoir.style.display = "block"; // ... on l'affiche...
        if (div2 !== null) {
            if ((document.getElementById("afficherGC2").innerHTML) == "2")
                div2.style.display = "block";
        }
        if (div3 !== null) {
            if ((document.getElementById("afficherGC3").innerHTML) == "2")
                div3.style.display = "block";
        }
        if (div4 !== null) {
            if ((document.getElementById("afficherGC4").innerHTML) == "2")
                div4.style.display = "block";
        }
        if (div5 !== null) {
            if ((document.getElementById("afficherGC4").innerHTML) == "2")
                div5.style.display = "block";
        }
        document.getElementById("personnaliser_1").checked = true;
        bouton.innerHTML = text2button.concat(" <span class='glyphicon glyphicon-menu-up'></span>"); // ... et on change le contenu du bouton.
    }

    document.getElementById(idtextfocus).focus();

}

$('#second').fadeOut(1600, function () {
    $('#myDIV').fadeIn(1500);
});