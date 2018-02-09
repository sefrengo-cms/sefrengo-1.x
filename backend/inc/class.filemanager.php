<?PHP
// File: $Id: class.filemanager.php 28 2008-05-11 19:18:49Z mistral $
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
Last update : 2003-10-30

DB functions
-- filetype methods
get_filetype_id($filetype, $create = false)
get_filetype($filetype, $type = 1)
get_filetype_by_id($idfiletype, $type = 1)
update_filetype($idfiletype, $filetype = '', $filetypepict = '', $filetypegroup = '', $description = '', $status = '')
insert_filetype($filetype, $filetypepict = '',  $filetypegroup = '',  $description = '',  $status = '')
check_filetype($idfiletype, $newfilename)

-- directory methods
get_directory_id($dirname, $idclient, $create = false)
get_directory_name($iddirectory)
create_directory($dirname, $idclient = '', $description = '', $status = 0)
get_directory($directory, $idclient = '', $type = 1 )
update_directory($iddirectory, $idclient = '', $name = '',  $dirname = '',  $description = '',  $parentid = '',  $status = '')
insert_directory($idclient,  $name,  $dirname,  $description = '',  $parentid = 0,  $status = 0)
delete_directory($iddirectory, $idclient)
scan_directory( $iddirectory )
delete_missing_directories()
get_directory_tree($idclient, $idexpand)
get_parent_directories($iddirectory, $idclient)

-- file methods
get_file($file, $idclient = '', $iddirectory = '', $type = 1)
update_file($idupl, $idclient = '', $filename = '', $directory = '', $filetype = '', $status = '', $description = '', $used = '' )
insert_file($idclient, $filename, $directory, $filetype, $status, $description, $used = '', $filetime = 'now()' )
delete_file($idupl, $idclient = '')
upload_file($path, $iddirectory, $idclient, $fieldname = 'userfile')
get_files_in_directory($iddirectory, $idclient, $order = 'A.filename', $ordersort = 'ASC' )


Filesystem functions
rename_file_fs( $idupl, $newfilename )
is_filename_in_use_fs( $directory, $filename )
copy_file_fs( $directory, $filename, $idfile )
move_file_fs( $directory, $filename, $idfile )
delete_file_fs($idupl)
create_directory_fs($dirname, $dirstart)
delete_dir_fs($iddirectory)
rename_directory_fs($dirname, $iddirectory )
write_file($location, $content)

Utility functions
validate_filename($name)

******************************************************************************/
require_once ($this_dir.'inc/class.fileaccess.php');

class filemanager extends fileaccess {
	//
	// properties
	//
	var $addon_factory  = null;
	var $addon_call     = false;
	var $set_addon_flag = false;
    var $thumb_ext      = '_cms_thumb';
	var $edit_files     = array();
	var $edit_dirs      = array();
	var $error_dirs     = array();
	var $error_files    = array();
	var $chmod_enabled  = false;
	var $chmod_value    = 0777;
 	var $scan_called    = 0;
 	var $found_files    = 0;
 	var $found_dirs     = 0;
 	//
	var $upload_dir     = '';
	//
	// constructor
	//
	function __construct() {
		global $this_dir, $cfg_cms;
		
		parent::__construct();
		if ($this->cfg_client['upl_addon']) {
			include_once ($this_dir.'inc/class.fileaddon.php');
			$this->addon_factory = new addon_factory();
		}
	    if ($this->cfg_client['thumbext']) $this->thumb_ext = $this->cfg_client['thumbext'];
		// set properties
		$this->upload_dir    = $this->cfg_client['upl_path'];
		$this->chmod_enabled = ($cfg_cms['chmod_enabled'] == '1');
		$this->chmod_value   = intval($cfg_cms['chmod_value'], 8);

	}

	function set_chmod( $enable, $value ) {
		$this->chmod_enabled = $enable;
		$this->chmod_value   = intval($value, 8);
	}
	
	//
	// db methods
	//

	//
	// filetype methods
	//
	// remark: no delete of any filetype, this is to ensure data integrity
	//         update only for description, filetypepict, status, filetypegroup
	//         creating of new filetypes when adding files to a client with unknown extention
	//

	// retrieves a filetype id
	// a filetype will be created if $created is true
	// if no record for the filetype is found an empty string will be returned
	function get_filetype_id($filetype, $create = false) {
		// check if wanted id is in cache ...
		if ($this->tmp_filetype_ids[$filetype]) return $this->tmp_filetype_ids[$filetype];
		// else retrieve id from db, save in cache and return id
		$tmp = $this->get_filetype($filetype);
		if (!$tmp && $create) {
			$this->insert_filetype($filetype, '',  '',  '',  0);
			$tmp = $this->get_filetype($filetype);
		}
		if ($tmp) $this->tmp_filetype_ids[$filetype] = $tmp['idfiletype'];
		return (($tmp) ? $this->tmp_filetype_ids[$filetype]: '');
	}


	//
	// update_filetype($idfiletype, $filetype = '', $filetypepict = '', $filetypegroup = '', $description = '', $status = '')
	//
	// mixed: $ status	 -> array : status will be changed according to status and keep-bitmask
	//					 -> else  : new value of status
	//
	function update_filetype($idfiletype, $filetype = '', $filetypepict = '', $filetypegroup = '', $description = '', $status = '') {
		if (!$idfiletype) return false; // error, missing filetypeid
		else {
			$parameter = array( 'author'=>array('uid', 'std'), 'idfiletype'=>array($idfiletype, 'num', '=') );
			// if ($filetype)   $parameter['filetype']      = array( $filetype     , 'str' );
			if ($filetypepict)  $parameter['filetypepict']  = array( $filetypepict , 'str' );
			if ($filetypegroup) $parameter['filetypegroup'] = array( $filetypegroup, 'str' );
			if ($description)   $parameter['description']   = array( $description  , 'str' );
			if (is_array($status) || is_int($status)) $parameter['status'] = (is_array($status)) ? array('((status & '.$status['keep'].')|'.$status['status'].')', 'func'): array($status, 'num');
			$this->db->update( 'filetype', $parameter );
			$this->tmp_filetypedata[$filetype] = '';
		}
		return true;
	}

	//
	// insert_filetype($filetype, $filetypepict = '',  $filetypegroup = '',  $description = '',  $status = '')
	//
	function insert_filetype($filetype, $filetypepict = '',  $filetypegroup = '',  $description = '',  $status = '') {
		if (empty($filetype)) return 0; // error, missing name of filetype

		$parameter = array( 'author'=>array('uid', 'std'), 'created'=>array('now()', 'func') );
		$parameter['filetype'] = array( $filetype     , 'str' );
		if (empty($filetypepict) || !file_exists($this->fileicon_path.$filetypepict)) {
			// check if a fileicon named $filetype.gif exists
			if(file_exists($this->fileicon_path.$filetype.'.gif')) {
				$parameter['filetypepict']  = array($filetype.'.gif' , 'str' );
			} elseif (file_exists($this->fileicon_path.'upl_'.$filetype.'.gif')) {
				$parameter['filetypepict']  = array('upl_'.$filetype.'.gif' , 'str' );
			}
		} else {
			$parameter['filetypepict']  = array( $filetypepict , 'str' );
		}
		if ($filetypegroup) $parameter['filetypegroup'] = array( $filetypegroup, 'str' );
		if ($description)   $parameter['description']   = array( $description  , 'str' );
		if ($status != '')  $parameter['status']  		= array($status, 'num');
		$this->tmp_filetype_ids[$filetype] = $this->db->insert( 'filetype', 'idfiletype', $parameter );
		return ($this->tmp_filetype_ids[$filetype]);
	}

	//
	// directory methods
	//
	// retrieves a directory id
	function get_directory_id($dirname, $idclient = '', $create = false) {
		$client = (!empty($idclient)) ? $idclient: $GLOBALS['client'];
		// check if wanted id is in cache ...
		if ($this->tmp_directory_ids[$dirname.'_'.$client]) return $this->tmp_directory_ids[$dirname.'_'.$client];
		// else retrieve id from db, save in cache and return id
		$tmp = $this->get_directory($dirname, $client);
		if (empty($tmp) && $create) {
			$tmp = $this->create_directory($dirname, $client);
			$tmp = $this->get_directory($dirname, $client);
		}
		if (!empty($tmp)) $this->tmp_directory_ids[$dirname.'_'.$client] = $tmp['iddirectory'];
		return (empty($tmp) ? '': $this->tmp_directory_ids[$dirname.'_'.$client]);
	}

	// creates a new directory
	// additionally all missing parent directories will be created
	function create_directory($dirname, $idclient = '', $description = '', $status = 0) {
		// set parameter
		$this->errno = '';
		$dirnew    = array();
		$dirstart  = '';
		$parenntid = 0;
		$this->edit_dirs = array();
		// check requirements
		if (!$dirname) $this->errno = '1401'; // required field missing
		else {
			$dirs      = explode ('/', strtr($dirname, '\\', '/'));
			$client = (!empty($idclient)) ? $idclient: $GLOBALS['client'];
			// search for a record of a parent directory
			while (!$parentid && count($dirs) > 0) {
				$dir = join ( '/', $dirs);
				if (substr($dir, -1) != '/') $dir .= '/';
				$tmp = $this->get_directory($dir, $client);
				if ($tmp) {
					$parentid = $tmp['iddirectory'];
					$dirstart = $dir;
				} else {
					array_push($dirnew, array_pop($dirs));
				}
			}
			// create all missing directories
			for($i = count($dirnew)-1; $i > 0; $i--) {
				if (trim($dirnew[$i]) != '') {
					$newdir = $dirstart . $dirnew[$i] . '/';
					if (!$this->create_directory_fs($dirnew[$i], $dirstart)) {
						// set errno and leave the function
						$this->errno = '1418'; // make directory failed
						return 0;
					} else {
						$parentid = $this->insert_directory($client, $dirnew[$i], $newdir, (($i == 1)? $description:''), (int) $parentid, (($newdir == $dirname)? $status:0));
						$dirstart = $newdir;
						if (!$parentid) {
							$this->errno = '1403'; // directory record could not be written
							return 0;
						}
						$this->edit_dirs[] = $parentid;
					}
				}
			}
		}
		return $parentid;
	}

	//
	// update_directory($iddirectory, $idclient, $name,  $dirname,  $description,  $parentid,  $status )
	//
	// mixed: $parentid -> string: retrieve ID of parent directory from db
	//					-> else  : ID of parent directory
	// mixed: $ status	 -> array : status will be changed according to status and keep-bitmask
	//					 -> else  : new value of status
	//
	function update_directory($iddirectory, $idclient = '', $name = '',  $dirname = '',  $description = '',  $parentid = '',  $status = '') {
  	if(!$iddirectory) return false; // error, missing directoryid
		else {
  		$parameter = array( 'author'=>array('uid', 'std'), 'iddirectory'=>array($iddirectory, 'num', '=') );
			if ($idclient)     $parameter['idclient']    = array( $idclient, 'num' );
			if ($name)         $parameter['name']        = array( $name    , 'str' );
			if ($dirname)      $parameter['dirname']     = array( $dirname , 'str' );
			if ($description)  $parameter['description'] = array( $description, 'str' );
			if (is_array($status) || is_int($status)) $parameter['status'] = (is_array($status)) ? array('((status & '.$status['keep'].')|'.$status['status'].')', 'func'): array($status, 'num');
			if ($parentid) {
				$client = (!empty($idclient)) ? $idclient: $GLOBALS['client'];
				$parent = (is_string($parentid)) ? $this->get_directory_id($parentid, $client): $parentid;
			 	$parameter['parentid'] = array( $parent, 'num' );
			}
			$this->db->update( 'directory', $parameter );
			$this->tmp_directorydata[$iddirectory] = '';
		}
		return true;
	}

	//
	// insert_directory($idclient,  $name,  $dirname,  $description,  $parentid,  $status)
	//
	// mixed: $parentid -> string: retrieve ID of parent directory from db
	//					-> else  : ID of parent directory
	//
	function insert_directory($idclient,  $name,  $dirname,  $description = '',  $parentid = 0,  $status = 0) {
		if (!$idclient || !$name || !$dirname) return 0; // missing required fields
		else {
			$parent = (!empty($parentid) && is_string($parentid)) ? $this->get_directory_id((string)$parentid, $idclient): $parentid;
			$parameter = array( 'author'=>array('uid', 'std'), 'created'=>array('now()', 'func') );
			$parameter['idclient'] = array( $idclient, 'num' );
			$parameter['name']     = array( $name    , 'str' );
			$parameter['dirname']  = array( $dirname , 'str' );
			$parameter['status']   = array( $status  , 'num' );
		 	$parameter['parentid'] = array( $parent  , 'num' );
			if ($description)  $parameter['description'] = array( $description, 'str' );

			$this->tmp_directory_ids[$dirname.'_'.$idclient] = $this->db->insert( 'directory', 'idupl', $parameter );
			return ($this->tmp_directory_ids[$dirname.'_'.$idclient]);
		}
	}

	// Löscht das verzeichnis $iddirectory im Projekt $idclient
	function delete_directory($iddirectory, $idclient) {
		if (empty($iddirectory) || empty($idclient)) $this->errno = '1405'; // missing required fields
		else {
			if (!$this->delete_dir_fs($iddirectory)) $this->errno = '1408'; // delete of directory failed
			else $this->db->delete_by_id_and_client( 'directory', 'iddirectory', $iddirectory, $idclient );
		}
		return (empty($this->errno));
	}

	//
	// change_child_dirname($olddirname, $client, $newdir)
	//
	// Aktualisiert die Directory-Namen der Unterverzeichnisse eines
	// Verzeichnisses, das zuvor umbenannt wurde
	//
	function change_child_dirname($olddirname, $client, $newdir) {
		global $cms_db;

		$position = strlen($olddirname);

		$sql  = 'SELECT ';
		$sql .= '   iddirectory, dirname ';
		$sql .= 'FROM ';
		$sql .=   $cms_db['directory'] . ' ';
		$sql .= 'WHERE ';
		$sql .= "    dirname  like '" . $olddirname . "%' ";
		$sql .= 'AND idclient =     ' . $client;
		
		$this->db->sql = $sql;
		$this->db->needReturn = true;
		$db = $this->db->exec_query();
		while($db->next_record()) {
			$iddir   = $db->f("iddirectory");
			$dirname = substr( $db->f("dirname"), $position );
			if (strlen($dirname) > 0) {
				$sqls[]  = 'UPDATE ' . $cms_db['directory'] . " SET dirname = '" . $newdir . $dirname . "' WHERE iddirectory = $iddir ";
			}
		}
		
		$max = count($sqls);
		$this->db->needReturn = false;
		for($i = 0; $i < $max; $i++) {
			$this->db->sql = $sqls[$i];
			$this->db->exec_query();
		}
		return $this->errno;
	}
	
	//
	// scan_directory($iddirectory)
	//
	// to do: directories
	//			übernehme status vom parent directory
	//        files
	//			übernehme status von filetype OR directory
	//
	function scan_directory( $iddirectory, $nosubdirscan ) {
		global $client, $cms_db, $deb;

		// get validator object
		$validate = get_validator('upload');
		// scan directory
		$newdirs2scan     = array();
		$this->edit_dirs  = array();
		$content_found    = false;
		$currentdir       = [];
		$scandir          = '';

		// get scan directory
		$this->_get_scan_dir($iddirectory, $currentdir, $scandir);
		if (!is_dir($scandir)) {
			// error ... given name is not a known directory
			$this->db->delete_by_id_and_client( 'directory', 'iddirectory', $iddirectory, $client );
		} else {
			if (($currentdir['status'] & 0x40) != 0x40) {
				$this->scan_called++;
				// do scan directory
				// set constant values
				$new_dir_status      = ($currentdir['status'] | 0x30);	// set status for file scanning in new directories
				$current_iddirectory = $currentdir['iddirectory'];		// get current directory id
				// set directories to scanning status
				$sql  = 'UPDATE ';
				$sql .=   $cms_db['directory'] . ' ';
				$sql .= 'SET ';
				$sql .= ' status       = (status | 0x30), ';
				$sql .= ' lastmodified = lastmodified ';
				$sql .= 'WHERE ';
				$sql .= '     idclient        = ' . $client; 
				$sql .= ' AND (status & 0x64) = 0x00 ';
				// jb - 12.09.2004 - if no subdirectory scan wanted restrict status to current dir
				if (!empty($nosubdirscan)) {
					$sql .= ' AND iddirectory = ' . $current_iddirectory ;
				} else {
				// if current directory is not root, restrict status to current dir and children
					if ($current_iddirectory != '0') {
						$sql .= ' AND (parentid       = ' . $current_iddirectory . ' OR iddirectory = ' . $current_iddirectory . ')';
					}
				}
				$this->db->sql = $sql;
				$this->db->needReturn = false;
				$this->db->exec_query();
				// get dirs and files
				$handle      = opendir ($scandir);
				while (false !== ($file = readdir($handle))) {
					if (strpos($file, $this->thumb_ext) === false && $file != '.' && $file != '..' ) {
						// handle subdirectories
						$onefile = $scandir.$file;
						if (is_dir($onefile)) {
							// check if directory name contains invalid chars
							if ($validate->filepath($file)) { // no invalid chars
								// prüfe ob directory existiert:
								// ja: markiere für subscan
								// nein: anlegen und markieren für subscan
								$dirname = $currentdir['dirname'].$file.'/';
								$newdir  = $this->get_directory($dirname, $client);
								if (empty($newdir)) {
									$deb->collect("Datei: ".$file);
									// new directory found, insert 
									$this->insert_directory($client, $file,  $dirname,  '',  (int) $current_iddirectory,  $new_dir_status);
									$newdir            = $this->get_directory($dirname, $client);
									$this->edit_dirs[] = $newdir['iddirectory'];			// add new dir to editlist, to copy perms
									$newdirs2scan[]    = (string) $newdir['iddirectory'];   // add new dir to new scan list
									$this->found_dirs++;
								} else {
									// save directory id to remove the scan marks later
									if ($newdir['iddirectory'] != $current_iddirectory) {
										$newdirs2scan[]  = (string) $newdir['iddirectory'];   // add new dir to new scan list
									}
									$this->found_dirs++;
								}
							} else {
								echo $file."<br>";
								$this->error_dirs[] = $onefile;
							}
							$content_found = true;
						} else {
							if ($current_iddirectory != 0) $this->found_files++;
							$content_found = true;
						}
			        } else {
						if ($file != '.' && $file != '..') $content_found = true;	// count files or anything else than directories ...
					}
	  			}
	  			closedir($handle);
				$this->db->needReturn = false;
				// delete all scan marks of found directories
				$countdirs = count($newdirs2scan) + count($this->error_dirs);
				if (!$content_found && $countdirs == 0 && ($this->cfg_client['remove_empty_directories'] || $this->cfg_client['remove_empty_directories'] == '1')) {
					// delete directory because no files or directories were found and flag is set to true/1
					$this->delete_directory($current_iddirectory, $client);
					// delete files of directory (delete via ftp ...)
					$sql  = 'DELETE FROM ';
					$sql .= $cms_db['upl'];
					$sql .= ' WHERE ';
					$sql .= '     iddirectory = ' . $current_iddirectory;
					$sql .= ' AND idclient    = ' . $client;
					$this->db->sql = $sql;
					$this->db->exec_query();
				}
				// delete scan marks of current directory
				$this->clear_directories_status($client, 0xEF, $current_iddirectory);
				$this->set_directories_status($client, 0x40, '', $current_iddirectory, false);
			}
    	}
		// return ids of new directories
		return $newdirs2scan;
	}

	function _get_scan_dir($iddirectory, &$currentdir, &$scandir) {
		global $client;
		
		if ($iddirectory > 0) {
			$scandir      = $this->cfg_client['upl_path'] . $this->get_directory_name( (int) $iddirectory);
			$currentdir  = $this->get_directory((int)$iddirectory, $client);
		} else {
			$scandir      = $this->cfg_client['upl_path'];
			$currentdir  = array( 'iddirectory'=>0, 'status'=>0, 'dirname'=>'' );
		}
	}

	function delete_directories_not_found($idclient) {
		global $cms_db;
		// get all the directories to be deleted to delete all file entries in cms_upl, ignore system dirs
		$dirs = array();
		$dirnames = array();
		$this->get_directories_by_status(0x30, 0x74, $dirs, $idclient, $dirnames);
		if (is_array($dirs) && count($dirs) > 0) {
			// get all children dirs of all dirs to be deleted
			for($i = 0; $i < count($dirnames); $i++) {
				$this->get_children_directories($dirnames[$i], $dirs, $idclient);
			}
			// compose where-clause for the delete-queries
			$sqlwhere  = ' WHERE ';
			$sqlwhere .= '     iddirectory IN (' . implode(',', $dirs) . ')';
			$sqlwhere .= ' AND idclient    = ' . $idclient;
			// delete all files of missing directories
			$sql  = 'DELETE FROM ' . $cms_db['upl'] . $sqlwhere;
			$this->db->sql = $sql;
			$this->db->exec_query();
			// delete all missing directories and subdirectories, ignore system dirs
			$sql  = 'DELETE FROM ' . $cms_db['directory'] . $sqlwhere;
			$this->db->sql = $sql;
			$this->db->exec_query();
		}
	}
	
  function clear_directories_status($idclient, $mask, $iddirectory = -1) {
  	global $cms_db;
		// delete all scan-marks
		$sql  = 'UPDATE ';
		$sql .=   $cms_db['directory'] . ' ';
		$sql .= 'SET ';
		$sql .= ' status       = (status & ' . $mask . '), ';
		$sql .= ' lastmodified = lastmodified ';
		$sql .= 'WHERE ';
		$sql .= ' idclient = ' . $idclient;
		if ($iddirectory != -1) $sql .= ' AND iddirectory = ' . $iddirectory;
		$this->db->sql = $sql;
		$this->db->needReturn = false;
		$this->db->exec_query();
  }

  function set_directories_status($idclient, $mask, $extendwhere, $iddirectory = -1, $parents = false) {
  	global $cms_db;
		// set directories to scanning status
		$sql  = 'UPDATE ';
		$sql .=   $cms_db['directory'] . ' ';
		$sql .= 'SET ';
		$sql .= ' status       = (status | ' . $mask . '), ';
		$sql .= ' lastmodified = lastmodified ';
		$sql .= 'WHERE ';
		$sql .= '     idclient        = ' . $idclient; 
		$sql .= $extendwhere;
		// if directory is set, restrict update
		if ($iddirectory != -1) {
			if ($parents) $sql .= ' AND (parentid = ' . $iddirectory . ' OR iddirectory = ' . $iddirectory . ')';
			else          $sql .= ' AND iddirectory = ' . $iddirectory;
		}
		$this->db->sql = $sql;
		$this->db->needReturn = false;
		$this->db->exec_query();
  }
	
  function get_directories_by_status($status, $mask, &$dirs, $idclient, &$dirnames) {
		global $cms_db;
 
		$sql  = 'SELECT ';
		$sql .= ' iddirectory, dirname ';
		$sql .= 'FROM ';
		$sql .=   $cms_db['directory'] . ' ';
		$sql .= 'WHERE ';
		$sql .= '     (status & ' . $mask . ') = ' . $status;
		$sql .= ' AND idclient        = ' . $idclient;
		$this->db->sql = $sql;
		$this->db->needReturn = true;
		$db = $this->db->exec_query();
		while($db->next_record()) {
			$dirs[]     = $db->f("iddirectory");
			$dirnames[] = $db->f("dirname");
		}
		return $fm->errno;
  }
  
  // neue funktion für unterverzeichnisse
  function get_children_directories($dirname, &$dirs, $idclient) {
		global $cms_db;

		$sql  = 'SELECT ';
		$sql .= ' iddirectory ';
		$sql .= 'FROM ';
		$sql .=   $cms_db['directory'] . ' ';
		$sql .= 'WHERE ';
		$sql .= "     dirname like '" . $dirname . "%' ";
		$sql .= ' AND idclient        = ' . $idclient;
		$this->db->sql = $sql;
		$this->db->needReturn = true;
		$db = $this->db->exec_query();
		while($db->next_record()) {
			$dirs[] = $db->f("iddirectory");
		}
		return $fm->errno;
  }
  
  
	//
	// file methods
	//

	//
	// update_file( $idupl, $idclient = '', $filename = '', $directory = '', $filetype = '', $status = '', $description = '', $used = '', $filesize = '', $filetime = '', $width = '', $height = '' )
	//
	// mixed: $directory -> string: retrieve ID from db
	//					 -> else  : ID of directory
	// mixed: $filetype  -> string: retrieve ID from db
	//					 -> else  : ID of filetype
	// mixed: $status	 -> array : status will be changed according to status and keep-bitmask
	//					 -> else  : new value of status
	// mixed: $used	 	 -> + or -: increment or decrement the field used
	//					 -> else  : set used to $used
	//
	// return
	//	true	update has been made or no update neccessary
	//  false	update failed
	//
	// errno
	//	no errno set
	//
	function update_file( $idupl, $idclient = '', $filename = '', $directory = '', $filetype = '', $status = '', $description = '', $used = '', $filesize = '', $filetime = '', $width = '', $height = '', $twidth = '', $theight = '', $title = '' ) {
		// check requirements
		if (empty($idupl)) return false; // error, fileid missing
		// create parameter array for update
		$parameter = array();
		if (!empty($idclient) || is_int($idclient)) $parameter['idclient'] = array( $idclient, 'num' );
		if (!empty($filename))    $parameter['filename']       = array( $filename, 'str' );
		
		if (!empty($description)) $parameter['description']    = array( $description, 'str' );
		
		if (!empty($filesize))    $parameter['filesize']       = array( $filesize, 'num' );
//		if ($filetime)    $parameter['lastmodified']   = array( $filetime, 'timestamp1' );
		if (!empty($filetime))    $parameter['created']        = array( $filetime, 'timestamp1' );
		
		if (!empty($title))       $parameter['titel']          = array( $title, 'str' );
		
		if (!empty($width)   || is_int($width) )  $parameter['pictwidth']  = array( $width , 'num' );
		if (!empty($height)  || is_int($height))  $parameter['pictheight'] = array( $height, 'num' );
		if (!empty($twidth)  || is_int($twidth) ) $parameter['pictthumbwidth']  = array( $twidth , 'num' );
		if (!empty($theight) || is_int($theight)) $parameter['pictthumbheight'] = array( $theight, 'num' );
		if (is_array($status) || is_int($status)) {
			$_addonstatus = ($this->set_addon_flag) ? 0x20: 0x00;
			$parameter['status'] = (is_array($status)) ? array('((status & '.($status['keep']|$_addonstatus).')|'.($status['status']|$_addonstatus).')', 'func'): array(($status|$_addonstatus), 'num');
		}
		if (!empty($used))        $parameter['used']        = ($used == '+' || $used == '-') ? array("used $used 1", 'func'): array($used, 'num');
		if (!empty($directory)) {
			$client = (!empty($idclient)) ? $idclient: $GLOBALS['client'];
			$iddirectory = (is_string($directory)) ? $this->get_directory_id($directory, $client): $directory;
		 	$parameter['iddirectory'] = array( $iddirectory, 'num' );
		}
		if (!empty($filetype)) {
			$idfiletype  = (is_string($filetype) ) ? $this->get_filetype_id($filetype): $filetype;
		 	$parameter['idfiletype'] = array( $idfiletype, 'num' );
		}
		// only if there is any field to be changed
		// do update and clear cache
		if (count($parameter) > 0) {
		 	$parameter['author'] = array( 'uid', 'std' );
		 	$parameter['idupl']  = array( $idupl, 'num', '=' );
			$this->db->update( 'upl', $parameter );
			if ($this->tmp_filedata[$idupl])  $this->tmp_filedata[$idupl] = '';
			$tmp = $this->get_file( (int) $idupl);
			if (!$this->addon_call && !$this->set_addon_flag) {
				$this->check_file_addons($tmp['filetype'], array('idfile'=>$idupl, 'location'=>$this->cfg_client['upl_path'].$tmp['dirname'].$tmp['filename']), "update" );
			}
		}
		return true;
	}

	//
	// update_file_sizes(  $idupl, $filesize = '', $filetime = '', $width = '', $height = '', $twidth = '', $theight = '' )
	//
	// mixed: $directory -> string: retrieve ID from db
	//					 -> else  : ID of directory
	// mixed: $filetype  -> string: retrieve ID from db
	//					 -> else  : ID of filetype
	// mixed: $status	 -> array : status will be changed according to status and keep-bitmask
	//					 -> else  : new value of status
	// mixed: $used	 	 -> + or -: increment or decrement the field used
	//					 -> else  : set used to $used
	//
	// return
	//	true	update has been made or no update neccessary
	//  false	update failed
	//
	// errno
	//	no errno set
	//
	function update_file_sizes( $idupl, $filesize, $filetime, $width, $height, $twidth, $theight ) {
		// check requirements
		if (empty($idupl)) return false; // error, fileid missing
		// create parameter array for update
		$parameter = array();
		$parameter['filesize']        = array( $filesize, 'num'        );
		$parameter['created']         = array( $filetime, 'timestamp1' );
		$parameter['pictwidth']       = array( $width   , 'num'        );
		$parameter['pictheight']      = array( $height  , 'num'        );
		$parameter['pictthumbwidth']  = array( $twidth  , 'num'        );
		$parameter['pictthumbheight'] = array( $theight , 'num'        );

		// do update and clear cache
	 	$parameter['author'] = array( 'uid', 'std' );
	 	$parameter['idupl']  = array( $idupl, 'num', '=' );
		$this->db->update( 'upl', $parameter );
		if ($this->tmp_filedata[$idupl])  $this->tmp_filedata[$idupl] = '';
		$tmp = $this->get_file( (int) $idupl);
		return true;
	}

	//
	// update_file2( $idupl, $idclient, $filename, $directory, $filetype, $status, $description, $title )
	//
	// mixed: $directory -> string: retrieve ID from db
	//					 -> else  : ID of directory
	// mixed: $filetype  -> string: retrieve ID from db
	//					 -> else  : ID of filetype
	// mixed: $status	 -> array : status will be changed according to status and keep-bitmask
	//					 -> else  : new value of status
	//
	// return
	//	true	update has been made or no update neccessary
	//  false	update failed
	//
	// errno
	//	no errno set
	//
	function update_file2( $idupl, $idclient, $filename, $directory, $filetype, $status, $description, $title ) {
		// check requirements
		if (empty($idupl)) return false; // error, fileid missing
		// create parameter array for update
		$parameter = array();
		$parameter['idclient']    = array( $idclient, 'num' );
		$parameter['filename']    = array( $filename, 'str' );
		$parameter['description'] = array( $description, 'str' );
		$parameter['titel']       = array( $title, 'str' );
		
		$_addonstatus = ($this->set_addon_flag) ? 0x20: 0x00;
		$parameter['status'] = (is_array($status)) ? array('((status & '.($status['keep']|$_addonstatus).')|'.($status['status']|$_addonstatus).')', 'func'): array(($status|$_addonstatus), 'num');

		$client = (!empty($idclient)) ? $idclient: $GLOBALS['client'];
		$iddirectory = (is_string($directory)) ? $this->get_directory_id($directory, $client): $directory;
	 	$parameter['iddirectory'] = array( $iddirectory, 'num' );

		$idfiletype  = (is_string($filetype) ) ? $this->get_filetype_id($filetype): $filetype;
	 	$parameter['idfiletype'] = array( $idfiletype, 'num' );

		// only if there is any field to be changed
		// do update and clear cache
		if (count($parameter) > 0) {
		 	$parameter['author'] = array( 'uid', 'std' );
		 	$parameter['idupl']  = array( $idupl, 'num', '=' );
			$this->db->update( 'upl', $parameter );
			if ($this->tmp_filedata[$idupl])  $this->tmp_filedata[$idupl] = '';
			$tmp = $this->get_file( (int) $idupl);
			if (!$this->addon_call && !$this->set_addon_flag) {
				$this->check_file_addons($tmp['filetype'], array('idfile'=>$idupl, 'location'=>$this->cfg_client['upl_path'].$tmp['dirname'].$tmp['filename']), "update" );
			}
		}
		return true;
	}
	
	/**
	 * Schreibt die Daten einer Datei in die Datenbank
	 *
	 * @param	int		$idclient		ID des Projektes
	 * @param	string	$filename		Vollständiger Dateiname, max. 255 Zeichen
	 * @param	mixed	$directory		Verzeichnis der Datei, entweder Text oder Integer
	 *                                  int	- ID des Verzeichnisses aus cms_directory
	 *									string - Vollständiger Verzeichnisname, wird vor dem 
	 *											 Speichern in eine ID aus cms_directory umge-
	 *											 wandelt
	 * @param	mixed	$filetype		Dateityp der Datei, entweder Text oder Integer
	 *                                  int	- ID des Dateityps aus cms_filetype
	 *									string - Filetyp als Text, wird vor dem Speichern in 
	 *											 eine ID aus cms_filetype umgewandelt
	 * @param	int		$status			Status der Datei
	 *									Optional - Standard: 0
	 * @param	string	$description	Beschreibung der Datei
	 *									Optional - Standard: ''
	 * @param	int		$used			Flag zur Kennzeichnung, ob die Datei in Sefrengo verlinkt ist
	 *									zur Zeit nicht verwendet
	 *									Optional - Standard: ''
	 * @param	date	$filetime		Datum und Zeit der Datei
	 *									Optional - Standard: now()
	 * @param	int		$filesize		Größe der Datei in Bytes
	 *									Optional - Standard: 0
	 * @param	int		$width			Breite bei Bildmaterialien oder Mediadateien
	 *									Optional - Standard: 0
	 * @param	int		$height			Höhe bei Bildmaterialien oder Mediadateien
	 *									Optional - Standard: 0
	 * @param	int		$twidth			Breite des Thumbnails bei Bildmaterialien oder Mediadateien
	 *									Optional - Standard: 0
	 * @param	int		$theight		Höhe des Thumbnails bei Bildmaterialien oder Mediadateien
	 *									Optional - Standard: 0
	 * @param	string	$titel			Titel der Datei, max. 200 Zeichen
	 *									Optional - Standard: ''
	 *
	 * @return 	int		ID des neuen Datensatzes, oder 0 bei Fehlern
	 *
	 * @author	Jürgen Brändle
	 * @since	ALPHA
	 * @version 0.2 / 20041010
	**/
	function insert_file($idclient, $filename, $directory, $filetype, $status = 0, $description = '', $used = '', $filetime = 'now()', $filesize = 0, $width = 0, $height = 0, $twidth = 0, $theight = 0, $titel = '') {
		// Prüfe notwendige Informationen
		if (!is_int($idclient) || empty($filename) || empty($directory) || empty($filetype)) return 0; // required fields missing
		// Aufbereiten der Daten
		$_addonstatus = ($this->set_addon_flag) ? 0x20: 0x00;
		// Ermittle Verzeichnis- und Dateityp-ID wenn notwendig
		$iddirectory = (is_string($directory)) ? $this->get_directory_id($directory, $idclient): $directory;
		$idfiletype  = (is_string($filetype) ) ? $this->get_filetype_id($filetype)             : $filetype;
		// Setze Used-Counter
		$count       = ($used) ? $used: 0;
		// Setze Erstelldatum für die DB-Operation
		$created     = ($filetime == 'now()')? array( 'now()', 'func'): array( $filetime, 'timestamp1');
		// Erzeuge Einträge für Verzeichnisse und Dateitypen, wenn diese nicht existieren
		if (empty($iddirectory) && (is_string($directory))) $iddirectory = $this->create_directory($directory, $idclient);
		if (empty($idfiletype)  && (is_string($filetype)))  $idfiletype  = $this->insert_filetype($filetype);
		// Prüfe ob Verzeichnis und Dateityp existieren
		if (empty($iddirectory) || empty($idfiletype)) return 0; // missing directory or filetype id
		// INSERT vorbereiten und durchführen
		$parameter = array(
						'idclient'    		=> array( $idclient   , 'num' ),
						'filename'    		=> array( $filename   , 'str' ),
						'iddirectory' 		=> array( $iddirectory, 'num' ),
						'idfiletype'  		=> array( $idfiletype , 'num' ),
						'description' 		=> array( $description, 'str' ),
						'titel' 		    => array( $titel      , 'str' ),
						'used'        		=> array( $count      , 'num' ),
						'filesize'    		=> array( $filesize   , 'num' ),
						'pictwidth'   		=> array( $width      , 'num' ),
						'pictheight'  		=> array( $height     , 'num' ),
						'pictthumbwidth'	=> array( $twidth , 'num' ),
						'pictthumbheight'	=> array( $theight, 'num' ),
						'status'      		=> array( ($status|$_addonstatus), 'num' ),
						'author'      		=> array( 'uid'       , 'std' ),
						'created'     		=> array( $created[0] , $created[1])
					  );
		$id = $this->db->insert( 'upl', 'idupl', $parameter );
		$tmp = $this->get_file((int) $id, (int) $idclient);
		// Prüfe ob ADD-On-Funktionen aufzurufen sind und ob der Funktionsaufruf notwendig ist
		if (!$this->addon_call && !$this->set_addon_flag) {
			$this->check_file_addons($tmp['filetype'], array('idfile'=>(int) $id, 'location'=>$this->cfg_client['upl_path'].$tmp['dirname'].$tmp['filename']), "new" );
		}
		// Rückgabe ist die ID des neuen Eintrags
		return $id;
	}

	//
	// Löscht eine Datei
	//
	// Prüft ob die Datei benutzt wird
	// Löscht die Datei zunächst im Filesystem
	// Ruft bei ADDON-Filetypes die deleteAddOn-Funktion auf
	// Löscht den Datenbankeintrag
	//
	function delete_file($idupl, $idclient = '', $addon = false, $cms_path = 'upl_path' ) {
	    $client = (!empty($idclient) || (is_int($idclient) && $idclient == 0)) ? $idclient: $GLOBALS['client'];
		if (empty($idupl)) return '1405'; // missing required field
		if ($this->is_file_in_use($idupl, $client)) return '1410'; // file is in use, no delete
		if (!$this->delete_file_fs($idupl, $cms_path))	return '1409'; // file could not be deleted
		
		$tmp = $this->tmp_filedata[$idupl];
		// filetype has a special function to be called ... get it from filetype table
		if (!$this->addon_call && !$this->set_addon_flag) {
			$this->check_file_addons($tmp['filetype'], array('idfile'=>$idupl, 'location'=>$this->cfg_client['upl_path'].$tmp['dirname'].$tmp['filename']), "delete" );
		}
		$this->db->delete_by_id_and_client( 'upl', 'idupl', $idupl, $client );

		return (!$this->errno);
	}

	function upload_file_2($iddirectory, $idclient, $fieldname = 'userfile') {
		// test path 
		$path = $this->get_directory_name((int)$iddirectory);
		if (empty($path)) return '0706'; // path not found
		// do file-upload
		$success = $this->upload_file($path, $iddirectory, $idclient, $fieldname);
		return $success;
	}

	function upload_file($path, $iddirectory, $idclient, $fieldname = 'userfile') {
		global $uploadfile;

		$this->edit_files = array();

		if (empty($_FILES[$fieldname]['tmp_name'][0])) return '1421';
		if (empty($_FILES[$fieldname]['name'][0]) || empty($_FILES[$fieldname]['size'][0])) return '0703'; // not uploaded file

		$uploadfile_name = $_FILES[$fieldname]['name'][0];
		$uploadfile      = $_FILES[$fieldname]['tmp_name'][0];
		$uploadfile_size = $_FILES[$fieldname]['size'][0];

		// check filename
		$validate = get_validator('upload');
		if (!$validate->filename($uploadfile_name)) return '1420'; // check for bad chars in upload-filename

		$validate->normalize_name($uploadfile_name, false, $this->cfg_cms['trouble_chars']);
		$uploadfile_type = strtolower(substr(strrchr ($uploadfile_name, "."),1));

		// check if forbidden filetype
		 
		if (!empty($uploadfile_type) && strstr($this->cfg_client['upl_forbidden'], $uploadfile_type) != false) return '0705'; // forbidden filetype
		// jb_todo: check file exists, ask overwrite
		$location = $this->upload_dir . $path . $uploadfile_name;
		if (!@move_uploaded_file($uploadfile, $location)) return '1424';
		
		if ($this->chmod_enabled) chmod($location,$this->chmod_value); 
		$filetype = $this->get_filetype_id($uploadfile_type, true);
		$filetime = filemtime($location);
		$tmp = $this->get_file($uploadfile_name, $idclient, $iddirectory);
		if (empty($tmp)) {
			$tmp['idupl'] = $this->insert_file((int)$idclient, $uploadfile_name, (int) $iddirectory, (int) $filetype, (int) 0, '', '', $filetime, (int) $uploadfile_size );
			if ($tmp['idupl'] == 0) return '1423';
		} else {
			 $this->update_file((int) $tmp['idupl'], $idclient, $uploadfile_name, (int) $iddirectory, (int) $filetype, (int) $tmp['status'], $tmp['description'], '', (int) $uploadfile_size, '', '', '', '', '',  $tmp['titel'] );
		}
		$this->edit_files[] = $tmp['idupl'];
		return '';
	}

	function upload_archive($path, $fieldname = 'userfile') {
		global $uploadfile;
		
		$this->error_files = array();
		$this->edit_files = array();
		$this->errno = '';

		if (empty($_FILES[$fieldname]['tmp_name'][0])) return '1421';
		if (empty($_FILES[$fieldname]['name'][0]) || empty($_FILES[$fieldname]['size'][0])) return '0703'; // not uploaded file

		$uploadfile      = $_FILES[$fieldname]['tmp_name'][0];
		$uploadfile_name = $_FILES[$fieldname]['name'][0];
		$uploadfile_size = $_FILES[$fieldname]['size'][0];
		// get filetype to decide which bulk-type
		$uploadfile_type = strtolower(substr(strrchr ($uploadfile_name, "."),1));
		switch ($uploadfile_type) {
			case 'zip':
				$this->_doZIP($uploadfile, $path);
				break;
			case 'tar':
				$this->_doTAR($uploadfile, $path);
				break;
			default: 
				return '0705'; // forbidden filetype for bulk-upload
		}
		@unlink($uploadfile_name);
		return $this->errno;
	}

	function _doZIP($upfile, $path) {
		// get validator object
		$validate = get_validator('upload');
		// check file exists, ask overwrite ... todo
		$zip = @zip_open($upfile);
		if ($zip) {
			while ($zip_entry = zip_read($zip)) {
				if (zip_entry_open($zip, $zip_entry, 'rb')) {
					$zip_name    = zip_entry_name($zip_entry);
					// check filename of zipped file
					// filenames will be normalized to avoid trouble chars
					if ($validate->filename($zip_name)) {
						$validate->normalize_name($zip_name, false, $this->cfg_cms['trouble_chars']);
						$zip_content = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
						$new_location = $this->upload_dir . $path . $zip_name;
						$newfile     = @fopen($new_location, "wb+");
						if ($newfile) {
							if (!fwrite($newfile, $zip_content)) {
								$this->errno = '0705';
								return;
							}
							fclose($newfile);
							if ($this->chmod_enabled) chmod($new_location, $this->chmod_value); 
						}
					} else {
						$this->error_files[] = $zip_name;
					}
					zip_entry_close($zip_entry);
				}
			}
			zip_close($zip);
		}
	}
	
	function _doTAR($upfile, $path) {
		// get validator object
		$validate = get_validator('upload');
		// check file exists, ask overwrite ... todo
		// get required pear-class
		require_once("Archive/Tar.php");
		// 
		$tar = new Archive_Tar($upfile);
		$dir = $this->upload_dir . $path;
		$dir = substr($dir, 0, strlen($dir)-1);
		$tarfiles = array();
		if (($v_list  =  $tar->listContent()) != 0) {
			$max = count($v_list);
			// get a list of files to extract regadring the restrictions on filenames and directories
			// since we can not rename file while extracting, all filenames may not include special chars
			// and the trouble_chars that are set in the configuration
			// aborts the extraction if a invalid directory name is in the tar-file
			for ($i = 0; $i < $max; $i++) {
				$name = $v_list[$i]['filename'];
				if (empty($v_list[$i]['typeflag'])) {
					// compressed file
					if ($validate->filename($tar_name, $this->cfg_cms['trouble_chars'])) $tarfiles[] = $name;
					else $this->error_files[] = $name;
				} else {
					// directory
					if (!$validate->filepath($tar_name, true, $this->cfg_cms['trouble_chars'])) {
						$this->errno = '1422';
						return;
					}
					$tarfiles[] = $name;
				}
			}
			$tar->extractList($tarfiles, $dir);
		}
	}

	//
	// check_file_addons($type, $param = '', $func = 'new')
	//
	// creates a addon-object for a specific filetype and calls the specified function with the parameter-array $param
	// blocks additional addon_calls
	//
	function check_file_addons($type, $param = '', $func = 'new') {
		if (!is_null($this->addon_factory)) {
			$addon = $this->addon_factory->get($type);
			if (!is_null($addon) && !$this->addon_call) {
				$this->addon_call = true;
				$functioncall = $func.'_file';
				$addon->$functioncall($param);
				$this->addon_call = false;
			}
		}
	}

	function clear_file_status($idclient, $mask, $idfile = '') {
  		global $cms_db;
		// delete all scan-marks
		$sql  = 'UPDATE ';
		$sql .=   $cms_db['upl'] . ' ';
		$sql .= 'SET ';
		$sql .= ' status       = (status & ' . $mask . '), ';
		$sql .= ' lastmodified = lastmodified ';
		$sql .= 'WHERE ';
		$sql .= ' idclient = ' . $idclient;
		if (!empty($idfile)) $sql .= ' AND idupl = ' . $idfile;
		$this->db->sql = $sql;
		$this->db->needReturn = false;
		$this->db->exec_query();
  	}
  
	function get_files_by_status($status, $mask, &$files) {
		global $cms_db, $client;
 
		$sql  = 'SELECT ';
		$sql .= ' idupl ';
		$sql .= 'FROM ';
		$sql .=   $cms_db['upl'] . ' ';
		$sql .= 'WHERE ';
		$sql .= '     (status & ' . $mask . ') = ' . $status;
		$sql .= ' AND idclient        = ' . $client;
		$this->db->sql = $sql;
		$this->db->needReturn = true;
		$db = $this->db->exec_query();
		while($db->next_record()) {
			$files[] = $db->f("idupl");
		}
		return $fm->errno;
	}

	function scan_addon( $idfile ) {
		global $client;
		
		$addonfile = $this->get_file($idfile);
		$dirname   = $addonfile['dirname'];
		$filename  = $addonfile['filename'];
		$filetype  = $addonfile['filetype'];
		$this->check_file_addons($filetype, array('idfile'=>$idfile, 'location'=>$this->cfg_client['upl_path'].$dirname.$filename), "update" );
		$this->clear_file_status($client, 0xCF, $idfile);
	}
	
	//
	// scan_files($iddirectory)
	//
	//        files
	//			übernehme status von filetype OR directory
	//
	function scan_files( $iddirectory, $updatethumbs = 0 ) {
		global $deb, $client, $cfg_client, $cms_db;

		// get validator object
		$validate = get_validator('upload');
		// scan files in directory
		$found_files_id    = array();
		$this->edit_files  = array();
		$currentdir        = [];
		$scandir           = '';
		
		// get scan dir
		$this->_get_scan_dir($iddirectory, $currentdir, $scandir);
		if (!is_dir($scandir)) {
			// error ... given name is no directory
			$this->db->delete_by_id_and_client( 'directory', 'iddirectory', $iddirectory, $client );
		} else {
			// do scan directory for files
			// set the all files in current directory to be scanned
			$current_iddirectory = $currentdir['iddirectory'];
			$sql  = 'UPDATE ';
			$sql .=   $cms_db['upl'] . ' ';
			$sql .= 'SET ';
			$sql .= ' status       = (status | 0x10), ';
			$sql .= ' lastmodified = lastmodified ';
			$sql .= 'WHERE ';
			$sql .= '     iddirectory = ' . $current_iddirectory;
			$sql .= ' AND idclient    = ' . $client; 
			$sql .= ' AND (status & 0x04) = 0';
			$this->db->sql = $sql;
			$this->db->needReturn = false;
			$this->db->exec_query();
			// get dirs and files
			$handle = opendir ($scandir);
			while (false !== ($file = readdir($handle))) {
				if (strpos($file, $this->thumb_ext) === false && $file != '.' && $file != '..' ) { // exclude thumbs and special dirs
					// handle files
					$onefile = $scandir.$file;
					if (is_file($onefile)) {
						$this->found_files++;
						$filetype      = strtolower(substr(strrchr( $file, '.'), 1));
						// check if forbidden filetype
						$filetype_okay = (empty($filetype)) ? true: !strstr($this->cfg_client['upl_forbidden'], $filetype);
						if ($validate->filename($file) && $filetype_okay) { // not a forbidden filetype
							// prüfe ob file existiert:
							// ja: aktualisiere datensatz
							// nein: ermittle filetype und lege die datei an
							$filesize    = filesize ($onefile);
							$filetime    = filemtime($onefile);
							$newfile     = $this->get_file($file, $client, $current_iddirectory);
							if (empty($newfile)) {
	  							$filetype_id = $this->get_filetype_id($filetype, true);
	   							$status      = ($currentdir['status'] | $this->tmp_filetypedata[$filetype]['status']);
								$idfile      = $this->insert_file((int)$client, $file, (int) $current_iddirectory, (int) $filetype_id, (int) ($status & 0xEF), '', '', $filetime, $filesize);
								$this->edit_files[] = $idfile;	// add new file to editlist
							} else {
								if ($filesize != $newfile['filesize'] || $filetime != $newfile['created'] || !empty($updatethumbs)) {
									if (!empty($updatethumbs)) {
										switch ($newfile['idfiletype']) {
											case 3:
											case 4:
											case 12:
											case 13:
												$filesize = -2;
												break;
											default:
												break;
										}
									}
									$this->update_file($newfile['idupl'], '', $file, '', '', (0xEF & $newfile['status']), '', '', $filesize, $filetime);
								}
			                	$found_files_id[] = $newfile['idupl'];
							}
						} else {
							// jb_todo: forbidden file type ?? collect ??
							// jb_todo: filename not valid  ?? collect ??
							$this->error_files[] = $onefile;
						}
					}
				}
  			}
	  		// delete all scanned marks of found files
  			closedir($handle);
			$this->db->needReturn = false;
			if (count($found_files_id) > 0) {
				$sql  = 'UPDATE ';
				$sql .=  $cms_db['upl'] . ' ';
				$sql .= 'SET ';
				$sql .= ' status       = (status & 0xEF), ';
				$sql .= ' lastmodified = lastmodified ';
				$sql .= 'WHERE ';
				$sql .= '     idupl in (' . implode(',', $found_files_id) . ') ';
				$sql .= ' AND idclient = ' . $client;
				$this->db->sql = $sql;
				$this->db->exec_query();
			}
			// delete scannfile mark of current dir
			$sql  = 'UPDATE ';
			$sql .=  $cms_db['directory'] . ' ';
			$sql .= 'SET ';
			$sql .= ' status       = (status & 0xDF), ';
			$sql .= ' lastmodified = lastmodified ';
			$sql .= 'WHERE ';
			$sql .= '     iddirectory = ' . $current_iddirectory;
			$sql .= ' AND idclient    = ' . $client;
			$this->db->sql = $sql;
			$this->db->exec_query();
    	}
		return count($found_files_id);
	}

	function delete_files_not_found($idclient) {
		global $cms_db, $cfg_client;
		
		if ($cfg_client['remove_files_404'] || $cfg_client['remove_files_404'] == '1') {
			// check for files not updated to remove them if config-value is set to do so
			$sql  = 'DELETE FROM ';
			$sql .=  $cms_db['upl'] . ' ';
			$sql .= 'WHERE ';
			$sql .= '     (status & 0x14) = 0x10 ';
			$sql .= ' AND idclient    = ' . $idclient;
		} else {
			// otherwise delete all scan marks even if remove_file_404 contains false
			$sql  = 'UPDATE ';
			$sql .=  $cms_db['upl'] . ' ';
			$sql .= 'SET ';
			$sql .= ' status       = (status & 0xEF), ';
			$sql .= ' lastmodified = lastmodified ';
			$sql .= 'WHERE ';
			$sql .= ' idclient = ' . $idclient;
		}
		$this->db->sql = $sql;
		$this->db->exec_query();
	}

	//
	// filesystem methods
	//

	//
	// rename_file_fs
	//
	// renames the file $idupl in the filesystem to $newfilename
	//
	// return
	//	return true if successful, else errno is set an false will be returned
	//
	// error
	//	set $this->errno if error occurred
	//
	function rename_file_fs( $idupl, $newfilename ) {
		if (!$idupl || !$newfilename) $this->errno = '1405'; // required file information for rename is missing
		else {
			$this->errno = '';
			// retrieve file data from db, if not done before
			$tmp = (!$this->tmp_filedata[$idupl]) ? $this->get_file($idupl): $this->tmp_filedata[$idupl];
			// check if filenames are different
			if ($tmp['filename'] != $newfilename) {
				// check if new filename is already in use ... no rename possible
				if ($this->is_filename_in_use_fs( $tmp['dirname'], $newfilename )) $this->errno = '1401'; // new filename is already in use, don't rename
				else {
					$path = $this->cfg_client['upl_path'].$tmp['dirname'];
					// rename file on disk and set errno if error occurred
					if (@!rename($path.$tmp['filename'], $path.$newfilename)) $this->errno = '1412'; // rename failed
				}
			}
		}
		return (!$this->errno);
	}

	//
	// copy_file_fs
	//
	// copy a file $filename to the directory $directory
	//
	// mixed: $directory -> string: retrieve ID from db
	//					 -> else  : ID of directory
	//
	// return
	//	return true if successful, else errno is set an false will be returned
	//
	// error
	//	set $this->errno if error occurred
	//
	function copy_file_fs( $directory, $filename, $idfile ) {
		if (!$directory || !$filename || !$idfile) $this->errno = '1405'; // required fields missing
		else {
			$tmp = $this->get_file($idfile);
			$dir = ((is_string($directory)) ? $directory: $this->get_directory_name((int)$directory));
			if (!$dir) $dir = $this->create_directory($directory, $GLOBALS['client']);
			$this->copy_file_fs2($this->cfg_client['upl_path'].$dir.$filename, $this->cfg_client['upl_path'].$tmp['dirname'].$tmp['filename']);
		}
		return (!$this->errno);
	}

	//
	// _copy_file_fs
	//
	// copy a file $filename to the directory $directory
	//
	// return
	//	return true if successful, else errno is set an false will be returned
	//
	// error
	//	set $this->errno if error occurred
	//
	function copy_file_fs2($target, $source) {
		if (!$target || !$source) $this->errno = '1405'; // required fields missing
		else {
			if (@!copy($source, $target)) $this->errno = '1403'; // file copy failed
		}
		return (!$this->errno);
	}

	//
	// move_file_fs( $directory, $filename, $idfile )
	//
	// move a file $idfile with the $filename to the directory $directory
	//
	// mixed: $directory -> string: retrieve ID from db
	//					 -> else  : ID of directory
	//
	// return
	//	return true if successful, else errno is set an false will be returned
	//
	// error
	//	set $this->errno if error occurred
	//
	function move_file_fs( $directory, $filename, $idfile ) {

		if (empty($directory) || empty($filename) || empty($idfile)) $this->errno = '1405'; // required fields missing
		else {
			if (!$this->copy_file_fs( $directory, $filename, $idfile)) $this->errno = '1404'; // move failed, because file could not be copied to new destination
			else {
				// delete the original file
				$source = $this->cfg_client['upl_path'] . $this->tmp_filedata[$idfile]['dirname']  . $this->tmp_filedata[$idfile]['filename'];
				if (@!unlink($source)) {
					$this->errno = is_writeable($source) ? '1404': '1419'; // move file failed, original file could not be deleted
					// clean up, delete file copy
					$destination = $this->cfg_client['upl_path'] . ((is_string($directory)) ? $directory: $this->get_directory_name((int)$directory)) . $filename;
					@unlink($destination);
				} else {
					$this->tmp_filedata[$idfile] = '';
				}
			}
		}
		return (!$this->errno);
	}

	//
	// move_file_fs2( $target, $sourcel )
	//
	// move a file $sourcel to $target
	//
	// return
	//	return true if successful, else errno is set an false will be returned
	//
	// error
	//	set $this->errno if error occurred
	//
	function move_file_fs2($target, $source) {
		if (!$target || !$source) $this->errno = '1405'; // required fields missing
		else {
			if (!$this->copy_file_fs2($target, $source)) $this->errno = '1404'; // move failed, because file could not be copied to new destination
			else { // delete the original file
				if (@!unlink($source)) {
				 	// move file failed, original file could not be deleted, do clean up: delete file copy
					$this->errno = is_writeable($source) ? '1404': '1419';
					@unlink($target);
				}
			}
		}
		return (!$this->errno);
	}

	//
	// delete_file_fs
	//
	// deletes the file $name_or_id in the filesystem
	//
	// return
	//	return true if successful, else errno is set an false will be returned
	//
	// error
	//	set $this->errno if error occurred
	//
	function delete_file_fs( $name_or_id, $cms_path = 'upl_path' ) {
		$filename = $name_or_id;
		
		// if $param is integer get filename from db
		if (is_int($name_or_id)) {
			$this->errno = '';
			$filename    = $this->get_complete_filename((int)$name_or_id);
			$filename    = $this->cfg_client[$cms_path].$filename;
			if (!empty($this->errno)) return false;
		}
		// if $filename is string assume this is a complete filename including path -> try to delete file
		if (is_string($filename)) {
			 // if file is not existing - give error 1411 back
			 // error can be overwritten by cms_value:
			 //	fm_delete_ignore_404 - true/1:  ignore this failure
			 // 					 - false/0: set errno and stop any further actions
			if (!file_exists($filename)) {
				if (!$this->cfg_client["fm_delete_ignore_404"] || $this->cfg_client["fm_delete_ignore_404"] == '0' ) $this->errno = '1409';
			} else {
				if (!unlink($filename)) $this->errno = '1409'; // file delete failed
			}
		} else {
			$this->errno = '1415'; // wrong parameter type
		}
		return (empty($this->errno));
	}

	//
	// create_directory_fs($dirname, $dirstart)
	//
	// creates a new directory $dirname in the directory $dirstart
	//
	// return
	//	return true if successful, else false
	//
	function create_directory_fs($dirname, $dirstart) {
		umask(0000);
		$success = @mkdir($this->cfg_client['upl_path'].$dirstart.$dirname, $this->chmod_value);
		return $success;
	}

	//
	// delete_dir_fs($iddirectory)
	//
	// deletes the directory $iddirectory
	//
	// return
	//	return true if successful, else false
	//
	function delete_dir_fs($iddirectory) {
		$dirname = $this->get_directory_name((int)$iddirectory);
		return (@rmdir($this->cfg_client['upl_path'].$dirname));
	}

	//
	// rename_directory_fs
	//
	// renames the directory $iddirectroy in the filesystem to $dirname
	//
	// return
	//	return true if successful, else errno is set an false will be returned
	//
	// error
	//	set $this->errno if error occurred
	//
	function rename_directory_fs($dirname, $iddirectory ) {
		if (!$dirname || !$iddirectory) $this->errno = '1405'; // required directory information for rename is missing
		else {
			$this->errno = '';
			// retrieve file data from db, if not done before - 
			$tmp = $this->get_directory((int)$iddirectory);
			if ($tmp['name'] != $dirname) {
				// clear cached directory data ??
//				$this->tmp_directorydata[$tmp['name'].'_'.$client] = '';
				// calculate path
				$dir = explode('/', $tmp['dirname']);
				array_pop($dir);
				array_pop($dir);
				$path = $this->cfg_client['upl_path'] . implode('/', $dir) . '/';
				// check if new dirname is already in use for a file or directory ... no rename possible
				if (file_exists($path.$dirname)) $this->errno = '1413'; // new dirname is already in use, don't rename
				else {
					// rename file on disk and set errno if error occurred
					if (@!rename($path.$tmp['name'], $path.$dirname)) $this->errno = '1414'; // rename failed
				}
			}
		}
		return (!$this->errno);
	}

	//
	// write_file_fs
	//
	// writes the given content into a file
	//
	// return
	//	return true if successful, else errno is set an false will be returned
	//
	// error
	//	set $this->errno if error occurred
	//
  function write_file_fs($location, $file, $content, $clientpath = 'upl_path', $overwrite = true) {
		if (empty($location) || empty($file)) $this->errno = '1405'; // required directory information missing
    else {
      $dir = (is_string($location)) ? $location: $this->get_directory_name((int)$location);
      $filename = $this->cfg_client[$clientpath].$dir.$file;
      // delete an existing file if neccessary
  		if (file_exists($filename)) {
        if ($overwrite) @unlink($filename);
        else {
          $this->errno = '1416'; // file overwrite failed
          return;
        }
      }
      // create file and write content to file
      $fp = @fopen($filename, 'ab');
      if (!$fp) {
        $this->errno = '1417'; // file open failed
      } else {
        fwrite( $fp, $content );
        fclose( $fp );
      }
    }
    return (empty($this->errno));
  }

  //
  // some utility functions
  //
	//
	// validate_filename
	//
	// checks if a filename contains invalid chars or if it is longer than 200 chars
	//
	// return
	//	return true if filename is okay, else false will be returned
	//
  function validate_filename($name) {
  	$isOkay = (preg_match('/[\/\?\*\"\<\>\|\n\t\r\\\\ßÄÜÖäöü€@]/', $name) == 0);
  	$isOkay = $isOkay && (strlen($name) < 200);
  	return $isOkay;
  } 

} // end class filemanager
?>