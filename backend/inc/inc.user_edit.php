<?PHP
// File: $Id: inc.user_edit.php 64 2008-11-19 19:00:33Z bjoern $
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
// + Revision: $Revision: 64 $
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

include('inc/fnc.user.php');

/******************************************************************************
 2. Eventuelle Actions/ Funktionen abarbeiten
******************************************************************************/

$perm->check('area_user');
switch($action) {
	case 'save':
		$errno = user_save();
		if (!$errno && ! isset($_REQUEST['sf_apply'])) {
			header ('HTTP/1.1 302 Moved Temporarily');
			header ('Location:'.$sess->urlRaw("main.php?area=user&idgroup=$idgroup&order=$order&ascdesc=$ascdesc&page=$page&searchterm=".urldecode($searchterm)));
			exit;
		}
		break;
}
/******************************************************************************
 3. Eventuelle Dateien zur Darstellung includieren
******************************************************************************/

include('inc/inc.header.php');

/******************************************************************************
 4. Bildschirmausgabe aufbereiten und ausgeben
******************************************************************************/

// Templatedatei laden
$tpl->loadTemplatefile('user_edit.tpl');
$tmp['AREA'] = $cms_lang['area_user_edit'];
$tmp['FOOTER_LICENSE'] = $cms_lang['login_license'];
if (!empty($errno)) {
	$tpl -> setCurrentBlock('ERROR');
	$tpl_error['ERRORMESSAGE'] = $cms_lang['err_'.$errno];
	$tpl->setVariable($tpl_error);
	$tpl->parseCurrentBlock();
}
// Tabellenformatierung
$tmp['CELLPADDING'] = $cellpadding;
$tmp['CELLSPACING'] = $cellspacing;
$tmp['BORDER'] = $border;
$tmp['LANG_USERNAME'] = $cms_lang['user_loginname'];
$tmp['LANG_NAME'] = $cms_lang['user_name'];
$tmp['LANG_SURNAME'] = $cms_lang['user_surname'];
$tmp['LANG_NEWPASSWORD'] = $cms_lang['user_newpassword'];
$tmp['LANG_NEWPASSWORDAGAIN'] = $cms_lang['user_newpasswordagain'];
$tmp['LANG_EMAIL'] = $cms_lang['user_email'];
$tmp['LANG_GROUP'] = $cms_lang['user_group'];

// RF/symap: block start
$tmp['LANG_SALUTATION'] = $cms_lang['user_salutation'];
$tmp['LANG_TITLE'] = 'Titel';
$tmp['LANG_STREET'] = $cms_lang['user_street'];
$tmp['LANG_STREET_ALT'] = $cms_lang['user_street_alt'];
$tmp['LANG_ZIP'] = $cms_lang['user_zip'];
$tmp['LANG_LOCATION'] = $cms_lang['user_location'];
$tmp['LANG_STATE'] = $cms_lang['user_state'];
$tmp['LANG_COUNTRY'] = $cms_lang['user_country'];
$tmp['LANG_PHONE'] = $cms_lang['user_phone'];
$tmp['LANG_FAX'] = $cms_lang['user_fax'];
$tmp['LANG_MOBILE'] = $cms_lang['user_mobile'];
$tmp['LANG_PAGER'] = $cms_lang['user_pager'];
$tmp['LANG_HOMEPAGE'] = $cms_lang['user_homepage'];
$tmp['LANG_BIRTHDAY'] = $cms_lang['user_birthday'];

$tmp['LANG_FIRM'] = $cms_lang['user_firm'];
$tmp['LANG_POSITION'] = $cms_lang['user_position'];
$tmp['LANG_FIRM_STREET'] = $cms_lang['user_firm_street'];
$tmp['LANG_FIRM_STREET_ALT'] = $cms_lang['user_firm_street_alt'];
$tmp['LANG_FIRM_ZIP'] = $cms_lang['user_firm_zip'];
$tmp['LANG_FIRM_LOCATION'] = $cms_lang['user_firm_location'];
$tmp['LANG_FIRM_STATE'] = $cms_lang['user_firm_state'];
$tmp['LANG_FIRM_COUNTRY'] = $cms_lang['user_firm_country'];
$tmp['LANG_FIRM_EMAIL'] = $cms_lang['user_firm_email'];
$tmp['LANG_FIRM_PHONE'] = $cms_lang['user_firm_phone'];
$tmp['LANG_FIRM_FAX'] = $cms_lang['user_firm_fax'];
$tmp['LANG_FIRM_MOBILE'] = $cms_lang['user_firm_mobile'];
$tmp['LANG_FIRM_PAGER'] = $cms_lang['user_firm_pager'];
$tmp['LANG_FIRM_HOMEPAGE'] = $cms_lang['user_firm_homepage'];
$tmp['LANG_COMMENT'] = $cms_lang['user_comment'];

$tmp['LANG_LAST_LOGIN'] = 'Letzter g&uuml;ltiger Login';
$tmp['LANG_LAST_LOGIN_FAILED'] = 'Letzter ung&uuml;ltiger Login';
$tmp['LANG_FAILED_COUNT'] = 'Ung&uuml;ltige Anmeldungen';
$tmp['LANG_LAST_MODIFIED'] = 'Letzte Bearbeitung';




// Formulareinstellungen
$tmp['FORM_URL'] = $sess->url('main.php');
if (!isset($username) && !empty($iduser)) {
	$iduser = (int) $iduser;
	$sf_user =& sf_factoryGetObject('ADMINISTRATION', 'User'); 
    $sf_user->loadByIduser($iduser);
    
    //only admins can edit their own
    if ($sf_user->getIsAdmin()) {
    	if (! $perm->is_admin()) {
    		exit;
    	}
    }
	
	$sql = "SELECT * FROM ".$cms_db['users']." WHERE user_id='$iduser' LIMIT 0, 1";
	$db->query($sql);
	$db->next_record();
	$tmp['FORM_OLDUSERNAME'] = htmlspecialchars($db->f('username'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_USERNAME'] = htmlspecialchars($db->f('username'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_NAME'] = htmlspecialchars($db->f('name'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_SURNAME'] = htmlspecialchars($db->f('surname'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_EMAIL'] = htmlspecialchars($db->f('email'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_SALUTATION'] = htmlspecialchars($db->f('salutation'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_TITLE'] = htmlspecialchars($db->f('title'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_STREET'] = htmlspecialchars($db->f('street'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_STREET_ALT'] = htmlspecialchars($db->f('street_alt'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_ZIP'] = htmlspecialchars($db->f('zip'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_LOCATION'] = htmlspecialchars($db->f('location'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_STATE'] = htmlspecialchars($db->f('state'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_COUNTRY'] = htmlspecialchars($db->f('country'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_PHONE'] = htmlspecialchars($db->f('phone'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FAX'] = htmlspecialchars($db->f('fax'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_MOBILE'] = htmlspecialchars($db->f('mobile'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_PAGER'] = htmlspecialchars($db->f('pager'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_HOMEPAGE'] = htmlspecialchars($db->f('homepage'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_BIRTHDAY'] = htmlspecialchars($db->f('birthday'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FIRM'] = htmlspecialchars($db->f('firm'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_POSITION'] = htmlspecialchars($db->f('position'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FIRM_STREET'] = htmlspecialchars($db->f('firm_street'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FIRM_STREET_ALT'] = htmlspecialchars($db->f('firm_street_alt'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FIRM_ZIP'] = htmlspecialchars($db->f('firm_zip'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FIRM_LOCATION'] = htmlspecialchars($db->f('firm_location'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FIRM_STATE'] = htmlspecialchars($db->f('firm_state'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FIRM_COUNTRY'] = htmlspecialchars($db->f('firm_country'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FIRM_EMAIL'] = htmlspecialchars($db->f('firm_email'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FIRM_PHONE'] = htmlspecialchars($db->f('firm_phone'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FIRM_FAX'] = htmlspecialchars($db->f('firm_fax'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FIRM_MOBILE'] = htmlspecialchars($db->f('firm_mobile'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FIRM_PAGER'] = htmlspecialchars($db->f('firm_pager'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FIRM_HOMEPAGE'] = htmlspecialchars($db->f('firm_homepage'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_COMMENT'] = htmlspecialchars($db->f('comment'), ENT_COMPAT, 'UTF-8');
	$deletable = $db->f('is_deletable');
	$tmp['FORM_DELETABLE'] = $deletable;
	$tmp['FORM_LAST_LOGIN'] = ($sf_user->getCurrentLoginTimestamp() > 0) ? date($cfg_cms['FormatDate'], $sf_user->getCurrentLoginTimestamp()) . ' '.date($cfg_cms['FormatTime'], $sf_user->getCurrentLoginTimestamp()) : '-';
	$tmp['FORM_LAST_LOGIN_FAILED'] = ($sf_user->getLastLoginFailedTimestamp() > 0) ? date($cfg_cms['FormatDate'], $sf_user->getLastLoginFailedTimestamp()) . ' '.date($cfg_cms['FormatTime'], $sf_user->getLastLoginFailedTimestamp()):'-';
	$tmp['FORM_FAILED_COUNT'] = $sf_user->getFailedCount();
	$sf_user2 =& sf_factoryGetObject('ADMINISTRATION', 'User'); 
    $sf_user2->loadByIduser( $sf_user->getLastmodifiedAuthor() );
	$tmp['FORM_LAST_MODIFIED'] = date($cfg_cms['FormatDate'], $sf_user->getLastmodifiedTimestamp()) . ' '.date($cfg_cms['FormatTime'], $sf_user->getLastmodifiedTimestamp()) .' von '.$sf_user2->getUsername();
	
	// zugeh�rige Gruppen suchen
	$sql = "SELECT idgroup FROM ".$cms_db['users_groups']." WHERE user_id='$iduser'";
	$db->query($sql);
	while ($db->next_record()) {
		$group[] = $db->f('idgroup');
	}
} else {
	remove_magic_quotes_gpc($username);
	remove_magic_quotes_gpc($name);
	remove_magic_quotes_gpc($surname);
	remove_magic_quotes_gpc($email);
	remove_magic_quotes_gpc($password);
	remove_magic_quotes_gpc($salutation);
	remove_magic_quotes_gpc($street);
	remove_magic_quotes_gpc($street_alt);
	remove_magic_quotes_gpc($zip);
	remove_magic_quotes_gpc($location);
	remove_magic_quotes_gpc($state);
	remove_magic_quotes_gpc($country);
	remove_magic_quotes_gpc($phone);
	remove_magic_quotes_gpc($fax);
	remove_magic_quotes_gpc($mobile);
	remove_magic_quotes_gpc($pager);
	remove_magic_quotes_gpc($homepage);
	remove_magic_quotes_gpc($birthday);
	remove_magic_quotes_gpc($firm);
	remove_magic_quotes_gpc($position);
	remove_magic_quotes_gpc($firm_street);
	remove_magic_quotes_gpc($firm_street_alt);
	remove_magic_quotes_gpc($firm_zip);
	remove_magic_quotes_gpc($firm_location);
	remove_magic_quotes_gpc($firm_state);
	remove_magic_quotes_gpc($firm_country);
	remove_magic_quotes_gpc($firm_email);
	remove_magic_quotes_gpc($firm_phone);
	remove_magic_quotes_gpc($firm_fax);
	remove_magic_quotes_gpc($firm_mobile);
	remove_magic_quotes_gpc($firm_pager);
	remove_magic_quotes_gpc($firm_homepage);

	remove_magic_quotes_gpc($comment);
	$tmp['FORM_OLDUSERNAME'] = htmlspecialchars($oldusername, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_USERNAME'] = htmlspecialchars($username, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_PASSWORD'] = htmlspecialchars($password, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_PASSWORDVALIDATE'] = htmlspecialchars($password_validate, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_NAME'] = htmlspecialchars($name, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_SURNAME'] = htmlspecialchars($surname, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_EMAIL'] = htmlspecialchars($email, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_DELETABLE'] = $deletable;
	$tmp['FORM_SALUTATION'] = htmlspecialchars($salutation, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_TITLE'] = htmlspecialchars($title, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_STREET'] = htmlspecialchars($street, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_STREET_ALT'] = htmlspecialchars($street_alt, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_ZIP'] = htmlspecialchars($zip, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_LOCATION'] = htmlspecialchars($location, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_STATE'] = htmlspecialchars($state, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_COUNTRY'] = htmlspecialchars($country, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_PHONE'] = htmlspecialchars($phone, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FAX'] = htmlspecialchars($fax, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_MOBILE'] = htmlspecialchars($mobile, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_PAGER'] = htmlspecialchars($pager, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_HOMEPAGE'] = htmlspecialchars($homepage, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_BIRTHDAY'] = htmlspecialchars($birthday, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FIRM'] = htmlspecialchars($firm, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_POSITION'] = htmlspecialchars($position, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FIRM_STREET'] = htmlspecialchars($firm_street, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FIRM_STREET_ALT'] = htmlspecialchars($firm_street_alt, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FIRM_ZIP'] = htmlspecialchars($firm_zip, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FIRM_LOCATION'] = htmlspecialchars($firm_location, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FIRM_STATE'] = htmlspecialchars($firm_state, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FIRM_COUNTRY'] = htmlspecialchars($firm_country, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FIRM_EMAIL'] = htmlspecialchars($firm_email, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FIRM_PHONE'] = htmlspecialchars($firm_phone, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FIRM_FAX'] = htmlspecialchars($firm_fax, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FIRM_MOBILE'] = htmlspecialchars($firm_mobile, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FIRM_PAGER'] = htmlspecialchars($firm_pager, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_FIRM_HOMEPAGE'] = htmlspecialchars($firm_homepage, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_COMMENT'] = htmlspecialchars($comment, ENT_COMPAT, 'UTF-8');
	
	
	if ($iduser > 0) {
		$iduser = (int) $iduser;
		$sf_user =& sf_factoryGetObject('ADMINISTRATION', 'User'); 
	    $sf_user->loadByIduser($iduser);
	    
	    
	
		$tmp['FORM_LAST_LOGIN'] = ($sf_user->getCurrentLoginTimestamp() > 0) ? date($cfg_cms['FormatDate'], $sf_user->getCurrentLoginTimestamp()) . ' '.date($cfg_cms['FormatTime'], $sf_user->getCurrentLoginTimestamp()) : '-';
		$tmp['FORM_LAST_LOGIN_FAILED'] = ($sf_user->getLastLoginFailedTimestamp() > 0) ? date($cfg_cms['FormatDate'], $sf_user->getLastLoginFailedTimestamp()) . ' '.date($cfg_cms['FormatTime'], $sf_user->getLastLoginFailedTimestamp()):'-';
		$tmp['FORM_FAILED_COUNT'] = $sf_user->getFailedCount();
		$sf_user2 =& sf_factoryGetObject('ADMINISTRATION', 'User'); 
	    $sf_user2->loadByIduser( $sf_user->getLastmodifiedAuthor() );
		$tmp['FORM_LAST_MODIFIED'] = date($cfg_cms['FormatDate'], $sf_user->getLastmodifiedTimestamp()) . ' '.date($cfg_cms['FormatTime'], $sf_user->getLastmodifiedTimestamp()) .' von '.$sf_user2->getUsername();
	} else {
		$tmp['FORM_LAST_LOGIN'] = '-';
		$tmp['FORM_LAST_LOGIN_FAILED'] = '-';
		$tmp['FORM_FAILED_COUNT'] = 0;
		$tmp['FORM_LAST_MODIFIED'] = '-';
		
	}
}
if (!is_array($group)) {
	$group['0'] = $idgroup;
}

$tmp['BUTTON_SUBMIT_VALUE'] = $cms_lang['gen_save'];
$tmp['BUTTON_SUBMIT_TEXT'] = $cms_lang['gen_save_titletext'];

$tmp['BUTTON_APPLY_VALUE'] = $cms_lang['gen_apply'];
$tmp['BUTTON_APPLY_TEXT'] = $cms_lang['gen_apply_titletext'];

$tmp['BUTTON_CANCEL_URL'] = $sess->url("main.php?area=user&idgroup=$idgroup&order=$order&ascdesc=$ascdesc&page=$page&searchterm=".urldecode($searchterm));
$tmp['BUTTON_CANCEL_VALUE'] = $cms_lang['gen_cancel'];
$tmp['BUTTON_CANCEL_TEXT'] = $cms_lang['gen_cancel_titletext'];

$tmp['IDUSER'] = $iduser;
$tmp['IDGROUP'] = $idgroup;
$tmp['ORDER'] = $order;
$tmp['ASCDESC'] = $ascdesc;
$tmp['PAGE'] = $page;
$tmp['SEARCHTERM'] = $searchterm;
$tpl->setVariable($tmp);
unset($tmp);

// Usergruppen auflisten
$tpl->setCurrentBlock('SELECT_SHOWLIST');
$sql = "SELECT idgroup, name, is_active, is_sys_admin FROM ".$cms_db['groups']." ORDER BY name";
$db->query($sql);
while ($db->next_record()) {

	// Standardusergruppe ausblenden
	if ($db->f('idgroup') != '1') {

		//normale user mit zugriff auf userverwaltung d�rfen keine admins vergeben
        if (! $perm->is_admin() && $db->f('is_sys_admin')) {
			continue;
		}
		//wenn root nur systemadminstrator anzeigen
		if ($iduser == 1 && $db->f('idgroup') != 2) {
		  continue;
		}
		
		$tmp['SHOWLIST_VALUE'] = $db->f('idgroup');
		$tmp['SHOWLIST_ENTRY'] = $db->f('name');
		if (in_array($db->f('idgroup'), $group)) {
			$tmp['SHOWLIST_SELECTED'] = ' selected';
		}
		$tmp['SHOWLIST_STYLE'] = ($db->f('is_sys_admin') == '1') ? ' style="color:darkgreen;"' : '';
		if (empty($tmp['SHOWLIST_STYLE']) && $db->f('is_active') == '0') {
			$tmp['SHOWLIST_STYLE'] = ' style="color:red;"';
		}
		$tpl->setVariable($tmp);
		$tpl->parseCurrentBlock();
		unset($tmp);
	}
}
?>