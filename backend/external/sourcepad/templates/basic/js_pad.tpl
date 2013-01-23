document.writeln('<table><tr bgcolor ="#CFCFCF">')

if(conf[<!--{unique_nr}-->].allow_ico_preview){
	document.writeln('<td bgcolor = "#EFEFEF" class="xpbtn" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">')
	document.writeln('	<a href="javascript:preview(<!--{unique_nr}-->);">')
	document.writeln('	<img src="'+ conf[<!--{unique_nr}-->].handle_http_path + '' + conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_preview&gp_tpl_set=' + conf[<!--{unique_nr}-->].templateset + '&' + conf[<!--{unique_nr}-->].ext_para_str +'" title ="<!--{lan_preview}-->" border ="0">')
	document.writeln('	</a>')
	document.writeln('</td>')

}

if(conf[<!--{unique_nr}-->].allow_ico_font){
	document.writeln('<td bgcolor = "#EFEFEF" class="xpbtn">')
	document.writeln('		<select name="font<!--{unique_nr}-->" title="<!--{lan_chose_font}-->" onchange="font_choser(\'<font face=\', this.form.font<!--{unique_nr}-->.options[this.form.font<!--{unique_nr}-->.selectedIndex].value,\'</font>\', <!--{unique_nr}-->)">')
	document.writeln('			<option value="NULL"><!--{lan_font}--></option>')
	document.writeln('			<option value="Arial">Arial</option>')
	document.writeln('			<option value="Arial Black">Arial Black</option>')
	document.writeln('			<option value="Arial Narrow">Arial Narrow</option>')
	document.writeln('			<option value="Comic Sans MS">Comic Sans MS</option>')
	document.writeln('			<option value="Courier New">Courier New</option>')
	document.writeln('			<option value="System">System</option>')
	document.writeln('			<option value="Tahoma">Tahoma</option>')
	document.writeln('			<option value="Times New Roman">Times New Roman</option>')
	document.writeln('			<option value="Verdana">Verdana</option>')
	document.writeln('			<option value="Wingdings">Wingdings</option>')
	document.writeln('		</select>')
	document.writeln('</td>')
}

if(conf[<!--{unique_nr}-->].allow_ico_fontsize){
	document.writeln('<td bgcolor = "#EFEFEF" class="xpbtn">')
	document.writeln('		<select name="fontsize<!--{unique_nr}-->" onchange="font_choser(\'<font size=\', this.form.fontsize<!--{unique_nr}-->.options[this.form.fontsize<!--{unique_nr}-->.selectedIndex].value, \'</font>\', <!--{unique_nr}-->)">')
	document.writeln('			<option value="NULL"><!--{lan_font_size}--></option>')
	document.writeln('			<option value="1">1</option>')
	document.writeln('			<option value="2">2</option>')
	document.writeln('			<option value="3">3</option>')
	document.writeln('			<option value="4">4</option>')
	document.writeln('			<option value="5">5</option>')
	document.writeln('			<option value="6">6</option>')
	document.writeln('			<option value="7">7</option>')
	document.writeln('			<option value="8">8</option>')
	document.writeln('			<option value="10">10</option>')
	document.writeln('			<option value="12">12</option>')
	document.writeln('			<option value="14">14</option>')
	document.writeln('		</select>')
	document.writeln('</td>')
}

if(conf[<!--{unique_nr}-->].allow_ico_txtcolor){
	document.writeln('<td bgcolor = "#EFEFEF" class="xpbtn">')
	document.writeln('<select name="fontcolor<!--{unique_nr}-->" onChange="font_choser(\'<font color=\', this.form.fontcolor<!--{unique_nr}-->.options[this.form.fontcolor<!--{unique_nr}-->.selectedIndex].value, \'</font>\', <!--{unique_nr}-->)">')
	document.writeln('  <option style="color:black; background-color: #FFFFFF " value="NULL"><!--{lan_font_color}--></option>')
	document.writeln('  <option style="color:red; background-color: #DEE3E7" value="red"><!--{lan_red}--></option>')
	document.writeln('  <option style="color:darkred; background-color: #DEE3E7" value="darkred"><!--{lan_darkred}--></option>')
	document.writeln('  <option style="color:orange; background-color: #DEE3E7" value="orange"><!--{lan_orange}--></option>')
	document.writeln('  <option style="color:brown; background-color: #DEE3E7" value="brown"><!--{lan_brown}--></option>')
	document.writeln('  <option style="color:yellow; background-color: #DEE3E7" value="yellow" ><!--{lan_yellow}--></option>')
	document.writeln('  <option style="color:green; background-color: #DEE3E7" value="green" ><!--{lan_green}--></option>')
	document.writeln('  <option style="color:olive; background-color: #DEE3E7" value="olive" ><!--{lan_olive}--></option>')
	document.writeln('  <option style="color:cyan; background-color: #DEE3E7" value="cyan" ><!--{lan_cyan}--></option>')
	document.writeln('  <option style="color:blue; background-color: #DEE3E7" value="blue" ><!--{lan_blue}--></option>')
	document.writeln('  <option style="color:darkblue; background-color: #DEE3E7" value="darkblue" ><!--{lan_darkblue}--></option>')
	document.writeln('  <option style="color:indigo; background-color: #DEE3E7" value="indigo" ><!--{lan_indigo}--></option>')
	document.writeln('  <option style="color:violet; background-color: #DEE3E7" value="violet" ><!--{lan_violet}--></option>')
	document.writeln('  <option style="color:white; background-color: #DEE3E7" value="white" ><!--{lan_white}--></option>')
	document.writeln('  <option style="color:black; background-color: #DEE3E7" value="black" ><!--{lan_black}--></option>')
	document.writeln('</select>')
	document.writeln('</td>')
}

if(conf[<!--{unique_nr}-->].allow_ico_txtcolor_ext){
	document.writeln('<td bgcolor = "#EFEFEF" class="xpbtn" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">')
	document.writeln('	<a href="javascript:popup(\''+ conf[<!--{unique_nr}-->].handle_http_path +''+conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_popup_color&gp_color_opener=font_color&gp_lang='+ conf[<!--{unique_nr}-->].language +'&gp_tpl_set='+ conf[<!--{unique_nr}-->].templateset +'&gp_unique_nr=<!--{unique_nr}-->&'+ conf[<!--{unique_nr}-->].ext_para_str +'\', \'font_color\', \'320\', \'230\', \'no\')">')
	document.writeln('	<img src="'+ conf[<!--{unique_nr}-->].handle_http_path + '' + conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_fontcolor&gp_tpl_set=' + conf[<!--{unique_nr}-->].templateset + '&' + conf[<!--{unique_nr}-->].ext_para_str +'" title ="<!--{lan_more_txt_colors}-->" border ="0">')
	document.writeln('	</a>')
	document.writeln('</td>')
}

if(conf[<!--{unique_nr}-->].allow_ico_txtbgcolor){
	document.writeln('<td bgcolor = "#EFEFEF" class="xpbtn">')
	document.writeln('<select name="fontbgcolor<!--{unique_nr}-->" onChange="font_choser(\'<FONT style=\', this.form.fontbgcolor<!--{unique_nr}-->.options[this.form.fontbgcolor<!--{unique_nr}-->.selectedIndex].value, \'</font>\', <!--{unique_nr}-->)">')
	document.writeln('  <option style="color:black; background-color: #FFFFFF " value="NULL"><!--{lan_txt_bg_color}--></option>')
	document.writeln('  <option style="color:red; background-color: #DEE3E7" value="BACKGROUND-COLOR: red"><!--{lan_red}--></option>')
	document.writeln('  <option style="color:darkred; background-color: #DEE3E7" value="BACKGROUND-COLOR: darkred"><!--{lan_darkred}--></option>')
	document.writeln('  <option style="color:orange; background-color: #DEE3E7" value="BACKGROUND-COLOR: orange"><!--{lan_orange}--></option>')
	document.writeln('  <option style="color:brown; background-color: #DEE3E7" value="BACKGROUND-COLOR: brown"><!--{lan_brown}--></option>')
	document.writeln('  <option style="color:yellow; background-color: #DEE3E7" value="BACKGROUND-COLOR: yellow" ><!--{lan_yellow}--></option>')
	document.writeln('  <option style="color:green; background-color: #DEE3E7" value="BACKGROUND-COLOR: green" ><!--{lan_green}--></option>')
	document.writeln('  <option style="color:olive; background-color: #DEE3E7" value="BACKGROUND-COLOR: olive" ><!--{lan_olive}--></option>')
	document.writeln('  <option style="color:cyan; background-color: #DEE3E7" value="BACKGROUND-COLOR: cyan" ><!--{lan_cyan}--></option>')
	document.writeln('  <option style="color:blue; background-color: #DEE3E7" value="BACKGROUND-COLOR: blue" ><!--{lan_blue}--></option>')
	document.writeln('  <option style="color:darkblue; background-color: #DEE3E7" value="BACKGROUND-COLOR: darkblue" ><!--{lan_darkblue}--></option>')
	document.writeln('  <option style="color:indigo; background-color: #DEE3E7" value="BACKGROUND-COLOR: indigo" ><!--{lan_indigo}--></option>')
	document.writeln('  <option style="color:violet; background-color: #DEE3E7" value="BACKGROUND-COLOR: violet" ><!--{lan_violet}--></option>')
	document.writeln('  <option style="color:white; background-color: #DEE3E7" value="BACKGROUND-COLOR: white" ><!--{lan_white}--></option>')
	document.writeln('  <option style="color:black; background-color: #DEE3E7" value="BACKGROUND-COLOR: black" ><!--{lan_black}--></option>')
	document.writeln('</select>')
	document.writeln('</td>')
}

if(conf[<!--{unique_nr}-->].allow_ico_txtbgcolor_ext){
	document.writeln('<td bgcolor = "#EFEFEF" class="xpbtn" onmouseover="button_over(this, 0);" onmouseout="button_out(this, 0);" onmousedown="button_down(this, 0);" onmouseup="button_up(this, 0);">')
	document.writeln('	<a href="javascript:popup(\''+ conf[<!--{unique_nr}-->].handle_http_path +''+conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_popup_color&gp_color_opener=bg_color&gp_lang='+ conf[<!--{unique_nr}-->].language +'&gp_tpl_set='+ conf[<!--{unique_nr}-->].templateset +'&gp_unique_nr=<!--{unique_nr}-->&'+ conf[<!--{unique_nr}-->].ext_para_str +'\', \'font_color\', \'320\', \'230\', \'no\')">')
	document.writeln('	<img src="'+ conf[<!--{unique_nr}-->].handle_http_path + '' + conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_bgcolor&gp_tpl_set=' + conf[<!--{unique_nr}-->].templateset + '&' + conf[<!--{unique_nr}-->].ext_para_str +'" title ="<!--{lan_more_txt_bg_colors}-->" border ="0">')
	document.writeln('	</a>')
	document.writeln('</td>')
}

if(conf[<!--{unique_nr}-->].allow_ico_color_ext){
	document.writeln('<td bgcolor = "#EFEFEF" class="xpbtn" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">')
	document.writeln('	<a href="javascript:popup(\''+ conf[<!--{unique_nr}-->].handle_http_path +''+conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_popup_color&gp_color_opener=color&gp_lang='+ conf[<!--{unique_nr}-->].language +'&gp_tpl_set='+ conf[<!--{unique_nr}-->].templateset +'&gp_unique_nr=<!--{unique_nr}-->&'+ conf[<!--{unique_nr}-->].ext_para_str +'\', \'font_color\', \'320\', \'230\', \'no\')">')
	document.writeln('	<img src="'+ conf[<!--{unique_nr}-->].handle_http_path + '' + conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_colorchoser&gp_tpl_set=' + conf[<!--{unique_nr}-->].templateset + '&' + conf[<!--{unique_nr}-->].ext_para_str +'" title ="<!--{lan_chose_color}-->" border ="0">')
	document.writeln('	</a>')
	document.writeln('</td>')
}

if(conf[<!--{unique_nr}-->].allow_ico_special_chars){
	document.writeln('<td bgcolor = "#EFEFEF" class="xpbtn" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">')
	document.writeln('	<a href="javascript:popup(\''+ conf[<!--{unique_nr}-->].handle_http_path +''+conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_popup_special_chars&gp_lang='+ conf[<!--{unique_nr}-->].language +'&gp_tpl_set='+ conf[<!--{unique_nr}-->].templateset +'&gp_unique_nr=<!--{unique_nr}-->&'+ conf[<!--{unique_nr}-->].ext_para_str +'\', \'special_chars\', \'380\', \'200\', \'no\')">')
	document.writeln('	<img src="'+ conf[<!--{unique_nr}-->].handle_http_path + '' + conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_special_chars&gp_tpl_set=' + conf[<!--{unique_nr}-->].templateset + '&' + conf[<!--{unique_nr}-->].ext_para_str +'" title ="<!--{lan_insert_sc}-->" border ="0">')
	document.writeln('	</a>')
	document.writeln('</td>')
}

if(conf[<!--{unique_nr}-->].allow_ico_undo && browser.is_ie){
	document.writeln('<td bgcolor = "#EFEFEF" class="xpbtn" onmouseover="button_over(this, 0);" onmouseout="button_out(this, 0);" onmousedown="button_down(this, 0);" onmouseup="button_up(this, 0);">')
	document.writeln('	<a href="javascript:undo(\'<!--{unique_nr}-->\')">')
	document.writeln('	<img src="'+ conf[<!--{unique_nr}-->].handle_http_path + '' + conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_undo&gp_tpl_set=' + conf[<!--{unique_nr}-->].templateset + '&' + conf[<!--{unique_nr}-->].ext_para_str +'" title ="<!--{lan_undo}-->" border ="0">')
	document.writeln('	</a>')
	document.writeln('</td>')
}

if(conf[<!--{unique_nr}-->].allow_ico_redo && browser.is_ie){
	document.writeln('<td bgcolor = "#EFEFEF" class="xpbtn" onmouseover="button_over(this, 2);" onmouseout="button_out(this, 2);" onmousedown="button_down(this, 2);" onmouseup="button_up(this, 2);">')
	document.writeln('	<a href="javascript:redo(\'<!--{unique_nr}-->\')">')
	document.writeln('	<img src="'+ conf[<!--{unique_nr}-->].handle_http_path + '' + conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_redo&gp_tpl_set=' + conf[<!--{unique_nr}-->].templateset + '&' + conf[<!--{unique_nr}-->].ext_para_str +'" title ="<!--{lan_redo}-->" border ="0">')
	document.writeln('	</a>')
	document.writeln('</td>')

}

document.writeln('</tr></table>')
document.writeln('<table><tr bgcolor ="#CFCFCF">')


if(conf[<!--{unique_nr}-->].allow_ico_search && browser.is_ie){
	document.writeln('<td bgcolor = "#EFEFEF" class="xpbtn" onmouseover="button_over(this, 0);" onmouseout="button_out(this, 0);" onmousedown="button_down(this, 0);" onmouseup="button_up(this, 0);">')
	document.writeln('	<a href="javascript:popup(\''+ conf[<!--{unique_nr}-->].handle_http_path +''+conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_popup_search&gp_lang='+ conf[<!--{unique_nr}-->].language +'&gp_tpl_set='+ conf[<!--{unique_nr}-->].templateset +'&gp_unique_nr=<!--{unique_nr}-->&'+ conf[<!--{unique_nr}-->].ext_para_str +'\', \'search\', \'270\', \'100\', \'no\')">')
	document.writeln('	<img src="'+ conf[<!--{unique_nr}-->].handle_http_path + '' + conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_search&gp_tpl_set=' + conf[<!--{unique_nr}-->].templateset + '&' + conf[<!--{unique_nr}-->].ext_para_str +'" title ="<!--{lan_search}-->" border ="0">')
	document.writeln('	</a>')
	document.writeln('</td>')
}

if(conf[<!--{unique_nr}-->].allow_ico_search_replace && browser.is_ie){
	document.writeln('<td bgcolor = "#EFEFEF" class="xpbtn" onmouseover="button_over(this, 0);" onmouseout="button_out(this, 0);" onmousedown="button_down(this, 0);" onmouseup="button_up(this, 0);">')
	document.writeln('	<a href="javascript:popup(\''+ conf[<!--{unique_nr}-->].handle_http_path +''+conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_popup_search_replace&gp_lang='+ conf[<!--{unique_nr}-->].language +'&gp_tpl_set='+ conf[<!--{unique_nr}-->].templateset +'&gp_unique_nr=<!--{unique_nr}-->&'+ conf[<!--{unique_nr}-->].ext_para_str +'\', \'search_replace\', \'340\', \'130\', \'no\')">')
	document.writeln('	<img src="'+ conf[<!--{unique_nr}-->].handle_http_path + '' + conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_search_replace&gp_tpl_set=' + conf[<!--{unique_nr}-->].templateset + '&' + conf[<!--{unique_nr}-->].ext_para_str +'" title ="<!--{lan_search_replace}-->" border ="0">')
	document.writeln('	</a>')
	document.writeln('</td>')

	document.writeln('<td></td>')
	document.writeln('<td></td>')
}


if(conf[<!--{unique_nr}-->].allow_ico_bold){
	document.writeln('<td bgcolor = "#EFEFEF" id ="number0-<!--{unique_nr}-->" class="xpbtn" onmouseover="button_over2(this, 0);" onmouseout="button_out2(this, 0, <!--{unique_nr}-->);" onmousedown="button_down2(this, 0);" onmouseup="button_up2(this, 0);">')
	document.writeln('	<a href="javascript:doubletag(0, <!--{unique_nr}-->)">')
	document.writeln('	<img src="'+ conf[<!--{unique_nr}-->].handle_http_path + '' + conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_bold&gp_tpl_set=' + conf[<!--{unique_nr}-->].templateset + '&' + conf[<!--{unique_nr}-->].ext_para_str +'" title ="<!--{lan_bold}-->" border ="0">')
	document.writeln('	</a>')
	document.writeln('</td>')
}

if(conf[<!--{unique_nr}-->].allow_ico_italic){
	document.writeln('<td bgcolor = "#EFEFEF" id ="number2-<!--{unique_nr}-->" class="xpbtn" onmouseover="button_over2(this, 2);" onmouseout="button_out2(this, 2, <!--{unique_nr}-->);" onmousedown="button_down2(this, 2);" onmouseup="button_up2(this, 2);">')
	document.writeln('	<a href="javascript:doubletag(2, <!--{unique_nr}-->)">')
	document.writeln('	<img src="'+ conf[<!--{unique_nr}-->].handle_http_path + '' + conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_italic&gp_tpl_set=' + conf[<!--{unique_nr}-->].templateset + '&' + conf[<!--{unique_nr}-->].ext_para_str +'" title ="<!--{lan_italic}-->" border ="0">')
	document.writeln('	</a>')
	document.writeln('</td>')
}

if(conf[<!--{unique_nr}-->].allow_ico_underline){
	document.writeln('<td bgcolor = "#EFEFEF" id ="number4-<!--{unique_nr}-->" class="xpbtn" onmouseover="button_over2(this, 4);" onmouseout="button_out2(this, 4, <!--{unique_nr}-->);" onmousedown="button_down2(this, 4);" onmouseup="button_up2(this, 4);">')
	document.writeln('	<a href="javascript:doubletag(4, <!--{unique_nr}-->)">')
	document.writeln('	<img src="'+ conf[<!--{unique_nr}-->].handle_http_path + '' + conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_underline&gp_tpl_set=' + conf[<!--{unique_nr}-->].templateset + '&' + conf[<!--{unique_nr}-->].ext_para_str +'" title ="<!--{lan_underline}-->" border ="0">')
	document.writeln('	</a>')
	document.writeln('</td>')

	document.writeln('<td></td>')
	document.writeln('<td></td>')
}

if(conf[<!--{unique_nr}-->].allow_ico_align_left){
	document.writeln('<td bgcolor = "#EFEFEF" id ="number6-<!--{unique_nr}-->" class="xpbtn" onmouseover="button_over2(this, 6);" onmouseout="button_out2(this, 6, <!--{unique_nr}-->);" onmousedown="button_down2(this, 6);" onmouseup="button_up2(this, 6);">')
	document.writeln('<a href="javascript:doubletag(6, <!--{unique_nr}-->)">')
	document.writeln('	<img src="'+ conf[<!--{unique_nr}-->].handle_http_path + '' + conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_a_left&gp_tpl_set=' + conf[<!--{unique_nr}-->].templateset + '&' + conf[<!--{unique_nr}-->].ext_para_str +'" title ="<!--{lan_a_left}-->" border ="0">')
	document.writeln('	</a>')
	document.writeln('</td>')
}

if(conf[<!--{unique_nr}-->].allow_ico_align_center){
	document.writeln('<td bgcolor = "#EFEFEF" id ="number8-<!--{unique_nr}-->" class="xpbtn" onmouseover="button_over2(this, 8);" onmouseout="button_out2(this, 8, <!--{unique_nr}-->);" onmousedown="button_down2(this, 8);" onmouseup="button_up2(this, 8);">')
	document.writeln('	<a href="javascript:doubletag(8, <!--{unique_nr}-->)">')
	document.writeln('	<img src="'+ conf[<!--{unique_nr}-->].handle_http_path + '' + conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_a_center&gp_tpl_set=' + conf[<!--{unique_nr}-->].templateset + '&' + conf[<!--{unique_nr}-->].ext_para_str +'" title ="<!--{lan_a_center}-->" border ="0">')
	document.writeln('	</a>')
	document.writeln('</td>')
}

if(conf[<!--{unique_nr}-->].allow_ico_align_right){
	document.writeln('<td bgcolor = "#EFEFEF" id ="number10-<!--{unique_nr}-->" class="xpbtn" onmouseover="button_over2(this, 10);" onmouseout="button_out2(this, 10, <!--{unique_nr}-->);" onmousedown="button_down2(this, 10);" onmouseup="button_up2(this, 10);">')
	document.writeln('	<a href="javascript:doubletag(10, <!--{unique_nr}-->)">')
	document.writeln('	<img src="'+ conf[<!--{unique_nr}-->].handle_http_path + '' + conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_a_right&gp_tpl_set=' + conf[<!--{unique_nr}-->].templateset + '&' + conf[<!--{unique_nr}-->].ext_para_str +'" title ="<!--{lan_a_right}-->" border ="0">')
	document.writeln('	</a>')
	document.writeln('</td>')
}

if(conf[<!--{unique_nr}-->].allow_ico_align_justify){
	document.writeln('<td bgcolor = "#EFEFEF" id ="number12-<!--{unique_nr}-->" class="xpbtn" onmouseover="button_over2(this, 12);" onmouseout="button_out2(this, 12, <!--{unique_nr}-->);" onmousedown="button_down2(this, 12);" onmouseup="button_up2(this, 12);">')
	document.writeln('	<a href="javascript:doubletag(12, <!--{unique_nr}-->)">')
	document.writeln('	<img src="'+ conf[<!--{unique_nr}-->].handle_http_path + '' + conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_a_justify&gp_tpl_set=' + conf[<!--{unique_nr}-->].templateset + '&' + conf[<!--{unique_nr}-->].ext_para_str +'" title ="<!--{lan_a_justify}-->" border ="0">')
	document.writeln('	</a>')
	document.writeln('</td>')
}

if(conf[<!--{unique_nr}-->].allow_ico_margin){
	document.writeln('<td bgcolor = "#EFEFEF" id ="number14-<!--{unique_nr}-->" class="xpbtn" onmouseover="button_over2(this, 14);" onmouseout="button_out2(this, 14, <!--{unique_nr}-->);" onmousedown="button_down2(this, 14);" onmouseup="button_up2(this, 14);">')
	document.writeln('	<a href="javascript:doubletag(14, <!--{unique_nr}-->)"> ')
	document.writeln('	<img src="'+ conf[<!--{unique_nr}-->].handle_http_path + '' + conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_indent&gp_tpl_set=' + conf[<!--{unique_nr}-->].templateset + '&' + conf[<!--{unique_nr}-->].ext_para_str +'" title ="<!--{lan_margin}-->" border ="0">')
	document.writeln('	</a>')
	document.writeln('</td>')
}

if(conf[<!--{unique_nr}-->].allow_ico_close_open_tags){
	document.writeln('<td bgcolor = "#EFEFEF" class="xpbtn" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">')
	document.writeln('	<a href="javascript:doubletag(-1, <!--{unique_nr}-->)">')
	document.writeln('	<img src="'+ conf[<!--{unique_nr}-->].handle_http_path + '' + conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_close_all&gp_tpl_set=' + conf[<!--{unique_nr}-->].templateset + '&' + conf[<!--{unique_nr}-->].ext_para_str +'" title ="<!--{lan_close_open_tags}-->" border ="0">')
	document.writeln('	</a>')
	document.writeln('</td>')

	document.writeln('<td></td>')
	document.writeln('<td></td>')
}

if(conf[<!--{unique_nr}-->].allow_ico_hr){
	document.writeln('<td bgcolor = "#EFEFEF" class="xpbtn" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">')
	document.writeln('	<a href="javascript:singletag(\'<HR>\', <!--{unique_nr}-->)">')
	document.writeln('	<img src="'+ conf[<!--{unique_nr}-->].handle_http_path + '' + conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_line&gp_tpl_set=' + conf[<!--{unique_nr}-->].templateset + '&' + conf[<!--{unique_nr}-->].ext_para_str +'" title ="<!--{lan_hr}-->" border ="0">')
	document.writeln('	</a>')
	document.writeln('</td>')
}

if(conf[<!--{unique_nr}-->].allow_ico_br){
	document.writeln('<td bgcolor = "#EFEFEF" class="xpbtn" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">')
	document.writeln('	<a href="javascript:singletag(\'<BR>\', <!--{unique_nr}-->)">')
	document.writeln('	<img src="'+ conf[<!--{unique_nr}-->].handle_http_path + '' + conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_break&gp_tpl_set=' + conf[<!--{unique_nr}-->].templateset + '&' + conf[<!--{unique_nr}-->].ext_para_str +'" title ="<!--{lan_br}-->" border ="0">')
	document.writeln('	</a>')
	document.writeln('</td>')
}

if(conf[<!--{unique_nr}-->].allow_ico_link){
	document.writeln('<td bgcolor = "#EFEFEF" class="xpbtn" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">')
	document.writeln('	<a href="javascript:popup(\''+ conf[<!--{unique_nr}-->].handle_http_path +''+conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_popup_link&gp_lang='+ conf[<!--{unique_nr}-->].language +'&gp_tpl_set='+ conf[<!--{unique_nr}-->].templateset +'&gp_unique_nr=<!--{unique_nr}-->&'+ conf[<!--{unique_nr}-->].ext_para_str +'\', \'link\', \'320\', \'200\', \'no\')">')
	document.writeln('	<img src="'+ conf[<!--{unique_nr}-->].handle_http_path + '' + conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_link&gp_tpl_set=' + conf[<!--{unique_nr}-->].templateset + '&' + conf[<!--{unique_nr}-->].ext_para_str +'" title ="<!--{lan_insert_link}-->" border ="0">')
	document.writeln('	</a>')
	document.writeln('</td>')

}

if(conf[<!--{unique_nr}-->].allow_ico_image){
	document.writeln('<td bgcolor = "#EFEFEF" class="xpbtn" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">')
	document.writeln('	<a href="javascript:popup(\''+ conf[<!--{unique_nr}-->].handle_http_path +''+conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_popup_image&gp_lang='+ conf[<!--{unique_nr}-->].language +'&gp_tpl_set='+ conf[<!--{unique_nr}-->].templateset +'&gp_unique_nr=<!--{unique_nr}-->&'+ conf[<!--{unique_nr}-->].ext_para_str +'\', \'image\', \'400\', \'300\', \'no\')">')
	document.writeln('	<img src="'+ conf[<!--{unique_nr}-->].handle_http_path + '' + conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_image&gp_tpl_set=' + conf[<!--{unique_nr}-->].templateset + '&' + conf[<!--{unique_nr}-->].ext_para_str +'" title ="<!--{lan_insert_gfx}-->" border ="0">')
	document.writeln('	</a>')
	document.writeln('</td>')
}

if(conf[<!--{unique_nr}-->].allow_ico_list  && browser.is_opera == false){
	document.writeln('<td bgcolor = "#EFEFEF" class="xpbtn" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">')
	document.writeln('	<a href="javascript:popup(\''+ conf[<!--{unique_nr}-->].handle_http_path +''+conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_popup_list&gp_lang='+ conf[<!--{unique_nr}-->].language +'&gp_tpl_set='+ conf[<!--{unique_nr}-->].templateset +'&gp_unique_nr=<!--{unique_nr}-->&'+ conf[<!--{unique_nr}-->].ext_para_str +'\', \'list\', \'420\', \'300\', \'no\')">')
	document.writeln('	<img src="'+ conf[<!--{unique_nr}-->].handle_http_path + '' + conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_numlist&gp_tpl_set=' + conf[<!--{unique_nr}-->].templateset + '&' + conf[<!--{unique_nr}-->].ext_para_str +'" title ="<!--{lan_insert_numlist}-->" border ="0">')
	document.writeln('	</a>')
	document.writeln('</td>')

	document.writeln('<td></td>')
	document.writeln('<td></td>')
}

if(conf[<!--{unique_nr}-->].allow_ico_table){
	document.writeln('<td bgcolor = "#EFEFEF" class="xpbtn" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">')
	document.writeln('	<a href="javascript:popup(\''+ conf[<!--{unique_nr}-->].handle_http_path +''+conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_popup_table&gp_lang='+ conf[<!--{unique_nr}-->].language +'&gp_tpl_set='+ conf[<!--{unique_nr}-->].templateset +'&gp_unique_nr=<!--{unique_nr}-->&'+ conf[<!--{unique_nr}-->].ext_para_str +'\', \'table\', \'300\', \'420\', \'no\')">')
	document.writeln('	<img src="'+ conf[<!--{unique_nr}-->].handle_http_path + '' + conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_table_ins&gp_tpl_set=' + conf[<!--{unique_nr}-->].templateset + '&' + conf[<!--{unique_nr}-->].ext_para_str +'" title ="<!--{lan_insert_table}-->" border ="0">')
	document.writeln('	</a>')
	document.writeln('</td>')
}

if(conf[<!--{unique_nr}-->].allow_ico_tablerow){
	document.writeln('<td bgcolor = "#EFEFEF" class="xpbtn" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">')
	document.writeln('	<a href="javascript:font_choser(\'<TR>\', \'\',\'</TR>\', <!--{unique_nr}-->)">')
	document.writeln('	<img src="'+ conf[<!--{unique_nr}-->].handle_http_path + '' + conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_table_ins_row&gp_tpl_set=' + conf[<!--{unique_nr}-->].templateset + '&' + conf[<!--{unique_nr}-->].ext_para_str +'" title ="<!--{lan_insert_tr}-->" border ="0">')
	document.writeln('	</a>')
	document.writeln('</td>')
}

if(conf[<!--{unique_nr}-->].allow_ico_tabledesk){
	document.writeln('<td bgcolor = "#EFEFEF" class="xpbtn" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">')
	document.writeln('	<a href="javascript:font_choser(\'<TD>\', \'\',\'</TD>\', <!--{unique_nr}-->)">')
	document.writeln('	<img src="'+ conf[<!--{unique_nr}-->].handle_http_path + '' + conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_table_ins_col&gp_tpl_set=' + conf[<!--{unique_nr}-->].templateset + '&' + conf[<!--{unique_nr}-->].ext_para_str +'" title ="<!--{lan_insert_td}-->" border ="0">')
	document.writeln('	</a>')
	document.writeln('</td>')
}

document.writeln('</tr></table>')