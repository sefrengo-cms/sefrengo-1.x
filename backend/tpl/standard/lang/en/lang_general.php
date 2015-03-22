<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

// Navigation
$cms_lang['nav_1_0']					= 'Editorial';
$cms_lang['nav_1_1']					= 'Content';
$cms_lang['nav_1_2']					= 'Filemanager';

$cms_lang['nav_2_0']					= 'Design';
$cms_lang['nav_2_1']					= 'Layouts';
$cms_lang['nav_2_2']					= 'Stylesheet';
$cms_lang['nav_2_3']					= 'Javascript';
$cms_lang['nav_2_4']					= 'Modules';
$cms_lang['nav_2_5']					= 'Templates';

$cms_lang['nav_3_0']					= 'Administration';
$cms_lang['nav_3_1']					= 'User';
$cms_lang['nav_3_2']					= 'Groups';
$cms_lang['nav_3_3']					= 'Projects';
$cms_lang['nav_3_4']					= 'System';
$cms_lang['nav_3_5']					= 'Plugins';

$cms_lang['nav_4_0']					= 'Plugins';

$cms_lang['login_pleaselogin']			= 'Please enter your username and password.';
$cms_lang['login_username']			= 'Username';
$cms_lang['login_password']			= 'Password';
$cms_lang['login_invalidlogin']		= 'Wrong username or password. Please try again!';
$cms_lang['login_logininuse']		= 'your username is still is use.<br>Please try again later!';
$cms_lang['login_challenge_fail']		= 'your challenge is fail.<br>Please try again!';

$cms_lang['login_nolang']				= 'No language assignd yet.';
$cms_lang['login_nojs']				= 'Please activate javascript in your browser, so that the backend works correctly.';
$cms_lang['login_licence']				= 'Sefrengo &copy; 2005 - 2006 <a href="http://www.sefrengo.org" target="_blank">sefrengo.org</a>. This is free software, and you may redistribute it under the GPL. Sefrengo comes with absolutely no warranty; for details, see the <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">license</a>.';

$cms_lang['logout_thanksforusingcms']	= 'Thank you for using "Sefrengo". In a few seconds we forward you to the login.';
$cms_lang['logout_youareloggedout']	= 'You are logged off now.';
$cms_lang['logout_backtologin1']		= 'Click here to';
$cms_lang['logout_backtologin2']		= 'relogin';

$cms_lang['area_mod']					= '<b>'.$cms_lang['nav_2_0'].' - Installed modules</b>';
$cms_lang['area_mod_new']				= '<b>'.$cms_lang['nav_2_0'].' - New module</b>';
$cms_lang['area_mod_edit']				= '<b>'.$cms_lang['nav_2_0'].' - Edit module</b>';
$cms_lang['area_mod_edit_sql']	    	= '<b>'.$cms_lang['nav_2_0'].' - Edit module-sql</b>';
$cms_lang['area_mod_config']			= '<b>'.$cms_lang['nav_2_0'].' - Configure module</b>';
$cms_lang['area_mod_import']			= '<b>'.$cms_lang['nav_2_0'].' - Import module</b>';
$cms_lang['area_mod_duplicate']	    = '<b>'.$cms_lang['nav_2_0'].' - Copy module</b>';
$cms_lang['area_mod_xmlimport']		= '<b>'.$cms_lang['nav_2_0'].' - Import XMS style module</b>';
$cms_lang['area_mod_xmlexport']		= '<b>'.$cms_lang['nav_2_0'].' - Export XMS style module</b>';
$cms_lang['area_mod_database']         = $cms_lang['area_mod_import'] . ' <b>- Database</b>';
$cms_lang['area_mod_repository']       = $cms_lang['area_mod_import'] . ' <b>- Repository</b>';

$cms_lang['area_plug']					= '<b>'.$cms_lang['nav_3_0'].' - Installed plugins</b>';
$cms_lang['area_plug_new']		    	= '<b>'.$cms_lang['nav_3_0'].' - New plugin</b>';
$cms_lang['area_plug_new_import']		= $cms_lang['area_plug_new'].' <b>Import</b>';
$cms_lang['area_plug_new_create']		= $cms_lang['area_plug_new'].' <b>Create</b>';
$cms_lang['area_plug_edit']			= '<b>'.$cms_lang['nav_3_0'].' - Edit plugin</b>';
$cms_lang['area_plug_edit_sql']	    = '<b>'.$cms_lang['nav_3_0'].' - Edit plugin meta data</b>';
$cms_lang['area_plug_config']			= '<b>'.$cms_lang['nav_3_0'].' - Configure plugin</b>';
$cms_lang['area_plug_import']			= '<b>'.$cms_lang['nav_3_0'].' - Import plugin</b>';
$cms_lang['area_plug_folder']          = $cms_lang['area_plug_import'] . ' <b>- Folder</b>';
$cms_lang['area_plug_repository']      = $cms_lang['area_plug_import'] . ' <b>- Repository</b>';

$cms_lang['area_con']					= '<b>'.$cms_lang['nav_1_0'].' - '.$cms_lang['nav_1_1'].'</b>';
$cms_lang['area_con_configcat']		= '<b>'.$cms_lang['nav_1_0'].' - Configure folder</b>';
$cms_lang['area_con_configside']		= '<b>'.$cms_lang['nav_1_0'].' - Configure page</b>';
$cms_lang['area_con_edit']				= ''.$cms_lang['nav_1_0'].' - Edit page';

$cms_lang['area_upl']					= '<b>'.$cms_lang['nav_1_0'].' - '.$cms_lang['nav_1_2'].'</b>';

$cms_lang['area_lay']					= '<b>'.$cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_1'].'</b>';
$cms_lang['area_lay_edit']				= '<b>'.$cms_lang['nav_2_0'].' - Edit layout</b>';

$cms_lang['area_css']					= '<b>'.$cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_2'].'</b>';
$cms_lang['area_css_edit']				= '<b>'.$cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_2'].' - Edit css rule</b>';
$cms_lang['area_css_edit_file']		= '<b>'.$cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_2'].' - Edit css file</b>';
$cms_lang['area_css_new_file']			= '<b>'.$cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_2'].' - Ereate css file</b>';
$cms_lang['area_css_import']			= '<b>'.$cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_2'].' - Import css rule</b>';

$cms_lang['area_js']					= '<b>'.$cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_3'].'</b>';
$cms_lang['area_js_edit_file']			= '<b>'.$cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_3'].' - Edit javascript file</b>';
$cms_lang['area_js_import']			= '<b>'.$cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_3'].' - Import javascript file</b>';

$cms_lang['area_tpl']					= '<b>'.$cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_5'].'</b>';
$cms_lang['area_tpl_edit']				= '<b>'.$cms_lang['nav_2_0'].' - Edit template</b>';

$cms_lang['area_user']					= '<b>'.$cms_lang['nav_3_0'].' - '.$cms_lang['nav_3_1'].'</b>';
$cms_lang['area_user_edit']   			= '<b>'.$cms_lang['nav_3_0'].' - Edit user</b>';
$cms_lang['area_group']				= '<b>'.$cms_lang['nav_3_0'].' - '.$cms_lang['nav_3_2'].'</b>';
$cms_lang['area_group_edit']			= '<b>'.$cms_lang['nav_3_0'].' - Edit group</b>';
$cms_lang['area_group_config']			= '<b>'.$cms_lang['nav_3_0'].' - Configure group</b>';
$cms_lang['area_lang']					= '<b>'.$cms_lang['nav_3_0'].' - '.$cms_lang['nav_3_3'].'</b>';
$cms_lang['area_settings']				= '<b>'.$cms_lang['nav_3_0'].' - Project preferences</b>';
$cms_lang['area_settings_general']		= '<b>'.$cms_lang['nav_3_0'].' - '.$cms_lang['nav_3_4'].'</b>';

$cms_lang['lay_action']				= 'Action';
$cms_lang['lay_edit']					= 'Edit';
$cms_lang['lay_export']				= 'Export layout';
$cms_lang['lay_defaultname']			= 'New layout';
$cms_lang['lay_delete']				= 'Delete';
$cms_lang['lay_import']				= 'Import layout';
$cms_lang['lay_layoutname']			= 'Layout name';
$cms_lang['lay_description']			= 'Description';
$cms_lang['lay_doctype']		 	= 'Doctype';
$cms_lang['lay_doctype_autoinsert']		 	= 'Insert doctype automatically at the top of the document';
$cms_lang['lay_doctype_none']		 	= 'no doctype';
$cms_lang['lay_code']					= 'Source code';
$cms_lang['lay_new']					= 'New layout';
$cms_lang['lay_nolayouts']				= 'No layout available.';
$cms_lang['lay_cmshead']				= 'Header files';
$cms_lang['lay_css']					= 'Stylesheet';
$cms_lang['lay_js']					= 'Javascript';
$cms_lang['lay_nofile']				= 'No files';
$cms_lang['lay_duplicate']				= 'Publish layout';
$cms_lang['lay_copy_of']				= 'Copy of ';
$cms_lang['lay_used']			    = 'used';
$cms_lang['lay_available']			    = 'available';

$cms_lang['tpl_action']				= 'Action';
$cms_lang['tpl_actions']['10']			= 'New template';
$cms_lang['tpl_edit']					= 'Edit';
$cms_lang['tpl_defaultname']			= 'New template';
$cms_lang['tpl_is_start']		  	= 'Is starttemplate';
$cms_lang['tpl_delete']				= 'Delete';
$cms_lang['tpl_templatename']			= 'Template name';
$cms_lang['tpl_description']			= 'Description';
$cms_lang['tpl_container']				= 'Container';
$cms_lang['tpl_notemplates']			= 'No template found.';
$cms_lang['tpl_layout']				= 'Layout';
$cms_lang['tpl_duplicate']				= 'Duplicate template';
$cms_lang['tpl_copy_of']				= 'Copy of ';
$cms_lang['tpl_overwrite_all']			= 'Overwrite folder and page template information.';
$cms_lang['tpl_devmessage']			= 'Development version. For testing purpose only';

$cms_lang['lang_language']				= 'Language';
$cms_lang['lang_actions']				= 'Action';
$cms_lang['lang_rename']				= 'Rename';
$cms_lang['lang_delete']				= 'Delete';
$cms_lang['lang_newlanguage']			= 'New language';

$cms_lang['form_nothing']				= '--- empty ---';
$cms_lang['form_select']				= '--- please select ---';


$cms_lang['gen_back']					= 'Back';
$cms_lang['gen_abort']					= 'Cancel';
$cms_lang['gen_sort']					= 'Sort entries';
$cms_lang['gen_default']				= 'Default';
$cms_lang['gen_welcome']                = 'Welcome';
$cms_lang['gen_logout']				= 'Logout';
$cms_lang['gen_deletealert']			= 'Confirm delete?';
$cms_lang['gen_mod_active']			= 'Module is activated';
$cms_lang['gen_mod_deactive']			= 'Module is deactivated';
$cms_lang['gen_mod_edit_allow']		= 'Page content may be edited';
$cms_lang['gen_mod_edit_disallow']		= 'Page content may not be edited';
$cms_lang['gen_fundamental']           = 'Basic setting';
$cms_lang['gen_configuration']			= 'Configuration';
$cms_lang['gen_version']				= 'Version';
$cms_lang['gen_description']			= 'Description';
$cms_lang['gen_verbosename']			= 'Alternate';
$cms_lang['gen_titel']			        = 'Name';
$cms_lang['gen_author']			    = 'Author';
$cms_lang['gen_cat']			        = 'Categorie';
$cms_lang['gen_original']			    = 'Original';
$cms_lang['gen_rights']				= 'Rights';
$cms_lang['gen_overide']				= 'Overide';
$cms_lang['gen_reinstall']				= 'Reinstallation';
$cms_lang['gen_expand']				= 'Expanded';
$cms_lang['gen_parent']				= 'Dependencies';
$cms_lang['gen_delete']				= 'Delete';
$cms_lang['gen_update']				= 'Update';
$mod_lang['gen_font']					= 'Font';
$mod_lang['gen_errorfont']				= 'Font for error message';
$mod_lang['gen_inputformfont']			= 'Font for input field';
$mod_lang['gen_picforsend']				= 'Image for send button';
$mod_lang['gen_select']					= 'Select';
$cms_lang['gen_select_actions']	    = 'Actions...';
$cms_lang['gen_select_view'] 		  	= 'View...';
$cms_lang['gen_select_change_to']	  	= 'Change to...';
$cms_lang['gen_help']                  = 'Help';
$cms_lang['gen_logout_wide']           = 'Logout';
$cms_lang['gen_user']			        = 'User: ';
$cms_lang['gen_licence']			     = 'Licence';

$cms_lang['gen_save']			        = 'Save';
$cms_lang['gen_apply']			        = 'Apply';
$cms_lang['gen_cancel']			        = 'Cancel';

$cms_lang['gen_save_titletext']			= 'Daten speichern und zur&uuml;ck zur vorherigen Ansicht';// to translate
$cms_lang['gen_apply_titletext']		= 'Daten speichern und in dieser Ansicht bleiben';// to translate
$cms_lang['gen_cancel_titletext']		= 'Abbrechen und zur&uuml;ck zur vorherigen Ansicht';// to translate

//contentflex
$mod_lang['cf_add_first_pos']			= 'Insert as first element';
$mod_lang['cf_insert_p1']			= 'Insert after element';
$mod_lang['cf_insert_p2']			= '';

$mod_lang['news_inputname']				= 'Field label';
$mod_lang['news_email']					= 'Email address';
$mod_lang['news_name']					= 'Name (optional)';
$mod_lang['news_subcribe']				= 'Subscribe';
$mod_lang['news_unsubcribe']			= 'Unsubscribe';
$mod_lang['news_both']					= 'both';
$mod_lang['news_headline']				= 'Keep yourself up-to-date.';
$mod_lang['news_headlineback']			= 'Headline';
$mod_lang['news_subcribemessageback']	= 'Subscribe message';
$mod_lang['news_unsubcribemessageback']	= 'Unsubscribe message';
$mod_lang['news_subcribemessage']		= 'Your subscription was successfully.';
$mod_lang['news_unsubcribemessage']		= 'Your subscription was successfully deleted.';
$mod_lang['news_stopmessage']			= 'Your subscription was deactivated.';
$mod_lang['news_goonmessage']			= 'Your subscription was activated.';
$mod_lang['news_err_1001']				= 'Missing email address.';
$mod_lang['news_err_1002']				= 'Error in email address.';
$mod_lang['news_err_1003']				= 'Email address already in use.';
$mod_lang['news_err_1004']				= 'Email address not found.';


$mod_lang['login_error']				= 'Error message';
$mod_lang['login_errormsg']				= 'login information incorrect.';
$mod_lang['login_send']					= 'Login now';
$mod_lang['login_sendout']				= 'Logout';
$mod_lang['login_name']					= 'Please enter login name';
$mod_lang['login_password']				= 'Pplease enter password';
$mod_lang['login_login']				= 'Login - click here';
$mod_lang['login_logout']				= 'Logout - click here';
$mod_lang['login_picforlogout']			= 'Image of logout button';

$mod_lang['link_extern']				= 'external link';
$mod_lang['link_intern']				= 'internal link';
$mod_lang['link_blank']					= 'open link in new window (_blank)';
$mod_lang['link_self']					= 'open link in same window (_self)';
$mod_lang['link_parent']	  			= 'open link in parent frame (_parent)';
$mod_lang['link_top']					= 'open link in top frame (_top)';
$mod_lang['link_edit']					= 'edit link';

$mod_lang['type_text']					= 'Text';
$mod_lang['type_textarea']				= 'Textarea';
$mod_lang['type_wysiwyg']				= 'WYSIWYG';
$mod_lang['type_link']					= 'Link';
$mod_lang['type_file']					= 'File link';
$mod_lang['type_image']					= 'Image';
$mod_lang['type_sourcecode']			= 'Sourcecode';
$mod_lang['type_typegroup']				= 'Content';
$mod_lang['type_container']				= 'Content';
$mod_lang['type_edit_container']		= 'Modul';
$mod_lang['type_edit_side']				= 'Page';
$mod_lang['type_edit_folder']			= 'Folder';
$mod_lang['type_save']					= 'save';
$mod_lang['type_edit']					= 'edit';
$mod_lang['type_new']					= 'new';
$mod_lang['side_new']					= 'create';
$mod_lang['side_config']				= 'configure';
$mod_lang['side_mode']					= 'mode';
$mod_lang['side_publish']				= 'publish';
$mod_lang['side_delete']				= 'delete';
$mod_lang['side_edit']					= 'edit page';
$mod_lang['side_overview']				= 'page overview';
$mod_lang['side_preview']				= 'preview';
$mod_lang['type_delete']				= 'delete';
$mod_lang['type_up']					= 'up';
$mod_lang['type_down']					= 'down';

$mod_lang['img_edit']					= 'edit image';
$mod_lang['imgdescr_edit']				= 'edit image description';

$mod_lang['err_id']						= 'wrong ID.';
$mod_lang['err_type']					= 'wrong TYPE.';

$cms_lang['title_rp_popup'] 			= 'Edit rights';

// 01xx = Con

// 02xx = Str
$cms_lang['err_0201']					= 'Folder contains subfolders. Delete denied.'; 
$cms_lang['err_0202']					= 'Folder contains pages. Delete denied.';

// 03xx = Lay
$cms_lang['err_0301']					= 'Layout in use. Delete denied.';
$cms_lang['err_0302']					= '<font color="black">Layout successfully copied.</font>';

// 05xx = Tpl
$cms_lang['err_0501']					= 'Template in use. Delete denied.';

// 06xx = Dis
$cms_lang['err_0601']					= 'Page not created. Template for folder is missing.';

// 07xx = Upl
$cms_lang['err_0701']					= 'File in use. Delete denied.';
$cms_lang['err_0702']					= 'Directory is already existing.';
$cms_lang['err_0703']					= 'File upload failed.';
$cms_lang['err_0704']					= 'Invalid name.';
$cms_lang['err_0705']					= 'File type not permitted.';
$cms_lang['err_0706']					= 'Destination directory not found.';
$cms_lang['err_0707']					= 'File upload aborted.';

// 08xx = Lang
$cms_lang['err_0801']					= 'Language is already existing';
$cms_lang['err_0802']					= 'There are still pages or folders online. Set pages and folders offline to delete language. CAUTION: This can not be undo!!';


// 09xx = Projekte
$cms_lang['err_0901']					= '';
$cms_lang['err_0902']					= '';

// 13xx = SQL-Schicht
$cms_lang['err_1310']					= 'Missing values for INSERT-Query!';
$cms_lang['err_1320']					= 'Missing values for UPDATE-Query!';
$cms_lang['err_1321']					= 'SQL query without WHERE statement. UPDATE not executed.';
$cms_lang['err_1330']					= 'Missing values for DELETE-Query!';
$cms_lang['err_1331']					= 'SQL query without WHERE statement. DELETE not executed.';
$cms_lang['err_1340']					= 'Missing values for SELECT-Query!';
$cms_lang['err_1350']					= 'Missing parameter for SQL data!'; 
$cms_lang['err_1360']					= 'database error. records inconsistent!';

// 15xx = Repository
$cms_lang['err_1500']	   				= 'Repository error. Function canceld!';
$cms_lang['err_1501']	   				= 'Repository error. File not found!';
$cms_lang['err_1502']	   				= 'Repository error. XML type unknown!';
$cms_lang['err_1503']	   				= 'Repository error. XML type faulty!';
$cms_lang['err_1504']	   				= 'Repository error. XML parser not started or found!';
$cms_lang['err_1505']	   				= 'Repository error. Unable to write to file!';
$cms_lang['err_1506']	   				= 'Repository error. Unable to read file!';
$cms_lang['err_1507']	   				= 'Repository error. Repository-Id not uniqe!';
$cms_lang['err_1508']	   				= 'Repository error. Unable to connect Db!';
$cms_lang['err_1509']	   				= 'Repository error. a needet extension couldn\'t be found!';
$cms_lang['err_1510']	   				= 'Repository error. Unable to delete file!';

// 17xx = Allgemeine Rechtefehler
$cms_lang['err_1701']					= 'Permission denied!';

// 18xx = Konfigurationsfehler
$cms_lang['err_1800']                  = 'Configuration Error!';
$cms_lang['err_1801']                  = 'Safe-Mode aktivated. Function canceld!';

// 19xx = Debugmessages
$cms_lang['err_1900']                  = 'Caution, debug in progress!';
$cms_lang['err_1901']                  = 'Debugmessage: 1!';
$cms_lang['err_1902']                  = 'Debugmessage: 2!';
$cms_lang['err_1903']                  = 'Debugmessage: 3!';
$cms_lang['err_1904']                  = 'Debugmessage: 4!';
$cms_lang['err_1905']                  = 'Debugmessage: 5!';
?>
