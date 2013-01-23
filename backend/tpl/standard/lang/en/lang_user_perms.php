<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

// Strings of rights panel
$cms_lang['panel_grouphead']    = "Rights of groups and users";
$cms_lang['panel_rechte']       = "Rights";
$cms_lang['panel_granted']      = "Granted";
$cms_lang['panel_denied']       = "Denied";
$cms_lang['panel_savebutton']   = "Apply";
$cms_lang['panel_addbutton']	 = "Add user or group";
$cms_lang['panel_closebutton']	 = "Cancel";
$cms_lang['panel_closebutton2'] = "Okay";
$cms_lang['panel_usergroups']   = "Usergroups";
$cms_lang['panel_user']         = "User";

// area_frontend
$cms_lang['group_area_frontend']	 	= 'Bereich Frontend';
$cms_lang['group_area_frontend_2']	 	= 'Gesch&uuml;tzte Ordner im Frontend anzeigen';
$cms_lang['group_area_frontend_18']	 	= 'Gesch&uuml;tzte Seiten im Frontend anzeigen';
$cms_lang['group_area_frontend_19']	 	= 'Darf interaktive Inhalte bearbeiten';
//frontendcat
$cms_lang['group_frontendcat_2']	 	= 'Gesch&uuml;tzte Ordner im Frontend anzeigen';
$cms_lang['group_frontendcat_18']	 	= 'Gesch&uuml;tzte Seiten im Frontend anzeigen';
$cms_lang['group_frontendcat_19']	 	= 'Darf interaktive Inhalte bearbeiten';
//frontendpage
$cms_lang['group_frontendpage_18']	 	= 'Gesch&uuml;tzte Seite im Frontend anzeigen';
$cms_lang['group_frontendpage_19']	 	= 'Darf interaktive Inhalte bearbeiten';

// area_backend
$cms_lang['group_area_backend']	 	= 'Area Backend';
$cms_lang['group_area_backend_1']	 	= 'access backend';

//area_con
$cms_lang['group_area_con']	 		= 'Area Content / Pages';
$cms_lang['group_area_con_1']	 		= 'view folder';
$cms_lang['group_area_con_2']	 		= 'create folder';
$cms_lang['group_area_con_3']	 		= 'edit folder';
$cms_lang['group_area_con_5']	 		= 'delete folder';
$cms_lang['group_area_con_6']	 		= 'set folder rights';
$cms_lang['group_area_con_7']	 		= 'folder online/ offline/ publish';
$cms_lang['group_area_con_8']	 		= 'protect folder';
$cms_lang['group_area_con_9']	 		= 'sort folders';
$cms_lang['group_area_con_11']	 		= 'configure folder template';
$cms_lang['group_area_con_14']	 		= 'Kategorie + Seite Frontendrechte vergeben';
$cms_lang['group_area_con_15']	 		= 'Kategorie Alias vergeben';
$cms_lang['group_area_con_17']	 		= 'view page';
$cms_lang['group_area_con_18']	 		= 'create page';
$cms_lang['group_area_con_19']	 		= 'edit page';
$cms_lang['group_area_con_20']	 		= 'configure page';
$cms_lang['group_area_con_21']	 		= 'delete page';
$cms_lang['group_area_con_22']	 		= 'set page rightsn';
$cms_lang['group_area_con_23']	 		= 'page online/ offline/ publish';
$cms_lang['group_area_con_24']	 		= 'protect page';
$cms_lang['group_area_con_25']	 		= 'sort pages';
$cms_lang['group_area_con_26']	 		= 'select page template';
$cms_lang['group_area_con_27']	 		= 'select page template';
$cms_lang['group_area_con_28']	 		= 'define start page';
$cms_lang['group_area_con_29']	 		= 'edit META infomation';
$cms_lang['group_area_con_30']	 		= 'move / clone page';
$cms_lang['group_area_con_31']	 		= 'Seiten URL vergeben';

//cat
$cms_lang['group_cat_1']	 		= $cms_lang['group_area_con_1'];
$cms_lang['group_cat_2']	 		= $cms_lang['group_area_con_2'];
$cms_lang['group_cat_3']	 		= $cms_lang['group_area_con_3'];
$cms_lang['group_cat_5']	 		= $cms_lang['group_area_con_5'];
$cms_lang['group_cat_6']	 		= $cms_lang['group_area_con_6'];
$cms_lang['group_cat_7']	 		= $cms_lang['group_area_con_7'];
$cms_lang['group_cat_8']	 		= $cms_lang['group_area_con_8'];
$cms_lang['group_cat_9']	 		= $cms_lang['group_area_con_9'];
$cms_lang['group_cat_11']	 		= $cms_lang['group_area_con_11'];
$cms_lang['group_cat_14']	 		= $cms_lang['group_area_con_14'];
$cms_lang['group_cat_15']	 		= $cms_lang['group_area_con_15'];
$cms_lang['group_cat_17']	 		= $cms_lang['group_area_con_17'];
$cms_lang['group_cat_18']	 		= $cms_lang['group_area_con_18'];
$cms_lang['group_cat_19']	 		= $cms_lang['group_area_con_19'];
$cms_lang['group_cat_20']	 		= $cms_lang['group_area_con_20'];
$cms_lang['group_cat_21']	 		= $cms_lang['group_area_con_21'];
$cms_lang['group_cat_22']	 		= $cms_lang['group_area_con_22'];
$cms_lang['group_cat_23']	 		= $cms_lang['group_area_con_23'];
$cms_lang['group_cat_24']	 		= $cms_lang['group_area_con_24'];
$cms_lang['group_cat_25']	 		= $cms_lang['group_area_con_25'];
$cms_lang['group_cat_26']	 		= $cms_lang['group_area_con_26'];
$cms_lang['group_cat_27']	 		= $cms_lang['group_area_con_27'];
$cms_lang['group_cat_28']	 		= $cms_lang['group_area_con_28'];
$cms_lang['group_cat_29']	 		= $cms_lang['group_area_con_29'];
$cms_lang['group_cat_30']	 		= $cms_lang['group_area_con_30'];
$cms_lang['group_cat_31']	 		= $cms_lang['group_area_con_31'];

//side
$cms_lang['group_side_17']	 		= $cms_lang['group_area_con_17'];
$cms_lang['group_side_19']	 		= $cms_lang['group_area_con_19'];
$cms_lang['group_side_20']	 		= $cms_lang['group_area_con_20'];
$cms_lang['group_side_21']	 		= $cms_lang['group_area_con_21'];
$cms_lang['group_side_22']	 		= $cms_lang['group_area_con_22'];
$cms_lang['group_side_23']	 		= $cms_lang['group_area_con_23'];
$cms_lang['group_side_24']	 		= $cms_lang['group_area_con_24'];
$cms_lang['group_side_25']	 		= $cms_lang['group_area_con_25'];
$cms_lang['group_side_26']	 		= $cms_lang['group_area_con_26'];
$cms_lang['group_side_27']	 		= $cms_lang['group_area_con_27'];
$cms_lang['group_side_28']	 		= $cms_lang['group_area_con_28'];
$cms_lang['group_side_29']	 		= $cms_lang['group_area_con_29'];
$cms_lang['group_side_30']	 		= $cms_lang['group_area_con_30'];
$cms_lang['group_side_31']	 		= $cms_lang['group_area_con_31'];

// area_lay
$cms_lang['group_area_lay']	 	    = 'Area Design / Layouts';
$cms_lang['group_area_lay_1']	 	    = 'view layout';
$cms_lang['group_area_lay_2']	 	    = 'create layout';
$cms_lang['group_area_lay_3']	 	    = 'edit layout';
$cms_lang['group_area_lay_5']	 	    = 'delete layout';
$cms_lang['group_area_lay_6']	 	    = 'set layout rights';
$cms_lang['group_area_lay_7']	 	    = 'import layout';
$cms_lang['group_area_lay_8']	 	    = 'export layout';
$cms_lang['group_lay_1']	 		    = $cms_lang['group_area_lay_1'];
$cms_lang['group_lay_3']	 		    = $cms_lang['group_area_lay_3'];
$cms_lang['group_lay_5']	 		    = $cms_lang['group_area_lay_5'];
$cms_lang['group_lay_6']	 		    = $cms_lang['group_area_lay_6'];
$cms_lang['group_lay_7']	 		    = $cms_lang['group_area_lay_7'];
$cms_lang['group_lay_8']	 		    = $cms_lang['group_area_lay_8'];


// area_css
$cms_lang['group_area_css']			= 'Area Design / Stylesheet';
$cms_lang['group_area_css_1']  		= 'view css-file';
$cms_lang['group_area_css_2']  		= 'create css-file';
$cms_lang['group_area_css_3']  		= 'edit css-file';
$cms_lang['group_area_css_5']  		= 'delete css-file';
$cms_lang['group_area_css_6']  		= 'set css-file rights';
$cms_lang['group_area_css_8']  		= 'download css-file';
$cms_lang['group_area_css_9']  		= 'upload css-file';
$cms_lang['group_area_css_13'] 		= 'import css-file';
$cms_lang['group_area_css_14'] 		= 'export css-file';
$cms_lang['group_area_css_17'] 		= 'show css-rule';
$cms_lang['group_area_css_18'] 		= 'create css-rule';
$cms_lang['group_area_css_19'] 		= 'edit css-rule';
$cms_lang['group_area_css_21'] 		= 'delete css-rule';
$cms_lang['group_area_css_22'] 		= 'set css-rule rights';
$cms_lang['group_area_css_29'] 		= 'import css-rule';
$cms_lang['group_area_css_30'] 		= 'export css-rule';
$cms_lang['group_css_file_1']  		= $cms_lang['group_area_css_1'];
$cms_lang['group_css_file_2']  		= $cms_lang['group_area_css_2'];
$cms_lang['group_css_file_3']  		= $cms_lang['group_area_css_3'];
$cms_lang['group_css_file_5']  		= $cms_lang['group_area_css_5'];
$cms_lang['group_css_file_6']  		= $cms_lang['group_area_css_6'];
$cms_lang['group_css_file_8']  		= $cms_lang['group_area_css_8'];
$cms_lang['group_css_file_9']  		= $cms_lang['group_area_css_9'];
$cms_lang['group_css_file_13'] 		= $cms_lang['group_area_css_13'];
$cms_lang['group_css_file_14'] 		= $cms_lang['group_area_css_14'];
$cms_lang['group_css_file_17'] 		= $cms_lang['group_area_css_17'];
$cms_lang['group_css_file_18'] 		= $cms_lang['group_area_css_18'];
$cms_lang['group_css_file_19'] 		= $cms_lang['group_area_css_19'];
$cms_lang['group_css_file_21'] 		= $cms_lang['group_area_css_21'];
$cms_lang['group_css_file_22'] 		= $cms_lang['group_area_css_22'];
$cms_lang['group_css_file_29'] 		= $cms_lang['group_area_css_29'];
$cms_lang['group_css_file_30'] 		= $cms_lang['group_area_css_30'];

// area_js
$cms_lang['group_area_js']	 			= 'Area Design / Javascript';
$cms_lang['group_area_js_1']  			= 'view js-file';
$cms_lang['group_area_js_2']  			= 'create js-file';
$cms_lang['group_area_js_3']  			= 'edit js-file';
$cms_lang['group_area_js_5']  			= 'delete js-file';
$cms_lang['group_area_js_6']  			= 'set js-file rights';
$cms_lang['group_area_js_8']  			= 'download js-file';
$cms_lang['group_area_js_9']  			= 'upload js-file';
$cms_lang['group_area_js_13'] 			= 'import js-file';
$cms_lang['group_area_js_14'] 			= 'export js-file';
$cms_lang['group_js_file_1']  			= $cms_lang['group_area_js_1'];
$cms_lang['group_js_file_2']  			= $cms_lang['group_area_js_2'];
$cms_lang['group_js_file_3']  			= $cms_lang['group_area_js_3'];
$cms_lang['group_js_file_5']  			= $cms_lang['group_area_js_5'];
$cms_lang['group_js_file_6']  			= $cms_lang['group_area_js_6'];
$cms_lang['group_js_file_8']  			= $cms_lang['group_area_js_8'];
$cms_lang['group_js_file_9']  			= $cms_lang['group_area_js_9'];
$cms_lang['group_js_file_13'] 			= $cms_lang['group_area_js_13'];
$cms_lang['group_js_file_14'] 			= $cms_lang['group_area_js_14'];

// area_mod
$cms_lang['group_area_mod']	        = 'Area Design / Modules';
$cms_lang['group_area_mod_1']	        = 'view modules';
$cms_lang['group_area_mod_2']	        = 'create modules';
$cms_lang['group_area_mod_3']	        = 'edit modules';
$cms_lang['group_area_mod_4']	        = 'configure modules';
$cms_lang['group_area_mod_5']	        = 'delete modules';
$cms_lang['group_area_mod_6']	        = 'set modules rights';
$cms_lang['group_area_mod_7']	        = 'import modules';
$cms_lang['group_area_mod_8']	        = 'export modules';
$cms_lang['group_area_mod_9']	        = 'upload modules';
$cms_lang['group_area_mod_10']	        = 'download modules';
$cms_lang['group_area_mod_11']	        = 'access repository';
$cms_lang['group_area_mod_12']	        = 'update modul from repository';
$cms_lang['group_area_mod_13']	        = 'download modul from repository';
$cms_lang['group_area_mod_14']	        = 'import modul from repository';
$cms_lang['group_area_mod_15']	        = 'view dev-modules in repository';
$cms_lang['group_mod_1']	            = $cms_lang['group_area_mod_1'];
$cms_lang['group_mod_2']	            = $cms_lang['group_area_mod_2'];
$cms_lang['group_mod_3']	            = $cms_lang['group_area_mod_3'];
$cms_lang['group_mod_4']	            = $cms_lang['group_area_mod_4'];
$cms_lang['group_mod_5']	            = $cms_lang['group_area_mod_5'];
$cms_lang['group_mod_6']	            = $cms_lang['group_area_mod_6'];
$cms_lang['group_mod_7']	            = $cms_lang['group_area_mod_7'];
$cms_lang['group_mod_8']	            = $cms_lang['group_area_mod_8'];
$cms_lang['group_mod_10']	            = $cms_lang['group_area_mod_10'];
$cms_lang['group_mod_11']	            = $cms_lang['group_area_mod_11'];
$cms_lang['group_mod_12']	            = $cms_lang['group_area_mod_12'];
$cms_lang['group_mod_13']	            = $cms_lang['group_area_mod_13'];
$cms_lang['group_mod_14']	            = $cms_lang['group_area_mod_14'];
$cms_lang['group_mod_15']	            = $cms_lang['group_area_mod_15'];

// area_plug
$cms_lang['group_area_plug']	        = 'Area Administration / Plugins';
$cms_lang['group_area_plug_1']	        = 'view plugin';
$cms_lang['group_area_plug_2']	        = 'create plugin';
$cms_lang['group_area_plug_3']	        = 'edit plugin';
$cms_lang['group_area_plug_4']	        = 'configure plugin - client';
$cms_lang['group_area_plug_5']	        = 'delete plugin';
$cms_lang['group_area_plug_6']	        = 'set plugin rights';
$cms_lang['group_area_plug_7']	        = 'import plugin';
$cms_lang['group_area_plug_8']	        = 'export plugin';
$cms_lang['group_area_plug_9']	        = 'upload plugin';
$cms_lang['group_area_plug_10']	    = 'download plugin';
$cms_lang['group_area_plug_11']	    = 'access repository';
$cms_lang['group_area_plug_12']	    = 'update plugin from repository';
$cms_lang['group_area_plug_13']	    = 'download plugin from repository';
$cms_lang['group_area_plug_14']	    = 'import plugin from repository';
$cms_lang['group_area_plug_15']	    = 'view dev-plugins in repository';
$cms_lang['group_area_plug_16']	    = 'access plugin configuration';
$cms_lang['group_area_plug_17']	    = 'access plugin setup';
$cms_lang['group_area_plug_18']	    = 'configure plugin - common';
$cms_lang['group_plug_1']	            = $cms_lang['group_area_plug_1'];
$cms_lang['group_plug_2']	            = $cms_lang['group_area_plug_2'];
$cms_lang['group_plug_3']	            = $cms_lang['group_area_plug_3'];
$cms_lang['group_plug_4']	            = $cms_lang['group_area_plug_4'];
$cms_lang['group_plug_5']	            = $cms_lang['group_area_plug_5'];
$cms_lang['group_plug_6']	            = $cms_lang['group_area_plug_6'];
$cms_lang['group_plug_7']	            = $cms_lang['group_area_plug_7'];
$cms_lang['group_plug_8']	            = $cms_lang['group_area_plug_8'];
$cms_lang['group_plug_10']	            = $cms_lang['group_area_plug_10'];
$cms_lang['group_plug_11']	            = $cms_lang['group_area_plug_11'];
$cms_lang['group_plug_12']	            = $cms_lang['group_area_plug_12'];
$cms_lang['group_plug_13']	            = $cms_lang['group_area_plug_13'];
$cms_lang['group_plug_14']	            = $cms_lang['group_area_plug_14'];
$cms_lang['group_plug_15']	            = $cms_lang['group_area_plug_15'];
$cms_lang['group_plug_16']	            = $cms_lang['group_area_plug_16'];
$cms_lang['group_plug_17']	            = $cms_lang['group_area_plug_17'];
$cms_lang['group_plug_18']	            = $cms_lang['group_area_plug_18'];

$cms_lang['group_area_rep']	        = 'Area Administration / Repository';
$cms_lang['group_area_rep_1']	        = 'view repository';
$cms_lang['group_area_rep_2']	        = 'repository user';
$cms_lang['group_area_rep_3']	        = 'edit repository';
$cms_lang['group_area_rep_4']	        = 'configure repository';
$cms_lang['group_area_rep_5']	        = 'delete repository';
$cms_lang['group_area_rep_6']	        = 'set repository rights';
$cms_lang['group_area_rep_7']	        = 'import repository';
$cms_lang['group_area_rep_8']	        = 'export repository';
$cms_lang['group_area_rep_9']	        = 'upload repository';
$cms_lang['group_area_rep_10']	        = 'download repository';
$cms_lang['group_area_rep_11']	        = 'access repository';
$cms_lang['group_area_rep_12']	        = 'sign repository';
$cms_lang['group_area_rep_13']	        = 'check repository';
$cms_lang['group_rep_1']	            = $cms_lang['group_area_rep_1'];
$cms_lang['group_rep_2']	            = $cms_lang['group_area_rep_2'];
$cms_lang['group_rep_3']	            = $cms_lang['group_area_rep_3'];
$cms_lang['group_rep_4']	            = $cms_lang['group_area_rep_4'];
$cms_lang['group_rep_5']	            = $cms_lang['group_area_rep_5'];
$cms_lang['group_rep_6']	            = $cms_lang['group_area_rep_6'];
$cms_lang['group_rep_7']	            = $cms_lang['group_area_rep_7'];
$cms_lang['group_rep_8']	            = $cms_lang['group_area_rep_8'];
$cms_lang['group_rep_10']	            = $cms_lang['group_area_rep_10'];
$cms_lang['group_rep_11']	            = $cms_lang['group_area_rep_11'];
$cms_lang['group_rep_12']	            = $cms_lang['group_area_rep_12'];
$cms_lang['group_rep_13']	            = $cms_lang['group_area_rep_13'];

// area_upl
$cms_lang['group_area_upl']	 		= 'Area Content / Filemanager';
$cms_lang['group_area_upl_1']	 		= 'view folder';
$cms_lang['group_area_upl_2']	 		= 'create folder';
$cms_lang['group_area_upl_3']	 		= 'edit folder';
$cms_lang['group_area_upl_5']	 		= 'delete folder';
$cms_lang['group_area_upl_6']	 		= 'set folder rights';
$cms_lang['group_area_upl_8']	 		= 'download folder';
$cms_lang['group_area_upl_9']	 		= 'upload folder';
$cms_lang['group_area_upl_11']	 		= 'scan folder';
$cms_lang['group_area_upl_17']	 		= 'view file';
$cms_lang['group_area_upl_19']	 		= 'edit file';
$cms_lang['group_area_upl_21']	 		= 'delete file';
$cms_lang['group_area_upl_22']	 		= 'set file rights';
$cms_lang['group_area_upl_25']	 		= 'upload file';
$cms_lang['group_area_upl_24']	 		= 'download file';
$cms_lang['group_folder_1']	 		= $cms_lang['group_area_upl_1'];
$cms_lang['group_folder_2']	 		= $cms_lang['group_area_upl_2'];
$cms_lang['group_folder_3']	 		= $cms_lang['group_area_upl_3'];
$cms_lang['group_folder_5']	 		= $cms_lang['group_area_upl_5'];
$cms_lang['group_folder_6']	 		= $cms_lang['group_area_upl_6'];
$cms_lang['group_folder_8']	 		= $cms_lang['group_area_upl_8'];
$cms_lang['group_folder_9']	 		= $cms_lang['group_area_upl_9'];
$cms_lang['group_folder_11']	 		= $cms_lang['group_area_upl_11'];
$cms_lang['group_folder_17']	 		= $cms_lang['group_area_upl_17'];
$cms_lang['group_folder_19']	 		= $cms_lang['group_area_upl_19'];
$cms_lang['group_folder_21']	 		= $cms_lang['group_area_upl_21'];
$cms_lang['group_folder_22']	 		= $cms_lang['group_area_upl_22'];
$cms_lang['group_folder_24']	 		= $cms_lang['group_area_upl_24'];
$cms_lang['group_folder_25']	 		= $cms_lang['group_area_upl_25'];
$cms_lang['group_file_17']	 			= $cms_lang['group_area_upl_17'];
$cms_lang['group_file_19']	 			= $cms_lang['group_area_upl_19'];
$cms_lang['group_file_21']	 			= $cms_lang['group_area_upl_21'];
$cms_lang['group_file_22']	 			= $cms_lang['group_area_upl_22'];
$cms_lang['group_file_24']	 			= $cms_lang['group_area_upl_24'];
$cms_lang['group_file_25']	 			= $cms_lang['group_area_upl_25'];

// area_tpl
$cms_lang['group_area_tpl']	= 'Area Design / Templates';
$cms_lang['group_area_tpl_1']	= 'view template';
$cms_lang['group_area_tpl_2']	= 'create template';
$cms_lang['group_area_tpl_3']	= 'edit template';
$cms_lang['group_area_tpl_5']	= 'delete template';
$cms_lang['group_area_tpl_6']	= 'set template rights';
$cms_lang['group_area_tpl_12']	= 'define starttemplate';
$cms_lang['group_tpl_1']	 	= $cms_lang['group_area_tpl_1'];
$cms_lang['group_tpl_3']	 	= $cms_lang['group_area_tpl_3'];
$cms_lang['group_tpl_5']	 	= $cms_lang['group_area_tpl_5'];
$cms_lang['group_tpl_6']	 	= $cms_lang['group_area_tpl_6'];
$cms_lang['group_tpl_12']	 	= $cms_lang['group_area_tpl_12'];

//area users
$cms_lang['group_area_user']	 = 'Bereich Administration / Benutzer';

//area groups
$cms_lang['group_area_group']	 = 'Bereich Administration / Gruppen';

// area_clients
$cms_lang['group_area_clients']	 = 'Area Administration / Projects';
$cms_lang['group_area_clients_1']	 = 'view project';
$cms_lang['group_area_clients_2']	 = 'create project';
$cms_lang['group_area_clients_3']	 = 'edit project';
$cms_lang['group_area_clients_4']	 = 'configure project';
$cms_lang['group_area_clients_5']	 = 'delete project';
$cms_lang['group_area_clients_6']	 = 'set project rights';
$cms_lang['group_area_clients_17']	 = 'view language';
$cms_lang['group_area_clients_18']	 = 'create language';
$cms_lang['group_area_clients_19']	 = 'edit language';
$cms_lang['group_area_clients_21']	 = 'configure language';
$cms_lang['group_area_clients_22']	 = 'delete language';
$cms_lang['group_area_clients_28']	 = 'define startlang';

$cms_lang['group_clients_1']	 = $cms_lang['group_area_clients_1'];
$cms_lang['group_clients_3']	 = $cms_lang['group_area_clients_3'];
$cms_lang['group_clients_4']	 = $cms_lang['group_area_clients_4'];
$cms_lang['group_clients_5']	 = $cms_lang['group_area_clients_5'];
$cms_lang['group_clients_6']	 = $cms_lang['group_area_clients_6'];
$cms_lang['group_clients_17']	 = $cms_lang['group_area_clients_17'];
$cms_lang['group_clients_18']	 = $cms_lang['group_area_clients_18'];
$cms_lang['group_clients_19']	 = $cms_lang['group_area_clients_19'];
$cms_lang['group_clients_21']	 = $cms_lang['group_area_clients_21'];
$cms_lang['group_clients_22']	 = $cms_lang['group_area_clients_22'];
$cms_lang['group_clients_28']	 = $cms_lang['group_area_clients_28'];

$cms_lang['group_clientlangs_17']	= $cms_lang['group_area_clients_17'];
$cms_lang['group_clientlangs_19']	= $cms_lang['group_area_clients_19'];
$cms_lang['group_clientlangs_21']	= $cms_lang['group_area_clients_21'];
$cms_lang['group_clientlangs_22']	= $cms_lang['group_area_clients_22'];
$cms_lang['group_clientlangs_28']	= $cms_lang['group_area_clients_28'];

// area_settings
$cms_lang['group_area_settings']	= 'Area Administration / System';
$cms_lang['group_area_settings_1']	= 'view/edit system settings';

// area_plugin
$cms_lang['group_area_plugin']		= 'Area Plugins';
?>
