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
    <script type="text/javascript">
<!--
function sf_reload() {
    var element = document.getElementById("action");
    element.value="screen_test";
    document.Daten.action="index.php";
    document.Daten.submit.click();
}
function sf_back() {
    var element = document.getElementById("action");
    element.value="screen_finish";
    document.Daten.action="index.php";
    document.Daten.submit.click();
}
//-->
</script>
  </head>
  <body>
    <h1 class="hide">
     Sefrengo <!--{version}--> Setup
    </h1>
    <div id="wrapper">
      <div id="header">
        <h2>
         <!--[test_title]-->
        </h2>
        <p>
         <!--[test_title_text]-->
        </p>
      </div>
      <div id="content">
        <div class="dbx-group" id="purple1">
          <h3>
           <!--[test_folder]-->
          </h3>
          <!--{start:test_folder}--><div class="<!--{class_folder}-->">
             <div class="dbx-handle" style="cursor:auto;">
             <!--{value_folder}-->
             <!--{desc_folder}--><!--{name_folder}-->
             </div>
           </div>
        <!--{stop:test_folder}-->
        </div>
        <div class="dbx-group" id="purple2">
          <h3>
           <!--[test_config]-->
          </h3>
          <!--{start:test_configuration}--><div class="<!--{class_folder}-->">
             <div class="dbx-handle" style="cursor:auto;">
             <!--{value_configuration}-->
             <!--{name_configuration}--><!--{desc_configuration}-->
             </div>
           </div>
          <!--{stop:test_configuration}-->
        </div>
        <p>
          <!--[test_text]-->
        </p>
      </div>
      <form name="Daten" action ="../backend/main.php" method="get">
         <div id="navi">
           <p class="buttons">
            <input type="hidden" name="action" id="action" value="" />
            <input type="hidden" name="lang" value="<!--{lang}-->" />
            <input class="button" type="button" name="back" value="&laquo; <!--[back]-->" accesskey="z" onclick="javascript:sf_back()" onfocus="this.className ='button on'" onmouseover="this.className ='button on'"  onblur="this.className ='button'" onmouseout="this.className='button'" />
            <input class="button" type="submit" name="submit" value="Zum Login &raquo;" accesskey="w" onfocus="this.className ='button on'" onmouseover="this.className ='button on'"  onblur="this.className ='button'" onmouseout="this.className='button'" />
           </p>
           <p class="copyright">
             Sefrengo <!--{version}--> | &copy; www.sefrengo.org
           </p>
        </div>
      </form>
    </div>
  </body>
</html>