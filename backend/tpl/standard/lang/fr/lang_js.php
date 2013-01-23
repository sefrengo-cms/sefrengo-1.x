<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

	include_once($cfg_cms['cms_path'] .'tpl/standard/lang/'.$cfg_cms['backend_lang'].'/lang_upl.php');

$cms_lang['js_action']					= 'Actions';
$cms_lang['js_cancel_form']			= 'Annuler l\'entr&eacute;e, retour &agrave; la derni&egrave;re vue';
$cms_lang['js_created']				= 'Cr&eacute;&eacute; le';
$cms_lang['js_description']			= 'Description';
$cms_lang['js_editor']					= 'Redacteur';
$cms_lang['js_filename']				= 'Nom de fichier';
$cms_lang['js_file_content']			= 'Code Javascript';
$cms_lang['js_file_delete']			= 'Supprimer le fichier';
$cms_lang['js_file_delete_confirm']	= 'Confirmez l\'effacement du fichier';
$cms_lang['js_file_delete_cancel']		= 'Annuler l\'effacement du fichier';
$cms_lang['js_file_duplicate']			= 'Dupliquer le fichier';
$cms_lang['js_file_edit']				= 'Editer le fichier';
$cms_lang['js_file_new']				= 'Nouveau fichier';
$cms_lang['js_fileimport']				= 'T&eacute;l&eacute;chargement vers le serveur fichier Javascript';
$cms_lang['js_import']					= 'Importer un fichier';
$cms_lang['js_export']					= 'Exporter un fichier';
$cms_lang['js_lastmodified']			= 'Modifi&eacute; le';
$cms_lang['js_nofile']					= 'Il n\'y a pas de fichiers Javascript.';
$cms_lang['js_nofiles']				= 'Il n\'y a pas de fichiers Javascript.';
$cms_lang['js_submit_form']			= 'Sauvegarder les changements';
$cms_lang['js_file_download']         	= 'T&eacute;l&eacute;charger le fichier';
$cms_lang['js_edit_rights']         	= '&Eacute;diter les droits';

// 12xx = JS
$cms_lang['err_1201']					= 'Le nom du fichier Javascript est erron&eacute; ou n\'est pas sp&eacute;cifi&eacute;.';
$cms_lang['err_1202']					= 'Il y a d&eacute;j&agrave; un fichier Javascript avec ce nom. Veuillez choisir un autre nom.';
$cms_lang['err_1203']					= 'Le fichier Javascript n\'a pas pu &ecirc;tre cr&eacute;&eacute;.';
$cms_lang['err_1204']					= 'Le fichier Javascript n\'existe pas.';
$cms_lang['err_1205']					= 'Le fichier Javascript n\'a pas pu &ecirc;tre export&eacute;.';
$cms_lang['err_1206']					= 'Le fichier Javascript a &eacute;t&eacute; export&eacute; avec succ&egrave;s.';
$cms_lang['err_1207']					= 'Le fichier Javascript n\'a pas pu &ecirc;tre import&eacute;.';
$cms_lang['err_1208']					= $cms_lang['err_1207']. ' Pas de donn&eacute;es trouv&eacute;es.';
$cms_lang['err_1209']					= $cms_lang['err_1207']. ' Il y a d&eacute;j&agrave; un fichier avec ce nom.';
$cms_lang['err_1210']					= 'Le fichier Javascript a &eacute;t&eacute; import&eacute; avec succ&egrave;s.';
$cms_lang['err_1211']					= 'Le fichier Javascript est utilis&eacute;. Suppression impossible.';
$cms_lang['err_1212']					= 'Le fichier Javascript n\'a pas pu &ecirc;tre d&eacute;termin&eacute;. Suppression impossible.';
$cms_lang['err_1213']					= $cms_lang['err_1205']. ' Pas de donn&eacute;es trouv&eacute;es.';
$cms_lang['err_1214']					= $cms_lang['err_1205']. ' Il y a d&eacute;j&agrave; un fichier avec ce nom.';
$cms_lang['err_1215']					= 'Le fichier Javascript n\'a pas pu &ecirc;tre actualis&eacute;! Veuillez v&eacute;rifier les droits.';
$cms_lang['err_1216']				   	= 'Le fichier Javascript n\'a pas pu &ecirc;tre supprim&eacute;! Veuillez v&eacute;rifier les droits.';
$cms_lang['err_1217']				   	= 'T&eacute;l&eacute;chargement vers le serveur du fichier Javascript a &eacute;chou&eacute;. Le contenu n\'a pas &eacute;t&eacute; &eacute;cris dans la base de donn&eacute;es.';
$cms_lang['err_1218']					= 'Le fichier Javascript n\'a pas pu &ecirc;tre mis &agrave; jour!';
?>
