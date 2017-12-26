<?PHP
// File: $Id: inc.tpl_edit.php 52 2008-07-20 16:16:33Z bjoern $
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

/**
 * 1. Ben�tigte Funktionen und Klassen includieren
 */

include('inc/fnc.tpl.php');
include('inc/fnc.mipforms.php');

/**
 * 2. Eventuelle Actions/ Funktionen abarbeiten
 */

//um den Bereich betreten zu d�rfen, mu� das Recht bearbeiten gesetzt sein
if(is_numeric($idtpl)) $perm->check(3, 'tpl', $idtpl);
else $perm->check(3, 'area_tpl', 0);

switch($action) {
	case 'save':  // Template bearbeiten
		$errno = tpl_save($idtpl, $idlay, $tplname, $description, $tpl_overwrite_all);
		tpl_autoset_starttpl((int) $client, (int) $idtpl);
		if (!$errno && ! isset($_REQUEST['sf_apply'])) {
			header ('HTTP/1.1 302 Moved Temporarily');
			header ("Location:".$sess->urlRaw("main.php?area=tpl"));
			exit;
			break;
		} 
	case 'change':  // Layout oder Modul wechseln
		$cconfig = tpl_change($idlay);
		remove_magic_quotes_gpc($tplname);
		remove_magic_quotes_gpc($description);
		$tplname = htmlentities($tplname, ENT_COMPAT, 'UTF-8');
		$description = htmlentities($description, ENT_COMPAT, 'UTF-8');
		break;
}

/**
 * 3. Eventuelle Dateien zur Darstellung includieren
 */

include('inc/inc.header.php');

/**
 * 4. Bildschirmausgabe aufbereiten und ausgeben
 */
echo "<!-- Anfang inc.tpl_edit.php -->\n";
echo "<div id=\"main\">\n";
echo "    <h5>".$cms_lang['area_tpl_edit']."</h5>\n";
if ($errno) echo "<p class=\"errormsg\">".$cms_lang["err_$errno"]."</p>";

// Templateeinstellungen suchen
if ($idtpl && (!$idlay || $idlay == '0')) {
	// Template suchen
	$sql = "SELECT A.name, A.description, B.idlay
		FROM ". $cms_db['tpl'] ." A
		LEFT JOIN ". $cms_db['lay'] ." B using(idlay)
		WHERE A.idtpl='$idtpl'";
	$db->query($sql);
	$db->next_record();
	$tplname = htmlentities($db->f('name'), ENT_COMPAT, 'UTF-8');
	$description = htmlentities($db->f('description'), ENT_COMPAT, 'UTF-8');

	// Template zur�cksetzen
	if ($idlay < 1) {
		$idlay = $db->f('idlay');
		$list = browse_layout_for_containers($idlay);
		if (is_array($list)) {
			foreach ($list['id'] as $value) unset(${'c'.$value});
		}
	} else $idlay = $db->f('idlay');

	// Containereinstellungen suchen
	$sql = "SELECT A.container, A.idmod, B.config, B.view, B.edit, C.input 
                FROM $cms_db[container] A 
                LEFT JOIN $cms_db[container_conf] B USING(idcontainer) 
                LEFT JOIN $cms_db[mod] C ON A.idmod=C.idmod 
                WHERE A.idtpl='$idtpl' AND B.idtplconf='0'";
	$db->query($sql);
	while ($db->next_record()) {
		${'c'.$db->f('container')} = $db->f('idmod');
		${'cview'.$db->f('container')} = $db->f('view');
		${'cedit'.$db->f('container')} = $db->f('edit');
		${'cconfig'.$db->f('container')} = $db->f('config');
	}

// Templateeinstellungen �bernehmen
} else {
	if (is_array($cconfig)) {
		foreach ($cconfig as $key => $value) {
			if ($changed != $key) ${'cconfig'.$key} = $value;
		}
	}
}

// Layoutbeschreibung raussuchen
$sql = "SELECT idlay, name, description 
        FROM $cms_db[lay] 
        WHERE idclient='$client' 
        ORDER BY name";
$db->query($sql);
while ($db->next_record()) {
	$lay['id'][] = $db->f('idlay');
	$lay[$db->f('idlay')]['name'] = $db->f('name');
	$lay[$db->f('idlay')]['description'] = $db->f('description');
}

// Modulliste erstellen
if ($idlay) {
	$sql = "SELECT idmod, cat, name, input, config, version, verbose, cat, source_id, idmod 
                FROM $cms_db[mod]
                WHERE idclient='$client'
                ORDER BY cat, name";
	$db->query($sql);
	while ($db->next_record()) {
		$mod['id'][] = $db->f('idmod');
		if ($db->f('cat') != '') $mod[$db->f('idmod')]['cat'] = $db->f('cat'). ':';
		$mod[$db->f('idmod')]['name'] = htmlentities($db->f('name'), ENT_COMPAT, 'UTF-8');
		$mod[$db->f('idmod')]['input'] = $db->f('input');
		$mod[$db->f('idmod')]['config'] = $db->f('config');
		$mod[$db->f('idmod')]['version'] = htmlentities($db->f('version'), ENT_COMPAT, 'UTF-8');
		$mod[$db->f('idmod')]['verbose'] = htmlentities($db->f('verbose'), ENT_COMPAT, 'UTF-8');
        $mod[$db->f('idmod')]['cat'] = htmlentities($db->f('cat'), ENT_COMPAT, 'UTF-8');
        $mod[$db->f('idmod')]['source_id'] = htmlentities($db->f('source_id'), ENT_COMPAT, 'UTF-8');
        $mod[$db->f('idmod')]['idmod'] = htmlentities($db->f('idmod'), ENT_COMPAT, 'UTF-8');
	}
}

// Template dublizieren
if($action == 'duplicate') {
	$idtpl_for_form = '';
	$tplname = $cms_lang['tpl_copy_of'].$tplname;
	unset($idtpl);
} else $idtpl_for_form = $idtpl;

// Formular zur Templatebearbeitung
echo "    <form name=\"editform\" method=\"post\" action=\"".$sess->url("main.php?area=tpl_edit&idtpl=$idtpl_for_form#edit_container")."\">\n";
echo "    <input type=\"hidden\" name=\"action\" value=\"save\" />\n";
echo "    <input type=\"hidden\" name=\"anchor\" value=\"\" />\n";
echo "    <table class=\"config\" cellspacing=\"1\">\n";
echo "      <tr>\n";
echo "        <td class=\"head nowrap\" width=\"110\"><p>".$cms_lang['tpl_templatename']."</p></td>\n";
echo "        <td><input class=\"w800\" type=\"text\" name=\"tplname\" value=\"$tplname\" size=\"90\" /></td>\n";
echo "      </tr>\n";

// rechte management
if (!empty($idtpl) && $action != 'duplicate' && $perm->have_perm(6, 'tpl', $idtpl)) {
	$panel = $perm->get_right_panel('tpl', $idtpl, array( 'formname'=>'editform' ), 'text');
	if (!empty($panel)) {
		echo "      <tr>\n";
		echo "        <td class=\"head\">" . $cms_lang['gen_rights'] . "</td>\n";
		echo "        <td>";
		echo implode("", $panel);
		echo "      </tr>\n";
	}
}


echo "      <tr>\n";
echo "        <td class=\"head\">".$cms_lang['tpl_description']."</td>\n";
echo "        <td><textarea class=\"w800\" name=\"description\" rows=\"3\" cols=\"52\">$description</textarea></td>\n";
echo "      </tr>\n";

// Layout ausw�hlen
echo "      <tr>\n";
echo "        <td class=\"head\">".$cms_lang['tpl_layout']."</td>\n";
echo "        <td>\n<select name=\"idlay\" size=\"1\" onchange=\"document.editform.action.value='change';document.editform.submit();\">\n";
if (!$idlay) $idlay = '0';
if ($idlay && $idtpl) echo "          <option value=\"0\">zur&uuml;cksetzen</option>\n";
elseif ($idlay == '0') echo "          <option value=\"0\" selected>".$cms_lang['form_nothing']."</option>\n";
else echo "          <option value=\"0\">".$cms_lang['form_nothing']."</option>\n";
if (is_array($lay['id'])) {
	foreach ($lay['id'] as $value) {
		if ($value == $idlay) echo "      <option value=\"$value\" selected>".$lay[$value]['name']."</option>\n";
		else echo "      <option value=\"$value\">".$lay[$value]['name']."</option>\n";
	}
}
echo "        </select>\n</td>\n";
echo "      </tr>\n";

// wenn Layout gew�hlt
if ($idlay) {
	echo "      <tr>\n";
	echo "        <td class=\"head\">\n<input type=\"hidden\" name=\"changed\" value=\"0\" />\n".$cms_lang['tpl_description']."</td>\n";
	echo "        <td width=\"640\">".$lay[$idlay]['description']."&nbsp;</td>\n";
	echo "      </tr>\n";
//	echo "      <tr>\n";
//	echo "        <td colspan=\"2\"></td>\n";
//	echo "      </tr>\n";

	// Container auflisten
	$list = browse_layout_for_containers($idlay);
	if (is_array($list)) {
		echo "      <input type=\"hidden\" name=\"container\" value=\"".implode(',', $list['id'])."\" />\n";
		natsort ($list['id']);
		foreach ($list['id'] as $value) {

			// Containername
			$_container_name = !empty($list[$value]['title']) ? $list[$value]['title']:"$value. ".$cms_lang['tpl_container']."";
			
			echo ($anchor == $_container_name) ? "      <tr id=\"edit_container\">\n" : "      <tr>\n";

			printf ("        <td class=\"head\"%s>%s</td>\n", (${'c'.$value}) ? ' rowspan="2"':'', (!empty($list[$value]['title'])) ? $list[$value]['title']:"$value. ".$cms_lang['tpl_container']."");

			// Modul ausw�hlen
			printf ("        <td class=\"%s\">", (${'c'.$value}) ? 'headre':'content');
			$echo = "\n<div class=\"forms\">\n<select class=\"element\" name=\"c$value\" size=\"1\" onchange=\"document.editform.action.value='change';document.editform.changed.value='$value';document.editform.anchor.value='$_container_name';document.editform.submit();\">\n";
			if (${'c'.$value} < 1) {
				$echo .= "          <option value=\"0\" selected>".$cms_lang['form_nothing']."</option>\n";
				$modinfo = "<img src=\"tpl/" . $cfg_cms['skin'] . "/img/space.gif\" alt=\"\" title=\"\" width=\"16\" height=\"16\" />\n";
			} else {
				$echo .= "          <option value=\"0\">".$cms_lang['form_nothing']."</option>\n";
			}
			
			if (is_array($mod['id'])) {
				$_cat_arr = array();
        		
        		//build option select
        		$first_run = true;
        		foreach ($mod['id'] as $idmod) {
					//check perm
					if (!$perm->have_perm(15, 'mod', 0) && (strpos($mod[$idmod]['version'], 'dev') != false 
							&& $mod[$idmod]['version'] != '') && $idmod != ${'c'.$value}) {
								continue;
					}
					
					//optgroup
					if (!$_cat_arr[$mod[$idmod]['cat']] && $mod[$idmod]['cat'] != '') {
          				if (! $first_run) {
          					$echo .= "          </optgroup>\n";
          				}
          				$_cat_arr[$mod[$idmod]['cat']] = true;
          				$echo .= "          <optgroup label=\"".$mod[$idmod]['cat']."\">\n";
          				$first_run = false;
        			}
        			
        			$mod[$idmod]['referer'] = (($mod[$idmod]['verbose'] != '') 
        										? "'" . $mod[$idmod]['verbose'] . "'" : "'" . $mod[$idmod]['name'] . "'");

					$mod[$idmod]['referer'] .= empty($mod[$idmod]['version']) ? '' :' (' . $mod[$idmod]['version'] . ')';
        			
        			// selected
        			if ($idmod == ${'c'.$value}) {
						$echo .= "          <option value=\"$idmod\" selected>".$mod[$idmod]['referer']."</option>\n";
// das hier muss weiter nach unten nach dem schl. DIV, sieh Kommentar Zeile 322
      					//make modinfo text
      					$modinfotext = ' ++ ' .$cms_lang['gen_description'] . ' ++ &#10;';
      					if ($mod[${'c'.$value}]['cat'] != '') {
      						$modinfotext .= $cms_lang['gen_cat'] . ': ' . $mod[${'c'.$value}]['cat'] . ' &#10;';
      					} 
      					if (empty($mod[${'c'.$value}]['source_id'])) {
      						$modinfotext .= $cms_lang['gen_verbosename'] . ': ' . $mod[${'c'.$value}]['verbose'] . ' &#10;';
      					}
      					if ($mod[${'c'.$value}]['version'] != '') {
      						$modinfotext .= $cms_lang['gen_version'] . ': ' . $mod[${'c'.$value}]['version'] . ' &#10;';
      					}
      					$modinfotext .= 'IdMod: ' . $mod[${'c'.$value}]['idmod']; 
      					

          				$modcursor = 'pointer';
         				$modinfotext = "<img style=\"cursor:$modcursor\" src=\"tpl/" . $cfg_cms['skin'] 
         								. "/img/about.gif\" alt=\"" . $modinfotext .
            							"\" title=\"" . $modinfotext . "\" width=\"16\" height=\"16\" />";
					} else {
						//not selected
          				$echo .= "          <option value=\"$idmod\">".$mod[$idmod]['referer']."</option>\n";
        			}
				}
				
				//close last optgroup
				$echo .= "          </optgroup>\n";
			}

			echo  $echo . "        </select>\n";
                        
			// Modul konfigurieren
			if (${'c'.$value}) {
				echo "\n<select name=\"cview$value\" size=\"1\">\n";
				printf ("          <option value=\"0\"%s>". $cms_lang['gen_mod_active'] ."</option>\n", (${'cview'.$value} == '0' || !${'cview'.$value}) ? ' selected':'');
				printf ("          <option value=\"-1\"%s>". $cms_lang['gen_mod_deactive'] ."</option>\n", (${'cview'.$value} == '-1') ? ' selected':'');
				//printf ("          <option value=\"".$auth->auth['uid']."\"%s>ich</option>\n", (${'cview'.$value} > '0') ? ' selected':'');
				echo "        </select>\n<select name=\"cedit$value\" size=\"1\">\n";
				printf ("          <option value=\"0\"%s>". $cms_lang['gen_mod_edit_allow'] ."</option>\n", (${'cedit'.$value} == '0' || !${'cedit'.$value}) ? ' selected':'');
				printf ("          <option value=\"-1\"%s>". $cms_lang['gen_mod_edit_disallow'] ."</option>\n", (${'cedit'.$value} == '-1') ? ' selected':'');
				//printf ("          <option value=\"".$auth->auth['uid']."\"%s>ich</option>\n", (${'cedit'.$value} > '0') ? ' selected':'');
				echo "        </select></div>\n";
   echo $modinfotext;
				echo "        </td>\n";
				echo "      </tr>\n";
				echo "      <tr>\n";
				echo "        <td>\n";

				// Modulkonfiguration einlesen
				$input = $mod[${'c'.$value}]['input'];

				// Developer-Modul
				if (strpos($mod[${'c'.$value}]['version'], 'dev') != false && $mod[${'c'.$value}]['version'] != '') {
					$input = '<p align="center" class="errormsg">'.$cms_lang['tpl_devmessage']."</p>\n".$input;
				}
	
				$mod_tpl_conf = ${'cconfig'.$value};
				$mod_id = ${'c'.$value};
				$mod_conf = ( empty($mod_tpl_conf) ) ? $mod[$mod_id]['config']:$mod_tpl_conf;
				$tmp1 = preg_split("/&/", $mod_conf);
				$varstring = array();
				
				foreach ($tmp1 as $key1=>$value1) {
					$tmp2 = explode('=', $value1);
					foreach ($tmp2 as $key2=>$value2) $varstring["$tmp2[0]"]=$tmp2[1];
				}
				
				foreach ($varstring as $key3=>$value3) {
					$cms_mod['value'][$key3] = cms_stripslashes(urldecode($value3));
				}
				
				//TODO - remove dedi backward compatibility
				$dedi_mod =& $cms_mod;
				if (is_array($mod[${'c'.$value}])) {
					foreach ($mod[${'c'.$value}] as $key4=>$value4) {
						$cms_mod['info'][$key4] = cms_stripslashes(urldecode($value4));
					}
				}
				
				$input = str_replace("MOD_VAR", "C".$value."MOD_VAR" , $input);
				
				eval(' ?>'.$input);
				
				unset($cms_mod['value'], $dedi_mod['value'], $cms_mod['info'], $dedi_mod['info']);
				
				// hiermit stimmt was nicht, wenn Modul da dann sollte TR+Table nicht geschlossen werden
				// wenn kein Modul da dann muss das greifen
				
			
			
			} else {
				
			}
		
			echo "</td>\n";
			echo "      </tr>\n";
		}
	} else {

	}
}

// Einstellungen f�r alle Templates �bernehmen
if(is_numeric($idtpl)) {
	echo "  <tr>\n";
	echo "    <td class=\"head\">Erweitert</td>\n";
	echo '    <td><input type="checkbox" name="tpl_overwrite_all" value="1" id="touchme" /> <label for="touchme">'.$cms_lang['tpl_overwrite_all'].'</label></td>';
	echo "  </tr>\n";
}


//buttons    
echo "      <tr>\n";
echo "        <td class='content7' colspan=\"2\" style='text-align:right'>\n";
echo "        <input type='submit' name='sf_save' title='".$cms_lang['gen_save_titletext']."' value='".$cms_lang['gen_save']."' class=\"sf_buttonAction\" />\n";
echo "        <input type='submit' name='sf_apply' title='".$cms_lang['gen_apply_titletext']."' value='".$cms_lang['gen_apply']."' class=\"sf_buttonAction\"/>\n";
echo "        <input type='button' name='sf_cancel' title='".$cms_lang['gen_cancel_titletext']."' value='".$cms_lang['gen_cancel']."' class=\"sf_buttonActionCancel\" onclick=\"window.location='".$sess->url('main.php?area=tpl')."'\"/>\n";
echo "        </td>\n";
echo "      </tr>\n";

echo "</table>\n";
echo "</form>\n";
echo "</div>\n";
echo '<div class="footer">'. $cms_lang['login_license'] .'</div>'."\n";
echo "</body>\n";
echo "</html>\n";
?>
