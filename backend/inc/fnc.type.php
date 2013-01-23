<?PHP
// File: $Id: fnc.type.php 67 2008-11-21 17:05:08Z bjoern $
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
// + Revision: $Revision: 67 $
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

include_once($cms_path.'inc/fnc.type_common.php');

/**
 * Frontendausgabe CMS:tag text
 *
 * @Args: int type_container -> id von cms:tag <cms:lay type="container" id="XX" />
 *        int type_number  -> Entspricht der Verdopplungsid eines Containers
 *        int type_typenumber -> Eindeutige Id des Contents
 *        array $type_config -> Attribute und deren Werte des cms:tags
 * @Return String Content
 * @Access public
 */
function type_output_text($type_container, $type_number, $type_typenumber, $type_config)
{
	global $sess, $cfg_cms, $cfg_client, $idcatside, $mod_lang, $content, $cms_side, $cms_edittype;
	global $con_side, $cms_mod;

	//catch vars and arrays from the tag attributes
	eval(_type_get_dynamic_val_string($type_config));

	// Content aus Array beziehen
	$mod_content = (is_array($content[$type_container][$type_number])) ? $content[$type_container][$type_number]['1'][$type_typenumber]['1'] : '';
	
	// HTML+PHP Tags modifizieren
	if($type_config['htmltags'] == 'convert' || empty($type_config['htmltags'])
  	   || ($type_config['htmltags'] != 'strip' && $type_config['htmltags'] != 'allow'))
		$mod_content = htmlspecialchars($mod_content, ENT_COMPAT, 'UTF-8');
	else if ($type_config['htmltags'] == 'strip')
		$mod_content = strip_tags($mod_content);

	// make global edit_all url
	if (_type_check_editall($cms_side, $type_config))
		$cms_edittype[$type_container][$type_number][] = '1-'.$type_typenumber;

	// Editiermodus
	if (_type_check_editable($cms_side['edit'], $type_config['editable'], $cms_side['view'],
			isset($cms_side['edit_all'])) && $type_config['menuoptions'] != 'false') {

		$title = (empty($type_config['title'])) ? $mod_lang['type_text'] : $type_config['title'];
		// advanced Modus
		if ($type_config['menuoptions'] == 'advanced') {
			$new=false; $delete=false; $up= false; $down=false;
			// neu anlegen & loeschen
			if ($type_number != '1' || $mod_content != '') {
	            $new = true;
	            $delete = true;
			}
			// nach oben verschieben
			if ($type_number != '1') $up = true;
			// nach unten verschieben
			if ($mod_content != '' && $cms_mod['modul']['lastentry'] != 'true') $down = true;
		}

        $ids[] = 1;
		$infos = array(
                    'container_number' => $type_container,
                    'cmstag_id' => $type_typenumber,
                    'mod_repeat_id' => $type_number,
                    'title' => $title,
                    'base_url' => $con_side[$idcatside]['link'],
                    'mode' => $type_config['menuoptions']
				);
		//menu erstellen
		$mod = _type_get_layer_menu($ids, $infos, $delete, $new, $up, $down);


		//Wenn nur editbutton gefordert - Ausgabe BACKEND
		if($type_config['mode'] == 'editbutton') return $mod;
		//Content mit Editbutton erzeugen
		$mod = $mod_content . $mod;

	}
	else $mod = $mod_content;
	
	//Frontend: wenn Editbutton
	if($type_config['mode'] == 'editbutton') return;

	//Style
	$css = _type_get_style($type_config['styleclass'], $type_config['styleid'], $type_config['styledb']);
	$css['type'] = trim($css['type']);
	if (!empty($css['type']))
		$mod = '<span '. $css['fullstyle'] .'>'.$mod.'</span>';

	
	return $mod;
}

/**
 * Frontendausgabe CMS:tag textarea
 *
 * @Args: int type_container -> id von cms:tag <cms:lay type="container" id="XX" />
 *        int type_number  -> Entspricht der Verdopplungsid eines Containers
 *        int type_typenumber -> Eindeutige Id des Contents
 *        array $type_config -> Attribute und deren Werte des cms:tags
 * @Return String Content
 * @Access public
 */
function type_output_textarea($type_container, $type_number, $type_typenumber, $type_config) {
	global $sess, $cfg_cms, $cfg_client, $idcatside, $mod_lang, $content, $cms_side;
	global $cms_edittype, $cms_mod, $con_side, $cms_path, $core_bbcode;

	//catch vars and arrays from the tag attributes
	eval(_type_get_dynamic_val_string($type_config));

	// Content aus Array holen
	$mod_content = (is_array($content[$type_container][$type_number])) ? $content[$type_container][$type_number]['3'][$type_typenumber]['1'] : '';

	// HTML Tags maskieren falls htmltags=convert
	if($type_config['htmltags'] == 'convert' || empty($type_config['htmltags']) || ($type_config['htmltags'] != 'strip' && $type_config['htmltags'] != 'allow')) $mod_content = htmlspecialchars($mod_content, ENT_COMPAT, 'UTF-8');

	// nl2br
	if ($type_config['nl2br'] == 'true' || empty($type_config['nl2br'])) {
		$mod_content = nl2br($mod_content);
	}

	// bbcode
	if ($type_config['transform'] == 'bbcode') {
		include_once($cms_path.'inc/class.core_bbcode.php');
		if (!is_object($core_bbcode)) $core_bbcode = new core_bbcode();
		$mod_content = $core_bbcode->parse_bbcode($mod_content);
	}

	// alle Tags entfernen
	if($type_config['htmltags'] == 'strip') {
		// wenn nl2br aktiviert, <br>- tags duerfen nicht gestrippt werden
		if ($type_config['nl2br'] == 'true' || empty($type_config['nl2br'])) {
		  $mod_content = str_replace('<br />', '{cms_temp_break}', $mod_content);
		  $mod_content = strip_tags($mod_content);
		  $mod_content = str_replace('{cms_temp_break}', '<br />', $mod_content);
		} else $mod_content = strip_tags($mod_content);
	}

	// make global edit_all url
	if (_type_check_editall($cms_side, $type_config))
		$cms_edittype[$type_container][$type_number][] = '3-'.$type_typenumber;

	// Editiermodus
	if (_type_check_editable($cms_side['edit'], $type_config['editable'], $cms_side['view'],
			isset($cms_side['edit_all'])) && $type_config['menuoptions'] != 'false') {

		$title = (empty($type_config['title'])) ? $mod_lang['type_textarea'] : $type_config['title'];

		// advanced Modus
		if ($type_config['menuoptions'] == 'advanced') {
			$new=false; $delete=false; $up= false; $down=false;
			// neu anlegen & loeschen
			if ($type_number != '1' || $mod_content != '') {
	            $new = true;
	            $delete = true;
			}
			// nach oben verschieben
			if ($type_number != '1') $up = true;
			// nach unten verschieben
			if ($mod_content != '' && $cms_mod['modul']['lastentry'] != 'true') $down = true;
		}

        $ids[] = 3;
		$infos = array(
                    'container_number' => $type_container,
                    'cmstag_id' => $type_typenumber,
                    'mod_repeat_id' => $type_number,
                    'title' => $title,
                    'base_url' => $con_side[$idcatside]['link'],
                    'mode' => $type_config['menuoptions']
				);
		//menu erstellen
		$mod = _type_get_layer_menu($ids, $infos, $delete, $new, $up, $down);

		//Wenn nur editbutton gefordert - Ausgabe BACKEND
		if($type_config['mode'] == 'editbutton') return $mod;
		//Content mit Editbutton erzeugen
		$mod = $mod_content . $mod;

	} else $mod = $mod_content;
    
    //Frontend: wenn Editbutton
	if($type_config['mode'] == 'editbutton') return;
    
	// Style
	$css = _type_get_style($type_config['styleclass'], $type_config['styleid'], $type_config['styledb']);
	$css['type'] = trim($css['type']);
	if (!empty($css['type'])) $mod = '<span '.$css['fullstyle'].'>'.$mod.'</span>';
	return $mod;
}


/**
 * Frontendausgabe CMS:tag WYSIWYG
 *
 * @Args: int type_container -> id von cms:tag <cms:lay type="container" id="XX" />
 *        int type_number  -> Entspricht der Verdopplungsid eines Containers
 *        int type_typenumber -> Eindeutige Id des Contents
 *        array $type_config -> Attribute und deren Werte des cms:tags
 * @Return String Content
 * @Access public
 */
function type_output_wysiwyg($type_container, $type_number, $type_typenumber, $type_config)
{
	global $sess, $cfg_cms, $cfg_client, $idcatside, $mod_lang, $content, $cms_side, $sid_sniffer;
	global $cms_edittype, $cms_mod, $con_side, $lang;

	$unique_type_id = 2;

	//catch vars and arrays from the tag attributes
	eval(_type_get_dynamic_val_string($type_config));

	return _type_output_wysiwyg_common($type_container, $type_number, $type_typenumber, $type_config, $unique_type_id);
}

/**
 * Frontendausgabe CMS:tag WYSIWYG2
 *
 * @Args: int type_container -> id von cms:tag <cms:lay type="container" id="XX" />
 *        int type_number  -> Entspricht der Verdopplungsid eines Containers
 *        int type_typenumber -> Eindeutige Id des Contents
 *        array $type_config -> Attribute und deren Werte des cms:tags
 * @Return String Content
 * @Access public
 */
function type_output_wysiwyg2($type_container, $type_number, $type_typenumber, $type_config)
{
	global $sess, $cfg_cms, $cfg_client, $idcatside, $mod_lang, $content, $cms_side, $sid_sniffer;
	global $cms_edittype, $cms_mod, $con_side, $lang;

	$unique_type_id = 13;

	//catch vars and arrays from the tag attributes
	eval(_type_get_dynamic_val_string($type_config));

	return _type_output_wysiwyg_common($type_container, $type_number, $type_typenumber, $type_config, $unique_type_id);
}

/**
 * Aufbereitung Content fuer Frontendausgabe WYSIWYG1 und WYSIWYG2. Da sich beide Editoren von
 * der Frontendausgabe nicht unterscheiden, koennen Sie zusammengefasst werden
 *
 * @Args: int type_container -> id von cms:tag <cms:lay type="container" id="XX" />
 *        int type_number  -> Entspricht der Verdopplungsid eines Containers
 *        int type_typenumber -> Eindeutige Id des Contents
 *        array $type_config -> Attribute und deren Werte des cms:tags
 * @Return String Content
 * @Access private
 */
function _type_output_wysiwyg_common($type_container, $type_number, $type_typenumber, $type_config, $unique_type_id)
{
	global $sess, $cfg_cms, $cfg_client, $idcatside, $mod_lang, $content, $cms_side, $sid_sniffer;
	global $cms_edittype, $cms_mod, $con_side, $con_tree, $db, $cms_db, $client, $lang;

	// Content holen
	$mod_content = (is_array($content[$type_container][$type_number])) ? $content[$type_container][$type_number][$unique_type_id][$type_typenumber]['1'] : '';
	$mod_content = rawurldecode($mod_content);

	// Text formatieren
	$mod_content = str_replace('<o:p>', '<p>', $mod_content);
	$mod_content = str_replace('</o:p>', '</p>', $mod_content);
	$mod_content = str_replace('<?', '<', $mod_content);
	$mod_content = str_replace('<xml>', '', $mod_content);
	$mod_content = str_replace('<xml:>', '', $mod_content);
	if ($type_config['striptags'] == 'true') $mod_content = strip_tags($mod_content);
	if (stristr ($type_config['striptags'], 'styletags')) $mod_content = preg_replace("/([\s\'\"])style=[^>]+/i", ' ', $mod_content);
	if (stristr ($type_config['striptags'], 'styleclasses')) $mod_content = preg_replace("/([\s\'\"])class=[^>]+/i", ' ', $mod_content);
	if (stristr ($type_config['striptags'], 'styleids')) $mod_content = preg_replace("/([\s\'\"])id=[^>]+/i", ' ', $mod_content);
	if (stristr ($type_config['striptags'], 'fontfaces')) $mod_content = preg_replace("/([\s\'\"])face=[^>]+/i", ' ', $mod_content);
	if (stristr ($type_config['striptags'], 'fontsizes')) $mod_content = preg_replace("/([\s\'\"])size=[^>]+/i", ' ', $mod_content);
	if (stristr ($type_config['striptags'], 'events')) $mod_content = preg_replace("/([\s\'\"])on[a-z]+=[^>]+/i", ' ', $mod_content);
	if ($type_config['tidyhtml'] != 'false') {
		$mod_content = preg_replace('/\s+/', ' ', $mod_content);
		$mod_content = preg_replace("!<p [^>]*BodyTextIndent[^>]*>([^\n|\n15|15\n]*)</p>!i",'<p>\\1</p>', $mod_content);
		$mod_content = preg_replace("!<p [^>]*margin-left[^>]*>([^\n|\n15|15\n]*)</p>!i",'<blockquote>\\1</blockquote>', $mod_content);
		$mod_content = preg_replace("!</?(html|body|xml:|[a-z]:)[^>]*>!i", '', $mod_content);
		$mod_content = preg_replace("!(<p></p>)!i", "<p />\n", $mod_content);
		$mod_content = preg_replace("/<![^>]*>/i", '', $mod_content);
		$mod_content = preg_replace('/\s+/', ' ', $mod_content);
		$mod_content = str_replace('<br>',"<br />\r", $mod_content);
		$mod_content = str_replace('> <', ">\r<", $mod_content);
	}

	// make global edit_all url
	if (_type_check_editall($cms_side, $type_config)) $cms_edittype[$type_container][$type_number][] = $unique_type_id . '-'.$type_typenumber;

	// Editiermodus
	if (_type_check_editable($cms_side['edit'], $type_config['editable'], $cms_side['view'], isset($cms_side['edit_all'])) && $type_config['menuoptions'] != 'false') {

		  $title = (empty($type_config['title'])) ? $mod_lang['type_wysiwyg'] : $type_config['title'];

		//extract sniffer vars
		if (!$tmp_sniffer) $tmp_sniffer = explode(',', $sid_sniffer);
		//build menu
		//inline editing?
		$inline = ($tmp_sniffer['18'] == 'true' || $tmp_sniffer['9'] == 'true') ? true: false;

		// advanced Modus
		if ($type_config['menuoptions'] == 'advanced') {
			$new=false; $delete=false; $up= false; $down=false;
			// neu anlegen & loeschen
			if ($mod_content != '') {
	            $new = true;
	            $delete = true;
			}
			// nach oben verschieben
			if ($type_number != '1') $up = true;
			// nach unten verschieben
			if ($mod_content != '' && $cms_mod['modul']['lastentry'] != 'true') $down = true;
		}

        $ids = array($unique_type_id);
		$infos = array(
                    'container_number' => $type_container,
                    'cmstag_id' => $type_typenumber,
                    'mod_repeat_id' => $type_number,
                    'title' => $title,
                    'base_url' => $con_side[$idcatside]['link'],
                    'mode' => $type_config['menuoptions']
				);
		//menu erstellen
		$layermenu = _type_get_layer_menu($ids, $infos, $delete, $new, $up, $down, $inline);

		//IE + mozilla inline mode
		if ($tmp_sniffer['18'] == 'true' || $tmp_sniffer['9'] == 'true') { 
			
			$in = array("!cms://idfile=(\d+)!",
					"!cms://idfilethumb=(\d+)!",
				    "!cms://idcat=(\d+)!",
				    "!cms://idcatside=(\d+)!");
			$out = array("cms://temp_idfile=\\1",
				         "cms://temp_idfilethumb=\\1",
				         "cms://temp_idcat=\\1",
				         "cms://temp_idcatside=\\1");
			
			$mod_content = preg_replace($in, $out, $mod_content);
			//Mozilla braucht midestens ein "&nbsp;" um auf ein editdiv zugreifen zu können/damit dort das editieren funktioniert
			$content_to_edit = (empty($mod_content) && $tmp_sniffer['9'] == 'true') ? '&nbsp;':$mod_content;

			$mod = "<form name=\"editform_".$type_container.'_'.$type_number.'_'.$unique_type_id.'_'.$type_typenumber."\" method=\"post\" action=\"".$sess->url($cfg_client['contentfile']."?lang=$lang&view=edit&action=savex&idcatside=$idcatside")."\">\n".
		                 "<input type=\"hidden\" name=\"data\" value=\"\">\n";
			$mod .= "<div id=\"content_".$type_container.'_'.$type_number.'_'.$unique_type_id.'_'.$type_typenumber."\" onfocus=\"document.getElementById('content_".$type_container.'_'.$type_number.'_'.$unique_type_id.'_'.$type_typenumber."').className ='actEditFrame';\"".
			"onblur=\"document.getElementById('content_".$type_container.'_'.$type_number.'_'.$unique_type_id.'_'.$type_typenumber."').className ='editFrame';\" contentEditable=\"true\" class=\"editFrame\">$content_to_edit</div><div align=\"right\">$layermenu</div></form>";
		} else {
			$mod = $mod_content . $layermenu;
		}

	} else {
		$mod = $mod_content;
	}
	// Style
	$css = _type_get_style($type_config['styleclass'], $type_config['styleid'], $type_config['styledb']);
	$css['type'] = trim($css['type']);
	if (!empty($css['type'])) $mod = '<span '.$css['fullstyle'].'>'.$mod.'</span>';

	return $mod;
}


/**
 * Frontendausgabe CMS:tag image
 *
 * @Args: int type_container -> id von cms:tag <cms:lay type="container" id="XX" />
 *        int type_number  -> Entspricht der Verdopplungsid eines Containers
 *        int type_typenumber -> Eindeutige Id des Contents
 *        array $type_config -> Attribute und deren Werte des cms:tags
 * @Return String Content
 * @Access public
 */
function type_output_image($type_container, $type_number, $type_typenumber, $type_config) {
	global $sess, $DB_cms, $cms_db, $cfg_cms, $cfg_client, $idcatside, $mod_lang, $content, $cms_side;
	global $client, $cms_edittype, $cms_mod, $con_side;

	//catch vars and arrays from the tag attributes
	eval(_type_get_dynamic_val_string($type_config));


	// URL formatieren
	$mod_content = (is_array($content[$type_container][$type_number])) ? $content[$type_container][$type_number]['4'][$type_typenumber]['1'] : '';
	$match = array();
	if (preg_match_all('#^cms://(idfile|idfilethumb)=(\d+)$#', $mod_content, $match) ) {
		$is_thumb = $match['1']['0'] == 'idfilethumb'; 
		$id = $match['2']['0'];
		
		$sql = "SELECT 
					A.*, B.filetype, C.dirname 
				FROM 
					". $cms_db['upl'] ." A 
					LEFT JOIN ". $cms_db['filetype'] ." B USING(idfiletype) 
					LEFT JOIN ". $cms_db['directory'] ." C ON A.iddirectory=C.iddirectory 
				WHERE 
					A.idclient='$client' 
					AND idupl='".$id."'";
					
		$db =& new DB_cms;
		$db->query($sql);
		if ($db->next_record()){
			$mod_url = $cfg_client['upl_htmlpath'].$db->f('dirname').$db->f('filename');
			$mod_path = $cfg_client['upl_path'].$db->f('dirname').$db->f('filename');
			$fileid = $db->f('idupl');
			$original_x = $db->f('pictwidth');
			$original_y = $db->f('pictheight');
			$pic_filetype = $db->f('filetype');
			$pic_dirname = $db->f('dirname');
			$pic_filename = $db->f('filename');
			$pic_idfiletype = $db->f('idfiletype');
			$pic_iddirectory = $db->f('iddirectory');
			$thumb_x = $db->f('pictthumbwidth');
			$thumb_y = $db->f('pictthumbheight');
			$pic_db_desc = $db->f('description');
			$pic_db_titel = $db->f('titel');
			$file_size = $db->f('filesize');
			
			if ( ( in_array($type_config['mode'], array('thumb', 'thumbamplitude', 'thumbwidth', 'thumbheight', 'thumburl', 'thumbpath', ) ) 
					&& $pic_filetype != 'gif' && ($thumb_x != 0 || $thumb_y != 0) )
					|| $is_thumb) {
				$original_x = $thumb_x;
				$original_y = $thumb_y;
				$name_length = strlen($pic_filename);
				$extension_length = strlen($pic_filetype);
				$new_name = substr ($pic_filename, 0, ($name_length - $extension_length - 1) );
				$new_name .= $cfg_client['thumbext'].'.'. $pic_filetype;
				$mod_url = $cfg_client['upl_htmlpath'].$db->f('dirname').$new_name;
				$mod_path = $cfg_client['upl_path'].$db->f('dirname').$new_name;
				$pic_filename = $new_name;

			}
		}
	}
 
	if ($type_config['autoresize'] == 'true' && ! empty($mod_url)) {
		// Extract needed data
		$is_aspectratio = ($type_config['aspectratio'] != 'false') ? true:false;
		$extracted_size = _type_calculate_new_image_size($original_x, $original_y, $type_config['width'], $type_config['height'], $is_aspectratio);
		$new_x = $extracted_size['width'];
		$new_y = $extracted_size['height'];

		// Skip transform if new image have the same properties as the original image,
		if($new_x == $original_x && $new_y == $original_y){
			$skip_transform = true;
		}

		// If _type_calculate_new_image_size(..) throwed an error, new_x and new_y are not set, skip transform
		if(! empty($new_x) && ! empty($new_y) ){
			$name_length = strlen($pic_filename);
			$extension_length = strlen($pic_filetype);

			// New filename for image
			$new_name = substr ($pic_filename, 0, ($name_length - $extension_length - 1) );
			$new_name .= '_'. $new_x .'X'. $new_y .'.'. $pic_filetype;
			if( file_exists($cfg_client['upl_path'] . $pic_dirname . $new_name) ){

				// manipulate current CMS:tag values with new transform values
				$mod_url = $cfg_client['upl_htmlpath'] . $pic_dirname . $new_name;
				$mod_path = $cfg_client['upl_path'].$pic_dirname . $new_name;
				$type_config['width'] = $original_x = $new_x;
				$type_config['height'] = $original_y = $new_y;
			} else if ($skip_transform || ($cfg_cms['image_mode'] == 'gd' && $pic_filetype == 'gif') ) {
				$type_config['width'] =  $original_x = $new_x;
				$type_config['height'] = $original_y = $new_y;
			} else {
				// Create image only if file doesn't exist, isn't GD with filetype gif
				// and imagedriver isn't empty
				if (!($cfg_cms['image_mode'] == 'gd' && $pic_filetype == 'gif' ) && ! empty($cfg_cms['image_mode'])) {
					// IMAGE TRANSFORM
					global $cms_image;
					require_once 'Image/Transform.php';
                    $cms_image = ( is_object($cms_image) ) ? $cms_image: Image_Transform::factory( $cfg_cms['image_mode'] );
                    $cms_image -> load($mod_path);
					$cms_image -> resize($new_x, $new_y);
					$cms_image->save($cfg_client['upl_path'] . $pic_dirname . $new_name);
					$cms_image->free();
                    global $fm;
					$fm -> insert_file((int)$client, $new_name, (int) $pic_iddirectory, (int) $pic_idfiletype);

					//manipulate current CMS:tag values with new transform values
					$mod_url = $cfg_client['upl_htmlpath'] . $pic_dirname . $new_name;
					$mod_path = $cfg_client['upl_path'].$pic_dirname . $new_name;
					$type_config['width'] =  $original_x = $new_x;
					$type_config['height'] =  $original_y = $new_y;
				}
			}
		}
	}

	// Wenn amplitude angefordert - Ausgabe FRONTEND + BACKEND
	if ($type_config['mode'] == 'amplitude' || $type_config['mode'] == 'thumbamplitude') return 'width="'. $original_x .'" height="'. $original_y . '"';

	// Wenn nur filesize gefordert, Ausgabe - FRONTEND und BACKEND
	if ($type_config['mode'] == 'filesize') return $file_size;
	
	// Wenn nur id der Datei gefordert, Ausgabe - FRONTEND und BACKEND
	if ($type_config['mode'] == 'id') return $fileid;

	// Wenn dateimanager titel angefordert - Ausgabe FRONTEND + BACKEND
	if ($type_config['mode'] == 'fmtitle') return $pic_db_titel;

	// Wenn dateimanager beschreibung angefordert - Ausgabe FRONTEND + BACKEND
	if ($type_config['mode'] == 'fmdesc') return $pic_db_desc;

	// Wenn Filetype angefordert - Ausgabe FRONTEND + BACKEND
	if ($type_config['mode'] == 'filetype') return $pic_filetype;

	// Wenn Bildhöhe angefordert - Ausgabe FRONTEND + BACKEND
	if ($type_config['mode'] == 'width' || $type_config['mode'] == 'thumbwidth') return $original_x;

	// Wenn Bildweite angefordert - Ausgabe FRONTEND + BACKEND
	if ($type_config['mode'] == 'height' || $type_config['mode'] == 'thumbheight') return $original_y;

	// Wenn nur url gefordert - Ausgabe FRONTEND + BACKEND
	if ($type_config['mode'] == 'url' || $type_config['mode'] == 'thumburl') return $mod_url;

	// Wenn nur pfad gefordert - Ausgabe FRONTEND + BACKEND
	if ($type_config['mode'] == 'path' || $type_config['mode'] == 'thumbpath') return $mod_path;

	// Beschreibung raussuchen
	$mod_descr = (is_array($content[$type_container][$type_number])) ? htmlspecialchars($content[$type_container][$type_number]['5'][$type_typenumber]['1'], ENT_COMPAT, 'UTF-8') : '';

	// Wenn nur Beschreibung gefordert - Ausgabe FRONTEND + BACKEND
	if ($type_config['mode'] == 'desc') return $mod_descr;

	// Bildgroesse ermitteln
	// Wenn defaultimage
	if (empty($mod_url)) {
		$pic_width  = (!empty($type_config['defaultwidth'])) ? $type_config['defaultwidth'] : $type_config['width'];
		$pic_height = (!empty($type_config['defaultheight'])) ? $type_config['defaultheight'] : $type_config['height'];

	// wenn schon ausgewaehltes Bild
	} else {
		$pic_width  = (empty($type_config['width']) ) ? '': $type_config['width'];
		$pic_height = (empty($type_config['height']) ) ? '': $type_config['height'];

	// Bildgroesse soll automatisch ermittelt werden
	} if( (empty($type_config['width']) || $type_config['width'] == 'true' || empty($type_config['height']) || $type_config['height'] == 'true') && ! empty($mod_url)) {

		//Bildweite aus der DB
		if($type_config['width'] == 'true' || empty($type_config['width'])) $pic_width = $original_x ;

		//Bildhoehe aus der DB
		if($type_config['height'] == 'true' || empty($type_config['height'])) $pic_height = $original_y ;
	}

	// defaultimage, wenn nicht gesetzt dann transparentes bild einsetzen
	if ($type_config['defaultimage']) $mod_def = $type_config['defaultimage'];
	else $mod_def = $cfg_client['space'];
	if (!$mod_url) $mod_url = $mod_def;

	// make global edit_all url
	if(_type_check_editall($cms_side, $type_config)) $cms_edittype[$type_container][$type_number][] = '4-'.$type_typenumber.',5-'.$type_typenumber;

	// CSS ausfindig machen
	$css = _type_get_style($type_config['styleclass'], $type_config['styleid'], $type_config['styledb']);

	// Wenn style angefordert oder default
	if($type_config['mode'] == 'style') return $css['style'];

	// Wenn styletype angefordert oder default
	if($type_config['mode'] == 'styletype')	return $css['type'];

	// Wenn fullstyle angefordert oder default
	if($type_config['mode'] == 'fullstyle') return $css['fullstyle'];

	// Bild generieren
	if (_type_check_editable($cms_side['edit'], $type_config['editable'], $cms_side['view'], isset($cms_side['edit_all']))) {

		// Bearbeitungsbutton / layer erzeugen
		if($type_config['menuoptions'] != 'false'){
			$title = (empty($type_config['title'])) ? $mod_lang['type_image'] : $type_config['title'];
			// advanced Modus
			if ($type_config['menuoptions'] == 'advanced') {
				$new=false; $delete=false; $up= false; $down=false;
				// neu anlegen & loeschen
				if ($type_number != '1' || $mod_url != $mod_def) {
		            $new = true;
		            $delete = true;
				}
				// nach oben verschieben
				if ($type_number != '1') $up = true;
				// nach unten verschieben
				if ($mod_url != $mod_def && $cms_mod['modul']['lastentry'] != 'true') $down = true;
			}

	        $ids = array(4,5);
			$infos = array(
	                    'container_number' => $type_container,
	                    'cmstag_id' => $type_typenumber,
	                    'mod_repeat_id' => $type_number,
	                    'title' => $title,
	                    'base_url' => $con_side[$idcatside]['link'],
	                    'mode' => $type_config['menuoptions']
					);

			//menu erstellen
			$editbutton_image = _type_get_layer_menu($ids, $infos, $delete, $new, $up, $down);
			//Wenn nur editbutton gefordert - Ausgabe BACKEND
			if($type_config['mode'] == 'editbutton') return $editbutton_image;
		}//

        if($type_config['menuoptions'] != 'false'){
			$mod_content = sprintf("<img src=\"$mod_url\"%s%s%s ". $css['fullstyle'] ." />", ($mod_descr != '') ? ' alt="'.$mod_descr.'" title="'.$mod_descr.'"' : " alt=\"\"", ($pic_width) ? ' width="'.($pic_width-29).'"' : '', ($pic_height) ? ' height="'.($pic_height-8).'"' : '');
			$mod = '<span style="background-color: #DBE3EF; border: 1px solid black; padding: 3px;">'.$mod_content.'<span style="padding-left: 3px;">'.$editbutton_image.'</span></span>';
			$mod = $editbutton.$mod;
		}
		else{
			if ($type_config['mode'] == 'editbutton') return;
			return sprintf("<img src=\"$mod_url\"%s%s%s ". $css['fullstyle'] ." />", ($mod_descr != '') ? ' alt="'.$mod_descr.'" title="'.$mod_descr.'"' : " alt=\"\"", ($pic_width) ? ' width="'.$pic_width.'"' : '', ($pic_height) ? ' height="'.$pic_height.'"' : '');
		}
	} else {
		// Wenn nur editbutton gefordert - keine Ausgabe Frontend
		if ($type_config['mode'] == 'editbutton') return;

		// Ausgabe image - Frontend
	  $mod = sprintf("<img src=\"$mod_url\"%s%s%s ". $css['fullstyle'] ." />", ($mod_descr != '') ? ' alt="'.$mod_descr.'" title="'.$mod_descr.'"' : " alt=\"\"", ($pic_width) ? ' width="'.$pic_width.'"' : '', ($pic_height) ? ' height="'.$pic_height.'"' : '');
	}
	return $mod;
}

/**
 * Frontendausgabe CMS:tag link
 *
 * @Args: int type_container -> id von cms:tag <cms:lay type="container" id="XX" />
 *        int type_number  -> Entspricht der Verdopplungsid eines Containers
 *        int type_typenumber -> Eindeutige Id des Contents
 *        array $type_config -> Attribute und deren Werte des cms:tags
 * @Return String Content
 * @Access public
 */
function type_output_link($type_container, $type_number, $type_typenumber, $type_config)
{
	global $sess, $DB_cms, $cms_db, $cfg_cms, $cfg_client, $idcatside, $mod_lang, $content, $cms_side;
	global $client, $cms_edittype, $cms_mod, $con_side;

	//catch vars and arrays from the tag attributes
	eval(_type_get_dynamic_val_string($type_config));

	// Linkurl extrahieren
	$link_url = (is_array($content[$type_container][$type_number])) ? $content[$type_container][$type_number]['6'][$type_typenumber]['1'] : '';
	$match = array();
	if (preg_match_all('#^cms://(idcatside|idcat)=(\d+)$#', $link_url, $match) ) {
		$is_page = $match['1']['0'] == 'idcatside'; 
		$id = $match['2']['0']; 
		if ($is_page) {
			$link_url = "cms://idcatside=".$id;
		} else {
			$link_url = "cms://idcat=".$id;
		}
		 
	} //else if (substr($link_url,0,7) != 'http://' && $link_url != '0' && ! empty($link_url)) {
	  else if (! preg_match('#^(http|https|mailto|ftp)://(.*)$#i', $link_url) && $link_url != '0' && ! empty($link_url)) {
		$link_url = 'http://'.$link_url;
	}

	// Wenn nur URL gefordert, Ausgabe - FRONTEND und BACKEND
	if ($type_config['mode'] == 'url') {
		if($link_url == '0') return;
		else return $link_url;
	}

	// Linktarget extrahieren
	$link_target = (is_array($content[$type_container][$type_number])) ? $content[$type_container][$type_number]['8'][$type_typenumber]['1'] : '';

	// Wenn nur target gefordert, Ausgabe - FRONTEND und BACKEND
	if ($type_config['mode'] == 'target') return $link_target;

	// Linkbeschreibung extrahieren
	$link_desc = (is_array($content[$type_container][$type_number])) ? htmlspecialchars($content[$type_container][$type_number]['7'][$type_typenumber]['1'], ENT_COMPAT, 'UTF-8') : '';

	// Wenn nur Beschreibung gefordert, Ausgabe - FRONTEND und BACKEND
	if ($type_config['mode'] == 'desc') return $link_desc;

	// make global edit_all url
	if (_type_check_editall($cms_side, $type_config)) $cms_edittype[$type_container][$type_number][] = '6-'.$type_typenumber.',7-'.$type_typenumber.',8-'.$type_typenumber;

	//Editbutton erzeugen fuer BACKEND
	if (_type_check_editable($cms_side['edit'], $type_config['editable'], $cms_side['view'],
			isset($cms_side['edit_all'])) && $type_config['menuoptions'] != 'false') {

		$title = (empty($type_config['title'])) ? $mod_lang['type_link'] : $type_config['title'];

		// advanced Modus
		if ($type_config['menuoptions'] == 'advanced') {
			$new=false; $delete=false; $up= false; $down=false;
			// neu anlegen & loeschen
			if (type_number != '1' || !empty($link_url)) {
	            $new = true;
	            $delete = true;
			}
			// nach oben verschieben
			if ($type_number != '1') $up = true;
			// nach unten verschieben
			if (!empty($link_url) && $cms_mod['modul']['lastentry'] != 'true') $down = true;
		}

        $ids = array(6,7,8);
		$infos = array(
                    'container_number' => $type_container,
                    'cmstag_id' => $type_typenumber,
                    'mod_repeat_id' => $type_number,
                    'title' => $title,
                    'base_url' => $con_side[$idcatside]['link'],
                    'mode' => $type_config['menuoptions']
				);
		//menu erstellen
		$layer_menu = _type_get_layer_menu($ids, $infos, $delete, $new, $up, $down);

		// Wenn Editbutton angefordert wurde, ausgeben - BACKEND
		if($type_config['mode'] == 'editbutton') return $layer_menu;
	}

	// Vollstaendigen Link bauen
	// CSS ausfindig machen
	$css = _type_get_style($type_config['styleclass'], $type_config['styleid'], $type_config['styledb']);

	// Wenn style angefordert oder default
	if ($type_config['mode'] == 'style') return $css['style'];

	// Wenn styletype angefordert oder default
	if ($type_config['mode'] == 'styletype') return $css['type'];

	// Wenn fullstyle angefordert oder default
	if ($type_config['mode'] == 'fullstyle') return $css['fullstyle'];

	$target = (empty($link_target)) ? '':'target="'. $link_target .'"';
	$desc = (empty($link_desc)) ? $link_url : $link_desc;
  $link_textlink = '<a href="'.$link_url.'" '. $css['fullstyle'] .'  '. $target .' >'. $desc .'</a>';

	// Wenn textlink angefordert oder default FRONTEND und BACKEND
	if (($type_config['mode'] == 'textlink' || empty($type_config['mode'])) ) {
		if (!empty($link_url) && $link_url != '0') return $link_textlink . $layer_menu;
		else return $layer_menu;
	}
}

/**
 * Frontendausgabe CMS:tag file
 *
 * @Args: int type_container -> id von cms:tag <cms:lay type="container" id="XX" />
 *        int type_number  -> Entspricht der Verdopplungsid eines Containers
 *        int type_typenumber -> Eindeutige Id des Contents
 *        array $type_config -> Attribute und deren Werte des cms:tags
 * @Return String Content
 * @Access public
 */
function type_output_file($type_container, $type_number, $type_typenumber, $type_config)
{
	global $sess, $DB_cms, $cms_db, $cfg_cms, $cfg_client, $idcatside, $mod_lang, $content, $cms_side;
	global $client, $cms_edittype, $cms_mod, $con_side;

	//catch vars and arrays from the tag attributes
	eval(_type_get_dynamic_val_string($type_config));

	// Fileid extrahieren
	$file_id = (is_array($content[$type_container][$type_number])) ? $content[$type_container][$type_number]['10'][$type_typenumber]['1'] : '';


	// Filebeschreibung extrahieren
	$file_desc = (is_array($content[$type_container][$type_number])) ? htmlspecialchars($content[$type_container][$type_number]['11'][$type_typenumber]['1'], ENT_COMPAT, 'UTF-8') : '';

	// Wenn nur Beschreibung gefordert, Ausgabe - FRONTEND und BACKEND
	if($type_config['mode'] == 'desc') return $file_desc;

	// Filetarget extrahieren
	$file_target = (is_array($content[$type_container][$type_number])) ? $content[$type_container][$type_number]['12'][$type_typenumber]['1'] : '';

	// Wenn nur target gefordert, Ausgabe - FRONTEND und BACKEND
	if($type_config['mode'] == 'target') return $file_target;

	//Benoetigte Dateiinformationen aus der Datenbank holen
	$match = array();
	if (preg_match_all('#^cms://(idfile|idfilethumb)=(\d+)$#', $file_id, $match) ) {
		$is_thumb = $match['1']['0'] == 'idfilethumb'; 
		$id = $match['2']['0'];
		
		// Wenn idupl gefordert, Ausgabe - FRONTEND, BACKEND
		if($type_config['mode'] == 'id') return $id;
	
		$db =& new DB_cms;
		$sql = "SELECT
					A.*, B.filetype, C.dirname
				FROM
					".$cms_db['upl']." AS A
					LEFT JOIN ". $cms_db['filetype'] ." B USING(idfiletype)
					LEFT JOIN ". $cms_db['directory'] ." C ON A.iddirectory=C.iddirectory
				WHERE
					A.idclient='$client'
					AND idupl='".$id."'";
		$db->query($sql);
		if ($db->next_record()) {			
			$file_url = $cfg_client['upl_htmlpath'].$db->f('dirname').$db->f('filename');
			$file_path = $cfg_client['upl_path'].$db->f('dirname').$db->f('filename');
			$file_size = $db->f('filesize');
			$file_db_desc  = $db->f('description');
			$file_db_titel = $db->f('titel');
			$file_filetype = $db->f('filetype');
			$file_name     = $db->f('filename');
			
			if ($is_thumb){
				$name_length = strlen($file_name);
				$extension_length = strlen($file_filetype);
				$t_name = substr ($file_name, 0, ($name_length - $extension_length - 1) );
				$t_name .= $cfg_client['thumbext'].'.'. $file_filetype;
				$file_url = $cfg_client['upl_htmlpath'].$db->f('dirname'). $t_name;
				$file_path = $cfg_client['upl_path'].$db->f('dirname'). $t_name;
			}
		}
	}

	// Wenn nur filesize gefordert, Ausgabe - FRONTEND und BACKEND
	if ($type_config['mode'] == 'filesize') return $file_size;

	// Wenn dateimanager titel angefordert - Ausgabe FRONTEND + BACKEND
	if ($type_config['mode'] == 'fmtitle') return $file_db_titel;

	// Wenn dateimanager beschreibung angefordert - Ausgabe FRONTEND + BACKEND
	if ($type_config['mode'] == 'fmdesc') return $file_db_desc;

	// Wenn Filetype angefordert - Ausgabe FRONTEND + BACKEND
	if ($type_config['mode'] == 'filetype') return $file_filetype;

	// Wenn Filename angefordert - Ausgabe FRONTEND + BACKEND
	if ($type_config['mode'] == 'filename') return $file_name;

	// Wenn nur path gefordert, Ausgabe - FRONTEND und BACKEND
	if ($type_config['mode'] == 'path') return $file_path;

	// Wenn nur url gefordert, Ausgabe - FRONTEND und BACKEND
	if ($type_config['mode'] == 'url') return $file_url;

	// make global edit_all url
	if (_type_check_editall($cms_side, $type_config) )
		$cms_edittype[$type_container][$type_number][] = '10-'.$type_typenumber.',11-'.$type_typenumber.',12-'.$type_typenumber;

	// Editbutton erzeugen fuer BACKEND
	if (_type_check_editable($cms_side['edit'], $type_config['editable'], $cms_side['view'],
			isset($cms_side['edit_all'])) && $type_config['menuoptions'] != 'false') {

		$title = (empty($type_config['title'])) ? $mod_lang['type_file'] : $type_config['title'];

		// advanced Modus
		if ($type_config['menuoptions'] == 'advanced') {
			$new=false; $delete=false; $up= false; $down=false;
			// neu anlegen & loeschen
			if (type_number != '1' || !empty($file_url)) {
	            $new = true;
	            $delete = true;
			}
			// nach oben verschieben
			if ($type_number != '1') $up = true;
			// nach unten verschieben
			if (!empty($file_url) && $cms_mod['modul']['lastentry'] != 'true') $down = true;
		}

        $ids = array(10,11,12);
		$infos = array(
                    'container_number' => $type_container,
                    'cmstag_id' => $type_typenumber,
                    'mod_repeat_id' => $type_number,
                    'title' => $title,
                    'base_url' => $con_side[$idcatside]['link'],
                    'mode' => $type_config['menuoptions']
				);
		//menu erstellen
		$layer_menu = _type_get_layer_menu($ids, $infos, $delete, $new, $up, $down);

		// Wenn Editbutton angefordert wurde, ausgeben - BACKEND
		if($type_config['mode'] == 'editbutton') return $layer_menu;
	}

	// Vollstaendigen Dateilink bauen
	// CSS ausfindig machen
	$css = _type_get_style($type_config['styleclass'], $type_config['styleid'], $type_config['styledb']);

	// Wenn style angefordert oder default
	if($type_config['mode'] == 'style') return $css['style'];

	// Wenn styletype angefordert oder default
	if($type_config['mode'] == 'styletype')	return $css['type'];

	// Wenn fullstyle angefordert oder default
	if($type_config['mode'] == 'fullstyle') return $css['fullstyle'];
	$target = ( empty($file_target) ) ? '':'target="'. $file_target .'"';
	$desc = ( empty($file_desc) ) ? $file_url : $file_desc;
  $file_textlink = '<a href="'. $file_url .'" '. $css['fullstyle'] .'  '. $target .' >'. $desc .'</a>';

	// Wenn textlink angefordert oder default
	if($type_config['mode'] == 'textlink' || empty($type_config['mode'])) {
		if(! empty($file_url)) return $file_textlink . $layer_menu;
		else return $layer_menu;
	}
}

/**
 * Frontendausgabe CMS:tag sourcecode
 *
 * @Args: int type_container -> id von cms:tag <cms:lay type="container" id="XX" />
 *        int type_number  -> Entspricht der Verdopplungsid eines Containers
 *        int type_typenumber -> Eindeutige Id des Contents
 *        array $type_config -> Attribute und deren Werte des cms:tags
 * @Return String Content
 * @Access public
 */
function type_output_sourcecode($type_container, $type_number, $type_typenumber, $type_config)
{
	global $sess, $cfg_cms, $cfg_client, $idcatside, $mod_lang, $content, $cms_side, $cms_edittype;
	global $cms_mod, $con_side;

	//catch vars and arrays from the tag attributes
	eval(_type_get_dynamic_val_string($type_config));

	//content auslesen
	$mod_content = (is_array($content[$type_container][$type_number])) ? $content[$type_container][$type_number]['9'][$type_typenumber]['1'] : '';

	//nl2br
	if ($type_config['nl2br'] == 'true') {
		$mod_content = nl2br($mod_content);
	}

	// make global edit_all url
	if(_type_check_editall($cms_side, $type_config)) $cms_edittype[$type_container][$type_number][] = '9-'.$type_typenumber;

	if (_type_check_editable($cms_side['edit'], $type_config['editable'], $cms_side['view'], isset($cms_side['edit_all'])) && $type_config['menuoptions'] != 'false') {
		$title = (empty($type_config['title'])) ? $mod_lang['type_sourcecode'] : $type_config['title'];

		// advanced Modus
		if ($type_config['menuoptions'] == 'advanced') {
			$new=false; $delete=false; $up= false; $down=false;
			// neu anlegen & loeschen
			if ($type_number != '1' || $mod_content != '') {
	            $new = true;
	            $delete = true;
			}
			// nach oben verschieben
			if ($type_number != '1') $up = true;
			// nach unten verschieben
			if ($mod_content != '' && $cms_mod['modul']['lastentry'] != 'true') $down = true;
		}

        $ids = array(9);
		$infos = array(
                    'container_number' => $type_container,
                    'cmstag_id' => $type_typenumber,
                    'mod_repeat_id' => $type_number,
                    'title' => $title,
                    'base_url' => $con_side[$idcatside]['link'],
                    'mode' => $type_config['menuoptions']
				);
		//menu erstellen
		$layer_menu = _type_get_layer_menu($ids, $infos, $delete, $new, $up, $down);

		$mod = $mod_content.$layer_menu;
	} else $mod = $mod_content;
	return $mod;
}


/**
 * Frontendausgabe CMS:tag typegroup
 *
 * @Args: int type_container -> id von cms:tag <cms:lay type="container" id="XX" />
 *        int type_number  -> Entspricht der Verdopplungsid eines Containers
 *        int type_typenumber -> Eindeutige Id des Contents
 *        array $type_config -> Attribute und deren Werte des cms:tags
 * @Return String Content
 * @Access public
 */
function type_output_typegroup($type_container, $type_number, $type_typenumber, $type_config)
{
	global $sess, $DB_cms, $cms_db, $cfg_cms, $cfg_client, $idcatside, $mod_lang, $content, $cms_side;
	global $client, $cms_edittype, $cms_mod, $con_side;
	
	// Wenn keine Elemente uebergeben wurden abbrechen
	if (empty($type_config['elements'])) return 'Kein Element in typegroup';

	//catch vars and arrays from the tag attributes
	eval(_type_get_dynamic_val_string($type_config));

	// Puefen ob bearbeitet werden darf
	if (!_type_check_editable($cms_side['edit'], $type_config['editable'], $cms_side['view'], isset($cms_side['edit_all']))) return false;

	// Elementstring aufbereiten
	$elements = $type_config['elements'];
	preg_match_all("/(\w*)\[([^\]]*)\]/", $elements , $matches);
	$menustring = '_';
	$link_edit = '';
	$link_new = '';
	$is_empty = true;
	$mc = count($matches['1']);
	for($cms_inc=0; $cms_inc<$mc; $cms_inc++) {
		$e_name = $matches['1'][$cms_inc];
		$e_id = $matches['2'][$cms_inc];
		if(! is_numeric($e_id) ){
			$to_eval = "global $e_id;\n". '$extracted_var'."= $e_id;";
			eval($to_eval);
			$e_id = $extracted_var;
		}
		switch($e_name)	{
			case 'text':
				$link_edit[]  = '1-'.$e_id;
				$mod_content['1'] = (is_array($content[$type_container][$type_number])) ? $content[$type_container][$type_number]['1'][$e_id]['1'] : '';
				if(!empty($mod_content['1'])) $is_empty = false;
				break;
			case 'wysiwyg':
				$link_edit[]  = '2-'.$e_id;
				$mod_content['2'] = (is_array($content[$type_container][$type_number])) ? $content[$type_container][$type_number]['2'][$e_id]['1'] : '';
				if(!empty($mod_content['2'])) $is_empty = false;
				break;
			case 'textarea':
				$link_edit[]  = '3-'.$e_id;
				$mod_content['3'] = (is_array($content[$type_container][$type_number])) ? $content[$type_container][$type_number]['3'][$e_id]['1'] : '';
				if(!empty($mod_content['3'])) $is_empty = false;
				break;
			case 'image':
				$link_edit[]  = '4-'.$e_id.',5-'.$e_id;
				$mod_content['4'] = (is_array($content[$type_container][$type_number])) ? $content[$type_container][$type_number]['4'][$e_id]['1'] : '';
				if(!empty($mod_content['4'])) $is_empty = false;
				break;
			case 'link':
				$link_edit[]  = '6-'.$e_id.',7-'.$e_id.',8-'.$e_id;
				$mod_content['6'] = (is_array($content[$type_container][$type_number])) ? $content[$type_container][$type_number]['6'][$e_id]['1'] : '';
				if(!empty($mod_content['6'])) $is_empty = false;
				break;
			case 'sourcecode':
				$link_edit[]  = '9-'.$e_id;
				$mod_content['9'] = (is_array($content[$type_container][$type_number])) ? $content[$type_container][$type_number]['9'][$e_id]['1'] : '';
				if(!empty($mod_content['9'])) $is_empty = false;
				break;
			case 'file':
				$link_edit[]  = '10-'.$e_id.',11-'.$e_id.',12-'.$e_id;
				$mod_content['10'] = (is_array($content[$type_container][$type_number])) ? $content[$type_container][$type_number]['10'][$e_id]['1'] : '';
				if(!empty($mod_content['10'])) $is_empty = false;
				break;
			case 'wysiwyg2':
				$link_edit[]  = '13-'.$e_id;
				$mod_content['13'] = (is_array($content[$type_container][$type_number])) ? $content[$type_container][$type_number]['13'][$e_id]['1'] : '';
				if(!empty($mod_content['13'])) $is_empty = false;
				break;
			case 'select':
				$link_edit[]  = '14-'.$e_id;
				$mod_content['14'] = (is_array($content[$type_container][$type_number])) ? $content[$type_container][$type_number]['14'][$e_id]['1'] : '';
				if(!empty($mod_content['14'])) $is_empty = false;
				break;
			case 'hidden':
				$link_edit[]  = '15-'.$e_id;
				$mod_content['15'] = (is_array($content[$type_container][$type_number])) ? $content[$type_container][$type_number]['15'][$e_id]['1'] : '';
				if(!empty($mod_content['15'])) $is_empty = false;
				break;
			case 'checkbox':
				$link_edit[]  = '16-'.$e_id.',20-'.$e_id;
				$mod_content['16'] = (is_array($content[$type_container][$type_number])) ? $content[$type_container][$type_number]['16'][$e_id]['1'] : '';
				if(!empty($mod_content['16'])) $is_empty = false;
				break;
			case 'radio':
				$link_edit[]  = '17-'.$e_id;
				$mod_content['17'] = (is_array($content[$type_container][$type_number])) ? $content[$type_container][$type_number]['17'][$e_id]['1'] : '';
				if(!empty($mod_content['17'])) $is_empty = false;
				break;
			case 'date':
				$link_edit[]  = '18-'.$e_id;
				$mod_content['18'] = (is_array($content[$type_container][$type_number])) ? $content[$type_container][$type_number]['18'][$e_id]['1'] : '';
				if(!empty($mod_content['18'])) $is_empty = false;
				break;
		}
	}

	$full_edit_link = implode(',', $link_edit);
	//$linkset_unique_identifier = str_replace(',', '-', $full_edit_link);

	// Editbutton erzeugen
	if (_type_check_editable($cms_side['edit'], $type_config['editable'], $cms_side['view'],
		isset($cms_side['edit_all'])) && $type_config['menuoptions'] != 'false') {

		$title = ( empty($type_config['title']) ) ? $mod_lang['type_typegroup'] : $type_config['title'];
		// advanced Modus
		if ($type_config['menuoptions'] == 'advanced') {
			$new=false; $delete=false; $up= false; $down=false;
			// neu anlegen & loeschen
			if ($is_empty != true) {
	            $new = true;
	            $delete = true;
			}
			// nach oben verschieben
			if ($type_number != '1') $up = true;
			// nach unten verschieben
			if ($cms_mod['modul']['lastentry'] != 'true') $down = true;
		}

        $ids = array(NULL);
		$infos = array(
                    'container_number' => $type_container,
                    'pre_compiled' => $full_edit_link,
                    'mod_repeat_id' => $type_number,
                    'title' => $title,
                    'base_url' => $con_side[$idcatside]['link'],
                    'mode' => $type_config['menuoptions']
				);
		//menu erstellen
		$layer_menu = _type_get_layer_menu($ids, $infos, $delete, $new, $up, $down);

		return $editbutton_image.$layer_menu;
	}
}

// edit whole side button - only for pros
function type_output_edit_container($type_container, $type_number, $type_typenumber, $type_config) {
	global $cms_side, $DB_cms, $cms_db, $cms_edittype, $sess, $cfg_client, $mod_lang, $idcatside;
	global $cfg_cms, $idcatside, $con_side, $cms_mod;

	if (($cms_side['edit_all'] || ($type_config['editable'] == 'true' && $cms_side['view'] == 'edit'))
		&& $type_config['editable'] != 'false' && is_array($cms_edittype[$type_container])) {

		//catch vars and arrays from the tag attributes
		eval(_type_get_dynamic_val_string($type_config));

  		$edit_content = $type_container.'.'.$type_number.'.';
		foreach ($cms_edittype[$type_container][$type_number] as $value)
		{
			$edit_content .= $value.',';
		}
		$edit_content = substr ($edit_content,  0, strlen ($edit_content)-1);
		//echo $edit_content .'<br>';

		// nur Edit-URL
		if ($type_config['mode'] == 'editurl') return $con_side[$idcatside]['link']."&action=edit&content=$edit_content";
		if ($type_config['menuoptions'] != 'false') {

			$title = (empty($type_config['title'])) ? $mod_lang['type_container'] : $type_config['title'];

			// advanced Modus
			if ($type_config['menuoptions'] == 'advanced') {
				$new=false; $delete=false; $up= false; $down=false;

				// gibt es schon einen Eintrag?
				$sql = "SELECT
							*
						FROM
							$cms_db[content]
						WHERE
							idsidelang='".$con_side[$idcatside]['idsidelang']."'
							AND container='$type_container'
							AND number='$type_number'";
				$db = &new DB_cms;
				$db->query($sql);

				// neu anlegen & loeschen
				if ($type_number != '1' || $db->affected_rows()) {
		            $new = true;
		            $delete = true;
				}
				// nach oben verschieben
				if ($type_number != '1') $up = true;
				// nach unten verschieben
				if ($db->affected_rows() && $cms_mod['modul']['lastentry'] != 'true') $down = true;
			}

	        $ids = array(NULL);
			$infos = array(
	                    'container_number' => $type_container,
	                    'pre_compiled' => $edit_content,
	                    'tag_type' => 'edit_container',
	                    'mod_repeat_id' => $type_number,
	                    'title' => $title,
	                    'base_url' => $con_side[$idcatside]['link'],
	                    'mode' => $type_config['menuoptions']
					);
			//menu erstellen
			$layer_menu = _type_get_layer_menu($ids, $infos, $delete, $new, $up, $down);
		}

		return $layer_menu;
	}
}

function type_output_cache($type_container, $type_number, $type_typenumber, $type_config)
{
	global $idcatside;

	// gucken ob eingabe ok ist
	if (is_integer($type_config['expires'])) return;
	//catch vars and arrays from the tag attributes
	eval(_type_get_dynamic_val_string($type_config));

	$expires =  time() + $type_config['expires'];
	return '<CMSPHP>check_cache_is_expired('.$expires.')</CMSPHP>';
}


/**
 * Frontendausgabe CMS:tag option
 *
 * @Args: int type_container -> id von cms:tag <cms:lay type="container" id="XX" />
 *        int type_number  -> Entspricht der Verdopplungsid eines Containers
 *        int type_typenumber -> Eindeutige Id des Contents
 *        array $type_config -> Attribute und deren Werte des cms:tags
 * @Return String Content
 * @Access public
 */
function type_output_select($type_container, $type_number, $type_typenumber, $type_config) {
    global $sess, $cfg_cms, $cfg_client, $idcatside, $mod_lang, $content, $cms_side, $cms_edittype, $cms_mod, $con_side;
    
    //catch vars and arrays from the tag attributes
    eval(_type_get_dynamic_val_string($type_config));
    
    //content aus array holen
	$mod_content = (is_array($content[$type_container][$type_number])) ? $content[$type_container][$type_number]['14'][$type_typenumber]['1'] : '';
    
    //html tags maskieren falls htmltags=convert
    if($type_config['htmltags'] == 'convert' || empty($type_config['htmltags'])
        || ($type_config['htmltags'] != 'strip' && $type_config['htmltags'] != 'allow' ) ){
        
        $mod_content = htmlspecialchars($mod_content, ENT_COMPAT, 'UTF-8');
    }
    // make global edit_all url
    if (_type_check_editall($cms_side, $type_config))
        $cms_edittype[$type_container][$type_number][] = '14-'.$type_typenumber;
    
	// Editiermodus
    if (_type_check_editable($cms_side['edit'], $type_config['editable'], $cms_side['view'],
            isset($cms_side['edit_all'])) && $type_config['menuoptions'] != 'false') {

        $title = (empty($type_config['title'])) ? $mod_lang['type_select'] : $type_config['title'];
        
        $ids[] = 14;
		$infos = array(
                    'container_number' => $type_container,
                    'cmstag_id' => $type_typenumber,
                    'mod_repeat_id' => $type_number,
                    'title' => $title,
                    'base_url' => $con_side[$idcatside]['link'],
                    'mode' => $type_config['menuoptions']
				);
		//menu erstellen
		$mod = _type_get_layer_menu($ids, $infos, $delete, $new, $up, $down);

		//Wenn nur editbutton gefordert - Ausgabe BACKEND
		if($type_config['mode'] == 'editbutton') return $mod;
		//Content mit Editbutton erzeugen
		$mod = $mod_content . $mod;

	} else $mod = $mod_content;
	
	//Frontend: wenn Editbutton
	if($type_config['mode'] == 'editbutton') return;

    //Style
    $css = _type_get_style($type_config['styleclass'], $type_config['styleid'], $type_config['styledb']);
    $css['type'] = trim($css['type']);
    if(! empty($css['type']) ){
         $mod = '<span '. $css['fullstyle'] .'>'. $mod .'</span>';
    }
    return $mod;
}


/**
 * Frontendausgabe CMS:tag hidden
 *
 * @Args: int type_container -> id von cms:tag <cms:lay type="container" id="XX" />
 *        int type_number  -> Entspricht der Verdopplungsid eines Containers
 *        int type_typenumber -> Eindeutige Id des Contents
 *        array $type_config -> Attribute und deren Werte des cms:tags
 * @Return String Content
 * @Access public
 */
function type_output_hidden($type_container, $type_number, $type_typenumber, $type_config)
{
    global $sess, $cfg_cms, $cfg_client, $idcatside, $mod_lang, $content, $cms_side, $cms_edittype, $con_side;
    
    //catch vars and arrays from the tag attributes
    eval(_type_get_dynamic_val_string($type_config));
    
    //Content aus Array beziehen
	$mod_content = (is_array($content[$type_container][$type_number])) ? $content[$type_container][$type_number]['15'][$type_typenumber]['1'] : '';
    if (empty($mod_content)) $mod_content = $type_config['value'];

    //html tags maskieren falls htmltags=convert
    if($type_config['htmltags'] == 'convert' || empty($type_config['htmltags'])
        || ($type_config['htmltags'] != 'strip' && $type_config['htmltags'] != 'allow' ) ){
    
        $mod_content = htmlspecialchars($mod_content, ENT_COMPAT, 'UTF-8');
    }
    //alle tags entfernen
    else if($type_config['htmltags'] == 'strip')
        $mod_content = strip_tags($mod_content);

    // make global edit_all url
    if (_type_check_editall($cms_side, $type_config))
        $cms_edittype[$type_container][$type_number][] = '15-'.$type_typenumber;
           
    $css = _type_get_style($type_config['styleclass'], $type_config['styleid'], $type_config['styledb']);
    $css['type'] = trim($css['type']);
    if(! empty($css['type']) ){
        $mod_content = '<span '. $css['fullstyle'] .'>'. $mod_content .'</span>';
    }
    return $mod_content;
}

/**
 * Frontendausgabe CMS:tag checkbox
 *
 * @Args: int type_container -> id von cms:tag <cms:lay type="container" id="XX" />
 *        int type_number  -> Entspricht der Verdopplungsid eines Containers
 *        int type_typenumber -> Eindeutige Id des Contents
 *        array $type_config -> Attribute und deren Werte des cms:tags
 * @Return String Content
 * @Access public
 */
function type_output_checkbox($type_container, $type_number, $type_typenumber, $type_config) {
	global $sess, $cfg_cms, $cfg_client, $idcatside, $mod_lang, $content, $cms_side;
	global $cms_edittype, $cms_mod, $con_side, $cms_path, $core_bbcode;

	//catch vars and arrays from the tag attributes
	eval(_type_get_dynamic_val_string($type_config));

	// Content aus Array holen
	$mod_content = (is_array($content[$type_container][$type_number])) ? $content[$type_container][$type_number]['16'][$type_typenumber]['1'] : '';

	// HTML Tags maskieren falls htmltags=convert
	if($type_config['htmltags'] == 'convert' || empty($type_config['htmltags']) || ($type_config['htmltags'] != 'strip' && $type_config['htmltags'] != 'allow')) $mod_content = htmlspecialchars($mod_content, ENT_COMPAT, 'UTF-8');

	// alle Tags entfernen
	if($type_config['htmltags'] == 'strip') {
		// wenn nl2br aktiviert, <br>- tags duerfen nicht gestrippt werden
		if ($type_config['nl2br'] == 'true' || empty($type_config['nl2br'])) {
		  $mod_content = str_replace('<br />', '{cms_temp_break}', $mod_content);
		  $mod_content = strip_tags($mod_content);
		  $mod_content = str_replace('{cms_temp_break}', '<br />', $mod_content);
		} else $mod_content = strip_tags($mod_content);
	}

	// make global edit_all url
	if (_type_check_editall($cms_side, $type_config))
		$cms_edittype[$type_container][$type_number][] = '16-'.$type_typenumber;

	// Editiermodus
	if (_type_check_editable($cms_side['edit'], $type_config['editable'], $cms_side['view'],
			isset($cms_side['edit_all'])) && $type_config['menuoptions'] != 'false') {

		$title = (empty($type_config['title'])) ? $mod_lang['type_checkbox'] : $type_config['title'];

		// advanced Modus
		if ($type_config['menuoptions'] == 'advanced') {
			$new=false; $delete=false; $up= false; $down=false;
			// neu anlegen & loeschen
			if ($type_number != '1' || $mod_content != '') {
	            $new = true;
	            $delete = true;
			}
			// nach oben verschieben
			if ($type_number != '1') $up = true;
			// nach unten verschieben
			if ($mod_content != '' && $cms_mod['modul']['lastentry'] != 'true') $down = true;
		}

        $ids = array(16,20);
		$infos = array(
                    'container_number' => $type_container,
                    'cmstag_id' => $type_typenumber,
                    'mod_repeat_id' => $type_number,
                    'title' => $title,
                    'base_url' => $con_side[$idcatside]['link'],
                    'mode' => $type_config['menuoptions']
				);
		//menu erstellen
		$mod = _type_get_layer_menu($ids, $infos, $delete, $new, $up, $down);

		//Wenn nur editbutton gefordert - Ausgabe BACKEND
		if($type_config['mode'] == 'editbutton') return $mod;
		//Content mit Editbutton erzeugen
		$mod = $mod_content . $mod;

	} else $mod = $mod_content;
	
	//Frontend: wenn Editbutton
	if($type_config['mode'] == 'editbutton') return;

	// Style
	$css = _type_get_style($type_config['styleclass'], $type_config['styleid'], $type_config['styledb']);
	$css['type'] = trim($css['type']);
	if (!empty($css['type'])) $mod = '<span '.$css['fullstyle'].'>'.$mod.'</span>';
	return $mod;
}


/**
 * Frontendausgabe CMS:tag radiobutton
 *
 * @Args: int type_container -> id von cms:tag <cms:lay type="container" id="XX" />
 *        int type_number  -> Entspricht der Verdopplungsid eines Containers
 *        int type_typenumber -> Eindeutige Id des Contents
 *        array $type_config -> Attribute und deren Werte des cms:tags
 * @Return String Content
 * @Access public
 */
function type_output_radio($type_container, $type_number, $type_typenumber, $type_config) {
	global $sess, $cfg_cms, $cfg_client, $idcatside, $mod_lang, $content, $cms_side;
	global $cms_edittype, $cms_mod, $con_side, $cms_path, $core_bbcode;

	//catch vars and arrays from the tag attributes
	eval(_type_get_dynamic_val_string($type_config));

	// Content aus Array holen
	$mod_content = (is_array($content[$type_container][$type_number])) ? $content[$type_container][$type_number]['17'][$type_typenumber]['1'] : '';

	// HTML Tags maskieren falls htmltags=convert
	if($type_config['htmltags'] == 'convert' || empty($type_config['htmltags']) || ($type_config['htmltags'] != 'strip' && $type_config['htmltags'] != 'allow')) $mod_content = htmlspecialchars($mod_content, ENT_COMPAT, 'UTF-8');

	// alle Tags entfernen
	if($type_config['htmltags'] == 'strip') {
		// wenn nl2br aktiviert, <br>- tags duerfen nicht gestrippt werden
		if ($type_config['nl2br'] == 'true' || empty($type_config['nl2br'])) {
		  $mod_content = str_replace('<br />', '{cms_temp_break}', $mod_content);
		  $mod_content = strip_tags($mod_content);
		  $mod_content = str_replace('{cms_temp_break}', '<br />', $mod_content);
		} else $mod_content = strip_tags($mod_content);
	}

	// make global edit_all url
	if (_type_check_editall($cms_side, $type_config))
		$cms_edittype[$type_container][$type_number][] = '17-'.$type_typenumber;

	// Editiermodus
	if (_type_check_editable($cms_side['edit'], $type_config['editable'], $cms_side['view'],
			isset($cms_side['edit_all'])) && $type_config['menuoptions'] != 'false') {

		$title = (empty($type_config['title'])) ? $mod_lang['type_radio'] : $type_config['title'];

		// advanced Modus
		if ($type_config['menuoptions'] == 'advanced') {
			$new=false; $delete=false; $up= false; $down=false;
			// neu anlegen & loeschen
			if ($type_number != '1' || $mod_content != '') {
	            $new = true;
	            $delete = true;
			}
			// nach oben verschieben
			if ($type_number != '1') $up = true;
			// nach unten verschieben
			if ($mod_content != '' && $cms_mod['modul']['lastentry'] != 'true') $down = true;
		}

        $ids[] = 17;
		$infos = array(
                    'container_number' => $type_container,
                    'cmstag_id' => $type_typenumber,
                    'mod_repeat_id' => $type_number,
                    'title' => $title,
                    'base_url' => $con_side[$idcatside]['link'],
                    'mode' => $type_config['menuoptions']
				);
		//menu erstellen
		$mod = _type_get_layer_menu($ids, $infos, $delete, $new, $up, $down);

		//Wenn nur editbutton gefordert - Ausgabe BACKEND
		if($type_config['mode'] == 'editbutton') return $mod;
		//Content mit Editbutton erzeugen
		$mod = $mod_content . $mod;

	} else $mod = $mod_content;
	
	//Frontend: wenn Editbutton
	if($type_config['mode'] == 'editbutton') return;

	// Style
	$css = _type_get_style($type_config['styleclass'], $type_config['styleid'], $type_config['styledb']);
	$css['type'] = trim($css['type']);
	if (!empty($css['type'])) $mod = '<span '.$css['fullstyle'].'>'.$mod.'</span>';
	return $mod;
}


/**
 * Frontendausgabe CMS:tag date
 *
 * @Args: int type_container -> id von cms:tag <cms:lay type="container" id="XX" />
 *        int type_number  -> Entspricht der Verdopplungsid eines Containers
 *        int type_typenumber -> Eindeutige Id des Contents
 *        array $type_config -> Attribute und deren Werte des cms:tags
 * @Return String Content
 * @Access public
 */
function type_output_date($type_container, $type_number, $type_typenumber, $type_config) {
	global $sess, $cfg_cms, $cfg_client, $idcatside, $mod_lang, $content, $cms_side;
	global $cms_edittype, $cms_mod, $con_side, $cms_path, $core_bbcode;

	//catch vars and arrays from the tag attributes
	eval(_type_get_dynamic_val_string($type_config));

	// Content aus Array holen
	$mod_content = (is_array($content[$type_container][$type_number])) ? $content[$type_container][$type_number]['18'][$type_typenumber]['1'] : '';
    // Wenn Default Format angefordert wird
    if ((empty($mod_content)) || ($mod_content=="")) {
        $mod_content = "";
    } else {
        if ((empty($type_config['mode'])) || ($type_config['mode'] == 'default-cms-format')) $mod_content = date($cfg_cms['FormatDate'].' '.$cfg_cms['FormatTime'],$mod_content);
        elseif ((empty($type_config['mode'])) || ($type_config['mode'] == 'default-cms-date-format')) $mod_content = date($cfg_cms['FormatDate'],$mod_content);
        elseif ((empty($type_config['mode'])) || ($type_config['mode'] == 'default-cms-time-format')) $mod_content = date($cfg_cms['FormatTime'],$mod_content);
        elseif ($type_config['mode'] == 'timestamp') $mod_content = $mod_content;
        else  $mod_content = date($type_config['mode'],$mod_content);
    }

	// make global edit_all url
	if (_type_check_editall($cms_side, $type_config))
		$cms_edittype[$type_container][$type_number][] = '18-'.$type_typenumber;

	// Editiermodus
	if (_type_check_editable($cms_side['edit'], $type_config['editable'], $cms_side['view'],
			isset($cms_side['edit_all'])) && $type_config['menuoptions'] != 'false') {

		$title = (empty($type_config['title'])) ? $mod_lang['type_date'] : $type_config['title'];

		// advanced Modus
		if ($type_config['menuoptions'] == 'advanced') {
			$new=false; $delete=false; $up= false; $down=false;
			// neu anlegen & loeschen
			if ($type_number != '1' || $mod_content != '') {
	            $new = true;
	            $delete = true;
			}
			// nach oben verschieben
			if ($type_number != '1') $up = true;
			// nach unten verschieben
			if ($mod_content != '' && $cms_mod['modul']['lastentry'] != 'true') $down = true;
		}

        $ids[] = 18;
		$infos = array(
                    'container_number' => $type_container,
                    'cmstag_id' => $type_typenumber,
                    'mod_repeat_id' => $type_number,
                    'title' => $title,
                    'base_url' => $con_side[$idcatside]['link'],
                    'mode' => $type_config['menuoptions']
				);
		//menu erstellen
		$mod = _type_get_layer_menu($ids, $infos, $delete, $new, $up, $down);

		//Wenn nur editbutton gefordert - Ausgabe BACKEND
		if($type_config['mode'] == 'editbutton') return $mod;
		//Content mit Editbutton erzeugen
		$mod = $mod_content . $mod;

	} else $mod = $mod_content;
	
	//Frontend: wenn Editbutton
	if($type_config['mode'] == 'editbutton') return;

	// Style
	$css = _type_get_style($type_config['styleclass'], $type_config['styleid'], $type_config['styledb']);
	$css['type'] = trim($css['type']);
	if (!empty($css['type'])) $mod = '<span '.$css['fullstyle'].'>'.$mod.'</span>';
	return $mod;
}




?>
