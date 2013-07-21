/*global
	jQuery,
	$,
	$jqsf,
	swfobject,
	document,
	window
*/

// ----------------------------------------------------------------- //

// indicate that js support is enabled as CSS class
document.getElementsByTagName('html')[0].setAttribute('class', 'js')

/**
 * Set up Sefrengo namespace
 */
var SF;
if (typeof SF == 'undefined') {
	SF = {};
} else if (typeof SF != 'object') {
	throw new Error("SF already exists and is not an object");
}
if (typeof SF.plugins == 'undefined') {
	SF.plugins = {};
} else if (typeof SF.plugins != 'object') {
	throw new Error("SF.plugins already exists and is not an object");
}


// ----------------------------------------------------------------- //

/**
 * Configuration
 */
SF.Config = function() {
	// public properties
	return {
		debug: false
	}
}();

// ----------------------------------------------------------------- //

/**
 * Language strings
 */
SF.Lang = function() {
	// public properties
	return {
		//lang1: 'My String' // String
	}
}();
//SF.Lang.lang2 = 'String 2';

// ----------------------------------------------------------------- //

/**
 * Language strings
 */
SF.Plugin = function() {
	// public properties
	return {
		create: function(name, selectors, files, loadingComplete, options) {
			var defaults = {
					autoLoad: false
				},
				options = (typeof(options) === 'undefined') ? {} : options; 
			options = SF.Utils.mergeOptions(defaults, options);
			
			SF.plugins[name] = {};
			SF.plugins[name].name = name;
			SF.plugins[name].selectors = (typeof(selectors) !== 'object') ? [] : selectors;
			SF.plugins[name].isLoaded = false;
			SF.plugins[name].loadingComplete = (loadingComplete == null || typeof(loadingComplete) !== 'function') ? function() {} : loadingComplete;

			SF.plugins[name].files = (typeof(files) !== 'object') ? [] : files;
			
			if(options.autoLoad == true)
			{
				this.load(name);
			}
		},
		exists: function(name) {
			var p;
			for (p in SF.plugins) {
				if(SF.plugins[p].name == name)
				{
					return true;
				}
			};
			return false;
		},
		get: function(name) {
			if(this.exists(name) == true)
			{
				return SF.plugins[name];
			} 
			return null;
		},
		load: function(name, options) {
			var p = this.get(name),
				jQueryCached = jQuery, // cache jQuery incase there two different version/objects included
				defaults = {
					rerun: false, // call loading complete again (if plugin already loaded)
					arguments: [], // arguements for recalled complete function
					manually: false // load the file even no elements selected
				},
				options = (typeof(options) === 'undefined') ? {} : options; 
			options = SF.Utils.mergeOptions(defaults, options);
			
			if(p === null) { return false; }
			
			//console.log('load:', p.name, p.selectors.join(','), $jqsf(p.selectors.join(',')).length);
			
			if( p.isLoaded == false &&
				(options.manually == true || $jqsf(p.selectors.join(','))[0]))
			{
				// no files to load - call complete function directly
				if(p.files.length == 0) {
					p.loadingComplete.apply(p, options.arguments);
					// write the original jQuery back
					jQuery = jQueryCached;
					return true;
				}
				
				var	s,
					i = 1,
					ii = p.files.length,
					filename = '',
					filetype = '',
					onScriptLoaded = function(data, response) {
						if (i++ == ii)
						{
							p.isLoaded = true;
							p.loadingComplete.apply(p, options.arguments);
							// write the original jQuery back
							jQuery = jQueryCached;
						}
					};
				
				for(s in p.files)
				{
					filename = p.files[s];
					filetype = filename.substr(filename.lastIndexOf('.')+1, filename.length).replace('!', '');
					
					if(filetype == 'css')
					{
						filename = SF.Config.css_dir + filename;
						$jqsf('head').append('<link rel="stylesheet" href="' + filename + '" type="text/css" />');
						onScriptLoaded();
					}
					else if(filetype == 'js')
					{
						filename = SF.Config.js_dir + filename;
						
						// use unminified file if possible (no '!' at the end)
						if(SF.Config.debug == true && filename.lastIndexOf('!') != (filename.length-1))
						{
							filename = filename.replace('.min', '').replace('.pack', '');
						}
						
						// remove '!' in path
						if(filename.lastIndexOf('!') == (filename.length-1))
						{
							filename = filename.replace('!', '');
						}
						
						// use overwrite the jQuery instance from Sefrengo's jQuery 
						jQuery = $jqsf;
						
						// no caching: $jqsf.getScript(filename, onScriptLoaded);
						$jqsf.ajax({
							type: "GET",
							url: filename,
							success: onScriptLoaded,
							dataType: "script",
							cache: true // enable caching!
						});
					}
				};
				
				return true;
			}
			else if(options.rerun == true &&
					p.isLoaded == true &&
					(options.manually == true || $jqsf(p.selectors.join(','))[0]))
			{
				p.loadingComplete.apply(p, options.arguments);
				return true;
			}
			
			return false;
		},
		loadAll: function() {
			for (p in SF.plugins) {
				if(SF.plugins[p].isLoaded == false)
				{
					this.load(SF.plugins[p].name);
				}
			};
			return true;
		}
	}
}();

// ----------------------------------------------------------------- //

/**
 * Little helper functions
 */
SF.Utils = function() {
	// variables for loadPreviewPic()
	var image_temp_stack = new Array();
	var image_temp_data = new Array();
	var image_max_retrys = 3;
	
	var displayPreviewPicTimerCallback = function() {
		var local_stack = new Array();
		for (e in image_temp_stack) {
			//alert(e + ": " + image_temp_data[e] );
			if (image_temp_stack[e] != 'undefined') {
				if (image_temp_stack[e] <= image_max_retrys) {
					image_temp_stack[e]++;
					SF.Utils.loadPreviewPic(image_temp_data[e + 'url'], image_temp_data[e + 'prefix'], image_temp_data[e + 'imageid']);
				} else {
					image_temp_stack[e] = 1;
					//alert('Bild laden fehlgeschlagen: ' + image_temp_data[e + 'url'] + "\n Insgesamte Ladeversuche:" + image_max_retrys);
				}
			}
		}
	}
	
	// public properties
	return {
		mergeOptions: function(defaults, options) {
			for (prop in defaults)
			{ 
				if (prop in options) { continue; }
				options[prop] = defaults[prop];
			}
			return options;
		},
		readablizeBytes: function(bytes) {
			var s = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB'],
				e = Math.floor(Math.log(bytes)/Math.log(1024));
			return (bytes/Math.pow(1024, Math.floor(e))).toFixed(2)+" "+s[e];
		},
		deleteConfirm: function(name, lang) {
			if(typeof(name) !== 'string') { name = ''; }
			
			if(typeof(lang) !== 'string' || lang == '' || typeof(SF.Lang[lang]) === 'undefined') {
				lang = (name != '') ? 'deletealert_name' : 'deletealert';
			}
			
			if(confirm(SF.Lang[lang].replace('\{name\}', name))) { return true; }
			else { return false; }
		},
		popup: function(theURL, winName, features, myWidth, myHeight, isCenter) {
			if (window.screen && (isCenter == true || isCenter=="true"))
			{
				var myLeft = (screen.width-myWidth)/2;
				var myTop = (screen.height-myHeight)/2;
				features+=(features!='')?',':'';
				features+=',left='+myLeft+',top='+myTop;
			}
			window.open(theURL,winName,features+((features!='')?',':'')+'width='+myWidth+',height='+myHeight+', resizable=yes');
		},
		confirmToUrl: function(msg, url, to) {
			var confirm_to = false;
			var string_url = '';
			if(confirm(msg)) confirm_to = true;
			string_url = url+'&'+to+'='+confirm_to;
			window.location.href = string_url;
			return !confirm_to;
		},
		loadPreviewPic: function(url, prefix_thumb, imageid) {
			var img = document.getElementById(imageid);
			var new_img = new Image();
			
			//check input
			if(url.search(new RegExp("\.(jpg|jpeg|png|gif)$", "i")) == -1)
			{
				img.src = "cms/img/space.gif";
				return;
			}
			
			//look for thumb
			var match_yes = new RegExp("\.(jpg|jpeg|png)$", "i") ;
			var match_no = new RegExp(prefix_thumb +"(_\d+)?\.(jpg|jpeg|png)$", "i");
			if (url.search(match_yes) != -1 && url.search(match_no) == -1)
			{
				splitted = url.split(match_yes)
				url = url.replace(/\.(jpg|jpeg|png)$/i, prefix_thumb + "." + splitted[1].toLowerCase());
			}
			
			new_img.src = url;
			
			if(new_img.complete != true)
			{
				image_temp_data[imageid + 'url'] = url;
				image_temp_data[imageid + 'prefix'] = prefix_thumb;
				image_temp_data[imageid + 'imageid'] = imageid;
				img.src = "cms/img/space.gif";
				if(! image_temp_stack[imageid] || image_temp_stack[imageid] == 'undefined')
				{	
					image_temp_stack[imageid] = 1;
				}
				
				var timeoutID = window.setTimeout(function() { window.clearTimeout(timeoutID); displayPreviewPicTimerCallback(); }, image_temp_stack[imageid]*1000);
				return;
			}
			image_temp_stack[imageid] = 'undefined';
			
			width = new_img.width;
			height = new_img.height;
	
			if(new_img.width < 1)
			{
				//load image fails - invalid url?
				//alert("Bild laden fehlgeschlage: " + url);
				//eval("window.setTimeout(\"displayPreviewPic(url, prefix_thumb, imageid)\",1000)");
				return;
			}
	
			//calculate thumbsize
			if (width > 100 || height > 100)
			{
				if (height/width <= 1)
				{
					f = width / 100;
					w = 100;
					h = height / f ;
				}
				else
				{
					f = height / 100;
					w = width / f;
					h = 100;
				}
				width = w;
				height = h;
	   		}
	
			img.src = new_img.src;
			img.height = height;
			img.width = width;
		}
	}
}();

// ----------------------------------------------------------------- //