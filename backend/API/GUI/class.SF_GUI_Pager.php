<?php
class SF_GUI_Pager extends SF_API_Object {
	var $pager;
	//'urlVar' => 'page'
	//'prevImg'
	//'nextImg'
	//'spacesBeforeSeparator'
	//'curPageLinkClassName'
	//'separator'
	//'firstPageText'
	//'lastPageText'
	//'excludeVars' => array('changepage1', 'changepage2')
	var $data = array('total_items' => 0,
						'links' => array(),
						'items_per_page' => 10,
						'delta' => 2,
						'current_page' => 1,
						'url_var' => 'page',
						'text_prev' => '&lsaquo;',
						'text_next' => '&rsaquo;',
						'text_first' => '&laquo;',
						'text_last' => '&raquo;',
						'spaces_before_separator' => 1,
						'separator' => '',
						'class_current_span' => 'akt',
						'execlude_vars' => array()
						);
	
	function __construct() {
		include_once 'Pager/Pager.php';
	}
	
	function setTotalItems($items) { $this->data['total_items'] = (int) $items; }
	function setItemsPerPage($items) { $this->data['items_per_page'] = (int) $items; }
	function setDelta($delta) { $this->data['delta'] = (int) $delta; }
	function setCurrentPage($page) { $this->data['current_page'] = (int) $page; }
	
	function setUrlVar($v) { $this->data['url_var'] =  $v; }
	function setTextPrev($v) { $this->data['text_prev'] =  $v; }
	function setTextNext($v) { $this->data['text_next'] =  $v; }
	function setTextFirst($v) { $this->data['text_first'] =  $v; }
	function setTextLast($v) { $this->data['text_last'] =  $v; }
	function setSpacesBeforeSeparator($v) { $this->data['spaces_before_separator'] =  (int) $v; }
	function setSeparator($v) { $this->data['separator'] =  $v; }
	function setClassCurrentSpan($v) { $this->data['class_current_span'] =  $v; }
	function setExecludeVars($v) { $this->data['execlude_vars'] =  $v; }
	
	function generate() {
		$params = array(
		// 'itemData' => '', // [array] // Array of items to page.
		 'totalItems' => $this->data['total_items'], // [integer]// Number of items to page (used only if itemData is not provided).
		 'perPage' => $this->data['items_per_page'], // [integer]// Number of items to display on each page.
		 'delta' => $this->data['delta'], // [integer]// Number of page numbers to display before and after the current one.
		 'mode' => 'Sliding', // [string]// "Jumping" or "Sliding" -window - It determines pager behaviour.
		// 'httpMethod' => '', // [string]// Specifies the HTTP method to use. Valid values are 'GET' or 'POST'.
		// 'formID' => '', // [string]// Specifies which HTML form to use in POST mode.
		// 'importQuery' => '', // [boolean]// if true (default behaviour), variables and values are imported from the submitted data (query string) and used in the generated links, otherwise they're ignored completely
		 'currentPage' => $this->data['current_page'], // [integer]// Initial page number (if you want to show page #2 by default, set currentPage to 2)
		// 'expanded' => '', // [boolean]// if TRUE, window size is always 2*delta+1
		// 'linkClass' => '', // [string]// Name of CSS class used for link styling.
		 'urlVar' => $this->data['url_var'], // [string]// Name of URL var used to indicate the page number. Default value is "pageID".
		// 'path' => '', // [string]// Complete path to the page (without the page name).
		// 'fileName' => '', // [string]// name of the page, with a "%d" if append == TRUE.
		// 'fixFileName' => '', // [boolean]// If set to FALSE, the fileName option is not overridden. Use at your own risk.
		 'append' => true, // [boolean]// If TRUE pageID is appended as GET value to the URL. If FALSE it is embedded in the URL according to fileName specs.
		 'altFirst' => '', // [string]// Alt text to display on the link of the first page. Default value is "first page"; if you want a string with the page number, use "%d" as a placeholder (for instance "page %d")
		 'altPrev' => '', // [string]// Alt text to display on the link of the previous page. Default value is "previous page";
		 'altNext' => '', // [string]// Alt text to display on the link of the next page. Default value is "next page";
		 'altLast' => '', // [string]// Alt text to display on the link of the last page. Default value is "last page"; if you want a string with the page number, use "%d" as a placeholder (for instance "page %d")
		 'altPage' => '', // [string]// Alt text to display before the page number. Default value is "page ".
		 'prevImg' => $this->data['text_prev'], // [string]// Something to display instead of "<<". It can be text such as "<< PREV" or an <img/> as well.
		 'nextImg' => $this->data['text_next'], // [string]// Something to display instead of ">>". It can be text such as "NEXT >>" or an <img/> as well.
		 'separator' => $this->data['separator'], // [string]// What to use to separate numbers. It can be an <img/>, a comma, an hyphen, or whatever.
		 'spacesBeforeSeparator' => $this->data['spaces_before_separator'], // [integer]// Number of spaces before the separator.
		 'spacesAfterSeparator' => 0, // [integer]// Number of spaces after the separator.
		 'curPageLinkClassName' => $this->data['class_current_span'], // [string]// CSS class name for the current page link.
		// 'curPageSpanPre' => '', // [string]// Text before the current page link.
		// 'curPageSpanPost' => '', // [string]// Text after the current page link.
		 'firstPagePre' => '', // [string]// String used before the first page number. It can be an <img/>, a "{", an empty string, or whatever.
		 'firstPageText' => $this->data['text_first'], // [string]// String used in place of the first page number.
		 'firstPagePost' => '', // [string]// String used after the first page number. It can be an <img/>, a "}", an empty string, or whatever.
		 'lastPagePre' => '', // [string]// Similar to firstPagePre, but used for last page number.
		 'lastPageText' => $this->data['text_last'], // [string]// Similar to firstPageText, but used for last page number.
		 'lastPagePost' => '', // [string]// Similar to firstPagePost, but used for last page number.
		  'clearIfVoid' => true, // [boolean]// if there's only one page, don't display pager links (returns an empty string).
		// 'extraVars' => '', // [array]// additional URL vars to be added to the querystring.
		 'excludeVars' => $this->data['execlude_vars'], // [array]// URL vars to be excluded from the querystring.
		 'useSessions' => false, // [boolean]// if TRUE, number of items to display per page is stored in the $_SESSION[$_sessionVar] var.
		// 'closeSession' => '', // [boolean]// if TRUE, the session is closed just after R/W.
		// 'sessionVar' => '', // [string]// Name of the session var for perPage value. A value different from default can be useful when using more than one Pager istance in the page.
		// 'showAllText' => '', // [string]// Text to be used for the 'show all' option in the select box generated by getPerPageSelectBox()
		// 'pearErrorMode' => '', // [constant]// PEAR_ERROR mode for raiseErr
		);
		
		$this->pager =& Pager::factory($params);
		$this->data['links'] = $this->pager->getLinks();
		
	}
	
	function getData() {
		return $this->data['links'];
	}

	function getLinks() {
	    $temp = '';
	    if ( $this->data['links']['first'] != "" ) $temp .= $this->data['links']['first'].$this->data['separator'];	    
	    if ( $this->data['links']['back'] != "" ) $temp .= $this->data['links']['back'].$this->data['separator'];	    
	    $temp .= $this->data['links']['pages'];	    
	    if ($this->data['separator'] != "")
	    {
	        if ( $this->data['links']['next'] != "" ) 
	        {   
	            if ($temp != "") 
	            {
	                if (strpos(strrev($temp),strrev($this->data['separator'])) > 0)
    	            {
	                    $temp .= $this->data['separator'];	    
	                }   
	            }
	            $temp .= $this->data['links']['next'];	    
	        }
	        if ( $this->data['links']['last'] != "" )
	        { 
	            if ($temp != "") 
	            {
    	            if (strpos(strrev($temp),strrev($this->data['separator'])) > 0)
    	            {
    	                $temp .= $this->data['separator'];	    
    	            }   
	            }
       	        $temp .= $this->data['links']['last'];	    
    	    }
    	} else {
	            $temp .= $this->data['links']['next'];	    
       	        $temp .= $this->data['links']['last'];	    
    	}
		return $temp;
	}
	
	function getCountPages() {
		return $this->pager->numPages();
	}
}

?>