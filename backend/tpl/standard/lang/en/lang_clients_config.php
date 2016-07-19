<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

$cms_lang['set_session_frontend_lifetime']		= 'Session lifetime for frontend';
$cms_lang['set_session_frontend_enabled']		= 'Session support for frontend';

$cms_lang['setuse_pathes']						= 'Path and file information';
$cms_lang['setuse_path']						= 'Path to frontend';
$cms_lang['setuse_html_path']					= 'HTML path to frontend';
$cms_lang['setuse_contentfile']				= 'Frontend file name';
$cms_lang['setuse_space']						= 'Placeholder of images';
$cms_lang['setuse_general']					= 'Common preferences';
$cms_lang['setuse_publish']					= 'Publish changes after release only';
$cms_lang['setuse_edit_mode']					= 'Edit mode 0=Visual 1=Vis.-Cont. 2=Cont.-Vis. 3=Content';
$cms_lang['setuse_wysiwyg_applet']				= 'WYSIWYG applet 0=never, 1=no, IE 2=always, 3=no IE + Mozilla';
$cms_lang['setuse_default_layout']				= 'Layout template';
$cms_lang['setuse_errorpage']					= 'Idcatside of error page 404';
$cms_lang['setuse_loginpage']					= 'Idcatside of Login timeout page';
$cms_lang['setuse_cache']						= 'Cache frontend pages';
$cms_lang['setuse_session_frontend_domain']		= 'Session frontend domain';
$cms_lang['setuse_url_rewrite']				= 'Apache mod_rewrite support';
$cms_lang['setuse_url_langid_in_defaultlang']		= 'ID der Standardsprache in URL zeigen';
$cms_lang['setuse_url_rewrite_suffix']				= 'URL Rewrite Seiten Suffix';
$cms_lang['setuse_url_rewrite_basepath']		= 'Basepath bei UrlRewrite=2. Variablen: {%http_host}';
$cms_lang['setuse_url_rewrite_404']				= '404 Fehlerseite bei UrlRewrite=2. Variablen: {%http_host}, {%request_uri} oder idcatside';
$cms_lang['setuse_session_disabled_useragents']		= 'Useragents f&uuml;r die keine Session erzeugt wird (eine pro Zeile)';
$cms_lang['setuse_session_disabled_ips']			= 'IPs f&uuml;r die keine Session erzeugt wird (eine pro Zeile)';

$cms_lang['setuse_manipulate_output']			= 'Output manipulation';
$cms_lang['setuse_upl_path']					= 'Start directory filemanager';
$cms_lang['setuse_upl_htmlpath']				= 'Start directory filemanager - HTML';
$cms_lang['setuse_filemanager']				= 'Preferences file manager';
$cms_lang['setuse_forbidden']					= 'Forbidden file extension';
$cms_lang['setuse_thumb_size']					= 'Size of preview images (Thumbs)';
$cms_lang['setuse_thumb_aspectratio']			= 'Keep aspect ratio 0=no, 1=yes, 2=y scales, 3=x scales';
$cms_lang['setuse_upl_addon']          		= 'Generate thumbmails for (if possible)';
$cms_lang['setuse_css_sort_original']          = 'Sorting of CSS rules  (0=alphabetically, 1=order by submission)';
$cms_lang['setuse_thumb_ext']					= 'File name extension of generated thumbnails';
$cms_lang['setuse_fm_delete_ignore_404']		= 'Ignore errors for missing files while deleting 1=yes/0=no';
$cms_lang['setuse_remove_files_404']          	= 'Delete orphaned file entries on database synchronization 1=yes/0=no';
$cms_lang['setuse_css']						= 'CSS editor preferences';
$cms_lang['setuse_csschecking']				= 'Validate CSS rules 1=yes/0=no';
$cms_lang['setuse_css_ignore_error_rules'] 	= 'include invalid CSS rules in CSS file 2=yes/0=no';
$cms_lang['setuse_remove_empty_directories']	= 'Delete empty directories on sychnonization 1=yes/0=no';

$cms_lang['set_meta']							= 'META entries predefinition';
$cms_lang['set_meta_title']				= 'META page title';
$cms_lang['set_meta_other']				= 'Additional meta-values';
$cms_lang['set_meta_description']				= 'META page description';
$cms_lang['set_meta_keywords']					= 'META keywords';
$cms_lang['set_meta_robots']					= 'META robots directives';

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
?>
