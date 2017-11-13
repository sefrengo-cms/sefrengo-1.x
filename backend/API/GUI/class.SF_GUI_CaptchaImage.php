<?php
class SF_GUI_CaptchaImage extends SF_API_Object {
	
	var $captcha_object;
	var $template = '{image}{textfield}{hiddentoken}';
	var $picture_prefix = 'sf_ca_';
	var $picture_path = '';
	
	function __construct() {
		global $cfg_cms, $cfg_client;
		include_once $cfg_cms['cms_path'].'external/misc/class.captcha.php';
		$this->captcha_object = new captcha();
		$this->picture_path = $cfg_client["path"].'cms/files/tmp/captcha';
		if (! is_dir($cfg_client["path"].'cms/files/tmp')) {
			mkdir ($cfg_client["path"].'cms/files/tmp', 0777);
		}
		if (! is_dir($cfg_client["path"].'cms/files/tmp/captcha')) {
			mkdir ($cfg_client["path"].'cms/files/tmp/captcha', 0777);
		}
	}

	
	function setCaptchaTemplate($tpl) {
		
		//only accept template with all vars
		if (strstr($tpl, '{image}') && strstr($tpl, '{textfield}') && strstr($tpl, '{hiddentoken}')) {
			$this->template = $tpl;
			return true;
		}
		
		return false;
	}
	
	function setCaptchapathPrefix($prefix) {
		$this->picture_prefix = $prefix;
	}
	
	function validateByCharseqAndRequestFieldname($charseq, $fieldname, $method = 'post', $delete_files_after_test = true) {
		switch (strtolower($method)) {
			case 'get':
				$session = addslashes($_GET[$fieldname.'_cses']);
				break;
			case 'post':
				$session = addslashes($_POST[$fieldname.'_cses']);
				break;
			default:
				return false;
		}
		return $this->validateByCharseqAndSession($charseq, $session, $delete_files_after_test);
	}
	
	function validateByCharseqAndSession($charseq, $session, $delete_files_after_test = true) {
		return $this->captcha_object->verify($session, $charseq, $this->picture_prefix, $this->picture_path, $delete_files_after_test);
	}
	
	
	function getCaptchaHtml($element_name, $lenght = 4, $attributes_textfield = '', $attributes_image = '', $styleset = '', $width = 100, $height = 30) {
		
		//generate object values
		$session = md5(round(rand(0,40000)).time());
		$this->captcha_object->setCommons($session, $this->picture_path, $this->picture_prefix , $width , $height, $styleset);
		$pictoken = $this->captcha_object->get_pic($lenght);
		
		//init captcha object
		
		//make HTML
		$c_pic = '<img src="cms/inc/capimg.php?img='.$pictoken.'&amp;nadd='.$this->picture_prefix.'" '
				. $attributes_image 
				. ' width="'.$width.'" height="'.$height.'" alt="" />';
		$c_field = '<input type="text" name="'.$element_name.'" id="'.$element_name.'" '
				. $attributes_textfield . ' value="" />';
		$c_hidden = '<input type="hidden" name="'.$element_name.'_cses" id="'.$element_name.'_cses" '
					.' value="'. htmlspecialchars($session) .'" />';
		$e = str_replace(array('{image}', '{textfield}', '{hiddentoken}'), array($c_pic, $c_field, $c_hidden), $this->template);
		
		return $e;
	}
}
?>