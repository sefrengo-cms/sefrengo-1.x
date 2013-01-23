<?PHP
// File: $Id: inc.plug_edit.php 28 2008-05-11 19:18:49Z mistral $
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
 * 1. Benötigte Funktionen und Klassen includieren
 */
include_once('inc/fnc.plug.php');
/**
 * 2. Eventuelle Actions/ Funktionen abarbeiten
 */
if ($idclient != 0 && $idclient != (int) $client || !isset($idclient)) $idclient = (int) $client;
if (is_numeric($idplug)) $perm->check(3, 'plug', $idplug);
else $perm->check(3, 'area_plug', 0);
$sql = "SELECT DISTINCT cat FROM ". $cms_db['plug'];
$cat_arr = $rep->_fetch_query($sql);
/**
 * 3. Eventuelle Dateien zur Darstellung includieren
 */
include('inc/inc.header.php');
/**
 * 4. Bildschirmausgabe aufbereiten und ausgeben
 */
echo "<!-- Anfang inc.plug_edit.php -->\n";
echo "<div id=\"main\">\n";

    if (is_numeric($idplug)) {
        $plugin = $rep->plug_data($idplug, $idclient);
        if (is_array($plugin)) {
            $plugname      = $plugin['name'];
            $plugversion   = $plugin['version'];
            $plugcat       = $plugin['cat'];
            $description   = $plugin['description'];
            $plugroot      = $plugin['root_name'];
            $plugindex     = $plugin['index_file'];
            $repository_id = $plugin['repository_id'];
            $sql_install   = $rep->plug_execute($idplug, 'this', 'get', 'install');
            $sql_uninstall = $rep->plug_execute($idplug, 'this', 'get', 'uninstall');
            $sql_update    = $rep->plug_execute($idplug, 'this', 'get', 'update');
            if ($idclient >= 1) $noeeditversion = 'readonly';
        } else $errno = '1600';
    } elseif ($name != '') {
        $filecontent = $rep->_file($cfg_cms['cms_path'] . 'plugins/' . $name . '/' . $name . '.cmsplug');
        if(is_array($xml   = $rep->cms_plug($filecontent))) {
            $plugname      = $xml['name'];
            $plugversion   = $xml['version'];
            $plugcat       = $xml['cat'];
            $repository_id = $xml['repository_id'];
            $description   = $xml['description'];
            $plugroot      = $xml['root_name'];
            $plugindex     = $xml['index_file'];
            $noeeditversion = 'readonly';
        } else $errno = '1613';
    } else {
        $plugroot = '';
        $newplugin = true;
    }
    // remove '-1' for no existing File
    $sql_install   = ($sql_install != '-1')   ? $sql_install : '';
    $sql_uninstall = ($sql_uninstall != '-1') ? $sql_uninstall : '';
    $sql_update    = ($sql_update != '-1')    ? $sql_update : '';
    $tmp['BACK'] = "<div class=\"forms\">\n<a class=\"action\" href=\"" . $sess->url("main.php?area=plug&idclient=$client") . "\" onmouseover=\"on('" . $cms_lang['gen_back'] . "');return true;\" onmouseout=\"off();return true;\">" . $cms_lang['gen_back'] . "</a>";
    if ($editsql != '1' && $idplug >= 1 && $idclient == 0) {
        $tmp['NEW_SQL'] = " | <a class=\"action\" href=\"" . $sess->url("main.php?area=plug_edit&idplug=$idplug&idclient=$idclient&editsql=1") . "\" onmouseover=\"on('" . $cms_lang['plug_new_sql'] . "');return true;\" onmouseout=\"off();return true;\">" . $cms_lang['plug_new_sql'] . "</a>";
    } elseif($idplug >= 1 && $idclient == 0 && $editsql) $tmp['NEW_SQL'] = " | <a class=action href=\"" . $sess->url("main.php?area=plug_edit&idplug=$idplug&idclient=$idclient&editsql=0") . "\" onmouseover=\"on('" . $cms_lang['plug_edit'] . "');return true;\" onmouseout=\"off();return true;\">" . $cms_lang['plug_edit'] . "</a>";
    elseif(!$import && empty($idplug)) $tmp['NEW_SQL'] = " | <a class=\"action\" href=\"" . $sess->url("main.php?area=plug_edit&idplug=&idclient=$idclient&import=1") . "\" onmouseover=\"on('" . $cms_lang['plug_import_path'] . "');return true;\" onmouseout=\"off();return true;\">" . $cms_lang['plug_import_path'] . "</a>";
    #else $tmp['BACK'] = "<a class=\"action\" href=\"" . $sess->url("main.php?area=plug_edit&idplug=&idclient=$idclient") . "\" onmouseover=\"on('" . $cms_lang['gen_back'] . "');return true;\" onmouseout=\"off();return true;\">" . $cms_lang['gen_back'] . "</a>";
    $idclient = !$idclient ? '0' : $idclient;
    echo "" . $tmp['BACK'] . $tmp['NEW_SQL'] . "";
    echo "  </div>\n";

    echo "  <h5>\n";
if ($editsql != '1') if ($idplug >= 1) echo $cms_lang['area_plug_edit'];
    else (($name != '' || $import) ? print($cms_lang['area_plug_new_import']) : print($cms_lang['area_plug_new_create']));
    else echo $cms_lang['area_plug_edit_sql'];
    echo "  </h5>\n";

    $okmessages = array(1602, 1605, 1606, 1607, 1608, 1609, 1610, 1611, 1612, 1614);
	$warnmessages = array(1617, 1618, 1619);
    if ($errno) {
    	if (in_array($errno, $okmessages)) {
    		$messageclass = 'ok';
    	} else if (in_array($errno, $okmessages)) {
    		$messageclass = 'warning';
    	} else {
    		$messageclass = 'errormsg';
    	}
    	echo "<p class=\"$messageclass\">" . $cms_lang["err_$errno"] . "</p>";
    }
    

    // Formular zum Reload
    echo "    <form name=\"reload\" method=\"post\" action=\"" . $sess->url("main.php?area=plug_edit&idclient=$idclient&newplugin=$newplugin") . "\">\n";
    echo "    <input type=\"hidden\" name=\"name\" value=\"$name\" />\n";
    echo "    <input type=\"hidden\" name=\"import\" value=\"$import\" />\n";
    echo "    </form>\n";
    // Formular zum bearbeiten der Plugins
    echo "    <form name=\"newplugin\" method=\"post\" action=\"" . $sess->url("main.php") . "\">\n";
    echo "    <input type=\"hidden\" name=\"area\" value=\"plug\" />\n";
    echo "    <input type=\"hidden\" name=\"action\" value=\"save\" />\n";
    echo "    <input type=\"hidden\" name=\"idplug\" value=\"$idplug\" />\n";
    echo "    <input type=\"hidden\" name=\"repid\" value=\"$repository_id\" />\n";
    echo "    <input type=\"hidden\" name=\"idclient\" value=\"$idclient\" />\n";
    echo "    <input type=\"hidden\" name=\"editsql\" value=\"$editsql\" />\n";
    if ($editsql != '1' && !$newplugin) {
        echo "    <input type=\"hidden\" name=\"install_sql\" value=\"" . htmlspecialchars($sql_install, ENT_COMPAT, 'UTF-8') . "\" />\n";
        echo "    <input type=\"hidden\" name=\"uninstall_sql\" value=\"" . htmlspecialchars($sql_uninstall, ENT_COMPAT, 'UTF-8') . "\" />\n";
        echo "    <input type=\"hidden\" name=\"update_sql\" value=\"" . htmlspecialchars($sql_update, ENT_COMPAT, 'UTF-8') . "\" />\n";
    } elseif (!$newplugin) {
        $noeeditversion = $noeditfield = 'readonly';
        echo "    <input type=\"hidden\" name=\"plugcat\" value=\"" . htmlspecialchars($plugcat, ENT_COMPAT, 'UTF-8') . "\" />\n";
    }
    if ($noeeditversion) {
        echo "    <input type=\"hidden\" name=\"plugname\" value=\"" . htmlspecialchars($plugname, ENT_COMPAT, 'UTF-8') . "\" />\n";
        echo "    <input type=\"hidden\" name=\"plugversion\" value=\"" . htmlspecialchars($plugversion, ENT_COMPAT, 'UTF-8') . "\" />\n";
        echo "    <input type=\"hidden\" name=\"root_name\" value=\"" . htmlspecialchars($plugroot, ENT_COMPAT, 'UTF-8') . "\" />\n";
        echo "    <input type=\"hidden\" name=\"index_file\" value=\"" . htmlspecialchars($plugindex, ENT_COMPAT, 'UTF-8') . "\" />\n";
    }
    if ($editsql == '1' | $idclient >= 1) $noeditfield2 = 'readonly';
echo "    <table class=\"config\" cellspacing=\"1\">\n";
    echo "	   <tr>\n";
    echo "	     <td class=\"head\"><p>" . $cms_lang["plug_pluginname"] . "</p></td>\n";
    if (!$noeeditversion) echo "	     <td><input class=\"w800\" $noeditfield type=\"text\" name=\"plugname\" value=\"" . htmlspecialchars($plugname, ENT_COMPAT, 'UTF-8') . "\" size=\"90\" /></td>\n";
    else echo "	     <td>" . htmlspecialchars($plugname, ENT_COMPAT, 'UTF-8') . "</td>\n";
    echo "      </tr>\n";

    //rechte bearbeiten
    if (!empty($idplug) && $perm->have_perm(6, 'area_plug', 0)){
    	$panel = $perm->get_right_panel('area_plug', $idplug, array('formname' => 'newplugin'), 'text', true);
		if (!empty($panel)) {
		    echo "	   <tr>\n";
		    echo "	     <td class=\"head\">" . $cms_lang['gen_rights'] . "</td>\n";
		    echo "	     <td>".implode("", $panel)."</td>\n";
		    echo "      </tr>\n";
		}
    }    
    echo "	   <tr>\n";
    echo "	     <td class=\"head\">" . $cms_lang["plug_version"] . "</td>\n";
    if (!$noeeditversion) echo "	     <td><input class=\"w200\" $noeditfield type=\"text\" name=\"plugversion\" value=\"" . htmlspecialchars($plugversion, ENT_COMPAT, 'UTF-8') . "\" size=\"90\" maxlength=\"60\" /></td>\n";
    else echo "	     <td>" . htmlspecialchars($plugversion, ENT_COMPAT, 'UTF-8') . "</td>\n";
    echo "      </tr>\n";
    echo "	   <tr>\n";
    echo "	     <td class=\"head\">" . $cms_lang["plug_cat"] . "</td>\n";
    echo "	     <td>";
    if (!$noeditfield) echo "	     <input class=\"w200\" type=\"text\" name=\"plugcat\" value=\"" . htmlspecialchars($plugcat, ENT_COMPAT, 'UTF-8') . "\" maxlength=\"60\" size=\"90\" />\n";
    else echo htmlspecialchars($plugcat, ENT_COMPAT, 'UTF-8');
    if (!$noeditfield) {
        echo "<label for=\"catchange\">".$mod_lang['gen_select'].":</label> <select onchange=\"document.newplugin.plugcat.value=(this.value);\" name=\"catchange\" id=\"catchange\" size=\"1\">\n";
        echo "<option value=\"\"></option>\n";
        foreach($cat_arr as $cat_art) {
            if (empty($cat_art['cat'])) continue;
            $cat_value = $cat_art['cat'];
            if($cat_value == '') continue;
            if($cat_value == htmlspecialchars($plugcat, ENT_COMPAT, 'UTF-8')) $cat_selected = ' selected '; else $cat_selected = '';
            echo "<option value=\"".htmlspecialchars($cat_value, ENT_COMPAT, 'UTF-8')."\"$cat_selected>".htmlspecialchars($cat_value, ENT_COMPAT, 'UTF-8')."</option>\n";
        }
        echo "</select>\n";
    }
    echo "</td>\n";
    echo "      </tr>\n";
    echo "      <tr>\n";
    echo "        <td class=\"head nowrap\">" . $cms_lang["plug_description"] . "</td>\n";
    echo "	     <td><textarea class=\"w800\" $noeditfield name=\"description\" rows=\"3\" cols=\"52\">" . htmlspecialchars($description, ENT_COMPAT, 'UTF-8') . "</textarea></td>\n";
    echo "	   </tr>\n";
    if ($editsql == '1') {
        echo "      <tr>\n";
        echo "        <td class=\"head nowrap\">" . $cms_lang["plug_sql_install"] . "</td>\n";
        echo "        <td><textarea class=\"w800\" name=\"install_sql\" rows=\"14\" cols=\"52\" wrap=\"off\">" . htmlspecialchars($sql_install, ENT_COMPAT, 'UTF-8') . "</textarea></td>\n";
        echo "	   </tr>\n";
        echo "      <tr>\n";
        echo "        <td class=\"head nowrap\">" . $cms_lang["plug_sql_uninstall"] . "</td>\n";
        echo "        <td><textarea class=\"w800\" name=\"uninstall_sql\" rows=\"14\" cols=\"52\" wrap=\"off\">" . htmlspecialchars($sql_uninstall, ENT_COMPAT, 'UTF-8') . "</textarea></td>\n";
        echo "	   </tr>\n";
        echo "      <tr>\n";
        echo "        <td class=\"head\">" . $cms_lang["plug_sql_update"] . "</td>\n";
        echo "        <td><textarea class=\"w800\" name=\"update_sql\" rows=\"14\" cols=\"52\" wrap=\"off\">" . htmlspecialchars($sql_update, ENT_COMPAT, 'UTF-8') . "</textarea></td>\n";
        echo "	   </tr>\n";
    } else {
        echo "      <tr>\n";
        echo "	     <td class=\"head\">" . $cms_lang["plug_root_name"] . "</td>\n";
        if (!$newplugin) {
            if (!$noeeditversion) echo "        <td><input class=\"w800\" $noeditfield2 type=\"text\" name=\"root_name\" value=\"" . htmlspecialchars($plugroot, ENT_COMPAT, 'UTF-8') . "\" maxlength=\"60\" size=\"90\" /></td>\n";
            else echo "        <td>plugins/" . htmlspecialchars($plugroot, ENT_COMPAT, 'UTF-8') . "</td>\n";
        } elseif($import) {
            $newliste = $rep->get_new_plugin_list();
            $oldliste = $rep->get_plugin_list();
            foreach($oldliste as $old) {
                $js_old .= "olds[\"" . htmlspecialchars($old['root_name'], ENT_COMPAT, 'UTF-8') . "\"] = " . intval($old['base']) . ";\n";
            } 
            echo "        <td><select onchange=\"reloadthis(this.value);\" name=\"root_name\">\n";
            echo "         <option value=\"" . htmlspecialchars($plugroot) . "\">" . htmlspecialchars($plugroot, ENT_COMPAT, 'UTF-8') . "</option>\n";
            foreach($newliste as $new) {
                if($new['root_name'] != $plugroot) {
                    $js_var .= "values[\"" . htmlspecialchars($new['root_name'], ENT_COMPAT, 'UTF-8') . "\"] = " . intval($new['xml']) . ";\n";
                    echo "         <option value=\"" . htmlspecialchars($new['root_name'], ENT_COMPAT, 'UTF-8') . "\">" . htmlspecialchars($new['root_name'], ENT_COMPAT, 'UTF-8') . "</option>\n";
                } 
            } 
            echo "        </select>\n";
            echo "<script  type=\"text/javascript\">\n
              <!--\n
              function reloadthis(name) {\n
              var values = new Array();\n
              var olds   = new Array();\n";
            echo $js_old;
            echo $js_var;
            echo "   if ( values[name] == 1 ) {\n
                    document.reload.name.value=name;
                    document.reload.submit();\n
                 }
              }\n
              //-->\n
              </script>";
            echo "        </td>\n";
        } else {
            echo "        <td><input class=\"w800\" type=\"text\" name=\"root_name\" value=\"\" maxlength=\"60\" size=\"90\" /></td>\n";
        } 
        echo "	   </tr>\n";
        echo "      <tr>\n";
        echo "        <td class=\"head\">" . $cms_lang["plug_index_file"] . "</td>\n";
        if (!$noeeditversion) echo "        <td><input class=\"w800\" $noeditfield2 type=\"text\" name=\"index_file\" value=\"" . htmlspecialchars($plugindex, ENT_COMPAT, 'UTF-8') . "\" maxlength=\"60\" size=\"90\" /></td>\n";
    else {echo "        <td>"; if ($plugindex != '') echo "plugins/" . htmlspecialchars($plugroot, ENT_COMPAT, 'UTF-8') . '/' . htmlspecialchars($plugindex, ENT_COMPAT, 'UTF-8'); echo "</td>\n";}
        echo "	   </tr>\n";
    } 
    
	//buttons    
	echo "      <tr>\n";
	echo "        <td class='content7' colspan='2' style='text-align:right'>\n";
	echo "        <input type='submit' name='sf_save' title='".$cms_lang['gen_save_titletext']."' value='".$cms_lang['gen_save']."' class=\"sf_buttonAction\" onmouseover=\"this.className='sf_buttonActionOver'\" onmouseout=\"this.className='sf_buttonAction'\" />\n";
	echo "        <input type='submit' name='sf_apply' title='".$cms_lang['gen_apply_titletext']."' value='".$cms_lang['gen_apply']."' class=\"sf_buttonAction\" onmouseover=\"this.className='sf_buttonActionOver'\" onmouseout=\"this.className='sf_buttonAction'\" />\n";
	echo "        <input type='button' name='sf_cancel' title='".$cms_lang['gen_cancel_titletext']."' value='".$cms_lang['gen_cancel']."' class=\"sf_buttonAction\" onclick=\"window.location='".$sess->url("main.php?area=plug&idclient=$idclient")."'\" onmouseover=\"this.className='sf_buttonActionCancelOver'\" onmouseout=\"this.className='sf_buttonAction'\" />\n";
	echo "        </td>\n";
	echo "      </tr>\n";    
    

    echo "    </table>\n";
    echo "    </form>\n";
    echo "    </div>\n";
    echo '<div class="footer">'. $cms_lang['login_licence'] .'</div>'."\n";
    echo "</body>\n";
    echo "</html>\n";
    ?>
