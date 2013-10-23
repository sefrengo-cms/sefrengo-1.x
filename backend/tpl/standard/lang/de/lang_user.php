<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

$cms_lang['user_name']					= 'Name';
$cms_lang['user_newpassword']				= 'Neues Passwort';
$cms_lang['user_newpasswordagain']			= 'Passwort best&auml;tigen';
$cms_lang['user_delete'] 				= 'Benutzer l&ouml;schen';
$cms_lang['user_nousers'] 				= 'In dieser Gruppe existieren keine Benutzer.';
$cms_lang['user_edit'] 				= 'Benutzer bearbeiten';
$cms_lang['user_email']				= 'E-Mail';
$cms_lang['user_on']					= 'Benutzer aktivieren';
$cms_lang['user_off']					= 'Benutzer deaktivieren';
$cms_lang['user_surname']   				= 'Nachname';
$cms_lang['user_sendmail']   				= 'E-Mail senden';
$cms_lang['user_new']					= 'neuen Benutzer anlegen';
$cms_lang['user_loginname']				= 'Benutzername';
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
$cms_lang['user_firm_email']				= 'E-Mail';
$cms_lang['user_firm_phone']				= 'Telefon';
$cms_lang['user_firm_fax']				= 'Fax';
$cms_lang['user_firm_mobile']				= 'Mobiltelefon';
$cms_lang['user_firm_pager']				= 'Pager';
$cms_lang['user_firm_homepage']			= 'Homepage';
$cms_lang['user_comment']				= 'Kommentar';

include_once($cfg_cms['cms_path'] .'tpl/standard/lang/'.$cfg_cms['backend_lang'].'/lang_user_perms.php');
?>