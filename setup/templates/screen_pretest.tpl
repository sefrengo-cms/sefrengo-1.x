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
    element.value="screen_pretest";
    document.Daten.submit.click();
}
function sf_back() {
    var element = document.getElementById("action");
    element.value="screen_license";
    document.Daten.submit.click();
}

function sf_switchDisplay(obj) {
	var el = document.getElementById(obj);
	if ( el.style.display != "none" ) {
		el.style.display = 'none';
	} else {
		el.style.display = '';
	}
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
         <!--[pretest_title]-->
        </h2>
          <p>
           <!--[pretest_title_text]-->
          </p>
       </div>
       <div id="content">
         <div class="dbx-group" id="purple">
           <h3><!--[pretest_check]--></h3>
           <!--{start:test_PHP_check}-->
           <div class="dbx-box <!--{class_PHP_check}-->">
             <div class="dbx-handle" onclick="sf_switchDisplay('<!--{id_PHP_check}-->');">
             <!--{value_PHP_check}-->
             <!--{name_PHP_check}-->
             </div>
             <div class="dbx-content" id="<!--{id_PHP_check}-->" style="<!--{style_PHP_config}-->">
             <!--{desc_PHP_check}-->
             </div>
           </div>
           <!--{stop:test_PHP_check}-->
         </div>
         <div class="dbx-group" id="purple2">
           <h3><!--[pretest_config]--></h3>
             <!--{start:test_PHP_config}-->
             <div class="dbx-box <!--{class_PHP_config}-->">
               <div class="dbx-handle" onclick="sf_switchDisplay('<!--{id_PHP_config}-->');">
               <!--{link_PHP_config}-->
               <!--{name_PHP_config}-->
               <!--{value_PHP_config}-->
               </div>
               <div class="dbx-content" id="<!--{id_PHP_config}-->" style="<!--{style_PHP_config}-->"> 
               <!--{desc_PHP_config}-->
              </div>
            </div>
            <!--{stop:test_PHP_config}-->
         </div>
       </div>
       <form name="Daten" action="index.php" method="post">
         <div id="navi">
           <p class="buttons">
             <input type="hidden" name="action" id="action" value="<!--{next_step}-->" />
             <input type="hidden" name="lang" value="<!--{lang}-->" />
            <input class="button" type="button" name="back" value="&laquo; <!--[back]-->" accesskey="z" onclick="javascript:sf_back()" onfocus="this.className ='button on'" onmouseover="this.className ='button on'"  onblur="this.className ='button'" onmouseout="this.className='button'" />
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
