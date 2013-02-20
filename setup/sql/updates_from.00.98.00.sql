# 12.04. cms_values für Dateimanager umstellen
UPDATE cms_values SET value = '0' WHERE value = 'false' AND group_name = 'cfg_client' AND key1 IN ('remove_empty_directories','remove_files_404','fm_delete_ignore_404');
UPDATE cms_values SET value = '1' WHERE value = 'true'  AND group_name = 'cfg_client' AND key1 IN ('remove_empty_directories','remove_files_404','fm_delete_ignore_404');

#12.04.2004 set new versionnumber
UPDATE cms_values  SET value =  '00.99.00' WHERE group_name =  'cfg' AND key1 =  'version';

#16.5.2004 enable db cache
UPDATE cms_values SET value = '1' WHERE group_name = 'cfg' AND key1 IN ('db_cache_enabled');
#17.5.2004 update cms_cache
ALTER TABLE cms_db_cache ADD `item` VARCHAR( 25 ) ;
UPDATE cms_values SET conf_head_langstring = NULL, conf_visible = 0 WHERE group_name = 'cfg' and key1 = 'db_cache_groups' and key2 = 'default';
UPDATE cms_values SET conf_head_langstring = NULL, conf_visible = 0 WHERE group_name = 'cfg' and key1 = 'db_cache_groups' and key2 = 'standard';
UPDATE cms_values SET conf_head_langstring = 'set_db_cache_groups', conf_visible = 1, key2 = 'frontend', conf_desc_langstring = 'set_db_cache_group_frontend', value = '0' WHERE group_name = 'cfg' and key1 = 'db_cache_groups' and key2 = 'frontend_tree';
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'db_cache_items', 'frontend', 'tree', '', '1440', 840, 'set_db_cache_item_tree', 'set_db_cache_items', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'db_cache_items', 'frontend', 'content', '', '1440', 850, 'set_db_cache_item_content', NULL, 'txt', NULL, NULL, 0);

#bugfix rechte area_mod modul konfigurieren
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '4', NULL, NULL, '8', 40, 'group_area_mod_4', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'mod', '4', NULL, NULL, '8', 40, 'group_mod_4', '', 'txt', NULL, NULL, 0);

#24.5.2004 alter Module/Plugins Namespace
ALTER TABLE cms_mod ADD verbose varchar(100) default NULL; 
ALTER TABLE cms_plug ADD verbose varchar(100) default NULL; 

#24.5.2004 insert optimece tables
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'db_optimice_tables', 'enable', '', '', '1', 900, 'set_db_optimice_tables_enable', 'set_db_optimice_tables', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'db_optimice_tables', 'last_run', '', '', '0', 910, 'set_db_optimice_tables_lastrun', NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'db_optimice_tables', 'time', '', '', '39600', 920, 'set_db_optimice_tables_time', NULL, 'txt', NULL, NULL, 0);