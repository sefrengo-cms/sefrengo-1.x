<?PHP
// File: $Id: fnc.mod.php 37 2008-05-12 13:26:12Z mistral $
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
 * 1. Benötigte Funktionen und Klassen includieren
 */
include_once('inc/fnc.libary.php');

function mod_copy($idmod, $from, $into) {
    global $db, $auth, $cms_db, $cfg_cms, $rep, $mod, $perm;
    if (!$from) $from = '0';
    if (!$into) $into = '0';
    $modul = $rep->mod_data($idmod, $from);
    if (is_array($modul)) {
        $sql = "SELECT idmod, source_id, is_install FROM " . $cms_db['mod'] . "
			WHERE idclient >= 1 AND source_id='$idmod' AND is_install = '1' LIMIT 0, 1";
        $db->query($sql);
        if ($db->next_record()) $installed = $db->f('idmod');
        $oid = $idmod;
        $install = $modul['install_sql'];
        $is_install = (bool) $modul['is_install'];
        $installer = ($into > 0 && $install != '' && !$is_install && !$installed) ? 1 : 0;
        if ($from != $into) {
            $name        = make_string_dump($modul['name']);
			$verbose     = make_string_dump($modul['verbose']);
            $description = make_string_dump($modul['description']);
            $version     = make_string_dump($modul['version']);
            $cat    = make_string_dump($modul['cat']);
            $input  = make_string_dump($modul['input']);
            $output = make_string_dump($modul['output']);
            $config = make_string_dump($modul['config']);
            $rep_id = make_string_dump($modul['repository_id']);
            $source_id     = make_string_dump($modul['source_id']);
            $sql_install   = mysql_escape_string($rep->decode_sql($modul['install_sql']));
            $sql_uninstall = mysql_escape_string($rep->decode_sql($modul['uninstall_sql']));
            $sql_update    = mysql_escape_string($rep->decode_sql($modul['update_sql']));
            $source        = ($from == '0') ? $idmod : (($source_id > 0) ? $source_id : '');
            $repid         = $rep->gen_new_mod($name, true);
            $checked       = (string) $modul['checked'];
            $sql = "INSERT INTO
					" . $cms_db['mod'] . "
					(name, description, version, cat, input, output, config, idclient, author, created,
					lastmodified, repository_id, install_sql, uninstall_sql, update_sql, source_id, is_install, verbose, checked)
				    VALUES
					('$name', '$description', '$version', '$cat', '$input', '$output', '$config',
					'$into', '" . $auth->auth['uid'] . "', '" . time() . "', '" . time() . "', '$repid',
					'$sql_install', '$sql_uninstall', '$sql_update', '$source', '$installer', '$verbose', '$checked')";
            $affect = $db->query($sql);
            if (!$affect || $affect < 1) return '0400';
            $idmod = $db->insert_id();
            // Neues Userrecht erstellen
            $perm->xcopy_perm($oid, 'mod', $idmod, 'mod', 0x00000AFD, 0, 0, true);
            if ($into == '0' && $source == '') {
                $sql = "UPDATE
						" . $cms_db['mod'] . "
						SET
						source_id='$idmod'
						WHERE
						idmod='$oid'";
                $db->query($sql);
            }
        } else {
            $uninstall = $modul['uninstall_sql'];
            if ($uninstall != '' && $is_install) {
                $error = $rep->bulk_sql($uninstall);
                // Event
                fire_event('mod_uninstall_sql', array('idmod' => $idmod, 'name' => $name));
                $return = '0407';
                $installer = (!$error) ? true : false;
            }
        }
        if ($installer) {
            $error = $rep->bulk_sql($install);
            // Event
            fire_event('mod_install_sql', array('idmod' => $idmod, 'name' => $name));
            return '0409';
        }
        // Event
        if ($from != '0') {
            fire_event('mod_export', array('idmod' => $idmod, 'name' => $name));
            $return = '0414';
        } else {
            fire_event('mod_import', array('idmod' => $idmod, 'name' => $name));
            $return = '0402';
        }
        return (!$error ? $return : $error);
    }
}
function mod_save_config($idmod, $config = '') {
    // Modul Konfiguration übernehmen
    global $db, $cms_db, $perm;
    set_magic_quotes_gpc($config);
    $sql = "UPDATE " . $cms_db['mod'] . " SET config='$config' WHERE idmod='$idmod'";
    $db->query($sql);
    
    // Rechte setzen
    if ($perm->have_perm('6', 'mod', $idmod)) {
        global $cms_gruppenids, $cms_gruppenrechte, $cms_gruppenrechtegeerbt, $cms_gruppenrechteueberschreiben;
        $perm->set_group_rights('mod', $idmod, $cms_gruppenids, $cms_gruppenrechte, $cms_gruppenrechtegeerbt, $cms_gruppenrechteueberschreiben, '',0x00000AFD);
    }
}
function mod_set_config_all($idmod, $config = '') {
    // Konfiguration in alle Templatekonfigurationen übernehmen?
    global $db, $cms_db;
    if ($idmod >= 1) {
        $sql = "SELECT idcontainer FROM " . $cms_db['container'] . " WHERE idmod='$idmod'";
        $db->query($sql);
        while ($db->next_record()) $idcontainer_array[] = $db->f('idcontainer');
        if (is_array($idcontainer_array)) {
            $sql = "UPDATE " . $cms_db['container_conf'] . " SET config='$config'
                         	WHERE idcontainer IN(" . implode(',', $idcontainer_array) . ")";
            $db->query($sql);
            mod_set_config_status($idmod);
        }
    }
}
function mod_set_config_status($idmod) {
    // Status der 'code' Tabelle ändern
    if ($idmod >= 1) {
        $list = get_idtplconf_by_using_type($idmod, 'mod');
        $list = get_idcode_by_idtplconf($list);
        change_code_status($list, '1');
        unset($list);
    }
}
function mod_save ($idmod_in, $name, $verbose, $description, $modversion, $modcat, $input, $output, $idclient, $repid = '', $sql_install = '', $sql_uninstall = '', $sql_update = '', $mod_rebuild_sql = false, $source_id = '0', $mod_no_wedding = false, $stripe = false, $mod_config_takeover = false) {
    global $db, $auth, $cms_db, $cfg_cms, $cms_lang, $cfg_client, $rep, $perm;
    global $idmod;//make global for header redirect
    $idmod = $idmod_in;
    
    // Eintrag in 'mod' Tabelle
    if (empty($name) || $name == '') $name = $cms_lang['mod_defaultname'];
    if (empty($modversion) || $modversion == '') $modversion = '1.0';
    if ($stripe == 1) {
        $name        = make_string_dump($name);
		$verbose     = make_string_dump($verbose);
        $description = make_string_dump($description);
        $modversion  = make_string_dump($modversion);
        $modcat      = make_string_dump($modcat);
        $input       = make_string_dump($input);
        $output      = make_string_dump($output);
    } elseif ($stripe != 2){
        set_magic_quotes_gpc($name);
        set_magic_quotes_gpc($verbose);
        set_magic_quotes_gpc($description);
        set_magic_quotes_gpc($modversion);
        set_magic_quotes_gpc($modcat);
        set_magic_quotes_gpc($input);
        set_magic_quotes_gpc($output);
    }
    $checked = (($err_i = $rep->mod_test(cms_stripslashes($input), $idmod)) || ($err_0 = $rep->mod_test(cms_stripslashes($output), $idmod))) ? '0' : '1';
    $modverbose = ($verbose == '-1') ? $name : $verbose;
    $mod_sql_uninstall = $sql_uninstall;
    $mod_sql_install = $sql_install;
    $sql_install = mysql_escape_string($rep->decode_sql($sql_install));
    $sql_uninstall = mysql_escape_string($rep->decode_sql($sql_uninstall));
    $sql_update = mysql_escape_string($rep->decode_sql($sql_update));
    if ($mod_no_wedding == true) {
       $source_id     = 0;
       $repositoryid  = $rep->gen_new_mod($name);
       $update_source = ", source_id='0'";
    } elseif ($source_id) {
       $repositoryid = $rep->gen_new_mod($name, true);
    } elseif ($repid == '') {
       $repositoryid = $rep->gen_new_mod($name);
       if ( ($errno = $rep->error(true)) ) return $errno;
    } else {
       $repositoryid = $rep->gen_new_mod($name, true);
    }
    if ( ($errno = $rep->error(true)) ) return $errno;
    elseif ( empty($input) && empty($output) ) return '0424';
    if (!$idmod) {
        // Modul existiert noch nicht
        $sql = "INSERT INTO
				" . $cms_db['mod'] . "
				(name, description, version, cat, input, output, idclient, author, created, lastmodified,
				repository_id, install_sql, uninstall_sql, update_sql, source_id, verbose, checked)
				VALUES
				('$name', '$description', '$modversion', '$modcat', '$input', '$output', '$idclient',
				'" . $auth->auth['uid'] . "', '" . time() . "', '" . time() . "', '$repositoryid', '$sql_install', '$sql_uninstall', '$sql_update', '$source_id', '$modverbose', '$checked')";
        $affect = $db->query($sql);
        if (!$affect || $affect < 1) return '0400';
        $idmod = $last_id = $db->insert_id();
        if ($mod_config_takeover == true) {
			$modul = $rep->mod_data($source_id, $idclient);
			mod_save_config($idmod, make_string_dump($modul['config']));
		}
		// Event
        fire_event('mod_new', array('idmod' => $idmod, 'name' => $name));
        
    } else {
	// hat sich das Modul geändert?
    $sql = "SELECT output FROM ".$cms_db['mod']." WHERE idmod='$idmod'";
	$db->query($sql);
	$db->next_record();
	$output_old = $db->f('output');
	set_magic_quotes_gpc($output_old);
	
	//don't change verbose name by sql update
	if ($verbose == '-2') {
		$sql_verbose_name = '';
	} else {
		$sql_verbose_name = "verbose = '$modverbose',";
	}
	
	if ($output != $output_old) {
            $sql = "UPDATE " . $cms_db['mod'] . "
					SET
					name='$name', description='$description', version = '$modversion', cat = '$modcat',
					input='$input', output='$output', author='" . $auth->auth['uid'] . "', lastmodified='" . time() . "',
					install_sql ='$sql_install', uninstall_sql ='$sql_uninstall', update_sql ='$sql_update' $update_source, repository_id = '$repositoryid', $sql_verbose_name checked = '$checked'
					WHERE
					idmod='$idmod'";
            $db->query($sql);
            $change = 'true';
        } else {
            $sql = "UPDATE " . $cms_db['mod'] . "
					SET
					name='$name', description='$description', version = '$modversion', cat = '$modcat', input='$input',
					author='" . $auth->auth['uid'] . "', lastmodified='" . time() . "', install_sql='$sql_install',
					uninstall_sql='$sql_uninstall', update_sql='$sql_update' $update_source, repository_id = '$repositoryid', $sql_verbose_name checked = '$checked'
					WHERE
					idmod='$idmod'";
            $db->query($sql);
        }
        // Event
        fire_event('mod_edit', array('idmod' => $idmod, 'name' => $name));
    }
    if ($idclient > 0 && $mod_sql_install != '' && $mod_rebuild_sql == true) {
        if ($mod_sql_uninstall != '') $error = $rep->bulk_sql($mod_sql_uninstall);
        if (!$error) $error = $rep->bulk_sql($mod_sql_install);
        if (!$error) {
            $sql = "UPDATE " . $cms_db['mod'] . " SET is_install='1', lastmodified='" . time() . "' WHERE idmod='$idmod'";
            $db->query($sql);
        }
        // Event
        fire_event('mod_install_sql', array('idmod' => $idmod, 'name' => $name));
    }
    if ($change) {
        // Status der 'code' Tabelle ändern
        $list = get_idtplconf_by_using_type($idmod, 'mod');
        $list = get_idcode_by_idtplconf($list);
        change_code_status($list, '1');
        unset($list);
    }
    // Rechte setzen
    if ($perm->have_perm('6', 'mod', $idmod)) {
        global $cms_gruppenids, $cms_gruppenrechte, $cms_gruppenrechtegeerbt, $cms_gruppenrechteueberschreiben;
        $perm->set_group_rights('mod', $idmod, $cms_gruppenids, $cms_gruppenrechte, $cms_gruppenrechtegeerbt, $cms_gruppenrechteueberschreiben, '',0x00000AFD);
    }
    return (!$error ? '0412' : $error);
}
function mod_delete($idmod) {
    global $db, $client, $cms_db, $cfg_cms, $rep, $perm;
    // Wird Modul noch verwendet?
    $sql = "SELECT idcontainer FROM " . $cms_db['container'] . " WHERE idmod='$idmod' LIMIT 0, 1";
    $affect = $db->query($sql);
    if ($db->num_rows() >= 1) return '0401';
    else {
        $sql = "SELECT idmod, source_id, is_install, repository_id FROM " . $cms_db['mod'] . "
				WHERE idclient >= 1 AND source_id='$idmod' AND is_install = '0' LIMIT 0, 1";
        $db->query($sql);
        if ($db->next_record()) {
            $installed = $db->f('idmod');
            $repositoryid = $db->f('repository_id');
        }
        $sql2 = "SELECT name, idclient, uninstall_sql, is_install FROM " . $cms_db['mod'] . "
			WHERE idmod='$idmod' LIMIT 0, 1";
        $db->query($sql2);
        if ($db->next_record()) {
            $sql_uninstall = $db->f('uninstall_sql');
            $name = make_string_dump($db->f('name'));
            if ($sql_uninstall != '' && $db->f('idclient') >= 1 && $db->f('is_install') == 1 && !$installed) {
                $error = $rep->bulk_sql($sql_uninstall);
                // Event
                fire_event('mod_delete_sql', array('idmod' => $idmod, 'name' => $name));
                $return = '0411';
            } elseif ($installed) {
                $sql = "UPDATE " . $cms_db['mod'] . " SET is_install='1', lastmodified='" . time() . "'
						WHERE idmod='$installed'";
                $db->query($sql);
                $sql = "UPDATE " . $cms_db['mod'] . " SET source_id='$installed'
						WHERE source_id='$idmod' AND idmod != '$installed'";
                $db->query($sql);
                $return = '0410';
            }
            $sql = "DELETE FROM " . $cms_db['mod'] . " WHERE idmod='$idmod'";
            $db->query($sql);
            // Rechte löschen
            $perm->delete_perms($idmod, 'mod');
            // Event
            fire_event('mod_delete', array('idmod' => $idmod, 'name' => $name));
        } else $return = '0413';
    }
    return $return;
}
function mod_download($idmod, $idclient) {
    global $rep;
    $modul = $rep->mod_data($idmod, $idclient);
    if (is_array($modul)) {
        $xmlstring = utf8_decode(trim($rep->mod_generate($modul)));
        // Event
        fire_event('mod_download', array('idmod' => $idmod, 'name' => $modul['name']));
        ob_end_clean();
		header('Content-Type: text/xml; name="' . $modul['name'] . '.cmsmod"');
        header('Content-Disposition: attachment; filename="' . $modul['name'] . '.cmsmod');
        header('Content-Description: Download Data');
        header('Content-Length: ' . strlen($xmlstring));
        header('Expires: 0');
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
        echo $xmlstring;
        exit;
    }
}
function mod_upload($idclient, $override = false, $module = array(NULL), $cmodule = array(NULL), $smodule = array(NULL), $rmodule = array(NULL)) {
    global $cfg_cms, $db, $cms_db, $rep, $perm, $auth, $sess, $s_upload;
    if (is_array($s_upload)) {
        $tmp_file = $s_upload;
        $returncode = $s_upload['returncode'];
        if ($sess->is_registered('s_upload')) $sess->unregister('s_upload');
    } else $tmp_file = lib_get_upload('mod_upload_file');
    // Es wurde keine Datei hochgeladen
    if (!is_array($tmp_file) || $tmp_file['error'] == '-1' || $tmp_file['error'] == '-2') {
			if ($tmp_file['code'] >= 1) {
					//if (lib_test_safemode()) return array('1801', false);
					if ($tmp_file['code'] == 1) return array('0708', false);
					if ($tmp_file['code'] == 2) return array('0709', false);
					if ($tmp_file['code'] == 3) return array('0710', false);
					if ($tmp_file['code'] == 4) return array('0711', false);
			} else return array('0404', false);
		}
		if (($filecontent = $rep->_file($tmp_file['copy'])) == '') {
        lib_delete_file($tmp_file['copy']);
        if (lib_test_safemode()) return '1801'; else return '0400';
    }
	$filecontent = utf8_encode($filecontent);
    //todo:remove this hack
    list($idmod, $xml_array) = $rep->mod_import($filecontent, $idclient, $override, $module, $cmodule, $smodule, $rmodule);
    if ($idmod == '-1' || $idmod == '-2' || $idmod == '-3') {
        $s_upload = $tmp_file;
        $s_upload['returncode'] = $idmod;
        $s_upload['modversion'] = $xml_array['version'];
        $s_upload['modname']    = $xml_array['name'];
        list($type,$rid,$uid) = explode(":",$xml_array['repository_id']);
        $s_upload['modrepid'] = $type.':'.$rid;
        $sess->register('s_upload');
        if ($idmod == '-3') return '0419'; else return '0417';
    } elseif ($idmod == '-4') {
        if ($sess->is_registered('s_update')) $sess->unregister('s_update');
        return '0420';
    } elseif ($idmod == '-5') {
        if ($sess->is_registered('s_update')) $sess->unregister('s_update');
        return '0421';
    } elseif ($idmod == '-6') {
        if ($sess->is_registered('s_update')) $sess->unregister('s_update');
        return '0422';
    } elseif ($idmod == '-7') {
		lib_delete_file($tmp_file['copy']);
        return '0403';
	} elseif (!$idmod || !is_array($xml_array)) {
        lib_delete_file($tmp_file['copy']);
        return '0400';
    }
    $modul = $rep->mod_data($idmod, $idclient);
    if ($modul['repository_id']) $repcount = $rep->mod_count("$idclient", $modul['repository_id'], true);
    if ($modul['install_sql'] != '' && $idclient >= 1 && $repcount < 1) {
        $error = $rep->bulk_sql($modul['install_sql']);
        fire_event('mod_install_sql', array('idmod' => $idmod, 'name' => $modul['name']));
        $return = '0409';
    } else $return = '0408';
    lib_delete_file($tmp_file['copy']);
    // Event
    fire_event('mod_upload', array('idmod' => $idmod, 'name' => $modul['name']));
    return $return;
}
// Repository && Local
function mod_update($idmod, $repid, $modname, $description, $modversion, $modcat, $input, $output, $sql_install, $sql_uninstall, $sql_update, $idclient, $force = false) {
    $stripe = !$force ? true : 2;
    global $cfg_cms, $db, $cms_db, $rep;
    mod_save($idmod, $modname, '-1', $description, $modversion, $modcat, $input, $output, $idclient, $repid, $sql_install, $sql_uninstall, $sql_update, false, '', false, $stripe);
    if ($idclient > 0 && $sql_update != '') {
        $error = $rep->bulk_sql($sql_update);
        // Event
        fire_event('mod_repository_update_sql', array('idmod' => $idmod, 'repid' => $repid, 'name' => $modname));
    }
    // Event
    fire_event('mod_repository_update', array('idmod' => $idmod, 'repid' => $repid, 'name' => $modname));
    return '0405';
}
function mod_lupdate($idmod, $repid, $modname, $description, $modversion, $modcat, $input, $output, $sql_install, $sql_uninstall, $sql_update, $idclient, $force = false) {
    $stripe = !$force ? true : 2;
    global $cfg_cms, $db, $cms_db, $rep;
    mod_save($idmod, $modname, '-2', $description, $modversion, $modcat, $input, $output, $idclient, $repid, $sql_install, $sql_uninstall, $sql_update, false, '', false, $stripe);
    if ($idclient > 0 && $sql_update != '') {
        $error = $rep->bulk_sql($sql_update);
        // Event
        fire_event('mod_local_update_sql', array('idmod' => $idmod, 'name' => $modname));
    }
    // Event
    fire_event('mod_local_update', array('idmod' => $idmod, 'name' => $modname));
    return '0415';
}
function mod_install($repid, $modname, $description, $modversion, $modcat, $input, $output, $sql_install, $sql_uninstall, $sql_update, $idclient, $force = false) {
    $stripe = !$force ? true : 2;
    mod_save('', $modname, '', $description, $modversion, $modcat, $input, $output, $idclient, $repid, $sql_install, $sql_uninstall, $sql_update, false, '', false, $stripe);
    if ($idclient > 0 && $sql_install != '') {
        $error = $rep->bulk_sql($sql_install);
        // Event
        fire_event('mod_repository_install_sql', array('repid' => $repid, 'name' => $modname));
    }
    // Event
    fire_event('mod_repository_import', array('repid' => $repid, 'name' => $modname));
    return '0406';
}
?>
