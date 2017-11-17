<?php
class SF_HTTP_WebRequest extends SF_API_Object {
    var $mq_gpc_is_active;
    var $req;

    function __construct() {
        $this->_API_objectIsSingleton(true);

        $this->mq_gpc_active = get_magic_quotes_gpc();
        $this->req = $_REQUEST;
    } 

    function getVal($name, $default = false) {
        $v = ''; 
        // get _REQUEST val
        if (is_array($name)) {
            $keys = array_keys($name);
            $c = count($keys);
            $to_eval = '$v = $this->req["' . $name[ $keys['0'] ] . '"]';
            for ($i = 1; $i < $c;++$i) {
                $to_eval .= '["' . $name[ $keys[$i] ] . '"]';
            } 
            $to_eval .= ';';
            eval($to_eval);
        } else {
            $v = $this->req[$name];
        } 
        // fix magic quotes
        if ($this->mq_gpc_is_active) {
            $this->_fixMagicQuotes($v);
        } 
        // check UTF-8 encoding
        $this->_checkUTF8($v);

        if ($v != '') {
            return $v;
        } else {
            return $default;
        } 
    }
    
    function getValAsInt($name, $default = false) {
    	return (int) $this->getVal($name, $default);
    }
    
    function getValEntityEncoded($name, $default = false) {
    	return htmlentities($this->getVal($name, $default), ENT_COMPAT, 'utf-8');
    }

    function _fixMagicQuotes(&$val) {
        if (is_array($val)) {
            foreach ($val as $k => $v) {
                $this->_fixMagicQuotes($val[$k]);
            } 
        } else {
            $val = stripslashes($val);
        } 
    } 

    function _checkUTF8(&$val) {
        if (is_array($val)) {
            foreach ($val as $k => $v) {
                $this->_checkUTF8($val[$k]);
            } 
        } else {
            // only asccii 0-127 are in use
            if (! preg_match('/[\x80-\xff]/', $val)) {
                return;
            } 

      		$is_utf8 = preg_match('%([\xC2-\xDF][\x80-\xBF]|\xE0[\xA0-\xBF][\x80-\xBF]|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}|\xED[\x80-\x9F][\x80-\xBF]|\xF0[\x90-\xBF][\x80-\xBF]{2}|[\xF1-\xF3][\x80-\xBF]{3}|\xF4[\x80-\x8F][\x80-\xBF]{2})%xs', $val) ? true : false; 
            if (! $is_utf8) {
                $val = utf8_encode($val);
            } 
        } 
    } 
} 

?>
