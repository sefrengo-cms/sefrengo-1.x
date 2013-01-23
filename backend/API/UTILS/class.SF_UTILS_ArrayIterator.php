<?php
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

 
 class SF_UTILS_ArrayIterator extends SF_API_Object{
	var $arr = array();
	var $pos_current = 0;
	var $pos_max = 0;
	var $keys = array();
	
	function SF_UTILS_ArrayIterator() {	}
	
	function loadByRef(&$arr) {
		$this->arr =& $arr;
		$this->keys = array_keys($this->arr);		
		$this->pos_max = count($this->keys);
	}
	
	function load($arr) {
		$this->arr =& $arr;
		$this->keys = array_keys($this->arr);		
		$this->pos_max = count($this->keys);
	}
	
	 /**
	* setzt den Iterator zurueck auf das erste Element
	*/
	function rewind(){
		$this->pos_current = 0;
	}
	
	/**
	* validieren, dass es das aktuelle Element gibt
	*/
	function valid(){
		return array_key_exists($this->pos_current, $this->keys);
	}
	
	/**
	* zum naechsten Element springen
	*/
	function next(){
		++$this->pos_current;
	}
	
	/**
	* gibt den Wert des aktuellen Elements zurueck
	*/
	function current(){
		return $this->arr[ $this->keys[$this->pos_current] ];
	}
	
/**
	* gibt den Schluessel des aktuellen Elements zurueck
	*/
	function key(){
		return $this->keys[$this->pos_current];
	}
		
	/**
	* Anzahl der Elemente
	*/
	function count(){
		return $this->pos_max;
	}	
}
 
?>
