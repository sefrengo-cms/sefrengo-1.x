<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" xmlns="http://www.w3.org/1999/xhtml"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" xmlns="http://www.w3.org/1999/xhtml"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" xmlns="http://www.w3.org/1999/xhtml"> <![endif]-->
<!--[if gt IE 8]><!--> <html  class="no-js" xmlns="http://www.w3.org/1999/xhtml"> <!--<![endif]-->
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="chrome=1" />
	<meta name="robots" content="noindex, nofollow" />
	<title>Sefrengo {VERSION}</title>
	<link rel="shortcut icon" href="favicon.ico" />
	
	<link rel="stylesheet" type="text/css" href="tpl/{SKIN}/css/styles.css" />
	<link rel="stylesheet" href="tpl/{SKIN}/css/dynCalendar.css" type="text/css" />
	<!--[if lt IE 7]>
	<link rel="stylesheet" type="text/css" href="tpl/{SKIN}/css/ie.css" />
	<![endif]-->
	
	<script src="tpl/{SKIN}/js/{SKIN}.js" type="text/javascript"></script>
	<script src="tpl/{SKIN}/js/tabpane.js" type="text/javascript"></script>
	
	<script type="text/javascript" src="tpl/{SKIN}/js/jquery/init.sefrengo.js"></script>
	<script type="text/javascript" src="tpl/{SKIN}/js/jquery/lib/jquery.min.js"></script>
	<script type="text/javascript">
		var $jqsf = jQuery;
		//var $jqsf = $.noConflict(true); // currently the jQuery plugins won't work in this mode 

		SF.Config.backend_dir = '{BACKEND_DIR}'; // e.g. /backend/
		SF.Config.js_dir = 'tpl/{SKIN}/js/';
		SF.Config.css_dir = 'tpl/{SKIN}/css/';
		SF.Config.img_dir = 'tpl/{SKIN}/img/';
	</script>
	<script type="text/javascript" src="tpl/{SKIN}/js/jquery/jquery.sefrengo.js"></script>

	<script type="text/javascript">
	<!--
	function delete_confirm() {
		if(confirm('{DELETE_MSG}')) return true;
		else return false;
	}
	//-->
	</script>
</head>
<body onload="{ONLOAD_FUNCTION}return true;">
	<div id="sf_overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
	<div id="header">
		<p id="logout" class="headerlink">{LOGGED_USER}
			( <a href="{LOGOUT_URL}" target="_top">{LOGOUT_WIDTH}</a> )
		</p>
		
		<p id="license" class="headerlink">
			<a href="http://www.sefrengo.org/" target="_blank">Sefrengo {VERSION}</a> | <a href="license.html" target="_blank">{LNG_LICENSE}</a>
		</p>
		<!-- BEGIN CLIENT-LANG-SELECT -->
		<div id="clientlang">

			<!-- BEGIN CLIENT-LANG-SELECT_CLIENT-SELECT -->		
				<form name="clientform" id="clientform" method="post" action="{FORM-ACTION}" target="_top">
					<input type="hidden" name="changeclient" value="1" />
					<input type="hidden" name="change_show_tree" value="0" />
					<!-- BEGIN CLIENT-LANG-SELECT_CLIENT-SELECT_HIDDEN-FIELDS -->		
					<input type="hidden" name="{FIELD-NAME}" value="{FIELD-VALUE}" />
					<!-- END CLIENT-LANG-SELECT_CLIENT-SELECT_HIDDEN-FIELDS -->	
					<select name="client" size="1" onchange="clientform.submit()">"
					<!-- BEGIN CLIENT-LANG-SELECT_CLIENT-SELECT_ENTRY -->
						<option value="{FIELD-VALUE}" {FIELD-SELECTED}>{FIELD-TITLE}</option>
					<!-- END CLIENT-LANG-SELECT_CLIENT-SELECT_ENTRY -->
					</select>
				</form>
			<!-- END CLIENT-LANG-SELECT_CLIENT-SELECT -->		

			<!-- BEGIN CLIENT-LANG-SELECT_LANG-SELECT -->		
				<form name="languageform" id="languageform" method="post" action="{FORM-ACTION}" target="_top">
					<!-- BEGIN CLIENT-LANG-SELECT_LANG-SELECT_HIDDEN-FIELDS -->		
					<input type="hidden" name="{FIELD-NAME}" value="{FIELD-VALUE}" />
					<!-- END CLIENT-LANG-SELECT_LANG-SELECT_HIDDEN-FIELDS -->	
					<select name="lang" size="1" onchange="languageform.submit()">"
					<!-- BEGIN CLIENT-LANG-SELECT_LANG-SELECT_ENTRY -->
						<option value="{FIELD-VALUE}" {FIELD-SELECTED}>{FIELD-TITLE}</option>
					<!-- END CLIENT-LANG-SELECT_LANG-SELECT_ENTRY -->
					</select>
				</form>
			<!-- END CLIENT-LANG-SELECT_LANG-SELECT -->		
			
		</div>
		<!-- END CLIENT-LANG-SELECT -->
		
		<!-- BEGIN MAINMENU -->
		<ul id="mainmenu" class="clearfix">
			<!-- BEGIN MAINMENU_ENTRY -->
			<li {ITEM-CLASS}><a href="{LINK-HREF}" {LINK-CLASS}>{LINK-TITLE}</a>
				<!-- BEGIN SUBMENU -->
				<ul class="clearfix" id="sub-{COUNT}">
					<!-- BEGIN SUBMENU_ENTRY -->
					<li {ITEM-CLASS}><a href="{LINK-HREF}" target="_top" {LINK-CLASS}>{LINK-TITLE}</a></li>
					<!-- END SUBMENU_ENTRY -->
				</ul>
				<!-- END SUBMENU -->
			</li>
			<!-- END MAINMENU_ENTRY -->
		</ul>
		<!-- END MAINMENU -->
	</div>

