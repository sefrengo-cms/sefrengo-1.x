<html>
<head>
<title><!--{lan_colorchose}--></title>
<script language="JavaScript">
<!--
var color_opener= "<!--{color_opener}-->";
document.writeln('<link rel="stylesheet" href="' + window.opener.conf[<!--{unique_nr}-->].handle_http_path + '' + window.opener.conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_css&gp_tpl_set=' + window.opener.conf[<!--{unique_nr}-->].templateset + '&' + window.opener.conf[<!--{unique_nr}-->].ext_para_str +'" type="text/css">')

function color_select(val)
{
	document.getElementById("final").value = val;
	document.getElementById("chose_table").bgColor  = val;
}

function color_preview(val)
{
	document.getElementById("prev").value = val;
	document.getElementById("prev_table").bgColor  = val;
}

function color_insert()
{
	color = document.getElementById("final").value;
	if(color != ""){
		if(color_opener == "color"){
			window.opener.singletag(color, <!--{unique_nr}-->);
		}
		else if(color_opener == "font_color"){
			window.opener.font_choser('<font color=', color,  '</font>', <!--{unique_nr}-->);
		}
		else if(color_opener == "bg_color"){
			window.opener.font_choser('<FONT style="BACKGROUND-COLOR:'+ color +';">',"", '</font>', <!--{unique_nr}-->);
		}

		window.close();
		window.opener.focus();
	}
	else{
		alert("<!--{lan_mes_no_color}-->")
	}
}

function make_image()
{
document.write('<img usemap="#colmap" src="'+ window.opener.conf[<!--{unique_nr}-->].handle_http_path + '' + window.opener.conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_colortable&gp_tpl_set=' + window.opener.conf[<!--{unique_nr}-->].templateset + '&' + window.opener.conf[<!--{unique_nr}-->].ext_para_str +'" border="0" width="289" height="67">');
}

function make_blind_gif()
{
document.write('<img src="'+ window.opener.conf[<!--{unique_nr}-->].handle_http_path + '' + window.opener.conf[<!--{unique_nr}-->].handle_file +'?gp_action=make_image&gp_image_name=gp_blind_toolpic&gp_tpl_set=' + window.opener.conf[<!--{unique_nr}-->].templateset + '&' + window.opener.conf[<!--{unique_nr}-->].ext_para_str +'" border="0" width="23" height="22">');
}

// -->
</script>
</head>

<body onLoad="this.focus();" class="dialogstyle">
<div align="center"><b><!--{lan_opener_txt}--></b></div>
<table width="100%">

<form name="colorform">

<tr>
	<td align="center">
		<script language="JavaScript">make_image()</script>
		<map name="colmap">
			<area shape="rect" coords="1,1,7,10" href="javascript:color_select('#00FF00')" onmouseover="javascript:color_preview('#00FF00')">
			<area shape="rect" coords="9,1,15,10" href="javascript:color_select('#00FF33')" onmouseover="javascript:color_preview('#00FF33')">
			<area shape="rect" coords="17,1,23,10" href="javascript:color_select('#00FF66')" onmouseover="javascript:color_preview('#00FF66')">
			<area shape="rect" coords="25,1,31,10" href="javascript:color_select('#00FF99')" onmouseover="javascript:color_preview('#00FF99')">
			<area shape="rect" coords="33,1,39,10" href="javascript:color_select('#00FFCC')" onmouseover="javascript:color_preview('#00FFCC')">
			<area shape="rect" coords="41,1,47,10" href="javascript:color_select('#00FFFF')" onmouseover="javascript:color_preview('#00FFFF')">
			<area shape="rect" coords="49,1,55,10" href="javascript:color_select('#33FF00')" onmouseover="javascript:color_preview('#33FF00')">
			<area shape="rect" coords="57,1,63,10" href="javascript:color_select('#33FF33')" onmouseover="javascript:color_preview('#33FF33')">
			<area shape="rect" coords="65,1,71,10" href="javascript:color_select('#33FF66')" onmouseover="javascript:color_preview('#33FF66')">
			<area shape="rect" coords="73,1,79,10" href="javascript:color_select('#33FF99')" onmouseover="javascript:color_preview('#33FF99')">
			<area shape="rect" coords="81,1,87,10" href="javascript:color_select('#33FFCC')" onmouseover="javascript:color_preview('#33FFCC')">
			<area shape="rect" coords="89,1,95,10" href="javascript:color_select('#33FFFF')" onmouseover="javascript:color_preview('#33FFFF')">
			<area shape="rect" coords="97,1,103,10" href="javascript:color_select('#66FF00')" onmouseover="javascript:color_preview('#66FF00')">
			<area shape="rect" coords="105,1,111,10" href="javascript:color_select('#66FF33')" onmouseover="javascript:color_preview('#66FF33')">
			<area shape="rect" coords="113,1,119,10" href="javascript:color_select('#66FF66')" onmouseover="javascript:color_preview('#66FF66')">
			<area shape="rect" coords="121,1,127,10" href="javascript:color_select('#66FF99')" onmouseover="javascript:color_preview('#66FF99')">
			<area shape="rect" coords="129,1,135,10" href="javascript:color_select('#66FFCC')" onmouseover="javascript:color_preview('#66FFCC')">
			<area shape="rect" coords="137,1,143,10" href="javascript:color_select('#66FFFF')" onmouseover="javascript:color_preview('#66FFFF')">
			<area shape="rect" coords="145,1,151,10" href="javascript:color_select('#99FF00')" onmouseover="javascript:color_preview('#99FF00')">
			<area shape="rect" coords="153,1,159,10" href="javascript:color_select('#99FF33')" onmouseover="javascript:color_preview('#99FF33')">
			<area shape="rect" coords="161,1,167,10" href="javascript:color_select('#99FF66')" onmouseover="javascript:color_preview('#99FF66')">
			<area shape="rect" coords="169,1,175,10" href="javascript:color_select('#99FF99')" onmouseover="javascript:color_preview('#99FF99')">
			<area shape="rect" coords="177,1,183,10" href="javascript:color_select('#99FFCC')" onmouseover="javascript:color_preview('#99FFCC')">
			<area shape="rect" coords="185,1,191,10" href="javascript:color_select('#99FFFF')" onmouseover="javascript:color_preview('#99FFFF')">
			<area shape="rect" coords="193,1,199,10" href="javascript:color_select('#CCFF00')" onmouseover="javascript:color_preview('#CCFF00')">
			<area shape="rect" coords="201,1,207,10" href="javascript:color_select('#CCFF33')" onmouseover="javascript:color_preview('#CCFF33')">
			<area shape="rect" coords="209,1,215,10" href="javascript:color_select('#CCFF66')" onmouseover="javascript:color_preview('#CCFF66')">
			<area shape="rect" coords="217,1,223,10" href="javascript:color_select('#CCFF99')" onmouseover="javascript:color_preview('#CCFF99')">
			<area shape="rect" coords="225,1,231,10" href="javascript:color_select('#CCFFCC')" onmouseover="javascript:color_preview('#CCFFCC')">
			<area shape="rect" coords="233,1,239,10" href="javascript:color_select('#CCFFFF')" onmouseover="javascript:color_preview('#CCFFFF')">
			<area shape="rect" coords="241,1,247,10" href="javascript:color_select('#FFFF00')" onmouseover="javascript:color_preview('#FFFF00')">
			<area shape="rect" coords="249,1,255,10" href="javascript:color_select('#FFFF33')" onmouseover="javascript:color_preview('#FFFF33')">
			<area shape="rect" coords="257,1,263,10" href="javascript:color_select('#FFFF66')" onmouseover="javascript:color_preview('#FFFF66')">
			<area shape="rect" coords="265,1,271,10" href="javascript:color_select('#FFFF99')" onmouseover="javascript:color_preview('#FFFF99')">
			<area shape="rect" coords="273,1,279,10" href="javascript:color_select('#FFFFCC')" onmouseover="javascript:color_preview('#FFFFCC')">
			<area shape="rect" coords="281,1,287,10" href="javascript:color_select('#FFFFFF')" onmouseover="javascript:color_preview('#FFFFFF')">
			<area shape="rect" coords="1,12,7,21" href="javascript:color_select('#00CC00')" onmouseover="javascript:color_preview('#00CC00')">
			<area shape="rect" coords="9,12,15,21" href="javascript:color_select('#00CC33')" onmouseover="javascript:color_preview('#00CC33')">
			<area shape="rect" coords="17,12,23,21" href="javascript:color_select('#00CC66')" onmouseover="javascript:color_preview('#00CC66')">
			<area shape="rect" coords="25,12,31,21" href="javascript:color_select('#00CC99')" onmouseover="javascript:color_preview('#00CC99')">
			<area shape="rect" coords="33,12,39,21" href="javascript:color_select('#00CCCC')" onmouseover="javascript:color_preview('#00CCCC')">
			<area shape="rect" coords="41,12,47,21" href="javascript:color_select('#00CCFF')" onmouseover="javascript:color_preview('#00CCFF')">
			<area shape="rect" coords="49,12,55,21" href="javascript:color_select('#33CC00')" onmouseover="javascript:color_preview('#33CC00')">
			<area shape="rect" coords="57,12,63,21" href="javascript:color_select('#33CC33')" onmouseover="javascript:color_preview('#33CC33')">
			<area shape="rect" coords="65,12,71,21" href="javascript:color_select('#33CC66')" onmouseover="javascript:color_preview('#33CC66')">
			<area shape="rect" coords="73,12,79,21" href="javascript:color_select('#33CC99')" onmouseover="javascript:color_preview('#33CC99')">
			<area shape="rect" coords="81,12,87,21" href="javascript:color_select('#33CCCC')" onmouseover="javascript:color_preview('#33CCCC')">
			<area shape="rect" coords="89,12,95,21" href="javascript:color_select('#33CCFF')" onmouseover="javascript:color_preview('#33CCFF')">
			<area shape="rect" coords="97,12,103,21" href="javascript:color_select('#66CC00')" onmouseover="javascript:color_preview('#66CC00')">
			<area shape="rect" coords="105,12,111,21" href="javascript:color_select('#66CC33')" onmouseover="javascript:color_preview('#66CC33')">
			<area shape="rect" coords="113,12,119,21" href="javascript:color_select('#66CC66')" onmouseover="javascript:color_preview('#66CC66')">
			<area shape="rect" coords="121,12,127,21" href="javascript:color_select('#66CC99')" onmouseover="javascript:color_preview('#66CC99')">
			<area shape="rect" coords="129,12,135,21" href="javascript:color_select('#66CCCC')" onmouseover="javascript:color_preview('#66CCCC')">
			<area shape="rect" coords="137,12,143,21" href="javascript:color_select('#66CCFF')" onmouseover="javascript:color_preview('#66CCFF')">
			<area shape="rect" coords="145,12,151,21" href="javascript:color_select('#99CC00')" onmouseover="javascript:color_preview('#99CC00')">
			<area shape="rect" coords="153,12,159,21" href="javascript:color_select('#99CC33')" onmouseover="javascript:color_preview('#99CC33')">
			<area shape="rect" coords="161,12,167,21" href="javascript:color_select('#99CC66')" onmouseover="javascript:color_preview('#99CC66')">
			<area shape="rect" coords="169,12,175,21" href="javascript:color_select('#99CC99')" onmouseover="javascript:color_preview('#99CC99')">
			<area shape="rect" coords="177,12,183,21" href="javascript:color_select('#99CCCC')" onmouseover="javascript:color_preview('#99CCCC')">
			<area shape="rect" coords="185,12,191,21" href="javascript:color_select('#99CCFF')" onmouseover="javascript:color_preview('#99CCFF')">
			<area shape="rect" coords="193,12,199,21" href="javascript:color_select('#CCCC00')" onmouseover="javascript:color_preview('#CCCC00')">
			<area shape="rect" coords="201,12,207,21" href="javascript:color_select('#CCCC33')" onmouseover="javascript:color_preview('#CCCC33')">
			<area shape="rect" coords="209,12,215,21" href="javascript:color_select('#CCCC66')" onmouseover="javascript:color_preview('#CCCC66')">
			<area shape="rect" coords="217,12,223,21" href="javascript:color_select('#CCCC99')" onmouseover="javascript:color_preview('#CCCC99')">
			<area shape="rect" coords="225,12,231,21" href="javascript:color_select('#CCCCCC')" onmouseover="javascript:color_preview('#CCCCCC')">
			<area shape="rect" coords="233,12,239,21" href="javascript:color_select('#CCCCFF')" onmouseover="javascript:color_preview('#CCCCFF')">
			<area shape="rect" coords="241,12,247,21" href="javascript:color_select('#FFCC00')" onmouseover="javascript:color_preview('#FFCC00')">
			<area shape="rect" coords="249,12,255,21" href="javascript:color_select('#FFCC33')" onmouseover="javascript:color_preview('#FFCC33')">
			<area shape="rect" coords="257,12,263,21" href="javascript:color_select('#FFCC66')" onmouseover="javascript:color_preview('#FFCC66')">
			<area shape="rect" coords="265,12,271,21" href="javascript:color_select('#FFCC99')" onmouseover="javascript:color_preview('#FFCC99')">
			<area shape="rect" coords="273,12,279,21" href="javascript:color_select('#FFCCCC')" onmouseover="javascript:color_preview('#FFCCCC')">
			<area shape="rect" coords="281,12,287,21" href="javascript:color_select('#FFCCFF')" onmouseover="javascript:color_preview('#FFCCFF')">
			<area shape="rect" coords="1,23,7,32" href="javascript:color_select('#009900')" onmouseover="javascript:color_preview('#009900')">
			<area shape="rect" coords="9,23,15,32" href="javascript:color_select('#009933')" onmouseover="javascript:color_preview('#009933')">
			<area shape="rect" coords="17,23,23,32" href="javascript:color_select('#009966')" onmouseover="javascript:color_preview('#009966')">
			<area shape="rect" coords="25,23,31,32" href="javascript:color_select('#009999')" onmouseover="javascript:color_preview('#009999')">
			<area shape="rect" coords="33,23,39,32" href="javascript:color_select('#0099CC')" onmouseover="javascript:color_preview('#0099CC')">
			<area shape="rect" coords="41,23,47,32" href="javascript:color_select('#0099FF')" onmouseover="javascript:color_preview('#0099FF')">
			<area shape="rect" coords="49,23,55,32" href="javascript:color_select('#339900')" onmouseover="javascript:color_preview('#339900')">
			<area shape="rect" coords="57,23,63,32" href="javascript:color_select('#339933')" onmouseover="javascript:color_preview('#339933')">
			<area shape="rect" coords="65,23,71,32" href="javascript:color_select('#339966')" onmouseover="javascript:color_preview('#339966')">
			<area shape="rect" coords="73,23,79,32" href="javascript:color_select('#339999')" onmouseover="javascript:color_preview('#339999')">
			<area shape="rect" coords="81,23,87,32" href="javascript:color_select('#3399CC')" onmouseover="javascript:color_preview('#3399CC')">
			<area shape="rect" coords="89,23,95,32" href="javascript:color_select('#3399FF')" onmouseover="javascript:color_preview('#3399FF')">
			<area shape="rect" coords="97,23,103,32" href="javascript:color_select('#669900')" onmouseover="javascript:color_preview('#669900')">
			<area shape="rect" coords="105,23,111,32" href="javascript:color_select('#669933')" onmouseover="javascript:color_preview('#669933')">
			<area shape="rect" coords="113,23,119,32" href="javascript:color_select('#669966')" onmouseover="javascript:color_preview('#669966')">
			<area shape="rect" coords="121,23,127,32" href="javascript:color_select('#669999')" onmouseover="javascript:color_preview('#669999')">
			<area shape="rect" coords="129,23,135,32" href="javascript:color_select('#6699CC')" onmouseover="javascript:color_preview('#6699CC')">
			<area shape="rect" coords="137,23,143,32" href="javascript:color_select('#6699FF')" onmouseover="javascript:color_preview('#6699FF')">
			<area shape="rect" coords="145,23,151,32" href="javascript:color_select('#999900')" onmouseover="javascript:color_preview('#999900')">
			<area shape="rect" coords="153,23,159,32" href="javascript:color_select('#999933')" onmouseover="javascript:color_preview('#999933')">
			<area shape="rect" coords="161,23,167,32" href="javascript:color_select('#999966')" onmouseover="javascript:color_preview('#999966')">
			<area shape="rect" coords="169,23,175,32" href="javascript:color_select('#999999')" onmouseover="javascript:color_preview('#999999')">
			<area shape="rect" coords="177,23,183,32" href="javascript:color_select('#9999CC')" onmouseover="javascript:color_preview('#9999CC')">
			<area shape="rect" coords="185,23,191,32" href="javascript:color_select('#9999FF')" onmouseover="javascript:color_preview('#9999FF')">
			<area shape="rect" coords="193,23,199,32" href="javascript:color_select('#CC9900')" onmouseover="javascript:color_preview('#CC9900')">
			<area shape="rect" coords="201,23,207,32" href="javascript:color_select('#CC9933')" onmouseover="javascript:color_preview('#CC9933')">
			<area shape="rect" coords="209,23,215,32" href="javascript:color_select('#CC9966')" onmouseover="javascript:color_preview('#CC9966')">
			<area shape="rect" coords="217,23,223,32" href="javascript:color_select('#CC9999')" onmouseover="javascript:color_preview('#CC9999')">
			<area shape="rect" coords="225,23,231,32" href="javascript:color_select('#CC99CC')" onmouseover="javascript:color_preview('#CC99CC')">
			<area shape="rect" coords="233,23,239,32" href="javascript:color_select('#CC99FF')" onmouseover="javascript:color_preview('#CC99FF')">
			<area shape="rect" coords="241,23,247,32" href="javascript:color_select('#FF9900')" onmouseover="javascript:color_preview('#FF9900')">
			<area shape="rect" coords="249,23,255,32" href="javascript:color_select('#FF9933')" onmouseover="javascript:color_preview('#FF9933')">
			<area shape="rect" coords="257,23,263,32" href="javascript:color_select('#FF9966')" onmouseover="javascript:color_preview('#FF9966')">
			<area shape="rect" coords="265,23,271,32" href="javascript:color_select('#FF9999')" onmouseover="javascript:color_preview('#FF9999')">
			<area shape="rect" coords="273,23,279,32" href="javascript:color_select('#FF99CC')" onmouseover="javascript:color_preview('#FF99CC')">
			<area shape="rect" coords="281,23,287,32" href="javascript:color_select('#FF99FF')" onmouseover="javascript:color_preview('#FF99FF')">
			<area shape="rect" coords="1,34,7,43" href="javascript:color_select('#006600')" onmouseover="javascript:color_preview('#006600')">
			<area shape="rect" coords="9,34,15,43" href="javascript:color_select('#006633')" onmouseover="javascript:color_preview('#006633')">
			<area shape="rect" coords="17,34,23,43" href="javascript:color_select('#006666')" onmouseover="javascript:color_preview('#006666')">
			<area shape="rect" coords="25,34,31,43" href="javascript:color_select('#006699')" onmouseover="javascript:color_preview('#006699')">
			<area shape="rect" coords="33,34,39,43" href="javascript:color_select('#0066CC')" onmouseover="javascript:color_preview('#0066CC')">
			<area shape="rect" coords="41,34,47,43" href="javascript:color_select('#0066FF')" onmouseover="javascript:color_preview('#0066FF')">
			<area shape="rect" coords="49,34,55,43" href="javascript:color_select('#336600')" onmouseover="javascript:color_preview('#336600')">
			<area shape="rect" coords="57,34,63,43" href="javascript:color_select('#336633')" onmouseover="javascript:color_preview('#336633')">
			<area shape="rect" coords="65,34,71,43" href="javascript:color_select('#336666')" onmouseover="javascript:color_preview('#336666')">
			<area shape="rect" coords="73,34,79,43" href="javascript:color_select('#336699')" onmouseover="javascript:color_preview('#336699')">
			<area shape="rect" coords="81,34,87,43" href="javascript:color_select('#3366CC')" onmouseover="javascript:color_preview('#3366CC')">
			<area shape="rect" coords="89,34,95,43" href="javascript:color_select('#3366FF')" onmouseover="javascript:color_preview('#3366FF')">
			<area shape="rect" coords="97,34,103,43" href="javascript:color_select('#666600')" onmouseover="javascript:color_preview('#666600')">
			<area shape="rect" coords="105,34,111,43" href="javascript:color_select('#666633')" onmouseover="javascript:color_preview('#666633')">
			<area shape="rect" coords="113,34,119,43" href="javascript:color_select('#666666')" onmouseover="javascript:color_preview('#666666')">
			<area shape="rect" coords="121,34,127,43" href="javascript:color_select('#666699')" onmouseover="javascript:color_preview('#666699')">
			<area shape="rect" coords="129,34,135,43" href="javascript:color_select('#6666CC')" onmouseover="javascript:color_preview('#6666CC')">
			<area shape="rect" coords="137,34,143,43" href="javascript:color_select('#6666FF')" onmouseover="javascript:color_preview('#6666FF')">
			<area shape="rect" coords="145,34,151,43" href="javascript:color_select('#996600')" onmouseover="javascript:color_preview('#996600')">
			<area shape="rect" coords="153,34,159,43" href="javascript:color_select('#996633')" onmouseover="javascript:color_preview('#996633')">
			<area shape="rect" coords="161,34,167,43" href="javascript:color_select('#996666')" onmouseover="javascript:color_preview('#996666')">
			<area shape="rect" coords="169,34,175,43" href="javascript:color_select('#996699')" onmouseover="javascript:color_preview('#996699')">
			<area shape="rect" coords="177,34,183,43" href="javascript:color_select('#9966CC')" onmouseover="javascript:color_preview('#9966CC')">
			<area shape="rect" coords="185,34,191,43" href="javascript:color_select('#9966FF')" onmouseover="javascript:color_preview('#9966FF')">
			<area shape="rect" coords="193,34,199,43" href="javascript:color_select('#CC6600')" onmouseover="javascript:color_preview('#CC6600')">
			<area shape="rect" coords="201,34,207,43" href="javascript:color_select('#CC6633')" onmouseover="javascript:color_preview('#CC6633')">
			<area shape="rect" coords="209,34,215,43" href="javascript:color_select('#CC6666')" onmouseover="javascript:color_preview('#CC6666')">
			<area shape="rect" coords="217,34,223,43" href="javascript:color_select('#CC6699')" onmouseover="javascript:color_preview('#CC6699')">
			<area shape="rect" coords="225,34,231,43" href="javascript:color_select('#CC66CC')" onmouseover="javascript:color_preview('#CC66CC')">
			<area shape="rect" coords="233,34,239,43" href="javascript:color_select('#CC66FF')" onmouseover="javascript:color_preview('#CC66FF')">
			<area shape="rect" coords="241,34,247,43" href="javascript:color_select('#FF6600')" onmouseover="javascript:color_preview('#FF6600')">
			<area shape="rect" coords="249,34,255,43" href="javascript:color_select('#FF6633')" onmouseover="javascript:color_preview('#FF6633')">
			<area shape="rect" coords="257,34,263,43" href="javascript:color_select('#FF6666')" onmouseover="javascript:color_preview('#FF6666')">
			<area shape="rect" coords="265,34,271,43" href="javascript:color_select('#FF6699')" onmouseover="javascript:color_preview('#FF6699')">
			<area shape="rect" coords="273,34,279,43" href="javascript:color_select('#FF66CC')" onmouseover="javascript:color_preview('#FF66CC')">
			<area shape="rect" coords="281,34,287,43" href="javascript:color_select('#FF66FF')" onmouseover="javascript:color_preview('#FF66FF')">
			<area shape="rect" coords="1,45,7,54" href="javascript:color_select('#003300')" onmouseover="javascript:color_preview('#003300')">
			<area shape="rect" coords="9,45,15,54" href="javascript:color_select('#003333')" onmouseover="javascript:color_preview('#003333')">
			<area shape="rect" coords="17,45,23,54" href="javascript:color_select('#003366')" onmouseover="javascript:color_preview('#003366')">
			<area shape="rect" coords="25,45,31,54" href="javascript:color_select('#003399')" onmouseover="javascript:color_preview('#003399')">
			<area shape="rect" coords="33,45,39,54" href="javascript:color_select('#0033CC')" onmouseover="javascript:color_preview('#0033CC')">
			<area shape="rect" coords="41,45,47,54" href="javascript:color_select('#0033FF')" onmouseover="javascript:color_preview('#0033FF')">
			<area shape="rect" coords="49,45,55,54" href="javascript:color_select('#333300')" onmouseover="javascript:color_preview('#333300')">
			<area shape="rect" coords="57,45,63,54" href="javascript:color_select('#333333')" onmouseover="javascript:color_preview('#333333')">
			<area shape="rect" coords="65,45,71,54" href="javascript:color_select('#333366')" onmouseover="javascript:color_preview('#333366')">
			<area shape="rect" coords="73,45,79,54" href="javascript:color_select('#333399')" onmouseover="javascript:color_preview('#333399')">
			<area shape="rect" coords="81,45,87,54" href="javascript:color_select('#3333CC')" onmouseover="javascript:color_preview('#3333CC')">
			<area shape="rect" coords="89,45,95,54" href="javascript:color_select('#3333FF')" onmouseover="javascript:color_preview('#3333FF')">
			<area shape="rect" coords="97,45,103,54" href="javascript:color_select('#663300')" onmouseover="javascript:color_preview('#663300')">
			<area shape="rect" coords="105,45,111,54" href="javascript:color_select('#663333')" onmouseover="javascript:color_preview('#663333')">
			<area shape="rect" coords="113,45,119,54" href="javascript:color_select('#663366')" onmouseover="javascript:color_preview('#663366')">
			<area shape="rect" coords="121,45,127,54" href="javascript:color_select('#663399')" onmouseover="javascript:color_preview('#663399')">
			<area shape="rect" coords="129,45,135,54" href="javascript:color_select('#6633CC')" onmouseover="javascript:color_preview('#6633CC')">
			<area shape="rect" coords="137,45,143,54" href="javascript:color_select('#6633FF')" onmouseover="javascript:color_preview('#6633FF')">
			<area shape="rect" coords="145,45,151,54" href="javascript:color_select('#993300')" onmouseover="javascript:color_preview('#993300')">
			<area shape="rect" coords="153,45,159,54" href="javascript:color_select('#993333')" onmouseover="javascript:color_preview('#993333')">
			<area shape="rect" coords="161,45,167,54" href="javascript:color_select('#993366')" onmouseover="javascript:color_preview('#993366')">
			<area shape="rect" coords="169,45,175,54" href="javascript:color_select('#993399')" onmouseover="javascript:color_preview('#993399')">
			<area shape="rect" coords="177,45,183,54" href="javascript:color_select('#9933CC')" onmouseover="javascript:color_preview('#9933CC')">
			<area shape="rect" coords="185,45,191,54" href="javascript:color_select('#9933FF')" onmouseover="javascript:color_preview('#9933FF')">
			<area shape="rect" coords="193,45,199,54" href="javascript:color_select('#CC3300')" onmouseover="javascript:color_preview('#CC3300')">
			<area shape="rect" coords="201,45,207,54" href="javascript:color_select('#CC3333')" onmouseover="javascript:color_preview('#CC3333')">
			<area shape="rect" coords="209,45,215,54" href="javascript:color_select('#CC3366')" onmouseover="javascript:color_preview('#CC3366')">
			<area shape="rect" coords="217,45,223,54" href="javascript:color_select('#CC3399')" onmouseover="javascript:color_preview('#CC3399')">
			<area shape="rect" coords="225,45,231,54" href="javascript:color_select('#CC33CC')" onmouseover="javascript:color_preview('#CC33CC')">
			<area shape="rect" coords="233,45,239,54" href="javascript:color_select('#CC33FF')" onmouseover="javascript:color_preview('#CC33FF')">
			<area shape="rect" coords="241,45,247,54" href="javascript:color_select('#FF3300')" onmouseover="javascript:color_preview('#FF3300')">
			<area shape="rect" coords="249,45,255,54" href="javascript:color_select('#FF3333')" onmouseover="javascript:color_preview('#FF3333')">
			<area shape="rect" coords="257,45,263,54" href="javascript:color_select('#FF3366')" onmouseover="javascript:color_preview('#FF3366')">
			<area shape="rect" coords="265,45,271,54" href="javascript:color_select('#FF3399')" onmouseover="javascript:color_preview('#FF3399')">
			<area shape="rect" coords="273,45,279,54" href="javascript:color_select('#FF33CC')" onmouseover="javascript:color_preview('#FF33CC')">
			<area shape="rect" coords="281,45,287,54" href="javascript:color_select('#FF33FF')" onmouseover="javascript:color_preview('#FF33FF')">
			<area shape="rect" coords="1,56,7,65" href="javascript:color_select('#000000')" onmouseover="javascript:color_preview('#000000')">
			<area shape="rect" coords="9,56,15,65" href="javascript:color_select('#000033')" onmouseover="javascript:color_preview('#000033')">
			<area shape="rect" coords="17,56,23,65" href="javascript:color_select('#000066')" onmouseover="javascript:color_preview('#000066')">
			<area shape="rect" coords="25,56,31,65" href="javascript:color_select('#000099')" onmouseover="javascript:color_preview('#000099')">
			<area shape="rect" coords="33,56,39,65" href="javascript:color_select('#0000CC')" onmouseover="javascript:color_preview('#0000CC')">
			<area shape="rect" coords="41,56,47,65" href="javascript:color_select('#0000FF')" onmouseover="javascript:color_preview('#0000FF')">
			<area shape="rect" coords="49,56,55,65" href="javascript:color_select('#330000')" onmouseover="javascript:color_preview('#330000')">
			<area shape="rect" coords="57,56,63,65" href="javascript:color_select('#330033')" onmouseover="javascript:color_preview('#330033')">
			<area shape="rect" coords="65,56,71,65" href="javascript:color_select('#330066')" onmouseover="javascript:color_preview('#330066')">
			<area shape="rect" coords="73,56,79,65" href="javascript:color_select('#330099')" onmouseover="javascript:color_preview('#330099')">
			<area shape="rect" coords="81,56,87,65" href="javascript:color_select('#3300CC')" onmouseover="javascript:color_preview('#3300CC')">
			<area shape="rect" coords="89,56,95,65" href="javascript:color_select('#3300FF')" onmouseover="javascript:color_preview('#3300FF')">
			<area shape="rect" coords="97,56,103,65" href="javascript:color_select('#660000')" onmouseover="javascript:color_preview('#660000')">
			<area shape="rect" coords="105,56,111,65" href="javascript:color_select('#660033')" onmouseover="javascript:color_preview('#660033')">
			<area shape="rect" coords="113,56,119,65" href="javascript:color_select('#660066')" onmouseover="javascript:color_preview('#660066')">
			<area shape="rect" coords="121,56,127,65" href="javascript:color_select('#660099')" onmouseover="javascript:color_preview('#660099')">
			<area shape="rect" coords="129,56,135,65" href="javascript:color_select('#6600CC')" onmouseover="javascript:color_preview('#6600CC')">
			<area shape="rect" coords="137,56,143,65" href="javascript:color_select('#6600FF')" onmouseover="javascript:color_preview('#6600FF')">
			<area shape="rect" coords="145,56,151,65" href="javascript:color_select('#990000')" onmouseover="javascript:color_preview('#990000')">
			<area shape="rect" coords="153,56,159,65" href="javascript:color_select('#990033')" onmouseover="javascript:color_preview('#990033')">
			<area shape="rect" coords="161,56,167,65" href="javascript:color_select('#990066')" onmouseover="javascript:color_preview('#990066')">
			<area shape="rect" coords="169,56,175,65" href="javascript:color_select('#990099')" onmouseover="javascript:color_preview('#990099')">
			<area shape="rect" coords="177,56,183,65" href="javascript:color_select('#9900CC')" onmouseover="javascript:color_preview('#9900CC')">
			<area shape="rect" coords="185,56,191,65" href="javascript:color_select('#9900FF')" onmouseover="javascript:color_preview('#9900FF')">
			<area shape="rect" coords="193,56,199,65" href="javascript:color_select('#CC0000')" onmouseover="javascript:color_preview('#CC0000')">
			<area shape="rect" coords="201,56,207,65" href="javascript:color_select('#CC0033')" onmouseover="javascript:color_preview('#CC0033')">
			<area shape="rect" coords="209,56,215,65" href="javascript:color_select('#CC0066')" onmouseover="javascript:color_preview('#CC0066')">
			<area shape="rect" coords="217,56,223,65" href="javascript:color_select('#CC0099')" onmouseover="javascript:color_preview('#CC0099')">
			<area shape="rect" coords="225,56,231,65" href="javascript:color_select('#CC00CC')" onmouseover="javascript:color_preview('#CC00CC')">
			<area shape="rect" coords="233,56,239,65" href="javascript:color_select('#CC00FF')" onmouseover="javascript:color_preview('#CC00FF')">
			<area shape="rect" coords="241,56,247,65" href="javascript:color_select('#FF0000')" onmouseover="javascript:color_preview('#FF0000')">
			<area shape="rect" coords="249,56,255,65" href="javascript:color_select('#FF0033')" onmouseover="javascript:color_preview('#FF0033')">
			<area shape="rect" coords="257,56,263,65" href="javascript:color_select('#FF0066')" onmouseover="javascript:color_preview('#FF0066')">
			<area shape="rect" coords="265,56,271,65" href="javascript:color_select('#FF0099')" onmouseover="javascript:color_preview('#FF0099')">
			<area shape="rect" coords="273,56,279,65" href="javascript:color_select('#FF00CC')" onmouseover="javascript:color_preview('#FF00CC')">
			<area shape="rect" coords="281,56,287,65" href="javascript:color_select('#FF00FF')" onmouseover="javascript:color_preview('#FF00FF')">
		</map>

	</td>


</tr>

</table>
<div align="center">
<table>
<tr>
<td style="text-align:right;">
<b><!--{lan_preview_color}--></b>
</td>
<td>
</td>
<td>
</td>
<td>
<b><!--{lan_chose_color}--></b>
</td>
</tr>

<tr>
<td>
<input type="text" id="prev" size="10" value="" readonly style="text-align:right;background:buttonface;border-style:none">
</td>
<td>
<table id="prev_table" style="border-style:solid;border-width:1px;border-color:#000000;"><tr><td><script language="JavaScript">make_blind_gif()</script></td></tr></table>
</td>
<td>
<table id="chose_table" style="border-style:solid;border-width:1px;border-color:#000000;"><tr><td><script language="JavaScript">make_blind_gif()</script></td></tr></table>
</td>
<td>
<input type="text" id="final" size="10" value="" readonly style="background:buttonface;border-style:none">
</td>
</tr>
</table>
</div>
<br>
<div align="center">
<input type="button" value=" <!--{lan_insert}--> " onClick="javascript:color_insert();">
<input type="button" value=" <!--{lan_abort}--> " onClick="javascript:self.close();window.opener.focus();">
</div>

</form>


</body>
</html>