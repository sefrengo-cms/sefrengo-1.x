<?php
class SF_UTILS_Mail extends SF_API_Object {
    var $charset = 'utf-8';
    var $crlf = "\n";
    var $from = '';
    var $adresses = array('to' => array(),
    						'cc' => array(),
    						'bcc' => array());
    var $subject = '';
    var $txt_body = '';
    var $attachments = array();
    
    var $mailrule = '/^[a-zA-Z0-9\._-]+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}|[0-9]{1,6})(\]?)$/';
    
    // objects
    var $db;
    
    var $db_names;

    function SF_UTILS_Mail() {
		global $cms_db;		
		$this->db =& $GLOBALS['sf_factory']->getObject('DATABASE', 'Ado');
		$this->db_names =& $cms_db;		
    }
    
    function setCharset($e) {
    	$this->Charset = $e;
    }
    
    function setCrlf($crlf) {
    	$this->crlf = $crlf; 
    }
    
    function setFrom($frm) {
    	$this->from = $frm;
    }
    
    function addTo($adress) {
    	return $this->addAdress($adress, 'to');
    }
    
    function addCc($adress) {
    	return $this->addAdress($adress, 'cc');
    }
    
    function addBcc($adress) {
    	return $this->addAdress($adress, 'bcc');
    }
    
    function addAdressByUsername($name, $where = 'to') {
    	$sql = "SELECT email FROM ".$this->db_names['users']." WHERE username = ".$this->db->qstr($name)."";
		$rs = $this->db->Execute($sql);
		if ($rs) {
			$adr = $rs->fields['email'];
			return $this->addAdress($adr, $where);
		}
		
		return false;
    }
    
    function addAdressByGroupname($name, $where = 'to') {
    	$sql = "SELECT 
					U.email 
				FROM 
					".$this->db_names['users']." U
					LEFT JOIN  ".$this->db_names['users_groups']." UG USING(user_id)
					LEFT JOIN  ".$this->db_names['groups']." G USING(idgroup)
				WHERE 
					G.name = ".$this->db->qstr($name);
					
		$rs = $this->db->Execute($sql);
		$to_return = true;

		if ($rs) {
			while(!$rs->EOF) {
				$adr = $rs->fields['email'];
				if (! $this->addAdress($adr, $where)) {
					$to_return = false;
				}
				$rs->MoveNext();
			}
			
		}
		
		return $to_return;
    }
    
    function addAdress($adr, $where) {
    	if (! preg_match($this->mailrule, $adr)) {
			return false;
		}
		
		if (in_array($adr, $this->adresses['to']) 
				|| in_array($adr, $this->adresses['cc']) || in_array($adr, $this->adresses['bcc'])) {
			return false;
		}
    	
    	  switch(strtolower($where)) {
    		case 'to':
    			array_push($this->adresses['to'], $adr);
    			break;
    		case 'cc':
    			array_push($this->adresses['cc'], $adr);
    			break;
    		case 'bcc':
    			array_push($this->adresses['bcc'], $adr);
    			break;
    		default:
    			return false;		
    	}
    	
    	return true;
    }
    
    function setSubject($s) {
    	$this->subject = $s;
    }
    
    function setTxtBody($b) {
    	$this->txt_body = $b;
    }
     
    function addAttachment($file, $type, $name) {
    	if ($file == '' || $type == '' || $name == '') {
    		return false;
    	}
    	
    	array_push($this->attachments, array('file' => $file,
    											'type' => $type,
    											'name' => $name) ); 
    	
    	return true;
    }
    
    function process() {
    	//include PEAR mailing class
		include_once('Mail.php');
		include_once('Mail/mime.php');
		
    	$mime =& new Mail_mime($this->crlf);
    	$mail =& Mail::factory('mail');
    	
    	//set encoding		
		$mime->_build_params['head_charset'] = $mime->_build_params['text_charset'] = $mime->_build_params['html_charset'] = $this->charset;
		
		//set from, subject
		$hdrs = array(
		              'From'    => $this->from,
		              'Subject' => $this->subject
		              );
		
		//set to
		if ( count($this->adresses['to'] > 0) ) {
			$to = implode(', ', $this->adresses['to']);
		} else {
			return false;
		}
		
		//set cc               
		if ( count($this->adresses['cc'] > 0) ) {
			$mime->addCc(implode(', ', $this->adresses['cc']));
		}
		
		//set bcc               
		if ( count($this->adresses['bcc'] > 0) ) {
			$mime->addBcc(implode(', ', $this->adresses['bcc']));
		}		
		
		//add attachments
		foreach ($this->attachments AS $v){
			$mime->addAttachment($v['file'], $v['type'], $v['name']);
		}
		
		//set message
		$mime->setTXTBody($this->txt_body);	
		
		$body = $mime->get();
		$hdrs = $mime->headers($hdrs);
		$mail =& Mail::factory('mail');
		
		return $mail->send($to, $hdrs, $body);//$mail->send(array($to), 'mail');

    }
} 

?>
