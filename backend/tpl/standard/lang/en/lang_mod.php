<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

// Modul
$cms_lang['mod_modul']					= 'modul';
$cms_lang['mod_parent']				= 'modul-parent';
$cms_lang['mod_action']				= 'action';
$cms_lang['mod_edit']					= 'edit module';
$cms_lang['mod_config']				= 'configure module';
$cms_lang['mod_config_save']			= 'safe module configuration';
$cms_lang['mod_update_save']			= 'update module';
$cms_lang['mod_reinstall_save']		= 'reinstall module';
$cms_lang['mod_export']				= 'export module';
$cms_lang['mod_defaultname']			= 'new module';
$cms_lang['mod_delete']				= 'delete module';
$cms_lang['mod_modulename']			= 'module name';
$cms_lang['mod_verbosename']			= 'alternate';
$cms_lang['mod_konfiguration']			= 'Konfiguration';
$cms_lang['mod_version']				= 'version';
$cms_lang['mod_cat']			   		= 'category';
$cms_lang['mod_description']			= 'description';
$cms_lang['mod_import']				= 'import module';
$cms_lang['mod_input']					= 'configuration';
$cms_lang['mod_new']					= 'new module';
$cms_lang['mod_nomodules']				= 'no modules found.';
$cms_lang['mod_output']				= 'frontend screen';
$cms_lang['mod_duplicate']				= 'duplicate module';
$cms_lang['mod_upload']				= 'upload module';
$cms_lang['mod_download']	   			= 'download module';
$cms_lang['mod_xmlimport']				= 'import XML style module';
$cms_lang['mod_xmlexport']				= 'export XML style module';
$cms_lang['mod_config_change']			= 'edit module configuration';
$cms_lang['mod_konfiguration']			= 'configuration';

$cms_lang['mod_repository']			= 'module repository';
$cms_lang['mod_repository_install']	= 'install module from repository';
$cms_lang['mod_repository_upload']		= 'upload module to repository';
$cms_lang['mod_repository_update']		= 'update module in repository';
$cms_lang['mod_repository_use_dev']	= 'use dev-modules from repository';
$cms_lang['mod_repository_noupdate']	= 'module is up2date';
$cms_lang['mod_repository_notonline']	= 'repository not accessable';
$cms_lang['mod_local_update']	     	= 'update module';
$cms_lang['mod_database']				= 'module database';
$cms_lang['mod_repository_download']	= 'donwload module from repository';
$cms_lang['mod_repository_import']		= 'import module from repository';
$cms_lang['mod_database_import']		= 'import module from database';
$cms_lang['mod_author']		        = 'author';
$cms_lang['mod_sql_install']			= 'SQL-install';
$cms_lang['mod_sql_uninstall']			= 'SQL-uninstall';
$cms_lang['mod_sql_update']			= 'SQL-update';
$cms_lang['mod_new_sql']			    = 'edit SQL';
$cms_lang['mod_rebuild_sql']			= 'reinstall SQL';
$cms_lang['mod_no_wedding']            = 'create new module from ' . $cms_lang['mod_parent'] .  "&#10;" . '(%s - version: %s)';
$cms_lang['mod_config_all']            = 'copy settings to all templates/ folders/ pages using this module';
$cms_lang['mod_config_return']         = 'reset modul to default settings';
$cms_lang['mod_s_overide']             = 'ignore errors and save module in module-%s';
$cms_lang['mod_confirm_update_config'] = 'update module configuration and copy settings to all templates/ folders/ pages using this module?';
$cms_lang['mod_confirm_update']	    = '<b>found update!</b><br>select modul to update?';
$cms_lang['mod_confirm_lupdate']	    = '<b>update started!</b><br>select options to update!';
$cms_lang['mod_confirm_new']	        = 'safe modul anyway?';
$cms_lang['mod_confirm_reinstall']	    = '<b>modul already exist!</b><br>select reinstall module';

// 04xx = modules
$cms_lang['err_0400']	   				= 'module error. function aborted!';
$cms_lang['err_0401']					= 'module in use. delete aborted.';
$cms_lang['err_0402']					= '<font color="black">module successfully copied.</font>';
$cms_lang['err_0403']					= 'no valid *.cmsmod file uploaded';
$cms_lang['err_0404']					= 'no file found for upload';
$cms_lang['err_0405']					= '<font color="black">module update in repository successfully completed.</font>';
$cms_lang['err_0406']					= '<font color="black">module import in repository successfully completed.</font>';
$cms_lang['err_0407']					= '<font color="black">module-SQL successfully completed.</font>';
$cms_lang['err_0408']					= '<font color="black">module upload successfully completed.</font>';
$cms_lang['err_0409']					= '<font color="black">module installation successfully completed.</font>';
$cms_lang['err_0410']					= '<font color="black">module delete successfully completed.</font>';
$cms_lang['err_0411']					= '<font color="black">module uninstall successfully completed.</font>';
$cms_lang['err_0412']					= '<font color="black">saving of module successfully completed.</font>';
$cms_lang['err_0413']					= 'parameter error, function aborted!';
$cms_lang['err_0414']					= '<font color="black">module export successfully completed.</font>';
$cms_lang['err_0415']					= '<font color="black">module update successfully completed.</font>';
$cms_lang['err_0416']					= 'error in module %s, line: <b>%s</b>';
$cms_lang['err_0417']					= 'update in progress';
$cms_lang['err_0418']					= 'action abort';
$cms_lang['err_0419']					= 'reinstall in progress';
$cms_lang['err_0420']					= '<font color="black">module update successfully completed.</font>';
$cms_lang['err_0421']					= '<font color="black">module reinstall successfully completed.</font>';
$cms_lang['err_0422']					= '<font color="black">module reinstall & update successfully completed.</font>';
$cms_lang['err_0423']					= 'parameter error, module parse-error!';
$cms_lang['err_0424']					= 'parameter error, module empty';
$cms_lang['err_0425']	   				= '- please take a new module-name!';
?>
