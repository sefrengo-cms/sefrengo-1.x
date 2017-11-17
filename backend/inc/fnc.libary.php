<?PHP
// File: $Id: fnc.libary.php 28 2008-05-11 19:18:49Z mistral $
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

/* functions that returns html*/

// javascript redirection
function lib_java_redir($url, $time = 1000) {
	return "
	<script type=\"text/javascript\">\n
	function go(){\n
	window.location.href=\"$url\";\n
	}\n
	setTimeout(\"go();\", \"$time\");\,
	</script>\n";
}

/* spezial functions */

// nachladefunktion für Klassen
function lib_load_class ($class, $class_name, $start_var = "") {
    include_once($class);
    $class_var = new $class_name($start_var);
    return $class_var;
}

// run it as php
function lib_run_php($code) {
    return eval('?>' . $code);
}

// parse key string
function lib_parse_keys($text, $array, $sep1 = "{", $sep2 = "}") {
    while(list($key,$val)=@each($array)){
        $text=str_replace($sep1.$key.$sep2,$val,$text);
    }
    return $text;
}

// returns a static cache var
function lib_static($key, $val = true) {
    static $_static;
    $return = $_static[$key];
    $_static[$key] = $val;
    return $return;
}

// return colored php
function lib_colored_modul($code) {
    $code = str_replace('<CMSPHP>', "<?php\n //<CMSPHP>", $code);
    $code = str_replace('</CMSPHP>', "//</CMSPHP>\n ?>", $code);
	//todo: 2remove
	$code = str_replace('<DEDIPHP>', "<?php\n //<DEDIPHP>", $code);
    $code = str_replace('</DEDIPHP>', "//</DEDIPHP>\n ?>", $code);
		
    $code = str_replace('MOD_VALUE', '$MOD_VALUE', $code);
    $code = str_replace('MOD_VAR', '$MOD_VAR', $code);
		//todo: 2remove
    $code = preg_replace ('/(<(cms|dedi):[\/\!]*?[^<>]*?>)/si', '""', $code);
    $color = @highlight_string($code, true);
    return $color;
}
/* string functions */

// returns a float from a string
function lib_floatval($strValue) {
    $floatValue = preg_replace("/[^0-9\.]*/", "", $strValue);
    return $floatValue;
}

// return a autoversion
function lib_auto_version($version) {
    $num = explode('.', $version);
    $num[(count($num)-1)] =+ 1;
    return implode(".", array_values($num));
}

/* functions for crypting */

// entschlüsseln ROT5
function lib_decrypt($key = "") {
    $key=strrev("$key");
    $len = strlen("$key");
    for($i=0;$i<$len;$i++) {
        $pos=$i;
        $tmp=substr("$key",$pos,1);
        $pos=$pos+1;
        $erg .= chr(substr("$key",$pos,$tmp)-5); //ROT5
        $i=$i+$tmp;
    }
    return $erg;
}

// verschlüsseln ROT5
function lib_encrypt($var) {
    settype($var, "string");
    settype($erg, "string");
    $len = strlen($var);
    for($i=0;$i<$len;$i++) {
        $tmp = ord($var[$i])+5; //ROT5
        $erg .= strlen($tmp).$tmp;
    }
    $erg=strrev("$erg");
    return $erg;
}

// make a key
function lib_make_key($letters = 8) {
	return substr(md5(uniqid(microtime(),1)), 0, $letters);
}

// make a uniqe id 32
function lib_make_uniqe_id($prefix = 'hash') {
    $uniqe_id  = "";
    $uniqe_id .= chr(rand(65,90));
    $uniqe_id .= time() . uniqid($prefix);
}

// return a control key
function lib_hash($str, $type = '') {
    switch ($type) {
        case 'crc32':
            return sprintf('% 32d', crc32($str));
        case 'strlen':
            return sprintf('% 32d', strlen($str));
        case 'md5':
        default:
            return md5($str);
    }
}
// hash name
function lib_hash_name($name, $prefix = 'hash', $type = '') {
    $name = urlencode(trim(strtolower($name)));
    $hash = trim($prefix).':'.lib_hash($name, $type);
    return $hash;
}
/* functions for sql */

// returns a encodet query
function lib_enc_sql($query) {
    global $cfg_cms, $client;
    $query = str_replace('{plug_client_prefix}', '' . $cfg_cms['db_table_prefix'] . 'plug_' . $client . '_', $query);
    $query = str_replace('{plug_prefix}', '' . $cfg_cms['db_table_prefix'] . 'plug_', $query);
    $query = str_replace('{mod_client_prefix}', '' . $cfg_cms['db_table_prefix'] . 'mod_' . $client . '_', $query);
    $query = str_replace('{mod_prefix}', '' . $cfg_cms['db_table_prefix'] . 'mod_', $query);
    $query = str_replace('{client_prefix}', '' . $cfg_cms['db_table_prefix'] . 'client_' . $client . '_', $query);
    $query = str_replace('{table_prefix}', '' . $cfg_cms['db_table_prefix'], $query);
    $query = str_replace('{client_id}', $client, $query);
    $query = str_replace('{now}', time(), $query);
    return $query;
}

// returns a decodet query
function lib_dec_sql($query) {
    global $cfg_cms, $client;
    $query = str_replace('' . $cfg_cms['db_table_prefix'] . 'plug_' . $client . '_', '{plug_client_prefix}', $query);
    $query = str_replace('' . $cfg_cms['db_table_prefix'] . 'plug_', '{plug_prefix}', $query);
    $query = str_replace('' . $cfg_cms['db_table_prefix'] . 'mod_' . $client . '_', '{mod_client_prefix}', $query);
    $query = str_replace('' . $cfg_cms['db_table_prefix'] . 'mod_', ' {mod_prefix}', $query);
    $query = str_replace('' . $cfg_cms['db_table_prefix'] . 'client_' . $client . '_', '{client_prefix}', $query);
    //$query = str_replace('' . $cfg_cms['db_table_prefix'], '{table_prefix}', $query);
    return $query;
}

// split a sql string
function lib_split_sql($sql) {
    $return = array();
    $sql = trim($sql);
    $sql_len = strlen($sql);
    $char = '';
    $string_start = '';
    $in_string = false;
    for ($i = 0; $i < $sql_len; ++$i) {
        $char = $sql[$i];
        if ($in_string) {
            for (;;) {
                $i = strpos($sql, $string_start, $i);
                if (!$i) {
                    $return[] = $sql;
                    return $return;
                } elseif ($string_start == '`' || $sql[$i-1] != '\\') {
                    $string_start = '';
                    $in_string = false;
                    break;
                } else {
                    $j = 2;
                    $escaped_backslash = false;
                    while ($i - $j > 0 && $sql[$i - $j] == '\\') {
                        $escaped_backslash = !$escaped_backslash;
                        $j++;
                    }
                    if ($escaped_backslash) {
                        $string_start = '';
                        $in_string = false;
                        break;
                    } else {
                        $i++;
                    }
                }
            }
        } elseif ($char == ';') {
            $return[] = substr($sql, 0, $i);
            $sql = ltrim(substr($sql, min($i + 1, $sql_len)));
            $sql_len = strlen($sql);
            if ($sql_len) {
                $i = -1;
            } else return $return;
        } elseif (($char == '"') || ($char == '\'') || ($char == '`')) {
            $in_string = true;
            $string_start = $char;
        } elseif ($char == '#' || ($char == ' ' && $i > 1 && $sql[$i-2] . $sql[$i-1] == '--')) {
            $start_of_comment = (($sql[$i] == '#') ? $i : $i-2);
            $end_of_comment = (strpos(' ' . $sql, "\012", $i + 2)) ? strpos(' ' . $sql, "\012", $i + 2) : strpos(' ' . $sql, "\015", $i + 2);
            if (!$end_of_comment) {
                if ($start_of_comment > 0) {
                    $return[] = trim(substr($sql, 0, $start_of_comment));
                }
                return $return;
            } else {
                $sql = substr($sql, 0, $start_of_comment) . ltrim(substr($sql, $end_of_comment));
                $sql_len = strlen($sql);
                $i--;
            }
        }
    }
    if (!empty($sql) && preg_match('@[^[:space:]]+@', $sql)) {
        $return[] = $sql;
    }
    return $return;
}

/* config reader */

// reads global settings from db
function lib_init_settings($name) {
    global $val_ct, $client;
    $cfg_name = 'cfg_' . $name;
    global $$cfg_name;
    $$cfg_name = $val_ct->get_by_group($name, $client);
    return $vars;
}

// reads gloabl settings from filecontent
function lib_read_settings($source, $name = 'GLOBALS', $process_sections = false) {
    $cfg_name = 'cfg_' . $name;
    global $$cfg_name;
    $lines = preg_split("/\n/s", $source, -1, PREG_SPLIT_NO_EMPTY);
    foreach($lines as $line) {
        $line = trim($line);
        if ($line == '') continue;
        if ($line[0] == '[' && $line[strlen($line) - 1] == ']') $sec_name = substr($line, 1, strlen($line) - 2);
        else {
            $pos = strpos($line, '=');
            $property = substr($line, 0, $pos);
            $value = substr($line, $pos + 1);
            if ($process_sections && $sec_name != '') $vars[$sec_name][$property] = $value;
            else $vars[$property] = $value;
        }
        unset($property, $value, $sec_name);
    }
    $$cfg_name = $vars;
    return $vars;
}

/* file functions */

// return a file info from uploadet file thats moved to upload/in
function lib_get_upload($name) {
    global $_FILES, $cfg_cms;
		$tmp['name'] = $_FILES["$name"]['tmp_name'];
    $tmp['size'] = $_FILES["$name"]['size'];
    $tmp['real'] = $_FILES["$name"]['name'];
    $tmp['copy'] = $cfg_cms['cms_path'].'upload/'.'in/'. $tmp['real'];
		$tmp['code'] = $_FILES["$name"]['error'];
    // Es wurde keine Datei hochgeladen
    if (empty($tmp['name']) || empty($tmp['size']) || $tmp['code'] >= 1) $tmp['error'] = '-1';
    elseif (false == move_uploaded_file($tmp['name'], $tmp['copy'])) $tmp['error'] = '-2';
    return $tmp;
}

// test the safe mode
function lib_test_safemode() {
    if (ini_get('safe_mode') === 1 || ini_get('safe_mode') === true)
		return true;
}

// checkup dir
function lib_check_dir($dir) {
    if(@file_exists($dir) && @is_dir($dir) && @is_writeable($dir))
		return true;
}

// checkup file
function lib_check_file($file, $readable = false) {
    $ret = false;
    clearstatcache();
    if(@file_exists($file) && @is_file($file)) {
        if($readable && is_readable($file) ) {
            return true;
        } elseif(!is_writable($file)) {
            @chmod($file, 0777);
            if(!$fp = @fopen($file, "w")) return $ret;
            if($fp) fclose($fp);
        }
        $ret = true;
    }
    return $ret;
}

// returns a file content
function lib_read_file($file) {
    @chmod($file, 0777);
    if (!@is_file($file)) return '-1';
    if (($handle = @fopen ($file, 'rb')) != false) {
        $filecontent = fread ($handle, filesize($file));
        fclose ($handle);
        return $filecontent;
    } return '-2';
}

// write a file
function lib_write_file($file, $content, $mode = 2) {
	switch ($mode) {
        case 1: // append
            if (!@is_writable($file)) return '-1';
            if (!$fp = @fopen($file, 'a+')) return '-2';
            if (!fwrite($fp, $content . "\r\n")) return '-3';
            fclose($fp);
            break;
        case 2: // create
            if (@is_file($file)) @chmod($file, 0775);
            else @umask(0000);
            if (!$fp = @fopen($file, 'w+')) return '-4';
            if (!fwrite($fp, $content . "\r\n")) return '-5';
            fclose($fp);
            break;
    }
    if (!lib_check_file($file)) return '-6';
    else return true;
}

// delete a file
function lib_delete_file($file) {
    @chmod($file,0777);
    if (lib_check_file($file)) {
        clearstatcache();
        $delete = @unlink($file);
        if (@file_exists($file)) {
            clearstatcache();
            $filesys = preg_replace("#/#i", "\\", $file);
            $delete = @system("del $filesys");
            if (@file_exists($file)) {
                clearstatcache();
                $delete = @chmod ($file, 0777);
                $delete = @unlink($file);
                $delete = @system("del $filesys");
            }
        }
    } elseif (@is_dir($file)) if (lib_remove_dir($file) == '-1') return '-1';
    if (@file_exists($file)) return '-1';
}

// remove dir recursive
function lib_remove_dir($dir) {
    @chmod($dir,0777);
    if (lib_check_dir($dir)) {
        if (($handle = @opendir($dir)) != false ) {
            while(($filename = @readdir($handle)) != false) {
                if ($filename == "." || $filename == "..") continue;
                else {
                    clearstatcache();
                    if (@is_file($dir."/".$filename)) {if (lib_delete_file($dir."/".$filename) == '-1') return '-1';}
                    else {
                        clearstatcache();
                        if (@is_dir($dir."/".$filename)) {if (lib_remove_dir($dir."/".$filename) == '-1') return '-1';}
                    }
                }
            }
            closedir($handle);
            @rmdir($dir);
        }
    } elseif (lib_check_file($dir)) {
        return lib_delete_dir($dir);
    }
    clearstatcache();
    if (@is_dir($dir)) return '-1';
}

/* simple xml functions */

// build a xml tree
function lib_xml_tree($data, $strict = true, $white = 1) {
    $data = trim($data);
    $vals = $index = $array = array();
    if (!($parser = @xml_parser_create())) return '-1';
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, $white);
    if (!xml_parse_into_struct($parser, $data, $vals, $index) && $strict) return '-2';
    xml_parser_free($parser);
    $i = 0;
    $tagname = $vals[$i]['tag'];
    if (isset ($vals[$i]['attributes'])) $array[$tagname]['@'] = $vals[$i]['attributes'];
    else $array[$tagname]['@'] = array();
    $array[$tagname]["#"] = lib_xml_depth($vals, $i);
    return $array;
}

// build an array from xml
function lib_xml_depth($vals, &$i) {
    $children = array();
    if (isset($vals[$i]['value'])) array_push($children, $vals[$i]['value']);
    while (++$i < count($vals)) {
        switch ($vals[$i]['type']) {
            case 'open':
                if (isset ($vals[$i]['tag'])) $tagname = $vals[$i]['tag'];
                else $tagname = '';
                if (isset ($children[$tagname])) $size = sizeof($children[$tagname]);
                else $size = 0;
                if (isset ($vals[$i]['attributes'])) $children[$tagname][$size]['@'] = $vals[$i]['attributes'];
                $children[$tagname][$size]['#'] = lib_xml_depth($vals, $i);
                break;
            case 'cdata':
                array_push($children, $vals[$i]['value']);
                break;
            case 'complete':
                $tagname = $vals[$i]['tag'];
                if (isset ($children[$tagname])) $size = sizeof($children[$tagname]);
                else $size = 0;
                if (isset ($vals[$i]['value'])) $children[$tagname][$size]["#"] = $vals[$i]['value'];
                else $children[$tagname][$size]["#"] = '';
                if (isset ($vals[$i]['attributes'])) $children[$tagname][$size]['@'] = $vals[$i]['attributes'];
                break;
            case 'close':
                return $children;
                break;
        }
    }
    return $children;
}

/* some misc */

// build new repository_id´s for update
function lib_build_repository_ids($order = 'mod', $idclient = '') {
    global $db, $cfg_cms, $cms_db;
    $dbupdate = $db = new DB_cms;
    $ident = $idclient == '' ? '' : "idclient = '$idclient' AND";
    $sql = "SELECT name, id$order FROM " . $cms_db["$order"] . " WHERE $ident repository_id IS NULL";
    $db->query($sql);
    while ($db->next_record()) {
        $update = "UPDATE " . $cms_db["$order"] . " SET repository_id = '" . lib_hash_name($db->f('name'), $order) . ':' . lib_make_key() . "' WHERE id$order = '" . $db->f('id'.$order) . "'";
        $dbupdate->query($update);
    }
}

// optimice tables
function lib_optimice_tables() {
    global $cms_db, $db, $cfg_cms, $val_ct;
    foreach ($cms_db as $table) {
        $sql = 'OPTIMIZE TABLE `' . $table . '`';
        $db->query($sql);        
    }
}
?>
