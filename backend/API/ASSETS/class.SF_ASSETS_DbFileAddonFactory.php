<?php
// File: $Id: class.SF_ASSETS_DbFileAddonFactory.php 28 2008-05-11 19:18:49Z mistral $
// +----------------------------------------------------------------------+
// | Version: Sefrengo $Name:  $
// +----------------------------------------------------------------------+
// | Copyright (c) 2005 the sefrengo-group <sefrengo-group@sefrengo.de>   |
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
// + jb - 2006-08-18 - new mods for thumbnail generation
// +                   for every pict a thumbnail will be created
// +----------------------------------------------------------------------+
// + ToDo:
// +----------------------------------------------------------------------+
class SF_ASSETS_DbFileAddonFactory extends SF_API_Object {
	var $fileobject;
	
	function SF_ASSETS_DbFileAddonFactory() {
		
	}
	
	function setFileObject(&$o) {
		$this->fileobject =& $o;
	}
	
	function &getAddonObject() {
		switch ($this->fileobject->getFiletype()) {
			case 'jpg':
			case 'jpeg':
			case 'png':
			case 'gif':
				$o =& $GLOBALS['sf_factory']->getObjectForced('ASSETS', 'DbFileAddonFactory', 'DbFileAddonImage');
				$o->setFileObject($this->fileobject);
				break;
			default:
				$o =& $GLOBALS['sf_factory']->getObjectForced('ASSETS', 'DbFileAddonFactory', 'DbFileAddon');
				break;
		}
		
		return $o;
	}
	
}

class SF_ASSETS_DbFileAddon extends SF_API_Object {
	var $fileobject;
	
	function SF_ASSETS_DbFileAddon() {
	}
	
	function setFileObject(&$o) {
		$this->fileobject =& $o;
	}
	
	function newFile() {
		
	}
	
	function updateFile() {
		
	}
	
	function deleteFile() {
		
	}
}


class SF_ASSETS_DbFileAddonImage extends SF_ASSETS_DbFileAddon{
	
	var $img_lib        = '';
	var $img_lib_type   = '';
	var $thumbwidth     =  0;
	var $thumbheight    =  0;
	var $thumbext       = '';
	var $size           =  0;
	var $aspect_ratio   =  0;
	var $chmod_enabled  =  false;
	var $chmod_value    =  0777;
	var $sourcefile     = '';
	
	function SF_ASSETS_DbFileAddonImage() {	
		global $cfg_client,$cfg_cms, $cms_image, $fm;

		require_once 'Image/Transform.php';
		
		$this->thumbext      = $cfg_client['thumbext'];
		$this->size          = $cfg_client['thumb_size'];
		$this->aspect_ratio  = (int) $cfg_client['thumb_aspectratio'];
		$this->chmod_enabled = ($cfg_cms['chmod_enabled'] == '1');
		$this->chmod_value   = intval($cfg_cms['chmod_value'], 8);
		$this->img_lib_type  = $cfg_cms['image_mode'];

		$this->img_lib       = Image_Transform::factory($this->img_lib_type);
        if (!$this->thumbext) $this->thumbext = "_cms_thumb";
	}
	
	function newFile() {
		//$this->fileobject->data['testing'] = 'schubidu';
		$this->sourcefile = $this->fileobject->getFilepathAbsolute();
		$this->_resizeImage(true);
		$this->_setImageInfo();
	}
	
	function updateFile() {
		$this->sourcefile = $this->fileobject->getFilepathAbsolute();
		$this->_resizeImage(($this->fileobject->getExternalSourcefile() != ''));
		$this->_setImageInfo();
	}
	
	function deleteFile() {
		if (is_file($this->_getTumbnailName())) {
			unlink($this->_getTumbnailName());
		}
	}
	
	function _setImageInfo() {
		$thumbfile = $this->_getTumbnailName();
		if (is_file($thumbfile)) {
			$this->img_lib->load($thumbfile);
			$this->fileobject->data['file']['pictthumbwidth'] = $this->img_lib->img_x;
			$this->fileobject->data['file']['pictthumbheight'] = $this->img_lib->img_y;
			$this->img_lib->free();
		} 
	}
	
	function _resizeImage($force_thumbnailgeneration) {
		$this->thumbwidth = 0;
		$this->thumbheight = 0;
		// generate thumbnails, if cms-value thumb_size is set to any value > 0
		if ($this->size > 0) {
			$thumb_filename = $this->_getTumbnailName();
			//echo $thumb_filename;exit;
			// Create thumbnail if pict is bigger than the thumbnail size, or system is set to scale any
			// size of pict to the desired thumbnail size (aspect_ratios > 9)
			$this->img_lib->load($this->sourcefile);
			$this->fileobject->data['file']['pictwidth'] = $this->img_lib->img_x;
			$this->fileobject->data['file']['pictheight'] = $this->img_lib->img_y;
			// changes: jb - old modes + 10 -> size check ignored, every pict will have a thumbnail
			if ($this->aspect_ratio > 9 || $this->size < $this->img_lib->img_x || $this->size < $this->img_lib->img_y) {
				if (!file_exists($thumb_filename) || $force_thumbnailgeneration) {
					// Resize image if new or update
					// Erweiterung Aspect-Ratio 2,3 auf Vorschlag von Mistral
					// changes: jb - old modes + 10 -> size check ignored, every pict will have a thumbnail
					switch ($this->aspect_ratio) {
						case  0:
						case 10:
							$this->img_lib->resize($this->size, $this->size);	
							break;
						case  2:
						case 12:
							$this->img_lib->scaleByX($this->size);
							break;
						case  3:
						case 13:
							$this->img_lib->scaleByY($this->size);
							break;
						default:
							$this->img_lib->scaleByLength($this->size);
							break;
					}
					// save thumbnail and change user access rights
					$this->img_lib->save($thumb_filename);
					if ($this->chmod_enabled) chmod($thumb_filename,$this->chmod_value);
					$this->img_lib->free();
				}
			}
		}
	}
	
	function _getTumbnailName() {
		$file_pre = preg_replace('#\.'.$this->fileobject->getFiletype().'$#', '', $this->fileobject->getFilename());
		return $this->fileobject->getDirpathAbsolute().$file_pre . $this->thumbext . '.' . $this->fileobject->getFiletype();
	}
	
	
}
?>