# phpMyAdmin MySQL-Dump

# Host: localhost

# --------------------------------------------------------

# cms config

INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'cms_path', NULL, NULL, NULL, '<!--{cms_path}-->backend/', 100, 'set_cms_path', NULL, 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'cms_html_path', NULL, NULL, NULL, '<!--{cms_full_http_path}-->backend/', 101, 'set_html_path', NULL, 'txt', NULL, NULL, '1');

# client konfiguration
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'path', '', '', '', '<!--{cms_path}-->projekt01/', 100, 'setuse_path', 'setuse_pathes', 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'htmlpath', '', '', '', '<!--{cms_full_http_path}-->projekt01/', 101, 'setuse_html_path', NULL, 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'space', '', '', '', '<!--{cms_full_http_path}-->projekt01/cms/img/space.gif', 105, 'setuse_space', NULL, 'txt', NULL, NULL, '1');


INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'upl_path', '', '', '', '<!--{cms_path}-->projekt01/media/', 300, 'setuse_upl_path', 'setuse_filemanager', 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'upl_htmlpath', '', '', '', '<!--{cms_full_http_path}-->projekt01/media/', 301, 'setuse_upl_htmlpath', NULL, 'txt', NULL, NULL, '1');
