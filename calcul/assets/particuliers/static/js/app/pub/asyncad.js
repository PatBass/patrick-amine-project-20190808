/* 
 * Asynchronous loading of external scripts that make heavy use of "document.write".
 * Useful for ad scripts
 * Author : Jacques Archimede
 * Version 1.0.0 - December 29, 2014
 *
 * Configuration parameters are defined in the ad_slots array
 * 
 * ad_slots example :
 * ===================
 * var ad_slots = {
 *		"revive_slot": { 
 *			ad_url: function(slot) { return 'xxxxxxxxxx'; },
 *			ad_parameters: { 
 *				zoneid : yy 
 *			}
 *		}, 
 *		"oas_slot": { 
 *			ad_url: function(slot) { return 'xxxxxxxxxx'; },
 *			ad_parameters: { 
 ²				oas_sitepage: 'yyyyyyyyyyy',
 *				oas_position: 'zzzzzzz'
 *			}
 *		} 
 * };	 
 */
(function(global) {

	var slots = []; // Array of slots (HTML container) receiving the result of execution of an external script
	var currSlot = -1;
	var buffer = [];
	var stack = []; // stack of parent nodes
	var nativeDocWrite = null;
	var nativeDocWriteln = null;
	
/******************************************************************* 
 * Main entry point automatically called when the document loading is completed.	
 *******************************************************************/	
	var asyncAd = function() {
		nativeDocWrite = document.write;
		nativeDocWriteln = document.writeln;
		redefDocWrite();
		if (ad_slots) {
			for (var slot in ad_slots) {
				slots.push(slot);
			}
			nextSlot();
		}
	};
	
/******************************************************************* 
 * Intercepts "document.write" and saves its content for later use.	
 *******************************************************************/	
	var redefDocWrite = function() {
		document.write = document.writeln = function() {
			for (var i in arguments) {
				buffer.push(arguments[i]);
            }
	    }
	};
	
/******************************************************************* 
 * remove comments and CDATA before runnning a script (mandatory for IE).
 * @param {string} script code. 
 *******************************************************************/	
	var cleanScript = function(script) {
		return script
			.replace(/\n/g, String.fromCharCode(255))
			.replace(/\<!--(.+)--\>/, "$1")
			.replace(/(\/\/)?\s*\<!\[CDATA\[(.+)(\/\/)?\s*\]\]\>/, "$2")
			.trim()
			.replace(/^\/\//, "")
			.replace(/\/\/$/, "")
			.replace(new RegExp(String.fromCharCode(255) + "+", 'g'), "\n")
			.replace(/^\n/, "");
	};
	
/******************************************************************* 
 * run script code in global context.
 * @param {string} clean script code. 
 *******************************************************************/	
	var runGlobalScript = function( data ) {
		if ( data && /\S/.test(data) ) {
			var script = document.createElement( "script" );
			script.appendChild( document.createTextNode( data ) );
			document.head.appendChild( script ).parentNode.removeChild( script );
		}
	};	

	
/******************************************************************* 
 * replaces a script element by the content returned by all 
 * "document.write" of this script, 
 * then goes to the next child of the parent element (next sibling of the script element).
 * @param {DOM script element} script element. 
 *******************************************************************/	
	var replaceContent = function(scriptElt) {
		var content = buffer.join("");
		var div = document.createElement('div');
		div.innerHTML = content;
		scriptElt.parentNode.replaceChild(div, scriptElt);
		div.currChild = -1;
		stack.push(div);
		nextChild();
	};
	
	
/******************************************************************* 
 * loads into a dom element and runs a script url, 
 * goes to the next sibling an then call a callback function when the excution is done  
 * @param {string} script url. 
 * @param {DOM element} dom element. 
 * @param {function} callback function. 
 *******************************************************************/	
	var playUrl = function(url, slotElt, callback) {
		buffer.length = 0;
		var script = document.createElement('script');
		script.setAttribute("type","text/javascript");
		script.setAttribute("async", false);
		slotElt.appendChild(script);
		var done = false;
		script.onload = script.onreadystatechange = function(){
			if ( !done && 
				(!this.readyState || this.readyState == "loaded" || this.readyState == "complete") ) {
				done = true;
				var content = buffer.join("");
				slotElt.innerHTML = content;
				slotElt.currChild = -1;
				stack.push(slotElt);
				nextChild();
				if (callback) {
					callback();
				}
			}
		};
		script.setAttribute("src", url);
	};
	
/******************************************************************* 
 * Process the next child of a parent node in the stack 
 *******************************************************************/	
	var nextChild = function() {
		var parentElt = stack.pop();
		var children = parentElt.childNodes;
		for (var c = parentElt.currChild + 1; c < children.length; c++) {
			var child = children[c];
			if (child.nodeName === "SCRIPT") {
				if (child.src) {
					var src = child.src;
					var div = document.createElement('div');
					parentElt.replaceChild(div, child);
					parentElt.currChild = c;
					stack.push(parentElt);
                    playUrl(src, div, nextChild);
					return;
				} else {
					buffer.length = 0;
					try {
						runGlobalScript(child.innerHTML);
					} catch (e) {
						console && console.log(e);
					}
					if (buffer.length > 0) {
						replaceContent(child);
					}
				}
			}
		}
	};
	
/******************************************************************* 
 * Process the next slot 
 *******************************************************************/	
	var nextSlot = function() {
		if (++currSlot < slots.length) {
			var slot = slots[currSlot];
			try {
				var ad_url = ad_slots[slot].ad_url;
				var url = typeof ad_url === 'function' ? ad_url(ad_slots[slot]) : ad_url;
				if (url !== "") {
					var slotElt = document.getElementById(slot);
					setTimeout(function () {
						playUrl(url, slotElt, nextSlot);
					}, 0);
				}
			} catch (e) {
				console && console.log(e);
			}
		}
	};

	global.runGlobalScript = runGlobalScript;
	global.asyncAd = asyncAd;
})(this);

if (window.addEventListener) {
	window.addEventListener("load", function () {
		asyncAd();
	});
} else {
	window.onload = asyncAd;
}