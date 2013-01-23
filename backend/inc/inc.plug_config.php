<?PHP
// File: $Id: inc.plug_config.php 62 2008-10-31 18:29:40Z bjoern $
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
// + Revision: $Revision: 62 $
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
/**
 * 1. Benötigte Funktionen und Klassen includieren
 */
include_once('inc/fnc.plug.php');
/**
 * 2. Eventuelle Actions/ Funktionen abarbeiten
 */
if ($idclient != 0 && $idclient != (int) $client || !isset($idclient)) $idclient = (int) $client;
if (!$idplug && $cms_plugin) $idplug = $cms_plugin;
$perm_edit_settings = $idclient > 0 ? 'area_plug_settings' : 'area_plug_settings_general';
$perm_edit_settings_view = $idclient > 0 ? 'area_plug_settings_view' : 'area_plug_settings_view_general';
$perm->check('area_plug_conf');
// get plugconfig
$sql = "SELECT * FROM " . $cms_db['plug'] . " WHERE idplug='$idplug'";
$db->query($sql);
$db->next_record();
$plugname = $db->f('name');
$plugroot = $db->f('root_name');
$plugversion = $db->f('version');
$plugcat = $db->f('cat');
$description = $db->f('description');
$plugconfig = $db->f('config');
$list['id'][] = '';
/**
 * 3. Eventuelle Dateien zur Darstellung includieren
 */
include('inc/inc.header.php');
include('inc/class.values_ct_edit.php');
/**
 * 4. Bildschirmausgabe aufbereiten und ausgeben
 */
$BACK = "<a class=\"action\" href=\"" . $sess->url("main.php?area=plug&idplug=&idclient=$idclient") . "\" onmouseover=\"on('" . $cms_lang['gen_back'] . "');return true;\" onmouseout=\"off();return true;\">" . $cms_lang['gen_back'] . "</a>\n";
if($idclient >= 1) {
    if($perm->have_perm(18, 'plug', $idplug)) $CONFIG_GLOBAL = "<a class=\"action\" href=\"" . $sess->url("main.php?area=plug_config&idplug=$idplug&idclient=0") . "\" onmouseover=\"on('Globale" . $cms_lang['plug_konfiguration'] . "');return true;\" onmouseout=\"off();return true;\">Globale " . $cms_lang['plug_konfiguration'] . "</a> |\n";
} else {
    if($perm->have_perm(4, 'plug', $idplug) || $perm->have_perm(18, 'plug', $idplug)) $CONFIG_CLIENT = "<a class=\"action\" href=\"" . $sess->url("main.php?area=plug_config&idplug=$idplug&idclient=$client") . "\" onmouseover=\"on('Client " . $cms_lang['plug_konfiguration'] . "');return true;\" onmouseout=\"off();return true;\">Client " . $cms_lang['plug_konfiguration'] . "</a> |\n";
}
echo "<!-- Anfang inc.plug_config.php -->\n";
echo "<div id=\"main\">\n";
echo "<div class=\"forms\">\n" . $CONFIG_GLOBAL . $CONFIG_CLIENT . $BACK . "\n</div>\n";
echo "    <h5>".$cms_lang['area_plug_config']."</h5>\n";
if ($errno) echo "<p class=\"errormsg\">".$cms_lang["err_$errno"]."</p>\n";

function extract_plugconfig($in) {
    $keyandvalues = preg_split("/&/", $in);
    foreach ($keyandvalues as $kandv) {
        $extracted_pairs = explode('=', $kandv);
        $key = $extracted_pairs['0'];
        $value = $extracted_pairs['1'];
        $out[$key] = urldecode($value);
        remove_magic_quotes_gpc($out[$key]);
    } 
    return $out;
}
// todo:insert a full configuration module-table for plugin
$a = extract_plugconfig($plugconfig);
foreach ($a as $key => $value) {
    $cms_plug['value'][$key] = $value;
    $input = str_replace("MOD_VALUE[$key]", $value, $input);
} 
echo "    <form name=\"editform\" onsubmit=\"document.editform.area.value='plug'\" method=\"post\" action=\"" . $sess->url("main.php") . "\">\n";
echo "    <input type=\"hidden\" name=\"area\" value=\"plug_config\" />\n";
echo "    <input type=\"hidden\" name=\"action\" value=\"save_config\" />\n";
echo "    <input type=\"hidden\" name=\"idclient\" value=\"$idclient\" />\n";
echo "    <input type=\"hidden\" name=\"idplug\" value=\"$idplug\" />\n";
echo "    <input type=\"hidden\" name=\"anchor\" value=\"\" />\n";
echo "    <table class=\"config uber\" cellspacing=\"1\">\n";
echo "      <tr>\n";
echo "        <td class=\"head\"><p>" . $cms_lang['plug_pluginname'] . "</p></td>\n";
echo "        <td class=\"headre\" colspan=\"2\">$plugname $plugversion</td>\n";
echo "      </tr>\n";

//rechte bearbeiten
if (!empty($idplug) && $perm->have_perm(6, 'area_plug', 0)){
	$panel = $perm->get_right_panel('area_plug_'.$plugroot, $idplug, array('formname' => 'editform'), 'text', true);
	if (!empty($panel)) {
	    echo "	   <tr>\n";
	    echo "	     <td class=\"head\">" . $cms_lang['gen_rights'] . "</td>\n";
	    echo "	     <td colspan=\"2\">".implode("", $panel)."</td>\n";
	    echo "      </tr>\n";
	}
}    

// Konfiguration Data
if ($perm->have_perm($perm_edit_settings_view) || $perm->have_perm($perm_edit_settings)) {
    $output = &new values_ct_edit(array('sqlgroup' => $plugroot,
            'client' => $idclient,
            'lang' => '0',
            'perm_edit' => $perm_edit_settings,
            'tpl_file' => 'plug_settings.tpl',
            'table_cellpadding' => $cellpadding,
            'table_cellspacing' => $cellspacing,
            'table_border' => $border,
            'area' => 'plug_config',
            'cms_plugin' => $idplug,
            'action' => $action,
            'view' => 'use',
            'prefix' => '$cfg_' . $plugroot));
    $output->start();
    $old_cache = $tpl->clearCache;
    $tpl->clearCache = true;
    echo "      <tr>\n";
    echo "        <td class=\"head\">" . $cms_lang['plug_config_settings'] . "</td>\n";
    echo "        <td colspan=\"2\">\n";
    echo $return_tpl = $tpl->get('TABLE');
    $tpl->clearCache = $old_cache;
    if ($return_tpl == '') echo $cms_lang['plug_config_nosetting'];
    echo "        </td>\n";
    echo "      </tr>\n";
}
if ($idclient == '0' && $perm->have_perm($perm_edit_settings)) {
// Einstellungen auf alle Projekte anwenden, die das Plugin enthalten
echo "  <tr>\n";
echo "    <td class=\"head\">" . $cms_lang['gen_expand'] . "</td>\n";
echo '    <td><label for="touchme">' . $cms_lang['plug_config_all'] . '</label></td>';
echo '    <td><input type="checkbox" name="plug_config_overwrite_all" value="1" id="touchme"></td>';
echo "  </tr>\n";
}

//buttons    
echo "      <tr>\n";
echo "        <td class='content7' colspan='3' style='text-align:right'>\n";
echo "        <input type='submit' name='sf_save' title='".$cms_lang['gen_save_titletext']."' value='".$cms_lang['gen_save']."' class=\"sf_buttonAction\" onmouseover=\"this.className='sf_buttonActionOver'\" onmouseout=\"this.className='sf_buttonAction'\" />\n";
echo "        <input type='submit' name='sf_apply' title='".$cms_lang['gen_apply_titletext']."' value='".$cms_lang['gen_apply']."' class=\"sf_buttonAction\" onmouseover=\"this.className='sf_buttonActionOver'\" onmouseout=\"this.className='sf_buttonAction'\" />\n";
echo "        <input type='button' name='sf_cancel' title='".$cms_lang['gen_cancel_titletext']."' value='".$cms_lang['gen_cancel']."' class=\"sf_buttonAction\" onclick=\"window.location='".$sess->url("main.php?area=plug&idclient=" . $idclient)."'\" onmouseover=\"this.className='sf_buttonActionCancelOver'\" onmouseout=\"this.className='sf_buttonAction'\" />\n";
echo "        </td>\n";
echo "      </tr>\n";  

echo "</table>\n";
echo "</form>\n";
echo "</div>\n";
echo '<div class="footer">'. $cms_lang['login_licence'] .'</div>'."\n";
echo "</body>\n";
echo "</html>\n";

?>
