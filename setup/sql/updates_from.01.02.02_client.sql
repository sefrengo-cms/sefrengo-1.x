# 18.05.2006 - displace value conf_sortindex numbers between 208 and 250 to make space for new conf values
UPDATE `cms_values` SET `conf_sortindex` = `conf_sortindex`+10 WHERE `group_name` = 'cfg_client' AND idclient = '{idclient}' AND `conf_sortindex` BETWEEN 208 AND 250;
INSERT INTO `cms_values` ( `idvalues` , `idclient` , `idlang` , `group_name` , `key1` , `key2` , `key3` , `key4` , `value` , `conf_sortindex` , `conf_desc_langstring` , `conf_head_langstring` , `conf_input_type` , `conf_input_type_val` , `conf_input_type_langstring` , `conf_visible` )
VALUES (
NULL , '{idclient}', '0', 'cfg_client', 'url_langid_in_defaultlang', '', '', '', '0', '210', 'setuse_url_langid_in_defaultlang', NULL , 'txt', NULL , NULL , '1'
);
INSERT INTO `cms_values` ( `idvalues` , `idclient` , `idlang` , `group_name` , `key1` , `key2` , `key3` , `key4` , `value` , `conf_sortindex` , `conf_desc_langstring` , `conf_head_langstring` , `conf_input_type` , `conf_input_type_val` , `conf_input_type_langstring` , `conf_visible` )
VALUES (
NULL , '{idclient}', '0', 'cfg_client', 'url_rewrite_suffix', '', '', '', '.html', '211', 'setuse_url_rewrite_suffix', NULL , 'txt', NULL , NULL , '1'
);
INSERT INTO `cms_values` (`idvalues`, `idclient`, `idlang`, `group_name`, `key1`, `key2`, `key3`, `key4`, `value`, `conf_sortindex`, `conf_desc_langstring`, `conf_head_langstring`, `conf_input_type`, `conf_input_type_val`, `conf_input_type_langstring`, `conf_visible`) VALUES (NULL, '{idclient}', '0', 'cfg_client', 'session_disabled_useragents', '', '', '', 'Googlebot
Yahoo
Scooter
FAST-WebCrawler
MSNBOT
Seekbot
Inktomi
Lycos_Spider
Ultraseek
Overture
Slurp
Sidewinder
Metaspinner
Jeeves
WISEnutbot
Zealbot
ia_archiver
AbachoBOT
Firefly', '214', 'setuse_session_disabled_useragents', NULL, 'txtarea', NULL, NULL, '1');
INSERT INTO `cms_values` (`idvalues`, `idclient`, `idlang`, `group_name`, `key1`, `key2`, `key3`, `key4`, `value`, `conf_sortindex`, `conf_desc_langstring`, `conf_head_langstring`, `conf_input_type`, `conf_input_type_val`, `conf_input_type_langstring`, `conf_visible`) VALUES (NULL, '{idclient}', '0', 'cfg_client', 'session_disabled_ips', '', '', '', '127.0.0.98
127.0.0.99', '216', 'setuse_session_disabled_ips', NULL, 'txtarea', NULL, NULL, '1');

# 18.05.2006 rewrite path value
INSERT INTO cms_values VALUES ('', '{idclient}', 0, 'cfg_client', 'url_rewrite_basepath', '', '', '', '', '212', 'setuse_url_rewrite_basepath', NULL , 'txt', NULL , NULL , '1');

#29.05.2006 rewrite error page
INSERT INTO cms_values VALUES ('', {idclient}, 0, 'cfg_client', 'url_rewrite_404', '', '', '', '0', '213', 'setuse_url_rewrite_404', NULL , 'txt', NULL , NULL , '1');
