<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

$cms_lang['set_session_frontend_lifetime']		= 'Dur&eacute;e de vie de la session de l\'interface frontal';
$cms_lang['set_session_frontend_enabled']		= 'Support de la session de l\'interface frontal';

$cms_lang['setuse_pathes']						= 'Chemins et fichiers';
$cms_lang['setuse_path']						= 'Chemin de l\'interface frontal';
$cms_lang['setuse_html_path']					= 'Chemin HTML de l\'interface frontal';
$cms_lang['setuse_contentfile']				= 'Nom du fichier de l\'interface frontal';
$cms_lang['setuse_space']						= 'Caract&egrave;re de remplacement pour images';
$cms_lang['setuse_general']					= 'Pr&eacute;f&eacute;rences g&eacute;n&eacute;rales';
$cms_lang['setuse_publish']					= 'Publier les changements seulement apr&egrave;s la mise &agrave; jour';
$cms_lang['setuse_edit_mode']					= 'Mode d\'&eacute;dition 0=Visuelle 1=Vis.-Cont. 2=Cont.-Vis. 3=Contenu';
$cms_lang['setuse_wysiwyg_applet']				= 'Applet WYSIWYG 0=jamais, 1=pas IE, 2=toujours, 3=pas IE + Mozilla';
$cms_lang['setuse_default_layout']				= 'Mod&egrave;le de mise en page';
$cms_lang['setuse_errorpage']					= 'idcatside pour page d\'erreur 404';
$cms_lang['setuse_loginpage']					= 'idcatside pour d&eacute;lai de la page de login';
$cms_lang['setuse_cache']						= 'Ant&eacute;m&eacute;moire de l\'interface frontal';
$cms_lang['setuse_session_frontend_domain']		= 'Session frontend domain';
$cms_lang['setuse_url_rewrite']				= 'Support Apache mod_rewrite ';
$cms_lang['setuse_url_langid_in_defaultlang']		= 'ID der Standardsprache in URL zeigen';
$cms_lang['setuse_url_rewrite_suffix']				= 'URL Rewrite Seiten Suffix';
$cms_lang['setuse_url_rewrite_basepath']		= 'Basepath bei UrlRewrite=2. Variablen: {%http_host}';
$cms_lang['setuse_url_rewrite_404']				= '404 Fehlerseite bei UrlRewrite=2. Variablen: {%http_host}, {%request_uri} oder idcatside';
$cms_lang['setuse_session_disabled_useragents']		= 'Useragents f&uuml;r die keine Session erzeugt wird (eine pro Zeile)';
$cms_lang['setuse_session_disabled_ips']			= 'IPs f&uuml;r die keine Session erzeugt wird (eine pro Zeile)';
$cms_lang['setuse_manipulate_output']			= 'Manipulation des donn&eacute;es de sortie';
$cms_lang['setuse_upl_path']					= 'R&eacute;pertoire de d&eacute;part du gestionnaire de fichiers';
$cms_lang['setuse_upl_htmlpath']				= 'Chemin HTML du r&eacute;pertoire de d&eacute;part';
$cms_lang['setuse_filemanager']				= 'Pr&eacute;f&eacute;rences du gestionnaire de fichiers';
$cms_lang['setuse_forbidden']					= 'Extensions de fichier interdits';
$cms_lang['setuse_thumb_size']					= 'Tailles des vignettes';
$cms_lang['setuse_thumb_aspectratio']			= 'Garder les proportions';
$cms_lang['setuse_upl_addon']					= 'Cr&eacute;er, si possible, les vignettes';
$cms_lang['setuse_thumb_aspectratio']			= 'Garder les proportions 0=non, 1=oui, 2=balances de y , 3=balances de x ';
$cms_lang['setuse_thumb_ext']					= 'Extensions de fichier pour les vignettes g&eacute;n&eacute;r&eacute;s';
$cms_lang['setuse_upl_addon']					= 'Cr&eacute;er (si possible) les vignettes pour';
$cms_lang['setuse_fm_delete_ignore_404']		= 'Ignorer les messages d\'erreur lors <br>de la suppression de fichiers manquants   1=oui/0=non';
$cms_lang['setuse_remove_files_404']			= 'Supprimer les fichiers vide lors de la synchronisation 1=oui/0=non';
$cms_lang['setuse_css']						= 'Pr&eacute;f&eacute;rences de l\'&eacute;diteur CSS';
$cms_lang['setuse_css_sort_original']			= 'Classement des CSS (0=Ordre alphab&eacute;tique, 1=Ordre de sauvegarde)';
$cms_lang['setuse_csschecking']				= 'V&eacute;rifier la validit&eacute; des r&egrave;gles CSS 1=oui/0=non';
$cms_lang['setuse_css_ignore_error_rules']		= 'Sauvergarder les r&egrave;gles CSS invalides dans un fichier 2=oui/0=non';
$cms_lang['setuse_remove_empty_directories']	= 'Supprimer les r&eacute;pertoires vides lors de la synchronisation 1=oui/0=non';

$cms_lang['set_meta']							= 'Pr&eacute;configurer les m&eacute;ta-donn&eacute;es';
$cms_lang['set_meta_title']				= 'Titre de la page';
$cms_lang['set_meta_other']				= 'D\'autres méta-valeurs';
$cms_lang['set_meta_description']				= 'Description de la page';
$cms_lang['set_meta_keywords']					= 'Termes de recherche';
$cms_lang['set_meta_robots']					= 'Instruction pour moteur de recherche';

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
?>
