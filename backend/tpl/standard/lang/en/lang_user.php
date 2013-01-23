<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}


$cms_lang['user_name']					= 'Name';
$cms_lang['user_newpassword']			= 'New password';
$cms_lang['user_newpasswordagain']		= 'Confirm new password';
$cms_lang['user_delete'] 				= 'delete user';
$cms_lang['user_nousers'] 				= 'no users in this group.';
$cms_lang['user_edit'] 				= 'edit user';
$cms_lang['user_email']				= 'email';
$cms_lang['user_on']					= 'activate user';
$cms_lang['user_off']					= 'deactivate user';
$cms_lang['user_surname']   			= 'last name';
$cms_lang['user_sendmail']   			= 'send email';
$cms_lang['user_new']					= 'create new user';
$cms_lang['user_loginname']			= 'login name';
$cms_lang['user_action']				= 'action';
$cms_lang['user_group']				= 'groups';

$cms_lang['user_firm']					= 'Company';
$cms_lang['user_position']				= 'Position';
$cms_lang['user_salutation']			= 'Salutation';
$cms_lang['user_street']				= 'Street';
$cms_lang['user_street_alt']			= 'Street 2';
$cms_lang['user_zip']					= 'Post code';
$cms_lang['user_location']				= 'City';
$cms_lang['user_state']				= 'State';
$cms_lang['user_country']				= 'Country';
$cms_lang['user_phone']				= 'Telephone';
$cms_lang['user_fax']					= 'Fax';
$cms_lang['user_mobile']				= 'Mobile phone';
$cms_lang['user_pager']				= 'Pager';
$cms_lang['user_homepage']				= 'Homepage';
$cms_lang['user_birthday']				= 'Birthday';

$cms_lang['user_firm_street']			= 'Company street 1';
$cms_lang['user_firm_street_alt']		= 'Company street 2';
$cms_lang['user_firm_zip']				= 'Company post code';
$cms_lang['user_firm_location']		= 'Company city';
$cms_lang['user_firm_state']			= 'Company state';
$cms_lang['user_firm_country']			= 'Company country';
$cms_lang['user_firm_email']			= 'Company email';
$cms_lang['user_firm_phone']			= 'Company phone';
$cms_lang['user_firm_fax']				= 'Company fax';
$cms_lang['user_firm_mobile']			= 'Company mobile phone';
$cms_lang['user_firm_pager']			= 'Company pager';
$cms_lang['user_firm_homepage']		= 'Company homepage';

$cms_lang['user_comment']				= 'Comments';

	include_once($cfg_cms['cms_path'] .'tpl/standard/lang/'.$cfg_cms['backend_lang'].'/lang_user_perms.php');
?>
