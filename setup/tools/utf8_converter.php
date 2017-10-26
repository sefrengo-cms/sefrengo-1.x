<?php

class UTF8Converter{
	
	var $action;
	
	var $data = array('host' => 'localhost',
						'database' => '',
						'username' => '',
						'password' => '',
						'tables' => array(),
						'mode' => 'encode', //encode or decode utf-8
						'js_at_end' => false,
						'current_table' => '',
						'current_limit_start' => 0,
						'current_limit_max' => 50);
	
	var $tables_postfix_backup = '_backup';
	var $tables_postfix_converted = '_conv';
	
	var $output;
	var $mysql_con_handle;
	
	
	function __construct() {
		$this->action = $_REQUEST['action'];
		$this->importDataString();
	}
	
	function exportDataString() {
		return base64_encode(serialize($this->data));
	}
	
	function importDataString() {
		if(isset($_REQUEST['data'])) {
			$this->data = unserialize(base64_decode($_REQUEST['data']));
		}
	}
	
	function tables_was_chosen() {
		return count($this->data['tables']) > 0;
	}
	
	function assignRequestToData($request_keys = array()) {
		foreach ($request_keys AS $v) {
			if (is_array($_REQUEST[$v]) || is_array($this->data[$v])) {
				//handle array requests
				$this->data[$v] = array();
				if (is_array($_REQUEST[$v])) {
					foreach ($_REQUEST[$v] AS $v2) {
						array_push($this->data[$v], $v2);
					}
				} 
			} else {
				//handle normal vars
				$this->data[$v] = $_REQUEST[$v];
			}
		}
	}
	
	
	
	function start() {
		switch($this->action) {
			case 'select_tables':
				$this->assignRequestToData(array('host','database','username','password'));
				if($this->_connect()) {
					$this->paintTableSelectForm();
				} else {
					$this->paintUserdataForm('Es konnte keine Verbindug zur Datanbank hergestellt werden');
				}
				break;
			case 'create_table_definitions':
				$this->assignRequestToData(array('tables', 'mode'));
				$this->_connect();
				if ($this->tables_was_chosen() ) {
					$this->paintTableConvertInfo();
				} else {
					$this->paintTableSelectForm('Es wurden keine Tabellen ausgewählt');
				}
				break;
			case 'start_from_external_config':
				$this->_connect();
				$this->paintTableConvertInfo();
				break;
			case 'convert_table_data':
				$this->_connect();
				if ($this->tables_was_chosen() ) {
					$this->paintDataConvertInfo();
				} else {
					$this->paintTableSelectForm();
				}
				break;	
			case 'finish':
				$this->_connect();
				$this->paintSuccessScreen();
				break;
			case 'start':
			default:
				$this->paintUserdataForm();
				break;
		}
		
		echo $this->output;
	}
	
	function paintUserdataForm($error = false) {
		
		$out = '';
		
		if($error) {
			$out .= '<span style="color:red;font-weight:bold;">'.$error.'</span>';
		}
		
		$out .=  '<form action ="utf8_converter.php" name="data" method="post">
		Host: <input type="text" id="input" name="host" value="'.$this->data['host'].'" size="10" "><br />
		Datenbank: <input type="text" id="input" name="database" value="'.$this->data['database'].'" size="10" ><br />
		Benutzername: <input type="text" id="input" name="username" value="'.$this->data['username'].'" size="10" ><br />
		Passwort: <input type="password" id="input" name="password" autocomplete="off" value="'.$this->data['password'].'" size="10" ><br />
		<input type ="hidden" name = "action" value="select_tables">
		<input type ="hidden" name = "data" value="'.$this->exportDataString().'">
		<input type="submit" value="OK" />
		</form>';
		
		$this->output = $this->getHTMLCoat($out);
	}
	
	function paintTableSelectForm($error = false) {
		$out = '';
		
		if($error) {
			$out .= '<span style="color:red;font-weight:bold;">'.$error.'</span>';
		}
		
		$sql = 'SHOW TABLES';
		$result = mysqli_query ($this->mysql_con_handle, $sql);
		//print_r($this->data);
        while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
           foreach ($line AS $col_value) {
				$sel = (in_array($col_value, $this->data['tables'])) ? 'selected="selected"': '';

               if (!isset($options)) {
                   $options = '';
               }
               $options .= sprintf('  <option value="%s"  %s>%s</option>'. "\n", $col_value, $sel, $col_value);
			}
        }

        mysqli_free_result($result);
		$select  = '<select name="tables[]" size="20" multiple="multiple">' ."\n";
		$select .= $options;
		$select .= '</select>' ."\n";
		
		$select_mode  = '<select name="mode" size="1">' ."\n";
		$sel = ($this->data['mode'] == 'encode') ? 'selected="selected"': '';
		$select_mode .= sprintf('  <option value="%s"  %s>%s</option>'. "\n", 'encode', $sel, 'Von ISO8859-1 nach UTF-8 konvertieren');
		$sel = ($this->data['mode'] == 'decode') ? 'selected="selected"': '';
		$select_mode .= sprintf('  <option value="%s"  %s>%s</option>'. "\n", 'decode', $sel, 'Von UTF-8 nach ISO8859-1 konvertieren');
		$select_mode .= '</select>' ."\n";

		$out .= '<form action ="utf8_converter.php" name="data" method="post">
		Bitte wählesn Sie die zu konvertierenden Tabellen:<br />
		'.$select.'<br />'.$select_mode.'
		<input type ="hidden" name = "action" value="create_table_definitions">
		<input type ="hidden" name = "data" value="'.$this->exportDataString().'">
		<input type="submit" value="OK" />
		</form>';
		
		$this->output = $this->getHTMLCoat($out);
	}
	
	function paintTableConvertInfo($error = false) {
		$sql = '';
		
		//make convert tables
		foreach($this->data['tables'] AS $v) {
			$sql .= $this->getTableDef($v, $this->tables_postfix_converted, true);
		}
		$this->execDump($sql);
		
		$out = 'Temporäre Tabellen werden angelegt.'
			.'<meta http-equiv="refresh" content="0; URL=utf8_converter.php?action=convert_table_data&amp;data='.$this->exportDataString().'">';
			//.'<br /><br /><a href="utf8_converter.php?action=convert_table_data&amp;data='.$this->exportDataString().'">Link</a>';
		
		$this->output = $this->getHTMLCoat(nl2br($out)); 
	}
	
	function paintDataConvertInfo() {
		$sql = '';
		$action = 'convert_table_data';
		
		if ($this->data['current_table'] == '') {
			$this->data['current_table'] = $this->data['tables']['0'];
		}
		
		$current_table = $this->data['current_table'];
		$current_start = $this->data['current_limit_start'];
		$current_stop = ($this->data['current_limit_start']+$this->data['current_limit_max']);
		$sql = $this->getTableContent($this->data['current_table'], $this->tables_postfix_converted, 
					$this->data['current_limit_start'], 
					$this->data['current_limit_max']);
						
		if(trim($sql) !='') {
			if ($this->data['mode'] == 'encode') {
				//if(! $this->isUTF8($sql)) {
					$sql = utf8_encode($sql);
				//}
			} else {
				//if( $this->isUTF8($sql) ) {
					$sql = utf8_decode($sql);
				//}
			}
			$this->execDump($sql);
			$this->data['current_limit_start'] = ($this->data['current_limit_start']+$this->data['current_limit_max']);
		} else {
			$old_table = $this->data['current_table'];
			foreach($this->data['tables'] AS $k=>$v) {
				if ($v == $this->data['current_table']) {
					if($this->data['tables'][($k+1)] != '') {
						$this->data['current_table'] = $this->data['tables'][($k+1)];
						$this->data['current_limit_start'] = 0;
						break;
					}
				}
			}
			//no new table found - finish convert
			if ($old_table == $this->data['current_table']) {
				$action = 'finish';
			}
		}
		
		$out = 'Tabelle '.$current_table.' Datensatz '.$current_start.' bis '.$current_stop.' wird konvertiert.'
			.'<meta http-equiv="refresh" content="0; URL=utf8_converter.php?action='.$action.'&amp;data='.$this->exportDataString().'">';
			//.'<br /><br /><a href="utf8_converter.php?action='.$action.'&amp;data='.$this->exportDataString().'"></a>';
		
		$this->output = $this->getHTMLCoat($out); 
	}
	
	function paintSuccessScreen() {
		foreach($this->data['tables'] AS $v) {
			mysqli_query($this->mysql_con_handle,'DROP TABLE IF EXISTS '.$v.$this->tables_postfix_backup);
			mysqli_query($this->mysql_con_handle,'ALTER TABLE '.$v.' RENAME '.$v.$this->tables_postfix_backup);
			mysqli_query($this->mysql_con_handle,'ALTER TABLE '.$v.$this->tables_postfix_converted.' RENAME '.$v);
		}
		
		$out = '';
		if($this->data['js_at_end']) {
			$out .= '
			<script type="text/javascript">
	   			window.parent.enableSubmitButton();
			</script>';
		}
		
		$out .= 'Konvertiervorgang erfolgreich beendet';
		
		$this->output = $this->getHTMLCoat($out); 
	}
	
	function execDump($sql_data) {
		$sql_data = $this -> remove_remarks($sql_data);
		$sql_pieces = $this -> split_sql_file($sql_data, ';');
		$sql_data = '';
		foreach ($sql_pieces AS $v) {
			$sql = trim($v);
			if (!empty($sql)) {
				//echo $sql. "<br /><br />";
				mysqli_query($this->mysql_con_handle, $sql);
			}
		}
		
	}

	function _connect() {
		$con_handle = @mysqli_connect($this->data['host'], $this->data['username'], $this->data['password']);
		if(! mysqli_select_db($con_handle, $this->data['database']) ) {
			return false;
		}
		$this->mysql_con_handle = $con_handle;
		return true;			
	}
	
	
	function getHTMLCoat($content) {
		
		return '<html><head></head><body>'.$content.'</body></html>';
		
	}

	function isUTF8($val) {
        // only asccii 0-127 are in use - utf8 conform
        if (! preg_match('/[\x80-\xff]/', $val)) {
            return true;
        } 
		// multibyte check
        return preg_match('/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' . '[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})+$/', $val); 
    } 
	
	
	/*
	 * MySql Dump Functions
	 * 
	 */
	
	
	/**
	 * Returns table definition of a selected table.
	 * 
	 * @param string table 
	 * @param boolean drop insert drop datbase command at beginning of the definition
	 * @param string crlf limiter between lines (\n)
	 * @return string table definition
	 */
	function getTableDef($table, $postfix=false, $drop=false, $crlf = "\n")
	{
	
		$schema_create = '';
		$field_query = "SHOW FIELDS FROM $table";
		$key_query = "SHOW KEYS FROM $table";
	
		//
		// If the user has selected to drop existing tables when doing a restore.
		// Then we add the statement to drop the tables....
		//
		if ($drop == 1)
		{
			$schema_create .= "DROP TABLE IF EXISTS $table$postfix;$crlf";
		}
	
		$schema_create .= "CREATE TABLE $table$postfix($crlf";
	
		//
		// Ok lets grab the fields...
		//
		$result = mysqli_query($this->mysql_con_handle, $field_query);
		if(!$result)
		{
			die("Failed in get_table_def (show fields)");
		}
	
		while ($row = mysqli_fetch_array($result))
		{	
			
			$schema_create .= '	`' . $row['Field'] . '` ' . $row['Type'];
	
			if(!empty($row['Default']))
			{
				$schema_create .= ' DEFAULT \'' . $row['Default'] . '\'';
			}
	
			if($row['Null'] != "YES")
			{
				$schema_create .= ' NOT NULL';
			}
	
			if($row['Extra'] != "")
			{
				$schema_create .= ' ' . $row['Extra'];
			}
	
			$schema_create .= ",$crlf";
		}
		//
		// Drop the last ',$crlf' off ;)
		//
		$schema_create = preg_replace('#,' . $crlf . '$#', "", $schema_create);
	
		//
		// Get any Indexed fields from the database...
		//
		$result = mysqli_query($this->mysql_con_handle, $key_query);
		if(!$result)
		{
			die("FAILED IN get_table_def (show keys)");
		}
	
		while($row = mysqli_fetch_array($result))
		{
			//print_r($row);echo "-- $x<br>";
			$kname = $row['Key_name'];
	
			if(($kname != 'PRIMARY') && ($row['Non_unique'] == 0))
			{
				$kname = "UNIQUE|$kname";
			} 
			else if ($row['Comment'] == 'FULLTEXT' || $row['Index_type'] == 'FULLTEXT') 
			{
				$kname = "FULLTEXT|$kname";
			}

            if (!isset($index) || !is_array($index)) {
			    $index = [];
            }
            if(!is_array($index[$kname]))
			{
				$index[$kname] = array();
			}
	
			$index[$kname][] = $row['Column_name'];
		}
	
		while(list($x, $columns) = @each($index))
		{
			$schema_create .= ", $crlf";
			if($x == 'PRIMARY')
			{
				$schema_create .= '	PRIMARY KEY (' . implode($columns, ', ') . ')';
			}
			elseif (substr($x,0,6) == 'UNIQUE')
			{
				$schema_create .= '	UNIQUE ' . substr($x,7) . ' (' . implode($columns, ', ') . ')';
			}
			elseif (substr($x,0,8) == 'FULLTEXT')
			{
				$schema_create .= '	FULLTEXT KEY ' . substr($x,9) . ' (' . implode($columns, ', ') . ')';
			}
			else
			{
				$schema_create .= "	KEY `$x` (" . implode($columns, ', ') . ')';
			}
		}
	
		$schema_create .= "$crlf);";
	
		if(get_magic_quotes_runtime())
		{
			return(stripslashes($schema_create));
		}
		else
		{
			return($schema_create);
		}
	
	}



	/**
	 * Get content of one table.
	 * @param string table the table to dump
	 * @param int limit_start offset where to begin dumping
	 * @param int limit_max max rows to fetch
	 * @return string dump
	 */
	function getTableContent($table, $postfix, $limit_start, $limit_max)
	{
		//
		// Grab the data from the table.
		//
		//echo "SELECT * FROM $table LIMIT $limit_start, $limit_max";
		$result = mysqli_query($this->mysql_con_handle, "SELECT * FROM $table LIMIT $limit_start, $limit_max");
	
		if (!$result)
		{
			die("Failed in get_table_content (select *) - SELECT * FROM $table");
		}
	
		if(mysqli_num_rows($result) > 0)
		{
			$schema_insert = "\n#\n# Table Data for $table\n#\n";
		}
		else
		{
			$schema_insert = "";
		}
	
		//$handler($schema_insert);
	
		//
		// Loop through the resulting rows and build the sql statement.
		//
	
		while ($row = mysqli_fetch_array($result))
		{
			$table_list = '(';
			$num_fields = mysqli_num_fields($result);
			//
			// Grab the list of field names.
			//
			for ($j = 0; $j < $num_fields; $j++)
			{
				$table_list .=  @mysqli_fetch_field_direct($result, $j)->name . ', ';
			}
			//
			// Get rid of the last comma
			//
			$table_list = preg_replace('#, $#', '', $table_list);
			$table_list .= ')';
			//
			// Start building the SQL statement.
			//
			$schema_insert = "INSERT INTO $table$postfix $table_list VALUES(";
			//
			// Loop through the rows and fill in data for each column
			//
			for ($j = 0; $j < $num_fields; $j++)
			{
				if(!isset($row[$j]))
				{
					//
					// If there is no data for the column set it to null.
					// There was a problem here with an extra space causing the
					// sql file not to reimport if the last column was null in
					// any table.  Should be fixed now :) JLH
					//
					$schema_insert .= ' NULL,';
				}
				elseif ($row[$j] != '')
				{
					$schema_insert .= ' \'' . $this->make_string_dump($row[$j]) . '\',';
				}
				else
				{
					$schema_insert .= '\'\',';
				}
			}
			//
			// Get rid of the the last comma.
			//
			$schema_insert = preg_replace('#,$#', '', $schema_insert);
			$schema_insert .= ');'."\n";

            if (!isset($final)) {
                $final = '';
            }
            $final .= $schema_insert;
	
		}

        if (empty($final)) {
		    $final = '';
        }

        //echo $final; exit;
		return trim($final);
	}
	
	function make_string_dump($tmp = '') {
		$tmp = str_replace('\\', '\\\\', $tmp);
		$tmp = str_replace('\'', '\\\'', $tmp);
		$tmp = str_replace(array('\x00', '\x0a', '\x0d', '\x1a', "\r\n"), array('\0', '\n', '\r', '\Z', '\r\n'), $tmp);
		return $tmp;
	}
	
	function remove_remarks($sql) {
		$lines = explode("\n", $sql);

		// try to keep mem. use down
		$sql = '';
		$linecount = count($lines);
		$output = '';
		for ($i = 0; $i < $linecount; $i++) {
			if (($i != ($linecount - 1)) || (strlen($lines[$i]) > 0)) {
				if ($lines[$i]['0'] != '#' && ! preg_match('#^--#', $lines[$i]) ) $output .= $lines[$i] . "\n";
				else $output .= "\n";

				// Trading a bit of speed for lower mem. use here.
				$lines[$i] = '';
			}
		}
		return $output;
	}

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
}

$u8c = new UTF8Converter();
$u8c->start();