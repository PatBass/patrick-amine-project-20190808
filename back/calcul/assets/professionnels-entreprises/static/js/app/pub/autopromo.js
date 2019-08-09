(function(global) {

	var autopromo = function(slot) {
		var refpub = slot.ad_parameters.refpub;
		if (refpub) runGlobalScript('var refpub = "'+refpub+'";');
		var zoneid = slot.ad_parameters.zoneid;
		var m3_u = (location.protocol=='https:'?'https://regiepub.dila.gouv.fr/revive/www/delivery/ajs.php':'https://regiepub.dila.gouv.fr/revive/www/delivery/ajs.php');
		if (!document.MAX_used) document.MAX_used = ',';
		var params = [ 
			'zoneid=' + zoneid, 
			'cb=' + Math.floor(Math.random()*99999999999),
			'loc=' + escape(window.location)
		];
		if (document.MAX_used != ',') {
			params.push('exclude=' + document.MAX_used);
		}
		if (document.charset) {
			params.push('charset=' + document.charset);
		} else if (document.characterSet) {
			params.push('charset=' + document.characterSet);
		}
		if (document.referrer) {
			params.push('referer=' + escape(document.referrer));
		}
		if (document.context) {
			params.push('context=' + escape(document.context));
		}
		if (document.mmm_fo) {
			params.push('mmm_fo=' + 1);
		}
		return m3_u + "?" + params.join( '&' );
	};

	global.autopromo = autopromo;
})(this);
