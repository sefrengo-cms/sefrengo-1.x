<html>
<head>
<title><!--{lan_pop_gfx_title}--></title>
<script type="text/javascript">
document.writeln('<link rel="stylesheet" href="' + window.opener.conf[<!--{unique_nr}-->].handle_http_path + '' + window.opener.conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_css&gp_tpl_set=' + window.opener.conf[<!--{unique_nr}-->].templateset + '&' + window.opener.conf[<!--{unique_nr}-->].ext_para_str +'" type="text/css">')

function validate_number (evt)
{
  key_code = evt.which ? evt.which : evt.keyCode;

  if(key_code < 48 || key_code > 58){
 	if(key_code == 8 || key_code == 46){
 		//nix tun :)
 	}
  	else{
  		return false;
  	}

  }
}


function insert_image()
{
	var url = '';
	var alt = '';
	var width = '';
	var height = '';
	var border = '';
	var align = '';
	var vspace = '';
	var hspace = '';


	url = ' SRC = "'+ document.forms['image'].elements['url'].value + '"';

	if(document.forms['image'].elements['alt'].value != ''){
		alt = ' ALT = "'+ document.forms['image'].elements['alt'].value + '"';
	}
	if(document.forms['image'].elements['width'].value != ''){
		width = ' WIDTH = "'+ document.forms['image'].elements['width'].value + '"';
	}
	if(document.forms['image'].elements['height'].value != ''){
		height = ' HEIGHT = "'+ document.forms['image'].elements['height'].value + '"';
	}
	if(document.forms['image'].elements['border'].value != ''){
		border = ' BORDER = "'+ document.forms['image'].elements['border'].value + '"';
	}
	if(document.forms['image'].elements['align'].value != ''){
		align = ' ALIGN = "'+ document.forms['image'].elements['align'].value + '"';
	}
	if(document.forms['image'].elements['vspace'].value != ''){
		vspace = ' VSPACE = "'+ document.forms['image'].elements['vspace'].value + '"';
	}
	if(document.forms['image'].elements['hspace'].value != ''){
		hspace = ' HSPACE = "'+ document.forms['image'].elements['hspace'].value + '"';
	}


	text = '<img ' + url + '' + width + '' + height + '' + border + '' + alt + '' + align + '' + hspace;
	text += vspace + '>';


	window.opener.singletag(text, <!--{unique_nr}-->);
	window.close();
}

</script>
</head>

<body class ="dialogstyle" onLoad="this.focus()">
<form name="image" onsubmit="return false;">

<table cellpadding="5">
<tr>
	<td><!--{lan_pop_gfx_url}--></td>
	<td><input type="text" name="url" value="" size="40"></td>
</tr>

<tr>
	<td><!--{lan_pop_gfx_alt}--></td>
	<td><input type="text" name="alt" value="" size="40"></td>
</tr>

<tr>
	<td><!--{lan_pop_gfx_align}--></td>
	<td>
	<select name="align">
		<option value=""><!--{lan_pop_gfx_default}--></option>
		<option value="left"><!--{lan_pop_gfx_left}--></option>
		<option value="right"><!--{lan_pop_gfx_right}--></option>
		<option value="top"><!--{lan_pop_gfx_top}--></option>
		<option value="middle"><!--{lan_pop_gfx_middle}--></option>
		<option value="bottom"><!--{lan_pop_gfx_bottom}--></option>
	</select>
	</td>
<tr>
	<td colspan ="2">
		<div align="center">
		<!--{lan_pop_gfx_width}--> <input type="text" name="width" value="" size="3" ONKEYDOWN="return validate_number(event)">&nbsp;&nbsp;
		<!--{lan_pop_gfx_height}--> <input type="text" name="height" value="" size="3" ONKEYDOWN="return validate_number(event)">&nbsp;&nbsp;
		<!--{lan_pop_gfx_border}--> <input type="text" name="border" value="0" size="3" ONKEYDOWN="return validate_number(event)">
		</div>
	</td>
</tr>
</table>

<div align ="center">
<table cellpadding="5">
<tr>
	<td><!--{lan_pop_gfx_vspace}--></td>
	<td><input type="text" name="vspace" value="" size="3" ONKEYDOWN="return validate_number(event)"></td>
</tr>

<tr>
	<td><!--{lan_pop_gfx_hspace}--></td>
	<td><input type="text" name="hspace" value="" size="3" ONKEYDOWN="return validate_number(event)"></td>
</tr>
</table>
</div>
<br>
<div align ="center">
<input  type="button" value="<!--{lan_pop_gfx_insert}-->" onClick="insert_image();window.opener.focus();">
<input  type="button" value="<!--{lan_pop_gfx_abort}-->" onClick="window.close();window.opener.focus();"></td>
</div>

</body>
</html>
