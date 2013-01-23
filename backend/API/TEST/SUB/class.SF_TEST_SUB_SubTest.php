<?php
class SF_TEST_SUB_SubTest extends SF_API_Object{
	
	var $string = "I am a String in the subtest package"; 
	
	function get(){
		return $this->string;
	}
	
	function set($v){
		$this->string = $v;
	}
}
?>
