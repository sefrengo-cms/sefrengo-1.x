<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

	include_once($cfg_cms['cms_path'] .'tpl/standard/lang/'.$cfg_cms['backend_lang'].'/lang_user.php');

	$cms_lang['group_on']					= 'Activer le groupe';
	$cms_lang['group_off']					= 'D&eacute;sactiver le groupe';
	$cms_lang['group_back']	   		    = 'Retour';
	$cms_lang['group_nocat']       		= 'Cette langue n\'a pas encore de structure de dossiers.';
	$cms_lang['group_langconfig']		    = 'Configurer les droits de cette langue';
	$cms_lang['group_langon']		      	= 'Ajouter une langue';
	$cms_lang['group_langoff']		      	= 'Supprimer une langue';
	$cms_lang['group_edit']				= '&Eacute;diter le groupe';
	$cms_lang['group_config']				= 'Configurer le groupe';
	$cms_lang['group_delete']				= 'Supprimer le groupe';
	$cms_lang['group_new']					= 'Nouveau groupe';
	$cms_lang['group_nogroups']			= 'Il n\'y a pas encore de groupes.';
	$cms_lang['group_actions']				= 'Actions';
	$cms_lang['group_new']					= 'Nouveau groupe';
	$cms_lang['group_name']				= 'Nom du groupe';
	$cms_lang['group_description']	 		= 'Description du groupe';

	$cms_lang['group_access_area_granted'] = 'Acc&egrave;s &agrave; la branche permise';
	$cms_lang['group_access_area_denied']  = 'Acc&egrave;s &agrave; la branche interdite';

	$cms_lang['err_group_existname'] = 'Ce nom de groupe existe d&eacute;j&agrave;.';
	$cms_lang['err_group_noname'] = 'Aucun nom de groupe a &eacute;t&eacute; indiqu&eacute;';
	$cms_lang['err_group_incorrectcharacter'] = 'Veuillez utiliser seulement des caract&egrave;res alphanum&eacute;riques pour le nom de groupe ([0-9a-zA-Z])';
?>
