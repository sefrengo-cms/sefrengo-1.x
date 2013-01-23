<?php

class SF_TEST_MultipleClasses extends SF_API_Object{
	
	var $string = "I am the first class of the libary mutiple classes"; 
	
	function get(){
		return $this->string;
	}
	
	function set($v){
		$this->string = $v;
	}
}

class SF_TEST_MultipleClasses2 extends SF_API_Object{
	
	var $string = "I am the second class of the libary mutiple classes"; 
	
	function get(){
		return $this->string;
	}
	
	function set($v){
		$this->string = $v;
	}
}

class SF_TEST_TheThirdClass extends SF_API_Object{
	
	var $string = "I am the third class of the libary mutiple classes"; 
	
	function get(){
		return $this->string;
	}
	
	function set($v){
		$this->string = $v;
	}
}
?>