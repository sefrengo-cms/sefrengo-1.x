<?PHP
/*
 +----------------------------------------------------------------------+
 | GOOSEBERRY SOURCEPAD --> Advanced Sourcecode - Editor                |
 +----------------------------------------------------------------------+
 | Copyright (c) 2002 Björn Brockmann. All rights reserved.             |
 +----------------------------------------------------------------------+
 | This source file is subject to the QPL Software License, Version 1.0,|
 | that is bundled with this package in the file QPL.txt. If you did    |
 | not receive a copy of this file, you may send an email to            |
 | license@project-gooseberry.de, so I can mail you a copy.             |
 | In short this license allows for free use of GOOSEBERRY SOURCEPAD    |
 | for all free open-source projects. Please note that QPL 1.0 does not |
 | allow you to use GOOSEBERRY SOURCEPAD in a closed source commercial  |
 | product/project. If you would like to use GOOSEBERRY SOURCEPAD in a  |
 | commercial context please contact me for further details.            |
 +----------------------------------------------------------------------+
 | Author: Björn Brockmann < bjoern@project-gooseberry.de >             |
 | -------------------------------------------------------------------- |
 | Homepage: http://www.project-gooseberry.de                           |
 +----------------------------------------------------------------------+
*/

$gb_conf['extension'] = 'php';

$gb_conf['root_path'] = str_replace ('\\', '/', dirname(__FILE__) . '/');


class gb_source_pad
{
	var $conf;

	function gb_source_pad($form_name, $editor_name = 'default_editor')
	{
		global $gb_conf;

		switch($form_name)
		{
			case '*handle*':
				$this -> include_templates();
				break;
			default:
				$this -> include_defaults();
				$this -> include_templates();
				$this -> init_pad();
				$this -> conf['form_name'] = $form_name;
				$this -> conf['textfield_name'] = $editor_name;

		}

	}


	function include_templates()
	{
		global $gb_conf;

		if(!class_exists(totemplate)){
			require_once $gb_conf['root_path'] . 'include/class.template.' . $gb_conf['extension'];
			require_once $gb_conf['root_path'] . 'include/class.template_maker.' . $gb_conf['extension'];
		}
	}


	function include_defaults()
	{
		global $gb_conf, $gb_unique_nr;

		if(empty($gb_uniqe_nr)){
			require_once $gb_conf['root_path'] . 'include/defaults.' . $gb_conf['extension'];
		}
	}


	function include_lang($lang = '')
	{
		global $gb_conf, $gb_lang;

		if($lang == ''){
			$lang = $this -> conf['language'];
		}

		require_once $gb_conf['root_path'] . 'lang/' . $lang . '.' . $gb_conf['extension'];
	}


	function include_debug()
	{
		global $gb_conf;

		require_once $gb_conf['root_path'] . 'include/class.debug.' . $gb_conf['extension'];
	}


	function unique_nr()
	{
		global $gb_uniqe_nr;

		if (empty($gb_uniqe_nr)){
			$gb_uniqe_nr = 1;
		}
		else{
			$gb_uniqe_nr++;
		}

		return $gb_uniqe_nr;
	}


	function set($key, $value)
	{
		global $gb_conf, $gb_lang;

		$this -> include_lang();

		if($gb_conf[debug]){
			if(!isset($this -> conf[$key])){
				$this -> include_debug();
				gb_debug::die_nice($gb_lang['error_key_not_exist'] . $key);
			}
		}

		$this -> conf[$key] = $value;

	}


	function init_pad()
	{
		global $gb_conf;

		include $gb_conf['root_path'] . 'include/defaults.' . $gb_conf['extension'];

		$this -> unique_nr();

		$this -> conf['handle_file'] = $gb_conf['handle_file'];
		$this -> conf['handle_http_path'] = $gb_conf['handle_http_path'];

		$this -> conf['templateset'] = $gb_conf['templateset'];
		$this -> conf['language'] = $gb_conf['language'];

		$this -> conf['ext_para_str'] = $gb_conf['ext_para_str'];

		$this -> conf['unselectable'] = $gb_conf['unselectable'];

		$this -> conf['form_name'] = $gb_conf['form_name'];
		$this -> conf['textfield_name'] = $gb_conf['textfield_name'];

		$this -> conf['allow_ico_save'] = $gb_conf['allow_ico_save'];
		$this -> conf['allow_ico_set_back'] = $gb_conf['allow_ico_set_back'];
		$this -> conf['allow_ico_font'] = $gb_conf['allow_ico_font'];
		$this -> conf['allow_ico_fontsize'] = $gb_conf['allow_ico_fontsize'];
		$this -> conf['allow_ico_txtcolor'] = $gb_conf['allow_ico_txtcolor'];
		$this -> conf['allow_ico_txtcolor_ext'] = $gb_conf['allow_ico_txtcolor_ext'];
		$this -> conf['allow_ico_txtbgcolor'] = $gb_conf['allow_ico_txtbgcolor'];
		$this -> conf['allow_ico_txtbgcolor_ext'] = $gb_conf['allow_ico_txtbgcolor_ext'];
		$this -> conf['allow_ico_special_chars'] = $gb_conf['allow_ico_special_chars'];
		$this -> conf['allow_ico_color_ext'] = $gb_conf['allow_ico_color_ext'];
		$this -> conf['allow_ico_print'] = $gb_conf['allow_ico_print'];
		$this -> conf['print_nl2br'] = $gb_conf['print_nl2br'];
		$this -> conf['allow_ico_preview'] = $gb_conf['allow_ico_preview'];
		$this -> conf['preview_nl2br'] = $gb_conf['preview_nl2br'];
		$this -> conf['allow_ico_search'] = $gb_conf['allow_ico_search'];
		$this -> conf['allow_ico_search_replace'] = $gb_conf['allow_ico_search_replace'];
		$this -> conf['allow_ico_undo'] = $gb_conf['allow_ico_undo'];
		$this -> conf['allow_ico_redo'] = $gb_conf['allow_ico_redo'];
		$this -> conf['allow_ico_bold'] = $gb_conf['allow_ico_bold'];
		$this -> conf['allow_ico_italic'] = $gb_conf['allow_ico_italic'];
		$this -> conf['allow_ico_underline'] = $gb_conf['allow_ico_underline'];
		$this -> conf['allow_ico_align_left'] = $gb_conf['allow_ico_align_left'];
		$this -> conf['allow_ico_align_center'] = $gb_conf['allow_ico_align_center'];
		$this -> conf['allow_ico_align_right'] = $gb_conf['allow_ico_align_right'];
		$this -> conf['allow_ico_align_justify'] = $gb_conf['allow_ico_align_justify'];
		$this -> conf['allow_ico_close_open_tags'] = $gb_conf['allow_ico_close_open_tags'];
		$this -> conf['allow_ico_hr'] = $gb_conf['allow_ico_hr'];
		$this -> conf['allow_ico_br'] = $gb_conf['allow_ico_br'];
		$this -> conf['allow_ico_margin'] = $gb_conf['allow_ico_margin'];
		$this -> conf['allow_ico_link'] = $gb_conf['allow_ico_link'];
		$this -> conf['allow_ico_image'] = $gb_conf['allow_ico_image'];
		$this -> conf['allow_ico_list'] = $gb_conf['allow_ico_list'];
		$this -> conf['allow_ico_table'] = $gb_conf['allow_ico_table'];
		$this -> conf['allow_ico_tablerow'] = $gb_conf['allow_ico_tablerow'];
		$this -> conf['allow_ico_tabledesk'] = $gb_conf['allow_ico_tabledesk'];
		$this -> conf['undo_limit'] = $gb_conf['undo_limit'];

		$this -> conf['print_as'] = $gb_conf['print_as'];
		$this -> conf['preview_as'] = $gb_conf['preview_as'];
		$this -> conf['nl_to_br'] = $gb_conf['nl_to_br'];
		$this -> conf['editorheight'] = $gb_conf['editorheight'];
		$this -> conf['editorwidth'] = $gb_conf['editorwidth'];
		$this -> conf['editorheight_css'] = $gb_conf['editorheight_css'];
		$this -> conf['editorwidth_css'] = $gb_conf['editorwidth_css'];
		$this -> conf['wrap'] = $gb_conf['wrap'];

		$this -> conf['content'] = $gb_conf['content'];
	}


	function make_pad()
	{
		$this -> include_lang();

		return $this -> make_js_init();
	}


	function make_js_init()
	{
		global $gb_conf, $gb_lang;

		$tpl = &new totemplate;
		$tpl_maker = &new template_maker;

		$tpl = $tpl_maker -> tpl_init($tpl, $this -> conf, $gb_lang);

		return $tpl -> make($gb_conf['root_path'] . 'templates/' . $this -> conf['templateset'] . "/init.tpl");

	}

	function handle_make_js_pad($unique_nr, $tpl_set, $lang)
	{
		global $gb_lang, $gb_conf;

		$this -> include_lang($lang);

		$tpl = &new totemplate;
		$tpl_maker = &new template_maker;

		$tpl = $tpl_maker -> tpl_js_pad($tpl, $tpl_set, $unique_nr, $gb_lang);

		return $tpl -> make($gb_conf['root_path'] . 'templates/' . $tpl_set . "/js_pad.tpl");

	}

	function handle_make_js_functions($tpl_set, $lang)
	{
		global $gb_conf, $gb_lang;

		$this -> include_lang($lang);

		$tpl = &new totemplate;
		$tpl_maker = &new template_maker;

		$tpl = $tpl_maker -> tpl_make_js_functions($tpl, $gb_lang);

		return $tpl -> make($gb_conf['root_path'] . 'templates/' . $tpl_set . "/js_functions.tpl");

	}

	function handle_make_popup_color($color_opener, $unique_nr, $tpl_set, $lang)
	{
		global $gb_lang, $gb_conf;

		$this -> include_lang($lang);

		$tpl = &new totemplate;
		$tpl_maker = &new template_maker;

		$tpl = $tpl_maker -> tpl_popup_color($tpl, $color_opener, $unique_nr, $gb_lang);

		return $tpl -> make($gb_conf['root_path'] . 'templates/' . $tpl_set . "/popup_color.tpl");

	}

	function handle_make_popup_search($unique_nr, $tpl_set, $lang)
	{
		global $gb_lang, $gb_conf;

		$this -> include_lang($lang);

		$tpl = &new totemplate;
		$tpl_maker = &new template_maker;

		$tpl = $tpl_maker -> tpl_popup_search($tpl, $unique_nr, $gb_lang);

		return $tpl -> make($gb_conf['root_path'] . 'templates/' . $tpl_set . "/popup_search.tpl");

	}

	function handle_make_popup_search_replace($unique_nr, $tpl_set, $lang)
	{
		global $gb_lang, $gb_conf;

		$this -> include_lang($lang);

		$tpl = &new totemplate;
		$tpl_maker = &new template_maker;

		$tpl = $tpl_maker -> tpl_popup_search_replace($tpl, $unique_nr, $gb_lang);

		return $tpl -> make($gb_conf['root_path'] . 'templates/' . $tpl_set . "/popup_search_and_replace.tpl");

	}

	function handle_make_popup_link($unique_nr, $tpl_set, $lang)
	{
		global $gb_lang, $gb_conf;

		$this -> include_lang($lang);

		$tpl = &new totemplate;
		$tpl_maker = &new template_maker;

		$tpl = $tpl_maker -> tpl_popup_link($tpl, $unique_nr, $gb_lang);

		return $tpl -> make($gb_conf['root_path'] . 'templates/' . $tpl_set . "/popup_link.tpl");

	}

	function handle_make_popup_image($unique_nr, $tpl_set, $lang)
	{
		global $gb_lang, $gb_conf;

		$this -> include_lang($lang);

		$tpl = &new totemplate;
		$tpl_maker = &new template_maker;

		$tpl = $tpl_maker -> tpl_popup_image($tpl, $unique_nr, $gb_lang);

		return $tpl -> make($gb_conf['root_path'] . 'templates/' . $tpl_set . "/popup_image.tpl");

	}

	function handle_make_popup_list($unique_nr, $tpl_set, $lang)
	{
		global $gb_lang, $gb_conf;

		$this -> include_lang($lang);

		$tpl = &new totemplate;
		$tpl_maker = &new template_maker;

		$tpl = $tpl_maker -> tpl_popup_list($tpl, $unique_nr, $gb_lang);

		return $tpl -> make($gb_conf['root_path'] . 'templates/' . $tpl_set . "/popup_list.tpl");

	}

	function handle_make_popup_table($unique_nr, $tpl_set, $lang)
	{
		global $gb_lang, $gb_conf;

		$this -> include_lang($lang);

		$tpl = &new totemplate;
		$tpl_maker = &new template_maker;

		$tpl = $tpl_maker -> tpl_popup_table($tpl, $unique_nr, $gb_lang);

		return $tpl -> make($gb_conf['root_path'] . 'templates/' . $tpl_set .'/popup_table.tpl');

	}

	function handle_make_popup_special_chars($unique_nr, $tpl_set, $lang)
	{
		global $gb_lang, $gb_conf;

		$this -> include_lang($lang);

		$tpl = &new totemplate;
		$tpl_maker = &new template_maker;

		$tpl = $tpl_maker -> tpl_popup_special_chars($tpl, $unique_nr, $gb_lang);

		return $tpl -> make($gb_conf['root_path'] . 'templates/' . $tpl_set .'/popup_special_chars.tpl');

	}

	function handle_make_css($tpl_set)
	{
		global $gb_conf;

		return implode("",(file($gb_conf['root_path'] . 'templates/' . $tpl_set . '/css.css')));

	}

	function handle_make_image($image_name, $tpl_set)
	{
		global $gb_conf;

		header ("Content-type: image/jpeg");

		readfile($gb_conf['root_path'] . 'templates/' . $tpl_set .'/images/' . $image_name . '.gif');
	}

}

?>