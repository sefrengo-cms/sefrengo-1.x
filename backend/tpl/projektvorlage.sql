INSERT INTO <!--{db_prefix}-->clients VALUES (<!--{idclient}-->, '<!--{projectname}-->', '<!--{projectdesc}-->', <!--{userid}-->, <!--{time}-->, 0);
INSERT INTO <!--{db_prefix}-->directory VALUES ('', <!--{idclient}-->, 'css', 'cms/css/', '', 0, 4, <!--{userid}-->, <!--{time}-->, 0);
INSERT INTO <!--{db_prefix}-->directory VALUES ('', <!--{idclient}-->, 'js', 'cms/js/', '', 0, 4, <!--{userid}-->, <!--{time}-->, 0);

INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'path', '', '', '', '<!--{cms_path}-->', 100, 'setuse_path', 'setuse_pathes', 'txt', NULL, NULL, '1');
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'htmlpath', '', '', '', '<!--{cms_full_http_path}-->', 101, 'setuse_html_path', NULL, 'txt', NULL, NULL, '1');
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'contentfile', '', '', '', 'index.php', 102, 'setuse_contentfile', NULL, 'txt', NULL, NULL, '1');
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'space', '', '', '', '<!--{cms_full_http_path}-->cms/img/space.gif', 105, 'setuse_space', NULL, 'txt', NULL, NULL, '1');
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'session_enabled', '', '', '', '1', 201, 'set_session_frontend_enabled', 'setuse_general', 'txt', NULL, NULL, '1');
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'session_lifetime', '', '', '', '15', 202, 'set_session_frontend_lifetime', '', 'txt', NULL, NULL, '1');
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'publish', '', '', '', '0', 203, 'setuse_publish', NULL, 'txt', NULL, NULL, '1');
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'edit_mode', '', '', '', '0', 204, 'setuse_edit_mode', NULL, 'txt', NULL, NULL, '0');
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'default_layout', '', '', '', '<html>\r\n<head>\r\n<cms:lay type="head"/>\r\n</head>\r\n<body>\r\n<cms:lay type="container" id="1" title="Seiten-Content"/>\r\n\r\n<cms:lay type="config"/>\r\n<cms:lay type="foot"/>\r\n</body>\r\n</html>', 206, 'setuse_default_layout', NULL, 'txtarea', NULL, NULL, 1);
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'url_rewrite', '', '', '', '0', 207, 'setuse_url_rewrite', NULL, 'txt', NULL, NULL, 1);
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'url_langid_in_defaultlang', '', '', '', '0', '210', 'setuse_url_langid_in_defaultlang', NULL , 'txt', NULL , NULL , '1');
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'url_rewrite_suffix', '', '', '', '.html', '211', 'setuse_url_rewrite_suffix', NULL , 'txt', NULL , NULL , '1');
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'url_rewrite_basepath', '', '', '', '<!--{cms_full_http_path}-->', '212', 'setuse_url_rewrite_basepath', NULL , 'txt', NULL , NULL , '1');
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'url_rewrite_404', '', '', '', '0', '213', 'setuse_url_rewrite_404', NULL , 'txt', NULL , NULL , '1');
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'session_disabled_useragents', '', '', '', 'Googlebot\r\nYahoo\r\nScooter\r\nFAST-WebCrawler\r\nMSNBOT\r\nSeekbot\r\nInktomi\r\nLycos_Spider\r\nUltraseek\r\nOverture\r\nSlurp\r\nSidewinder\r\nMetaspinner\r\nJeeves\r\nWISEnutbot\r\nZealbot\r\nia_archiver\r\nAbachoBOT\r\nFirefly', '214', 'setuse_session_disabled_useragents', NULL, 'txtarea', NULL, NULL, '1');
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'session_disabled_ips', '', '', '', '127.0.0.98\r\n127.0.0.99', '216', 'setuse_session_disabled_ips', NULL, 'txtarea', NULL, NULL, '1');


INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'manipulate_output', '', '', '', 'echo $output;', 208, 'setuse_manipulate_output', NULL, 'txtarea', NULL, NULL, 1);
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'errorpage', '', '', '', '0', 209, 'setuse_errorpage', NULL, 'txt', NULL, NULL, 1);
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'loginpage', '', '', '', '0', 210, 'setuse_loginpage', NULL, 'txt', NULL, NULL, 1);
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'cache', '', '', '', '1', 211, 'setuse_cache', NULL, 'txt', NULL, NULL, 1);
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'session_frontend_domain', '', '', '', '', 222, 'setuse_session_frontend_domain', NULL, 'txt', NULL, NULL, '1');

INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'upl_path', '', '', '', '<!--{cms_path}-->media/', 300, 'setuse_upl_path', 'setuse_filemanager', 'txt', NULL, NULL, '1');
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'upl_htmlpath', '', '', '', '<!--{cms_full_http_path}-->media/', 301, 'setuse_upl_htmlpath', NULL, 'txt', NULL, NULL, '1');
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'upl_forbidden', '', '', '', 'php, htaccess, htpasswd, css, js', 302, 'setuse_forbidden', NULL, 'txt', NULL, NULL, '1');
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'thumb_size', '', '', '', '100', 303, 'setuse_thumb_size', '', 'txt', NULL, NULL, '1');
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'thumb_aspectratio', '', '', '', '1', 304, 'setuse_thumb_aspectratio', '', 'txt', NULL, NULL, '1');
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'thumbext', '', '', '', '_cms_thumb', 305, 'setuse_thumb_ext', '', 'txt', NULL, NULL, 1);
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'fm_delete_ignore_404', '', '', '', '1', 306, 'setuse_fm_delete_ignore_404', '', 'txt', NULL, NULL, 1);
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'upl_addon', '', '', '', 'gif,jpg,jpeg,png', 310, 'setuse_upl_addon', '', 'txt', '', '', '1');
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'remove_files_404', '', '', '', '1', 315, 'setuse_remove_files_404', '', 'txt', '', '', '1');
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'remove_empty_directories', '', '', '', '0', 316, 'setuse_remove_empty_directories', '', 'txt', '', '', 1);
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'css_sort_original', '', '', '', '0', 400, 'setuse_css_sort_original', 'setuse_css', 'txt', '', '', '1');
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'css_checking', '', '', '', '1', 405, 'setuse_csschecking', '', 'txt', NULL, NULL, 1);
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'css_ignore_rules_with_errors', '', '', '', '0', 410, 'setuse_css_ignore_error_rules', '', 'txt', NULL, NULL, 1);
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'max_count_scandir', '', '', '', '10', 0, '', '', 'txt', NULL, NULL, 0);
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'extend_time_scandir', '', '', '', '60', 0, '', '', 'txt', NULL, NULL, 0);
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'max_count_scanfile', '', '', '', '2', 0, '', '', 'txt', NULL, NULL, 0);
INSERT INTO <!--{db_prefix}-->values VALUES ('', <!--{idclient}-->, 0, 'cfg_client', 'max_count_scanthumb', '', '', '', '10', 0, '', '', 'txt', NULL, NULL, 0);
