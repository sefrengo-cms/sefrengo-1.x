<?PHP
// File: $Id: inc.js.php 28 2008-05-11 19:18:49Z mistral $
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
// + Autor: $Author: mistral $
// +----------------------------------------------------------------------+
// + Revision: $Revision: 28 $
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

include('inc/fnc.js.php');
include('inc/class.filemanager.php');
$fm = new filemanager();

/******************************************************************************
 2. Eventuelle Actions/ Funktionen abarbeiten
******************************************************************************/

$perm->check('area_js');
if ($idclient != 0 && $idclient != (int) $client || !isset($idclient)) $idclient = (int) $client;
// if $action is named prepare a function call
if ($action && preg_match("/^\d/", $action) == 0) {
	eval( '$errno = js_'.$action.'();' );

	// Event
	$errlog  = ($errno) ? ', Fehler:' . $errno: '';
	fire_event('js_'.$action, array('idjsfile' => $idjsfile, 'errlog' => $errlog));
}

/******************************************************************************
 3. Eventuelle Dateien zur Darstellung includieren
******************************************************************************/

include('inc/inc.header.php');

/******************************************************************************
 4. Bildschirmausgabe aufbereiten und ausgeben
******************************************************************************/

// Kopfbereich
$tmp['AREA_TITLE']    = (empty($idclient) && $perm->have_perm(13, 'area_js', '0')) ? $cms_lang['area_js_import']: $cms_lang['area_js']; // import rechte gesetzt?
$tmp['FOOTER_LICENSE'] = $cms_lang['login_licence'];
if ($errno) {
	switch($errno) {
		case '1206':
		case '1210':
			$tmp['OKMESSAGE']       = $cms_lang["err_$errno"];
			break;
		default:
			$tmp['ERR_MSG']       = $cms_lang["err_$errno"];
	}
}
$tmp['SUB_NAV_RIGHT'] = '';
if ($idclient) {
	// Im Client-Bereich ... Buttons je nach Rechten
	if ($perm->have_perm(2, 'area_js')) { // edit rechte gesetzt?
		$end = ($perm->have_perm(13, 'area_js')) ? ' | ': ' '; // import rechte gesetzt?
		$tmp['SUB_NAV_RIGHT'] = $tmp['SUB_NAV_RIGHT'] . make_nav_link('action', "main.php?area=js_edit_file&idclient=$client", $cms_lang['js_file_new'], $cms_lang['js_file_new'], $end);
	}
	if ($perm->have_perm(13, 'area_js')) { // import rechte gesetzt?
		$tmp['SUB_NAV_RIGHT'] = $tmp['SUB_NAV_RIGHT'] . make_nav_link('action', "main.php?area=js&idclient=0", $cms_lang['js_import'], $cms_lang['js_import']);
	}
} else {
	// Im Import-Bereich ... nur der Button "Zurück"
	$tmp['SUB_NAV_RIGHT'] = $tmp['SUB_NAV_RIGHT'] . make_nav_link('action', "main.php?area=js&idclient=$client", $cms_lang['gen_back'], $cms_lang['gen_back']);
}

// Tabellenformatierung
$tmp['TABLE_PADDING'] = $cellpadding;
$tmp['TABLE_SPACING'] = $cellspacing;
$tmp['TABLE_BORDER'] = $border;

// Templatedatei laden
$tpl->loadTemplatefile('filelist.tpl');

//
// headline
//
$tmp['FILENAME'] = $cms_lang['js_filename'];
$tmp['DESCRIPTION'] = $cms_lang['js_description'];
$tmp['ACTIONS'] = $cms_lang['js_action'];
$tpl->setVariable($tmp);
unset($tmp);

//
// show the js-files of a particular client, i.e. $idclient != 0
//
$tpl->setCurrentBlock('ENTRY');
if (!empty($idclient)) {
	// set client list
	$block = 'export';
	$nothing_text = $cms_lang['js_nofile'];
  $_client = $client;
}
else {
	// set import list
	$block = 'import';
	$nothing_text = $cms_lang['js_nofiles'];
  $_client = 0;
}
// query all js-file for the given $idclient
$db = (!empty($idclient)) ? get_jsfile_list($client): get_jsfile_list(0);
// show the list of js-files
$_found == 0;
while ($db->next_record()) {
  $_found += set_filedata($db, $block, $_client);
}
if ($_found == 0) {
  // no js-files found
  show_none( 'NODETAIL', 'contenta', $nothing_text );
}
unset($tmp);

// generate js-file upload
// Upload nur wenn wir im Projektbereich sind
if (!empty($idclient) && $perm->have_perm(9, 'area_js')) {	// upload rechte gesetzt??
	$tpl->setCurrentBlock('FILEIMPORT');
	$tmp['FILEIMPORT_ACTION'] = $sess->url("main.php");
	$tmp['FILEIMPORT_TYPE']   = 'js';
	$tmp['FILEIMPORT_FUNC']   = 'uploadfile';
	$tmp['FILEIMPORT_DIR']    = 'cms/js/';
	$tmp['FILEIMPORT_CLIENT'] = $client;

	$tmp['FILEIMPORT_BGCOLOR'] = 'content7';
	$tmp['FILEIMPORT_TEXT'] = $cms_lang['js_fileimport'];
	$tmp['FILEIMPORT_NAME'] = 'jsuploadfile';
	$tmp['FILEIMPORT_PICT'] = 'tpl/' . $cfg_cms['skin'] . '/img/upl_upload.gif';
	$tmp['FILEIMPORT_HINT'] = $cms_lang["upl_upload"];
 	$tmp['FILEIMPORT_BUTTONWIDTH']  = '16';
	$tmp['FILEIMPORT_BUTTONHEIGHT'] = '16';

	$tpl->setVariable($tmp);
	$tpl->parseCurrentBlock();
	unset($tmp);
}

//
// function for displaying a message if no rules or files available
//
function show_none( $block, $bgcolor, $content ) {
	global $tmp, $tpl;

	$tpl->setCurrentBlock($block);
	$tmp['BGCOLOR'] = $bgcolor;
	$tmp['CONTENT'] = $content;
	$tpl->setVariable($tmp);
	$tpl->parseCurrentBlock();
}

function set_filedata($db, $imorexport, $idclient) {
	global $tmp, $tpl, $action, $idjsfile, $cms_lang, $cfg_cms, $perm, $sess;

	// variablen belegen
	$idjs = $db->f("idjs");
	// get perms for testing
	if (!empty($idclient)) {
		$test_type = 'js_file';
		$test_id   = $idjs;
    if (!$perm->have_perm(2, "js_file", $idjs)) return 0;
	} else {
		$test_type = 'area_js';
		$test_id   = '0';
	}
	// Hintergrundfarbe wählen
	$tmp['ENTRY_BGCOLOR'] = '#FFFFFF';
	$tmp['ENTRY_BGCOLOROVER'] = '#fff7ce';

	// filename und description
	$tmp['ENTRY_ICON'] = make_image('ressource_browser/icons/rb_typ_js.gif', '', '16', '16', false, 'class="icon"');
	$tmp['ENTRY_NAME'] = htmlentities($db->f('filename'), ENT_COMPAT, 'UTF-8');
	// handle delete css-file
	if ($action == 30 && $idjs == $idjsfile && $perm->have_perm(5, $test_type, $test_id)) {
		$tmp['ENTRY_NAME'] = $tmp['ENTRY_NAME'] . '&nbsp;&nbsp;' . make_image_link3 ("main.php?area=js&action=deletefile&idjsfile=".$idjs."&idupl=".$db->f('idupl')."&idclient=$idclient",  'but_confirm.gif', 'but_cancel_delete.gif', $cms_lang['js_file_delete_confirm'], $cms_lang['js_file_delete_cancel'], 'action', 'deletethis' );
	}
	$tmp['ENTRY_DESCRIPTION'] = htmlentities($db->f('description'), ENT_COMPAT, 'UTF-8');

	// buttons vorbesetzen
	$tmp['ENTRY_DOWNLOAD']  = make_image_link2($url = '#', $image = 'space.gif', '', '16', '16', '', '', '', 'action', '', '', '', 'action');
	$tmp['ENTRY_DUPLICATE'] = make_image_link2($url = '#', $image = 'space.gif', '', '16', '16', '', '', '', 'action', '', '', '', 'action');
	$tmp['ENTRY_EDIT']      = make_image_link2($url = '#', $image = 'space.gif', '', '16', '16', '', '', '', 'action', '', '', '', 'action');
	$tmp['ENTRY_DELBUT']    = make_image_link2($url = '#', $image = 'space.gif', '', '16', '16', '', '', '', 'action', '', '', '', 'action');
	$tmp['ENTRY_IMEXPORT']  = make_image_link2($url = '#', $image = 'space.gif', '', '16', '16', '', '', '', 'action', '', '', '', 'action');

	// button: download js-file - wenn nicht im import
	if (!empty($idclient) && $perm->have_perm(8, $test_type, $test_id)&&isUrl($db->f('filename'))==false) {
		$tmp['ENTRY_DOWNLOAD'] = make_image_link2 ("main.php?area=js&action=downloadfile&idjsfile=".$db->f('idupl')."&idclient=$idclient", 'but_download.gif', $cms_lang['js_file_download'], '16', '16', '', '', '', 'action', '', '', '', 'action');
	}
  
	// buttons: edit, copy, 
	if ((!empty($idjs) && $perm->have_perm(3, $test_type, $test_id)&&isUrl($db->f('filename'))==false) || (empty($idclient) && $perm->have_perm(3, $test_type, $test_id)&&isUrl($db->f('filename'))==false)) {
		// button: edit js-file
		$tmp['ENTRY_EDIT']   = make_image_link2 ("main.php?area=js_edit_file&idjsfile=".$idjs."&idclient=$idclient", 'but_edit.gif', $cms_lang['js_file_edit'], '16', '16', '', '', '', 'action', '', '', '', 'action');
		// button: duplicate js-file -- benötigt auch Neu-Anlegen-Rechte
		if ($perm->have_perm(2, 'area_js'))
			$tmp['ENTRY_DUPLICATE']   = make_image_link2 ("main.php?area=js_edit_file&action=10&idjsfile=".$idjs."&idclient=$idclient", 'but_duplicate.gif', $cms_lang['js_file_duplicate'], '16', '16', '', '', '', 'action', '', '', '', 'action');
	}
	// button: delete js-file
	if ($perm->have_perm(5, $test_type, $test_id)) {
		$tmp['ENTRY_DELBUT'] = make_image_link2 ("main.php?area=js&action=30&idjsfile=".$idjs."&idclient=$idclient", 'but_deleteside.gif', $cms_lang['js_file_delete'], '16', '16', '', '', '', 'action', 'deletethis', '', '', 'action');
  }
  
	// button: export js-file
	$rights_okay = ($imorexport == 'import') ? $perm->have_perm(13, $test_type, $test_id): $perm->have_perm(14, $test_type, $test_id);
	if ($rights_okay && !empty($idjs)&&isUrl($db->f('filename'))==false ) {
		$upl = ($imorexport == 'import') ? '&idupl='.$db->f('idupl'): '';
		$tmp['ENTRY_IMEXPORT']= make_image_link2 ("main.php?area=js&action=".$imorexport."&idjsfile=".$idjs.$upl."&idclient=$idclient", $imorexport.'.gif', $cms_lang['js_'.$imorexport], '16', '16', '', '', '', 'action', '', '', '', 'action');
	}
  
	$tpl->setVariable($tmp);
	$tpl->parseCurrentBlock();
	unset($tmp);
  
  return 1;
}
?>
