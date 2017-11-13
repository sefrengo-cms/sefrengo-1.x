<?PHP
// File: $Id: class.validator.php 28 2008-05-11 19:18:49Z mistral $
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
// | Authors: Jürgen Brändle <braendle@web.de>                            |
// +----------------------------------------------------------------------+
// | Changed: 08.08.2004 - Jürgen Brändle                                 |
// |          _css_element: Bugfix für CSS-Werten mit 0 ohne Einheit      |
// |          _create_matchstring: neue Funktion für _css_elements        |
// |          show: private Funktion umbenannt in _show                   |
// |          Dokumentation teilweise nachgezogen                         |
// |          27.08.2004 - Jürgen Brändle                                 |
// |          css_elements:  Leere Stilangaben werden als korrekt be-     |
// |          trachtet und nicht mehr als Stile mit doppelten Elementen   |
// |          als fehlerhaft gekennzeichnet                               |
// |          css_rulename:  []~ als erlaubte Zeichen hinzugefügt, für    |
// |          CSS2-Selektoren                                             |
// |          04.09.2004 - Jürgen Brändle                                 |
// |          neue Funktion: cssrule_vendor_specific(), prüft ob ein      |
// |          CSS-Selektor eine spezielle Erweiterung durch den Herstel-  |
// |          ler darstellt (CSS2.1-Spec: 4.1.2)                          |
// +----------------------------------------------------------------------+

/**
 * Klasse validator
 * 
 * Klasse mit Funktion zum Validitieren von Benutzereingaben
 *
 * @author	Jürgen Brändle
 * @since	ALPHA
 * @version 1.0.2 / 20040827
**/
class validator {

	var $css_elements;
	var $css_units;
	var $warnings;
	var $cfg_cms;
	
	//
	// constructor
	//
	function __construct() {
		$this->cfg_cms = $GLOBALS['cfg_cms'];
		$this->preg_limiter = utf8_encode('ö');
	}


	//
	// css-validator
	//
	
	//
	// cssrule_name( $name )
	//
	// check if a name of a css-rule is valid
	// - must begin with a character, underscore or dash
	// - may contain any of these characters  >#:._-,[]~ and space
	//
	// return
	//	true is $name is valid, otherwise false
	//
	// jb - 27.08.2004 - []~ als erlaubte Zeichen hinzugefügt, für CSS2
	function cssrule_name($name) {
		if (preg_match("/([A-Za-z_-][A-Za-z0-9\-_\.\: \,\#>\[\]~]*)/", $name, $match) > 0) return (strlen($match[0]) == strlen($name));
		return false;
	}

	//
	// cssrule_vendor_specific( $name )
	//
	// check if a name of a css-rule is vendor-specific
	// - must begin _- or mso-
	//
	// return
	//	true is $name is vendor-specific, otherwise false
	//
	function cssrule_vendor_specific($name) {
		if (preg_match("/^(_|-|mso-)/", $name, $match) > 0) return true;
		return false;
	}

	//
	// css_elements( $styles )
	//
	// check if the elements of a css-rule are valid
	//
	// return
	//	true if valid, otherwise false
	//
	// jb - 27.08.2004
	function css_elements($styles, $check) {
		global $css_warnings, $cms_lang;
		// jb - 27.08.2004: added check for empty styles, treated as okay
		if (!$check || trim($styles) == ';') return '1';
		else {
			$css_warnings = '';
			// split the style-rules into an array of elements, split-char is ';'
			$elements = explode( ';', $styles);
			foreach($elements as $value) {
				// all empty values will be ignored ... minor failure in syntax of css-rule
				if (trim($value) != '') {
					// get element name and value
					$elementname  = trim(substr($value, 0, strpos($value, ":")));
					$elementvalue = trim(substr($value, strpos($value, ":")+1));
					$names[]      = $elementname;
					// check the element
					$this->_css_element($elementname, $elementvalue);
					if ($this->warnings) $css_warnings .= $this->warnings;
					$this->warnings = '';
				}
			}
			// check duplicated elements
			$original = count($names);
			$unique   = count(array_unique($names));
			if ($original != $unique) $css_warnings .= $cms_lang['css_validator_001'] . $this->warnings;
			// return 
			return empty($css_warnings);
		}
	}
	
	//
	// file validator
	//
	
	//
	// filename( $name )
	//
	// check if a name of a css-file is valid
	// - must begin with a character or underscore
	// - may not contain any of these characters  \/:?*"<>|' and special char like \n,\r,\t
	// - may not be longer than 200 chars
	//
	// return
	//	true is $name is valid, otherwise false
	//
	function filename($name, $chars = '') {
		// get list of forbidden chars
		$trouble  = '\\&?:#\/\"\'@\*' . $chars;
		// test name and return results
		$isOkay = (preg_match('/(^\.{1,2}\/)|(\/(\.){1,2}\/)|[' . $trouble . ']/i', $name) == 0);
		$isOkay = $isOkay && (strlen($name) < 200);
		return $isOkay;
	}

	//
	// filepath( $name, $slashokay )
	//
	// check if a name of a director-path is valid
	// - must begin with a character or underscore
	// - may contain any of these characters  \:?*"<>|' and special char like \n,\r,\t
	// - may not be longer than 200 chars
	// 
	// forward slash may be allowed to create subfolders, default: no forward slashes allowed
	//
	// return
	//	true is $name is valid, otherwise false
	//
	function filepath($name, $slashokay = false, $chars = '') {
		// get list of forbidden chars
		$trouble  = '\\&?:#\"\'@\*';
		$trouble .= ($slashokay) ? '':'\/';
		$trouble .= $chars;
		// test name and return results
		$regexp = '/(^\.{1,2}\/)|(\/(\.){1,2}\/)|[' . $trouble . ']/i';
		$isOkay = (preg_match($regexp, $name) == 0);
		$isOkay = $isOkay && (strlen($name) < 200);
		return $isOkay;
	}

	//
	// normalize_name(&$name)
	//
	// replaces special characters found in a name by "-"
	// user may extend or replace the standard list with the configuration setting "trouble_chars"
	// the following chars will always be removed:
	// 1. single or double quote
	// 2. #
	// 3. :
	// 4. /
	// 5. \ (optional)
	// 6. ?
	// 7. &
	// 8. @
	// 9. *
	//
	// return
	//	$name changed
	//
	function normalize_name(&$name, $slashokay = false, $chars = '') {
		//special replacement for some chars
//		$regs = array('/[ÄÆ]/', '/[äæ]/', '/[ÀÁÂÃÅ]/', '/[àáâãå]/', '/[Ö]/', '/[ö]/', '/[Ü]/', '/[ü]/', 
//						'/[ß]/', '/[ÒÓÔÕ]/', '/[òóôõ]/', '/[ÙÚÛ]/', '/[ùúû]/', '/[ÈÉÊË]/', '/[èéêë]/');
//		$replacements = array('Ae', 'ae', 'A', 'a', 'Oe', 'o', 'Ue', 'ue', 
//								'ss', 'O', 'o', 'U', 'u', 'E', 'e', 'Ae');
//		$name = preg_replace('/[ÄÆ]/u', 'Ae', $name);
		
		// get list of forbidden chars
		$trouble  = ' \\&?:#\"\'@\*';
		$trouble .= ($slashokay) ? '':'\/';
		$trouble .= (empty($chars)) ? $this->cfg_cms['trouble_chars']: $chars;
		// replace chars
		$regexp = '/[' . $trouble . ']/iu';
		//$regexp = '/[ ]/i';
		$name = preg_replace($regexp, '-', $name);
	}



	//
	// private methods
	//

	//
	// css-related private methods
	//
	function _css_element($name, $evalue) {
		global $cms_lang, $val_ct;

		// container for warnings
		$this->warnings = '';
		
		// check if element and unit lists are available
		if (!$this->css_units)    $this->css_units    = $val_ct->get_by_group('css_units', 0);
		if (!$this->css_elements) $this->css_elements = $val_ct->get_by_group('css_elements', 0);

		// if information for testing is available check the element value
		if ($this->css_elements[$name]) {
			// compose regexp for the css-element $name out of the vaild css-units for the element
			$strRegExp = (isset($this->css_elements[$name]['position'])) ? $this->css_elements[$name]['position']: '';
			$flags     = (isset($this->css_elements[$name]['flags']))    ? $this->css_elements[$name]['flags']: 0;
			$value     = $this->css_elements[$name]['units'];
			for($key = 0; $key < count($value); $key++) {
				$reg_part = '';
				foreach($this->css_units[$value[$key]] as $value1) if ($value1) $reg_part .= (($reg_part) ? '|': '') . $value1;
				$strRegExp = ($strRegExp) ? str_replace($this->preg_limiter.'REGEXP'.$key.$this->preg_limiter, $reg_part, $strRegExp): $reg_part;
			}
       // echo $strRegExp . "<br />";
			// no regexp ... nothing to match ... this is okay - always
			if ($strRegExp) {
				// if there is a valid regexp, then we check the value to match the expression
				preg_match_all('/(\!\s*?important)$/i', $evalue, $important, PREG_PATTERN_ORDER);
				preg_match_all('/'.$strRegExp.'/i'    , $evalue, $match    , PREG_PATTERN_ORDER);
				// remove unvalid matches of whitespaces
				foreach($match[0] as $value2) {
					if (trim($value2)) {
						$validmatch[] = trim($value2);
					} else {
						// prüfen ob 0 als Zahl verwendet wurde ... php-spezial wegen '0' == '' == empty
						if ($value2 == '0') $validmatch[] = $value2;
					}
				}
				$match[0] = $validmatch;
				// check if $match[0] is set 
				if (!isset($match[0]) || !is_array($match[0]) || count($match[0]) == 0) {
					// no match ... rule is not valid 
					$this->warnings = $cms_lang['css_validator_002'];
				} else {
					// do further rule checking depending on $flags for the element
					$this->_show($match, $important, $strRegExp);
					$original = count($match[0]);
					$unique   = count(array_unique($match[0]));
					if ($flags & 0x80) {
						// check for unique matches
						if ($original != $unique) $this->warnings .= $cms_lang['css_validator_003'];
					}
					if ($flags & 0x40) {
						// check for a maximum count of matches
						$count = (($flags & 0x80) ? $unique: $original);
						if (($flags & 0x0F) < $count) $this->warnings .= $cms_lang['css_validator_004'];
					}
					if ($flags & 0x30) {
						// check for identity - matched values must equal trimed value
						// extra test auf 0 als Wertangabe ohne Einheit
						// prüfen ob 0 als Zahl verwendet wurde ... php-spezial wegen '0' == '' == empty
						if ($flags & 0x20) {
							$matchstring = $this->_create_matchstring( $match[0] );
						} else {
							$matchstring = $this->_create_matchstring( array_unique($match[0]) );
						}
						if (isset($important[0]))  foreach($important[0] as $value) $matchstring .= (($value && trim($value) != '') ? $value . ' ': '');
						if (strlen($evalue) != strlen(trim($matchstring))) $this->warnings .= $cms_lang['css_validator_005'];
					}
				}
				if ($this->warnings) $this->warnings = '-ele-' . $name . ': ' . $evalue .';,' . $this->warnings;
			}
		} else {
			// ignorierte elemente
		}
	}

	/**
	 * 
	 * Ermittelt alle gültigen Texte aus dem übergebenen Array
	 * Ein gültiger Text muss folgenden Bedingungen genügen:
	 * 1. Der Text darf nicht leer sein
	 * 2. Der Text darf nach Entfernen aller führenden und nachfolgenden Leerzeichen nicht leer sein
	 * 3. Der Text darf den Wert '0' enthalten (spezial wegen php: '0' == empty)
	 *
	 * @param	array	$matches			Array mit Texten
	 *
	 * @return	string	String mit allen gültigen Matches
	 *
	 * @Version: 0.1 / 20040808
	 * Change: -
	 *
	**/
	function _create_matchstring( $matches ) {
		$matchstring = '';
		
		foreach($matches as $value) {
			$matchstring .= ((($value || $value == '0') && trim($value) != '') ? $value . ' ': '');
		}
		
		return $matchstring;
	}
	
	/**
	 * 
	 * Gibt für Debugging verschiedene Informationen aus:
	 * 1. Regular Expression
	 * 2. Alle Matches in Match[0]
	 * 3. Alle eindeutigen Matches
	 * 4. Alle Importants-Matches
	 * 5. Alle Matches mit Submatches
	 *
	 * @param	array	$match		Array mit Treffern
	 * @param	array	$important	Array mit Important-Treffer
	 * @param	string	$strRegExp	Regular Expression
	 *
	 * @Version: 0.1 / ALPHA
	 * Change: -
	 *
	**/
	function _show($match, $important, $strRegExp) {
		return true;
		echo $strRegExp."<br />\n";
		foreach($match[0] as $value2) echo '--> Match: ' . $value2 . "<br />\n";
		foreach(array_unique($match[0]) as $value2) echo '--> Unique Match: ' . $value2 . "<br />\n";
		foreach($important[0] as $value2) echo '--> Important: ' . $value2 . "<br />\n";
		foreach($match as $key => $value2) {
			echo '--> Match: ' . $key . "<br />\n";
			foreach($value2 as $value3) echo '--> Match: ' . $value3 . "<br />\n";
		}
	}

} // end of class validator
?>
