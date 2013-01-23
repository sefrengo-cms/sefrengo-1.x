<!--
var regExpPict = /\.(gif|jpeg|jpg|png)$/i

var messages        = new Array();
var sf_image_temp_stack = new Array();
var sf_image_temp_data = new Array();
var sf_image_max_retrys = 5;

function sf_displayPreviewPicTimerCallback() {
	var local_stack = new Array();
	for (e in sf_image_temp_stack) {
		//alert(e + ": " + sf_image_temp_data[e] );
		if (sf_image_temp_stack[e] != 'undefined') {
			if (sf_image_temp_stack[e] <= sf_image_max_retrys) {
				sf_image_temp_stack[e]++;
				sf_loadPreviewPic(sf_image_temp_data[e + 'url'], sf_image_temp_data[e + 'prefix'], sf_image_temp_data[e + 'imageid']);
			} else {
				sf_image_temp_stack[e] = 1;
				//alert('Bild laden fehlgeschlagen: ' + sf_image_temp_data[e + 'url'] + "\n Insgesamte Ladeversuche:" + sf_image_max_retrys);
			}
		}
	}
	
}

function sf_loadPreviewPic(url, prefix_thumb, imageid) {
		
		var img = document.getElementById(imageid);
		var new_img = new Image();
		
		//check input
		if(url.search(new RegExp("\.(jpg|jpeg|png|gif)$", "i")) == -1) {
			img.src = "cms/img/space.gif";
			return;
		}
		
		//look for thumb
		var match_yes = new RegExp("\.(jpg|jpeg|png)$", "i") ;
		var match_no = new RegExp(prefix_thumb +"\.(jpg|jpeg|png)$", "i") ;
		if (url.search(match_yes) != -1 && url.search(match_no) == -1) {
			url = url.replace(/\.(jpg|jpeg|png)$/i, prefix_thumb+".$1");
		}
		
		new_img.src = url;
		

		if(new_img.complete != true) {
				sf_image_temp_data[imageid + 'url'] = url;
				sf_image_temp_data[imageid + 'prefix'] = prefix_thumb;
				sf_image_temp_data[imageid + 'imageid'] = imageid;
				img.src = "cms/img/space.gif";
			if(! sf_image_temp_stack[imageid] || sf_image_temp_stack[imageid] == 'undefined') {	
				sf_image_temp_stack[imageid] = 1;
			}
			
			setTimeout("sf_displayPreviewPicTimerCallback()", sf_image_temp_stack[imageid]*1000);
			return;
		}
		sf_image_temp_stack[imageid] = 'undefined';
		
		width = new_img.width;
		height = new_img.height;

		if(new_img.width < 1) {
			//load image fails - invalid url?
			//alert("Bild laden fehlgeschlage: " + url);
			//eval("window.setTimeout(\"sf_displayPreviewPic(url, prefix_thumb, imageid)\",1000)");
			return;
		}

		//calculate thumbsize
		if (width > 100 || height > 100){
			if (height/width <= 1){
				f = width / 100;
				w = 100;
				h = height / f ;
			} else {
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



function con_layer(task) {
	for (j=1; j<=max_subs; j++) con_hide(eval('"' + "menu_layer" + j +'"'));
	con_show(eval('"' + "menu_layer" + task +'"'));
}

function con_hide(layer) {
	if (document.layers) document.layers[''+layer].visibility = 'hide';
	if (document.all) document.all[''+layer].style.visibility = 'hidden';
	if (!document.all && document.getElementById) {
         	task = document.getElementById(''+layer);
		task.style.visibility = 'hidden';
	}
}

function con_show(layer) {
	if (document.layers) document.layers[''+layer].visibility = 'show';
	if (document.all) document.all[''+layer].style.visibility = 'visible';
	if (!document.all && document.getElementById) {
         	task = document.getElementById(''+layer);
		task.style.visibility = 'visible';
         }
}

function on(message) {
	window.status = message;
	window.defaultStatus = window.status;
}

function on_func(message_id) {
	if (messages.length > message_id) {
		window.status = messages[message_id];
		window.defaultStatus = window.status;
	}
}

function on_func_add_message(message_id, message_text) {
	if (!messages[message_id]) {
		messages[message_id] = message_text;
	}
}

function off() {
	window.status = "Sefrengo CMS";
	window.defaultStatus = window.status;
}


function imgon(img,src) {
	document[img].src = 'images/menu_'+img+src+'.gif';
}


function new_window(theURL,winName,features,myWidth,myHeight,isCenter) {
	if (window.screen) if (isCenter) if (isCenter=="true") {
		var myLeft = (screen.width-myWidth)/2;
		var myTop = (screen.height-myHeight)/2;
		features+=(features!='')?',':'';
		features+=',left='+myLeft+',top='+myTop;
	}
	window.open(theURL,winName,features+((features!='')?',':'')+'width='+myWidth+',height='+myHeight+', resizable=yes');
}

function con_window(theURL,name,myWidth,myHeight,path) {
	con_popup(theURL,'', myWidth, myHeight, name, name, '#000000', '#5A7BAD', '#5A7BAD', '#A8BADE', 'Verdana, Arial, Helvetica', '1', '#000000', path);
}

function new_imagepopup(theURL,winName,altName,features,myWidth,myHeight,isCenter) {
	// Bildformate
	if (regExpPict.test(theURL)) {
		if(window.screen)if(isCenter)if(isCenter == 'true') {
			var myLeft = (screen.width-myWidth)/2;
			var myTop = (screen.height-myHeight)/2;
			features+=(features!='')?',':'';
			features+=',left='+myLeft+',top='+myTop;
		}
		imagepopup = window.open('','',features+((features!='')?',':'')+'width='+myWidth+',height='+myHeight);
		with (imagepopup) {    
			document.open();
			document.write('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">');
			document.write('<html xmlns="http://www.w3.org/1999/xhtml" lang="de" xml:lang="de">');
			document.write('<head><title>'+ winName + '</title>');
			document.write('<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />');
			document.write('<meta http-equiv="ImageToolbar" content="no" />');
			document.write('<meta http-equiv="Language" content="de" />');
			document.write('<style type="text/css"><!--')
			document.write('body,div{margin:0;padding:0;overflow:hidden;}img{border:0 none;margin:0;padding:0;}')
			document.write('--></style></head>')
			document.write('<body><div>')
			document.write('<a href="javascript:self.close()"><img src="'+theURL+'" border="0" alt="'+altName+'" title="'+altName+'"></a>')
			document.write('</div></body></html>');
			document.close();
		}
	// andere Dateiformate
	} else {
		new_window(theURL,name,features,600,400,'true');
	}
}


function previewPict(adresse, breite, hoehe, created, modified, redakteur, thumbnail, filename) {
	previewPict2(adresse, breite, hoehe, created, modified, redakteur, thumbnail, filename, "")
}

function previewPict2(adresse, breite, hoehe, created, modified, redakteur, thumbnail, filename, sizeinfo, id) {
	text = "";
	titel = pp_title;
	if (adresse != "") {
		if (regExpPict.test(filename)) {
			text += "<img src=\"" + adresse + "\" border=\"0\"";
			text += (breite != "") ? " width=\"" + breite + "\"": "";
			text += (hoehe  != "") ? " height=\"" + hoehe + "\"": "";
			text += "><br>";
			titel = (thumbnail) ? pp_header_bild: pp_header_datei;
		}
	}
	text += (created   != "") ? pp_created  + created   + "<br>": "";
	text += (modified  != "") ? pp_modified + modified  + "<br>": "";
	text += (redakteur != "") ? pp_author   + redakteur + "<br>": "";
	text += (sizeinfo  != "") ? pp_size     + sizeinfo          : "";
	
	sf_overlib(text, titel, 'ID: '+id, 'sideinfo');
}

function confirm_to_url(msg,url,to) {
  var confirm_to = false;
  var string_url = '';
  if(confirm(msg)) confirm_to = true;
  string_url = url+'&'+to+'='+confirm_to;
  window.location.href = string_url;
  return !confirm_to;
}
//-->
