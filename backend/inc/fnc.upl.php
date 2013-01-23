<?PHP
// File: $Id: fnc.upl.php 28 2008-05-11 19:18:49Z mistral $
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

/******************************************************************************
Description : class for the filemanager
Copyright   : Jürgen Brändle, 2003
Author      : Jürgen Brändle, braendle@web.de
Urls        : www.Sefrengo.de
Create date : 2003-03-06
Last update : 2003-05-17

Public functions
upl_editfile()
upl_deletefile()
upl_copyfile()
upl_movefile()
upl_exportfile
upl_importfile
upl_downloadfile
upl_uploadfile()

upl_createdir()
upl_editdir()
upl_deletedir()
upl_copydir()
upl_movedir()
upl_downloaddir()
upl_scandir()


Private functions

******************************************************************************/
//
// methods for the filemanager display
//

//
// upl_editfile()
//
// edits a file
// - disk and db
//   - rename file
// - db only
//   - change description
//   - change visibility
//   - change protection
//
// parameter
// $idupl must be set
// $newfilename must be set
// $newdescription must be set
// $filename must be set
// $description must be set
// $filevisible may be set
//	if missing: visible
// $fileprotected may be set
//	if missing: not protected
//
// workflow:
//	check if filename has changed
//	  check if new filename isn't used in directory
//	  rename file
//	  update db record
//	check if user is allowed to change visibility and or protection of a file
//	  change the status of the file
//
//	all other changes might be changed without further testing
//
function upl_editfile() {
	global $fm, $client, $cfg_client, $idupl, $idfiletype, $iddirectory, $perm;
	global $newtitle, $newfilename, $newdescription, $filename, $filevisible, $fileprotected, $cms_gruppenids, $cms_gruppenrechte, $cms_gruppenrechtegeerbt, $cms_gruppenrechteueberschreiben;

	// rechte prüfen für edit file
	if (!$perm->have_perm(19, 'file', $idupl, $iddirectory)) return '1701';

	// get validator object
	$validate = get_validator('upload');

	// check if the required values are set
	if (empty($newfilename) || !$validate->filename($newfilename)) return '1400'; // filename missing or contains invalid chars

	// check if filetype and extention are equal
	if (!$fm->check_filetype((int) $idfiletype, $newfilename)) {
		// automatically add the extention of the filetype if config-value "add_filetype" is set
		if ($cfg_client['add_filetype']) $newfilename .= '.' . $fm->tmp_filetypedata['id'.$idfiletype]['filetype'];
		else return '1407';	 // change of filetype not allowed
	}

	// check if filename has changed and if the new filename can be used
	if ($newfilename != $filename) {
		if (!$fm->rename_file_fs( (int)$idupl, $newfilename)) return '1401'; // new filename is in use ... no rename possible
		// delete thumbnail
		$oldthumbnail = $cfg_client['upl_path'] . $fm->get_thumbnail_filename($fm->get_complete_filename($idupl), $fm->tmp_filetypedata['id'.$idfiletype]['filetype']);
		$fm->delete_file_fs($oldthumbnail);
		$fm->errno = '';
	}

	// so far no error conditions ... update db record
	// get status settings
	$status = (int)$filevisible + (int)$fileprotected;
	$fm->update_file2( $idupl, $client, $newfilename, (int) $iddirectory, (int) $idfiletype, (int)$status, $newdescription, $newtitle );
	
	// check if user rights have to be set
	if (empty($fm->errno)) {
		if ($perm->have_perm(22, 'file', $idupl, $iddirectory)) {
			$perm->set_group_rights( 'file', $idupl, $cms_gruppenids, $cms_gruppenrechte, $cms_gruppenrechtegeerbt, $cms_gruppenrechteueberschreiben, '', 0xFFFFFFFF, $iddirectory );
		}
	}
	return $fm->errno;
}

function upl_deletefile(){
	global $fm, $idupl, $client, $perm, $idexpand;

	// check if the required values are set
	if (empty($idupl)) return '1402'; // fileid is missing

	// rechte prüfen für delete file
	if (!$perm->have_perm(21, 'file', $idupl, $idexpand)) return '1701';

	// so far no error conditions ... delete file and db record
	$fm->delete_file( (int) $idupl, (int) $client);
	// delete user rights have to bee set
	if (empty($fm->errno)) {
		$perm->delete_perms($idupl, 'file', 0, 0, 0, true);
	}
	return $fm->errno;
}

/**
 * Kopiert eine Datei innerhalb des Medienbereiches und in der Datenbank
 *
 * @author	Jürgen Brändle
 * @since	ALPHA
 * @version 0.2 / 20041010
**/
// to do: check filetype change
function upl_copyfile(){
	global $fm, $newfilename, $movetargetid, $idupl, $client, $cfg_client, $perm, $idexpand;

	// rechte prüfen für copy file
	if (!$perm->have_perm(19, 'file', $idupl, $idexpand)) return '1701';

	if ($fm->is_filename_in_use_fs( (int) $movetargetid, $newfilename )) return '1401'; // filename is used, copy not possible
	if (!$fm->copy_file_fs( (int) $movetargetid, $newfilename, (int) $idupl)) return '1403'; // copy in filesystem failed
	// so far no error conditions ... create db record
	$tmp_data = $fm->tmp_filedata[(int)$idupl];
	$idfile = $fm->insert_file((int)$client, $newfilename, (int) $movetargetid, (int) $tmp_data['idfiletype'], (int) $tmp_data['status'], $tmp_data['description'], '', $tmp_data['created'], $tmp_data['filesize'], $tmp_data['pictwidth'], $tmp_data['pictheight'], $tmp_data['pictthumbwidth'], $tmp_data['pictthumbheight'], $tmp_data['titel']);
	$fm->check_file_addons($tmp_data['filetype'], array('idfile'=>$idfile, 'location'=>$cfg_client['upl_path'].$tmp_data["dirname"].$tmp_data["filename"]), 'new' );
	// copy existing user rights for new file
	if (empty($fm->errno) && !empty($idfile)) {
		$perm->copy_perm($idupl, 'file', $idfile, 0, $fm->lang, false);
	}
	return $fm->errno;
}

// to do: check filetype change
function upl_movefile(){
	global $fm, $newfilename, $movetargetid, $idupl, $idexpand, $client, $cfg_client, $perm;

	// rechte prüfen für move file
	if (!$perm->have_perm(19, 'file', $idupl, $idexpand)) return '1701';

	if ($fm ->is_filename_in_use_fs( (int) $movetargetid, $newfilename )    ) return '1401'; // filename is used, move not possible
	if (!$fm->move_file_fs( (int) $movetargetid, $newfilename, (int) $idupl)) return $fm->errno; // move in filesystem failed
	// so far no error conditions ... update db record
	$tmp_data = $fm->get_file( (int) $idupl);
	$fm->check_file_addons($tmp_data['filetype'], array('idfile'=>$idupl, 'location'=>$cfg_client['upl_path'].$tmp_data["dirname"].$tmp_data["filename"]), "delete" );
	$fm->update_file( $idupl, $client, $newfilename, (int) $movetargetid );
	return $fm->errno;
}

function upl_downloadfile(){
	global $fm, $idupl, $cfg_client, $perm, $idexpand;

	// rechte prüfen für download file
	if (!$perm->have_perm(24, 'file', $idupl, $idexpand)) return '1701';

	$fm->get_file((int)$idupl);
	// so far no error conditions ... get directory and filename
	$dir      = $cfg_client['upl_path'] . $fm->tmp_filedata[(int)$idupl]['dirname'];
	$filename = $fm->tmp_filedata[(int)$idupl]['filename'];
	$fm->download_file($dir, $filename, (int)$idupl);
}

// Bulk-Uploads sind mit negativer iddirectory versehen, das muss zurückgewandelt werden
// und zwar nach der Unterscheidung ob Bulk-Upload oder File-Upload
function upl_uploadfile(){
	global $fm, $client, $iddirectory, $auth, $perm;

	// Pflichtwerte prüfen
	if (empty($iddirectory)) return '0706'; // path not found

	// Rechte prüfen für Upload ins Vereichnis $iddirectory
	if (!$perm->have_perm(25, 'folder', abs($iddirectory))) return '1701';

	$_iddir = abs($iddirectory);
	$path = $fm->get_directory_name((int)$_iddir);
	if (empty($path)) return '0706'; // path not found

	if ($iddirectory > 0) {
		$errno = $fm->upload_file($path, (int)$_iddir, (int)$client);
		if (!empty($errno) || count($fm->edit_files) == 0) return $errno;
		// set userright after upload
		// 1. if userrights are existing ... no changes
		// 2. no userrights are existing ... xcopy folder-rights to the file, reset the folder bits
		foreach($fm->edit_files as $idfile) {
			if (!$perm->perms_existing($idfile, 'file')) {
				$perm->xcopy_perm((int)$_iddir, 'folder', $idfile, 'file', 0x01B50000, 0, 0, true);  // copy userrights from folder
			}
		}
		$perm->set_owner_rights( 'file', $fm->edit_files, 0x01B50000); // set ownerrights for current language and user
	} else {
		// Bulk-Upload abarbeiten
		// user rights will be set by scan-directory, according to the rule of a single upload
		// this will be initiated by a script call onload of the fielmanager
		return ($fm->upload_archive($path));
	}
	return $fm->errno;
}

	//
	// methods for the directory handling
	//
	function upl_createdir(){
		global $fm, $client, $parentid, $parentdirname, $newdirname, $newdirdescription, $visible, $protected, $perm, $idexpandshort;

		// rechte prüfen für create directory
		// jb ... 23.04.04 ... parentid des parent für rechte prüfung als id übergeben
		//                     ermöglicht die prüfung ob in einem directory durch den user 
		//                     weitere directories angelegt werden dürfen.
		//                     Rechtegruppe auf Untergruppe folder von area_upl gesetzt.
		if (!$perm->have_perm(2, 'folder', $parentid)) return '1701';

		// get validator object
		$validate = get_validator('upload');

		// check requirements
		if (empty($newdirname)) return '1405'; // requirements
		if (!$validate->filepath($newdirname, true)) return '1406'; // bad chars in dirname
		// create new directory
		$newdir = $parentdirname . $newdirname;
		if (substr($newdir, -1) != '/') $newdir .= '/';
		$status = (int)$visible+(int)$protected;
		if (!$status) $status = 0;
		$fm->create_directory($newdir, $client, $newdirdescription, (int)$status );
		// set userrights
		if (!$fm->errno && is_array($fm->edit_dirs)) {
			foreach($fm->edit_dirs as $iddir) {
				if (!$perm->perms_existing($iddir, 'folder', true)) {
					if   ($parentid > 0) $perm->copy_perm($parentid, 'folder', $iddir, 0, 0, true);  // copy userrights from parent folder
				}
			}
			$perm->set_owner_rights( 'folder', $fm->edit_dirs, 0x01B505B7); // set ownerrights
			$idexpandshort = $iddir;
		}
		return $fm->errno;
	}

	function upl_editdir(){
		global $fm, $client, $newdirname, $newdirdescription, $dirname, $dirdescription, $iddirectory, $cms_gruppenids, $cms_gruppenrechte, $cms_gruppenrechtegeerbt, $cms_gruppenrechteueberschreiben;

		// rechte prüfen für edit directory
		if (!$fm->perm->have_perm(3, 'folder', $iddirectory)) return '1701';

		// get validator object
		$validate = get_validator('upload');

		// check if the required values are set
		if (empty($newdirname)) return '1405'; // requirements
		if (!$validate->filepath($newdirname)) return '1406'; // bad chars in dirname
		// check if the new dirname can be used
		$rename_done = $fm->rename_directory_fs($newdirname, $iddirectory);
		if ($rename_done) {
			// so far no error conditions ... update db record
			// get status settings
			$status = 0;
			$tmp = $fm->tmp_directorydata[$iddirectory];
			$olddirname = $tmp['dirname'];
			$newdir = substr ( $tmp['dirname'], 0, strlen($tmp['dirname'])-strlen($tmp['name'])-1 ) .$newdirname . '/';
			$fm->update_directory($iddirectory, $client, $newdirname, $newdir, $newdirdescription, (int)$parentid, (int)$status );
			$fm->change_child_dirname($olddirname, $client, $newdir);
		}
		// check if user rights have to be set
    	if ($fm->perm->have_perm(6, 'folder', $idupl)) {
			$fm->perm->set_group_rights( 'folder', $iddirectory, $cms_gruppenids, $cms_gruppenrechte, $cms_gruppenrechtegeerbt, $cms_gruppenrechteueberschreiben, '', 0xFFFFFFFF, '0' );
		}
		return $fm->errno;
	}

	function upl_deletedir(){
		global $fm, $iddirectory, $client, $perm;

		// check if the required values are set
		if (empty($iddirectory)) return '1405'; // requirements

		// rechte prüfen für delete directory
		if (!$perm->have_perm(5, 'folder', $iddirectory)) return '1701';

		// call the filemanager delete_directory
		$fm->delete_directory((int)$iddirectory, (int)$client);
		if (empty($fm->errno)) {
			$perm->delete_perms($iddirectory, 'folder', 0, 0, 0, true);
		}
		return $fm->errno;
	}

	// to do ... sometime ...
	function upl_copydir(){
		return '';
	}

	function upl_movedir(){
		return '';
	}

	function upl_downloaddir(){
		return '';
	}
	
	function upl_exportfile(){
		return '';
	}
	
	function upl_importfile(){
		return '';
	}

?>
