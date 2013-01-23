<?PHP
// File: $Id: class.popupmenubuilder_js.php 28 2008-05-11 19:18:49Z mistral $
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

/**
* Helperclass to build hovermenus with the 'popupmenu.js'
*
* @author   (c) Bjoern Brockmann 2004
*/
class popupmenubuilder_js
{
   /**
    * saves the incomming data for the menu
    *
    * @var  array
    */
	var $stack = array();

   /**
    * contains the generated menustring
    *
    * @var  string
    */
	var $outstring;

   /**
    * the image, imagewidth, imageheight for the mouseover
    *
    * @var  string
    */
	var $image;
	var $image_width;
	var $image_height;

	function set_image($image, $width, $height)
	{
		$this->image = $image;
		$this->image_width = $width;
		$this->image_height = $height;
	}

	function add_title($entry)
	{
		$element = array(
					'type' => 'title',
					'entry' => $entry
					);
		
		array_push ($this->stack, $element);
	}
	
	function add_seperator()
	{
		$this->add_title('');
	}
	
	function add_entry($entry, $link='#', $target= '_self', $mouseover_text = '', $optional_js='')
	{
		$element = array(
					'type' => 'entry',
					'entry' => $entry,
					'link' => $link,
					'target' => $target,
					'mouseover_text' => $mouseover_text,
					'optional_js' => $optional_js
					);

		array_push ($this->stack, $element);
	}


	function get_menu()
	{
		return $this->_generate();
	}
	
	function flush()
	{
		$this->_delete_outstring();
		$this->_delete_stack();
	}

	function get_menu_and_flush()
	{
		$out = $this->get_menu();
		$this->flush();
		
		return $out;
	}
	
	function _generate()
	{
		if(! empty($this->outstring) ) return $this->outstring;

		$stack_count = count($this->stack);
		$all = array();
		$out = '[';
		//print_r($this->stack[1]);
		
		for($i=0; $i<$stack_count; $i++)
		{
			$element = $this->_generate_element($this->stack[$i]);

			if($this->stack[$i]['type'] == 'title')
				$out .= $element;
			else if($this->stack[$i]['type'] == 'entry'){
				if($this->stack[($i-1)]['type'] != 'entry')
					$out.= '[';

				$out.= $element;

				if($this->stack[($i+1)]['type'] != 'entry')
					$out.= ']';
			}

			array_push($all, $out);
			$out = '';
		}

        $cooked = implode(',', $all);
		//if($this->stack[($i-1)]['type'] == 'entry')
		$cooked .= ']';

		$this->outstring = $this->_get_menuimage($cooked);
		return $this->outstring;
	}

	function _generate_element($raw)
	{
		switch($raw['type'])
		{
			case 'title':
			    $cooked = "'".$raw['entry']."'";
			    break;
			case 'entry':
			    $cooked = "['".$raw['entry']."','".$raw['link']."','".$raw['mouseover_text']."','"
							.$raw['target']."','".$raw['optional_js']."']";
			    break;
		}

		return $cooked;
	}
	
	function _delete_outstring()
	{
		$this->outstring = '';
	}
	
	function _delete_stack()
	{
		unset($this->stack);
		$this->stack = array();
	}
	
	function _get_menuimage($menucontent)
	{
		return '<img src="'.$this->image.'" width="'.$this->image_width.'" height="'.$this->image_height.'"'
				.' onmouseover="showmenu(event, '. $menucontent .')" onmouseout="delayhidemenu()" '
				.' style="cursor:pointer" border="0" />';
	}
}

?>
