<?php
// File: $Id: inc.con_copycat.php 28 2008-05-11 19:18:49Z mistral $
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

$SF_wr =& sf_factoryGetObject('HTTP', 'WebRequest');
$idcat = $SF_wr->getValAsInt('idcat');
$perm->check(2, 'cat', $idcat);

//fetch cattitle
$cat =& sf_factoryGetObject('PAGE', 'Cat');
if (! $cat->loadByIdcatIdlang($idcat, $lang) ) {
	header ('Location:'. $sess->url("main.php?area=con"));
	exit;
}
		
// Seitenkonfiguration speichern
switch($action) {
	case 'save': 
		$title = ($SF_wr->getVal('title')== '') ? 'Kopie von '.$cat->getTitle() : $SF_wr->getVal('title');
		$target_idcat = $SF_wr->getValAsInt('sf_cat_copy_to');
		
		//check perms
		if ($target_idcat == 0) {
			$perm->check(2, 'area_con');
		} else {
			$perm->check(2, 'cat', $target_idcat);
		}
		
		$new_cat =& $cat->copy($target_idcat, $title,  array('set_online'=> 'no'));

		if ( isset($_POST['sf_apply']) ) {
			header ('Location:'. $sess->urlRaw("main.php?area=con_configcat&idcat=".$new_cat->getIdcat()."&idtplconf=".$new_cat->getIdtplconf()));
		} else {
			header ('Location:'. $sess->urlRaw("main.php?area=con&idcat=".$new_cat->getIdcat()."#sideanchor"));
		}
		exit;
		break;
}

include('inc/inc.header.php');

$cattree =& sf_factoryGetObject('PAGE', 'Cattree');
$cattree->setIdclient($client);
$cattree->generate();

$catinfos =& sf_factoryGetObject('PAGE', 'Catinfos');
$catinfos->setIdlang($lang);
$catinfos->generate();

if ($perm->have_perm(2, 'area_con')) {
	$options = '<option value="0">Als Hauptordner</option>'."\n";
}
$hide = false;
for ($iter =& $cattree->getLevelorderIter(); $iter->valid(); $iter->next() ) {
	$cid = $iter->current();
	if ($cid == $idcat) {
		$hide = true;
		$hide_level = $cattree->getLevel($cid);
		continue;
	} 
	if ($hide) {
		if ( $cattree->getLevel($cid)<= $hide_level) {
			$hide = false;
		} else {
			continue;
		}
		
	}
	
	//check cat perm
	if (! $perm->have_perm(2, 'cat', $cid)) {
		continue;
	}
	
	$repeat = str_repeat('&nbsp;&nbsp;', $cattree->getLevel($cid));
	$options .= '<option value="'.$cid.'">'.$repeat.$catinfos->getTitle($cid).'</option>'."\n";
}

$select = "<select name=\"sf_cat_copy_to\">$options</select>";
//echo $select;

$tpl->loadTemplatefile('cat_copy.tpl');

$tpl->setCurrentBlock('__global__');
$tpl_data['SKIN'] = $cfg_cms['skin'];
$tpl_data['FOOTER_LICENSE'] = $cms_lang['login_license'];

$tpl_data['FORM_ACTION'] = $sess->url("main.php?idcat=$idcat&area=con_copycat");
$tpl_data['AREA_TITLE'] = $cms_lang['nav_1_0']. ' - Kategorie kopieren';

$tpl_data['BUTTON_SUBMIT_VALUE'] = $cms_lang['gen_save'];
$tpl_data['BUTTON_SUBMIT_TEXT'] = $cms_lang['gen_save_titletext'];

$tpl_data['BUTTON_APPLY_VALUE'] = $cms_lang['gen_apply'];
$tpl_data['BUTTON_APPLY_TEXT'] = $cms_lang['gen_apply_titletext'];

$tpl_data['BUTTON_CANCEL_URL'] = $sess->url("main.php?area=con");
$tpl_data['BUTTON_CANCEL_VALUE'] = $cms_lang['gen_cancel'];
$tpl_data['BUTTON_CANCEL_TEXT'] = $cms_lang['gen_cancel_titletext'];


$tpl_data['CON_CATCONFIG'] = 'Ordnerinfos';
$tpl_data['CAT_TITLE_DESC'] = 'Ordnername';
$tpl_data['CAT_COPY_TO_DESC'] = 'Kopiere Ordner nach';
$tpl_data['CAT_TITLE'] = ($SF_wr->getVal('title')== '') ? 'Kopie von '.htmlentities($cat->getTitle(), ENT_COMPAT, 'UTF-8') : $SF_wr->getVal('title');
$tpl_data['CAT_SELECT'] = $select;
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
