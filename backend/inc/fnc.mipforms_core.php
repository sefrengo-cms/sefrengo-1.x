<?PHP
// File: $Id: fnc.mipforms_core.php 28 2008-05-11 19:18:49Z mistral $
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
 * MIP FORMS (Modul Input Forms)
 * Vorgefertigte Formulare zur Abstraktion der Eingaben
 * im Modulinput.
 *
 * Struktur:
 * fnc.mipforms.php --> Hauptfunktion
 * fnc.mipforms_core.php --> Diese Datei - Grundfunktionen
 * fnc.mipforms_extensions.php --> Erweiterungen
 * fnc.mipforms_apps.php --> Applikationen
 *
 *
 * @Author: (c) Björn Brockmann 2002
 * @Email: bjoern@project-gooseberry.de
 * @Homepage: www.project-gooseberry.de
 * @License: GPL
 */

//Die Basishtmltemplates zum Erzeugen der Forms
$mip_forms_tab['open']  = '<table class="mipformtable"><tr>';
$mip_forms_tab['close']  = '</table>';
$mip_forms_tab['left']  = '<td class="firsttab" style="width:%spx">%s</td>';
$mip_forms_tab['left_width'] = '450';
$mip_forms_tab['right'] = '<td class="secondtab">%s%s</td></tr>';
$mip_forms_tab['full'] = '<td>%s<br />%s%s</td><td width="5">&nbsp;</td></tr>';
$mip_forms_tab['tab'] = '<td width="20" valign="top">%s</td>';



// @args help_text, value
function mip_forms_abstrakt_help ($help_text,$width=0,$hight=-1)
{
   	global $cms_lang,$cfg_cms;
    if ($help_text != '') 
    {
		if ($width == '' || $width == 0) {
		    $width = 200;    
		}
		if ($hight == '' || $hight == 0) {
		    $hight = -1;    
		}
		$help_link = '';
        $help_text = preg_replace("(\')", '&#39;', $help_text);
        $help_text = htmlspecialchars($help_text,ENT_QUOTES,'UTF-8');
        $help_text = preg_replace("((\r\n)+)", '<br />', $help_text);
        $help_text = preg_replace("((\n)+)", '<br />', $help_text);
		$overlib_cmd = '';
		$overlib_cmd .= ', WIDTH, '.$width;
		$overlib_cmd .= ', HEIGHT, '. $hight;
//		$overlib_cmd .= ', CSSCLASS, folderinfo';
		if ( $hight != -1) {
    		$overlib_cmd .= ', SCROLLBARS ';
	    }
		
		$help_link .= '<a href="javascript:void(0);" onclick="return overlib(\''.$help_text.'\', STICKY, DRAGGABLE, CLOSECLICK, CAPTION, \''. $cms_lang['gen_help'] .'\''.$overlib_cmd.');" >';



		$help_link .= '<img style=\'cursor:pointer;border:0px;\' src=\'tpl/' . $cfg_cms['skin'] . '/img/about.gif\' alt=\'' . $cms_lang['gen_help'] .
                 '\' title=\'' . $cms_lang['gen_help'] . '\' width=\'16\' height=\'16\' />';
        $help_link .= "</a>";

    } else {
        $help_link = '';
    }

    return  $help_link;  
}

/**
 * Funktionscache. Hier wird zB das Ergebnis des css- parsers
 * zwischengespeichert, damit die Datei nur einmal geparst werden muß.
 * Beschleunigt die mip_forms beim zweiten Aufruf um mindestens 100%
 *
*/

// @args key, value
function mip_forms_check_cache($cache_var, $cache_key = '')
{
	global $mip_forms_cache_container;

	if(! empty($mip_forms_cache_container["$cache_var"]) || is_array($mip_forms_cache_container["$cache_var"]) && $cache_key != ''){
		return true;
	}
	else{
		return false;
	}

}

// @args key, value
function mip_forms_get_cache($cache_var, $cache_key = '')
{
	global $mip_forms_cache_container;

    if ($cache_key == '') return $mip_forms_cache_container["$cache_var"];
    else return $mip_forms_cache_container["$cache_var"]["$cache_key"];

}

// @args key, value
function mip_forms_write_cache($cache_var, $cache_val = '')
{
	global $mip_forms_cache_container;

	$mip_forms_cache_container["$cache_var"] = $cache_val;
}

// @args array, key, value
function mip_forms_push_cache($cache_var, $cache_key, $cache_val = '')
{
	global $mip_forms_cache_container;
	$cache_key = str_replace('[]','[0]',$cache_key);
	$mip_forms_cache_container["$cache_var"]["$cache_key"] = $cache_val;
}

/*
 * Abstrakte MIP- Forms
 *
*/

/**
 * Erzeugt
 * [tab] [var ueberschrift] [var inhalt]
 *
 * @Access private
 * @Args array in['desc'] (Textbeschreibung)
 *               ['tab']  (Einrückung)
 *       str input (Spezifische Formulardaten)
 * @Return str form (fertiges Formular mit allen Formatierungen)
 */
function mip_forms_abstrakt($in, $input)
{
	global $mip_forms_tab;

	$spaces= '';
	for($i = 0; $i < $in['tab']; $i++)
	{
		$spaces .= sprintf($mip_forms_tab['tab'], '&nbsp;');
	}

	$left_width = $mip_forms_tab['left_width'] - ($in['tab'] * 20);
	$cont_left  = sprintf($mip_forms_tab['left'], $left_width, $in['desc']);
	$cont_left = $spaces .$cont_left;

    $help = mip_forms_abstrakt_help($in['help'],$in['help_width'],$in['help_height']);
	$cont_right = sprintf($mip_forms_tab['right'], $input, $help);

	$to_return = $mip_forms_tab['open'] . "\n" .
					 $cont_left . "\n" .
					 $cont_right . "\n" .
					 $mip_forms_tab['close']. "\n\n";

	return $to_return;
}

/**
 * Erzeugt
 * [tab] [checkbox] [var ueberschrift] [var inhalt]
 *
 * @Access private
 * @Args array in['desc'] (Textbeschreibung)
 *               ['chk_var']  (Checkboxvariable)
 *               ['chk_val']  (Checkboxwert - "1" für angeklickt)
 *               ['tab']  (Einrückung)
 *       str input (Spezifische Formulardaten)
 * @Return str form (fertiges Formular mit allen Formatierungen)
 */
function mip_forms_abstrakt_chk($in, $input)
{
	global $mip_forms_tab;

	$in['chk_val'] = ($in['chk_val'] == '1') ? 'checked': '';
	if($in['chk_val'] =='checked'){
		$check_var = '1';
	}
	else{
		$check_var = '0';
	}

	$spaces= '';
	for($i = 1; $i <= $in['tab']; $i++)
	{
		$spaces .= sprintf($mip_forms_tab['tab'], '&nbsp;');
	}
    $spaces .= sprintf($mip_forms_tab['tab'], '<input type="checkbox" name="'. $in['chk_var'] .'" value="1"'. $in['chk_val'] .' /> ');
    if ($check_var == '1') mip_forms_push_cache('to_array', $in['chk_var'], '1');
    else  mip_forms_push_cache('to_array', $in['chk_var'], '');
	$left_width = $mip_forms_tab['left_width'] - (($in['tab']+1) * 20);
	$cont_left  = sprintf($mip_forms_tab['left'], $left_width, $in['desc']);
	$cont_left = $spaces .$cont_left;

    $help = mip_forms_abstrakt_help($in['help'],$in['help_width'],$in['help_height']);
	$cont_right = sprintf($mip_forms_tab['right'], $input, $help);

	$to_return = $mip_forms_tab['open'] . "\n" .
					 $cont_left . "\n" .
					 $cont_right . "\n" .
					 $mip_forms_tab['close']. "\n\n" ;

	return $to_return;
}

/**
 * Erzeugt das finale HTML- Form Radio - Beschreibung - Wert.
 * Der spezifische Inhalt wird mit den Argumenten übergeben.
 *
 * [tab] [checkbox] [var ueberschrift] [var inhalt]
 *
 * @Access private
 * @Args array in['desc'] (Textbeschreibung)
 *               ['radio_var']
 *               ['radio_val']  (Radiovariable)
 *               ['radio_user_val']  (Radiouservariable, wenn gleich
 *                                  radio_val ist der Radiobutton aktiviert)
 *               ['tab']  (Einrückung)
 *       str input (Spezifische Formulardaten)
 * @Return str form (fertiges Formular mit allen Formatierungen)
 */
function mip_forms_abstrakt_radio($in, $input)
{
	global $mip_forms_tab;

	$in['radio_checked'] = ($in['radio_val'] == $in['radio_user_val']) ? 'checked': '';
    if($in['radio_checked'] =='checked'){
		$check_var = '1';
	}
	else{
		$check_var = '0';
	}
	$spaces= '';
	for($i = 1; $i <= $in['tab']; $i++)
	{
		$spaces .= sprintf($mip_forms_tab['tab'], '&nbsp;');
	}
	$spaces .= sprintf($mip_forms_tab['tab'], '<input type="radio" class="radio" name="'. $in['radio_var'] .'" value="'. $in['radio_user_val'] .'"'. $in['radio_checked'] .' /> ');
    if ($check_var == 1) mip_forms_push_cache('to_array', $in['radio_var'], $in['radio_user_val']);
    $left_width = $mip_forms_tab['left_width'] - (($in['tab']+1) * 20);
	$cont_left  = sprintf($mip_forms_tab['left'], $left_width, $in['desc']);
	$cont_left = $spaces .$cont_left;

    $help = mip_forms_abstrakt_help($in['help'],$in['help_width'],$in['help_height']);
	$cont_right = sprintf($mip_forms_tab['right'], $input, $help);

	$to_return = $mip_forms_tab['open'] . "\n" .
					 $cont_left . "\n" .
					 $cont_right . "\n" .
					 $mip_forms_tab['close']. "\n\n" ;

	return $to_return;
}

/**
 * Erzeugt
 * [tab] [var ueberschrift]
 *       [var inhalt]
 *
 * @Access private
 * @Args array in['desc'] (Textbeschreibung)
 *               ['tab']  (Einrückung)
 *       str input (Spezifische Formulardaten)
 * @Return str form (fertiges Formular mit allen Formatierungen)
 */
function mip_forms_abstraktl($in, $input)
{
	global $mip_forms_tab;

	$spaces= '';
	for($i = 0; $i < $in['tab']; $i++)
	{
		$spaces .= sprintf($mip_forms_tab['tab'], '&nbsp;');
	}

    $help = mip_forms_abstrakt_help($in['help'],$in['help_width'],$in['help_height']);
	$cont_full  = sprintf($mip_forms_tab['full'], $in['desc'], $input, $help);
	$cont_full = $spaces .$cont_full;

	$to_return = $mip_forms_tab['open'] . "\n" .
					 $cont_full . "\n" .
					 $mip_forms_tab['close']. "\n\n";

	return $to_return;
}

/**
 * Erzeugt das finale HTML- Form Checkbox - Beschreibung - langer Wert.
 *
 * [tab] [checkbox] [var ueberschrift]
 *                  [var inhalt]
 *
 * @Access private
 * @Args array in['desc'] (Textbeschreibung)
 *               ['chk_var'] (Checkboxvariable)
 *               ['chk_val'] (Checkboxwert "1" für angeklickt)
 *               ['tab'] (Einrückung)
 *       str input (Spezifische Formulardaten)
 * @Return str form      (fertiges Formular mit allen Formatierungen)
 */
function mip_forms_abstrakt_chkl($in, $input)
{
	global $mip_forms_tab;

	$in['chk_val'] = ($in['chk_val'] == '1') ? 'checked': '';
    if($in['chk_val'] =='checked'){
		$check_var = '1';
	}
	else{
		$check_var = '0';
	}
	
	$spaces= '';
	for($i = 1; $i <= $in['tab']; $i++)
	{
		$spaces .= sprintf($mip_forms_tab['tab'], '&nbsp;');
	}
	$spaces .= sprintf($mip_forms_tab['tab'], '<input type="checkbox"  name="'. $in['chk_var'] .'" value="1"'. $in['chk_val'] .' /> <br />');
    if ($check_var == '1') mip_forms_push_cache('to_array', $in['chk_var'], '1');
    else  mip_forms_push_cache('to_array', $in['chk_var'], '');
    $help = mip_forms_abstrakt_help($in['help'],$in['help_width'],$in['help_height']);
	$cont_full  = sprintf($mip_forms_tab['full'], $in['desc'], $input, $help);
	$cont_full = $spaces .$cont_full;

	$to_return = $mip_forms_tab['open'] . "\n" .
					 $cont_full . "\n" .
					 $mip_forms_tab['close']. "\n\n";

	return $to_return;
}

/**
 * Erzeugt
 *
 * [tab] [Beschreibung]
 *       [Radiobutton x1] [Radiobuttonbeschreibung x1]
 *       [Radiobutton x2] [Radiobuttonbeschreibung x2]
 *       [Radiobutton x3] [Radiobuttonbeschreibung x3]
 *       ...           ...
 *
 * @Access private
 * @Args array in['desc'] (Textbeschreibung)
 *               ['radio_val'] (Radiovariable)
 *               ['radio_user_val'] (Radiouservariable, wenn gleich
 *                                  radio_val ist der Radiobutton aktiviert)
 *               ['tab'] (Einrückung)
 *       str input (Spezifische Formulardaten)
 * @Return str form      (fertiges Formular mit allen Formatierungen)
 */
function mip_forms_abstrakt_radiol($in, $input)
{
	global $mip_forms_tab;

	$in['radio_checked'] = ($in['radio_val'] == $in['radio_user_val']) ? 'checked': '';
    if($in['radio_checked'] =='checked'){
		$check_var = '1';
	}
	else{
		$check_var = '0';
	}
	$spaces= '';
	for($i = 1; $i <= $in['tab']; $i++)
	{
		$spaces .= sprintf($mip_forms_tab['tab'], '&nbsp;');
	}
    $help = mip_forms_abstrakt_help($in['help'],$in['help_width'],$in['help_height']);
	$spaces .= sprintf($mip_forms_tab['tab'], '<input type="radio"  name="'. $in['radio_var'] .'" value="'. $in['radio_user_val'] .'"'. $in['radio_checked'] .'><br>&nbsp;', $help);
    if ($check_var == '1') mip_forms_push_cache('to_array', $in['radio_var'], $in['radio_user_val']);
	$cont_full  = sprintf($mip_forms_tab['full'], $in['desc'], $input, '');// 3rd parameter? wozu? help?
	$cont_full = $spaces .$cont_full;

	$to_return = $mip_forms_tab['open'] . "\n" .
					 $cont_full . "\n" .
					 $mip_forms_tab['close']. "\n\n";

	return $to_return;
}


/**
 * Erzeugt <select> [ein (mehrere) Options-Feld(er)] </select>.
 * Wird $in['option_desc'] nicht angegeben, wird
 * $in['option_val'] als Beschreibung ausgegeben.
 *
 * [Option x1] [Optionbeschreibung x1]
 * [Option x2] [Optionbeschreibung x2]
 * ...          ...
 *
 * @Access private
 * @Args array in['desc'] (Textbeschreibung)
 *               ['cms_val']  (Select-Feld Variable)
 *               ['cms_var']  (Aktueller Wert des Select- Feldes)
 *               ['option_desc'] (optional)  (Beschreibung einer Option)
 *               ['option_val']  (Wert einer Option)
 *               ['size'] (optional default ='1') (Breite des <select> - Tags)
 *               ['flag'] (optional, nur bei ['size'] > 1; Möglicher Wert: 'multiple')
 * @Return str selectbox (fertige Selectbox mit allen Formatierungen)
 */
function mip_forms_abstrakt_multi_option($in)
{
	$size = ($in['size'] != '') ? $in['size'] : '1';
	$flag = ($size > 1 && trim($in['flag']) == 'multiple') ? 'multiple' : '';
	$flag .= (trim($in['flag']) == 'reload') ? ' onchange="document.editform.action.value=\'change\';document.editform.submit();"' : '';
	if($flag == 'multiple' && substr($in['cms_var'],-2) != '[]'){
		$in['cms_var'] .= '[]';
		$in['cms_val'] = ','.$in['cms_val'].',';
	}
	$array_count = count($in['option_val']);
	$array_for_multiple = explode(',', $in['cms_val']);
	
	//filter double values
	$array_for_multiple = array_unique($array_for_multiple);

	for($i = 0; $i < $array_count; $i++)
	{
		if( $in['option_val'][$i] == htmlentities($in['cms_val'], ENT_COMPAT, 'UTF-8')
			|| ($flag == "multiple" && in_array($in['option_val'][$i], $array_for_multiple) ) ){
			if(strlen(trim($in['option_desc'][$i])) > 0){
				$options .= '<option selected value="'. $in['option_val'][$i] .'" >'. $in['option_desc'][$i] .'</option>'. "\n";
			}
			else{
				$options .= '<option selected>'. $in['option_val'][$i] .'</option>'. "\n";
			}
			mip_forms_push_cache('to_array', $in['cms_var'], $in['option_val'][$i]);
		}
		else{
			if(strlen(trim($in['option_desc'][$i])) > 0){
				$options .= '<option value="'. $in['option_val'][$i] .'">'. $in['option_desc'][$i] .'</option>'. "\n";
			}
			else{
				$options .= '<option>'. $in['option_val'][$i] .'</option>'. "\n";
			}
		}
        if ($in['cms_val'] == '' && $i == 0) mip_forms_push_cache('to_array', $in['cms_var'], $in['option_val'][$i]);
	}

	$to_return = '<select name="'. $in['cms_var'] .'" size="'. $size .'" ' . $flag . '>' . "\n" . $options . ' </select>';

	return $to_return;

}


/**
 * Erzeugt eine Auswahlliste mit Radiobuttons.
 * Wird $in['option_desc'] nicht angegeben, wird
 * $in['option_val'] als Beschreibung ausgegeben.
 *
 * [Radiobutton x1] [Radiobuttonbeschreibung x1]
 * [Radiobutton x2] [Radiobuttonbeschreibung x2]
 * ...          ...
 *
 * @Access Private
 * @Args array in['desc'] (Textbeschreibung)
 *               ['cms_val']  (Select-Feld Variable)
 *               ['cms_var']  (Aktueller Wert des Select- Feldes)
 *               ['option_desc'] (optional)  (Beschreibung einer Option)
 *               ['option_val']  (Wert einer Option)
 * @Return str selectbox (fertige Selectbox mit allen Formatierungen)
 */
function mip_forms_abstrakt_multi_radio($in)
{
	$array_count = count($in['option_val']);

	for($i = 0; $i < $array_count; $i++)
	{
		if($in['option_val'][$i] == htmlentities($in['cms_val'], ENT_COMPAT, 'UTF-8')){
			if(strlen(trim($in['option_desc'][$i])) > 0){
				$options .= '<input type="radio" class="radio" checked="checked" name="' . $in['cms_var'] . '" value="'. $in['option_val'][$i] .'" /> '. $in['option_desc'][$i] .'<br />'. "\n";
            }
			else{
				$options .= '<input type="radio" class="radio" checked="checked" name="' . $in['cms_var'] . '" value="'. $in['option_val'][$i] .'" /> '. $in['option_val'][$i] .'<br />'. "\n";
			}
			mip_forms_push_cache('to_array', $in['cms_var'], $in['option_val'][$i]);
		}
		else{
			if(strlen(trim($in['option_desc'][$i])) > 0){
				$options .= '<input type="radio" class="radio" name="' . $in['cms_var'] . '" value="'. $in['option_val'][$i] .'" /> '. $in['option_desc'][$i] .'<br />'. "\n";
			}
			else{
				$options .= '<input type="radio" class="radio" name="' . $in['cms_var'] . '" value="'. $in['option_val'][$i] .'" /> '. $in['option_val'][$i] .'<br />'. "\n";
			}
		}
	}

	$to_return = $options;

	return $to_return;

}


/**
 * Erzeugt mehrere checkboxen-Felder, die mit <br /> von einander getrennt
 * werden.
 *
 * [Checkbox x1] [Checkboxbeschreibung x1]
 * [Checkbox x2] [Checkboxbeschreibung x2]
 * ...          ...
 *
 * @Access Private
 * @Args array in['option_desc'][x] (Beschreibung einer Checkbox)
 *               ['option_var'][x]  (cms_var[x] einer Checkbox)
 *               ['option_val'][x]  (MOD_VALUE[x] einer Checkbox)
 *               ['option_val_select'][x]  (MOD_VALUE[x] einer Checkbox)
 * @Return str mutiple checkbox (fertige Selectboxen)
 */
function mip_forms_abstrakt_multi_chk($in)
{
	$array_count = count($in['option_val']);

	for($i = 0; $i < $array_count; $i++)
	{

		if($in['option_val'][$i] == $in['option_val_select'][$i]){
			 $options .= '<input type ="checkbox" checked="checked" name ="'. $in['option_var'][$i] .'" value="'. $in['option_val_select'][$i] .'" /> '. $in['option_desc'][$i] ."<br />\n";
             mip_forms_push_cache('to_array', $in['option_var'][$i], $in['option_val_select'][$i]);
        }
		else{
			 $options .= '<input type ="checkbox" name ="'. $in['option_var'][$i] .'" value="'. $in['option_val_select'][$i] .'" /> '. $in['option_desc'][$i] .'<br />'. "\n";
        }
	}

	$to_return = $options;

	return $to_return;

}

/**
* Beschreibungsfelder
*
* Erzeugt ein Beschreibungstextfeld.
* Das zu erzeugende Textfeld kann mit $in['type']
* beinflusst werden.
*
* $in['type'] = '' (default)
* [Beschreibung]
*
* $in['type'] = 'chk'
* [Checkbox] [Beschreibung]
*
* $in['type'] = 'radio'
* [Radiobutton] [Beschreibung]
*
* @Access Private
* @Args array 	in['type'] (Textfeldtyp)
*                ['desc'] (Textbeschreibung)
*                ['chk_var'] (Checkboxvariable)
*                            Nur $in['type'] = chk, chk_long
*                ['chk_val'] (Checkboxwert  "1" für angeklickt)
*                            Nur $in['type'] = chk, chk_long
*                ['radio_var'] (Radiovariable)
*                              Nur $in['type'] = radio, radio_long
*                ['radio_val'] (Wert der Radiovariable)
*                               Nur $in['type'] = radio, radio_long
*                ['radio_user_val'] (Radiouservariable, wenn gleich ['radio_val']
*                                   ist der Radiobutton aktiviert)
*                                   Nur $in['type'] = radio, radio_long
*					  ['tab']  (Einrückung)
* @Return string HTML-Tabelle mit Werten
*/
function mip_forms_desc($in)
{
    global $mip_forms_to_array;
    
    $input = '';

	switch($in['type'])
	{
		case '':
			$to_return = mip_forms_abstraktl($in, $input);
			break;
		case 'chk':
			$to_return = mip_forms_abstrakt_chkl($in, $input);
			break;
		case 'radio':
			$to_return = mip_forms_abstrakt_radiol($in, $input);
			break;
		default:
			if ($mip_forms_to_array === true) return false;
            else echo 'Fehler in mip_forms_desc()! $in[\'type\'] = '. $in['type'] . ' existiert nicht bei Beschreibungen!';
	}

	//$to_return = str_replace('<br />', '', $to_return);

	return $to_return;
}


/**
* TEXTFELDER
*
* Erzeugt ein Formulartextfeld.
* Das zu erzeugende Formularfeld kann mit $in['type']
* beinflusst werden.
*
* $in['type'] = '' (default)
* [Beschreibung] [Textbox]
*
* $in['type'] = 'chk'
* [Checkbox] [Beschreibung] [Textbox]
*
* $in['type'] = 'radio'
* [Radiobutton] [Beschreibung] [Textbox]
*
* $in['type'] = 'long'
* [Beschreibung]
* [Textbox]
*
* $in['type'] = 'chk_long'
* [Checkbox] [Beschreibung]
*            [Textbox]
*
* $in['type'] = 'radio_long'
* [Radiobutton] [Beschreibung]
*               [Textbox]
*
* @Access Private
* @Args array 	in['type'] (Textfeldtyp)
*                ['desc'] (Textbeschreibung)
*                ['cms_var'] (Textfeldvariable)
*                ['cms_val'] (Textfeldwert)
*                ['chk_var'] (Checkboxvariable)
*                            Nur $in['type'] = chk, chk_long
*                ['chk_val'] (Checkboxwert  "1" für angeklickt)
*                            Nur $in['type'] = chk, chk_long
*                ['radio_var'] (Radiovariable)
*                              Nur $in['type'] = radio, radio_long
*                ['radio_val'] (Wert der Radiovariable)
*                               Nur $in['type'] = radio, radio_long
*                ['radio_user_val'] (Radiouservariable, wenn gleich ['radio_val']
*                                   ist der Radiobutton aktiviert)
*                                   Nur $in['type'] = radio, radio_long
*					  ['tab']  (Einrückung)
* @Return string HTML-Tabelle mit Werten
*/
function mip_forms_txt($in)
{
    global $mip_forms_to_array;
    
    switch($in['type'])
	{
		case 'long':
		case 'chk_long':
		case 'radio_long':
			$input = '<input type ="text" name="'. $in['cms_var'] .'" value="'. htmlentities($in['cms_val'], ENT_COMPAT, 'UTF-8') .'" size="40" style="width:100%;min-width:100%"; /><br /> ';
			mip_forms_push_cache('to_array', $in['cms_var'], htmlentities($in['cms_val'], ENT_COMPAT, 'UTF-8'));
			break;
		default:
			$input = '<input type ="text" name="'. $in['cms_var'] .'" value="'. htmlentities($in['cms_val'], ENT_COMPAT, 'UTF-8') .'" size="40" class="default" /><br /> ';
			mip_forms_push_cache('to_array', $in['cms_var'], htmlentities($in['cms_val'], ENT_COMPAT, 'UTF-8'));
	}

	switch($in['type'])
	{
		case '':
			$to_return = mip_forms_abstrakt($in, $input);
			break;
		case 'chk':
			$to_return = mip_forms_abstrakt_chk($in, $input);
			break;
		case 'radio':
			$to_return = mip_forms_abstrakt_radio($in, $input);
			break;
		case 'long':
			$to_return = mip_forms_abstraktl($in, $input);
			break;
		case 'chk_long':
			$to_return = mip_forms_abstrakt_chkl($in, $input);
			break;
		case 'radio_long':
			$to_return = mip_forms_abstrakt_radiol($in, $input);
			break;
		default:
			if ($mip_forms_to_array === true) return false;
            else echo 'Fehler in mip_forms_txt()! $in[\'type\'] = '. $in['type'] . ' existiert nicht!';
	}

	return $to_return;
}

function mip_forms_txtp($in)
{
	echo mip_forms_txt($in);
}


/*
* OPTIONFELDER
*
* Erzeugt ein oder mehrere Formularoptionfeld(er).
* Das zu erzeugende Formularfeld kann mit $in['type']
* beinflusst werden.
*
* $in['type'] = '' (default)
* [Beschreibung] [Option x1] [Optionbeschreibung x1]
*                [Option x2] [Optionbeschreibung x2]
*                ...           ...
*
* $in['type'] = 'chk'
* [Option] [Beschreibung] [Option x1] [Optionbeschreibung x1]
*                           [Option x2] [Optionbeschreibung x2]
*                           ...           ...
* $in['type'] = 'radio'
* [Radiobutton] [Beschreibung] [Option x1] [Optionbeschreibung x1]
*                              [Option x2] [Optionbeschreibung x2]
*                              ...           ...
*
* $in['type'] = 'long'
* [Beschreibung]
* [Option x1] [Optionbeschreibung x1]
* [Option x2] [Optionbeschreibung x2]
* ...           ...
*
* $in['type'] = 'chk_long'
* [Option] [Beschreibung]
*            [Option x1] [Optionbeschreibung x1]
*            [Option x2] [Optionbeschreibung x2]
*            ...           ...
*
* $in['type'] = 'radio_long'
* [Radiobutton] [Beschreibung]
*               [Option x1] [Optionbeschreibung x1]
*               [Option x2] [Optionbeschreibung x2]
*               ...           ...
*
* @Access public
* @Args array 	in['type'] (Optionfeldtyp)
*                ['desc'] (Textbeschreibung)
*                ['cms_var'] (Selectfeldvariable)
*                ['cms_val'] (Selectfeldwert)
*                ['chk_var'] (Checkboxvariable)
*                            Nur $in['type'] = chk, chk_long
*                ['chk_val'] (Checkboxwert  "1" für angeklickt)
*                            Nur $in['type'] = chk, chk_long
*                ['radio_var'] (Radiovariable)
*                              Nur $in['type'] = radio, radio_long
*                ['radio_val'] (Wert der Radiovariable)
*                               Nur $in['type'] = radio, radio_long
*                ['radio_user_val'] (Radiouservariable, wenn gleich ['radio_val']
*                                   ist der Radiobutton aktiviert)
*                                   Nur $in['type'] = radio, radio_long
*                ['option_desc'][x] (Beschreibung einer Option)
*                ['option_val'][x] (MOD_VALUE[x] einer Option)
*                ['tab']  (Einrückung)
* @Return string HTML-Tabelle mit Werten
*/
function mip_forms_option($in)
{
    global $mip_forms_to_array;
    
    $input = mip_forms_abstrakt_multi_option($in);

	switch($in['type'])
	{
		case '':
			$to_return = mip_forms_abstrakt($in, $input);
			break;
		case 'chk':
			$to_return = mip_forms_abstrakt_chk($in, $input);
			break;
		case 'radio':
			$to_return = mip_forms_abstrakt_radio($in, $input);
			break;
		case 'long':
			$to_return = mip_forms_abstraktl($in, $input);
			break;
		case 'chk_long':
			$to_return = mip_forms_abstrakt_chkl($in, $input);
			break;
		case 'radio_long':
			$to_return = mip_forms_abstrakt_radiol($in, $input);
			break;
		case 'input':
			$to_return = $input;
			break;
		default:
			if ($mip_forms_to_array === true) return false;
            else echo 'mip_forms - Fehler! $in[\'type\'] = '. $in['type'] . ' existiert nicht!';
	}

	return $to_return;
}

function mip_forms_optionp($in)
{
	echo mip_forms_option($in);
}


/**
* CHECKBOXEN
*
* Erzeugt ein oder mehrere Formularfeld(er).
* Das zu erzeugende Formularfeld kann mit $in['type']
* beinflusst werden.
*
* $in['type'] = '' (default)
* [Beschreibung] [Checkbox x1] [Checkboxbeschreibung x1]
*                [Checkbox x2] [Checkboxbeschreibung x2]
*                ...           ...
*
* $in['type'] = 'chk'
* [Checkbox] [Beschreibung] [Checkbox x1] [Checkboxbeschreibung x1]
*                           [Checkbox x2] [Checkboxbeschreibung x2]
*                           ...           ...
* $in['type'] = 'radio'
* [Radiobutton] [Beschreibung] [Checkbox x1] [Checkboxbeschreibung x1]
*                              [Checkbox x2] [Checkboxbeschreibung x2]
*                              ...           ...
*
* $in['type'] = 'long'
* [Beschreibung]
* [Checkbox x1] [Checkboxbeschreibung x1]
* [Checkbox x2] [Checkboxbeschreibung x2]
* ...           ...
*
* $in['type'] = 'chk_long'
* [Checkbox] [Beschreibung]
*            [Checkbox x1] [Checkboxbeschreibung x1]
*            [Checkbox x2] [Checkboxbeschreibung x2]
*            ...           ...
*
* $in['type'] = 'radio_long'
* [Radiobutton] [Beschreibung]
*               [Checkbox x1] [Checkboxbeschreibung x1]
*               [Checkbox x2] [Checkboxbeschreibung x2]
*               ...           ...
*
* @Access Private
* @Args array 	in['type'] (Checkboxfeldtyp)
*                ['desc'] (Textbeschreibung)
*                ['chk_var'] (Checkboxvariable)
*                            Nur $in['type'] = chk, chk_long
*                ['chk_val'] (Checkboxwert  "1" für angeklickt)
*                            Nur $in['type'] = chk, chk_long
*                ['radio_var'] (Radiovariable)
*                              Nur $in['type'] = radio, radio_long
*                ['radio_val'] (Wert der Radiovariable)
*                               Nur $in['type'] = radio, radio_long
*                ['radio_user_val'] (Radiouservariable, wenn gleich ['radio_val']
*                                   ist der Radiobutton aktiviert)
*                                   Nur $in['type'] = radio, radio_long
*                ['option_desc'][x] (Beschreibung einer Checkbox)
*                ['option_var'][x] (cms_var[x] einer Checkbox)
*                ['option_val'][x] (MOD_VALUE[x] einer Checkbox)
*                ['option_val_select'][x] (Wert einer Checkbox)
*                ['tab']  (Einrückung)
* @Return string HTML-Tabelle mit Formelement(en)
*/
function mip_forms_chk($in)
{
    global $mip_forms_to_array;
    
    $input = mip_forms_abstrakt_multi_chk($in);

	switch($in['type'])
	{
		case '':
			$to_return = mip_forms_abstrakt($in, $input);
			break;
		case 'chk':
			$to_return = mip_forms_abstrakt_chk($in, $input);
			break;
		case 'radio':
			$to_return = mip_forms_abstrakt_radio($in, $input);
			break;
		case 'long':
			$to_return = mip_forms_abstraktl($in, $input);
			break;
		case 'chk_long':
			$to_return = mip_forms_abstrakt_chkl($in, $input);
			break;
		case 'radio_long':
			$to_return = mip_forms_abstrakt_radiol($in, $input);
			break;
		default:
			if ($mip_forms_to_array === true) return false;
            else echo 'mip_forms - Fehler! $in[\'type\'] = '. $in['type'] . ' existiert nicht!';
	}

	return $to_return;
}



/**
* RADIOS
*
*
* $in['type'] = '' (default)
* [Beschreibung] [Radiobutton x1] [Radiobuttonbeschreibung x1]
*                [Radiobutton x2] [Radiobuttonbeschreibung x2]
*                ...           ...
*
* $in['type'] = 'chk'
* [Radiobutton] [Beschreibung] [Radiobutton x1] [Radiobuttonbeschreibung x1]
*                           [Radiobutton x2] [Radiobuttonbeschreibung x2]
*                           ...           ...
* $in['type'] = 'radio'
* [Radiobutton] [Beschreibung] [Radiobutton x1] [Radiobuttonbeschreibung x1]
*                              [Radiobutton x2] [Radiobuttonbeschreibung x2]
*                              ...           ...
*
* $in['type'] = 'long'
* [Beschreibung]
* [Radiobutton x1] [Radiobuttonbeschreibung x1]
* [Radiobutton x2] [Radiobuttonbeschreibung x2]
* ...           ...
*
* $in['type'] = 'chk_long'
* [Radiobutton] [Beschreibung]
*            [Radiobutton x1] [Radiobuttonbeschreibung x1]
*            [Radiobutton x2] [Radiobuttonbeschreibung x2]
*            ...           ...
*
* $in['type'] = 'radio_long'
* [Radiobutton] [Beschreibung]
*               [Radiobutton x1] [Radiobuttonbeschreibung x1]
*               [Radiobutton x2] [Radiobuttonbeschreibung x2]
*               ...           ...
*
* @Access public
* @Args array 	in['type'] (Feldtyp)
*                ['desc'] (Textbeschreibung)
*                ['cms_var'] (Selectfeldvariable)
*                ['cms_val'] (Selectfeldwert)
*                ['chk_var'] (Checkboxvariable)
*                            Nur $in['type'] = chk, chk_long
*                ['chk_val'] (Checkboxwert  "1" für angeklickt)
*                            Nur $in['type'] = chk, chk_long
*                ['radio_var'] (Radiovariable)
*                              Nur $in['type'] = radio, radio_long
*                ['radio_val'] (Wert der Radiovariable)
*                               Nur $in['type'] = radio, radio_long
*                ['radio_user_val'] (Radiouservariable, wenn gleich ['radio_val']
*                                   ist der Radiobutton aktiviert)
*                                   Nur $in['type'] = radio, radio_long
*                ['option_desc'][x] (Beschreibung des Radios)
*                ['option_val'][x] (Wert eines Radios - NICHT MOD_VAR[x]!!!
*                ['tab']  (Einrückung)
* @Return string form in tabelle
*/
function mip_forms_radio($in)
{
    global $mip_forms_to_array;
    
    $input = mip_forms_abstrakt_multi_radio($in);

	switch($in['type'])
	{
		case '':
			$to_return = mip_forms_abstrakt($in, $input);
			break;
		case 'chk':
			$to_return = mip_forms_abstrakt_chk($in, $input);
			break;
		case 'radio':
			$to_return = mip_forms_abstrakt_radio($in, $input);
			break;
		case 'long':
			$to_return = mip_forms_abstraktl($in, $input);
			break;
		case 'chk_long':
			$to_return = mip_forms_abstrakt_chkl($in, $input);
			break;
		case 'radio_long':
			$to_return = mip_forms_abstrakt_radiol($in, $input);
			break;
		default:
			if ($mip_forms_to_array === true) return false;
            else echo 'mip_forms - Fehler! $in[\'type\'] = '. $in['type'] . ' existiert nicht!';
	}

	return $to_return;
}



/**
* TEXTAREAS
*
* Erzeugt eine Textarea.
* Wird kein Wert $in['rows'] angegeben, ist der
* defaultwert = '5'
* Das zu erzeugende Formularfeld kann mit $in['type']
* beinflusst werden.
*
* $in['type'] = '' (default)
* [Beschreibung] [Textarea]
*
* $in['type'] = 'chk'
* [Checkbox] [Beschreibung] [Textarea]
*
* $in['type'] = 'radio'
* [Radiobutton] [Beschreibung] [Textarea]
*
* $in['type'] = 'long'
* [Beschreibung]
* [Textarea]
*
* $in['type'] = 'chk_long'
* [Checkbox] [Beschreibung]
*            [Textarea]
*
* $in['type'] = 'radio_long'
* [Radiobutton] [Beschreibung]
*               [Textarea]
*
* @Access Private
* @Args array 	in['type'] (Textareatyp)
*                ['desc'] (Textareabeschreibung)
*                ['cms_var'] (MOD_VAR[x])
*                ['cms_val'] (MOD_VALUE[x])
*                ['rows'] (optional, default = '5')(Textareahoehe in Textzeilen)
*                ['chk_var'] (Checkboxvariable)
*                            Nur $in['type'] = chk, chk_long
*                ['chk_val'] (Checkboxwert  "1" für angeklickt)
*                            Nur $in['type'] = chk, chk_long
*                ['radio_var'] (Radiovariable)
*                              Nur $in['type'] = radio, radio_long
*                ['radio_val'] (Wert der Radiovariable)
*                               Nur $in['type'] = radio, radio_long
*                ['radio_user_val'] (Radiouservariable, wenn gleich ['radio_val']
*                                   ist der Radiobutton aktiviert)
*                                   Nur $in['type'] = radio, radio_long
*					  ['tab']  (Einrückung)
* @Return string HTML-textarea form in tabelle
*
*/
function mip_forms_txtarea($in)
{
    global $mip_forms_to_array;
    
    $rows = ($in['rows'] != '') ? $in['rows']: '5';

	$input = '<textarea name="'. $in['cms_var'] .'" cols="30" wrap="off" rows="'. $rows .'" class="template">'. htmlentities($in['cms_val'], ENT_COMPAT, 'UTF-8') .'</textarea>';
    mip_forms_push_cache('to_array', $in['cms_var'], htmlentities($in['cms_val'], ENT_COMPAT, 'UTF-8'));
	switch($in['type'])
	{
		case '':
			$to_return = mip_forms_abstrakt($in, $input);
			break;
		case 'chk':
			$to_return = mip_forms_abstrakt_chk($in, $input);
			break;
		case 'radio':
			$to_return = mip_forms_abstrakt_radio($in, $input);
			break;
		case 'long':
			$to_return = mip_forms_abstraktl($in, $input);
			break;
		case 'chk_long':
			$to_return = mip_forms_abstrakt_chkl($in, $input);
			break;
		case 'radio_long':
			$to_return = mip_forms_abstrakt_radiol($in, $input);
			break;
		default:
			if ($mip_forms_to_array === true) return false;
            else echo 'Fehler in mip_forms() [txtarea]! $in[\'type\'] = '. $in['type'] . ' existiert nicht!';
	}

	return $to_return;
}


/**
* HIDDEN
*
* Gibt aus
* [hiddenformularfeld]
*
* @Access private
* @Args array 	[i_var] (MOD_VAR[X])
*              [i_val] (Benutzerwert für MOD_VAR[X])
* @Return Formularhiddenfeld
*/
function mip_forms_hidden($in)
{
	$input = '<input type ="hidden" name="'. $in['cms_var'] .'" value="'. htmlentities($in['cms_val'], ENT_COMPAT, 'UTF-8') .'" /> '."\n";
    mip_forms_push_cache('to_array', $in['cms_var'], htmlentities($in['cms_val'], ENT_COMPAT, 'UTF-8'));
	return $input;
}

?>
