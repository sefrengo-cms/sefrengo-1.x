<?PHP
// File: $Id: class.fileaccess.php 28 2008-05-11 19:18:49Z mistral $
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
Description : Klasse für den Zugriff auf die Dateiverwaltung in Sefrengo
              Unterstützt nur lesenden Zugriff, schreibende Zugriffe sind in 
			  der abgeleitenden Klasse class.filemanager.php realisiert, die 
			  class.fileaccess.php erweitert
			  
Copyright   : Jürgen Brändle, (c) 2003
Author      : Jürgen Brändle, braendle@web.de
Urls        : www.sefrengo.de
Create date : 2003-07-20
Last update : 2003-07-20

-- filetype methods
get_filetype_id($filetype, $create = false)
get_filetype($filetype, $type = 1)
get_filetype_by_id($idfiletype, $type = 1)
check_filetype($idfiletype, $newfilename)

-- directory methods
get_directory_id($dirname, $idclient, $create = false)
get_directory_name($iddirectory)
get_directory($directory, $idclient = '', $type = 1 )
get_directory_dropdown( $idclient )
get_directory_tree($idclient, $idexpand)
get_parent_directories($iddirectory, $idclient)

-- file methods
get_file($file, $idclient = '', $iddirectory = '', $type = 1)
is_file_in_use($idupl, $idclient = '')
set_file_used($idupl, $idclient, $usage = '+')
upload_file($path, $iddirectory, $idclient, $fieldname = 'userfile')
get_files_in_directory($iddirectory, $idclient, $order = 'A.filename', $ordersort = 'ASC' )
get_file_list_by_filetype($idclient, $filetype, $orderfield = '', $sorting = 'ASC', $iddirectory = '' )
get_complete_filename($idfile)
download_file()

-- filesystem methods
is_filename_in_use_fs

******************************************************************************/
class fileaccess {
	//
	// properties
	//
	// objects
	// $db				database object
	var $db           = '';
	// $cfg_client		client configuration object
	var $cfg_client   = '';
	// $cfg_cms		cms configuration object
	var $cfg_cms     = '';
	// $ perm			permission object
	var $perm         = '';
	// $lang			current language id
	var $lang         = '';
	// $errno			error number of last error
	var $errno        = 0;
	// $use_cache		flag for cache usage, true (default): use cache
	var $use_cache    = true;
	// $file_iconpath	Path for the filetype icons, used in detailed view
	var $fileicon_path = '';
	// $aspect_ratio	Type of aspect_ratio of preview pictures
	var $aspect_ratio = 1;
	//
	// arrays to cache retrieved data for further use
	// only used when $use_cache is true
	//
	var $tmp_directory_ids   = array();
	var $tmp_filetype_ids    = array();
	var $tmp_directorydata   = array();
	var $tmp_filetypedata    = array();
	var $tmp_filedata        = array();
	var $tmp_parentdirs      = array();
	var $tmp_directory_names = array();

	//
	// constructor
	//
	function __construct() {
		$this->db            = $GLOBALS['db_query'];
		$this->cfg_client    = $GLOBALS['cfg_client'];
		$this->cfg_cms      = $GLOBALS['cfg_cms'];
		$this->perm          = $GLOBALS['perm'];
		$this->lang          = $GLOBALS['lang'];
		$this->fileicon_path = $this->cfg_cms['cms_html_path'].'tpl/' . $this->cfg_cms['skin'] . '/img/file_icons/';
		$this->aspect_ratio  = $this->cfg_client['thumb_aspectratio'];
	}


	//
	// db methods
	//

	//
	// methods to handle the filetype
	// remark: no permission checking at all for filetypes
	//

	// retrieves a filetype id
	// if no record for the filetype is found an empty string will be returned
	function get_filetype_id($filetype) {
		// check if wanted id is in cache ...
		if ($this->use_cache && $this->tmp_filetype_ids[$filetype]) return $this->tmp_filetype_ids[$filetype];
		// else retrieve id from db, save in cache and return id
		$tmp = $this->get_filetype($filetype);
		if ($this->use_cache && $tmp) $this->tmp_filetype_ids[$filetype] = $tmp['idfiletype'];
		return (($tmp) ? $tmp['idfiletype']: '');
	}

	// retrieves the record of a filetype
	function get_filetype($filetype, $type = 1) {
		if (!$filetype) return ''; // missing, filetype name
		if ($this->use_cache && $this->tmp_filetypedata[$filetype]) return $this->tmp_filetypedata[$filetype];
		// get data for a single filetype
		$table = array( 'filetype'=>array('A', 'LEFT JOIN', 'A.author', '='), 
						'users'=>array('B', '', 'B.user_id', '')
					  );
		$parameter = array( 'A.idfiletype, A.description, A.filetypepict, A.status, A.filetypegroup, A.author' =>array( 4=>'_key' ),
							'A.created'     =>array( 'A.created'     , 'timestamp',      4=>'created'      ),
							'A.lastmodified'=>array( 'A.lastmodified', 'timestamp',      4=>'lastmodified' ),
						  	'B.surname'  	=>array(                                     4=>'nachname'     ),
						  	'B.name'     	=>array(                                     4=>'vorname'      ),
							'A.filetype' 	=>array( $filetype       , 'str'      , '=', 4=> '_key'        )
						  );
		$tmp = $this->db->select_record($table, $parameter, $type);
		// save data in cache
		if ($this->use_cache && $tmp) $this->tmp_filetypedata[$filetype] = $tmp;
		return (($tmp) ? $tmp: '');
	}

	// retrieves the record of a filetype-id
	function get_filetype_by_id($idfiletype, $type = 1) {
		if (!$idfiletype) return ''; // missing, filetype name
		if ($this->use_cache && $this->tmp_filetypedata['id'.$idfiletype]) return $this->tmp_filetypedata['id'.$idfiletype];
		// get data for a single filetype
		$table = array( 'filetype'=>array('A', 'LEFT JOIN', 'A.author' , '='),
						'users'	  =>array('B', ''         , 'B.user_id', '' )
					  );
		$parameter = array( 'A.filetype, A.description, A.filetypepict, A.status, A.filetypegroup, A.author' =>array( 4=>'_key' ),
							'A.created'		=>array( 'A.created'      , 'timestamp',      4=>'created'      ),
							'A.lastmodified'=>array( 'A.lastmodified' , 'timestamp',      4=>'lastmodified' ),
						  	'B.surname'		=>array(                                      4=>'nachname'     ),
						  	'B.name'		=>array(                                      4=>'vorname'      ),
							'A.idfiletype'	=>array( $idfiletype      , 'num'      , '=', 4 => '_key'       )
						  );
		$tmp = $this->db->select_record($table, $parameter, $type);
		// save data in cache
		if ($this->use_cache && $tmp) $this->tmp_filetypedata['id'.$idfiletype] = $tmp;
		return (($tmp) ? $tmp: '');
	}

	//
	// check_filetype($idfiletype, $newfilename)
	//
	// compares a given filetypename to the filetypeame for a given filetype id
	function check_filetype($idfiletype, $newfilename) {
		$tmp = $this->get_filetype_by_id($idfiletype);
		return ('.' . strtolower($tmp['filetype']) == strrchr( strtolower($newfilename), '.'));
	}

	//
	// directory methods
	// methods to handle directories
	// checks permission on the methods:
	// get_directory, get_directory_dropdown, ge_directory_tree
	//

	// retrieves the id for a directory name
	function get_directory_id($dirname, $idclient) {
		// retrieves a directory id
		// check if wanted id is in cache ...
		$cache_name = $dirname.'_'.$idclient;
		if ($this->use_cache && $this->tmp_directory_ids[$cache_name]) return $this->tmp_directory_ids[$cache_name];
		// else retrieve id from db, save in cache and return id
		$tmp = $this->get_directory($dirname, $idclient);
		if ($this->use_cache && $tmp) $this->tmp_directory_ids[$cache_name] = $tmp['iddirectory'];
		return (($tmp) ? $tmp['iddirectory']: '');
	}

	function get_directory_name($iddirectory) {
		// retrieves a complete directory name
		// check if wanted id is in cache ...
		if ($this->use_cache && $this->tmp_directory_names[(int)$iddirectory]) return $this->tmp_directory_names[(int)$iddirectory];
		// else retrieve id from db, save in cache and return id
		$tmp = $this->get_directory((int)$iddirectory);
		if ($this->use_cache && $tmp) $this->tmp_directory_names[(int)$iddirectory] = $tmp['dirname'];
		return ((!empty($tmp)) ? $tmp['dirname']: '');
	}

	//
	// get_directory($directory, $idclient = '', $type = 1 )
	//
	// mixed   : $directory -> string: retrieve ID of directory from db
	//						-> else  : ID of directory
	// optional: $idclient  -> set : retrieve ID of directory of the client from db
	//						-> else: only $directory is used as ID
	//
	function get_directory($directory, $idclient = '', $type = 1 ) {
		if (empty($directory)) return ''; // missing directory information
		if ($this->use_cache && $this->tmp_directorydata[$directory]) return $this->tmp_directorydata[$directory];
		// set variables
   		$tmp_dir = array( 'dirname', 'iddirectory', 'str', 'num');
   		$dirval  = (is_string($directory)) ? 0: 1;
        if ($dirval == 0 && empty($idclient)) return ''; // directory MAYBE unique
		// get data for a single file
		$table     = array( 'directory'=>array('A', 'LEFT JOIN', 'A.author' , '='),
							'users'	   =>array('B', ''         , 'B.user_id', '' )
						  );
		$parameter = array( 'A.iddirectory, A.name, A.description, A.dirname, A.parentid, A.status, A.author' => array( 4=>'_key' ),
							'A.idclient'    =>array(                                 4=>'_key'        ),
							'A.created'		=>array( 'A.created'      , 'timestamp', 4=>'created'     ),
							'A.lastmodified'=>array( 'A.lastmodified' , 'timestamp', 4=>'lastmodified'),
							'B.surname'		=>array(                                 4=>'nachname'    ),
							'B.name'		=>array(                                 4=>'vorname'     )
						  );
		// insert additional statements
		if (!empty($idclient)) {
			$parameter['A.idclient']           = array( $idclient , 'num'              , '=', 'AND' );
		}
		$parameter['A.'.$tmp_dir[$dirval]] = array( $directory, $tmp_dir[$dirval+2], '='        );
		// save data in cache
		$tmp = $this->db->select_record($table, $parameter, $type);
		if ($this->use_cache && $tmp) $this->tmp_directorydata[(int)$tmp['iddirectory']] = $tmp;
		return ((!empty($tmp)) ? $tmp: '');
	}

	//
	// get_directory_dropdown($idclient)
	//
	function get_directory_dropdown( $idclient, $test_rights = false ) {
		$dir_list  = '';
		$table     = array( 'directory'=>array('A') );
		$parameter = array( 'DISTINCT A.iddirectory, A.dirname' => array( 4=>'_key'),
							'A.idclient'	=> array( $idclient, 'num', '=', 'AND' ),
							'A.status'		=> array( 4        , 'num', '<'        ),
							'A.dirname'		=> array( 5=>'ASC'                     )
						  );
		// save data in cache
		$db = $this->db->select($table, $parameter);
		if ($test_rights) {
			global $perm;
			while($db->next_record()) {
				if ($perm->have_perm(1, "folder", $db->f('iddirectory')) && $perm->have_perm(17, "folder", $db->f('iddirectory'))) {
					$dir_list .= '<option value="'.$db->f('iddirectory').'">'.$db->f('dirname')."</option>\n";
				}
			}
		} else {
			while($db->next_record()) {
				$dir_list .= '<option value="'.$db->f('iddirectory').'">'.$db->f('dirname')."</option>\n";
			}
		}
		return $dir_list;
	}

	//
	// get_directory_tree($idclient, $idexpand)
	//
	function get_directory_tree($idclient, $idexpand) {
		$fm_tree = array();
		if ($idclient) {
			// get data from database
			$table = array( 'directory'=>array('A') );
			$parameter = array( 'DISTINCT A.*'       => array( 4=>'_key' ),
								'A.idclient'=> array( $idclient, 'num', '=', 'AND' ),
								'A.parentid'=> array( '(0' . (($idexpand) ? ','.str_replace('_', ',', str_replace('-', '', $idexpand)):'') . ')' , 'func', ' IN ', 'AND' ),
								'A.status'  => array( 4, 'num', '<' ),
								'A.dirname' => array( 5=>'ASC' )
							  );
			$dbrs = $this->db->select($table, $parameter);
			// create tree-array
			while($dbrs->next_record()) {
				$fm_tree[$dbrs->f('iddirectory')] = array( 0 => $dbrs->Record);
				if ($dbrs->f('parentid') != 0) {
					if (is_array($fm_tree[$dbrs->f('parentid')])) $fm_tree[$dbrs->f('parentid')]['_members_'][] = $dbrs->f('iddirectory');
				}
			}
		}
		// return tree-array
		return $fm_tree;
	}

	//
	// get_parent_directories($iddirectory, $idclient)
	//
	function get_parent_directories($iddirectory, $idclient) {
		$dir_list = array();
		$iddir    = $iddirectory;
		// check if values are cached
		if ($this->use_cache && $this->tmp_parentdirs[$idclient.'_'.$iddirectory]) return $this->tmp_parentdirs[$idclient.'_'.$iddirectory];
		// do sql-building
		$table     = array( 'directory'  =>array() );
		$parameter = array( 'parentid'   => array( 4=>'_key' ),
							'iddirectory'=> array( 4=>'_key' ),
							'idclient'   => array( $idclient, 'num', '=' )
						  );
		$db = $this->db->select($table, $parameter);
		// get directory list
		while($db->next_record()) $dir_list[$db->f('iddirectory')] = $db->f('parentid');
		// create expand_list, i.e. 7_3_1
		$expand = "";
		do {
			$expand .= ($expand == "") ? $iddir: '_'.$parent;
			$parent  = $dir_list[$iddir];
			$iddir   = $parent;
		} while ($iddir > 0);
		if ($this->use_cache && $expand) $this->tmp_parentdirs[$idclient.'_'.$iddirectory] = $expand;
		return $expand;
	}

	//
	// file methods
	// methods to handle files
	// checks permission on the methods:
	// get_file, get_directory_dropdown, ge_directory_tree
	//

	//
	// get_file($file, $idclient = '', $type = 1)
	//
	// mixed   : $file		-> string: retrieve ID of file from db
	//						-> else  : ID of file
	// optional: $idclient  -> set : retrieve ID of file of the client from db
	//						-> else: only $file is used as ID
	//
	function get_file($file, $idclient = '', $iddirectory = '', $type = 1) {
		if (empty($file)) return ''; // missing fileid
		if ($this->use_cache && $this->tmp_filedata[$file]) return $this->tmp_filedata[$file];
		// get data for a single file
		$tmp_file = array( 'filename', 'idupl', 'str', 'num');
		$fileval  = (is_string($file)) ? 0: 1;
       	if ($fileval == 0 && empty($idclient) && empty($iddirectory)) return ''; // filename MAYBE unique
		// generate sql statement
		$table = array( 'upl'      =>array('A', 'LEFT JOIN', 'A.author'     , '='                 ),
		                'users'    =>array('B', 'LEFT JOIN', 'B.user_id'    , '=', 'A.iddirectory'), 
						'directory'=>array('C', 'LEFT JOIN', 'C.iddirectory', '=', 'A.idfiletype' ), 
						'filetype' =>array('D', ''         , 'D.idfiletype' , ''                  ) 
					  );
		$parameter = array( 'A.idupl, A.idclient, A.titel, A.filename, A.iddirectory, A.idfiletype, A.used, A.status, A.filesize, A.description, A.pictwidth, A.pictheight, A.pictthumbwidth, A.pictthumbheight, A.author' => array( 4=>'_key' ),
							'A.created'      =>array( 'A.created'      , 'timestamp', 4=>'created'      ),
							'A.lastmodified' =>array( 'A.lastmodified' , 'timestamp', 4=>'lastmodified' ),
						  	'B.surname'      =>array(                                 4=>'nachname'     ),
						  	'B.name'         =>array(                                 4=>'vorname'      ),
						  	'C.dirname'      =>array(                                 4=>'dirname'      ),
						  	'D.filetype'     =>array(                                 4=>'filetype'     ),
							'D.description'  =>array(                                 4=>'filetypedesc' ),
							'D.filetypepict' =>array(                                 4=>'_key'         ),
							'D.filetypegroup'=>array(                                 4=>'_key'         )
						  );
		// add 
		if (!empty($idclient))    $parameter['A.idclient']    = array( $idclient   , 'num', '=', 'AND' );
		if (!empty($iddirectory)) $parameter['A.iddirectory'] = array( $iddirectory, 'num', '=', 'AND' );
		$parameter['A.'.$tmp_file[$fileval]] = array( $file, $tmp_file[$fileval+2], '=' );
		// save data in cache
		$tmp = $this->db->select_record($table, $parameter, $type);
		if ($this->use_cache && $tmp) $this->tmp_filedata[$tmp['idupl']] = $tmp;
		return ((!empty($tmp)) ? $tmp: '');
	}

	//
	// to do: file usage from db-query
	//
	function is_file_in_use($idupl, $idclient = '') {
    return false;
/*
    have to be replaced by a function to check the database contents
    
		if ($this->tmp_filedata[$idupl]) $tmp = $this->tmp_filedata[$idupl];
		else {
			$table = array( 'upl'=>array() );
			$parameter['used'] = array( 4=>'_key' );
			if ($idclient != '') $parameter['idclient'] = array( $idclient, 'num', '=', 'AND' );
			$parameter['idupl'] = array( $idupl, 'num', '=' );
			// get data from db
			$tmp = $this->db->select_record($table, $parameter);
		}
		return ($tmp['used'] > 0);
*/
	}

	//
	// get_files_in_directory($file, $idclient, $order = 'A.filename', $ordersort = 'ASC')
	//
	function get_files_in_directory($iddirectory, $idclient, $order = 'A.filename', $ordersort = 'ASC') {
		if (!$iddirectory) return ''; // missing directoryid
		// get data for a single file
		$table    = array( 'upl'      =>array('A', 'LEFT JOIN', 'A.author'     , '='                 ), 
		                   'users'    =>array('B', 'LEFT JOIN', 'B.user_id'    , '=', 'A.iddirectory'), 
						   'directory'=>array('C', 'LEFT JOIN', 'C.iddirectory', '=', 'A.idfiletype' ), 
						   'filetype' =>array('D', ''         , 'D.idfiletype' , ''                  )
					     );
		$parameter = array('DISTINCT A.idupl, A.titel, A.filename, A.idfiletype, A.used, A.status, A.description, A.author, A.pictwidth, A.pictheight, A.pictthumbwidth, A.pictthumbheight, A.filesize ' => array( 4=>'_key' ),
						   'A.created'      =>array( 'A.created'     , 'timestamp',             4=>'created'     ),
						   'A.lastmodified' =>array( 'A.lastmodified', 'timestamp',             4=>'lastmodified'),
						   'B.surname'      =>array(                                            4=>'nachname'    ),
						   'B.name'         =>array(                                            4=>'vorname'     ),
						   'C.dirname'      =>array(                                            4=>'dirname'     ),
						   'D.filetype'     =>array(                                            4=>'filetype'    ),
						   'D.description'  =>array(                                            4=>'filetypedesc'),
						   'D.filetypepict' =>array(                                            4=>'_key'        ),
						   'D.filetypegroup'=>array(                                            4=>'_key'        ),
						   'A.idclient'     =>array( $idclient       , 'num'      , '=', 'AND', 4=>'_key'        ),
						   'A.iddirectory'  =>array( $iddirectory    , 'num'      , '=',        4=>'_key'        )
						  );
		if ($parameter[$order]) $parameter[$order][5] = $ordersort;
		else                    $parameter[$order]    = array( 5=>$ordersort );
		// get a file list from database and return the $db-object
		return ($this->db->select($table, $parameter));
	}

	function get_file_list_by_filetype( $idclient, $filetype, $orderfield = '', $sorting = 'ASC', $iddirectory = '', $status = '' ) {
		$tmp_filetype = array( 'filetype', 'idfiletype', 'str', 'num');
		$filetypeval  = (is_string($filetype)) ? 0: 1;
		$table = array( 'upl'      =>array('A', 'LEFT JOIN', 'A.author'     , '='                 ), 
						'users'    =>array('B', 'LEFT JOIN', 'B.user_id'    , '=', 'A.iddirectory'),
						'directory'=>array('C', 'LEFT JOIN', 'C.iddirectory', '=', 'A.idfiletype' ), 
						'filetype' =>array('D', ''         , 'D.idfiletype' , ''                  ) 
					  );
		$parameter = array( 'A.idupl, A.idclient, A.filename, A.iddirectory, A.idfiletype, A.used, A.status, A.pictwidth, A.pictheight, A.pictthumbwidth, A.pictthumbheight, A.description, A.author' => array( 4=>'_key' ),
							'A.created'     =>array( 'A.created'     , 'timestamp',            4=>'created'     ),
							'A.lastmodified'=>array( 'A.lastmodified', 'timestamp',            4=>'lastmodified'),
						  	'B.surname'     =>array(                                           4=>'nachname'    ),
						  	'B.name'        =>array(                                           4=>'vorname'     ),
						  	'C.dirname'     =>array(                                           4=>'dirname'     ),
							'A.idclient'    =>array( $idclient       , 'num'      , '=', 'AND'                  )
						  );
		if (is_int($status))    $parameter['A.status']      = array( ' & ' . $status . ' = ' . $status, 'func', ' ', 'AND' );
		if ($iddirectory != '') $parameter['A.iddirectory'] = array( $iddirectory, 'num', '=', 'AND' );
		$parameter['D.'.$tmp_filetype[$filetypeval]] = array( $filetype, $tmp_filetype[$filetypeval+2], '=', 4=>'filetype' );
		if ($orderfield != '') {
			if (!$parameter[$orderfield]) $parameter[$orderfield]    = array( 5=>'ASC' );
			else                          $parameter[$orderfield][5] = $sorting;
		}
		// retrieve data and return db-object
		return ($this->db->select($table, $parameter));
	}

	function get_complete_filename($idfile) {
		$tmp = $this->get_file($idfile);
		return (($tmp) ? $tmp['dirname'] . $tmp['filename']: '');
	}

	//
	// Ergänzt den Dateiname um die typische Thumbnail-Kennung "_cms_thumb" vor der Dateiextention
	//
	function get_thumbnail_filename( $file, $filetype, $fileext = '' ) {
		$fileextention = (!$fileext) ? $this->cfg_client['thumbext']: $fileext;
		return preg_replace("/\.".$filetype."$/i", $fileextention.'.'.$filetype, $file);
	}

	//
	// ermittelt für Bilder die Größe des Vorschaubildes
	// bei allen anderen Dateiformaten wird das Standardbild der Größe 32x32 des Dateityps angezeigt
	//
	function get_thumbnail_size($file, $default_size = 100) {
	
		if (is_int($file)) $filedata = $this->get_file($idfile);
		else {
			if (is_object($file) && method_exists($file, "next_record")) $filedata = $file->Record;
			else return array('width'=>32, 'height'=>32, 'thumbnail'=>$this->fileicon_path.'unknown.gif');
		}
		
		if ($filedata['filetypegroup'] != 'Bilder') {
			$thumbsize = array('width'=>32, 'height'=>32, 'thumbnail'=>$this->fileicon_path.$filedata['filetypepict']);
		} else {
			// get thumbnail infos from db record - size and name
			$thumbsize = array('width'=>$filedata['pictthumbwidth'], 'height'=>$filedata['pictthumbheight']);
			$thumbsize['thumbnail'] = $this->get_thumbnail_filename( $filedata['dirname'].$filedata['filename'], strtolower($filedata['filetype']) );
			// check if thumbnail exists, if not calculate thumbnailsize from original size
			if (file_exists($this->cfg_client['upl_path'].$thumbsize['thumbnail'])) {
				$thumbsize['thumbnail'] = $this->cfg_client['upl_htmlpath'].$thumbsize['thumbnail'];
			} else {
				$thumbsize['thumbnail'] = $this->cfg_client['upl_htmlpath'].$filedata['dirname'].$filedata['filename'];
				$thumbsize['width'] = $filedata['pictwidth'];
				$thumbsize['height'] = $filedata['pictheight'];
				if ($thumbsize['height'] > 0) {
					$size = ($this->cfg_client['thumb_size'] > 0)  ? $this->cfg_client['thumb_size']: $default_size;
					if ($thumbsize['width'] > $size || $thumbsize['height'] > $size) {
						// Berechnung, nur wenn Bild größer Thumbnail-Größe
						// Ergänzung für aspect_ratio 2,3
						$ratio = $thumbsize['width']/$thumbsize['height'];
						switch ($this->aspect_ratio) {
							case 0:
								$thumbsize['width']  = $size;
								$thumbsize['height']  = $size;
								break;
							case 2:
								$thumbsize['height'] = -1;
								$this->_scale_image($thumbsize, $size, $ratio);
								break;
							case 3:
								$thumbsize['width']  = -1;
								$this->_scale_image($thumbsize, $size, $ratio);
								break;
							default:
								$this->_scale_image($thumbsize, $size, $ratio);
								break;
						}
					}
				} else {
					// no thumbnail, no pict height ... normally error, so display default pict for this filetype
					$thumbsize = array('width'=>32, 'height'=>32, 'thumbnail'=>$this->fileicon_path.$filedata['filetypepict']);
				}
			}
		}
		return $thumbsize;
	}

	function _scale_image(&$thumbsize, $size, $ratio) {
		if ($thumbsize['width'] >= $thumbsize['height']) {
			$thumbsize['width']  = $size;
			$thumbsize['height'] = $size/$ratio;
		} else {
			$thumbsize['width']  = $size*$ratio;
			$thumbsize['height'] = $size;
		}
	}

 	function download_file($dir, $filename, $idupl){

		// write header for download
		ob_end_clean();
		header("Expires: Mon, 01 Jan 2000 01:00:00 GMT");
		header("Last-Modified: " . gmdate ("D, d M Y H:i:s", $this->tmp_filedata[$idupl]['lastmodified'] ) . " GMT");
		header("Pragma: no-cache");
		header('Content-type: application/octet-stream');
		header('Content-disposition: attachment; filename='.basename($filename));

		// write file and leave
		readfile($dir.$filename);
		exit;
	}


	//
	// filesystem methods
	//

	//
	// is_filename_in_use_fs
	//
	// checks if $filename is used in $dir
	//
	// mixed: $directory -> string: name of a directory
	//					 -> else  : ID of directory, retrieve dirname
	//
	// return
	//	return true if successful, else errno is set an false will be returned
	//
	function is_filename_in_use_fs( $directory, $filename ) {
		if (!$directory || !$filename) $this->errno = '1405'; // required fields missing
		else {
			$path = $this->cfg_client['upl_path'] . ((is_string($directory)) ? $directory: $this->get_directory_name((int)$directory));
			// check if new filename is already in use ...
			return (file_exists($path.$filename));
		}
	}

} // end class fileaccess
?>