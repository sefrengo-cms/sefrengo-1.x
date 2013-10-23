<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

$cms_lang['clients_new_client']		= 'New project';
$cms_lang['clients_submit']			= 'Submit';
$cms_lang['clients_clients']			= 'Projects';
$cms_lang['clients_headline']			= 'Projects/languages';
$cms_lang['clients_desc']				= 'Description';
$cms_lang['clients_actions']			= 'Action';
$cms_lang['clients_lang_edit']			= 'Edit / Rename language';
$cms_lang['clients_lang_delete']		= 'Delete language';
$cms_lang['clients_abort']				= 'Cancel';
$cms_lang['clients_lang_new']			= 'New language';
$cms_lang['clients_charset']			= 'Charset';
$cms_lang['clients_collapse']			= 'Collapse';
$cms_lang['clients_expand']			= 'Expand';
$cms_lang['clients_make_new_lang']		= 'Create new language';
$cms_lang['clients_modify']			= 'Edit project name';
$cms_lang['clients_config']			= 'Project settings / Configure project';
$cms_lang['clients_delete']			= 'Delete project';
$cms_lang['clients_client']			= 'Project';
$cms_lang['clients_client_desc']		= 'Project description';
$cms_lang['clients_client_path']		= 'Project path';
$cms_lang['clients_client_url']		= 'Project URL';
$cms_lang['clients_client_directory']	= 'Create directory';
$cms_lang['clients_client_start_lang']	= 'Primary language';
$cms_lang['clients_lang_desc']			= 'Language description';
$cms_lang['clients_lang_charset']		= 'Charset';

//Erfolgsmeldungen
$cms_lang['success_delete_lang']	= 'Language successfully deleted.';
$cms_lang['success_delete_client']	= 'Project successfully deleted from database. Please remove the project files in the file system!';
$cms_lang['success_new_lang']		= 'Language successfully created.';
$cms_lang['success_new_client']	= 'New project successfully created.';

//Errors
$cms_lang['err_cant_make_path']	= "Directory could not be created. Please check the permission for the file system and avoid duplicated directory names.";
$cms_lang['err_cant_extract_tar']	= "Failed to open project template. TAR-archive maybe corrupt?";

?>