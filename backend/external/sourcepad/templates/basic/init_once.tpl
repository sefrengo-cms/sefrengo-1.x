<!-- Edit: mvsxyz -> Nicht type="text/css" sondern type="text/html" -->
<link rel="stylesheet" href="<!--{handle_http_path}--><!--{handle_file}-->?gp_action=make_css&gp_tpl_set=<!--{templateset}-->&<!--{ext_para_str}-->" type="text/html">

<script language="JavaScript" type="text/javascript">
<!--

function browserCheck()
{
	this.agt = navigator.userAgent.toLowerCase();
	this.clientPC = navigator.userAgent.toLowerCase();
	this.clientVer = parseInt(navigator.appVersion);

    this.is_major = parseInt(navigator.appVersion);

	this.is_ie = ((this.clientPC.indexOf("msie") != -1) && (this.clientPC.indexOf("opera") == -1));
    this.is_ie3    = (this.is_ie && (this.is_major < 4));
    this.is_ie4    = (this.is_ie && (this.is_major == 4) && (this.agt.indexOf("msie 4")!=-1) );
    this.is_ie5    = (this.is_ie && (this.is_major == 4) && (this.agt.indexOf("msie 5.0")!=-1) );
    this.is_ie5up  = (this.is_ie && !this.is_ie3 && !this.is_ie4);
    this.is_ie5_5up =(this.is_ie && !this.is_ie3 && !this.is_ie4 && !this.is_ie5);
	this.is_nav  = ((this.clientPC.indexOf('mozilla')!=-1) && (this.clientPC.indexOf('spoofer')==-1)
                && (this.clientPC.indexOf('compatible') == -1) && (this.clientPC.indexOf('opera')==-1)
                && (this.clientPC.indexOf('webtv')==-1) && (this.clientPC.indexOf('hotjava')==-1));
	this.is_opera = (this.agt.indexOf("opera") != -1);


	this.is_win = ((this.clientPC.indexOf("win")!=-1) || (this.clientPC.indexOf("16bit") != -1));
	this.is_linux = (this.agt.indexOf("inux")!=-1);
	this.is_mac = (this.clientPC.indexOf("mac")!=-1);

	this.is_compatible = true ; //(this.is_ie || this.is_nav);

	return this;
}

//init browserdetectionobject
var browser = new browserCheck();

//init array for the texteditorSetup object
var conf = new Array();

//init undo functions (IE)
if(browser.is_ie){
	undo_container = new Array;
	undo_timer_new = new Array;
	undo_timer_diff = new Array;
	undo_timer_old = new Array;
	undo_container_index = new Array;
	undo_max = new Array;
	undo_start = new Array;
	undo_limit = new Array;
	undo_loop = new Array;
}

//Make all text unselectable because there are some
//problems with the textrange object(IE). Works with IE >= 5.5
if(browser.is_ie5_5up){
	for (i=0; i<document.all.length; i++)
	{
	    document.all(i).unselectable = "<!--{unselectable}-->";
	}
}

//Load the Java_script Functions for the editor

//Begin: mvsxyz
document.writeln('<script src="<!--{handle_http_path}--><!--{handle_file}-->?gp_action=make_js_functions&gp_lang=<!--{language}-->&gp_tpl_set=<!--{templateset}-->&<!--{ext_para_str}-->" type="text/javascript"></'+'script>');
//End: mvsxyz

-->
</script>
