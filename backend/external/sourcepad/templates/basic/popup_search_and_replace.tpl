<html>
<head>
<title><!--{lan_pop_sr_title}--></title>
<script language="JavaScript">
<!--
document.writeln('<link rel="stylesheet" href="' + window.opener.conf[<!--{unique_nr}-->].handle_http_path + '' + window.opener.conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_css&gp_tpl_set=' + window.opener.conf[<!--{unique_nr}-->].templateset + '&' + window.opener.conf[<!--{unique_nr}-->].ext_para_str +'" type="text/css">')

function search()
{
	str = this.gp.search_string.value;

	if(str != ""){
		window.opener.check_search_string_temp(str);
		window.opener.search_and_replace('search', str, '', <!--{unique_nr}-->);
		self.focus();
	}
	else{
		alert("<!--{lan_pop_sr_no_searchstring}-->");
	}
}

function replace()
{
	str = this.gp.search_string.value;
	repl= this.gp.replace_string.value;

	if(str != "" && repl != ""){
		window.opener.check_search_string_temp(str);
		window.opener.search_and_replace('replace', str, repl, <!--{unique_nr}-->);
		self.focus();
	}
	else if(str == ""){
		alert("<!--{lan_pop_sr_no_searchstring}-->");
	}
	else{
		alert("<!--{lan_pop_sr_no_replacestring}-->");
	}
}

function replace_all()
{
	str = this.gp.search_string.value;
	repl= this.gp.replace_string.value;

	if(str != "" && repl != ""){
		window.opener.check_search_string_temp(str);
		window.opener.search_and_replace('replace_all', str, repl, <!--{unique_nr}-->);
		self.focus();
	}
	else if(str == ""){
		alert("<!--{lan_pop_sr_no_searchstring}-->");
	}
	else{
		alert("<!--{lan_pop_sr_no_replacestring}-->");
	}
}



//-->
</script>
</head>
<body class="dialogstyle" onLoad = "javascript:this.gp.search_string.focus();">

<form name="gp" onsubmit="return false">
<table>
<tr>
	<td>
		<!--{lan_pop_sr_search}-->
	</td>
	<td>
		<input type="text" size="20"  name="search_string">
	</td>
	<td width="100%">
		<input type="Submit" onClick="javascript:search()" value ="<!--{lan_pop_sr_start_search}-->" class="btn_large">
	</td>

</tr>

<tr>
	<td>
		<!--{lan_pop_sr_replace}-->
	</td>
	<td>
		<input type="text" size="20"  name="replace_string">
	</td>
	<td>
		<input type="Submit" onClick="javascript:replace()" value ="<!--{lan_pop_sr_start_replace}-->" class="btn_large">
	</td>

</tr>

<tr>
	<td>

	</td>
	<td>

	</td>
	<td>
		<input type="Submit" onClick="javascript:replace_all()" value ="<!--{lan_pop_sr_replace_all}-->" class="btn_large">
	</td>

</tr>


<tr>
	<td>

	</td>
	<td>

	</td>
	<td>
		<input type="Submit" onClick="javascript:window.close();window.opener.focus();" value ="<!--{lan_pop_sr_abort}-->" class="btn_large">
	</td>

</tr>

</table>


</form>

</body>
</html>
