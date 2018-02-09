<?PHP
// File: $Id: inc.mod.php 37 2008-05-12 13:26:12Z mistral $
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
 * 1. Ben�tigte Funktionen und Klassen includieren
 */
include_once('inc/fnc.mod.php');
/**
 * 2. Eventuelle Actions/ Funktionen abarbeiten
 */
$perm->check('area_mod');
if ($idclient != 0 && $idclient != (int) $client && $idclient != '-1' || !isset($idclient)) $idclient = (int) $client;
unset($errno);
switch($action) {
    case 'copy':// Modul kopieren (import/ export)
        $errno = mod_copy($idmod, $from, $into);
        break;
    case 'save':// Modul speichern
        if(is_numeric($idmod)) $perm->check(3, 'mod', $idmod);
        else $perm->check(3, 'area_mod', 0);
	    $modname2     = $modname;
		$modverbose2  = $modverbose;
	    $description2 = $description;
	    $modversion2  = $modversion;
	    $modcat2 	  = $modcat;
	    $input2 	  = $input;
	    $output2 	  = $output;
	    remove_magic_quotes_gpc($modname2);
	    remove_magic_quotes_gpc($modverbose2);
	    remove_magic_quotes_gpc($description2);
	    remove_magic_quotes_gpc($modversion2);
	    remove_magic_quotes_gpc($modcat2);
	    remove_magic_quotes_gpc($input2);
	    remove_magic_quotes_gpc($output2);
	    if ( ((($errno = ($rep->mod_test($input2, $idmod) || $rep->mod_test($output2, $idmod)) ? '0423' : false) || ($errno = (empty($input2) && empty($output2)) ? '0424' : false))
           && !$s_overide)
           || '0412' != ($errno = mod_save($idmod, $modname, $modverbose, $description, $modversion, $modcat, $input, $output, $idclient, $repository_id,
              $install_sql, $uninstall_sql, $update_sql, $mod_rebuild_sql, $source, $mod_no_wedding, false, $mod_config_takeover)) ) {
            if (empty($modname) || $modname == '') $modname = $cms_lang['mod_defaultname'];
			if (empty($modversion) || $modversion == '') $modversion = '1.0';
            // base64_encode before writing to the session. There are problems with correct quote escape in complex statements
			$s_modul = base64_encode(serialize(array(
                'name'        => $modname2,
                'verbose'     => $modverbose2,
                'description' => $description2,
                'version'     => $modversion2,
                'cat'         => $modcat2,
                'input'       => $input2,
                'output'      => $output2)));
            $sess->register('s_modul'); // merke:erst ein value kann gespeichert werden ;)
            $sess->freeze();
            header ('Location:' . $sess->urlRaw("main.php?area=mod_edit&idmod=" . $idmod . "&idclient=" . $idclient . "&errno=" . $errno));
            exit;
        } else if(isset($_REQUEST['sf_apply'])) {
        	if (empty($modname) || $modname == '') $modname = $cms_lang['mod_defaultname'];
			if (empty($modversion) || $modversion == '') $modversion = '1.0';
            // base64_encode before writing to the session. There are problems with correct quote escape in complex statements
			$s_modul = base64_encode(serialize(array(
                'name'        => $modname2,
                'verbose'     => $modverbose2,
                'description' => $description2,
                'version'     => $modversion2,
                'cat'         => $modcat2,
                'input'       => $input2,
                'output'      => $output2)));
            $sess->register('s_modul'); // merke:erst ein value kann gespeichert werden ;)
            $sess->freeze();
            header ('Location:' . $sess->urlRaw("main.php?area=mod_edit&idmod=" . $idmod . "&idclient=" . $idclient ));
            exit;
        
        }
        if ($sess->is_registered('s_modul')) $sess->unregister('s_modul');
        break;
    case 'save_config':// Konfiguration speichern
        $perm->check(4, 'mod', $idmod);
        $config = make_array_to_urlstring($MOD_VAR);
        $default = make_array_to_urlstring($s_default); 
		// �bernahme von 0A hexadecimal (US-ASCII character LF), und 0D (US-ASCII character CR)
        if (strpos($config, '%0D%0A') && !strpos($default, '%0D%0A')) $default = preg_replace('/(?<!%0D)%0A/','%0D%0A',$default);
        // �bernahme von 5C hexadecimal (US-ASCII character \), und 22 (US-ASCII character ")
		if (strpos($config, '%5C%22') && !strpos($default, '%5C%22')) $default = preg_replace('/(?<!%5C)%22/','%5C%22',$default);
		// �bernahme von 5C hexadecimal (US-ASCII character \), und 27 (US-ASCII character ')
		if (strpos($config, '%5C%27') && !strpos($default, '%5C%27')) $default = preg_replace('/(?<!%5C)%27/','%5C%27',$default);
		if ($config == $default) $config = '';
        $errno = mod_save_config($idmod, $config);
        if ($mod_config_overwrite_all == '1') mod_set_config_all($idmod, $config);
        if ($sess->is_registered('s_default')) $sess->unregister('s_default');
        if(isset($_REQUEST['sf_apply'])) {
        	header ('Location:' . $sess->urlRaw("main.php?area=mod_config&idmod=" . $idmod . "&idclient=" . $idclient ));
			exit;
        }
        break;
    case 'download': // Modul downloaden
        $perm->check(10, 'area_mod', 0);
        $errno = mod_download($idmod, $idclient);
        break;
    case 'upload': // Modul hochladen
        $perm->check(9, 'mod', $idmod);
        if ($override == 'false' || ($override == 'true' && !is_array($umodule) && !is_array($smodule) && !is_array($rmodule))) {
            if ($sess->is_registered('s_upload')) $sess->unregister('s_upload');
            $errno = '0418';
            break;
        }
        else $errno = mod_upload($idclient, $override, $umodule, $cmodule, $smodule, $rmodule);
        if ($errno == '0417' || $errno == '0419') {
            // Modul Update ||Reinstall?
            $link = $sess->url('main.php?area=mod_update&idclient=' . $idclient. '&errno=' . $errno);
            $body_onload_func = "new_window('$link', 'updatewin','scrollbars=yes', 450, 400, 'true');";
        } 
        break;
    case 'delete': // Modul l�schen
        $perm->check(5, 'mod', $idmod);
        $errno = mod_delete($idmod);
        break;
    case 'update': // Modul updaten Repository
        $perm->check(12, 'mod', $idmod);
        $updatedata = $rep->mod_update_data($repid);
        if ($updatedata['repository_id'] == $repid) $errno = mod_update($idmod, $updatedata['repository_id'], $updatedata['name'], $updatedata['description'], $updatedata['version'], $updatedata['cat'], $updatedata['input'], $updatedata['output'], $updatedata['install_sql'], $updatedata['uninstall_sql'], $updatedata['update_sql'], $idclient);
        else $errno = '1500';
        break;
    case 'import': // Modul import Repository
        $perm->check(14, 'area_mod', 0);
        $installdata = $rep->mod_import_data($repid);
        if ($installdata['repository_id'] == $repid) $errno = mod_install($installdata['repository_id'], $installdata['name'], $installdata['description'], $installdata['version'], $installdata['cat'], $installdata['input'], $installdata['output'], $updatedata['install_sql'], $updatedata['uninstall_sql'], $updatedata['update_sql'], $idclient);
        else $errno = '1500';
        break;
    case 'lupdate': // Modul local updaten
        $perm->check(3, 'mod', $idmod);
        if ($override == 'false' || ($override == 'true' && !is_array($umodule) && !is_array($smodule) && !is_array($rmodule))) {
            $errno = '0418';
            break;
        } elseif ((is_array($umodule) || is_array($smodule) || is_array($rmodule)) && $override == 'true' && $sourceid) {
            $updatedata = $rep->mod_update_data($sourceid, 'all');
			if ($updatedata['idmod'] == $sourceid) {
            	if (is_array($umodule)) foreach($umodule as $uid => $key) {
                    if ($umodule["$uid"] == true) {
                        mod_lupdate($uid, $updatedata['repository_id'], $updatedata['name'], $updatedata['description'], $updatedata['version'], $updatedata['cat'], $updatedata['input'], $updatedata['output'], $updatedata['install_sql'], $updatedata['uninstall_sql'], $updatedata['update_sql'], $idclient);
                        if ($cmodule["$uid"] == false) {
							mod_save_config($uid, $updatedata['config']);
							mod_set_config_all($uid, $updatedata['config']);
						}
						else mod_set_config_status($uid);
						$errno = '0420';
                    }
                }
                if (is_array($rmodule)) foreach($rmodule as $uid => $key) {
                    if ($rmodule["$uid"] == true) {
                        mod_lupdate($uid, $updatedata['repository_id'], $updatedata['name'], $updatedata['description'], $updatedata['version'], $updatedata['cat'], $updatedata['input'], $updatedata['output'], $updatedata['install_sql'], $updatedata['uninstall_sql'], $updatedata['update_sql'], $idclient);
                        if ($cmodule["$uid"] == false) {
							mod_save_config($uid, $updatedata['config']);
							mod_set_config_all($uid, $updatedata['config']);
						}
						else mod_set_config_status($uid);
						$errno = ($errno == '0420') ? '0422' : '0421';
                    }
                }
			}
        	if (is_array($smodule) && $override == 'true') { 
            	if ($smodule[$sourceid] == 'true') $errno2 = mod_copy($sourceid, '0', $client);
        	}
			break;
        } elseif ($sourceid && $idmod && !$override) {
        	// Modul Update?
            $errno = '0417';
        	$link = $sess->url('main.php?area=mod_lupdate&idclient=' . $idclient . '&idmod=' . $idmod . '&sourceid=' . $sourceid);
            $body_onload_func = "new_window('$link', 'updatewin','scrollbars=yes', 450, 400, 'true');";
            break;
		}
		else $errno = '1500';
        break;
}

/**
 * 3. Eventuelle Dateien zur Darstellung includieren
 */
include('inc/inc.header.php');
// DebugMe! $debug_mod_update = true;
/**
 * 4. Bildschirmausgabe aufbereiten und ausgeben
 */
// Templatedatei laden
echo'<script type="text/javascript">';
echo'<!--';
echo'function update_confirm(url) {';
echo'  var update_all = false;';
echo"  if(confirm('" . $cms_lang['mod_confirm_update'] . "')) update_all = true;";
echo'  alert(url+'|'+update_all);';
echo'}';
echo'//-->';
echo'</script>';
$tpl->loadTemplatefile('mod.tpl');
unset($tmp);
if ($idclient > '0') {
    if ($perm->have_perm(2, 'area_mod', 0)) $tmp['NEW_MOD'] = "<a class=\"action\" href=\"" . $sess->url("main.php?area=mod_edit&idclient=$idclient") . "\">" . $cms_lang['mod_new'] . "</a>&nbsp;|&nbsp;";
    if ($perm->have_perm(7, 'area_mod', 0)) $tmp['IMPORT_MOD'] = "<a class=\"action\" href=\"" . $sess->url('main.php?area=mod&idclient=0') . "\">" . $cms_lang['mod_import'] . "</a>";
} else {
    $tmp['BACK'] = "<a class=\"action\" href=\"" . $sess->url("main.php?area=mod&idclient=$client") . "\">" . $cms_lang['gen_back'] . "</a>";
    if ($perm->have_perm(11, 'area_mod', 0) && $rep->enabled()) {
        if ($idclient == 0) {
            $tmp['BACK'] .= "|\n<a class=\"action\" href=\"" . $sess->url('main.php?area=mod&idclient=-1') . "\">" . $cms_lang['mod_repository'] . "</a>;";
        } elseif ($idclient == '-1') {
            $tmp['BACK'] .= "|\n<a class=\"action\" href=\"" . $sess->url('main.php?area=mod&idclient=0') . "\">" . $cms_lang['mod_database'] . "</a>";
        }
    }
}
$tmp['AREA'] = ($idclient == '0') ? (($perm->have_perm(12, 'mod', 0) && $rep->enabled()) ? $cms_lang['area_mod_database'] : $cms_lang['area_mod_import']) : (($idclient == '-1') ? (($perm->have_perm(12, 'mod', 0) && $rep->enabled()) ? $cms_lang['area_mod_repository'] : $cms_lang['area_mod_import']) : $cms_lang['area_mod']);
$tmp['FOOTER_LICENSE'] = $cms_lang['login_license'];
$errno3 = ($rep->error()) ? $rep->error(1) : false;
if(!empty($errno) ||!empty($errno2) || !empty($errno3)) {
    $okmessages = array('0402', '0405', '0406', '0407', '0408', '0409', '0410', '0411', '0412', '0414', '0415', '0420', '0421', '0422');
	$warnmessages = array('0417', '0418', '0419');
    
    $msg = (!empty($errno)) ? $cms_lang['err_' . $errno] . ' ' . $cms_lang['err_' . $errno2] :  $cms_lang['err_' . $errno2];
    
    if (!empty($errno3)) $msg .= '<br>'. $cms_lang['err_' . $errno3];
	if (in_array($errno, $okmessages)) {
		$tpl->setCurrentBlock('OK');
		$tpl_error['OKMESSAGE'] = $msg;
	} else if (in_array($errno, $warnmessages)) {
		$tpl->setCurrentBlock('WARN');
		$tpl_error['WARNMESSAGE'] = $msg;
	} else {
		$tpl->setCurrentBlock('ERROR');
		$tpl_error['ERRORMESSAGE'] = $msg;
	}
	
	$tpl->setVariable($tpl_error);
	unset($tpl_error);
	$tpl->parseCurrentBlock();
}
// Tabellenformatierung
$tmp['MOD_MODULENAME'] = $cms_lang['mod_modulename'];
$tmp['MOD_DESCRIPTION'] = $cms_lang['mod_description'];
$tmp['MOD_MODULEVERSION'] = $cms_lang['mod_version'];
$tmp['MOD_MODULECAT'] = $cms_lang['mod_cat'];
$tmp['MOD_ACTION'] = $cms_lang['mod_action'];
$tmp['MOD_AUTHOR'] = $cms_lang['mod_author'];
            
$tpl->setVariable($tmp);
unset($tmp);
if ($idclient != '-1') {
    // Module aus der Datenbank suchen
    $modlist = $rep->mod_list($idclient);
    if (is_array($modlist) && count($modlist) > 0) {
        $tpl->setCurrentBlock('ENTRY');
        foreach ($modlist as $mod) {
            $mod['titel'] = ( ' ++ ' .$cms_lang['gen_description'] . ' ++ &#10;' . (($mod['cat'] != '') ? $cms_lang['gen_cat'] . ': ' . htmlentities($mod['cat'], ENT_COMPAT, 'UTF-8') . ' &#10;' : '') .
                        (($mod['verbose'] != '') ? $cms_lang['gen_verbosename'] . ': ' . htmlentities($mod['verbose'], ENT_COMPAT, 'UTF-8') . ' &#10;' : '') .
                        (empty($mod['source_id']) ? $cms_lang['gen_name'] : $cms_lang['gen_original']) . ': ' . htmlentities($mod['name'], ENT_COMPAT, 'UTF-8') . ' &#10;' .
                        (($mod['version'] != '') ? $cms_lang['gen_version'] . ': ' . htmlentities($mod['version'], ENT_COMPAT, 'UTF-8') . ' &#10;' : '') . 'IdMod: ' . htmlentities($mod['idmod'], ENT_COMPAT, 'UTF-8') . ' &#10;' .
                        $cms_lang['gen_author'] . ': ' . htmlentities($mod['author'], ENT_COMPAT, 'UTF-8')); // todo: real author name
            if (!$perm->have_perm(1, 'mod', $mod['idmod'])) continue;
            if (!$perm->have_perm(15, 'mod', 0) && (strpos($mod['version'], 'dev') != false && $mod['version'] != '')) continue;
            $mod['parse_error'] = !$mod['checked'];
            #if (!$mod['checked']) if (($mod['err_i'] = $rep->mod_test($mod['input'], $mod['idmod']))) $mod['parse_error'] = sprintf($cms_lang['err_0416'], 'Input', $mod['err_i']);
            #if (!$mod['checked']) if (($mod['err_o'] = $rep->mod_test($mod['output'], $mod['idmod']))) $mod['parse_error'] = ($mod['parse_error'] != '') ? $mod['parse_error'] . ';  ' . sprintf($cms_lang['err_0416'], 'Output', $mod['err_o']) : sprintf($cms_lang['err_0416'], 'Output', $mod['err_o']);
            $mod['count'] = ($idclient == '0') ? (int) $rep->mod_count($client, $mod['idmod']) : $rep->mod_count('0', $mod['source_id']);
            $mod['count_delete'] = ($idclient == '0') ? (int) $rep->mod_count($client, $mod['idmod']) : $rep->mod_count($idclient, $mod['idmod']);
            $mod['count_repository'] = ($idclient == '-1') ? (int) $rep->mod_count('0', $mod['repository_id'], true) : false;
            // Hintergrundfarbe w�hlen
            if ($idclient == '0' && !$mod['parse_error']) {
                $tmpid['ENTRY_BGCOLOR'] = '#ffffff';
                $tmpid['OVERENTRY_BGCOLOR'] = '#fff7ce';
            } elseif ($mod['parse_error']) {
                $tmpid['ENTRY_BGCOLOR'] = '#ffefde';
                $tmpid['OVERENTRY_BGCOLOR'] = '#fff7ce';
            } else {
                $tmpid['ENTRY_BGCOLOR'] = '#ffffff';
                $tmpid['OVERENTRY_BGCOLOR'] = '#fff7ce';
            }
            // Modul Info
            $tmpid['CURSOR'] =  'pointer';
            $tmpid['ENTRY_INFO'] = "<img style=\"cursor:" . $tmpid['CURSOR'] . "\" src=\"tpl/" . $cfg_cms['skin'] . "/img/about.gif\" alt=\"" . $mod['titel'] . "\" title=\"" . $mod['titel'] . "\" width=\"16\" height=\"16\" />";
            // Modul bearbeiten
            if ($perm->have_perm(3, 'mod', $mod['idmod'])) $tmpid['ENTRY_EDIT'] = "\n<a href=\"" . $sess->url("main.php?area=mod_edit&idmod=" . $mod['idmod'] . "&idclient=$idclient") . "\">\n<img src=\"tpl/" . $cfg_cms['skin'] . "/img/but_edit.gif\" alt=\"" . $cms_lang['mod_edit'] . "\" title=\"" . $cms_lang['mod_edit'] . "\" width=\"16\" height=\"16\" /></a>";
            else $tmpid['ENTRY_EDIT'] = "\n<img src=\"tpl/" . $cfg_cms['skin'] . "/img/space.gif\" width=\"16\" height=\"16\" />";
            // Modul duplizieren - braucht perm neu und bearbeiten
            if ($perm->have_perm(2, 'mod', $mod['idmod']) && $perm->have_perm(3, 'area_mod', 0) && !$mod['parse_error']) $tmpid['ENTRY_DUPLICATE'] = "\n<a href=\"" . $sess->url("main.php?area=mod_edit&idmod=" . $mod['idmod'] . "&idclient=$idclient&action=duplicate") . "\">\n<img src=\"tpl/" . $cfg_cms['skin'] . "/img/but_duplicate.gif\" alt=\"" . $cms_lang['mod_duplicate'] . "\" title=\"" . $cms_lang['mod_duplicate'] . "\" width=\"16\" height=\"16\" /></a>";
            else $tmpid['ENTRY_DUPLICATE'] = "\n<img src=\"tpl/" . $cfg_cms['skin'] . "/img/space.gif\" width=\"16\" height=\"16\" />";
            // Modul Sql erneut installieren
            /* todo:oldperms!
             *if ( $idclient > 0 && ($perm->have_perm('area_mod_import' ) || $perm->have_perm('area_mod_export')) && $mod['install_sql'] != '' && !$mod['parse_error'] ) $tmpid['ENTRY_SQL'] = "<a class=\"action\" href=\"".$sess->url("main.php?area=mod&action=copy&idmod=".$mod['idmod']."&into=$client&idclient=$client&from=$client")."\"><img src=\"tpl/".$cfg_cms['skin']."/img/but_calendar.gif\" alt=\"".$cms_lang['mod_rebuild_sql']."\" title=\"".$cms_lang['mod_rebuild_sql']."\" width=\"16\" height=\"16\" /></a>";
	         *elseif ( $idclient > 0 && ($perm->have_perm('area_mod_import' ) || $perm->have_perm('area_mod_export'))  && !$mod['parse_error'] ) $tmpid['ENTRY_SQL'] = "\n<img src=\"tpl/" . $cfg_cms['skin'] . "/img/space.gif\" width=\"16\" height=\"16\">";// Modul konfigurieren
             *else $tmpid['ENTRY_SQL'] = "\n<img src=\"tpl/" . $cfg_cms['skin'] . "/img/space.gif\" width=\"16\" height=\"16\">";
             */
            // Modul konfigurieren
            $mod['but_config'] = $mod['config'] != '' ? 'but_config_red.gif' : 'but_config.gif';
            $mod['mod_config'] = $mod['config'] != '' ? $cms_lang['mod_config_change'] : $cms_lang['mod_config'];
            if ($perm->have_perm(4, 'mod', $mod['idmod']) && !$mod['parse_error']) $tmpid['ENTRY_CONFIG'] = "\n<a href=\"" . $sess->url("main.php?area=mod_config&idmod=" . $mod['idmod'] . "&idclient=$idclient") . "\">\n<img src=\"tpl/" . $cfg_cms['skin'] . "/img/" . $mod['but_config'] . "\" alt=\"" . $mod['mod_config'] . "\" title=\"" . $mod['mod_config'] . "\" width=\"16\" height=\"16\" /></a>";
            else $tmpid['ENTRY_CONFIG'] = "\n<img src=\"tpl/" . $cfg_cms['skin'] . "/img/space.gif\" width=\"16\" height=\"16\" />";
            // Modul importieren
            if ($idclient == '0' && ($perm->have_perm(7, 'mod', $mod['idmod']) || $perm->have_perm(8, 'area_mod', 0)) && !$mod['parse_error']) {
				if ($mod['count'] < 1) $tmpid['ENTRY_IMPORT'] = "\n<a href=\"" . $sess->url("main.php?area=mod&action=copy&idmod=" . $mod['idmod'] . "&into=$client&idclient=0") . "\">\n<img src=\"tpl/" . $cfg_cms['skin'] . "/img/import.gif\" alt=\"" . $cms_lang['mod_import'] . "\" title=\"" . $cms_lang['mod_import'] . "\" width=\"16\" height=\"16\" /></a>";
                else $tmpid['ENTRY_IMPORT'] = "\n<a href=\"" . $sess->url("main.php?area=mod&action=lupdate&idmod=" . $mod['idmod'] . "&sourceid=" . $mod['idmod'] . "&idclient=" . $client) . "\">\n<img src=\"tpl/" . $cfg_cms['skin'] . "/img/reinstall.gif\" alt=\"" . $cms_lang['mod_reinstall_save'] . "\" title=\"" . $cms_lang['mod_reinstall_save'] . "\" width=\"16\" height=\"16\" /></a>";
			} elseif ($idclient == '0') $tmpid['ENTRY_IMPORT'] = "\n<img src=\"tpl/" . $cfg_cms['skin'] . "/img/space.gif\" width=\"16\" height=\"16\" />";
            // Modul exportieren
            if ($idclient != '0' && $perm->have_perm(8, 'mod', $mod['idmod']) && $mod['count'] < 1 && !$mod['parse_error'] && empty($mod['source_id'])) $tmpid['ENTRY_EXPORT'] = "\n<a href=\"" . $sess->url("main.php?area=mod&action=copy&idmod=" . $mod['idmod'] . "&from=$client&idclient=$idclient&into=0") . "\">\n<img src=\"tpl/" . $cfg_cms['skin'] . "/img/export.gif\" alt=\"" . $cms_lang['mod_export'] . "\" title=\"" . $cms_lang['mod_export'] . "\" width=\"16\" height=\"16\" /></a>";
			// Modul reinstallieren
			elseif ($idclient != '0' && $perm->have_perm(3, 'mod', $mod['idmod']) && !empty($mod['source_id'])) $tmpid['ENTRY_IMPORT'] = "\n<a href=\"" . $sess->url('main.php?area=mod&action=lupdate&idmod=' . $mod['idmod'] . '&sourceid=' . $mod['source_id'] . '&idclient=' . $client) . "\">\n<img src=\"tpl/" . $cfg_cms['skin'] . "/img/reinstall.gif\" alt=\"" . $cms_lang['mod_reinstall_save'] . "\" title=\"" . $cms_lang['mod_reinstall_save'] . "\" width=\"16\" height=\"16\" /></a>";
			elseif ($idclient != '0') $tmpid['ENTRY_EXPORT'] = "\n<img src=\"tpl/" . $cfg_cms['skin'] . "/img/space.gif\" width=\"16\" height=\"16\" />";
			// Modul downloaden
            if($perm->have_perm(10, 'mod', $mod['idmod'])) $tmpid['ENTRY_DOWNLOAD'] = "\n<a href=\"" . $sess->url("main.php?area=mod&idmod=" . $mod['idmod'] . "&idclient=$idclient&action=download") . "\">\n<img src=\"tpl/" . $cfg_cms['skin'] . "/img/but_download.gif\" alt=\"" . $cms_lang['mod_download'] . "\" title=\"" . $cms_lang['mod_download'] . "\" width=\"16\" height=\"16\" /></a>";
            else $tmpid['ENTRY_DOWNLOAD'] = "\n<img src=\"tpl/" . $cfg_cms['skin'] . "/img/space.gif\" width=\"16\" height=\"16\" />";
            // Modul updaten
            if ($idclient == '0' && $perm->have_perm(12, 'mod', $mod['idmod']) && $rep->enabled()) {
                if (empty($mod['repository_id'])) $tmpid['ENTRY_UPDATE'] = "\n<img src=\"tpl/" . $cfg_cms['skin'] . "/img/space.gif\" width=\"16\" height=\"16\" />";
                elseif (!$rep->online() && $cfg_rep['repository_show_offline'] == 'true') $tmpid['ENTRY_UPDATE'] = "<a name='norepository'><img src=\"tpl/" . $cfg_cms['skin'] . "/img/but_offline.gif\" width=\"16\" height=\"16\" alt=\"" . $cms_lang['mod_repository_notonline'] . "\" title=\"" . $cms_lang['mod_repository_notonline'] . "\" /></a>";
                elseif ($rep->mod_updates($mod['repository_id'], $mod['version'], $perm->have_perm(15, 'mod', $mod['idmod']))) $tmpid['ENTRY_UPDATE'] = "<a href=\"" . $sess->url('main.php?area=mod&action=update&idmod=' . $mod['idmod'] . '&repid=' . $mod['repository_id'] . '&idclient=' . $idclient) . "\"><img src=\"tpl/" . $cfg_cms['skin'] . "/img/but_modul_outofsync.gif\" width=\"16\" height=\"16\" alt=\"" . $cms_lang['mod_repository_update'] . "\" title=\"" . $cms_lang['mod_repository_update'] . "\" /></a>";
                elseif ($cfg_rep['repository_show_up2date']) $tmpid['ENTRY_UPDATE'] = "<a name='norepository'><img src=\"tpl/" . $cfg_cms['skin'] . "/img/but_modul_insync.gif\" width=\"16\" height=\"16\" alt=\"" . $cms_lang['mod_repository_noupdate'] . "\" title=\"" . $cms_lang['mod_repository_noupdate'] . "\" /></a>";
                else $tmpid['ENTRY_UPDATE'] = "\n<img src=\"tpl/" . $cfg_cms['skin'] . "/img/space.gif\" width=\"16\" height=\"16\" />";
            } elseif ($idclient >=1 && ($perm->have_perm(7, 'mod', $mod['idmod']) || $perm->have_perm(8, 'area_mod', 0)) && $rep->loopback()) {
                if (empty($mod['source_id'])) $tmpid['ENTRY_UPDATE'] = "\n<img src=\"tpl/" . $cfg_cms['skin'] . "/img/space.gif\" width=\"16\" height=\"16\" />";
                elseif ($rep->mod_local_update($mod['source_id'], $mod['version'], 'all') || $debug_mod_update) $tmpid['ENTRY_UPDATE'] = "<a href=\"" . $sess->url('main.php?area=mod&action=lupdate&idmod=' . $mod['idmod'] . '&sourceid=' . $mod['source_id'] . '&idclient=' . $idclient) . "\"><img src=\"tpl/" . $cfg_cms['skin'] . "/img/but_modul_outofsync.gif\" width=\"16\" height=\"16\" alt=\"" . $cms_lang['mod_local_update'] . "\" title=\"" . $cms_lang['mod_local_update'] . "\" /></a>";
                elseif ($cfg_rep['repository_show_up2date']) $tmpid['ENTRY_UPDATE'] = "<a name='norepository'><img src=\"tpl/" . $cfg_cms['skin'] . "/img/but_modul_insync.gif\" width=\"16\" height=\"16\" alt=\"" . $cms_lang['mod_repository_noupdate'] . "\" title=\"" . $cms_lang['mod_repository_noupdate'] . "\" /></a>";
                else $tmpid['ENTRY_UPDATE'] = "\n<img src=\"tpl/" . $cfg_cms['skin'] . "/img/space.gif\" width=\"16\" height=\"16\" />";
            }
            //DebugMe! echo "<br>$idmod:".$mod['deletable'].'|'.$perm->have_perm(5, 'mod', $mod['idmod']).'|'.$mod['count_delete'];
            // Modul l�schen
            if ($mod['deletable'] == '1' && $perm->have_perm(5, 'mod', $mod['idmod']) && $mod['count_delete'] < 1) $tmpid['ENTRY_DELBUT'] = "<a href=\"" . $sess->url('main.php?area=mod&action=delete&idmod=' . $mod['idmod'] . '&idclient=' . $idclient) . "\" onclick=\"return delete_confirm()\"><img src=\"tpl/" . $cfg_cms['skin'] . "/img/but_deleteside.gif\" width=\"16\" height=\"16\" alt=\"" . $cms_lang['mod_delete'] . "\" title=\"" . $cms_lang['mod_delete'] . "\" /></a>";
            else $tmpid['ENTRY_DELBUT'] = "\n<img src=\"tpl/" . $cfg_cms['skin'] . "/img/space.gif\" width=\"16\" height=\"16\" />";
            $tmpid['ENTRY_NAME'] = ($idclient >= 1 && $mod['verbose'] != '') ? htmlentities($mod['verbose'], ENT_COMPAT, 'UTF-8') : htmlentities($mod['name'], ENT_COMPAT, 'UTF-8');
            $tmpid['ENTRY_ICON'] = make_image('but_modul.gif', '', '16', '16', false, 'class="icon"');
            $tmpid['ENTRY_ANAME'] = "<a name=\"" . (htmlentities('mod' . $mod['idmod'], ENT_COMPAT, 'UTF-8') . "\"></a>");
            $tmpid['ENTRY_DESCRIPTION'] = htmlentities($mod['description'], ENT_COMPAT, 'UTF-8');
            $tmpid['ENTRY_VERSION'] = htmlentities($mod['version'], ENT_COMPAT, 'UTF-8');
            $tmpid['ENTRY_CAT'] = htmlentities($mod['cat'], ENT_COMPAT, 'UTF-8');
            $tpl->setVariable($tmpid);
            unset($tmpid);
            $tpl->parseCurrentBlock();
        }
    } else {
        $tmp['MOD_NOMODULES'] = !$rep->error() ? $cms_lang['mod_nomodules'] : $cms_lang['err_' . $rep->error(1)];
        $tpl->setCurrentBlock('NOENTRY');
        $tpl->setVariable($tmp);
        unset($tmp);
        $tpl->parseCurrentBlock();
    }
} else {
    // Repository
    $modlist = $rep->mod_list($idclient);
    if (is_array($modlist) && count($modlist) > 0) {
        $tpl->setCurrentBlock('ENTRY');
        foreach ($modlist as $mod) {
            if (!$perm->have_perm(15, 'mod', 0) && (strpos($mod['version'], 'dev') != false && $mod['version'] != '')) continue;
            // Hintergrundfarbe w�hlen
            $tmp['ENTRY_BGCOLOR'] = '#DBE3EF';
            $tmp['OVERENTRY_BGCOLOR'] = '#C7D5EB';
            $mod['count_repository'] = (int) $rep->mod_count('0', $mod['repository_id'], true);
            $mod['titel'] = ( ' ++ ' .$cms_lang['gen_description'] . ' ++ &#10;' . (($mod['cat'] != '') ? $cms_lang['gen_cat'] . ': ' . htmlentities($mod['cat'], ENT_COMPAT, 'UTF-8') . ' &#10;' : '') .
                        $cms_lang['gen_name'] . ': ' . htmlentities($mod['name'], ENT_COMPAT, 'UTF-8') . ' &#10;' .
                        (($mod['version'] != '') ? $cms_lang['gen_version'] . ': ' . htmlentities($mod['version'], ENT_COMPAT, 'UTF-8') . ' &#10;' : '') .
                        $cms_lang['gen_author'] . ': ' . htmlentities($mod['author'], ENT_COMPAT, 'UTF-8')); // todo: real author name
            // Modul Info
            $tmp['CURSOR'] = 'pointer';
            $tmp['ENTRY_INFO'] = "<img style=\"cursor:" . $tmp['CURSOR'] . "\" src=\"tpl/" . $cfg_cms['skin'] . "/img/about.gif\" alt=\"" . $mod['titel'] . "\" title=\"" . $mod['titel'] . "\" width=\"16\" height=\"16\" />";
            // Modul importieren, todo:validate developer module
            if ($perm->have_perm(14, 'area_mod', 0) && $mod['count_repository'] < 1) $tmp['ENTRY_IMPORT'] = "\n<a href=\"" . $sess->url("main.php?area=mod&action=import&repid=" . $mod['repository_id'] . "&idclient=0") . "\">\n<img src=\"tpl/" . $cfg_cms['skin'] . "/img/import.gif\" alt=\"" . $cms_lang['mod_repository_import'] . "\" title=\"" . $cms_lang['mod_repository_import'] . "\" width=\"16\" height=\"16\" /></a>";
            else $tmp['ENTRY_IMPORT'] = "\n<img src=\"tpl/" . $cfg_cms['skin'] . "/img/space.gif\" width=\"16\" height=\"16\">";
            // Modul downloaden, todo:validate developer module
            if($perm->have_perm(13, 'area_mod', 0)) $tmp['ENTRY_DOWNLOAD'] = "\n<a href=\"" . $sess->url("main.php?area=mod&repid=" . $mod['repository_id'] . "&idclient=-1&action=download") . "\">\n<img src=\"tpl/" . $cfg_cms['skin'] . "/img/but_download.gif\" alt=\"" . $cms_lang['mod_repository_download'] . "\" title=\"" . $cms_lang['mod_repository_download'] . "\" width=\"16\" height=\"16\" /></a>";
            else $tmp['ENTRY_DOWNLOAD'] = "\n<img src=\"tpl/" . $cfg_cms['skin'] . "/img/space.gif\" width=\"16\" height=\"16\">";
            $tmp['ENTRY_NAME'] = htmlentities($mod['name'], ENT_COMPAT, 'UTF-8');
            $tmp['ENTRY_DESCRIPTION'] = htmlentities($mod['description'], ENT_COMPAT, 'UTF-8');
            $tmp['ENTRY_VERSION'] = htmlentities($mod['version'], ENT_COMPAT, 'UTF-8');
            $tmp['ENTRY_CAT'] = htmlentities($mod['cat'], ENT_COMPAT, 'UTF-8');
            $tpl->setCurrentBlock('ENTRY');
            $tpl->setVariable($tmp);
            unset($tmp);
            $tpl->parseCurrentBlock();
        }
    } else {
        $tpl->setCurrentBlock('NOENTRY');
        $tmp['MOD_NOMODULES'] = !$rep->error() ? $cms_lang['mod_nomodules'] : $cms_lang['err_' . $rep->error(1)];
        $tpl->setVariable($tmp);
        unset($tmp);
        $tpl->parseCurrentBlock();
    }
}
unset($tmp);
// Upload
if($perm->have_perm(9, 'area_mod', 0) && $idclient != '-1') {
    $tpl->setCurrentBlock('FILEUPLOAD');
    $tmp['FILEUPLOAD_SESSIONNAME'] = $sess->name;
    $tmp['FILEUPLOAD_SESSIONID'] = $sess->id;
    $tmp['FILEUPLOAD_AREA'] = 'mod';
    $tmp['FILEUPLOAD_ACTION'] = 'upload';
    $tmp['FILEUPLOAD_CLIENT'] = $idclient;
    $tmp['FILEUPLOAD_TEXT'] = $cms_lang['mod_upload'];
    $tmp['FILEUPLOAD_NAME'] = 'mod_upload_file';
    $tmp['FILEUPLOAD_HINT'] = $cms_lang['mod_upload'];
    $tmp['FILEUPLOAD_PICT'] = 'tpl/' . $cfg_cms['skin'] . '/img/upl_upload.gif';
} else {
    $tpl->setCurrentBlock('CLOSELINE');
    $tmp['NOVALUE'] = '&nbsp';
}
$tpl->setVariable($tmp);
unset($tmp);
$tpl->parseCurrentBlock();
?>
