/**
 * @author Jean-Pierre Gay <jean-pierre.gay@alterway.fr>
 * @param  options sous la forme d'un objet {}
 *
 * Cette extension apporte deux modifications au widget autocomplete :
 * 1) Etend le prototype pour gérer la localisation française du message introductif
 * 2) Surcharge le widget  pour permettre l'ajout de liens directs en plus
 *    des éléments normaux d'autocomplétion (utiliser alors spAutocomplete)
 *
 * Exemple : $( <input> ).spAutocomplete({
 *              source: "static/js/json/autocomplete-light.json?nbSuggest=5&nbAcces=3",
 *              minLength: 3
 *           });
 * Les paramètres nbSuggest et nbAcces sont gérés côté serveur pour limiter le nombre de résultats
 * à renvoyer dans le fichier JSON
 */

(function($) {
  'use strict';

  var proto = $.ui.autocomplete.prototype,
      response = proto.__response

  $.extend( proto, {
    options: {
      accesDirects: {"fiches_pratiques": "Fiches pratiques", "slf": "Services en lignes et formulaires", "annuaire": "Annuaire", "actualites": "Actualités" },
      messages: {
        noResults: "Aucun résultat de recherche.",
        results: function( autocompleteAmount, accesDirectsAmount ) {
          accesDirectsAmount = accesDirectsAmount || 0;
          var amount = autocompleteAmount + accesDirectsAmount;
          return amount + ( amount > 1 ? " résultats sont disponibles" : " résultat est disponible" ) + ( accesDirectsAmount > 1 ? " dont " + accesDirectsAmount + " liens d'accès directs" : "" ) +
          ", utilisez les touches haut et bas pour naviguer.";
        }
      },
      position: { my: "left top", at: "left bottom", collision: "none" }
    },
    __response: function( content ) {

      var accesDirectsLength = 0,
          message;

      if (content.acces_direct != undefined) {
        $.each( content.acces_direct, function( index, value ) {
          accesDirectsLength += value.length;
        });
      }

      response.apply( this, arguments );

      if ( this.options.disabled || this.cancelSearch ) {
        return;
      }
      if (content.autocomplete != undefined || content.acces_direct != undefined) {
        if ( content && ( content.autocomplete.length || content.acces_direct.length ) ) {
          message = this.options.messages.results( content.autocomplete.length, accesDirectsLength );
        } else {
          message = this.options.messages.noResults;
        }
      } else {
        message = (content.length) ? this.options.messages.results(content.length) : this.options.messages.noResults;
      }
      this.liveRegion.children().hide();
      $( "<div>" ).text( message ).appendTo( this.liveRegion );
    }
  });

  $.widget("aw.spAutocomplete", $.ui.autocomplete, {
    options: {
      accesDirects: {"fiches_pratiques": "Fiches pratiques", "slf": "Services en lignes et formulaires", "annuaire": "Annuaire", "actualites": "Actualités" },
      messages: {
        noResults: "Aucun résultat de recherche.",
        results: function( autocompleteAmount, accesDirectsAmount ) {
          var amount = autocompleteAmount + accesDirectsAmount;
          return amount + ( amount > 1 ? " résultats sont disponibles" : " résultat est disponible" ) + ( accesDirectsAmount > 1 ? " dont " + accesDirectsAmount + "liens d'accès directs" : "" ) +
          ", utilisez les touches haut et bas pour naviguer.";
        }
      }
    },
    _renderMenu: function(ul, items) {
      var label,
        that = this,
        tmpItems = [];

      /* Traitement de "autocomplete" */
      $.each( items[0], function( index, value ) {
        tmpItems.push({ label: value, value: value });
      });
      tmpItems.push("");

      /* Traitement de "acces_direct" */
      var hasResults = false;
      $.each( items[1], function( key, value ) {
        if (0 != value.length) {
          hasResults = true;
        }
      });
      if (hasResults === true) {
        label = "<h1 id=\"acces\">Accès directs</h1>";
        tmpItems.push({ label: label, value: that.term });
      }

      $.each( items[1], function( key, value ) {
        if (0 != value.length) {
          label = "<h2 id=\"" + key + "\" tabindex=\"-1\">" + that.options.accesDirects[key] + "</h2>";
          tmpItems.push({ label: label, value: that.term });
          $.each( value, function( index, value ) {
            label = "<a href=\"" + value["url"] + "\">" + value["title"] + "</a>";
            tmpItems.push({ label: label, value: that.term });
          });
          tmpItems.push("");
        }
      });
      tmpItems.pop();

      items = tmpItems;
      that._super(ul, items);

      $.each( $("ul.ui-autocomplete").children(), function( index, obj ) {
        if (obj.childNodes[0] != undefined && obj.childNodes[0].nodeType == 1 ) {
          var ariaLabel = $(obj).first().text();
          if (obj.childNodes[0].tagName != "A") {
            $(obj).attr( "aria-label" , ariaLabel );
            $(obj).addClass("ui-state-disabled");
          } else {
            $(obj).attr( "aria-label" , "Lien : " + ariaLabel );
            $(obj).addClass("ui-acces-item");
          }
        }
      });
    },
    _renderItem: function( ul, item ) {
      if (item == "") {
        return $( "<li role='presentation'>" ).html( item.label ).appendTo( ul );
      } else {
        return $( "<li>" ).html( item.label ).appendTo( ul );
      }
    }
  });

})(jQuery);
