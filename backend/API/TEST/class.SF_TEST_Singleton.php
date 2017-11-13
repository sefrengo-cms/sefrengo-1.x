<?php

class SF_TEST_Singleton extends SF_API_Object {
    var $string = "Hi, I am a singleton - yesterday, today, tomorrow";

    function __construct() {
        $this->_API_setObjectIsSingleton(true);
    } 

    function get() {
        return $this->string;
    } 

    function set($v) {
        $this->string = $v;
    } 
} 

?>