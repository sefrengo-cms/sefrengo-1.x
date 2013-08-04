<!--<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transistional.dtd">-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
	<title>{rbLangHtmlTitle}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<style type="text/css" media="all">
*{
padding:0;
margin:0;
}
.rbTableRow1{
height:34px;
background:#DFEEFF;
}
.rbTableRow1-1{
background:#DFEEFF url({rbImagePath}rb-kopf.gif) no-repeat left;
}
.rbTableRow2-1{
width: 101px;
height:6px;
}
.rbTableRow2-2,.rbTableRow2-1{
background: #DFEEFF url({rbImagePath}rb-border.gif) repeat-x bottom;
}
.rbTableRow3-1{
width: 101px;
background-image:url({rbImagePath}rb-drum.gif);
}
.rbTableRow3-2{
}
.rbTableRow4-1{
width: 101px;
height:6px;
}
.rbTableRow4-2{
background-image:url({rbImagePath}rb-4-1.gif);
}
.rbTableRow5-1{
height:40px;
background-color:#DFEEFF;
}
.rbRessourceChoser{
background:#F0F8FF url({rbImagePath}rb-border.gif) repeat-y left;
font-family:verdana, helvetica, arial, geneva, sans-serif;
font-size: 10px;
color:#000000;
padding:7px 0px 7px 0px;
text-align:center;
border-bottom:1px solid #C7D5EB;
cursor:pointer;
width:100px;
}
.rbRessourceChoserActive{
background:#E7F3FF url({rbImagePath}rb-border.gif) repeat-y left;
font-family:verdana, helvetica, arial, geneva, sans-serif;
color:#000000;
font-size: 10px;
padding:7px 0px 7px 0px;
text-align:center;
border-bottom:1px solid #C7D5EB;
cursor: pointer;
width:100px;
}
.rbTablePickerRow1-1{
font-family:verdana, helvetica, arial, geneva, sans-serif;
font-size: 10px;
font-weight:bold;
color:#5A7BAD;
width:101px;
text-align:right;
}
#sf_rb_picker{
width:100%;
background-color:#F4F7FB;
border: #7B95BD 1px solid;
}
.rbActionButton{
font-family:verdana, helvetica, arial, geneva, sans-serif;
font-size: 10px;
font-weight:bold;
color:#5A7BAD;
width:90px;
height:22px;
background-color:#F4F7FB;
border: #C7D5EB 2px ridge;
cursor:pointer;
}
.rbActionButtonOver{
font-family:verdana, helvetica, arial, geneva, sans-serif;
font-size: 10px;
font-weight:bold;
color:#5A7BAD;
width:90px;
height:22px;
background-color:#F4F7FB;
border: #AAD52C 3px ridge;
cursor:pointer;
}
.rbPathway {
background-color:#ffffff;
color:#000000;
margin-top :7px;
border: 1px solid #B7D9FF;
width:100%;
}
input.sf_buttonAction,
input.sf_buttonActionCancel {
	border: 1px solid #8E8E8E;
	color: #000000;
	cursor: pointer;
	font: bold 10px verdana, helvetica, arial, geneva, sans-serif;
	margin: 5px 0;
	padding: 5px 0;
	width: 90px;
	background: #fbfbfb; /* Old browsers */
	background: -moz-linear-gradient(top, #fbfbfb 44%, #e2e2e2 56%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(44%,#fbfbfb), color-stop(56%,#e2e2e2)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top, #fbfbfb 44%,#e2e2e2 56%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top, #fbfbfb 44%,#e2e2e2 56%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top, #fbfbfb 44%,#e2e2e2 56%); /* IE10+ */
	background: linear-gradient(top, #fbfbfb 44%,#e2e2e2 56%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fbfbfb', endColorstr='#e2e2e2',GradientType=0 ); /* IE6-9 */
}
input.sf_buttonAction:active,
input.sf_buttonAction:focus,
input.sf_buttonAction:hover {
	border: 1px solid #99CC01;
	background: #f8fbec; /* Old browsers */
	background: -moz-linear-gradient(top, #f8fbec 44%, #e1efb4 56%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(44%,#f8fbec), color-stop(56%,#e1efb4)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top, #f8fbec 44%,#e1efb4 56%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top, #f8fbec 44%,#e1efb4 56%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top, #f8fbec 44%,#e1efb4 56%); /* IE10+ */
	background: linear-gradient(top, #f8fbec 44%,#e1efb4 56%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f8fbec', endColorstr='#e1efb4',GradientType=0 ); /* IE6-9 */
}
input.sf_buttonActionCancel:active,
input.sf_buttonActionCancel:focus,
input.sf_buttonActionCancel:hover {
	border: 1px solid #B00101;
	background: #ffecec; /* Old browsers */
	background: -moz-linear-gradient(top, #ffecec 44%, #ffc1c1 56%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(44%,#ffecec), color-stop(56%,#ffc1c1)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top, #ffecec 44%,#ffc1c1 56%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top, #ffecec 44%,#ffc1c1 56%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top, #ffecec 44%,#ffc1c1 56%); /* IE10+ */
	background: linear-gradient(top, #ffecec 44%,#ffc1c1 56%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffecec', endColorstr='#ffc1c1',GradientType=0 ); /* IE6-9 */
}
</style>
	<script type="text/javascript">
	<!--
		//
		// START RB OBJECT
		//
		function sf_RessourceBrowser() {

			//currentSelectedRessource
			this.csr = new Object();
			this.currentActiveRessourceId = '';
			this.langSwitchToListView = 'Switch to Listview';
			this.langSwitchToDetailView = 'Switch to Detailview';
		
			//methods
			this.setCsrValues               = sf_RessourceBrowser_setCsrValues;
			this.performPathwayChange       = sf_RessourceBrowser_performPathwayChange;
			this.performPathwayUp           = sf_RessourceBrowser_performPathwayUp;
			this.performDetailSwitch        = sf_RessourceBrowser_performDetailSwitch;
			this.styleSwitch                = sf_RessourceBrowser_styleSwitch;
			this.performBrowserWindowURL    = sf_RessourceBrowser_performBrowserWindowURL;
			this.performBrowserWindow       = sf_RessourceBrowser_performBrowserWindow;
			this.sendValsToPicker           = sf_RessourceBrowser_sendValsToPicker;
		
		}
		
		
		function sf_RessourceBrowser_setCsrValues(csr) {
		
			this.csr = csr;
		
			//get actionfileds
			afPathway = document.getElementById('sf_rb_pathway');
			afPathwayUp = document.getElementById('sf_rb_pathwayUp');
			afDetailSwitch = document.getElementById('sf_rb_detailSwitch');
			afPicker = document.getElementById('sf_rb_picker');
		
			//alert(rb.csr.pathwayNames['1']);
		
		
			//make pathway select
			afPathway.length = 0;
			for(i=0;i<this.csr.pathwayNames.length;i++){
				isSelected = (this.csr.pathwayUrls[i] == this.csr.pathwaySelectedUrl) ? true: false;
				opt = new Option(this.csr.pathwayNames[i], this.csr.pathwayUrls[i], false, isSelected);
		   		afPathway.options[i] = opt;
			}
		
			// path up button
			var pway = document.getElementById('sf_imagePathwayOneUp');
			if (this.csr.pathwayUpEneabled) {
				pway.src = '{rbImagePath}icons/rb_folder_up.gif';
			} else {
				pway.src = '{rbImagePath}icons/rb_folder_up_off.gif';
			}
		
			//detailswitch
			var dswitch = document.getElementById('sf_imageDetailswitch');
			if (this.csr.detailSwitchEneabled) {
				if (this.csr.detailSwitchCurrentView == 'detail') {
					dswitch.src = '{rbImagePath}icons/rb_list.gif';
					dswitch.title = dswitch.alt = this.langSwitchToListView;
				} else {
					dswitch.src = '{rbImagePath}icons/rb_details.gif';
					dswitch.title = dswitch.alt = this.langSwitchToDetailView;
				}
			} else {
				pway.src = '{rbImagePath}icons/rb_details_off.gif';
				dswitch.title = dswitch.alt = '';
			}
		}
		
		
		function sf_RessourceBrowser_performPathwayChange() {
			ifr = document.getElementById('ressourceBrowserContent');
			var p = document.getElementById('sf_rb_pathway');
			var v = p.options[p.selectedIndex].value;
			if (v != '') {
				ifr.src = v;
			}
		}
		
		function sf_RessourceBrowser_performPathwayUp() {
			if (! this.csr.pathwayUpEneabled) {
				return false;
			}
			ifr = document.getElementById('ressourceBrowserContent');
			ifr.src = this.csr.pathwayUpUrl;
		}
		
		
		function sf_RessourceBrowser_performDetailSwitch() {
			if (! this.csr.detailSwitchEneabled) {
				return false;
			}
			ifr = document.getElementById('ressourceBrowserContent');
			ifr.src = this.csr.detailSwitchUrl;
		}
		
		function sf_RessourceBrowser_performBrowserWindowURL(url) {
			if (url) {
				ifr = document.getElementById('ressourceBrowserContent');
				ifr.src = url;
			}
		}
		
		      
		function sf_RessourceBrowser_sendValsToPicker(name, value, is_chooseable, is_editable_in_picker) {
			
			
			var n = document.getElementById('sf_rb_picker');
			var v = document.getElementById('sf_rb_picker_value');
			
			
			if (is_editable_in_picker) {
					if (n.readonly ) {
						n.removeAttribute('readonly');
					}
			} else {
				if (! n.readonly ) {
					//alert('123');
					var attr = document.createAttribute('readonly');
					attr.value = 'readonly';
					n.setAttributeNode(attr);
				}	
			}
		
			if (! is_chooseable) {
					return false;
			}
			
			n.value = name;
			v.value = value;
		}
		
		function sf_RessourceBrowser_performBrowserWindow(id, listurl, detailurl) {
			var oldActive = document.getElementById(this.currentActiveRessourceId);
			var newActive = document.getElementById(id);
			
			if(oldActive.id != id) {
				oldActive.className = 'rbRessourceChoser';
			}
				
			newActive.styleClass= 'rbRessourceChoserActive';
			
			this.currentActiveRessourceId = id;
			
			switch(this.csr.detailSwitchCurrentView) {
				case 'detail':
					this.performBrowserWindowURL(detailurl);
					break;
				case 'list':
				default:
					this.performBrowserWindowURL(listurl);
			}
		
		}
		
		
		function sf_RessourceBrowser_styleSwitch(obj, styleclass) {
			if (obj.id == this.currentActiveRessourceId) {
				return true;
			}
			obj.className = styleclass;
		}
		
		function showObject(obj, showEmpty) {
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
		
		//
		// END RB OBJECT
		//
		
		var rb = new sf_RessourceBrowser();
		
		function initRb() {
			//return;
			var startRessourceId = '{rbStartRessourceId}';
			rb.currentActiveRessourceId = startRessourceId
			rb.langSwitchToListView = '{rbLangSwitchToListView}';
			rb.langSwitchToDetailView = '{rbLangSwitchToDetailView}';
			var dswitch = document.getElementById('sf_imageDetailswitch');
			dswitch.title = dswitch.alt = rb.langSwitchToDetailView;
			var activeRessource = document.getElementById(startRessourceId); 
			activeRessource.className = 'rbRessourceChoserActive';
		}
	
		function rb_performJsCallback() {
			var ressource_type = rb.csr.ressourceName;
			var picked_name    = document.getElementById('sf_rb_picker').value;
			var picked_value   = document.getElementById('sf_rb_picker_value').value;
			var funcNum = rb_getUrlParam('CKEditorFuncNum');

			window.opener.{rbJsCallback};
			window.close();
		}
		
		function rb_getUrlParam(paramName)
		{
			var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
			var match = window.location.search.match(reParam) ;
		
			return (match && match.length > 1) ? match[1] : '' ;
		}
	-->
	</script>

</head>

<body id="rbBody" onload="initRb();window.focus()">
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
<form name="sf_rb_form" id="sf_rb_form">
<tr class="rbTableRow1">
	<td class="rbTableRow1-1"></td>
	<td>
		<table width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left" valign="middle" style="padding-left:5px;"><select size="1" name="sf_rb_pathway" id="sf_rb_pathway" class="rbPathway" onchange="rb.performPathwayChange()">
              <option>Eintrag</option>
              <option>anderer Eintrag</option>
            </select></td>
            <td width="25" align="center" valign="bottom"><a href="#" onclick="rb.performPathwayUp();return false;" id="sf_rb_pathwayUp"><img id="sf_imagePathwayOneUp" src="{rbImagePath}icons/rb_folder_up_off.gif" border="0" alt="{rbLangPathwayOneUp}" title="{rbLangPathwayOneUp}"></a></td>
            <td width="25" align="center" valign="bottom"><a href="#" onclick="rb.performDetailSwitch();return false;" id="sf_rb_detailSwitch"><img id="sf_imageDetailswitch" src="{rbImagePath}icons/rb_details.gif" border="0" alt="" title=""></a></td>
          </tr>
        </table></td>
</tr>
<tr>
	<td class="rbTableRow2-1"></td>
	<td class="rbTableRow2-2"></td>
</tr>
<tr>
	<td align="left" valign="top" class="rbTableRow3-1">
		<!-- BEGIN rbRessourceChoser -->
		<div class="rbRessourceChoser" id="{rbRessourceID}" onmouseover="rb.styleSwitch(this, 'rbRessourceChoserActive')" onmouseout="rb.styleSwitch(this, 'rbRessourceChoser')" onclick="rb.performBrowserWindow(this.id, '{rbRessourceChooserListURL}','{rbRessourceChooserDetailURL}');"><img src="{rbImagePath}{rbRessourceChooserImage}" border="0" alt=""><br />{rbRessourceChooserDisplayedName}</div>
		<!-- END rbRessourceChoser -->
	</td>
	<td class="rbTableRow3-2" width="100%" height="*">
		<iframe src="{rbStartRessourceURL}" name="ressourceBrowserContent" id="ressourceBrowserContent" frameborder="0" width="100%" height="100%"></iframe>
	</td>
</tr>
<tr>
	<td class="rbTableRow4-1"><img src="{rbImagePath}rb-4-1.gif" border="0"></td>
	<td class="rbTableRow4-2"></td>
</tr>
<tr>
	<td class="rbTableRow5-1" colspan="2">

		<table border="0" cellpadding="0" cellspacing="2" width="100%">
		<tr>
			<td width="101" class="rbTablePickerRow1-1">Auswahl: </td>
			<td><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><input type="text" value="" name="sf_rb_picker" id="sf_rb_picker"><input type="hidden" value="" name="sf_rb_picker_value" id="sf_rb_picker_value"></td></tr></table></td>
			<td style="width:5px"></td>
			<td width="200">
  <input type="button" value="{rbLangOpen}" name="sf_rb_submit" id="sf_rb_submit" class="sf_buttonAction" onClick="rb_performJsCallback()" />
  <input type="button" value="{rbLangCancel}" name="sf_rb_cancel" id="sf_rb_cancel" class="sf_buttonActionCancel" onClick="window.close()" />
		</tr>
		</table>



	</td>
</tr>
</form>
</table>



</body>

</html>