<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

$cms_lang['set_action']					= 'Actions';
$cms_lang['set_general']				= 'R&eacute;glages g&eacute;n&eacute;rales';
$cms_lang['set_submit']					= 'Sauvegarder';
$cms_lang['set_cancel']					= 'Annuler';
$cms_lang['set_edit']					= '&Eacute;diter';
$cms_lang['set_gzip']					= 'Compresser toutes les pages avec GZIP';
$cms_lang['set_backend_cache']	  			= 'Interdire aux navigateurs de m&eacute;moriser l\'ant&eacute;m&eacute;moire des pages du syst&egrave;me dorsal';
$cms_lang['set_chmod_enable']				= 'Appliquer CHMOD aux t&eacute;l&eacute;chargement vers le serveur 0=non, 1=oui';
$cms_lang['set_chmod_value']				= 'Valeur de base CHMOD (en octal, p.ex. 777)';
$cms_lang['set_gzip_enable']				= 'Appliquer la compression pour les t&eacute;l&eacute;chargements 0=non, 1=oui';
$cms_lang['set_manipulate_output']			= 'Manipuler la sortie';
$cms_lang['set_cms_path']				= 'Chemin vers le syst&egrave;me dorsal';
$cms_lang['set_html_path']				= 'Chemin HTML vers le syst&egrave;me dorsal';
$cms_lang['set_filebrowser']				= 'Gestionnaire de fichiers';
$cms_lang['set_backend_lang']				= 'Langue du syst&egrave;me dorsal';
$cms_lang['set_skin']					= 'Habillage';
$cms_lang['set_image']					= 'R&eacute;glages graphiques';
$cms_lang['set_image_mode']				= "Choisir pilote 'GD', 'IM', 'Imagick', 'NetPBM'";
$cms_lang['set_path_imagelib']				= 'Chemin pilote';

$cms_lang['set_paging_items_per_page'] = 'Nombre de lignes affich&eacute;es par page, si le Paging est support&eacute;';


$cms_lang['set_logs']					= 'Fichiers journaux';
$cms_lang['set_log_enabled']				= 'Fichiers journaux activ&eacute;s';
$cms_lang['set_log_prunetime']				= 'Supprimer automatiquement les fichiers journaux apr&egrave;s X jours';
$cms_lang['set_log_entries']				= 'Entr&eacute;es par page';
$cms_lang['set_time']					= 'Donn&eacute;es du temps';
$cms_lang['set_sidelock_time']				= 'Verrouillage d\'article  pendant l\'&eacute;dition en minutes';
$cms_lang['set_FormatDate']				= 'Format de date';
$cms_lang['set_FormatTime']				= 'Base horaire';
$cms_lang['set_session_backend_lifetime']		= 'Temps de vie d\'une session du syst&egrave;me dorsal';
$cms_lang['set_session_backend_domain']		= 'session backend domain';
$cms_lang['set_stat_enabled']				= 'Statistiques activ&eacute;es';

$cms_lang['set_repository']				= 'Configurer le r&eacute;f&eacute;rentiel';
$cms_lang['set_repository_enabled']			= 'Utiliser le r&eacute;f&eacute;rentiel';
$cms_lang['set_repository_server']			= 'Serveur du r&eacute;f&eacute;rentiel';
$cms_lang['set_repository_updatetime']			= 'Intervalle de mise &agrave; jour';
$cms_lang['set_repository_path']			= 'Service du r&eacute;f&eacute;rentiel';
$cms_lang['set_repository_pingtime']			= 'Intervalle ping';
$cms_lang['set_repository_show_up2date']		= 'Afficher le status de mise &agrave; jour du r&eacute;f&eacute;rentiel';
$cms_lang['set_repository_show_offline']		= 'Afficher le status de mode autonome du r&eacute;f&eacute;rentiel';
$cms_lang['set_auto_repair_dependency']		= 'R&eacute;paration automatique des d&eacute;pendances du r&eacute;f&eacute;rentiel';

$cms_lang['set_db_cache']				= 'Configurer l\'ant&eacute;m&eacute;moire de la base de donn&eacute;es';
$cms_lang['set_db_cache_enabled']			= 'Utiliser l\'ant&eacute;m&eacute;moire de la base de donn&eacute;es';
$cms_lang['set_db_cache_name']				= 'Nom de l\'ant&eacute;m&eacute;moire de la base de donn&eacute;es';
$cms_lang['set_db_cache_groups']			= 'Configurer les groupes d\'ant&eacute;m&eacute;moire (en sec.)';
$cms_lang['set_db_cache_group_default']			= 'Groupe d\'ant&eacute;m&eacute;moire "Par d&eacute;faut"';
$cms_lang['set_db_cache_group_standard']		= 'Groupe d\'ant&eacute;m&eacute;moire "Standard"';
$cms_lang['set_db_cache_group_frontend']		= 'Groupe d\'ant&eacute;m&eacute;moire "Frontal"';
$cms_lang['set_db_cache_items']			= 'Configurer l\'ant&eacute;m&eacute;moire de l\'article  (en sec.)';
$cms_lang['set_db_cache_item_tree']		= 'L\'ant&eacute;m&eacute;moire de l\'article "Fichier frontal & structure de la page"';
$cms_lang['set_db_cache_item_content']		= 'L\'ant&eacute;m&eacute;moire de l\'article "Frontal-Contenu de la page"';

$cms_lang['set_db_optimice_tables']		    = 'Configurer l\'optimisation de la base de donn&eacute;es';
$cms_lang['set_db_optimice_tables_enable']		= 'Utiliser l\'optimisation de la base de donn&eacute;es';
$cms_lang['set_db_optimice_tables_time']		= 'Intervalle de l\'optimisation de la base de donn&eacute;es';
$cms_lang['set_db_optimice_tables_lastrun']	= 'Dernier passage de l\'optimisation de la base de donn&eacute;es';
?>
