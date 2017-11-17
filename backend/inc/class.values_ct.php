<?PHP
// File: $Id: class.values_ct.php 28 2008-05-11 19:18:49Z mistral $
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

class values_ct
{
	var $cached_values = array();

	/**
	* Konstruktur, holt Wertegruppen aus der Tabelle cms_values, welche
	* immer gebraucht werden. Dies sind momentan die Gruppen config
	*
	*
	**/
	function __construct()
	{
		//CHANGE STam
		global $cms_db, $db, $cms_lang, $lang, $client;
		$_lang = (int) $lang > 0 ? ", " . $lang : '';
		$_client = (int) $client > 0 ? ", " . $client : '';
		$sql = "SELECT 		*
				FROM		". $cms_db['values'] ."
				WHERE		group_name IN ('cfg', 'lang')
				AND			idclient IN (0 " . $_client .")
				AND			idlang IN (0 " . $_lang . ")
				ORDER BY conf_sortindex";
		//CHANGE STam
		$db -> query($sql);

		while($db -> next_record())
		{
			//für globale langstrings (hauptsächlich für plugins gedacht)
			if($db->f('group_name') == 'lang'){
				$cms_lang[$db->f('key1')] = $db->f('value');
			}
			//Systemvariablen
			else{
				if($db->f('key2') == '' && $db->f('key1') != ''){
					$temp[$db->f('key1')] = $db->f('value');
				}
				elseif($db->f('key3') == ''){
					$temp[$db->f('key1')][$db->f('key2')] = $db->f('value');
				}
				elseif($db->f('key4') == ''){
					$temp[$db->f('key1')][$db->f('key2')][$db->f('key3')] = $db->f('value');
				}
				elseif($db->f('key1') == ''){
					$temp[$db->f('key1')][$db->f('key2')][$db->f('key3')][$db->f('key4')] = $db->f('value');
				}
			}
		}

		$this -> cached_values['cfg'] = $temp;

		//die($sql);
	}

	function get_by_group($group, $client=0, $lang=0)
	{
		global $cms_db, $db;
		
		$client= (int) $client; 
		$lang = (int) $lang;


		$sql = "SELECT 		*
				FROM		". $cms_db['values'] ."
				WHERE		group_name IN ('$group')
				AND			idclient IN ($client, 0)
				AND			idlang IN ($lang, 0)
				ORDER BY conf_sortindex";

		$db -> query($sql);

		return $this -> _make_array($db);
	}

 	function get_by_group_key($group, $key, $key_value, $client=0, $lang=0)
	{
		global $cms_db, $db;

		$sql = "SELECT 		*
				FROM		". $cms_db['values'] ."
				WHERE		group_name IN ('$group')
				AND			idclient IN ($client, 0)
				AND			idlang IN ($lang, 0)
        AND     $key IN ('$key_value')
				ORDER BY conf_sortindex";
		$db -> query($sql);

		return $this -> _make_array($db);
	}

  function _make_array(&$db) {
		while($db -> next_record())
		{
			if($db->f('key2') == '' && $db->f('key1') != ''){
				$temp[$db->f('key1')] = $db->f('value');
			}
			elseif($db->f('key3') == ''){
				$temp[$db->f('key1')][$db->f('key2')] = $db->f('value');
			}
			elseif($db->f('key4') == ''){
				$temp[$db->f('key1')][$db->f('key2')][$db->f('key3')] = $db->f('value');
			}
			elseif($db->f('key1') == ''){
				$temp[$db->f('key1')][$db->f('key2')][$db->f('key3')][$db->f('key4')] = $db->f('value');
			}
		}

		return $temp;
	}

	function get_cfg()
	{
		return $this -> cached_values['cfg'];
	}

	/**
	* Einen Wert in der Valuetabelle angeben. Existiert in der Datenbank
	* schon der entsprechende key- Kombination [1...4] zu einem Wert
	* wird dieser geupdatet. Ist die keykombination noch nicht vorhanden,
	* wird der entsprechende Eintrag neu erzeugt.
	*
	* Beispiel:
	*	$val_ct -> set_value(array(
	*						'group' 	=> 'test_gruppe',
	*						'key' 		=> 'key_des_arrays',
	*						'value'		=> 'wert_des_array'	))
	*
	* @args $mixed['client'] default '0'
	*             ['lang'] default '0'
	*             ['group'] default '0'
	*             ['key']
	*             ['key2']
	*             ['key3']
	*             ['key4']
	*             ['value']
	*             ['id']
	*
	* @return array
	*/
	function set_value($mixed)
	{
		global $cms_db, $db;
		//build query

		$sql_group = (empty($mixed['group'])) ? 0: ''.$mixed['group'];
		$sql_client = (empty($mixed['client'])) ? '': 'AND idclient IN ('. intval($mixed['client']) .')';
		$sql_lang = (empty($mixed['lang'])) ? '': 'AND idlang IN ('. intval($mixed['lang']) .')';
		$sql_key = (empty($mixed['key'])) ? '': 'AND V.key1 = "'. $mixed['key'] . '" ';
		$sql_key2 = (empty($mixed['key2'])) ? '': 'AND V.key2 = "'. $mixed['key2'] . '" ';
		$sql_key3 = (empty($mixed['key3'])) ? '': 'AND V.key3 = "'. $mixed['key3'] . '" ';
		$sql_key4 = (empty($mixed['key4'])) ? '': 'AND V.key4 = "'. $mixed['key4'] . '" ';
		$sql_id = (empty($mixed['id'])) ? "": "AND V.idvalues = '". intval($mixed['id']) . "' ";


		$sql = "SELECT 		*
				FROM		". $cms_db['values'] ."  AS V
				WHERE		V.group_name IN ('$sql_group')
				$sql_client $sql_lang
				$sql_key  $sql_key2  $sql_key3  $sql_key4 $sql_id";

		//die($sql);
		$db -> query($sql);

		$count_rows = $db ->num_rows();

		if($count_rows > 1){
			echo $sql .'<br> Fehler in Klasse "cms_value_ct". Es wurde mehr als ein Ergebnis gefunden. Anfrage ist nicht eindeutig';
			exit;
		}
		elseif($count_rows == 1){
			$db -> next_record();
			$mixed['id'] = $db -> f('idvalues');
			//echo "update";
			$this -> _update_by_id($mixed);
		}
		else{
			$this -> insert($mixed);
		}

	}

	/**
	* Einen neuen Datensatz in die Tabelle einfügen.
	* Um eine gültige Eingabe zu haben, muss zumindest der Wert
	* key angegeben werden.
	*
	* @args $mixed['client'] default '0'
	*             ['lang'] default '0'
	*             ['group'] default '0'
	*             ['key']
	*             ['key2']
	*             ['key3']
	*             ['key4']
	*             ['value']
	*/
	function insert($mixed)
	{
		global $cms_db, $db;

		if( empty($mixed['key']) ){return false;}

		//build query
		set_magic_quotes_gpc($mixed['value']);
        //$mixed['value'] = make_string_dump ($mixed['value']);
		$sql_group = (empty($mixed['group'])) ? 0: '"'. $mixed['group'] .'"';
		$sql_client = (empty($mixed['client'])) ? 0: $mixed['client'];
		$sql_lang = (empty($mixed['lang'])) ? 0: $mixed['lang'];
		$sql_key = (empty($mixed['key'])) ? '""': '"'. $mixed['key'] . '" ';
		$sql_key2 = (empty($mixed['key2'])) ? '""': '"'. $mixed['key2'] . '" ';
		$sql_key3 = (empty($mixed['key3'])) ? '""': '"'. $mixed['key3'] . '" ';
		$sql_key4 = (empty($mixed['key4'])) ? '""': '"'. $mixed['key4'] . '" ';
		$sql_value = (empty($mixed['value'])) ? '""': "'". $mixed['value'] . "' ";
		$sql = "INSERT INTO `". $cms_db['values'] ."` (`idvalues`, `idclient`, `idlang`, `group_name`, `key1`, `key2`, `key3`, `key4`, `value`)
				VALUES		(\"\",$sql_client, $sql_lang, $sql_group, $sql_key, $sql_key2,
							 $sql_key3, $sql_key4, $sql_value)";
		$db -> query($sql);
	}
	/**
	* Einen Datensatz über die ID updaten.
	* Um eine gültige Eingabe zu haben, muss der Wert
	* id angegeben werden.
	*
	* @access private
	* @args $mixed['id']
	*             ['value']
	*/
	function _update_by_id($mixed)
	{
		global $cms_db, $db;

		//build query
        set_magic_quotes_gpc($mixed['value']);
        //$mixed['value'] = make_string_dump ($mixed['value']);
		$sql_value =  " value ='". $mixed['value'] . "' ";
		$sql = "UPDATE 		". $cms_db['values'] ."
				SET			$sql_value
				WHERE		idvalues = " . $mixed['id'] ;
		//die($sql);

		$db -> query($sql);
	}
}

?>
