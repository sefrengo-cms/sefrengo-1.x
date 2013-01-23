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
         <!--[insert_title]-->
        </h2>
          <p>
           <!--[insert_title_text]-->
          </p>
      </div>
      <form action ="index.php" method="post">
        <div id="content">
          <p>
            <img src="templates/img/ok.gif" name="ok" title="ok" />
            <!--[insert_text]-->
          </p>
        </div>
        <div id="navi">
          <p class="buttons">
          <input type="hidden" name="host" value="<!--{host}-->" />
          <input type="hidden" name="db" value="<!--{db}-->" />
          <input type="hidden" name="prefix" value="<!--{prefix}-->" />
          <input type="hidden" name="user" value="<!--{user}-->" />
          <input type="hidden" name="pass" value="<!--{pass}-->" />
          <input type="hidden" name="adminpass" value="<!--{adminpass}-->" />
          <input type="hidden" name="root_path" value="<!--{root_path}-->" />
          <input type="hidden" name="root_http_path" value="<!--{root_http_path}-->" />
          <input type="hidden" name="root_full_http_path" value="<!--{root_full_http_path}-->" />
          <input type="hidden" name="sql_target" value="<!--{sql_target}-->" />
          <input type="hidden" name="mode" value="<!--{mode}-->" />
          <input type="hidden" name="action" value="<!--{next_step}-->" />
          <input type="hidden" name="lang" value="<!--{lang}-->" />
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