(function(global) {

	var eregie = function(slot) {
		var oas_server = 'http://eregie.premier-ministre.gouv.fr/';
		var oas_sitepage = slot['ad_parameters']['oas_sitepage'];
		var oas_position = slot['ad_parameters']['oas_position'];
		var oas_query="?";
		if (!(RN)) {
			var RN=new String (Math.random());
			var RNS=RN.substring (2, 11);
		}
		var oas_page=oas_sitepage + '/1' + RNS + '@' + oas_position + oas_query;
		return oas_server + '/3/' + oas_page;
	};
	global.eregie = eregie;
	
})(this);
