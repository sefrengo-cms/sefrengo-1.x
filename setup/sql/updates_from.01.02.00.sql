#01.01.2006 set new versionnumber - sefrengo 1.2.1
UPDATE cms_values  SET value =  '01.02.01' WHERE group_name =  'cfg' AND key1 =  'version';

#01.01.2006  correct utf-8 convert problem
UPDATE cms_mod SET config='' WHERE config= '=';

#01.01.2006  correct 'cms://'- Link convert problem
DELETE FROM cms_content WHERE `value` = 'cms://idcatside=0';
DELETE FROM cms_content WHERE `value` = 'cms://idcat=0';

#01.01.2006  delete some old cfg_client values
DELETE FROM cms_values WHERE group_name = 'cfg_client' AND `key1` IN ('meta_description', 'meta_keywords', 'meta_robots');

#01.01.2006 rename old dedi langstring in system settings (again - in some versions theire where problems with the updates_from.01.92.00.sql)
UPDATE cms_values  SET conf_desc_langstring =  'set_cms_path' WHERE conf_desc_langstring =  'set_dedi_path';

#02.01.2006
#make automatic updates from dediflex to cmsflex possible
UPDATE cms_mod SET repository_id = 'mod:2569fffccf82046a8e2f73f7e9de03b0:00000000' WHERE repository_id LIKE ('mod:27c2c0f9459f80a760b14dcc3ea917a8:%');
#make automatic updates from deditag to cmstag eingabefeld possible
UPDATE cms_mod SET repository_id = 'mod:e51c54f98b5032ea90502cd725250c05:00000000' WHERE repository_id LIKE ('mod:5236a828296b056b6c0c911442e0f89c:%');
