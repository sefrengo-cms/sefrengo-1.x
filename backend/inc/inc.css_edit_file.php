<?PHP
// File: $Id: inc.css_edit_file.php 52 2008-07-20 16:16:33Z bjoern $
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
include('inc/class.filemanager.php');
$fm = new filemanager();

/******************************************************************************
 2. Eventuelle Actions/ Funktionen abarbeiten
******************************************************************************/

if (empty($idcssfile)) 
	$perm->check(2, 'area_css', '0');
else
	$perm->check(3, 'css_file', $idcssfile);
	
if ($idclient != 0 && $idclient != (int) $client || !isset($idclient)) $idclient = (int) $client;

if ($action && preg_match("/^\d/", $action) == 0) {
	eval( '$errno = css_'.$action.'();' );

	// Event
	$errlog  = ($errno) ? ', Fehler:' . $errno: '';
	fire_event('css_'.$action, array('idcss' => $idcssfile, 'errlog' => $errlog));
	if (empty($errno) && ! isset($_REQUEST['sf_apply']) ) {
		header ('HTTP/1.1 302 Moved Temporarily');
		header ('Location: '.$sess->urlRaw("main.php?area=css&idexpand=$idexpand&idclient=$client&errno=$errno"));
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
$tmp['AREA_TITLE']    = empty($idcssfile) ? $cms_lang['area_css_new_file'] : $cms_lang['area_css_edit_file'];
if ($errno) {
	$tmp['ERR_MSG']       = $cms_lang["err_$errno"];
}
$tmp['SUB_NAV_RIGHT'] = '&nbsp;';
$tmp['FOOTER_LICENSE'] = $cms_lang['login_licence'];

// Templatedatei laden
$tpl->loadTemplatefile('css_edit_file.tpl');

// get data for editing
$date_format = $cfg_cms['FormatDate'] . ' ' . $cfg_cms['FormatTime'];

if (empty($errno)) {
	if (!empty($idcssfile) || !empty($idcssfilecopy)) {
		$idfile = (!empty($idcssfile)) ? $idcssfile: $idcssfilecopy;
		$tmp_data = $fm->get_file((int) $idfile, (int) $client);
	}
	if (!empty($tmp_data)) {
		$filedirname      = $tmp_data['dirname'];
		$filedescription  = $tmp_data['description'];
	    if (empty($idcssfilecopy)) {
	  		$filename         = $tmp_data['filename'];
	  		$filecreated      = date($date_format, $tmp_data['created']);
	  		$filelastmodified = date($date_format, $tmp_data['lastmodified']);
	  		$fileauthor       = $tmp_data['vorname'] . ' ' . $tmp_data['nachname'];
	    }
	} else {
		$filedirname      = 'cms/css/';
		$filecreated      = '';
		$filelastmodified = '';
		$fileauthor       = '';
		$idcssfile        = 0;
		$idcssfilecopy    = 0;
	}
} else {
	if (!empty($idcssfilecopy)) {
		$idfile = $idcssfilecopy;
		$tmp_data = $fm->get_file((int) $idfile, (int) $client);
		if (!empty($tmp_data)) {
			$filedirname      = $tmp_data['dirname'];
			$filedescription  = $tmp_data['description'];
		}
	} else {
		// get managementinfo
		$idfile = $idcssfile;
		$tmp_data = $fm->get_file((int) $idfile, (int) $client);
		if (!empty($tmp_data)) {
	  		$filecreated      = date($date_format, $tmp_data['created']);
	  		$filelastmodified = date($date_format, $tmp_data['lastmodified']);
	  		$fileauthor       = $tmp_data['vorname'] . ' ' . $tmp_data['nachname'];
		} else {
			$filecreated      = '';
			$filelastmodified = '';
			$fileauthor       = '';
		}
	}
}

/*
** set start of form
*/
$tmp['FORM_ACTION']   = $sess->url('main.php');
$tmp['CSS_FILE_ID']   = $idcssfile;
$tmp['CSS_EXPAND_ID'] = $idexpand;
$tmp['CSS_CLIENT_ID'] = (!empty($idclient)) ? $idclient: $client;
$tmp['CSS_FILE_DIR']  = $filedirname;
$tmp['CSS_COPY_ID']   = $idcssfilecopy;
/*
** name of css-file
*/
$tmp['CSS_FILE_NAME'] = $cms_lang['css_filename'];
$tmp['CSS_FILE']      = ($idcssfile) ? $filename.'<input type="hidden" id="filename" name="filename" value="'.$filename.'" />': '<input class="w600" type="text" maxlength="255" id="filename" name="filename" size="120" value="'.$filename.'" />';

/*
** description for css-file
*/
$tmp['CSS_FILE_DESC_NAME'] = $cms_lang['css_description'];
$tmp['CSS_FILE_DESC']      = $filedescription;

/*
** Cancel-, Submit-, Apply- and Reset-Button
*/
$tmp['BUT_RESET']       = 'tpl/'.$cfg_cms['skin'].'/img/but_resetform.gif';
$tmp['BUT_RESET_TEXT']  = $cms_lang['css_reset_form'];

$tmp['BUTTON_SUBMIT_VALUE'] = $cms_lang['gen_save'];
$tmp['BUTTON_SUBMIT_TEXT'] = $cms_lang['gen_save_titletext'];

$tmp['BUTTON_APPLY_VALUE'] = $cms_lang['gen_apply'];
$tmp['BUTTON_APPLY_TEXT'] = $cms_lang['gen_apply_titletext'];

$tmp['BUTTON_CANCEL_URL'] = $sess->url("main.php?area=css&idclient=$idclient");
$tmp['BUTTON_CANCEL_VALUE'] = $cms_lang['gen_cancel'];
$tmp['BUTTON_CANCEL_TEXT'] = $cms_lang['gen_cancel_titletext'];

$tpl->setVariable($tmp);
unset($tmp);

/*
** SELECT-Listen
*/

/*
** multi-selection-list for css-rules
** use only related or free css-rules
*/
$tpl->setCurrentBlock('CSSRULES');
$tmp_dbresult = false;
$tmp_rules = "";
// check if user may import css-rules into file or generally in the area if the file is a new one
if ($perm->have_perm(29, 'css_file', $idcssfile) || (empty($idcssfile) && $perm->have_perm(29, 'area_css', '0'))) {
	// css-rules for import
	$db = get_all_cssrules_of_file( 0, 0, 0, 1);
	while ($db->next_record()) {
		$cssname = $db->f('type').$db->f('name').",";
		if ( strpos($tmp_rules, $cssname) === false ) {
			set_option_tag(-$db->f('idcss'), $db->f('type').$db->f('name'), '');
			$tmp_dbresult = true;
		}
		}
  unset($tmp);
  if ($tmp_dbresult) {
    $tpl->setCurrentBlock('CSSSELECT');
    $tmp['CSS_RULES_SELECT_NAME'] = $cms_lang['css_rule_list_importable'];
    $tmp['CSS_RULES_SELECT'] = "<select class=\"w800\" multiple=\"multiple\" id=\"idcss[]\" name=\"idcss[]\" size=\"8\">";
    $tmp['CSS_RULES_SELECT_END'] = ($tmp_dbresult) ? '</select>': '&nbsp;'.$cms_lang['css_rule_listempty'];
  	$tpl->setVariable($tmp);
  	$tpl->parseCurrentBlock();
  	unset($tmp);
	}
}

if (!empty($idcssfile) || !empty($idcssfilecopy)) {
	// used css-rules
	$tpl->setCurrentBlock('CSSRULES1');
	$tmp_dbresult = 0;
	if ($idfile) {
		$db = get_all_cssrules_of_file( $client, $idfile, 0, 2);
		while ($db->next_record()) {
			set_option_tag($db->f('idcss'), $db->f('type').$db->f('name'), " selected", '1');
			$tmp_dbresult = true;
		}
	}
	// select-tag must be written, if any option is present
	unset($tmp);
  if ($tmp_dbresult) {
    $tpl->setCurrentBlock('CSSSELECT1');
    $tmp['CSS_RULES_SELECT_NAME1'] = $cms_lang['css_rule_list_used'];
    $tmp['CSS_RULES_SELECT1'] = "<select class=\"w800\" multiple=\"multiple\" id=\"idcss[]\" name=\"idcss[]\" size=\"8\">";
    $tmp['CSS_RULES_SELECT_END1'] = ($tmp_dbresult) ? '</select> '.$cms_lang['css_rule_listhint']: '&nbsp;'.$cms_lang['css_rule_listempty'];
  	$tpl->setVariable($tmp);
  	$tpl->parseCurrentBlock();
	unset($tmp);
  }
}


/*
** rechte management
*/
if (!empty($idcssfile) && !empty($idclient) && $perm->have_perm(6, 'css_file', $idcssfile)) {
	$panel = $perm->get_right_panel('css_file', $idcssfile, array( 'formname'=>'editcssfile' ), 'text' );
	if (!empty($panel)) {
		$tpl->setCurrentBlock('CSS_RIGHTS');
		$tmp['CSS_RIGHTS_CONTENT'] = implode("", $panel);
		$tpl->setVariable($tmp);
		$tpl->parseCurrentBlock();
		unset($tmp);
	}
}

/*
** show css-managementinfo
*/
if (!empty($idcssfile)) {
	$tpl->setCurrentBlock('CSS_MANAGEMENT');
	set_management_info($cms_lang['css_created']     , $filecreated);
	set_management_info($cms_lang['css_lastmodified'], $filelastmodified);
	set_management_info($cms_lang['css_editor']      , $fileauthor);
	unset($tmp);
}


function set_management_info($topic, $value) {
	global $tmp, $tpl;

	$tmp['CSS_MANAGEMENT_TOPIC']   = $topic;
	$tmp['CSS_MANAGEMENT_CONTENT'] = $value;
	$tpl->setVariable($tmp);
	$tpl->parseCurrentBlock();
	unset($tmp);
}
//
// set_option_tag($value, $rule, $selected)
//
// creates an option tag in the template
//
function set_option_tag($value, $rule, $selected, $pos = '') {
	global $tmp, $tpl;

	$tmp['CSSRULE_VALUE'.$pos]    = $value;
	$tmp['CSSRULE'.$pos]          = $rule;
	$tmp['CSSRULE_SELECTED'.$pos] = $selected;
	$tpl->setVariable($tmp);
	$tpl->parseCurrentBlock();
	unset($tmp);
}

?>