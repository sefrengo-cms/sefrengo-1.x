<html>
<head>
<title><!--{lan_pop_link_title}--></title>
<script language="JavaScript">
<!--
document.writeln('<link rel="stylesheet" href="' + window.opener.conf[<!--{unique_nr}-->].handle_http_path + '' + window.opener.conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_css&gp_tpl_set=' + window.opener.conf[<!--{unique_nr}-->].templateset + '&' + window.opener.conf[<!--{unique_nr}-->].ext_para_str +'" type="text/css">')

function preview(){
	text = make_link();
	text = '<html><head></head><body><br><br><div align ="center">'+ text +'<div></body></html>';
	prev = window.open("", "link_vorschau");
	prev.document.open();
	prev.document.write(text);
	prev.document.close();
	prev.document.focus();
}


function einfuegen(){
	text = make_link();
	window.opener.singletag(text, <!--{unique_nr}-->);
	window.close();
}

function make_link()
{
	link_pre = window.document.gp.url_pre.value;
	linkurl  = window.document.gp.link_url.value;
	linkname = window.document.gp.link_name.value;
	if(window.document.gp.target.value == "gp_custom"){
		if(window.document.gp.custom_target.value != ""){
			targ ='target="'+ window.document.gp.custom_target.value +'" ';
		}
		else{
			targ="";
		}
	}
	else{
		if(window.document.gp.target.value != ""){
			targ ='target="'+ window.document.gp.target.value +'" ';
		}
		else{
			targ="";
		}
	}

	final_link ='<a href="'+ link_pre + linkurl +'" ' + targ +'>'+ linkname +'</a>';
	return final_link;
}

function check_custom(target)
{
	if (target == 'gp_custom') {
		window.document.gp.custom_target.style.visibility = 'visible';
		window.document.gp.custom_target.focus();
	}
	else{
		window.document.gp.custom_target.style.visibility = 'hidden';
	}
}

//-->
</script>
</head>
<body class="dialogstyle" onLoad = "javascript:window.document.gp.link_name.focus();">

<form name="gp" onSubmit ="javascript:return false;">
<table>
<tr>
	<td>
		Name:
	</td>
	<td>
		<input type="text" size="30"  name="link_name">
	</td>
</tr>
<tr>
	<td valign="top">
		<!--{lan_pop_link_url}-->
	</td>
	<td>
		<select name="url_pre">
		<option value="">[<!--{lan_pop_link_other}-->]</option>
		<option selected value="http://">http://</option>
		<option value="https://">https://</option>
		<option value="mailto:">mailto:</option>
		<option value="ftp://">ftp://</option>
		<option value="news:">news:</option>
		<option value="javascript:">javascript:</option>
		<option value="gropher://">gropher://</option>
		</select><br>
		<input type="text" size="30"  name="link_url">
	</td>
</tr>
<tr>
	<td valign="top">
		<!--{lan_pop_link_target}-->
	</td>
	<td>
		<select name="target" onChange="javascript:check_custom(this.options[this.selectedIndex].value)">
		<option value="">[<!--{lan_pop_link_notarget}-->]</option>
		<option value="_self">_self</option>
		<option value="_blank">_blank</option>
		<option value="_top">_top</option>
		<option value="_parent">_parent</option>
		<option value="gp_custom"><!--{lan_pop_link_customtarget}--></option>
		</select><br>
		<input type="text" size="30" name="custom_target" style="visibility:hidden">
	</td>
</tr>
</table>

<br>

<div align="center">
	<input type="Submit" onClick="javascript:preview()" value ="<!--{lan_pop_link_preview}-->">
	<input type="Submit" onClick="javascript:einfuegen();window.opener.focus();" value ="<!--{lan_pop_link_insert}-->">
	<input type="Submit" onClick="javascript:window.close();window.opener.focus();" value ="<!--{lan_pop_link_abort}-->">
</div>
</form>

</body>
</html>
