<?PHP
// File: $Id: inc.css_edit.php 52 2008-07-20 16:16:33Z bjoern $
// +----------------------------------------------------------------------+
// | Version: Sefrengo $Name:  $                                          
// +----------------------------------------------------------------------+
// | Copyright (c) 2005 - 2007 sefrengo.org <info@sefrengo.org>           |
// +----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify |
// | it under the terms of the GNU General Public License                 |
// |                                                                      |
// | This program is subject to the GPL license, that is bundled with     |
// | this package in the file LICENSE.TXT.                                |
// | If you did not receive a copy of the GNU General Public License      |
// | along with this program write to the Free Software Foundation, Inc., |
// | 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA               |
// |                                                                      |
// | This program is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// +----------------------------------------------------------------------+
// + Autor: $Author: bjoern $
// +----------------------------------------------------------------------+
// + Revision: $Revision: 52 $
// +----------------------------------------------------------------------+
// + Description:
// +----------------------------------------------------------------------+
// + Changes: 
// +----------------------------------------------------------------------+
// + ToDo:
// +----------------------------------------------------------------------+

if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

/******************************************************************************
 1. Benötigte Funktionen und Klassen includieren
******************************************************************************/

include('inc/fnc.css.php');
include('inc/class.fileaccess.php');
$fm = &new fileaccess();

/******************************************************************************
 2. Eventuelle Actions/ Funktionen abarbeiten
******************************************************************************/

$perm->check(19, 'css_file', $idcssfile);
if ($idclient != 0 && $idclient != (int) $client || !isset($idclient)) $idclient = (int) $client;

if ($action && preg_match("/^\d/", $action) == 0) {
	eval( '$errno = css_'.$action."();" );

	// Event
	$errlog  = ($errno) ? ', Fehler:' . $errno: '';
	$errrule = (preg_match("/(file)/i", $action) > 0) ? '': 'Rule: '.$idcss;
	fire_event('css_'.$action, array('errrule' => $errrule, 'errlog' => $errlog));
	if (empty($errno) && ! isset($_REQUEST['sf_apply']) ) {
		header ('HTTP/1.1 302 Moved Temporarily');
		header ('Location: '.$sess->urlRaw("main.php?area=css&idcssfile=$idcssfile&idexpand=$idcssfile&idclient=$idclient&errno=$errno"));
		exit;
	}
}

/******************************************************************************
 3. Eventuelle Dateien zur Darstellung includieren
******************************************************************************/

include('inc/inc.header.php');

/******************************************************************************
 4. Bildschirmausgabe aufbereiten und ausgeben
******************************************************************************/

// Kopfbereich
$tmp['AREA_TITLE']    = $cms_lang['area_css_edit'];
if ($errno) {
	$tmp['ERR_MSG']       = $cms_lang["err_$errno"];
}
$tmp['SUB_NAV_RIGHT'] = '&nbsp;';

// Tabellenformatierung
$tmp['TABLE_PADDING'] = $cellpadding;
$tmp['TABLE_SPACING'] = $cellspacing;
$tmp['TABLE_BORDER'] = $border;

// Templatedatei laden
$tpl->loadTemplatefile('css_edit.tpl');

//
// set presets
//
// to do: import css-files
$stiltyp = array ( $cms_lang["css_rule_type01"] => "",
				   $cms_lang["css_rule_type02"] => ".",
				   $cms_lang["css_rule_type03"] => "#",
//				   $cms_lang["css_rule_type04"] => "@"
            );

$ruletype = $type;
$rulename = $name;
$ruledescription = $description;
$rulestyles = $styles;
$rulewarnings = '';
$rulecreated = '';
$rulelastmodified = '';
$ruleauthor  = '';

// get data for editing
if ($idcss && !$errno) {
	$tmp_data = get_cssrule_data( $idcss, 1 );
	if ($tmp_data) {
		$ruletype = $tmp_data['type'];
		$rulestyles = $tmp_data['styles'];
		$ruledescription = $tmp_data['description'];
		$rulewarnings = $tmp_data['warning'];
		$idcss = $tmp_data['idcss'];
		if ($action != '10') {
			$rulename = $tmp_data['name'];
			$rulecreated = date( $cfg_cms['FormatDate'] . ' ' . $cfg_cms['FormatTime'], $tmp_data['created']);
			$rulelastmodified = date($cfg_cms['FormatDate'] . ' ' . $cfg_cms['FormatTime'], $tmp_data['lastmodified']);
			$ruleauthor  = $tmp_data['vorname'] . ' ' . $tmp_data['nachname'];
		} else {
			// handle duplicate css-stile
			$rulename = '';
			$idcss    = '';
		}
	} else {
		$idcss = 0;
	}
} else {
	// if we an error message, then we have to remove the slashes that were added by php
	if ($errno) {
		remove_magic_quotes_gpc($rulestyles);
		remove_magic_quotes_gpc($ruledescription);
	}
}

/*
** start form
*/
$tmp['FORM_ACTION'] = $sess->url("main.php");
$tmp['IDCSS'] = $idcss;
$tmp['IDEXPAND'] = $idexpand;
$tmp['IDCLIENT'] = $idclient;

/*
** type of rule
*/
$tmp['EDIT_TYPE_DESC'] = $cms_lang["css_rule_type"];
$tmp['EDIT_TYPE_NAME'] = 'type';

/*
** name of the rule
*/
$tmp['EDIT_CSSSELECTOR_DESC'] = $cms_lang["css_rulename"];
$tmp['EDIT_CSSSELECTOR_NAME'] = 'name';
$tmp['EDIT_CSSSELECTOR_VALUE'] = $rulename;

/*
** description for rule
*/
$tmp['EDIT_CSSRULE_DESC'] = $cms_lang["css_description"];
$tmp['EDIT_CSSRULE_NAME'] = 'description';
$tmp['EDIT_CSSRULE_VALUE'] = $ruledescription;

/*
** list of existing css-files for the client
** only for new rules, existing rules show only files name
** editing of rules to file relation in css-file editing
*/
$tmp['EDIT_CSSFILE_DESC'] = $cms_lang["css_rule_file"];
$tmp['EDIT_CSSFILE_NAME'] = 'idcssfile';
$tmp['EDIT_CSSFILE_VALUE'] = $idcssfile;
if (!empty($idclient)) $tmp_data = $fm->get_complete_filename((int)$idcssfile);
$tmp['EDIT_CSSFILE_TEXT'] = ($tmp_data && !empty($idclient)) ? substr($tmp_data, strrpos($tmp_data, '/')+1): $cms_lang["css_rule_file01"];

/*
** textarea for rule content
*/
$tmp['EDIT_ELEMENT_TEXT'] = $cms_lang["css_rule_content"];
$tmp['EDIT_ELEMENT_NAME'] = 'styles';
//$tmp['EDIT_ELEMENT_ONBLUR'] = 'showPreview(-1)';
$tmp['EDIT_ELEMENT_ONBLUR'] = 'cssbuilder.showPreview(-1)';
$tmp['EDIT_ELEMENT_RULES'] = $rulestyles;

/*
** drop-down-list for element-editor
*/
$tmp['EDIT_ELEMENTS_NAME'] = 'stilelement';
$tmp['EDIT_ELEMENTS_ONCHANGE'] = 'cssbuilder.resetUnits(this.selectedIndex);';
$tmp['EDIT_ELEMENT_SELECTION'] = $cms_lang["css_posible_elements"];

/*
** drop-down list for element units of element-editor
** input field for element-value of elemenet-editor
*/
$tmp['EDIT_UNITS_NAME']   = "stileinheit";
$tmp['EDIT_UNITS_CHANGE'] = "cssbuilder.replaceUnit(this.selectedIndex);";
$tmp['EDIT_UNITS_DEFAULT_TEXT'] = $cms_lang["css_posible_units"];
$tmp['EDIT_UNITS_DEFAULT_VALUE'] = '';
$tmp['EDIT_ELEMENTVALUE_NAME'] = "stilelementwert";
$tmp['EDIT_ELEMENTVALUE_ONBLUR'] = 'cssbuilder.setLateValue()';

/*
** buttons for element-editor
*/
$tmp['BUTTON_CSSCLR_URL'] = "cssbuilder.doCssElement('clear')";
$tmp['BUTTON_CSSCLR_PICT'] = 'tpl/'.$cfg_cms['skin'].'/img/but_resetform.gif';
$tmp['BUTTON_CSSCLR_TEXT'] = $cms_lang["css_rule_clearhint"];
$tmp['BUTTON_CSSADD_URL'] = "cssbuilder.doCssElement('add')";
$tmp['BUTTON_CSSADD_PICT'] = 'tpl/'.$cfg_cms['skin'].'/img/but_newcsselement.gif';
$tmp['BUTTON_CSSADD_TEXT'] = $cms_lang["css_rule_addhint"];
$tmp['BUTTON_CSSREM_URL'] = "cssbuilder.doCssElement('remove')";
$tmp['BUTTON_CSSREM_PICT'] = 'tpl/'.$cfg_cms['skin'].'/img/but_delcsselement.gif';
$tmp['BUTTON_CSSREM_TEXT'] = $cms_lang["css_rule_removehint"];
$tmp['BUTTON_CSSHLP_URL'] = "cssbuilder.doHelp()";
$tmp['BUTTON_CSSHLP_PICT'] = 'tpl/'.$cfg_cms['skin'].'/img/but_helpcsselement.gif';
$tmp['BUTTON_CSSHLP_TEXT'] = $cms_lang["css_rule_helphint"];
$tmp['CHECKRULE_OPTION'] = $cms_lang["css_rule_checking"];
$tmp['CHECKRULE_CHECKED'] = ($cfg_client["css_checking"] == "1") ? " CHECKED": "";

/*
** support for colorpicker
*/
$colorpicker['linked'] = true;
$colorpicker['width'] = 210;
$colorpicker['visible'] = "hidden";
$colorpicker['func_select'] = "cssbuilder.copyColor";
$colorpicker['type'] = "short1";
include('tpl/'.$cfg_cms['skin'].'/colorpicker.php');
$tmp['COLORPCIKER'] = createColorPicker();

/*
** Preview area for a rule
*/
$tmp['PREVIEWTYPE_TEXT'] = $cms_lang['css_rule_previewtype'];
$tmp['PREVIEWHINT_TEXT'] = $cms_lang['css_rule_previewhint'];
$tmp['PREVIEW_PICT']     = 'tpl/'.$cfg_cms['skin'].'/img/but_preview.gif';
$tmp['PREVIEW_NAME']     = 'vorschautyp';
$tmp['PREVIEW_CHANGE']   = 'cssbuilder.showPreview()';
$tmp['PREVIEW_CLICK']    = 'cssbuilder.showPreview()';

/*
** button-related
*/

//$tmp_idcssfile = ($idcss) ? (($idexpand) ? $idexpand: $idcssfile): -1;
$tmp_idcssfile = ($idexpand) ? $idexpand: $idcssfile;

$tmp['BUTTON_SUBMIT_VALUE'] = $cms_lang['gen_save'];
$tmp['BUTTON_SUBMIT_TEXT'] = $cms_lang['gen_save_titletext'];

$tmp['BUTTON_APPLY_VALUE'] = $cms_lang['gen_apply'];
$tmp['BUTTON_APPLY_TEXT'] = $cms_lang['gen_apply_titletext'];

$tmp['BUTTON_CANCEL_URL'] = $sess->url("main.php?area=css&idclient=".$idclient."&idcssfile=".$tmp_idcssfile."&idexpand=".$tmp_idcssfile);
$tmp['BUTTON_CANCEL_VALUE'] = $cms_lang['gen_cancel'];
$tmp['BUTTON_CANCEL_TEXT'] = $cms_lang['gen_cancel_titletext'];





//$tmp['BUTTON_CANCEL_PICT'] = 'tpl/'.$cfg_cms['skin'].'/img/but_cancel.gif';

//$tmp['BUTTON_SUBMIT_PICT'] = 'tpl/'.$cfg_cms['skin'].'/img/but_ok.gif';


/*
** js-editor-related
*/
$tmp['EDITOR_PREVIEW_BASE']  = $cfg_client['htmlpath'];
$tmp['EDITOR_RULE_CHECK'] = 'cssbuilder.setRuleCheck(this);';
$tmp['EDITOR_JS'] = 'tpl/'.$cfg_cms['skin'].'/js/objcsseditor_' . $cfg_cms['backend_lang'] . '.js';
$tmp['EDITOR_INIT'] = ($rulestyles) ? '1': '0';
$tmp['EDITOR_PREVIEWNAME'] = $cms_lang["css_js_previewname"];
$tmp['EDITOR_PICKER'] = $cms_lang["css_js_picker"];
$tmp['EDITOR_VISIBLE'] = $cms_lang["css_js_visible"];
$tmp['EDITOR_HIDDEN'] = $cms_lang["css_js_hidden"];
$tmp['EDITOR_NOPREVIEW'] = $cms_lang["css_js_nopreview"];
$tmp['EDITOR_CHOOSEPREVIEW'] = $cms_lang["css_js_choosepreview"];
$tmp['EDITOR_CHOSSEELEMENT'] = $cms_lang["css_js_chosseelement"];
$tmp['EDITOR_ENTERVALUE0'] = $cms_lang["css_js_entervalue0"];
$tmp['EDITOR_ENTERVALUE1'] = $cms_lang["css_js_entervalue1"];
$tmp['EDITOR_ENTERVALUE1'] = $cms_lang["css_js_entervalue1"];
$tmp['FOOTER_LICENSE'] = $cms_lang['login_licence'];
$tpl->setVariable($tmp);
unset($tmp);

/*
** show css-managementinfo
*/
$tpl->setCurrentBlock('CSS_MANAGEMENT_INFO');
$tmp['CSS_INFO_TOPIC']   = $cms_lang["css_created"];
$tmp['CSS_INFO_CONTENT'] = $rulecreated;
$tpl->setVariable($tmp);
$tpl->parseCurrentBlock();
unset($tmp);
$tmp['CSS_INFO_TOPIC']   = $indent.$cms_lang["css_lastmodified"];
$tmp['CSS_INFO_CONTENT'] = $indent.$rulelastmodified;
$tpl->setVariable($tmp);
$tpl->parseCurrentBlock();
unset($tmp);
$tmp['CSS_INFO_TOPIC']   = $indent.$cms_lang["css_editor"];
$tmp['CSS_INFO_CONTENT'] = $indent.$ruleauthor;
$tpl->setVariable($tmp);
$tpl->parseCurrentBlock();
unset($tmp);

/*
** show Preview-Select
*/
$tpl->setCurrentBlock('OPTIONLIST');
for ($i = 1; $i < 11; $i++) {
		$tmp['OPTION_TEXT']  = $cms_lang["css_rule_previewtype".$i];
		$tmp['OPTION_VALUE'] = ($i != 1) ? $i-2: '';
		$tpl->setVariable($tmp);
		$tpl->parseCurrentBlock();
		unset($tmp);
}
/*
** show Type-Select
*/
$tpl->setCurrentBlock('TYPELIST');
foreach ($stiltyp as $key => $value) {
	$tmp['EDIT_TYPE_TEXT']  = $key;
	$tmp['EDIT_TYPE_VALUE'] = $value;
	$tmp['EDIT_TYPE_SELECTED'] = ($ruletype == $value) ? ' selected': '';
	$tpl->setVariable($tmp);
	$tpl->parseCurrentBlock();
	unset($tmp);
}

/*
** css-warnings
*/
if ($rulewarnings) {
	$tpl->setCurrentBlock('CSSERRORS');
	$tmp['CSSWARNING_DESC'] = $cms_lang["css_rule_warning"];
	$warn = preg_replace ( '/\-ele\-([^:]*):/i', "<a class=\"action\" href=\"javascript:cssbuilder.findErrorElement('\\1')\">\\1</a>:", trim($rulewarnings));
	$tmp['CSSWARNINGS'] = str_replace ( ',', '<br />', $warn);
	$tmp['CSSWARNING_NAME'] = 'css_warnings';
	$tmp['CSSWARNING_VALUE'] = $rulewarnings;
	$tpl->setVariable($tmp);
	$tpl->parseCurrentBlock();
	unset($tmp);
}

?>