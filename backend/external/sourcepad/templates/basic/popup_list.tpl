<html>
<head>
<title><!--{lan_pop_ls_title}--></title>
<script language="JavaScript">

document.writeln('<link rel="stylesheet" href="' + window.opener.conf[<!--{unique_nr}-->].handle_http_path + '' + window.opener.conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_css&gp_tpl_set=' + window.opener.conf[<!--{unique_nr}-->].templateset + '&' + window.opener.conf[<!--{unique_nr}-->].ext_para_str +'" type="text/css">')

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

function validate_return(evt)
{
  var keyCode = evt.which ? evt.which : evt.keyCode;

  if(keyCode == 13){
  	add_item(document.ls.ls_v.value);
  	return false;
  }
}


function add_item(newItem)
{
	if (document.ls.ls_v.value != "") {
		new_item = new Option(document.ls.ls_v.value, document.ls.ls_v.value, false, true);
		document.ls.final_list.options[document.ls.final_list.options.length] = new_item;
		document.ls.ls_v.value = "";
		document.ls.ls_v.focus();
	}
	else {
		alert("<!--{lan_pop_ls_no_new_item}-->");
	}
}

function update_item(new_value)
{
	if (document.ls.ls_v.value != "") {
		document.ls.final_list.options[document.ls.final_list.selectedIndex].value = new_value;
		document.ls.final_list.options[document.ls.final_list.selectedIndex].text = new_value;
	}
	else {
		alert("<!--{lan_pop_ls_nothing_to_update}-->");
	}
}



function up_down(direction)
{
	list_container = document.ls.final_list;
	source  = list_container.selectedIndex;

	if (direction == "up")
		destination = list_container.selectedIndex - 1;
	else if (direction == "down")
		destination = list_container.selectedIndex + 1;

	if ( list_container.selectedIndex != -1 ) {
		if ( ( list_container.selectedIndex > 0 && list_container.selectedIndex < list_container.length - 1 ) ||
			( list_container.selectedIndex == 0 && direction != "up") ||
			 ( list_container.selectedIndex == list_container.length - 1 && direction != "down" ) ) {
			tempText   = list_container[destination].text;
			tempValue  = list_container[destination].value;

			list_container.options[destination].text  = list_container[source].text;
			list_container.options[destination].value = list_container[source].value;

			list_container.options[source].text = tempText;
			list_container.options[source].value = tempValue;

			list_container.focus();
			list_container.selectedIndex = destination;
			list_container[destination].selected = true;
		}
	}
}

function delete_item()
{
	remove = document.ls.final_list.selectedIndex;
	document.ls.final_list.options[remove] = null;
}

function makeSelection()
{
	theIndex = document.ls.final_list.selectedIndex;
	theValue = document.ls.final_list[theIndex].value;
	document.ls.ls_v.value = theValue;
}

function make_list() {
	d = document.ls;

	if (d.final_list.options.length == 0) {
		alert('<!--{lan_pop_ls_no_ls}-->');
		return false;
	}

	if (d.list_type.value == '0') {
		list_container = "<ul>\n";
	}
	if (d.list_type.value == '1') {
		list_container = "<ul type=\"circle\">\n";
	}
	if (d.list_type.value == '2') {
		list_container = "<ul type=\"square\">\n";
	}
	if (d.list_type.value == '3') {
		list_container = "<ol type=\"1\">\n";
	}
	if (d.list_type.value == '4') {
		list_container = "<ol type=\"A\">\n";
	}
	if (d.list_type.value == '5') {
		list_container = "<ol type=\"a\">\n";
	}
	if (d.list_type.value == '6') {
		list_container = "<ol type=\"I\">\n";
	}
	if (d.list_type.value == '7') {
		list_container = "<ol type=\"i\">\n";
	}

	for (i = 0; i < d.final_list.options.length; i++) {
		list_container += "   <li>" + d.final_list.options[i].text + "</li>\n";
	}

	if (d.list_type.value == '0' || d.list_type.value == '1' || d.list_type.value == '2') {
		list_container += "</ul>\n";
	}
	else{
		list_container += "</ol>\n";
	}

	return list_container;
}

function preview_list() {
	list_container = make_list();
	if (list_container != false) {
		list_p = window.open("<!--{lan_pop_ls_preview_window}-->", "list_preview");
		list_p.document.open();
		list_p.document.write(list_container);
		list_p.document.close();
	}
}


function insert_list() {
	list_container = make_list();
	if (list_container != false) {
		window.opener.singletag(list_container, <!--{unique_nr}-->)
		self.close();
	}
}



</script>
</head>

<body class="dialogstyle" onload="this.focus();">


<table cellpadding="3" cellspacing="0" border="0" width="100%">

<form name="ls" onsubmit="return false;">


<tr>
	<td><!--{lan_pop_ls_listtype}--></td>
	<td>
		<select name="list_type" style="width: 100%;">
		<option value="0"><!--{lan_pop_ls_full_circle}--></option>
		<option value="1"><!--{lan_pop_ls_circle}--></option>
		<option value="2"><!--{lan_pop_ls_rectangle}--></option>
		<option value="3">1, 2, 3...</option>
		<option value="4">A, B, C...</option>
		<option value="5">a, b, c...</option>
		<option value="6">I, II, III...</option>
		<option value="7">i, ii, iii...</option>
		</select>
	</td>
	<td><input type="button" name="new" style="width:100%;" value="<!--{lan_pop_ls_insert}-->" onclick="add_item(document.ls.ls_v.value);return false;">
</tr>

<tr>
	<td valign = "top"><!--{lan_pop_ls_input}--></td>
	<td valign="top"><input type="text" name="ls_v" onkeydown="return validate_return(event);" value ="" size="29"></td>
	<td><input type="button" name="update" style="width:100%;" value="<!--{lan_pop_ls_update_ls}-->" onclick="javascript:update_item(document.ls.ls_v.value);"></td>
</tr>


<tr>
	<td colspan="3"><hr></td>
</tr>

<tr>
	<td valign="top"><!--{lan_pop_ls_ls}--></td>
	<td width="200">
		<select size="8" name="final_list" onchange="javascript:makeSelection();" onclick="javascript:makeSelection();" style="width:100%;"></select>
	</td>
	<td>
		<div align ="center">
	 	<table>
	 		<tr bgcolor ="#CFCFCF">
				<td bgcolor = "#EFEFEF" class="xpbtn" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">
				<script language="JavaScript">document.writeln('<input type="image" title ="<!--{lan_pop_ls_move_up}-->" src="'+ window.opener.conf[1].handle_http_path + '' + window.opener.conf[1].handle_file +'?gp_action=make_image&gp_image_name=gp_arrow_up&gp_tpl_set=' + window.opener.conf[1].templateset + '&' + window.opener.conf[1].ext_para_str +'" onclick="up_down(\'up\');return false">');</script>
				</td>
			</tr>
	 		<tr bgcolor ="#CFCFCF">
				<td bgcolor = "#EFEFEF" class="xpbtn" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">
				<script language="JavaScript">document.writeln('<input type="image" title ="<!--{lan_pop_ls_delete}-->" src="'+ window.opener.conf[1].handle_http_path + '' + window.opener.conf[1].handle_file +'?gp_action=make_image&gp_image_name=gp_delete&gp_tpl_set=' + window.opener.conf[1].templateset + '&' + window.opener.conf[1].ext_para_str +'" onclick="delete_item();return false">');</script>
				</td>
			</tr>
	 		<tr bgcolor ="#CFCFCF">
				<td bgcolor = "#EFEFEF" class="xpbtn" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">
				<script language="JavaScript">document.writeln('<input type="image" title ="<!--{lan_pop_ls_move_down}-->" src="'+ window.opener.conf[1].handle_http_path + '' + window.opener.conf[1].handle_file +'?gp_action=make_image&gp_image_name=gp_arrow_down&gp_tpl_set=' + window.opener.conf[1].templateset + '&' + window.opener.conf[1].ext_para_str +'" onclick="up_down(\'down\');return false">');</script>
				</td>
			</tr>
		</table>
		</div>



	</td>
</tr>


<tr>
	<td align="center" colspan="3">
		<br>
		<input type="button" name="insert" value="<!--{lan_pop_ls_preview}-->" onClick="javascript:preview_list();">
		<input type="button" name="insert" value="<!--{lan_pop_ls_insert_final}-->" onClick="javascript:insert_list();">
		<input type="button" name="cancel" value="<!--{lan_pop_ls_abort}-->" onClick="javascript:self.close();">
	</td>
</tr>

</table>
</form>

</body>
</html>