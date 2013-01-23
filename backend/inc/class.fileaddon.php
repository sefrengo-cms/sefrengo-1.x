<?PHP
// File: $Id: class.fileaddon.php 28 2008-05-11 19:18:49Z mistral $
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

/******************************************************************************
** Filetype-Addon Factory
**
** Description : Factory class to handle addon-objects of different filetypes
** Copyright   : Jürgen Brändle, 2003
** Author      : Jürgen Brändle, braendle@web.de
** Urls        : www.Sefrengo.de
** Create date : 2003-07-26
** Last update : 2003-07-27
**
******************************************************************************/
class addon_factory
{
	// properties
	var $_fileaddon = array();
	var $_supported = array();
	var $inc_path   = '';

	// constructor
	function addon_factory() {
		global $cfg_client, $this_dir;

		$this->_supported = explode ( ",", $cfg_client["upl_addon"] );
		$this->inc_path = $GLOBALS['this_dir'] . 'inc/';
	}

	//
	// get($type)
	//
	// returns an addon object for the desired filetype
	// the function creates a new addon object if there is no existing one
	// and stores it in the array $_addon
	//
	function get($filetype, $inc_path = '') {
		if ($inc_path) $this->inc_path = $inc_path;
		// check for valid and supported type
		if (empty($filetype) || !in_array($filetype, $this->_supported)) return null;
		// check if a addon object for the desired filetype exits
		if (!$this->_fileaddon[$filetype]) {
			// no ... check for supported standard filetypes: jpeg, jpg, png, gif
			if (!class_exists("fileaddon_".$filetype)) {
				// no ... check for an addon class file
				if (!file_exists($this->inc_path.'class.fileaddon_'.$filetype.'.php')) return null;
				else {
					// load the file found and check for supported class type
					include_once($this->inc_path.'class.fileaddon_'.$filetype.'.php');
					if (!class_exists("fileaddon_".$filetype)) return null;
				}
			}
			// there should be an addon class
			eval('$this->_fileaddon['.$filetype.'] = new fileaddon_'.$filetype.'();');
		}
		return $this->_fileaddon[$filetype];
	}
}

/******************************************************************************
Description : interface for the addon-functions
Copyright   : Jürgen Brändle, 2003
Author      : Jürgen Brändle, braendle@web.de
Urls        : www.Sefrengo.de
Create date : 2003-07-27
Last update : 2003-12-06
			  function is_format_supported added
******************************************************************************/
class fileaddon {
	var $type     = '';	// filetype of addon-class
	var $errno    =  0;	// error handling
	var $fm       = '';	// filemanager object
	var $location = '';	// full filename including path
	var $idupl    =  0;	// id of file

	//
	// Contructor
	//
	function fileaddon() {
	}
	
	//
	// to be called for a file that will be added
	//
	function new_file() {
	}
	
	//
	// to be called for a file that will be deleted
	//
	function delete_file() {
	}
	
	//
	// to be called for a file that will be moved
	//
	function move_file() {
	}
	
	//
	// to be called for a file that will be copied
	//
	function copy_file() {
	}
	
	//
	// to be called for a file that has changed
	//
	function update_file() {
	}

	//
	// to be implemented for all classes that have to
	// deal with partial support for a file format in 
	// diffrent libraries
	// for example: gd < 1.6.2 supports GIF file format,
	//              gd > 1.6.2 suport for GIF is read only
	//
	// based on bug reporting of 
	//      Karsten Pawlik - www.xeinfach.de
	// 
	function is_format_supported() {
		return true;
	}

}

/******************************************************************************
Description : class for the addon-functions of image-files
Copyright   : Jürgen Brändle, 2003
Author      : Jürgen Brändle, braendle@web.de
Urls        : www.Sefrengo.de
Create date : 2003-07-27
Last update : 2003-12-06
			  function new_file, update_file updated to test file support, so 
			  any special file format may only overwrite is_format_supported
			  function is_format_supported added
******************************************************************************/
class fileaddon_bilder extends fileaddon {
	//
	// properties
	//
	var $type           = "bilder";
	var $img_lib        = '';
	var $img_lib_type   = '';
	var $thumbwidth     =  0;
	var $thumbheight    =  0;
	var $thumbext       = '';
	var $size           =  0;
	var $aspect_ratio   =  0;
	var $chmod_enabled  =  false;
	var $chmod_value    =  0777;

	//
	// constructor
	//
	function fileaddon_bilder() {
		global $cfg_client,$cfg_cms, $cms_image, $fm;

		require_once 'Image/Transform.php';

		$this->fm = $fm;
		
		$this->thumbext      = $cfg_client['thumbext'];
		$this->size          = $cfg_client['thumb_size'];
		$this->aspect_ratio  = (int) $cfg_client['thumb_aspectratio'];
		$this->chmod_enabled = ($cfg_cms['chmod_enabled'] == '1');
		$this->chmod_value   = intval($cfg_cms['chmod_value'], 8);

		$this->img_lib_type  = $cfg_cms['image_mode'];
		$this->img_lib       = ($cms_image) ? $cms_image: Image_Transform::factory($this->img_lib_type);
        if (!$this->thumbext) $this->thumbext = "_cms_thumb";
	}

	//
	// public methods
	//
	
	//
	// new_file()
	//
	function new_file() {
		$param = func_get_arg(0);

		if (is_array($param)) {
			$this->location = $param['location'];
			$this->idupl = $param['idfile'];
			
			if ($this->is_format_supported()) $this->resize_image(false);
			$this->set_image_info();
		}
	}
	
	//
	// delete_file()
	//
	function delete_file() {
		$param = func_get_arg(0);

		if (is_array($param)) {
			$this->location = $param['location'];
			$this->idupl = $param['idfile'];
			$filename = $this->get_tumbnail_name();
			$this->fm->delete_file_fs($filename);
		}
	}
	
	//
	// move_file()
	//
	function move_file( $filelocation, $idupl, $filedestination ) {
		$this->location = $filelocation;
		$this->idupl = $idupl;

		// to be done
	}
	
	//
	// copy_file()
	//
	function copy_file( $filelocation, $idupl, $filedestination ) {
		$this->location = $filelocation;
		$this->idupl = $idupl;

		// to be done
	}
	
	//
	// update_file()
	//
	function update_file() {
		$param = func_get_arg(0);

		if (is_array($param)) {
			$this->location = $param['location'];
			$this->idupl = $param['idfile'];

			if ($this->is_format_supported()) {
				// Löschen des vorhandenen Thumbnails
				$filename = $this->get_tumbnail_name();
				$this->fm->delete_file_fs($filename);
				// neues Thumbnail erstellen
				$this->resize_image(true);
			}
			$this->set_image_info();
		}
	}

		//
	// Check if the filename contains the thumbnail extention as specified in cms_values cfg_client['thumbext']
	// default: "_cms_thumb"
	//
	function is_tumbnail() {
		$file = basename($this->location);
		return (strpos(strtolower($file), $this->thumbext) != false);
	}

	//
	// get a new filename with the thumbnail-extention as specified in cms_values cfg_client['thumbext']
	// default: "_cms_thumb"
	//
	function get_tumbnail_name() {
		return ($this->fm->get_thumbnail_filename($this->location, $this->type, $this->thumbext));
	}

	//
	// save infos for images in the database
	//
	function set_image_info() {
		$filesize = filesize ($this->location);
		$filetime = filemtime($this->location);
		$imgsize  = getimagesize($this->location);
		$this->fm->update_file_sizes($this->idupl, $filesize, $filetime, $imgsize[0], $imgsize[1], $this->thumbwidth, $this->thumbheight);
		$this->errno = 0;
	}
	
	/**
	 * Erstellt ein Thumbnail und trägt die Daten des Thumbnails in die Datenbank ein
	 * Die Funktion prüft zunächst ob die Datei größer als die Thumbnail-Größe ist und ob es sich nicht um ein Thumbnail
	 * handelt. Trifft beides nicht zu, wird ein Thumbnail erzeugt, sofern keines existiert oder der Übergabeparameter $update
	 * auf true gesetzt ist.
	 * Das generierte Thumbnail bekommt an den Dateinamen ein spezielle Kennung angehängt, die standardmäßig "_cms_thumb" lautet,
	 * und wird im gleichen verzeichnis gespeichert wie die Originaldatei. Der Dateimanager ignoriert Dateien mit der Kennung beim
	 * Scan der Verzeichnisse.
	 *
	 * Wichtig: Bilder, die kleiner als die Thumbnail-Größe sind, werden nicht als Thumbnails angelegt!
	 *
	 * Für die Erzeugung des Bildes können verschiedene Optionen in den Einstellungen des Clients hinterlegt werden:
	 * Proportionen beibehalten
	 *   0: Breite und Höhe werden auf Thumbnailgröße gesetzt
	 *   1: Seitenverhältnis bleibt erhalten, die größere Seite wird die Thumbnail-Größe gesetzt
	 *   2: Breite wird festgelegt auf Thumbnail-Größe, Höhe wird proportional dazu skaliert
	 *   3: Höhe wird festgelegt auf Thumbnail-Größe, Breite wird proportional dazu skaliert
	 * Größe der Vorschaubilder
	 *   freiwählbar, Standard: 100 Pixel
	 * Dateikennung für generierte Thumbnails
	 *   freiwählbar, Standard: "_cms_thumb"
	 * "Thumbnails generieren für (wenn möglich)"
	 *   freiwählbar, Angabe beinhaltet kommaseparierte List der Dateierweiterungen ohne Leerzeichen
	 *   Standard: gif,jpg,jpeg,png
	 *   Notwendig hierbei: für jeden Dateityp muss eine entsprechende Addon-Klasse im System vorhanden sein
	 *
	 * @param	boolean	$update 		true: Erzwingt die Erstellung eines Thumbnails
	 *
	 * @author	Jürgen Brändle
	 * @since	CMS 0.93.03 / ALPHA3SNAP3
	 * @version 0.6 / 20050309
	**/
	function resize_image($update) {
		$this->thumbwidth = 0;
		$this->thumbheight = 0;
		// generate thumbnails, if cms-value thumb_size is set to any value > 0
		if ($this->size > 0) {
			// generate thumbnails, if file is not a thumbnail
			if (!$this->is_tumbnail()) {
				$thumb_filename = $this->get_tumbnail_name();
				// Create thumbnail if pict is bigger than the thumbnail size
				$this->img_lib->load($this->location);
		        if ($this->size < $this->img_lib->img_x || $this->size < $this->img_lib->img_y) {
					if (!file_exists($thumb_filename) || $update) {
						// Resize image if new or update
						// Erweiterung Aspect-Ratio 2,3 auf Vorschlag von Mistral
						switch ($this->aspect_ratio) {
							case 0:
								$this->img_lib->resize($this->size, $this->size);	
								break;
							case 2:
								$this->img_lib->scaleByX($this->size);
								break;
							case 3:
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
					// Get thumbnail size data
					$this->img_lib->load($thumb_filename);
					$this->thumbwidth = $this->img_lib->img_x;
					$this->thumbheight = $this->img_lib->img_y;
					$this->img_lib->free();
				}
			}
		}
	}

	//
	// dummy function, must be overwritten were neccessary
	//
	function is_format_supported() {
		return true;
	}
}

/******************************************************************************
Description : class for the addon-functions of jpeg-files
Copyright   : Jürgen Brändle, 2003
Author      : Jürgen Brändle, braendle@web.de
Urls        : www.Sefrengo.de
Create date : 2003-07-27
Last update : 2003-07-27
******************************************************************************/
class fileaddon_jpeg extends fileaddon_bilder {
	//
	// properties
	//
	var $type           = "jpeg";

	//
	// constructor
	//
	// to do:
	// prüfe ob image-library notwendige functionen unterstützt
	function fileaddon_jpeg() {
		parent::fileaddon_bilder();
	}
}

/******************************************************************************
Description : class for the addon-functions of jpg-files
Copyright   : Jürgen Brändle, 2003
Author      : Jürgen Brändle, braendle@web.de
Urls        : www.Sefrengo.de
Create date : 2003-07-27
Last update : 2003-07-27
******************************************************************************/
class fileaddon_jpg extends fileaddon_bilder {
	//
	// properties
	//
	var $type           = "jpg";

	//
	// constructor
	//
	// to do:
	// prüfe ob image-library notwendige functionen unterstützt
	function fileaddon_jpg() {
		parent::fileaddon_bilder();
	}
}

/******************************************************************************
Description : class for the addon-functions of jpeg-files
Copyright   : Jürgen Brändle, 2003
Author      : Jürgen Brändle, braendle@web.de
Urls        : www.Sefrengo.de
Create date : 2003-07-27
Last update : 2003-07-27
******************************************************************************/
class fileaddon_png extends fileaddon_bilder {
	//
	// properties
	//
	var $type           = "png";

	//
	// constructor
	//
	// to do:
	// prüfe ob image-library notwendige functionen unterstützt
	function fileaddon_png() {
		parent::fileaddon_bilder();
	}
}

/******************************************************************************
Description : class for the addon-functions of jpeg-files
Copyright   : Jürgen Brändle, 2003
Author      : Jürgen Brändle, braendle@web.de
Urls        : www.Sefrengo.de
Create date : 2003-07-27
Last update : 2003-12-06
			  function new_file, update_file removed
			  function is_format_supported added
******************************************************************************/
class fileaddon_gif extends fileaddon_bilder {
	//
	// properties
	//
	var $type           = "gif";

	//
	// constructor
	//
	// to do:
	// prüfe ob image-library notwendige functionen unterstützt
	function fileaddon_gif() {
		parent::fileaddon_bilder();
	}

	//
	// public methods
	//

	//
	// is_format_supported()
	//
	// test if gd image library is used and if gd supports 
	//
	function is_format_supported() {
		if ($this->img_lib_type == 'gd') {
			return (method_exists($this->img_lib, "imagegif"));
		}
		return true;
	}
}
?>