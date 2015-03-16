<?PHP
// File: $Id: fnc.js.php 28 2008-05-11 19:18:49Z mistral $
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
Description : Defines the 'js' related functions
Copyright   : Jürgen Brändle, 2003
Author      : Jürgen Brändle, braendle@web.de
Urls        : www.Sefrengo.de
Create date : 2003-02-06
Last update : 2004-01-08

Public functions
js_export()
js_import()
js_editfile()
js_deletefile()
js_uploadfile()

Private functions
import_jsfile($idclient, $filedir, $jsfilename, $filetype)
delete_imported_jsfile($idclient, $filedir, $jsfilename, $filetype)
unlink_jsfile($idfile)
generate_jsfile($idjsfile, $jsfilecontent)

db functions
delete_jscontent($idjsfile, $idclient)
update_jscontent($idjsfile, $functions, $status)
is_duplicate_filename($idclient, $filename, $filedir, $idjsfile)
get_jscontent_data($idjsfile, $type = 1)
insert_jscontent($idupl, $idclient, $functions, $status)
is_jsfile_exported($idupl)
get_jscontent_id($idupl, $idclient)
get_jsfile_list($idclient)
is_jsfile_in_use($idjsfile)

********************************************************************************/
$js_directory = 'cms/js/';
$js_filetype  = 'js';

//
// js_export()
//
// export a js-file
//
// $idjsfile		must be set
//
// return
//	no return value
//
function js_export() {
	global $idjsfile, $client, $errno, $perm;

	if (!$perm->have_perm(14 ,'js_file', $idjsfile)) return '1701'; // keine ausreichenden Rechte

	$type = (get_magic_quotes_gpc() == 0) ? 1: 0;
	
	$tmp_jsdata  = get_jscontent_data( $idjsfile, $type );
	if (empty($tmp_jsdata)) return '1213';  // export failed, no data found

	$idjsfile = insert_jscontent($tmp_jsdata['idupl'], 0, $tmp_jsdata['filecontent'], $tmp_jsdata['status']);
	if (empty($idjsfile)) return '1205'; // export failed, could not coyp content
	return '1206'; // no error, set success message
}


//
// js_import()
//
// import a js-file to a client
//
// $idjsfile	must be set
//
// return
//	no return value
//
function js_import() {
	global $fm, $client, $idjsfile, $idupl, $errno, $js_directory, $perm;

	if (!$perm->have_perm(13 ,'area_js', '0')) return '1701';	// keine ausreichenden Rechte

	$type = (get_magic_quotes_gpc() == 0) ? 1: 0;

	$tmp_upldata = $fm->get_file((int)$idupl, '', '', 0);
	$tmp_jsdata  = get_jscontent_data($idjsfile, $type );
	$iddir = $fm->get_directory_id($js_directory, $client);
	if (empty($tmp_jsdata) || empty($tmp_upldata) || empty($iddir)) return '1208';  // import failed

	// check if filename already exists
	if (is_duplicate_filename( $client, $tmp_upldata['filename'], $js_directory, '0' )) return '1209'; // duplicated filename, no import

	// copy js-file from client 0 to current client
	// Erstelle Datei-Eintrag
	$idupl = $fm->insert_file((int)$client, $tmp_upldata['filename'], (int)$iddir, (int)$tmp_upldata['idfiletype'], (int) 5, $tmp_upldata['description']);
	if (empty($idupl)) return '1207';

	// copy js-content for client
	$idjsfile = insert_jscontent($idupl, $client, $tmp_jsdata['filecontent'], $tmp_jsdata['status']);
	if (empty($idjsfile)) {
		$fm->delete_file($idupl, $client, false, 'path'); // ensure db integrity
		return '1207';
	}
	$perm->set_owner_rights( 'js_file', $idjsfile, 0x000031B7); // set ownerrights for current language and user
	$fm->write_file_fs($js_directory, $tmp_upldata['filename'], cms_stripslashes($tmp_jsdata['filecontent']), 'path');
	return (!empty($fm->errno)) ? '1417': '1210';  // return errno if import failed, file could not be written, or success message
}

//
// js_editfile()
//
// edit or create a js-file
//
// $idjsfile		must be set for edit, else file will be created
// $filename		must be set
// $filedescription	may be set
// $idclient		must be set
//
// return
//	no return value
//
// error
//	$errno ist set
//
function js_editfile() {
	global $fm, $idclient, $client, $idjsfile, $jsfilename, $jsfiledescription, $jsfilecontent, $js_directory, $js_filetype, $perm, $cms_gruppenids, $cms_gruppenrechte, $cms_gruppenrechtegeerbt, $cms_gruppenrechteueberschreiben;

	if (!$perm->have_perm(2 ,'area_js', '0') && !$perm->have_perm(3 ,'js_file', $idjsfile) && !$perm->have_perm(3 ,'area_js', '0')) return '1701';

	$fileclient = (empty($idclient)) ? 0: $client;
	// check necessary values
	if ((empty($idjsfile) && empty($jsfilename)) || (!$fm->validate_filename($jsfilename))&&!isUrl($jsfilename)) return '1201'; // filename is missing

	//take care for extentions
	$pos = strpos ($jsfilename, ".".$js_filetype);
	if ($pos === false) $jsfilename .= ".".$js_filetype;
	// check if the filename is already in use
	// since there is no way to change a js-filename we have to check the duplicate only for new files
	if (empty($idjsfile) && is_duplicate_filename( $fileclient, $jsfilename, $js_directory, $idjsfile )) return '1202';
	$status = 1;
	// create a db-entry for a js-file
	// uses cms_upl to store the information needed
	if (!empty($idjsfile)) {
		if(isUrl($jsfilename)==false){
			// js-file is existing, so update it
			update_jscontent($idjsfile, $jsfilecontent, $status );
			// update js-file record, if not in import area
			if (!empty($fileclient)) {
				$tmp_data = get_jscontent_data( $idjsfile, 0 );
				$fm->update_file2((int) $tmp_data['idupl'], (int) $tmp_data['idclient'], $tmp_data['filename'], (int) $tmp_data['iddirectory'], (int) $tmp_data['idfiletype'], 5, $jsfiledescription, '');
				if (!empty($fm->errno)) return '1218'; // update file data failed, if this happens we have a big problem ... :(
	
				// perms to be set, check if user got the perms to change perms
				if ($perm->have_perm('6', 'js_file', $idjsfile)) {
					$perm->set_group_rights( 'js_file', $idjsfile, $cms_gruppenids, $cms_gruppenrechte, $cms_gruppenrechtegeerbt, $cms_gruppenrechteueberschreiben, '', 0xFFFFFFFF, '0' );
				}
			}
		}
	}
	else {
		// js-file is new, so create an entry in cms_upl
		$idupl = $fm->insert_file((int)$fileclient, $jsfilename, $js_directory, $js_filetype, (int) 5, $jsfiledescription);
		// insert js-file-content in cms_js
		if (empty($idupl)) return '1203';
		$idjsfile = insert_jscontent($idupl, $fileclient, $jsfilecontent, 1, '', 0);
		if (!empty($idjsfile)) {
			$perm->set_owner_rights( 'js_file', $idjsfile, 0x000031B7); // set ownerrights for current language and user
		} else {
			// insert js-file content failed, ensure db integrity and return errno
			$fm->delete_file($idupl, $fileclient, false, 'path');
			return '1203';
		}
		
	}
	if (!empty($idclient)) {
		if(isUrl($jsfilename)==false){
			remove_magic_quotes_gpc($jsfilecontent);
			$fm->write_file_fs($js_directory, $jsfilename, $jsfilecontent, 'path');
		}
	}
	return ((!empty($fm->errno)) ? '1417': '');  // write js-file failed, file could not be written else no errno
}


//
// js_deletefile()
//
// $idjsfile		must be set
// $idclient		must be set
//
// return
//	no return value
//
// error
//	$errno ist set
//
function js_deletefile() {
	global $fm, $idjsfile, $idupl, $idclient, $perm, $cfg_client;

	if (!$perm->have_perm(5 ,'js_file', $idjsfile)) return '1701'; // keine ausreichenden rechte

	if (is_jsfile_in_use($idupl)) return '1211'; // file is in use, no delete possible

	// clear record in cms_js
	if (!empty($idclient)) {
		if (!is_jsfile_exported($idupl))
			$return = $fm->delete_file((int)$idupl, $idclient, false, 'path');
		else
			$return = $fm->update_file((int)$idupl, (int) 0);
		if (is_string($return)) return $return;

		delete_jscontent( $idjsfile, $idclient );
		$perm->delete_perms($idjsfile, 'js_file', 0, 0, 0, true);
	} else {
		$return = $fm->delete_file((int)$idupl, $idclient, false, 'path');
		if (is_string($return)) return $return;
		delete_jscontent( $idjsfile, $idclient );
	}
}


//
// js_downloadfile()
//
// $idjsfile		must be set
//
// return
//	no return value
//
// error
//	$errno ist set
//
function js_downloadfile(){
	global $fm, $idjsfile, $cfg_client, $perm;

	// rechte prüfen für download js-file
	if (!$perm->have_perm(8 ,'js_file', $idjsfile)) return '1701';

	$fm->get_file((int)$idjsfile);
	// so far no error conditions ... get directory and filename
	$dir = $cfg_client['htmlpath'] . $fm->tmp_filedata[(int)$idjsfile]['dirname'];
	$filename = $fm->tmp_filedata[(int)$idjsfile]['filename'];
	$fm->download_file($dir, $filename, (int)$idjsfile);
}


//
// js_uploadfile()
//
// $idclient		must be set
// $_FILES	must be set
//
// return
//	no return value
//
// error
//	$errno ist set
//
function js_uploadfile() {
	global $cfg_client, $idclient, $path, $errno, $js_filetype, $js_directory, $cfg_cms, $perm, $idjsfile;

	// rechte prüfen für upload js-file
	if (!$perm->have_perm(9 ,'area_js', '0')) return '1701';

	$userfile = $_FILES['jsuploadfile']['tmp_name'];
	$userfilename = $_FILES['jsuploadfile']['name'];
	$userfile_size = $_FILES['jsuploadfile']['size'];

	if (empty($userfilename) && empty($userfile_size)) return '0707'; // no size or no name ... upload canceled??
	// check if js-file
	if (strtolower(substr(strrchr ($userfilename, "."),1)) != $js_filetype) return '0705'; // wrong filetype by fileextention

	// remove critical chars from filename
	$userfilename = strtr($userfilename,'ÄÖÜäöüßé?>\/:\"*<>|#+','AOUaouse............-');
	// copy uploaded js-file and import content
	$str_filelocation = $cfg_client['path'].$js_directory.$userfilename;
	if (!@move_uploaded_file($userfile,$str_filelocation)) return '0703'; // can't upload file, maybe safe_mode restrictions on
	// upload and move successful ... try chmod
	if ($cfg_cms['chmod_enabled'] == '1') chmod($str_filelocation,intval($cfg_cms['chmod_value'],8));
	// do further processing on js-file like saving info in db, setting userrights ...
	$errno = upload_jsfile($idclient, $js_directory, $userfilename, $js_filetype);
	if ($errno) return $errno;
	// upload okay ... set userrigths and ownerrights
	if (!empty($idjsfile)) {
		$perm->set_owner_rights( 'js_file', $idjsfile, 0x000031B7); // set ownerrights for current language and user
	}
}

//
// private functions
//
// all private functions need the specified parameter to work correctly
//

function upload_jsfile($idclient, $filedir, $jsfilename, $filetype) {
	global $fm, $cfg_client, $idjsfile, $errno;

	// get the complete js-file content as string
	$functions = implode('', file($cfg_client['path'].$filedir.$jsfilename) );
	if (get_magic_quotes_gpc() != 0) $functions = cms_addslashes($functions);

	// create or update db-record
	// get idjsfile for existing file
	$idupl = is_duplicate_filename($idclient, $jsfilename, $filedir, 0 );
	if (!empty($idupl)) {
		// js-file is existing, so update it
		$idjsfile = get_jscontent_id($idupl, $idclient);
		$tmp_data = get_jscontent_data( $idjsfile, 0 );
		update_jscontent($idjsfile, $functions, $tmp_data['status'] );
		// update js-file record
		$fm->update_file2($tmp_data['idupl'], $idclient, $jsfilename, $filedir, $filetype, 5, $tmp_data['description'], '');
		return (!empty($fm->errno));
	} else {
		// js-file is new, so create an entry in cms_upl
		$idupl = $fm->insert_file((int)$idclient, $jsfilename, $filedir, $filetype, (int) 5, '');
		if (empty($idupl)) return '0703';
		// insert js-file-content in cms_js
		$idjsfile = insert_jscontent($idupl, $idclient, $functions, 1, '', 0);
		if (empty($idjsfile)) return '1217';
	}
	return 0;
}

//
// unlink_jsfile($idfile)
//
// deletes a file which is specified by idfile
//
// return
//	filename for $idfile
//
// error
//	$errno ist set and filename is set to an empty string
//
function unlink_jsfile($idfile) {
	global $fm, $cfg_client, $errno;

  if (is_string($fm->delete_file_fs( $idfile, 'path' ))) {
    switch($fm->errno) {
      case '1411':
        break;
      case '1415':
        $errno = '1216';
        return false;
        break;
      default:
        break;
    }
  }
  return true;
}

//
// db functions
//

//
// delete_jscontent( $idcss )
//
// deletes a single js-file-content in cms_js
//
// return
//	no return value
//
function delete_jscontent( $idjsfile, $idclient ) {
	global $db_query;
	$db_query->delete_by_id_and_client( 'js', 'idjs', $idjsfile, $idclient );
}

//
// update_jscontent($idjsfile, $functions, $status )
//
// update a single js-filecontent in cms_js
//
// return
//	no return value
//
function update_jscontent($idjsfile, $functions, $status ) {
	global $db_query;

	$parameter = array(
						'filecontent' => array( $functions, 'str' ),
						'status'      => array( $status   , 'num' ),
						'author'      => array( 'uid'     , 'std' ),
						'idjs'        => array( $idjsfile , 'num', '=' )
					  );
	$db_query->update( 'js', $parameter );
}

//
// is_duplicate_filename( $idclient, $filename, $filedir, $idjsfile )
//
// check if the $filename in directory $filedir already exists
//
// return
//	if found idupl of the js-file, otherwise 0
//
function is_duplicate_filename( $idclient, $filename, $filedir, $idjsfile ) {
	global $fm, $db_query;

	$idfiledir  = $fm->get_directory_id($filedir, (int)$idclient, false);
	if ($idfiledir) {
		$table = array( 'upl'=>array('A'), 'js'=>array('B') );
		$parameter = array(
						  	'A.iddirectory' =>array( $idfiledir , 'num' , '=', 'AND'),
						  	'A.filename'    =>array( $filename, 'str' , '=', 'AND'),
						  	'A.idclient'    =>array( $idclient, 'num' , '=', 'AND'),
							'A.idupl'       =>array( 'B.idupl', 'func', '=', 'AND', '_key'),
							'B.idjs'        =>array( $idjsfile, 'num' , '<>')
						  );
		$db = $db_query->select( $table, $parameter, array(0,1) );
		return ( ($db->next_record()) ? $db->f("idupl"): 0);
	}
	return 0;
}

//
// get_jscontent_data( $idjsfile, $type = 0 )
//
// returns the data of the js-file $idjsfile as
// array[ 'idjs', 'idupl', 'filetype', 'dirname', 'filename', 'description', 'filecontent', 'status', 'importable', 'created', 'lastmodified', 'author' ]
// there are two possible types of return values:
// 0	data as string_dump 	- used for import
// 1	raw data				- used for display, default
//
// return
//	if found data as array, otherwise no return value
//
function get_jscontent_data( $idjsfile, $type = 0 ) {
	global $db_query;

	$table = array( 'js'=>array('A', 'LEFT JOIN', 'A.idupl', '=' ), 'upl'=>array('B', 'LEFT JOIN', 'B.idupl', '=', 'B.author' ), 'users'=>array('C', 'LEFT JOIN', 'C.user_id', '=', 'B.iddirectory'), 'directory'=>array('D', 'LEFT JOIN', 'D.iddirectory', '=', 'B.idfiletype'), 'filetype'=>array('E', '', 'E.idfiletype') );
	$parameter = array(
					  	'B.idupl, B.idclient, B.iddirectory, B.filename, B.idfiletype, B.description, A.filecontent, A.status, A.author, C.surname, C.name' =>array( 4=>'_key' ),
						'D.dirname'      =>array( 4=>'dirname' ),
						'E.filetype'     =>array( 4=>'filetype' ),
						'A.created'      =>array( 'A.created'      , 'timestamp', 4=>'created' ),
						'A.lastmodified' =>array( 'A.lastmodified' , 'timestamp', 4=>'lastmodified' ),
					  	'A.idjs'         =>array( $idjsfile        , 'num' , '=', 4=>'_key' )
					  );
	return ($db_query->select_record($table, $parameter, $type, array(0,1)));
}

//
// insert_jscontent($idupl, $idclient, $functions, $status, $importable)
//
// inserts a js-file record
//
// return
//	idjs of js-file
//
// error
//	return value is set to 0
//
function insert_jscontent($idupl, $idclient, $functions, $status) {
	global $db_query, $cms_db;
	$parameter = array(
						'idupl'       =>array($idupl,     'num' ),
						'idclient'    =>array($idclient,  'num' ),
						'filecontent' =>array($functions, 'str' ),
						'status'      =>array($status,    'num' ),
						'author'      =>array('uid',      'std' ),
						'created'     =>array('now()',    'func')
					  );
	return ($db_query->insert( 'js', 'idjs', $parameter ));

}

//
// is_jsfile_exported( $idupl )
//
// test if a js-file of cms_upl is bound to an exported js-file in cms_js
//
// return
//	true or false
//
function is_jsfile_exported( $idupl ) {
	$exported = get_jscontent_id( $idupl, '0' );
	return (($exported) ? 1: 0);
}

//
// get_jscontent_id( $idupl, $idclient )
//
// retrieves the idjs of a js-file
//
// return
//	idjs or 0
//
function get_jscontent_id( $idupl, $idclient ) {
	global $db_query;

	$table = array( 'js'=>array() );
	$parameter = array(
					  	'idjs'     =>array( 4=>'_key' ),
					  	'idupl'    =>array( $idupl,    'num', '=', 'AND' ),
					  	'idclient' =>array( $idclient, 'num', '=' )
					  );
	$db = $db_query->select( $table, $parameter );
	return ( ($db->next_record()) ? $db->f('idjs'): 0);
}

//
// get_jsfile_list($idclient)
//
// query all js-file for the given $idclient
//
// return
//	db-object
//
function get_jsfile_list($idclient) {
	global $db_query;

	$table = array( 'upl'=>array('A', 'LEFT JOIN', 'A.author', '='), 'users'=>array('B', 'LEFT JOIN', 'B.user_id', '=', 'A.iddirectory'), 'directory'=>array('C', 'LEFT JOIN', 'C.iddirectory', '=', 'A.idfiletype'), 'filetype'=>array('D', 'LEFT JOIN', 'D.idfiletype', '=', 'A.idupl'), 'js'=>array('E', '', 'E.idupl') );

	$parameter = array(
					  	'E.idjs' =>array( 4=>'_key' ),
					  	'A.description'   =>array( 4=>'_key' ),
					  	'A.idupl'         =>array( 4=>'_key' ),
					  	'A.filename'      =>array( 4=>'_key', 5=>'ASC' ),
					  	'D.filetype'      =>array( 'js', 'str', '=', 'AND' ),
						'A.status'		  =>array( ' & 4 = 4', 'func', ' ', 'AND' ),
					  	'E.idclient'      =>array( $idclient, 'num', '=' )
					  );
	return ($db_query->select( $table, $parameter ));
}

//
// is_jsfile_in_use( $idjsfile )
//
// check if the js-file $idjsfile is used
//
// return
//	if found true, otherwise false
//
function is_jsfile_in_use($idjsfile) {
	global $db, $cms_db;

	$sql = 'SELECT idupl FROM ' . $cms_db['lay_upl'] . " WHERE idupl = $idjsfile LIMIT 1";
	$db->query($sql);
	
	return (($db->next_record()) ? true: false);
}
?>