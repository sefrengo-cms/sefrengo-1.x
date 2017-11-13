<?php

//
// convert container config to utf-8 and convert dedi links 
//

function update01_00_03_transform_modconfig($in) {
    
    //extract
    $keyandvalues = preg_split("/&/", $in);
    foreach ($keyandvalues as $kandv) {
        $extracted_pairs = explode('=', $kandv);
        $key = $extracted_pairs['0'];
        $value = $extracted_pairs['1'];
        $plain[$key] = urldecode($value);
        $plain[$key] = stripslashes($plain[$key]);
    }
    
    //convert and pack
    $out = '';
    foreach($plain as $key => $value) {
		if (is_array($value)) {
			if (is_array($value)) {
				$value = implode(',',$value);
			}
        }
        $value = utf8_encode($value);
		$value = addslashes($value);
		$value = str_replace(array('http://dedilink/fileid=', 
									'http://dedilink/idcat=', 
									'http://dedilink/idcatside='), 
								array('cms://idfile=',
										'cms://idcat=',
										'cms://idcatside='), 
								$value);
		$value = urlencode($value);
		$out .= $key.'='.$value.'&';
	}
	$out = preg_replace('/&$/', '', $out);
	return $out;
   
}

function update01_00_03_make_string_dump($tmp = '') {
	$tmp = str_replace('\\', '\\\\', $tmp);
	$tmp = str_replace('\'', '\\\'', $tmp);
	$tmp = str_replace(array('\x00', '\x0a', '\x0d', '\x1a'), array('\0', '\n', '\r', '\Z'), $tmp);
	return $tmp;
}

//
// UPDATING CONTAINER_CONF
//

$table = $this->globals['prefix']. 'container_conf';
$sql = "SELECT idcontainerconf, config FROM $table";
$result = mysqli_query($this->mysql_con_handle, $sql);

if (!$result) {
	die("ERROR: No handle for '$sql'");
}

while ($row = mysqli_fetch_array($result)) {
	$content = update01_00_03_transform_modconfig($row['config']);
	$sql2 = "UPDATE $table SET config='$content' WHERE idcontainerconf=". $row['idcontainerconf'];
	mysqli_query($this->mysql_con_handle, $sql2);
}
mysqli_free_result($result);

//
// UPDATING MODS
//

$table = $this->globals['prefix']. 'mod';
$sql = "SELECT idmod, config FROM $table";
$result = mysqli_query($this->mysql_con_handle, $sql);

if (!$result) {
	die("ERROR: No handle for '$sql'");
}

while ($row = mysqli_fetch_array($result)) {
	$content = update01_00_03_transform_modconfig($row['config']);
	$sql2 = "UPDATE $table SET config='$content' WHERE idmod=". $row['idmod'];
	mysqli_query($this->mysql_con_handle, $sql2);
}
mysqli_free_result($result);


//
// SET STARTLANG
//

$table = $this->globals['prefix']. 'clients';
$sql = "SELECT idclient FROM $table";

$table2 = $this->globals['prefix']. 'clients_lang';
$sql2 = "SELECT idlang FROM $table2 WHERE idclient='%s' LIMIT 1";

$table3 = $this->globals['prefix']. 'lang';
$sql3 = "UPDATE $table3 SET is_start='1' WHERE idlang='%s'";

$result = mysqli_query($this->mysql_con_handle, $sql);

if (!$result) {
	die("ERROR: No handle for '$sql'");
}

while ($row = mysqli_fetch_array($result)) {
	$result2 = mysqli_query($this->mysql_con_handle, sprintf($sql2, $row['idclient']));
	if ( $row2 = mysqli_fetch_array($result2) ) {
		mysqli_query($this->mysql_con_handle, sprintf($sql3, $row2['idlang']));
	}
	mysqli_free_result($result2);
}
mysqli_free_result($result);


//
// update metatag defaults
//
$table = $this->globals['prefix']. 'clients';
$sql = "SELECT idclient FROM $table";

$table2 = $this->globals['prefix']. 'clients_lang';
$sql2 = "SELECT idlang FROM $table2 WHERE idclient='%s'";

$table_values = $this->globals['prefix']. 'values';


$result = mysqli_query($this->mysql_con_handle, $sql);
if (!$result) {
	die("ERROR: No handle for '$sql'");
}
while ($row = mysqli_fetch_array($result)) {
	$result2 = mysqli_query($this->mysql_con_handle, sprintf($sql2, $row['idclient']));
	
	$sql_m1 = "SELECT `value` FROM $table_values WHERE `idclient` = '".$row['idclient']."' AND`group_name` = 'cfg_client' AND `key1` = 'meta_description'";
	$r_m1 = mysqli_query($this->mysql_con_handle, $sql_m1);
	$row_m1 = mysqli_fetch_array($r_m1);
		
	$sql_m2 = "SELECT `value` FROM $table_values WHERE `idclient` = '".$row['idclient']."' AND`group_name` = 'cfg_client' AND `key1` = 'meta_keywords'";
	$r_m2 = mysqli_query($this->mysql_con_handle, $sql_m2);
	$row_m2 = mysqli_fetch_array($r_m2);
		
	$sql_m3 = "SELECT `value` FROM $table_values WHERE `idclient` = '".$row['idclient']."' AND`group_name` = 'cfg_client' AND `key1` = 'meta_robots'";
	$r_m3 = mysqli_query($this->mysql_con_handle, $sql_m3);
	$row_m3 = mysqli_fetch_array($r_m3);
		
	
	while ( $row2 = mysqli_fetch_array($result2) ) {
		
		$sql_meta_desc = "INSERT INTO $table_values VALUES ('', ".$row['idclient'].", ".$row2['idlang'].", 'cfg_lang', 'meta_description', '', '', '', '".update01_00_03_make_string_dump($row_m1['value'])."', 600, 'set_meta_description', 'set_meta', 'txt', NULL, NULL, '1')";
		$sql_meta_key = "INSERT INTO $table_values VALUES ('', ".$row['idclient'].", ".$row2['idlang'].", 'cfg_lang', 'meta_keywords', '', '', '', '".update01_00_03_make_string_dump($row_m2['value'])."', 601, 'set_meta_keywords', '', 'txt', NULL, NULL, '1')";
		$sql_meta_robots = "INSERT INTO $table_values VALUES ('', ".$row['idclient'].", ".$row2['idlang'].", 'cfg_lang', 'meta_robots', '', '', '', '".update01_00_03_make_string_dump($row_m3['value'])."', 602, 'set_meta_robots', '', 'txt', NULL, NULL, '1')";
		mysqli_query($this->mysql_con_handle, $sql_meta_desc);
		mysqli_query($this->mysql_con_handle, $sql_meta_key);
		mysqli_query($this->mysql_con_handle, $sql_meta_robots);
		
	}
	mysqli_free_result($result2);
	
	$sql_del_meta_desc = "DELETE FROM $table_values WHERE `idclient` = '".$row['idclient']."' AND`group_name` = 'cfg_client' AND `key1` = 'meta_description'";
	$sql_del_meta_key = "DELETE FROM $table_values WHERE `idclient` = '".$row['idclient']."' AND`group_name` = 'cfg_client' AND `key1` = 'meta_keywords'";
	$sql_del_meta_robots = "DELETE FROM $table_values WHERE `idclient` = '".$row['idclient']."' AND`group_name` = 'cfg_client' AND `key1` = 'meta_robots'";
	mysqli_query($this->mysql_con_handle, $sql_del_meta_desc);
	mysqli_query($this->mysql_con_handle, $sql_del_meta_key);
	mysqli_query($this->mysql_con_handle, $sql_del_meta_robots);
}
mysqli_free_result($result);
