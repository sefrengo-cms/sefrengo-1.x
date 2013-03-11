<?PHP
// File: $Id: fnc.css.php 28 2008-05-11 19:18:49Z mistral $
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
Description : Defines the 'css' related functions
Copyright   : Jürgen Brändle, 2002-2003
Author      : Jürgen Brändle, braendle@web.de
Urls        : www.Sefrengo.de
Create date : 2002-12-10
Last update : jb - 04.09.2004 - added checks for vendor-specific-css-rules
								fix: copy of css-files creates real copy

Public functions
css_editrule()
css_deleterule()
css_export()
css_import()
css_downloadfile()
css_editfile()
css_deletefile()
css_uploadfile()

Private functions
upload_cssfile($idclient, $filedir, $cssfilename, $filetype)
delete_imported_cssfile($idclient, $filedir, $cssfilename, $filetype)
import_cssrules($cssfile, $idcssfile, $idclient)
generate_cssfile($idcssfile)
generate_css_styleblock($idcssfile)
unlink_cssfile($idfile)
add_cssrule_to_file($idcss, $idcssfile)
copy_cssrule($idcss, $idcssfile)
delete_all_cssrules_of_file($idcssfile)
change_status_of_all_cssrules_of_file($idcssfile, $statusbit, $type = '|')
remove_cssrule_from_file($idcss, $idcssfile)
remove_all_cssrules_from_file($idcssfile)

DB functions
- css-rule -
remove_cssrules_by_status($idclient, $statusbit)
change_cssrule_status($idcss, $statusbit, $type = '|')
change_cssrules_status($arridcss, $statusbit, $type = '|')
insert_cssrule($client, $type, $name, $description, $styles, $status, $warnings)
update_cssrule($idcss, $type, $name, $description, $styles, $status, $warnings)
delete_cssrule($idcss)
delete_cssrules( $arridcss )
get_cssrule_data($idcss, $type = 1)
is_cssrule_in_use($idcss, $idcssfile)
is_duplicate_selektor($type, $name, $idclient, $idcssfile, $checktype = 0, $idcss = 0)

- css-rule-file-relationship -
insert_cssrelation($idcss, $idcssfile)
remove_cssrule_from_file($idcss, $idcssfile)
remove_all_cssrules_from_file($idcssfile)
get_all_cssrules_of_file($client, $idcssfile, $status, $type = 0, $css_sort_original = 0)
get_cssrule_ids_from_file($idcssfile)

- css-file -
is_duplicate_filename($idclient, $filename, $filedir, $filetype, $idcssfile)
is_cssfile_in_use($idcssfile)
********************************************************************************/
//
// css_editrule()
//
// edit or create a css-rule
//
// $type		must be set
// $name		must be set
// $styles		must be set
// $description	may be set
// $idcss		must be set for edit, else rule will be created
// $idcssfile	must be set
// $idclient	may be set
//
// return
//  no return value
//
// error
//	$errno is set
//
function css_editrule() {
	global $idcss, $type, $name, $description, $styles, $checkstyle, $idcssfile, $idclient, $css_errno, $css_warnings, $perm;

	// rechte prüfen für edit css-regel
	if ( !$perm->have_perm(19 ,'css_file', $idcssfile) && !($perm->have_perm(19 ,'area_css', '0') && empty($idclient)) && !$perm->have_perm(18 ,'area_css', '0') ) return '1701';

	// get validator object
	$validate = get_validator('css_validator');
	
	// check and validate neccessary values
	if ((!$name   || !$validate->cssrule_name($name)) && $type != '@' ) return '1102';
	if (!$styles) return '1103';
	// prepare data for db
	$styles = preg_replace ( '/\s{2,}/is', ' ', trim($styles));
	if (substr($styles, -1) != ';' && $type != '@') $styles .= ';';
	// check if a css-rule with $name and $type is existing in the file $idcssfile or in import list
	// a. idcss is given, then check if name and type exist for another idcss
	// b. idcss is not given, then check if name and type exist
	if (is_duplicate_selektor($type, $name, $idclient, $idcssfile, (($idclient)? 0: 1), $idcss)) return '1121';

	// no error so far ... do db work
	// check rule and set $css_warnings, $status is set to 1 if everything is okay or
	// the checking of the styles was disabled which is a work around for special, non
	// w3c-compilant css-rules for browsers
	// unknown rule-elements will be treated as okay - i.e. ignored while checking
	$status = ($validate->css_elements($styles, $checkstyle)) ? '1': '0';
	// check if we have to insert or update the css-rule
	if (!$idcss) {
		// new css-rule
		$id = insert_cssrule($idclient, $type, $name, $description, $styles, $status, $css_warnings);
		if ($id == 0) return '1119';  // insert of css-rule failed
	} else {
		// update css-rule
		update_cssrule( $idcss, $type, $name, $description, $styles, $status, $css_warnings);
		$id = $idcss;
	}
	
	$idcss = $id;
	
	// update a given css-file, if no errors occured
	if (!empty($idcssfile) && !empty($idclient)) {
		if (!add_cssrule_to_file($id, $idcssfile)) return '1111';
		return generate_cssfile($idcssfile);
	}
	return '';
}

//
// css_deleterule()
//
// delete a css-rule, if neccessary remove it from a css-file
//
// $idcss		must be set
// $idcssfile	must be set
// $idclient	may be set
//
// return
//  no return value
//
// error
//	$errno is set
//
function css_deleterule() {
	global $idcss, $idcssfile, $idclient, $log, $perm;

	// rechte prüfen für delete css-regel
	if ( !$perm->have_perm(21 ,'css_file', $idcssfile) && !($perm->have_perm(21 ,'area_css', '0') && empty($idclient)) ) return '1701';

	// if not in import mode, remove rule from css-file
	// generate css-file after removing
	if (!empty($idclient)) {
		remove_cssrule_from_file( $idcss, $idcssfile );
		$errno = generate_cssfile( $idcssfile );
		if (!empty($errno)) return $errno;	// error while generating css-file
	}
	// ensure to delete only css-rules not attached to any css-file
	if (is_cssrule_in_use($idcss)) return '1101';  // css-rule still in use
	// delete rule
	delete_cssrule($idcss);
	return '';
}

//
// css_export()
//
// export a css-rule from a css-file
//
// $idcss		must be set
//
// return
//	$errno is set to 1112 if successful
//
// error
//	$errno is set
//
function css_export() {
	global $idcss, $idcssfile, $perm;

	// rechte prüfen für export css-regel
	if ( !$perm->have_perm(30 ,'css_file', $idcssfile) ) return '1701';

	$tmp_data = get_cssrule_data( $idcss, 0);
	if (!$tmp_data) return '1108';  // export failed
	if (is_duplicate_selektor( $tmp_data['type'], $tmp_data['name'], '0', '', 1 )) return '1112';  // export success full, i.e. css-rule is in the list of css-exports

	// copy css-rule for client 0 an set result message: 1108 - failed, 1112 - success
	$idexport = insert_cssrule(0, $tmp_data['type'], $tmp_data['name'], $tmp_data['description'], $tmp_data['styles'], '1', $tmp_data['warnings']);
	return ((!empty($idexport)) ? '1112' :'1108');
}


//
// css_import()
//
// import a css-rule to css-file
//
// $idcss		must be set
// $idcssfile	must be set
//
// return
//	1113 if successful
//
// error
//	return $errno
//
function css_import() {
	global $idcss, $idcssfile, $perm;

	// rechte prüfen für import css-regel
	if ( !$perm->have_perm(29 ,'css_file', $idcssfile) ) return '1701';

	// do copy rule for import
	$idcssimport = copy_cssrule($idcss, $idcssfile);
	if ($idcssimport < 0) return ''.abs($idcssimport); // return errno
	// add copied rule to file ... 
	if (!add_cssrule_to_file($idcssimport, $idcssfile)) return '1111';	// return with errno if not successful
	// generate css-file
	$errno = generate_cssfile($idcssfile);
	return ((empty($errno)) ? '1113': $errno);
}

//
// css_editfile()
//
// edit or create a css-file
//
// $idcssfile		must be set for edit, else rule will be created
// $filename		must be set
// $filedescription	may be set
// $idcss			may be set, array of css-rules
// $idclient		must be set
//
// return
//	no return value
//
// error
//	$errno ist set
//
function css_editfile() {
	global $fm, $idclient, $idcssfile, $idcssfilecopy, $filename, $filedescription, $filedirname, $filetype, $idcss, $errno, $perm, $cms_gruppenids, $cms_gruppenrechte, $cms_gruppenrechtegeerbt, $cms_gruppenrechteueberschreiben;

	// rechte prüfen für edit css-file
	if ( !$perm->have_perm(29 ,'css_file', $idcssfile) ) return '1701';

	// get validator object
	$validate = get_validator('css_validator');
	
	$error = '';

	// check necessary values
	if (empty($filename) ||  !$validate->filename($filename)) return '1106'; // filename is missing

	//take care for quotes and extentions
	$pos = strpos ($filename, '.'.$filetype);
	if ($pos === false) $filename .= '.'.$filetype;
	// check if filename is used
	if (is_duplicate_filename( $idclient, $filename, $filedirname, $filetype, $idcssfile )) return '1107';

	$status = 5;
	// create a db-entry for a css-file
	// uses con_upl to store the information needed
	if (!empty($idcssfile)) {
		// delete existing css-file - to be done before a name changes
		$cssfilename = unlink_cssfile($idcssfile);
		// update db record
		if (empty($errno)) {
			$fm->update_file2( (int)$idcssfile, (int) $idclient, $filename, $filedirname, $filetype, (int) $status, $filedescription, '');
			if (!empty($fm->errno)) {
				// update failed ... create css_file to prevent loss of data and return error
				$error = $fm->errno;
				generate_cssfile($idcssfile);
				return $error;
			} else {
  				// any right to be set
				if ($perm->have_perm('6', 'css_file', $idcssfile)) {
					$perm->set_group_rights( 'css_file', $idcssfile, $cms_gruppenids, $cms_gruppenrechte, $cms_gruppenrechtegeerbt, $cms_gruppenrechteueberschreiben, '', 0xFFFFFFFF, '0' );
				}
			}
		}
	} else {
		// create new css-file in cms_upl and leave if any errors have occured
		$idcssfile = $fm->insert_file((int)$idclient, $filename, $filedirname, $filetype, (int) $status, $filedescription);
		if (empty($idcssfile)) return '1110';
		// set $status as flag for new file
		$status = 0;
		// set perms for new file
		$perm->xcopy_perm('0', 'area_css', $idcssfile, 'css_file', 0x303731B7, 0, 0, true);  // copy rights from area_css
		$perm->set_owner_rights( 'css_file', $idcssfile, 0x303731B7); // set ownerrights for current language and user
	}

	// set a marker on all css-rules of the css-file that has been related before
	// remove all existing rules from the rule-list
	// add all selected rules to the rule-list and remove the marker
	// generate css-file on hd
	// delete all marked css-rules if they aren't needed in any other css-file
	if ($status) change_status_of_all_cssrules_of_file( $idcssfile, 0x04, '|');
	// jb - 04.09.2004 
	// Kopieren von CSS-Files erstellt eigenständige Kopie statt abhängigen Clone
	if (is_array($idcss)) {
		reset ($idcss);
		$status_ids = array(0);
		foreach ($idcss as $value) {
			// check if rule must be imported first, uses the check is_duplicate_selektor
			if ($value < 0 || !empty($idcssfilecopy)) {
				$duplicate = is_duplicate_selektor( '', '', $idclient, $idcssfile, 2, abs($value));
				if (empty($duplicate)) {
					$id = copy_cssrule(abs($value), $idcssfile);
				} else {
					$id = 0;
				}
			} else {
				$id = $value;
			}
			// if rule okay, add to css-file
			if ($id > 0) {
				if (!add_cssrule_to_file($id, $idcssfile)) $error = true;
				// if update css-file, change status
				if ($status) {
					$status_ids[] = $id;
					if ($id != $value && $value > 0) $status_ids[] = $value;
				}
			}
		}
		change_cssrules_status($status_ids, 0x0B, '&');
		
		if ($error) return '1111';
		if ($status) remove_cssrules_by_status($idclient, 0x04);
		return generate_cssfile($idcssfile);
	}
}

//
// css_deletefile()
//
// $idcssfile		must be set
//
// return
//	no return value
//
// error
//	$errno ist set
//
function css_deletefile() {
	global $fm, $idcssfile, $errno, $perm;
	
	// rechte prüfen für delete css-file
	if (!$perm->have_perm(5 ,'css_file', $idcssfile)) return '1701';

	if (is_cssfile_in_use($idcssfile)) return '1120';

	// delete existing css-file
	unlink_cssfile($idcssfile);
	// only if physical file is deletes do delete the records
	if (!empty($errno)) return $errno;

	// clear records in cms_css
	delete_all_cssrules_of_file($idcssfile);
	// clear all relation in cms_css_upl and delete existing file
	remove_all_cssrules_from_file($idcssfile);
	// clear record in cms_upl
	$return = $fm->delete_file($idcssfile, '', false, 'path');
	if (is_string($return)) return $return;

	// delete rights of the file
	$perm->delete_perms($idcssfile, 'css_file', 0, 0, 0, true);
	return $perm->db->Errno;
}


//
// css_downloadfile()
//
// $idcssfile		must be set
//
// return
//	no return value
//
// error
//	$errno ist set
//
function css_downloadfile(){
	global $fm, $idcssfile, $cfg_client, $cfg_cms, $perm;

	// rechte prüfen für download css-file
	if (!$perm->have_perm(8 ,'css_file', $idcssfile)) return '1701';

	$fm->get_file((int)$idcssfile);
	// so far no error conditions ... get directory and filename
	$dir = $cfg_client['htmlpath'] . $fm->tmp_filedata[(int)$idcssfile]['dirname'];
	$filename = $fm->tmp_filedata[(int)$idcssfile]['filename'];
	$fm->download_file($dir, $filename, (int)$idcssfile);
	// return filemanager error code
	return $fm->errno;
}


//
// css_uploadfile()
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
function css_uploadfile() {
	global $cfg_client, $idclient, $path, $cfg_cms, $idcssfile, $perm;

	// rechte prüfen für upload css-file
	if (!$perm->have_perm(9 ,'area_css', '0')) return '1701';

	$filetype = 'css';
	$filedir  = $path;
	$userfile = $_FILES['cssuploadfile']['tmp_name'];
	$userfilename = $_FILES['cssuploadfile']['name'];
	$userfile_size = $_FILES['cssuploadfile']['size'];

	// check if upload successful
	if (empty($userfilename) || empty($userfile_size)) return '0707'; // no size or no name ... upload canceled??
	// check if css-file
	if (strtolower(substr(strrchr ($userfilename, "."),1)) != $filetype) return '0705'; // wrong filetype by fileextention
	// remove critical chars from filename
	$userfilename = strtr($userfilename,'ÄÖÜäöüßé?>\/:\"*<>|#+','AOUaouse............-');
	// copy uploaded css-file and import content
	$str_filelocation = $cfg_client['path'].$filedir.$userfilename;
	if (!@move_uploaded_file($userfile,$str_filelocation)) return '0703';	// can't upload file, maybe safe_mode restrictions on
	// upload and move successful ... try chmod
	if ($cfg_cms['chmod_enabled'] == '1') chmod($str_filelocation,intval($cfg_cms['chmod_value'], 8)); 
	// do further processing on css-file like saving info in db, importing the single rules ...
	$errno = upload_cssfile($idclient, $filedir, $userfilename, $filetype);
	if ($errno != 1116 && $errno != 1117) return $errno;
	
	// anything okay ... copy userrigths and set ownerrights
	if (!empty($idcssfile)) {
		$perm->xcopy_perm('0', 'area_css', $idcssfile, 'css_file', 0x303731B7, 0, 0, true);  // copy rights from area_css
		$perm->set_owner_rights( 'css_file', $idcssfile, 0x303731B7); // set ownerrights for current language and user
	}
}

//
// private functions
//
// all private functions need the specified parameter to work correctly
//

//
// upload_cssfile($idclient, $filedir, $cssfilename, $filetype)
//
// uploads the file $cssfilename into cms_upl and imports all css-rules found in
// the uploaded css-file
//
// return
//	errstatus, if true, import was okay, maybe names failed to be okay, display warning
//
// error
//	$errno is set
//
function upload_cssfile($idclient, $filedir, $cssfilename, $filetype) {
	global $fm, $cfg_client, $idcssfile, $idexpand;
	
	$errno = '';
	
	// create or update db-record
	// get idcssfile for existing file
	$idcssfile = is_duplicate_filename($idclient, $cssfilename, $filedir, $filetype, 0 );
	if (!empty($idcssfile)) {
		// css-file is existing, so overwrite it
		// clear records in cms_css and relations in cms_css_upl
		delete_all_cssrules_of_file($idcssfile);
		remove_all_cssrules_from_file( $idcssfile );
		// update css-file record
		$fm->update_file2( (int)$idcssfile, (int) $idclient, $cssfilename, $filedir, $filetype, (int) 5, '', '');
		if (!empty($fm->errno)) return $fm->errno;
	}
	else {
		// css-file is new, so create an entry in cms_upl
		$idcssfile = $fm->insert_file((int)$idclient, $cssfilename, $filedir, $filetype, 5, '');
		if (empty($idcssfile)) return '0703';
	}
	// no errors ... css-file arrived ... import rules it into the database
	$tmp = import_cssrules($cfg_client['path'].$filedir.$cssfilename, $idcssfile, $idclient);
	if ($tmp != '1116' && $tmp != '1117') return $tmp;
	$success = $tmp;
	
	// generate the css-file
	$tmp = generate_cssfile($idcssfile);
	if (!empty($tmp)) return $tmp;
	
	// open new imported file and set result message
	$idexpand = $idcssfile;
	return $success;
}

//
// delete_imported_cssfile($idclient, $filedir, $cssfilename, $filetype)
//
// deletes the rules associated to the file $cssfilename in cms_css and deletes
// all relations in cms_css_upl
//
// return
//	no return value
//
function delete_imported_cssfile($idclient, $filedir, $cssfilename, $filetype) {
	global $fm, $errno;
	// create or update db-record
	// get idcssfile for existing file
	$idcssfile = is_duplicate_filename($idclient, $cssfilename, $filedir, $filetype, 0 );
	if ($idcssfile) {
		// if file is used no delete may occure
		if (is_cssfile_in_use($idcssfile)) $errno = '1120';
		else {
			// delete existing css-file
			unlink_cssfile($idcssfile);
			// only if physical file is deletes do delete the records
			if (!$errno) {
				// clear records in cms_css
				delete_all_cssrules_of_file($idcssfile);
				// clear all relation in cms_css_upl and delete existing file
				remove_all_cssrules_from_file($idcssfile);
				// clear record in cms_upl
				$fm->delete_file($idcssfile, '', false, 'path');
				//delele_cssfile($idcssfile);
			}
		}
	}
}

//
// import_cssrules($cssfile, $idcssfile, $idclient)
//
// imports the css-rules of an uploaded css-file $cssfile
//
// return
//	errstatus, if true, import was okay, maybe names failed to be okay, display warning
//
// error
//	$errno is set
//
function import_cssrules($cssfile, $idcssfile, $idclient) {
	global $css_warnings, $cfg_client;

	if (!file_exists($cssfile)) return '1115';  // css-file is missing

	$errstatus = false;
	// get validator object
	$validate = get_validator('css_validator');
	// get all lines and make a long string of it
	$rules_array = file($cssfile);
	// remove all html and c-style comments, all unneccessary whitespaces
	$rules = implode( '   JB§BJ   ', $rules_array);
	$rules = preg_replace( '/(<\!\-\-)|(\-\->)/', '', $rules);
	$rules = preg_replace( '/\n|\r/s', '', $rules);
	$rules = preg_replace( '/(\/\*.*?\*\/)|([^:]\/\/.*?JB§BJ)|\t/s', '', $rules);
	$rules = preg_replace( '/((\/\/)$)|(JB§BJ)/', '', $rules);
	$rules = preg_replace ( '/\s{2,}/s', ' ', $rules);
    //
    // to do:
    // extract import-statements before rule-splitting
    //
	// split the rules-string into an array of rules, use } as split-character
	$rules_array = explode( '}', $rules);
	// create for all rules an db record
	foreach( $rules_array as $key => $value) {
		// split into name and elements
		$cssrule = explode( "{", trim($value));
		$name   = trim($cssrule[0]);
		$styles = trim($cssrule[1]);
		// normalize elements
		$styles = preg_replace( '/ *?:/', ':', $styles);
		$styles = preg_replace( '/: +/' , ': ', $styles);
		$styles = preg_replace( '/(( *?;) *)/', '; ', $styles);
		if (substr(trim($styles), -1) != ';') $styles .= ';';
		// get ruletype and change rulename if neccessary
		$type = substr($name, 0, 1);
		switch($type) {
			case '#':
			case '.':
			case '@':
			case ':':
				$name = substr($name, 1);
				break;
			default:
				$type = '';
				break;
		}
		// ignore rules without names or styles - might be errors
		if (!$name || !$styles) continue;
		// check names and rules - mark possible errors with status 0
		// jb - 04.09.2004 - addes checks for vendor-specific-css-rules
		// if name is vendor-specific the rule will always be okay, i.e. 
		// vendor-specific css-rules will be ignored by the validator
		// CSS2.1-Spec: 4.1.2
		$status = '1';
		if (!$validate->cssrule_vendor_specific($name)) {
			if (!$validate->cssrule_name($name) || !$validate->css_elements($styles, $cfg_client["css_checking"])) {
				$status = '0';
				$errstatus = true;
			}
		}
		// make db record and add rule to file, ignore errors while inserting ...
		// jb - 28.08.2004 - cms_addslashes hinzugefügt, wegen single quotes in CSS-Stilen
		$styles = cms_addslashes($styles);
		$id = insert_cssrule($idclient, $type, $name, '', $styles, $status, $css_warnings);
		if ($id) add_cssrule_to_file($id, $idcssfile);
	}
	// return import status
	return (($errstatus) ? '1117': '1116');
}

//
// generate_cssfile( $idcssfile )
//
// creates a css-file which is specified by idcssfile
//
// return
//	no return value
//
// error
//	$errno is set
//
function generate_cssfile( $idcssfile ) {
	global $css_errno;

	// delete existing file and retrive filename
	$filename = unlink_cssfile( $idcssfile );
	if (!empty($css_errno)) return $css_errno;
	
	// get css-rules as string
	$style_string = generate_css_styleblock($idcssfile);

	// create file and write all css-rules to it
	$fp = @fopen ($filename, 'ab');
	if (!$fp) return '1122';
 	fwrite( $fp, $style_string );
	fclose( $fp );
	// successful ... return empty
	return '';
}

//
// generate_css_styleblock($idcssfile)
//
// creates a css-style-block for the css-file $idcssfile
//
// return
//	style-block as string or nothing
//
function generate_css_styleblock( $idcssfile ) {
	global $client, $cfg_client, $cms_db, $db;

	$css_rules = array();
	
	// create sql-fragment
	$sql_frag  = 'SELECT * ';
	$sql_frag .= 'FROM ' . $cms_db['css'] . ' LEFT JOIN ' . $cms_db['css_upl'] . ' USING(idcss) ';
	$sql_frag .= 'WHERE idclient = ' . $client;
	$sql_frag .= ' AND idupl    = ' . $idcssfile;
	$sql_frag .= $cfg_client['css_ignore_rules_with_errors'] ? '': ' AND status = 1';

	// get pseudo-class: link
	$sql  = $sql_frag . " AND name LIKE '%:link%'";
	collect_from_db($sql, $css_rules);
	
	// get pseudo-class: visited
	$sql  = $sql_frag . " AND name LIKE '%:visited%'";
	collect_from_db($sql, $css_rules);

	// get pseudo-class: hover
	$sql  = $sql_frag . " AND name LIKE '%:hover%'";
	collect_from_db($sql, $css_rules);

	// get pseudo-class: active
	$sql  = $sql_frag . " AND name LIKE '%:active%'";
	collect_from_db($sql, $css_rules);

	// get all styles without pseudo-classes
	$sql  = $sql_frag;
	$sql .= " AND name NOT LIKE '%:visited%' AND name NOT LIKE '%:hover%'";
	$sql .= " AND name NOT LIKE '%:active%'  AND name NOT LIKE '%:link%'";
	collect_from_db($sql, $css_rules);

	// create the string with all the css-rule
	$css_block = '';
	$max = count($css_rules);
	for($i = 0; $i < $max; $i++) {
		if($css_rules[$i]['type'] == "@") {
			$css_block .= $css_rules[$i]['type'] . 'import url(' . $css_rules[$i]['styles'] . ");\n";
		} else {
			$css_block .= $css_rules[$i]['type'] . $css_rules[$i]['name'] . ' {' . $css_rules[$i]['styles'] . '}'."\n";
		}
	}
	return $css_block;
}

function collect_from_db($sql, &$css_rules) {
	global $db;
	
	$db->query($sql);
	while($db->next_record()) {
		$css_rules[] = array( 'type'  =>$db->f("type"),
							  'name'  =>$db->f('name'),
							  'styles'=>$db->f('styles'));
	}
}

//
// unlink_cssfile($idfile)
//
// deletes a file which is specified by idfile
//
// return
//	filename for $idfile
//
// error
//	$errno ist set and filename is set to an empty string
//
function unlink_cssfile($idfile) {
	global $fm, $client, $cfg_cms, $cfg_client, $css_errno;

	$file = $fm->get_complete_filename((int)$idfile);
	if (empty($file)) $css_errno = '1105'; // css-file not existing
	else {
		// delete file if file exists
		$file = $cfg_client['path'] . $file;
		if (file_exists( $file )) 
		if (!@unlink( $file )) $css_errno = '1123';
	}
	return $file;
}

//
// add_cssrule_to_file( $idcss, $idcssfile )
//
// adds a css-rule to a css-file
//
// return
//	no return value
//
// error
//	$errno is set
//
function add_cssrule_to_file($idcss, $idcssfile) {
	// check if rule is used in the css-file
	if ( !empty($idcssfile) && !empty($idcss)) {
		$rule_used = is_cssrule_in_use($idcss, $idcssfile);
		if (empty($rule_used)) {
			$rule_used = insert_cssrelation($idcss, $idcssfile);
			if (empty($rule_used)) return false; // adding the css-rule failed
		}
	}
	return true;
}

//
// copy_cssrule( $idcss, $idcssfile )
//
// imports a single css-rule to a css-file
//
// return
//	value of imported css-rule
//
// error
//	$errno is set, return value is set to 0
//
function copy_cssrule($idcss, $idcssfile) {
	global $client;

	$id = 0;
	// get css-rule data for import
	$tmp_data = get_cssrule_data($idcss, 0);
	if (empty($tmp_data)) return -1109; // import failed, could not get css-rule data
	// check for duplicates
	$duplicate = is_duplicate_selektor( $tmp_data['type'], $tmp_data['name'], $client, $idcssfile, 0 );
	if (!empty($duplicate)) return -1114;  // import failed, css-rule is existing

	$id = insert_cssrule($client, $tmp_data['type'], $tmp_data['name'], $tmp_data['description'], $tmp_data['styles'], $tmp_data['status'], $tmp_data['warning']);
	return (empty($id) ? -1109: $id);  // return errno or id of copied rule
}

//
// delete_all_cssrules_of_file( $idcssfile )
//
// deletes all css-rule attached to a single css-file,
// does not delete the records for the relationship itself!
//
// return
//	no return value
//
function delete_all_cssrules_of_file( $idcssfile ) {

	// get all css-rule of the css-file
	$idcss = get_cssrule_ids_from_file( $idcssfile );
	// if there are any rules, delete the record of them in cms_css
	if (isset($idcss) && count($idcss) > 0) {
		delete_cssrules( $idcss );
	}
}

//
// change_status_of_all_cssrules_of_file($idcssfile, $statusbit, $type = '|')
//
// set or resets one or more bits of the field status, default is set bits ($type = '|')
//
// return
//	no return value
//
function change_status_of_all_cssrules_of_file($idcssfile, $statusbit, $type = '|') {

	// get all css-rule of the css-file
	$idcss = get_cssrule_ids_from_file( $idcssfile );
	// if there are any rules, delete the record of them in cms_css
	if (isset($idcss) && is_array($idcss) && count($idcss) > 0) {
		change_cssrules_status($idcss, $statusbit, $type);
	}
}

//
// db functions
//
// to do: class-file of db-functions
//

//
// remove_cssrules_by_status($idclient, $statusbit)
//
// deletes css-rules of client $idclient if status has bits set according to $statusbit
//
// return
//	no return value
//
function remove_cssrules_by_status($idclient, $statusbit) {
	global $db_query;

	$db_query->delete_row( 'css', array('(status & '.$statusbit.')'=>array( $statusbit, 'none', '=', 'AND' ), 'idclient'=>array( $idclient, 'num', '=' )) );
}

//
// change_cssrule_status($idcss, $statusbit, $type = '|')
//
// set or resets one or more bits in status for a css-rule, default is set bits ($type = '|')
//
// return
//	no return value
//
function change_cssrule_status($idcss, $statusbit, $type = '|') {
	global $db_query;

	$db_query->update( 'css', array('status'=>array( 'status '.$type.' '.$statusbit, 'func', '', '' ), 'idcss'=>array( $idcss, 'num', '=', '' )) );
}

//
// change_cssrules_status($arridcss, $statusbit, $type = '|')
//
// set or resets one or more bits in status for the set of css-rules $arridcss, default is set bits ($type = '|')
//
// return
//	no return value
//
function change_cssrules_status($arridcss, $statusbit, $type = '|') {
	global $db_query;

	$db_query->update( 'css', array('status'=>array( 'status '.$type.' '.$statusbit, 'func', '', '' ), 'idcss'=>array('IN ('.implode(',', $arridcss ).')', 'func', ' ', '')) );
}

//
// get_cssrule_ids_from_file($idcssfile)
//
// retrieves an array with all css-rule-ids for the css-file $idcssfile
//
// return
//	array of all idcss-values, if any
//
function get_cssrule_ids_from_file($idcssfile) {
	global $db_query;

	$idcss = array();
	$table = array( 'css_upl'=>array() );
	$parameter = array( 'idcss'=>array(4=>'_key'), 'idupl'=>array($idcssfile,'num','=') );
	$db = $db_query->select( $table, $parameter );
	while ($db->next_record()) {
		$idcss[] = $db->f("idcss");
	}
	return $idcss;
}

//
// insert_cssrelation( $idcss, $idcssfile )
//
// inserts a css-rule-relationship to a css-file in cms_css_upl
//
// return
//	idcssupl of relation
//
// error
//	return value is set to 0
//
function insert_cssrelation( $idcss, $idcssfile ) {
	global $db, $cms_db;
	$sql  = 'INSERT INTO ' . $cms_db['css_upl'] . ' (idcss, idupl) VALUES ';
	$sql .= '(' . $idcss . ', ' . $idcssfile . ')';
	$db->query($sql);
	return mysql_insert_id();
}

//
// get_all_cssrules_of_file( $client, $idcssfile, $status, $type = 0, $css_sort_original = 0 )
//
// retrieves a resultset with all css-rule-informations for a given client, css-file and status
//
// return
//	filename with path
//
function get_all_cssrules_of_file( $client, $idcssfile, $status, $type = 0, $css_sort_original = 0 ) {
	global $db_query;

	// pay attention to the css-sort-order: type and  name or idcss
	if ($css_sort_original == 0) $parameter = array( 'A.type' => array( 5 => 'ASC' ), 'A.name' => array( 5 => 'ASC' ));
	else $parameter = array( 'A.idcss' => array( 5 => 'ASC' ) );
	// get all css-rules for the file
	switch ($type) {
		case 1: // css-rules of a client $client
			$table = array( 'css'=>array('A') );
			$parameter['DISTINCT A.*'] =  array(4=>'_key');
			$parameter['A.idclient'] = array( $client, 'num', '=' );
			break;
		case 2: // like default, but this ignores the status field
			$table = array( 'css'=>array('A'), 'css_upl'=>array('B') );
			$parameter['A.*']        = array(4=>'_key');
			$parameter['A.idclient'] = array( $client   , 'num' , '=', 'AND' );
			$parameter['B.idupl'   ] = array( $idcssfile, 'num' , '=', 'AND' );
			$parameter['B.idcss'   ] = array( 'A.idcss' , 'func', '=' );
			break;
		default: // $type = 0, i.e. all rules of one file - order by type, name, idcss
			$table = array( 'css'=>array('A'), 'css_upl'=>array('B') );
			$parameter['A.*']        = array(4=>'_key');
			$parameter['A.idclient'] = array( $client   , 'num' , '=', 'AND' );
			$parameter['A.status'  ] = array( $status   , 'num' , '=', 'AND' );
			$parameter['B.idupl'   ] = array( $idcssfile, 'num' , '=', 'AND' );
			$parameter['B.idcss'   ] = array( 'A.idcss' , 'func', '=' );
			break;
	}
	return ($db_query->select($table, $parameter));
}


//
// delete_cssrule( $idcss )
//
// deletes a single css-rule in cms_css
//
// return
//	no return value
//
function delete_cssrule( $idcss ) {
	global $db, $cms_db;

	$sql  = 'DELETE FROM ' . $cms_db['css'] . ' ';
	$sql .= 'WHERE idcss = ' . $idcss;
	$db->query($sql);
}

//
// delete_cssrules( $arridcss )
//
// deletes multiple css-rules in cms_css
//
// return
//	no return value
//
function delete_cssrules( $arridcss ) {
	global $db_query;

	$db_query->delete_row( 'css', array('idcss'=>array('IN ('.implode(',', $arridcss ).')', 'func', ' ', '')) );
  // $db_query->delete_by_id_and_client( 'css', 'idcss', $idcss );
	// delete_row( 'css', array('idcss'=>array( $idcss, 'num', '=', '' )) );
}

//
// update_cssrule( $idcss, $type, $name, $description, $styles, $status )
//
// update a single css-rule in cms_css
//
// return
//	no return value
//
function update_cssrule( $idcss, $type, $name, $description, $styles, $status, $warnings ) {
	global $db_query;

	$parameter = array(
						'type'        => array( $type, 'str' ),
						'name'        => array( $name, 'str' ),
						'description' => array( $description, 'str' ),
						'styles'      => array( $styles,  'str' ),
						'status'      => array( $status, 'num' ),
						'warning'     => array( $warnings, 'str' ),
						'author'      => array( 'uid', 'std' ),
						'idcss'       => array( $idcss, 'num', '=' )
					  );
	$db_query->update( 'css', $parameter );
}

//
// is_duplicate_filename( $idclient, $filename, $filedir, $filetype, $idcssfile )
//
// check if the $filename in directory $filedir already exists
//
// return
//	if found idupl of the css-file, otherwise 0
//
function is_duplicate_filename( $idclient, $filename, $filedir, $filetype, $idcssfile ) {
	global $fm;
	
	$idfiledir  = $fm->get_directory_id($filedir, (int)$idclient, false);
	if ($idfiledir) {
		$tmp = $fm->get_file( $filename, (int)$idclient, (int) $idfiledir );
		if (is_array($tmp) && array_key_exists('idupl', $tmp)) {
			if ($idcssfile) {
				return (($tmp['idupl'] == $idcssfile) ?0 : $tmp['idupl']);
			} else {
				return $tmp['idupl'];
			}
		}
	}
	return 0;
}

//
// get_cssrule_data( $idcss, $type )
//
// returns the data of the css-rule $idcss as
// array[ 'idcss', 'type', 'name', 'description', 'styles', 'status', 'created', 'lastmodified', 'author' ]
// there are two possible types of return values:
// 0	data as string_dump 	- used for import
// 1	raw data				- used for display, default
//
// return
//	if found data as array, otherwise no return value
//
function get_cssrule_data( $idcss, $type = 1 ) {
	global $db_query;
	$table = array( 'css'=>array('A', 'LEFT JOIN', 'A.author', '='), 'users'=>array('B', '', 'B.user_id') );
	$parameter = array(
					  	'A.idcss'        =>array( $idcss, 'num' , '=', 4=>'_key' ),
					  	'A.type, A.name, A.description, A.styles, A.status, A.warning, A.author' =>array( 4=>'_key' ),
						'A.created'      =>array( 'A.created'      , 'timestamp', 4=>'created' ),
						'A.lastmodified' =>array( 'A.lastmodified' , 'timestamp', 4=>'lastmodified' ),
						'B.surname'      =>array( 4=>'nachname' ),
						'B.name'         =>array( 4=>'vorname' ),
					  );
	return ($db_query->select_record($table, $parameter, $type));
}

//
// is_cssrule_in_use( $idcss, $idcssfile )
//
// check if the css-rule $idcss is used in any css-file or in the file $idcssfile
//
// return
//	if found idcssupl of the css-rule, otherwise 0
//
function is_cssrule_in_use( $idcss, $idcssfile = '' ) {
	global $db, $cms_db;

	$sql  = 'SELECT idcssupl ';
	$sql .= 'FROM '. $cms_db['css_upl'] . ' ';
	$sql .= 'WHERE idcss = ' . $idcss;
	if (!empty($idcssfile)) {
		$sql .= ' AND idupl = ' . $idcssfile;
	}
	$db->query( $sql );
	$idcssupl = ($db->next_record()) ? $db->f("idcssupl"): 0;
	return $idcssupl;
}

//
// is_cssfile_in_use( $idcssfile )
//
// check if the css-file $idcssfile is used
//
// return
//	if found true, otherwise false
//
function is_cssfile_in_use($idcssfile) {
	global $db_query, $fm;

	// check if file is used in relation layout-upload
	$table = array( 'lay_upl'=>array() );
	$parameter = array( 'idupl' =>array( $idcssfile, 'num', '=', 4=>'_key' ) );
	$db = $db_query->select( $table, $parameter );
	if ($db->next_record()) return true;
	// check if file is used in any way, used-counter > 0
	return ($fm->is_file_in_use($idcssfile));
}

//
// is_duplicate_selektor( $type, $name, $idclient, $idcssfile, $checktype, $idcss )
//
// check if the css-file $idcssfile already contains the rule $name of type $type
//
// return
//	if found idcss of the css-rule, otherwise 0
//
function is_duplicate_selektor( $type, $name, $idclient, $idcssfile, $checktype = 0, $idcss = 0 ) {
	global $db_query;

	$table = array( 'css'=>array() );
	switch ($checktype) {
		case 1:
			$parameter = array(
				  	'idcss'    =>array( 4=>'_key' ),
				  	'type'     =>array( $type    , 'str', '=', 'AND' ),
				  	'name'     =>array( $name    , 'str', '=', 'AND' ),
				  	'idclient' =>array( $idclient, 'num', '=' )
				  );
			if ($idcss) $parameter['idcss'] = array( $idcss, 'num', '<>', 'AND', '_key' );
			break;
		case 2:
			$table = array( 
					'css'    =>array( 'C', ' ', ''),
					'css_upl'=>array( 'B', 'RIGHT JOIN cms_css AS C', 'B.idcss', '= C.name', ' A.type = C.type AND A.name '  ), 
					'css'    =>array( 'A', 'LEFT JOIN' , 'A.idcss', '=')
						 );
			$parameter = array(
				  	'A.idclient' =>array( $idclient , 'num',  '=', 'AND' ),
				  	'B.idupl'    =>array( $idcssfile, 'num',  '=', 'AND' ),
				  	'C.idcss'    =>array( $idcss    , 'num',  '=', 'AND', 4=>'_key' ),
				  	'C.idclient' =>array( '0'       , 'num',  '=' )
				  );
			break;
		default:
			$table = array( 'css_upl'=>array('B', 'LEFT JOIN', 'B.idcss', '='), 'css'=>array( 'A', '', 'A.idcss' ) );
			$parameter = array(
					'A.type'     =>array( $type     , 'str', '=', 'AND' ),
				  	'A.name'     =>array( $name     , 'str', '=', 'AND' ),
				  	'A.idclient' =>array( $idclient , 'num', '=', 'AND' ),
				  	'B.idupl'    =>array( $idcssfile, 'num', '=' ),
					'A.idcss'	 =>array( 4=>'_key' )
				  );
			if ($idcss) {
				$parameter['A.idcss']    = array( $idcss, 'num', '<>', 4=>'_key' );
				$parameter['B.idupl'][3] = 'AND';
			}
			break;
	}
	$db = $db_query->select( $table, $parameter );
	return ( ($db->next_record()) ? $db->f("idcss"): 0);
}

//
// insert_cssrule( $client, $type, $name, $description, $styles, $status )
//
// inserts a css-rule record
//
// return
//	idcss of css-rule
//
// error
//	return value is set to 0
//
function insert_cssrule($idclient, $type, $name, $description, $styles, $status, $warnings) {
	global $db_query;
	$parameter = array(
						'idclient'    =>array($idclient,    'num'),
						'type'        =>array($type,        'str'),
						'name'        =>array($name,        'str'),
						'description' =>array($description, 'str'),
						'styles'      =>array($styles,      'str'),
						'status'      =>array($status,      'num'),
						'warning'     =>array($warnings,    'str'),
						'author'      =>array('uid',        'std'),
						'created'     =>array('now()',      'func')
					  );
	return ($db_query->insert( 'css', 'idcss', $parameter ));
}

//
// remove_cssrule_from_file( $idcss, $idcssfile )
//
// removes a css-rule from a css-file
//
// return
//	no return value
//
// error
//	$errno is set
//
function remove_cssrule_from_file( $idcss, $idcssfile ) {
	global $db, $cms_db;
	
	$sql  = 'DELETE FROM ' . $cms_db['css_upl'] . ' ';
	$sql .= 'WHERE idcss = ' . $idcss;
	$sql .= ' AND  idupl = ' . $idcssfile;
	$db->query($sql);
}

//
// remove_all_cssrules_from_file( $idcssfile )
//
// removes all css-rules from a css-file
//
// return
//	no return value
//
// error
//	$errno is set
//
function remove_all_cssrules_from_file( $idcssfile ) {
	global $db_query;
	$db_query->delete_by_id_and_client( 'css_upl', 'idupl', $idcssfile );
}
?>