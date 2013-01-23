<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

//Settings
$cms_lang['set_action']					= 'action';
$cms_lang['set_general']				= 'common preferences';
$cms_lang['set_submit']					= 'save';
$cms_lang['set_cancel']			    		= 'cancel';
$cms_lang['set_edit']					= 'edit';
$cms_lang['set_gzip']					= 'gzip compress all pages';
$cms_lang['set_backend_cache']	  	     		= 'deny browser caching of backend pages';
$cms_lang['set_chmod_enable']		     		= 'apply CHMOD on uploads';
$cms_lang['set_chmod_value']		    		= 'CHMOD value';
$cms_lang['set_gzip_enable']				    = 'apply compression on downloads';
$cms_lang['set_manipulate_output']	    		= 'output manipulation';
$cms_lang['set_cms_path']				= 'path to backend';
$cms_lang['set_html_path']				= 'HTML path to backend';
$cms_lang['set_filebrowser']		    		= 'File Manager';
$cms_lang['set_backend_lang']		    		= 'backend language';
$cms_lang['set_skin'] 					= 'Skin';
$cms_lang['set_image']					= 'image parameter';
$cms_lang['set_image_mode']		     		= "select driver 'GD', 'IM', 'Imagick', 'NetPBM'";
$cms_lang['set_path_imagelib']		     		= 'path of driver';
$cms_lang['set_logs']					= 'Logfiles';
$cms_lang['set_log_enabled']		     		= 'Logfiles aktiviert';
$cms_lang['set_log_prunetime']		    		= 'delete log files automatically after X days';
$cms_lang['set_log_entries']		    		= 'log file entries per page';
$cms_lang['set_time']					= 'time settings';
$cms_lang['set_FormatDate']		     		= 'format of date';
$cms_lang['set_FormatTime']		     		= 'format of time';
$cms_lang['set_session_frontend_lifetime']   		= 'frontend session livetime';
$cms_lang['set_session_backend_lifetime']    		= 'session livetime of backend';
$cms_lang['set_session_backend_domain']		= 'session backend domain';
$cms_lang['set_session_frontend_enabled']		= 'frontend session support';
$cms_lang['set_stat_enabled']				= 'Statistics activated';

$cms_lang['set_paging_items_per_page'] = 'Angezeigte Eintr&auml;ge pro Seite, wenn Paging unterst&uuml;tzt wird';


$cms_lang['set_repository']				= 'Configure repository';
$cms_lang['set_repository_enabled']	      		= 'Use repository';
$cms_lang['set_repository_server']			= 'Repository server';
$cms_lang['set_repository_updatetime']	    		= 'Update time';
$cms_lang['set_repository_path']			= 'Repository service';
$cms_lang['set_repository_pingtime']	    		= 'Ping time';
$cms_lang['set_repository_show_up2date']    		= 'Show status repository Up2Date';
$cms_lang['set_repository_show_offline']     		= 'Show status repository Offline';
$cms_lang['set_auto_repair_dependency']		= 'Auto repair repository dependency';

$cms_lang['set_db_cache']				= 'Configure database-cache';
$cms_lang['set_db_cache_enabled']			= 'Use database-cache';
$cms_lang['set_db_cache_name']				= 'database-cache name';
$cms_lang['set_db_cache_groups']			= 'Configure cache-groups (sec)';
$cms_lang['set_db_cache_group_default']			= 'cache-group "default"';
$cms_lang['set_db_cache_group_standard']		= 'cache-group "standard"';
$cms_lang['set_db_cache_group_frontend']		= 'cache-group "frontend"';
$cms_lang['set_db_cache_items']			= 'Configure cache-items (sec)';
$cms_lang['set_db_cache_item_tree']		= 'Cache-Item "frontend-tree"';
$cms_lang['set_db_cache_item_content']		= 'Cache-Item "frontend-content"';

$cms_lang['set_db_optimice_tables']		    = 'Configure database-optimice';
$cms_lang['set_db_optimice_tables_enable']		= 'use database-optimice';
$cms_lang['set_db_optimice_tables_time']		= 'database-optimice intervall';
$cms_lang['set_db_optimice_tables_lastrun']	= 'database-optimice lastrun';
?>
