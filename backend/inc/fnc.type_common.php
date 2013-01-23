<?PHP
// File: $Id: fnc.type_common.php 28 2008-05-11 19:18:49Z mistral $
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

/**
 * Check if backenduser have perm to edit contentfield
 *
 * @Args: config - attribute form mod config -> modcontent is editable/ uneditable
 *        tag_attribute - CMS:tag attribut editable
 *        view - the current userview
 * @Return boolean
 * @Access private
 */
function _type_check_editable($config, $tag_attribute, $view, $edit_all=false) {
	global $perm;
	if ($config != true) return false;
	if ($view != 'edit') return false;
	if ($edit_all) return false;
	if ($tag_attribute == 'false') return false;
	if ($tag_attribute == 'true' || empty($tag_attribute)) return true;
	if (_type_check_perm($tag_attribute)) return true;
	return false;
}

function _type_check_editall($cms_side, $type_config) {
	if (!_type_check_perm($type_config['editable'])) return false;
	if ($cms_side['view'] == 'edit' && isset($cms_side['edit']) || ($type_config['editable'] != 'false' || isset($cms_side['edit_all']))) return true;
	return false;
}

function _type_check_perm($string_to_check) {
	global $perm;

	if (stristr($string_to_check , ',') || is_numeric( trim($string_to_check))) {
		$string_to_check = str_replace(' ', '', $string_to_check);
		$checkme = explode(',', $string_to_check);
		if ($perm->is_admin()) return true;
        foreach ($checkme as  $val) {
    		if (in_array($val, $perm->idgroup)) return true;
        }
		return false;
	}
	return true;
}

/**
 * Check CMS:tag Attributes styleclass, styleid, styledb and returns style with the highest
 * priority
 *
 * @Args: CMS:tag attributes styleclass, styleid, styledb
 * @Return array ['style'] = 'iamastyle'
 *               ['type'] = 'class|id'
 *               ['fullstyle'] = 'class="iAmAClass"|id="iAmAnId"'
 * @Access private
 */
function _type_get_style($class = '', $id='', $dbstyle='') {
	global $db, $cms_db, $idlay, $client;

	$css = $class;

	// Style from DB have highest priority
	if (is_numeric($dbstyle)) {
		$sql = "SELECT A.type, A.name FROM ".$cms_db['css']." A LEFT JOIN ".$cms_db['css_upl']." B USING(idcss) LEFT JOIN ".$cms_db['lay_upl']." C USING(idupl) WHERE A.idclient = $client AND C.idlay = $idlay AND B.idcssupl=$dbstyle";
		$db->query($sql);
		if( $db->next_record() ){
			$out['style'] = $db->f('name');
			$out['type'] = ($db->f('type') == '#') ? 'id': ($db->f('type') == '.') ? 'class': 'ERROR-typeIsNotClassAndNotId';
			$out['fullstyle'] = $out['type'] .'="'. $out['style'] . '"';
			return $out;
		}
	}
	// Second priority is styleid
	if (!empty($id)) {
		$out['style'] = $id;
		$out['type'] = 'id';
		$out['fullstyle'] = $out['type'] .'="'. $out['style'] . '"';
		return $out;
	}
	// Last priority is styleclass
	if (!empty($class)) {
		$out['style'] = $class;
		$out['type'] = 'class';
		$out['fullstyle'] = $out['type'] .'="'. $out['style'] . '"';
		return $out;
	}
	return '';
}

function _type_get_stylelist($parameters) {
	global $db, $cms_db, $client;

	$type_operator = "'.'";
	if (empty($parameters)) return;
	$sql = "SELECT CU.idcssupl, C.type, C.name, C.description, C.styles FROM ". $cms_db['css'] ." C LEFT JOIN ". $cms_db['css_upl'] ." CU USING(idcss) WHERE C.type = $type_operator AND C.idclient = $client AND CU.idcssupl IN($parameters) ORDER BY C.description, C.name";
	$db->query($sql);
	for($i=0; $db->next_record(); $i++) {
		$out[$i]['idcssupl'] = $db->f('idcssupl');
		$out[$i]['type'] = $db->f('type');
		$out[$i]['name'] = $db->f('name');
		$out[$i]['desc'] = $db->f('description');
		$out[$i]['autodesc'] = ( empty($out[$i]['desc']) ) ? $out[$i]['name']:$out[$i]['desc'];
		$out[$i]['style'] = $db->f('styles');
	}
	return $out;
}

function _type_get_stylesheet() {
	global $db, $cms_db, $idcatside, $lang, $cfg_client, $cache;

	if (!$cache['stylesheet']) {
		$sql = "SELECT G.dirname, F.filename FROM ".$cms_db['cat_side']." A LEFT JOIN ".$cms_db['side_lang']." B USING(idside) LEFT JOIN ".$cms_db['tpl_conf']." C USING(idtplconf) LEFT JOIN ".$cms_db['tpl']." D USING(idtpl) LEFT JOIN ".$cms_db['lay_upl']." E USING(idlay) LEFT JOIN ".$cms_db['upl']." F USING(idupl) LEFT JOIN ".$cms_db['directory']." G USING(iddirectory) WHERE A.idcatside='$idcatside' AND B.idlang='$lang' AND B.idtplconf!='0' AND F.idfiletype='1'";
		$db->query($sql);
		if (!$db->affected_rows()) {
			$sql = "SELECT G.dirname, F.filename FROM ".$cms_db['cat_side']." A LEFT JOIN ".$cms_db['cat_lang']." B USING(idcat) LEFT JOIN ".$cms_db['tpl_conf']." C USING(idtplconf) LEFT JOIN ".$cms_db['tpl']." D USING(idtpl) LEFT JOIN ".$cms_db['lay_upl']." E USING(idlay) LEFT JOIN ".$cms_db['upl']." F USING(idupl) LEFT JOIN ".$cms_db['directory']." G USING(iddirectory) WHERE A.idcatside='$idcatside' AND B.idlang='$lang' AND B.idtplconf!='0' AND F.idfiletype='1'";
			$db->query($sql);
		}
		$cache['stylesheet_files'] = '';
		while ($db->next_record()) $cache['stylesheet_files'] .= "<link rel=\"StyleSheet\" href=\"".$cfg_client['htmlpath'].$db->f('dirname').$db->f('filename')."\" type=\"text/css\">";
		$cache['stylesheet'] = 'true';
         }
         return $cache['stylesheet_files'];
}


/**
 * Extract fileinfos in the light of maded  CMS:tag attributes settings filetypes, folders and subfolders
 * This settings are in use in the CMS:tag image, file, wysiwyg...
 *
 * @Args:  string filetypes - idfiletypes (comma seperated or empty)
 *         string folders - iddirectories (comma seperated or empty)
 *         string subfolders - if true subdirectorys of folders will extracted too
 * @Return array [$i]['id'] - idupload from table cms_upload
 *               [$i]['dirname'] - full directory relative from $cms_client['upl_path']
 *               [$i]['filename'] - filename
 *               [$i]['pic_width'] - if picture, width of the picture
 *               [$i]['pic_height'] - if picture, height of the picture
 *               [$i]['pic_thumb_width'] - if picture, thumbwidth, if no thumbnail available, width is 0
 *               [$i]['pic_thumb_height'] - if pictur, thumbheight, if no thumbnail available, height is 0
 *               [$i]['titel'] - title from filebrowser titelfield
 *               [$i]['name'] = contract of dirname, filename and (optionally if not empty) titel
 * @Access private
 */
function _type_get_files($filetypes , $folders, $subfolders)
{
	global $db, $cms_db, $cms_lang, $client, $perm;

	//Eventuell vorangehende Kommas aus mip-forms strippen
	if( substr($filetypes,0,1) == ',') $filetypes = substr($filetypes,1);
	if( substr($folders,0,1) == ',') $folders = substr($folders,1);

	if(! empty($filetypes) ){
		$sql_filetypes = '';
		$exploded = explode(',', $filetypes);
		while (list ($key, $value) = each($exploded) )
		{
			$sql_filetypes .= "'" . trim($value) . "',";
		}

		$sql_filetypes = substr ($sql_filetypes, 0, strlen($sql_filetypes)-1);
	}


	$sql_filetypes = (empty($filetypes) || $filetypes == 'true' ) ? '': ' AND B.filetype IN ('. $sql_filetypes .') ';
	$sql_folders = (empty($folders) || $folders == 'true' ) ? '': ' AND C.iddirectory IN ('. $folders .') ';

	// user have rights to show subdirectorys, figure they out
	if (trim($subfolders) == 'true' && ! empty($sql_folders)) {
		$sql = "SELECT DISTINCT
					iddirectory, dirname
				FROM
					".$cms_db['directory']." C
				WHERE
					idclient= '$client' $sql_folders";
		$db -> query($sql);
		for($i=0; $db -> next_record(); $i++)
		{
			if(! $perm->have_perm( 1, 'folder', $db->f('iddirectory') ) ){
				$i--;
				continue;
			}
				
			$subfolder_list[$i] = "C.dirname like '". $db->f('dirname') ."%'";
		}
		$sql_folders = 'AND '.implode(' AND ', $subfolder_list);
	}
	
	//$access_sql = ($perm->is_admin()) ?'': ' AND (A.iddirectory & 0x01) = 0x01 ';
	
	$sql = "SELECT
				A.*, B.filetype, C.dirname, C.iddirectory
			FROM
				".$cms_db['upl']." A
				LEFT JOIN ".$cms_db['filetype']." B USING(idfiletype)
				LEFT JOIN ".$cms_db['directory']." C ON A.iddirectory=C.iddirectory $access_sql
			WHERE
				A.idclient= '$client'
				AND C.dirname NOT LIKE('cms/%')
				$sql_filetypes
				$sql_folders
			ORDER BY
				C.dirname, A.filename, A.titel";
	$db -> query($sql);

	for($i=0; $db -> next_record(); $i++)
	{
		if(! $perm->have_perm( 17, 'file', $db->f('idupl'), $db->f('iddirectory') ) ){
			$i--;
			continue;
		}
			
		$file_list[$i]['id'] = $db->f('idupl');
		$file_list[$i]['dirname'] = $db->f('dirname');
		$file_list[$i]['filename'] = $db->f('filename');
        $file_list[$i]['filetype'] = $db->f('filetype');
		$file_list[$i]['pic_width'] = $db->f('pictwidth');
		$file_list[$i]['pic_height'] = $db->f('pictheight');
		$file_list[$i]['pic_thumb_height'] = $db->f('pictthumbheight');
		$file_list[$i]['pic_thumb_width'] = $db->f('pictthumbwidth');
		$titel_temp = $db->f('titel');
		$file_list[$i]['titel'] = ($titel_temp == '') ? '': '[ ' . $db->f('titel') .' ]';
		$file_list[$i]['name'] = $file_list[$i]['dirname'] . $file_list[$i]['filename'] . $file_list[$i]['titel'];
		$subfolder_list[$i] = "C.dirname like '". $file_list[$i]['dirname'] ."%'";
	}
	return $file_list;
}

/**
 * Extract folderinfos in the light of maded  CMS:tag attributes settings folders and subfolders
 * This settings are in use in the CMS:tag image, file, wysiwyg...
 *
 * @Args:  string folders - iddirectories (comma seperated for some or empty for all folders)
 *         string subfolders - if true subdirectorys of folders will extracted too
 * @Return array [$i]['id'] - iddirectory from table cms_directory
 *               [$i]['dirname'] - full directory relative from $cms_client['upl_path']
 */
function _type_get_folders($folders = '', $subfolders = 'true')
{
	global $db, $cms_db, $cms_lang, $client, $perm;

	//Eventuell vorangehende Kommas aus mip-forms strippen
	if( substr($folders,0,1) == ',') $folders = substr($folders,1);

	$sql_folders = (empty($folders) || trim($folders) == 'true' ) ? '': ' AND C.iddirectory IN ('. $folders .') ';

	// user have rights to show subdirectorys, figure they out
	if (trim($subfolders) == 'true' && ! empty($sql_folders)) {
		$sql = "SELECT DISTINCT
					iddirectory, dirname
				FROM
					".$cms_db['directory']." C
				WHERE
					idclient= '$client' $sql_folders";
		$db -> query($sql);
		for($i=0; $db -> next_record(); $i++)
		{
			if(! $perm->have_perm( 1, 'folder', $db->f('iddirectory') ) ){
				$i--;
				continue;
			}
				
			$subfolder_list[$i] = "C.dirname like '". $db->f('dirname') ."%'";
		}
		$sql_folders = 'AND '.implode(' OR ', $subfolder_list);
	}
	$sql = "SELECT
				C.iddirectory, C.dirname
			FROM
				".$cms_db['directory']." C
			WHERE
				C.idclient= '$client'
				AND C.dirname NOT LIKE('cms/%')
				$sql_folders
			ORDER BY
				C.dirname";
	$db -> query($sql);
	//echo $sql;

	for($i=0; $db -> next_record(); $i++)
	{
		if(! $perm->have_perm( 1, 'folder', $db->f('iddirectory') ) ){
			$i--;
			continue;
		}
			
		$folder_list[$i]['id'] = $db->f('iddirectory');
		$folder_list[$i]['dirname'] = $db->f('dirname');
	}

	return $folder_list;
}



/**
 * Get values for new width and new height of an existing image.
 * One of the inputvalues $new_width and $new_height must be set or an
 * erreor occur. If you would like to set booth values, it's fine but
 * not necessary.
 *
 * @Args:  int original_width - width of the given image
 *         int original_height - height of the given image
 *         int new_width - new width
 *         int new_height - new height
 *         boolean aspectratio - calculate new imagesize by aspectratio
 * @Return array ['width'] - new width of an image
 *               ['height'] - new height of an image
 *         OR
 *         boolean false - if an error occur
 * @Access private
 */
function _type_calculate_new_image_size($original_width, $original_height, $new_width = '', $new_height = '', $aspectratio = false)
{
	if( empty($new_width) && empty($new_height)) return false;
	if( empty($original_width) ||  empty($original_height) ) return false;

	//picture is smaller as original source
	if ((int) $new_width > $original_width && (int) $new_height > $original_height) {
		$out['width'] = $original_width;
		$out['height'] = $original_height;
	//calculate without aspectratio
	} else if($aspectratio != 'true'){
		$out['width'] = ( empty($new_width) ) ? $original_width: $new_width;
		$out['height'] = ( empty($new_height) ) ? $original_height: $new_height;
	}
	//calculate with aspectratio
	else{
		$calculate_height_by_width = round( ($new_width / $original_width) * $original_height, 0);
		$calculate_width_by_height = round( ($new_height / $original_height) * $original_width, 0);
		//both values, new height and width are set. We must figure out if we resize by
		//height or by width
		if(! empty($new_width) && ! empty($new_height)){
			//resize by height
			if($calculate_height_by_width > $new_height){
				$out['width'] = $calculate_width_by_height;
				$out['height'] = $new_height;
			}
			//resize by width
			else{
				$out['width'] = $new_width;
				$out['height'] = $calculate_height_by_width;
			}
		}
		//by width
		else if( empty($new_height) ){
			$out['width'] = $new_width;
			$out['height'] = $calculate_height_by_width;
		}
		//by height
		else if( empty($new_width) ){
			$out['width'] = $calculate_width_by_height;
			$out['height'] = $new_height;
		}
		//how did you create this nonsens?
		else{
			return false;
		}
	}

	return $out;
}



/**
* Generate the menus for the cms:tag- elements
*
* @vars   array ids - the ids of the element taken from the idtype from the
*                     table cms_types
*		  array infos['container_number']
*                    ['cmstag_id']
*                    ['pre_compiled'] - full editstring, used by typegroup and edit_container
*                    ['mod_repeat_id']
*                    ['title']
*                    ['base_url']
*                    ['mode']
*         bool delete
*         bool new
*         bool up
*         bool down
*         bool inline
*/
function _type_get_layer_menu($ids, $infos, $delete, $new, $up, $down, $inline=false)
{
	global $p_menu, $cfg_cms, $mod_lang;
	
	if(! is_object($p_menu)){
		include_once($cfg_cms['cms_path'].'inc/class.popupmenubuilder_js.php');
		$p_menu = new popupmenubuilder_js();
		$p_menu->set_image('cms/img/but_edit.gif', 16, 16);
	}
	
	//generate sublink {typeid}-{mod_repeat_id},{typeid}-{mod_repeat_id}...


	//generate edit-sublink for normal contentelements, like, text, wysiwyg, image...
	if(empty($infos['pre_compiled'])){
		$subs = array();
		foreach($ids AS $v)
		{
			array_push($subs, $v.'-'.$infos['cmstag_id']);
		}
		$subs_to_string = implode(',', $subs);
	}
	//special content generation for typegroup and edit_container
	else{
		$subs_to_string = $infos['pre_compiled'];
		//container und repeat id, sind bei precompielt schon vorhanden und sind daher
		//später doppelt, müssen daher ersetzt werden  
		if($infos['tag_type'] == 'edit_container'){
			$to_replace = $infos['container_number'].'.'.$infos['mod_repeat_id'];
			$subs_to_string = substr ($subs_to_string,  strlen ($to_replace)+1);
		}
	}

	//generate menu
	$p_menu->add_title($infos['title']);

	if($inline)
		$p_menu->add_entry(
			$mod_lang['type_save'], $infos['base_url'], '_self', $mod_lang['type_save'],
				'con_setcontent('.$infos['container_number'].','.$infos['mod_repeat_id'].','
				.$ids['0'].','.$infos['cmstag_id'].');return;');

	if ($subs_to_string != '-')
	$p_menu->add_entry(
		$mod_lang['type_edit'],
		$infos['base_url'] .'&action=edit&content='.$infos['container_number'].'.'
		.$infos['mod_repeat_id'].'.'.$subs_to_string,'_self', $mod_lang['type_edit']);

	if($infos['mode'] == 'advanced'){
		if(($new || $delete || $up || $down) && (($subs_to_string != '-') || $inline)) $p_menu->add_seperator();
		if($new)
			$p_menu->add_entry(
				$mod_lang['type_new'], $infos['base_url'].'&action=new&entry='.$infos['mod_repeat_id'].'&content='
				.$infos['container_number'].'.new.'.$subs_to_string, '_self', $mod_lang['type_new']);
		if($delete)
			$p_menu->add_entry(
				$mod_lang['type_delete'], $infos['base_url'].'&action=delete&content='
				.$infos['container_number'].'.'.$infos['mod_repeat_id'], '_self', $mod_lang['type_delete'], 'if(delete_confirm())');
		if($up)
			$p_menu->add_entry(
				$mod_lang['type_up'], $infos['base_url'].'&action=move_up&content='
				.$infos['container_number'].'.'.$infos['mod_repeat_id'], '_self', $mod_lang['type_up']);
		if($down)
			$p_menu->add_entry(
				$mod_lang['type_down'], $infos['base_url'].'&action=move_down&content='
				.$infos['container_number'].'.'.$infos['mod_repeat_id'], '_self', $mod_lang['type_down']);
	}
	
	return $p_menu->get_menu_and_flush();
}

/**
* Catch vars and arrays from the cmstag attributes. Returns a string wich must 
* execute with eval() in the type-function, to get the dynamic values 
* 
*
* @vars   array type_config - cms:tag attributes
* @return string 
*/
function _type_get_dynamic_val_string($type_config)
{
	$to_eval = '';
	foreach($type_config AS $k=>$v){
		if(preg_match("/^\\$/", $v)){
			//globa delaration for array or single_var?
			preg_match("/^\\$([^\\[]*)/", $v, $my_global);
			$to_eval .= "global $".$my_global['1'].";\n";
			$to_eval .= '$type_config["'.$k.'"] = '.$v.';'."\n";
			//eval($to_eval);
			//$type_config[$k] = $extracted_var;
		}
	}
	return $to_eval;	
}
?>
