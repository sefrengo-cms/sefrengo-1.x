<?PHP
// File: $Id: inc.group_config.php 52 2008-07-20 16:16:33Z bjoern $
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

/******************************************************************************
 0. Prüfe Zugriffsrechte
******************************************************************************/
$perm->check('area_group');

/******************************************************************************
 1. Benötigte Funktionen und Klassen includieren
******************************************************************************/
include('inc/fnc.group.php');
// Lade Liste der möglichen Rechte in Sefrengo
$cms_perm_val = $val_ct->get_by_group('user_perms', $idclient);

/******************************************************************************
 2. Eventuelle Actions/ Funktionen abarbeiten
******************************************************************************/
switch($action) {
	case 'save':
		$errno = group_save_perms();
		if (!$errno && ! isset($_REQUEST['sf_apply']) ) {
			header ('HTTP/1.1 302 Moved Temporarily');
			header ('Location:'.$sess->urlRaw("main.php?area=group&order=$order&ascdesc=$ascdesc&idgroup=$idgroup"));
			exit;
		}
		break;
}

/******************************************************************************
 3. Eventuelle Dateien zur Darstellung includieren
******************************************************************************/
include('inc/inc.header.php');

/******************************************************************************
 4. Bildschirmausgabe aufbereiten und ausgeben
******************************************************************************/
// Templatedatei laden und Überschrift setzen
$tpl->loadTemplatefile('group_config.tpl');

$tmp['AREA'] = $cms_lang['area_group_config'];

$sql = "SELECT name FROM ".$cms_db['groups']." WHERE idgroup = $idgroup";
$db->query($sql);
if ($db->next_record()) {
	$_group = ": " . htmlentities($db->f('name'), ENT_COMPAT, 'UTF-8');
}
$sql = "SELECT name FROM ".$cms_db['lang']." WHERE idlang = $idlang";
$db->query($sql);
if ($db->next_record()) {
	$_group .= "/" . htmlentities($db->f('name'), ENT_COMPAT, 'UTF-8');
}

$tmp['AREA'] = $tmp['AREA'] . $_group;

// Fehlermeldungen ausgeben
if (!empty($errno)) {
	$tpl -> setCurrentBlock('ERROR');
	$tpl_error['ERRORMESSAGE'] = $cms_lang['err_'.$errno];
	$tpl->setVariable($tpl_error);
	$tpl->parseCurrentBlock();
}

// Links zusammenstellen
$img_path   = 'tpl/'.$cfg_cms['skin'].'/img/';
$query = "main.php?area=group&order=$order&ascdesc=$ascdesc&idclient=$idclient&idlang=$idlang&idgroup=$idgroup";
$url = '<a href="'.$sess->url($query).'" class="action" onmouseover="on(\'%s\');return true;" onmouseout="off();return true;">%s</a>';
$img_abort  = '<img src="' . $img_path . 'but_cancel.gif" alt="' . $cms_lang['gen_abort'] . '" title="' . $cms_lang['gen_abort'];
$img_abort .= '" width="21" height="21" /></a>';

// Zurück-Link setzen
$tmp['BACK'] = sprintf($url, $cms_lang['group_back'], $cms_lang['group_back']);
$tmp['FOOTER_LICENSE'] = $cms_lang['login_licence'];

// Formulareinstellungen
$tmp['FORM_URL']   = $sess->url('main.php');
$tmp['IDGROUP']    = $idgroup;
$tmp['IDLANG']     = $idlang;
$tmp['IDCLIENT']   = $idclient;
$tmp['ORDER']      = $order;
$tmp['ASCDESC']    = $ascdesc;

$tmp['BUTTON_SUBMIT_VALUE'] = $cms_lang['gen_save'];
$tmp['BUTTON_SUBMIT_TEXT'] = $cms_lang['gen_save_titletext'];

$tmp['BUTTON_APPLY_VALUE'] = $cms_lang['gen_apply'];
$tmp['BUTTON_APPLY_TEXT'] = $cms_lang['gen_apply_titletext'];

$tmp['BUTTON_CANCEL_URL'] = $sess->url($query);
$tmp['BUTTON_CANCEL_VALUE'] = $cms_lang['gen_cancel'];
$tmp['BUTTON_CANCEL_TEXT'] = $cms_lang['gen_cancel_titletext'];

$tpl->setVariable($tmp);
unset($tmp);

// Lade eingetragene Rechte der Gruppe
$sim_perm = new cms_perms($idclient, $idlang, true, $idgroup);

// Lade die algemeinen Rechtegruppen und zeige für jede die eingetragenen Rechte an
$sql  = 'SELECT DISTINCT key2, conf_desc_langstring, conf_sortindex ';
$sql .= 'FROM ' . $cms_db['values'] . ' ';
$sql .= 'WHERE ';
$sql .= " (key1 = 'cms_access' AND key2 NOT LIKE 'area_plug_%') ";
$sql .= 'GROUP BY conf_sortindex, key2, conf_desc_langstring';
_show_rightgroup( $sql, false );

// Prüfe auf installierte Plugins und zeige für jede die eingetragenen Rechte an
// Signalisiere dem System, das hier Rechtenamen mit einem Punkt im Namen vorkommen, diese müssen mit "_" ersetzt werden
$sql  = 'SELECT DISTINCT V.key2, V.conf_desc_langstring, V.conf_sortindex ';
$sql .= 'FROM ' . $cms_db['values'] . ' V, ' . $cms_db['plug'] . ' P ';
$sql .= 'WHERE ';
// $sql .= " (V.group_name = 'user_perms' AND P.idclient = $idclient AND V.key2 LIKE concat('area_plug_', P.root_name, '_', P.version, '%')) ";
$sql .= " (V.group_name = 'user_perms' AND P.idclient = $idclient AND V.key2 LIKE concat('area_plug_', P.root_name, '%')) ";
$sql .= 'GROUP BY V.conf_sortindex, V.key2, V.conf_desc_langstring';
_show_rightgroup( $sql, true );


function _show_rightgroup( $sql, $plugins ) {
	global $db, $cms_lang, $tmp;

	$db2 = new DB_cms;
	$db2->query($sql);
	while ($db2->next_record()) {
		$area_name = $db2->f('key2');
		$display   = $db2->f('conf_desc_langstring');
		$topic     = $cms_lang[$display];
		create_area_checkbox($area_name, $topic, $display, $plugins);
		unset($tmp);
	}
}

?>