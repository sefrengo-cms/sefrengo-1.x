<?php
// File: $Id: class.SF_GUI_ContentStylerHTML.php 56 2008-08-06 20:42:22Z bjoern $
// +----------------------------------------------------------------------+
// | Version: Sefrengo $Name$
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
// + Revision: $Revision: 56 $
// +----------------------------------------------------------------------+
// + Description:
// +----------------------------------------------------------------------+
// + Changes:
// +----------------------------------------------------------------------+
// + ToDo:
// +----------------------------------------------------------------------+
class SF_GUI_ContentStylerHTML extends SF_GUI_ContentStylerPlain {
  //privat
    /**
    * Class Name
    *
    * The Common Class identifier.
    *
    * @param  string
    */
    var $_API_name = 'ContentStylerHTML';

    /**
    * Object version
    *
    * This string identify the SF_API_Object Version.
    *
    * @param  string
    */
    var $_API_object_version = '$Revision: 56 $';
    var $_API_object_internalversion = '00.01.00';


  //public

    /**
    * Common Class Constructor
    *
    * The Class Constructor.
    *
    */
    function SF_GUI_ContentStylerCore() {
        // constructor

    }


    /**
    *
    * @Args: CMS:tag attributes styleclass, styleid, styledb
    * @Return array ['style'] = 'iamastyle'
    *               ['type'] = 'class|id'
    *               ['fullstyle'] = 'class="iAmAClass"|id="iAmAnId"'
    * @Access private
    */
    function _getStyle($class = '', $id='', $dbstyle='') {
    	global $db, $cms_db, $idlay, $client;
    
    	$css = $class;
		$out = array(
			'style' => '',
			'type' => '',
			'fullstyle' => ''
		);
    
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
		
		// empty style array
    	return $out;
    }
    
    
    /**
    * Styled Output
    *
    * Allgemeine Frontendausgabe analog CMS:tag 
    *
    * @Args: string mod_content -> Inhalt wie er in der DB vorhanden ist
    *        array  $type_config -> Attribute und deren Werte analog des cms:tags
    * @Return String Content
    * @Access privat
    */
    function _getText($mod_content, $type_config)
    {
    	//catch vars and arrays from the tag attributes
    	eval($this->_getDynamicValString($type_config));
    
    	// HTML+PHP Tags modifizieren
    	if($type_config['htmltags'] == 'convert' || empty($type_config['htmltags'])
      	   || ($type_config['htmltags'] != 'strip' && $type_config['htmltags'] != 'allow')) {
    		$mod_content = htmlspecialchars($mod_content, ENT_COMPAT, 'UTF-8');
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
    	
    	$mod = $mod_content;
    
    	//Style
    	$css = $this->_getStyle($type_config['styleclass'], $type_config['styleid'], $type_config['styledb']);
    	$css['type'] = trim($css['type']);
    	if (!empty($css['type']))
    		$mod = '<span '. $css['fullstyle'] .'>'.$mod.'</span>';

    	return $mod;
    }
    
    
    /**
    * Styled text Output
    *
    * Frontendausgabe analog CMS:tag text
    *
    * @Args: string mod_content -> Inhalt wie er in der DB vorhanden ist
    *        array  $type_config -> Attribute und deren Werte analog des cms:tags
    * @Return String Content
    * @Access public
    */
    function getText($mod_content, $type_config) {
        return $this->_getText($mod_content, $type_config);
    }
    
    
    
    /**
    * Styled textarea Output
    *
    * Frontendausgabe analog CMS:tag textarea
    *
    * @Args: string mod_content -> Inhalt wie er in der DB vorhanden ist
    *        array  $type_config -> Attribute und deren Werte analog des cms:tags
    * @Return String Content
    * @Access public
    */
    function getTextarea($mod_content, $type_config) {
    	//catch vars and arrays from the tag attributes
    	eval($this->_getDynamicValString($type_config));
    
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
    
    	$mod = $mod_content;
    
    	// Style
    	$css = $this->_getStyle($type_config['styleclass'], $type_config['styleid'], $type_config['styledb']);
    	$css['type'] = trim($css['type']);
    	if (!empty($css['type'])) $mod = '<span '.$css['fullstyle'].'>'.$mod.'</span>';
    	
    	return $mod;
    }
    
    
    
    /**
    * Styled wysiwyg2 Output
    *
    * Frontendausgabe analog CMS:tag wysiwyg2
    *
    * @Args: string mod_content -> Inhalt wie er in der DB vorhanden ist
    *        array  $type_config -> Attribute und deren Werte analog des cms:tags
    * @Return String Content
    * @Access public
    */
    function getWysiwyg2($mod_content, $type_config)
    {
    	return $this->getWysiwyg($mod_content, $type_config);
    }
    
    
    
    /**
    * Styled wysiwyg Output
    *
    * Frontendausgabe analog CMS:tag wysiwyg
    *
    * @Args: string mod_content -> Inhalt wie er in der DB vorhanden ist
    *        array  $type_config -> Attribute und deren Werte analog des cms:tags
    * @Return String Content
    * @Access public
    */
    function getWysiwyg($mod_content, $type_config)
    {
    
    	//catch vars and arrays from the tag attributes
    	eval($this->_getDynamicValString($type_config));
    
    	// Content holen
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
    
    	$mod = $mod_content;
    
    	// Style
    	$css = $this->_getStyle($type_config['styleclass'], $type_config['styleid'], $type_config['styledb']);
    	$css['type'] = trim($css['type']);
    	if (!empty($css['type'])) $mod = '<span '.$css['fullstyle'].'>'.$mod.'</span>';
    
    	return $mod;
    }
    
    
    
    /**
    * Styled image Output
    *
    * Frontendausgabe analog CMS:tag image
    *
    * @Args: string mod_content -> Inhalt wie er in der DB vorhanden ist
    *        array  $type_config -> Attribute und deren Werte analog des cms:tags
    * @Return String Content
    * @Access public
    */
    function getImage($mod_content, $mod_descr, $type_config) {
    	global $sess, $DB_cms, $cms_db, $cfg_cms, $cfg_client, $idcatside, $mod_lang, $content, $cms_side;
    	global $client, $cms_edittype, $cms_mod, $con_side;
    
    	//catch vars and arrays from the tag attributes
    	eval($this->_getDynamicValString($type_config));
    
    	// URL formatieren
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
    			
    			if ( ($type_config['mode'] == 'thumb' && $pic_filetype != 'gif' && ($thumb_x != 0 || $thumb_y != 0) )
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
     
    	// Wenn amplitude angefordert - Ausgabe FRONTEND + BACKEND
    	if ($type_config['mode'] == 'amplitude') return 'width="'. $original_x .'" height="'. $original_y . '"';
    
    	// Wenn nur filesize gefordert, Ausgabe - FRONTEND und BACKEND
    	if ($type_config['mode'] == 'filesize') return $file_size;
    
    	// Wenn dateimanager titel angefordert - Ausgabe FRONTEND + BACKEND
    	if ($type_config['mode'] == 'fmtitle') return $pic_db_titel;
    
    	// Wenn dateimanager beschreibung angefordert - Ausgabe FRONTEND + BACKEND
    	if ($type_config['mode'] == 'fmdesc') return $pic_db_desc;
    
    	// Wenn Filetype angefordert - Ausgabe FRONTEND + BACKEND
    	if ($type_config['mode'] == 'filetype') return $pic_filetype;
    
    	// Wenn Bildhöhe angefordert - Ausgabe FRONTEND + BACKEND
    	if ($type_config['mode'] == 'width') return $original_x;
    
    	// Wenn Bildweite angefordert - Ausgabe FRONTEND + BACKEND
    	if ($type_config['mode'] == 'height') return $original_y;
    
    	// Wenn nur url gefordert - Ausgabe FRONTEND + BACKEND
    	if ($type_config['mode'] == 'url') return $mod_url;
    
    	// Wenn nur pfad gefordert - Ausgabe FRONTEND + BACKEND
    	if ($type_config['mode'] == 'path') return $mod_path;
    
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
    
    	// CSS ausfindig machen
    	$css = $this->_getStyle($type_config['styleclass'], $type_config['styleid'], $type_config['styledb']);
    
    	// Wenn style angefordert oder default
    	if($type_config['mode'] == 'style') return $css['style'];
    
    	// Wenn styletype angefordert oder default
    	if($type_config['mode'] == 'styletype')	return $css['type'];
    
    	// Wenn fullstyle angefordert oder default
    	if($type_config['mode'] == 'fullstyle') return $css['fullstyle'];
    
    	// Ausgabe image - Frontend
        $mod = sprintf("<img src=\"$mod_url\"%s%s%s ". $css['fullstyle'] ." />", ($mod_descr != '') ? ' alt="'.$mod_descr.'" title="'.$mod_descr.'"' : " alt=\"\"", ($pic_width) ? ' width="'.$pic_width.'"' : '', ($pic_height) ? ' height="'.$pic_height.'"' : '');
    	return $mod;
    }
    
    
    
    /**
    * Styled link Output
    *
    * Frontendausgabe analog CMS:tag link
    *
    * @Args: string mod_content -> Inhalt wie er in der DB vorhanden ist
    *        array  $type_config -> Attribute und deren Werte analog des cms:tags
    * @Return String Content
    * @Access public
    */
    function getLink($link_url, $link_desc, $link_target, $type_config)
    {
    
    	//catch vars and arrays from the tag attributes
    	eval($this->_getDynamicValString($type_config));
    
    	// Linkurl extrahieren
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
    
    	// Wenn nur target gefordert, Ausgabe - FRONTEND und BACKEND
    	if ($type_config['mode'] == 'target') return $link_target;
    
    	// Wenn nur Beschreibung gefordert, Ausgabe - FRONTEND und BACKEND
    	if ($type_config['mode'] == 'desc') return $link_desc;
    
    	// Vollstaendigen Link bauen
    	// CSS ausfindig machen
    	$css = $this->_getStyle($type_config['styleclass'], $type_config['styleid'], $type_config['styledb']);
    
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
    		if (!empty($link_url) && $link_url != '0') return $link_textlink;
    		else return;
    	}
    }
    
    
    
    /**
    * Styled file Output
    *
    * Frontendausgabe analog CMS:tag file
    *
    * @Args: string mod_content -> Inhalt wie er in der DB vorhanden ist
    *        array  $type_config -> Attribute und deren Werte analog des cms:tags
    * @Return String Content
    * @Access public
    */
    function getFile($file_id, $file_desc, $file_target, $type_config)
    {
    	global $sess, $DB_cms, $cms_db, $cfg_cms, $cfg_client, $idcatside, $mod_lang, $content, $cms_side;
    	global $client, $cms_edittype, $cms_mod, $con_side;
    
    	//catch vars and arrays from the tag attributes
    	eval($this->_getDynamicValString($type_config));
    
    	// Wenn nur Beschreibung gefordert, Ausgabe - FRONTEND und BACKEND
    	if($type_config['mode'] == 'desc') return $file_desc;
    
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
    
    	// Vollstaendigen Dateilink bauen
    	// CSS ausfindig machen
    	$css = $this->_getStyle($type_config['styleclass'], $type_config['styleid'], $type_config['styledb']);
    
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
    		if(! empty($file_url)) return $file_textlink;
    		else return ;
    	}
    }
    
    
    
    /**
    * Styled sourcecode Output
    *
    * Frontendausgabe analog CMS:tag sourcecode
    *
    * @Args: string mod_content -> Inhalt wie er in der DB vorhanden ist
    *        array  $type_config -> Attribute und deren Werte analog des cms:tags
    * @Return String Content
    * @Access public
    */
    function getSourcecode($mod_content, $type_config)
    {
    
    	//catch vars and arrays from the tag attributes
    	eval($this->_getDynamicValString($type_config));
    
    	//nl2br
    	if ($type_config['nl2br'] == 'true') {
    		$mod_content = nl2br($mod_content);
    	}
    
    	$mod = $mod_content;
    	
    	return $mod;
    }
    
    
    
    /**
    * Styled select Output
    *
    * Frontendausgabe analog CMS:tag select
    *
    * @Args: string mod_content -> Inhalt wie er in der DB vorhanden ist
    *        array  $type_config -> Attribute und deren Werte analog des cms:tags
    * @Return String Content
    * @Access public
    */
    function getSelect($mod_content, $type_config) {
        return $this->_getText($mod_content, $type_config);
    }
    
    
    
    /**
    * Styled hidden Output
    *
    * Frontendausgabe analog CMS:tag hidden
    *
    * @Args: string mod_content -> Inhalt wie er in der DB vorhanden ist
    *        array  $type_config -> Attribute und deren Werte analog des cms:tags
    * @Return String Content
    * @Access public
    */
    function getHidden($mod_content, $type_config)
    {
        //catch vars and arrays from the tag attributes
        eval($this->_getDynamicValString($type_config));
        
        if (empty($mod_content)) $mod_content = $type_config['value'];
    
        return $this->_getText($mod_content, $type_config);
    }
    
    
    
    /**
    * Styled checkbox Output
    *
    * Frontendausgabe analog CMS:tag checkbox
    *
    * @Args: string mod_content -> Inhalt wie er in der DB vorhanden ist
    *        array  $type_config -> Attribute und deren Werte analog des cms:tags
    * @Return String Content
    * @Access public
    */
    function getCheckbox($mod_content, $type_config) {
        return $this->_getText($mod_content, $type_config);
    }
    
    
    
    /**
    * Styled radio Output
    *
    * Frontendausgabe analog CMS:tag radio
    *
    * @Args: string mod_content -> Inhalt wie er in der DB vorhanden ist
    *        array  $type_config -> Attribute und deren Werte analog des cms:tags
    * @Return String Content
    * @Access public
    */
    function getRadio($mod_content, $type_config) {
        return $this->_getText($mod_content, $type_config);
    }
    
    
    
    /**
    * Styled date Output
    *
    * Frontendausgabe analog CMS:tag date
    *
    * @Args: string mod_content -> Inhalt wie er in der DB vorhanden ist
    *        array  $type_config -> Attribute und deren Werte analog des cms:tags
    * @Return String Content
    * @Access public
    */
    function getDate($mod_content, $type_config) {
        global $cfg_cms;
    
    	//catch vars and arrays from the tag attributes
    	eval($this->_getDynamicValString($type_config));
    
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
    
    	$mod = $mod_content;
    
    	// Style
    	$css = $this->_getStyle($type_config['styleclass'], $type_config['styleid'], $type_config['styledb']);
    	$css['type'] = trim($css['type']);
    	if (!empty($css['type'])) $mod = '<span '.$css['fullstyle'].'>'.$mod.'</span>';
    	return $mod;
    }

} 

?>