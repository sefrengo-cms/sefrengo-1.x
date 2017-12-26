<?PHP
// File: $Id: inc.js_edit_file.php 52 2008-07-20 16:16:33Z bjoern $
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
 1. Ben�tigte Funktionen und Klassen includieren
******************************************************************************/

include('inc/fnc.js.php');
include('inc/class.filemanager.php');
$fm = new filemanager();

/******************************************************************************
 2. Eventuelle Actions/ Funktionen abarbeiten
******************************************************************************/

// Pr�fe ob User diesen bereich betreten darf
if (empty($idjsfile)) 
	$perm->check(2, 'area_js');
else
	$perm->check(3, 'js_file', $idjsfile);
if ($idclient != 0 && $idclient != (int) $client || !isset($idclient)) $idclient = (int) $client;


if ($action && preg_match("/^\d/", $action) == 0) {
	eval( '$errno = js_'.$action.'();' );

	//Event
	$errlog  = ($errno) ? ', Fehler:' . $errno: '';
	fire_event('js_'.$action, array('idjsfile' => $idjsfile, 'errlog' => $errlog));
	if (empty($errno) && ! isset($_REQUEST['sf_apply']) ) {
		header ('HTTP/1.1 302 Moved Temporarily');
		header ('Location: '.$sess->urlRaw("main.php?area=js&idjsfile=$idjsfile&idclient=$idclient&errno=$errno"));
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
$tmp['AREA_TITLE']    = $cms_lang['area_js_edit_file'];
if ($errno) {
	$tmp['ERR_MSG']       = $cms_lang["err_$errno"];
}
$tmp['SUB_NAV_RIGHT'] = '&nbsp;';

// Tabellenformatierung
$tmp['TABLE_PADDING'] = $cellpadding;
$tmp['TABLE_SPACING'] = $cellspacing;
$tmp['TABLE_BORDER'] = $border;

// Templatedatei laden
$tpl->loadTemplatefile('js_edit_file.tpl');

$date_format = $cfg_cms['FormatDate'] . ' ' . $cfg_cms['FormatTime'];
// get data for editing
if (!$errno) {
	if ($idjsfile) $tmp_data = get_jscontent_data( $idjsfile, 1 );
	if ($tmp_data) {
		$jsfiledirname      = $tmp_data['dirname'];
		$jsfiledescription  = $tmp_data['description'];
		$jsfilecontent      = $tmp_data['filecontent'];
		// handle duplicate function
		if ($action == 10) {
			$idjsfile   = 0;
			$jsfilename = '';
		} else {
			$jsfilename         = $tmp_data['filename'];
			$jsfilecreated      = date($date_format, $tmp_data['created']);
			$jsfilelastmodified = date($date_format, $tmp_data['lastmodified']);
			$jsfileauthor       = $tmp_data['name'].' '.$tmp_data['surname'];
			$idjsfile           = $tmp_data['idjs'];
		}
	} else {
		$jsfilecreated      = '';
		$jsfilelastmodified = '';
		$jsfileauthor       = '';
		$idjsfile           = 0;
	}
	// Speziell f�r JS-Dateien wegen maskierten Zeichen notwendig
	if (get_magic_quotes_gpc() != 0) {
		$jsfilecontent = str_replace('\\', '\\\\', $jsfilecontent);
	}
	if (get_magic_quotes_gpc() == 0) {
		$jsfilecontent = str_replace('\\', '\\\\', $jsfilecontent);
	}
} else {
	// Speziell f�r JS-Dateien wegen maskierten Zeichen notwendig
	if (get_magic_quotes_gpc() == 0) {
		$jsfilecontent = str_replace('\\', '\\\\', $jsfilecontent);
	} else {
		remove_magic_quotes_gpc($jsfilecontent);
	}
}

/*
** start form
*/
$tmp['FORM_ACTION'] = $sess->url("main.php");
$tmp['IDJS']        = $idjsfile;
$tmp['IDCLIENT']    = $idclient;
$tmp['FOOTER_LICENSE'] = $cms_lang['login_license'];

/*
** name of the js-file
*/
$tmp['EDIT_JSFILENAME'] = $cms_lang["js_filename"];
$tmp['EDIT_JSFILE']     = ($jsfilename && $idjsfile != 0) ? $jsfilename.'<input type="hidden" name="jsfilename" value="'.$jsfilename.'">': '<input class="w800" type="text" maxlength="255" id="jsfilename" name="jsfilename" size="120" value="'.$jsfilename.'">';

/*
** description for the js-file
*/
$tmp['EDIT_JSFILEDESCNAME'] = $cms_lang["js_description"];
$tmp['EDIT_JSFILEDESC_NAME'] = 'jsfiledescription';
$tmp['EDIT_JSFILEDESC_VALUE'] = $jsfiledescription;

/*
** textarea for rule content
*/
$tmp['EDIT_JSCODENAME'] = $cms_lang["js_file_content"];
$tmp['EDIT_JSCODE_NAME'] = 'jsfilecontent';
$tmp['EDIT_JSCODE'] = $jsfilecontent;
$tmp['EDIT_JSCODE_CKEDITOR'] ='<script src="' . $sess->url($cfg_client['htmlpath'] . 'cms/ckeditor/ckeditor.js') . '"></script>' . "\n
<script>
	
	var sf_BasePath = '" . $cfg_client['htmlpath'] . "cms/ckeditor/';
	CKEDITOR.replace(
		'jsfilecontent',
		{
			customConfig : sf_BasePath + 'sefrengo/ckconfig.php'
		}
	);
	CKEDITOR.config.toolbarGroups = [];
	CKEDITOR.config.extraPlugins = 'codemirror';
	CKEDITOR.config.removePlugins = 'elementspath' ;
	CKEDITOR.config.resize_enabled = false;
	CKEDITOR.config.codemirror = {
		mode: 'javascript'
	}
	CKEDITOR.config.startupMode = 'source';
	</script>";

/*
** button-related
*/
$tmp['BUTTON_SUBMIT_VALUE'] = $cms_lang['gen_save'];
$tmp['BUTTON_SUBMIT_TEXT'] = $cms_lang['gen_save_titletext'];

$tmp['BUTTON_APPLY_VALUE'] = $cms_lang['gen_apply'];
$tmp['BUTTON_APPLY_TEXT'] = $cms_lang['gen_apply_titletext'];

$tmp['BUTTON_CANCEL_URL'] = $sess->url("main.php?area=js&idclient=".$idclient);
$tmp['BUTTON_CANCEL_VALUE'] = $cms_lang['gen_cancel'];
$tmp['BUTTON_CANCEL_TEXT'] = $cms_lang['gen_cancel_titletext'];


$tpl->setVariable($tmp);
unset($tmp);

/*
** show right management
*/
if (!empty($idjsfile) && !empty($idclient) && $perm->have_perm(6, 'js_file', $idjsfile)) {
	$panel = $perm->get_right_panel('js_file', $idjsfile, array( 'formname'=>'editjsfile' ), 'text' );
	if (!empty($panel)) {
		$tpl->setCurrentBlock('JS_RIGHTS');
		$tmp['JS_RIGHTS_CONTENT'] = implode("", $panel);
		$tpl->setVariable($tmp);
		$tpl->parseCurrentBlock();
		unset($tmp);
	}
}

/*
** show js-managementinfo
*/
if (!empty($idjsfile)) {
	$tpl->setCurrentBlock('JS_MANAGEMENT');
	set_management_info($cms_lang["js_created"]     , $jsfilecreated);
	set_management_info($cms_lang["js_lastmodified"], $jsfilelastmodified);
	set_management_info($cms_lang["js_editor"]      , $jsfileauthor);
	unset($tmp);
}

function set_management_info($topic, $value) {
	global $tmp, $tpl;

	$tmp['JS_MANAGEMENT_TOPIC']   = $topic;
	$tmp['JS_MANAGEMENT_CONTENT'] = $value;
	$tpl->setVariable($tmp);
	$tpl->parseCurrentBlock();
	unset($tmp);
}

?>