<?PHP
// File: $Id: fnc.generate_code.php 28 2008-05-11 19:18:49Z mistral $
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

// Konfigurationslayer erstellen
function generate_configlayer() {
	global $cms_db, $db, $sess, $cfg_cms, $cfg_client, $cms_side, $type_container, $type_number;
	global $type_typenumber, $mod_lang, $idcatside, $view, $con_side, $idcat, $con_tree, $lang, $perm;

	// Bearbeitungsrecht auf dieser Seite?
	if ($perm->have_perm(19, 'side', $idcatside, $con_side[$idcatside]['idcat']) || $perm->have_perm(2, 'cat' , $con_side[$idcatside]['idcat'])) {
		$out .= "<a href=\"javascript:void(0)\" onMouseover=\"showmenu(event,linkset['".$type_container.'_'.$type_number."_side_$type_typenumber'])\" onMouseout=\"delayhidemenu()\"><img src=\"cms/img/but_editside.gif\" border=\"0\" /></a><br />";
		$out .= "<script language=\"JavaScript1.2\">\n";

		// Seite
		$out .= "linkset['".$type_container.'_'.$type_number."_side_$type_typenumber']='<table width=\"140\" border=\"0\" cellpadding=\"1\" cellspacing=\"0\" bgcolor=\"#5A7BAD\"><tr><td><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
		if (($idcatside && $perm->have_perm(18, 'side', $idcatside, $idcat)) || ($idcatside && $perm->have_perm(19, 'side', $idcatside, $idcat)) || ($perm->have_perm('10', 'cat', $idcat) || $perm->have_perm('11', 'cat', $idcat)) || ($idcatside && $perm->have_perm('12', 'cat', $idcat))) {
			$out .= "<tr><td class=\"menutitle\"><b><font size=\"1\">&nbsp;".$mod_lang['type_edit_side']."&nbsp;</font></b></td>";

			// Seite publizieren
			if ($cfg_client['publish'] == '1' && $perm->have_perm(23, 'side', $idcatside, $idcat)) {
				$sql = "SELECT changed FROM ".$cms_db['code']." WHERE idcatside='".$idcatside."' AND idlang='$lang'";
				$db = &new DB_cms;
				$db->query($sql);
				$db->next_record();
				if ($db->f('changed') == '2') $out .= "<td class=\"menutitle\" align=\"right\"><b><font size=\"1\">&nbsp;<a href=\"".$sess->url($cfg_cms['cms_html_path'].'main.php?area=con&view='.$view.'&action=side_publish&idcatside='.$idcatside)."\" onmouseover=\"on(\'".$mod_lang['side_publish']."\');return true;\" onmouseout=\"off();return true;\"><img src=\"".$cfg_cms['cms_html_path']."tpl/".$cfg_cms['skin']."/img/but_onpublish.gif\" border=\"0\" width=\"10\" height=\"10\"></a>&nbsp;</font></b></td>";
			}
			$out .= "</tr>";
		}
		$out .= "</table><table width=\"100%\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\" bgcolor=\"#FFFFFF\">'\n";

		// Seite konfigurieren
		if ($idcatside && $perm->have_perm(20, 'side', $idcatside, $idcat)){
			$out .= "linkset['".$type_container.'_'.$type_number."_side_$type_typenumber']+='<tr><td class=\"menurow\" onclick=\"document.location.href=\'".$sess->url($cfg_cms['cms_html_path'].'main.php?area=con_configside&view='.$view.'&idcatside='.$idcatside.'&idside='.$con_side[$idcatside]['idside'].'&idcat='.$con_side[$idcatside]['idcat'].'&idtplconf='.$con_side[$idcatside]['idtplconf'])."\'\" onmouseover=\"on(\'".$mod_lang['side_config']."\');return true;\" onmouseout=\"off();return true;\"><a href=\"".$sess->url($cfg_cms['cms_html_path'].'main.php?area=con_configside&view='.$view.'&idcatside='.$idcatside.'&idside='.$con_side[$idcatside]['idside'].'&idcat='.$con_side[$idcatside]['idcat'].'&idtplconf='.$con_side[$idcatside]['idtplconf'])."\"><font face=\"Verdana, Arial, Helvetica\" size=\"1\">".$mod_lang['side_config']."</font></a></td></tr>'\n";
		}

		// Seite anlegen
		if ($perm->have_perm('18', 'cat', $idcat) ){
			$out .= "linkset['".$type_container.'_'.$type_number."_side_$type_typenumber']+='<tr><td class=\"menurow\" onclick=\"document.location.href=\'".$sess->url($cfg_cms['cms_html_path'].'main.php?area=con_configside&view='.$view.'&idcat='.$idcat.'&idtplconf=0')."\'\" onmouseover=\"on(\'".$mod_lang['side_new']."\');return true;\" onmouseout=\"off();return true;\"><a href=\"".$sess->url($cfg_cms['cms_html_path'].'main.php?area=con_configside&view='.$view.'&idcat='.$idcat.'&idtplconf=0')."\"><font face=\"Verdana, Arial, Helvetica\" size=\"1\">".$mod_lang['side_new']."</font></a></td></tr>'\n";
		}

		// Seite löschen
		if ($idcatside && $perm->have_perm(21, 'side', $idcatside, $idcat)){
			$out .= "linkset['".$type_container.'_'.$type_number."_side_$type_typenumber']+='<tr><td class=\"menurow\"><a href=\"".$sess->url($cfg_cms['cms_html_path'].'main.php?area=con&view='.$view.'&action=side_delete&idcat='.$idcat.'&idside='.$con_side[$idcatside]['idside'])."\" onmouseover=\"on(\'".$mod_lang['side_delete']."\');return true;\" onmouseout=\"off();return true;\" onclick=\"return delete_confirm()\"><font face=\"Verdana, Arial, Helvetica\" size=\"1\">".$mod_lang['side_delete']."</font></a></td></tr>'\n";
		}

		// Ordner
		$out .= "linkset['".$type_container.'_'.$type_number."_side_$type_typenumber']+='</table><table width=\"140\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#5A7BAD\">";//'

		if ($perm->have_perm('2', 'cat', $idcat) || ($perm->have_perm('3', 'cat', $idcat) || $perm->have_perm('4', 'cat', $idcat)) || (!$idcatside && $perm->have_perm('5', 'cat', $idcat))){
			$out .= "<tr><td><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"menutitle\"><b><font size=\"1\">&nbsp;".$mod_lang['type_edit_folder']."&nbsp;</font></b></td></tr>";
		}

		$out .= "</table><table width=\"100%\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\" bgcolor=\"#FFFFFF\">'\n";

		// Ordner konfigurieren
		if ($perm->have_perm('2', 'cat', $idcat)){
			$out .= "linkset['".$type_container.'_'.$type_number."_side_$type_typenumber']+='<tr><td class=\"menurow\" onclick=\"document.location.href=\'".$sess->url($cfg_cms['cms_html_path'].'main.php?area=con_configcat&view='.$view.'&idcat='.$idcat.'&idcatside='.$idcatside.'&idcatlang='.$con_tree[$idcat]['idcatlang'].'&idtplconf='.$con_tree[$idcat]['idtplconf'])."\'\" onmouseover=\"on(\'".$mod_lang['side_config']."\');return true;\" onmouseout=\"off();return true;\"><a href=\"".$sess->url($cfg_cms['cms_html_path'].'main.php?area=con_configcat&view='.$view.'&idcat='.$idcat.'&idcatside='.$idcatside.'&idcatlang='.$con_tree[$idcat]['idcatlang'].'&idtplconf='.$con_tree[$idcat]['idtplconf'])."\"><font face=\"Verdana, Arial, Helvetica\" size=\"1\">".$mod_lang['side_config']."</font></a></td></tr>'\n";
		}

		// Ordner anlegen
		if ($perm->have_perm('3', 'cat', $idcat) || $perm->have_perm('4', 'cat', $idcat)){
			$out .= "linkset['".$type_container.'_'.$type_number."_side_$type_typenumber']+='<tr><td class=\"menurow\" onclick=\"document.location.href=\'".$sess->url($cfg_cms['cms_html_path'].'main.php?area=con_configcat&view='.$view.'&idcatside='.$idcatside.'&parent='.$idcat.'&idtplconf=0')."\'\" onmouseover=\"on(\'".$mod_lang['side_new']."\');return true;\" onmouseout=\"off();return true;\"><a href=\"".$sess->url($cfg_cms['cms_html_path'].'main.php?area=con_configcat&view='.$view.'&idcatside='.$idcatside.'&parent='.$idcat.'&idtplconf=0')."\"><font face=\"Verdana, Arial, Helvetica\" size=\"1\">".$mod_lang['side_new']."</font></a></td></tr>'\n";
		}

		// Ordner löschen
		if (!$idcatside && $perm->have_perm('5', 'cat', $idcat)){
			$out .= "linkset['".$type_container.'_'.$type_number."_side_$type_typenumber']+='<tr><td class=\"menurow\"><a href=\"".$sess->url($cfg_cms['cms_html_path'].'main.php?area=con&view='.$view.'&action=cat_delete&idcat='.$idcat)."\" onmouseover=\"on(\'".$mod_lang['side_delete']."\');return true;\" onmouseout=\"off();return true;\" onclick=\"return delete_confirm()\"><font face=\"Verdana, Arial, Helvetica\" size=\"1\">".$mod_lang['side_delete']."</font></a></td></tr>'\n";
		}

		// Modus
		$out .= "linkset['".$type_container.'_'.$type_number."_side_$type_typenumber']+='</table><table width=\"140\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#5A7BAD\"><tr><td><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"menutitle\"><b><font size=\"1\">&nbsp;".$mod_lang['side_mode']."&nbsp;</font></b></td></tr></table><table width=\"100%\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\" bgcolor=\"#FFFFFF\">'\n";

		// Seitenübersicht
		if ($perm->have_perm('1', 'area_con')){
			$out .= "linkset['".$type_container.'_'.$type_number."_side_$type_typenumber']+='<tr><td class=\"menurow\" onclick=\"document.location.href=\'".$sess->url($cfg_cms['cms_html_path'].'main.php?area=con')."\'\" onmouseover=\"on(\'".$mod_lang['side_edit']."\');return true;\" onmouseout=\"off();return true;\"><a href=\"".$sess->url($cfg_cms['cms_html_path'].'main.php?area=con')."\" target=\"_top\"><font face=\"Verdana, Arial, Helvetica\" size=\"1\">".$mod_lang['side_overview']."</font></a></td></tr>'\n";
		}

		// Editor / Vorschau
		if ($view == 'preview'){
			$out .= "linkset['".$type_container.'_'.$type_number."_side_$type_typenumber']+='<tr><td class=\"menurow\" onclick=\"document.location.href=\'".$sess->url($cfg_client['htmlpath'].$cfg_client['contentfile'].'?lang='.$lang.'&idcat='.$idcat.'&idcatside='.$idcatside.'&view=edit')."\'\" onmouseover=\"on(\'".$mod_lang['side_edit']."\');return true;\" onmouseout=\"off();return true;\"><a href=\"".$sess->url($cfg_client['htmlpath'].$cfg_client['contentfile'].'?lang='.$lang.'&idcat='.$idcat.'&idcatside='.$idcatside.'&view=edit')."\"><font face=\"Verdana, Arial, Helvetica\" size=\"1\">".$mod_lang['side_edit']."</font></a></td></tr>'\n";
		}
		else{
			$out .= "linkset['".$type_container.'_'.$type_number."_side_$type_typenumber']+='<tr><td class=\"menurow\" onclick=\"document.location.href=\'".$sess->url($cfg_client['htmlpath'].$cfg_client['contentfile'].'?lang='.$lang.'&idcat='.$idcat.'&idcatside='.$idcatside.'&view=preview')."\'\" onmouseover=\"on(\'".$mod_lang['side_preview']."\');return true;\" onmouseout=\"off();return true;\"><a href=\"".$sess->url($cfg_client['htmlpath'].$cfg_client['contentfile'].'?lang='.$lang.'&idcat='.$idcat.'&idcatside='.$idcatside.'&view=preview')."\"><font face=\"Verdana, Arial, Helvetica\" size=\"1\">".$mod_lang['side_preview']."</font></a></td></tr>'\n";
		}
		$out .= "linkset['".$type_container.'_'.$type_number."_side_$type_typenumber']+='</table></td></tr></table>'\n";
		$out .= "</script>";
	}
	return $out;
}
?>