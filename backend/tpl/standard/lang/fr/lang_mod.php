<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

// Modul
$cms_lang['mod_modul']					= 'Module';
$cms_lang['mod_parent']				= 'Module parent';
$cms_lang['mod_action']				= 'Actions';
$cms_lang['mod_edit']					= '&Eacute;diter le module';
$cms_lang['mod_config']				= 'Configurer le module';
$cms_lang['mod_config_save']			= 'Garder la configuration du module';
$cms_lang['mod_update_save']			= 'Mise &agrave; jour du module';
$cms_lang['mod_reinstall_save']		= 'Remettre en place le module';
$cms_lang['mod_config_change']			= 'Changer la configuration du module';
$cms_lang['mod_export']				= 'Exporter le module';
$cms_lang['mod_defaultname']			= 'Nouveau module';
$cms_lang['mod_delete']				= 'Supprimer le module';
$cms_lang['mod_modulename']			= 'Nom du module';
$cms_lang['mod_verbosename']			= 'Alternative';
$cms_lang['mod_konfiguration']			= 'Configuration';
$cms_lang['mod_version']				= 'Version';
$cms_lang['mod_cat']			   		= 'Cat&eacute;gorie';
$cms_lang['mod_description']			= 'Description';
$cms_lang['mod_import']				= 'Importer un module';
$cms_lang['mod_input']					= 'Configuration';
$cms_lang['mod_new']					= 'Nouveau module';
$cms_lang['mod_nomodules']				= 'Il n\'y a pas de modules.';
$cms_lang['mod_output']				= 'Sortie frontale';
$cms_lang['mod_duplicate']				= 'Dupliquer le module';
$cms_lang['mod_upload']				= 'T&eacute;l&eacute;charger vers le serveur le module';
$cms_lang['mod_download']	   			= 'T&eacute;l&eacute;charger le module';
$cms_lang['mod_xmlimport']				= 'Importer un module XML';
$cms_lang['mod_xmlexport']				= 'Exporter le module comme XML';

$cms_lang['mod_repository']			= 'R&eacute;f&eacute;rentiel du module';
$cms_lang['mod_repository_install']	= 'Installer le module du r&eacute;f&eacute;rentiel';
$cms_lang['mod_repository_upload']		= 'T&eacute;l&eacute;chargement vers le serveur le module dans le r&eacute;f&eacute;rentiel';
$cms_lang['mod_repository_update']		= 'Mise &agrave; jour du module du r&eacute;f&eacute;rentiel';
$cms_lang['mod_repository_use_dev']	= 'Utiliser le module de d&eacute;velopement du r&eacute;f&eacute;rentiel';
$cms_lang['mod_repository_noupdate']	= 'Le module est &agrave; jour';
$cms_lang['mod_repository_notonline']	= 'Le r&eacute;f&eacute;rentiel est inacessible';
$cms_lang['mod_local_update']	     	= 'Mise &agrave; jour du module';
$cms_lang['mod_database']				= 'Base de donn&eacute;s du module';
$cms_lang['mod_repository_download']	= 'T&eacute;l&eacute;charger le module du r&eacute;f&eacute;rentiel';
$cms_lang['mod_repository_import']		= 'Importer le module du r&eacute;f&eacute;rentiel';
$cms_lang['mod_database_import']		= 'T&eacute;l&eacute;charger le module du la base de donn&eacute;es';
$cms_lang['mod_author']		        = 'Auteur';
$cms_lang['mod_sql_install']			= 'Installation - SQL';
$cms_lang['mod_sql_uninstall']			= 'D&eacute;installation - SQL';
$cms_lang['mod_sql_update']			= 'Mise &agrave; jour - SQL';
$cms_lang['mod_new_sql']			    = '&Eacute;diter la base SQL';
$cms_lang['mod_rebuild_sql']			= 'Installer de nouveau la base SQL';
$cms_lang['mod_no_wedding']            = 'Dissocier le Module de ' . $cms_lang['mod_parent'] . '' . "&#10;" . '(%s - Version: %s)';
$cms_lang['mod_config_all']            = 'Accepter les r&eacute;glages pour tous les mod&egrave;les / fichiers / pages qui utilisent ce module';
$cms_lang['mod_config_return']         = 'Remettre le module aux r&eacute;glages par d&eacute;faut';
$cms_lang['mod_s_overide']             = 'Ignorer les erreurs et sauvergarder le module dans -%s';
$cms_lang['mod_confirm_update_config'] = 'Mise &agrave; jour de la configuration du module et remettre par d&eacute;faut tous les r&eacute;glages pour tous les mod&egrave;les / fichiers / pages?';
$cms_lang['mod_confirm_reinstall']	    = '<b>Le module existe d&eacute;j&agrave;!</b><br>Quelle module d&eacute;sirez-vous r&eacute;installer?';
$cms_lang['mod_confirm_update']	    = '<b>Message de mise &agrave; jour!</b><br>Quel module doit &ecirc;tre mis &agrave; jour?';
$cms_lang['mod_confirm_lupdate']	    = '<b>Une mise &agrave; jour a &eacute;t&eacute; d&eacute;marer!</b><br>Comment le module doit-il &ecirc;tre mis &agrave; jour?';
$cms_lang['mod_confirm_new']	        = 'Sauvergarder le module comme \'nouveau Module\'?';

// 04xx = Modul
$cms_lang['err_0400']	   				= 'Erreur du module. La function n\'a pas &eacute;t&eacute; ex&eacute;cut&eacute;!';
$cms_lang['err_0401']					= 'Le module est utilis&eacute;. Suppression impossible.';
$cms_lang['err_0402']					= '<font color="black">Le module a &eacute;t&eacute; copi&eacute; avec succ&egrave;s.</font>';
$cms_lang['err_0403']					= 'Aucun fichier *.cmsmod valide a &eacute;t&eacute; t&eacute;l&eacute;charg&eacute;';
$cms_lang['err_0404']					= 'Aucun fichier n\'a &eacute;t&eacute; indiquer pour le t&eacute;l&eacute;chargement';
$cms_lang['err_0405']					= '<font color="black">Le module a &eacute;t&eacute; mis &agrave; jour avec succ&egrave;s du r&eacute;f&eacute;rentiel.</font>';
$cms_lang['err_0406']					= '<font color="black">Le module a &eacute;t&eacute; import&eacute; avec succ&egrave;s du r&eacute;f&eacute;rentiel.</font>';
$cms_lang['err_0407']					= '<font color="black">Le module SQL a &eacute;t&eacute; ex&eacute;cut&eacute; avec succ&egrave;s.</font>';
$cms_lang['err_0408']					= '<font color="black">Le module a &eacute;t&eacute; t&eacute;l&eacute;charg&eacute; avec succ&egrave;s sur le serveur.</font>';
$cms_lang['err_0409']					= '<font color="black">Le module a &eacute;t&eacute; install&eacute; avec succ&egrave;s.</font>';
$cms_lang['err_0410']					= '<font color="black">Le module a &eacute;t&eacute; effac&eacute; avec succ&egrave;s.</font>';
$cms_lang['err_0411']					= '<font color="black">Le module a &eacute;t&eacute; d&eacute;sinstall&eacute; avec succ&egrave;s.</font>';
$cms_lang['err_0412']					= '<font color="black">Le module a &eacute;t&eacute; sauvegard&eacute; avec succ&egrave;s.</font>';
$cms_lang['err_0413']					= 'Param&egrave;tre erron&eacute;. La function n\'a pas &eacute;t&eacute; ex&eacute;cut&eacute;!';
$cms_lang['err_0414']					= '<font color="black">Le module a &eacute;t&eacute; export&eacute; avec succ&egrave;s.</font>';
$cms_lang['err_0415']					= '<font color="black">Le module a &eacute;t&eacute; mis &agrave; jour avec succ&egrave;s.</font>';
$cms_lang['err_0416']					= 'Erreur dans le module-%s, Ligne: <b>%s</b>';
$cms_lang['err_0417']					= 'Mise &agrave; jour en cours';
$cms_lang['err_0418']					= 'Interrompre l\'action';
$cms_lang['err_0419']					= 'Nouvelle installation en cours';
$cms_lang['err_0420']					= '<font color="black">Le module a &eacute;t&eacute; mis &agrave; jour avec succ&egrave;s.</font>';
$cms_lang['err_0421']					= '<font color="black">Le module a &eacute;t&eacute; r&eacute;install&eacute; avec succ&egrave;s.</font>';
$cms_lang['err_0422']					= '<font color="black">Le module a &eacute;t&eacute; mis &agrave; jour et r&eacute;install&eacute; avec succ&egrave;s.</font>';
$cms_lang['err_0423']					= 'Param&egrave;tre erron&eacute;. Erreur Parse du module!';
$cms_lang['err_0424']					= 'Param&egrave;tre erron&eacute;. Module sans contenu';
$cms_lang['err_0425']	   				= '- indiquer le nom du nouveau module!';
?>
