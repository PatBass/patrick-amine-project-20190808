/*  Mécanismes js présent sur le gabarit de type fiche */
"use strict";

/* Mécanisme tout replier/ tout déplier
/* Deux cas à gérer :
/* - Un seul mécanisme présent dans la page
/* - Plusieurs mécanismes présents dans la page dans le cas d'onglets
 */

var uncollapseAllButton = $(".btn-fold"),
    collapseAllButton = $(".btn-unfold");

uncollapseAllButton.click(function(event){

  var scope = ( $( this ).parents().is("[role='tabpanel']") ) ? $( this ).parents("[role='tabpanel']") : $( this ).parents("article");
  scope.find(".bloc-principal .btn-collapse:not(.collapsed)").click();

});

collapseAllButton.click(function(event){

  var scope = ( $( this ).parents().is("[role='tabpanel']") ) ? $( this ).parents("[role='tabpanel']") : $( this ).parents("article");
  scope.find(".bloc-principal .btn-collapse.collapsed").click();

});


/* Mécanisme d'entonnoir pour le choix d'une démarche */

var choiceTree = $( "div.choice-tree" );
//console.log($( "div.choice-tree" )[0].innerHTML);
var count = 1;
choiceTree.find( ".choice-tree-item-content" ).addClass("radio").hide();

if( choiceTree.children().is(".choice-tree-item") ) {
  var html = choiceTree.html();
  //console.log(html);
  choiceTree.children().remove();
  choiceTree.wrap("<div class='choice-tree'><form action=''><fieldset><legend class='sr-only'>Préciser votre démarche</legend>" + html + "</fieldset></form></div>");
  if ( !$( "div.choice-tree" ).find("fieldset:last-child").children().is(".choice-tree-item-demarche") ) {
    $( "div.choice-tree" ).find("fieldset:last-child").append("<div class='choice-tree-item-demarche'></div>")
  }
}

$.each( $(".choice-tree .choice-tree-item"), function(index, elt) {

  var currentElt = $(elt).children("h4");
  var text1 = currentElt.text();
  currentElt.replaceWith( "<input type='radio' id='choice-tree" + index + count + "' class='input-choice' name='level" + count + "' ><label for='choice-tree" + index + count + "'>" + text1 + "</label>" );

  if( $( elt ).children().is(".choice-tree-item") ) {

    $.each( $( elt ).children(".choice-tree-item"), function(index, elt) {

      count += 1;

      var currentElt = $(elt).children("h5");
      var text2 = currentElt.text();
      currentElt.replaceWith( "<input type='radio' id='choice-tree" + index + count + "' class='input-choice' name='level" + count + "' ><label for='choice-tree" + index + count + "'>" + text2 + "</label>" );

      if( $( elt ).children().is(".choice-tree-item") ) {

        $.each( $( elt ).children(".choice-tree-item"), function(index, elt) {
          count += 1;
          var currentElt = $(elt).children("h6");
          var text3 = currentElt.text();
          currentElt.replaceWith( "<input type='radio' id='choice-tree" + index + count + "' class='input-choice' name='level" + count + "' ><label for='choice-tree" + index + count + "'>" + text3 + "</label>" );
          count -= 1;

          $(elt).addClass("radio");

        });

        $( elt ).children(".choice-tree-item").wrapAll( "<fieldset />");
        $( elt ).children("fieldset").prepend("<legend class='sr-only'>" + text2 + "</legend>");

      }

      count -= 1;

      $(elt).addClass("radio");

    });

    $( elt ).children(".choice-tree-item").wrapAll( "<fieldset />");
    $( elt ).children("fieldset").prepend("<legend class='sr-only'>" + text1 + "</legend>");

  }
  $(elt).addClass("radio");

});

$( ".choice-tree fieldset:nth-child(n+2)" ).hide();

$(".input-choice").on("click", function() {
  var that = $( this ),
      choiceTreeItemDemarche = that.parents("form").find(".choice-tree-item-demarche"),
      html = "";

  // Masquage par défaut des descendants et de la zone de démarche
  // et décochage des boutons radio
  that.parent().siblings( "div" ).find( "fieldset" ).hide();
  that.parent().siblings( "div" ).find( "[type='radio']" ).prop( "checked", false );
  choiceTreeItemDemarche.hide();

  if ( that.siblings().is(".choice-tree-item-content") ) {
    html = that.siblings( ".choice-tree-item-content" ).html();

    // Affichage du contenu de la démarche
    choiceTreeItemDemarche.html( html );

    // Ajout des boutons d'impression et d'affichage du formulaire
    if ( !choiceTreeItemDemarche.children().is(".tool-fiche") ) {
      choiceTreeItemDemarche.append("<p class='tool-fiche'><button class='btn btn-print'>Imprimer cette section</button><button class='btn btn-mail collapsed' data-toggle='collapse' data-target='#envoi-mail'>Envoyer par mail</button></p>");
    }

    choiceTreeItemDemarche.addClass("choice-tree-item-content fiche-item-demarche");
    choiceTreeItemDemarche.attr("aria-live", "polite");
    choiceTreeItemDemarche.show();

  } else {

    // Affichage des descendants directs et de la zone de démarche
    that.siblings( "fieldset" ).show(200);

  }

});


/* Mécanisme Afficher la suite */

$.each( $( ".block-show-more" ), function(index, elt) {

  var currentElt = $( elt ),
      selector = ".fiche-title-1, .fiche-title-2, .fiche-title-3, .format",
      title;

  if( currentElt.children().is( selector) ) {
    //console.log(currentElt.children(selector)[0].tagName);
    title = currentElt.children(selector)[0].tagName;
    if ( currentElt.find(title).siblings("p").length > 1 ) {
      currentElt.find(title).siblings("p:not(:first-child):not(" + title + "+p)").hide();
      currentElt.find(title).siblings("div.demarche-button").hide();
    }
  } else {

  }

  currentElt.children(".demarche-button").before("<div class='show-button'><button class='btn btn-show btn-block btn-default'>Afficher la suite</button></div>")

  //if( $( elt ).children().is(".choice-tree-item") ) {
  currentElt.on("click", ".btn-show", function() {
    console.log($(this));
    $(this).parents(".block-show-more").children(selector).siblings("p, div.demarche-button").show(200);
    currentElt.children(".demarche-button").after("<p class='tool-fiche'><button class='btn btn-fold'>Replier</button></p>");
    $(this).hide();
  });

  currentElt.on("click", ".tool-fiche > .btn-fold", function() {
    $(this).parents(".block-show-more").children(selector).siblings("p:not(:first-child):not(" + title + "+p), div.demarche-button").hide(200);
    $(".btn-show").show();
    $(this).hide();
  });

});


/* Gestion de la geopersonnalisation */
var whereButton = $( ".annuaire-where form" ).eq(1),
    url = ["static/js/json/geoperso-multi.json","static/js/json/geoperso-solo.json"],
    html;

whereButton.on("submit", function(event) {
  event.preventDefault();
  var that = $(this);
  var val = $(this).find("input").val();
  console.log(val);

  if ( val == "multi" ) {

    $.getJSON( url[0], function( data ) {
      console.log(data);

      var html = "<div class='form-group'>";
      html += "<label for='geoperso-confirm-select'>Plusieurs réponses existent, veuillez préciser</label>"
      html += "<select id='geoperso-confirm-select' class='form-control' size='3'>"; // Attention multiplicité possible
      $.each(data, function(key,obj) {
        html += "<option value='" + obj.insee + "'>" + obj.nom + "</option>";
      });
      html += "</select>";
      /*html += "<button class='btn btn-primary'>Choisir</button></div>";*/
      console.log(html);

      $( ".geoperso-confirm" ).html( html );
      $( "#geoperso-confirm-select" ).change( function() {
        //todo
      });


    });

  } else {

    $.getJSON( url[1], function( data ) {

      console.log( data);
      // Récupérer dans le contexte s'il s'agit de où s'informer / où s'adresser
      var scope = "informer", // "adresser"
          divToCollapse = [],
          obj = data[0];


      html = "<p>Vous avez saisi : <strong>" + obj.nom + " (" + obj.insee + ")</strong></p>";
      $( ".geoperso-confirm" ).html( html );

      html = "<h3 class='where-section-title'>***Préfecture [JSON en défaut]</h3>";
      html += "<p>***Paragraphe [JSON en défaut]</p>";
      $( ".where-section" ).html( html );

      html = "<div itemscope itemtype='http://schema.org/GovernmentOrganization'>";
      // Itération sur les organismes
      $.each(obj.organismes, function(index, organisme) {
        html += "<h4 class='where-title-1'><button aria-expanded='false' aria-controls='where-" + index + "' class='btn btn-collapse collapsed' data-target='#where-" + index + "' data-toggle='collapse'><span class='address-name'>" + organisme.nom + "</span></button></h4>";
        //  id='ui-collapse-" + index + "' role='button'
        html += "<div id='where-" + index + "'>"
        //  aria-hidden='true' aria-labelledby='ui-collapse-" + index + "'  class='collapse'

        // Pour m'y rendre
        html += "<p class='title-contact'><span class='icon icon-pieton' aria-hidden='true'></span><span class='title-contact-text'>Pour m'y rendre</span></p>";
        html += "<div itemprop='address' itemscope itemtype='http://schema.org/PostalAddress'><p>";
        html += "<span itemprop='streetAddress'>" + organisme.adresseDTOs[0].lignes[0] + "</span><br>";
        html += "<span itemprop='postalCode'>" + organisme.adresseDTOs[0].codePostal + " </span>";
        html += "<span itemprop='addressLocality'>" + organisme.adresseDTOs[0].nom + "</span><br>";
        html += "</p></div>";

        // Horaires d'ouverture
        html += "<p class='title-contact'><span class='icon icon-horaires' aria-hidden='true'></span><span class='title-contact-text'>Horaires d'ouverture</span></p>";
        html += "<div>";
        $.each(organisme.openingDaysDTO, function(index, openingDayDTO) {
          if (openingDayDTO.from == openingDayDTO.to) {
            html += "<p>Le " + openingDayDTO.from + " : ";
          } else {
            html += "<p>Du " + openingDayDTO.from + " au " + openingDayDTO.to + " : ";
          }
          html += "de " + organisme.openingDaysDTO[0].openingHours[0].from.replace(":","h") + " à " + organisme.openingDaysDTO[0].openingHours[0].to.replace(":","h");
          if (organisme.openingDaysDTO[0].openingHours.length > 1) {
            html += " et de " + organisme.openingDaysDTO[0].openingHours[1].from.replace(":","h") + " à " + organisme.openingDaysDTO[0].openingHours[1].to.replace(":","h");
          }
          html += "<br>";
          if (undefined !== organisme.openingDaysDTO.note) {
            html += "<span>("+ organisme.openingDaysDTO.note +")</span>";
          }
          html += "</p>";
        });
        html += "</div>";

        // Pour écrire
        html += "<p class='title-contact'><span class='icon icon-ecrire' aria-hidden='true'></span><span class='title-contact-text'>Pour écrire</span></p>";
        html += "<div itemprop='address' itemscope itemtype='http://schema.org/PostalAddress'><p>";
        $.each(organisme.adresseDTOs, function(index, adresseDTO) {
          var cs = "",
              nomService;
          if (adresseDTO.lignes.length > 2) {
            nomService = adresseDTO.lignes[1];
            cs = adresseDTO.lignes[2];
          } else {
            nomService = adresseDTO.lignes[1];
          }
          html += "<span class='address-name'>" + nomService + " </span><br>";
          html += "<span itemprop='streetAddress'>" + adresseDTO.lignes[0] + "</span><br>";
          html += "<span>" + " ??? CS71046 Inconsistance dans le JSON ! " + " </span>";
          html += "<span itemprop='postOfficeBoxNumber'>" + adresseDTO.codePostal + " " + adresseDTO.nom + "</span><br>";
        });
        html += "</p></div>";
        var coordonnees = organisme.coordonneesNumDTO;
        // Courriel
        html += "<p>";
        html += "<span class='contact-detail'>Courriels *** multiplicité attendue ? : </span>";
        html += "<span itemprop='email'>" + coordonnees.email + "*** Que faire si email null ? *** format : nom [ à ] domaine</span>";
        html += " - <a href='' class='send-mail'>envoyer un message</a>";
        html += "</p>";
        // Télécopie
        html += "<p>";
        html += "<span class='contact-detail'>Télécopie : </span>";
        html += "<span itemprop='faxNumber'>" + coordonnees.fax + "*** Que faire si fax null ?</span>";
        html += "</p>";
        // Téléphone
        html += "<p class='title-contact'><span class='icon icon-phone' aria-hidden='true'></span><span class='title-contact-text'>Téléphone</span></p>";
        html += "<p>";
        html += "<span itemprop='telephone'>" + coordonnees.telephone + "*** Que faire si telephone null ?</span><br>";
        html += "<span>(" + coordonnees.coutTelephone + "*** Que faire si coutTelephone null ?)</span>";
        html += "</p>";

        // Date de vérification
        html += "<p class='date'>Vérifié le " + organisme.dateMiseAjour + " - par " + organisme.editeurSource + "</p>";

        // Itération sur serviceDTOs
        $.each(organisme.serviceDTOs, function(index, serviceDTO) {
          html += "<div class='where-related' itemscope itemtype='http://schema.org/GovernmentOrganization'>";
          html += "<h5 class='where-title-1'><span class='address-name'>" + serviceDTO.nom + "</span></h5>";
          html += "<p>*** Que faire du commentaire si commentaire il y a ? </p>";

          // Pour m'y rendre serviceDTOs
          html += "<p class='title-contact'><span class='icon icon-pieton' aria-hidden='true'></span><span class='title-contact-text'>Pour m'y rendre</span></p>";
          // Itération dans pointAcceuilPhysiqueDTO.adresseDTOList
          $.each(serviceDTO.pointAcceuilPhysiqueDTO.adresseDTOList, function(index, adresseDTO) {
            html += "<div itemprop='address' itemscope itemtype='http://schema.org/PostalAddress'><p>";
            html += "<span itemprop='streetAddress'>" + adresseDTO.lignes[0] + "</span><br>";
            html += "<span itemprop='postalCode'>" + adresseDTO.codePostal + " </span>";
            html += "<span itemprop='addressLocality'>" + adresseDTO.nom + "</span><br>";
            html += "</p></div>";
          });

          // Horaires d'ouverture serviceDTOs
          html += "<p class='title-contact'><span class='icon icon-horaires' aria-hidden='true'></span><span class='title-contact-text'>Horaires d'ouverture</span></p>";
          // Itération dans pointAcceuilPhysiqueDTO.openingDaysDTOs
          //$.each(serviceDTO.pointAcceuilPhysiqueDTO.openingDaysDTOs, function(index, adresseDTO) {
            html += "<div>";
            $.each(serviceDTO.pointAcceuilPhysiqueDTO.openingDaysDTOs, function(index, openingDayDTO) {
              if (openingDayDTO.from == openingDayDTO.to) {
                html += "<p>Le " + openingDayDTO.from + " : ";
              } else {
                html += "<p>Du " + openingDayDTO.from + " au " + openingDayDTO.to + " : ";
              }
              html += "de " + organisme.openingDaysDTO[0].openingHours[0].from.replace(":","h") + " à " + organisme.openingDaysDTO[0].openingHours[0].to.replace(":","h");
              if (organisme.openingDaysDTO[0].openingHours.length > 1) {
                html += " et de " + organisme.openingDaysDTO[0].openingHours[1].from.replace(":","h") + " à " + organisme.openingDaysDTO[0].openingHours[1].to.replace(":","h");
              }
              html += "<br>";
              if (undefined !== organisme.openingDaysDTO.note) {
                html += "<span>("+ organisme.openingDaysDTO.note +")</span>";
              }
              html += "</p>";
            });
          //});
          html += "</div>";

          html += "<p>*** Que faire des coordonnées : tel, fax, courriel ?</p>";


          html += "</div>"; // fin serviceDTO
        });

        html += "</div>"; // fin div.where-

        divToCollapse.push("#where-" + index);
      });

      html += "</div>";
      $( ".annuaire" ).html( html );

      $(divToCollapse.join(",")).collapse();
    });

  }
});
