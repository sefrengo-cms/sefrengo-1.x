<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

	include_once($cfg_cms['cms_path'] .'tpl/standard/lang/'.$cfg_cms['backend_lang'].'/lang_user.php');
$cms_lang['err_incorrect']				= 'Mot de passe invalide ou trop court.';
$cms_lang['err_nologinname']	     			= 'Vous n\'avez pas sp&eacute;cifi&eacute; d\'identifiant.';
$cms_lang['err_existusername']	     			= 'Le nom d\'utilisateur existe d&eacute;j&agrave;.';
?>
