<?PHP
// File: $Id: inc.tpl.php 28 2008-05-11 19:18:49Z mistral $
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

/******************************************************************************
 1. Benötigte Funktionen und Klassen includieren
******************************************************************************/

include('inc/fnc.tpl.php');

/******************************************************************************
 2. Eventuelle Actions/ Funktionen abarbeiten
******************************************************************************/

$perm->check('area_tpl');
switch($action) {
	case 'delete':  // Template löschen
		$perm->check(5, 'tpl', $idtpl);
		$errno = tpl_delete_template($idtpl);
		break;
	case 'maketplstart':
		$errno = tpl_make_start_tpl((int) $client, (int) $idtpl);
		break;
}

/******************************************************************************
 3. Eventuelle Dateien zur Darstellung includieren
******************************************************************************/

include('inc/inc.header.php');

/******************************************************************************
 4. Bildschirmausgabe aufbereiten und ausgeben
******************************************************************************/


$tpl->loadTemplatefile('tpl.tpl');

$tmp['AREA'] = $cms_lang['area_tpl'];
$tmp['FOOTER_LICENSE'] = $cms_lang['login_licence'];

if ( $perm->have_perm(2, 'area_tpl', 0) ) $tmp['NEW_TEMPLATE'] = "\n<a class=\"action\" href=\"".$sess->url("main.php?area=tpl_edit")."\">".$cms_lang["tpl_actions"]["10"]."</a>";

if(! empty($errno)){
	$tpl -> setCurrentBlock('ERROR');
	$tpl_error['ERRORMESSAGE'] = $cms_lang['err_' . $errno];
	$tpl->setVariable($tpl_error);
	$tpl->parseCurrentBlock();
}

// Templatedatei laden

$tmp['TPL_TEMPLATENAME'] = $cms_lang['tpl_templatename'];
$tmp['TPL_DESCRIPTION'] = $cms_lang['tpl_description'];
$tmp['TPL_ACTION'] = $cms_lang['tpl_action'];
$tpl->setVariable($tmp);
unset($tmp);

// Template aus der Datenbank suchen
$tpl->setCurrentBlock('ENTRY');
$sql = "SELECT * FROM $cms_db[tpl] WHERE idclient='$client' ORDER BY name";
$db->query($sql);
$int_max = $db->affected_rows();
while ($db->next_record()) 
{
	$idtpl = $db->f('idtpl');
	
	//Darf Template sehen
	if( $perm->have_perm(1, 'tpl', $idtpl) ){
		// Hintergrundfarbe wählen
		$tmp['ENTRY_BGCOLOR'] = '#ffffff';
		$tmp['OVERENTRY_BGCOLOR'] = '#fff7ce';
		
		//Starttemplate festlegen
		if ( $perm->have_perm( 12, 'tpl', $idtpl) ){
			if ($db->f('is_start') == 1) {
				$tmp['ENTRY_STARTTPL'] = make_image_link('main.php?area=tpl&action=maketplstart&idtpl='.$db->f('idtpl'), 'but_start_yes.gif', $cms_lang['tpl_is_start'], '16', '16','','','');
			} else {
				$tmp['ENTRY_STARTTPL'] = make_image_link('main.php?area=tpl&action=maketplstart&idtpl='.$db->f('idtpl'), 'but_start_no.gif', $cms_lang['tpl_is_start'], '16', '16','','','');
			}
		}

		// Template dublizieren
		if ( $perm->have_perm(2, 'area_tpl', 0) && $perm->have_perm(3, 'tpl', $idtpl) ){
			$tmp['ENTRY_DUPLICATE'] = "\n<a href=\"".$sess->url("main.php?area=tpl_edit&action=duplicate&idtpl=".$db->f('idtpl'))."\">\n<img src=\"tpl/".$cfg_cms['skin']."/img/but_duplicate.gif\" alt=\"".$cms_lang['tpl_duplicate']."\" title=\"".$cms_lang['tpl_duplicate']."\" width=\"16\" height=\"16\" /></a>";
		}

		// Template bearbeiten
		if ( $perm->have_perm(3, 'tpl', $idtpl) ){
			$tmp['ENTRY_EDIT'] = "\n<a href=\"".$sess->url("main.php?area=tpl_edit&idtpl=".$db->f('idtpl'))."\">\n<img src=\"tpl/".$cfg_cms['skin']."/img/but_edit.gif\" alt=\"".$cms_lang['tpl_edit']."\" title=\"".$cms_lang['tpl_edit']."\" width=\"16\" height=\"16\" /></a>";
		}

		// Template löschen
		if ($db->f('deletable')=='1' && $perm->have_perm(5, 'tpl', $idtpl) ){
			$tmp['ENTRY_DELBUT'] = "\n<a href=\"".$sess->url('main.php?area=tpl&action=delete&idtpl='.$db->f('idtpl'))."\" onclick=\"return delete_confirm()\">\n<img src=\"tpl/".$cfg_cms['skin']."/img/but_deleteside.gif\" width=\"16\" height=\"16\" alt=\"".$cms_lang['tpl_delete']."\" title=\"".$cms_lang['tpl_delete']."\" /></a>";
		}
		else{
			$tmp['ENTRY_DELBUT'] = "\n<img src=\"tpl/".$cfg_cms['skin']."/img/space.gif\" width=\"16\" height=\"16\" />";
		}
		$tmp['ENTRY_ICON'] = make_image('but_template.gif', '', '16', '16', false, 'class="icon"');
		$tmp['ENTRY_NAME'] = htmlentities($db->f('name'), ENT_COMPAT, 'UTF-8');
		$tmp['ENTRY_DESCRIPTION'] = htmlentities($db->f('description'), ENT_COMPAT, 'UTF-8');
		$tpl->setVariable($tmp);
		$tpl->parseCurrentBlock();
		unset($tmp);
	}
}

$tpl->setCurrentBlock('NOENTRY');
if ($int_max < 1) {
	$tmp['TPL_NOTEMPLATES'] = $cms_lang['tpl_notemplates'];
	$tpl->setVariable($tmp);
	$tpl->parseCurrentBlock();
	unset($tmp);
}
?>
