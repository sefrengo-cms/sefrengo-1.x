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
    <script type="text/javascript" src="templates/css/check2.js"></script>
  </head>
  <body>
    <h1 class="hide">
     Sefrengo <!--{version}--> Setup
     </h1>
    <div id="wrapper">
      <div id="header">
        <h2>
         <!--[license_title]-->
        </h2>
          <p>
           <!--[license_title_text]-->
          </p>
       </div>
        <div id="content">
          <form name="gpl" action="index.php" method="post">
          <p>
            <textarea name="licence" readonly="readonly" class="licence"><!--{licence}--></textarea>
            <input type="checkbox" name="ckeck" id="accept" value="true" onclick="sf_EnableButtons(document.gpl.ckeck.checked)" />
            <label for="accept" class="agree">
             <!--[license_accept]-->
            </label>
          </p>
          </form>
        </div>
        <form action ="index.php" method="post">
        <div id="navi">
          <p class="buttons">
            <input type ="hidden" name = "action" value="<!--{next_step}-->" />
            <input type ="hidden" name = "lang" value="<!--{lang}-->" />
            <input class="button" type="button" name="back" value="&laquo; <!--[back]-->" accesskey="z" onclick="javascript:history.back()" onfocus="this.className ='button on'" onmouseover="this.className ='button on'"  onblur="this.className ='button'" onmouseout="this.className='button'" />
            <input class="button" type="submit" name="submit" id="submit" value="<!--[next]-->  &raquo;" accesskey="w" onfocus="this.className ='button on'" onmouseover="this.className ='button on'"  onblur="this.className ='button'" onmouseout="this.className='button'" disabled="disabled"/>
          </p>
          <p class="copyright">
           Sefrengo <!--{version}--> | &copy; www.sefrengo.org
          </p>
        </div>
      </form>
    </div>
  </body>
</html>