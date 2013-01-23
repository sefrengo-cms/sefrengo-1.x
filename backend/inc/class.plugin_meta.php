<?PHP
// File: $Id: class.plugin_meta.php 28 2008-05-11 19:18:49Z mistral $
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

class plugin_meta {

	/*
	* public
	*/

	/*
	* vars
	*/

	// general plugin configuration!

	/*
	* enable functions for each client as a singel plugin
	* this feature supports sql-statements for each client
	*/
	var $multi_client = false;

	/*
	* enable auto load of plugin settings on Sefrengo startup
	* this feature supports cms_values with group_named settings
	* sample:
	*        dirname = /myplugin
	*        group_name = myplugin
	*        $cfg_myplugin = array()
	*
	*/
	var $auto_settings = false;

    /*
	* enable this file for auto. Updates
	* this feature supports cms 1.0
	*
	*/
	var $auto_update = true;
	
	/*
	 * set this true to load the langfile for the backend automaticly
	 * this feature is supported since sefrengo 1.4
	 */
	var $auto_langfile = false;

	/*
	* simple set of the realname
	* sample:
	*        dirname = /myplugin
	*        root_name = myplugin
	*
	*/
	var $root_name = '';

	// standart vars for local use
	var $cfg_client = ''; // containing the client vars
	var $dir_name   = ''; // containing the absolute directory
	var $cfg_cms   = ''; // containing the cms vars
	var $is_unix    = ''; // containing the OS identifer
	var $cms_db    = ''; // containing the db vars
	var $client     = ''; // containing the client identifer
	var $perm       = ''; // access 2 perm class
	var $lang       = ''; // containing the lang identifer
	var $rep        = ''; // access 2 repository class
	var $db			= ''; // access 2 db class

    /*
     * constructor
     */

	function plugin_init($call_files = false)
	{
		global $rep, $cfg_cms, $db, $cms_db, $cfg_client, $perm;
		
		
		$this->db 	  	    = $db = new DB_cms;;
		$this->rep 			= $rep;
		$this->perm         = $perm;
        $this->lang         = $this->perm->get_lang();
		$this->client       = $this->perm->get_client();
        $this->cms_db 		= $cms_db;
        $this->is_unix      = $this->check_os();
		$this->cfg_cms 	    = $cfg_cms;
		$this->cfg_client 	= $cfg_client;
		$this->_files 		= array(
                              $this->_install   => $this->_install,
							  $this->_uninstall => $this->_uninstall,
							  $this->_update 	=> $this->_update,
							  $this->_config    => $this->_config,
                        	  $this->_settings  => $this->_settings,
							  $this->_module    => array(
							   					   $this->_install   => '3',
												   $this->_uninstall => '5',
												   $this->_update 	 => '6',
												   $this->_config    => '7'));
		//langfiles
		if ($this->auto_langfile) {
			$this->load_langstrings();
		}
		
		if ( $call_files === true ) {
			$this->_call_files();
		}
	}

    /*
     * functions
     */

	// general execute
	function install($order = '')
	{
		return $this->_what($this->_install,   $order);
	}
	
	function client_install($order = '')
	{
        return $this->_execute_metafile('client_install.meta');
	}
		
	function uninstall($order = '')
	{
		return $this->_what($this->_uninstall, $order);
	}
	
	function client_uninstall($order = '')
	{
		return $this->_execute_metafile('client_uninstall.meta');
	}
	
	function update($order = '')
	{
		return $this->_what($this->_update,    $order);
	}
	
	function client_update($order = '')
	{
		return $this->_execute_metafile('client_update.meta');
	}
	
	function config($order = '')
	{
		return $this->_what($this->_config,    $order);
	}

	// general init settings
	function init_settings()
	{
	    $order = $this->_settings;
		return $this->_init_settings($order);
	}

	// general get/set_vars
	function set($var, $val = '')
    {
        $this->$var = $val;
        return $this->get($var);
    }
    
    function get($var)
    {
        return $this->$var;
    }

	// general get file content
	function get_this($what, $order = '')
	{
		if ($order == '') $order = $this->_meta;
		return $this->_get($order, $what);
	}
	
	// general update file content
	function update_this($what, $string = '', $order = '')
	{
		if ($order == '') $order = $this->_meta;
		return $this->_update_file($order, $what, $string);
	}

	// some misc
	function version()
	{
		return $this->__version;
	}
	function multi_client()
	{
		return $this->multi_client;
	}
	function auto_settings()
	{
        return $this->auto_settings;
	}
	function check_os() 
    {
        if (strtoupper(substr(PHP_OS, 0, 3) == 'WIN')) return false;
        elseif (PATH_SEPERATOR == ";") return false;
        return true;
    }
    
    //load langstrings
    function load_langstrings()
    {
    	global $cms_lang, $cfg_cms;
    	
    	$lang_file = $cfg_cms['cms_path'].'plugins/'.$this->root_name.'/lang/'.$cfg_cms['backend_lang'].'/lang_general.php';
		$lang_default_file = $cfg_cms['cms_path'].'plugins/'.$this->root_name.'/lang/de/lang_general.php';
		
		if (file_exists($lang_file)) {
			include_once($lang_file);
		} else if (file_exists($lang_default_file)) {
			include_once($lang_default_file);
		}	
    }

    /*
     * privat
     */

    /*
     * vars
     */

	var $__version 		= '1.4';        // version of this class
	var $__mode    		= array(  		// default mode set
	 				      'sql' => '1',
						  'php' => '2',
						  'xml' => '3',
						  'txt' => '4');
	var $_uninstall 	= 'uninstall';  // varname of 'uninstall' script
	var $_install 	    = 'install';    // varname of 'install' script
	var $_config    	= 'config'; 	// varname of 'config' script
	var $_update    	= 'update'; 	// varname of 'update' script
	var $_meta_ext      = 'meta';       // varname of the metafile extension
	var $_xml_ext = array('cmsmod',     // varname of the cmsmodfile extension
                          'dedimod');   // varname of the dedimodfile extension
	var $_settings      = 'database';   // functionality of plugin settings (database = read settings from dB|settings = read settings from file)
	var $_module        = 'module';     // functionality of module extraction
	var $_meta     		= 'meta';       // functionality of meta extraction
	var $_files     	= array();     	// containing the additinal file information
	var $_modul_dir  	= '/module';    // varname of '/module' directory
	var $_meta_dir    	= '/meta';      // varname of '/meta' directory
	var $_dir			= '';           // containing the name of local directory
	var $_xml           = array();      // containing all xml Files

    /*
     * functions
     */

	function _what($what, $order = '')
	{
		$return2 = true;
        if ($order == '') {
			$order = $this->_meta;
			$return2 = $this->_do($this->_module, $what);
		}
		if($return2) $return  = $this->_do($order, $what);
		return $return;
	}
	function _call_files($dir = '', $mode = 0777)
	{
        if ( $dir == '' ) $dir = $this->_meta_dir;                
        $this->_dir = $this->dir_name . $dir;
        if ( !$this->is_unix ) $this->_dir = str_replace('/', '\\', $this->_dir);
        clearstatcache();
        if ( !is_dir( $this->_dir ) ) {
            if ($this->is_unix) {
                umask(000) and @mkdir( $this->_dir, $mode );
                return ( $this->_call_files( $dir, $mode ) );
            } else {
                @mkdir( $this->_dir, $mode );
                return ( $this->_call_files( $dir, $mode ) );
            }
		} else { 
		    clearstatcache();
            $_dh = opendir ( $this->_dir );
			while ( gettype ( $_file = readdir ( $_dh ) ) != 'boolean' ) {
				if ( is_readable ( $this->_dir."/$_file" ) ) {
					if ( strtolower($_ext = substr ( strrchr( $_file, "." ), 1) ) == $this->_meta_ext) {
						$_name = str_replace ( '.'.$_ext,'', $_file );
						$this->_files["$_name"] = array('file' => $_file, 'mode' => false);
						$_mode = ( preg_match('/^<\?php/is', $this->_get( $this->_meta, $_name) ) )  ? $this->__mode['php'] : $this->__mode['sql'];
						$this->_files["$_name"]['mode'] = $_mode;
					} elseif ( in_array(strtolower($_ext = substr ( strrchr( $_file, "." ), 1) ), $this->_xml_ext)) {
                        $_name = str_replace ( '.'.$_ext,'', $_file );
                        $this->_files["$_name"] = array('file' => $_file, 'mode' => $this->__mode['xml'], 'name' => $_name);
                        $this->_files[$this->_module]["$_name"] = $this->_files["$_name"];
                        $this->_xml[] = $this->_files["$_name"];
					}
				}
				clearstatcache();
			}
		}
	}
	function _do($order, $what)
	{
        $_dir = $order == $this->_meta ? $this->_meta_dir : $this->_modul_dir;
        $this->_call_files($_dir);
        if ($order == $this->_meta) {
			$_what 	 = $this->_files["$what"]['mode'];
			$_source = $this->_get( $order, $what );
		} elseif ($order == $this->_module) {
			$_what   = $this->_files["$order"]["$what"];
			$_source = $this->_get( $order, 'all' );
		}
		switch ( $_what ) {
			case 1:
			    return $this->rep->bulk_sql		($_source);
				break;
			case 2:
			    return $this->rep->run_php		($_source);
				break;
			case 3:
			    return $this->rep->bulk_mod_import($_source, 0);
				break;
			case 4:
			    return $this->rep->print_txt	($_source);
				break;
            case 5:
			    return $this->rep->uninstall_xml($_source);
				break;
            case 6:
			    return $this->rep->update_xml	($_source);
				break;
            case 7:
			    return true;
				break;
		}
	}
	function _get($order, $what = '')
	{
        $_dir = $order == $this->_meta ? $this->_meta_dir : $this->_modul_dir;
        $_file = $this->dir_name . $_dir .'/'. $this->_files["$what"]['file'];
        if ( !$this->is_unix ) $_file = str_replace('/', '\\', $_file);
        if ($order == $this->_meta && $what != 'all') {
		    return($this->rep->_file( $_file ));
		} elseif ($order == $this->_module && $what != 'all') {
            return($this->rep->_file( $_file ));
		} elseif ($order == $this->_module) {
            if (is_array($this->_xml)) foreach ($this->_xml as $_modul) {
				$_fmod = $this->dir_name . $_dir .'/'. $_modul['file'];
                if ( !$this->is_unix ) $_fmod = str_replace('/', '\\', $_fmod);
                $_modul['xml'] = $this->rep->cms_mod(($_modul['content'] = $this->rep->_file( $_fmod )));
				$_return[] = $_modul;
			}
            return $_return;
		}
	}
	function _update_file($order, $what, $string = '')
	{
        $_dir = $order == $this->_meta ? $this->_meta_dir : $this->_modul_dir;
        $this->_call_files($_dir);
        $_rebuild = false;
		$order_ext = '_'.$order.'_ext';
		if ( is_array($this->_files["$what"]) ) {
			$_file = $this->_files["$what"]['file'];
		} else {
	 		$_file = $this->_files["$what"].'.'.$this->{$order_ext};
	 		$_rebuild = true;
		}
		$_delete = $this->_dir."/$_file";
		if ( !$this->is_unix ) $_delete = str_replace('/', '\\', $_delete);
		if ( $string == '' ) {
			$return = $this->rep->_delete( $_delete );
			$_rebuild = true;
		} else {
			$return = $this->rep->_write( $_delete, $string );
		}
		if ( $_rebuild === true ) $this->_call_files($_dir);
  		return $return;
	}
	
	function _init_settings($order)
	{
		
	    switch ($order) {
            case 'database':
                return $this->rep->_init_settings($this->root_name);
                break;
            case 'settings':
                return $this->get_this($order);
                break;
            }
	}
	
	function _execute_metafile($file) {
		$_file = $this->dir_name . $this->_meta_dir .'/'.$file;
	    if ( !$this->is_unix ) {
	    	$_file = str_replace('/', '\\', $_file);
	    }	    
	    $_source = lib_read_file($_file);
	    if ($_source != '-1' && $_source != '-2' ) {
	    	
	    	if (preg_match('!^<\?php!i', $_source)) {
	    		//PHP
	    		//return $this->rep->bulk_sql($_source);
	    		return $this->rep->run_php($_source);	
	    	} else { 
	    		//SQL
	    		return $this->rep->bulk_sql($_source);
	    	}
	    }
	    return true;
	}
}
?>