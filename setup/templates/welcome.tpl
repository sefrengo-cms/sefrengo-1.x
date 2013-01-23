<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>
      Sefrengo CMS Setup - Willkommen
    </title>
    <link rel="stylesheet" href="templates/css/setup.css" type="text/css" />
    <script type="text/javascript" src="templates/css/check.js"></script>
  </head>
  <body>
    <noscript>
      <p class="nojs">
       Bitte aktivieren Sie Javascript in Ihrem Browser um das Setup fortsetzen
       zu k&#246;nnen!
      </p>
    </noscript>
    <h1 class="hide">
     Sefrengo <!--{version}--> Setup
    </h1>
    <div id="wrapper">
      <form action="index.php" method="post">
        <div id="content" class="splash">
          <p>
           <!--[welcome_text]-->
          </p>
        </div>
        <div id="navi">
          <p class="buttons">
          <select name="lang">
          <option value="de">Deutsch</option>
          </select>
            <input type="hidden" name="action" value="<!--{next_step}-->" />
            <input class="button" type="submit" name="submit" id="submit" value="<!--[next]-->  &raquo;" accesskey="w" onfocus="this.className ='button on'" onmouseover="this.className ='button on'"  onblur="this.className ='button'" onmouseout="this.className='button'" disabled="disabled" />
          </p>
          <p class="copyright">
           Sefrengo <!--{version}--> | &copy; www.sefrengo.org
          </p>
        </div>
      </form>
    </div>
  </body>
</html>