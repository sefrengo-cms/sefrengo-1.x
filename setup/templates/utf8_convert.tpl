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
         <!--[convert_title]-->
        </h2>
        <p>
         <!--[convert_title_text]-->
        </p>
      </div>
      <div id="content">
        <p>
         <!--[convert_text]-->
        </p>         
        <div class="dbx-group" id="purple2">
           <h3>Status der Konvertierung</h3>
           <div class="dbx-box">
       <iframe src="<!--{converter_url}-->" name="u8conv" id="u8conv" width="432" height="40" scrolling="no" ></iframe>
           </div>
        </div>
        <p>
         <!--[convert_text_2]-->
        </p>
      </div>
      <form action ="index.php" method="post">
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