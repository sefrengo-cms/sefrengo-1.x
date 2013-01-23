<?PHP
// File: $Id: fnc.search.php 28 2008-05-11 19:18:49Z mistral $
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

/******************************************************************************
Input:
$options['querystring']	- Der eingegebene String			* Pflicht
$options['sql_tables']	- zu durchsuchende Tables (komma-separiert)	* Pflicht
$options['sql_fields']	- zu durchsuchende Felder (komma-separiert)	* Pflicht
$options['get_fields']	- Felder die zurückgegeben werden sollen	# default: *
$options['sql_where']	- erweiterte Bedingungen
$options['sql_group']	- erweiterte Bedingungen
$options['result_limit']- Anzahl der Ergebnisse pro Seite
$options['result_start']- Anfangspunkt der Ausgabe			# default: 0
$options['sort_index']	- Sortieroption
$options['default_con']	- Verbindung der Wörter OR , AND , QUOT		# default: AND

Output:
$result[num_rows] => 2
$result[result] => Array
(
	[0] => Array
	(
		[field1] => value1,
		[field2] => value2,
	)
	[1] => Array
	(
		[field1] => value1,
		[field2] => value2,
	)
)
******************************************************************************/

function search($options) {
	global $db, $cms_db;

	// Anfrage vorbereiten
	$mod = '';
	$quot = '';
	$first = 1;
	$qfirst = 0;
	if (!$options['querystring'] || !$options['sql_tables'] || !$options['sql_fields']) return false;
	if (!$options['default_con']) $def = 'AND';
         else $def = $options['default_con'];
	if (!$options['get_fields']) $options['get_fields'] = '*';

	// Abfrage aufbauen
	$sql = 'SELECT '.$options['get_fields'].' FROM ';
	$tables = explode(',',$options['sql_tables']);
	$sql .= implode(',',$tables).' WHERE ';
	if ($options['sql_where']) $sql .= $options['sql_where'].' AND (';
         else $sql .= '(';
	$search = cms_stripslashes($options['querystring']);
	$fields = explode(',',$options['sql_fields']);
	$searcharray = explode(' ',$search);
	if ($def != 'QUOT') {

		// Searchparser
		foreach($searcharray AS $word) {
			$ftoken = substr($word, 0, 1);
			if ($mod != 'GQUOT'){
				switch ($word) {
					case 'AND':
                                         	$mod = 'AND';
                                                 $word = '_';
                                                 break;
					case 'UND':
                                         	$mod = 'AND';
                                                 $word = '_';
                                                 break;
					case '&&':
                                         	$mod = 'AND';
                                                 $word = '_';
                                                 break;
					case 'OR':
                                         	$mod = 'OR';
                                                 $word = '_';
                                                 break;
					case 'ODER':
                                         	$mod = 'OR';
                                                 $word = '_';
                                                 break;
					case '||':
                                         	$mod = 'OR';
                                                 $word = '_';
                                                 break;
					case 'NOT':
                                         	$mod = 'NOT';
                                                 $word = '_';
                                                 break;
				}
			}
			if($word != '_' && $mod != 'GQUOT'){
				switch ($ftoken) {
					case '+':
                                         	$mod = 'AND';
                                                 $word = substr($word, 1);
                                                 break;
					case '-':
                                         	$mod = 'NOT';
                                                 $word = substr($word, 1);
                                                 break;
					case '"':
                                         	if(substr_count($search,'"') > 1) {
                                                 	$word = substr($word, 1);
                                                         $mod = 'QUOT';
                                                 } else $mod = $def;
                                                 break;
					case "'":
                                         	if(substr_count($search,"'") > 1) {
                                                 	$word = substr($word, 1);
                                                         $mod = 'QUOT';
                                                 } else $mod = $def;
                                                 break;
				}
			}
			if ((substr($word, -1, 1) == "'" OR substr($word, -1, 1) == '"') AND (substr_count($search, '"') > 1 OR substr_count($search,"'") > 1)) {
				$word = substr($word, 0, -1);
				$mod = 'EQUOT';
			}

			if($word != '_') {
				$word = addslashes($word);
				if($first == 1){
					if($mod == 'QUOT') {
                                         	$quot = $word;
                                                 $mod = 'GQUOT';
                                                 $qfirst = 1;
                                         } elseif ($mod == 'NOT') {
                                         	$sql .= '(';
                                                 foreach($fields as $field) $sql .= $field." NOT LIKE '%".$word."%' AND ";
                                                 $sql = substr($sql, 0, -5).') '; $mod = '';
                                         } else {
                                         	$sql .= '(';
                                                 foreach($fields as $field) $sql .= $field." LIKE '%".$word."%' OR ";
                                                 $sql = substr($sql, 0, -4).') ';
                                         }
					$first = 0;
				} else {
					switch ($mod) {
						case 'QUOT':
                                                 	$quot = $word;
                                                         $mod = 'GQUOT';
                                                         break;
						case 'GQUOT':
                                                 	$quot .= ' '.$word;
                                                         break;
						case 'EQUOT':
                                                 	if ($qfirst == 1) {
                                                         	$sql .= ' (';
                                                                 $qfirst = 0;
                                                         } else $sql .= $def.' (';
							foreach($fields as $field) $sql .= $field." LIKE '%".$quot.' '.$word."%' OR ";
							$sql = substr($sql, 0, -4).') ';
							$quot = '';
                                                         $mod = '';
							break;
						case 'AND':
                                                 	$sql .= 'AND (';
							foreach($fields as $field) $sql .= $field." LIKE '%".$word."%' OR ";
							$sql = substr($sql, 0, -4).') ';
							$mod = '';
							break;
						case 'OR':
                                                 	$sql .= 'OR (';
							foreach($fields as $field) $sql .= $field." LIKE '%".$word."%' OR ";
							$sql = substr($sql, 0, -4).') ';
							$mod = '';
							break;
						case 'NOT':
                                                 	foreach($fields as $field) $sql .= 'AND '.$field." NOT LIKE '%".$word."%' ";
							$mod = '';
							break;
						case '':
                                                 	$sql .= $def.' (';
							foreach($fields as $field) $sql .= $field." LIKE '%".$word."%' OR ";
							$sql = substr($sql, 0, -4).') ';
							$mod = '';
							break;
					}
				}
			}
		}
	} else {
		foreach($fields as $field) $sql .= $field." LIKE '%".$search."%' OR ";
		$sql = substr($sql, 0, -4);
	}
	$sql .= ') ';
	if($options['sql_group']) $sql .= "GROUP BY ".$options['sql_group'].' ';

	// Anzahl der Einträge finden
	eval("\$sql = \"$sql\";");
	$anz_rows = @$db->num_rows($db->query($sql));

	// Sortierung und Limit
	if($options['sort_index']) $sql .= 'ORDER BY '.$options['sort_index'];
	if($options['result_limit'] > 0){
		if($options['result_start'] <= 0) $options['result_start'] = '0';
		$sql .= ' LIMIT '.$options['result_start'].','.$options['result_limit'];
	}

	// Abfrage ausführen und Ergebnis bereitstellen
	$db->query($sql);
	$result = array();
	if($options['get_fields'] == '*'){
		$options['get_fields'] = '';
		foreach($db->metadata() as $a) $options['get_fields'] .= $a['name'].',';
		$options['get_fields'] = substr($options['get_fields'], 0, -1);
	}
	while($db->next_record()) {
		foreach(explode(',', $options['get_fields']) as $field) {
		    if (preg_match("/ as (.*)$/i", $field, $match)) $field = $match[1];
			if (preg_match("/\.([^ ]*)/", $field, $match)) $field = $match[1];
			$fetch[$field] = $db->f($field);
		}
		array_push($result, $fetch);
	}
	return(array('result' => $result, 'num_rows' => $anz_rows));
}
?>