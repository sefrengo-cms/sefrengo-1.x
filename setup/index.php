<?PHP
// File: $Id: index.php 365 2011-05-19 18:30:35Z joern $
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
// + Autor: $Author: joern $
// +----------------------------------------------------------------------+
// + Revision: $Revision: 365 $
// +----------------------------------------------------------------------+
// + Description:
// +----------------------------------------------------------------------+
// + Changes: 
// +----------------------------------------------------------------------+
// + ToDo:
// +----------------------------------------------------------------------+
header('Content-type: text/html; charset=UTF-8');
class gb_template {
	function insert($Loop, $TemplateName, $ToInsert)
	{
		global $TemplateArray, $TemplateLoopArray;

		if ($Loop ==''){
			$TemplateArray[$TemplateName] = $ToInsert;
		}
		else{
			$TemplateLoopArray[$Loop][$TemplateName][] = $ToInsert;
		}
	}

	function make($File,$LangArray = '')
	{
		global $TemplateArray, $TemplateLoopArray;
        $LoopFinal = '';

		$Matrix = implode('', (file(dirname(__FILE__) . DIRECTORY_SEPARATOR . $File)));
		if (is_array($TemplateLoopArray)) {
			$KeysLoopname = array_keys($TemplateLoopArray);
			for ($f = 0; $f < count($KeysLoopname); $f++)
			{
				$Start = strpos($Matrix, "<!--{start:".$KeysLoopname[$f]."}-->");
				$Stop = strpos($Matrix, "<!--{stop:".$KeysLoopname[$f]."}-->");
				$LoopLength = $Stop - $Start;
				$Loop = substr( $Matrix, $Start, $LoopLength);
				$KeysLoopTemplate = array_keys($TemplateLoopArray[$KeysLoopname[$f]]);
				$KeysLoopValue = array_keys($TemplateLoopArray[$KeysLoopname[$f]][$KeysLoopTemplate[0]]);
				for ($t = 0; $t < count($KeysLoopValue); $t++)
				{
					$Loopb = $Loop;
					for ($s = 0; $s < count($KeysLoopTemplate); $s++)
					{
						$Loopb = str_replace("<!--{".$KeysLoopTemplate[$s]."}-->", $TemplateLoopArray[$KeysLoopname[$f]][$KeysLoopTemplate[$s]][$t], $Loopb);
					}
					$LoopFinal = $LoopFinal.$Loopb;
				}
				$Matrix = str_replace ($Loop, $LoopFinal, $Matrix);
				$Matrix = str_replace ("<!--{start:".$KeysLoopname[$f]."}-->", '', $Matrix);
				$Matrix = str_replace ("<!--{stop:".$KeysLoopname[$f]."}-->", '', $Matrix);
				$Matrix = str_replace ($Loop, $Start, $Matrix);
				$LoopFinal = '';
			}
		}
		if (is_array($TemplateArray)) {
			$Keys = array_keys($TemplateArray);
			for ($i = 0; $i < count($Keys); $i++) $Matrix = str_replace("<!--{".$Keys[$i]."}-->", $TemplateArray[$Keys[$i]], $Matrix);
		}
		if (is_array($LangArray)) 
		{
			$Keys = array_keys($LangArray);
			for ($i = 0; $i < count($Keys); $i++) 
			{
			    $Matrix = str_replace("<!--[".$Keys[$i]."]-->", $LangArray[$Keys[$i]], $Matrix);
			}
		}
		return $Matrix;
	}

	function flush()
	{
		global $TemplateArray, $TemplateLoopArray;

		unset($TemplateArray);
		unset($TemplateLoopArray);
	}
}

class setup {
	var $globals;
	var $debug = false;
	var $mysql_con_handle;
	var $updatefiles = array(
		'updates.sql',
		'updates_from.00.97.00.sql',
		'updates_from.00.98.00.sql',
		'updates_from.00.99.00.sql',
		'updates_from.01.00.00.sql',
		'updates_from.01.00.01.sql',
		'updates_from.01.00.02.sql',
		'updates_from.01.00.03.sql',
		'updates_from.01.01.90.sql',
		'updates_from.01.01.91.sql',
		'updates_from.01.01.92.sql',
		'updates_from.01.02.00.sql',
		'updates_from.01.02.01.sql',
		'updates_from.01.02.02.sql',
		'updates_from.01.03.00.sql',
		'updates_from.01.03.01.sql',
		'updates_from.01.04.00.sql',
		'updates_from.01.04.01.sql',
		'updates_from.01.04.02.sql',
		'updates_from.01.04.03.sql',
		'updates_from.01.04.04.sql',
		'updates_from.01.04.05.sql',
		'updates_from.01.04.06.sql',
		'updates_from.01.05.00.sql',
		'updates_from.01.05.01.sql',
		'updates_from.01.06.00.sql',
		'updates_from.01.06.01.sql',
		'updates_from.01.06.02.sql',
		'updates_from.01.06.03.sql',
		'updates_from.01.06.04.sql',
		'updates_from.01.06.05.sql'
	);

	/**
	* Konstruktor. Catch all globals
	*/
	function __construct()
	{
		$this -> catch_globals();
		$this -> version['prior'] = '01';
		$this -> version['minor'] = '06';
		$this -> version['fix']   = '06';
		$this -> version_text = $this -> version['prior'];
		$this -> version_text .= '.';
		$this -> version_text .= $this -> version['minor'];
		$this -> version_text .= '.';
		$this -> version_text .= $this -> version['fix'];
		//manipulate some actions if user chose updat
		if ($this -> globals['mode'] == 'update') {
			//seperate update finish screen
			if ($this -> globals['action'] == 'enter_email') $this -> globals['action'] = 'screen_execute_update_and_finish';
		}
	}

	/**
	* Manage the setup
	*
	* @return string specific HTML- screen
	*/
	function make_setup()
	{
        /* @var array $cms_lang */
        /* @var array $template_lang */
		if ($this -> globals['action'] != '') {
			include_once('templates/lang/'. $this -> globals['lang'] . '.php');
			$this -> cms_lang = $cms_lang;
			$this -> template_lang = $template_lang;
		} else {
			include_once('templates/lang/de.php');
            $this -> cms_lang = $cms_lang;
            $this -> template_lang = $template_lang;
		}
		switch ($this -> globals['action'])
		{
			case 'screen_license':
				$return_this = $this -> screen_license();
				break;
			case 'screen_pretest':
				$return_this = $this -> screen_pretest();
				break;
			case 'screen_test':
				$return_this = $this -> screen_test();
				break;
			case 'screen_chose_setup_kind':
				$return_this = $this -> screen_chose_setup_kind();
				break;
		    case 'screen_enter_path':
				$return_this = $this -> screen_enter_path();
				break;
		    case 'screen_validate_path':
				$return_this = $this -> screen_validate_path();
				break;
			case 'screen_enter_admin_account':
				$return_this = $this -> screen_enter_admin_account();
				break;
			case 'validate_admin_pass':
				$return_this = $this -> validate_admin_pass();
				break;
			case 'screen_enter_mysql_data':
				$return_this = $this -> screen_enter_mysql_data();
				break;
			case 'screen_ready_to_insert_sql':
				$return_this = $this -> screen_ready_to_insert_sql_dump();
				break;
			case 'screen_check_version':
				$return_this = $this -> manage_insert_sql_dump();
				$return_this .= $this -> screen_check_version();
				break;
			case 'screen_utf8_convert':
				$return_this = $this -> screen_utf8_convert();
				break;
			case 'screen_download_config':
				$return_this = $this -> manage_insert_sql_dump();
				$return_this .= $this -> screen_download_config();
				break;
			case 'screen_finish':
				$return_this = $this -> screen_finish();
				break;
			case 'screen_finish_update':
				$return_this = $this -> screen_finish_update();
				break;
			case 'screen_execute_update_and_finish':
				$return_this = $this -> screen_execute_update_and_finish();
				break;
			case 'make_cfg_general':
				$return_this = $this -> make_cfg_general();
				break;
			default:
				$return_this = $this -> screen_welcome();
		}
		return $return_this;
	}

	/**
	* Catch POST and GET - Statements
	*/
	function catch_globals()
	{
        if (!empty($_GET)) {
            foreach ($_GET as $key => $value) {
                $this->globals[$key] = $value;
			}
        }

        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                $this->globals[$key] = $value;
            }
        }

        if (!empty($_SERVER)) {
            foreach ($_SERVER as $key => $value) {
                $this->globals[$key] = $value;
            }
        }
	}

	/**
	* Make the Welcome screen. If it supported in future versions
	* you can chose your language here (at the moment only german)
	*
	* @return string comple HTML welcome screen
	*/
	function screen_welcome()
	{
 		$tpl = new gb_template();
		$tpl -> insert('', 'version', $this -> version_text);
		$tpl -> insert('', 'next_step', 'screen_license');
		return $tpl -> make('templates/welcome.tpl',$this -> template_lang);
	}


    function test_config($boolean) {
        $tpl = '';
        if ($boolean) {
            $tpl .= '<p>';
            $tpl .= 'Info &raquo;';
            $tpl .= '</p>';
            $tpl .= '<img src="templates/img/ok.gif" alt="ok" title="ok" />';
        } else {
            $tpl .= '<p>';
            $tpl .= '<a href="#" onclick="javascript:sf_reload()">'.$this -> cms_lang['again'].'</a>';
            $tpl .= '</p>';
            $tpl .= '<img src="templates/img/warning.gif" alt="warning" title="warning" />'; 
        }
        return $tpl;
    }

    function test_config_text($boolean) {
  		$tpl = '';
        if ($boolean) {
            $tpl .= '';
        } else {
            $tpl .= ' warning'; 
        }
        return $tpl;
    }

    function test_config_style($boolean) {
  		$tpl = '';
        if ($boolean) {
            $tpl .= 'display:none';
        } else {
            $tpl .= ''; 
        }
        return $tpl;
    }

    function test_get_php_config_value($value) {
    	if (ini_get($value) == '1') 
    	{
    		return 'ON';
    	} else {
    		return 'OFF';
    	}
    }

	/**
	* Make the Pre-Test screen. 
	*
	* @return string comple HTML welcome screen
	*/
	function screen_pretest()
	{
		$tpl = new gb_template();
		
		$tpl -> insert('', 'version', $this -> version_text);
		$tpl -> insert('', 'lang', $this -> globals['lang']);
		$tpl -> insert('', 'next_step', 'screen_chose_setup_kind');
        
        // PHP Version		
		$tpl -> insert('test_PHP_check', 'name_PHP_check' , 'PHP version >= 5.6.0' );
		$tpl -> insert('test_PHP_check', 'value_PHP_check', $this -> test_config( version_compare(phpversion(), '5.6.0') <= 0?0:1  ));
		$tpl -> insert('test_PHP_check', 'class_PHP_check', $this -> test_config_text(version_compare(phpversion(), '5.6.0') <= 0?0:1 ));
		$tpl -> insert('test_PHP_check', 'style_PHP_config', $this -> test_config_style(version_compare(phpversion(), '5.6.0') <= 0?0:1 ));
		$tpl -> insert('test_PHP_check', 'desc_PHP_check' , $this -> cms_lang['pretest_version'].phpversion() );
		$tpl -> insert('test_PHP_check', 'id_PHP_check' , 'version' );
		$tpl -> insert('test_PHP_check', 'info' , $this -> cms_lang['info'] );

        // mysql support		
		$tpl -> insert('test_PHP_check', 'name_PHP_check' , 'mysql support' );
		$tpl -> insert('test_PHP_check', 'value_PHP_check', $this -> test_config(extension_loaded( 'mysqli' )));
		$tpl -> insert('test_PHP_check', 'class_PHP_check', $this -> test_config_text(extension_loaded( 'mysqli' )));
		$tpl -> insert('test_PHP_check', 'style_PHP_config', $this -> test_config_style(extension_loaded( 'mysqli' )));
		$tpl -> insert('test_PHP_check', 'desc_PHP_check' , $this -> cms_lang['pretest_MySQL'] );
		$tpl -> insert('test_PHP_check', 'id_PHP_check' , 'mysql' );
		$tpl -> insert('test_PHP_check', 'info' , $this -> cms_lang['info'] );
        // zlib		
		$tpl -> insert('test_PHP_check', 'name_PHP_check' , 'zlib' );
		$tpl -> insert('test_PHP_check', 'value_PHP_check', $this -> test_config(extension_loaded('zlib')));
		$tpl -> insert('test_PHP_check', 'class_PHP_check', $this -> test_config_text(extension_loaded('zlib')));
		$tpl -> insert('test_PHP_check', 'style_PHP_config', $this -> test_config_style(extension_loaded('zlib')));
		$tpl -> insert('test_PHP_check', 'desc_PHP_check' , $this -> cms_lang['pretest_zlib'] );
		$tpl -> insert('test_PHP_check', 'id_PHP_check' , 'zlib' );
		$tpl -> insert('test_PHP_check', 'info' , $this -> cms_lang['info'] );
        // gdlib		
		$tpl -> insert('test_PHP_check', 'name_PHP_check' , 'gdlib' );
		$tpl -> insert('test_PHP_check', 'value_PHP_check', $this -> test_config(extension_loaded('gd')));
		$tpl -> insert('test_PHP_check', 'class_PHP_check', $this -> test_config_text(extension_loaded('gd')));
		$tpl -> insert('test_PHP_check', 'style_PHP_config', $this -> test_config_style(extension_loaded('gd')));
		$tpl -> insert('test_PHP_check', 'desc_PHP_check' , $this -> cms_lang['pretest_gdlib'] );
		$tpl -> insert('test_PHP_check', 'id_PHP_check' , 'gdlib' );
		$tpl -> insert('test_PHP_check', 'info' , $this -> cms_lang['info'] );
        // bclib BCMath support		
		$tpl -> insert('test_PHP_check', 'name_PHP_check' , 'zip support' );
		$tpl -> insert('test_PHP_check', 'value_PHP_check', $this -> test_config(extension_loaded('zip')));
		$tpl -> insert('test_PHP_check', 'class_PHP_check', $this -> test_config_text(extension_loaded('zip')));
		$tpl -> insert('test_PHP_check', 'style_PHP_config', $this -> test_config_style(extension_loaded('zip')));
		$tpl -> insert('test_PHP_check', 'desc_PHP_check' , $this -> cms_lang['pretest_zip']  );
		$tpl -> insert('test_PHP_check', 'id_PHP_check' , 'zip' );
		$tpl -> insert('test_PHP_check', 'info' , $this -> cms_lang['info'] );
		
        $php_settings = array(array ('Safe Mode','safe_mode','OFF',$this -> cms_lang['pretest_safe_mode']),
            array ('File Uploads','file_uploads','ON',$this -> cms_lang['pretest_file_uploads']),
        );
        foreach ($php_settings as $phpset) {
            $tpl -> insert('test_PHP_config', 'name_PHP_config' , $phpset[0] );
            $tpl -> insert('test_PHP_config', 'value_PHP_config', $phpset[2] );
            $temp_tpl = $this -> test_config ( $this -> test_get_php_config_value($phpset[1]) == $phpset[2] );
            $tpl -> insert('test_PHP_config', 'link_PHP_config' , $temp_tpl );
		    $tpl -> insert('test_PHP_config', 'class_PHP_config', $this -> test_config_text($this -> test_get_php_config_value($phpset[1]) == $phpset[2]));
    		$tpl -> insert('test_PHP_config', 'style_PHP_config', $this -> test_config_style($this -> test_get_php_config_value($phpset[1]) == $phpset[2]));
            $tpl -> insert('test_PHP_config', 'desc_PHP_config' , $phpset[3] );
            $tpl -> insert('test_PHP_config', 'id_PHP_config' , $phpset[1] );
            $tpl -> insert('test_PHP_config', 'info' , $this -> cms_lang['info'] );
        }
		return $tpl -> make('templates/screen_pretest.tpl',$this -> template_lang);
	}

    function test_folder( $tpl, $folder ) {
        //$is_unix=$_ENV["OSTYPE"]=="linux-gnu"?1:0;
        $is_unix = (PATH_SEPARATOR == ':')?1:0;
        $tpl -> insert('test_folder', 'name_folder' , $folder . '/' );
        if (file_exists( "../$folder" ))
        {
           $tpl -> insert('test_folder', 'desc_folder' , '['.substr( decoct( fileperms("../$folder" )), -4).'] ' );
            if(! $is_unix){
            	$tpl -> insert('test_folder', 'value_folder', $this -> cms_lang['test_system_test'] );
            	$tpl -> insert('test_folder', 'class_folder' , 'dbx-box' );
            }
            else if (is_writable( "../$folder" ))
            {
                $tpl -> insert('test_folder', 'value_folder', '<img src="templates/img/ok.gif" alt="ok" title="ok" />' );
            	$tpl -> insert('test_folder', 'class_folder' , 'dbx-box' );
            } else {
                $tpl -> insert('test_folder', 'value_folder', '<p><a href="#" onclick="javascript:sf_reload()">'.$this -> cms_lang['again'].'</a></p><img src="templates/img/warning.gif" alt="warning" title="warning" />' );
            	$tpl -> insert('test_folder', 'class_folder' , 'dbx-box warning' );
            }
        } else {
            $tpl -> insert('test_folder', 'value_folder', $this -> cms_lang['test_no_folder']);
            $tpl -> insert('test_folder', 'class_folder' , 'dbx-box' );
            $tpl -> insert('test_folder', 'desc_folder' , '' );
        }
    }

	/**
	* Make the Test screen.
	*
	* @return string comple HTML welcome screen
	*/
	function screen_test()
	{
		$tpl = new gb_template();

		$tpl -> insert('', 'version', $this -> version_text);
		$tpl -> insert('', 'lang', $this -> globals['lang']);
		
        $is_unix = (PATH_SEPARATOR == ':')?1:0;
        if ($is_unix)
        {
		    $this -> test_folder($tpl, 'backend/logs' );
		    $this -> test_folder($tpl, 'backend/plugins' );
		    $this -> test_folder($tpl, 'backend/upload/in' );
		    $this -> test_folder($tpl, 'backend/upload/out' );
            
            if (file_exists('../projekt01')) {
                $this -> test_folder($tpl, 'projekt01/cms/css' );
                $this -> test_folder($tpl, 'projekt01/cms/files' );
                $this -> test_folder($tpl, 'projekt01/cms/js' );
                $this -> test_folder($tpl, 'projekt01/media' );
                $this -> test_folder($tpl, 'projekt01/media/img' );
                $this -> test_folder($tpl, 'projekt01/media/pdf' );
                $this -> test_folder($tpl, 'projekt01/media/swf' );
                $this -> test_folder($tpl, 'projekt01/media/zip' );
                $this -> test_folder($tpl, 'projekt01/logs' );
            } else {
                $this -> test_folder($tpl, 'cms/css' );
                $this -> test_folder($tpl, 'cms/files' );
                $this -> test_folder($tpl, 'cms/js' );
                $this -> test_folder($tpl, 'media' );
                $this -> test_folder($tpl, 'media/img' );
                $this -> test_folder($tpl, 'media/pdf' );
                $this -> test_folder($tpl, 'media/swf' );
                $this -> test_folder($tpl, 'media/zip' );
                $this -> test_folder($tpl, 'logs' );
            }
        } else {
            $tpl -> insert('test_folder', 'name_folder' , '' );
            $tpl -> insert('test_folder', 'value_folder', $this -> cms_lang['test_system_test'] );
            $tpl -> insert('test_folder', 'class_folder' , 'dbx-box' );
            $tpl -> insert('test_folder', 'desc_folder' , '' );
        }

		
        // config.php		
        if (@file_exists('../backend/inc/config.php')) {
            $tpl -> insert('test_configuration', 'name_configuration' , 'config.php ' );
            $tpl -> insert('test_configuration', 'value_configuration', '<img src="templates/img/ok.gif" alt="ok" title="ok" />');
            $tpl -> insert('test_configuration', 'desc_configuration' , $this -> cms_lang['test_file_ok'] );
            $tpl -> insert('test_configuration', 'class_folder' , 'dbx-box' );
        } else {
            $tpl -> insert('test_configuration', 'name_configuration' , 'config.php ' );
            $tpl -> insert('test_configuration', 'value_configuration', '<p><a href="#" onclick="javascript:sf_reload()">'.$this -> cms_lang['again'].'</a></p><img src="templates/img/warning.gif" alt="warning" title="warning" />');
            $tpl -> insert('test_configuration', 'desc_configuration' , $this -> cms_lang['test_file_not_ok'] );
            $tpl -> insert('test_configuration', 'class_folder' , 'dbx-box warning' );
	    }
		return $tpl -> make('templates/screen_test.tpl',$this -> template_lang);
	}

	/**
	* Make screen where user can chose mysql-dump
	*
	* @return string complete HTML chose screen
	*/
	function screen_chose_setup_kind()
	{
		$tpl = new gb_template();
		$tpl -> insert('', 'version', $this -> version_text);
		$tpl -> insert('', 'lang', $this -> globals['lang']);
		$tpl -> insert('', 'next_step', 'screen_enter_path');
		return $tpl -> make('templates/chose_setup_kind.tpl',$this -> template_lang);
	}

	/**
	* Make screen where user must accept the GNU licende
	*
	* @return string complete HTML chose screen
	*/
	function screen_license()
	{
		$tpl = new gb_template();
        $fp = fopen('templates/gpl.txt', 'rb');
        $text = fread($fp, filesize('templates/gpl.txt'));
        fclose($fp);

		$tpl -> insert('', 'license', $text);
		$tpl -> insert('', 'version', $this -> version_text);
		$tpl -> insert('', 'lang', $this -> globals['lang']);
		$tpl -> insert('', 'next_step', 'screen_pretest');
		return $tpl -> make('templates/screen_license.tpl',$this -> template_lang);
	}

	/**
	* Make screen where user enter path informmation (root_path,
	* root_httpp_path, full_root_http_path)
	*
	* @return string complete HTML enter path screen
	*/
	function screen_enter_path()
	{
		$tpl = new gb_template();

		//make pathvariables
		if (!$_SERVER['PHP_SELF']) $_SERVER['PHP_SELF'] = $this -> globals['PHP_SELF'];
		if (!$_SERVER['HTTP_HOST']) $_SERVER['HTTP_HOST'] = $this -> globals['HTTP_HOST'];
		$root_path = str_replace ('\\', '/', dirname(__FILE__) . '/*');
		$root_path = str_replace('setup/*', '', $root_path);
        $root_http_path = str_replace('setup/index.php', '', $_SERVER['PHP_SELF']);
		$root_full_http_path = 'http://'.$_SERVER['HTTP_HOST'].$root_http_path;
    	$tpl -> insert('', 'path_error', '');
		$tpl -> insert('', 'path_class', '');
        $tpl -> insert('', 'root_path', $root_path);
		$tpl -> insert('', 'root_http_path', $root_http_path);
		$tpl -> insert('', 'root_full_http_path', $root_full_http_path);
		$tpl -> insert('', 'mode', $this -> globals['mode']);
		$tpl -> insert('', 'sql_target', $this -> globals['sql_target']);
		$tpl -> insert('', 'version', $this -> version_text);
		$tpl -> insert('', 'lang', $this -> globals['lang']);
		$tpl -> insert('', 'next_step', 'screen_validate_path');
		return $tpl -> make('templates/enter_path.tpl',$this -> template_lang);
	}

	/**
	* Validate path_values (root_path, root_http_path, root_full_http_path) and print
	* out error or the mysql data Screen.
	*
	* @return string complete HTML ready to enter mysql data or error screen
	*/
	function screen_validate_path()
	{
		$error = false;
		$tpl = new gb_template();

	        //check root_path
		if (!is_dir($this -> globals['root_path'])) {
			$target_tpl = "enter_path.tpl";
			$tpl -> insert('', 'path_error', $this -> cms_lang['path_error']);
			$tpl -> insert('', 'path_class', 'warning');
			$error = true;
		} 
		$tpl -> insert('', 'root_path', $this -> add_ending_slash($this -> globals['root_path']));
		$tpl -> insert('', 'root_http_path', $this -> add_ending_slash($this -> globals['root_http_path']));
		$tpl -> insert('', 'root_full_http_path', $this -> add_ending_slash($this -> globals['root_full_http_path']));
		$tpl -> insert('', 'sql_target', $this -> globals['sql_target']);
		$tpl -> insert('', 'version', $this -> version_text);
		$tpl -> insert('', 'lang', $this -> globals['lang']);
		$tpl -> insert('', 'mode', $this -> globals['mode']);

		if ($error) {
  			$tpl -> insert('', 'next_step', 'screen_validate_path');
			$target_tpl = "enter_path.tpl";
		} else {
			if (($this -> globals['sql_target'] == "updates.sql") || ($this -> globals['sql_target'] == "backup.sql")) {
				// Insert template dummies for some erros that can in occur screen_ready_to_insert_sql_dump()
				$tpl -> insert('', 'mode', 'update');
                $tpl -> insert('', 'host', 'localhost');
				$tpl -> insert('', 'db', '');
				$tpl -> insert('', 'prefix', 'cms_');
				$tpl -> insert('', 'user', '');
				$tpl -> insert('', 'pass', '');
				$tpl -> insert('', 'connection_error', '');
				$tpl -> insert('', 'connection_class', '');
	  			$tpl -> insert('', 'next_step', 'screen_ready_to_insert_sql');
				$target_tpl = "enter_mysql_data.tpl";
			}else {
				$tpl -> insert('', 'mode', 'normal');
				$tpl -> insert('', 'adminpass_error', '');
				$tpl -> insert('', 'adminpass_class', '');
				$tpl -> insert('', 'next_step', 'validate_admin_pass');
				$target_tpl = "enter_admin_account.tpl";
			}
		}
		return $tpl -> make('templates/' . $target_tpl,$this -> template_lang);
	}

	/**
	* Make screen where user enter administration passwort for admin account
	*
	* @return string complete HTML ready_to_insert_sql or error  screen
	*/
	function screen_enter_admin_account() {
		$tpl = new gb_template();
		$tpl -> insert('', 'mode', $this -> globals['mode']);
		$tpl -> insert('', 'sql_target', $this -> globals['sql_target']);
		$tpl -> insert('', 'version', $this -> version_text);
		$tpl -> insert('', 'lang', $this -> globals['lang']);
   		$tpl -> insert('', 'next_step', 'validate_admin_pass');
		$tpl -> insert('', 'root_path', $this -> globals['root_path']);
		$tpl -> insert('', 'root_http_path', $this -> globals['root_http_path']);
		$tpl -> insert('', 'root_full_http_path', $this -> globals['root_full_http_path']);
		return $tpl -> make('templates/enter_admin_account.tpl',$this -> template_lang);
	}

	// check admin_passwort for cms-account
	function validate_admin_pass() {
		$error = false;
		$tpl = new gb_template();
		if (empty($this -> globals['adminpass']) || $this -> globals['adminpass'] != $this -> globals['adminpass1'] || preg_match('/[\'\"\#\<\>]/i', $this -> globals['adminpass'])) {
			$tpl -> insert('', 'adminpass_error', $this -> cms_lang['adminpass_error']);
			$tpl -> insert('', 'adminpass_class', 'warning');
			$error = true;
		}
			$tpl -> insert('error', 'error_text2' , $this -> cms_lang['adminpass_error'] );
		$tpl -> insert('', 'mode', $this -> globals['mode']);
		$tpl -> insert('', 'sql_target', $this -> globals['sql_target']);
		$tpl -> insert('', 'version', $this -> version_text);
		$tpl -> insert('', 'lang', $this -> globals['lang']);
		$tpl -> insert('', 'root_path', $this -> globals['root_path']);
		$tpl -> insert('', 'root_http_path', $this -> globals['root_http_path']);
		$tpl -> insert('', 'root_full_http_path', $this -> globals['root_full_http_path']);
		$tpl -> insert('', 'adminpass', $this -> globals['adminpass']);

		// Insert template dummies for some erros that can in occur screen_ready_to_insert_sql_dump()
		$tpl -> insert('', 'host', 'localhost');
		$tpl -> insert('', 'db', '');
		$tpl -> insert('', 'prefix', 'cms_');
		$tpl -> insert('', 'user', '');
		$tpl -> insert('', 'pass', '');
		$tpl -> insert('', 'connection_error', '');
		$tpl -> insert('', 'connection_class', '');

		if ($error) {
  			$tpl -> insert('', 'next_step', 'validate_admin_pass');
			$target_tpl = "enter_admin_account.tpl";
		} else {
  			$tpl -> insert('', 'next_step', 'screen_ready_to_insert_sql');
			$target_tpl = "enter_mysql_data.tpl";
		}
		return $tpl -> make('templates/' . $target_tpl,$this -> template_lang);
	}

	/**
	* Make screen where user enter datbaseinformation (host,
	* database, user, password).
	*
	* @return string complete HTML enter_mysql_data screen
	*/
	function screen_enter_mysql_data()
	{
		$tpl = new gb_template();

		//check if user have chose "update"
		if ($this -> globals['sql_target'] == 'updates.sql') $this -> globals['mode'] = 'update';
		else $this -> globals['mode'] = 'normal';
		$tpl -> insert('', 'mode', $this -> globals['mode']);
		$tpl -> insert('', 'sql_target', $this -> globals['sql_target']);
		$tpl -> insert('', 'version', $this -> version_text);
		$tpl -> insert('', 'lang', $this -> globals['lang']);
   		$tpl -> insert('', 'next_step', 'screen_ready_to_insert_sql');
		$tpl -> insert('', 'root_path', $this -> globals['root_path']);
		$tpl -> insert('', 'root_http_path', $this -> globals['root_http_path']);
		$tpl -> insert('', 'root_full_http_path', $this -> globals['root_full_http_path']);
		$tpl -> insert('', 'adminpass', $this -> globals['adminpass']);

		// Insert template dummies for some erros that can in occur screen_ready_to_insert_sql_dump()
		$tpl -> insert('', 'host', '');
		$tpl -> insert('', 'db', '');
		$tpl -> insert('', 'prefix', 'cms_');
		$tpl -> insert('', 'user', '');
		$tpl -> insert('', 'pass', '');
		$tpl -> insert('', 'connection_error', '');
		$tpl -> insert('', 'connection_class', '');
		return $tpl -> make('templates/enter_mysql_data.tpl',$this -> template_lang);
	}

	/**
	* Validate User Data(host, database, prefix, user, password) and print out error
	* or the ready_to_insert_mysql Screen.
	*
	* @return string complete HTML ready_to_insert_sql or error  screen
	*/
	function screen_ready_to_insert_sql_dump()
	{
		$error = false;
		$tpl = new gb_template();

		// check table_prefix
		if (!preg_match('/^[a-zA-Z0-9-_]{1,1}[[:alnum:]_-]*$/', $this -> globals['prefix'])) {
			$tpl -> insert('', 'connection_error', $this -> cms_lang['connection_error1']);
		    $tpl -> insert('', 'connection_class', 'warning');
			$error = true;
		}

		//check host, username and password
		$con_handle = @mysqli_connect($this -> globals['host'],  $this -> globals['user'],  $this -> globals['pass']);
		if (empty($con_handle)) {
			$tpl -> insert('', 'connection_error', $this -> cms_lang['connection_error2']);
		    $tpl -> insert('', 'connection_class', 'warning');
			$error = true;
		} elseif (!mysqli_select_db($con_handle, $this -> globals['db'])) {
			$tpl -> insert('', 'connection_error', $this -> cms_lang['connection_error3']. $this -> globals[db] . $this -> cms_lang['connection_error4']);
		    $tpl -> insert('', 'connection_class', 'warning');
			$error = true;
		}

		$tpl -> insert('', 'root_path', $this -> globals['root_path']);
		$tpl -> insert('', 'root_http_path', $this -> globals['root_http_path']);
		$tpl -> insert('', 'root_full_http_path', $this -> globals['root_full_http_path']);
		$tpl -> insert('', 'host', $this -> globals['host']);
		$tpl -> insert('', 'db', $this -> globals['db']);
		$tpl -> insert('', 'user', $this -> globals['user']);
		$tpl -> insert('', 'pass', $this -> globals['pass']);
		$tpl -> insert('', 'sql_target', $this -> globals['sql_target']);
		$tpl -> insert('', 'version', $this -> version_text);
		$tpl -> insert('', 'lang', $this -> globals['lang']);
		$tpl -> insert('', 'mode', $this -> globals['mode']);
		$tpl -> insert('', 'adminpass', $this -> globals['adminpass']);
		if ($error) {
  			$tpl -> insert('', 'prefix', $this -> globals['prefix']);
  			$tpl -> insert('', 'next_step', 'screen_ready_to_insert_sql');
			$target_tpl = "enter_mysql_data.tpl";
		} else {
			//prefix check
			if(! preg_match('/(.*)_$/', $this -> globals['prefix'])){
				$this -> globals['prefix'] = $this -> globals['prefix'] .'_';
			}
			if($this -> globals['sql_target'] =='backup.sql'){
				//Backup
				$tpl -> insert('', 'prefix', $this -> globals['prefix']);
				$tpl -> insert('', 'next_step', 'screen_check_version');
				$target_tpl = "ready_to_insert_sql.tpl";
				
			} else if($this -> globals['sql_target'] =='updates.sql'){
				//Update
				$tpl -> insert('', 'prefix', $this -> globals['prefix']);
				$target_tpl = "ready_to_insert_sql.tpl";
				
				$arr_version = $this->get_cms_version();
				if (! is_array($arr_version )) {
					$tpl -> insert('', 'connection_error', $this -> cms_lang['connection_error5']);
		            $tpl -> insert('', 'connection_class', 'warning');
					$tpl -> insert('', 'next_step', 'screen_ready_to_insert_sql');
					$target_tpl = "enter_mysql_data.tpl";
				} else if ($this->compare_current_version_with('01', '01', '90') == 'higher') {
					$tpl -> insert('', 'prefix', $this -> globals['prefix']);
					$tpl -> insert('', 'next_step', 'screen_utf8_convert');
				} else {
					$tpl -> insert('', 'prefix', $this -> globals['prefix']);
					$tpl -> insert('', 'next_step', 'screen_download_config');
					$target_tpl = "ready_to_insert_sql.tpl";
				}
				
		
			}
			//normales setup
			else{
				$tpl -> insert('', 'prefix', $this -> globals['prefix']);
				$tpl -> insert('', 'next_step', 'screen_download_config');
				$target_tpl = "ready_to_insert_sql.tpl";
			}
		}
		return $tpl -> make('templates/' . $target_tpl,$this -> template_lang);
	}

	/**
	* Check Version after insert backup.
	*
	* @return string complete HTML check_version or error  screen
	*/
	function screen_check_version()
	{
		$tpl = new gb_template();

		$tpl -> insert('', 'root_path', $this -> globals['root_path']);
		$tpl -> insert('', 'root_http_path', $this -> globals['root_http_path']);
		$tpl -> insert('', 'root_full_http_path', $this -> globals['root_full_http_path']);
		$tpl -> insert('', 'host', $this -> globals['host']);
		$tpl -> insert('', 'db', $this -> globals['db']);
		$tpl -> insert('', 'user', $this -> globals['user']);
		$tpl -> insert('', 'pass', $this -> globals['pass']);
		$tpl -> insert('', 'sql_target', $this -> globals['sql_target']);
		$tpl -> insert('', 'version', $this -> version_text);
		$tpl -> insert('', 'lang', $this -> globals['lang']);
		$tpl -> insert('', 'mode', $this -> globals['mode']);
		$tpl -> insert('', 'adminpass', $this -> globals['adminpass']);
		$tpl -> insert('', 'prefix', $this -> globals['prefix']);
		$target_tpl = "screen_check_version.tpl";
			echo "<hr>";
			print_r($this->get_cms_version());
			print_r($this->compare_current_version_with($this -> version['prior'], $this -> version['minor'], $this -> version['fix']));
			echo "<hr>";
		
		if ($this->compare_current_version_with($this -> version['prior'], $this -> version['minor'], $this -> version['fix']) == 'higher') {
			$tpl -> insert('', 'prefix', $this -> globals['prefix']);
			$tpl -> insert('', 'sql_target', 'updates.sql');
			$tpl -> insert('', 'next_step', 'screen_ready_to_insert_sql');
		} else {
			return $this -> screen_download_config();
		}
		
		return $tpl -> make('templates/' . $target_tpl,$this -> template_lang);
	}

	
	//TODO
	function screen_utf8_convert() {

		$tpl = new gb_template();
		
		$tables = array('backendmenu', 'cat', 'cat_expand', 'cat_lang', 'cat_side', 'clients', 'clients_lang',
							'code', 'container', 'container_conf', 'content', 'content_external', 'css',
							'css_upl', 'db_cache', 'directory', 'filetype', 'groups', 'js', 'lang',
							'lay', 'lay_upl', 'mod', 'perms', 'plug', 'repository', 'sessions', 'side',
							'side_lang', 'tpl', 'tpl_conf', 'upl', 'uplcontent', 'users', 'users_groups',
							'values');
		
		foreach ($tables AS $k=>$v) {
			$tables[$k] = $this -> globals['prefix'] . $v;
		}
		
		$converter_config = array('host' => $this -> globals['host'],
						'database' => $this -> globals['db'],
						'username' => $this -> globals['user'],
						'password' =>  $this -> globals['pass'],
						'tables' => $tables,
						'mode' => 'encode',
						'js_at_end' => true,
						'current_table' => '',
						'current_limit_start' => 0,
						'current_limit_max' => 50);
		$config_string = base64_encode(serialize($converter_config));

		$tpl -> insert('', 'root_path', $this -> globals['root_path']);
		$tpl -> insert('', 'root_http_path', $this -> globals['root_http_path']);
		$tpl -> insert('', 'root_full_http_path', $this -> globals['root_full_http_path']);
		$tpl -> insert('', 'host', $this -> globals['host']);
		$tpl -> insert('', 'db', $this -> globals['db']);
		$tpl -> insert('', 'user', $this -> globals['user']);
		$tpl -> insert('', 'pass', $this -> globals['pass']);
		$tpl -> insert('', 'sql_target', $this -> globals['sql_target']);
		$tpl -> insert('', 'version', $this -> version_text);
		$tpl -> insert('', 'lang', $this -> globals['lang']);
		$tpl -> insert('', 'mode', $this -> globals['mode']);
		$tpl -> insert('', 'adminpass', $this -> globals['adminpass']);
		$tpl -> insert('', 'converter_url', 'tools/utf8_converter.php?action=start_from_external_config&amp;data='.$config_string);
		$tpl -> insert('', 'prefix', $this -> globals['prefix']);
		$tpl -> insert('', 'next_step', 'screen_download_config');

		return $tpl -> make('templates/utf8_convert.tpl',$this -> template_lang);
	}
	
	function compare_current_version_with($prior, $minor, $fix) {
		$versionarray = $this -> get_cms_version();
		
		// current version equals compare vals
		if ($versionarray['prior'] == $prior && $versionarray['minor'] == $minor
				 && $versionarray['fix'] == $fix) {
			return 'equal';		 	
		}
		
		//check if parm args are lower then the current version
		if($versionarray['prior'] > $prior){
			return 'lower';
		} else if($versionarray['prior'] == $prior){
			if($versionarray['minor'] >  $minor){
				return 'lower';
			} else if($versionarray['minor'] == $minor){
				if($versionarray['fix'] >  $fix)
					return 'lower';			
			}
		}
		
		//compared version is higher
		return 'higher';
		
		
	}

	/**
	* Give user the opinion to download the config.php
	*
	* @return string complete HTML download_config  screen
	*/
	function screen_download_config()
	{
		$tpl = new gb_template();

		if($this -> globals['mode'] == 'update') $tpl -> insert('', 'show_txt_only_by_update', $this -> cms_lang['show_txt_only_by_update']);
		else $tpl -> insert('', 'show_txt_only_by_update', '');
		$tpl -> insert('', 'root_path', $this -> globals['root_path']);
		$tpl -> insert('', 'root_http_path', $this -> globals['root_http_path']);
		$tpl -> insert('', 'root_full_http_path', $this -> globals['root_full_http_path']);
		$tpl -> insert('', 'host', $this -> globals['host']);
		$tpl -> insert('', 'db', $this -> globals['db']);
		$tpl -> insert('', 'prefix', $this -> globals['prefix']);
		$tpl -> insert('', 'user', $this -> globals['user']);
		$tpl -> insert('', 'pass', $this -> globals['pass']);
		$tpl -> insert('', 'email', $this -> globals['email']);
		$tpl -> insert('', 'sql_target', $this -> globals['sql_target']);
		$tpl -> insert('', 'version', $this -> version_text);
		$tpl -> insert('', 'lang', $this -> globals['lang']);
		$tpl -> insert('', 'next_step', 'screen_finish');
		$tpl -> insert('', 'mode', $this -> globals['mode']);
		$tpl -> insert('', 'adminpass', $this -> globals['adminpass']);
		return $tpl -> make('templates/download_config.tpl',$this -> template_lang);
	}

	/**
	* Setup is done. Throw out the finish screen
	*
	* @return string complete HTML finish screen
	*/
	function screen_finish()
	{
		$tpl = new gb_template();
		$tpl -> insert('', 'version', $this -> version_text);
		$tpl -> insert('', 'lang', $this -> globals['lang']);
		$tpl -> insert('', 'next_step', 'screen_test');
		return $tpl -> make('templates/finish.tpl',$this -> template_lang);
	}


	/**
	* Update is done. Throw out the finish screen
	* for update
	*
	* @return string complete HTML finish screen
	*/
	function screen_execute_update_and_finish()
	{
		$this -> _mysql_connect();
        $this -> insert_sql_dump();

		$tpl = new gb_template();
		$tpl -> insert('', 'version', $this -> version_text);
		return $tpl -> make('templates/finish_update.tpl',$this -> template_lang);
	}


	/**
	* Makes the config.php and send it to the user. Make use
	* of header functions.
	*
	* @return string config.php as downloadfile
	*/
	function make_cfg_general()
	{
		global $_SERVER;

		//make pathvariables for the configfile
		$cms_path = $this -> globals['root_path'];
		$client_path = $this -> globals['root_path'];
        if (!$_SERVER['HTTP_HOST']) $_SERVER['HTTP_HOST'] = $this -> globals['HTTP_HOST'];
        $_domain_arr = explode('.', $_SERVER['HTTP_HOST']);
        if (is_array($_domain_arr)) {
            $_domain_arr = array_slice ($_domain_arr, -2);
            $root_cookie_domain = implode('.', $_domain_arr);            
        } else $root_cookie_domain = '';    
        
		//make headers for download
		header("Content-Type: text/x-delimtext; name=\"config.php\"");
		header("Content-disposition: attachment; filename=config.php");

		//insert data
		$tpl = new gb_template();
		$tpl -> insert('', 'host', $this -> globals['host']);
		$tpl -> insert('', 'db', $this -> globals['db']);
		$tpl -> insert('', 'prefix', $this -> globals['prefix']);
		$tpl -> insert('', 'user', $this -> globals['user']);
		$tpl -> insert('', 'pass', $this -> globals['pass']);
		$tpl -> insert('', 'cms_path', $cms_path);
		//$tpl -> insert('', 'cms_http_path', $cms_http_path);
		//$tpl -> insert('', 'cms_cookie_domain', $root_cookie_domain);
        $tpl -> insert('', 'cms_full_http_path', $this -> globals['root_full_http_path']);
		$tpl -> insert('', 'client_path', $client_path);
		//$tpl -> insert('', 'client_http_path', $client_http_path);
		$tpl -> insert('', 'sql_target', $this -> globals['sql_target']);
		$tpl -> insert('', 'lang', $this -> globals['lang']);
		$tpl -> insert('', 'email', $this -> globals['email']);
		return $tpl -> make('templates/config.php.tpl',$this -> template_lang);
	}


    /**
     *
     */
    function insert_config_values()
	{
		global $_SERVER;

		//make pathvariables for the configfile
		$cms_path = $this -> globals['root_path'];
		$client_path = $this -> globals['root_path'];
        if (!$_SERVER['HTTP_HOST']) $_SERVER['HTTP_HOST'] = $this -> globals['HTTP_HOST'];
        $_domain_arr = explode('.', $_SERVER['HTTP_HOST']);
        if (is_array($_domain_arr)) {
            $_domain_arr = array_slice ($_domain_arr, -2);
            $root_cookie_domain = implode('.', $_domain_arr);            
        } else $root_cookie_domain = '';
		

		//insert data
		$tpl = new gb_template();
		$tpl -> insert('', 'host', $this -> globals['host']);
		$tpl -> insert('', 'db', $this -> globals['db']);
		$tpl -> insert('', 'prefix', $this -> globals['prefix']);
		$tpl -> insert('', 'user', $this -> globals['user']);
		$tpl -> insert('', 'pass', $this -> globals['pass']);
		$tpl -> insert('', 'cms_path', $cms_path);
		//$tpl -> insert('', 'cms_http_path', $cms_http_path);
		$tpl -> insert('', 'cms_full_http_path', $this -> globals['root_full_http_path']);
		$tpl -> insert('', 'cms_cookie_domain', $root_cookie_domain);
        $tpl -> insert('', 'client_path', $client_path);
		//$tpl -> insert('', 'client_http_path', $client_http_path);
		$tpl -> insert('', 'sql_target', $this -> globals['sql_target']);
		$tpl -> insert('', 'lang', $this -> globals['lang']);
		$tpl -> insert('', 'email', $this -> globals['email']);
		$sql_data = $tpl -> make('sql/config.sql');

		// replace prefix in sql statement
		$in = array("!ALTER TABLE cms_!",
	                "!ALTER TABLE `cms_!",
	                "!DELETE FROM cms_!",
                    "!DELETE FROM `cms_!",
		            "!UPDATE cms_!",
		            "!UPDATE `cms_!",
                    "!REPAIR TABLE `cms_!",
                    "!OPTIMIZE TABLE `cms_!",
                    "!DROP TABLE IF EXISTS cms_!",
			        "!CREATE TABLE cms_!",
			        "!CREATE TABLE `cms_!",
			        "!INSERT INTO `cms_!",
			        "!INSERT INTO cms_!");
		$out = array('ALTER TABLE '.$this -> globals['prefix'],
			         'ALTER TABLE `'.$this -> globals['prefix'],
			         'DELETE FROM '.$this -> globals['prefix'],
		             'DELETE FROM `'.$this -> globals['prefix'],
			         'UPDATE '.$this -> globals['prefix'],
			         'UPDATE `'.$this -> globals['prefix'],			         
                     'REPAIR TABLE `'.$this -> globals['prefix'],
                     'OPTIMIZE TABLE `'.$this -> globals['prefix'],
			         'DROP TABLE IF EXISTS '.$this -> globals['prefix'],
			         'CREATE TABLE '.$this -> globals['prefix'],
			         'CREATE TABLE `'.$this -> globals['prefix'],
			         'INSERT INTO `'.$this -> globals['prefix'],
			         'INSERT INTO '.$this -> globals['prefix']);
		$sql_data = preg_replace($in, $out, $sql_data);
		$sql_data = utf8_encode($sql_data);
		$sql_data = $this -> remove_remarks($sql_data);
		$sql_pieces = $this -> split_sql_file($sql_data, ';');
		$sql_count = count($sql_pieces);
		$con_handle = mysqli_connect($this -> globals['host'],  $this -> globals['user'],  $this -> globals['pass']);
		mysqli_select_db($con_handle, $this -> globals['db']);

		//DEBUGGING
		if ($this -> debug) echo "Auszuf&#252;hrende Config Values querys:  $sql_count <br><br>";

		for($i = 0; $i < $sql_count; $i++)
		{
			$sql = trim($sql_pieces[$i]);
			if (!empty($sql)) {
				mysqli_query($con_handle, $sql);

				//DEBUGGING
				if($this -> debug) {
					if(mysqli_error($con_handle) != '')	echo  $i+1 . ":  <font color='darkred'><b>FEHLER</b></font>  -->  " . mysqli_error($con_handle) . "<br>" . $sql . '<br><br>';
					else echo  $i+1 . ":   <font color= 'darkgreen'><b>AUSGEF&#220;HRT</b></font><br>". $sql . '<br><br>';
				}
			}
		}
	}
	
	function manage_insert_sql_dump(){
		if($this->globals['sql_target'] == 'updates.sql'){
			$versionarray = $this -> get_cms_version();
			$filecount = count($this->updatefiles);
					
			
			//compare version number, if cms version is greater then the newest versionnumber
			//of the updatefile, abort
			$fileversion = explode('.', $this->updatefiles[$filecount-1]);
			//print_r($versionarray);echo"<br>";
			//if system version is grater then updatefileversion abort 
			if($versionarray['prior'] > $fileversion['1']){
				return;
			} else if($versionarray['prior'] == $fileversion['1']){
				if($versionarray['minor'] >  $fileversion['2']){
					return;
				} else if($versionarray['minor'] == $fileversion['2']){
					if($versionarray['fix'] >  $fileversion['3'])
						return;					
				}
			}
			
			//figure out needed files for update
			for($i=$filecount; $i>0;$i--){
				$key = $i-1;
				$fileversion = explode('.', $this->updatefiles[$key]);
				if($versionarray['prior'] >=  $fileversion['1']
				   && $versionarray['minor'] >=  $fileversion['2']
				   && $versionarray['fix'] >=  $fileversion['3']){
					//$key is the startpoint for mutiple updates
					break;
				}
				//echo $this->updatefiles[$key] ."XXX<br>";
			}
			
			$return_this = '';
			
			//insert update files
			$this->_mysql_connect();
			for($i=$key; $i<$filecount; $i++){
				$basefile = str_replace('.sql', '', $this->updatefiles[$i]);
				$this->globals['sql_target'] = $this->updatefiles[$i];
				
				//global sql update
				$return_this .= $this->insert_sql_dump();
				
				//client sql update
				if( is_file('sql/' .$basefile . '_client.sql') ) {
					$result = mysqli_query( $this->mysql_con_handle, "SELECT idclient FROM ".$this -> globals['prefix'] .'clients');
					if (!$result) {
						die("Failed in get_table_content  - SELECT idclient FROM FROM ".$this->globals['prefix']."clients");
					}
				
					$idclients = array();
					while ($row = mysqli_fetch_array($result)) {
						$idclients[ $row['idclient'] ] = $row['idclient'];
					}
					mysqli_free_result($result);
					
					$this->globals['sql_target'] = $basefile . '_client.sql';
					
					foreach ($idclients AS $k=>$v) {
						$return_this .= $this->insert_sql_dump(array('idclient'=>$v));
					}
					
				}
				
				//lang sql update
				if( is_file('sql/' .$basefile . '_lang.sql') ) {
					$result = mysqli_query( $this->mysql_con_handle, "SELECT idclient, idlang FROM ".$this -> globals['prefix']."clients_lang");
					if (!$result) {
						die("Failed in get_table_content  - SELECT * FROM ".$this->globals['prefix']."clients_lang");
					}
				
					$replacements = array();
					while ($row = mysqli_fetch_array($result)) {
						$replacements[ $row['idlang'] ] = array('idclient'=>$row['idclient'], 'idlang'=>$row['idlang']);
					}
					mysqli_free_result($result);
					
					$this->globals['sql_target'] = $basefile . '_lang.sql';
					
					foreach ($replacements AS $k=>$v) {
						$return_this .= $this->insert_sql_dump($v);
					}
				}
								
				//eval php update
				$this->_include_php_updatefile($basefile);
				
				
			}
		}
		else{
			$return_this = $this->insert_sql_dump();
			if($this->globals['sql_target'] != 'backup.sql'){
				$return_this .= $this -> insert_config_values();//output only for debugging
			}
		}
		
		return $return_this;
	}
	
	function _include_php_updatefile($file) {
		if( is_file('sql/' .$file . '.php') ) {
			include 'sql/' .$file . '.php';
			return true;
		}
		
		return false;
	}
	
	
	function _mysql_connect() {
		if ( is_resource($this->mysql_con_handle) ) return;
        $con_handle =  mysqli_connect($this -> globals['host'], $this -> globals['user'], $this -> globals['pass']);
		mysqli_select_db( $con_handle, $this -> globals['db']);
		$this->mysql_con_handle = $con_handle;
		if (!$this->mysql_con_handle instanceof mysqli) {
			die('no sql connect handle defined');
		}
	}

	/**
	* insert the mysqldump into the datbase
	*
	* @return void
	*/
	function insert_sql_dump($special_replace = false) {
		// Zeitinterval vergroe�ern
		@set_time_limit(0);
		
		$this->_mysql_connect();

		//Load mysql_dump - file, replace prefix, format it and make it handy
		$sql_data = implode('',(file(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . $this -> globals['sql_target'])));
		//utf8 encode
		$sql_data = utf8_encode ($sql_data);		
		
		
		
		$in = array("!ALTER TABLE cms_!",
	                "!ALTER TABLE `cms_!",
	                "!DELETE FROM cms_!",
                    "!DELETE FROM `cms_!",
		            "!UPDATE cms_!",
		            "!UPDATE `cms_!",
                    "!REPAIR TABLE `cms_!",
                    "!OPTIMIZE TABLE `cms_!",
                    "!DROP TABLE IF EXISTS cms_!",
			        "!CREATE TABLE cms_!",
			        "!CREATE TABLE `cms_!",
			        "!INSERT INTO `cms_!",
			        "!INSERT INTO cms_!");
		$out = array('ALTER TABLE '.$this -> globals['prefix'],
			         'ALTER TABLE `'.$this -> globals['prefix'],
			         'DELETE FROM '.$this -> globals['prefix'],
		             'DELETE FROM `'.$this -> globals['prefix'],
			         'UPDATE '.$this -> globals['prefix'],
			         'UPDATE `'.$this -> globals['prefix'],			         
                     'REPAIR TABLE `'.$this -> globals['prefix'],
                     'OPTIMIZE TABLE `'.$this -> globals['prefix'],
			         'DROP TABLE IF EXISTS '.$this -> globals['prefix'],
			         'CREATE TABLE '.$this -> globals['prefix'],
			         'CREATE TABLE `'.$this -> globals['prefix'],
			         'INSERT INTO `'.$this -> globals['prefix'],
			         'INSERT INTO '.$this -> globals['prefix']);

		$sql_data = preg_replace($in, $out, $sql_data);
		// change admin passwort by user input if not in update mode
		if ($this -> globals['sql_target'] != 'updates.sql') {
			$sql_data = preg_replace('!{adminpass}!', md5($this -> globals['adminpass']), $sql_data);
		}
		
		//special replacements
		if (is_array($special_replace)) {
			foreach($special_replace AS $k=>$v) {
				$sql_data = str_replace('{'.$k.'}', $v, $sql_data);
			}
		}
		
		$sql_data = $this -> remove_remarks($sql_data);
		$sql_pieces = $this -> split_sql_file($sql_data, ';');
		$sql_count = count($sql_pieces);


		// DEBUGGING
		if($this -> debug) echo $this -> globals['sql_target']." - Auszuf&#252;hrende Dump querys:  $sql_count <br><br>";
		for ($i = 0; $i < $sql_count; $i++) {
			$sql = trim($sql_pieces[$i]);
			if (!empty($sql)) {
				mysqli_query( $this->mysql_con_handle, $sql);

				//DEBUGGING
				if($this -> debug) {
					if(mysqli_error($this->mysql_con_handle) != '') echo  $i+1 . ":  <font color='darkred'><b>FEHLER</b></font>  -->  " . mysqli_error($this->mysql_con_handle) . "<br>" . $sql . '<br><br>';
					else echo  $i+1 . ":   <font color= 'darkgreen'><b>AUSGEF&#220;HRT</b></font><br>";//. $sql . '<br><br>';
				}
			}
		}
	}
	
	function get_cms_version(){
		
		if( empty($this -> globals['host'])) die('Fatal error - No DB connection data');
		
		$con_handle = mysqli_connect($this -> globals['host'], $this -> globals['user'], $this -> globals['pass']);
		mysqli_select_db( $con_handle, $this -> globals['db']);
		
		$sql = 'SHOW TABLES';
		$result = mysqli_query( $con_handle, $sql);
		
        $version_exists = false;
        while ($line = mysqli_fetch_array($result,  MYSQLI_ASSOC)) {
           foreach ($line as $col_value) {
               if($col_value == $this -> globals['prefix'] . 'values'){
               		$version_exists = true;
               }
           }
        }
        mysqli_free_result($result);
        
        //abort - no update table found
        if(! $version_exists)
        	return false;
        
        //echo $this -> globals['prefix']."values<br>";
        if($version_exists){
        	$sql = "SELECT value FROM `".$this -> globals['prefix']."values`  WHERE group_name =  'cfg' AND key1 =  'version'";
			$result = mysqli_query( $con_handle, $sql);
			if ($line = mysqli_fetch_array($result,  MYSQLI_ASSOC)){
				$pieces = explode('.', $line['value']);
				$version['prior'] = $pieces['0'];
				$version['minor'] = $pieces['1'];
				$version['fix']   = $pieces['2'];
				//print_r($version);
				//return version
				return $version;
			}
        }
        
        mysqli_close($con_handle);

		$version['prior'] = '00';
		$version['minor'] = '00';
		$version['fix']   = '00';
		//print_r($version);
		//clean install - no version exists return 00 00 00
		return $version;			
	}

	/**
	* removes '# blabla...' from the mysql_dump.
	* This function was original developed for the phpbb 2.01
	* (C) 2001 The phpBB Group http://www.phpbb.com
	*
	* @return string input_without_#
	*/
	function remove_remarks($sql) {
		$lines = explode("\n", $sql);

		// try to keep mem. use down
		$sql = '';
		$linecount = count($lines);
		$output = '';
		for ($i = 0; $i < $linecount; $i++) {
			if (($i != ($linecount - 1)) || (strlen($lines[$i]) > 0)) {
				if ($lines[$i]['0'] != '#' && ($lines[$i]['0'] != '-' && $lines[$i]['1'] != '-') ) {
					 $output .= $lines[$i] . "\n";
				} else $output .= "\n";

				// Trading a bit of speed for lower mem. use here.
				$lines[$i] = '';
			}
		}
		return $output;
	}

	/**
	* Splits sql- statements into handy pieces.
	* This function was original developed for the phpbb 2.01
	* (C) 2001 The phpBB Group http://www.phpbb.com
	*
	* @return array sql_pieces
	*/
	function split_sql_file($sql, $delimiter) {
		// Split up our string into "possible" SQL statements.
		$tokens = explode($delimiter, $sql);

		// try to save mem.
		$sql = '';
		$output = array();

		// we don't actually care about the matches preg gives us.
		$matches = array();

		// this is faster than calling count($oktens) every time thru the loop.
		$token_count = count($tokens);
		for ($i = 0; $i < $token_count; $i++) {
			// Dont wanna add an empty string as the last thing in the array.
			if (($i != ($token_count - 1)) || (strlen($tokens[$i] > 0))) {
				// This is the total number of single quotes in the token.
				$total_quotes = preg_match_all("/'/", $tokens[$i], $matches);

				// Counts single quotes that are preceded by an odd number of backslashes,
				// which means they're escaped quotes.
				$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);
				$unescaped_quotes = $total_quotes - $escaped_quotes;

				// If the number of unescaped quotes is even, then the delimiter did NOT occur inside a string literal.
				if (($unescaped_quotes % 2) == 0) {
					// It's a complete sql statement.
					$output[] = $tokens[$i];
					// save memory.
					$tokens[$i] = '';
				} else {
					// incomplete sql statement. keep adding tokens until we have a complete one.
					// $temp will hold what we have so far.
					$temp = $tokens[$i] . $delimiter;

					// save memory..
					$tokens[$i] = '';

					// Do we have a complete statement yet?
					$complete_stmt = false;
					for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++)
					{
						// This is the total number of single quotes in the token.
						$total_quotes = preg_match_all("/'/", $tokens[$j], $matches);

						// Counts single quotes that are preceded by an odd number of backslashes,
						// which means theyre escaped quotes.
						$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);
						$unescaped_quotes = $total_quotes - $escaped_quotes;
        						if (($unescaped_quotes % 2) == 1) {
							// odd number of unescaped quotes. In combination with the previous incomplete
							// statement(s), we now have a complete statement. (2 odds always make an even)
							$output[] = $temp . $tokens[$j];

							// save memory.
							$tokens[$j] = '';
							$temp = '';

							// exit the loop.
							$complete_stmt = true;

							// make sure the outer loop continues at the right point.
							$i = $j;
						} else {
							// even number of unescaped quotes. We still dont have a complete statement.
							// (1 odd and 1 even always make an odd)
							$temp .= $tokens[$j] . $delimiter;
							// save memory.
							$tokens[$j] = '';
						}

					}
				}
			}
		}
		return $output;
	}

	/**
	* Add ending slash to url or path
	*
	* @return string input with ending slash
	*/
	function add_ending_slash($path_value)
	{
		if(substr($path_value,-1) != '/') $path_value = $path_value.'/';
		return $path_value;
	}
}

if (function_exists('set_magic_quotes_runtime')) {
    @set_magic_quotes_runtime (0);
}

// zeige alle Fehlermeldungen, aber keine Warnhinweise und Deprecated-Meldungen
if (defined('E_DEPRECATED'))
{
	error_reporting (E_ALL & ~E_NOTICE & ~E_DEPRECATED);
}
else
{
	error_reporting (E_ALL & ~E_NOTICE);
}

$con_setup = new setup();
$data = $con_setup -> make_setup();
echo $data;

?>
