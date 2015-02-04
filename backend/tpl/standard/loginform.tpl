<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
 die('NO CONFIGFILE FOUND');
}
//utf 8 hack
header('Content-type: text/html; charset=UTF-8');
include('tpl/standard/lang/'.$cfg_cms['backend_lang'].'/lang_general.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sefrengo CMS | Login</title>
<link href="tpl/standard/css/login.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="favicon.ico" />
</head>
<body>
  <noscript>
  <div class="nojs">
    <p><?PHP echo $cms_lang['login_nojs']; ?></p>
  </div>
  </noscript>
<div id="wrapper">
  <div id="header">
    <h2 class="hide">Sefrengo Login</h2>
  </div>
  <form name="login" action="<?php print $this->url() ?>" method="post" target="_top">
  <?php global $username, $doublelogin, $challengefail; if (isset($username) && !isset($doublelogin) && !isset($challengefail)): ?>
    <p class="warning"><?PHP echo $cms_lang['login_invalidlogin']; ?></p>
  <?php elseif (isset($doublelogin) && !isset($challengefail)): ?>
    <p class="warning"><?PHP echo $cms_lang['login_logininuse']; ?></p>
  <?php elseif (isset($challengefail)): ?>
    <p class="warning"><?PHP echo $cms_lang['login_challenge_fail']; ?></p>
  <?php endif ?>
  <?php global $error; if ($error=='noclient'): ?>
   <p class="warning"><?PHP echo $cms_lang['login_noclient']; ?></p>
  <?php endif ?>
  <div id="content">
    <p>
     <span class="hide"><?PHP echo $cms_lang['login_username'].": "; ?></span>
     <input class="breit" name="username" type="text" value="<?php print(htmlspecialchars(isset($this->auth['uname']) ? $this->auth['uname'] : '')) ?>" id="username" tabindex="1" maxlength="32" onfocus="this.style.backgroundColor='#FFF5CE'" onblur="this.style.backgroundColor='#ffffff'" />
     </p>
     <p>
     <span class="hide"><?PHP echo $cms_lang["login_password"].": "; ?></span>
     <input class="breit" name="password" type="password" value="" id="password" tabindex="2" maxlength="32" onfocus="this.style.backgroundColor='#FFF5CE'" onblur="this.style.backgroundColor='#ffffff'" />
     </p>
  <div id="navi">
  <p class="floatl"><?PHP echo $cms_lang['login_pleaselogin'] ?></p>
    <p class="floatr">
      <input type="submit" name="Submit" value="Login &raquo;" tabindex="3" class="sf_buttonAction"/>
    </p>
      <script language="javascript" src="tpl/standard/js/sniffer.js" type="text/javascript"></script>
      <input type="hidden" name="response"  value="" />
      <input type="hidden" name="area"  value="con" />
  </div>
  </div>
  </form>
  </div>
  <div class="footer">
    <p><?PHP echo $cms_lang['login_licence']; ?></p>
  </div>
<script type="text/javascript">
<!--
  // Activate the appropriate input form field.
  if (document.login.username.value == '') {
    document.login.username.focus();
  } else {
    document.login.password.focus();
  }
// -->
</script>
<?PHP echo "\n $mysql_debug"; ?>
</body>
</html>