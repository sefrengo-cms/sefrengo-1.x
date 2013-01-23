<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}
include('inc/inc.header.php');
$tpl->loadTemplatefile('license.tpl');
$tpl->touchBlock('LICENSE');
$tpl->parse();
?>