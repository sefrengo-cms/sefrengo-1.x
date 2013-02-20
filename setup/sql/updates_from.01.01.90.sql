#11.10.2004 set new versionnumber - sefrengo 1.2 alpha2
UPDATE cms_values  SET value =  '01.01.91' WHERE group_name =  'cfg' AND key1 =  'version';

#10.10.2005 - new perms
# perm set startlang
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_clients', '28', NULL, NULL, '134217728', 280, 'group_area_clients_28', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clients', '28', NULL, NULL, '134217728', 280, 'group_clients_28', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clientlangs', '28', NULL, NULL, '134217728', 280, 'group_clientlangs_28', '', 'txt', NULL, NULL, 0);
# perm set starttpl
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_tpl', '12', NULL, NULL, '2048', 120, 'group_area_tpl_12', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'tpl', '12', NULL, NULL, '2048', 120, 'group_tpl_12', '', 'txt', NULL, NULL, 0);
# add session cookie domain
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'session_backend_domain', '', '', '', '', 107, 'set_session_backend_domain', NULL, 'txt', NULL, NULL, '1');

