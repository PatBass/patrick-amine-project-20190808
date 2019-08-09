var sp = sp = sp || {};

sp.vdd = function () {
    var vdd = {
        'geoSearchSuccess': function (data, status, xhr) {
            console.log("success" + data);
            $("#selectCities").remove();
            $("#geoSearchForm").children("#messageGeoSearchForm").remove();
            $("#resultSearchGeo").html(data);
        },
        'geoSearchError': function (data, status, error) {
            if (data.status == 400) {
                $("#searchGeo").replaceWith(data.responseText);
                $("#resultSearchGeo").html("");
            } else {
                $("#messageGeoSearchForm").html("<p>Une erreur est survenue pendant la recherche, merci de réessayer.</p>")
            }
            sp.global.postFormRetrieveHtml("#geoSearchForm", sp.vdd.geoSearchError, sp.vdd.geoSearchSuccess);
        },
        'loadInfoBulleDefinition': function () {
            $("a[data-definition]").each(function () {
                var id = $(this).data().definition;
                var link = this;
                $.get(sp.global.getSpRootDomain() + "/infobulle/definition/" + id, function (data) {
                    //TODO a voir si on peut simplifier le json
                    var toolTipContent = data.elements[0].elementsTerminaux[0].content;
                    link.title = toolTipContent;
                    sp.global.addTooltip(link, toolTipContent);
                });
            });
        },
        'loadInfoBulleAbreviation': function () {
            $("a[data-sigle]").each(function () {
                var id = $(this).data().sigle;
                var link = this;
                var toolTipContent = $("div[data-sigle=" + id + "]");
                link.title = toolTipContent[0].textContent;
                sp.global.addTooltip(link, toolTipContent);
            });
            $("a[data-acronyme]").each(function () {
                var id = $(this).data().acronyme;
                var link = this;
                var toolTipContent = $("div[data-acronyme=" + id + "]");
                link.title = toolTipContent[0].textContent;
                sp.global.addTooltip(link, toolTipContent);
            });
        }
    }
    return vdd;
}();

/* Initialisation de la page */
$(document).ready(function () {
    $('form[data-application="geoSearchForm"]').each(function () {
        sp.global.postFormRetrieveHtml("#geoSearchForm", sp.vdd.geoSearchError, sp.vdd.geoSearchSuccess)
    });
    sp.vdd.loadInfoBulleAbreviation();
    sp.vdd.loadInfoBulleDefinition();

});
