var sp = sp = sp || {};
"use strict";

sp.annuaire = (function () {
    var currentLayer;
    var needUpdateCoordinates = false;
    var lastUserActionTimeStamp;

    function extractService(service, timestamp) {
        addServicesOnList(service);
        addServicesOnMap(service, timestamp);
    }

    function getTokenForSearchAddress(result) {
        if (!sp.global.isEmpty(result.adresse) && !sp.global.isEmpty(result.codePostal) && !sp.global.isEmpty(result.nomCommune)) {
            return result.adresse + ' ' + result.codePostal + ' ' + result.nomCommune;
        } else {
            return undefined;
        }
    }


    function addServicesOnList(data) {

        var items = [],
            type = data.type,
            libelle = data.libelle;

        var h2 = libelle + " <span>(" + data.count + ")</span>";
        $(".annuaire h2").html(h2).focus();

        $.each(data.results, function (key, val) {
            items.push("<li class='list-tdm'><a href='" + sp.global.rewriteAnnuaireUrl(val["url"]) + "'>" + type + " - " + val["nomCommune"] + " - " + val["nomDepartement"] + " - " + val["codeDepartement"] + "</a></li>");
        });

        $(".annuaire ul.list-tdm").html(items.join(""));
        $(".annuaire div.sr-only").html("<a href='#" + sp.annuaire.currentValue + "'>Retour au bouton radio " + libelle + "</a>");

    }

    function addServicesOnMap(service, timestamp) {
        if (!sp.ign.mapLoaded) {
            return;
        }
        //on a declenché entre temps une autre action
        if (timestamp != lastUserActionTimeStamp) {
            console.log("addServicesOnMap cancelled");
            return;
        }
        var points = extractPointfromResults(service.results);
        if (needUpdateCoordinates) {
            sp.ign.getCoordinatesFromAddress(points, function (pointsWithCoordinates) {
                if (timestamp != lastUserActionTimeStamp) {
                    console.log("putPointsOnLayerCb cancelled");
                    return;
                }
                putPointsOnLayerCb(pointsWithCoordinates, service.type);
            });
        } else {
            putPointsOnLayerCb(points, service.type);
        }
    }

    function extractPointfromResults(results) {
        var points = [];
        var resultLength = results.length;
        for (var i = 0; i < resultLength; i++) {
            var point = {
                longitude: results[i].longitude,
                latitude: results[i].latitude,
                description: results[i].adresse + '\n' + results[i].nomCommune + ' ' + results[i].codePostal,
                tokenForSearchOnAddress: getTokenForSearchAddress(results[i]),
                name: results[i].nom,
                url: sp.global.rewriteAnnuaireUrl(results[i].url)
            };
            if ((point.latitude === undefined || point.longitude === undefined) && point.tokenForSearchOnAddress != undefined) {
                needUpdateCoordinates = true;
            }
            if (point.tokenForSearchOnAddress != undefined) {
                points.push(point);
            }
        }
        return points;
    }

    function putPointsOnLayerCb(points, type) {
        if (currentLayer != undefined) {
            sp.ign.removeLayer(currentLayer.name);
        }
        currentLayer = sp.ign.buildVectorLayer(sp.ign.buildFeaturesFromPointArray(points), type, false);
        sp.ign.addVectorLayer(currentLayer, true);
    }


    var annuaire = {

        'initServiceFilter': function () {
            //var filtreForm = $("#filtreServiceForm");

            var collFilterForm = $("form.filter");

            collFilterForm.submit(function (event) {
                event.preventDefault();
                var value = $(this).find(":checked").val();

                if (undefined !== value) {
                    sp.annuaire.currentValue = value;
                    var url = $(this).attr("action") + "/" + value;
                    var timestamp = Date.now();
                    $.ajax({
                        dataType: "json",
                        url: url,
                        cache: false
                    }).done(function (service) {
                        extractService(service, timestamp);
                    });
                    lastUserActionTimeStamp = timestamp;
                    window.location.hash = "service-" + value;
                }
            });
            if (window.location.hash) {
                var inputs = $(collFilterForm).find("input[value=" + window.location.hash.split("-")[1] + "]");
                if (inputs) {
                    $(inputs[0]).prop('checked', true).change();
                    $(inputs[0].form).submit();
                }
            }
        }
    }

    return annuaire;
})();
