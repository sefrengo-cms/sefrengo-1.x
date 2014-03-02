<?php
class SF_Header
{	
	/**
	 * Global Sefrengo variables
	 */
	protected $cfg_cms;
	protected $cms_db;
	protected $db;
	protected $tpl;
	protected $perm;
	protected $sess;
	protected $auth;
	protected $lng;
	
	/**
	 * Current area parameter
	 * @var string
	 */
	protected $area;
	
	/**
	 * Tree for navigation
	 * @var array
	 */
	private $navigation_tree;
	
	/**
	 * Stores the content of the generated view
	 * Is default false until the view is generated
	 * @var string
	 */
	protected $generated_view = false;
	
	/**
	 * Constructor sets up {@link $db} and runs the
	 * functions to create the header. 
	 * @global string $area
	 * @return void
	 */
	public function __construct()
	{
		$this->_initGlobals();
		
		$this->tpl->loadTemplatefile('header.tpl');
		
		$this->_generateProjectSelect();
		$this->_generateLanguageSelect();
		
		$this->_generateTree();
		$this->_generateMenuArray();
		$this->_generateMenuLayer();
		
		$this->_setTemplateVariables();
	}
	
	private function _initGlobals()
	{
		// global area variable from main.php
		global $area, $auth, $cfg_cms, $cms_db, $cms_lang, $db, $perm, $sess, $tpl;
		
		$this->area = ($area == 'con_frameheader') ? 'con_editframe' : $area;
		
		// store db connection object
		$this->auth = $auth;
		$this->cfg_cms = $cfg_cms;
		$this->cms_db = $cms_db;
		$this->db = $db;
		$this->lng = $cms_lang;
		$this->tpl = $tpl;
		$this->perm = $perm;
		$this->sess = $sess;
	}
	
	/**
	 * Add JavaScript onload function to body tag. The function must end with an semicolon.
	 * @param String $js_function
	 * @return void
	 */
	public function setBodyOnLoadFunction($js_function)
	{
		$this->tpl->setCurrentBlock('__global__');
		$this->tpl->setVariable('ONLOAD_FUNCTION', $js_function);
	}
	
	/**
	 * Generates select field for projects
	 * @global integer $client
	 * @global integer $is_plugin
	 * @global integer $idcatside
	 * @global integer $idside
	 * @global integer $idcat
	 * @global integer $idlay
	 * @global integer $idmod
	 * @global integer $idtpl
	 * @global integer $idclient
	 * @global integer $idcatsidetpl
	 * @return void
	 */
	private function _generateProjectSelect()
	{
		// globals
		global $client, $is_plugin, $idcatside, $idside, $idcat, $idlay, $idmod, $idtpl, $idclient, $idcatsidetpl;
		
		$con_more_than_one_client = false;
		$prev_client = '';
		
		$sql = "SELECT DISTINCT * FROM ". $this->cms_db['clients'] ." A LEFT JOIN ". $this->cms_db['clients_lang']." B USING(idclient) ORDER BY A.name";
		$rs = $this->db->query($sql);
		
		$client_sel_entries = array();
		$client_sel_entries_c = 0;
		while($this->db->next_record())
		{
			// darf User Projekt sehen?
			if ($this->perm->have_perm('1', 'lang', $this->db->f('idlang')) && $prev_client != $this->db->f('idclient')) {
				if ($client == $this->db->f('idclient'))
				{
					$client_sel_entries[$client_sel_entries_c]['FIELD-TITLE'] = $this->db->f('name');
					$client_sel_entries[$client_sel_entries_c]['FIELD-VALUE'] = $this->db->f('idclient');
					$client_sel_entries[$client_sel_entries_c]['FIELD-SELECTED'] = 'selected="selected"';
					$con_act_client = $this->db->f('name');
				}
				else
				{
					$client_sel_entries[$client_sel_entries_c]['FIELD-TITLE'] = $this->db->f('name');
					$client_sel_entries[$client_sel_entries_c]['FIELD-VALUE'] = $this->db->f('idclient');
					$client_sel_entries[$client_sel_entries_c]['FIELD-SELECTED'] = '';
					$con_more_than_one_client = true;
				}
				// wenn mehrere sprachen in einem client sind, verhindern, das der client öfters als ein mal angezeigt wird
				$prev_client = $this->db->f('idclient');
				
				$client_sel_entries_c++;
			}
		}
		
		if ($con_more_than_one_client)
		{
			$this->tpl->setCurrentBlock('CLIENT-LANG-SELECT_CLIENT-SELECT_HIDDEN-FIELDS');
			
			if ($is_plugin || $this->area == 'con_editframe')
			{
				$this->tpl->setVariable('FIELD-NAME','area');
				$this->tpl->setVariable('FIELD-VALUE','con');
				$this->tpl->parseCurrentBlock();
			}
		
			if (!empty($idcatside))
			{
				$this->tpl->setVariable('FIELD-NAME','idcatside');
				$this->tpl->setVariable('FIELD-VALUE',$idcatside);
				$this->tpl->parseCurrentBlock();
			}
			if (!empty($idside))
			{
				$this->tpl->setVariable('FIELD-NAME','idside');
				$this->tpl->setVariable('FIELD-VALUE',$idside);
				$this->tpl->parseCurrentBlock();
			}
			if (!empty($idcat))
			{
				$this->tpl->setVariable('FIELD-NAME','idcat');
				$this->tpl->setVariable('FIELD-VALUE',$idcat);
				$this->tpl->parseCurrentBlock();
			}
			if (!empty($idlay))
			{
				$this->tpl->setVariable('FIELD-NAME','idlay');
				$this->tpl->setVariable('FIELD-VALUE',$idlay);
				$this->tpl->parseCurrentBlock();
			}
			if (!empty($idmod))
			{
				$this->tpl->setVariable('FIELD-NAME','idmod');
				$this->tpl->setVariable('FIELD-VALUE',$idmod);
				$this->tpl->parseCurrentBlock();
			}
			if (!empty($idtpl))
			{
				$this->tpl->setVariable('FIELD-NAME','idtpl');
				$this->tpl->setVariable('FIELD-VALUE',$idtpl);
				$this->tpl->parseCurrentBlock();
			}
			if (!empty($idclient))
			{
				$this->tpl->setVariable('FIELD-NAME','idclient');
				$this->tpl->setVariable('FIELD-VALUE',$idclient);
				$this->tpl->parseCurrentBlock();
			}
			if (!empty($idcatsidetpl))
			{
				$this->tpl->setVariable('FIELD-NAME','idcatsidetpl');
				$this->tpl->setVariable('FIELD-VALUE',$idcatsidetpl);
				$this->tpl->parseCurrentBlock();
			}
		
			$this->tpl->setCurrentBlock('CLIENT-LANG-SELECT_CLIENT-SELECT_ENTRY');
		
			foreach($client_sel_entries as $v)
			{
				$this->tpl->setVariable('FIELD-TITLE',$v['FIELD-TITLE']);
				$this->tpl->setVariable('FIELD-VALUE',$v['FIELD-VALUE']);
				$this->tpl->setVariable('FIELD-SELECTED',$v['FIELD-SELECTED']);
				$this->tpl->parseCurrentBlock();
			}
			
			$this->tpl->setCurrentBlock('CLIENT-LANG-SELECT_CLIENT-SELECT');
		
			$this->tpl->setVariable('FORM-ACTION',$this->sess->url('main.php'));
			$this->tpl->parseCurrentBlock();
		}
	}
	
	/**
	 * Generates select field for languages
	 * @global integer $client
	 * @global integer $is_plugin
	 * @global integer $idcatside
	 * @global integer $idside
	 * @global integer $idcat
	 * @global integer $idlay
	 * @global integer $idmod
	 * @global integer $idtpl
	 * @global integer $idclient
	 * @global integer $idcatsidetpl
	 * @return void
	 */
	private function _generateLanguageSelect()
	{
		// globals 
		global $client, $lang, $is_plugin, $idcatside, $idside, $idcat, $idlay, $idmod, $idtpl, $idclient, $idcatsidetpl, $change_show_tree, $show_tree;
		
		$con_more_than_one_lang = false;
		
		$sql = "SELECT A.idlang, A.name FROM ".$this->cms_db['lang']." A LEFT JOIN ".$this->cms_db['clients_lang']." B USING(idlang) WHERE B.idclient='$client' ORDER BY idlang";
		$rs = $this->db->query($sql);
		
		if ($rs === false || $rs->EOF)
		{
			return false;
		}
		
		$lang_sel_entries=array();
		$lang_sel_entries_c=0;
		while($this->db->next_record())
		{
			// darf User Sprache sehen?
			if($this->perm->have_perm('1', 'lang', $this->db->f('idlang'))) {
				if ($lang == $this->db->f('idlang')) {
					$lang_sel_entries[$lang_sel_entries_c]['FIELD-TITLE'] = $this->db->f('name');
					$lang_sel_entries[$lang_sel_entries_c]['FIELD-VALUE'] = $this->db->f('idlang');
					$lang_sel_entries[$lang_sel_entries_c]['FIELD-SELECTED'] = 'selected="selected"';
					$con_act_lang = $this->db->f('name');
				} else {
					$lang_sel_entries[$lang_sel_entries_c]['FIELD-TITLE'] = $this->db->f('name');
					$lang_sel_entries[$lang_sel_entries_c]['FIELD-VALUE'] = $this->db->f('idlang');
					$lang_sel_entries[$lang_sel_entries_c]['FIELD-SELECTED'] = '';
					$con_more_than_one_lang = true;
				}
				$lang_sel_entries_c++;
			}
		}
		
		if($con_more_than_one_lang)
		{
			$this->tpl->setCurrentBlock('CLIENT-LANG-SELECT_LANG-SELECT_HIDDEN-FIELDS');
		
			if ($this->area == 'plugin')
			{
				$sf_forbiddenvars = array('action', 'area', 'client', 'lang', 'idcatsidetpl', 'idclient', 'idtpl', 'idmod', 'idlay', 'idcat', 'idside');
				foreach ($_REQUEST AS $k=>$v)
				{
					if (! is_array($v))
					{
						if (! array_key_exists($k, $sf_forbiddenvars) )
						{
							$this->tpl->setVariable('FIELD-NAME',htmlentities($k, ENT_COMPAT, 'UTF-8'));
							$this->tpl->setVariable('FIELD-VALUE',htmlentities($v, ENT_COMPAT, 'UTF-8'));
							$this->tpl->parseCurrentBlock();
						}
					}
				}
			}
			
			if (!empty($idside))
			{
				$this->tpl->setVariable('FIELD-NAME','idside');
				$this->tpl->setVariable('FIELD-VALUE',$idside);
				$this->tpl->parseCurrentBlock();
			}
			if (!empty($idcat))
			{
				$this->tpl->setVariable('FIELD-NAME','idcat');
				$this->tpl->setVariable('FIELD-VALUE',$idcat);
				$this->tpl->parseCurrentBlock();
			}
			if (!empty($idlay))
			{
				$this->tpl->setVariable('FIELD-NAME','idlay');
				$this->tpl->setVariable('FIELD-VALUE',$idlay);
				$this->tpl->parseCurrentBlock();
			}
			if (!empty($idmod))
			{
				$this->tpl->setVariable('FIELD-NAME','idmod');
				$this->tpl->setVariable('FIELD-VALUE',$idmod);
				$this->tpl->parseCurrentBlock();
			}
			if (!empty($idtpl))
			{
				$this->tpl->setVariable('FIELD-NAME','idtpl');
				$this->tpl->setVariable('FIELD-VALUE',$idtpl);
				$this->tpl->parseCurrentBlock();
			}
			if (!empty($idclient))
			{
				$this->tpl->setVariable('FIELD-NAME','idclient');
				$this->tpl->setVariable('FIELD-VALUE',$idclient);
				$this->tpl->parseCurrentBlock();
			}
			if (!empty($idcatsidetpl))
			{
				$this->tpl->setVariable('FIELD-NAME','idcatsidetpl');
				$this->tpl->setVariable('FIELD-VALUE',$idcatsidetpl);
				$this->tpl->parseCurrentBlock();
			}
			if (!empty($change_show_tree))
			{
				$this->tpl->setVariable('FIELD-NAME','change_show_tree');
				$this->tpl->setVariable('FIELD-VALUE',$change_show_tree);
				$this->tpl->parseCurrentBlock();
			}
			else
			{
				$this->tpl->setVariable('FIELD-NAME','change_show_tree');
				$this->tpl->setVariable('FIELD-VALUE',$show_tree);
				$this->tpl->parseCurrentBlock();
			}
		
			$this->tpl->setCurrentBlock('CLIENT-LANG-SELECT_LANG-SELECT_ENTRY');
		
			foreach($lang_sel_entries as $v)
			{
				$this->tpl->setVariable('FIELD-TITLE',$v['FIELD-TITLE']);
				$this->tpl->setVariable('FIELD-VALUE',$v['FIELD-VALUE']);
				$this->tpl->setVariable('FIELD-SELECTED',$v['FIELD-SELECTED']);
				$this->tpl->parseCurrentBlock();
			}
		
			$this->tpl->setCurrentBlock('CLIENT-LANG-SELECT_LANG-SELECT');
		
			$this->tpl->setVariable('FORM-ACTION',$this->sess->url('main.php?area='.$this->area));
			$this->tpl->parseCurrentBlock();
		}
	}
	
	/**
	 * Generates an array from the database information
	 * @global integer $client
	 * @return void
	 */
	private function _generateTree()
	{
		// globals
		global $client;
		
		$sql = "SELECT 
					idbackendmenu, parent, sortindex, entry_langstring, entry_url, url_target, entry_validate
				FROM
					". $this->cms_db['backendmenu'] ."
				WHERE
					idclient IN(0, $client)
					AND entry_langstring NOT IN('empty_dummy')
				ORDER BY
					parent, sortindex";
		$rs = $this->db->query($sql);
		
		if ($rs === false || $rs->EOF)
		{
			return false;
		}
		
		while($this->db->next_record())
		{
			$parent_old = $parent_new;
			$parent_new = $this->db->f('parent');
			if($parent_new != $parent_old){$k=0;}
			$unsort_array[$parent_new][$k]['id'] = $this->db->f('idbackendmenu');
			$unsort_array[$parent_new][$k]['parent'] = $this->db->f('parent');
			$unsort_array[$parent_new][$k]['sort'] = $this->db->f('sortindex');
			$unsort_array[$parent_new][$k]['langstring'] = $this->db->f('entry_langstring');
			$unsort_array[$parent_new][$k]['url'] = $this->db->f('entry_url');
			$unsort_array[$parent_new][$k]['url_target'] = $this->db->f('url_target');
			$unsort_array[$parent_new][$k]['validate'] = $this->db->f('entry_validate');
			$k++;
		}
		// rekursives Auslesen vom $unsort_array
		// die Daten werden danach mit richtiger Reihenfolge in ein Array eingeordnet
		$this->count = 0;
		$this->maxlevel = 0;
		$this->unsort_array = $unsort_array;
		$this->_menuLevelOrder(0);
	}
	
	/**
	 * Read navigation points recursive to {@link $navigation_tree}
	 * @param integer $node_id
	 * @param integer $level
	 * @return void
	 */
	private function _menuLevelOrder($node_id, $level = 0)
	{
		for($i=0; ! empty($this->unsort_array[$node_id][$i]['id']) ; $i++) {
			$this->navigation_tree[$this->count]['level'] = $level;
			$this->navigation_tree[$this->count]['id'] = $this->unsort_array[$node_id][$i]['id'];
			$this->navigation_tree[$this->count]['sort'] = $this->unsort_array[$node_id][$i]['sort'];
			$this->navigation_tree[$this->count]['langstring'] = $this->unsort_array[$node_id][$i]['langstring'];
			$this->navigation_tree[$this->count]['url'] = $this->unsort_array[$node_id][$i]['url'];
			$this->navigation_tree[$this->count]['url_target'] = $this->unsort_array[$node_id][$i]['url_target'];
			$this->navigation_tree[$this->count]['validate'] = $this->unsort_array[$node_id][$i]['validate'];
			$this->navigation_tree[$this->count]['parent'] = $this->unsort_array[$node_id][$i]['parent'];
			if ($this->navigation_tree[$this->count][0] > $this->maxlevel)
			{
				$this->maxlevel = $this->navigation_tree[$this->count]['id'];
			}
			$this->count++;
			/* Haengt ein Leaf am Leaf? einfach mal rekursiv runtergehen und nachgucken ... */
			$this->_menuLevelOrder($this->navigation_tree[$this->count-1]['id'], ($level + 1));
		}
		/* Fallback aus der Rekursion */
		return;
	}
	
	/**
	 * Generates the main menu and the sub menu array for use in _generateMenuLayer()
	 * @global integer $cms_plugin
	 * @global integer $idplugin
	 * @global integer $client
	 * @return void
	 */
	private function _generateMenuArray()
	{
		// globals
		global $cms_plugin, $idplugin, $client;
		
		$perm = $this->perm;
		
		$main_index = -1;
		$sub_index = -1;
		
		// needed to figure out active sublaye
		$pos = strpos($this->area, '_');
		$layer_cutter = (!$pos) ? $this->area: substr( $this->area, 0, $pos );
		
		for($i = 0; $i < count($this->navigation_tree); $i++)
		{
			// Hauptmenü bauen
			if($this->navigation_tree[$i]['level'] == '0')
			{
				$main_index++;
				$sub_index = -1;
		        //CHANGE STAM
		        if ($this->navigation_tree[$i]['url'] == 'root')
		        {
		            $surl = (int) $main_index+1;
		        }
		        else
		        {
		            $dynamic = '$surl = "'.$this->navigation_tree[$i]['url']. '";';
		        	// parse url, this is nessesary if the var includes dynamic content like arrays, vars, etc...
		            eval($dynamic);
		        }
		        //CHANGE STAM
				$this->mainmenu[$main_index]['url'] = $surl;
				$this->mainmenu[$main_index]['title'] = $this->lng[$this->navigation_tree[$i]['langstring']];
				$this->mainmenu[$main_index]['validate'] = $this->navigation_tree[$i]['validate'];
		
			// Untermenü aufbauen
			}
			else
			{
				$sub_index++;
				$dynamic = '$surl = "'.$this->navigation_tree[$i]['url']. '";';
		
				// parse url, this is nessesary if the var includes dynamic content like arrays, vars, etc...
				eval($dynamic);
		
				// target
				if($this->navigation_tree[$i]['url_target'] == 'frame')
				{
					$surl = $this->sess->url('main.php?area=con_editframe&idplugin='. $this->navigation_tree[$i]['id']);
				}
				else
				{
					$surl = $this->sess->url($surl);
				}
				
				$submenu[$sub_index]['url'] = $surl;
				$submenu[$sub_index]['title']	= $this->lng[$this->navigation_tree[$i]['langstring']];
				
				// check for active menu layer
				if ( (preg_match('/area='.$layer_cutter.'\b/', $this->navigation_tree[$i]['url']) && empty($cms_plugin) && empty($idplugin) ) 
					|| $idplugin == $this->navigation_tree[$i]['id'] 
					|| strstr($this->navigation_tree[$i]['url'], 'cms_plugin='.$cms_plugin) 
					&& ! empty($cms_plugin))
				{
					$this->sub_final[$main_index][$sub_index]['active'] = true;
					$this->active_submenu_layer = $main_index +1;
				}
				else
				{
					$this->sub_final[$main_index][$sub_index]['active'] = false;
				}
				
				$this->mainmenu[$main_index]['permstring'] .= '( '. $this->navigation_tree[$i]['validate'] .')*';
				$dynamic = 'if('.$this->navigation_tree[$i]['validate'].') {$url="'. $submenu[$sub_index]['url'] .'";} else {$url="";}';
				// check perms
				eval($dynamic);
		
				$this->sub_final[$main_index][$sub_index]['url'] = $url;
				$this->sub_final[$main_index][$sub_index]['title'] = $submenu[$sub_index]['title'];
				
				$this->cat_is_not_empty[$main_index] = true;
			}
		}
		
		if(!isset($this->active_submenu_layer))
		{
			$this->active_submenu_layer = 1;
		}
	}
	
	/**
	 * Iterate through the main and sub menu array and set the corresponding template
	 * @return void
	 */
	private function _generateMenuLayer()
	{
		$perm = $this->perm;
		
		// throw out mainmenu
		$this->maincount = count($this->mainmenu);
		//print_r($this->mainmenu);
		//print_r($this->sub_final);
		for($i=0; $i < $this->maincount; $i++)
		{
			// submenu output
			if(is_array($this->sub_final[$i]))
			{
				foreach ($this->sub_final[$i] as $v)
				{
					if ($v['url'] != '')
					{
						$this->tpl->setCurrentBlock('SUBMENU_ENTRY');
						$this->tpl->setVariable('LINK-HREF',$v['url']);
						$this->tpl->setVariable('LINK-TITLE',$v['title']);
						if ($v['active']==true)
						{
							$this->tpl->setVariable('LINK-CLASS','class="active"');
						}
						$this->tpl->parseCurrentBlock();
					}		
				}
				
				$this->tpl->setCurrentBlock('SUBMENU');
				$this->tpl->setVariable('COUNT',$i+1);
				$this->tpl->parseCurrentBlock();
			}
			
			$permstring = $this->mainmenu[$i]['permstring'];
			$permstring = str_replace(
				array(')*(', ')*'),
				array(') || (', ')'),
				$permstring
			);
			
			if ($this->mainmenu[$i]['validate'] != 'root' && $permstring)
			{
				$permstring = '('.$permstring.') && '.$this->mainmenu[$i]['validate'];
			}
		
			// check perms for displaying maincat
			if(trim($permstring) != '')
			{
				$permurl = $this->mainmenu[$i]['url'];
				unset($url);
				$dynamic = 'if('.$permstring.') $url=$permurl; else $url="";';
				eval($dynamic);
				if ($url != '')
				{
					$this->tpl->setCurrentBlock('MAINMENU_ENTRY');
					$this->tpl->setVariable('LINK-HREF', (is_numeric($url)) ? '#sub-'.$url : $url);
					$this->tpl->setVariable('LINK-TITLE',$this->mainmenu[$i]['title']);

					if ($this->active_submenu_layer-1==$i)
					{
						$this->tpl->setVariable('LINK-CLASS','class="active"');
						$this->tpl->setVariable('ITEM-CLASS','class="open"');
					}
					
					$this->tpl->parseCurrentBlock();					
				}
			}
			// only output, no perms to check
			else if($this->cat_is_not_empty[$i])
			{
				$this->tpl->setCurrentBlock('MAINMENU_ENTRY');
				$this->tpl->setVariable('LINK-HREF',$this->mainmenu[$i]['url']);
				$this->tpl->setVariable('LINK-TITLE',$this->mainmenu[$i]['title']);
				
				if ($this->active_submenu_layer-1==$i)
				{
					$this->tpl->setVariable('LINK-CLASS','class="active"');
				}

				$this->tpl->parseCurrentBlock();
			}
		}
		
		$this->tpl->setCurrentBlock('MAINMENU');
		$this->tpl->parseCurrentBlock();
	}

	/**
	 * Set different template variables
	 * @return void
	 */	
	private function _setTemplateVariables()
	{
		$version = explode('.',$this->cfg_cms['version']);
		foreach($version as $index => $version_part)
		{
			$version[$index] = ltrim($version_part, '0');
			
			if(strlen($version[$index]) == 0)
        $version[$index] = "0";
		}
		$tpl_in['VERSION'] = 'v'.implode('.', $version);
		$tpl_in['MAIN_MENU_ENTRYS'] = $out;
		$tpl_in['MAX_SUBMENUS'] = $this->maincount;
		$tpl_in['ACTIVE_SUBMENU_LAYER'] = $this->active_submenu_layer;
		$tpl_in['LOGOUT_URL'] = $this->sess->url('main.php?area=logout');
		$tpl_in['PATH_HELP'] = 'help/index_'.$this->cfg_cms['backend_lang'] .'.php#'. $this->area ;
		$tpl_in['LANG_TOOLTIP'] = addslashes($this->lng['gen_logout']);
		$tpl_in['LNG_LICENCE'] = $this->lng['gen_licence'];
		$tpl_in['HELP_TOOLTIP'] = addslashes($this->lng['cms_help']);
		$tpl_in['LOGOUT_WIDTH'] = $this->lng['gen_logout_wide'];
		
		if(!empty($this->auth->auth['name']) && !empty($this->auth->auth['surname']))
		{
			$tpl_in['LOGGED_USER'] = $this->lng['gen_welcome'] . ', ' . $this->auth->auth['name'] . ' ' .$this->auth->auth['surname'];
		}
		else if(!empty($this->auth->auth['name']))
		{
			$tpl_in['LOGGED_USER'] = $this->lng['gen_welcome'] . ', ' .$this->auth->auth['name'];
		}
		else if(!empty($this->auth->auth['surname']))
		{
			$tpl_in['LOGGED_USER'] = $this->lng['gen_welcome'] . ', '.$this->auth->auth['surname'];
		}
		else
		{
			$tpl_in['LOGGED_USER'] = $this->lng['gen_welcome'] . ', ' . $this->auth->auth['uname'];
		}
		$tpl_in['SKIN'] = $this->cfg_cms['skin'];
		$tpl_in['DELETE_MSG'] = $this->lng['gen_deletealert'];
		
		
		$this->tpl->setCurrentBlock('__global__');
		$this->tpl->setVariable($tpl_in);
	}
	
	/**
	 * Check if JavaScript language variables was added first,
	 * then generate template. 
	 * @see API/VIEWS/SF_VIEW_AbstractView#generate()
	 */
	public function generate()
	{	
		$this->tpl->parse();
		$this->generated_view = $this->tpl->get();
		
		return TRUE;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see API/INTERFACES/SF_INTERFACE_View#get($clear_cache)
	 */
	public function get($clear_cache = FALSE)
	{
		if($this->generated_view == FALSE || $clear_cache == TRUE)
		{
			$this->generate();
		}
		
		return $this->generated_view;
	}
}
?>