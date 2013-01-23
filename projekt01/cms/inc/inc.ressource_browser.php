<?php
require_once('config.php');
require_once('../../'.$cms_path.'inc/inc.init_external.php');
require_once($this_dir.'tpl/'.$cfg_cms['skin'].'/lang/'.$cfg_cms['backend_lang'].'/lang_ressource_browser.php');

$sf_factory->requireClass('GUI/RESSOURCES', 'FileManager');
$sf_factory->requireClass('GUI/RESSOURCES', 'InternalLink');

$rb =& $sf_factory->getObject('GUI', 'RessourceBrowser');
$rb->importConfigURL();
$rb->run();

?>