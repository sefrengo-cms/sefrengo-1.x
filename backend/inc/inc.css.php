<?PHP
// File: $Id: inc.css.php 28 2008-05-11 19:18:49Z mistral $
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
include('inc/fnc.css.php');
include('inc/class.filemanager.php');
$fm = &new filemanager();
/******************************************************************************
 2. Eventuelle Actions/ Funktionen abarbeiten
******************************************************************************/

$perm->check('area_css');
if ($idclient != 0 && $idclient != (int) $client || !isset($idclient)) $idclient = (int) $client;

// if $action is named prepare a function call
if ($action && preg_match('/^\d/', $action) == 0) {
	eval( '$errno = css_'.$action.'();' );

	// Event
	$errlog  = ($errno != '1112' && $errno != '1113' && $errno != '1117' && $errno != '1116' && $errno) ? ', Fehler:' . $errno: '';
	$errrule = (preg_match('/(file)/i', $action) > 0) ? '': ', Rule: '.$idcss;
	fire_event('css_'.$action, array('idcss' => $idcssfile, 'errrule' => $errrule, 'errlog' => $errlog));
}

/******************************************************************************
 3. Eventuelle Dateien zur Darstellung includieren
******************************************************************************/

include('inc/inc.header.php');

/******************************************************************************
 4. Bildschirmausgabe aufbereiten und ausgeben
******************************************************************************/

// Kopfbereich
$tmp['AREA_TITLE'] = (!$idclient && $perm->have_perm('29', 'area_css')) ? $cms_lang['area_css_import']: $cms_lang['area_css'];

if ($errno) {
	switch($errno) {
		case '1112':
		case '1113':
		case '1116':
		case '1117':
			$tmp['OKMESSAGE']       = $cms_lang["err_$errno"];
			break;
		default:
			$tmp['ERR_MSG']       = $cms_lang["err_$errno"];
	}
}
if ($css_warnings != '') {
	$tmp['CSS_WARN'] = preg_replace ( '/\-ele\-([^:]*):/i', "\\1", trim($css_warnings));
	$tmp['CSS_WARN'] = '<br>&nbsp;<span class="errormsg">'.str_replace ( ',', '<br />', $tmp['CSS_WARN']).'</span>&nbsp;';
}
$tmp['SUB_NAV_RIGHT'] = '';
if (!empty($idclient)) {
	if ($perm->have_perm('2', 'area_css')) {
		$tmp['SUB_NAV_RIGHT'] = $tmp['SUB_NAV_RIGHT'] . make_nav_link('action', "main.php?area=css_edit_file&idclient=$idclient", $cms_lang['css_file_new'], $cms_lang['css_file_new']);
	}
} else {
	$tmp['SUB_NAV_RIGHT'] = $tmp['SUB_NAV_RIGHT'] . make_nav_link('action', "main.php?area=css&idclient=$client&idexpand=$idexpand&idcssfile=$idcssfile", $cms_lang['gen_back'], $cms_lang['gen_back']);
}

$tmp['FOOTER_LICENSE'] = $cms_lang['login_licence'];

// Templatedatei laden
$tpl->loadTemplatefile('filelist.tpl');

//
// headline
//
$tmp['FILENAME']    = $cms_lang['css_filename'];
$tmp['DESCRIPTION'] = $cms_lang['css_description'];
$tmp['ACTIONS']     = $cms_lang['css_action'];
$tpl->setVariable($tmp);
unset($tmp);

//
// show the css-rules of a particular css-file, i.e. $idclient != 0
//
if ($idclient != 0) {
	// get css-files from db
	$tpl->setCurrentBlock('ENTRY');
	
	$db = $fm->get_file_list_by_filetype( $client, 'css', 'A.filename', 'ASC', '', 4 );
	// get_cssfile_list($client);
	
	$int_max = $db->affected_rows();
	for($i = 0; $i < $int_max; $i++) {
		$db->next_record();
		$_idcssfile = $db->f('idupl');
		if ($perm->have_perm('1', 'css_file', $_idcssfile)) {
			// Hintergrundfarbe wählen
			$tmp['ENTRY_BGCOLOR'] = '#F7FBFF';
			$tmp['ENTRY_BGCOLOROVER'] = '#FFF7CE';

			// filename und description
			// jb - 04.09.2004 - added debug info: idcssfile of css-file
			$tmp['ENTRY_NAME'] = htmlentities($db->f('filename'), ENT_COMPAT, 'UTF-8') . (($cfg_cms['debug_active']) ? ' (' . $_idcssfile . ')': '') ;
			// handle delete css-file
			if ($action == 50 && $_idcssfile == $idcssfile && $perm->have_perm('5', 'area_css')) {
				$tmp['ENTRY_NAME'] = $tmp['ENTRY_NAME'] . '&nbsp;&nbsp;' . make_image_link3 ("main.php?area=css&action=deletefile&idcssfile=".$_idcssfile."&idclient=$idclient",  'but_confirm.gif', 'but_cancel_delete.gif', $cms_lang['css_file_delete_confirm'], $cms_lang['css_file_delete_cancel'], 'action', 'deletethis' );
			}
			$tmp['ENTRY_DESCRIPTION'] = htmlentities($db->f('description'), ENT_COMPAT, 'UTF-8');

			// buttons and actions
			$tmp['ENTRY_SHOWDETAIL'] = make_image_link2 ( '#', 'space.gif', '', 16, 16, '', '', '', 'action', '', '', '', 'action' );
			$tmp['ENTRY_NEW']		 = make_image_link2 ( '#', 'space.gif', '', 16, 14, '', '', '', 'action', '', '', '', 'action' );
			$tmp['ENTRY_EDIT']		 = make_image_link2 ( '#', 'space.gif', '', 16, 16, '', '', '', 'action', '', '', '', 'action' );
			$tmp['ENTRY_DELBUT']	 = make_image_link2 ( '#', 'space.gif', '', 16, 16, '', '', '', 'action', '', '', '', 'action' );
			$tmp['ENTRY_DOWNLOAD']   = make_image_link2 ( '#', 'space.gif', '', 16, 16, '', '', '', 'action', '', '', '', 'action' );
			$tmp['ENTRY_DUPLICATE']  = make_image_link2 ( '#', 'space.gif', '', 16, 16, '', '', '', 'action', '', '', '', 'action' );
			$tmp['ENTRY_IMEXPORT']   = make_image_link2 ( '#', 'space.gif', '', 16, 16, '', '', '', 'action', '', '', '', 'action' );

			// button: expand css-file
			if ($_idcssfile == $idexpand)
				$tmp['ENTRY_SHOWDETAIL'] = make_image_link2 ("main.php?area=css&idclient=$idclient", 'but_minus.gif', $cms_lang['css_hiderules'], '16', '16', '', '', '', 'action', 'showthis');
			else
				$tmp['ENTRY_SHOWDETAIL'] = make_image_link2 ("main.php?area=css&idexpand=".$_idcssfile."&idclient=$idclient", 'but_plus.gif', $cms_lang['css_showrules'], '16', '16', '', '', '', 'action', 'showthis');
			
			$tmp['ENTRY_ICON'] = make_image('ressource_browser/icons/rb_typ_css.gif', '', '16', '16', false, 'class="icon"');
			
			// button: new css-rule
			if ($perm->have_perm('18', 'css_file', $_idcssfile)) 
				$tmp['ENTRY_NEW'] = make_image_link2 ("main.php?area=css_edit&idcssfile=".$_idcssfile."&idcss=0&idclient=$idclient", 'but_newstyle.gif', $cms_lang['css_new'], '16', '16', '', '', '', 'action', '', '', '', 'action');

			// button: download css-file
			if ($perm->have_perm('8', 'css_file', $_idcssfile)) {
				$tmp['ENTRY_DOWNLOAD'] = make_image_link2 ("main.php?area=css&action=downloadfile&idcssfile=".$_idcssfile."&idclient=$idclient&idexpand=$idexpand", 'but_download.gif', $cms_lang['css_file_download'], '16', '16', '', '', '', 'action', '', '', '', 'action');
			}
			if ($perm->have_perm('3', 'css_file', $_idcssfile)) {
			// button: edit css-file
				$tmp['ENTRY_EDIT'] = make_image_link2 ("main.php?area=css_edit_file&idcssfile=".$_idcssfile."&idclient=$idclient&idexpand=$idexpand", 'but_edit.gif', $cms_lang['css_file_edit'], '16', '16', '', '', '', 'action', '', '', '', 'action');
				// button: duplicate css-file, benötigt zusätzlich create rechte
			if ($perm->have_perm('2', 'area_css')) {
				$tmp['ENTRY_DUPLICATE'] = make_image_link2 ("main.php?area=css_edit_file&idcssfilecopy=".$_idcssfile."&idclient=$idclient&idexpand=$idexpand", 'but_duplicate.gif', $cms_lang['css_file_duplicate'], '16', '16', '', '', '', 'action', '', '', '', 'action');
				}
			}
			if ($perm->have_perm('5', 'css_file', $_idcssfile)) {
			// button: delete css-file
				$tmp['ENTRY_DELBUT'] = make_image_link2 ("main.php?area=css&action=50&idexpand=$idexpand&idcssfile=".$_idcssfile."&idclient=$idclient", 'but_deleteside.gif', $cms_lang['css_file_delete'], '16', '16', '', '', '', 'action', 'deletethis', '', '', 'action');
			}
			// button: import css-rules
			if ($perm->have_perm('29', 'css_file', $_idcssfile)) {
				$tmp['ENTRY_IMEXPORT'] = make_image_link2 ("main.php?area=css&idcssfile=".$_idcssfile."&idclient=0&idexpand=$idexpand", 'import.gif', $cms_lang['css_import'], '16', '16', '', '', '', 'action', '', '', '', 'action');
			}

			$tpl->setVariable($tmp);
			$tpl->parseCurrentBlock();
			unset($tmp);

			// change the current block, to show all further entries below the details
			// we do not support viewing more than one css-file details
			if ($_idcssfile == $idexpand) $tpl->setCurrentBlock('PASTENTRY');
		}
	}

	// no css-files to display
	if ($int_max < 1) show_none( 'NODETAIL', 'content7', $cms_lang['css_nofile'] );
	else {
		//
		// show css-rules, if a css-file is in expanded view
		//
		if ($idexpand && $idexpand != -1 && $perm->have_perm('17', 'css_file', $idexpand)) {
			$tpl->setCurrentBlock('DETAIL');
			$db = get_all_cssrules_of_file( $client, $idexpand, 0, 2, $cfg_client['css_sort_original'] );
			$int_max = $db->affected_rows();
			for($i = 0; $i < $int_max; $i++) {
				$db->next_record();
				set_rule_data($db, 'export');
				$tpl->setVariable($tmp);
				$tpl->parseCurrentBlock();
				unset($tmp);
			}
			// no css-rules in the css-file to display
			if ($int_max < 1) show_none( 'NODETAIL', 'contenta', $cms_lang['css_norules'] );
		}
	}
	unset($tmp);
}
//
// show the css-rules the user may import into a css-file, i.e. $idclient == 0
//
else {
 	if ($perm->have_perm('29', 'css_file', $idcssfile)) {
		// Zeige den Importberecih, wenn der User die Berechtigung zum Inport hat
	$tpl->setCurrentBlock('DETAIL');
	$db = get_all_cssrules_of_file( $idclient, 0, 0, 1 );
	$int_max = $db->affected_rows();
	for($i = 0; $i < $int_max; $i++) {
		$db->next_record();
		set_rule_data($db, 'import');
		$tpl->setVariable($tmp);
		$tpl->parseCurrentBlock();
		unset($tmp);
	}

	// no css-rules to display for import
	if ($db->affected_rows() == '0') show_none( 'NODETAIL', 'contenta', $cms_lang['css_norulesforimport'] );
	unset($tmp);
	}
}


if ($perm->have_perm('9', 'area_css')) {
	// Zeige den File-Upload wenn der User die Berechtigung zum Upload hat
	$tpl->setCurrentBlock('FILEIMPORT');
	$tmp['FILEIMPORT_ACTION'] = $sess->url("main.php");
	$tmp['FILEIMPORT_TYPE']   = 'css';
	$tmp['FILEIMPORT_FUNC']   = 'uploadfile';
	$tmp['FILEIMPORT_DIR']    = 'cms/css/';
	$tmp['FILEIMPORT_CLIENT'] = $client;

	$tmp['FILEIMPORT_BGCOLOR'] = 'content7';
	$tmp['FILEIMPORT_TEXT'] = $cms_lang['css_fileimport'];
	$tmp['FILEIMPORT_NAME'] = 'cssuploadfile';
	$tmp['FILEIMPORT_PICT'] = 'tpl/' . $cfg_cms['skin'] . '/img/upl_upload.gif';
	$tmp['FILEIMPORT_HINT'] = $cms_lang["upl_upload"];
 	$tmp['FILEIMPORT_BUTTONWIDTH']  = '16';
	$tmp['FILEIMPORT_BUTTONHEIGHT'] = '16';

	$tpl->setVariable($tmp);
	$tpl->parseCurrentBlock();
	unset($tmp);
}

//
// display error message if a rule has warnings
//
if ($ruleserror) show_none( 'RULESERROR', 'content3a', $cms_lang['err_1118'] );
unset($tmp);

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

//
// function for displaying rule-records
//
function set_rule_data($db, $imorexport) {
	global $tmp, $tpl, $ruleserror, $action, $idcss, $idcssfile, $idexpand, $idclient, $cms_lang, $cfg_cms, $perm, $sess;

	if ($perm->have_perm('17', 'css_file', $idexpand)) {
		// Zeige alle CSS-regeln, wenn der User die Berechtigung zum Anzeigen von CSS-Regeln hat
		// background-color of css-rules
		$tmp['DETAIL_BGCOLOR'] = ($db->f("status") == 1) ? '#FFFFFF':'#FFEFDE';
		$tmp['DETAIL_BGCOLOROVER'] = ($db->f("status") == 1) ? '#FFF7CE':'#FFF7CE';
		if (!$db->f('status')) $ruleserror = '1';

		// buttons
		$tmp['DETAIL_EDIT']		 = make_image_link2 ( '#', 'space.gif', '', 16, 16, '', '', '', 'action', '', '', '', 'action' );
		$tmp['DETAIL_DELBUT']	 = make_image_link2 ( '#', 'space.gif', '', 16, 16, '', '', '', 'action', '', '', '', 'action' );
		$tmp['DETAIL_DUPLICATE'] = make_image_link2 ( '#', 'space.gif', '', 16, 16, '', '', '', 'action', '', '', '', 'action' );
		$tmp['DETAIL_EXIMPORT']	 = make_image_link2 ( '#', 'space.gif', '', 16, 16, '', '', '', 'action', '', '', '', 'action' );

		// css-rule name and description
		// jb - 29.08.2004 - added details as tooltip for comfort
		// jb - 04.09.2004 - added debug info: idcss of rule
		$tmp['DETAIL_ICON'] = make_image('but_style.gif', '', '16', '16');
		$tmp['DETAIL_NAME'] = '<a class="action" href="javascript:void(0)" title="' . htmlspecialchars ($db->f('styles'), ENT_QUOTES, 'UTF-8') . '">' . htmlentities($db->f('type')) . htmlentities($db->f('name')) . (($cfg_cms['debug_active']) ? ' (' . $db->f('idcss') . ')': '') . '</a>';
		// handle delete css-rule
		if ($action == 30 && $db->f('idcss') == $idcss && $perm->have_perm('21', 'css_file', $idexpand)) {
			$tmp['DETAIL_NAME'] = $tmp['DETAIL_NAME'] . '&nbsp;&nbsp;' . make_image_link3 ("main.php?area=css&action=deleterule&idcssfile=$idcssfile&idexpand=$idexpand&idcss=".$db->f('idcss')."&idclient=$idclient", 'but_confirm.gif', 'but_cancel_delete.gif', $cms_lang['css_delete_confirm'], $cms_lang['css_delete_cancel'], 'action', 'deletethis' );
		}
		$tmp['DETAIL_DESCRIPTION'] = htmlentities($db->f('description'), ENT_COMPAT, 'UTF-8');

		// buttons
		if ($perm->have_perm('19', 'css_file', $idexpand)) {
			$url = ($imorexport == 'export') ? "&idexpand=$idexpand&idcssfile=$idexpand" : "&idcssfile=$idcssfile";
			// button: edit css-rule
			$tmp['DETAIL_EDIT']      = make_image_link2 ("main.php?area=css_edit".$url."&idcss=".$db->f('idcss')."&idclient=$idclient", 'but_edit.gif', $cms_lang['css_edit'], '16', '16', '', '', '', 'action', '', '', '', 'action');
			// button: duplicate css-rule, benötigt zusätzlich create rechte
			if ($perm->have_perm('18', 'area_css')) {
				$tmp['DETAIL_DUPLICATE'] = make_image_link2 ("main.php?area=css_edit&action=10".$url."&idcss=".$db->f('idcss')."&idclient=$idclient", 'but_duplicate.gif', $cms_lang['css_duplicate'], '16', '16', '', '', '', 'action', '', '', '', 'action');
			}
		}
		if ($perm->have_perm('21', 'css_file', $idexpand)) {
			// button: delete css-rule
			$tmp['DETAIL_DELBUT']    = make_image_link2 ("main.php?area=css&action=30".$url."&idcss=".$db->f("idcss")."&idclient=$idclient", 'but_deleteside.gif', $cms_lang['css_delete'], '16', '16', '', '', '', 'action', 'deletethis', '', '', 'action');
		}
		// button: export or import css-rule
		if (($perm->have_perm('29', 'css_file', $idcssfile) && $imorexport == 'import' && $idclient == 0) || ($perm->have_perm('30', 'css_file', $idexpand) && $imorexport == 'export' && $idclient != 0)) {
			$url = "&idexpand=$idcssfile&idcssfile=$idcssfile";
			$tmp['DETAIL_EXIMPORT'] = make_image_link2 ("main.php?area=css&action=$imorexport".$url."&idcss=".$db->f('idcss')."&idclient=$idclient", "$imorexport.gif", $cms_lang['css_'.$imorexport], '16', '16', '', '', '', 'action', '', '', '', 'action');
		}
	}
}
?>
