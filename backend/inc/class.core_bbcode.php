<?PHP
// File: $Id: class.core_bbcode.php 28 2008-05-11 19:18:49Z mistral $
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

class core_bbcode{
	var $tags;
	var $settings;
	function core_bbcode(){
		$this->tags = array();
		$this->settings = array('enced'=>true);
        $this->add_tag(array('Name'=>'b','HtmlBegin'=>'<strong>','HtmlEnd'=>'</strong>'));
        $this->add_tag(array('Name'=>'i','HtmlBegin'=>'<em>','HtmlEnd'=>'</em>'));
        $this->add_tag(array('Name'=>'u','HtmlBegin'=>'<ins>','HtmlEnd'=>'</ins>'));
        $this->add_tag(array('Name'=>'link','HasParam'=>true,'HtmlBegin'=>'<a href="%%P%%">','HtmlEnd'=>'</a>'));
        $this->add_tag(array('Name'=>'color','HasParam'=>true,'ParamRegex'=>'[A-Za-z0-9#]+','HtmlBegin'=>'<span style="color: %%P%%;">','HtmlEnd'=>'</span>','ParamRegexReplace'=>array('/^[A-Fa-f0-9]{6}$/'=>'#$0')));
        $this->add_tag(array('Name'=>'email','HasParam'=>true,'HtmlBegin'=>'<a href="mailto:%%P%%">','HtmlEnd'=>'</a>'));
        $this->add_tag(array('Name'=>'size','HasParam'=>true,'HtmlBegin'=>'<span style="font-size: %%P%%pt;">','HtmlEnd'=>'</span>','ParamRegex'=>'[0-9]+'));
        $this->add_tag(array('Name'=>'bg','HasParam'=>true,'HtmlBegin'=>'<span style="background: %%P%%;">','HtmlEnd'=>'</span>','ParamRegex'=>'[A-Za-z0-9#]+'));
        $this->add_tag(array('Name'=>'s','HtmlBegin'=>'<del>','HtmlEnd'=>'</del>'));
        $this->add_tag(array('Name'=>'align','HtmlBegin'=>'<div style="text-align: %%P%%">','HtmlEnd'=>'</div>','HasParam'=>true,'ParamRegex'=>'(center|right|left)'));
        $this->add_tag(array('Name'=>'code','HtmlBegin'=>'<code>','HtmlEnd'=>'</code>'));
        $this->add_tag(array('Name'=>'p','HtmlBegin'=>'<p>','HtmlEnd'=>'</p>'));
        $this->add_alias('url','link');
        $this->add_tag(array('Name'=>'list','HtmlBegin'=>'<ul>','HtmlEnd'=>'</ul>'));
        $this->add_tag(array('Name'=>'numbered','HtmlBegin'=>'<ol type="%%P%%">','HtmlEnd'=>'</ol>','HasParam'=>true,'ParamRegex'=>'(I|i|a|A|n)'));
        $this->add_tag(array('Name'=>'item','HtmlBegin'=>'<li>','HtmlEnd'=>'</li>'));

	}
    function begtoend($htmltag){
	    return preg_replace('/<([A-Za-z]+)>/','</$1>',$htmltag);
    }
    function replace_pcre_array($text,$array){
	    $pattern = array_keys($array);
	    $replace = array_values($array);
	    $text = preg_replace($pattern,$replace,$text);
	    return $text;
    }
	function get_data($name,$cfa = ''){
		if(!array_key_exists($name,$this->tags)) return '';
		$data = $this->tags[$name];
		if($cfa) $sbc = $cfa; else $sbc = $name;
		if(!is_array($data)){
			$data = preg_replace('/^ALIAS(.+)$/','$1',$data);
			return $this->get_data($data,$sbc);
		}else{
			$data['Name'] = $sbc;
			return $data;
		}
	}
	function change_setting($name,$value){
		$this->settings[$name] = $value;
	}
	function add_alias($name,$aliasof){
		if(!array_key_exists($aliasof,$this->tags) or array_key_exists($name,$this->tags)) return false;
		$this->tags[$name] = 'ALIAS'.$aliasof;
		return true;
	}
	function onparam($param,$regexarray){
		$param = $this->replace_pcre_array($param,$regexarray);
		if(!$this->settings['enced']){
			$param = htmlentities($param, ENT_COMPAT, 'UTF-8');
		}
		return $param;
	}
	function export_definition(){
		return serialize($this->tags);
	}
	function import_definiton($definition,$mode = 'append'){
		switch($mode){
			case 'append':
			$array = unserialize($definition);
			$this->tags = $array + $this->tags;
			break;
			case 'prepend':
			$array = unserialize($definition);
			$this->tags = $this->tags + $array;
			break;
			case 'overwrite':
			$this->tags = unserialize($definition);
			break;
			default:
			return false;
		}
		return true;
	}
	function add_tag($params){
		if(!is_array($params)) return 'Paramater array not an array.';
		if(!array_key_exists('Name',$params) or empty($params['Name'])) return 'Name parameter is required.';
		if(preg_match('/[^A-Za-z]/',$params['Name'])) return 'Name can only contain letters.';
		if(!array_key_exists('HasParam',$params)) $params['HasParam'] = false;
		if(!array_key_exists('HtmlBegin',$params)) return 'HtmlBegin paremater not specified!';
		if(!array_key_exists('HtmlEnd',$params)){
			 if(preg_match('/^(<[A-Za-z]>)+$/',$params['HtmlBegin'])){
			 	$params['HtmlEnd'] = $this->begtoend($params['HtmlBegin']);
			 }else{
			 	return 'You didn\'t specify the HtmlEnd parameter, and your HtmlBegin parameter is too complex to change to an HtmlEnd parameter.  Please specify HtmlEnd.';
			 }
		}
		if(!array_key_exists('ParamRegexReplace',$params)) $params['ParamRegexReplace'] = array();
		if(!array_key_exists('ParamRegex',$params)) $params['ParamRegex'] = '[^\\]]+';
		if(!array_key_exists('HasEnd',$params)) $params['HasEnd'] = true;
		if(array_key_exists($params['Name'],$this->tags)) return 'The name you specified is already in use.';
		$this->tags[$params['Name']] = $params;
		return '';
	}
	function parse_bbcode($text){
		foreach($this->tags as $tagname => $tagdata){
			if(!is_array($tagdata)) $tagdata = $this->get_data($tagname);
			$startfind = "/\\[{$tagdata['Name']}";
			if($tagdata['HasParam']){
				$startfind.= '=('.$tagdata['ParamRegex'].')';
			}
			$startfind.= '\\]/';
			if($tagdata['HasEnd']){
				$endfind = "[/{$tagdata['Name']}]";
				$starttags = preg_match_all($startfind,$text,$ignore);
				$endtags = substr_count($text,$endfind);
				if($endtags < $starttags){
					$text.= str_repeat($endfind,$starttags - $endtags);
				}
				$text = str_replace($endfind,$tagdata['HtmlEnd'],$text);
			}
			$replace = str_replace(array('%%P%%','%%p%%'),'\'.$this->onparam(\'$1\',$tagdata[\'ParamRegexReplace\']).\'','\''.$tagdata['HtmlBegin'].'\'');
			$text = preg_replace($startfind.'e',$replace,$text);
		}
		$text = str_replace('[*]','<li>',$text);
		return $text;
	}
}
?>