<?php
// File: $Id: class.SF_SYSTEM_COMPAT_Php.php 28 2008-05-11 19:18:49Z mistral $
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
// + Description: The Php Compat Class
// +----------------------------------------------------------------------+
// + Changes:
// +----------------------------------------------------------------------+
// + ToDo:
// +----------------------------------------------------------------------+
/**
* The Php Compat Class
*
* This Class provide the general SF_SYSTEM_COMPAT_Php.
* This Class is emulate the Compability to Php 5.1
*
* @package SYSTEM , the Common SYSTEM Package.
* @name SF_SYSTEM_COMPAT_Php
*/
class SF_SYSTEM_COMPAT_Php extends SF_API_Object { 
    /**
    * Object version
    *
    * This string identify the SF_API_Object Version.
    *
    * @param  string
    */
    var $_API_object_version = '$Revision: 28 $';
    
    /**
    * OS
    *
    * The OS identifier.
    * @param  string
    */
    var $compat_os = '';
    
    /**
    * Path
    *
    * The Path identifier.
    * @param  string
    */
    var $compat_path = '';
    
    /**
    * Compat Extension
    *
    * The Extension identifier.
    * @param  string
    */
    var $compat_extension = 'php';
    
    /**
    * Compat Files
    *
    * The File Store.
    * @param  array
    */
    var $compat_files = array();
    
    /**
    * Copat Dir
    *
    * The Compat Dirs to include.
    * @param  array
    */
    var $compat_dir = array('Constant', 'Function');
    
    /**
    * Common Class Constructor
    *
    * The Class Constructor.
    *
    */
    function __construct() {
        $this->compat_os = strtoupper(substr(PHP_OS, 0, 3) == 'WIN') ? 'win' : 'other';
        $this->compat_path = str_replace ('\\', '/', dirname(__FILE__) . '/');
        while (list($k, $d) = each($this->compat_dir)) $this->_call_files($d);
    }
    
    /**
    * Call Compat Files
    * 
    * Load any available Compat File,
    * determinate with $dir.
    * 
    * @access protected 
    * @input (string) $dir
    */
    function _call_files($dir = '') {
        if ($dir == '') return;                
        $path = $this->compat_path . $dir;
        if ( $this->compat_os == 'win' ) $this->_dir = str_replace('/', '\\', $path);
        clearstatcache();
        if ( !is_dir($path) ) {
            return false;
		} else { 
		    clearstatcache();
            $_dh = opendir($path);
			while (gettype($_file = readdir($_dh)) != 'boolean') {
				if (is_readable($path."/$_file")) {
					if (($_ext = substr(strrchr($_file, "."), 1)) == $this->compat_extension) {
						$_name = str_replace('.'.$_ext,'', $_file);
						$this->compat_files["$_name"] = array('file' => $_file, 'mode' => false, 'name' => $_name);
						$_mode = strpos($_code = $this->_read_file($path."/$_file"), '<?php') !== false ? 'php' : 'config';
						$this->_files["$_name"]['mode'] = $_mode;
						if ($_mode == 'php') {
						    $this->_run_php($_code); 
                        } else {
                            require_once $path."/$_file";                            
                        }
					}
				}
				clearstatcache();
			}
		}
	}
	
	/**
    * Read Compat Files
    * 
    * Read any File,
    * determinate with $file.
    * 
    * @access protected 
    * @input (string) $file
    */
    function _read_file($file) {
        @chmod($file, 0777);
        if (!@is_file($file)) return '-1';
        if (($handle = @fopen ($file, 'rb')) != false) {
            $filecontent = fread ($handle, filesize($file));
            fclose ($handle);
            return $filecontent;
        } return '-2';
    }
    
    /**
    * Run as Php
    * 
    * Parse Code as Php.
    * 
    * @access protected 
    * @input (string) $code
    */
    function _run_php($code) {
        return eval('?>' . $code);
    }
}
?>