<?PHP
// File: $Id: inc.plug.php 28 2008-05-11 19:18:49Z mistral $
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
$perm->check('area_plug');

if ($idclient != 0 && $idclient != (int) $client && $idclient != '-1' || !isset($idclient)) $idclient = (int) $client;
switch($action) {
    case 'copy':// Plugin kopieren
        $errno = plug_copy($idplug, $from, $into);
        $sess->freeze();
        header ('Location:' . $sess->urlRaw("main.php?area=plug&errno=" . $errno . "&idclient=" . $idclient));
        break;
    case 'save':// Plugin speichern
        $perm->check(3, 'plug', $idplug);
        $oldliste = !$idplug ? $rep->get_plugin_list() : array();
        if (empty($plugname) || $plugname == '') $plugname = $cms_lang['plug_defaultname'];
        if (preg_match('/^[0-9].*$/', $plugname)) {
            $errno = '1625';
            $idclient = $client;
            break;
		}
        if (empty($plugversion) || $plugversion == '') $plugversion = '1.0';
        if (empty($root_name) || $root_name == '' || in_array($root_name, $oldliste)) {
            $errno = (in_array($root_name, $oldliste)) ? '1624' : '1623';
            $idclient = $client;
            break;
        }
        //ATTENTION!!! plug_save(...) set global $idplug/ necessary for apply header
        $errno = plug_save($idplug, $plugname, $description, $plugversion, $plugcat, $idclient, $repid, $install_sql, $uninstall_sql, $update_sql, $root_name, $index_file);
        if ( isset($_REQUEST['sf_apply']) ) {
        	header ('Location:' . $sess->urlRaw("main.php?area=plug_edit&idplug=$idplug&idclient=$idclient&editsql=$editsql&errno=$errno"));
      		exit;
        }
        break;
    case 'save_config':// Konfiguration speichern
        $perm->check(4, 'plug', $idplug);
        $config = make_array_to_urlstring($PLUG_VAR);
        $errno = plug_save_config($idplug, $config, $plug_config_overwrite_all);
        if ( isset($_REQUEST['sf_apply']) ) {
        	header ('Location:' . $sess->urlRaw("main.php?area=plug_config&idplug=$idplug&idclient=$idclient"));
      		exit;
        }
        break;
    case 'download': // Plugin downloaden
        $perm->check(10, 'plug', $idplug);
        $gzip = (!$nogzip && $type != 'tar' && $cfg_cms['gzip_enabled'] == true) ? true : false;
        $name = (empty($name)) ? false : urldecode($name);
        $type = (empty($type)) ? false : trim($type);
        $errno = plug_download($idplug, $idclient, $gzip, $name, $type);
        break;
    case 'upload': // Plugin hochladen
        $perm->check(9, 'plug', $idplug);
        if ($override == 'false') {
            if ($sess->is_registered('s_update')) $sess->unregister('s_update');
            $errno = '1618';
            break;
        }
        else list($errno, $errmsg) = plug_upload($idclient, $override);
        if ($errno == '1617') {
            // Plugin Update?
            $link = $sess->url('main.php?area=plug&action=upload&idclient=' . $idclient);
            $body_onload_func = "confirm_to_url('" . sprintf($cms_lang['plug_confirm_update'], $s_upload['plugname'], $s_upload['plugversion']) . "','" . $link . "','override');";
            $sess->freeze();
        } elseif ($errno == '1619') {
            // Plugin Reinstall?
            $link = $sess->url('main.php?area=plug&action=upload&idclient=' . $idclient);
            $body_onload_func = "confirm_to_url('" . sprintf($cms_lang['plug_confirm_reinstall'], $s_upload['plugname'], $s_upload['plugversion']) . "','" . $link . "','override');";
            $sess->freeze();
        }
        break;
    case 'delete': // Plugin löschen
        $perm->check(5, 'plug', $idplug);
        $errno = plug_delete($idplug, $idclient);
        $sess->freeze();
        header ('Location:' . $sess->urlRaw("main.php?area=plug&errno=" . $errno . "&idclient=" . $idclient));
        break;
    case 'update': // Plugin updaten Repository
        $perm->check(12, 'mod', $idplug);
        $updatedata = $rep->plug_update_data($repid);
        if ($updatedata['repository_id'] == $repid) $errno = plug_update($idplug, $repid, $updatedata['name'], $updatedata['description'], $updatedata['version'], $updatedata['cat'], $updatedata['input'], $updatedata['output'], $updatedata['install_sql'], $updatedata['uninstall_sql'], $updatedata['update_sql'], $idclient);
        else $errno = '1500';
        break;
    case 'import': // Plugin import Repository
        $perm->check(14, 'mod', $idplug);
        $installdata = $rep->plug_import_data($repid);
        if ($installdata['repository_id'] == $repid) $errno = plug_install($repid, $installdata['name'], $installdata['description'], $installdata['version'], $installdata['cat'], $installdata['input'], $installdata['output'], $installdata['install_sql'], $installdata['uninstall_sql'], $installdata['update_sql'], $installdata['root_name'], $installdata['index_file']);
        else $errno = '1500';
        break;
} 
/**
 * 3. Eventuelle Dateien zur Darstellung includieren
 */
include('inc/inc.header.php');
/**
 * 4. Bildschirmausgabe aufbereiten und ausgeben
 */
// Templatedatei laden
$tpl->loadTemplatefile('plug.tpl');
unset($tmp);
if ($idclient > '0') {
    if ($perm->have_perm(2, 'area_plug', 0)) $tmp['NEW_PLUG'] = "<a class=\"action\" href=\"" . $sess->url("main.php?area=plug_edit&idclient=0") . "\">" . $cms_lang['plug_new'] . "</a>&nbsp;|&nbsp;";
    if ($perm->have_perm(7, 'area_plug', 0)) $tmp['IMPORT_PLUG'] = "<a class=\"action\" href=\"" . $sess->url('main.php?area=plug&idclient=0') . "\">" . $cms_lang['plug_import'] . "</a>&nbsp;";
} else {
    $tmp['BACK'] = "<a class=action href=\"" . $sess->url("main.php?area=plug&idclient=$client") . "\">" . $cms_lang['gen_back'] . "</a>&nbsp;";
    if ($perm->have_perm(11, 'area_plug', 0) && $rep->enabled()) {
        if ($idclient == 0) {
            $tmp['BACK'] .= "|&nbsp;<a class=\"action\" href=\"" . $sess->url('main.php?area=plug&idclient=-1') . "\">" . $cms_lang['plug_repository'] . "</a>&nbsp;";
        } elseif ($idclient == '-1') {
            $tmp['BACK'] .= "|&nbsp;<a class=\"action\" href=\"" . $sess->url('main.php?area=plug&idclient=0') . "\">" . $cms_lang['plug_folder'] . "</a>&nbsp;";
        } 
    } 
} 
$tmp['AREA'] = ($idclient == '0') ? (($perm->have_perm(12, 'plug', 0) && $rep->enabled()) ? $cms_lang['area_plug_folder'] : $cms_lang['area_plug_import']) : (($idclient == '-1') ? (($perm->have_perm(12, 'plug', 0) && $rep->enabled()) ? $cms_lang['area_plug_repository'] : $cms_lang['area_plug_import']) : $cms_lang['area_plug']);
$tmp['FOOTER_LICENSE'] = $cms_lang['login_licence'];
$errno3 = ($rep->error()) ? $rep->error(1) : false;
if(!empty($errno) || !empty($errno3) || !empty($errmsg)) {
    $okmessages = array(1602, 1605, 1606, 1607, 1608, 1609, 1610, 1611, 1612, 1614);
	$warnmessages = array(1617, 1618, 1619);
    
    
    if ($errno) {
    	$msg = $cms_lang["err_$errno"];
    }
    
    if (!empty($errno3)) $msg .= '<br />'. $cms_lang['err_' . $errno3];
    if (!empty($errmsg)) $msg .= '<br /> '. $errmsg;
    
	if (in_array($errno, $okmessages)) {
		$tpl->setCurrentBlock('OK');
		$tpl_error['OKMESSAGE'] = $msg;
	} else if (in_array($errno, $okmessages)) {
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

$tmp['PLUG_PLUGINNAME'] = $cms_lang['plug_pluginname'];
$tmp['PLUG_DESCRIPTION'] = $cms_lang['plug_description'];
$tmp['PLUG_PLUGINVERSION'] = $cms_lang['plug_version'];
$tmp['PLUG_PLUGINCAT'] = $cms_lang['plug_cat'];
$tmp['PLUG_ACTION'] = $cms_lang['plug_action'];
$tmp['PLUG_AUTHOR'] = $cms_lang['plug_author'];
$tpl->setVariable($tmp);
unset($tmp);
if ($idclient != '-1') {
    // Plugine aus der Datenbank suchen
    $tpl->setCurrentBlock('ENTRY');
    $pluglist = $rep->plug_list($idclient);
    if (is_array($pluglist) && count($pluglist) > 0) {
        foreach ($pluglist as $plug) {
            if (!$perm->have_perm(1, 'plug', $plug['idplug'])) continue;
            if (!$perm->have_perm(15, 'plug', 0) && (strpos($plug['version'], 'dev') != false && $plug['version'] != '')) continue;
            $plug['count']  = ($idclient == '0') ?  (int) $rep->plug_count($client, $plug['idplug']) : ($rep->plug_count($idclient, $plug['source_id']) + $rep->plug_count('0', $plug['source_id']));
            $plug['count_delete'] = ($idclient == '0') ? (int) $rep->plug_count('all', $plug['idplug']) : 0;
            // Hintergrundfarbe wählen
            $tmp['ENTRY_BGCOLOR'] = '#ffffff';
            $tmp['OVERENTRY_BGCOLOR'] = '#fff7ce';
            $tmp['ENTRY_ICON'] = make_image('but_plugin.gif', '', '16', '16', false, 'class="icon"');
            // Plugin bearbeiten
            if ($perm->have_perm(3, 'plug', $plug['idplug'])) $tmp['ENTRY_EDIT'] = "<a class=\"action\" href=\"" . $sess->url("main.php?area=plug_edit&idplug=" . $plug['idplug'] . "&idclient=$idclient") . "\"><img src=\"tpl/" . $cfg_cms['skin'] . "/img/but_edit.gif\" alt=\"" . $cms_lang['plug_edit'] . "\" title=\"" . $cms_lang['plug_edit'] . "\" width=\"16\" height=\"16\"></a>";
            else $tmpid['ENTRY_EDIT'] = "<img src=\"tpl/" . $cfg_cms['skin'] . "/img/space.gif\" width=\"16\" height=\"16\">"; 
            // Plugin Sql erneut installieren
            /* todo:oldperms!
            *if ( $idclient > 0 && ($perm->have_perm('area_plug_import') || $perm->have_perm('area_plug_export')) && $plug['install_sql'] != '' ) $tmp['ENTRY_SQL'] = "<a class=\"action\" href=\"".$sess->url("main.php?area=plug&action=copy&idplug=".$plug['idplug']."&into=1&from=1&idclient=$idclient")."\"><img src=\"tpl/".$cfg_cms['skin']."/img/but_calendar.gif\" border=\"0\" alt=\"".$cms_lang['plug_rebuild_sql']."\" title=\"".$cms_lang['plug_rebuild_sql']."\" width=\"14\" height=\"14\"></a>";
	        *elseif ( $idclient > 0 && $perm->have_perm('area_plug_import') ) $tmp['ENTRY_SQL'] = "<img src=\"tpl/".$cfg_cms['skin']."/img/space.gif\" border=\"0\" width=\"13\" height=\"13\">";// Plugin konfigurieren
	        */
            // Plugin konfigurieren
            $plug['but_config'] = $plug['config'] != '' ? 'but_config_red.gif' : 'but_config.gif';
            $plug['plug_config'] = $plug['config'] != '' ? $cms_lang['plug_config_change'] : $cms_lang['plug_config'];
            if($perm->have_perm(4, 'plug', $plug['idplug']) || $perm->have_perm(18, 'plug', $plug['idplug'])) $tmp['ENTRY_CONFIG'] = "<a class=\"action\" href=\"" . $sess->url("main.php?area=plug_config&idplug=" . $plug['idplug'] . "&idclient=$idclient") . "\"><img src=\"tpl/" . $cfg_cms['skin'] . "/img/but_config.gif\" alt=\"" . $plug['plug_config'] . "\" title=\"" . $plug['plug_config'] . "\" width=\"16\" height=\"16\" /></a>";
            else $tmpid['ENTRY_CONFIG'] = "<img src=\"tpl/" . $cfg_cms['skin'] . "/img/space.gif\" alt=\"\" width=\"16\" height=\"16\">"; 
            // Plugin importieren
            if ($idclient == '0' && ($perm->have_perm(7, 'plug', $plug['idplug']) || $perm->have_perm(8, 'area_plug', 0)) && $plug['count'] < 1) $tmp['ENTRY_IMPORT'] = "<a class=\"action\" href=\"" . $sess->url("main.php?area=plug&action=copy&idplug=" . $plug['idplug'] . "&into=$client&idclient=$client") . "\"><img src=\"tpl/" . $cfg_cms['skin'] . "/img/import.gif\" alt=\"" . $cms_lang['plug_import'] . "\" title=\"" . $cms_lang['plug_import'] . "\" width=\"16\" height=\"16\" /></a>";
            elseif ($idclient == '0') $tmp['ENTRY_IMPORT'] = "<img src=\"tpl/" . $cfg_cms['skin'] . "/img/space.gif\" alt=\"\" width=\"16\" height=\"16\">";
            // Plugin exportieren
            /* todo:only for repository export!
            /*
             *if ($idclient != '0' && $perm->have_perm(8, 'plug', $plug['idplug']) && $plug['count'] < 1) $tmp['ENTRY_EXPORT'] = "<a class=\"action\" href=\"" . $sess->url("main.php?area=plug&action=copy&idplug=" . $plug['idplug'] . "&from=$client&idclient=$idclient") . "\"><img src=\"tpl/" . $cfg_cms['skin'] . "/img/export.gif\" border=\"0\" alt=\"" . $cms_lang['plug_export'] . "\" title=\"" . $cms_lang['plug_export'] . "\" width=\"19\" height=\"11\"></a>";
             *elseif ($idclient != '0') $tmpid['ENTRY_EXPORT'] = "<img src=\"tpl/" . $cfg_cms['skin'] . "/img/space.gif\" border=\"0\" width=\"19\" height=\"11\">";
             */
            // Plugin downloaden
            if($perm->have_perm(10, 'plug', $plug['idplug']))$tmp['ENTRY_DOWNLOAD'] = "<a class=\"action\" href=\"" . $sess->url("main.php?area=plug&idplug=" . $plug['idplug'] . "&action=download&idclient=$idclient") . "\"><img src=\"tpl/" . $cfg_cms['skin'] . "/img/but_download.gif\" alt=\"" . $cms_lang['plug_download'] . "\" title=\"" . $cms_lang['plug_download'] . "\" width=\"16\" height=\"16\" /></a>";
            else $tmpid['ENTRY_DOWNLOAD'] = "<img src=\"tpl/" . $cfg_cms['skin'] . "/img/space.gif\" alt=\"\" width=\"16\" height=\"16\">"; 
            // Plugin updaten
            if ($perm->have_perm(12, 'plug', $plug['idplug']) && $rep->enabled()) {
                if (!$plug['repository_id']) $tmp['ENTRY_UPDATE'] = "<img src=\"tpl/" . $cfg_cms['skin'] . "/img/space.gif\" alt=\"\" width=\"16\" height=\"16\" />";
                elseif (!$rep->online() && $cfg_rep['repository_show_offline'] == 'true') $tmp['ENTRY_UPDATE'] = "<a name='norepository'><img src=\"tpl/" . $cfg_cms['skin'] . "/img/but_offline.gif\" width=\"16\" height=\"16\" alt=\"" . $cms_lang['plug_repository_notonline'] . "\" title=\"" . $cms_lang['plug_repository_notonline'] . "\" /></a>";
                elseif ($rep->plug_updates($plug['repository_id'], $plug['version'], $perm->have_perm(15, 'plug', $plug['idplug']))) $tmp['ENTRY_UPDATE'] = "<a href=\"" . $sess->url('main.php?area=plug&action=update&idplug=' . $plug['idplug'] . '&repid=' . $plug['repository_id'] . '&idclient=' . $idclient) . "\"><img src=\"tpl/" . $cfg_cms['skin'] . "/img/but_onpublish.gif\" width=\"16\" height=\"16\" alt=\"" . $cms_lang['plug_repository_update'] . "\" title=\"" . $cms_lang['plug_repository_update'] . "\" /></a>";
                elseif ($cfg_rep['repository_show_up2date']) $tmp['ENTRY_UPDATE'] = "<a name='norepository'><img src=\"tpl/" . $cfg_cms['skin'] . "/img/but_online.gif\" width=\"16\" height=\"1163\" alt=\"" . $cms_lang['plug_repository_noupdate'] . "\" title=\"" . $cms_lang['plug_repository_noupdate'] . "\" /></a>";
                else $tmp['ENTRY_UPDATE'] = "<img src=\"tpl/" . $cfg_cms['skin'] . "/img/space.gif\" alt=\"\" width=\"16\" height=\"16\">";
            } 
            // Plugin löschen
            if ($plug['deletable'] == '1' && $perm->have_perm(5, 'plug', $plug['idplug']) && ( ($idclient == 0) ? $plug['count_delete'] < 1 : 1 ) ) $tmp['ENTRY_DELBUT'] = "<a href=\"" . $sess->url('main.php?area=plug&action=delete&idplug=' . $plug['idplug'] . '&idclient=' . $idclient) . "\" onclick=\"return delete_confirm()\"><img src=\"tpl/" . $cfg_cms['skin'] . "/img/but_deleteside.gif\" width=\"16\" height=\"16\" alt=\"" . $cms_lang['plug_delete'] . "\" title=\"" . $cms_lang['plug_delete'] . "\" /></a>";
            else $tmp['ENTRY_DELBUT'] = "<img src=\"tpl/" . $cfg_cms['skin'] . "/img/space.gif\" alt=\"\" width=\"16\" height=\"16\" />";
            $tmp['ENTRY_NAME'] = htmlentities($plug['name'], ENT_COMPAT, 'UTF-8');
            $tmp['ENTRY_DESCRIPTION'] = htmlentities($plug['description'], ENT_COMPAT, 'UTF-8');
            $tmp['ENTRY_VERSION'] = htmlentities($plug['version'], ENT_COMPAT, 'UTF-8');
            $tmp['ENTRY_CAT'] = htmlentities($plug['cat'], ENT_COMPAT, 'UTF-8');
            $tpl->setVariable($tmp);
            $tpl->parseCurrentBlock();
            unset($tmp);
        } 
    } else {
        $tpl->setCurrentBlock('NOENTRY');
        $tmp['PLUG_NOPLUGINS'] = !$rep->error() ? $cms_lang['plug_noplugins'] : $cms_lang['err_' . $rep->error(1)];
        $tpl->setVariable($tmp);
        $tpl->parseCurrentBlock();
        unset($tmp);
    } 
} else {
    // Repository
    $tpl->setCurrentBlock('ENTRY');
    $pluglist = $rep->plug_list($idclient);
    if (is_array($pluglist) && count($pluglist) > 0) {
        foreach ($pluglist as $plug) {
            if (!$perm->have_perm(15, 'plug', 0) && (strpos($plug['version'], 'dev') != false && $plug['version'] != '')) continue;
            $plug['count'] = (int) $rep->plug_count('0', $plug['repository_id'], true); 
            // Hintergrundfarbe wählen
            $tmp['ENTRY_BGCOLOR'] = '#ffffff';
            $tmp['OVERENTRY_BGCOLOR'] = '#fff7ce';
            $tmp['ENTRY_ICON'] = make_image('but_plugin.gif', '', '16', '16');
            // Plugin importieren, todo:validate developer module
            if ($perm->have_perm(14, 'area_plug', 0) && $plug['count'] < 1) $tmp['ENTRY_IMPORT'] = "<a class=\"action\" href=\"" . $sess->url("main.php?area=plug&action=import&repid=" . $plug['repository_id'] . "&idclient=$client") . "\"><img src=\"tpl/" . $cfg_cms['skin'] . "/img/import.gif\" alt=\"" . $cms_lang['plug_repository_import'] . "\" title=\"" . $cms_lang['plug_repository_import'] . "\" width=\"16\" height=\"16\" /></a>"; 
            // Plugin downloaden, todo:validate developer module
            if ($perm->have_perm(13, 'area_plug', 0))$tmp['ENTRY_DOWNLOAD'] = "<a class=\"action\" href=\"" . $sess->url("main.php?area=plug&repid=" . $plug['repository_id'] . "&idclient=-1&action=download") . "\"><img src=\"tpl/" . $cfg_cms['skin'] . "/img/but_download.gif\" alt=\"" . $cms_lang['plug_repository_download'] . "\" title=\"" . $cms_lang['plug_repository_download'] . "\" width=\"16\" height=\"16\"></a>";
            /* // Plugin updaten
            * todo:validate list of local plugins
            * todo:oldperms!
            *if ( $perm->have_perm('area_plug_repository_update')  && $rep->enabled() ) {
            *   if ( $plug['repository_id'] > 0 && !$rep->online() ) $tmp['ENTRY_UPDATE'] = "<a name='norepository'><img src=\"tpl/".$cfg_cms['skin']."/img/but_offline.gif\" width=\"13\" height=\"13\" border=\"0\" alt=\"".$cms_lang['plug_repository_notonline']."\" title=\"".$cms_lang['plug_repository_notonline']."\"></a>";
            *   elseif ( $rep->plug_updates($plug['repository_id']) && $plug['repository_id'] > 0 ) $tmp['ENTRY_UPDATE'] = "<a href=\"".$sess->url('main.php?area=plug&action=update&idplug='.$plug['idplug'].'&repid='.$db->f('repository_id').'&idclient='.$idclient)."\"><img src=\"tpl/".$cfg_cms['skin']."/img/but_onpublish.gif\" width=\"13\" height=\"13\" border=\"0\" alt=\"".$cms_lang['plug_repository_update']."\" title=\"".$cms_lang['plug_repository_update']."\"></a>";
	        *   elseif ( $plug['repository_id'] > 0 ) $tmp['ENTRY_UPDATE'] = "<a name='norepository'><img src=\"tpl/".$cfg_cms['skin']."/img/but_online.gif\" width=\"13\" height=\"13\" border=\"0\" alt=\"".$cms_lang['plug_repository_noupdate']."\" title=\"".$cms_lang['plug_repository_noupdate']."\"></a>";
            *   else $tmp['ENTRY_UPDATE'] = "<img src=\"tpl/".$cfg_cms['skin']."/img/space.gif\" border=\"0\" width=\"13\" height=\"13\">";
            *}
            */
            // Plugin löschen
            $tmp['ENTRY_DELBUT'] = "<img src=\"tpl/" . $cfg_cms['skin'] . "/img/space.gif\" width=\"16\" height=\"16\" alt=\"\" />";
            $tmp['ENTRY_NAME'] = htmlentities($plug['name'], ENT_COMPAT, 'UTF-8');
            $tmp['ENTRY_DESCRIPTION'] = htmlentities($plug['description'], ENT_COMPAT, 'UTF-8');
            $tmp['ENTRY_VERSION'] = htmlentities($plug['version'], ENT_COMPAT, 'UTF-8');
            $tmp['ENTRY_CAT'] = htmlentities($plug['cat'], ENT_COMPAT, 'UTF-8');
            $tpl->setVariable($tmp);
            $tpl->parseCurrentBlock();
            unset($tmp);
        } 
    } else {
        $tpl->setCurrentBlock('NOENTRY');
        $tmp['PLUG_NOPLUGINS'] = !$rep->error() ? $cms_lang['plug_noplugins'] : $cms_lang['err_' . $rep->error(1)];
        $tpl->setVariable($tmp);
        $tpl->parseCurrentBlock();
        unset($tmp);
    } 
} 
// Upload
if($perm->have_perm('area_plug_upload') && $idclient == '0') {
    $tpl->setCurrentBlock('FILEUPLOAD');
    $tmp['FILEUPLOAD_SESSIONNAME'] = $sess->name;
    $tmp['FILEUPLOAD_SESSIONID'] = $sess->id;
    $tmp['FILEUPLOAD_AREA'] = 'plug';
    $tmp['FILEUPLOAD_ACTION'] = 'upload';
    $tmp['FILEUPLOAD_CLIENT'] = 0;
    $tmp['FILEUPLOAD_TEXT'] = $cms_lang['plug_upload'];
    $tmp['FILEUPLOAD_NAME'] = 'plug_upload_file';
    $tmp['FILEUPLOAD_HINT'] = $cms_lang['plug_upload'];
    $tmp['FILEUPLOAD_PICT'] = 'tpl/' . $cfg_cms['skin'] . '/img/upl_upload.gif';
} else {
    $tpl->setCurrentBlock('CLOSELINE');
    $tmp['NOVALUE'] = '&nbsp';
} 
$tpl->setVariable($tmp);
$tpl->parseCurrentBlock();
unset($tmp);
?>
