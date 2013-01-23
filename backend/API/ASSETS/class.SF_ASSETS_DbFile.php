<?php

class SF_ASSETS_DbFile extends SF_API_Object {
	var $db;
	var $db_names;
	var $data = array( 'file' => array(
							'idupl'=>'',
							'idclient'=> 0,
							'filename'=>'',
							'iddirectory'=>'',
							'idfiletype'=>'',
							'pictwidth'=>'',
							'pictheight'=>'',
							'pictthumbwidth'=>'',
							'pictthumbheight'=>'',
							'filesize'=>'',
							'status'=> 0,
							'titel'=> '',
							'description'=>'',
							'author'=>'',
							'created'=>'',
							'lastmodified'=>''
						),
						'filetype' => array(
							'filetype'=>'',
							'mimetype'=>'',		
						),
						'directory' => array(
							'name'=>'',
							'dirname'=>''
						),
						'mixed' => array(
							'filepath_relative'=>'',
							'filepath_absolute'=>'',
							'httppath_relative'=>'',
							'httppath_absolute'=>'',
							'dirpath_absolute'=> ''
						)
				);
	// load external source file - empty or filename
	var $external_source_file = '';
	
	var $_overwrite_existing_file = true;
	var $_autorename_if_exists = false;
	
	var $_dirty = false;
	
	function SF_ASSETS_DbFile() {
		global $cms_db, $client;	
		$this->db =& $GLOBALS['sf_factory']->getObject('DATABASE', 'Ado');
		$this->db_names =& $cms_db;
		$this->data['file']['idclient'] = $client;
	}
	
	function loadByIdupl($id) {
		$id = (int) $id;
		
		if ($id < 1) {
			return false;
		}
		
		$sql = "SELECT 
					U.idupl, U.idclient, U.titel, U.filename, U.iddirectory, U.idfiletype, U.used, 
					U.status, U.filesize, U.description, U.pictwidth, U.pictheight, U.pictthumbwidth, 
					U.pictthumbheight, U.author, UNIX_TIMESTAMP(U.created) created, 
					UNIX_TIMESTAMP(U.lastmodified) lastmodified, D.name, D.dirname,
					F.filetype, F.mimetype
				FROM 
					".$this->db_names['upl']." U
					LEFT JOIN ".$this->db_names['directory']." D USING (iddirectory)
					LEFT JOIN ".$this->db_names['filetype']." F ON (U.idfiletype = F.idfiletype)
				WHERE 
					U.idupl='".$id."'";
		$rs = $this->db->Execute($sql);
		if ($rs) {
			if ($rs->RecordCount() == 1) {			
				$this->data = array( 'file' => array(
									'idupl'=> $rs->fields['idupl'],
									'idclient'=> $rs->fields['idclient'],
									'filename'=> $rs->fields['filename'],
									'iddirectory'=> $rs->fields['iddirectory'],
									'idfiletype'=> $rs->fields['idfiletype'],
									'pictwidth'=> $rs->fields['pictwidth'],
									'pictheight'=> $rs->fields['pictheight'],
									'pictthumbwidth'=> $rs->fields['pictthumbwidth'],
									'pictthumbheight'=> $rs->fields['pictthumbheight'],
									'filesize'=> $rs->fields['filesize'],
									'status'=> $rs->fields['status'],
									'description'=> $rs->fields['description'],
									'titel'=> $rs->fields['titel'],
									'author'=> $rs->fields['author'],
									'created'=> $rs->fields['created'],
									'lastmodified'=> $rs->fields['lastmodified']
									),
								'filetype' => array(
									'filetype'=> $rs->fields['filetype'],
									'mimetype'=> $rs->fields['mimetype']
									),
								'directory' => array(
									'name'=> $rs->fields['name'],
									'dirname'=> $rs->fields['dirname']
									)
								);
				$this->_buildPathes();
			} else {
				return false;
			}
		} else {
			return false;
		}
		
		return true;
	}
	
	
	function getIdupl() {return $this->data['file']['idupl'];}
	function getIdclient() {return $this->data['file']['idclient'];}
	function getFilename() {return $this->data['file']['filename'];}
	function getTitle() {return $this->data['file']['titel'];}
	function getIddirectory() {return $this->data['file']['iddirectory'];}
	function getIdfiletype() {return $this->data['file']['idfiletype'];}
	function getPictwidth() {return $this->data['file']['pictwidth'];}
	function getPictheight() {return $this->data['file']['pictheight'];}
	function getPictthumbwidth() {return $this->data['file']['pictthumbwidth'];}
	function getPictthumbheight() {return $this->data['file']['pictthumbheight'];}
	function getFilesize() {return $this->data['file']['filesize'];}
	function getStatus() {return $this->data['file']['status'];}
	function getDescription() {return $this->data['file']['description'];}
	function getIdAuthor() {return $this->data['file']['author'];}
	function getCreated() {return $this->data['file']['created'];}
	function getLastmodified() {return $this->data['file']['lastmodified'];}
	function getFiletype() {return $this->data['filetype']['filetype'];}
	function getMimetype() {return $this->data['filetype']['mimetype'];}
	function getDirectorypathRelative() {return $this->data['directory']['dirname'];}
	function getFilepathRelative() {return $this->data['mixed']['filepath_relative'];}
	function getFilepathAbsolute() {return $this->data['mixed']['filepath_absolute'];}
	function getHttppathRelative() {return $this->data['mixed']['httppath_relative'];}
	function getHttpDirpathRelative() {return $this->data['mixed']['httpdirpath_relative'];}
	function getHttppathAbsolute() {return $this->data['mixed']['httppath_absolute'];}
	function getDirpathAbsolute() {return $this->data['mixed']['dirpath_absolute'];}
	function getCmsLink() {return 'cms://idfile='.$this->data['file']['idupl'];}
	function getCmsThumbLink() {return 'cms://idfilethumb='.$this->data['file']['idupl'];}
	function getExternalSourcefile() {return $this->external_source_file;}
	
	
	//function setFilename($value) {return $this->_set('file', 'filename', $value);}
	function setIddirectory($value) {return $this->_set('file', 'iddirectory', (int) $value);}
	function setTitle($value) {return $this->_set('file', 'titel', $value);}
	function setDescription($value) {return $this->_set('file', 'description', $value);}

	function setOverwriteExistingFile($b) { $this->_overwrite_existing_file = (boolean) $b; }
	function setAutorenameIfExists($b) { $this->_autorename_if_exists = (boolean) $b; }
	function setExternalSourceFile($file, $mimetype = '') {
		if (! is_file($file)) {
			return false;
		}
		
		$this->external_source_file = $file;
		if ($mimetype != '') {
			$this->data['filetype']['mimetype'] = $mimetype;
		}
		$this->_dirty = true;
		return true;
	}
	
	function _set($group, $key, $value) {
		if ($this->data[$group][$key] != $value) {
			$this->data[$group][$key] = $value;
			$this->_dirty = true;
			return true;
		}
		
		return false;
	}
	
	
	function save() {
		if (! $this->_dirty) {
			return false;
		}
		
		//check iddirectory
		if ($this->data['file']['iddirectory'] < 1) {
			return false;
		}
		
		$timestamp = time();
		
		//find filesource
		$file = '';
		if ($this->external_source_file != '') {
			$fileinfos = pathinfo($this->external_source_file);
			$this->data['file']['filename'] = $fileinfos['basename'];
			$this->data['file']['filesize'] = filesize($this->external_source_file);
			$this->data['filetype']['filetype'] = strtolower($fileinfos['extension']);			 
		}
		
		//handle directory
		$sql = "SELECT 
					name, dirname 
				FROM 
					".$this->db_names['directory']." 
				WHERE 
					iddirectory = '".$this->data['file']['iddirectory']."'" ;
		$rs = $this->db->Execute($sql);
		if ($rs) {
			if($rs->RecordCount() == 1) {
				$this->data['directory']['name'] = $rs->fields['name'];
				$this->data['directory']['dirname'] = $rs->fields['dirname'];
				
				//build pathes
				$this->_buildPathes();
			} else {
				return false;
			}
		} else {
			return false;
		}
		
		//copy file physical
		if ($this->external_source_file != '') {
			if ($this->_autorename_if_exists && is_file($this->data['mixed']['filepath_absolute'])) {
				$new_filename = preg_replace('#\.'.$this->data['filetype']['filetype'].'$#', '', $this->data['file']['filename']);
				for ($i = 2; is_file($this->data['mixed']['dirpath_absolute'].$new_filename.$i.'.'.$this->data['filetype']['filetype']); ++$i) { 
					/* count me */
				}
				$this->data['file']['filename'] = $new_filename.$i.'.'.$this->data['filetype']['filetype'];
				$this->_buildPathes();
			} else if (! $this->_overwrite_existing_file && is_file($this->data['mixed']['filepath_absolute'])) {
				return false;
			}
			if (!copy($this->external_source_file, $this->data['mixed']['filepath_absolute'])) {
	    		return false;
			}
		}
		
		//handle filetype
		$sql = "SELECT 
					idfiletype, filetype , mimetype
				FROM 
					".$this->db_names['filetype']." 
				WHERE 
					filetype='".$this->data['filetype']['filetype']."'";
		
		$rs = $this->db->Execute($sql);
		
		
		if($rs) { 
			if($rs->RecordCount() > 0) {
				$this->data['file']['idfiletype'] = $rs->fields['idfiletype'];
				$this->data['filetype']['filetype'] = $rs->fields['filetype'];
				$this->data['filetype']['mimetype'] = $rs->fields['mimetype'];

			} else if ($this->data['filetype']['filetype'] != ''){
				if ($this->data['filetype']['mimetype'] == '') {
					$this->data['filetype']['mimetype'] = 'x-cms/x-unknown';
				}
				
				$this->data['filetype']['created'] = $timestamp;
				$this->data['filetype']['lastmodified'] = $timestamp;
				$this->db->AutoExecute($this->db_names['filetype'], $this->data['filetype'], 'INSERT');
				$this->data['file']['idfiletype'] = $this->db->Insert_ID();
			}
		} else {
			return false;
		}
		$o =& $GLOBALS['sf_factory']->getObjectForced('ASSETS', 'DbFileAddonFactory');
		$o->setFileObject($this);
		$addon =& $o->getAddonObject();
		
		//handle file
		if ($this->data['file']['idupl'] > 0) {
			$addon->updateFile();
			$this->data['file']['lastmodified'] = $timestamp;
			$this->db->AutoExecute($this->db_names['upl'], $this->data['file'], 'UPDATE', 
											"idupl = '".$this->data['file']['idupl']."'");
		} else {
			global $auth;
			$addon->newFile();
			$this->data['file']['created'] = $timestamp;
			$this->data['file']['lastmodified'] = $timestamp;
			$this->data['file']['author'] = ((int) $auth->auth['uid']==0) ? 2 : $auth->auth['uid'];
			$this->db->AutoExecute($this->db_names['upl'], $this->data['file'], 'INSERT');
			$this->data['file']['idupl'] = $this->db->Insert_ID();
		}
		
		
		$this->_dirty = false;
		return true;
	}
	
	function delete() {
		$sql ="DELETE FROM 
					".$this->db_names['upl']." 
				WHERE 
					idupl='".$this->data['file']['idupl']."'";
		
		 $this->db->Execute($sql);
		 
		 unlink($this->data['mixed']['filepath_absolute']);
		 
		 $o =& $GLOBALS['sf_factory']->getObjectForced('ASSETS', 'DbFileAddonFactory');
		 $o->setFileObject($this);
		 $addon =& $o->getAddonObject();
		 $addon->deleteFile();
		 
		 $this->data['file']['idupl'] = false;
	}
	
	function _buildPathes() {
		global $cfg_client;
		$this->data['mixed']['filepath_relative'] = str_replace($cfg_client['path'], '', $cfg_client['upl_path']) 
														. $this->data['directory']['dirname'].$this->data['file']['filename'];
		$this->data['mixed']['filepath_absolute'] = $cfg_client['upl_path'].$this->data['directory']['dirname'].$this->data['file']['filename'];
		$this->data['mixed']['dirpath_absolute'] = $cfg_client['upl_path'].$this->data['directory']['dirname'];
		$this->data['mixed']['httpdirpath_relative']= str_replace($cfg_client['htmlpath'], '', $cfg_client['upl_htmlpath']) 
														. $this->data['directory']['dirname'];
		$this->data['mixed']['httppath_relative']= $this->data['mixed']['httpdirpath_relative'].$this->data['file']['filename'];
		$this->data['mixed']['httppath_absolute'] = $cfg_client['upl_htmlpath'].$this->data['directory']['dirname'].$this->data['file']['filename'];
	}
}

?>