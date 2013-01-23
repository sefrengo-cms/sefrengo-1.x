<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

	include_once($cfg_cms['cms_path'] .'tpl/standard/lang/'.$cfg_cms['backend_lang'].'/lang_user.php');
$cms_lang['err_incorrect']				= 'Passworteingabe falsch, oder zu Kurz.';
$cms_lang['err_nologinname']	     			= 'Es wurde kein Loginname angegeben.';
$cms_lang['err_existusername']	     			= 'Der Benutzername existiert schon.';
?>