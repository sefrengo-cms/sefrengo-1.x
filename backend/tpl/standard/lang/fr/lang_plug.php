<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

// Plugin

$cms_lang['plug_action']				= 'Actions';
$cms_lang['plug_edit']					= '&Eacute;diter l\'extension';
$cms_lang['plug_config']				= 'Configurer l\'extension';
$cms_lang['plug_config_change']		= 'Changer la configuration de l\'extension';
$cms_lang['plug_export']				= 'Exporter l\'extension';
$cms_lang['plug_defaultname']			= 'Nouvelle extension';
$cms_lang['plug_delete']				= 'Supprimer l\'extension';
$cms_lang['plug_pluginname']			= 'Nom de l\'extension';
$cms_lang['plug_verbosename']			= 'Alternative';
$cms_lang['plug_konfiguration']		= 'Configuration';
$cms_lang['plug_version']				= 'Version';
$cms_lang['plug_cat']			   		= 'Cat&eacute;gorie';
$cms_lang['plug_description']			= 'Description';
$cms_lang['plug_import']				= 'Importer l\'extension';
$cms_lang['plug_input']				= 'Configuration';
$cms_lang['plug_new']					= 'Nouvelle extension';
$cms_lang['plug_noplugins']			= 'Il n\'y a pas d\'extension';
$cms_lang['plug_output']				= 'Sortie frontale';
$cms_lang['plug_duplicate']			= 'Dupliquer l\'extension';
$cms_lang['plug_upload']				= 'T&eacute;l&eacute;charger vers le serveur l\'extension';
$cms_lang['plug_download']	   			= 'T&eacute;l&eacute;charger l\'extension';
$cms_lang['plug_repository']			= 'R&eacute;f&eacute;rentiel-Module d\'extension';
$cms_lang['plug_repository_install']	= 'Installer l\'extension du r&eacute;f&eacute;rentiel';
$cms_lang['plug_repository_upload']	= 'T&eacute;l&eacute;charger vers le serveur l\'extension dans le r&eacute;f&eacute;rentiel';
$cms_lang['plug_repository_update']	= 'Mise &agrave; jour de l\'extension du r&eacute;f&eacute;rentiel';
$cms_lang['plug_repository_use_dev']	= 'Utiliser l\'extension de d&eacute;velopement du r&eacute;f&eacute;rentiel';
$cms_lang['plug_repository_noupdate']	= 'L\'extension est &agrave; jour';
$cms_lang['plug_repository_notonline']	= 'Le r&eacute;f&eacute;rentiel est inacessible';
$cms_lang['plug_folder']				= 'R&eacute;pertoire de l\'extension';
$cms_lang['plug_repository_download']	= 'T&eacute;l&eacute;charger l\'extension du r&eacute;f&eacute;rentiel';
$cms_lang['plug_repository_import']	= 'Importer l\'extension du r&eacute;f&eacute;rentiel';
$cms_lang['plug_folder_import']		= 'Importer l\'extension du r&eacute;pertoire';
$cms_lang['plug_author']		        = 'Auteur';
$cms_lang['plug_sql_install']			= 'M&eacute;ta - Installation';
$cms_lang['plug_sql_uninstall']		= 'M&eacute;ta - D&eacute;installation';
$cms_lang['plug_sql_update']			= 'M&eacute;ta - Mise &agrave; jour';
$cms_lang['plug_new_sql']			    = '&Eacute;diter Meta';
$cms_lang['plug_rebuild_sql']			= 'Installer de nouveau les informations m&eacute;ta';
$cms_lang['plug_root_name']		    = 'R&eacute;pertoire racine';
$cms_lang['plug_index_file']			= 'Fichier de recherche';
$cms_lang['plug_config_all']           = 'Accepter les r&eacute;glages et changement pour tous les projets';
$cms_lang['plug_config_return']        = 'Remettre l\'extension aux r&eacute;glages par d&eacute;faut';
$cms_lang['plug_config_nosetting']     = 'aucune valeur existante!';
$cms_lang['plug_config_settings']      = 'Valeur';
$cms_lang['plug_import_path']	        = 'Importer le r&eacute;pertoire';
$cms_lang['plug_confirm_update']	    = 'Une version plus r&eacute;cente a &eacute;t&eacute; trouv&eacute;!\r\nModule d\'extension-%s Version:%s\r\nVoulez-vous le mettre &agrave; jour?';
$cms_lang['plug_confirm_reinstall']	= 'Module d\'extension-%s Version:%s\r\nexiste d&eacute;j&agrave;!\r\nD&eacute;sirez-vous r&eacute;installer l\'extension?';

// 16xx = Plugin
$cms_lang['err_1600']	   				= 'Erreur de l\'extension. La function n\'a pas &eacute;t&eacute; ex&eacute;cut&eacute;!';
$cms_lang['err_1601']					= 'L\'extension est utilis&eacute;. Suppression impossible.';
$cms_lang['err_1602']					= '<font color="black">L\'extension a &eacute;t&eacute; import&eacute; avec succ&egrave;s.</font>';
$cms_lang['err_1603']					= 'Aucun fichier *.cmsplug valide a &eacute;t&eacute; t&eacute;l&eacute;charg&eacute;';
$cms_lang['err_1604']					= 'Aucun fichier n\'a &eacute;t&eacute; indiquer pour le t&eacute;l&eacute;chargement';
$cms_lang['err_1605']					= '<font color="black">L\'extension a &eacute;t&eacute; mis &agrave; jour avec succ&egrave;s du r&eacute;f&eacute;rentiel.</font>';
$cms_lang['err_1606']					= '<font color="black">L\'extension a &eacute;t&eacute; import&eacute; avec succ&egrave;s du r&eacute;f&eacute;rentiel.</font>';
$cms_lang['err_1607']					= '<font color="black">L\'extension SQL a &eacute;t&eacute; ex&eacute;cut&eacute; avec succ&egrave;s.</font>';
$cms_lang['err_1608']					= '<font color="black">L\'extension a &eacute;t&eacute; t&eacute;l&eacute;charg&eacute; avec succ&egrave;s sur le serveur.</font>';
$cms_lang['err_1609']					= '<font color="black">L\'extension a &eacute;t&eacute; install&eacute; avec succ&egrave;s.</font>';
$cms_lang['err_1610']					= '<font color="black">L\'extension a &eacute;t&eacute; effac&eacute; avec succ&egrave;s.</font>';
$cms_lang['err_1611']					= '<font color="black">L\'extension a &eacute;t&eacute; d&eacute;sinstall&eacute; avec succ&egrave;s.</font>';
$cms_lang['err_1612']					= '<font color="black">L\'extension a &eacute;t&eacute; sauvegard&eacute; avec succ&egrave;s.</font>';
$cms_lang['err_1613']					= 'Param&egrave;tre erron&eacute;. La function n\'a pas &eacute;t&eacute; ex&eacute;cut&eacute;!';
$cms_lang['err_1614']					= '<font color="black">Le module a &eacute;t&eacute; export&eacute; avec succ&egrave;s.</font>';
$cms_lang['err_1615']					= 'Erreur du fichier TAR. La function n\'a pas &eacute;t&eacute; ex&eacute;cut&eacute;!';
$cms_lang['err_1616']					= 'Erreur dans le module d\'extension: <b>%s</b>';
$cms_lang['err_1617']					= 'Mise &agrave; jour en cours';
$cms_lang['err_1618']					= 'Interrompre l\'action';
$cms_lang['err_1619']					= 'R&eacute;installation en cours';
$cms_lang['err_1620']	   				= '- indiquer le nom du nouveau module d\'extension!';
$cms_lang['err_1621']					= 'Erreur du fichier TAR. L\'extension n&eacute;cessaire n\'a pas &eacute;t&eacute; charg&eacute;!';
?>
