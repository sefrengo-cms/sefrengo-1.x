<?PHP
// File: $Id: inc.mod_edit.php 37 2008-05-12 13:26:12Z mistral $
// +----------------------------------------------------------------------+
// | Version: Sefrengo $Name:  $                                          
// +----------------------------------------------------------------------+
// | Copyright (c) 2005 - 2006 sefrengo.org <info@sefrengo.org>           |
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
// + Revision: $Revision: 37 $
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
include_once('inc/fnc.mod.php');
/**
 * 2. Eventuelle Actions/ Funktionen abarbeiten
 */
if ($idclient != 0 && $idclient != (int) $client || !isset($idclient)) $idclient = (int) $client;
if (is_numeric($idmod)) $perm->check(3, 'mod', $idmod);
else $perm->check(3, 'area_mod', 0);
$sql = "SELECT DISTINCT cat FROM ". $cms_db['mod'];
$cat_arr = $rep->_fetch_query($sql);
/**
 * 3. Eventuelle Dateien zur Darstellung includieren
 */
include('inc/inc.header.php');
/**
 * 4. Bildschirmausgabe aufbereiten und ausgeben
 */
if (is_numeric($idmod)) {
    $modul = $rep->mod_data($idmod, $idclient);
}
if (is_array($s_modul)) {
    $modul = ( is_array($modul) ) ? array_merge($modul, $s_modul) : $s_modul;
    if ($sess->is_registered('s_modul')) $sess->unregister('s_modul');
}
if (is_array($modul)) {
    $modname = $modul['name'];
	$modverbose = $modul['verbose'];
    $modversion = $modul['version'];
    $modcat = $modul['cat'];
    $description = $modul['description'];
    $input =  $modul['input'];
    $output = $modul['output'];
    $source = $modul['source_id'];
    $repository_id =  $modul['repository_id'];
    $sql_install = cms_stripslashes($rep->encode_sql($modul['install_sql']));
    $sql_uninstall = cms_stripslashes($rep->encode_sql($modul['uninstall_sql']));
    $sql_update = cms_stripslashes($rep->encode_sql($modul['update_sql']));
}
if (!is_numeric($idmod) && is_array($modul)) $errno = ($errno) ? $errno : '0400';
if (($err_i = $rep->mod_test($input, $idmod))) $error = sprintf($cms_lang['err_0416'], 'Input', $err_i);
if (($err_o = $rep->mod_test($output, $idmod))) $error = ($error != '') ? $error . ';  ' . sprintf($cms_lang['err_0416'], 'Output', $err_o) : sprintf($cms_lang['err_0416'], 'Output', $err_o);

// Modul dublizieren
if($action == 'duplicate') {
    $idmod_for_form = '';
    if ($idclient >= 1) $modverbose = $cms_lang['tpl_copy_of'] . $modname; 
    $source_for_form = $idmod;
} elseif($mod_no_wedding) {
    $idmod_for_form = '';
    $source_for_form = 0;
    $error .= $cms_lang['err_0425'];
} else {
    $idmod_for_form = $idmod;
    $source_for_form = $source;
    if ($idmod) $noeeditname = 'readonly';
}
if  ($source_for_form > 0) {
	if ($idclient >= 1) $noeeditversion = 'readonly';
	$parent  = $rep->mod_data($source_for_form, 'all');
	if ($idclient >= 1) $modname = htmlspecialchars($parent['name'], ENT_COMPAT, 'UTF-8'); else $modname = $cms_lang['tpl_copy_of'] . $modname;
	$modname_version = (!empty($parent['version'])) ? ' (' . htmlspecialchars($parent['version'], ENT_COMPAT, 'UTF-8') . ')' : '';
	if (empty($modverbose)) $modverbose = htmlspecialchars($modname, ENT_COMPAT, 'UTF-8');
    $wedding = sprintf($cms_lang['mod_no_wedding'], htmlspecialchars($modname, ENT_COMPAT, 'UTF-8'), htmlspecialchars($modname_version, ENT_COMPAT, 'UTF-8'));
}
 
// Menue
echo "<!-- Anfang inc.mod_edit.php -->\n";
echo "<div id=\"main\">\n";

//make and print nav links
$tmp['BACK'] = "<a class=\"action\" href=\"" . $sess->url("main.php?area=mod&idclient=$idclient") . "\">" . $cms_lang['gen_back'] . "</a>\n";
if ($editsql != '1' && $idmod >= 1 && empty($source_for_form)) {
    $tmp['NEW_SQL'] = "| <a class=\"action\" href=\"" . $sess->url("main.php?area=mod_edit&idmod=$idmod&idclient=$idclient&editsql=1") . "\">" . $cms_lang['mod_new_sql'] . "</a>\n";
} else if ($idmod >= 1 && empty($source_for_form)) {
	$tmp['NEW_SQL'] = "| <a class=action href=\"" . $sess->url("main.php?area=mod_edit&idmod=$idmod&idclient=$idclient&editsql=0") . "\">" . $cms_lang['mod_edit'] . "</a>\n";
}
echo "\n<div class=\"forms\">" . $tmp['BACK'] . $tmp['NEW_SQL'] . "</div>\n";

//find and print area name
if ($editsql != '1') {
	if ($idmod >= 1) {
		if ($action == 'duplicate') {
			echo "\n<h5>" . $cms_lang['area_mod_duplicate'] . "</h5>\n";
		} else {
			echo "\n<h5>" . $cms_lang['area_mod_edit'] . "</h5>\n";
		}
	} else {
		echo "\n<h5>" . $cms_lang['area_mod_new'] . "</h5>\n";
	}
} else {
	echo "\n<h5>" . $cms_lang['area_mod_edit_sql'] . "</h5>\n";
}

//print error
if ($errno) echo "<p class=\"errormsg\">" . $cms_lang["err_$errno"] . "</p>\n";
if ($error) echo "<p class=\"errormsg\">" . $error . "</p>\n";

    // Formular zum bearbeiten der Module
    echo "    <form name=\"newmodule\" method=\"post\" action=\"" . $sess->url("main.php") . "\">\n";
    echo "    <input type=\"hidden\" name=\"area\" value=\"mod\" />\n";
    echo "    <input type=\"hidden\" name=\"action\" value=\"save\" />\n";
    echo "    <input type=\"hidden\" name=\"oldversion\" value=\"" . htmlspecialchars($modversion, ENT_COMPAT, 'UTF-8') . "\" />\n";
    echo "    <input type=\"hidden\" name=\"idmod\" value=\"$idmod_for_form\" />\n";
    echo "    <input type=\"hidden\" name=\"source\" value=\"$source_for_form\" />\n";
    echo "    <input type=\"hidden\" name=\"idclient\" value=\"" . (string)$idclient . "\" />\n";
    echo "    <input type=\"hidden\" name=\"repository_id\" value=\"" . (string)$repository_id . "\" />\n";
    echo "    <input type=\"hidden\" name=\"mod_no_wedding\" value=\"" . $mod_no_wedding . "\" />\n";
    if ($editsql != '1') {
        echo "    <input type=\"hidden\" name=\"install_sql\" value=\"" . htmlspecialchars($sql_install, ENT_COMPAT, 'UTF-8') . "\" />\n";
        echo "    <input type=\"hidden\" name=\"uninstall_sql\" value=\"" . htmlspecialchars($sql_uninstall, ENT_COMPAT, 'UTF-8') . "\" />\n";
        echo "    <input type=\"hidden\" name=\"update_sql\" value=\"" . htmlspecialchars($sql_update, ENT_COMPAT, 'UTF-8') . "\" />\n";
    } else {
        $noeeditversion = $noeditfield = 'readonly';
        echo "    <input type=\"hidden\" name=\"input\" value=\"" . htmlspecialchars($input, ENT_COMPAT, 'UTF-8') . "\" />\n";
        echo "    <input type=\"hidden\" name=\"output\" value=\"" . htmlspecialchars($output, ENT_COMPAT, 'UTF-8') . "\" />\n";
        echo "    <input type=\"hidden\" name=\"modcat\" value=\"" . htmlspecialchars($modcat, ENT_COMPAT, 'UTF-8') . "\" />\n";
    }
    if ($noeeditversion) {
        $noeeditname = $noeeditversion;
        echo "    <input type=\"hidden\" name=\"modversion\" value=\"" . htmlspecialchars($modversion, ENT_COMPAT, 'UTF-8') . "\" />\n";
        echo "    <input type=\"hidden\" name=\"modverbose\" value=\"" . htmlspecialchars($modverbose, ENT_COMPAT, 'UTF-8') . "\" />\n";
    }
    if ($noeeditname) {
        echo "    <input type=\"hidden\" name=\"modname\" value=\"" . htmlspecialchars($modname, ENT_COMPAT, 'UTF-8') . "\" />\n";
    }
	if ($idclient == 0 && ($perm->have_perm(7, 'mod', $idmod) || $perm->have_perm(8, 'mod', $idmod)) && $source_for_form > 0) {
		echo "    <input type=\"hidden\" name=\"mod_no_wedding\" value=\"1\" />\n";
	}
echo "    <table class=\"config\" cellspacing=\"1\">\n";
    echo "	   <tr>\n";
    echo "	     <td class=\"head\">\n<p>"; 
    if (!empty($source_for_form)) {
    	echo $cms_lang["mod_parent"]; 
    } else {
    	echo $cms_lang["mod_modulename"];
    } 
    
    echo "</p>\n</td>\n";
    	
    if (!$noeeditname) {
    	echo "	     <td><input class=\"w600\" type=\"text\" name=\"modname\" value=\"" . $modname . "\" size=\"90\" /></td>\n";
    } else {
        echo "          <td class=\"headre\">";
        // Modul vom Original trennen
        if ($idclient >= 1 && ($perm->have_perm(7, 'mod', $idmod) || $perm->have_perm(8, 'mod', $idmod)) && $source_for_form > 0) {
        	 echo "<div class=\"forms\">\n<a class=\"action\" href=\"" . $sess->url("main.php?area=mod_edit&idmod=" . $idmod . "&idclient=" . $idclient . "&mod_no_wedding=1&action=" . $action) . "\"><img src=\"tpl/" . $cfg_cms['skin'] . "/img/but_resetform.gif\" alt=\"" . $wedding . "\" title=\"" . $wedding . "\" width=\"16\" height=\"16\" /></a>\n</div>\n";
        }
        echo '<p>'. $modname . $modname_version .'</p>';
        echo "</td>\n";
    }
    echo "      </tr>\n";
	if ($idclient >= 1 && $idmod >= 1) {
		echo "	   <tr>\n";
		echo "	     <td class=\"head\">" . $cms_lang["mod_verbosename"] . "</td>\n";
		if ($editsql != '1') echo "	     <td><input class=\"w600\" type=\"text\" name=\"modverbose\" value=\"" . htmlspecialchars($modverbose, ENT_COMPAT, 'UTF-8') . "\" size=\"90\" /></td>\n";
		else echo "	     <td>" . htmlspecialchars($modverbose, ENT_COMPAT, 'UTF-8') . "</td>\n";
		echo "      </tr>\n";
	}
	
    // rechte management
    // change JB: Kein Panel ohne Gruppen
    if (!empty($idmod) && $perm->have_perm(6, 'mod', $idmod) && $editsql != '1') {
        $panel = $perm->get_right_panel('mod', $idmod, array('formname' => 'newmodule'), 'text');
		if (!empty($panel)) {
        echo "      <tr>\n";
        echo "        <td class=\"head\">" . $cms_lang['gen_rights'] . "</td>\n";
        echo "        <td>";
	        echo implode("", $panel);
        echo "      </tr>\n";
		}
    } 

    echo "	   <tr>\n";
    echo "	     <td class=\"head\">" . $cms_lang["mod_version"] . "</td>\n";
    if (!$noeeditversion) echo "	     <td><input class=\"w600\" $noeeditversion type=\"text\" name=\"modversion\" value=\"" . htmlspecialchars($modversion) . "\" size=\"90\" maxlength=\"60\" /></td>\n";
    else echo "	     <td>" . htmlspecialchars($modversion, ENT_COMPAT, 'UTF-8') . "</td>\n";
    echo "      </tr>\n";
    echo "	   <tr>\n";
    echo "	     <td class=\"head\">" . $cms_lang["mod_cat"] . "</td>\n";
    echo "	     <td>";
    if (!$noeditfield) echo "          <input class=\"w200\" type=\"text\" name=\"modcat\" value=\"" . htmlspecialchars($modcat, ENT_COMPAT, 'UTF-8') . "\" maxlength=\"60\" size=\"90\" />\n";
    else echo htmlspecialchars($modcat, ENT_COMPAT, 'UTF-8') . "\n";
    if (!$noeditfield) {
        echo " ".$mod_lang['gen_select'].": <select onchange=\"document.newmodule.modcat.value=(this.value);\" name=\"catchange\" maxlength=\"60\" size=\"1\">\n";
        echo "<option value=\"\"></option>\n";
        foreach($cat_arr as $cat_art) {
            if (empty($cat_art['cat'])) continue;
            $cat_value = $cat_art['cat'];
            if($cat_value == '') continue;
            if($cat_value == htmlspecialchars($modcat, ENT_COMPAT, 'UTF-8')) $cat_selected = ' selected '; else $cat_selected = '';
            echo "<option value=\"".htmlspecialchars($cat_value, ENT_COMPAT, 'UTF-8')."\"$cat_selected>".htmlspecialchars($cat_value)."</option>\n";
        }
        echo "</select>\n";
    }
    echo "</td>\n";
    echo "      </tr>\n";
    echo "      <tr>\n";
    echo "        <td class=\"head\">" . $cms_lang["mod_description"] . "</td>\n";
    echo "	     <td><textarea class=\"w800\" $noeditfield name=\"description\" rows=\"3\" cols=\"52\">" . htmlspecialchars($description, ENT_COMPAT, 'UTF-8') . "</textarea></td>\n";
    echo "	   </tr>\n";
    if ($editsql == '1') {
        echo "      <tr>\n";
        echo "        <td class=\"head\">" . $cms_lang["mod_sql_install"] . "</td>\n";
        echo "        <td><textarea class=\"w800\" name=\"install_sql\" rows=\"14\" cols=\"52\" wrap=\"off\">" . htmlspecialchars($sql_install, ENT_COMPAT, 'UTF-8') . "</textarea></td>\n";
        echo "	   </tr>\n";
        echo "      <tr>\n";
        echo "        <td class=\"head\">" . $cms_lang["mod_sql_uninstall"] . "</td>\n";
        echo "        <td><textarea class=\"w800\" name=\"uninstall_sql\" rows=\"14\" cols=\"52\" wrap=\"off\">" . htmlspecialchars($sql_uninstall, ENT_COMPAT, 'UTF-8') . "</textarea></td>\n";
        echo "	   </tr>\n";
        echo "      <tr>\n";
        echo "        <td class=\"head\">" . $cms_lang["mod_sql_update"] . "</td>\n";
        echo "        <td><textarea class=\"w800\" name=\"update_sql\" rows=\"14\" cols=\"52\" wrap=\"off\">" . htmlspecialchars($sql_update, ENT_COMPAT, 'UTF-8') . "</textarea></td>\n";
        echo "	   </tr>\n"; 
        // rechte import und export werden benötigt
        if ($idclient >= 1 && ($perm->have_perm(7, 'mod', $idmod) || $perm->have_perm(8, 'mod', $idmod))) {
            // Sql erneut installieren
            echo "  <tr>\n";
            echo "    <td class=\"head\">" . $cms_lang['gen_expand'] . "</td>\n";
            echo '    <td><input type="checkbox" name="mod_rebuild_sql" value="1" id="touchme"><label for="touchme">' . $cms_lang['mod_rebuild_sql'] . '</label></td>';
            echo "  </tr>\n";
        } 
    } else {
        echo "      <tr>\n";
        echo "	     <td class=\"" . (($err_i) ? 'headerror' : 'head') . "\">" . $cms_lang["mod_input"] . "</td>\n";
        echo "        <td><textarea class=\"w800\" name=\"input\" rows=\"14\" cols=\"52\" wrap=\"off\">" . htmlspecialchars($input, ENT_COMPAT, 'UTF-8') . "</textarea></td>\n";
        echo "	   </tr>\n";
        echo "      <tr>\n";
        echo "        <td class=\"" . (($err_o) ? 'headerror' : 'head') . "\">" . $cms_lang["mod_output"] . "</td>\n";
        echo "        <td><textarea class=\"w800\" name=\"output\" rows=\"14\" cols=\"52\" wrap=\"off\">" . htmlspecialchars($output, ENT_COMPAT, 'UTF-8') . "</textarea></td>\n";
        echo "	   </tr>\n";
        if (!empty($idmod) && $error) {
            // Modul trotz Fehler speichern
            echo "  <tr>\n";
            echo "    <td class=\"head\">" . $cms_lang['gen_expand'] . "</td>\n";
            echo '    <td><input type="checkbox" name="s_overide" value="1" id="s_overide">'."\n".'<label for="s_overide">' . sprintf($cms_lang['mod_s_overide'], (($err_i) ? (($err_o) ? 'Input/Output' : 'Input') : 'Output')) . '</label>'."\n".'<img src="tpl/' . $cfg_cms['skin'] . '/img/space.gif" width="18" height="18" /></td>';
            echo "  </tr>\n";
        } 
    }

	if ($action == 'duplicate') {
		// Einstellungen auf neues Modul übernehmen
		echo "  <tr>\n";
		echo "    <td class=\"head\" valign=\"middle\">" . $cms_lang['gen_expand'] . "</td>\n";
		echo '    <td><input type="checkbox" name="mod_config_takeover" value="1" id="touchme" /> <label for="touchme">' . $cms_lang['mod_config_save'] . '</label></td>';
		echo "  </tr>\n";
	}

	//buttons    
	echo "      <tr>\n";
	echo "        <td class='content7' colspan='2' style='text-align:right'>\n";
	echo "        <input type='submit' name='sf_save' title='".$cms_lang['gen_save_titletext']."' value='".$cms_lang['gen_save']."' class=\"sf_buttonAction\"/>\n";
	echo "        <input type='submit' name='sf_apply' title='".$cms_lang['gen_apply_titletext']."' value='".$cms_lang['gen_apply']."' class=\"sf_buttonAction\"/>\n";
	echo "        <input type='button' name='sf_cancel' title='".$cms_lang['gen_cancel_titletext']."' value='".$cms_lang['gen_cancel']."' class=\"sf_buttonActionCancel\" onclick=\"window.location='".$sess->url("main.php?area=mod&idclient=$idclient")."'\" />\n";
	echo "        </td>\n";
	echo "      </tr>\n";
    
    echo "    </table>\n";
    echo "    </form>\n";
    echo "    </div>\n";
    echo '<div class="footer">'. $cms_lang['login_licence'] .'</div>'."\n";
    echo "  </body>\n";
    echo "</html>\n";
?>