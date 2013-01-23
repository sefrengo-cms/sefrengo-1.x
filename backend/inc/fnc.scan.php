<?PHP
// File: $Id: fnc.scan.php 28 2008-05-11 19:18:49Z mistral $
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
Description : functions for scanning directories and files
Copyright   : Jürgen Brändle, 2003
Author      : Jürgen Brändle, braendle@web.de
Urls        : www.Sefrengo.de
Create date : 2003-12-01
Last update : 2003-12-21

Public functions
upl_scandir()
upl_scanfiles()
upl_scanthumbs()

Private functions

******************************************************************************/

function upl_scandir(){
	global $deb, $fm, $iddirectory, $cfg_client, $client, $auth, $scanned_dirs, $found_dirs, $found_files;
	global $scanned_dirs_complete, $max_dirs, $extend_time, $perm, $nosubdirscan;

	$extend        = (strtolower(get_cfg_var("safe_mode")) == 'off');
	$dirs2scan     = array();
	$dirnames2scan = array();
	
	$fm->use_cache = false;
	$fm->found_files = 0;
	$fm->found_dirs = 0;
	$fm->error_dirs = array();
	//
	// find the start directory
	if ((int)$iddirectory < 0) {
		// iddirectoy negativ: the scan process has been started, but not finished
		// get the directory list of directoies to scan from the database
		$fm->get_directories_by_status(0x30, 0x74, $dirs2scan, $client, $dirnames2scan);
	} else {
		// erster aufruf ... löschen scan flags in directories und files
		$fm->clear_directories_status($client, 0x04);
		$fm->clear_file_status($client, 0x04);
		$tmp = $fm->get_directory( (int) $iddirectory, $client );
		$dirs2scan = array( (string) $tmp['iddirectory'] );
	}
	//
	// start scanning the directories
	//
	$i = 0;
	while ($i < count($dirs2scan)) {
	    $current_directory = (int) $dirs2scan[$i];
		$newdirs = $fm->scan_directory( $current_directory, $nosubdirscan );
		// Lösche Rückgabe, wenn keine Unterverzeichnisse bearbeitet werden sollen
		if (!empty($nosubdirscan)) {
			for($j = 0; $j < count($newdirs); $j++) {
				$fm->clear_directories_status($client, 0x8F, $newdirs[$j]);
			}
			$newdirs = array();
		}
		// set user rights for new found directories
		if (count($fm->edit_dirs) > 0) {
			$sourcerights = ($current_directory == 0) ? 'area_upl': 'folder';
			$perm->copy_perm($current_directory, $sourcerights, $fm->edit_dirs, 0, $fm->lang, false);
		}
		
		if (count($newdirs) > 0) $dirs2scan = array_merge($dirs2scan, $newdirs);
		$i++;
		// extend script time out if possible
		// or stop scanning if max limit for scanning is reached
		if ($extend) set_time_limit($extend_time);
		else {
			if ($i > $max_dirs) break;
		}
	}
	// if all dirs are done ...
	if ($i >= count($dirs2scan)) {
		// delete missing directories and clear the directory scan status
		$fm->delete_directories_not_found($client);
		$fm->clear_directories_status($client, 0xAF);
		$scanned_dirs_complete = true;
	} else {
		$scanned_dirs_complete = false;
	}
	$found_files  = $fm->found_files;
	$scanned_dirs = $fm->scan_called;
	$found_dirs   = $fm->found_dirs;
	// return error-code
	$fm->use_cache = true;
	return $fm->errno;
}

	function upl_scanfiles(){
		global $deb, $fm, $iddirectory, $cfg_client, $client, $auth, $scanned_dirs, $scanned_files, $scanned_dirs_complete;
		global $scanned_files_complete, $max_files, $extend_time, $perm, $found_files, $updatethumbs;
		
		$extend        = (strtolower(get_cfg_var("safe_mode")) == 'off');
		$scanned_files = 0;
		$scanned_dirs  = 0;
		$dirs2scan     = array();
		$dirnames2scan = array();
		$scanned_dirs_complete = true;
		
		$fm->found_files = 0;
		$fm->use_cache = false;
		$fm->set_addon_flag = true;
		$fm->get_directories_by_status(0x20, 0x24, $dirs2scan, $client, $dirnames2scan);
		$i = 0;
		while ($i < count($dirs2scan)) {
			// $scanned_files += $fm->scan_files( $dirs2scan[$i] );
			$fm->scan_files( $dirs2scan[$i], $updatethumbs );
			// set user rights for new found directories
			// set user rights for new found files
			if (count($fm->edit_files) > 0) {
				$perm->xcopy_perm((int) $dirs2scan[$i], 'folder', $fm->edit_files, 'file', 0x01B50000, 0, $fm->lang, false);
			}
			$i++;
			if ($extend) set_time_limit($extend_time);
			else {
				if ($i > $max_files) break;
			}
		}
		$scanned_files = $fm->found_files;
		// do file delete if any files are missing
		if ($i >= count($dirs2scan)) {
			$scanned_files_complete = true;
			$fm->delete_files_not_found($client);
			$fm->clear_directories_status($client, 0x8F);
		}
		// return error-code
		$fm->use_cache = true;
		return $fm->errno;
	}

	function upl_scanthumbs(){
		global $fm, $iddirectory, $cfg_client, $client, $auth, $scanned_thumbs, $found_thumbs, $scanned_dirs_complete, $scanned_files_complete, $scanned_thumbs_complete, $max_thumbs;
		$scanned_dirs_complete  = true;
		$scanned_files_complete = true;
		$scanned_thumbs = 0;
		$files2thumb = array();
		
		$fm->use_cache      = false;
		$fm->set_addon_flag = false;
		$fm->get_files_by_status(32, 36, $files2thumb);
		$i = 0;
		$found_thumbs = count($files2thumb);
		while ($i < $found_thumbs) {
			$donefiles = $fm->scan_addon( (int) $files2thumb[$i] );
			$i++;
			if ($extend) set_time_limit($extend_time);
			else {
				if ($i > $max_thumbs) break;
			}
		}
		$scanned_thumbs = $i;
		// do directory delete if any directories are missing
		if ($i >= count($files2thumb)) {
			$scanned_thumbs_complete = true;
			$fm->clear_file_status($client, 0xCF);
		}
		// return error-code
		$fm->use_cache = true;
		return $fm->errno;
	}


?>
