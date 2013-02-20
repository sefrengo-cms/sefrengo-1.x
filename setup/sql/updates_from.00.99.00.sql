#02.06.2004 insert repair repository dependency
INSERT INTO cms_values VALUES ('', 0, 0, 'rep', 'repository_auto_repair_dependency', '', '', '', '1', 160, 'set_auto_repair_dependency', '', 'txt', NULL, NULL, 1);
UPDATE cms_values SET conf_sortindex = 120 WHERE group_name = 'rep' and key1 = 'repository_loopback';

#12.06.2004 set new versionnumber
UPDATE cms_values  SET value =  '01.00.00' WHERE group_name =  'cfg' AND key1 =  'version';

#14.06.2004 fuer Sessionmodule/Plugins
ALTER TABLE cms_sessions ADD user_id INT(11) NOT NULL;

#22.06 recht für seite für andere redakteure sperren - entfernt
DELETE FROM cms_values WHERE group_name = 'user_perms' AND key1 = 'side' AND key2 = '31';
DELETE FROM cms_values WHERE group_name = 'user_perms' AND key1 = 'cat' AND key2 = '31';
DELETE FROM cms_values WHERE group_name = 'user_perms' AND key1 = 'area_con' AND key2 = '31';

# 24.06. cms_values für Dateimanager umstellen - wegen Fehler in der projektvorlage.sql/projektupload.sql
UPDATE cms_values SET value = '0' WHERE value = 'false' AND group_name = 'cfg_client' AND key1 IN ('remove_empty_directories','remove_files_404','fm_delete_ignore_404');
UPDATE cms_values SET value = '1' WHERE value = 'true'  AND group_name = 'cfg_client' AND key1 IN ('remove_empty_directories','remove_files_404','fm_delete_ignore_404');

# 28.06. cms_value für Dateimanagement
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'gzip_enabled', '', '', '', '1', 303, 'set_gzip_enable', NULL, 'txt', NULL, NULL, 1);

# Alten Code entfernen und Tabellen optimieren
DELETE FROM cms_code;
DELETE FROM cms_db_cache;