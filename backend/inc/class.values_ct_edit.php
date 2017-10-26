<?PHP
// File: $Id: class.values_ct_edit.php 28 2008-05-11 19:18:49Z mistral $
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

class values_ct_edit extends values_ct
{
	//related for sql table
	var $sqlgroup;
	var $client;
	var $lang;

	//for making url
	var $area;
	var $action;
	var $vid;//id beim bearbeiten, entspricht idvalues in mysqltabelle

	var $tpl_file;
	var $head_vars = array();
	var $body_vars = array();
	var $view;// use or dev (dev for more internal infos)
	var $prefix;// wenn dev , angezeigtes prefix der keys
	var $perm_edit;

	/**
	* KONSTRUKTOR - Konfiguration
	*
	*/
	function __construct($mixed = '')
	{
		global $cms_lang, $vid;

		$this -> sqlgroup = $mixed['sqlgroup'];
		$this -> client = $mixed['client'];
		$this -> lang = $mixed['lang'];
		$this -> tpl_file = $mixed['tpl_file'];
		$this -> area = $mixed['area'];
		$this -> action = $mixed['action'];
		$this -> cms_plugin = $mixed['cms_plugin'];
		$this -> perm_edit = $mixed['perm_edit'];
		$this -> perm_type = ( empty($mixed['perm_type']) ) ? 'general': $mixed['perm_type'];
		$this -> extra_url_args = $mixed['extra_url_args'];

		$this -> head_vars['FOOTER_LICENSE'] = $cms_lang['login_licence'];

		$this -> view = (empty($mixed['view'])) ? 'use' : $mixed['view'];
		$this -> prefix = (empty($mixed['prefix'])) ? '$cfg' : $mixed['prefix'];
		//private
		$this -> head_vars['SET_ACTION'] = $cms_lang['set_action'];
		$this -> vid = $vid;

		switch($this -> action)
		{
			case 'save_value':
				$this -> _save_entry();
		}


	}

	function start()
	{
		global $cms_db, $db, $tpl;
		global $cms_lang;

		$this -> _make_base_url();

		$tpl->loadTemplatefile($this -> tpl_file);

		$sql = "SELECT 		*
				FROM		". $cms_db['values'] ."
				WHERE		group_name IN ('". $this -> sqlgroup ."')
				AND			idclient IN (". $this -> client .")
				AND			idlang IN (". $this -> lang .")
				AND			conf_visible = 1
				ORDER BY 	conf_sortindex";

		$db -> query($sql);


		// get and handle db values
		while($db -> next_record())
		{
			// Tabellenüberschriften
			if($db->f('conf_head_langstring')  ){
				$head_langstring = $db->f('conf_head_langstring');
				$this -> _make_row_head($head_langstring);
			}
			// Tabelleninhalt
			$this -> _make_row_body();
		}
		// Letzte Tabellenüberschrift
		$this -> _make_row_head($head_langstring);

	}

	function _make_row_head($head_langstring)
	{
		global $tpl, $cms_lang;

		// ist nicht der erste Aufruf
		if(! empty($this -> head_vars['SET_HEADNAME']) ){
			$tpl->setVariable($this -> head_vars);
			$tpl->parse('TABLE');
		}
		//head template vorbereiten
		$this -> head_vars['SET_HEADNAME'] = $cms_lang[$head_langstring];
		$this -> head_vars['LAY_DESCRIPTION'] = $cms_lang['lay_description'];
	}

	function _make_row_body()
	{
		global $cms_lang, $db, $tpl, $vid;

		//Werte auslesen
		$db_val['id'] = $db->f('idvalues');
		$db_val['key1'] = $db->f('key1');
		$db_val['key2'] = $db->f('key2');
		$db_val['key3'] = $db->f('key3');
		$db_val['key4'] = $db->f('key4');
		$db_val['value'] = $db->f('value');
		$db_val['conf_sortindex'] = $db->f('conf_sortindex');
		$db_val['conf_desc_langstring'] = $db->f('conf_desc_langstring');
		$db_val['conf_input_type'] = $db->f('conf_input_type');

		$row['ENTRY_DESC'] =  $this -> _format_value_desc($db_val);
		$row['ENTRY_ICON'] =  make_image('but_parameter.gif', '', '16', '16', false, 'class="icon"');
		$row['ENTRY_BGCOLOR'] = '#ffffff';
		$row['OVERENTRY_BGCOLOR'] = '#fff7ce';
                
		// Ein Wert wird bearbeitet
		if($this -> action == 'edit' && $this -> vid == $db_val['id']){
			$row['ENTRY_VALUE'] =  $this -> _format_edit_field($db_val);
			$row['ENTRY_ACTIONS'] = $this -> _format_submit_buttons($db_val);
			$row['FORM_START'] = '<form action="main.php#val'.$db_val['id'].'" method="post">';
			$row['FORM_END'] = '</form>';
		}
		//Normale Anzeige der Werte
		else{
			$row['ENTRY_VALUE'] = $this -> _format_value($db_val);;
			$row['ENTRY_ACTIONS'] = $this -> _format_actions($db_val);
		}

		$tpl->setVariable($row);
		$tpl->parse('ENTRY');
	}

	function _save_entry()
	{
		global $value_to_save, $value_id, $perm;

		//erstmal deaktiviert
		//$perm -> check($this -> perm_edit, $this -> perm_type);

		if(is_array($value_to_save)){
			$v = '';
			$c = count($value_to_save);
			for($i=0; $i<$c; $i++)
			{
				if($i+1<$c){
					$v .= $value_to_save[$i] .',';
				}
				else{
					$v .= $value_to_save[$i];
				}
			}
			unset($value_to_save);
			$value_to_save = $v;
		}
		//set_magic_quotes_gpc($value_to_save);
		$this -> set_value(array(
								'id'    => $value_id,
								'value' => $value_to_save,
								'group' => $this -> sqlgroup
									));
	}

	function _format_edit_field($db_val)
	{
		global $sess;

		$text_field = '<input class="w600" type="text" name="%s" value="%s" size="%s" />';
		$textarea_field = '<textarea class="w600" name="%s" cols="%s" rows="%s">%s</textarea>' . "\n";
		$hidden_field ='<input type="hidden" name="%s" value="%s" />';
		$session = sprintf($hidden_field, $sess -> name, $sess -> id);
		$area = sprintf($hidden_field, 'area', $this -> area);
		if(! empty ($this -> cms_plugin) ){
			$area .= sprintf($hidden_field, 'cms_plugin', $this -> cms_plugin);
			$area .= sprintf($hidden_field, 'idclient', $this -> client);

		}
		if(! empty ($this->extra_url_args)){
			$pairs = explode("&",substr($this->extra_url_args, 1));
			foreach($pairs AS $k => $v)
			{
				$thatsit = explode("=", $v);
				$area .= sprintf($hidden_field, $thatsit['0'], $thatsit['1']);
			}
		}
		$action = sprintf($hidden_field, 'action', 'save_value');
		$id = sprintf($hidden_field, 'value_id', $db_val['id']);

		$anchor = '<a name ="val' . $db_val['id'] .'"></a>%s';

		switch($db_val['conf_input_type'])
		{
			case 'txt':
				$form = sprintf($text_field, 'value_to_save', $db_val['value'], 100);
				break;
			case 'txtarea':
				$form = sprintf($textarea_field, 'value_to_save',80, 8, $db_val['value'], 100);
				break;
			case 'mutiple_groups':
				$form = $this -> _editfield_mutiple_groups($db_val['value']);
				break;
		}

		$form = sprintf($anchor, $form);
		$form .= $session . $area . $action .$id;
		return $form;

	}

	function _editfield_mutiple_groups($val)
	{
		global $DB_cms, $cms_db;

		$dbvals = new DB_cms;
		$val_array = explode(',', $val);
		$sql = "SELECT idgroup, name FROM ". $cms_db['groups'] ." WHERE idgroup NOT IN(1)";
		$dbvals -> query($sql);
		$options ='';
		while($dbvals -> next_record())
		{
			$val = $dbvals -> f('idgroup');
			$selected = (in_array($val, $val_array)) ? 'selected' : '';
			$desc = $dbvals -> f('name');
			$options .= "<option value =\"$val\" $selected >$desc</option>";
		}



		$form = '<select name="value_to_save[]" size="7" multiple>'. $options .'</select>';

		return $form;

	}

	function _format_submit_buttons($db_val)
	{
		global $cms_lang, $cfg_cms;

		$button_submit = '<input type="image" src="tpl/'.$cfg_cms['skin'].'/img/but_confirm.gif"'
			.' alt="'.$cms_lang['set_submit'] .'"'
			.' title="'.$cms_lang['set_submit'].'" />';
		$button_cancel = '<a href="'.sprintf($this -> base_url, '',$db_val['id'], $db_val['id']).'">'
			.'<img src="tpl/'.$cfg_cms['skin'].'/img/but_cancel_delete.gif"'
			.' alt="'.$cms_lang['set_cancel'].'" title="'.$cms_lang['set_cancel'].'" /></a>';
		return $button_submit . $button_cancel;
	}

	function _format_actions($db_val)
	{
		global $cfg_cms, $cms_lang;

		$edit_button =  '<a href="'. sprintf($this -> base_url, 'edit', $db_val['id'], $db_val['id'])
			.'" class="action">'
			.'<img src="tpl/'.$cfg_cms['skin'].'/img/but_edit.gif" alt="' .$cms_lang['set_edit'].'"'
			.'title="' .$cms_lang['set_edit'].'" width="16" height="16" /></a>';
		return $edit_button;
	}

	function _make_base_url()
	{
		global $sess;

		//url erstellen zugriff später mit sprintf($url, value, value)
		$ar = 'area=' . $this -> area;
		if(! empty ($this -> cms_plugin) ){
			$ar .= '&cms_plugin=' . $this -> cms_plugin;
			$ar .= '&idclient=' . $this -> client;
		}
		$ac = '&action=%s&vid=%s' ;
		$this -> base_url = $sess->url('main.php?'.$ar.$ac. $this -> extra_url_args).'#val%s';
	}

	function _format_value($db_val)
	{
		switch($db_val['conf_input_type'])
		{
			case 'txt':
				$value = htmlentities($db_val['value'], ENT_COMPAT, 'UTF-8');
				break;
			case 'txtarea':
				$value = nl2br(htmlentities($db_val['value'], ENT_COMPAT, 'UTF-8'));
				break;
			case 'mutiple_groups':
				$value = $this -> _format_mutiple_groups($db_val['value']);
				break;
		}
		$to_return = '<a name ="val' . $db_val['id'] .'"></a>'.$value;
		return $to_return;
	}

	function _format_mutiple_groups($val)
	{
		global $DB_cms, $cms_db;

		if(empty($val)){
			return '-';
		}

		$dbvals = new DB_cms;
		$sql = "SELECT name FROM ". $cms_db['groups'] ." WHERE idgroup IN($val)";

		$dbvals -> query($sql);
		$v = '';
		$c = $dbvals -> num_rows();
		$i =0;
		while($dbvals -> next_record())
		{
			if($i+1<$c){
				$v .= $dbvals -> f('name') .', ';
			}
			else{
				$v .= $dbvals -> f('name');
			}
			$i++;
		}

		return $v;
	}

	function _format_value_desc($db_val)
	{
		global $cms_lang;

		if($this -> view == 'use'){
			return $cms_lang[ $db_val['conf_desc_langstring'] ];
		}
		elseif($this -> view == 'dev'){
			$to_return = ( empty($db_val['key1']) ) ? '': $this -> prefix . '[\'' . $db_val['key1'] . '\']';
			$to_return .= ( empty($db_val['key2']) ) ? '': '[\'' . $db_val['key2'] . '\']';
			$to_return .= ( empty($db_val['key3']) ) ? '': '[\'' . $db_val['key3'] . '\']';
			$to_return .= ( empty($db_val['key4']) ) ? '': '[\'' . $db_val['key4'] . '\']';
			return $to_return;
		}
	}


}
?>
