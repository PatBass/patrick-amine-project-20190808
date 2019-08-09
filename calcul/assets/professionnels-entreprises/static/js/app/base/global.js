var sp = sp = sp || {};
"use strict";
sp.global = function () {
  var global = {
    'postForm': function (id, errorCb, successCb, returnDataType) {
      $(id).submit(function (e) {
        e.preventDefault();
        var form = this;
        //TODO form validation;
        $.ajax({
          url: form.action,
          type: form.method,
          dataType: returnDataType,
          data: $(form).serialize(),
          success: function (result, status, xhr) {
            successCb(result, status, xhr);
          },
          error: function (result, status, error) {
            errorCb(result, status, error);
          }
        });

      });
    },

    "postFormRetrieveHtml": function (id, errorCb, successCb) {
      this.postForm(id, errorCb, successCb, 'html')
    },

    'postFormRetrieveJson': function (id, errorCb, successCb) {
      this.postForm(id, errorCb, successCb, 'json')
    },

    'initAjaxHeader': function () {
      var token = $("meta[name='_csrf']").attr("content");
      var header = $("meta[name='_csrf_header']").attr("content");
      $.ajaxSetup({
        beforeSend: function (xhr, settings) {
          if (settings.type != "GET" && settings.type != "HEAD") {
            xhr.setRequestHeader(header, token);
          }
        }
      });
    },
    'formatErrorMessages': function (listOfMessages) {
      var formattedMessages = "<ul>";
      $.each(listOfMessages, function (index, value) {
        formattedMessages += "<li>" + value + "</li>";
      });
      formattedMessages += "</ul>";
      return $.parseHTML(formattedMessages);
    },
    'getSpRootDomain': function () {
      var spRoot = $("meta[name=sp_root]").attr("content");
      return spRoot != undefined ? spRoot : "";
    },
    'getResourcesPath': function () {
      var resourcesPath = $("meta[name=resources_path]").attr("content");
      return resourcesPath != undefined ? resourcesPath : "/resources";
    },
    'getAnnuaireRootDomain': function () {
      var annuaireRoot = $("meta[name=annuaire_root]").attr("content");

      return annuaireRoot != undefined ? annuaireRoot : "";
    },
    'isEmpty': function (str) {
      return (!str || 0 === str.length);
    },
    'rewriteAnnuaireUrl': function (url) {
      if (window.location.pathname.contains("/annuaire/")) {
        return "/annuaire" + url;
      } else {
        return url;
      }
    },
    'transformlineBreakToHtml': function (str) {
      return str.replace(/(?:\r\n|\r|\n)/g, '<br/>');
    },
    'addTooltip': function (element, content) {
      $(element).tooltip({
        content: function () {
          return content;
        }, open: function (event, ui) {
          ui.tooltip.css("max-width", "400px");
        }
      });
    }
  };
  return global;

}();

/* Initialisation de la page */
$(document).ready(function () {
  sp.global.initAjaxHeader();
});
