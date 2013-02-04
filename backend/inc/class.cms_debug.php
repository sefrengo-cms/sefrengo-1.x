<?PHP
// File: $Id: class.cms_debug.php 40 2008-05-12 21:30:26Z mistral $
// +----------------------------------------------------------------------+
// | Version: Sefrengo $Name:  $                                          
// +----------------------------------------------------------------------+
// | Copyright (c) 2005 - 2006 sefrengo.org <info@sefrengo.org>           |
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
// + Revision: $Revision: 40 $
// +----------------------------------------------------------------------+
// + Description:
// +----------------------------------------------------------------------+
// + Changes: 
// +----------------------------------------------------------------------+
// + ToDo:
// +----------------------------------------------------------------------+

class cms_debug
{
	var $sql = array();
	var $general = array();
	var $error = array();
	var $memory = array();
	var $t_enabled = false;
	var $t;
	var $m_enabled = false;
	
	function cms_debug() {
		list($low, $high) = explode(" ", microtime());
    	$this->t = (float)$high + (float)$low;
	}

    function enableTime($boolean) {
    	$this->t_enabled = (boolean) $boolean;
    }
    
    function enableMem($boolean) {
    	$this->m_enabled = (boolean) $boolean;
    }

	function collect($message, $cat = 'general', $log = false)
	{
		switch($cat)
		{
			case 'general':
				$this -> general[] = $message;
				break;
			case 'sql':
				$this -> sql[] = $message;
				break;
			case 'mem':
				$temp_arr['message'] = $message;
    			if (function_exists(memory_get_usage)) {
    		        $temp_arr['usage'] = memory_get_usage()/1024;
    			}
			    if (function_exists(memory_get_peak_usage)) {
        		    $temp_arr['peak'] = memory_get_peak_usage()/1024;
			    }
				$this -> memory[] = $temp_arr;
				break;
			case 'cache':
				$this -> sql[] = $message;
				break;
			case 'error':
				$this -> error[] = $message;
				break;
			default:
				echo 'Error - debug cat  "'. $cat .'" (message: '. $message .') does not exist!';			
		}
		if ($log) $this->log($message);
	}

	function log($message)
	{
  		global $this_dir;
        if (FALSE == ($fp = @fopen($this_dir.'logs/errorlog.txt', 'a+')))
			$this->collect('<B>Dateifehler:</B> ' . $this_dir . 'logs/errorlog.txt ' , 'error');
		@fputs($fp, $message);
		@fclose($fp);
		
	}
	
	function show()
	{
		global $cfg_cms;
		
		$to_return ="";
		
		if($cfg_cms['debug_error'] && ! empty($this -> error['0'])){
			$to_return .= '<b><font color="red">ERROR:</font></b>'."<br>\n";
			$i=1;
			foreach($this -> error as $value){
				$to_return .= '<b>'. $i . '. Query:</b> '. $value . "<br>\n";
				$i++;
			}
		}
		if($cfg_cms['debug_general'] && ! empty($this -> general['0'])){
			$to_return .= '<br><b>GENERALL:</b>'."<br>\n";
			foreach($this -> general as $value){
				$to_return .= $value . "<br>\n";
			}
		}
		if($cfg_cms['debug_sql'] && ! empty($this -> sql['0'])){
			$to_return .= '<br><b>SQL:</b>'."<br>\n";
			$i=1;
			foreach($this -> sql as $value){
				$to_return .= '<b>'. $i . '. Query:</b> '. $value . "<br>\n";
				$i++;
			}
		}

		if($this->m_enabled && ! empty($this -> memory['0'])){
			$to_return .= '<br><b>Memory:</b>'."<br>\n";
			$i=1;
			foreach($this -> memory as $value){
				$to_return .= '<b>'. $i . '. Mem:</b> ';
    		    $to_return .= sprintf("usage: %6uk / ",  $value['usage']);
    		    $to_return .= sprintf("peak : %6uk / ",  $value['peak']);
				$to_return .= $value['message']."<br>\n";
				$i++;
			}
		}

		if ($this->t_enabled) {
			list($low, $high) = explode(" ", microtime());
		    $t    = (float)$high + (float)$low;
		    $used = $t - $this->t;
		    $to_return .= sprintf("<br>executiontime: (%8.4f)\n",  $used);
		    //$this->log($to_return);
		}
		
		if ($this->m_enabled) {
			if (function_exists(memory_get_usage)) {
    		    $to_return .= sprintf("<br>memory allocated: (%6uk)\n",  memory_get_usage()/1024);
			}
			if (function_exists(memory_get_peak_usage)) {
    		    $to_return .= sprintf("<br>peak of memory allocated: (%6uk)\n",  memory_get_peak_usage()/1024);
			}
		}

		return $to_return;
	}
	

}

?>