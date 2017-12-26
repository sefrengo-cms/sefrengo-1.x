<?PHP
// File: $Id: inc.mod_config.php 28 2008-05-11 19:18:49Z mistral $
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

/**
 * 1. Ben�tigte Funktionen und Klassen includieren
 */
include_once('inc/fnc.mod.php');
include_once('inc/fnc.mipforms.php');
/**
 * 2. Eventuelle Actions/ Funktionen abarbeiten
 */
$perm->check(4, 'mod', $idmod);
if ($idclient != 0 && $idclient != (int) $client || !isset($idclient)) $idclient = (int) $client;
/**
 * 3. Eventuelle Dateien zur Darstellung includieren
 */
include('inc/inc.header.php');
/**
 * 4. Bildschirmausgabe aufbereiten und ausgeben
 */
echo "<!-- Anfang inc.mod_config.php -->\n";
echo "<div id=\"main\">\n";
$BACK = "<div class=\"forms\"><a class=\"action\" href=\"" . $sess->url("main.php?area=mod&idmod=&idclient=$idclient") . "\">" . $cms_lang['gen_back'] . "</a></div>";
echo "".$CONFIG_GLOBAL.$CONFIG_CLIENT.$BACK."";

echo "    <h5>" . $cms_lang['area_mod_config'] . "</h5>";
if ($errno) echo "<p class=\"errormsg\">" . $cms_lang["err_$errno"] . "</p>";

// get modconfig
$modul = $rep->mod_data($idmod, $idclient);
if (is_array($modul)) {
    $modname = (empty($modul['verbose'])) ? htmlspecialchars($modul['name'], ENT_COMPAT, 'UTF-8') : htmlspecialchars($modul['verbose'], ENT_COMPAT, 'UTF-8');
    $modversion = (!empty($modul['version'])) ? ' (' . htmlspecialchars($modul['version'], ENT_COMPAT, 'UTF-8') . ')' : '';
    $modcat = htmlspecialchars($modul['cat'], ENT_COMPAT, 'UTF-8');
    $description = htmlspecialchars($modul['description'], ENT_COMPAT, 'UTF-8');
    $default = $modul['input'];
    $input = $modul['input'];
    $output = $modul['output'];
    if($action == 'change' && !$resetmod) $modconfig = make_array_to_urlstring($MOD_VAR);
    elseif (!$resetmod) $modconfig = $modul['config'];
}
$reset = '';
$list['id'][] = '';
$s_default = array(NULL);
if ($sess->is_registered('s_default')) $sess->unregister('s_default');
mip_forms_ob_start();
eval(' ?>' . $default);
$default_arr = mip_forms_get_array();
$default_arr = array_merge ($cms_mod, $default_arr);
mip_forms_ob_end();
$trans_html = get_html_translation_table(HTML_ENTITIES);
$trans_html = array_flip($trans_html);
if (is_array($default_arr)) foreach($default_arr as $test_key => $test_var) {
    if ($test_key == 'value') {
        foreach ($test_var as $val_key => $val_var) {
            if (!is_array($val_var)) $s_default[$val_key] = ($val_var != '') ? strtr($val_var, $trans_html) : '';
        }
        continue;
    }
    preg_match("/MOD_VAR\[([^\]]\d*)\]/", $test_key, $match_key);
    $s_default[$match_key['1']] = ($test_var != '') ? strtr($test_var, $trans_html) : '';
}
$sess->register('s_default');
// Alle verf�gbaren Layouts einlesen
$sql = "SELECT idlay FROM " . $cms_db['lay'] . " WHERE idclient='$client'";
$db->query($sql);
while ($db->next_record()) $idlay[] = $db->f('idlay');
if (is_array($idlay)) $idlay = implode(',', $idlay);
function extract_modconfig($in) {
    $keyandvalues = preg_split("/&/", $in);
    foreach ($keyandvalues as $kandv) {
        $extracted_pairs = explode('=', $kandv);
        $key = $extracted_pairs['0'];
        $value = $extracted_pairs['1'];
        $out[$key] = urldecode($value);
        $out[$key] = cms_stripslashes($out[$key]);
    }
    return $out;
}
$a = extract_modconfig($modconfig);
foreach ($a as $key => $value) {
    $cms_mod['value'][$key] = $value;
    $input = str_replace("MOD_VALUE[$key]", $value, $input);
}
//TODO - remove dedi backward compatibility
$dedi_mod =& $cms_mod;

echo "    <form name=\"editform\" onsubmit=\"document.editform.area.value='mod'\" method=\"post\" action=\"" . $sess->url("main.php") . "\">\n";
echo "    <input type=\"hidden\" name=\"area\" value=\"mod_config\" />\n";
echo "    <input type=\"hidden\" name=\"action\" value=\"save_config\" />\n";
echo "    <input type=\"hidden\" name=\"idclient\" value=\"$idclient\" />\n";
echo "    <input type=\"hidden\" name=\"idmod\" value=\"$idmod\" />\n";
echo "    <input type=\"hidden\" name=\"anchor\" value=\"\" />\n";
echo "    <table class=\"config\" cellspacing=\"1\">\n";
echo "      <tr valign=\"top\">\n";
echo "        <td class=\"head\" width=\"110\" valign=\"middle\"><p>" . $cms_lang['mod_modulename'] . "</p></td>\n";
echo "        <td class=\"headre\">\n<div class=\"forms\">\n<a class=\"action\" href=\"" . $sess->url("main.php?area=mod_config&idmod=" . $idmod . "&idclient=$idclient&resetmod=1") . "\"><img src=\"tpl/" . $cfg_cms['skin'] . "/img/but_resetform.gif\" alt=\"" . $cms_lang['mod_config_return'] . "\" title=\"" . $cms_lang['mod_config_return'] . "\" width=\"16\" height=\"16\" /></a>\n</div>\n$modname $modversion</td>\n";
echo "      </tr>\n";
// rechte management
if (!empty($idmod) && $perm->have_perm(6, 'mod', $idmod)) {
    $panel = $perm->get_right_panel('mod', $idmod, array('formname' => 'editform'), 'text');
	if (!empty($panel)) {
    echo "      <tr>\n";
    echo "        <td class=\"head\">" . $cms_lang['gen_rights'] . "</td>\n";
    echo "        <td>";
	echo implode("", $panel);
    echo "      </tr>\n";
	}
}
echo "      <tr valign=\"top\">\n";
echo "        <td class=\"head\" width=\"110\" nowrap>" . $cms_lang['mod_konfiguration'] . "</td>\n";
echo "        <td>\n";
// Konfiguration Data
eval(' ?>' . $input);
echo "        </td>\n";
echo "      </tr>\n";
if ($idclient >= 1) {
    // Einstellungen auf alle Templates anwenden, die das Modul enthalten
    echo "  <tr>\n";
    echo "    <td class=\"head\" valign=\"middle\">" . $cms_lang['gen_expand'] . "</td>\n";
    echo '    <td><input type="checkbox" name="mod_config_overwrite_all" value="1" id="touchme" /> <label for="touchme">' . $cms_lang['mod_config_all'] . '</label></td>';
    echo "  </tr>\n";
}

//buttons    
echo "      <tr>\n";
echo "        <td class='content7' colspan='2' style='text-align:right'>\n";
echo "        <input type='submit' name='sf_save' title='".$cms_lang['gen_save_titletext']."' value='".$cms_lang['gen_save']."' class=\"sf_buttonAction\"/>\n";
echo "        <input type='submit' name='sf_apply' title='".$cms_lang['gen_apply_titletext']."' value='".$cms_lang['gen_apply']."' class=\"sf_buttonAction\"/>\n";
echo "        <input type='button' name='sf_cancel' title='".$cms_lang['gen_cancel_titletext']."' value='".$cms_lang['gen_cancel']."' class=\"sf_buttonActionCancel\" onclick=\"window.location='".$sess->url('main.php?area=mod&idclient=' . $idclient)."'\"/>\n";
echo "        </td>\n";
echo "      </tr>\n";



echo "</table>\n";
echo "</form>\n";
echo "</div>\n";
echo '<div class="footer">'. $cms_lang['login_license'] .'</div>'."\n";
echo "</body>\n";
echo "</html>\n";
function eval_default($input) {
    global $db, $auth, $cms_db, $cfg_cms, $rep, $mod, $perm, $client, $lang;
    eval(' ?>' . $input);
}
?>
