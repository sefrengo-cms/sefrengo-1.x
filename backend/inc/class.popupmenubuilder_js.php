<?PHP
// File: $Id: class.popupmenubuilder_js.php 440 2011-09-10 14:23:27Z bjoern $
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
// + Autor: $Author: bjoern $
// +----------------------------------------------------------------------+
// + Revision: $Revision: 440 $
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
		$element = array(
					'type' => 'seperator',
					'entry' => ''
					);
		
		array_push ($this->stack, $element);
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
	
	function _generate() {
		if(! empty($this->outstring) ) return $this->outstring;

		$stack_count = count($this->stack);
		$all = array();
		$out = '<ul>';
		//print_r($this->stack[1]);
		
		for($i=0; $i<$stack_count; $i++) {
			$element = $this->_generate_element($this->stack[$i]);
			$out .= $element;
		}

		$out.= '</ul>';
			
		//array_push($all, $out);
		
		$this->outstring = $this->_get_menuimage($out);
		$out = '';
		return $this->outstring;
	}

	function _generate_element($raw)
	{
		switch($raw['type'])
		{
			case 'title':
			    $cooked = "<li class=\"sf_hmenu_title\">".$raw['entry']."</li>";
			    break;
			case 'seperator':
			    $cooked = "<li><hr class=\"sf_hmenu_trenner\"></li>";
			    break;
			case 'entry':
				$onclick_content = '';
				if($raw['optional_js'] != '')
				{
					$onclick_content = " onclick=\"".$raw['optional_js']." return true; return false;\"";
				}
			    $cooked = "<li class=\"sf_hmenu_entry\"><a href=\"".$raw['link']."\" rel=\"".$raw['mouseover_text']."\" target=\"".$raw['target']."\" ".$onclick_content.">".$raw['entry']."</a></li>";
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
	
	function _get_menuimage($menucontent) {		
	return	'<span class="sf_hmenu_wrapper">
				<span class="sf_hmenu_trigger"><img src="'.$this->image.'" width="'.$this->image_width.'" height="'.$this->image_height.'" /></span>
				<span class="sf_hovermenu">
					'. $menucontent .'
				</span>
			</span>';
	}
}

?>
