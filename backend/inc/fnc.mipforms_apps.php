<?PHP
// File: $Id: fnc.mipforms_apps.php 28 2008-05-11 19:18:49Z mistral $
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

/*
*
* MINI - APPLIKATIONEN
*
*/
/**
* Lädt CSS-Regeln aus den einem Layout zugeordneten CSS-Dateien
*
* $mip_form['output_cat'] gibt die Ordner an, wie das Formularfeld
* später beschaffen sein soll. 'option' oder 'radio' werden unterstützt.
* Empfehlung: 'option'
*
* Mit $mip_form['type'] kann bestimmt werden, wie das CSS- File
* ausgegeben werden soll.
* Mögliche Optionen sind '', 'chk', 'radio', 'long', 'chk_long', 'radio_long'
* (siehe auch "Optionsfelder", "Radiobuttons").
*
* Mit $mip_form['file'] wird das Layout angegeben dessen CSS-Regeln angezeigt
* werden. Wird dies nicht gemacht, werden keine CSS-Regeln zur Auswahl angeboten
*
* $mip_form['flag'] ist ein optionaler Parameter. 'id_only' gibt nur
* css-Id's, 'class_only' nur css-Klassen. Es wird dringend
* empfohlen, diesen Parameter zu setzen, da hinterher keine Rückschlüsse
* mehr auf die Herkunft gezogen werden können (z.B. wird die Klasse
* ".class_or_id" später zu der Variablen 'class_or_id', ebenso wird
* '#class_or_id' zur Variablen 'class_or_id'.
*
*
* @Access private
* @Args array  in['cms_var'] (MOD_VAR)
*                ['cms_val'] (MOD_VALUE)
*                ['desc'] (Beschreibung)
*                ['output_cat'] ('option' oder 'radio')
*                ['type'] ('', 'chk', 'radio', 'long' , 'chk_long', 'radio_long')
*                ['flag'] ('class_only', 'id_only', '')(optional - default ids AND classes)
*                ['return_val'] ('idcssupl', 'name')(optional - default name)
*                ['tab']  (Einrückung)
* @Return css- Werte als form
*/
function mip_forms_app_css($in) {
        global $client, $db, $cms_db, $idlay;

        // Cache auslesen
        $cache_string = md5('css_files_for'.$in['flag'].$in['type'].$in['output_cat'].$in['return_val']);
        $cache_container_desc = $cache_string.'desc';
        $cache_container_val = $cache_string.'val';

        if(mip_forms_check_cache($cache_container_val)){
		$in['option_desc'] = mip_forms_get_cache($cache_container_desc);
		$in['option_val']  = mip_forms_get_cache($cache_container_val);
        } else {
		$in['option_desc'][0] = '--kein--';
		$in['option_val'][0] = '';

		// Stylesheets aus der DB suchen
		if (!empty($idlay)) {
			switch ($in['flag']) {
				case 'class_only':
					$type_operator = "= '.'";
					break;
				case 'id_only':
					$type_operator = "= '#'";
					break;
				default:
					$type_operator = " NOT LIKE ''";
					break;
			}

			// Stylesheets in Array schreiben
			$sql = "SELECT DISTINCT	B.idcssupl, A.type, A.name, A.description FROM ".$cms_db['css']." A LEFT JOIN ".$cms_db['css_upl']." B USING(idcss) LEFT JOIN ".$cms_db['lay_upl']." C USING(idupl) WHERE A.type$type_operator AND A.idclient='$client' AND C.idlay IN($idlay) AND A.name NOT LIKE '%:%' ORDER BY A.name";
			$db->query($sql);
			while($db->next_record()) {
				$in['option_desc'][] = $db->f('name');
				if($in['return_val'] == 'idcssupl') $in['option_val'][]  = $db->f('idcssupl');
				else $in['option_val'][]  = $db->f('name');
			}

			// Cache schreiben
			if ($db->affected_rows()) {
				mip_forms_write_cache($cache_container_desc, $in['option_desc']);
				mip_forms_write_cache($cache_container_val, $in['option_val']);
			}
                 }
        }
        if(isset($in['option_val'][0])) {
                                $in['cat'] = $in['output_cat'];
                                $in['flag'] = $in['output_flag'];
                                $to_return = mip_forms($in);
        }
        return $to_return;
}

function mip_forms_app_cat($in, $_client = '', $_lang = '')
{
	global $db, $client, $lang, $cms_db, $con_lang, $catlist, $catlist_level, $tlo_tree;
	$_client = (int) $_client >= 1 ? (int) $_client : $client;
	$_lang   = (int) $_lang >= 1   ? (int) $_lang : $lang;
	//Check Cache and give output if true
	$cache_string = md5($in['flag'] . $in['type'] . $in['cat'] . $in['output_cat']);
	$cache_container_desc = $cache_string . 'desc';
	$cache_container_val = $cache_string . 'val';

	if(mip_forms_check_cache($cache_container_val)){
		$in['option_desc'] = mip_forms_get_cache($cache_container_desc);
		$in['option_val']  = mip_forms_get_cache($cache_container_val);
	}
	else{
		if(! $in['without_root_cat']){
			$in['option_desc'][] = 'Root';
			$in['option_val'][]  = 'root';
		}
		if(! $in['without_this_cat']){
			$in['option_desc'][] = 'aktueller Ordner';
			$in['option_val'][]  = 'this';
		}
		$sql = "SELECT A.name, B.idcat, B.parent, B.sortindex FROM ". $cms_db['cat_lang'] ." A LEFT JOIN ". $cms_db['cat'] ." B USING(idcat) WHERE A.idlang='$_lang' AND B.idclient='$_client' ORDER BY B.parent, B.sortindex";
		$db->query($sql);
		$rowcount = $db -> num_rows();
		
		while($db->next_record()) {
			$cat_tree[$db->f('idcat')]['idcat'] = $db->f('idcat');
			$cat_tree[$db->f('idcat')]['name'] = $db->f('name');
			$tlo_tree[$db->f('parent')][$db->f('sortindex')] = $db->f('idcat');
		}
		// Ordner sortieren
		//unset($catlist);
		tree_level_order('0', 'catlist');
		//unset($tlo_tree);

		//BUG: $catlist reagiert nicht auf unset($catlist). Liegt vermutlch daran, dass der
		//Array $catlist in tree_level_order() dynamisch erzeugt wird und/ oder dort auch
		//als global deklariert ist.
		//Der Effekt ist, dass die alten Kategorien erhalten bleiben, neu erzeugt werden
		//einfach hinten dran gehängt.
		//Workaround: foreach abbrechen, sobald $catlist größer als $db->num_rows();
		if( is_array($catlist) ){
			$i = 0;
			foreach($catlist as $a)
			{
				$spaces = str_repeat('&nbsp;&nbsp;',$catlist_level[$a]).'&nbsp;';

				$in['option_desc'][] = $spaces.$cat_tree[$a]['name'];
				$in['option_val'][]  = $cat_tree[$a]['idcat'];
				$i++;
				if($i >= $rowcount){
					break;
				}
			}

			//print_r($catlist);
			unset($catlist);
		}
		//write cache
		//mip_forms_write_cache($cache_container_desc, $in['option_desc']);
		//mip_forms_write_cache($cache_container_val, $in['option_val']);
   	}

	$in['cat'] = $in['output_cat'];
	$to_return = mip_forms($in);

	return $to_return;
}

function mip_forms_app_filetype($in)
{
	global $db, $client, $cms_db, $con_lang;

	//Check Cache and give output if true
	$cache_string = md5($in['flag'] . $in['type'] . $in['cat'] . $in['output_cat']);
	$cache_container_desc = $cache_string . 'desc';
	$cache_container_val = $cache_string . 'val';

	if(mip_forms_check_cache($cache_container_val) ){
		$in['option_desc'] = mip_forms_get_cache($cache_container_desc);
		$in['option_val']  = mip_forms_get_cache($cache_container_val);
	}
	else{
		if(! $in['without_all_files']){
			$in['option_desc'][0] = 'Alle Dateitypen';
			$in['option_val'][0]  = 'true';
		}
		$sql = "SELECT
					filetype, description
				FROM
					". $cms_db['filetype'] ."
				ORDER BY
					description, filetype";

		$db->query($sql);
		$rowcount = $db -> num_rows();
		$i = 1;
		while($db->next_record())
		{
			$in['option_val'][$i] = $db-> f('filetype');
			$in['option_desc'][$i]  = $db-> f('description') .' ( *.' . $db-> f('filetype') .' )';
			$i++;
		}

		//write cache
		mip_forms_write_cache($cache_container_desc, $in['option_desc']);
		mip_forms_write_cache($cache_container_val, $in['option_val']);
   	}

	$in['cat'] = $in['output_cat'];
	$to_return = mip_forms($in);

	return $to_return;
}

function mip_forms_app_directory($in) {
	global $db, $client, $cms_db, $con_lang;

	$sql = "SELECT iddirectory, dirname FROM ".$cms_db['directory']." WHERE status < 4 AND idclient='$client' ORDER BY dirname";
	$sql_val = 'iddirectory';
	$sql_val_desc = 'dirname';
	if(! $in['without_all_folders']){
		$user_desc['0'] = 'Alle Ordner&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		$user_val['0'] = 'true';
	}
	return _mip_forms_abstract_simple_sql_app($in, $sql, $sql_val, $sql_val_desc, $user_desc, $user_val);
}

function mip_forms_app_file($in) {
	global $db, $client, $cms_db, $con_lang, $perm;
	// Check Cache and give output if true
	$cache_string = md5($in['flag'] . $in['type'] . $in['output_cat'] . $in['cat']);
	$cache_container_desc = $cache_string . 'desc';
	$cache_container_val = $cache_string . 'val';
	if(mip_forms_check_cache($cache_container_val) && 1==2){
		$in['option_desc'] = mip_forms_get_cache($cache_container_desc);
		$in['option_val']  = mip_forms_get_cache($cache_container_val);
	}
	else{
		$i = 0;
		if(! $in['without_no_file']){
			$in['option_desc'][0] = 'Keine Datei';
			$in['option_val'][0]  = '';
			$i = 1;
		}
		
        //Eventuell vorangehende Kommas aus mip-forms strippen
        if( substr($in['filetypes'],0,1) == ',') $in['filetypes'] = substr($in['filetypes'],1);
        if( substr($in['folders'],0,1) == ',') $in['folders'] = substr($in['folders'],1);
        if( substr($in['folders_by_name'],0,1) == ',') $in['folders_by_name'] = substr($in['folders_by_name'],1);
        		
		if(! empty($in['filetypes']) ){
			$sql_filetypes = '';
			$exploded = explode(',', $in['filetypes']);
			while (list ($key, $value) = each($exploded) )
			{
				$sql_filetypes .= "'" . trim($value) . "',";
			}

			$sql_filetypes = substr ($sql_filetypes, 0, strlen($sql_filetypes)-1);
		}
        $sql_filetypes = (empty($in['filetypes'])) ? '': ' AND B.filetype IN ('. $sql_filetypes .') ';
        $sql_folders = (empty($in['folders'])) ? '': ' AND C.iddirectory IN ('. $in['folders'] .') ';
        if ($in['folders'] == '*') {
            $sql_folders = '';
        }
        if (empty($in['folders']) && array_key_exists('folders',$in)) {
			$in['option_desc'][0] = 'Bitte Ordner ausw&auml;hlen';
			$in['option_val'][0]  = '';
			$i = 1;
			$sql_folders = ' AND C.iddirectory=0 ';
			$in['subfolders'] = false;
        } 
        if (!array_key_exists('folders',$in)) {
    		if(! empty($in['folders_by_name']) ){
    		    $sql_folders =  "'".str_replace(",","','",$in['folders_by_name'])."'";
                $sql_folders = (empty($in['folders_by_name'])) ? '': ' AND C.dirname IN ('. $sql_folders .') ';
        		$sql = "SELECT DISTINCT
        					iddirectory, dirname
        				FROM
        					".$cms_db['directory']." C
        				WHERE
        					idclient= '$client' $sql_folders";
        		$db -> query($sql);
        		for($n=0; $db -> next_record(); $n++)
        		{
        			if(! $perm->have_perm( 1, 'folder', $db->f('iddirectory') ) ){
        				$n--;
        				continue;
        			}
        				
        			$subfolder_list[$n] = ''.$db->f('iddirectory') .'';
        		}
        		$sql_folders = implode(',', $subfolder_list);
        		$sql_folders = ' AND C.iddirectory IN ('. $sql_folders .') ';
        		unset($subfolder_list);
    		}
        }        

    	// user have rights to show subdirectorys, figure they out
    	if (($in['subfolders']) && ! empty($sql_folders)) {
    		$sql = "SELECT DISTINCT
    					iddirectory, dirname
    				FROM
    					".$cms_db['directory']." C
    				WHERE
    					idclient= '$client' $sql_folders";
    		$db -> query($sql);
    		for($n=0; $db -> next_record(); $n++)
    		{
    			if(! $perm->have_perm( 1, 'folder', $db->f('iddirectory') ) ){
    				$n--;
    				continue;
    			}
    				
    			$subfolder_list[$n] = "C.dirname like '". $db->f('dirname') ."%'";
    		}
    		$sql_folders = 'AND '.implode(' AND ', $subfolder_list);
    	}
        
		$sql = "SELECT
				A.idupl, A.filename, B.filetype, C.dirname
			FROM
				".$cms_db['upl']." A
				LEFT JOIN ".$cms_db['filetype']." B USING(idfiletype)
				LEFT JOIN ".$cms_db['directory']." C ON A.iddirectory=C.iddirectory
			WHERE
				A.idclient= '$client'
				AND C.dirname NOT LIKE('cms/%')
				$sql_filetypes
				$sql_folders
			ORDER BY
				C.dirname, A.filename, A.titel";

		$db->query($sql);
		while($db->next_record())
		{
			$in['option_desc'][$i] = $db->f('dirname') . $db->f('filename');
			$in['option_val'][$i]  = $db-> f('idupl');
			$i++;
		}

		//write cache
		mip_forms_write_cache($cache_container_desc, $in['option_desc']);
		mip_forms_write_cache($cache_container_val, $in['option_val']);
   	}

	$in['cat'] = $in['output_cat'];
	$to_return = mip_forms($in);

	return $to_return;
}

function mip_forms_app_group($in) {
	global $db, $client, $cms_db, $con_lang;

	$sql = "SELECT idgroup, name FROM ".$cms_db['groups']." WHERE idgroup > 2 ORDER BY name";
	$sql_val = 'idgroup';
	$sql_val_desc = 'name';
	$i = 0;
	if(! $in['without_all_groups']){
		$user_desc[$i] = 'Alle Gruppen&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		$user_val[$i] = 'true';
		$i++;
	}
	if( $in['with_admin']){
		$user_desc[$i] = 'Nur Administratoren&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		$user_val[$i] = '1';
		$i++;
	}
	return _mip_forms_abstract_simple_sql_app($in, $sql, $sql_val, $sql_val_desc, $user_desc, $user_val);
}

function _mip_forms_abstract_simple_sql_app($in, $sql, $sql_val, $sql_val_desc, $user_desc='', $user_val= '') {
	global $db, $client, $cms_db, $con_lang;

	// Check Cache and give output if true
	$cache_string = md5($in['flag'] . $in['type'] . $in['output_cat'] . $in['cat']);
	$cache_container_desc = $cache_string . 'desc';
	$cache_container_val = $cache_string . 'val';
	if(mip_forms_check_cache($cache_container_val) && 1==2){
		$in['option_desc'] = mip_forms_get_cache($cache_container_desc);
		$in['option_val']  = mip_forms_get_cache($cache_container_val);
	}
	else{
		$i = 0;
		if( is_array($user_desc) ){
			$c = count($user_desc);
			for($i=0; $i<$c;$i++)
			{
				$in['option_desc'][$i] = $user_desc[$i];
				$in['option_val'][$i]  = $user_val[$i];
			}
		}

		$db->query($sql);
		//echo $sql;
		while($db->next_record())
		{
			$in['option_desc'][$i] = $db-> f($sql_val_desc);
			$in['option_val'][$i]  = $db-> f($sql_val);
			$i++;
		}

		//write cache
		mip_forms_write_cache($cache_container_desc, $in['option_desc']);
		mip_forms_write_cache($cache_container_val, $in['option_val']);
   	}

	$in['cat'] = $in['output_cat'];
	$to_return = mip_forms($in);

	return $to_return;
}
?>
