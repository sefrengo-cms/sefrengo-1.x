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
         <!--[mysql_title]-->
        </h2>
        <p>
         <!--[mysql_title_text]-->
        </p>
      </div>
      <form action ="index.php" method="post" autocomplete="off">
            <p class="fehl <!--{connection_class}-->"><!--{connection_error}--></p>
        <div id="content">
          <p>
            <label for="host"><!--[mysql_host]--></label><br />
            <input class="breit" type="text" name="host" id="host" value="<!--{host}-->" size="10" onfocus="this.style.backgroundColor='#FFF5CE'" onblur="this.style.backgroundColor='#FFFFFF'" />
          </p>
          <p>
            <label for="db"><!--[mysql_db]--></label><br />
            <input class="breit" type="text" name="db" id="db" value="<!--{db}-->" size="10" onfocus="this.style.backgroundColor='#FFF5CE'" onblur="this.style.backgroundColor='#FFFFFF'" />
          </p>
          <p>
            <label for="prefix"><!--[mysql_prefix]--></label><br />
            <input class="breit" type="text" name="prefix" id="prefix" value="<!--{prefix}-->" size="10" onfocus="this.style.backgroundColor='#FFF5CE'" onblur="this.style.backgroundColor='#FFFFFF'" />
          </p>
          <p class="floatl">
            <label for="user"><!--[mysql_user]--></label><br />
            <input class="breit" type="text" name="user" id="user" value="<!--{user}-->" size="10" onfocus="this.style.backgroundColor='#FFF5CE'" onblur="this.style.backgroundColor='#FFFFFF'" />
          </p>
          <p class="floatr">
            <label for="pass"><!--[mysql_pass]--></label><br />
            <input class="breit" type="password" name="pass" id="pass" value="<!--{pass}-->" size="10" onfocus="this.style.backgroundColor='#FFF5CE'" onblur="this.style.backgroundColor='#FFFFFF'" />
          </p>
          <br style="clear:both;" />
        </div>
        <div id="navi">
          <p class="buttons">
            <input type ="hidden" name = "root_path" value="<!--{root_path}-->" />
            <input type ="hidden" name = "root_http_path" value="<!--{root_http_path}-->" />
            <input type ="hidden" name = "root_full_http_path" value="<!--{root_full_http_path}-->" />
            <input type ="hidden" name = "action" value="<!--{next_step}-->" />
            <input type ="hidden" name = "lang" value="<!--{lang}-->" />
            <input type ="hidden" name = "sql_target" value="<!--{sql_target}-->" />
            <input type ="hidden" name = "mode" value="<!--{mode}-->" />
            <input type="hidden" name="adminpass" value="<!--{adminpass}-->" />
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