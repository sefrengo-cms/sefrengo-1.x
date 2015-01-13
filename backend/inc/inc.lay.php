<?PHP
// File: $Id: inc.lay.php 28 2008-05-11 19:18:49Z mistral $
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
// + Description:
// +----------------------------------------------------------------------+
// + Changes: 
// +----------------------------------------------------------------------+
// + ToDo:
// +----------------------------------------------------------------------+

if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

include('inc/fnc.lay.php');


$perm->check('area_lay');
switch($action)
{
	case 'copy':  // Layout kopieren
		$errno = lay_copy($idlay, $from, $into);
		break;
	case 'save':  // Layout speichern
	case 'saveedit': 
		if(is_numeric($idlay)) $perm->check(3, 'lay', $idlay);
		else $perm->check(3, 'area_lay', 0);
		$doctype_autoinsert = $sf_doctype_autoinsert == 1 ? 1:0;
		$idlay = lay_edit_layout($idlay, $layname, $description, $code, $sf_doctype, $doctype_autoinsert, $idclient);
		if ($action == 'saveedit') {
			header ('Location:'.$sess->urlRaw("main.php?area=lay_edit&idlay=$idlay&idclient=$idclient"));
			exit;
		}
		break;
	case 'delete':  // Layout löschen
		$perm->check(5, 'lay', $idlay);
		$errno = lay_delete_layout($idlay);
		break;
}

include('inc/inc.header.php');

// Templatedatei laden
$tpl->loadTemplatefile('lay.tpl');

if ($idclient != '0') {
	//Layout anlegen
	if ($perm->have_perm(2, 'area_lay', 0)){
		$tmp['NEW_LAYOUT'] = "\n<a class=\"action\" href=\"".$sess->url("main.php?area=lay_edit&idclient=$idclient")."\">".$cms_lang['lay_new']."</a>";
	}
	//Layout importieren
	if ($perm->have_perm(7, 'area_lay', 0)){
		$tmp['IMPORT_LAYOUT'] = " |\n<a class=\"action\" href=\"".$sess->url('main.php?area=lay&idclient=0')."\">".$cms_lang['lay_import']."</a>";
	}
}
else{
	$tmp['BACK'] = "\n<a class=\"action\" href=\"".$sess->url("main.php?area=lay&idclient=$client")."\">".$cms_lang['gen_back']."</a>";
}


$tmp['AREA'] = $cms_lang['area_lay'];
$tmp['FOOTER_LICENSE'] = $cms_lang['login_licence'];
if(! empty($errno)){
	$tpl -> setCurrentBlock('ERROR');
	$tpl_error['ERRORMESSAGE'] = $cms_lang['err_' . $errno];
	$tpl->setVariable($tpl_error);
	$tpl->parseCurrentBlock();
}

$tmp['LAY_LAYOUTNAME'] = $cms_lang['lay_layoutname'];
$tmp['LAY_DESCRIPTION'] = $cms_lang['lay_description'];
$tmp['LAY_ACTION'] = $cms_lang['lay_action'];
$tpl->setVariable($tmp);
unset($tmp);

// Layout aus der Datenbank suchen
$tpl->setCurrentBlock('ENTRY');
$sql = "SELECT * FROM $cms_db[lay] WHERE idclient='$idclient' ORDER BY name";
$db->query($sql);
$affected_rows = $db->affected_rows();
while ($db->next_record()) 
{
	$idlay = $db->f('idlay');

	//Darf aktuelles Layout sehen, wenn nicht, Schleifendurchgang abbrechen
	//In der Standardübersicht
	if(! $perm->have_perm(1, 'lay', $idlay)) continue;
	
	// Hintergrundfarbe wählen
	if ($idclient=='0') {
         	$tmp['ENTRY_BGCOLOR'] = '#FFFFFF';
            $tmp['OVERENTRY_BGCOLOR'] = '#FFF7CE';
	} else {
         	$tmp['ENTRY_BGCOLOR'] = '#FFFFFF';
            $tmp['OVERENTRY_BGCOLOR'] = '#FFF7CE';
	}
	
	$tmp['ENTRY_ICON'] = "\n<img src=\"tpl/".$cfg_cms['skin']."/img/but_layout.gif\" border=\"0\" width=\"16\" height=\"16\" class=\"icon\">\n";

	//Layout duplirieren - braucht Rechte neu anlegen (2) und bearbeiten (3)
	if ( $perm->have_perm(2, 'lay', $idlay) && $perm->have_perm(3, 'lay', $idlay) ) $tmp['ENTRY_DUPLICATE'] = "\n<a href=\"".$sess->url("main.php?area=lay_edit&idlay=".$db->f('idlay')."&idclient=$idclient&action=duplicate")."\"><img src=\"tpl/".$cfg_cms['skin']."/img/but_duplicate.gif\" alt=\"".$cms_lang['lay_duplicate']."\" title=\"".$cms_lang['lay_duplicate']."\" width=\"16\" height=\"16\" /></a>";

	//Layout bearbeiten - Braucht Recht bearbeiten (3)
	if ( $perm->have_perm(3, 'lay', $idlay) ) $tmp['ENTRY_EDIT'] = "\n<a href=\"".$sess->url("main.php?area=lay_edit&idlay=".$db->f('idlay')."&idclient=$idclient")."\"><img src=\"tpl/".$cfg_cms['skin']."/img/but_edit.gif\" alt=\"".$cms_lang['lay_edit']."\" title=\"".$cms_lang['lay_edit']."\" width=\"16\" height=\"16\" /></a>";

	//Layout importieren - Recht importieren (7), exportieren (8)
	//Nur im Importbereich 
	if ($idclient=='0' && $perm->have_perm(7, 'lay', $idlay) ) $tmp['ENTRY_IMPORT'] = "\n<a href=\"".$sess->url("main.php?area=lay&action=copy&idlay=".$db->f('idlay')."&into=$client&idclient=0")."\"><img src=\"tpl/".$cfg_cms['skin']."/img/import.gif\" alt=\"".$cms_lang['lay_import']."\" title=\"".$cms_lang['lay_import']."\" width=\"16\" height=\"16\" /></a>";

	// Layout exportieren - Recht exportieren (8)
	if ($idclient!='0' && $perm->have_perm(8, 'lay', $idlay) ) $tmp['ENTRY_EXPORT'] = "\n<a href=\"".$sess->url("main.php?area=lay&action=copy&idlay=".$db->f('idlay')."&from=$client&idclient=$idclient")."\"><img src=\"tpl/".$cfg_cms['skin']."/img/export.gif\" alt=\"".$cms_lang['lay_export']."\" title=\"".$cms_lang['lay_export']."\" width=\"16\" height=\"16\" /></a>";

	//Layout löschen - Recht löschen (5) 
	if ($db->f('deletable')=='1' &&  $perm->have_perm(5, 'lay', $idlay)  ) $tmp['ENTRY_DELBUT'] = "\n<a href=\"".$sess->url('main.php?area=lay&action=delete&idlay='.$db->f('idlay').'&idclient='.$idclient)."\" onclick=\"return delete_confirm()\">\n<img src=\"tpl/".$cfg_cms['skin']."/img/but_deleteside.gif\" width=\"16\" height=\"16\" alt=\"".$cms_lang['lay_delete']."\" title=\"".$cms_lang['lay_delete']."\" /></a>";
	else $tmp['ENTRY_DELBUT'] = "\n<img src=\"tpl/".$cfg_cms['skin']."/img/space.gif\" border=\"0\" width=\"16\" height=\"16\">";

	$tmp['ENTRY_NAME'] = htmlentities($db->f('name'), ENT_COMPAT, 'UTF-8');
	$tmp['ENTRY_DESCRIPTION'] = htmlentities($db->f('description'), ENT_COMPAT, 'UTF-8');
	$tpl->setVariable($tmp);
	$tpl->parseCurrentBlock();
	unset($tmp);
}


if ($affected_rows < 1) {
	$tpl->setCurrentBlock('NOENTRY');
	$tmp['LAY_NOLAYOUTS'] = $cms_lang['lay_nolayouts'];
	$tpl->setVariable($tmp);
	$tpl->parseCurrentBlock();
	unset($tmp);
}
?>
