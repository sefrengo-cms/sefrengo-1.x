<?PHP
// File: $Id: fnc.mipforms_extensions.php 28 2008-05-11 19:18:49Z mistral $
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

/** OLD FILE PARSER DEPRECATED
* Parst eine frei zu definierende CSS- Datei.
*
* $mip_form['output_cat'] gibt die Ordner an, wie das Formularfeld
* später beschaffen sein soll. 'option' oder 'radio' werden unterstützt.
* Empfehlung: 'option'
*
* Mit $mip_form['type'] kann bestimmt werden, wie das CSS- File
* ausgegeben werden soll.
* Mögliche Optionen sind '', 'chk', 'radio', 'long', 'chk_long', 'radio_long'
* (siehe auch "Optionsfelder", "Radiobuttons").
*
* Mit $mip_form['file'] kann optional eine zu parsende CSS- Datei angegeben
* werden. Wird dies nicht gemacht, wird automatisch die Standard- CSS- Datei
* aus dem Frontend geparst ("css/styles.css")
*
* $mip_form['flag'] ist ein optionaler Parameter. 'id_only' parst nur
* css-Id's, 'class_only' parst nur css-Klassen. Es wird dringend
* empfohlen, diesen Parameter zu setzen, da hinterher keine Rückschlüsse
* mehr auf die Herkunft gezogen werden können (z.B. wird die Klasse
* ".class_or_id" später zu der Variablen 'class_or_id', ebenso wird
* '#class_or_id' zur Variablen 'class_or_id'.
*
*
* @Access private
* @Args array         in['cms_var'] (MOD_VAR)
*                ['cms_val'] (MOD_VALUE)
*                                          ['desc'] (Beschreibung)
*                ['output_cat'] ('option' oder 'radio')
*                ['type'] ('', 'chk', 'radio', 'long' , 'chk_long', 'radio_long')
*                ['file'] (optional)
*                ['flag'] (optional)
*                                          ['tab']  (Einrückung)
* @Return css- Werte als form
*/
/*
function mip_forms_app_css($in) {
        global $cfg_client;

        $default_css = $cfg_client['cssfile'];

        $css_file = (trim($in['file']) != '') ? $in['file'] : $default_css;

        //Check Cache and give output if true
        $cache_string = md5($css_file . $in['flag'] . $in['type'] . $in['output_cat']);
        $cache_container_desc = $cache_string . 'desc';
        $cache_container_val = $cache_string . 'val';

        if(mip_forms_check_cache($cache_container_val)){
                $in['option_desc'] = mip_forms_get_cache($cache_container_desc);
                $in['option_val']  = mip_forms_get_cache($cache_container_val);

        }
        elseif(file_exists($css_file)){

                $css_array = file($css_file);

                $css_count = count($css_array);
                $id_count = 0;
                $class_count = 0;
                for($i =0; $i < $css_count; $i++)
                {
                        $element = ltrim($css_array[$i]);

                        if(substr($element, 0, 1) == '#'){
                                $to_replace = strstr($element, '{');
                                if($to_replace != false){
                                        $by_id[$id_count] = substr(trim(str_replace($to_replace, '', $element)), 1);
                                        $id_count++;
                                }
                                else{
                                        $splitted = explode(' ' , $element);
                                        $by_id[$id_count] = substr(trim($splitted[0]), 1);
                                        $id_count++;
                                        unset($splitted);
                                }
                                if(strstr($by_id[($id_count-1)], ':') ){
                                        $id_count--;
                                }
                        }

                        else if(substr($element, 0, 1) == '.'){
                                $to_replace = strstr($element, '{');
                                if($to_replace != false){
                                        $by_class[$class_count] = substr(trim(str_replace($to_replace, '', $element)), 1);
                                        $class_count++;
                                }
                                else{
                                        $splitted = explode(' ' , $element);
                                        $by_class[$id_count] = substr(trim($splitted[0]), 1);
                                        $class_count++;
                                        unset($splitted);
                                }
                                if(strstr($by_class[($class_count-1)], ':') ){
                                        $class_count--;
                                }

                        }

                        $element = '';
                }

                $i = 0;
                $in['option_desc'][$i] = '--kein--';
                $in['option_val'][$i]  = '';
                $i++;

                if(is_array($by_id)){

                        natsort($by_id);
                        reset($by_id);

                        if($id_count > 0 && $in['flag'] != 'class_only'){

                                while (list ($key, $val) = each ($by_id))
                                {
                                        $in['option_desc'][$i] =  $val;
                                        $in['option_val'][$i]  = $val;
                                        $val ;
                                        $i++;
                                }
                        }


                }

                if(is_array($by_class)){

                        natsort($by_class);
                        reset($by_class);

                        if($class_count > 0 && $in['flag'] != 'id_only'){

                                while (list ($key, $val) = each ($by_class))
                                {
                                        $in['option_desc'][$i] = $val;
                                        $in['option_val'][$i]  = $val;
                                        $val ;
                                        $i++;
                                }
                        }

                }

                //write cache
                mip_forms_write_cache($cache_container_desc, $in['option_desc']);
                mip_forms_write_cache($cache_container_val, $in['option_val']);

        }
        else{
                $to_return = 'Fehler! Die angegebene Datei "'. $css_file .'" existiert nicht! ';
        }

        if(isset($in['option_val']['0'])){
                                $in['cat'] = $in['output_cat'];
                                $to_return = mip_forms($in);
        }

        return $to_return;
}

*/
?>