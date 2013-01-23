<?xml version="1.0" encoding="utf-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="robots" content="noindex, nofollow" />
  <title>Sefrengo {VERSION}</title>
  <link rel="stylesheet" type="text/css" href="tpl/standard/css/styles.css" />
  <link rel="stylesheet" href="tpl/standard/css/dynCalendar.css" type="text/css" />
  <!--[if lt IE 7]>
  <link rel="stylesheet" type="text/css" href="tpl/standard/css/ie.css" />
  <![endif]-->
  <script src="tpl/standard/js/standard.js" type="text/javascript"></script>
  <script src="tpl/standard/js/tabpane.js" type="text/javascript"></script>
  <script type="text/javascript" src="tpl/standard/js/overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
  <script type="text/javascript" src="tpl/standard/js/overlib_hideform.js"></script>
  <script type="text/javascript" src="tpl/standard/js/overlib_draggable.js"></script>
  <script type="text/javascript" src="tpl/standard/js/overlib_positioncap.js"></script>
  <link rel="shortcut icon" href="favicon.ico" />
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
<p id="version" class="lizenz">
<a href="http://www.sefrengo.org/" target="_blank">Sefrengo V {VERSION}</a> | <a href="license.html" target="_blank">Lizenz</a>
</p>
<!-- BEGIN CLIENT_LANG_SELECT -->
<div class="forms">
{CLIENT_FORM}
{LANG_FORM}
</div><!-- END CLIENT_LANG_SELECT -->
<p class="logout">{LOGGED_USER}
( <a href="{LOGOUT_URL}" target="_top">{LOGOUT_WIDTH}</a> )
</p>
<div id="menu-layer0" class="clearfix">
{MAIN_MENU_ENTRYS}
</div>
<!-- BEGIN SUBMENU -->
<div id="menu_layer{COUNT}" class="menu-akt">
<p>
{SUB_MENU_ENTRYS}
</p>
</div>
<!-- END SUBMENU -->
<script type="text/javascript">
  max_subs = {MAX_SUBMENUS};
  con_layer('{ACTIVE_SUBMENU_LAYER}');
</script>
</div>
<!-- Ende header.tpl -->
