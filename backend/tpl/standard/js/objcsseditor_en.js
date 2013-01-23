var cssbuilder = null;

//
// anything following this comment is specific for the css-editor-object
// the functions won't work as standalone
//

//
// copy the selected color to the input field
//
function copyColorFromPicker( colorstring ) {
	var content = this.elementbuilder.value;
	var found = content.match( this.rxpColor );
	this.elementbuilder.value = (found != null) ? content.replace( this.rxpColor, colorstring): colorstring;
	this.getUnitFromValue();
}

//
// shows or hides the colorpicker matrix
//
function showColorPicker(show) {
	// only IE5+/Moz1.x
	if (document.getElementById) {
		document.getElementById(this.picker).style.visibility = (show) ? this.visible: this.hidden;
	}
	// only IE4
	else if (document.all) {
		document.all[this.picker].style.visibility = (show) ? this.visible: this.hidden;
	}
}

//
// create a preview of the style rule using the selected preview type
//
function showPreview( first ) {
	if (first && first >= 0) this.previewType.selectedIndex = first;
	this.previewID = this.previewType.selectedIndex-1;
	if (this.previewID >= 0) {
		// to ensure we only use browser which conform to W3C-DOM
		if (document.getElementById) {
			if (this.elements.value.indexOf("'") > 0) {
				this.writePreview(this.previews[this.previewID].replace('--JBSTIL--', this.elements.value.replace(/\'/gi, "\"") ));
			} else {
				this.writePreview(this.previews[this.previewID].replace('--JBSTIL--', this.elements.value));
			}
		}
		else {
			// No DOM, no preview ...
			alert(this.nopreview, navigator.userAgent);
		}
	}
	else {
		// no preview type selected ...
		if (first && first >= 0) alert(this.choosepreview);
		else this.writePreview('');
	}
}

//
// write any html-string to the preview area
//
function writePreview( strHTML ) {
	// regEx to decide whether we have a background-image with relative, 
	// root-relative or absolute adress.
	var regURL = /(url\(\s*(\/|http:\/\/))/gi;

	this.previewarea.open();

	// set base adress according to background-image path
	if (strHTML.match(regURL) != null) {
		// absolute or root-relative path - set the base adress to domain only
		this.previewarea.write("<html><head><base href=\"" + this.css_preview_baseadr + "\" target=\"_self\"></head>\n")
	} else {
		//relative path - set the base adress to css directory of the projekt
		this.previewarea.write("<html><head><base href=\"" + this.css_preview_baseadr + "cms/css/\" target=\"_self\"></head>\n")
	}
	if (strHTML.indexOf("<body") >= 0) {
		this.previewarea.write(strHTML);
	} else {
		this.previewarea.write("<body>" + strHTML + "</body>");
	}
	this.previewarea.write("</html>\n");
 	this.previewarea.close();
}

//
// adds an array of name-value-pairs to the unit-dropdown
//
function addOptions( liste ) {
	for (var i = 0; i < liste.length; i += 2 ) {
		if ( liste [i+1] != "" ) {
			opt = document.createElement("OPTION");
			opt.value = liste[i];
			opt.text = ( (liste[i] != "") ? "  - ": "") + liste[i+1];
			this.elementunit.add(opt, ((navigator.appName == "Netscape" )?null:this.elementunits.length) );
		}
	}
}

//
// fetches for a given element-name any available value from the editor's textarea
//
function getElementValueFromTextarea() {
	var content = " " + this.elements.value;
	var match = content.match(new RegExp("((^"+this.selectedName+")|([ ;]+"+this.selectedName+"))+:", "i"));
	var start = (match != null) ? content.indexOf(":", match.index)+1: -1;
	if (start >= 0) {
		ende = content.indexOf(";", start);
		if (ende < 0) ende = content.length;
		content = content.substring( start, ende );
		return content.replace( /(\s+$|^\s+)/, "");
	} else
		return "";
}

//
// changes the unit selection when css-element changes
// changed in version 0.6
// added support for colorpicker
//
function resetUnits( num ) {
	// set ELEMENT-Variables
	this.selectedID   = num;
	this.selectedName = this.elementlist.options[this.selectedID].value;
	this.findElement();
	// get unit for css-element from element-array, clear unit-list
	this.elementunits.length = 0;
	this.selectedUnits = (this.selectedID > 0) ? this.csselements[this.selectedElementID+1]: 0;
	if (this.selectedUnits != 0) {
		// create the options-elements and add them to the unit-list
		var probe = 1;
		for ( var i = 0; i < this.cssunitcount ; i++ ) {
			if ( (this.selectedUnits & probe) == probe ) {
				this.addOptions( this.cssunits[i] );
			}
			probe *= 2;
		}
		this.showColorPicker(this.selectedUnits & 0x20);
	}
	if (this.elementunits.length == 0) {
		// no elements added
		this.addOptions( this.cssnone );
	}

	// show unit-list and set focus on inputfield
	this.elementunit.selectedIndex = 0;
	this.elementunit.disabled = (this.selectedUnits == 0);
	this.elementbuilder.value = this.getElementValueFromTextarea();
	this.elementbuilder.focus();
	// set UNIT-Variables
	this.getUnitFromValue();
}

//
// searches the csselement-list for a given name
//
function findElement() {
	this.selectedElementID = -1;
	for(var i = 0; i < this.csselements.length; i += 3 ) {
		if (this.csselements[i] == this.selectedName) {
			this.selectedElementID = i;
			break;
		}
	}
}

//
// searches the option-list of css-elements for a given name
//
function findErrorElement(name) {
	for (var i = 0; i < this.elementlist.options.length; i++) {
		if (name == this.elementlist.options[i].value) {
			this.elementlist.selectedIndex = i;
			this.resetUnits(i);
			break;
		}
	}
}


//
// replaces the unit in the input filed if css-units-dropdown has changed
//
function replaceUnit( num ) {
	if ( num > 0 ) {
		// check the value of the dropdown
		var newunit = this.elementunits[num].value;
		if (newunit != '') {
			// There is a previouslly set value, do replace
			if (this.unitName != null) {
		      	if (this.cssvalue.indexOf(this.unitName) >= 0) {
					// alter wert ist kein token
					if (this.cssvalue.indexOf(newunit) >= 0) {
						// new unit is not a token, replace old unit
						// check special units: url,rgb,#,rect
						check = false;
						switch (this.unitName) {
							case 'rgb':
							case 'url':
							case 'rect':
								regexp = new RegExp( this.unitName + "\(.*?\)", "i") ;
								break;
							case '#':
								regexp = /#[a-f0-9]{3,6}/i;
								break;
							default:
								regexp = this.unitRegExp;
								check = true;
						}
						this.elementbuilder.value = this.elementbuilder.value.replace(regexp, newunit);
						// ensure the unit is displayed in the elementbuilder, but not for the special units: rgb,url,rect,#
						if (this.elementbuilder.value.indexOf(newunit) < 0 && check) this.elementbuilder.value += newunit;
					} else {
						// new unit is a token
						this.elementbuilder.value = newunit;
					}
				} else {
					// old unit was a token ...
					if (this.cssvalue.indexOf(newunit) >= 0) {
						// new unit is not a token ... clear elementbuilder
						this.elementbuilder.value = '';
						this.unitLateValue = newunit;
					} else {
						// new unit is a token .. replace it
						if (this.elementbuilder.value.length > 0) this.elementbuilder.value = this.elementbuilder.value.replace(this.unitName, newunit);
						else this.elementbuilder.value = newunit;
					}
				}
				this.unitID = num;
				this.unitName = newunit;
			} else {
				// no unit selected so far
				// set the unit and set the regexp for the unit
				// if there is content in the elementbuilder ...
				if (this.elementbuilder.value != '') {
					// check if valued unit ... add unit to content
					// if token ... replace value
					if (this.cssvalue.indexOf(newunit) >= 0) {
	            		this.setUnitValue(newunit)
						this.unitLateValue = newunit;
					} else {
						this.elementbuilder.value = newunit;
					}
					this.unitID = num;
					this.unitName = newunit;
					this.unitRegExp = this.findUnitRegExp( newunit );
				} else {
					// so far no content in the elementbuilder ...
					// only add tokens to elementbuilder
					if (this.cssvalue.indexOf(newunit) < 0) {
						this.elementbuilder.value = newunit;
						this.unitID = num;
						this.unitName = newunit;
						this.unitRegExp = this.findUnitRegExp( newunit );
					} else {
						this.unitLateValue = newunit;
					}
				}
			}
		}
	}
}

function getUnitFromValue() {
	this.unitID        = null;
	this.unitName      = null;
	this.unitRegExp    = null;
	this.unitLateValue = '';

	var content = this.elementbuilder.value
	var match = null;
	var start = -1;

	if (this.selectedUnits != 0) {
		// create the options-elements and add them to the unit-list
		var probe = 1;
		for ( var i = 0; i < this.cssunitcount ; i++ ) {
			if ( (this.selectedUnits & probe) == probe ) {
				match = content.match(this.regexp_units[i]);
				if (match != null) {
					// get match 0
					this.unitName = match[0];
					this.unitRegExp = this.regexp_units[i];
					if (this.cssvalue.indexOf(this.unitName) >= 0) this.unitLateValue = this.unitName;
					// get unitID
					for(var i = 0; i < this.elementunits.length; i++) {
						if (this.elementunits[i].value == this.unitName) {
							this.unitID = i;
							this.elementunit.selectedIndex = this.unitID;
							break;
						}
					}
					break;
				}
			}
			probe *= 2;
		}
	}
}

function findUnitRegExp( value ) {
	var probe = 1;
	for ( var i = 0; i < this.cssunitcount ; i++ ) {
		if ( (this.selectedUnits & probe) == probe) {
			for ( var j = 0; j < this.cssunits[i].length; j += 2 ) {
				if (this.cssunits[i][j] == value) return (this.regexp_units[i]);
			}
		}
		probe *= 2;
	}
	return -1;
}

function validateElementValue() {
	var content = this.elementbuilder.value
	var probe = 1;
	var start = -1;
	for ( var i = 0; i < this.cssunitcount ; i++ ) {
		if ( (this.selectedUnits & probe) == probe ) {
			match = content.match(this.regexp_validunits[i]);
			// if (match) for(a in match) alert(a + ":\n" + match[a]);
			start = (match != null) ? match.index: -1;
			if (start >= 0) break;
		}
		probe *= 2;
	}
	return (start >= 0);
}

function setUnitValue(type) {
	switch(type) {
		case "rgb":
		case "rect":
		case "url":
			this.elementbuilder.value = type + "(" + this.elementbuilder.value + ")";
			break;
		case '#':
			this.elementbuilder.value = "#" + this.elementbuilder.value;
			break;
		default:
			this.elementbuilder.value += type;
	}
}

//
// handles a later
//
function setLateValue() {
	if (this.unitLateValue != '') {
		if (this.elementbuilder.value.indexOf(this.unitLateValue) < 0) this.setUnitValue(this.unitLateValue);
	}
}

//
// shows an alert and resets the focus to the input field
//
function alertHint( text ) {
	alert(text);
	this.elementbuilder.focus();
}

function setRuleCheck(theCheckbox) {
  this.blnCheckStyle = theCheckbox.checked;
}

//
// Add an element to the style rule
//
function doCssElement( type ) {
	// check if css-element is selected
	if (this.selectedID == 0) {
		alert(this.chosseelement);
		return false;
	}
	var content = this.elements.value;
	var wert    = this.elementbuilder.value
	// get start position of element in textarea ...
	regexp = new RegExp("((^"+this.selectedName+")|(( |;)"+this.selectedName+"))+:", "i")
	var match = content.match(regexp);
	var start = (match != null) ? match.index: -2;
	start += (start == 0) ? 0: 1;
	var replace = (start >= 0 && content.substring(start-1, start) != "-" );
	// check if user wanted to clear the input field ... do it and leave
	if (type == "clear") {
		this.elementbuilder.value = "";
		return true;
	}
	// check if the user wanted to remove an element ... there is no need for an input then
	if (type == "remove") {
		// if the element exists in textarea .... remove it
		if (replace) this.elements.value  = content.replace(content.substring( start, content.indexOf(";", start)+1 ), '');
	}
	else {
		// check if we have any value in the elementbuilder ...
		if (wert == null || wert == "") {
			this.alertHint(this.entervalue0 + this.selectedName + this.entervalue1);
			return false;
		}
		// check if the value is valid, if checkstyle is enabled and checked
    if (this.blnCheckStyle) {
      if (!this.validateElementValue()) {
      	// check if a unit is selected ... if so --> error message
      	if (!confirm(this.confirmvalue)) return false;
      }
    }
		// remove element-name, : and ; from wert
//    alert(this.selectedUnits)
    if ((this.selectedUnits & 0x0000010) == 0x0000010 || (this.selectedUnits & 0x0100000) == 0x0100000) {
      wert = wert.replace( new RegExp("((" + this.selectedName + " *:|;) )*", "i"), "");
		} else {
  		wert = wert.replace( new RegExp("((" + this.selectedName + " *:|:|;) )*", "i"), "");
    }
		// remove whitespaces at begin or end of the value
		wert = wert.replace( /(\s+$|^\s+)/, "");
		// compose css-defintion
		var definition = " " + this.selectedName + ": " + wert + ";";
		// replace or add css-definition
		if (replace) // overwrite
			this.elements.value  = content.replace(content.substring( start, content.indexOf(";", start)+1 ), definition);
		else // add
			this.elements.value += definition;
	}
	// normalize css-elements
	this.elements.value  = this.elements.value.replace( /(\s+$|^\s+)/, "");
	this.elements.value  = this.elements.value.replace( /\s{2,}/, " ");
	// get unit settings
	this.getUnitFromValue();
	// refresh preview
	if (this.previewID >= 0) this.showPreview();
}

//
// enables the css-editor fields and drops downs
//
function initCssEditor( first ) {
	// Editor-Elemente aktivieren
	this.elementlist.disabled = false;
	this.elementlist.selectedIndex = 0;
	this.elementbuilder.disabled = false;
	this.elementbuilder.value = "";
	this.elementunit.selectedIndex = 0;
	this.elementunit.disabled = true;
	// Stilvorschau anzeigen
	if (first) this.showPreview(first);
}

//
// object: csseditor
//
// object for the wysiwyg-css-editor for web-pages
//
//
function csseditor ( first, cssconfig ) {

	// some constants and strings
	this.css_preview_baseadr = cssconfig.ddx_css_preview_baseadr;
	this.previewname   		 = cssconfig.ddx_previewname;
	this.picker        		 = cssconfig.ddx_picker;
	this.visible       		 = cssconfig.ddx_visible;
	this.hidden        		 = cssconfig.ddx_hidden;
	this.nopreview     		 = cssconfig.ddx_nopreview;
	this.choosepreview 		 = cssconfig.ddx_choosepreview;
	this.chosseelement 		 = cssconfig.ddx_chosseelement;
	this.entervalue0   		 = cssconfig.ddx_entervalue0;
	this.entervalue1   		 = cssconfig.ddx_entervalue1;
	this.confirmvalue  		 = cssconfig.ddx_confirmvalue;

	// parameter
	this.editor	= document.editstyle;					// id of the editor form
	this.name = this.editor.name;						// name of the input field for the name of the css-rule
	this.description = this.editor.description			// name of the input field for the description of the css-rule-name
	this.elements = this.editor.styles					// name of the textarea for the elements of the css-rule
	// builder parameter
	this.elementlist = this.editor.stilelement;			// name of the list of available css-elements for the element builder
	this.elementunit = this.editor.stileinheit;			// name of the list of available css-units for the element builder
	this.elementunits = this.elementunit.options;		// option list of available css-units for the element builder
	this.elementbuilder = this.editor.stilelementwert;	// name of the input field for the element builder
	// preview parameter
	this.previewType = this.editor.vorschautyp;			// name of the select list of the preview drop-down
	this.previewarea = window.frames[this.previewname].document;	// preview-area
  // checkstyle
  this.blnCheckStyle = this.editor.elements['checkstyle'].checked

	// builder parameter - for editing of any style
	this.selectedID   	   = 0;
	this.selectedName 	   = null;
	this.selectedUnits	   = 0;
	this.selectedElementID = -1;
	this.unitID       	   = 0;
	this.unitName     	   = null;
	this.unitRexExp   	   = null;
	this.unitLateValue	   = '';
	this.previewID    	   = 0;

	// methods for the css-editor
	this.copyColor = copyColorFromPicker;
	this.showColorPicker = showColorPicker;
	this.showPreview = showPreview;
	this.addOptions = addOptions;
	this.resetUnits = resetUnits;
	this.getElementValueFromTextarea = getElementValueFromTextarea;
	this.alertHint = alertHint;
	this.doCssElement = doCssElement;
	this.initCssEditor = initCssEditor;
	this.writePreview = writePreview;
	this.getUnitFromValue = getUnitFromValue;
	this.replaceUnit = replaceUnit;
	this.findElement = findElement;
	this.validateElementValue = validateElementValue;
	this.findUnitRegExp = findUnitRegExp;
	this.setLateValue = setLateValue;
	this.findErrorElement = findErrorElement;
  this.setUnitValue = setUnitValue
  this.setRuleCheck = setRuleCheck

	// list of regExp
	this.rxpColor = /transparent|aqua|black|blue|fuchsia|yellow|white|teal|silver|red|purple|olive|navy|maroon|lime|green|gray|(#[A-F0-9]{3,6})|rgb\((\s*?\d{1,3}%?\s*?,?){3,3}\s*?\)/ig;
	this.rxpLength = /pt|px|cm|mm|in|em|ex/i;
	this.rxpAuto   = /auto/i;
	this.rxpPercent= /\%/i;
	this.rxpDefault= /normal/i;
	this.rxpUrl = /url|none/i;
	this.rxpColors = /aqua|black|blue|fuchsia|yellow|white|teal|silver|red|purple|olive|navy|maroon|lime|green|gray|#|rgb/i;
	this.rxpFonts = /.*/i;
	this.rxpFsizes = /xx-small|x-small|medium|xx-large|x-large|larger|smaller|small|large/i;
	this.rxpFstyles = /normal|italic|oblique/i;
	this.rxpFweights = /normal|bolder|lighter|bold|light|[1-9]00/i;
	this.rxpFvariants = /normal|small-caps/i;
	this.rxpTdecorations = /none|underline|overline|line-through|blink/i;
	this.rxpTtransforms = /none|capitalize|uppercase|lowercase/i;
	this.rxpTaligns = /left|right|center|justify/i;
	this.rxpTvaligns = /baseline|sub|super|text-top|text-bottom|middle|top|bottom/i
	this.rxpBgrepeats = /no-repeat|repeat-x|repeat-y|repeat/i
	this.rxpBgattachments = /scroll|fixed/i;
	this.rxpBgpositions = /top|center|bottom|left|right/i
	this.rxpBorderstyles = /none|dotted|dashed|solid|double|groove|rigde|inset|outset/i
	this.rxpBorderwidths = /thin|medium|thick|none/i;
	this.rxpLists = /disc|circle|square|decimal|lower-roman|upper-roman|lower-alpha|upper-alpha|none|inside|outside|url/i
	this.rxpWhitespaces = /normal|pre|nowrap/i;
	this.rxpFloats = /none|left|right|both/i;
	this.rxpDisplays = /block|inline|list-item|none/i;
	this.rxpPositions = /fixed|absolute|relative/i;
	this.rxpVisibilities = /visible|hidden|inherit/i;
	this.rxpTransparent = /transparent/i;
	this.rxpValidColors = /aqua|black|blue|fuchsia|yellow|white|teal|silver|red|purple|olive|navy|maroon|lime|green|gray|#[a-f0-9]{3,6}|rgb\([^\)]*?\)/i;
	this.rxpValidUrl = /url\([^\)]*?\)|none/i;
	this.rxpValidPercent= /[\d\.]*?\d\%/i;
	this.rxpValidLength = /(?:[\d\.]*?\d(pt|px|cm|mm|in|em|ex))|0/i;
	this.rxpValidLists = /disc|circle|square|decimal|lower-roman|upper-roman|lower-alpha|upper-alpha|none|inside|outside|url\([^\)]*?\)/i



	// array of the previews available
	this.previews = new Array("<p style='--JBSTIL--'>Styled text</p>",
							  "<p><span style='--JBSTIL--'>Styled text</span></p>",
							  "<table border='1'><tr><td style='--JBSTIL--'>Styled text</td></tr></table>",
							  "<ul><li style='--JBSTIL--'>Styled text</li></ul>",
							  "<ol><li style='--JBSTIL--'>Styled text</li></ol>",
							  "<p><a href='javascript:void(0)' style='--JBSTIL--'>Styled text</a></p>",
							  "<p><img src='tpl/standard/img/but_cancel.gif' style='--JBSTIL--'><br>Styled picture</p>",
							  "<p><a href='javascript:void(0)'><img src='tpl/standard/img/but_cancel.gif' style='--JBSTIL--'></a><br>Styled picture with link</p>",
							  "<p><a href='javascript:void(0)'><img src='tpl/standard/img/but_cancel.gif' border='0' style='--JBSTIL--'></a><br>Styled picture with link</p>");

	//
	// array with all information of css-elements
	// corresponding to the select list in css_edit_form.inc.php
	//
	// element, allowed units, hintid
	//
	// allowed units:
	//	      0 - selection disabled
	//		  1 - length units
	//		  2 - auto unit
	//		  4 - procent unit
	//		  8 - normal unit
	//	     16 - url unit
	//	     32 - color units
	//	     64 - font standards
	//	    128 - font-size standards
	//	    256 - font-style standards
	//	    512 - font-weight standards
	// 	   1024 - font-variant standards
	//	   2048 - text-decoration standards
	//	   4096 - text-transform standards
	// 	   8192 - text-align standards
	//    16384 - vertical-align standards
	//    32768 - background-repeat standards
	//    65536 - background-attachment standards
	//   131072 - background-position standards
	//   262144 - border-style standards
	//   524288 - border-width standards
	//  1048576 - list-styles standards
	//  2097152 - white-spaces standards
	//  4194304 - clear-float standards
	//  8388608 - display standards
	// 16777216 - position standards
	// 33554432 - visible standards
	// 67108864 - transparent standards
	//134217727 - all values
	//
	this.csselements  = new Array (""					,  0x0000000, 0,
							  "font-family"				,  0x0000040, 0,
							  "font-size"				,  0x0000085, 0,
							  "font-size-adjust"		,  0x0000000, 0,
							  "font-stretch"			,  0x0000000, 0,
							  "font-style"				,  0x0000100, 0,
							  "font-variant"			,  0x0000400, 0,
							  "font-weight"				,  0x0000200, 0,
							  "color"					,  0x0000020, 0,

							  "letter-spacing"			,  0x0000009, 0,
							  "word-spacing"			,  0x0000009, 0,
							  "line-height"				,  0x000000D, 0,
							  "text-align"				,  0x0002000, 0,
							  "vertical-align"			,  0x0004000, 0,
							  "text-decoration"			,  0x0000800, 0,
							  "text-indent"				,  0x0000005, 0,
							  "text-shadow"				,  0x0000000, 0,
							  "text-transform"			,  0x0001000, 0,

							  "background-color"		,  0x4000020, 0,
							  "background-image"		,  0x0000010, 0,
							  "background-repeat"		,  0x0008000, 0,
							  "background-attachment"	,  0x0010000, 0,
							  "background-position"		,  0x0020000, 0,

							  "width"					,  0x0000007, 0,
							  "height"					,  0x0000007, 0,
							  "clear"					,  0x0400000, 0,
							  "float"					,  0x0400000, 0,
							  "display"					,  0x0800000, 0,
							  "white-space"				,  0x0200000, 0,

							  "border-color"			,  0x0000020, 0,
							  "border-style"			,  0x0040000, 0,
							  "border-width"			,  0x0080001, 0,
							  "border-left"				,  0x00C0021, 0,
							  "border-left-width"		,  0x0080001, 0,
							  "border-right"			,  0x00C0021, 0,
							  "border-right-width"		,  0x0080001, 0,
							  "border-top"				,  0x00C0021, 0,
							  "border-top-width"		,  0x0080001, 0,
							  "border-bottom"			,  0x00C0021, 0,
							  "border-bottom-width"		,  0x0080001, 0,

							  "padding"					,  0x0000005, 0,
							  "padding-bottom"			,  0x0000005, 0,
							  "padding-left"			,  0x0000005, 0,
							  "padding-right"			,  0x0000005, 0,
							  "padding-top"				,  0x0000005, 0,

							  "margin"					,  0x0000007, 0,
							  "margin-bottom"			,  0x0000007, 0,
							  "margin-left"				,  0x0000007, 0,
							  "margin-right"			,  0x0000007, 0,
							  "margin-top"				,  0x0000007, 0,

							  "list-style-type"			,  0x0100000, 0,
							  "list-style-image"		,  0x0100000, 0,
							  "list-style-position"		,  0x0100000, 0,

							  "position"				,  0x1000000, 0,
							  "left"					,  0x0000001, 0,
							  "right"					,  0x0000001, 0,
							  "top"						,  0x0000001, 0,
							  "bottom"					,  0x0000001, 0,
							  "width"					,  0x0000007, 0,
							  "height"					,  0x0000007, 0,
							  "z-Index"					,  0x0000000, 0,
							  "visibility"				,  0x2000000, 0 );

	//
	// arrays for the different css-units
	//
	this.csslength        = new Array ("", "Length", "pt", "Point", "px", "Pixel", "cm", "Centimeter", "mm", "Millimeter", "in", "Inch", "em", "EM", "ex", "EX");
	this.cssauto          = new Array ("auto", "automatic");
	this.csspercent       = new Array ("%", "Percent");
	this.cssdefault       = new Array ("normal", "normal");
	this.cssurl           = new Array ("", "URL", "url", "URL", "none", "none");
	this.csscolors        = new Array ("", "Colors", "rgb", "RGB", "#", "Hexadecimal", "aqua", "Aqua", "black", "Black", "blue", "Blue", "fuchsia", "Fuchsia", "gray", "Gray", "green", "Green", "lime", "Lime", "maroon", "Maroon", "navy", "Navy", "olive", "Olive", "purple", "Purple", "red", "Red", "silver", "Silver", "teal", "Teal", "white", "White", "yellow", "Yellow");
	this.csstransparent   = new Array ("transparent", "transparent");
	this.cssfonts         = new Array ("", "Fonts", "Arial, Helvetica, sans-serif", "Arial, Helvetica, sans-serif", "Verdana, Arial, Helvetica, sans-serif", "Verdana, Arial, Helvetica, sans-serif", "Times New Roman, Times, serif", "Times New Roman, Times, serif", "Georgia, Times New Roman, Times, serif", "Georgia, Times New Roman, Times, serif", "Courier New, Courier, monospaced", "Courier New, Courier, monospaced");
	this.cssfsizes        = new Array ("", "Font size", "xx-small", "xx-small", "x-small", "x-small", "small", "small", "medium", "medium", "large", "large", "x-large", "x-large", "xx-large", "xx-large", "larger", "larger", "smaller", "smaller");
	this.cssfstyles       = new Array ("", "Font style", "normal", "Normal", "italic", "Italic", "oblique", "Oblique");
	this.cssfweights      = new Array ("", "Font weight", "normal", "Normal", "bold", "Bold", "bolder", "Bolder", "lighter", "Lighter", "100", "100", "200", "200", "300", "300", "400", "400", "500", "500", "600", "600", "700", "700", "800", "800", "900", "900");
	this.cssfvariants     = new Array ("", "Font variant", "normal", "Normal", "small-caps", "Small-caps");
	this.csstdecorations  = new Array ("", "Text decoration", "none", "None", "underline", "Underline", "overline", "Overline", "line-through", "Line-through", "blink", "Blink");
	this.cssttransforms   = new Array ("", "Text transform", "none", "None", "capitalize", "Capitalize", "uppercase", "Uppercase", "lowercase", "Lowercase" );
	this.csstaligns       = new Array ("", "Text align", "left", "Left", "right", "Right", "center", "Center", "justify", "Justify");
	this.csstvaligns      = new Array ("", "Vertical align", "baseline", "Baseline", "sub", "Sub", "super", "Super", "top", "Top", "text-top", "Text-Top", "middle", "Middle", "bottom", "Bottom", "text-bottom", "Text-Bottom");
	this.cssbgrepeats     = new Array ("", "Background repeat", "no-repeat", "No repeat", "repeat-x", "Repeat X", "repeat-y", "Repeat Y", "repeat", "Repeat" );
	this.cssbgattachments = new Array ("", "Background attachment", "scroll", "Scroll", "fixed", "Fixed");
	this.cssbgpositions   = new Array ("", "Background position", "top", "Top", "center", "Center", "bottom", "Bottom", "left", "Left", "right", "Right");
	this.cssborderstyles  = new Array ("", "Border style", "none", "None", "dotted", "Dotted", "dashed", "Dashed", "solid", "Solid", "double", "Double", "groove", "Groove", "rigde", "Ridge", "inset", "Inset", "outset", "Outset");
	this.cssborderwidths  = new Array ("", "Border wieght", "thin", "Thin", "medium", "Medium", "thick", "Thick", "none", "None");
	this.csslists         = new Array ("", "List style", "disc", "Disc", "circle", "Circle", "square", "Square", "decimal", "Decimal", "lower-roman", "Lower roman", "upper-roman", "Upper roman", "lower-alpha", "Lower alpha", "upper-alpha", "Upper alpha", "none", "None", "inside", "Inside", "outside", "Outside", "url", "list image");
	this.csswhitespaces   = new Array ("", "Whitespaces", "normal", "Normal", "pre", "Predefined", "nowrap", "No wrap");
	this.cssfloats        = new Array ("", "Float", "none", "None", "left", "Left", "right", "Right","both", "Both");
	this.cssdisplays      = new Array ("", "Display", "block", "Block", "inline", "Inline", "list-item", "list-Item", "none", "None");
	this.csspositions     = new Array ("", "Position", "fixed", "Fixed", "absolute", "Absolute", "relative", "Relative");
	this.cssvisibilities  = new Array ("", "Visibility", "visible", "Visible", "hidden", "Hidden", "inherit", "Inherit");
	this.cssnone 		  = new Array ("", "Possible units ...");

	//
	// all units-postfixes or prefixes that require a value
	//
	this.cssvalue = "exptcmpxmminem%#urlrgbrect"

	//
	// array for all css-units
	//
	this.cssunits = new Array( this.csslength, this.cssauto, this.csspercent, this.cssdefault, this.cssurl, this.csscolors,
						this.cssfonts, this.cssfsizes, this.cssfstyles, this.cssfweights, this.cssfvariants,
						this.csstdecorations, this.cssttransforms, this.csstaligns, this.csstvaligns,
						this.cssbgrepeats, this.cssbgattachments, this.cssbgpositions,
						this.cssborderstyles, this.cssborderwidths,
						this.csslists, this.csswhitespaces, this.cssfloats, this.cssdisplays,
						this.csspositions, this.cssvisibilities, this.csstransparent );

	this.regexp_units = new Array ( this.rxpLength, this.rxpAuto, this.rxpPercent, this.rxpDefault, this.rxpUrl, this.rxpColors,
							this.rxpFonts, this.rxpFsizes, this.rxpFstyles, this.rxpFweights, this.rxpFvariants,
							this.rxpTdecorations, this.rxpTtransforms, this.rxpTaligns, this.rxpTvaligns,
							this.rxpBgrepeats, this.rxpBgattachments, this.rxpBgpositions,
							this.rxpBorderstyles, this.rxpBorderwidths,
							this.rxpLists, this.rxpWhitespaces, this.rxpFloats, this.rxpDisplays,
							this.rxpPositions, this.rxpVisibilities, this.rxpTransparent );

	this.regexp_validunits = new Array ( this.rxpValidLength, this.rxpAuto, this.rxpValidPercent, this.rxpDefault, this.rxpValidUrl, this.rxpValidColors,
								this.rxpFonts, this.rxpFsizes, this.rxpFstyles, this.rxpFweights, this.rxpFvariants,
								this.rxpTdecorations, this.rxpTtransforms, this.rxpTaligns, this.rxpTvaligns,
								this.rxpBgrepeats, this.rxpBgattachments, this.rxpBgpositions,
								this.rxpBorderstyles, this.rxpBorderwidths,
								this.rxpValidLists, this.rxpWhitespaces, this.rxpFloats, this.rxpDisplays,
								this.rxpPositions, this.rxpVisibilities, this.rxpTransparent );

	//
	// get some constants
	//
	this.cssunitcount = this.cssunits.length;

	//
	// we got all parameters and arrays, lets show the editor
	//
	this.initCssEditor( first );
}