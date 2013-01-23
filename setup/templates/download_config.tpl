<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>
      Sefrengo CMS Setup
    </title>
    <link rel="stylesheet" href="templates/css/setup.css" type="text/css" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
  </head>
  <body>
    <h1 class="hide">
     Sefrengo <!--{version}--> Setup
     </h1>
    <div id="wrapper">
      <div id="header">
        <h2>
         <!--[config_title]-->
        </h2>
        <p>
         <!--[config_title_text]-->
        </p>
      </div>
        <!--{show_txt_only_by_update}-->
        <form action ="index.php" method="post">
        <div id="content">
        <p>
        <img src="templates/img/ok.gif" name="ok" title="ok" /> <!--[config_ok]-->
        </p>
        <p>
         <!--[config_text]-->
        </p>
          <input type ="hidden" name = "host" value="<!--{host}-->" />
          <input type ="hidden" name = "db" value="<!--{db}-->" />
          <input type ="hidden" name = "prefix" value="<!--{prefix}-->" />
          <input type ="hidden" name = "user" value="<!--{user}-->" />
          <input type ="hidden" name = "pass" value="<!--{pass}-->" />
          <input type ="hidden" name = "root_path" value="<!--{root_path}-->" />
          <input type ="hidden" name = "root_http_path" value="<!--{root_http_path}-->" />
          <input type ="hidden" name = "root_full_http_path" value="<!--{root_full_http_path}-->" />
          <input type ="hidden" name = "email" value="<!--{email}-->" />
          <input type ="hidden" name = "action" value="make_cfg_general" />
          <input type ="hidden" name = "lang" value="<!--{lang}-->" />
          <input type ="hidden" name = "sql_target" value="<!--{sql_target}-->" />
          <input type ="submit" name="submit" class="button" id="input" value="> <!--[config_download]--> <" onfocus="this.className ='button on'" onmouseover="this.className ='button on'"  onblur="this.className ='button'" onmouseout="this.className='button'"/>
        </div>
      </form>
      <!-- DEBUG DONT DELETE
      <form action ="index.php" name ="insert_config_vars" method="post">
      <div>
      <input type ="hidden" name = "host" value="<!--{host}-->" />
      <input type ="hidden" name = "db" value="<!--{db}-->" />
      <input type ="hidden" name = "prefix" value="<!--{prefix}-->" />
      <input type ="hidden" name = "user" value="<!--{user}-->" />
      <input type ="hidden" name = "pass" value="<!--{pass}-->" />
      <input type ="hidden" name = "root_path" value="<!--{root_path}-->" />
      <input type ="hidden" name = "root_http_path" value="<!--{root_http_path}-->" />
      <input type ="hidden" name = "root_full_http_path" value="<!--{root_full_http_path}-->" />
      <input type ="hidden" name = "email" value="<!--{email}-->" />
      <input type ="hidden" name = "action" value="insert_config_values" />
      <input type ="hidden" name = "lang" value="<!--{lang}-->" />
      <input type ="hidden" name = "sql_target" value="<!--{sql_target}-->" />
      <input type ="submit" name="submit" value="> Zeige SQL <" />
      </div>
      -->
      <form action ="index.php" method="post">
        <div id="navi">
          <p class="buttons">
        <input type ="hidden" name = "host2" value="<!--{host}-->" />
        <input type ="hidden" name = "db" value="<!--{db}-->" />
        <input type ="hidden" name = "prefix" value="<!--{prefix}-->" />
        <input type ="hidden" name = "user" value="<!--{user}-->" />
        <input type ="hidden" name = "pass" value="<!--{pass}-->" />
        <input type ="hidden" name = "root_path" value="<!--{root_path}-->" />
        <input type ="hidden" name = "root_http_path" value="<!--{root_http_path}-->" />
        <input type ="hidden" name = "root_full_http_path" value="<!--{root_full_http_path}-->" />
        <input type ="hidden" name = "sql_target" value="<!--{sql_target}-->" />
        <input type ="hidden" name = "mode" value="<!--{mode}-->" />
        <input type ="hidden" name = "action" value="<!--{next_step}-->" />
        <input type ="hidden" name = "lang" value="<!--{lang}-->" />
        <input class="button" type="button" name="back" value="&laquo; <!--[back]-->" accesskey="z" onclick="javascript:history.back()" onfocus="this.className ='button on'" onmouseover="this.className ='button on'"  onblur="this.className ='button'" onmouseout="this.className='button'" />
        <input class="button" type="submit" name="submit" value="<!--[next]-->  &raquo;" accesskey="w" onfocus="this.className ='button on'" onmouseover="this.className ='button on'"  onblur="this.className ='button'" onmouseout="this.className='button'" />
          </p>
          <p class="copyright">
           Sefrengo <!--{version}--> | &copy; www.sefrengo.org
          </p>
        </div>
      </form>
    </div>
  </body>
</html>