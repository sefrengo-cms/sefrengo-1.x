<!--
var linkset = new Array();
/*
** Simple browser-sniffer 
*/
var ns4 = (document.layers)? true:false;
var ns6 = (document.getElementById)? true:false;
var ie4 = (document.all)? true:false;
var ie4plus = ie4
var ie5 = false
var ie6plus = false
if (ie4plus) {
	if (navigator.userAgent.indexOf('MSIE 5') >  0) {
		ie5 = true;
	} else {
		ie6plus = true;
	}
}
if (ie5 || ie6plus) { 
	ns6 = false
	ie4 = false
}
var blnPopup = (ie4plus || ns6 || ns4);

/*
** Klasse Popup
**
** Erstellt ein DHTML-Popup-Ebene mit beliebigen Inhalt, vorgesehen haupts&auml;chlich f&uuml;r Menu- und Info-Popups
** Author: J&uuml;rgen Br&auml;ndle, (c) 2003
** Based on Open Source written by ...
** 
*/
function Popup() {
	this.basistext        = ""
	this.basislink        = ""
	this.basisimage       = "<img src=\"%cms0%\" width=\"%cms1%\" height=\"%cms2%\" class=\"%cms3%\" alt=\"%cms4%\" title=\"%cms4%\" border=\"%cms5%\">"

	this.emptylink        = "#"
	this.timeout          = 500
	
	// Menu Properties
	this.popupobj     = null
	this.name         = "popmenu"
	this.cols         = 1
	this.width        = 140
	this.height       = 140
	this.border       = 0
	this.bordercolor  = "#5A7BAD"
	this.type         = 0
	this.browser      = (ns4) ? 0: 1;
	this.callByClick  = false
	this.visible      = false;
	this.highlightoff = 'menurow'
	this.highlighton  = 'menurow_over'

	this.arrtext      = new Array()
	this.stroutput    = ""
	
	// Position des Menu/Popup
	this.x            = 0
	this.y            = 0
    this.offsetx      = 10
	this.offsety      = 10
	this.eventX       = 0
	this.eventY       = 0
	this.screenWidth  = 0
	this.screenHeight = 0
	this.scrollX      = 0
	this.scrollY      = 0
	this.dct          = null	
	this.unit         = (ns4) ? '': 'px';
		
	// text templates
	// template for menu box
	this.templatebox   = [ "<table width=\"", "\" border=\"", "\" cellpadding=\"1\" cellspacing=\"0\" bgcolor=\"", "\" class=\"templatebox\"><tr><td>", "</td></tr></table>", "<table width=\"100%\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\" bgcolor=\"#FFFFFF\">", "</table>" ]
	// template for menu title
	this.templatetitle = [ "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr>", "<td class=\"menutitle\"><b><font face=\"Verdana, Arial, Helvetica\" size=\"1\">&nbsp;", "&nbsp;</font></b>", "</td></tr></table>" ];
	// template for menu seperator
	this.templateseperator = [ "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr>", "<td class=\"menutitle\">", "<img src=\'space.gif\' width=\'1\' height=\'1\'", "</td></tr></table>" ];
	// templates for single array
	// templates[0]:	overDiv for sideinfo, filemanager compact view
	//this.templates     = [ "<table id=\"overlib\"><tr><td><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td width=\"100%\" class=\"%cms1%\">%cms2%</td><td class=\"%cms1%\">%cms3%</td></tr></table><table width=\"100%\" border=0 cellpadding=2 cellspacing=0 bgcolor=\"#FFFFFF\"><tr><td valign=top class=\"sideinfo2\"><font color=\"#000000\" face=\"Verdana, Arial, Helvetica\" size=\"1\">%cms0%</font></td></tr></table></td></tr></table>\n" ]
// Versuch von Olaf das aufzuräumen
	this.templates     = [ "<div id=\"overlib\"><div class=\"%cms1%\"><p class=\"nummer\">%cms3%</p>%cms2%</div><p class=\"sideinfo2\">%cms0%</p></div>\n" ]
	// template for menu entry
	// type   elements  description
	//    0     0,1     single line menu - no picture, no additonal column
	//    1     2,3     single line menu - with picture, no addtional column
	//    2     4,5     single line menu - no picture, one additional column
	//    3     6,7     single line menu - with picture, one additional column
	//
	// parameter order for menu
	// type   elements  parameter order
	//    0   0,1       strText, strLink/arrLink, strOver, strTarget, strOptionalJS
	//    1   2,3       strText, strLink/arrLink, strOver, strTarget, arrImg
	//  2,3   4,5,6,7   strText, strLink/arrLink, strOver, strTarget, arrImg, arrColumn
	//
	// parameter strLink/arrLink
	// strLink: text-only url
	// arrLink: parameter array for basislink for replacement of placeholders, count and order depend on basislink
	//
	// parameter arrImg
	// arrImg: parameter array for an icon in front of the link text
	//         order of paramter: src, width, height, border, style-class
	//
	// parameter arrColumn
	// arrColumn: parameter array for an addtional column to the right
	//            order of parameter: text, rowspan, vertical-align, style-class
	//            each set of 4 values will be used for one table cell
	//  
	this.templateentry = [ 
                          "<tr class=\"menurow\"><td class=\"menuentry\" nowrap><a href=\"%cms0%\" %cms1%><font face=\"Verdana, Arial, Helvetica\" size=\"1\">%cms3%</font></a></td></tr>",
                          "<tr class=\"menurow\" onClick=\"%cms4%window.open('%cms0%','%cms1%');\"><td class=\"menuentry\" nowrap><font face=\"Verdana, Arial, Helvetica\" size=\"1\">%cms3%</font></td></tr>",

                          "<tr class=\"menurow\"><td class=\"menuentry\" nowrap><a href=\"%cms0%\" %cms1%><font face=\"Verdana, Arial, Helvetica\" size=\"1\">%cms4%&nbsp;%cms3%</font></a></td></tr>", 
                          "<tr class=\"menurow\" onClick=\"window.open('%cms0%','%cms1%');\"><td class=\"menuentry\" nowrap><font face=\"Verdana, Arial, Helvetica\" size=\"1\">%cms4%&nbsp;%cms3%</font></td></tr>", 

                          "<tr class=\"menurow\"><td class=\"menuentry\" nowrap><a href=\"%cms0%\" %cms1%><font face=\"Verdana, Arial, Helvetica\" size=\"1\">%cms3%</font></a></td>%cms5%</tr>", 
                          "<tr class=\"menurow\" onClick=\"window.open('%cms0%','%cms1%');\"><td class=\"menuentry\" nowrap><font face=\"Verdana, Arial, Helvetica\" size=\"1\">%cms3%</font></td>%cms5%</tr>", 

                          "<tr class=\"menurow\"><td class=\"menuentry\" nowrap><a href=\"%cms0%\" %cms1%><font face=\"Verdana, Arial, Helvetica\" size=\"1\">%cms4%&nbsp;%cms3%</font></a></td>%cms5%</tr>", 
                          "<tr class=\"menurow\" onClick=\"window.open('%cms0%','%cms1%');\"><td class=\"menuentry\" nowrap><font face=\"Verdana, Arial, Helvetica\" size=\"1\">%cms4%&nbsp;%cms3%</font></td>%cms5%</tr>" ]

	this.createMenuObject  = createMenuObject
	this.createMenu        = createMenu
	this.createMenuTitle   = createMenuTitle
	this.createMenuEntries = createMenuEntries
	this.createMenuentry   = createMenuentry
	this.createCell        = createCell
	this.createTarget      = createTarget
	this.createLink        = createLink
	this.createImage       = createImage
	this.replaceParameter  = replaceParameter
	this.writeHTML         = writeHTML
	this.clearHTML         = clearHTML
	this.adjustPosition    = adjustPosition
	this.show              = show
	this.hide              = hide
	this.move              = move
	this.highlight         = highlight
	this.createNS4Layer    = createNS4Layer
	this.getSizeAndOffset  = getSizeAndOffset
}

// erstellt das menuobj, das f&uuml;r die weitere Bearbeitung genutzt wird
function createMenuObject() {
	this.popupobj = (ie4plus) ? eval("document.all." + this.name) : ns6? document.getElementById(this.name) : ns4?  eval("document." + this.name) : '';
	this.popupobj.thestyle = (ns4) ? this.popupobj : this.popupobj.style;
}

// erstellt ein menutext aus den &uuml;bergebenen werten
function createMenu() {
  var arrMenuValue  = (arguments.length >= 1) ? arguments[0]: new Array();
  var isSingleArray = (arguments.length >= 2) ? arguments[1]: false;
  var intSATemplate = (arguments.length >= 3) ? arguments[2]: -1;
  
  // create new menu
  this.arrtext = new Array();
  //bb start 18.3.2005 reset menuheight - otherwise in IE a display error occurs, if 
  //you call [first] a large menu and at [second] a small menu. 
  this.popupobj.thestyle.height = "10px"
  //bb stop
  // create menus
  if (isSingleArray) {
	  // single array ... everything needed must be in the basistext, only placeholder must be replaced
  	if (intSATemplate >= 0 && intSATemplate < this.templates.length) this.basistext = this.templates[intSATemplate];
	this.arrtext[this.arrtext.length] = this.replaceParameter(this.basistext, arrMenuValue, "g")
  } else {
	  // complex array ... display block and everything else must be created
	  // open menu table
	  this.arrtext[this.arrtext.length] = this.templatebox[0] 
	  this.arrtext[this.arrtext.length] = this.width 
	  this.arrtext[this.arrtext.length] = this.templatebox[1]
	  this.arrtext[this.arrtext.length] = this.border
	  this.arrtext[this.arrtext.length] = this.templatebox[2]
	  this.arrtext[this.arrtext.length] = this.bordercolor
	  this.arrtext[this.arrtext.length] = this.templatebox[3]
	  // create menu lines
	  for(var i = 0; i < arrMenuValue.length; i+=2 ) {
			this.createMenuTitle(arrMenuValue[i])
			this.arrtext[this.arrtext.length] = this.templatebox[5]
		    this.createMenuEntries(arrMenuValue[i+1])
			this.arrtext[this.arrtext.length] = this.templatebox[6]
	  }
	  // close menu table
	  this.arrtext[this.arrtext.length] = this.templatebox[4]
  }
  this.stroutput = this.arrtext.join("");
  if (ns4) this.createNS4Layer();
}

function createNS4Layer() {
  this.stroutput = '<layer name="gui" bgColor="#E6E6E6" width="' + this.width + '" onmouseover="clearhidemenu()" onmouseout="hidemenu()">' + this.stroutput + '</layer>'
}

// erstellt den menutitel
function createMenuTitle( arrMenuTitle ) {
  
  var tpl_to_use = new Array();
  
  // check for values
  if (arrMenuTitle) {
  	var tpl_to_use = this.templatetitle;
  }
  //No value, use seperator template
  else{
	var tpl_to_use = this.templateseperator; 
  }
    // start menutitle
    this.arrtext[this.arrtext.length] = tpl_to_use[0]
    // create title cells
    if (arrMenuTitle.sort) { // test for sort-method in Array-Object - only aaray
      // create multiple headers cells
      var intMax = Math.min(this.cols, arrMenuTitle.length)
      for(var i = 0; i < intMax; i++) {
        this.arrtext[this.arrtext.length] = tpl_to_use[1]
		this.arrtext[this.arrtext.length] = arrMenuTitle[i]
		this.arrtext[this.arrtext.length] = tpl_to_use[2]
      }
    } else {
      // Erzeuge eine einzelne &Uuml;berschriftszelle
      this.arrtext[this.arrtext.length] = tpl_to_use[1]
	  this.arrtext[this.arrtext.length] = arrMenuTitle
	  this.arrtext[this.arrtext.length] = tpl_to_use[2]
    }
    // end menutitle
    this.arrtext[this.arrtext.length] = tpl_to_use[3]
}

// erstellt menuzeilen
function createMenuEntries( arrMenuEntries ) {
  // check for values
  if (arrMenuEntries) {
    intType = this.type*2 + this.browser;
    // create entry rows
    for(var i = 0; i < arrMenuEntries.length; i++) {
      var arrEntry = arrMenuEntries[i]
      this.arrtext[this.arrtext.length] = this.createMenuentry( arrEntry, intType )
    }
  }
}

// erstellt eine menuzelle
function createMenuentry( arrEntry, intType ) {
    // only one cell per row
    var strText   = arrEntry[0];
    var strLink   = this.createLink(arrEntry[1])
    var strOver   = (arrEntry.length >= 3) ? arrEntry[2]: strText;
    var strTarget = (arrEntry.length >= 4) ? this.createTarget(arrEntry[3]): this.createTarget("_self");
    var strImage  = (arrEntry.length >= 5) ? this.createImage(arrEntry[4]): "";
    var strColumn = (arrEntry.length >= 6) ? this.createCell(arrEntry[5]): "";
    // create parameter array
    var arrValues = [ strLink, strTarget, strOver, strText, strImage, strColumn ]
    // create menu entry from parameter array
    return this.replaceParameter( this.templateentry[intType], arrValues, "g" ); 
}

// erstellt eine tabellenzelle
function createCell( arrValues ) {
  // check for values
  if (arrValues) {
    var arrColumn = new Array("")
    var intMax = Math.min(arrValues.length, ((this.cols-1)*4))
    for(var i = 0; i < intMax; i += 4 ) {
      arrColumn[arrColumn.length] = "<td"
      if (arrValues[i+1] != '') {
	  	arrColumn[arrColumn.length] = " rowspan=\"" + arrValues[i+1] + "\""
	  }
      if (arrValues[i+2] != '') {
	  	arrColumn[arrColumn.length] = " valign=\"" + arrValues[i+2] + "\""
      }
	  if (arrValues[i+3] != '') {
	  	arrColumn[arrColumn.length] = " class=\"" + arrValues[i+3] + "\""
	  }
      arrColumn[arrColumn.length] = ">" + arrValues[i] + "</td>"
    }
    // return cells
    return arrColumn.join("")
  }
  // return empty string
  return "";
}


// liefert das target f&uuml;r einen link zur&uuml;ck
function createTarget( strValue ) {
  var strTarget = strValue
  // check for values
  if (strTarget) {
    if (ns4) return (" target=\"" + strTarget + "\"");
    return strTarget;
  }
  // return empty string
  return "";
}

// liefert einen link als text zur&uuml;ck
// verwendet basislink
function createLink( arrLink ) {
  // check for values
  if (arrLink) {
    if (arrLink.sort) return this.replaceParameter( this.basislink, arrLink, "g" );
    return arrLink;
  }
  // return empty string
  return this.emptylink;
}

// liefert ein bild als text zur&uuml;ck
// verwendet basisimage
function createImage( arrImage ) {
  // check for values
  if (arrImage) {
    if (arrImage.sort) {
      return ((arrImage.length > 0) ? this.replaceParameter( this.basisimage, arrImage, "gi" ): "");
    }
    return arrImage;
  }
  // return empty string
  return "";
}

// ersetzt die platzhalter %cmsXXX% durch den Array-Wert XXX aus arrValues
// kann sowohl einzelne fundstellen ersetzen, als auch alle Fundstellen
// verwendet regul&auml;ren ausdr&uuml;cke f&uuml;r das ersetzen
function replaceParameter() {
	var strText     = (arguments.length >= 1) ? arguments[0]: "";
	var arrValues   = (arguments.length >= 2) ? arguments[1]: new Array();
	var strRegParam = (arguments.length >= 3) ? arguments[2]: "";
	var blnClean    = (arguments.length >= 4) ? arguments[3]: true;
	// replace the %cmsXX% paramter in strTemplate with the elemnts in arrValues
	for(var i = 0; i < arrValues.length; i++) {
		regXP = new RegExp("%cms" + i + "%", strRegParam);
		strText = strText.replace(regXP, arrValues[i])
	}
	// L&ouml;sche alle noch vorhandenen Platzhalter, wenn nicht gew&uuml;nscht, mu&szlig; 4. Parameter entsprechend gesetzt sein
	if (blnClean) strText = strText.replace(new RegExp("%cms\d*%", "gi"), "");
	return strText
}

// schreibt das menu/den popuptext in die ebene von menuobj
function writeHTML() {
	// write menu into html
	if (ns4) {
		this.popupobj.document.write(this.stroutput)
		this.popupobj.document.close()
	} else if(ie4plus) {
		this.popupobj.innerHTML=this.stroutput
	} else if(ns6) {
		range = document.createRange();
		range.setStartBefore(this.popupobj);
		domfrag = range.createContextualFragment(this.stroutput);
		while (this.popupobj.hasChildNodes()){
			this.popupobj.removeChild(this.popupobj.lastChild);
		}
		this.popupobj.appendChild(domfrag);
	}
}
// schreibt das menu/den popuptext in die ebene von menuobj
function clearHTML() {
	// write menu into html
	if (ns4) {
		this.popupobj.document.write("")
		this.popupobj.document.close()
	} else if(ie4plus) {
		this.popupobj.innerHTML=""
	} else if(ns6) {
		range = document.createRange();
		range.setStartBefore(this.popupobj);
		domfrag = range.createContextualFragment("");
		while (this.popupobj.hasChildNodes()){
			this.popupobj.removeChild(this.popupobj.lastChild);
		}
		this.popupobj.appendChild(domfrag);
	}
}
// adjust position of layer (from over.js)
function adjustPosition(objEvent) {
	// get size and menu position
	if (this.getSizeAndOffset(objEvent)) {
		// calc leftedge, topedge, rightedge, bottomedge
		var leftedge = this.eventX + this.offsetx
		var topedge  = this.eventY + this.offsety
		if (this.width <= 0) {
			this.width = 140;
		} else if (! this.width) {
			this.width = 340;
		}
		var rightedge  = leftedge + this.width
	  	//alert(this.width);
	  	var bottomedge = topedge + this.height
		// calc borders to the right
		// jb - 03.09.2004 - offset calculation adjusted
		
		if (rightedge > this.screenWidth && this.width < this.screenWidth) {
			//alert(123);
			leftedge = this.eventX - (rightedge +100 - this.screenWidth);
		}
		// jb - 03.09.2004 - offset calculation adjusted
		if (bottomedge > this.screenHeight && this.height < this.screenHeight) {
			topedge = this.eventY  - (bottomedge - this.screenHeight)
		}
		// adjust to 0 if starting point negativ
		if (leftedge < 0) leftedge = 0 + this.scrollX
		if (topedge  < 0) topedge  = 0 + this.scrollY
		this.popupobj.thestyle.left = leftedge + this.unit
		this.popupobj.thestyle.top  = topedge  + this.unit
		// jb - 03.09.2004 - set width and height for the panel
		this.popupobj.thestyle.width = this.width + this.unit
		this.popupobj.thestyle.height  = this.height  + this.unit
	}
}

function getSizeAndOffset(objEvent) {
	this.dct          = false;
	// get size and menu position
	if (ns4) { // tested: ns4.75
		this.scrollX      = window.pageXOffset
		this.scrollY      = window.pageYOffset
		this.screenWidth  = window.innerWidth + this.scrollX
		this.screenHeight = window.innerHeight + this.scrollY
		this.eventX       = objEvent.x
		this.eventY       = objEvent.y
		this.width        = this.popupobj.document.gui.document.width
		this.height       = this.popupobj.document.gui.document.height
	} else {
		if (ie4) { // todo: testing
			this.width        = this.popupobj.offsetWidth
			this.height       = this.popupobj.offsetHeight
			this.scrollX      = document.body.scrollLeft
			this.scrollY      = document.body.scrollTop
			this.screenWidth  = document.body.clientWidth + this.scrollX
			this.screenHeight = document.body.clientHeight + this.scrollY
			this.eventX       = event.clientX + this.scrollX
			this.eventY       = event.clientY + this.scrollY
		} else if (ie5 || ie6plus) {
			// tested: ie5.01 W2KSP4, ie5.5 sp2, ie6.0 sp1
			this.width        = this.popupobj.offsetWidth
			this.height       = this.popupobj.offsetHeight
			this.dct = (document.documentElement.clientWidth != 0 && document.documentElement.clientHeight != 0)
			var doc = (this.dct) ? document.documentElement: document.body;
			this.scrollX      = doc.scrollLeft
			this.scrollY      = doc.scrollTop
			this.screenWidth  = doc.clientWidth + this.scrollX
			this.screenHeight = doc.clientHeight + this.scrollY
			this.eventX       = event.clientX + this.scrollX
			this.eventY       = event.clientY + this.scrollY
		} else if (ns6) { // tested: moz 1.02, moz 1.1.0, moz 1.2.1, moz 1.3.1, moz 1.4.1, ns6.01, ns6.2, ns7.0, ns7.02, ns7.1
			// jb - 03.09.2004 - use first element inside the panel to get a resonable size for it because the div-element has none
			//                   at the first time and results for the right edge might be wrong
			this.width        = this.popupobj.firstChild.scrollWidth
			this.height       = this.popupobj.firstChild.scrollHeight
			this.dct          = (document.doctype) ? true: false ;
			this.scrollX      = window.pageXOffset
			this.scrollY      = window.pageYOffset
			this.screenWidth  = window.innerWidth + this.scrollX
			this.screenHeight = window.innerHeight + this.scrollY
			this.eventX       = objEvent.clientX + this.scrollX
			this.eventY       = objEvent.clientY + this.scrollY
		} else {
			return false
		}
	}
	return true
}

// show an object
function show () {
	this.popupobj.thestyle.visibility = (ns4) ? "show" : "visible";
	this.visible = true
}
// hide an object
function hide () {
	this.popupobj.thestyle.visibility = (ns4) ? "hide" : "hidden";
	this.visible = false
	//objPopup = new Popup();
}
// move an object to x,y
function move() {
	if (this.popupobj != null) {
		this.popupobj.thestyle.left = this.x + ((ns4) ? "" : "px");
		this.popupobj.thestyle.top  = this.y + ((ns4) ? "" : "px");
	}
}
// highlight a table row
function highlight( objEvent, state ) {
	if (document.all) source_el = objEvent.srcElement;
	else if (document.getElementById) source_el = objEvent.target;
	if (source_el.className == this.highlightoff) source_el.id = (state == 'on') ? this.highlighton : '';
	else {
		while (source_el.id != this.name) {
			source_el = document.getElementById ? source_el.parentNode : source_el.parentElement;
			if (source_el.className == this.highlightoff) source_el.id = (state == 'on') ? this.highlighton : '';
		}
	}
}
// end function class Menu


// wrapper for all functions of popmenu.js, over.js and popmenu_fm.js
// funktionen aus popmenu.js/popmenu_fm.js
function showmenu() {
	if (!blnPopup) return;
	// get arguments
	objEvent    = (arguments.length >= 1) ? arguments[0]: event;
	varMenu     = (arguments.length >= 2) ? arguments[1]: null;
	strMenuName = (arguments.length >= 3) ? ((arguments[2].length > 0) ? arguments[2]: "popmenu"): "popmenu";
	intMenuType = (arguments.length >= 4) ? arguments[3]: 0;
	// check requirements
	// hide old menu and set global vars menuobj and menuobj.thestyle
	clearhidemenu();
	objPopup.callByClick = (objEvent.type.toLowerCase() == "click")
	if (objPopup.callByClick && objPopup.visible) {
		objPopup.hide()
	} else {
		objPopup.name = strMenuName
		objPopup.createMenuObject();
		// create menutext from parameter
		// if parameter is instanceof Array then create new menutext
		// else if parameter is a string, then take it for the menu
		if (varMenu.sort) objPopup.createMenu(varMenu, (intMenuType == 1));
		else objPopup.stroutput = varMenu;
	    // if ns4 create a layer-tag
    	if (ns4) objPopup.createNS4Layer();
		// write menu into html
		objPopup.writeHTML()
		// get size and menu position
		objPopup.adjustPosition(objEvent)
		// show menu
		objPopup.show()
	}
	return false;
}
function highlightmenu(objEvent,state) {
	objPopup.highlight(objEvent,state)
}
// common timing calls
function contains_ns6(a, b) {
	while (b && b.parentNode && b.parentNode != "undefined") {
		if ((b = b.parentNode) == a) return true;
	}
	return false;
}
function hidemenu() {
	if (objPopup.popupobj) objPopup.hide();
}
function dynamichide(objEvent) {
	if (!objPopup.callByClick) {
		if (ie4plus && !objPopup.popupobj.contains(objEvent.toElement)) hidemenu();
		else if (ns6 && objEvent.currentTarget != objEvent.relatedTarget && !contains_ns6 (objEvent.currentTarget, objEvent.relatedTarget)) hidemenu();
	}
}
function delayhidemenu() {
	delayhide = setTimeout('hidemenu()',objPopup.timeout);
}
function clearhidemenu() {
	if (window.delayhide) clearTimeout(delayhide);
}
// funktionen aus over.js
function sf_overlib() {
	var text       = arguments[0]
	var title      = arguments[1]
	var id         = arguments[2]
	var style      = arguments[3]
	var popupname  = (arguments.length >= 5) ? arguments[4]: "sf_overDiv";
	var popupwidth = (arguments.length >= 6) ? arguments[5]: 240;

	if (!blnPopup) return;
	// if (ns6) objPopup.offsetx = 10
	objPopup.width = popupwidth
	objPopup.name  = popupname
	objPopup.createMenuObject();
	if (ie4plus) objPopup.adjustPosition(event);
	// capture mouseMove
	document.onmousemove=mouseMove
	if (ns4) document.captureEvents(Event.MOUSEMOVE)
	// create popup
	return sf_drc(text, title, id, style);
}
// Caption popup
function sf_drc(text, title, id, style) {
	objPopup.createMenu([text, style, title, id], true, 0)
	objPopup.writeHTML();
	objPopup.show();
}
// Common calls
function sf_disp() {
	if (!blnPopup) return;
	objPopup.show();
}
function sf_nd(){
	if (!blnPopup) return;
	if (objPopup.popupobj != null) objPopup.hide();
	// capture mouseMove
	document.onmousemove=""
	if (ns4) document.releaseEvents(Event.MOUSEMOVE)
}
function sf_layerWrite(txt) {
	if (txt != null && txt != "" && txt != "undefined") objPopup.stroutput = txt;
	objPopup.writeHTML()
}
function sf_showObject(obj) {
	if (obj != null) objPopup.popupobj = obj;
	objPopup.show()
}
function sf_hideObject(obj) {
	if (obj != null) objPopup.popupobj = obj;
	objPopup.hide()
}
function moveTo( obj, xL, yL ) {
	if (obj != null) objPopup.popupobj = obj;
	objPopup.x = xL
	objPopup.y = yL
	objPopup.move()
}
function mouseMove(objEvent) {
	objPopup.adjustPosition(objEvent)
}

// funktionen aus standard.js
function on(message) {
return true;
	window.status = message;
	window.defaultStatus = window.status;
}

function off() {
	window.status = "Sefrengo CMS";
	window.defaultStatus = window.status;
}


// init menu object
var objPopup = new Popup();
//-->

// debugging code
function sf_showObject(obj, showEmpty) {
	var strText = ""
	var strFunction = ""
	var strLast = "\n"
	for(ele in obj) {
		if (showEmpty || obj[ele] != "") {
			strLast = (strLast != "\n") ? "\n": " --- ";
			if (typeof(obj[ele]) != "function" && ele != "innerHTML") {
				strText += "Element: " + ele + " - " + obj[ele] + strLast
			} else {
				strFunction += ele + "(), "
			}
		}
	}
	alert(strText + "\n" +  strFunction)
}
