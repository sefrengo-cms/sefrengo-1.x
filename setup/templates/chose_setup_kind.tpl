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
         <!--[setup_kind_title]-->
        </h2>
          <p>
           <!--[setup_kind_title_text]-->
          </p>
       </div>
         <form action ="index.php" method="post">
           <div id="content">
       <!--    
           <div class="blue">
           <p>
       <input type="radio" name="sql_target" value="backup.sql" id="backup" />
       <label for="backup"><!--[setup_kind_1_title]--></label>
       </p>
       <p class="instart">
       <!--[setup_kind_1_text]-->
       </p>
       </div>
       -->
         <div class="blue2">
         <p>
           <input type="radio" name="sql_target" value="standard.sql" id ="standard" checked="checked" />
           <label for="standard"><!--[setup_kind_2_title]--></label>
         </p>
         <p class="instart">
           <!--[setup_kind_2_text]-->
         </p>
         </div>
         <div class="blue">
         <p>
           <input type="radio" name="sql_target" value="updates.sql" id="update" />
           <label for="update"><!--[setup_kind_3_title]-->
           </label>
          </p>
          <p class="instart">
            <!--[setup_kind_3_text]-->           
          </p>
         </div>
        </div>
        <div id="navi">
          <p class="buttons">
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