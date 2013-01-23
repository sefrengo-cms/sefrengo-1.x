<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

	include_once($cfg_cms['cms_path'] .'tpl/standard/lang/'.$cfg_cms['backend_lang'].'/lang_user.php');

	$cms_lang['group_on']			= 'Enable usergroup';
	$cms_lang['group_off']			= 'Disable usergroup';
	$cms_lang['group_back']	   	= 'Back';
	$cms_lang['group_nocat']       = 'No folders for the selected language found.';
	$cms_lang['group_langconfig']	= 'Configure rights for the selected language';
	$cms_lang['group_langon']		= 'Add language';
	$cms_lang['group_langoff']		= 'Delete language';
	$cms_lang['group_edit']		= 'Edit usergroup';
	$cms_lang['group_config']		= 'Configure usergroup';
	$cms_lang['group_delete']		= 'Delete usergroup';
	$cms_lang['group_new']			= 'Add usergroup';
	$cms_lang['group_nogroups']	= 'No usergroups found.';
	$cms_lang['group_actions']		= 'Action';
	$cms_lang['group_new']			= 'New usergroup';
	$cms_lang['group_name']		= 'Usergroup';
	$cms_lang['group_description']	= 'Description';

	$cms_lang['group_access_area_granted'] = 'Area access granted';
	$cms_lang['group_access_area_denied']  = 'Area access denied';

	$cms_lang['err_group_existname'] = 'This groupname always exists';
	$cms_lang['err_group_noname'] = 'No groupname was given';
	$cms_lang['err_group_incorrectcharacter'] = 'For groupnamcharacters only alphanumeric characters are supported ([0-9a-zA-Z])';

?>