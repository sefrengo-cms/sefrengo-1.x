<html>
<head>
<title><!--{lan_pop_table_title}--></title>
<script language="JavaScript">
<!--
document.writeln('<link rel="stylesheet" href="' + window.opener.conf[<!--{unique_nr}-->].handle_http_path + '' + window.opener.conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_css&gp_tpl_set=' + window.opener.conf[<!--{unique_nr}-->].templateset + '&' + window.opener.conf[<!--{unique_nr}-->].ext_para_str +'" type="text/css">')

var conf = new Array();
conf[<!--{unique_nr}-->] = new confSetup();
var active_color_field;
function confSetup()
{
	this.handle_file = window.opener.conf[<!--{unique_nr}-->].handle_file;
	this.handle_http_path = window.opener.conf[<!--{unique_nr}-->].handle_http_path;
	this.images_http_path = window.opener.conf[<!--{unique_nr}-->].images_http_path;
	this.language = window.opener.conf[<!--{unique_nr}-->].language;
	this.templateset = window.opener.conf[<!--{unique_nr}-->].templateset;
	this.ext_para_str = window.opener.conf[<!--{unique_nr}-->].ext_para_str;

	return this;
}


function button_over(eButton)
{
	eButton.style.backgroundColor = "#B5BDD6";
	eButton.style.borderColor = "darkblue darkblue darkblue darkblue";
}

function button_out(eButton)
{
	eButton.style.backgroundColor = "#EFEFEF";
	eButton.style.borderColor = "threedface";
}

function button_down(eButton)
{
	eButton.style.backgroundColor = "#8494B5";
	eButton.style.borderColor = "darkblue darkblue darkblue darkblue";
}

function button_up(eButton)
{
	eButton.style.backgroundColor = "#B5BDD6";
	eButton.style.borderColor = "darkblue darkblue darkblue darkblue";
	eButton = null;
}


function validate_number (evt)
{
  key_code = evt.which ? evt.which : evt.keyCode;

  if(key_code < 48 || key_code > 58){
    if(key_code < 96 || key_code > 105){
 	  if(key_code == 8 || key_code == 46){
 	  	//nix tun :)
 	  }
  	  else{
  	  	return false;
  	  }
    }
  }
}


function popup(url, name, widthX, heightY, resz)
{
	posX = (screen.width) ? (screen.width-widthX)/2 : 0;
	posY = (screen.height) ? (screen.height-heightY)/2 : 0;

	popthis = window.open(url,name,"width="+ widthX +",height="+ heightY +",left="+ posX +",top="+ posY +",resizable="+ resz);
}

function singletag(text, unique_nr)
{
	if(active_color_field == 'border'){
		document.forms['table'].elements['bordercolor'].value  = text;
		document.forms['table'].elements['bordercolor'].focus();
	}
	else{
		document.forms['table'].elements['bgcolor'].value  = text;
		document.forms['table'].elements['bgcolor'].focus();
	}
}

function insert_color(referer)
{
	active_color_field = referer;

	para = window.opener.conf[<!--{unique_nr}-->].handle_http_path +''+ window.opener.conf[<!--{unique_nr}-->].handle_file;
	para += '?gp_action=make_popup_color&gp_color_opener=color&gp_lang='+ window.opener.conf[<!--{unique_nr}-->].language;
	para += '&gp_tpl_set='+ window.opener.conf[<!--{unique_nr}-->].templateset;
	para += '&gp_unique_nr=<!--{unique_nr}-->&'+ window.opener.conf[<!--{unique_nr}-->].ext_para_str;

	popup(para , 'color', '320', '230', 'no');
}

function insert_table()
{
	var cellpadding = '';
	var cellspacing = '';
	var width = '';
	var height = '';
	var border = '';
	var align = '';
	var bordercolor = '';
	var bgcolor = '';
	var inner = '';

	rows = document.forms['table'].elements['rows'].value ? parseInt(document.forms['table'].elements['rows'].value) : 2;
	cols = document.forms['table'].elements['cols'].value ? parseInt(document.forms['table'].elements['cols'].value) : 2;

	if(document.forms['table'].elements['cellpadding'].value != ''){
		cellpadding = ' CELLPADDING = "'+ document.forms['table'].elements['cellpadding'].value + '"';
	}
	if(document.forms['table'].elements['cellspacing'].value != ''){
		cellspacing = ' CELLSPACING = "'+ document.forms['table'].elements['cellspacing'].value + '"';
	}
	if(document.forms['table'].elements['width'].value != ''){
		width = ' WIDTH = "'+ document.forms['table'].elements['width'].value + '' + document.forms['table'].elements['width_type'].value + '"';
	}
	if(document.forms['table'].elements['height'].value != ''){
		height = ' HEIGHT = "'+ document.forms['table'].elements['height'].value + '' + document.forms['table'].elements['height_type'].value + '"';
	}
	if(document.forms['table'].elements['border'].value != ''){
		border = ' BORDER = "'+ document.forms['table'].elements['border'].value + '"';
	}
	if(document.forms['table'].elements['align'].value != ''){
		align = ' ALIGN = "'+ document.forms['table'].elements['align'].value + '"';
	}
	if(document.forms['table'].elements['bordercolor'].value != ''){
		bordercolor = ' BORDERCOLOR = "'+ document.forms['table'].elements['bordercolor'].value + '"';
	}
	if(document.forms['table'].elements['bgcolor'].value != ''){
		bgcolor = ' BGCOLOR = "'+ document.forms['table'].elements['bgcolor'].value + '"';
	}


	for (var i = 0; i < rows; i++)
	{
		inner += '   <TR>\n';

		for (var j = 0; j < cols; j++)
		{
			inner += '      <TD></TD>\n';
		}

		inner += '   </TR>\n';
	}

	text = '<TABLE ' + align + '' + width + '' + height + '' + border + '' + bordercolor + '' + bgcolor + '' + cellpadding;
	text += cellspacing + '>\n';
	text += inner;
	text += '</TABLE>';


	window.opener.singletag(text, <!--{unique_nr}-->);
	window.close();
}
-->
</script>

<body class="dialogstyle" onload="this.focus()">

<div align="center">
<table cellpadding="5" cellspacing="0" border="0" width="100%">

<form name="table" onsubmit ="return false;">

<tr>
	<td><!--{lan_pop_table_rows}--></td>
	<td colspan ="2"><input type="text" name="rows" value="2" size="3" ONKEYDOWN="return validate_number(event)"></td>
</tr>


<tr>
	<td><!--{lan_pop_table_cols}--></td>
	<td colspan ="2"><input type="text" name="cols" value="2" size="3" ONKEYDOWN="return validate_number(event)"></td>
</tr>


<tr>
	<td><!--{lan_pop_table_cellpadding}--></td>
	<td colspan ="2"><input type="text" name="cellpadding" value="" size="3" ONKEYDOWN="return validate_number(event)"></td>
</tr>


<tr>
	<td><!--{lan_pop_table_cellspacing}--></td>
	<td colspan ="2"><input type="text" name="cellspacing" value="" size="3" ONKEYDOWN="return validate_number(event)"></td>
</tr>


<tr>
	<td><!--{lan_pop_table_width}--></td>
	<td colspan ="2"><input type="text" name="width" value="" size="3" ONKEYDOWN="return validate_number(event)">
	<select name="width_type">
		<option value=""><!--{lan_pop_table_pixel}--></option>
		<option value="%"><!--{lan_pop_table_percent}--></option>
	</select>
	</td>
</tr>


<tr>
	<td><!--{lan_pop_table_height}--></td>
	<td colspan ="2"><input type="text" name="height" value="" size="3" ONKEYDOWN="return validate_number(event)">
	<select name="height_type">
		<option value=""><!--{lan_pop_table_pixel}--></option>
		<option value="%"><!--{lan_pop_table_percent}--></option>
	</select>
	</td>
</tr>


<tr>
	<td><!--{lan_pop_table_border}--></td>
	<td colspan ="2"><input type="text" name="border" value="0" size="3" ONKEYDOWN="return validate_number(event)"></td>
</tr>


<tr>
	<td><!--{lan_pop_table_align}--></td>
	<td colspan ="2">
		<select name="align">
		<option value=""><!--{lan_pop_table_default}--></option>
		<option value="left"><!--{lan_pop_table_left}--></option>
		<option value="center"><!--{lan_pop_table_center}--></option>
		<option value="right"><!--{lan_pop_table_right}--></option>
		</select>
	</td>
</tr>


<tr>
	<td><!--{lan_pop_table_bordercolor}--></td>
	<td><input type="text" name="bordercolor" value="" size="8"></td>
	<td>
		<table width="23"><tr>
			<td width = "23" bgcolor = "#EFEFEF" class="xpbtn" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">
			<script language="JavaScript">document.writeln('<input type="image" title ="<!--{lan_pop_table_chose_bordercolor}-->" src="'+ window.opener.conf[<!--{unique_nr}-->].handle_http_path + '' + window.opener.conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_colorchoser&gp_tpl_set=' + window.opener.conf[<!--{unique_nr}-->].templateset + '&' + window.opener.conf[<!--{unique_nr}-->].ext_para_str +'" onclick="insert_color(\'border\')">');</script>

		</tr></td></table>
	</td>
</tr>


<tr>
	<td><!--{lan_pop_table_bgcolor}--></td>
	<td><input type="text" name="bgcolor" value="" size="8"></td>
	<td>
		<table width="23"><tr>
			<td width = "23" bgcolor = "#EFEFEF" class="xpbtn" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">
			<script language="JavaScript">document.writeln('<input type="image" title ="<!--{lan_pop_table_chose_bgcolor}-->" src="'+ window.opener.conf[<!--{unique_nr}-->].handle_http_path + '' + window.opener.conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_colorchoser&gp_tpl_set=' + window.opener.conf[<!--{unique_nr}-->].templateset + '&' + window.opener.conf[<!--{unique_nr}-->].ext_para_str +'" onclick="insert_color(\'bg\')">');</script>
		</tr></td></table>
	</td>
</tr>


</table>
</div>
<br>

<div align ="center">
	<input type="button" name="insert" value="<!--{lan_pop_table_insert}-->" onClick="javascript:insert_table();window.opener.focus();">
	<input type="button" name="cancel" value="<!--{lan_pop_table_abort}-->" onClick="javascript:self.close();window.opener.focus();">
</div>
</form>
</body>
</html>