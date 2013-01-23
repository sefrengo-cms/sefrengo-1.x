<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

$cms_lang['user_name']					= 'Pr&eacute;nom';
$cms_lang['user_newpassword']				= 'Nouveau mot de passe';
$cms_lang['user_newpasswordagain']			= 'Confirmation du nouveau mot de passe';
$cms_lang['user_delete'] 				= 'Supprimer l\'utilisateur';
$cms_lang['user_nousers'] 				= 'Il n\'y a pas d\'utilisateurs dans ce groupe.';
$cms_lang['user_edit'] 				= '&Eacute;diter l\'utilisateur';
$cms_lang['user_email']				= 'courriel';
$cms_lang['user_on']					= 'Activer l\'utilisateur';
$cms_lang['user_off']					= 'D&eacute;sactiver l\'utilisateur';
$cms_lang['user_surname']   				= 'Nom';
$cms_lang['user_sendmail']   				= 'Envoyer un courriel';
$cms_lang['user_new']					= 'Cr&eacute;er un nouvel utilisateur';
$cms_lang['user_loginname']				= 'Identifiant';
$cms_lang['user_action']				= 'Actions';
$cms_lang['user_group']				= 'Groupes';
$cms_lang['user_salutation']				= 'Titre';
$cms_lang['user_street']				= 'Adresse';
$cms_lang['user_street_alt']				= 'Compl&eacute;ment d\'adresse';
$cms_lang['user_zip']					= 'Code postal';
$cms_lang['user_location']				= 'Localit&eacute;';
$cms_lang['user_state']				= 'D&eacute;partement';
$cms_lang['user_country']				= 'Pays';
$cms_lang['user_phone']				= 'T&eacute;l&eacute;phone';
$cms_lang['user_fax']					= 'Fax';
$cms_lang['user_mobile']				= 'T&eacute;l&eacute;phone mobile';
$cms_lang['user_pager']				= 'T&eacute;l&eacute;avertisseur';
$cms_lang['user_homepage']				= 'Page d\'accueil';
$cms_lang['user_birthday']				= 'Date de naissance';
$cms_lang['user_firm']					= 'Nom de la soci&eacute;t&eacute;';
$cms_lang['user_position']				= 'Position/D&eacute;partement';
$cms_lang['user_firm_street']				= 'Adresse';
$cms_lang['user_firm_street_alt']			= 'Compl&eacute;ment d\'adresse';
$cms_lang['user_firm_zip']				= 'Code postal';
$cms_lang['user_firm_location']			= 'Localit&eacute;';
$cms_lang['user_firm_state']				= 'D&eacute;partement';
$cms_lang['user_firm_country']				= 'Pays';
$cms_lang['user_firm_email']				= 'Courriel';
$cms_lang['user_firm_phone']				= 'T&eacute;l&eacute;phone';
$cms_lang['user_firm_fax']				= 'Fax';
$cms_lang['user_firm_mobile']				= 'T&eacute;l&eacute;phone mobile';
$cms_lang['user_firm_pager']				= 'T&eacute;l&eacute;avertisseur';
$cms_lang['user_firm_homepage']			= 'Page d\'accueil';
$cms_lang['user_comment']				= 'Commentaire';

include_once($cfg_cms['cms_path'] .'tpl/standard/lang/'.$cfg_cms['backend_lang'].'/lang_user_perms.php');
?>
