<?PHP
// File: $Id: fnc.clients.php 28 2008-05-11 19:18:49Z mistral $
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

function clients_get_clients()
{
	global $db, $perm, $cms_db;

	$sql = "SELECT DISTINCT 
				* 
			FROM 
				". $cms_db['clients'] ." A 
				LEFT JOIN ". $cms_db['clients_lang']." B USING(idclient) 
			ORDER BY 
				A.name";

	$db->query($sql);

	$prev_client = '';
	while($db->next_record()) 
	{
		if ($perm -> have_perm(1, 'clients', $db->f('idclient')) && $prev_client != $db->f('idclient')) {
			$p_id = $db->f('idclient');
			$projects['order'][] = $p_id;
			$projects[$p_id]['name'] = $db->f('name');
			$projects[$p_id]['desc'] = $db->f('description');
			//wenn mehrere sprachen in einem client sind, verhindern, 
			//dass der client öfters als ein mal angezeigt wird
			$prev_client = $db->f('idclient');
		}
		$projects['num_clients'] = count($projects['order']);
	}
	
	$projects['num_langs'] = 0;
	if(is_array($projects['order'])){
		foreach ($projects['order'] AS $idclient)
		{
			$projects[$idclient]['langs'] = clients_get_langs($idclient);
			if(! empty($projects[$idclient]['langs']['order']['0']) ) $projects[$idclient]['have_childs'] = true;
			else $projects[$idclient]['have_childs'] = false;
			
			$projects['num_langs'] += count($projects[$idclient]['langs']['order']);
		}
	}
	
	return $projects;
}

function clients_get_langs($idclient, $disable_perms = false)
{
	global $db, $perm, $cms_db;

	$sql = "SELECT 
				A.idlang, A.name , A.description, A.charset, A.iso_3166_code, A.rewrite_key, A.rewrite_mapping, A.is_start
			FROM 
				".$cms_db['lang']." A 
				LEFT JOIN ".$cms_db['clients_lang']." B USING(idlang) 
			WHERE 
				B.idclient='$idclient' 
			ORDER BY idlang";

	$db->query($sql);

	while($db->next_record()) 
	{
		if ($disable_perms || $perm -> have_perm(17, 'clientlangs', $db->f('idlang')) ) {
			$l_id = $db->f('idlang');
			$langs['order'][] = $l_id;
			$langs[$l_id]['name'] = $db->f('name');
			$langs[$l_id]['desc'] = $db->f('description');
			$langs[$l_id]['charset'] = $db->f('charset');
			$langs[$l_id]['iso_3166_code'] = $db->f('iso_3166_code');
			$langs[$l_id]['rewrite_key'] = $db->f('rewrite_key');
			$langs[$l_id]['rewrite_mapping'] = $db->f('rewrite_mapping');
			$langs[$l_id]['is_start'] = $db->f('is_start');
		}
	}
	
	return $langs;
}

function clients_new_client($cid, $project_name, $newdesc, $newpath, $newurl, $with_dir, $newlang, $newlangdesc, $charset)
{
	global $db, $cms_db, $auth, $cfg_cms, $perm;
	global $errno, $user_msg;
	//Globals die für Neue Sprache anlegen gebraucht werden
	global $sess, $lang;
	
	$project_name = empty($project_name) ? 'Neues Projekt': $project_name;

	include_once 'Archive/Tar.php';
	$tar = &new Archive_Tar($cfg_cms['cms_path'].'tpl/projektvorlage.tar');

	
	if($with_dir == 1){
		umask(0000);
		$chmod_value = intval($cfg_cms['chmod_value'], 8);
		if(@!mkdir($newpath, $chmod_value)){
			$errno = 'cant_make_path';
			return;
		}
		if(!$tar->extract($newpath)){
			$errno = 'cant_extract_tar';
			return;
		}
	}

	// SQL Einträge
	$sql_array = file($cfg_cms['cms_path'].'tpl/projektvorlage.sql');
	foreach ($sql_array as $sql)
	{
		if(! empty($sql)){
			$sql = substr(chop($sql),0,-1);
			$sql = str_replace("<!--{db_prefix}-->", $cfg_cms['db_table_prefix'],$sql);			
			$sql = str_replace("<!--{cms_path}-->", $newpath,$sql);
			$sql = str_replace("<!--{cms_full_http_path}-->",$newurl,$sql);
			$sql = str_replace("<!--{idclient}-->",$cid,$sql);
			$sql = str_replace("<!--{projectname}-->",$project_name,$sql);
			$sql = str_replace("<!--{projectdesc}-->",$newdesc,$sql);
			$sql = str_replace("<!--{userid}-->",$auth->auth['uid'],$sql);
			$sql = str_replace("<!--{time}-->",time(),$sql);
			$db->query($sql);
		}
	};

	//neue rechte einfügen für client
	$perm->xcopy_perm(0, 'area_clients', $cid, 'clients', 0xFFFFFFFF, 0, 0, true);  // make new userright
	
	//Neue Sprache anlegen
	$newlang = ( empty($newlang)) ? 'Neue Sprache': $newlang;
	$errno = lang_new_language($cid, $newlang, $newlangdesc, $charset, '', 'standard', false);
	
	$sql = "SELECT MAX(idlang) AS max FROM ". $cms_db['lang'];
	$db->query($sql);
	$db->next_record();
	$nextlang = $db->f('max');
	
	//set new lang as startlang
	lang_make_start_lang($cid, $nextlang);
	
	// Config schreiben
	if($with_dir == 1){
		$fh = fopen($newpath."/cms/inc/config.php","w");
		fwrite($fh,"<?PHP\n\$cms_path = '../backend/';\n\$client = '$cid';\n\n?".">\n");
		fclose($fh);
	}

	//neue rechte einfügen für sprache
	$perm->xcopy_perm($cid, 'clients', $nextlang, 'clientlangs', 0xFFFF0000, 0, 0, true);
	
	//langstring for new client success userinfo
	$user_msg = 'success_new_client';

}


function clients_rename_client($idclient, $name, $desc) 
{
	global $db, $auth, $cms_db, $val_ct, $perm;

	set_magic_quotes_gpc($name);
	set_magic_quotes_gpc($desc);

	$sql = "UPDATE 
				".$cms_db['clients'] ."
			SET 
				name='$name', 
				description='$desc', 
				author='".$auth->auth['uid']."', 
				lastmodified='".time()."' 
			WHERE 
				idclient='$idclient'";
	
	$db->query($sql);

	//Rechte setzen
	if ($perm->have_perm(6, 'clients', $idclient)) {
		global $cms_gruppenids, $cms_gruppenrechte, $cms_gruppenrechtegeerbt, $cms_gruppenrechteueberschreiben;
		$perm->set_group_rights( 'clients', $idclient, $cms_gruppenids, $cms_gruppenrechte, $cms_gruppenrechtegeerbt, $cms_gruppenrechteueberschreiben );
	}
}

function clients_delete_client($idclient)
{
	global $db, $sess, $cms_db, $val_ct;

	$langs = clients_get_langs($idclient);
	
	if(is_array($langs['order'])){
		foreach($langs['order'] AS $idlang)
		{
			$err = lang_delete_language($idclient, $idlang);
			if(! empty($err) ) return $err;
		}
	}
	
	$client_config = $val_ct -> get_by_group('cfg_client', $idclient);
	
	//delete all client data in teh following tables
	$del_data = array( $cms_db['clients'], $cms_db['clients_lang'], $cms_db['values'],
					   $cms_db['backendmenu'], $cms_db['css'], $cms_db['directory'],
					   $cms_db['js'], $cms_db['lay'], $cms_db['mod'],
					   $cms_db['upl'], $cms_db['tpl']
					 );
	foreach($del_data AS $v)
	{
		$sql = "DELETE FROM
					". $v . "
				WHERE
					idclient=$idclient";
		$db-> query($sql);
	}
	
	/* ADDED RECURSIVE FILEDELETE LATER
	//check fileperms
	//
	$file_perm = fileperms ($client_config['path']);
	$octalperms = sprintf("%o",$file_perm);
	if(strlen($octalperms) == 5) $octal_final = substr($octalperms,2);
	else $octal_final = substr($octalperms,3);
	$octal_back_nr = substr($octal_final,2);
	//Warnung, wenn perm für public < 6
	if($octal_back_nr < 6){
		echo "WARNUNG! Die Dateirechte der CSS- Datei entsprechen momentan 'CHMOD $octal_final'. Änderungen an der Datei lassen sich vermutlich nicht speichern";
		return;
	}
	*/

}

function recursiveDelete($dir)
{
   if ($handle = @opendir($dir)){
      while (($file = readdir($handle)) !== false)
      {
         if (($file == ".") || ($file == "..")) continue;
         if (is_dir($dir . '/' . $file)) recursiveDelete($dir . '/' . $file);//one dir up
         else unlink($dir . '/' . $file); // remove this file
      }
      @closedir($handle);
      rmdir ($dir);  
   }
} 

?>