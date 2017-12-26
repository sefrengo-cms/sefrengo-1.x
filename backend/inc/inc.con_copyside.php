<?php
// File: $Id: inc.con_copyside.php 28 2008-05-11 19:18:49Z mistral $
// +----------------------------------------------------------------------+
// | Version: Sefrengo $Name:  $
// +----------------------------------------------------------------------+
// | Copyright (c) 2005 - 2007 sefrengo.org <info@sefrengo.org>           |
// +----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify |
// | it under the terms of the GNU General Public License                 |
// |                                                                      |
// | This program is subject to the GPL license, that is bundled with     |
// | this package in the file LICENSE.TXT.                                |
// | If you did not receive a copy of the GNU General Public License      |
// | along with this program write to the Free Software Foundation, Inc., |
// | 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA               |
// |                                                                      |
// | This program is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// +----------------------------------------------------------------------+
// + Autor: $Author: mistral $
// +----------------------------------------------------------------------+
// + Revision: $Revision: 28 $
// +----------------------------------------------------------------------+

if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

include('inc/fnc.con.php');

	
// idcatside vorhanden, pr�fen, ob Recht zum konfigurieren gegeben ist
$perm->check(20, 'side', $idcatside, $idcat);

//fetch sidetitle
$sql = "SELECT * FROM ".$cms_db['cat_side']." WHERE idcatside='$idcatside'";
$db->query($sql);
if ($db->next_record() ) {
	$idcat_from = $db->f('idcat');
	$idside_from = $db->f('idside');
	$sortindex_from = $db->f('sortindex');
} else {
	header ('Location:'. $sess->url("main.php?area=con"));
	exit;
}

$sql = "SELECT * FROM ".$cms_db['side_lang']." WHERE idside='$idside_from' AND idlang='$lang'";
$db->query($sql);
if ( $db->next_record() ) {
	$title_from = $db->f('title');
}
		
// Seitenkonfiguration speichern
switch($action) {
	case 'save': 
		$title = empty($title) ? 'Kopie von '.$title_from:$title;
		$idcatside = con_copy_page($client, $lang, $idcatside, $title, -1, true, array('set_online'=> 'no') );
		con_delete_cache($lang);
		if ( isset($_POST['sf_apply']) ) {
			$sql = "SELECT idside, $idcat FROM ".$cms_db['cat_side']." WHERE idcatside='$idcatside'";
			$db->query($sql);
			$db->next_record();
			$idside = $db->f('idside');
			$idcat = $db->f('idcat');
			
			$sql = "SELECT
					SL.idtplconf
				FROM 
					".$cms_db['side_lang']." SL
				WHERE
					SL.idlang='$lang'
					AND SL.idside = '$idside'";
			$db->query($sql);
			$db->next_record();
			$idtplconf = $db->f('idtplconf');
			header ('Location:'. $sess->urlRaw("main.php?area=con_configside&idside=$idside&idcat=$idcat&idcatside=$idcatside&idtplconf=$idtplconf"));
		} else {
			header ('Location:'. $sess->urlRaw("main.php?area=con&idactside=$idcatside&idcat=$idcat#sideanchor"));
		}
		exit;
		break;
}


include('inc/inc.header.php');

$tpl->loadTemplatefile('side_copy.tpl');

$tpl->setCurrentBlock('__global__');
$tpl_data['TABLE_PADDING'] = $cellpadding;
$tpl_data['TABLE_SPACING'] = $cellspacing;
$tpl_data['TABLE_BORDER'] = $border;
$tpl_data['SKIN'] = $cfg_cms['skin'];

$tpl_data['FORM_ACTION'] = $sess->url("main.php?idcatside=$idcatside&idcat=$idcat&area=con_copyside");
$tpl_data['AREA_TITLE'] = $cms_lang['nav_1_0']. ' - Seite kopieren';
$tpl_data['FOOTER_LICENSE'] = $cms_lang['login_license'];

$tpl_data['BUTTON_SUBMIT_VALUE'] = $cms_lang['gen_save'];
$tpl_data['BUTTON_SUBMIT_TEXT'] = $cms_lang['gen_save_titletext'];

$tpl_data['BUTTON_APPLY_VALUE'] = $cms_lang['gen_apply'];
$tpl_data['BUTTON_APPLY_TEXT'] = $cms_lang['gen_apply_titletext'];

$tpl_data['BUTTON_CANCEL_URL'] = $sess->url("main.php?area=con");
$tpl_data['BUTTON_CANCEL_VALUE'] = $cms_lang['gen_cancel'];
$tpl_data['BUTTON_CANCEL_TEXT'] = $cms_lang['gen_cancel_titletext'];


$tpl_data['CON_SIDECONFIG'] = $cms_lang['con_sideconfig'];
$tpl_data['SIDE_TITLE_DESC'] = $cms_lang['con_title'];
$tpl_data['SIDE_TITLE'] = empty($title) ? 'Kopie von '.htmlentities($title_from, ENT_COMPAT, 'UTF-8') : $title;
$tpl->setVariable($tpl_data);
unset($tpl_data);
// Look for Errors
if (!empty($errno)) {
	$tpl->setCurrentBlock('ERROR_BLOCK');
	$tpl_error['ERR_MSG'] = $cms_lang['err_' . $errno];
	$tpl->setVariable($tpl_data);
	$tpl->parseCurrentBlock();
	unset($tpl_data);
}
?>