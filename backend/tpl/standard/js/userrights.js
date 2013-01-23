/* controll for user rights management */
function userrights() {
  this.formname      = (arguments.length >= 1) ? arguments[0] : 'rights'    ;
  this.panelname     = (arguments.length >= 2) ? arguments[1] : 'rightsmenu';

  // Flag zur Pr&uuml;fung ob RP angezeigt wird
  this.panelvisible = false;
  
  // Elemente zum Ausblenden bevor das Panel angezeigt wird
  this.hideUnwanted = new Array();
  
  // HTML-Ausgabe vorbereiten, Klassen und HTML-Konstrukt setzen
  this.radioname       = "uright";
  this.selectname      = "rmgruppe";
  this.checkinherit      = "rmerben";
  this.checkueberschreiben = "rmueberschreiben";

  this.adjustposition = true;

  // form shortcuts
  this.formele = null
  this.panelelements = null
  // groups and rights shortcuts
  this.groupidselement      = 'cms_gruppenids'
  this.groupids             = null
  this.groupids_origin      = null
  this.groupselement        = 'cms_gruppen'
  this.groups               = null
  this.groups_origin        = null
  this.groupsrightselement  = 'cms_gruppenrechte'
  this.groupsrights         = null
  this.groupsrights_origin  = null
  this.groupsinheritelement = 'cms_gruppenrechtegeerbt'
  this.groupsinherit        = null
  this.groupsinherit_origin = null
  this.groupsoverwriteelement = 'cms_gruppenrechteueberschreiben'
  this.groupsoverwrite        = null
  this.groupsoverwrite_origin = null
  
  // methods
  this.createRightPanel  = createRightPanel
  this.saveRights        = saveRights
  this.showRightsOfGroup = showRightsOfGroup
  this.cancelRights      = cancelRights
  this.setPanelShortCut  = setPanelShortCut
  this.showRightPanel    = showRightPanel
  this.hideRightPanel    = hideRightPanel
  this.addUser           = addUser
  this.set_area          = set_area
  this.collectUnwanted   = collectUnwanted
  this.handleRadioReadonly = handleRadioReadonly
  
  this.cms_rmpopup = new Popup()
  
  // panel methods
  this.showRP                  = showRP
  this.hideRP                  = hideRP
  this.highlightRP             = highlightRP
  this.dynamichideRP           = dynamichideRP
  this.delayhideRP             = delayhideRP
  this.clearhideRP             = clearhideRP
  this.setVisibilityUnwanted   = setVisibilityUnwanted 
}

//disable perm radios if user choser inerhit from parent element
function handleRadioReadonly() {
	var is_disabled = (this.panelelements[this.checkinherit].checked == true) ? true : false;
	for(i = 0; i <= 32; i++) {
		objRadio = this.panelelements[this.radioname+i]
		if (objRadio) {
			this.panelelements[this.radioname+i][0].disabled = is_disabled
			this.panelelements[this.radioname+i][1].disabled = is_disabled
		}
	}
	
	if (is_disabled) {
	   this.panelelements[this.checkueberschreiben].checked = false
	   this.groupsoverwrite[this.panelelements[this.selectname].selectedIndex] = ''
       this.formele[this.groupsoverwriteelement].value = this.groupsoverwrite.join(",")
	}
	this.panelelements[this.checkueberschreiben].disabled = is_disabled

}

// sets or clears the checkboxes of a whole area
function set_area(strArea, intCount, blnSelect) {
	strName = strArea + "_";
	for(i = 1; i <= 32; i++) {
		objCheckbox = document.perms.elements[strName + i]
		if (objCheckbox) objCheckbox.checked = blnSelect;
	}
}


function createRightPanel() {
	// Erstelle Panelteile
	// Set properties for form access
	// Shortcuts for element acces
	this.formele = document.forms[this.formname].elements
	// Get usergroups, rights, group rights into arrays
	this.groups              = this.formele[this.groupselement].value.split(",")
	this.groups_origin       = this.formele[this.groupselement].value.split(",")
	this.groupids            = this.formele[this.groupidselement].value.split(",")
	this.groupids_origin     = this.formele[this.groupidselement].value.split(",")
	this.groupsrights        = this.formele[this.groupsrightselement].value.split(",")
	this.groupsrights_origin = this.formele[this.groupsrightselement].value.split(",")
	this.groupsinherit        = this.formele[this.groupsinheritelement].value.split(",")
	this.groupsinherit_origin = this.formele[this.groupsinheritelement].value.split(",")
	this.groupsoverwrite        = this.formele[this.groupsoverwriteelement].value.split(",")
	this.groupsoverwrite_origin = this.formele[this.groupsoverwriteelement].value.split(",")
	
	// und gebe es im Rechtepopup aus
	this.cms_rmpopup.name = this.panelname
	this.cms_rmpopup.createMenuObject();
	// if ns4 create a layer-tag
//	if (ns4) cms_rmpopup.createNS4Layer();
	// write menu into html
//	cms_rmpopup.writeHTML()
}

// cancel rights changes
function cancelRights( blnClose ) {
  this.formele[this.groupsrightselement].value  = this.groupsrights_origin.join(",")
  this.formele[this.groupselement].value        = this.groups_origin.join(",")
  this.formele[this.groupidselement].value      = this.groupids_origin.join(",")
  this.formele[this.groupsinheritelement].value = this.groupsinherit_origin.join(",")
  this.formele[this.groupsoverwriteelement].value = this.groupsoverwrite_origin.join(",")
  if (blnClose) this.hideRightPanel();
}

// save the rights of the current group into the hidden field of the formular
function saveRights( blnClose, intRights ) {
  var result = 0
  for (var i = 0; i < intRights; i++) {
    result += (this.panelelements[this.radioname+i][1].checked) ? parseInt(this.panelelements[this.radioname+i][1].value): 0;
  }
  this.groupsrights[this.panelelements[this.selectname].selectedIndex] = result
  this.groupsinherit[this.panelelements[this.selectname].selectedIndex] = (this.panelelements[this.checkinherit].checked == true) ? 1: ''
  this.groupsoverwrite[this.panelelements[this.selectname].selectedIndex] = (this.panelelements[this.checkueberschreiben].checked == true) ? 1: ''
  this.formele[this.groupsrightselement].value = this.groupsrights.join(",")
  this.formele[this.groupsinheritelement].value = this.groupsinherit.join(",")
  this.formele[this.groupsoverwriteelement].value = this.groupsoverwrite.join(",")
  if (blnClose) this.hideRightPanel();
}

// show the rights for the current group
function showRightsOfGroup(intRights) {
  this.setPanelShortCut();
  var intGroupRights = this.groupsrights[this.panelelements[this.selectname].selectedIndex]
  for (var i = 0; i < intRights; i++) {
	var intMask = parseInt(this.panelelements[this.radioname+i][1].value)
    this.panelelements[this.radioname+i][((intMask & intGroupRights)/intMask)].checked = true
  }
  
  var isInherit = (this.groupsinherit[this.panelelements[this.selectname].selectedIndex] == 1) ? true:false;
  this.panelelements[this.checkinherit].checked = isInherit
  this.handleRadioReadonly()
  
  var isOverwrite = (this.groupsoverwrite[this.panelelements[this.selectname].selectedIndex] == 1) ? true:false;
  this.panelelements[this.checkueberschreiben].checked = isOverwrite

  
  this.panelelements[this.selectname].focus();
}

// sets the shortcut for panel acces
function setPanelShortCut() {
  this.panelelements = (ns4) ? eval("document." + objPopup.name + ".document.gui.document.forms['"+this.formname+"'].elements;"): document.forms[this.formname].elements;
}

// shows the rights panel, prior hides an unwanted element on the screen
// to do: ns4-compatibility
function showRightPanel(objEvent, intRights) {
  // Ermittle Dropdowns und Menulisten
  this.collectUnwanted("fileuploads");
  this.collectUnwanted();
  // Anzeigen
  setVisibilityUnwanted(this.hideUnwanted, "hidden");
  this.showRP(objEvent);
  this.showRightsOfGroup(intRights);
  this.panelvisible = true;
}

// hides the rights panel, afterwards shows the unwanted again
// to do: ns4-compatibility
function hideRightPanel() {
  	this.hideRP();
	this.setVisibilityUnwanted(this.hideUnwanted, "visible");
	this.panelvisible = false;
}

function collectUnwanted() {
	// selects k&ouml;nnen mit ns4 nicht ausgeblendet werden
	if (ns4) return true;
	// suche alle select-listen und speichere diese f&uuml;r's ausblenden
	var form_name = (arguments.length >= 1) ? arguments[0]: this.formname;
	if (document.forms[form_name]) {
		objElements = document.forms[form_name].elements
		intMax  = objElements.length 
	  	for(var i = 0; i < intMax; i++) {
			if (objElements[i]) {
				if (objElements[i].tagName.toLowerCase() == "select" && objElements[i].name.toLowerCase().indexOf('rmgruppe') < 1 && objElements[i].name.toLowerCase() != this.selectname.toLowerCase() ) {
					this.hideUnwanted[this.hideUnwanted.length] = objElements[i];
				}
			}
		}
	}
}

//
// 
function addUser() {
	alert("to do ... jb");
}


//
// display controller for rights panel to be used in all pages with popup-menu-styled rights-panel
//
function showRP() {
	if (!blnPopup) return;
	// get arguments
	objEvent    = (arguments.length >= 1) ? arguments[0]: event;
	noSubClick  = (arguments.length >= 3) ? arguments[2]: true;
	// check requirements
	// hide old menu and set global vars menuobj and menuobj.thestyle
	
	clearhidemenu();
	this.cms_rmpopup.callByClick = (objEvent.type.toLowerCase() == "click")
	if (this.cms_rmpopup.callByClick && this.cms_rmpopup.visible && noSubClick) {
		//feature is not in use and not multiple object save
		//if (cms_rm.hideUnwanted != null) cms_rm.hideRightPanel();
		this.cms_rmpopup.hide()
	} else {
		// get size and menu position
		
		//disable adjustposition feature is not in use and not multiple object save
		//if (cms_rm.adjustposition) 
		this.cms_rmpopup.adjustPosition(objEvent);
		// show menu
		this.cms_rmpopup.show()
	}
	return false;
}
function hideRP() {
	if (this.cms_rmpopup.popupobj) this.cms_rmpopup.hide();
}
function highlightRP(objEvent,state) {
	this.cms_rmpopup.highlight(objEvent,state)
}
function dynamichideRP(objEvent) {
	if (!this.cms_rmpopup.callByClick) {
		if (ie4 && !this.cms_rmpopup.popupobj.contains(objEvent.toElement)) this.hideRP();
		else if (ns6 && objEvent.currentTarget != objEvent.relatedTarget && !contains_ns6 (objEvent.currentTarget, objEvent.relatedTarget)) this.hideRP();
	}
}
function delayhideRP() {
	delayhide = setTimeout('hideRP()',this.cms_rmpopup.timeout);
}
function clearhideRP() {
	if (window.delayhide) clearTimeout(delayhide);
}
function setVisibilityUnwanted( objElements, strVisible ) {
	if (objElements != null) {
		for(var i = 0; i < objElements.length; i++) {
			objElements[i].style.visibility = strVisible;
		}
	}
}

/* init object - used by group_config*/
var cms_rm  = new userrights();
/* init object and create rights panel */
//var cms_rmpopup = new Popup();
