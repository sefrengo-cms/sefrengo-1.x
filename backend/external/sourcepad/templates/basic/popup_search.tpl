<html>
<head>
<title><!--{lan_pop_search}--></title>
<script language="JavaScript">
<!--
document.writeln('<link rel="stylesheet" href="' + window.opener.conf[<!--{unique_nr}-->].handle_http_path + '' + window.opener.conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_css&gp_tpl_set=' + window.opener.conf[<!--{unique_nr}-->].templateset + '&' + window.opener.conf[<!--{unique_nr}-->].ext_para_str +'" type="text/css">')

function einfuegen()
{
	str = this.gp.search_string.value;

	if(str != ""){
		window.opener.check_search_string_temp(str);
		window.opener.search(str, <!--{unique_nr}-->);
		self.focus();
	}
	else{
		alert("<!--{lan_pop_search_no_searchstring}-->");
	}
}

function start_up()
{
this.gp.search_string.focus();
window.opener.reset_search_string_count();
}


//-->
</script>
</head>
<body class="dialogstyle" onLoad = "start_up()">

<form name="gp" onsubmit="return false">
<table>
<tr>
	<td>
		<input type="text" size="20"  name="search_string">
	</td>
	<td width="100%">
		<input type="Submit" onClick="javascript:einfuegen()" value ="<!--{lan_pop_search_start}-->" class="btn_large">
	</td>

</tr>

<tr>
	<td>

	</td>
	<td>
		<input type="Submit" onClick="javascript:window.close();window.opener.focus();" value ="<!--{lan_pop_search_abort}-->" class="btn_large">
	</td>

</tr>

</table>


</form>

</body>
</html>
