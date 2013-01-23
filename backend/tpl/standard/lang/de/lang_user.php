<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

$cms_lang['user_name']					= 'Name';
$cms_lang['user_newpassword']				= 'Neues Passwort';
$cms_lang['user_newpasswordagain']			= 'Passwort best&auml;tigen';
$cms_lang['user_delete'] 				= 'User l&ouml;schen';
$cms_lang['user_nousers'] 				= 'In dieser Gruppe existieren keine User.';
$cms_lang['user_edit'] 				= 'User bearbeiten';
$cms_lang['user_email']				= 'eMail';
$cms_lang['user_on']					= 'User aktivieren';
$cms_lang['user_off']					= 'User deaktivieren';
$cms_lang['user_surname']   				= 'Nachname';
$cms_lang['user_sendmail']   				= 'eMail senden';
$cms_lang['user_new']					= 'neuen User anlegen';
$cms_lang['user_loginname']				= 'Loginname';
$cms_lang['user_action']				= 'Aktionen';
$cms_lang['user_group']				= 'Gruppen';
$cms_lang['user_salutation']				= 'Anrede';
$cms_lang['user_street']				= 'Adresse 1';
$cms_lang['user_street_alt']				= 'Adresse 2';
$cms_lang['user_zip']					= 'PLZ';
$cms_lang['user_location']				= 'Stadt';
$cms_lang['user_state']				= 'Bundesland';
$cms_lang['user_country']				= 'Land';
$cms_lang['user_phone']				= 'Telefon';
$cms_lang['user_fax']					= 'Fax';
$cms_lang['user_mobile']				= 'Mobiltelefon';
$cms_lang['user_pager']				= 'Pager';
$cms_lang['user_homepage']				= 'Homepage';
$cms_lang['user_birthday']				= 'Geburtstag';
$cms_lang['user_firm']					= 'Firmenname';
$cms_lang['user_position']				= 'Position/Abteilung';
$cms_lang['user_firm_street']				= 'Adresse 1';
$cms_lang['user_firm_street_alt']			= 'Adresse 2';
$cms_lang['user_firm_zip']				= 'PLZ';
$cms_lang['user_firm_location']			= 'Stadt';
$cms_lang['user_firm_state']				= 'Bundesland';
$cms_lang['user_firm_country']				= 'Land';
$cms_lang['user_firm_email']				= 'Email';
$cms_lang['user_firm_phone']				= 'Telefon';
$cms_lang['user_firm_fax']				= 'Fax';
$cms_lang['user_firm_mobile']				= 'Mobiltelefon';
$cms_lang['user_firm_pager']				= 'Pager';
$cms_lang['user_firm_homepage']			= 'Homepage';
$cms_lang['user_comment']				= 'Kommentar';

include_once($cfg_cms['cms_path'] .'tpl/standard/lang/'.$cfg_cms['backend_lang'].'/lang_user_perms.php');
?>