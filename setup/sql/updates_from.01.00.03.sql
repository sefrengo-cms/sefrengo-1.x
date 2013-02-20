#11.10.2004 set new versionnumber - second bugfix-version for dedi 1.0
UPDATE cms_values  SET value =  '01.01.90' WHERE group_name =  'cfg' AND key1 =  'version';

#09.05.2005 Tabelle cms_type wird nicht mehr benötigt
DROP TABLE IF EXISTS cms_type;

#10.07.2005 add is_start coloum to tpl table
ALTER TABLE cms_tpl ADD is_start TINYINT( 1 ) UNSIGNED DEFAULT '0' NOT NULL AFTER description ;

#10.07.2005 add is_start and iso_3166_code coloums to lang table
ALTER TABLE cms_lang ADD iso_3166_code VARCHAR( 8 ) AFTER charset , ADD is_start ENUM( '0', '1' ) DEFAULT '0' NOT NULL AFTER iso_3166_code;

#29.07.2005 replace old dedilinks to new cms:// links in cms_content for images, links and files
UPDATE cms_content SET value = REPLACE (value, 'http://dedilink/fileid=', 'cms://idfile=');
UPDATE cms_content SET value = REPLACE (value, 'http://dedilink/idcat=', 'cms://idcat=');
UPDATE cms_content SET value = REPLACE (value, 'http://dedilink/idcatside=', 'cms://idcatside=');

UPDATE cms_lay SET code = REPLACE (code, 'http://dedilink/fileid=', 'cms://idfile=');
UPDATE cms_lay SET code = REPLACE (code, 'http://dedilink/idcat=', 'cms://idcat=');
UPDATE cms_lay SET code = REPLACE (code, 'http://dedilink/idcatside=', 'cms://idcatside=');

UPDATE cms_lay SET code = REPLACE (code, '<DEDIPHP>', '<CMSPHP>');
UPDATE cms_lay SET code = REPLACE (code, '</DEDIPHP>', '</CMSPHP>');
UPDATE cms_lay SET code = REPLACE (code, '<dedi:lay', '<cms:lay');
UPDATE cms_lay SET code = REPLACE (code, '<DEDI:LAY', '<cms:lay');

#10.08.2005 Delete wysiwyg chooser key
DELETE FROM cms_values WHERE group_name='cfg_client' AND key1='wysiwyg_applet';

#03.09.2005 Add new doctype fields
ALTER TABLE cms_lay ADD `doctype` VARCHAR( 63 ) AFTER `code` , ADD `doctype_autoinsert` TINYINT DEFAULT '0' AFTER `doctype` ;

#29.09.2005 convert path vars 
UPDATE cms_values  SET key1 =  'cms_path' WHERE group_name =  'cfg' AND key1 =  'dedi_path';
UPDATE cms_values  SET key1 =  'cms_html_path' WHERE group_name =  'cfg' AND key1 =  'dedi_html_path';

#29.09.2005 Replace image link and file ids with cms://links
UPDATE cms_content SET value = CONCAT('cms://idfile=', value) where value REGEXP '^[[:digit:]]+$' AND `idtype` = 4;
UPDATE cms_content SET value = CONCAT('cms://idcatside=', value) where value REGEXP '^[[:digit:]]+$' AND `idtype` = 6;
UPDATE cms_content SET value = CONCAT('cms://idfile=', value) where value REGEXP '^[[:digit:]]+$' AND `idtype` = 10;

#08.11.2005 change dedi_access to cms_access
UPDATE cms_perms SET  type='cms_access' WHERE type='dedi_access';
UPDATE cms_values SET key1='cms_access' WHERE key1='dedi_access';

