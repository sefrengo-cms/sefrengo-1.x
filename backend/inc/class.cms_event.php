<?PHP
// File: $Id: class.cms_event.php 28 2008-05-11 19:18:49Z mistral $
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

class cms_event {
	var $events = array();
	var $returnval = array();

	function __construct($val_ct) {
		$this -> events = $val_ct -> get_by_group('events');
	}

	function fire($event, $args) {
		global $db, $cms_db, $client, $lang, $perm, $sess, $cfg_client, $cfg_cms;
        
        $this->returnval = array();
        
        if (is_array($this->events[$event]['actions'])) {
			foreach ($this->events[$event]['actions'] as $eventvalue) {
				 eval($eventvalue);
			}
		} 
        
        if (is_array($this->events['standard']['actions'])) {
			$standard[] = $event;
            if (is_array($args)) {
            	foreach ($args as $key => $val) {
            		$standard[] = $val;
            	}
            	foreach($this->events['standard']['actions'] as $eventvalue) {
            		eval($eventvalue);
            	}
            }
		}
		
	}
	
	function addReturnval($val) {
		array_push($this->returnval, $val);
	}
	
	function getReturnval() {
		return $this->returnval;
	}
}
?>
