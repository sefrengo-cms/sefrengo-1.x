<?PHP
// File: $Id: inc.upl.php 28 2008-05-11 19:18:49Z mistral $
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

include('inc/fnc.upl.php');
include('inc/class.filemanager.php');
$fm = new filemanager();

/******************************************************************************
 2. Eventuelle Actions/ Funktionen abarbeiten
******************************************************************************/
$perm->check('area_upl');
if ($idclient != 0 && $idclient != (int) $client || !isset($idclient)) $idclient = (int) $client;

// if $action is named prepare a function call
if (!empty($action) && preg_match('/^\d/', $action) == 0) {
	eval( '$errno = upl_'.$action.'();' );

	// Event
	$errlog  = ($errno) ? 'Fehler:' . $errno: '';
	fire_event('upl'.$action, array('idupl' => $idupl, 'errlog' => $errlog));
	if (!empty($errno)) {
		switch($action) {
			case 'createdir':
				$action = 100;
				break;
			case 'editdir':
				$action = 110;
				break;
			default:
				break;
		}
	}
}


// actions:
// 1xx	directory actions to be confirmed
// 2xx	file actions to be confirmed
// named actions are confirmed to be done
//
// numbered actions
//
// 100	create directory
// 110	edit directory
// 120	delete directory
// 130	copy directory
// 140	move directory
// 150	change viewtype
// 160	reserved
// 170	reserved - download directory
// 180	scan directory
//
// 200	reserved
// 210	edit file
// 220	delete file
// 230	copy file
// 240	move file
// 250	export file
// 260	import file
// 270	download file
// 280	reserved
//
// named actions
//
// upl_createdir
// upl_editdir
// upl_deletedir
// upl_copydir
// upl_movedir
// upl_downloaddir
// upl_scandir
//
// upl_editfile
// upl_deletefile
// upl_copyfile
// upl_movefile
// upl_exportfile
// upl_importfile
// upl_downloadfile

/******************************************************************************
 3. Eventuelle Dateien zur Darstellung includieren
******************************************************************************/

include('inc/inc.header.php');

/******************************************************************************
 4. Bildschirmausgabe aufbereiten und ausgeben
******************************************************************************/

// Kopfbereich
$tmp['AREA_TITLE']    = $cms_lang['area_upl'];
$tmp['FOOTER_LICENSE'] = $cms_lang['login_licence'];
if ($errno) {
	$tmp['ERR_MSG']       = $cms_lang["err_$errno"];
}
$tmp['SUB_NAV_RIGHT'] = '';


// Templatedatei laden
$tpl->loadTemplatefile('filelist.tpl');

//
// headline
//
$tmp['FILENAME']    = $cms_lang['upl_directoriesandfiles'];
$tmp['DESCRIPTION'] = $cms_lang['upl_description'];
$tmp['ACTIONS']     = $cms_lang['upl_action'];

//
// create idexpand from quicknav-value
//
if ($idexpandshort) {
	if ($action = 'createdir') $fm->use_cache = false;
	$idexpand = $fm->get_parent_directories($idexpandshort, $client);
	if ($action = 'createdir') $fm->use_cache = true;
}
//
// get list of expanded directories
//
if (empty($idexpand)) {
	$idopen = 0;
} else {
	$idopen = explode('_', $idexpand );
	$idopen = $idopen[0];
}
//
// get directory tree in a array
//
$fm_tree = $fm->get_directory_tree($client, $idexpand);
//
// do viewtype setting
//
if (empty($viewtype)) $viewtype = 'compact';
if (!empty($action) && $action == '150') {
	$viewtype = (!$viewtype || $viewtype == 'detail') ? 'compact': 'detail';
	$action   = 0;
}
//
// get directory list in a dropdown-list
//
$dir_list     = $fm->get_directory_dropdown($client, true);
$tmp['QNAV']  = '<form action="' . $sess->url("main.php") . '" method="post" style="display: inline;" name="qnav">';
$tmp['QNAV'] = $tmp['QNAV'] . '<input type="hidden" name="area" value="upl" />';
$tmp['QNAV'] = $tmp['QNAV'] . '<input type="hidden" name="viewtype" value="' . $viewtype . '" />';
$tmp['QNAV'] = $tmp['QNAV'] . '<select name="idexpandshort" onchange="document.qnav.submit();">';
$tmp['QNAV'] = $tmp['QNAV'] . '<option selected value="">' . $cms_lang['upl_showfilesindir'] . '</option>'.$dir_list.'</select></form>';

//
// set javascript texts
//
$tmp['JSTEXT'] = '<script type="text/javascript">' . "\n";
$tmp['JSTEXT'] = $tmp['JSTEXT'] . 'var pp_title        = "' . $cms_lang['upl_js_texte_pp_title']        . '";' . "\n";
$tmp['JSTEXT'] = $tmp['JSTEXT'] . 'var pp_header_bild  = "' . $cms_lang['upl_js_texte_pp_header_bild']  . '";' . "\n";
$tmp['JSTEXT'] = $tmp['JSTEXT'] . 'var pp_header_datei = "' . $cms_lang['upl_js_texte_pp_header_datei'] . '";' . "\n";
$tmp['JSTEXT'] = $tmp['JSTEXT'] . 'var pp_created      = "' . $cms_lang['upl_js_texte_pp_created']      . '";' . "\n";
$tmp['JSTEXT'] = $tmp['JSTEXT'] . 'var pp_modified     = "' . $cms_lang['upl_js_texte_pp_modified']     . '";' . "\n";
$tmp['JSTEXT'] = $tmp['JSTEXT'] . 'var pp_author       = "' . $cms_lang['upl_js_texte_pp_author']       . '";' . "\n";
$tmp['JSTEXT'] = $tmp['JSTEXT'] . 'var pp_size         = "' . $cms_lang['upl_js_texte_pp_size']         . '";' . "\n";
$tmp['JSTEXT'] = $tmp['JSTEXT'] . '</script>' . "\n";

//
// do layout props
//
$tpl->setVariable($tmp);
unset($tmp);

//
// show directories and files
//
$tpl->setCurrentBlock('ENTRY');
showTree($fm_tree);

if ($idopen > 0 && $perm->have_perm(1, 'folder', $idopen)) show_files($idopen);

//
// generate file upload
// check upload permission for area first
if ($perm->have_perm(25, 'area_upl') || $perm->have_perm(25, 'folder', $idopen) ) {
	$tpl->setCurrentBlock('FILEIMPORTBULK');
	$tmp['FILEIMPORTTRIPLE_ACTION']  = $sess->url("main.php?area=upl&amp;idexpand=$idexpand&amp;viewtype=$viewtype");
	$tmp['FILEIMPORTTRIPLE_FUNC']    = 'uploadfile';
	$tmp['FILEIMPORTTRIPLE_CLIENT']  = $client;
	$tmp['FILEIMPORTTRIPLE_BGCOLOR'] = 'content7';
	$tmp['FILEIMPORTTRIPLE_TEXT']    = $cms_lang['upl_upload'];
	$tmp['FILEIMPORTTRIPLE_NAME']    = 'userfile[]';
	$tmp['FILEIMPORTTRIPLE_PICT']    = 'tpl/' . $cfg_cms['skin'] . '/img/upl_upload.gif';
	$tmp['FILEIMPORTTRIPLE_HINT']    = $cms_lang["upl_upload"];
	$tmp['FILEIMPORTTRIPLE_TO']      = $cms_lang['upl_newplace'];
	$dir_select = '<option value="">' . $cms_lang['upl_selectuploaddir'] . '</option>';
	if (extension_loaded('zip') && $idopen > 0) {
		$dir_select .= '<option value="-' . $idopen . '">' . $cms_lang['upl_bulkupload'] . '</option>';
	}
	if ($idopen > 0) {
		$dir_select .= '<option value="-' . $idopen . '">' . $cms_lang['upl_tarupload'] . '</option>';
	}
	$tmp['FILEIMPORTTRIPLE_DIRECTORY']    = str_replace('"'.$idopen.'">', '"'.$idopen.'" selected>', $dir_select.$dir_list);
 	$tmp['FILEIMPORTTRIPLE_BUTTONWIDTH']  = '15';
	$tmp['FILEIMPORTTRIPLE_BUTTONHEIGHT'] = '15';

	$tmp['FILEIMPORTSCRIPT_CALL'] = '';
	if ($action == 'uploadfile' && $iddirectory < 0 && empty($errno)) {
		$idbulkdir = abs($iddirectory);
		$errfiles = implode("|", $fm->error_files);	// list files with errors
		$link = $sess->url('main.php?area=scancontrol&amp;action=scandir&amp;idexpand=' . $idexpand . '&amp;iddirectory=' . $idbulkdir . '&amp;viewtype=' . $viewtype);
		$tmp['FILEIMPORTSCRIPT_CALL']  = '<script  type="text/javascript">';
		$tmp['FILEIMPORTSCRIPT_CALL'] = $tmp['FILEIMPORTSCRIPT_CALL'] . "	window.open('$link', 'scanwin', 'width=400,height=400,scrollbars=0');";
		$tmp['FILEIMPORTSCRIPT_CALL'] = $tmp['FILEIMPORTSCRIPT_CALL'] . '	var error_files = "' . str_replace('"', '\"', $errfiles) . '";';
		$tmp['FILEIMPORTSCRIPT_CALL'] = $tmp['FILEIMPORTSCRIPT_CALL'] . '</script>';
	}
	$tpl->setVariable($tmp);
	$tpl->parseCurrentBlock();
	unset($tmp);
}

//
// insert directory dropdown if action is move or copy dir
//
if (!empty($action) && ($action == 140 || $action == 130)) {
	$tmp_data = $tpl->get();
	$tmp_data = preg_replace ( '/%selectiondirlist%/i', str_replace('selected', '', $dir_list), $tmp_data );
	$tpl->setTemplate( $tmp_data, false, false );
}
//
// end of filemanager display
//


//
// functions for filemanager display
//
//
// shows the directory tree
//
function showTree( $subtree, $level = -1, $expand = '' ) {
	global $fm_tree, $cfg_client, $perm, $cms_lang, $idclient;

	if ($level == -1) {
		$treeroot = array( 0 => array( "iddirectory"=>0,
									 "dirname"=>$cfg_client['upl_path'],
									 "status"=>0,
									 "idclient"=>$idclient,
									 "parentid"=>0,
									 "name"=>$cms_lang["upl_root_dir"],
									 "description"=>$cfg_client['upl_htmlpath'],
									 "author"=>0 ) );
		showDirectory( -1, $treeroot[0], '', $treeroot[0]['status'], '#F8F8F8');
		$level = 0;
	}
	if (empty($level)) {
		foreach($subtree as $key => $value) {
			if ($value[0]['parentid'] != 0) continue;
      if ($perm->have_perm(1, 'folder', $value[0]['iddirectory'])) {
			showDirectory( $level, $value[0], $value[0]['iddirectory'], $value[0]['status'] );
			if (is_array($value['_members_'])) {
				foreach($value['_members_'] as $membervalue) {
					showTree( $fm_tree[$membervalue], $level+1, $value[0]['iddirectory'] );
				}
			}
		}
		}
	} else {
    if ($perm->have_perm(1, 'folder', $subtree[0]['iddirectory'])) {
		showDirectory( $level, $subtree[0], $subtree[0]['iddirectory'].'_'.$expand, $subtree[0]['status'] );
		if (is_array($subtree['_members_'])) {
			foreach($subtree['_members_'] as $membervalue) {
				showTree( $fm_tree[$membervalue], $level+1, $subtree[0]['iddirectory'].'_'.$expand );
			}
		}
	}
	}
}

//
// shows the files in an open directory
//
function show_files($idopen) {
	global $tpl, $idupl, $action, $cms_lang, $newname, $sess, $perm, $filelevel, $fileexpand, $cfg_client, $cfg_cms, $fm, $dir_list, $client, $viewtype, $deb;

	$is_detailview = ($viewtype == 'detail');	// unterscheidung für die anzeige
	$details_okay  = false;						// no files to display
	$indent        = ($filelevel+1)*11+12;

	// get file list and set display template
	$db       = $fm->get_files_in_directory($idopen, $client);
	$tpl_name = ($is_detailview) ? 'DETAILFM1': 'DETAIL';
	$tpl->setCurrentBlock($tpl_name);

	while($db->next_record()) {
		// found files to display
		$details_okay = true;
		// create basis link and get file id
		$idfile = $db->f('idupl');
	    // prüfe ob Datei gezeigt werden darf ...
		if ($perm->have_perm(17, 'file', $idfile, $idopen)) {
		$link = 'main.php?area=upl&amp;action=%s&amp;idexpand=' . $fileexpand . '&amp;iddirectory=' . $idopen . '&amp;idupl=' . $idfile . '&amp;viewtype=' . $viewtype.'#';
		// get thumbnail size
		$thumbsize = $fm->get_thumbnail_size($db);
		// get file information
		$file_info = get_dateinfo( array($db->f('created'), $db->f('lastmodified'), $db->f('vorname'), $db->f('nachname')) );
		
		// create view of file // Datei in Originalgröße anzeigen
		$popup_link      = '<a class="action" href="javascript:void(0)" onmouseout="sf_nd();" ';
		$popup_link     .= $is_detailview ? ' title="' . $cms_lang['upl_openfileinnewwindow'] . '" ': '';
		$popup_link     .= 'onclick="new_imagepopup(\'' . $cfg_client['upl_htmlpath'] . $db->f('dirname') . $db->f('filename');
		$popup_link     .= "','','" . $cms_lang['upl_popupclose'] . "','','" . $db->f('pictwidth') . "','" . $db->f('pictheight') . "','true');";
		$str_titel       = $db->f('titel')       ? htmlentities($db->f('titel'), ENT_NOQUOTES, 'UTF-8')      : '';
		$str_description = $db->f('description') ? htmlentities($db->f('description'), ENT_NOQUOTES, 'UTF-8'): '';
		$file_size_info  = ( $db->f('filesize') > 1024) ? sprintf( "%01.2f", $db->f('filesize')/1024) . '&nbsp;kByte': $db->f('filesize') . '&nbsp;Byte';
		$file_size_info .= $tmp['DETAILFM1_FILESIZE'] . '&nbsp;-&nbsp;' . $db->f('pictwidth') . 'x' . $db->f("pictheight") . 'px';
		if ($is_detailview) {
			$tmp['DETAILFM1BLOCKEDIT']    = 'fileblock';
			$tmp['DETAILFM1_NAMESTART']	  = $popup_link . " window.event.cancelBubble = true;\">";
			$tmp['DETAILFM1_NAMEEND']     = "</a>";
			$tmp['DETAILFM1_NAME']    	  = $db->f('filename')."<br /><br />";
			$tmp['DETAILFM1_PICTSRC']     = $thumbsize['thumbnail'];
			$tmp['DETAILFM1_PICTWIDTH']   = intval($thumbsize['width']);
			$tmp['DETAILFM1_PICTHEIGHT']  = intval($thumbsize['height']);
			$tmp['DETAILFM1_FILESIZE']    = $file_size_info . '<br />' . implode( "/", $file_info );
			$tmp['DETAILFM1_DESCRIPTION'] = '<strong>' . $cms_lang['upl_titel']       . ':</strong><br />' . $str_titel . '<br />';
			$tmp['DETAILFM1_DESCRIPTION'] = $tmp['DETAILFM1_DESCRIPTION']  . '<strong>' . $cms_lang['upl_description'] . ':</strong><br />' . $str_description;
			// create new function array
			$fm_func = array();
		} else {
			if (file_exists('tpl/'.$cfg_cms['skin'].'/img/ressource_browser/icons/rb_typ_'.$db->f('filetype').'.gif')) {
				$detail_icon = 'ressource_browser/icons/rb_typ_'.$db->f('filetype').'.gif';
			} else {
				$detail_icon = 'ressource_browser/icons/rb_typ_generic.gif';
			}
			$tmp['DETAIL_ICON'] = make_image($detail_icon, '', '16', '16', false, 'class="icon"');
			$tmp['DETAIL_NAME']        = $popup_link . '" onmouseover="previewPict2(\'' . $thumbsize['thumbnail'] . "', '" . intval($thumbsize['width']);
			$tmp['DETAIL_NAME']        = $tmp['DETAIL_NAME'] . "', '" . intval($thumbsize['height']) . "', '" . $file_info['created'] . "', '" . $file_info['changed'];
			$tmp['DETAIL_NAME']        = $tmp['DETAIL_NAME'] . "', '" . $file_info['author'] . "', true, '" . $db->f('filename') . "', '" . $file_size_info . "', '".$idfile."');\" >" . $db->f('filename') . "</a>";
			$tmp['DETAIL_BGCOLOR']     = '#FFFFFF';
			$tmp['DETAIL_BGCOLOROVER'] = '#FFF7CE';
			$tmp['DETAIL_STYLE']       = ' style="padding-left: '.$indent.'px;"';
			$tmp['DETAIL_DESCRIPTION'] = ($str_titel ? '<strong>' . $str_titel . '</strong><br />': '') . $str_description;
			// buttons and actions
			$tmp['DETAIL_DOWNLOAD']    = make_image_link2 ( '#', 'space.gif', '', 16, 16, '', '', '', 'action toolinfo', '', '', '', 'action' );
			$tmp['DETAIL_EDIT']        = make_image_link2 ( '#', 'space.gif', '', 16, 16, '', '', '', 'action toolinfo', '', '', '', 'action' );
			$tmp['DETAIL_DELBUT']      = make_image_link2 ( '#', 'space.gif', '', 16, 16, '', '', '', 'action toolinfo', '', '', '', 'action' );
			$tmp['DETAIL_DUPLICATE']   = make_image_link2 ( '#', 'space.gif', '', 16, 16, '', '', '', 'action toolinfo', '', '', '', 'action' );
			$tmp['DETAIL_MOVE']        = make_image_link2 ( '#', 'space.gif', '', 16, 16, '', '', '', 'action toolinfo', '', '', '', 'action' );
		}
		// buttons and actions
		// check permission for file download
  		if ($perm->have_perm(24, 'file', $idfile, $idopen)) {
			$str_link = sprintf($link, 'downloadfile');
			if ($is_detailview) $fm_func["DOWNLOAD"]    = "[ '" . $cms_lang['upl_download'] . "', '" . $str_link . "' ]";
			else                $tmp['DETAIL_DOWNLOAD'] = make_image_link2 ( $str_link, 'but_download.gif', $cms_lang['upl_downloadfile'], 16, 16, '', '', '', 'action' );
		}
		// check permission for file modifications
  		if ($perm->have_perm(19, 'file', $idfile, $idopen)) {
			$str_link = sprintf($link, '210');
			if ($is_detailview) $fm_func["EDIT"]    = "[ '" . $cms_lang['upl_edit'] . "' , '" . $str_link . "fileworkplace'   ]";
			else                $tmp['DETAIL_EDIT'] = make_image_link2 ( $str_link, 'but_edit.gif', $cms_lang['upl_editfile'], 16, 16, '', '', '', 'action', 'fileworkplace', '', '', 'action' );
			$str_link = sprintf($link, '230');
			if ($is_detailview) $fm_func["DUPLICATE"]    = "[ '" . $cms_lang['upl_copy'] . "'   , '" . $str_link . "fileworkplace'   ]";
			else                $tmp['DETAIL_DUPLICATE'] = make_image_link2 ( $str_link, 'but_duplicate.gif', $cms_lang['upl_copyfiletodir'], 16, 16, '', '', '', 'action', 'fileworkplace', '', '', 'action' );
			$str_link = sprintf($link, '240');
			if ($is_detailview) $fm_func["MOVE"]    = "[ '" . $cms_lang['upl_move'] . "', '" . $str_link . "fileworkplace'   ]";
			else                $tmp['DETAIL_MOVE'] = make_image_link2 ( $str_link, 'but_moveup.gif', $cms_lang['upl_movefiletodir'], 16, 16, '', '', '', 'action', 'fileworkplace', '', '', 'action' );
		}
		// check permission for file delete
  		if ($perm->have_perm(21, 'file', $idfile, $idopen)) {
			$str_link = sprintf($link, '220');
			if ($is_detailview) $fm_func["DELBUT"]    = "[ '" . $cms_lang['upl_del'] . "'    , '" . $str_link . "fileworkplace' ]";
			else                $tmp['DETAIL_DELBUT'] = make_image_link2 ( $str_link, 'but_delete.gif', $cms_lang["upl_deletefile"], 16, 16, '', '', '', 'action', 'fileworkplace', '', '', 'action' );
		}
		if ($is_detailview) {
			$tmp['DETAILFM1_AKTIONEN'] = '<img src="tpl/' . $cfg_cms['skin'] . '/img/but_edit.gif" onmouseover="showmenu(event, [\'' . $cms_lang['upl_editfile'] . "', [ " . implode(", ", $fm_func) . ' ] ])" onmouseout="delayhidemenu();" align="left" alt="" />';
		}
		// handle called actions
		if ($idupl == $db->f('idupl')) {
			switch ($action) {
				// edit file
				case 210:
					$actionname     = 'editfile';
					$linkokay       = 'javascript:document.fmedit.submit()';
					$str_filename   = $cms_lang['upl_copyfilename'] . ':<br /><input type="text" name="newfilename" value="' . (($newfilename)?$newfilename:$db->f('filename')) . '" size="30" maxlength="255" />';
					$str_titel      = $cms_lang['upl_titel'] . ':<br /><input type="text" name="newtitle" value="' . (($newtitle)?$newtitle:$db->f('titel')) . '" size="30" maxlength="200" />';
					$str_descrption = $cms_lang['upl_description'] . ':<br /><textarea name="newdescription" rows="3" cols="50">' . (($newdescription)?$newdescription:$db->f('description')) . '</textarea>';

					$tmp[$tpl_name.'_NAME']        = $str_filename;
					$tmp[$tpl_name.'_DESCRIPTION'] = $str_titel . '<br />' . $str_descrption . '<br />';
					if ($is_detailview) {
						$tmp[$tpl_name.'_DESCRIPTION'] = $tmp[$tpl_name.'_DESCRIPTION'] . $str_filename . '<br /><br />';
					}
					// check for permission to change user rights
  					if ($perm->have_perm(22, 'file', $idfile, $idopen)) {
						$panel = $perm->get_right_panel('file', $idfile, array( 'formname'=>'fmedit' ), 'img', false, false, $idopen);
						if (!empty($panel)) {
							$tmp[$tpl_name.'_DESCRIPTION'] = $tmp[$tpl_name.'_DESCRIPTION'] . $panel['rights'];
							$tmp[$tpl_name.'_DESCRIPTION'] = $tmp[$tpl_name.'_DESCRIPTION'] . $panel['call'];
							$tmp[$tpl_name.'_EDITSCRIPT']  = $panel['scripts'];
						}
					}
					// add buttons
					$tmp[$tpl_name.'_DESCRIPTION'] = $tmp[$tpl_name.'_DESCRIPTION'] . make_image_link4 ('but_confirm.gif', 'but_cancel_delete.gif', $cms_lang["upl_editfile"], $cms_lang['upl_cancel'], '', '', 'main.php?area=upl&amp;viewtype=' . $viewtype . '&amp;idexpand=' .$fileexpand );
					break;

				// delete file
				case 220:
					$actionname  = 'deletefile';
					$linkokay    = $link;
					$but_confirm = make_image_link3 ('main.php?area=upl&amp;action=deletefile&amp;viewtype=' . $viewtype . '&amp;idupl=' . $idupl . '&amp;idexpand=' . $fileexpand, 'but_confirm.gif', 'but_cancel_delete.gif', $cms_lang["upl_deletefile"], $cms_lang['upl_cancel'], '', 'fileworkplace' );
					$tmp[$tpl_name.'_DESCRIPTION']  = $cms_lang['upl_confirm_delete'] . '<br /><br />' . $but_confirm;
					break;
				// duplicate file
				case 230:
					$actionname = 'copyfile';
					$linkokay   = 'javascript:document.fmedit.submit()';
					$tmp[$tpl_name.'_DESCRIPTION'] = $cms_lang["upl_copyfilename"] . '<br /><input type="text" name="newfilename" value="' . (($newfilename)?$newfilename:$db->f('filename')) . '" size="30" maxlength="255" /><br />';
					$tmp[$tpl_name.'_DESCRIPTION'] = $tmp[$tpl_name.'_DESCRIPTION'] . $cms_lang['upl_newplace'] . ':<br /><select name="movetargetid"><option value="">' . $cms_lang["upl_copyfiletodir"] . ' ...</option>' . str_replace('selected', '', $dir_list) . '</select><br /><br />';
					$tmp[$tpl_name.'_DESCRIPTION'] = $tmp[$tpl_name.'_DESCRIPTION'] . make_image_link4 ('but_confirm.gif', 'but_cancel_delete.gif', $cms_lang["upl_copyfiletodir"], $cms_lang['upl_cancel'], '', '', 'main.php?area=upl&amp;viewtype=' . $viewtype . '&amp;idexpand=' . $fileexpand);
					break;
				// move file
				case 240:
					$actionname = 'movefile';
					$linkokay   = 'javascript:document.fmedit.submit()';
					$tmp[$tpl_name.'_DESCRIPTION'] = $cms_lang['upl_copyfilename'] . '<br /><input type="text" name="newfilename" value="' . (($newfilename)?$newfilename:$db->f('filename')) . '" size="30" maxlength="255" /><br />';
					$tmp[$tpl_name.'_DESCRIPTION'] = $tmp[$tpl_name.'_DESCRIPTION'] . $cms_lang['upl_newplace'] . ':<br /><select name="movetargetid"><option value="">' . $cms_lang["upl_movefiletodir"] . ' ...</option>';
					$tmp[$tpl_name.'_DESCRIPTION'] = $tmp[$tpl_name.'_DESCRIPTION'] . str_replace('selected', '', $dir_list) . '</select><br /><br />';
					$tmp[$tpl_name.'_DESCRIPTION'] = $tmp[$tpl_name.'_DESCRIPTION'] . make_image_link4 ('but_confirm.gif', 'but_cancel_delete.gif', $cms_lang["upl_movefiletodir"], $cms_lang['upl_cancel'], '', '', 'main.php?area=upl&amp;viewtype='.$viewtype.'&amp;idexpand='.$fileexpand);
					// $tmp['DETAIL_DESCRIPTION']     = $tmp['DETAILFM1_DESCRIPTION'];
					break;
				default:
					break;
			}
			if ($actionname) {
				if ($is_detailview) {
					$tmp['DETAILFM1_NAME']		= '';
					$tmp['DETAILFM1_FILESIZE']	= '';
					$tmp['DETAILFM1_AKTIONEN']	= ''; 
					$tmp['DETAILFM1ACTIVE']		= '<a name="fileworkplace"></a>';
					$tmp['DETAILFM1BLOCKEDIT']	= 'fileblockactive';
				}
				makeRowEdit($tmp, array('url'     =>'main.php',
										'enctype' =>'text/plain',
										'block'   =>$tpl_name.'ROWEDIT',
										'hidden'  => array( 'area'		 => 'upl',
															'idexpand'	 => $fileexpand,
															'idupl'		 => $db->f('idupl'),
															'action'	 => $actionname,
															'filename'	 => $db->f('filename'),
															'description'=> htmlentities($db->f('description'), ENT_QUOTES, 'UTF-8'),
															'iddirectory'=> $db->f('iddirectory'),
															'idfiletype' => $db->f('idfiletype'),
															'viewtype'	 => $viewtype
															)
										)
							);
			}
		}
		$tpl->setVariable($tmp);
		$tpl->parseCurrentBlock();
		unset($tmp);
	}
	}

	// create detail box if in detail view and files where found
	if ($details_okay && $is_detailview) {
		// open detail block
		$tpl->setCurrentBlock('DETAILFM');
		$tmp = array();
		$tmp['DETAILFM_BGCOLOR']		= '#DEEFFF';
		$tmp['DETAILFM_BGCOLOROVER']	= '#FFF7CE';
		// $tmp['DETAILFM_STYLE']		= ' style="padding:5px 0px 5px 0px;"';
		$tpl->setVariable($tmp);
		$tpl->parseCurrentBlock();
		unset($tmp);
		// close detail block
		$tpl->setCurrentBlock('DETAILFM2');
		$tmp['DETAILFM2_FAKE'] = ' ';
		$tpl->setVariable($tmp);
		$tpl->parseCurrentBlock();
		unset($tmp);
	}
}

function showDirectory( $level, $line_array, $prefixexpand, $status, $monocolor = false ) {
	global $tpl, $idopen, $client, $iddirectory, $action, $cms_lang, $newname, $sess, $perm, $filelevel, $fileexpand;
	global $newdirname, $newdirdescription, $idexpand, $cfg_client, $cfg_cms, $viewtype;

	$named_place	= '<a name="dirworkplace"></a>';
	$iddir			= $line_array['iddirectory'];
	$selected		= '';
	$class			= $monocolor ? $monocolor : '#FFFFFF';
	$class2			= $monocolor ? $monocolor : '#FFF7CE';
	$hint			= $cms_lang['upl_opendir'];
	$pict			= 'but_folder_off2.gif';
	$expand			= $iddir . '_' . ($level) ? $prefixexpand :'';
	$actionexpand	= $idexpand;
	$indent			= ($level == -1) ? 4: ($level*21+4);

	if ($idopen == $iddir) {
		$class		= $monocolor ? $monocolor : '#DEEFFF';
		$class2		= $monocolor ? $monocolor : '#FFF7CE';
		$hint		= $cms_lang['upl_closedir'];
		$pict		= 'but_folder.gif';
		$selected	= ' selected';
		$expand		= ($level) ? '-'.$expand: '';
	}

	// directory informations
	$link						= 'main.php?area=upl&amp;action=%s&amp;idexpand=' . $actionexpand . '&amp;iddirectory=' . $iddir . '&amp;viewtype=' . $viewtype;

	$tmp['ENTRY_SHOWDETAIL']	= ($level < 0) ?  $line_array['name']: make_image_link2 ('main.php?area=upl&amp;viewtype=' . $viewtype . '&amp;idexpand=' .$expand, $pict, $hint, '', '', '', '', '', 'action', '', '', $line_array['name'], 'action' );

	$tmp['ENTRY_BGCOLOR']		= $class;
	$tmp['ENTRY_BGCOLOROVER']	= $class2;
	$tmp['ENTRY_STYLE']			= ' style="padding-left: ' . $indent . 'px;" ';
	$tmp['ENTRY_DESCRIPTION']	= $line_array['description'];
	// buttons and actions
	$tmp['ENTRY_SCAN']			= make_image_link2 ( '#', 'space.gif', '', 16, 16, '', '', '', 'action toolinfo', '', '', '', 'action' );
	$tmp['ENTRY_NEW']			= make_image_link2 ( '#', 'space.gif', '', 16, 16, '', '', '', 'action toolinfo', '', '', '', 'action' );
	$tmp['ENTRY_EDIT']			= make_image_link2 ( '#', 'space.gif', '', 16, 16, '', '', '', 'action toolinfo', '', '', '', 'action' );
	$tmp['ENTRY_DELBUT']		= make_image_link2 ( '#', 'space.gif', '', 16, 16, '', '', '', 'action toolinfo', '', '', '', 'action' );

	// check if root directory
	if ($level < 0) {
		// button for changing the view
    // Auswahl für Detail- oder Compact-Modus
		if ($viewtype == 'compact')
			$tmp['ENTRY_SPECIAL'] = make_image_link2 ( sprintf($link, 150), 'but_viewdetail.gif', $cms_lang['upl_changeviewdetail'], 16, 16, '', '', '', '', '', '', '', 'action' );
		else 
			$tmp['ENTRY_SPECIAL'] = make_image_link2 ( sprintf($link, 150), 'but_viewcompact.gif', $cms_lang['upl_changeviewcompact'], 16, 16, '', '', '', '', '', '', '', 'action' );
		// clear dir name
		$line_array['dirname'] = '';
	} else {
	
	}
	// buttons for functions
	// check permission for create directory
	if ($perm->have_perm(2, 'folder', $iddir)) {
		$tmp['ENTRY_NEW'] = make_image_link2 ( sprintf($link, 100), 'but_newcat.gif', $cms_lang['upl_createdir'], 16, 16, '', '', '', '', 'dirworkplace', '', '', 'action' );
	}
	// check permission for edit directory
	if ($perm->have_perm(3, 'folder', $iddir)) {
		if ($level >= 0) {
			$tmp['ENTRY_EDIT'] = make_image_link2 ( sprintf($link, 110), 'but_edit.gif', $cms_lang['upl_editdir'], 16, 16, '', '', '', '', 'dirworkplace', '', '', 'action' );
		}
	}
	// check permission for delete directory
	if ($perm->have_perm(5, 'folder', $iddir)) {
		if ($level >= 0) {
			$tmp['ENTRY_DELBUT'] = make_image_link2 ( sprintf($link, 120), 'but_delete.gif', $cms_lang['upl_deletedir'], 16, 16, '', '', '', '', 'dirworkplace', '', '', 'action' );
		}
	}
	// check permission for directory scan
	if (($perm->have_perm(11, 'area_upl') && $level == -1) || $perm->have_perm(11, 'folder', $iddir)) {
		$link = 'main.php?area=scancontrol&amp;action=%s&amp;idexpand=' . $actionexpand . '&amp;iddirectory=' . $iddir . '&amp;viewtype=' . $viewtype;
		$tmp['ENTRY_SCAN'] = make_image_link5 ( sprintf($link, 'scandir'), 'but_folder_scan.gif', (($level == -1) ? $cms_lang['upl_scandir_root']: $cms_lang['upl_scandir']), 16, 16, 'scanwin', 'width=400,height=400,scrollbars=0', '', '', 'action', '', '', '', 'action' );
	}

	// handle called actions
	// functions will override the description or add something to the name
	if ($iddirectory == $line_array['iddirectory']) {
		switch ($action) {
			// create new directory
			case 100:
				$actionname = 'createdir';
				// show directory that will contain the new directory
				$tmp['ENTRY_SHOWDETAIL'] = $tmp['ENTRY_SHOWDETAIL'] . $named_place;
				$tpl->setVariable($tmp);
				$tpl->parseCurrentBlock();
				unset($tmp);
				// create line for new directory
				quoteSaveText($newdirname);
				quoteSaveText($newdirdescription);
				$tmp['ENTRY_SHOWDETAIL']  = '<input type="text" name="newdirname" value="' . $newdirname . '" size="20" maxlength="255" />';
				$tmp['ENTRY_BGCOLOR']	  = $class;
				$tmp['ENTRY_BGCOLOROVER'] = $class2;
				$tmp['ENTRY_STYLE']		  = ' style="padding-left: '.($indent+3).'px;" ';
				$tmp['ENTRY_DESCRIPTION'] = $cms_lang['upl_description'] . ':<br /><textarea name="newdirdescription" rows="3" cols="50">' . $newdirdescription . '</textarea><br /> ';
				$tmp['ENTRY_DESCRIPTION'] = $tmp['ENTRY_DESCRIPTION'] . make_image_link4 ('but_confirm.gif', 'but_cancel_delete.gif', $cms_lang['upl_createdir'], $cms_lang['upl_cancel'], '', '', 'main.php?area=upl&amp;viewtype=' . $viewtype . '&amp;idexpand=' . $actionexpand . '&amp;iddirectory=' . $iddir, '16', '16');
				break;
			// edit existing directory
			case 110:
				$actionname = 'editdir';
				quoteSaveText($newdirname);
				quoteSaveText($newdirdescription);
				$tmp['ENTRY_SHOWDETAIL']	 = $named_place . make_image_link2 ('main.php?area=upl&amp;idexpand=' . $expand, $pict, $hint, 16, 16, '', '', '', 'action', '', '', '', 'action' );
				$tmp['ENTRY_NAME']			 = '<input type="text" name="newdirname" value="' . (($newdirname)?$newdirname:$line_array['name']) . '" size="20" maxlength="255" />';
				$tmp['ENTRY_DESCRIPTION']	 = $cms_lang['upl_description'] . ':<br /><textarea name="newdirdescription" rows="3" cols="50">' . (($newdirdescription)?$newdirdescription:$line_array['description']) . '</textarea><br /> ';
				if ($perm->have_perm(6, 'folder', $iddirectory)) {
					$panel = $perm->get_right_panel('folder', $iddirectory, array( 'formname'=>'fmedit' ), 'img' );
					if (!empty($panel)) {
						$tmp['ENTRY_DESCRIPTION'] = $tmp['ENTRY_DESCRIPTION'] . implode("", $panel);
					}
				}
				$tmp['ENTRY_DESCRIPTION'] = $tmp['ENTRY_DESCRIPTION'] . make_image_link4 ('but_confirm.gif', 'but_cancel_delete.gif', $cms_lang['upl_editdir'], $cms_lang['upl_cancel'], '', '', 'main.php?area=upl&amp;viewtype=' . $viewtype . '&amp;idexpand=' . $actionexpand . '&amp;iddirectory=' . $iddir, '16', '16');
				break;
			// delete directory
			case 120:
				$tmp['ENTRY_DELETE'] = make_image_link3 ('main.php?area=upl&amp;action=deletedir&amp;viewtype=' . $viewtype . '&amp;iddirectory=' . $line_array['iddirectory'] . '&amp;idclient=' . $client . '&amp;idexpand=' . $expand, 'but_confirm.gif', 'but_cancel_delete.gif', $cms_lang['upl_deletedir'], $cms_lang['upl_cancel'], '', 'dirworkplace' );
				break;
			// to do for version 1.x
			// 130: copy directory
			// 140: move directory
			// 170: download directory
			default:
				break;
		}
		if ($actionname) {
			makeRowEdit($tmp, array('url'		=>'main.php?action=' . $actionname,
									'enctype'	=>'text/plain',
									'block'		=>'ROWEDIT',
									'hidden'	=>array('area'				=>'upl',
														'idexpand'			=>$actionexpand,
														'iddirectory'		=>$line_array['iddirectory'],
														'parentid'			=>$line_array['iddirectory'],
														'parentdirname'		=>$line_array['dirname'],
														'dirdescription'	=>$line_array['iddirectory'],
														'dirname'			=>$line_array['dirname'],
														'viewtype'			=>$viewtype
														)
									));
		}
	}
	$tpl->setVariable($tmp);
	$tpl->parseCurrentBlock();
	unset($tmp);

	if ($idopen == $iddir) {
		$tpl->setCurrentBlock('PASTENTRY');
		$filelevel = $level;
		$fileexpand = str_replace('-', '', $actionexpand);
	}
}

function get_dateinfo( $infos ) {
	global $cfg_cms;
	$date_format = $cfg_cms['FormatDate'] . ' ' . $cfg_cms['FormatTime'];
	$file_info   = array( 'created'=>date($date_format, $infos[0]),
						  'changed'=>date($date_format, $infos[1]),
						  'author' =>$infos[2] . " " . $infos[3] );
	return $file_info;
}

function makeRowEdit( &$tmp, $in ) {
	global $sess;
	$tmp[$in['block']]       = '<form name="fmedit" class="filemanageredit" action="' . $sess->url($in['url']) . '" method="post">';
	foreach($in['hidden'] as $key => $value) {
		$tmp[$in['block']] = $tmp[$in['block']] . '<input type="hidden" name="' . $key . '" value="' . $value . '" />';
	}
	$tmp[$in['block'].'END'] = '</form>';
}

function makeStatusEdit( &$tmp, $in ) {
	global $cms_lang;
	$tmp[$in['block']] = $tmp[$in['block']] . make_image_link4 ('but_confirm.gif', 'but_cancel_delete.gif', $cms_lang[$in['func']], $cms_lang['upl_cancel'], 'action', 'action', $in['back'], '16', '16');
}

function quoteSaveText(&$code) {
	remove_magic_quotes_gpc($code);
	$code = str_replace( '"', '&quot;', $code);
}
?>
