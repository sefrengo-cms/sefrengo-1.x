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


class template_maker
{

	function tpl_init($tpl, $conf, $lang)
	{
		global $gb_uniqe_nr;

		//Var Templates
		$tpl -> insert('', 'form_name', $this -> conf['form_name']);
		$tpl -> insert('', 'textfield_name', $this -> conf['textfield_name']);
		$tpl -> insert('', 'unique_nr', $gb_uniqe_nr);

		$tpl -> insert('', 'handle_file', $conf['handle_file']);
		$tpl -> insert('', 'handle_http_path', $conf['handle_http_path']);
		$tpl -> insert('', 'output_file', $conf['output_file']);
		$tpl -> insert('', 'output_http_path', $conf['output_http_path']);

		$tpl -> insert('', 'templateset', $conf['templateset']);
		$tpl -> insert('', 'template_path_number', $conf['template_path_number']);
		$tpl -> insert('', 'images_http_path', $conf['images_http_path']);
		$tpl -> insert('', 'language', $conf['language']);

		$tpl -> insert('', 'ext_para_str', $conf['ext_para_str']);

		$tpl -> insert('', 'form_name', $conf['form_name']);
		$tpl -> insert('', 'textfield_name', $conf['textfield_name']);

		$tpl -> insert('', 'allow_ico_save', $conf['allow_ico_save']);
		$tpl -> insert('', 'allow_ico_set_back', $conf['allow_ico_set_back']);
		$tpl -> insert('', 'allow_ico_font', $conf['allow_ico_font']);
		$tpl -> insert('', 'allow_ico_fontsize', $conf['allow_ico_fontsize']);
		$tpl -> insert('', 'allow_ico_txtcolor', $conf['allow_ico_txtcolor']);
		$tpl -> insert('', 'allow_ico_txtcolor_ext', $conf['allow_ico_txtcolor_ext']);
		$tpl -> insert('', 'allow_ico_txtbgcolor', $conf['allow_ico_txtbgcolor']);
		$tpl -> insert('', 'allow_ico_txtbgcolor_ext', $conf['allow_ico_txtbgcolor_ext']);
		$tpl -> insert('', 'allow_ico_special_chars', $conf['allow_ico_special_chars']);
		$tpl -> insert('', 'allow_ico_color_ext', $conf['allow_ico_color_ext']);
		$tpl -> insert('', 'allow_ico_print', $conf['allow_ico_print']);
		$tpl -> insert('', 'print_nl2br', $conf['print_nl2br']);
		$tpl -> insert('', 'allow_ico_preview', $conf['allow_ico_preview']);
		$tpl -> insert('', 'preview_nl2br', $conf['preview_nl2br']);
		$tpl -> insert('', 'allow_ico_search', $conf['allow_ico_search']);
		$tpl -> insert('', 'allow_ico_search_replace', $conf['allow_ico_search_replace']);
		$tpl -> insert('', 'allow_ico_undo', $conf['allow_ico_undo']);
		$tpl -> insert('', 'allow_ico_redo', $conf['allow_ico_redo']);
		$tpl -> insert('', 'allow_ico_bold', $conf['allow_ico_bold']);
		$tpl -> insert('', 'allow_ico_italic', $conf['allow_ico_italic']);
		$tpl -> insert('', 'allow_ico_underline', $conf['allow_ico_underline']);
		$tpl -> insert('', 'allow_ico_align_left', $conf['allow_ico_align_left']);
		$tpl -> insert('', 'allow_ico_align_center', $conf['allow_ico_align_center']);
		$tpl -> insert('', 'allow_ico_align_right', $conf['allow_ico_align_right']);
		$tpl -> insert('', 'allow_ico_align_justify', $conf['allow_ico_align_justify']);
		$tpl -> insert('', 'allow_ico_close_open_tags', $conf['allow_ico_close_open_tags']);
		$tpl -> insert('', 'allow_ico_hr', $conf['allow_ico_hr']);
		$tpl -> insert('', 'allow_ico_br', $conf['allow_ico_br']);
		$tpl -> insert('', 'allow_ico_margin', $conf['allow_ico_margin']);
		$tpl -> insert('', 'allow_ico_link', $conf['allow_ico_link']);
		$tpl -> insert('', 'allow_ico_image', $conf['allow_ico_image']);
		$tpl -> insert('', 'allow_ico_list', $conf['allow_ico_list']);
		$tpl -> insert('', 'allow_ico_table', $conf['allow_ico_table']);
		$tpl -> insert('', 'allow_ico_tablerow', $conf['allow_ico_tablerow']);
		$tpl -> insert('', 'allow_ico_tabledesk', $conf['allow_ico_tabledesk']);
		$tpl -> insert('', 'undo_limit', $conf['undo_limit']);

		$tpl -> insert('', 'print_as', $conf['print_as']);
		$tpl -> insert('', 'preview_as', $conf['preview_as']);
		$tpl -> insert('', 'nl_to_br', $conf['nl_to_br']);
		$tpl -> insert('', 'editorheight', $conf['editorheight']);
		$tpl -> insert('', 'editorwidth', $conf['editorwidth']);
		$tpl -> insert('', 'editorheight_css', $conf['editorheight_css']);
		$tpl -> insert('', 'editorwidth_css', $conf['editorwidth_css']);
		$tpl -> insert('', 'wrap', $conf['wrap']);

		$tpl -> insert('', 'content', $conf['content']);

		if($conf['allow_ico_save'] == 1){
			$tpl -> insert('', 'btn_save', '<input type="submit" value="' . $lang['save_file'] . '">');
		}
		else{
			$tpl -> insert('', 'btn_save', '');

		}

		if($conf['allow_ico_set_back'] == 1){
			$tpl -> insert('', 'btn_reset', '<input type="reset" value="'. $lang['reset'] .'">');
		}
		else{
			$tpl -> insert('', 'btn_reset', '');
		}

		if($gb_uniqe_nr == 1){
			$sub = $this -> sub_tpl_init_once($conf, $gb_uniqe_nr, $lang);
			$tpl -> insert('', 'init_once', $sub);
		}
		else{
			$tpl -> insert('', 'init_once', '');
		}

		if(!empty($conf['ext_para_str'])){
			$key_value = explode( '&', $conf['ext_para_str']);

			$kv_count = count($key_value);
			for($i = 0; $i < $kv_count; $i++)
			{
				$key_value_expl = explode( '=', $key_value[$i]);
				$hidden_value .= '<input type="hidden" name="' . $key_value_expl['0'] . '" value="' . $key_value_expl['1'] . '">' . "\n";
				unset($key_value_expl);
			}

			$tpl -> insert('', 'ext_para_str_hidden', $hidden_value);
		}
		else{
			$tpl -> insert('', 'ext_para_str_hidden', '');
		}

		return $tpl;
	}


	function sub_tpl_init_once($conf, $gb_uniqe_nr, $lang)
	{
		global $gb_conf;

		$tplsub = new totemplate;

		$tplsub -> insert('', 'language', $conf['language']);
		$tplsub -> insert('', 'unselectable', $conf['unselectable']);
		$tplsub -> insert('', 'ext_para_str', $conf['ext_para_str']);
		$tplsub -> insert('', 'templateset', $conf['templateset']);
		$tplsub -> insert('', 'handle_file', $conf['handle_file']);
		$tplsub -> insert('', 'handle_http_path', $conf['handle_http_path']);
		return $tplsub -> make($gb_conf['root_path'] . 'templates/' . $conf['templateset'] . '/init_once.tpl');

	}

	function tpl_make_js_functions($tpl, $lang)
	{

		//Language Templates
		$tpl -> insert("", "lan_js_not_found", $lang['js_not_found']);
		$tpl -> insert("", "lan_js_replace_1", $lang['js_replace_1']);
		$tpl -> insert("", "lan_js_replace_2", $lang['js_replace_2']);
		$tpl -> insert("", "lan_js_click_textfield_after_undo", $lang['js_click_textfield_after_undo']);

		$tpl -> insert("", "lan_js_preview", $lang['js_preview']);
		$tpl -> insert("", "lan_js_empty_textfield", $lang['js_empty_textfield']);
		$tpl -> insert("", "lan_js_replace_1", $lang['js_replace_1']);
		$tpl -> insert("", "lan_js_replace_1", $lang['js_replace_1']);
		$tpl -> insert("", "lan_js_replace_1", $lang['js_replace_1']);



		return $tpl;
	}

	function tpl_js_pad($tpl, $tpl_set, $unique_nr, $lang)
	{

		//Var Templates
		$tpl -> insert('', 'unique_nr', $unique_nr);
		$tpl -> insert('', 'templateset', $tpl_set);

		//Language Templates
		$tpl -> insert('', 'lan_save_file', $lang['save_file']);
		$tpl -> insert('', 'lan_reset', $lang['reset']);
		$tpl -> insert('', 'lan_chose_font', $lang['chose_font']);
		$tpl -> insert('', 'lan_font', $lang['font']);
		$tpl -> insert('', 'lan_font_size', $lang['font_size']);
		$tpl -> insert('', 'lan_font_color', $lang['font_color']);
		$tpl -> insert('', 'lan_red', $lang['red']);
		$tpl -> insert('', 'lan_darkred', $lang['darkred']);
		$tpl -> insert('', 'lan_orange', $lang['orange']);
		$tpl -> insert('', 'lan_brown', $lang['brown']);
		$tpl -> insert('', 'lan_yellow', $lang['yellow']);
		$tpl -> insert('', 'lan_green', $lang['green']);
		$tpl -> insert('', 'lan_olive', $lang['olive']);
		$tpl -> insert('', 'lan_cyan', $lang['cyan']);
		$tpl -> insert('', 'lan_blue', $lang['blue']);
		$tpl -> insert('', 'lan_darkblue', $lang['darkblue']);
		$tpl -> insert('', 'lan_indigo', $lang['indigo']);
		$tpl -> insert('', 'lan_violet', $lang['violet']);
		$tpl -> insert('', 'lan_white', $lang['white']);
		$tpl -> insert('', 'lan_black', $lang['black']);
		$tpl -> insert('', 'lan_more_txt_colors', $lang['more_txt_colors']);
		$tpl -> insert('', 'lan_txt_bg_color', $lang['txt_bg_color']);
		$tpl -> insert('', 'lan_more_txt_bg_colors', $lang['more_txt_bg_colors']);
		$tpl -> insert('', 'lan_chose_color', $lang['chose_hex_color']);
		$tpl -> insert('', 'lan_insert_sc', $lang['insert_sc']);
		$tpl -> insert('', 'lan_print_preview', $lang['print_preview']);
		$tpl -> insert('', 'lan_preview', $lang['preview']);
		$tpl -> insert('', 'lan_search', $lang['search']);
		$tpl -> insert('', 'lan_search_replace', $lang['search_replace']);
		$tpl -> insert('', 'lan_undo', $lang['undo']);
		$tpl -> insert('', 'lan_redo', $lang['redo']);
		$tpl -> insert('', 'lan_bold', $lang['bold']);
		$tpl -> insert('', 'lan_italic', $lang['italic']);
		$tpl -> insert('', 'lan_underline', $lang['underline']);
		$tpl -> insert('', 'lan_a_left', $lang['a_left']);
		$tpl -> insert('', 'lan_a_center', $lang['a_center']);
		$tpl -> insert('', 'lan_a_right', $lang['a_right']);
		$tpl -> insert('', 'lan_a_justify', $lang['a_justify']);
		$tpl -> insert('', 'lan_close_open_tags', $lang['close_open_tags']);
		$tpl -> insert('', 'lan_hr', $lang['hr']);
		$tpl -> insert('', 'lan_br', $lang['br']);
		$tpl -> insert('', 'lan_margin', $lang['margin']);
		$tpl -> insert('', 'lan_insert_link', $lang['insert_link']);
		$tpl -> insert('', 'lan_insert_gfx', $lang['insert_gfx']);
		$tpl -> insert('', 'lan_insert_numlist', $lang['insert_numlist']);
		$tpl -> insert('', 'lan_insert_table', $lang['insert_table']);
		$tpl -> insert('', 'lan_insert_tr', $lang['insert_tr']);
		$tpl -> insert('', 'lan_insert_td', $lang['insert_td']);

		return $tpl;
	}


	function tpl_popup_special_chars($tpl, $unique_nr, $lang)
	{

		//Var Templates
		$tpl -> insert("", "unique_nr", $unique_nr);

		//Language Templates
		$tpl -> insert("", "lan_pop_sc_title", $lang['pop_sc_title']);
		$tpl -> insert("", "lan_pop_sc_close", $lang['pop_sc_close']);

		return $tpl;
	}


	function tpl_popup_color($tpl, $color_opener, $unique_nr, $lang)
	{

		//Var Templates
		$tpl -> insert("", "unique_nr", $unique_nr);
		$tpl -> insert("", "color_opener", $color_opener);


		//Language Templates
		$tpl -> insert("", "lan_colorchose", $lang['color_chose']);
		$tpl -> insert("", "lan_insert", $lang['insert']);
		$tpl -> insert("", "lan_abort", $lang['abort']);
		$tpl -> insert("", "lan_preview_color", $lang['preview_color']);
		$tpl -> insert("", "lan_chose_color", $lang['chose_color']);
		$tpl -> insert("", "lan_mes_no_color", $lang['mes_no_color']);

		switch ($color_opener)
		{
 		   case 'font_color':
				$tpl -> insert("", "lan_opener_txt", $lang['mes_popup_txt_color_chose']);
				break;
 		   case 'bg_color':
				$tpl -> insert("", "lan_opener_txt", $lang['mes_popup_txt_bg_color_chose']);
				break;
 		   case 'color':
				$tpl -> insert("", "lan_opener_txt", $lang['mes_popup_color_chose']);
				break;
		}

		return $tpl;
	}


	function tpl_popup_search($tpl, $unique_nr, $lang)
	{

		//Var Templates
		$tpl -> insert("", "unique_nr", $unique_nr);

		//Language Templates
		$tpl -> insert("", "lan_pop_search", $lang['pop_search']);
		$tpl -> insert("", "lan_pop_search_no_searchstring", $lang['pop_search_no_searchstring']);
		$tpl -> insert("", "lan_pop_search_start", $lang['pop_search_start']);
		$tpl -> insert("", "lan_pop_search_abort", $lang['pop_search_abort']);

		return $tpl;
	}


	function tpl_popup_search_replace($tpl, $unique_nr, $lang)
	{

		//Var Templates
		$tpl -> insert("", "unique_nr", $unique_nr);

		//Language Templates
		$tpl -> insert("", "lan_pop_sr_title", $lang['pop_sr_title']);
		$tpl -> insert("", "lan_pop_sr_no_searchstring", $lang['pop_sr_no_searchstring']);
		$tpl -> insert("", "lan_pop_sr_no_replacestring", $lang['pop_sr_no_replacestring']);
		$tpl -> insert("", "lan_pop_sr_search", $lang['pop_sr_search']);
		$tpl -> insert("", "lan_pop_sr_replace", $lang['pop_sr_replace']);
		$tpl -> insert("", "lan_pop_sr_start_search", $lang['pop_sr_start_search']);
		$tpl -> insert("", "lan_pop_sr_start_replace", $lang['pop_sr_start_replace']);
		$tpl -> insert("", "lan_pop_sr_replace_all", $lang['pop_sr_replace_all']);
		$tpl -> insert("", "lan_pop_sr_abort", $lang['pop_sr_abort']);
		return $tpl;
	}


	function tpl_popup_link($tpl, $unique_nr, $lang)
	{

		//Var Templates
		$tpl -> insert("", "unique_nr", $unique_nr);

		//Language Templates
		$tpl -> insert("", "lan_pop_link_title", $lang['pop_link_title']);
		$tpl -> insert("", "lan_pop_link_url", $lang['pop_link_url']);
		$tpl -> insert("", "lan_pop_link_other", $lang['pop_link_other']);
		$tpl -> insert("", "lan_pop_link_target", $lang['pop_link_target']);
		$tpl -> insert("", "lan_pop_link_notarget", $lang['pop_link_notarget']);
		$tpl -> insert("", "lan_pop_link_customtarget", $lang['pop_link_customtarget']);
		$tpl -> insert("", "lan_pop_link_preview", $lang['pop_link_preview']);
		$tpl -> insert("", "lan_pop_link_insert", $lang['pop_link_insert']);
		$tpl -> insert("", "lan_pop_link_abort", $lang['pop_link_abort']);

		return $tpl;
	}


	function tpl_popup_image($tpl, $unique_nr, $lang)
	{

		//Var Templates
		$tpl -> insert("", "unique_nr", $unique_nr);

		//Language Templates
		$tpl -> insert("", "lan_pop_gfx_title", $lang['pop_gfx_title']);
		$tpl -> insert("", "lan_pop_gfx_url", $lang['pop_gfx_url']);
		$tpl -> insert("", "lan_pop_gfx_alt", $lang['pop_gfx_alt']);
		$tpl -> insert("", "lan_pop_gfx_align", $lang['pop_gfx_align']);
		$tpl -> insert("", "lan_pop_gfx_default", $lang['pop_gfx_default']);
		$tpl -> insert("", "lan_pop_gfx_left", $lang['pop_gfx_left']);
		$tpl -> insert("", "lan_pop_gfx_right", $lang['pop_gfx_right']);
		$tpl -> insert("", "lan_pop_gfx_top", $lang['pop_gfx_top']);
		$tpl -> insert("", "lan_pop_gfx_middle", $lang['pop_gfx_middle']);
		$tpl -> insert("", "lan_pop_gfx_bottom", $lang['pop_gfx_bottom']);
		$tpl -> insert("", "lan_pop_gfx_width", $lang['pop_gfx_width']);
		$tpl -> insert("", "lan_pop_gfx_height", $lang['pop_gfx_height']);
		$tpl -> insert("", "lan_pop_gfx_border", $lang['pop_gfx_border']);
		$tpl -> insert("", "lan_pop_gfx_vspace", $lang['pop_gfx_vspace']);
		$tpl -> insert("", "lan_pop_gfx_hspace", $lang['pop_gfx_hspace']);
		$tpl -> insert("", "lan_pop_gfx_insert", $lang['pop_gfx_insert']);
		$tpl -> insert("", "lan_pop_gfx_abort", $lang['pop_gfx_abort']);

		return $tpl;
	}


	function tpl_popup_list($tpl, $unique_nr, $lang)
	{

		//Var Templates
		$tpl -> insert("", "unique_nr", $unique_nr);

		//Language Templates
		$tpl -> insert("", "lan_pop_ls_title", $lang['pop_ls_title']);
		$tpl -> insert("", "lan_pop_ls_no_new_item", $lang['pop_ls_no_new_item']);
		$tpl -> insert("", "lan_pop_ls_nothing_to_update", $lang['pop_ls_nothing_to_update']);
		$tpl -> insert("", "lan_pop_ls_preview_window", $lang['pop_ls_preview_window']);
		$tpl -> insert("", "lan_pop_ls_listtype", $lang['pop_ls_listtype']);
		$tpl -> insert("", "lan_pop_ls_full_circle", $lang['pop_ls_full_circle']);
		$tpl -> insert("", "lan_pop_ls_circle", $lang['pop_ls_circle']);
		$tpl -> insert("", "lan_pop_ls_rectangle", $lang['pop_ls_rectangle']);
		$tpl -> insert("", "lan_pop_ls_input", $lang['pop_ls_input']);
		$tpl -> insert("", "lan_pop_ls_insert", $lang['pop_ls_insert']);
		$tpl -> insert("", "lan_pop_ls_update_ls", $lang['pop_ls_update_ls']);
		$tpl -> insert("", "lan_pop_ls_ls", $lang['pop_ls_ls']);
		$tpl -> insert("", "lan_pop_ls_move_up", $lang['pop_ls_move_up']);
		$tpl -> insert("", "lan_pop_ls_delete", $lang['pop_ls_delete']);
		$tpl -> insert("", "lan_pop_ls_move_down", $lang['pop_ls_move_down']);
		$tpl -> insert("", "lan_pop_ls_preview", $lang['pop_ls_preview']);
		$tpl -> insert("", "lan_pop_ls_insert_final", $lang['pop_ls_insert_final']);
		$tpl -> insert("", "lan_pop_ls_abort", $lang['pop_ls_abort']);
		$tpl -> insert("", "lan_pop_ls_no_ls", $lang['pop_ls_no_ls']);

		return $tpl;
	}


	function tpl_popup_table($tpl, $unique_nr, $lang)
	{

		//Var Templates
		$tpl -> insert("", "unique_nr", $unique_nr);

		//Language Templates
		$tpl -> insert("", "lan_pop_table_title", $lang['pop_table_title']);
		$tpl -> insert("", "lan_pop_table_rows", $lang['pop_table_rows']);
		$tpl -> insert("", "lan_pop_table_cols", $lang['pop_table_cols']);
		$tpl -> insert("", "lan_pop_table_cellpadding", $lang['pop_table_cellpadding']);
		$tpl -> insert("", "lan_pop_table_cellspacing", $lang['pop_table_cellspacing']);
		$tpl -> insert("", "lan_pop_table_width", $lang['pop_table_width']);
		$tpl -> insert("", "lan_pop_table_pixel", $lang['pop_table_pixel']);
		$tpl -> insert("", "lan_pop_table_percent", $lang['pop_table_percent']);
		$tpl -> insert("", "lan_pop_table_height", $lang['pop_table_height']);
		$tpl -> insert("", "lan_pop_table_border", $lang['pop_table_border']);
		$tpl -> insert("", "lan_pop_table_align", $lang['pop_table_align']);
		$tpl -> insert("", "lan_pop_table_default", $lang['pop_table_default']);
		$tpl -> insert("", "lan_pop_table_left", $lang['pop_table_left']);
		$tpl -> insert("", "lan_pop_table_center", $lang['pop_table_center']);
		$tpl -> insert("", "lan_pop_table_right", $lang['pop_table_right']);
		$tpl -> insert("", "lan_pop_table_bordercolor", $lang['pop_table_bordercolor']);
		$tpl -> insert("", "lan_pop_table_chose_bordercolor", $lang['pop_table_chose_bordercolor']);
		$tpl -> insert("", "lan_pop_table_bgcolor", $lang['pop_table_bgcolor']);
		$tpl -> insert("", "lan_pop_table_chose_bgcolor", $lang['pop_table_chose_bgcolor']);
		$tpl -> insert("", "lan_pop_table_insert", $lang['pop_table_insert']);
		$tpl -> insert("", "lan_pop_table_abort", $lang['pop_table_abort']);

		return $tpl;
	}
}

?>