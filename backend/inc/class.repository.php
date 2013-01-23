<?PHP
// File: $Id: class.repository.php 36 2008-05-12 13:25:45Z mistral $
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
// + Revision: $Revision: 36 $
// +----------------------------------------------------------------------+
// + Description:
// +----------------------------------------------------------------------+
// + Changes: 
// +----------------------------------------------------------------------+
// + ToDo:
// +----------------------------------------------------------------------+

/**
 * 1. Benötigte Funktionen und Klassen includieren
 */


/**
* class repository
*
* this class provides some misc Module/Plugin methods.
*
*/
class repository {
    
    /**
    * repository::repository()
    *
    * constructor
    *
    */
    function repository() {
        global $val_ct, $perm, $cfg_cms, $db, $cms_db, $cms_lang, $auth, $client, $lang, $sess;
        // get global values
        $this->_db = $db = new DB_cms;
        $this->_perm     = $perm;
        $this->_auth 	 = $auth;
        $this->_sess 	 = $sess;
        $this->_db_var   = $cms_db;
        $this->_cms_var = $cfg_cms;
        $this->_lang_var = $cms_lang;
        // make local values
        $this->_version    = $this->_cms_var['version'];
        $this->_alive      = (bool) $this->_cms_var['repository_enabled'];
        $this->_client     = $client;
        $this->_lang       = $lang;
        $this->_referer    = 'Repository v1.2';
        $this->_plugin_dir = $this->_cms_var['cms_path'] . 'plugins/';
        // get global config
        $this->_init_settings('rep');
        global $cfg_rep;
        $this->_rep_var    = $cfg_rep;
        // make repository config global
        $this->_loopback     = (bool) $this->_rep_var['repository_loopback'];
        $this->_server       = $this->_rep_var['repository_server'];
        $this->_service_path = $this->_rep_var['repository_service_path'];
        $this->_xml_message  = $this->_rep_var['repository_service_message'];
        $this->_xml_list     = $this->_rep_var['repository_service_list'];
        $this->_lastupdate   = $this->_rep_var['repository_lastupdate'];
        $this->_lastping     = $this->_rep_var['repository_lastping'];
        $this->_updatetime   = $this->_rep_var['repository_updatetime'];
        $this->_pingtime     = $this->_rep_var['repository_pingtime'];
        $this->_auto_repair  = $this->_rep_var['repository_auto_repair_dependency'];
        $this->_dis_tests    = (bool) $this->_rep_var['repository_disable_auto_test'];
        $this->_safemode     = (bool) lib_test_safemode();
        // run repository update
        if ($this->online()) $this->life_update();
        $this->_rebuild_ids();
    }

    /**
    * public functions
    *
    */
    
    /**
    * repository::init_plugins()
    *
    * init all Plugins with default settings at startup
    *
    */
    function init_plugins() {
        $this->_call_plugin_dir();
        if (is_array($this->_plugin_list)) {
            foreach ($this->_plugin_list AS $plugin) {
                if ($plugin['base'] === true) {
                    $init = $this->_init_plugger($plugin['root_name']);
                    if ($init && $this->_call_plugin('','get', 'auto_settings')) $this->_call_plugin('init', 'settings');
                }
            }
        }
    }
    /**
    * repository::mod_updates()
    *
    * return a list of current Module updates
    *
    * @param int    $repid Repository Id
    * @param string $version Version
    * @param bool   $devtrue 'is Developer Update allowed'
    * @return array list of all updates
    */
    function mod_updates($repid, $version, $devtrue) {
        $order = 'mod';
        return $this->_update_list($order, $repid, $version, $devtrue);
    }
    /**
    * repository::plug_updates()
    *
    * return a list of current Plugin updates
    *
    * @param int    $repid   Repository Id
    * @param string $version Version
    * @param bool   $devtrue 'is Developer Update allowed'
    * @return array list of all updates
    */
    function plug_updates($repid, $version, $devtrue) {
        $order = 'plug';
        return $this->_update_list($order, $repid, $version, $devtrue);
    }
    /**
    * repository::mod_local_update()
    *
    * return a current local update for a Module
    *
    * @param int    $id         Module Id
    * @param string $version    Version
    * @param string $client     Client Id
    * @param bool   $repository 'is $id a Repository Id'
    */
    function mod_local_update($id, $version, $idclient = '0', $repository = false) {
        $order = 'mod';
        $module = $this->rep_local_list($idclient, $order, $id, $repository);
        if (is_array($module)) {
            foreach($module as $modul) {
                if ($this->_floatval($modul['version']) > $this->_floatval($version)) {
                    $return = $modul['idmod'];
                    $version = $modul['version'];
                }
            }
        }
        return $return;
    }
    /**
    * repository::enabled()
    *
    * return true is the Local Repository-Service available
    *
    */
    function enabled() {
        return $this->_alive;
    }
    /**
    * repository::online()
    *
    * return true is the Online Repository-Service available
    *
    */
    function online() {
        if (!$this->enabled()) return;
        $now = time();
        if ($now > ($this->_lastping + $this->_pingtime) && !$this->_static('pingnow')) {
            $this->_xml_message = $this->get_service('ping_online');
            $this->_update_rep_data('repository_service_message', $this->_xml_message);
            $this->_update_rep_data('repository_lastping', $now);
        }
        $xml = $this->xml_tree($this->_xml_message);
        $this->_online = !$this->error() ? ($xml['service']['#']['status'][0]['#'] == "online" ? true : false) : false;
        $this->_online_mods = $xml['service']['#']['module'][0]['#'];
        $this->_online_plugs = $xml['service']['#']['plugins'][0]['#'];
        return $this->_online;
    }
    /**
    * repository::life_update()
    *
    * execute a Life-Update from Repository-Service
    *
    */
    function life_update() {
        if (!$this->enabled()) return;
        $now = time();
        if ($now > ($this->_lastupdate + $this->_updatetime) && !$this->_static('updatenow')) {
            $this->_xml_list = $this->get_service('list_all_updates');
            $this->_update_rep_data('repository_service_list', $this->_xml_list);
            $this->_update_rep_data('repository_lastupdate', $now);
        }
        $xml = $this->xml_tree($this->_xml_list);
        $this->_online_mods = $xml['service']['#']['module'][0]['#'];
        $this->_online_plugs = $xml['service']['#']['plugins'][0]['#'];
        return true;
    }
    /**
    * repository::loopback()
    *
    * return true is the Local Repository-Loopback available
    *
    */
    function loopback() {
        return $this->_loopback;
    }
    /**
    * repository::mod_data()
    *
    * return the current Module Data
    *
    * @param int    $id         Module Id
    * @param string $idclient   Client Id
    * @param bool   $repository 'is $id a Repository Id'
    */
    function mod_data($id, $idclient = '-1', $repository = false) {
        $order = 'mod';
        $return = $this->_data($idclient, $order, $id, (($idclient == '-1' || $repository) ? true : false));
        return $return[0];
    }
    /**
    * repository::plug_data()
    *
    * return the current Plugin Data
    *
    * @param int    $id         Module Id
    * @param string $idclient   Client Id
    * @param bool   $repository 'is $id a Repository Id'
    */
    function plug_data($id, $idclient = '-1', $repository = false) {
        $order = 'plug';
        $return = $this->_data($idclient, $order, $id, (($idclient == '-1' || $repository) ? true : false));
        return $return[0];
    }
    /**
    * repository::plug_count()
    *
    * return the count of Plugin childs
    *
    * @param int    $idclient   Client Id
    * @param string $id         Module Id
    * @param bool   $repository 'is $id a Repository Id'
    */
    function plug_count($idclient, $id = '0', $repository = false) {
        $return = '0';
        $order = 'plug';
        $items = $this->rep_local_count($idclient, $order);
        //DebugMe! static $test = 0; $test++; if ($test ==1) print_r($items);
        list($type,$rid,$uid) = explode(":",$id);
        $repid = $type.':'.$rid;
        $return = (!$repository) ? (int) $items["$order"]["$id"]['childs']["$idclient"] : count($items['rep']["$repid"]);
        return $return;
    }
    /**
    * repository::mod_count()
    *
    * return the count of Module childs
    *
    * @param int    $idclient   Client Id
    * @param string $id         Module Id
    * @param bool   $repository 'is $id a Repository Id'
    */
    function mod_count($idclient, $id = '0', $repository = false) {
        $return = '0';
        $order = 'mod';
        $items = $this->rep_local_count($idclient, $order);
        //DebugMe! static $test = 0; $test++; if ($test ==1) print_r($items);
        list($type,$rid,$uid) = explode(":",$id);
        $repid = $type.':'.$rid;
        $return = (!$repository) ? (int) $items["$order"]["$id"]['childs']["$idclient"] : count($items['rep']["$repid"]);
        return $return;
    }
    /**
    * repository::mod_import_data()
    *
    * return the Module import Data
    *
    * @param int    $id         Module Id
    * @param string $idclient   Client Id
    * @param bool   $repository 'is $id a Repository Id'
    */
    function mod_import_data($id, $idclient = '-1', $repository = true) {
        return $this->mod_data($id, $idclient, (($idclient == '-1' && $repository) ? true : false));
    }
    /**
    * repository::plug_import_data()
    *
    * return the Pluginimport Data
    *
    * @param int    $id         Module Id
    * @param string $idclient   Client Id
    * @param bool   $repository 'is $id a Repository Id'
    */
    function plug_import_data($id, $idclient = '-1', $repository = true) {
        return $this->plug_data($id, $idclient, (($idclient == '-1' && $repository) ? true : false));
    }
    /**
    * repository::mod_update_data()
    *
    * return the Module update Data
    *
    * @param int    $id         Module Id
    * @param string $idclient   Client Id
    * @param bool   $repository 'is $id a Repository Id'
    */
    function mod_update_data($id, $idclient = '-1', $repository = true) {
        return $this->mod_data($id, $idclient, (($idclient == '-1' && $repository) ? true : false));
    }
    /**
    * repository::plug_update_data()
    *
    * return the Plugin update Data
    *
    * @param int    $id         Module Id
    * @param string $idclient   Client Id
    * @param bool   $repository 'is $id a Repository Id'
    */
    function plug_update_data($id, $idclient = '-1', $repository = true) {
        return $this->plug_data($id, $idclient, (($idclient == '-1' && $repository) ? true : false));
    }
    /**
    * repository::mod_import()
    *
    * install a Module in the $idclient Client and returns the new idmod
    *
    * @param string  $xmlstring Module Content
    * @param integer $idclient  Client Id
    */
    function mod_import($xmlstring = '', $idclient = '0', $override = false, $module = array(NULL), $cmodule = array(NULL), $smodule = array(NULL), $rmodule = array(NULL)) {
        if ($xmlstring == '' || !is_array(($xml_array = $this->cms_mod($xmlstring)))) return array('-7', false);
        if ($xml_array['repository_id'] == '') $xml_array['repository_id'] = $this->gen_new_mod($xml_array['name'], true);
        $items = $this->rep_local_count($idclient, 'mod');
        //Debug me! print_r($items);
        list($type,$rid,$uid) = explode(":",$xml_array['repository_id']);
        $repid = $type.':'.$rid;
        if ($idclient != '0' && count($items['rep']["$idclient"]["$repid"]) >= 1) {
            foreach($items['rep']["$idclient"]["$repid"] as $modul) {
                if ((lib_floatval($xml_array['version']) > lib_floatval($modul['version']))) {
                    if (!$override) return array('-1', $xml_array);
                    else $idmod['update'][] = $modul['idmod'];
                } elseif (lib_floatval($xml_array['version']) == lib_floatval($modul['version'])) {
                    if (!$override) return array('-3', $xml_array);
                    else $idmod['reinstall'][] = $modul['idmod'];                    
                } 
            }
        } elseif ($idclient == '0' && count($items['rep']["$repid"]) >= 1) {
            foreach($items['rep']["$repid"] as $modul) {
                if ((lib_floatval($xml_array['version']) > lib_floatval($modul['version'])) && $modul['idclient'] == '0') {
                    if (!$override) return array('-2', $xml_array);
                    else $idmod['update'][] = $modul['idmod'];
                } elseif (lib_floatval($xml_array['version']) == lib_floatval($modul['version']) && $modul['idclient'] == '0') {
                    if (!$override) return array('-3', $xml_array);
                    else $idmod['reinstall'][] = $modul['idmod'];                    
                } 
            }
        }
        if (!$override) {
            return array($this->_mod_import($xml_array, $idclient), $xml_array);
        } elseif (is_array($idmod) && (is_array($module) || is_array($rmodule))) {
            if ($smodule[$repid]) $this->_mod_import($xml_array, $idclient); 
            if (is_array($idmod['reinstall'])) {
                foreach($idmod['reinstall'] as $uid) {
                    if ($rmodule["$uid"] == true) {
                        mod_lupdate($uid, $xml_array['repository_id'], $xml_array['name'], $xml_array['description'], $xml_array['version'], $xml_array['cat'], $xml_array['input'], $xml_array['output'], $xml_array['sql_install'], $xml_array['sql_uninstall'], $xml_array['sql_update'], $idclient, true);
                        if ($cmodule["$uid"] == false) {mod_save_config($uid, $xml_array['config']); mod_set_config_status($uid);}
                    }
                } $return = '-5';
            }
            if (is_array($idmod['update'])) {
                foreach($idmod['update'] as $uid) {
                    if ($module["$uid"] == true) {
                        mod_lupdate($uid, $xml_array['repository_id'], $xml_array['name'], $xml_array['description'], $xml_array['version'], $xml_array['cat'], $xml_array['input'], $xml_array['output'], $xml_array['sql_install'], $xml_array['sql_uninstall'], $xml_array['sql_update'], $idclient, true);
                        if ($cmodule["$uid"] == false) {mod_save_config($uid, $xml_array['config']); mod_set_config_status($uid);}
                    }
                } $return = ($return == '-5') ? '-6' : '-4';
            } 
            return array($return, $xml_array);
        } elseif ($smodule[$repid] == true) {
            return array($this->_mod_import($xml_array, $idclient), $xml_array);
        }else return;
        return array($idmod, $xml_array);
    }
    /**
    * repository::bulk_mod_import()
    *
    * install a Bulk of Module in the $idclient Client and returns the new idplug
    *
    * @param string  $xmlarray Module Content
    * @param integer $idclient  Client Id
    */
    function bulk_mod_import($xml_array, $idclient = '0') {
        if (is_array($xml_array)) { 
        	foreach ($xml_array as $modul) {
            	if (is_array($modul['xml'])) {
            		foreach ($modul['xml'] AS $k=> $v) {
            			$modul['xml'][$k] = utf8_encode($v);
            		}
            		$_idmod[] = $this->_mod_import($modul['xml'], $idclient);
            	}
            	else if ($modul['content'] != '' && is_array(($modul['xml'] = $this->cms_mod($modul['content'])))) {
            		$_idmod[] = $this->_mod_import($modul['xml'], $idclient);
            	}
            		
        	}
        }
        return $_idmod;
    }
    /**
    * repository::plug_import()
    *
    * install a Plugin in the $idclient Client and returns the new idplug
    *
    * @param string  $xmlstring Plugin Content
    * @param integer $idclient  Client Id
    */
    function plug_import($xmlstring = '', $idclient = '0', $override = false) {
        if ($xmlstring == '' || !is_array(($xml_array = $this->cms_plug($xmlstring)))) return array('-4', false);
        if ($xml_array['repository_id'] == '') $xml_array['repository_id'] = $this->gen_new_plug($xml_array['name'], true);
        $items = $this->rep_local_count($idclient, 'plug');
        list($type,$rid,$uid) = explode(":",$xml_array['repository_id']);
        $repid = $type.':'.$rid;
        if ($idclient != '0' && count($items['rep']["$idclient"]["$repid"]) >= 1) {
            //todo: dieser modus ist deaktiviert!
            foreach($items['rep']["$idclient"]["$repid"] as $plugin) {
                if (lib_floatval($xml_array['version']) > lib_floatval($plugin['version'])) {
                    if (!$override) return array('-1', $xml_array);
                    $idplug = $plugin['idplug'];
                    break;
                } elseif (lib_floatval($xml_array['version']) == lib_floatval($plugin['version'])) {
                    if (!$override) return array('-3', $xml_array);
                    $idplug = $plugin['idplug'];
                    break;
                }
            }
        } elseif ($idclient == '0' && count($items['rep']["$repid"]) >= 1) {
            foreach($items['rep']["$repid"] as $plugin) {
                if (lib_floatval($xml_array['version']) > lib_floatval($plugin['version']) && $plugin['idclient'] == '0') {
                    if (!$override) return array('-2', $xml_array);
                    $idplug = $plugin['idplug'];
                    break;
                } elseif (lib_floatval($xml_array['version']) == lib_floatval($plugin['version']) && $plugin['idclient'] == '0') {
                    if (!$override) return array('-3', $xml_array);
                    $idplug = $plugin['idplug'];
                    break;
                }
            }
        }
        if (!$override) {
            return array($this->_plug_import($xml_array, $idclient), $xml_array);
        } elseif ($idplug) {
            $sql = "UPDATE
			   " . $this->_db_var['plug'] . "
			   SET
			   name='".$xml_array['name']."',
               description='".$xml_array['description']."',
               version ='".$xml_array['version']."',
               lastmodified='" . time() . "',
               index_file='".$xml_array['index_file']."'
               WHERE idplug=$idplug OR source_id=$idplug";
            $this->_db->query($sql);
        } else return;
        return array($idplug, $xml_array);
    }
    /**
    * repository::cms_mod()
    *
    * Parse and check a Module XML-Code
    *
    * @param string  $xmlstring Module Content
    */
    function cms_mod($xmlstring) {
        $trans_tbl = get_html_translation_table (HTML_ENTITIES);
        $trans_tbl = array_flip ($trans_tbl);
        $xml_array = array();
        
        //pcre.backtrack_limit is available scince PHP 5.2. In some cases big modules crashes
        //by upload, so we disable the backtrack_limit.
        @ini_set('pcre.backtrack_limit',-1);
        
        while (list ($key, $value) = each ($this->_xmlexport['mod'])) {
            // funktionalit?t von cmsmod v0.2 bleibt erhalten
            if(!preg_match("/(<" . $key . ">)(.*)(<\/" . $key . ">)/ims", $xmlstring, $match) && $key != 'config' && $key != 'repository_id' && $key != 'install_sql' && $key != 'uninstall_sql' && $key != 'update_sql') return '1603';
            $match['2'] = strtr ($match['2'], $trans_tbl);
            if ($key == 'created' && $match['2'] < 1) $match['2'] = time();
            if ($key == 'lastmodified') $match['2'] = time();
            if ($key == 'author') $match['2'] = $this->_auth->auth['uid'];
            $match['2'] = make_string_dump ($match['2']);
            $match['2'] = $value == 'char' ? (string) $match['2'] : (int) $match['2'];
            $xml_array["$key"] = $match['2'];
            unset($match);
        }
        if ($xml_array['repository_id'] == '' ) $xml_array['repository_id'] = $this->gen_new_mod($xml_array['name'], true);
        return $xml_array;
    }
    /**
    * repository::cms_plug()
    *
    * Parse and check a Plugin XML-Code
    *
    * @param string  $xmlstring Plugin Content
    */
    function cms_plug($xmlstring) {
        $trans_tbl = get_html_translation_table (HTML_ENTITIES);
        $trans_tbl = array_flip ($trans_tbl);
        $xml_array = array();
        while (list ($key, $value) = each ($this->_xmlexport['plug'])) {
            // funktionalit?t von cmsplug v0.2 bleibt erhalten
            if(!preg_match("/(<" . $key . ">)(.*)(<\/" . $key . ">)/ims", $xmlstring, $match) && $key != 'config' && $key != 'repository_id' && $key != 'root_name' && $key != 'index_file') return '1603';
            $match['2'] = strtr ($match['2'], $trans_tbl);
            if ($key == 'created' && $match['2'] < 1) $match['2'] = time();
            if ($key == 'lastmodified') $match['2'] = time();
            if ($key == 'author') $match['2'] = $this->_auth->auth['uid'];
            $match['2'] = make_string_dump ($match['2']);
            $match['2'] = $value == 'char' ? (string) $match['2'] : (int) $match['2'];
            $xml_array["$key"] = $match['2'];
            unset($match);
        }
        if ($xml_array['repository_id'] == '' ) $xml_array['repository_id'] = $this->gen_new_plug($xml_array['name'], true);
        return $xml_array;
    }
    /**
    * repository::mod_generate()
    *
    * generator to create a Module XML Code
    *
    * @param array  $modul Modul Content
    */
    function mod_generate($modul) {
        if (is_array($modul)) {
            if ($modul['repository_id'] == '' ) $modul['repository_id'] = $this->gen_new_mod($modul['name'], true);
            $xmlstring = "<?xml version='1.0' encoding='" . $this->_xmlexport['codepage'] . "'?>\r\n"; //<?
            $xmlstring .= "<modul version='0.4' date='" . time() . "'>\r\n";
            while(list($key, $value) = each($this->_xmlexport['mod'])) $xmlstring .= "   <$key>" . htmlspecialchars($modul["$key"], ENT_COMPAT, 'UTF-8') . "</$key>\r\n";
            $xmlstring .= "</modul>";
        } else return false;
        return $xmlstring;
    }
    /**
    * repository::plug_generate()
    *
    * generator to create a Plugin XML Code
    *
    * @param array  $plugin Plugin Content
    */
    function plug_generate($plugin) {
        if (is_array($plugin)) {
            if ($plugin['repository_id'] == '' ) $plugin['repository_id'] = $this->gen_new_plug($plugin['name'], true);
            $xmlstring = "<?xml version='1.0' encoding='" . $this->_xmlexport['codepage'] . "'?>\r\n"; //<?
            $xmlstring .= "<plugin version='0.3' date='" . time() . "'>\r\n";
            while(list($key, $value) = each($this->_xmlexport['plug'])) $xmlstring .= "   <$key>" . htmlspecialchars($plugin["$key"], ENT_COMPAT, 'UTF-8') . "</$key>\r\n";
            $xmlstring .= "</plugin>";
        } else return false;
        return $xmlstring;
    }
    /**
    * repository::rep_local_count()
    *
    * returns a array with all the module/plugins
    * separated by client/repository/all
    *
    */
    function rep_local_count($idclient, $order) {
        $identc = ( ($idclient != 'all') ? ( ($idclient == '0') ? "AND citem.idclient = '" . $this->_client . "'" : "AND citem.idclient = '" . $idclient . "'" ) : '' ); // Child
        $idents = ( ($idclient != 'all') ? ( ($idclient == '0') ? "AND sitem.idclient = '" . $this->_client . "'" : "AND sitem.idclient = '" . $idclient . "'" ) : '' ); // Parent
        $identw = ( ($idclient != 'all') ? ( ($idclient == '0') ? $this->_client : $idclient ) : 'all' ); // Child
        if (!($identw == $this->_static("items_$order_$identw"))) {
            $sql = "SELECT
                    pitem.id" . $order . ",
                    pitem.name,
                    pitem.version,
                    pitem.source_id,
                    pitem.idclient,
                    pitem.repository_id,
                    count( citem.id" . $order . " ) AS ccount,
                    count( citemo.id" . $order . " ) AS ccounto,
                    CASE WHEN count( sitem.id" . $order . " )  > 0 THEN 1 ELSE 0 END AS scount,
                    CASE WHEN count( sitemo.id" . $order . " ) > 0 THEN 1 ELSE 0 END AS scounto,
                    CASE WHEN pitem.verbose IS NOT NULL THEN pitem.verbose ELSE pitem.name END AS verbose
                  FROM " . $this->_db_var["$order"] . " AS pitem
                    LEFT JOIN " . $this->_db_var["$order"] . " AS citem  ON ( citem.source_id = pitem.id" . $order . " " . $identc . " )
                    LEFT JOIN " . $this->_db_var["$order"] . " AS citemo ON ( citemo.source_id = pitem.id" . $order . " AND citemo.idclient = '0' )
                    LEFT JOIN " . $this->_db_var["$order"] . " AS sitem  ON ( sitem.id" . $order . " = pitem.source_id " . $idents . " )
                    LEFT JOIN " . $this->_db_var["$order"] . " AS sitemo ON ( sitemo.id" . $order . " = pitem.source_id AND sitemo.idclient = '0' )
                  GROUP BY
                    pitem.id" . $order;
            $result_arr = $this->_fetch_query($sql);
            if (is_array($result_arr)) foreach($result_arr as $entry) {
                if ($entry['source_id'] >= 1 && $entry['scount'] == 0 && $entry['scounto'] == 0) if (!$this->_repair_dependency($entry, $order)) return $this->_set_error('1', '1360');
                $this->_items["items_$order_$identw"]["$order"][$entry['id' . $order]] = array('id' . $order => $entry['id' . $order],
                    'name' => $entry['name'],
                    'version' => $entry['version'],
                    'source_id' => $entry['source_id'],
                    'idclient' => $entry['idclient'],
                    'repository_id' => $entry['repository_id'],
                    'verbose' => $entry['verbose'],
                    'childs' => array('0' => $entry['ccounto'],
                    "$identw" => $entry['ccount']));
                if ($entry['repository_id'] != '') {
                    list($type,$id,$uid) = explode(":",$entry['repository_id']);
                    $this->_items["items_$order_$identw"]["rep"]["$type"]["$id"][] = $this->_items["items_$order_$identw"]["$order"][$entry['id' . $order]];
                    $this->_items["items_$order_$identw"]["rep"]["$type:$id"][] = $this->_items["items_$order_$identw"]["$order"][$entry['id' . $order]];
                    $this->_items["items_$order_$identw"]["rep"][$entry['idclient']]["$type:$id"][] = $this->_items["items_$order_$identw"]["$order"][$entry['id' . $order]];
                }
                $this->_items["items_$order_$identw"]["$order"]["client"][$entry['idclient']][] = $this->_items["items_$order_$identw"]["$order"][$entry['id' . $order]];
                $this->_items["items_$order_$identw"]["client"][$entry['idclient']][] = $this->_items["items_$order_$identw"]["$order"][$entry['id' . $order]];
            }
        }
        return $this->_items["items_$order_$identw"];
    }
    /**
    * repository::rep_local_list()
    *
    * returns a array with a list of local module/plugins
    *
    * @param string $idclient
    * @param string $order
    * @param string $id
    * @param [type] $repository
    */
    function rep_local_list($idclient = '0', $order = 'plug', $id = '', $repository = false) {
        $listrepid = ($id != '' && $repository) ? "AND repository_id='" . $id . "'" : (($id != '') ? "AND id" . $order . "='" . $id . "'" : '');
        $ident = ($idclient != 'all') ? "idclient='" . $idclient . "'" : '1';
        $io = ($order == 'mod') ? ", input, output" : '';
        $sql = "SELECT id$order, name, version, lastmodified, cat, description, repository_id, deletable, config , idclient, is_install , source_id, verbose, checked $io FROM " . $this->_db_var["$order"] . " WHERE $ident $listrepid ORDER BY cat, name, id$order";
        return $this->_fetch_query($sql);
    }
    /**
    * repository::rep_server_list()
    *
    * returns a array with a list of repository module/plugins
    *
    * @param string $repid
    */
    function rep_server_list($order, $repid = '') {
        // fake
        // general emulate online repository
        return $this->rep_local_list('0', $order, $repid, true);
    }
    /**
    * repository::rep_local_data()
    *
    * returns a array with the data from selectetd local module/plugin
    *
    * @param string $idclient
    * @param string $id
    * @param [type] $repository
    */
    function rep_local_data($idclient = '0', $order, $id = '', $repository = false) {
        $listrepid = ($id != '' && $repository) ? "AND repository_id LIKE '" . $id . "%'" : (($id != '') ? "AND id" . $order . "='" . $id . "'" : '');
        $ident = ($idclient != 'all') ? "idclient='" . $idclient . "'" : '1';
        $sql = "SELECT * FROM " . $this->_db_var["$order"] . " WHERE $ident $listrepid ORDER BY cat, name, id$order";
        return $this->_fetch_query($sql);
    }
    /**
    * repository::rep_server_data()
    *
    * returns a array with the data from selectetd repository module/plugin
    *
    * @param string $repid
    */
    function rep_server_data($order, $repid = '') {
        // fake
        // general emulate online repository
        return $this->rep_local_data('0', $order, $repid);
    }
    /**
    * repository::mod_list()
    *
    * returns a array with the current module list
    *
    */
    function mod_list($idclient) {
        $order = 'mod';
        return $this->_list($idclient, $order);
    }
    /**
    * repository::plug_list()
    *
    * returns a array with the current plugin list
    *
    */
    function plug_list($idclient) {
        $order = 'plug';
        return $this->_list($idclient, $order);
    }
    /**
    * repository::get_content_file()
    *
    * returns the content from a selected file
    *
    * @param string $dir
    */
    function get_content_file($file, $dir = '') {
        return $this->_file($dir . $file);
    }
    /**
    * repository::get_xml_string()
    *
    * returns the last xml string
    *
    */
    function get_xml_string() {
        return $this->_xml_string;
    }
    /**
    * repository::get_xml_message()
    *
    * { Description }
    *
    */
    function get_xml_message() {
        return $this->_xml_message;
    }
    /**
    * repository::plug_get_meta()
    *
    * { Description }
    *
    */
    function plug_get_meta($dir) {
        $return = array();
        foreach ($this->_meta_arr as $key => $value) $return[$key] = $this->get_content_file($value, $dir . 'meta/');
        return $return;
    }
    /**
    * repository::error()
    *
    * { Description }
    *
    * @param [type] $order
    */
    function error($order = false) {
        $return = ($order == true && $this->_error == true) ? $this->message() : $this->_error;
        return $return;
    }
    /**
    * repository::message()
    *
    * { Description }
    *
    */
    function message() {
        return $this->_msg;
    }
    /**
    * repository::bulk_sql()
    *
    * { Description }
    *
    */
    function bulk_sql($query) {
        if ($query == '') return;
        $qenc = $this->encode_sql($query);
        $qarr = $this->_splitsqlfile($qenc);
        if(is_array($qarr)) foreach ($qarr as $num => $qrow) {
            // todo:if (! preg_match('@DROP[[:space:]]+(IF EXISTS[[:space:]]+)?DATABASE @i ', $sql_query)) // check for drop db
            // todo: check function _inneraddslashes for errors
            //$qval = @preg_replace_callback('/\,+\s\'{1,1}(.*?)\'{1,1}\,+/sim', array(&$this, '_inneraddslashes'), $qrow);
            $qval = $qrow;
            $error[$num] = $this->_db->query(trim($qval));
            if (!$error[$num]) $return[$num] = $qval;
        }
        return $return;
    }
    /**
    * repository::decode_sql()
    *
    * { Description }
    *
    */
    function decode_sql($query) {
        if ($query == '') return;
        //$query = $this->encode_sql($query);
        $newline = strpos ($query, '<?php') !== false ? '' : "#encode '{table_prefix}' with your Settings\r\n";
        $query = str_replace($newline, '', $query);
        $newquery = $this->_dec_sql($query);
        return trim($newline . $newquery);
    }
    /**
    * repository::encode_sql()
    *
    * { Description }
    *
    */
    function encode_sql($query) {
        if ($query == '') return;
        $stripeline = $newline = strpos ($query, '<?php') !== false ? '' : "#encode '{table_prefix}' with your Settings";
        $stripequery = str_replace($stripeline, '', $query);
        $newquery = $this->_enc_sql($stripequery);
        return trim($newquery);
    }
    /**
    * repository::xml_tree()
    *
    * { Description }
    *
    * @param integer $white
    */
    function xml_tree($data, $white = 1) {
        $return = lib_xml_tree($data, $strict = false, $white);
        if ($return == '-1') return $this->_set_error('1', '1504');
        return $return;
    }
    /**
    * repository::get_service()
    *
    * { Description }
    *
    * @param string $get
    */
    function get_service($get = 'ping_online') {
        if (!$this->enabled()) return;
        $trans_tbl = get_html_translation_table (HTML_ENTITIES);
        $trans_tbl = array_flip ($trans_tbl);
        $loose = "<service name=\"$get\">\n<error errno=\"1500\">\n" . $this->_lang_var['err_1500'] . "\n</error>\n</service>\n";
        $this->_service = $get;
        $data = $this->_conn_server($get);
        if (!preg_match("/<repository>(.*)<\/repository>/ims", $data, $match)) $this->_xml_string = $loose;
        else $this->_xml_string = trim(strtr ($match['1'], $trans_tbl));
        $xml = $this->xml_tree($this->_xml_string);
        if ($xml['service']['@']['name'] != $this->_service) {
            if (!is_array($xml['service']['#']['error'])) {
                $this->_xml_string = $loose;
                $xml = $this->xml_tree($this->_xml_string);
            }
            $this->_set_error('1', $xml['service']['#']['error']['0']['#']['errno']);
            return false;
        } else return $this->_xml_string;
    }
    /**
    * repository::plug_execute()
    *
    * { Description }
    *
    * @param string $pre
    * @param string $string
    * @param string $content
    */
    function plug_execute($id, $order, $pre = '', $string = '', $content = '') {
        if ($id < 1 || !$this->_plug_init($id)) return;
        return $this->_call_plugin($pre, $order, $string, $content);
    }
    /**
    * repository::mod_execute()
    *
    * { Description }
    *
    * @param string $pre
    * @param string $string
    */
    function mod_execute($id, $order, $pre = '', $string = '') {
        // fake
    }
    /**
    * repository::get_new_plugin_list()
    *
    * return a list of not used plugins
    *
    */
    function get_new_plugin_list() {
        $new_list = array();
        $this->_call_plugin_dir();
        if (is_array($this->_plugin_list)) foreach ($this->_plugin_list AS $plugin) {
            if (!$plugin['base']) $new_list[] = $plugin;
        }
        return $new_list;
    }
    /**
    * repository::get_plugin_list()
    *
    * return a list of used plugins
    *
    */
    function get_plugin_list() {
        $base_list = array();
        $this->_call_plugin_base();
        foreach ($this->_plugin_base AS $plugin) $base_list[] = $plugin;
        return $base_list;
    }
    /**
    * repository::run_php()
    *
    * execute php string as is
    *
    */
    function run_php($code) {
        global $db, $auth, $cms_db, $cfg_cms, $rep, $mod, $perm, $client, $lang;
		return eval('?>' . $code);
    }
    /**
    * repository::mod_test()
    *
    * test module in a box
    *
    */
    function mod_test ($code, $id) {
        global $db, $auth, $cms_db, $cfg_cms, $rep, $mod, $perm, $client, $lang, $con_tree, $con_side;
        static $_id;
        $_strict = explode(',',($_dis = @ini_get('disable_functions')));
        if(in_array('ini_set',$_strict) || !isset($_dis) || $this->_dis_tests) return false;
        $_id = $id.'_in' == $_id ? ($id .= '_out') : ($id .= '_in'); 
        // spezial for Navigationsmodule
        $idcatside = 1;
        // include the Mipforms
        include_once('inc/fnc.mipforms.php');
        $mod_test_var = 0;
        // spezial for 'Druckmodul'
        $list['id'][] = 1;
        // add constant __cmsMODTEST
        $code .= '<?php define(\'__cmsMODTEST\', true); ?>';
        // replaces        
        $code = str_replace('<CMSPHP>', '', $code);
        $code = str_replace('</CMSPHP>', '', $code);
        //todo: 2remove
        $code = str_replace('<DEDIPHP>', '', $code);
        $code = str_replace('</DEDIPHP>', '', $code);
		// replaces		
        $code = str_replace('MOD_VALUE', '$MOD_VALUE', $code);
        $code = str_replace('MOD_VAR', '$MOD_VAR', $code);
        $code = preg_replace ('/(<(cms|dedi):[\/\!]*?[^<>]*?>)/si', '""', $code);
        // Init the Box
        $code = "function mod_test_" . $id . " () {" . $code;
        $code .= "\n}\n";
        $code .= '$mod_test_var = $id;';
        // Ini Set
        @ini_set("error_prepend_string", "<mod_test_error>");
        @ini_set("error_append_string", "</mod_test_error>");
        // Debug Me! print_r($code);
        // Run the code in a Box
        ob_start();
        eval(' ?>' . $code);
        $output = ob_get_contents();
        // Later Parse! call_user_func("function mod_test_" . $id,'');
        ob_end_clean();
        // Ini Restore
        @ini_restore("error_prepend_string");
        @ini_restore("error_append_string");
        // Strip <mod_test_error>
        $start = strpos($output, "<mod_test_error>");
        $end = strpos($output, "</mod_test_error>");
        if ($start !== false) {
            $start = strpos($output, "eval()");
            $error = substr($output, $start, $end - $start);
            preg_match ('/<b>(\d+)<\/b>/i', $error, $match);
            $error_line = (int) $match['1'] - 1;
        }
        if ($mod_test_var != $id) {
            return $error_line;
        } else {
            return false;
        }
    }
    /**
    * repository::gen_new_mod()
    *
    * generate new module repository Id´s
    *
    */
    function gen_new_mod($name, $force = false) {
        $id = $this->_gen_repid($name, 'mod', $force);
        return $id;
    }
    /**
    * repository::gen_new_plug()
    *
    * generate new plugin repository Id´s
    *
    */
    function gen_new_plug($name, $force = false) {
        $id = $this->_gen_repid($name, 'plug', $force);
        return $id;
    }
    // privat vars
    /**
    * repository::$_alive
    *
    * { Description }
    *
    */
    var $_alive = false;
    /**
    * repository::$_online
    *
    * { Description }
    *
    */
    var $_online = false;
    /**
    * repository::$_error
    *
    * { Description }
    *
    */
    var $_error = false;
    /**
    * repository::$_halt
    *
    * { Description }
    *
    */
    var $_halt = false;
    /**
    * repository::$_client
    *
    * { Description }
    *
    */
    var $_client = '';
    /**
    * repository::$_items
    *
    * { Description }
    *
    */
    var $_items = array(
        'mod' => array(),
        'plug' => array(),
        'rep' => array());
    /**
    * repository::$_lang
    *
    * { Description }
    *
    */
    var $_lang = '';
    /**
    * repository::$_perm
    *
    * { Description }
    *
    */
    var $_perm = '';
    /**
    * repository::$_auth
    *
    * { Description }
    *
    */
    var $_auth = '';
    /**
    * repository::$_sess
    *
    * { Description }
    *
    */
    var $_sess = '';
    /**
    * repository::$_msg
    *
    * { Description }
    *
    */
    var $_msg = '';
    /**
    * repository::$_db
    *
    * { Description }
    *
    */
    var $_db = '';
    /**
    * repository::$_db_var
    *
    * { Description }
    *
    */
    var $_db_var = '';
    /**
    * repository::$_rep_var
    *
    * { Description }
    *
    */
    var $_rep_var = '';
    /**
    * repository::$_cms_var
    *
    * { Description }
    *
    */
    var $_cms_var = '';
    /**
    * repository::$_lang_var
    *
    * { Description }
    *
    */
    var $_lang_var = '';
    /**
    * repository::$_service
    *
    * { Description }
    *
    */
    var $_service = '';
    /**
    * repository::$_referer
    *
    * { Description }
    *
    */
    var $_referer = '';
    /**
    * repository::$_plugger
    *
    * { Description }
    *
    */
    var $_plugger = '';
    /**
    * repository::$_lastupdate
    *
    * { Description }
    *
    */
    var $_lastupdate = '';
    /**
    * repository::$_lastping
    *
    * { Description }
    *
    */
    var $_lastping = '';
    /**
    * repository::$_updatetime
    *
    * { Description }
    *
    */
    var $_updatetime = '';
    /**
    * repository::$_pingtime
    *
    * { Description }
    *
    */
    var $_pingtime = '';
    /**
    * repository::$_sfemode
    *
    * { Description }
    *
    */
    var $_sfemode = false;
    /**
    * repository::$_server
    *
    * { Description }
    *
    */
    var $_server = '';
    /**
    * repository::$_version
    *
    * { Description }
    *
    */
    var $_version = '';
    /**
    * repository::$_plugin
    *
    * { Description }
    *
    */
    var $_plugin = '';
    /**
    * repository::$_modul
    *
    * { Description }
    *
    */
    var $_modul = '';
    /**
    * repository::$_service_path
    *
    * { Description }
    *
    */
    var $_service_path = '';
    /**
    * repository::$_xml_string
    *
    * { Description }
    *
    */
    var $_xml_string = '';
    /**
    * repository::$_xml_message
    *
    * { Description }
    *
    */
    var $_xml_message = '';
    /**
    * repository::$_xml_list
    *
    * { Description }
    *
    */
    var $_xml_list = '';
    /**
    * repository::$_plugin_dir
    *
    * { Description }
    *
    */
    var $_plugin_dir = '';
    /**
    * repository::$_plugin_list
    *
    * { Description }
    *
    */
    var $_plugin_list = array();
    /**
    * repository::$_plugin_base
    *
    * { Description }
    *
    */
    var $_plugin_base = array();
    /**
    * repository::$_meta_arr
    *
    * { Description }
    *
    */
    var $_meta_arr = array(
	    'install_sql' => 'install.sql',
        'uninstall_sql' => 'uninstall.sql',
        'update_sql' => 'update.sql',
        'config_sql' => 'config.sql');
    /**
    * repository::$_xmlexport
    *
    * { Description }
    *
    */
    var $_xmlexport = array(
	    'codepage' => 'ISO-8859-1',
        'mod' => array(
            'name' => 'char',
            'cat' => 'char',
            'version' => 'char',
            'author' => 'char',
            'created' => 'int',
            'lastmodified' => 'int',
            'deletable' => 'int',
            'description' => 'char',
            'input' => 'char',
            'output' => 'char',
            'config' => 'char',
            'repository_id' => 'char',
            'install_sql' => 'char',
            'uninstall_sql' => 'char',
            'update_sql' => 'char'),
        'plug' => array(
            'name' => 'char',
            'cat' => 'char',
            'version' => 'char',
            'author' => 'char',
            'created' => 'int',
            'lastmodified' => 'int',
            'deletable' => 'int',
            'description' => 'char',
            'config' => 'char',
            'repository_id' => 'char',
            'root_name' => 'char',
            'index_file' => 'char'));
    /**
    * repository::_halt()
    *
    * { Description }
    *
    */
    function _halt() {
        if ($this->error()) $this->_halt = true;
        return $this->_halt;
    }
    /**
    * repository::_list()
    *
    * { Description }
    *
    */
    function _list($idclient, $order) {
        switch ($idclient) {
            case '-1':
                $return = $this->rep_server_list($order);
                break;
            default:
                $return = $this->rep_local_list($idclient, $order);
                break;
        }
        return $return;
    }
    /**
    * repository::_data()
    *
    * { Description }
    *
    * @param [type] $repository
    */
    function _data($idclient, $order, $id, $repository = false) {
        switch ($idclient) {
            case '-1':
                $return = $this->rep_server_data($order, $id);
                break;
            default:
                $return = $this->rep_local_data($idclient, $order, $id, $repository);
                break;
        }
        return $return;
    }
    /**
    * repository::_file()
    *
    * { Description }
    *
    */
    function _file($file) {
        $return = lib_read_file($file);
        if ($file == '-1') return $this->_set_error('0', '1501');
        elseif ($file == '-2') return $this->_set_error('0', '1506');
        else return $return;
    }
    /**
    * repository::_write()
    *
    * { Description }
    *
    * @param integer $mode
    */
    function _write($file, $content, $mode = 2) {
        $return = lib_write_file($file, $content, $mode);
        switch ($return) {
            case '-1':
            case '-2':
            case '-4':
                return $this->_set_error('0', '1501');
                break;
            case '-3':
            case '-5':
            case '-6':
                return $this->_set_error('0', '1505');
                break;
        }
        return true;
    }
    /**
    * repository::_delete()
    *
    * { Description }
    *
    */
    function _delete($file) {
        $return = lib_delete_file($file);
        if ($return == '-1') $this->_set_error('0', '1510');
        else return;
    }
    /**
    * repository::_remove()
    *
    * { Description }
    *
    */
    function _remove($dir) {
        return lib_remove_dir($dir);
    }
    /**
    * repository::_set_error()
    *
    * { Description }
    *
    * @param [type] $error
    * @param string $msg
    */
    function _set_error($error = true, $msg = '1500') {
        if ($this->_error != true && $error == true) $this->_error = $error;
        $this->_set_msg($msg);
    }
    /**
    * repository::_set_msg()
    *
    * { Description }
    *
    * @param string $msg
    */
    function _set_msg($msg = '') {
        $this->_msg = $msg;
    }
    /**
    * repository::_enc_sql()
    *
    * { Description }
    *
    */
    function _enc_sql($query) {
        return lib_enc_sql($query);
    }
    /**
    * repository::_dec_sql()
    *
    * { Description }
    *
    */
    function _dec_sql($query) {
        return lib_dec_sql($query);
    }
    /**
    * repository::_update_rep_data()
    *
    * { Description }
    *
    * @param integer $client
    */
    function _update_rep_data($key, $value, $client = 0) {
        $sql = "UPDATE " . $this->_db_var['values'] . " SET value = '$value' WHERE key1 = '$key' AND idclient= '$client'";
        return $this->_db->query($sql);
    }
    /**
    * repository::_update_list()
    *
    * { Description }
    *
    * @param [type] $devtrue
    */
    function _update_list($order, $repid, $version, $devtrue = false) {
        $sorder = array('mod' => 'module', 'plug' => 'plugins');
        $xml = $this->xml_tree($this->_xml_list);
        $$order = $xml['service']['#'][$sorder[$order]]['0']['#']['cms' . $order];
        if (is_array($$order)) foreach ($$order as $key => $val) {
            if ($val['@']['repository_id'] == $repid && ($this->_floatval($val['#']['references']['0']['#']['data']['0']['@']['version']) > $this->_floatval($version))) {
                if (isset($val['#']['references']['0']['#']['data']['1']['@']['dev'])) $return = $val['#']['references']['0']['#']['data']['1']['@']['dev'] == $devtrue ? $return = true : $return = false;
                else $return = true;
            }
        }
        return $return;
    }
    /**
    * repository::_plug_init()
    *
    * { Description }
    *
    */
    function _plug_init($id) {
        if(!($this->_plugin = $this->_static("plugin$id")) && $id >= 1) {
            $sql = "SELECT * FROM " . $this->_db_var['plug'] . " WHERE idplug = '$id' LIMIT 1";
            $result = $this->_fetch_query($sql);
            if (!is_array($result[0])) return $this->_set_error('1', '1508');
            $this->_plugin = $result[0];
            $this->_static("plugin$id", $this->_plugin);
            return $this->_init_plugger($this->_plugin['root_name'], true);
        } elseif (!$id) return;
        else return true;
    }
    /**
    * repository::_init_plugger()
    *
    * { Description }
    *
    * @param [type] $call_files
    */
    function _init_plugger($name, $call_files = false) {
        unset($this->_plugger);
        $class = $name . '_meta';
        $file = $this->_plugin_dir . $name . '/' . $class . '.php';
        if (lib_check_file($file, true)) {
            include_once('inc/class.plugin_meta.php');
			include_once(trim($file));
            $this->_plugger = new $class($call_files);
            if ($this->_plugger->get('root_name') != $name) $this->_plugger->set('root_name', $name);
            return true;
        } elseif (($_class = lib_read_file($file))) {
			if ($_class == '-1' || $_class == '-2') return $this->_set_error('0', '1622');
			include_once('inc/class.plugin_meta.php');
			eval("?>" . $_class);
			$this->_plugger = new $class($call_files);
            if ($this->_plugger->get('root_name') != $name) $this->_plugger->set('root_name', $name);
            return true;
		} else $this->_set_error('0', '1622');
    }
    /**
    * repository::_fetch_query()
    *
    * { Description }
    *
    * @param string $mode
    */
    function _fetch_query($sql, $mode = 'DB_FETCH_ASSOC') {
        $affect = $this->_db->query($sql);
        if (!$affect || $affect < 1) return $this->_set_error('1', '1508');
        $oldmode = $this->_db->get_fetch_mode();
        $this->_db->set_fetch_mode($mode);
        $return = array();
        while ($this->_db->next_record()) array_push($return, $this->_db->this_record());
        $this->_db->set_fetch_mode($oldmode);
        return $return;
    }
    /**
    * repository::_call_plugin()
    *
    * { Description }
    *
    * @param string $string
    * @param string $content
    */
    function _call_plugin($pre, $order, $string = '', $content = '') {
        $_function = $pre != '' ? $pre . '_' . $order : $order;
        //DebugMe! echo "$_function($string, $content)";
        //echo '<br /><br /><br /><br /><br />$_function($string, $content)<br />';
        //echo "$_function($string, $content)";
        $return = ($content == '') ? $this->_plugger->$_function($string) : $this->_plugger->$_function($string, $content);
        return $return;
    }
    /**
    * repository::_call_plugin_dir()
    *
    * { Description }
    *
    */
    function _call_plugin_dir() {
        if(!$this->_static("call_plugin_dir")) {
            unset($this->_plugin_list);
            $this->_call_plugin_base();
            if ($_dh = @opendir($this->_plugin_dir)) {
                while (($dir = readdir($_dh)) !== false) {
                    if ($dir != '.' && $dir != '..' && strtoupper($dir) != 'CVS' && is_dir($this->_plugin_dir . $dir)) {
                        $base = in_array($dir, $this->_plugin_base) ? true : false;
                        $this->_plugin_list[] = array('root_name' => $dir, 'base' => $base, 'meta' => is_readable($this->_plugin_dir . $dir . '/' . $dir . '_meta.php'), 'xml' => is_readable($this->_plugin_dir . $dir . '/' . $dir . '.cmsplug'));
                    }
                }
                closedir($_dh);
            }
        }
    }
    /**
    * repository::_call_plugin_base()
    *
    * { Description }
    *
    */
    function _call_plugin_base() {
        if(!$this->_static("call_plugin_base")) {
            unset($this->_plugin_base);
            $list_base = array();
            $sql = "SELECT DISTINCT root_name FROM " . $this->_db_var['plug'];
            $result = $this->_fetch_query($sql);
            if (is_array($result)) {
                foreach ($result AS $plugin) $list_base[] = $plugin['root_name'];
            }
            $this->_plugin_base = $list_base;
        }
    }
    /**
    * repository::_init_settings()
    *
    * { Description }
    *
    * @param string $name
    */
    function _init_settings($name = 'rep') {
        return lib_init_settings($name);
    }
    /**
    * repository::_floatval()
    *
    * { Description }
    *
    */
    function _floatval($strValue) {
        return lib_floatval($strValue);
    }
    /**
    * repository::_static()
    *
    * { Description }
    *
    * @param [type] $val
    */
    function _static($key, $val = true) {
        return lib_static($key, $val);
    }
    /**
    * repository::_read_settings()
    *
    * { Description }
    *
    * @param string $name
    * @param [type] $process_sections
    */
    function _read_settings($source, $name = 'GLOBALS', $process_sections = false) {
        return lib_read_settings($source, $name, $process_sections);
    }
    /**
    * repository::_inneraddslashes()
    *
    * { Description }
    *
    */
    function _inneraddslashes($matches) {
        return preg_replace(',/' . $matches[1] . '/,', addslashes($matches[1]), $matches[0]);
    }
    /**
    * repository::_splitsqlfile()
    *
    * { Description }
    *
    */
    function _splitsqlfile($sql) {
        return lib_split_sql($sql);
    }
    /**
    * repository::_conn_server()
    *
    * { Description }
    *
    * @param string $get
    */
    function _conn_server($get = '') {
        $get = $get != '' ? $get : $this->_service;
        $fp = @fsockopen ($this->_server, 80, $errno, $errstr, 5);
        if (!$fp) $return = "<repository>\n<service name=\"$get\">\n<error errno=\"$errno\">\n" . $errstr . "\n</error>\n</service>\n</repository>\n";
        else {
            $poststring = 'service=' . $get;
            $getstring = "GET " . $this->_service_path . "?$poststring HTTP/1.0\r\n";
            $getstring .= "Host: " . $this->_server . "\r\n";
            $getstring .= "Referer: " . $this->_referer . "\r\n";
            $getstring .= "User-Agent: Sefrengo/" . $this->_version . "\r\n";
            $getstring .= "\r\n";
            fputs ($fp, $getstring);
            while (!feof($fp)) $return .= fgets ($fp, 4096);
            fclose ($fp);
        }
        return $return;
    }
    /**
    * repository::_checkDir()
    *
    * { Description }
    *
    * @param string $dir_name
    */
    function _checkDir($dir, $dir_name = '') {
        if(!$this->_safemode) return lib_check_dir($dir);
    }
    /**
    * repository::_checkFile()
    *
    * { Description }
    *
    * @param string $file_name
    */
    function _checkFile($file, $file_name = '') {
        if(!$this->_safemode) return lib_check_file($file);
    }
    /**
    * repository::_rebuild_ids()
    *
    * { Description }
    *
    */
    function _rebuild_ids() {
        global $val_ct;
        if ($this->_cms_var['repository_auto_version'] == true) {
            lib_build_repository_ids('mod');
            lib_build_repository_ids('plug');
            $group = 'cfg';
		    $client = 0;
		    $key = 'repository_auto_version';
		    $value = 0;
		    $val_ct->set_value(array('group' => $group, 'client' => $client, 'key' => $key, 'value' => $value));
        }
    }
    /**
    * repository::_mod_import()
    *
    * { Description }
    *
    */
    function _mod_import($xml_array, $idclient) {
        $checked = (($err_i = $this->mod_test(cms_stripslashes($xml_array['input']), $idmod)) || ($err_0 = $this->mod_test(cms_stripslashes($xml_array['output']), $idmod))) ? '0' : '1';
        $sql = "INSERT INTO " . $this->_db_var['mod'] . " (idclient, " . implode(',', array_keys($xml_array)) . ", checked) VALUES($idclient, '" . implode("','", array_values($xml_array)) . "', '$checked')";
        if (!($insert = $this->_db->query($sql))) return false;
        $idmod = $this->_db->insert_id();
        return $idmod;
    }
    /**
    * repository::_plug_import()
    *
    * { Description }
    *
    */
    function _plug_import($xml_array, $idclient) {
        $sql = "INSERT INTO " . $this->_db_var['plug'] . " (idclient, " . implode(',', array_keys($xml_array)) . ") VALUES($idclient, '" . implode("','", array_values($xml_array)) . "')";
        if (!($insert = $this->_db->query($sql))) return false;
        $idplug = $this->_db->insert_id();
        return $idplug;
    }
    /**
    * repository::_repair_dependency()
    *
    * repair repository dependency
    *
    */
    function _repair_dependency(&$entry, $order) {
        list($type,$rid,$uid) = explode(":",$entry['repository_id']);
        if (!empty($type) && empty($order)) $order = $type;
        elseif (empty($type) && !empty($order)) $type = $order;
        if (empty($order)) return false;
        $_modrepid = $type.':'.$rid;
        $_parent_found = false;
        if ($this->_auto_repair && $entry['repository_id'] != '') {
            if (is_array(($parent = $this->_data(0, $type, $_modrepid, true)))) foreach ($parent as $modul) {
                if ((lib_floatval($entry['version']) >= lib_floatval($modul['version'])) && empty($modul['source_id']) && $modul['id'.$type] != $entry['id'.$type]) {
                    $entry['source_id'] = $modul['id'. $type];
                    $entry['scounto'] = 1;
                    $sql = "UPDATE " . $this->_db_var[$type] . " SET source_id = '" . $entry['source_id'] . "' WHERE repository_id = '" . $entry['repository_id'] . "'";
                    $_parent_found = true;
                    break;
                }
            } elseif (is_array(($parent = $this->_data($entry['idclient'], $type, $_modrepid, true)))) foreach ($parent as $modul) {
                if ((lib_floatval($entry['version']) >= lib_floatval($modul['version'])) && empty($modul['source_id']) && $modul['id'.$type] != $entry['id'.$type]) {
                    $entry['source_id'] = $modul['id'. $type];
                    $entry['scount'] = 1;
                    $sql = "UPDATE " . $this->_db_var[$type] . " SET source_id = '" . $entry['source_id'] . "' WHERE repository_id = '" . $entry['repository_id'] . "'";
                    $_parent_found = true;
                    break;
                }
            } else {
                if ($this->_repair_repid($entry, $order)) return $this->_repair_dependency($entry, $order);
            }
        } 
        if (!$_parent_found) {
            $entry['source_id'] = 0;
            $sql = "UPDATE " . $this->_db_var[$type] . " SET source_id = '" . $entry['source_id'] . "' WHERE id". $order . " = '" . $entry['id'. $order] . "'";
        }
        if (!($update = $this->_db->query($sql))) return false;
        else return true;
    }
    /**
    * repository::_repair_repid()
    *
    * repair repository Id´s
    *
    */
    function _repair_repid(&$entry, $order) {
        #UPDATE cms_mod SET cms_mod.name = REPLACE(cms_mod.name, 'Kopie von ', ''), cms_mod.verbose = CONCAT('Kopie von ', cms_mod.name) WHERE cms_mod.name LIKE 'Kopie von%'
        list($type,$rid,$uid) = explode(":",$entry['repository_id']);
        if (!empty($type) && empty($order)) $order = $type;
        elseif (empty($type) && !empty($order)) $type = $order;
        if (empty($order)) return false;
        $name  = str_replace('Kopie von ', '', $entry['name']);
        $repid = $this->_gen_repid($name, $type, true);
        $sql = "UPDATE " . $this->_db_var[$type] . " SET name = '".$name."', verbose = '".$entry['name']."', repository_id = '" . $repid . "' WHERE id".$type." = '" . $entry['id'.$type] . "' AND repository_id = '" . $entry['repository_id'] . "'";
        if (!($update = $this->_db->query($sql))) return false;
        else {
            $entry['repository_id'] = $repid;
            $entry['name'] = $name;
            return true;
        }
    }
    /**
    * repository::_gen_repid
    *
    * generate new repository Id´s
    *
    */
    function _gen_repid($name, $type = 'mod', $force = false) {
        global $cms_db;
        $hash  = lib_hash_name($name, $type);
        $key   = lib_make_key();
        $sql   = "SELECT * FROM " . $cms_db[$type] . " WHERE repository_id like '" . $hash . "%' AND source_id = 0";
        $result = $this->_fetch_query($sql);
        if ($result && !$force) return $this->_set_error('1', '1507');
        else return ($hash . ':' . $key);
    }
}
?>
