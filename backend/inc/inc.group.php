<?PHP
// File: $Id: inc.group.php 52 2008-07-20 16:16:33Z bjoern $
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
// + Autor: $Author: bjoern $
// +----------------------------------------------------------------------+
// + Revision: $Revision: 52 $
// +----------------------------------------------------------------------+
// + Description:
// +----------------------------------------------------------------------+
// + Changes: 
// +----------------------------------------------------------------------+
// + ToDo:
// +----------------------------------------------------------------------+

if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

include('inc/fnc.group.php');

$perm->check('area_group');
switch ($action) {
	case 'activate':
		group_set_active('1');
		break;
	case 'deactivate':
		group_set_active('0');
		break;
	case 'delete':
		group_delete();
		//Wird eine gruppe gelöscht, wird eventuell noch eine aufgeklappte Spracheinstellung angezeigt - redirect um das zu verhindern
		header ('HTTP/1.1 302 Moved Temporarily');
		header ('Location:'.$sess->urlRaw("main.php?area=group&order=$order&ascdesc=$ascdesc"));
		exit;
		break;
	case 'activate_lang':
		group_visible_lang();
		break;
}

include('inc/inc.header.php');
$row_bgcolor['project'] = '#f7fbff';
$row_bgcolor['project_active'] = '#dfeeff';
$row_bgcolor['project_lang'] = '#ffffff';

// Allgemeine Einstellungen initialisieren
if (empty($ascdesc)) {
	$order = 'name';
	$ascdesc = 'ASC';
}
$base_url = $sess->url('main.php?area=%s&order=%s&ascdesc=%s');

// Templatedatei laden
$tpl->loadTemplatefile('group.tpl');
$tmp['AREA'] = $cms_lang['area_group'];
$tmp['FOOTER_LICENSE'] = $cms_lang['login_licence'];
if (!empty($errno)) {
	$tpl -> setCurrentBlock('ERROR');
	$tpl_error['ERRORMESSAGE'] = $cms_lang['err_'.$errno];
	$tpl->setVariable($tpl_error);
	$tpl->parseCurrentBlock();
}

$tmp['NEW_GROUP'] = '<a href="'.sprintf($base_url, 'group_edit', $order, $ascdesc).'" class="action">Neue Gruppe</a>';


// Tabellenüberschrift
$tmp['LANG_NAME'] = '<a href ="'.sprintf($base_url, 'group', 'name', ($order == 'name' && ($ascdesc == 'ASC' || $ascdesc == '')) ? 'DESC' : 'ASC').'">'.$cms_lang['group_name'].'</a>';
$tmp['LANG_DESCRIPTION'] = '<a href ="'.sprintf($base_url, 'group', 'description', ($order == 'description' && ($ascdesc == 'ASC' || $ascdesc == '')) ? 'DESC' : 'ASC').'">'.$cms_lang['group_description'].'</a>';
$tmp['LANG_ACTIONS'] = $cms_lang['group_actions'];
$tpl->setVariable($tmp);
unset($tmp);

// Usergruppen auflisten
$tpl->setCurrentBlock('PREENTRY');

/*
*  vorerst so gelöst, weil wenn order=if(1=1,sleep(5),0) wirkt weder add_slashes noch mysql_real_escape_string
**/
if(isset($order)){
	if($order!=="name"){
		$order = "description";
	}
}

$sql = "SELECT * FROM ".$cms_db['groups']." WHERE is_deletable = '1' ORDER BY ? ?";
$rs = $adb->Execute($sql, array($order, $ascdesc));
while (!$rs->EOF) {
	$tr_color = $row_bgcolor['project'];
	if ($rs->fields['idgroup'] == $idgroup) {
		$tpl->setCurrentBlock('ENTRY');
		$tr_color = $row_bgcolor['project_active'];
	}
	$tmp['BGCOLOR'] = $tr_color;
	$tmp['ENTRY_ICON'] = make_image('but_group.gif', '', '16', '16', false, 'class="icon"');
	$tmp['NAME'] = htmlentities($rs->fields['name'], ENT_COMPAT, 'UTF-8');
	$tmp['DESCRIPTION'] = htmlentities($rs->fields['description'], ENT_COMPAT, 'UTF-8');
	$tmp['BUTTON_EDIT'] = '<a href="'.sprintf($base_url, 'group_edit', $order, $ascdesc).'&idgroup='.$rs->fields['idgroup'].'"><img src="tpl/'.$cfg_cms['skin'].'/img/but_edit.gif" border="0" alt="'.$cms_lang['group_edit'].'" title="'.$cms_lang['group_edit'].'" width="16" height="16" /></a>';
	$tmp['BUTTON_CONFIG'] = '<a href="'.sprintf($base_url.'%s', 'group', $order, $ascdesc, ($rs->fields['idgroup'] != $idgroup) ? '&idgroup='.$rs->fields['idgroup'] : '').'"><img src="tpl/'.$cfg_cms['skin'].'/img/but_config.gif" border="0" alt="'.$cms_lang['group_config'].'" title="'.$cms_lang['group_config'].'" width="16" height="16" /></a>';
	$tmp['BUTTON_AKTIVE'] = ($rs->fields['is_active'] == '0') ? '<a href="'.sprintf($base_url, 'group', $order, $ascdesc).'&action=activate&idgroup='.$rs->fields['idgroup'].'"><img src="tpl/'.$cfg_cms['skin'].'/img/but_offline.gif" border="0" alt="'.$cms_lang['group_on'].'" title="'.$cms_lang['group_on'].'" width="16" height="16" /></a>' : '<a href="'.sprintf($base_url, 'group', $order, $ascdesc).'&action=deactivate&idgroup='.$rs->fields['idgroup'].'"><img src="tpl/'.$cfg_cms['skin'].'/img/but_online.gif" border="0" alt="'.$cms_lang['group_off'].'" title="'.$cms_lang['group_off'].'" width="16" height="16" /></a>';
	$tmp['BUTTON_DELETE'] = '<a href="'.sprintf($base_url, 'group', $order, $ascdesc).'&action=delete&idgroup='.$rs->fields['idgroup'].'" onclick="return delete_confirm()"><img src="tpl/'.$cfg_cms['skin'].'/img/but_delete.gif" alt="'.$cms_lang['group_delete'].'" title="'.$cms_lang['group_delete'].'" width="16" height="16" /></a>';
	$tpl->setVariable($tmp);
	$tpl->parseCurrentBlock();
	unset($tmp);
	if ($rs->fields['idgroup'] == $idgroup) {
		$tpl->setCurrentBlock('POSTENTRY');
	}
	$rs->MoveNext();
}
if ($rs === FALSE) {
	$tpl -> setCurrentBlock('EMPTY');
	$tmp['LANG_NOGROUPS'] = $cms_lang['group_nogroups'];
	$tpl->setVariable($tmp);
	$tpl->parse('EMPTY');
	unset($tmp);
}
$rs->Close();

// Sprachen zuordnen
if ($idgroup) {
	$sim_perm = new cms_perms($client_id, $idlang, true, $idgroup);
	$tpl->setCurrentBlock('CONFIG');
	$sql = "SELECT A.idclient, A.name, B.idlang, B.name AS lang FROM ". $cms_db['clients'] ." A, ". $cms_db['lang'] ." B LEFT JOIN ". $cms_db['clients_lang'] ." C USING(idlang) WHERE A.idclient = C.idclient ORDER BY A.idclient, lang";
	$rs = $adb->Execute($sql);
	while (!$rs->EOF) {
		$tmp['BGCOLOR'] = $row_bgcolor['project_lang'];
		$tmp['ENTRY_ICON'] = make_image('but_permission.gif', '', '16', '16');
		$tmp['NAME'] = htmlentities($rs->fields['name'], ENT_COMPAT, 'UTF-8');
		$tmp['DESCRIPTION'] = htmlentities($rs->fields['lang'], ENT_COMPAT, 'UTF-8');
		if ($sim_perm -> have_perm('1', 'lang', $rs->fields['idlang'])) {
			$tmp['BUTTON_CONFIG'] = '<a href="'.sprintf($base_url, 'group_config', $order, $ascdesc).'&idgroup='.$idgroup.'&idlang='.$rs->fields['idlang'].'&idclient='.$rs->fields['idclient'].'" onmouseover="on(\''.$cms_lang['group_langconfig'].'\');return true;" onmouseout="off()" ;return true;"><img src="tpl/'.$cfg_cms['skin'].'/img/but_config.gif" border="0" alt="'.$cms_lang['group_langconfig'].'" title="'.$cms_lang['group_langconfig'].'" width="16" height="16" /></a>';
			$tmp['BUTTON_AKTIVE'] = '<a href="'.sprintf($base_url, 'group', $order, $ascdesc).'&action=activate_lang&idgroup='.$idgroup.'&idlang='.$rs->fields['idlang'].'" onmouseover="on(\''.$cms_lang['group_langoff'].'\');return true;" onmouseout="off()" ;return true;"><img src="tpl/'.$cfg_cms['skin'].'/img/but_online.gif" border="0" alt="'.$cms_lang['group_langoff'].'" title="'.$cms_lang['group_langoff'].'" width="16" height="16" /></a>';
                 } else $tmp['BUTTON_AKTIVE'] = '<a href="'.sprintf($base_url, 'group', $order, $ascdesc).'&action=activate_lang&idgroup='.$idgroup.'&idlang='.$rs->fields['idlang'].'" onmouseover="on(\''.$cms_lang['group_langon'].'\');return true;" onmouseout="off()" ;return true;"><img src="tpl/'.$cfg_cms['skin'].'/img/but_offline.gif" border="0" alt="'.$cms_lang['group_langon'].'" title="'.$cms_lang['group_langon'].'" width="16" height="16" /></a>';
		$tmp['SPACE'] = '<img src="tpl/'.$cfg_cms['skin'].'/img/space.gif" width="16" height="16" />';
		$tpl->setVariable($tmp);
		$tpl->parseCurrentBlock();
		unset($tmp);

		$rs->MoveNext();
	}
	$rs->Close();
}  
?>
