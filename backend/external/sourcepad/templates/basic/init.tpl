

<!--{init_once}-->

<script language="JavaScript" type="text/javascript">
<!--

function confSetup<!--{unique_nr}-->()
{
	this.form_name = '<!--{form_name}-->';
	this.textfield_name = '<!--{textfield_name}-->';

	this.handle_file = '<!--{handle_file}-->';
	this.handle_http_path = '<!--{handle_http_path}-->';

	this.templateset = '<!--{templateset}-->';
	this.language = '<!--{language}-->';

	this.ext_para_str = '<!--{ext_para_str}-->';

	this.allow_ico_save = <!--{allow_ico_save}-->;
	this.allow_ico_set_back = <!--{allow_ico_set_back}-->;
	this.allow_ico_font = <!--{allow_ico_font}-->;
	this.allow_ico_fontsize = <!--{allow_ico_fontsize}-->;
	this.allow_ico_txtcolor = <!--{allow_ico_txtcolor}-->;
	this.allow_ico_txtcolor_ext = <!--{allow_ico_txtcolor_ext}-->;
	this.allow_ico_txtbgcolor = <!--{allow_ico_txtbgcolor}-->;
	this.allow_ico_txtbgcolor_ext = <!--{allow_ico_txtbgcolor_ext}-->;
	this.allow_ico_color_ext = <!--{allow_ico_color_ext}-->;
	this.allow_ico_special_chars = <!--{allow_ico_special_chars}-->;
	this.allow_ico_print = <!--{allow_ico_print}-->;
	this.allow_ico_preview = <!--{allow_ico_preview}-->;
	this.allow_ico_search = <!--{allow_ico_search}-->;
	this.allow_ico_search_replace = <!--{allow_ico_search_replace}-->;
	this.allow_ico_undo = <!--{allow_ico_undo}-->;
	this.allow_ico_redo = <!--{allow_ico_redo}-->;
	this.allow_ico_bold = <!--{allow_ico_bold}-->;
	this.allow_ico_italic = <!--{allow_ico_italic}-->;
	this.allow_ico_underline = <!--{allow_ico_underline}-->;
	this.allow_ico_align_left = <!--{allow_ico_align_left}-->;
	this.allow_ico_align_center = <!--{allow_ico_align_center}-->;
	this.allow_ico_align_right = <!--{allow_ico_align_right}-->;
	this.allow_ico_align_justify = <!--{allow_ico_align_justify}-->;
	this.allow_ico_close_open_tags = <!--{allow_ico_close_open_tags}-->;
	this.allow_ico_hr = <!--{allow_ico_hr}-->;
	this.allow_ico_br = <!--{allow_ico_br}-->;
	this.allow_ico_margin = <!--{allow_ico_margin}-->;
	this.allow_ico_link = <!--{allow_ico_link}-->;
	this.allow_ico_image = <!--{allow_ico_image}-->;
	this.allow_ico_list = <!--{allow_ico_list}-->;
	this.allow_ico_table = <!--{allow_ico_table}-->;
	this.allow_ico_tablerow = <!--{allow_ico_tablerow}-->;
	this.allow_ico_tabledesk = <!--{allow_ico_tabledesk}-->;

	this.print_as = '<!--{print_as}-->';
	this.print_nl2br = '<!--{print_nl2br}-->';
	this.preview_as = '<!--{preview_as}-->';
	this.preview_nl2br = '<!--{preview_nl2br}-->';
	this.editorheight = '<!--{editorheight}-->';
	this.editorwidth = '<!--{editorwidth}-->';
	this.editorheight_css = '<!--{editorheight_css}-->';
	this.editorwidth_css = '<!--{editorwidth_css}-->';

	return this;
}

//init specificeditorSetup object
conf[<!--{unique_nr}-->] = new confSetup<!--{unique_nr}-->();

//init texteffects for b,i,u...
bgColor_dbltag[<!--{unique_nr}-->] = new colored_dbltag();

//init specific undo/redo vars
if(browser.is_ie){
	//Undo config for specific editor
	undo_container[<!--{unique_nr}-->] = new Array;
	undo_timer_new[<!--{unique_nr}-->] = '';
	undo_timer_diff[<!--{unique_nr}-->] = '';
	undo_container_index[<!--{unique_nr}-->] = 0;

	undo_start[<!--{unique_nr}-->] = 0;
	undo_limit[<!--{unique_nr}-->] = <!--{undo_limit}-->;
	undo_loop[<!--{unique_nr}-->] = false;
	undo_max[<!--{unique_nr}-->] = 0;

	undo_date(<!--{unique_nr}-->);
}

//Load the JavaScriptEditor
if(browser.is_compatible ){

//Begin: mvsxyz
	document.writeln('<script src="<!--{handle_http_path}--><!--{handle_file}-->?gp_action=make_js_pad&gp_lang=<!--{language}-->&gp_tpl_set=<!--{templateset}-->&gp_unique_nr=<!--{unique_nr}-->&<!--{ext_para_str}-->" type="text/javascript"></'+'script>');
//End: mvsxyz

}
else{
	document.writeln('<!--{btn_save}--><!--{btn_reset}-->');
}
-->
</script>
<noscript>
	<!--{btn_save}-->
	<!--{btn_reset}-->
</noscript>

<!--{ext_para_str_hidden}-->

  <textarea name="<!--{textfield_name}-->" cols="<!--{editorwidth}-->" rows="<!--{editorheight}-->"
    onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" onkeydown="undo_timer(event, <!--{unique_nr}-->)"
    wrap="<!--{wrap}-->" style="height:<!--{editorheight_css}-->;width:<!--{editorwidth_css}-->;"><!--{content}--></textarea>
