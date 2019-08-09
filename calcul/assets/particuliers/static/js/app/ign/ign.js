var sp = sp = sp || {};
"use strict";
sp.ign = function () {

  var comWithoutContour = ['nouvelle-caledonie', 'polynesie-francaise', 'terres-australes-et-antarctiques-francaises', 'wallis-et-futuna-wallis-futuna-alofi'];

  var interfaceViewer = null;

  function generateControlMap() {
    return [new OpenLayers.Control.Zoom({'position': new OpenLayers.Pixel(10, 10)}), new OpenLayers.Control.PanPanel({'position': new OpenLayers.Pixel(5, 70)}), new OpenLayers.Control.KeyboardDefaults(), new OpenLayers.Control.Navigation()];
  }


  function loadMapIGN(ign, zoomlevel, options) {
    interfaceViewer = Geoportal.load(
      // div's ID:
      ign.divName,
      // API's keys:
      [ign.ignKey],
      {// map's center :
        lon: parseFloat(ign.initPoint.longitude),
        lat: parseFloat(ign.initPoint.latitude)
      },
      //zoom level
      zoomlevel,
      //options
      options);
  }

  function buildHtmlContentPopup(point) {
    var htmlContent = document.createElement('div');
    if (!sp.global.isEmpty(point.name)) {
      var pName = document.createElement('p');
      var name;
      if (!sp.global.isEmpty(point.url)) {
        name = document.createElement('a');
        name.href = point.url;
        name.title = point.name;
        name.appendChild(document.createTextNode(point.name));
      } else {
        name = document.createTextNode(point.name);
      }
      pName.appendChild(name);
      htmlContent.appendChild(pName);
    }
    if (!sp.global.isEmpty(point.description)) {
      var pDescription = document.createElement('p');
      pDescription.innerHTML = sp.global.transformlineBreakToHtml(point.description);
      htmlContent.appendChild(pDescription);
    }
    return htmlContent.innerHTML;
  }

  function chooseMode(enableControl, localisationParam) {
    //TODO recupérer avecMediaQuery.js
    if (SmartPhone.isAny()) {
      $("div#ign").hide();
      $("a#openNativeMap").show();
      setNativeMapHref(localisationParam.initPoint);
    }
    else {
      $("div#ign").show();
      $("a#openNativeMap").hide();
      sp.ign.initIgnLocalisation(enableControl, localisationParam);
    }
  }

  function setNativeMapHref(point) {
    var link = $("a#openNativeMap");
    if (link != undefined) {
      //detection de la plateforme
      if (SmartPhone.isIOS()) {
        link.attr('href', 'maps://maps.apple.com/?q=' + encodeURI(point.description));
      } else if (SmartPhone.isAndroid()) {
        link.attr('href', 'https://map.google.com/?q=' + encodeURI(point.description));
      } else {
        link.attr('href', 'https://map.google.com/?q=' + encodeURI(point.description));
      }
    }
  }

  var popupUtils = {
    'getSafeContentSize': function (size) {
      var new_h;
      var new_w;
      // make sure the popup isn't being sized too large or small.
      // if it is find which of the limits are closer.
      if (size.w > this.maxSize.w || size.w < this.minSize.w) {
        new_w = closestTo(size.w, [this.maxSize.w, this.minSize.w]);
      } else {
        new_w = size.w;
      }
      if (size.h > this.maxSize.h || size.h < this.minSize.h) {
        new_h = closestTo(size.h, [this.maxSize.h, this.minSize.h]);
      } else {
        new_h = size.h;
      }
      // build a new size object with the new_h and new_w vars and return it.
      return new OpenLayers.Size(new_w, new_h);
      function closestTo(number, set) {
        var closest = set[0];
        var prev = Math.abs(set[0] - number);
        for (var i = 1; i < set.length; i++) {
          var diff = Math.abs(set[i] - number);
          if (diff < prev) {
            prev = diff;
            closest = set[i];
          }
        }
        return closest;
      }
    }
  };

  function addPopUpForMarkerOnLayer(layer) {
    var map = layer.map;
    var selectControl = new OpenLayers.Control.SelectFeature(layer,
      {
        onSelect: onPopupFeatureSelect,
        onUnselect: onPopupFeatureUnselect
      });
    map.addControl(selectControl);
    selectControl.activate();

    function onPopupClose(evt) {
      selectControl.unselectAll();
    }

    function onPopupFeatureSelect(feature) {
      if (!sp.global.isEmpty(feature.attributes.htmlContent)) {
        var popup = new OpenLayers.Popup.FramedCloud(feature.attributes.name,
          feature.geometry.getBounds().getCenterLonLat(),
          null, feature.attributes.htmlContent, null, true, onPopupClose);
        popup.panMapIfOutOfView = true;
        popup.autoSize = true;
        popup.getSafeContentSize = popupUtils.getSafeContentSize;
        popup.minSize = new OpenLayers.Size(210, 80);
        popup.maxSize = new OpenLayers.Size(260, 130);
        feature.popup = popup;
        map.addPopup(popup);
      }
    }

    function onPopupFeatureUnselect(feature) {
      map.removePopup(feature.popup);
      feature.popup.destroy();
      feature.popup = null;
    }
  }

  var contourStyleMap = new OpenLayers.StyleMap({
    "default": new OpenLayers.Style({
      'strokeWidth': 2,
      'fillOpacity': '0.1',
      'cursor': 'pointer',
      'strokeColor': '#ff0000',
      'fillColor': '#ff0000'

    }),
    "select": new OpenLayers.Style({
      'strokeWidth': 3,
      'fillOpacity': '0.3',
      'fillColor': '#ff0000'
    })
  });
  var markerStyleMap = new OpenLayers.StyleMap({
    "default": new OpenLayers.Style({
      externalGraphic: sp.global.getResourcesPath() + "/web/js/ign/img/picto-carto.png",
      graphicWidth: 32,
      graphicHeight: 32,
      graphicXOffset: -16,
      graphicYOffset: -32,
      pointRadius: 16,
      cursor: "pointer"
    })
  });


  var ign = {
    'mapLoaded': function () {
      return interfaceViewer != undefined;
    },
    'initMap': function (enableControl, localisationParam) {
      var initPoint = localisationParam.initPoint;
      if ((initPoint.latitude == null || initPoint.longitude == null) && initPoint.tokenForSearchOnAddress) {
        sp.ign.getCoordinatesFromAddress([ignParam.initPoint], function (points) {
          var point = points[0];
          if (point.latitude == null || point.longitude == null || !point.enoughAccurate) {
            return;
          }
          localisationParam.initPoint = point;
          chooseMode(enableControl, localisationParam);
        });
      } else {
        chooseMode(enableControl, localisationParam);
      }
    },
    'initIgnLocalisation': function
      (keyBoardControl, ignParam) {
      var controls = keyBoardControl ? generateControlMap() : [new OpenLayers.Control.Zoom()]
      var enoughAccurate = true;
      if (ignParam.initPoint.latitude == null || ignParam.initPoint.longitude == null) {
        return;
      }
      var vectorLayer = enoughAccurate ? sp.ign.buildVectorLayer(sp.ign.buildFeaturesFromPointArray([ignParam.initPoint]), 'Localisation', true) : null;
      var ignOptions = {
        type: 'js',
        layers: [ignParam.ignMap],
        controls: controls,
        description: ignParam.initPoint.description,
        overlays: {},
        onView: function () {
          if (vectorLayer) {
            sp.ign.addVectorLayer(vectorLayer, true);
          }
        },
        territory: 'WLD'
      };
      loadMapIGN(ignParam, 16, ignOptions);

    }
    ,
    'initMapForPopin': function (ign) {
      if (!mapLoaded) {
        mapLoaded = true;
        sp.ign.initIgnLocalisation(true, ign);
        document.getElementById(ign.divName).style.display = 'block';
      }
    }
    ,
    'removeLayer': function (name) {
      var map = interfaceViewer.getViewer().getMap();
      var layersToRemove = map.getLayersByName(name);
      if (layersToRemove === undefined || layersToRemove.length < 1) {
        return;
      }
      var layerToRemove = layersToRemove[0];
      //suppression des controls concernant le layer
      var controlsLength = map.controls.length;
      for (var i = 0; i < controlsLength; i++) {
        if (map.controls[i].layer != undefined && map.controls[i].layer.id === layerToRemove.id) {
          var control = map.controls[i];
          map.removeControl(control);
          control.destroy();
        }
      }
      //suppression du layer de la map
      map.removeLayer(layerToRemove);
      //destruction du layer
      layerToRemove.destroyFeatures();
      layerToRemove.destroy();
    }
    ,
    'getCoordinatesFromAddress': function (points, cb) {
      $.ajax({
        url: sp.global.getSpRootDomain() + "/ign/coordinatesFromAddressWS",
        type: "POST",
        data: JSON.stringify(points),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: cb
      });
    }
    ,
    'getUrlWsContour': function (region, departement) {
      var dep = sp.global.isEmpty(departement) ? '' : '/' + departement;
      return sp.global.rewriteAnnuaireUrl('/ign/contourKmlWS/' + region + dep);
    }
    ,
    'addKmlLayer': function (kmlLayer) {
      interfaceViewer.getViewer().getMap().addLayer(kmlLayer);
    }
    ,
    'addVectorLayer': function (vectorLayer, enbalePopup) {
      var map = interfaceViewer.getViewer().getMap();
      map.addLayer(vectorLayer);
      if (enbalePopup) {
        addPopUpForMarkerOnLayer(vectorLayer);
      }
    },
    'buildKmlLayer': function (name, url, options) {
      return {
        type: 'KML',
        name: name,
        url: url,
        options: options
      };
    }
    ,
    'buildVectorLayer': function (features, layerName, zoomAfterLoad) {

      var vectorLayer = new OpenLayers.Layer.Vector(layerName);
      if (zoomAfterLoad) {
        vectorLayer.events.register('added', vectorLayer, sp.ign.zoomToLayer);
      }
      //TODO : create une stratégie de cluster pour gerer les niveaux de zoom.
      vectorLayer.addFeatures(features);
      vectorLayer.styleMap = markerStyleMap;
      return vectorLayer;
    }
    ,
    'buildFeaturesFromPointArray': function (points) {
      var length = points.length;
      var features = [];
      for (var i = 0; i < length; i++) {
        var point = points[i];
        var lonlat = new OpenLayers.LonLat(point.longitude, point.latitude).transform(
          new OpenLayers.Projection("EPSG:4326"), // transform from WGS84
          new OpenLayers.Projection("EPSG:3857") // to Spherical Mercator Projection
        );
        var feature = new OpenLayers.Feature.Vector(
          new OpenLayers.Geometry.Point(lonlat.lon, lonlat.lat), {
            description: point.description,
            name: point.name,
            htmlContent: buildHtmlContentPopup(point)
          }
        );
        features.push(feature);
      }
      return features;


    }
    ,
    'zoomToLayer': function (layer) {
      var map = interfaceViewer.getViewer().getMap();
      map.zoomToExtent(layer.object.getDataExtent());
    }
    ,
    'initIgnContours': function (divName, ignParam, callback) {
      if (SmartPhone.isAny()) {
        $("div#ign").hide();
        $("a#openNativeMap").show();
        return;
      }
      if (ignParam.departement != undefined && $.inArray(ignParam.departement, comWithoutContour) > -1) {
        //si on a pas de contour
        return;
      }
      $("div#ign").show();
      $("a#openNativeMap").hide();
      var controls = generateControlMap();
      ignParam.divName = divName;
      ignParam.initPoint = {
        lon: 2.351828,
        lat: 48.856578
      };
      var kmlOptions = {
        params: {
          visibility: true,
          eventListeners: {
            "loadend": sp.ign.zoomToLayer
          },
          styleMap: contourStyleMap,
          formatOptions: {extractStyles: false}
        }
      };
      ignParam.kml = sp.ign.buildKmlLayer('Contours', sp.ign.getUrlWsContour(ignParam.region, ignParam.departement), kmlOptions);
      var options = {
        type: 'js',
        layers: [ignParam.ignMap],
        controls: controls,
        overlays: {
          // Cas de fichiers "KML", "GPX" ou "OSM"
          'KML': [ignParam.kml]
        },
        onView: function () {
          callback();
        },
        territory: 'WLD'
      }
      loadMapIGN(ignParam, 5, options);
    }
  };

  return ign;
}();
