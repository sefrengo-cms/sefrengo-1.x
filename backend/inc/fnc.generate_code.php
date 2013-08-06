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

	include_once('class.popupmenubuilder_js.php');
	$p_menu = new popupmenubuilder_js();
	
	// Bearbeitungsrecht auf dieser Seite?
	if ($perm->have_perm(19, 'side', $idcatside, $con_side[$idcatside]['idcat']) || $perm->have_perm(2, 'cat' , $con_side[$idcatside]['idcat']))
	{
		$p_menu->set_image('cms/img/but_editside.gif', 16, 16);


		// Seite
		if (($idcatside && $perm->have_perm(18, 'side', $idcatside, $idcat)) || ($idcatside && $perm->have_perm(19, 'side', $idcatside, $idcat)) || ($perm->have_perm('10', 'cat', $idcat) || $perm->have_perm('11', 'cat', $idcat)) || ($idcatside && $perm->have_perm('12', 'cat', $idcat)))
		{
			$p_menu->add_title($mod_lang['type_edit_side']);
		}

		// Seite konfigurieren
		if ($idcatside && $perm->have_perm(20, 'side', $idcatside, $idcat))
		{

			$entry = $mod_lang['side_config'];
			$link = $sess->url($cfg_cms['cms_html_path'].'main.php?area=con_configside&view='.$view.'&idcatside='.$idcatside.'&idside='.$con_side[$idcatside]['idside'].'&idcat='.$con_side[$idcatside]['idcat'].'&idtplconf='.$con_side[$idcatside]['idtplconf']);
			$target = '_self';
			$mouseover_text = $mod_lang['side_config'];
			$optional_js = '';
			$p_menu->add_entry($entry, $link, $target, $mouseover_text, $optional_js);
		}

		// Seite anlegen
		if ($perm->have_perm('18', 'cat', $idcat) )
		{
			$entry = $mod_lang['side_new'];
			$link = $sess->url($cfg_cms['cms_html_path'].'main.php?area=con_configside&view='.$view.'&idcat='.$idcat.'&idtplconf=0');
			$target = '_self';
			$mouseover_text = $mod_lang['side_new'];
			$optional_js = '';
			$p_menu->add_entry($entry, $link, $target, $mouseover_text, $optional_js);
		}

		// Seite löschen
		if ($idcatside && $perm->have_perm(21, 'side', $idcatside, $idcat))
		{
			$entry = $mod_lang['side_delete'];
			$link = $sess->url($cfg_cms['cms_html_path'].'main.php?area=con&view='.$view.'&action=side_delete&idcat='.$idcat.'&idside='.$con_side[$idcatside]['idside']);
			$target = '_self';
			$mouseover_text = $mod_lang['side_delete'];
			$optional_js = 'return delete_confirm();';
			$p_menu->add_entry($entry, $link, $target, $mouseover_text, $optional_js);
		}

		// Ordner
		if ($perm->have_perm('2', 'cat', $idcat) || ($perm->have_perm('3', 'cat', $idcat) || $perm->have_perm('4', 'cat', $idcat)) || (!$idcatside && $perm->have_perm('5', 'cat', $idcat)))
		{
			$p_menu->add_title($mod_lang['type_edit_folder']);
		}

		// Ordner konfigurieren
		if ($perm->have_perm('2', 'cat', $idcat))
		{
			$entry = $mod_lang['side_config'];
			$link = $sess->url($cfg_cms['cms_html_path'].'main.php?area=con_configcat&view='.$view.'&idcat='.$idcat.'&idcatside='.$idcatside.'&idcatlang='.$con_tree[$idcat]['idcatlang'].'&idtplconf='.$con_tree[$idcat]['idtplconf']);
			$target = '_self';
			$mouseover_text = $mod_lang['side_config'];
			$optional_js = '';
			$p_menu->add_entry($entry, $link, $target, $mouseover_text, $optional_js);
		}

		// Ordner anlegen
		if ($perm->have_perm('3', 'cat', $idcat) || $perm->have_perm('4', 'cat', $idcat))
		{
			$entry = $mod_lang['side_new'];
			$link = $sess->url($cfg_cms['cms_html_path'].'main.php?area=con_configcat&view='.$view.'&idcatside='.$idcatside.'&parent='.$idcat.'&idtplconf=0');
			$target = '_self';
			$mouseover_text = $mod_lang['side_new'];
			$optional_js = '';
			$p_menu->add_entry($entry, $link, $target, $mouseover_text, $optional_js);
		}

		// Ordner löschen
		if (!$idcatside && $perm->have_perm('5', 'cat', $idcat))
		{
			$entry = $mod_lang['side_delete'];
			$link = $sess->url($cfg_cms['cms_html_path'].'main.php?area=con&view='.$view.'&action=cat_delete&idcat='.$idcat);
			$target = '_self';
			$mouseover_text = $mod_lang['side_delete'];
			$optional_js = 'return delete_confirm();';
			$p_menu->add_entry($entry, $link, $target, $mouseover_text, $optional_js);
		}

		// Modus
		$p_menu->add_title($mod_lang['side_mode']);


		// Seitenübersicht
		if ($perm->have_perm('1', 'area_con'))
		{
			$entry = $mod_lang['side_overview'];
			$link = $sess->url($cfg_cms['cms_html_path'].'main.php?area=con');
			$target = '_top';
			$mouseover_text = $mod_lang['side_overview'];
			$optional_js = '';
			$p_menu->add_entry($entry, $link, $target, $mouseover_text, $optional_js);
		}

		// Editor / Vorschau
		if ($view == 'preview')
		{
			$entry = $mod_lang['side_edit'];
			$link = $sess->url($cfg_client['htmlpath'].$cfg_client['contentfile'].'?lang='.$lang.'&idcat='.$idcat.'&idcatside='.$idcatside.'&view=edit');
			$target = '_self';
			$mouseover_text = $mod_lang['side_edit'];
			$optional_js = '';
			$p_menu->add_entry($entry, $link, $target, $mouseover_text, $optional_js);
		}
		else
		{
			$entry = $mod_lang['side_preview'];
			$link = $sess->url($cfg_client['htmlpath'].$cfg_client['contentfile'].'?lang='.$lang.'&idcat='.$idcat.'&idcatside='.$idcatside.'&view=preview');
			$target = '_self';
			$mouseover_text = $mod_lang['side_preview'];
			$optional_js = '';
			$p_menu->add_entry($entry, $link, $target, $mouseover_text, $optional_js);
		}
	}
	
	return $p_menu->get_menu_and_flush();
}
?>