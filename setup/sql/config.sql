# phpMyAdmin MySQL-Dump

# Host: localhost

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `cms_values`
#
DROP TABLE IF EXISTS cms_values;
CREATE TABLE `cms_values` (
  `idvalues` int(6) NOT NULL auto_increment,
  `idclient` int(6) NOT NULL default '0',
  `idlang` int(6) NOT NULL default '0',
  `group_name` varchar(63) NOT NULL default '0',
  `key1` varchar(63) NOT NULL default '',
  `key2` varchar(63) default NULL,
  `key3` varchar(63) default NULL,
  `key4` varchar(63) default NULL,
  `value` text,
  `conf_sortindex` int(11) NOT NULL default '0',
  `conf_desc_langstring` varchar(127) default NULL,
  `conf_head_langstring` varchar(127) default NULL,
  `conf_input_type` varchar(127) NOT NULL default 'txt',
  `conf_input_type_val` varchar(255) default NULL,
  `conf_input_type_langstring` varchar(255) default NULL,
  `conf_visible` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`idvalues`),
  KEY `group_name` (`group_name`),
  KEY `key1` (`key1`),
  KEY `key2` (`key2`),
  KEY `key3` (`key3`),
  KEY `key4` (`key4`),
  KEY `conf_sortindex` (`conf_sortindex`)
) ENGINE=MyISAM AUTO_INCREMENT=4195 ;

#
# Daten für Tabelle `cms_values`
#

# css editor regexp und werte
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'word-spacing', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'word-spacing', 'position', '', '', '(?:\\-?öREGEXP0ö)|öREGEXP1ö', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'word-spacing', 'units', '1', '', 'normal', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'word-spacing', 'units', '0', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'width', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'width', 'position', '', '', '(?:öREGEXP0ö)|(?:öREGEXP1ö)|öREGEXP2ö', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'width', 'units', '2', '', 'auto', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'width', 'units', '1', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'width', 'units', '0', '', 'percent', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'white-space', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'white-space', 'position', '', '', 'öREGEXP0ö|öREGEXP1ö', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'white-space', 'units', '1', '', 'normal', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'white-space', 'units', '0', '', 'whitespace', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'vertical-align', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'vertical-align', 'position', '', '', 'öREGEXP0ö|(?:\\-?öREGEXP1ö)', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'vertical-align', 'units', '1', '', 'percent', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'vertical-align', 'units', '0', '', 'valign', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'text-transform', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'text-transform', 'position', '', '', 'öREGEXP0ö|öREGEXP1ö', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'text-transform', 'units', '1', '', 'none', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'text-indent', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'text-transform', 'units', '0', '', 'ttrans', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'text-indent', 'position', '', '', '\\-?(?:(?:öREGEXP0ö)|(?:öREGEXP1ö))', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'text-indent', 'units', '1', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'text-indent', 'units', '0', '', 'percent', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'text-decoration', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'text-decoration', 'position', '', '', '(öREGEXP0ö|öREGEXP1ö)', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'text-align', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'text-decoration', 'units', '1', '', 'none', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'text-decoration', 'units', '0', '', 'tdeco', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'padding-top', 'position', '', '', '(?:öREGEXP0ö)|(?:öREGEXP1ö)', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'padding-top', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'text-align', 'units', '0', '', 'talign', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'padding-right', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'padding-top', 'units', '1', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'padding-top', 'units', '0', '', 'percent', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'padding-right', 'position', '', '', '(?:öREGEXP0ö)|(?:öREGEXP1ö)', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'padding-left', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'padding-right', 'units', '1', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'padding-right', 'units', '0', '', 'percent', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'padding-left', 'position', '', '', '(?:öREGEXP0ö)|(?:öREGEXP1ö)', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'padding-bottom', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'padding-left', 'units', '1', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'padding-left', 'units', '0', '', 'percent', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'padding-bottom', 'position', '', '', '(?:öREGEXP0ö)|(?:öREGEXP1ö)', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'padding', 'flags', '', '', '100', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'padding-bottom', 'units', '1', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'padding-bottom', 'units', '0', '', 'percent', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'padding', 'position', '', '', '(?:(?:öREGEXP0ö)|(?:öREGEXP1ö)){1,4}', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'padding', 'units', '1', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'padding', 'units', '0', '', 'percent', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'margin-top', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'margin-top', 'units', '2', '', 'auto', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'margin-top', 'position', '', '', '(?:(?:\\-?öREGEXP0ö)|(?:\\-?öREGEXP1ö)|öREGEXP2ö){1,4}', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'margin-right', 'position', '', '', '(?:(?:\\-?öREGEXP0ö)|(?:\\-?öREGEXP1ö)|öREGEXP2ö){1,4}', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'margin-right', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'margin-top', 'units', '1', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'margin-top', 'units', '0', '', 'percent', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'margin-right', 'units', '2', '', 'auto', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'margin-right', 'units', '1', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'margin-right', 'units', '0', '', 'percent', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'margin-left', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'margin-left', 'position', '', '', '(?:(?:\\-?öREGEXP0ö)|(?:\\-?öREGEXP1ö)|öREGEXP2ö){1,4}', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'margin-left', 'units', '2', '', 'auto', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'margin-left', 'units', '1', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'margin-left', 'units', '0', '', 'percent', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'margin-bottom', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'margin-bottom', 'position', '', '', '(?:(?:\\-?öREGEXP0ö)|(?:\\-?öREGEXP1ö)|öREGEXP2ö){1,4}', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'margin-bottom', 'units', '2', '', 'auto', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'margin-bottom', 'units', '1', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'margin-bottom', 'units', '0', '', 'percent', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'margin', 'flags', '', '', '100', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'margin', 'position', '', '', '(?:(?:\\-?öREGEXP0ö)|(?:\\-?öREGEXP1ö)|öREGEXP2ö){1,4}', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'margin', 'units', '2', '', 'auto', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'margin', 'units', '1', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'margin', 'units', '0', '', 'percent', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'list-style-type', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'list-style-type', 'position', '', '', 'öREGEXP0ö|öREGEXP1ö', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'list-style-type', 'units', '1', '', 'none', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'list-style-position', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'list-style-type', 'units', '0', '', 'listtype', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'list-style-image', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'list-style-position', 'units', '0', '', 'listpos', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'list-style-image', 'units', '0', '', 'url', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'list-style-image', 'units', '1', '', 'none', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'list-style-image', 'position', '', '', '(öREGEXP0ö)|öREGEXP1ö', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'list-style', 'flags', '', '', '99', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'list-style', 'units', '2', '', 'listposition', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'list-style', 'units', '3', '', 'listtype', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'list-style', 'position', '', '', '(öREGEXP0ö)|öREGEXP1ö|öREGEXP2ö|öREGEXP3ö', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'list-style', 'units', '1', '', 'none', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'line-height', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'list-style', 'units', '0', '', 'url', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'line-height', 'position', '', '', '(öREGEXP0ö)|(öREGEXP1ö)|(öREGEXP2ö)|(öREGEXP3ö)', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'line-height', 'units', '3', '', 'decimal', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'line-height', 'units', '2', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'line-height', 'units', '0', '', 'percent', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'line-height', 'units', '1', '', 'normal', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'letter-spacing', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'letter-spacing', 'position', '', '', '(\\-?öREGEXP0ö)|öREGEXP1ö', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'letter-spacing', 'units', '1', '', 'normal', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'height', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'letter-spacing', 'units', '0', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'height', 'units', '2', '', 'auto', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'height', 'position', '', '', '(öREGEXP0ö)|(öREGEXP1ö)|öREGEXP2ö', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'font-weight', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'height', 'units', '1', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'height', 'units', '0', '', 'percent', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'font-weight', 'position', '', '', 'öREGEXP0ö|öREGEXP1ö', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'font-variant', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'font-weight', 'units', '1', '', 'normal', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'font-weight', 'units', '0', '', 'fweight', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'font-variant', 'position', '', '', 'öREGEXP0ö|öREGEXP1ö', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'font-variant', 'units', '1', '', 'normal', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'font-variant', 'units', '0', '', 'fvariant', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'font-style', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'font-style', 'units', '1', '', 'normal', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'font-style', 'position', '', '', 'öREGEXP0ö|öREGEXP1ö', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'font-size', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'font-style', 'units', '0', '', 'fstyle', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'font-size', 'position', '', '', '(öREGEXP0ö)|(öREGEXP1ö)|(öREGEXP2ö)', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'font-size', 'units', '2', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'font-size', 'units', '1', '', 'percent', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'font-size', 'units', '0', '', 'fsize', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'font', 'flags', '', '', '0', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'font-family', 'flags', '', '', '0', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'font-family', 'units', '0', '', 'string', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'font', 'units', '0', '', 'string', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'float', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'float', 'position', '', '', 'öREGEXP0ö|öREGEXP1ö', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'float', 'units', '1', '', 'none', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'display', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'float', 'units', '0', '', 'float', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'display', 'position', '', '', 'öREGEXP0ö|öREGEXP1ö', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'display', 'units', '1', '', 'none', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'display', 'units', '0', '', 'display', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'color', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'color', 'units', '0', '', 'color', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'clear', 'units', '1', '', 'none', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'clear', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'clear', 'position', '', '', 'öREGEXP0ö|öREGEXP1ö', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'clear', 'units', '0', '', 'clear', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-width', 'position', '', '', '(?:öREGEXP0ö|(?:öREGEXP1ö)){1,4}', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-width', 'flags', '', '', '116', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-width', 'units', '0', '', 'borderwidth', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-width', 'units', '1', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-top-width', 'units', '1', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-top-width', 'position', '', '', 'öREGEXP0ö|(öREGEXP1ö)', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-top-width', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-top-style', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-top-width', 'units', '0', '', 'borderwidth', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-top-color', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-top-style', 'units', '0', '', 'borderstyle', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-top', 'flags', '', '', '243', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-top-color', 'units', '0', '', 'color', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-top', 'units', '2', '', 'borderstyle', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-top', 'units', '3', '', 'color', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-top', 'position', '', '', '(öREGEXP0ö|(öREGEXP1ö))|(öREGEXP2ö)|(öREGEXP3ö)', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-style', 'units', '0', '', 'borderstyle', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-style', 'flags', '', '', '116', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-top', 'units', '0', '', 'borderwidth', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-top', 'units', '1', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-right-width', 'position', '', '', 'öREGEXP0ö|(öREGEXP1ö)', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-right-width', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-right-width', 'units', '1', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-right-style', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-right-width', 'units', '0', '', 'borderwidth', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-right-color', 'units', '0', '', 'color', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-right-color', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-right-style', 'units', '0', '', 'borderstyle', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-right', 'flags', '', '', '243', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-right', 'position', '', '', '(öREGEXP0ö|(öREGEXP1ö))|(öREGEXP2ö)|(öREGEXP3ö)', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-right', 'units', '2', '', 'borderstyle', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-right', 'units', '3', '', 'color', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-right', 'units', '1', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-right', 'units', '0', '', 'borderwidth', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-left-width', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-left-width', 'units', '0', '', 'borderwidth', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-left-width', 'units', '1', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-left-width', 'position', '', '', 'öREGEXP0ö|(öREGEXP1ö)', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-left-color', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-left-style', 'units', '0', '', 'borderstyle', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-left-style', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-left-color', 'units', '0', '', 'color', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-left', 'position', '', '', '(öREGEXP0ö|(öREGEXP1ö))|(öREGEXP2ö)|(öREGEXP3ö)', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-left', 'flags', '', '', '243', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-left', 'units', '3', '', 'color', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-left', 'units', '2', '', 'borderstyle', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-left', 'units', '1', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-color', 'flags', '', '', '116', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-left', 'units', '0', '', 'borderwidth', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-color', 'position', '', '', '(öREGEXP0ö){1,4}', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-color', 'units', '0', '', 'color', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-bottom-width', 'position', '', '', 'öREGEXP0ö|(öREGEXP1ö)', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-bottom-width', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-bottom-width', 'units', '0', '', 'borderwidth', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-bottom-width', 'units', '1', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-bottom-style', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-bottom-color', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-bottom-style', 'units', '0', '', 'borderstyle', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-bottom-color', 'units', '0', '', 'color', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-bottom', 'flags', '', '', '243', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-bottom', 'position', '', '', '(öREGEXP0ö|(öREGEXP1ö))|(öREGEXP2ö)|(öREGEXP3ö)', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-bottom', 'units', '3', '', 'color', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-bottom', 'units', '1', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-bottom', 'units', '2', '', 'borderstyle', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border-bottom', 'units', '0', '', 'borderwidth', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border', 'flags', '', '', '243', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border', 'position', '', '', '(öREGEXP0ö|(öREGEXP1ö))|(öREGEXP2ö)|(öREGEXP3ö)', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border', 'units', '3', '', 'color', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border', 'units', '2', '', 'borderstyle', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border', 'units', '0', '', 'borderwidth', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'border', 'units', '1', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background-repeat', 'units', '0', '', 'bgrepeat', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background-repeat', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background-position', 'flags', '', '', '98', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background-position', 'units', '2', '', 'bgpos', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background-position', 'position', '', '', '(?:(öREGEXP0ö)|(öREGEXP1ö)){1,2}|(öREGEXP2ö)', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background-position', 'units', '1', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background-position', 'units', '0', '', 'percent', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background-image', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background-image', 'units', '1', '', 'none', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background-image', 'position', '', '', '(?:öREGEXP0ö)|öREGEXP1ö', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background-color', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background-image', 'units', '0', '', 'url', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background-color', 'position', '', '', '(?:öREGEXP0ö)|öREGEXP1ö', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background-color', 'units', '1', '', 'transparent', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background-color', 'units', '0', '', 'color', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background-attachment', 'flags', '', '', '241', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background-attachment', 'units', '0', '', 'attachment', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background', 'flags', '', '', '102', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background', 'position', '', '', '(öREGEXP0ö)|(?:öREGEXP1ö|öREGEXP2ö)|(öREGEXP3ö)|(öREGEXP4ö)|(öREGEXP5)|(öREGEXP6ö)|(öREGEXP7ö)|(öREGEXP8ö)', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background', 'units', '7', '', 'bgpos', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background', 'units', '8', '', 'transparent', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background', 'units', '6', '', 'length', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background', 'units', '5', '', 'percent', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background', 'units', '4', '', 'attachment', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background', 'units', '3', '', 'bgrepeat', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background', 'units', '2', '', 'none', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background', 'units', '0', '', 'color', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_elements', 'background', 'units', '1', '', 'url', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'whitespace', 'v', '', '', 'nowrap|pre', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'visibility', 'v', '', '', 'hidden|inherit|visible|show|hide', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'valign', 'v', '', '', 'baseline|middle|sub|super|(?:(?:text\\-)?bottom|top)', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'url', 'u', '', '', 'url\\((?:[A-Z]*?:\\/\\/(?:[\\w\\:]*?\\@)?[A-Z0-9\\-\\_]{3,}\\.[A-Z0-9\\-\\_]{3,63}\\.[A-Z]{2,4}(?:\\:\\d{1,5})?)?(?:(?:\\/?(?:[A-Z0-9\\_\\.\\-\\%\\+\\~]*))+(?:\\?[\\w\\=\\&\\+\\%]*)?(?:\\#\\w+)?)+\\)', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'transparent', 'v', '', '', 'transparent', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'ttrans', 'v', '', '', '(?:(?:upper|lower)+case)|capitalize', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'tdeco', 'v', '', '', '((\\s*?(underline|overline|line-through)){1,3})|blink', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'talign', 'v', '', '', 'left|right|center|justify', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'string', 'v', '', '', '[\\w \\.\\-\'\\"]*', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'position', 'v', '', '', 'absolute|fixed|normal|relative|static', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'percent', 'u', '', '', '(?:[\\d\\.]*?\\d\\%)', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'overflow', 'v', '', '', 'hidden|scroll|visible', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'normal', 'v', '', '', 'normal', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'none', 'v', '', '', 'none', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'listtype', 'v', '', '', 'decimal|((lower|upper)+-(alpha|roman)+)|disc|circle|square', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'listpos', 'v', '', '', '(in|out)side', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'length', 'u', '', '', '(?:[\\d\\.]*?\\d(pt|px|cm|mm|in|em|ex))|0+', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'fweight', 'u', '', '', '[1-9]00', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'int', 'u', '', '', '\\d*?', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'fweight', 'v', '', '', 'bolder|lighter|bold|light', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'fvariant', 'v', '', '', 'small-caps', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'fstyle', 'v', '', '', 'italic|oblique', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'float', 'v', '', '', 'left|right', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'fsize', 'v', '', '', '(xx\\-(small|large))|(x\\-(small|large))|(small(er)?)|(large(r)?)|medium', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'display', 'v', '', '', 'block|compact|(inline(-table)?)|list-item|run-in|(table(-(caption|cell|((column|footer|header)+-group)|(row(-group)?)))?)', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'dirtext', 'v', '', '', 'ltr|rtl|ltr-override|rtl-override', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'decimal', 'u', '', '', '\\d*?\\.?\\d*', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'cursor', 'v', '', '', 'auto|crosshair|default|help|move|pointer|text|wait|(?:(?:ne|nw|se|sw|e|n|s|w)\\-resize)', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'color', 'u', '', '', '(#[A-F0-9]{3,6})|rgb\\((\\s*?\\d{1,3}%?\\s*?,?){3,3}\\s*?\\)', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'color', 'v', '', '', 'aqua|black|blue|fuchsia|yellow|white|teal|silver|red|purple|olive|navy|maroon|lime|green|gray', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'clip', 'u', '', '', 'rect\\((\\s*?\\-?\\d*?\\.?\\d*?(px|pt|cm|mm|inch|em|ex)){4,4}\\s*?\\)', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'clip', 'v', '', '', 'auto', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'clear', 'v', '', '', 'both|left|right', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'borderwidth', 'v', '', '', 'thin|medium|thick', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'bgpos', 'v', '', '', '((top|center|bottom)?\\s*?(left|center|right)?)+', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'borderstyle', 'v', '', '', 'dashed|dotted|double|groove|(?:(?:in|out)set)|none|ridge|solid', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'bgrepeat', 'v', '', '', '(repeat(\\-(x|y))?)|no\\-repeat', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'auto', 'v', '', '', 'auto', 0, NULL, NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'css_units', 'attachment', 'v', '', '', 'fixed|scroll', 0, NULL, NULL, 'txt', NULL, NULL, 0);

# cms config
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'version', '', '', '', '01.06.05', '0', NULL, NULL, '', NULL, NULL, '0');

INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'cms_path', NULL, NULL, NULL, '<!--{cms_path}-->backend/', 100, 'set_cms_path', NULL, 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'cms_html_path', NULL, NULL, NULL, '<!--{cms_full_http_path}-->backend/', 101, 'set_html_path', NULL, 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'backend_lang', '', '', '', 'de', 102, 'set_backend_lang', NULL, 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'skin', '', '', '', 'standard', 103, 'set_skin', NULL, 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'backend_cache', '', '', '', '0', 104, 'set_backend_cache', NULL, 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'gzip', NULL, NULL, NULL, '1', 105, 'set_gzip', 'set_general', 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'manipulate_output', '', '', '', 'echo $output;', 106, 'set_manipulate_output', NULL, 'txtarea', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'session_backend_domain', '', '', '', '', 107, 'set_session_backend_domain', NULL, 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'paging_items_per_page', '', '', '', '20', 200, 'set_paging_items_per_page', NULL, 'txt', NULL, NULL, '1');

INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'chmod_value', '', '', '', '777', 300, 'set_chmod_value', 'set_filebrowser', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'chmod_enabled', '', '', '', '1', 301, 'set_chmod_enable', NULL, 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'gzip_enabled', '', '', '', '1', 303, 'set_gzip_enable', NULL, 'txt', NULL, NULL, 1);

INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'image_mode', '', '', '', 'GD', 400, 'set_image_mode', 'set_image', 'txt', NULL, NULL, '1');

INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'FormatDate', '', '', '', 'd.m.Y', 500, 'set_FormatDate', 'set_time', 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'FormatTime', '', '', '', 'H:i', 501, 'set_FormatTime', NULL, 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'session_backend_lifetime', '', '', '', '45', 503, 'set_session_backend_lifetime', NULL, 'txt', NULL, NULL, '1');

INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'repository_enabled', '', '', '', '0', 600, 'set_repository_enabled', 'set_repository', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'repository_auto_version', '', '', '', '1', 610, 'set_repository_auto_version', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'trouble_chars', '', '', '', 'äöüßéèáà<>|+', 0, 'set_trouble_chars', 'set_filemanager', 'txt', NULL, NULL, 0);

INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'db_cache_enabled', '', '', '', '1', 800, 'set_db_cache_enabled', 'set_db_cache', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'db_cache_name', '', '', '', 'db_cache', 805, 'set_db_cache_name', NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'db_cache_groups', 'default', '', '', '60', 810, 'set_db_cache_group_default', NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'db_cache_groups', 'standard', '', '', '3600', 820, 'set_db_cache_group_standard', NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'db_cache_groups', 'frontend', '', '', '0', 830, 'set_db_cache_group_frontend', 'set_db_cache_groups', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'db_cache_items', 'frontend', 'tree', '', '1440', 840, 'set_db_cache_item_tree', 'set_db_cache_items', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'db_cache_items', 'frontend', 'content', '', '1440', 850, 'set_db_cache_item_content', NULL, 'txt', NULL, NULL, 0);

INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'db_optimice_tables', 'enable', '', '', '1', 900, 'set_db_optimice_tables_enable', 'set_db_optimice_tables', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'db_optimice_tables', 'last_run', '', '', '0', 910, 'set_db_optimice_tables_lastrun', NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'db_optimice_tables', 'time', '', '', '39600', 920, 'set_db_optimice_tables_time', NULL, 'txt', NULL, NULL, 0);

# client konfiguration
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'path', '', '', '', '<!--{cms_path}-->projekt01/', 100, 'setuse_path', 'setuse_pathes', 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'htmlpath', '', '', '', '<!--{cms_full_http_path}-->projekt01/', 101, 'setuse_html_path', NULL, 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'contentfile', '', '', '', 'index.php', 102, 'setuse_contentfile', NULL, 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'space', '', '', '', '<!--{cms_full_http_path}-->projekt01/cms/img/space.gif', 105, 'setuse_space', NULL, 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'session_enabled', '', '', '', '1', 201, 'set_session_frontend_enabled', 'setuse_general', 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'session_lifetime', '', '', '', '15', 202, 'set_session_frontend_lifetime', '', 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'publish', '', '', '', '0', 203, 'setuse_publish', NULL, 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'edit_mode', '', '', '', '0', 204, 'setuse_edit_mode', NULL, 'txt', NULL, NULL, '0');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'default_layout', '', '', '', '<html>\r\n<head>\r\n<cms:lay type="head"/>\r\n</head>\r\n<body>\r\n<cms:lay type="container" id="1" title="Seiten-Content"/>\r\n\r\n<cms:lay type="config"/>\r\n<cms:lay type="foot"/>\r\n</body>\r\n</html>', 206, 'setuse_default_layout', NULL, 'txtarea', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'url_rewrite', '', '', '', '0', 207, 'setuse_url_rewrite', NULL, 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'url_langid_in_defaultlang', '', '', '', '0', '210', 'setuse_url_langid_in_defaultlang', NULL , 'txt', NULL , NULL , '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'url_rewrite_suffix', '', '', '', '.html', '211', 'setuse_url_rewrite_suffix', NULL , 'txt', NULL , NULL , '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'url_rewrite_basepath', '', '', '', '<!--{cms_full_http_path}-->projekt01/', '212', 'setuse_url_rewrite_basepath', NULL , 'txt', NULL , NULL , '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'url_rewrite_404', '', '', '', '0', '213', 'setuse_url_rewrite_404', NULL , 'txt', NULL , NULL , '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'session_disabled_useragents', '', '', '', 'Googlebot\r\nYahoo\r\nScooter\r\nFAST-WebCrawler\r\nMSNBOT\r\nSeekbot\r\nInktomi\r\nLycos_Spider\r\nUltraseek\r\nOverture\r\nSlurp\r\nSidewinder\r\nMetaspinner\r\nJeeves\r\nWISEnutbot\r\nZealbot\r\nia_archiver\r\nAbachoBOT\r\nFirefly', '214', 'setuse_session_disabled_useragents', NULL, 'txtarea', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'session_disabled_ips', '', '', '', '127.0.0.98\r\n127.0.0.99', '216', 'setuse_session_disabled_ips', NULL, 'txtarea', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'manipulate_output', '', '', '', 'echo $output;', 218, 'setuse_manipulate_output', NULL, 'txtarea', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'errorpage', '', '', '', '0', 219, 'setuse_errorpage', NULL, 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'loginpage', '', '', '', '0', 220, 'setuse_loginpage', NULL, 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'cache', '', '', '', '1', 221, 'setuse_cache', NULL, 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'session_frontend_domain', '', '', '', '', 222, 'setuse_session_frontend_domain', NULL, 'txt', NULL, NULL, '1');


INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'upl_path', '', '', '', '<!--{cms_path}-->projekt01/media/', 300, 'setuse_upl_path', 'setuse_filemanager', 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'upl_htmlpath', '', '', '', '<!--{cms_full_http_path}-->projekt01/media/', 301, 'setuse_upl_htmlpath', NULL, 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'upl_forbidden', '', '', '', 'php, htaccess, htpasswd, css, js', 302, 'setuse_forbidden', NULL, 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'thumb_size', '', '', '', '100', 303, 'setuse_thumb_size', '', 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'thumb_aspectratio', '', '', '', '1', 304, 'setuse_thumb_aspectratio', '', 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'thumbext', '', '', '', '_cms_thumb', 305, 'setuse_thumb_ext', '', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'fm_delete_ignore_404', '', '', '', '1', 306, 'setuse_fm_delete_ignore_404', '', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'upl_addon', '', '', '', 'gif,jpg,jpeg,png', 310, 'setuse_upl_addon', '', 'txt', '', '', '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'remove_files_404', '', '', '', '1', 315, 'setuse_remove_files_404', '', 'txt', '', '', '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'remove_empty_directories', '', '', '', '0', 316, 'setuse_remove_empty_directories', '', 'txt', '', '', 1);
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'css_sort_original', '', '', '', '0', 400, 'setuse_css_sort_original', 'setuse_css', 'txt', '', '', '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'css_checking', '', '', '', '1', 405, 'setuse_csschecking', '', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'css_ignore_rules_with_errors', '', '', '', '0', 410, 'setuse_css_ignore_error_rules', '', 'txt', NULL, NULL, 1);

INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'max_count_scandir', '', '', '', '10', 0, '', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'extend_time_scandir', '', '', '', '60', 0, '', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'max_count_scanfile', '', '', '', '2', 0, '', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'max_count_scanthumb', '', '', '', '10', 0, '', '', 'txt', NULL, NULL, 0);


INSERT INTO cms_values VALUES ('', 1, 1, 'cfg_lang', 'meta_description', '', '', '', '', 600, 'set_meta_description', 'set_meta', 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 1, 1, 'cfg_lang', 'meta_keywords', '', '', '', '', 601, 'set_meta_keywords', '', 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 1, 1, 'cfg_lang', 'meta_robots', '', '', '', 'index, follow', 602, 'set_meta_robots', '', 'txt', NULL, NULL, '1');




INSERT INTO cms_values VALUES ('', 0, 0, 'rep', 'repository_init_plugins', '', '', '', '1', 150, 'rep_repository_init_plugins', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'rep', 'repository_lastupdate', '', '', '', 0, 100, 'rep_repository_lastupdate', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'rep', 'repository_updatetime', '', '', '', '39600', 20, 'set_repository_updatetime', '', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 0, 0, 'rep', 'repository_show_up2date', '', '', '', '1', 50, 'set_repository_show_up2date', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'rep', 'repository_show_offline', '', '', '', '0', 60, 'set_repository_show_offline', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'rep', 'repository_service_list', '', '', '', '', 110, 'rep_repository_service_list', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'rep', 'repository_server', '', '', '', 'service.sefrengo.de', 10, 'set_repository_server', '', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 0, 0, 'rep', 'repository_service_path', '', '', '', '/', 15, 'set_repository_path', '', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 0, 0, 'rep', 'repository_lastping', '', '', '', 0, 200, 'rep_repository_lastping', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'rep', 'repository_pingtime', '', '', '', '3600', 20, 'set_repository_pingtime', '', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 0, 0, 'rep', 'repository_service_message', '', '', '', '', 130, 'rep_repository_service_message', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'rep', 'repository_loopback', '', '', '', '1', 120, 'set_repository_loopback', '', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 0, 0, 'rep', 'repository_auto_repair_dependency', '', '', '', '1', 160, 'set_auto_repair_dependency', '', 'txt', NULL, NULL, 1);

# user perms rep
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_rep', '1', NULL, NULL, '1', 10, 'group_area_rep_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_rep', '2', NULL, NULL, '2', 20, 'group_area_rep_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_rep', '3', NULL, NULL, '4', 30, 'group_area_rep_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_rep', '5', NULL, NULL, '16', 50, 'group_area_rep_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_rep', '6', NULL, NULL, '32', 60, 'group_area_rep_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_rep', '7', NULL, NULL, '64', 70, 'group_area_rep_7', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_rep', '8', NULL, NULL, '128', 80, 'group_area_rep_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_rep', '9', NULL, NULL, '256', 90, 'group_area_rep_9', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_rep', '10', NULL, NULL, '512', 100, 'group_area_rep_10', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_rep', '11', NULL, NULL, '1024', 110, 'group_area_rep_11', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_rep', '12', NULL, NULL, '2048', 120, 'group_area_rep_12', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_rep', '13', NULL, NULL, '4096', 130, 'group_area_rep_13', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'rep', '1', NULL, NULL, '1', 10, 'group_rep_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'rep', '3', NULL, NULL, '4', 40, 'group_rep_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'rep', '5', NULL, NULL, '16', 50, 'group_rep_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'rep', '6', NULL, NULL, '32', 60, 'group_rep_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'rep', '7', NULL, NULL, '64', 70, 'group_rep_7', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'rep', '8', NULL, NULL, '128', 80, 'group_rep_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'rep', '10', NULL, NULL, '512', 100, 'group_rep_10', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'rep', '11', NULL, NULL, '1024', 110, 'group_rep_11', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'rep', '12', NULL, NULL, '2048', 120, 'group_rep_12', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'rep', '13', NULL, NULL, '4096', 130, 'group_rep_13', '', 'txt', NULL, NULL, 0);

# user perms plug
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '1', NULL, NULL, '1', 10, 'group_area_plug_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '2', NULL, NULL, '2', 20, 'group_area_plug_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '3', NULL, NULL, '4', 30, 'group_area_plug_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '4', NULL, NULL, '8', 40, 'group_area_plug_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '5', NULL, NULL, '16', 50, 'group_area_plug_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '6', NULL, NULL, '32', 60, 'group_area_plug_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '7', NULL, NULL, '64', 70, 'group_area_plug_7', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '8', NULL, NULL, '128', 80, 'group_area_plug_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '9', NULL, NULL, '256', 90, 'group_area_plug_9', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '10', NULL, NULL, '512', 100, 'group_area_plug_10', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '11', NULL, NULL, '1024', 110, 'group_area_plug_11', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '12', NULL, NULL, '2048', 120, 'group_area_plug_12', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '13', NULL, NULL, '4096', 130, 'group_area_plug_13', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '14', NULL, NULL, '8192', 140, 'group_area_plug_14', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '15', NULL, NULL, '16384', 150, 'group_area_plug_15', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '16', NULL, NULL, '32768', 160, 'group_area_plug_16', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '17', NULL, NULL, '65536', 170, 'group_area_plug_17', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '18', NULL, NULL, '131072', 180, 'group_area_plug_18', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'plug', '1', NULL, NULL, '1', 10, 'group_plug_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'plug', '3', NULL, NULL, '4', 30, 'group_plug_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'plug', '4', NULL, NULL, '8', 40, 'group_plug_4', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'plug', '5', NULL, NULL, '16', 50, 'group_plug_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'plug', '6', NULL, NULL, '32', 60, 'group_plug_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'plug', '7', NULL, NULL, '64', 70, 'group_plug_7', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'plug', '8', NULL, NULL, '128', 80, 'group_plug_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'plug', '10', NULL, NULL, '512', 100, 'group_plug_10', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'plug', '12', NULL, NULL, '2048', 120, 'group_plug_12', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'plug', '16', NULL, NULL, '32768', 160, 'group_plug_16', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'plug', '17', NULL, NULL, '65536', 170, 'group_plug_17', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'plug', '18', NULL, NULL, '131072', 180, 'group_plug_18', '', 'txt', NULL, NULL, 0);

# zugriffsrechte cms_access
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_frontend', NULL, NULL, 'frontendcat,frontendpage', 10, 'group_area_frontend', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_backend', NULL, NULL, '', 20, 'group_area_backend', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_con', NULL, NULL, 'cat,side', 30, 'group_area_con', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_upl', NULL, NULL, 'folder,file', 40, 'group_area_upl', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_lay', NULL, NULL, 'lay', 50, 'group_area_lay', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_css', NULL, NULL, 'css_file,css_rule', 60, 'group_area_css', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_js', NULL, NULL, 'js_file,js_func', 70, 'group_area_js', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_mod', NULL, NULL, 'mod', 80, 'group_area_mod', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_tpl', NULL, NULL, 'tpl', 90, 'group_area_tpl', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_user', NULL, NULL, '', 94, 'group_area_user', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_group', NULL, NULL, '', 97, 'group_area_group', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_clients', NULL, NULL, 'clients,clientlangs', 100, 'group_area_clients', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_settings', NULL, NULL, '', 110, 'group_area_settings', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_plug', NULL, NULL, 'plug', 120, 'group_area_plug', '', 'txt', NULL, NULL, 0);

# user perms dateimanager
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '1', NULL, NULL, '1', 10, 'group_area_upl_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '2', NULL, NULL, '2', 20, 'group_area_upl_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '3', NULL, NULL, '4', 30, 'group_area_upl_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '5', NULL, NULL, '16', 40, 'group_area_upl_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '6', NULL, NULL, '32', 50, 'group_area_upl_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '8', NULL, NULL, '128', 60, 'group_area_upl_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '9', NULL, NULL, '256', 70, 'group_area_upl_9', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '11', NULL, NULL, '1024', 80, 'group_area_upl_11', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '17', NULL, NULL, '65536', 90, 'group_area_upl_17', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '19', NULL, NULL, '262144', 100, 'group_area_upl_19', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '21', NULL, NULL, '1048576', 110, 'group_area_upl_21', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '22', NULL, NULL, '2097152', 120, 'group_area_upl_22', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '24', NULL, NULL, '8388608', 130, 'group_area_upl_24', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '25', NULL, NULL, '16777216', 140, 'group_area_upl_25', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '1', NULL, NULL, '1', 10, 'group_area_upl_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '2', NULL, NULL, '2', 20, 'group_area_upl_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '3', NULL, NULL, '4', 30, 'group_area_upl_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '5', NULL, NULL, '16', 40, 'group_area_upl_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '6', NULL, NULL, '32', 50, 'group_area_upl_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '8', NULL, NULL, '128', 60, 'group_area_upl_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '9', NULL, NULL, '256', 70, 'group_area_upl_9', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '11', NULL, NULL, '1024', 80, 'group_area_upl_11', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '17', NULL, NULL, '65536', 90, 'group_area_upl_17', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '19', NULL, NULL, '262144', 100, 'group_area_upl_19', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '21', NULL, NULL, '1048576', 110, 'group_area_upl_21', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '22', NULL, NULL, '2097152', 120, 'group_area_upl_22', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '24', NULL, NULL, '8388608', 130, 'group_area_upl_24', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '25', NULL, NULL, '16777216', 140, 'group_area_upl_25', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'file', '17', NULL, NULL, '65536', 10, 'group_area_upl_17', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'file', '19', NULL, NULL, '262144', 20, 'group_area_upl_19', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'file', '21', NULL, NULL, '1048576', 30, 'group_area_upl_21', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'file', '22', NULL, NULL, '2097152', 40, 'group_area_upl_22', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'file', '24', NULL, NULL, '8388608', 50, 'group_area_upl_24', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'file', '25', NULL, NULL, '16777216', 60, 'group_area_upl_25', '', 'txt', NULL, NULL, 0);
# user perms layout
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_lay', '1', NULL, NULL, '1', 10, 'group_area_lay_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_lay', '2', NULL, NULL, '2', 20, 'group_area_lay_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_lay', '3', NULL, NULL, '4', 30, 'group_area_lay_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_lay', '5', NULL, NULL, '16', 50, 'group_area_lay_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_lay', '6', NULL, NULL, '32', 60, 'group_area_lay_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_lay', '7', NULL, NULL, '64', 70, 'group_area_lay_7', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_lay', '8', NULL, NULL, '128', 80, 'group_area_lay_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'lay', '1', NULL, NULL, '1', 10, 'group_lay_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'lay', '3', NULL, NULL, '4', 40, 'group_lay_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'lay', '5', NULL, NULL, '16', 50, 'group_lay_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'lay', '6', NULL, NULL, '32', 60, 'group_lay_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'lay', '7', NULL, NULL, '64', 70, 'group_lay_7', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'lay', '8', NULL, NULL, '128', 80, 'group_lay_8', '', 'txt', NULL, NULL, 0);
# user perms frontend
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_frontend', '2', NULL, NULL, '2', 20, 'group_area_frontend_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_frontend', '18', NULL, NULL, '131072', 180, 'group_area_frontend_18', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_frontend', '19', NULL, NULL, '262144', 190, 'group_area_frontend_19', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'frontendcat', '2', NULL, NULL, '2', 20, 'group_frontendcat_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'frontendcat', '18', NULL, NULL, '131072', 180, 'group_frontendcat_18', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'frontendcat', '19', NULL, NULL, '262144', 190, 'group_frontendcat_19', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'frontendpage', '18', NULL, NULL, '131072', 180, 'group_frontendpage_18', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'frontendpage', '19', NULL, NULL, '262144', 190, 'group_frontendpage_19', '', 'txt', NULL, NULL, 0);



# user perms backend
# nothing yet
# user perms con/cat/side
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '1', NULL, NULL, '1', 10, 'group_area_con_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '2', NULL, NULL, '2', 20, 'group_area_con_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '3', NULL, NULL, '4', 30, 'group_area_con_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '5', NULL, NULL, '16', 50, 'group_area_con_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '6', NULL, NULL, '32', 60, 'group_area_con_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '7', NULL, NULL, '64', 70, 'group_area_con_7', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '8', NULL, NULL, '128', 80, 'group_area_con_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '9', NULL, NULL, '256', 90, 'group_area_con_9', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '11', NULL, NULL, '1024', 110, 'group_area_con_11', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '14', NULL, NULL, '8192', 65, 'group_area_con_14', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '15', NULL, NULL, '16384', 150, 'group_area_con_15', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '17', NULL, NULL, '65536', 170, 'group_area_con_17', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '18', NULL, NULL, '131072', 180, 'group_area_con_18', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '19', NULL, NULL, '262144', 190, 'group_area_con_19', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '20', NULL, NULL, '524288', 200, 'group_area_con_20', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '21', NULL, NULL, '1048576', 210, 'group_area_con_21', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '22', NULL, NULL, '2097152', 220, 'group_area_con_22', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '23', NULL, NULL, '4194304', 230, 'group_area_con_23', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '24', NULL, NULL, '8388608', 240, 'group_area_con_24', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '25', NULL, NULL, '16777216', 250, 'group_area_con_25', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '26', NULL, NULL, '33554432', 260, 'group_area_con_26', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '27', NULL, NULL, '67108864', 270, 'group_area_con_27', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '28', NULL, NULL, '134217728', 280, 'group_area_con_28', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '29', NULL, NULL, '268435456', 290, 'group_area_con_29', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '30', NULL, NULL, '536870912', 300, 'group_area_con_30', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '31', NULL, NULL, '1073741824', 310, 'group_area_con_31', '', 'txt', NULL, NULL, 0);

INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '1', NULL, NULL, '1', 10, 'group_cat_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '2', NULL, NULL, '2', 20, 'group_cat_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '3', NULL, NULL, '4', 30, 'group_cat_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '5', NULL, NULL, '16', 50, 'group_cat_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '6', NULL, NULL, '32', 60, 'group_cat_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '7', NULL, NULL, '64', 70, 'group_cat_7', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '8', NULL, NULL, '128', 80, 'group_cat_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '9', NULL, NULL, '256', 90, 'group_cat_9', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '11', NULL, NULL, '1024', 110, 'group_cat_11', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '14', NULL, NULL, '8192', 65, 'group_cat_14', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '15', NULL, NULL, '16384', 150, 'group_cat_15', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '17', NULL, NULL, '65536', 170, 'group_cat_17', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '18', NULL, NULL, '131072', 180, 'group_cat_18', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '19', NULL, NULL, '262144', 190, 'group_cat_19', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '20', NULL, NULL, '524288', 200, 'group_cat_20', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '21', NULL, NULL, '1048576', 210, 'group_cat_21', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '22', NULL, NULL, '2097152', 220, 'group_cat_22', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '23', NULL, NULL, '4194304', 230, 'group_cat_23', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '24', NULL, NULL, '8388608', 240, 'group_cat_24', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '25', NULL, NULL, '16777216', 250, 'group_cat_25', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '26', NULL, NULL, '33554432', 260, 'group_cat_26', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '27', NULL, NULL, '67108864', 270, 'group_cat_27', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '28', NULL, NULL, '134217728', 280, 'group_cat_28', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '29', NULL, NULL, '268435456', 290, 'group_cat_29', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '30', NULL, NULL, '536870912', 300, 'group_cat_30', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '31', NULL, NULL, '1073741824', 310, 'group_cat_31', '', 'txt', NULL, NULL, 0);

INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '17', NULL, NULL, '65536', 170, 'group_side_17', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '19', NULL, NULL, '262144', 190, 'group_side_19', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '20', NULL, NULL, '524288', 200, 'group_side_20', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '21', NULL, NULL, '1048576', 210, 'group_side_21', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '22', NULL, NULL, '2097152', 220, 'group_side_22', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '23', NULL, NULL, '4194304', 230, 'group_side_23', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '24', NULL, NULL, '8388608', 240, 'group_side_24', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '25', NULL, NULL, '16777216', 250, 'group_side_25', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '26', NULL, NULL, '33554432', 260, 'group_side_26', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '27', NULL, NULL, '67108864', 270, 'group_side_27', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '28', NULL, NULL, '134217728', 280, 'group_side_28', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '29', NULL, NULL, '268435456', 290, 'group_side_29', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '30', NULL, NULL, '536870912', 300, 'group_side_30', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '31', NULL, NULL, '1073741824', 310, 'group_side_31', '', 'txt', NULL, NULL, 0);


# user perms css
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '1', NULL, NULL, '1', 10, 'group_area_css_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '2', NULL, NULL, '2', 20, 'group_area_css_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '3', NULL, NULL, '4', 30, 'group_area_css_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '5', NULL, NULL, '16', 40, 'group_area_css_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '6', NULL, NULL, '32', 50, 'group_area_css_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '8', NULL, NULL, '128', 60, 'group_area_css_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '9', NULL, NULL, '256', 70, 'group_area_css_9', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '13', NULL, NULL, '4096', 80, 'group_area_css_13', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '14', NULL, NULL, '8192', 90, 'group_area_css_14', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '17', NULL, NULL, '65536', 100, 'group_area_css_17', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '18', NULL, NULL, '131072', 110, 'group_area_css_18', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '19', NULL, NULL, '262144', 120, 'group_area_css_19', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '21', NULL, NULL, '1048576', 130, 'group_area_css_21', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '22', NULL, NULL, '2097152', 140, 'group_area_css_22', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '29', NULL, NULL, '268435456', 150, 'group_area_css_29', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '30', NULL, NULL, '536870912', 160, 'group_area_css_30', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '1', NULL, NULL, '1', 10, 'group_area_css_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '2', NULL, NULL, '2', 20, 'group_area_css_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '3', NULL, NULL, '4', 30, 'group_area_css_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '5', NULL, NULL, '16', 40, 'group_area_css_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '6', NULL, NULL, '32', 50, 'group_area_css_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '8', NULL, NULL, '128', 60, 'group_area_css_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '9', NULL, NULL, '256', 70, 'group_area_css_9', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '13', NULL, NULL, '4096', 80, 'group_area_css_13', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '14', NULL, NULL, '8192', 90, 'group_area_css_14', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '17', NULL, NULL, '65536', 100, 'group_area_css_17', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '18', NULL, NULL, '131072', 110, 'group_area_css_18', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '19', NULL, NULL, '262144', 120, 'group_area_css_19', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '21', NULL, NULL, '1048576', 130, 'group_area_css_21', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '22', NULL, NULL, '2097152', 140, 'group_area_css_22', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '29', NULL, NULL, '268435456', 150, 'group_area_css_29', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '30', NULL, NULL, '536870912', 160, 'group_area_css_30', '', 'txt', NULL, NULL, 0);
# user perms mod
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '1', NULL, NULL, '1', 10, 'group_area_mod_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '2', NULL, NULL, '2', 20, 'group_area_mod_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '3', NULL, NULL, '4', 30, 'group_area_mod_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '4', NULL, NULL, '8', 40, 'group_area_mod_4', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '5', NULL, NULL, '16', 50, 'group_area_mod_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '6', NULL, NULL, '32', 60, 'group_area_mod_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '7', NULL, NULL, '64', 70, 'group_area_mod_7', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '8', NULL, NULL, '128', 80, 'group_area_mod_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '9', NULL, NULL, '256', 90, 'group_area_mod_9', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '10', NULL, NULL, '512', 100, 'group_area_mod_10', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '11', NULL, NULL, '1024', 110, 'group_area_mod_11', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '12', NULL, NULL, '2048', 120, 'group_area_mod_12', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '13', NULL, NULL, '4096', 130, 'group_area_mod_13', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '14', NULL, NULL, '8192', 140, 'group_area_mod_14', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '15', NULL, NULL, '16384', 150, 'group_area_mod_15', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'mod', '1', NULL, NULL, '1', 10, 'group_mod_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'mod', '3', NULL, NULL, '4', 30, 'group_mod_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'mod', '4', NULL, NULL, '8', 40, 'group_mod_4', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'mod', '5', NULL, NULL, '16', 50, 'group_mod_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'mod', '6', NULL, NULL, '32', 60, 'group_mod_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'mod', '7', NULL, NULL, '64', 70, 'group_mod_7', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'mod', '8', NULL, NULL, '128', 80, 'group_mod_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'mod', '10', NULL, NULL, '512', 100, 'group_mod_10', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'mod', '12', NULL, NULL, '2048', 120, 'group_mod_12', '', 'txt', NULL, NULL, 0);
# user perms js
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_js', '1', NULL, NULL, '1', 10, 'group_area_js_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_js', '2', NULL, NULL, '2', 20, 'group_area_js_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_js', '3', NULL, NULL, '4', 30, 'group_area_js_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_js', '5', NULL, NULL, '16', 40, 'group_area_js_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_js', '6', NULL, NULL, '32', 50, 'group_area_js_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_js', '8', NULL, NULL, '128', 60, 'group_area_js_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_js', '9', NULL, NULL, '256', 70, 'group_area_js_9', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_js', '13', NULL, NULL, '4096', 80, 'group_area_js_13', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_js', '14', NULL, NULL, '8192', 90, 'group_area_js_14', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'js_file', '1', NULL, NULL, '1', 10, 'group_area_js_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'js_file', '2', NULL, NULL, '2', 20, 'group_area_js_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'js_file', '3', NULL, NULL, '4', 30, 'group_area_js_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'js_file', '5', NULL, NULL, '16', 40, 'group_area_js_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'js_file', '6', NULL, NULL, '32', 50, 'group_area_js_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'js_file', '8', NULL, NULL, '128', 60, 'group_area_js_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'js_file', '9', NULL, NULL, '256', 70, 'group_area_js_9', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'js_file', '13', NULL, NULL, '4096', 80, 'group_area_js_13', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'js_file', '14', NULL, NULL, '8192', 90, 'group_area_js_14', '', 'txt', NULL, NULL, 0);
# user perms clients
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_clients', '1', NULL, NULL, '1', 10, 'group_area_clients_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_clients', '2', NULL, NULL, '2', 20, 'group_area_clients_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_clients', '3', NULL, NULL, '4', 30, 'group_area_clients_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_clients', '4', NULL, NULL, '8', 40, 'group_area_clients_4', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_clients', '5', NULL, NULL, '16', 50, 'group_area_clients_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_clients', '6', NULL, NULL, '32', 60, 'group_area_clients_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_clients', '17', NULL, NULL, '65536', 170, 'group_area_clients_17', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_clients', '18', NULL, NULL, '131072', 180, 'group_area_clients_18', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_clients', '19', NULL, NULL, '262144', 190, 'group_area_clients_19', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_clients', '21', NULL, NULL, '1048576', 210, 'group_area_clients_21', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_clients', '22', NULL, NULL, '2097152', 220, 'group_area_clients_22', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_clients', '28', NULL, NULL, '134217728', 280, 'group_area_clients_28', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clients', '1', NULL, NULL, '1', 10, 'group_clients_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clients', '3', NULL, NULL, '4', 30, 'group_clients_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clients', '4', NULL, NULL, '8', 40, 'group_clients_4', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clients', '5', NULL, NULL, '16', 50, 'group_clients_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clients', '6', NULL, NULL, '32', 60, 'group_clients_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clients', '17', NULL, NULL, '65536', 170, 'group_clients_17', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clients', '18', NULL, NULL, '131072', 180, 'group_clients_18', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clients', '19', NULL, NULL, '262144', 190, 'group_clients_19', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clients', '21', NULL, NULL, '1048576', 210, 'group_clients_21', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clients', '22', NULL, NULL, '2097152', 220, 'group_clients_22', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clients', '28', NULL, NULL, '134217728', 280, 'group_clients_28', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clientlangs', '17', NULL, NULL, '65536', 170, 'group_clientlangs_17', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clientlangs', '19', NULL, NULL, '262144', 190, 'group_clientlangs_19', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clientlangs', '21', NULL, NULL, '1048576', 210, 'group_clientlangs_21', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clientlangs', '22', NULL, NULL, '2097152', 220, 'group_clientlangs_22', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clientlangs', '28', NULL, NULL, '134217728', 280, 'group_clientlangs_28', '', 'txt', NULL, NULL, 0);
# user perms settings
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_settings', '1', NULL, NULL, '1', 10, 'group_area_settings_1', '', 'txt', NULL, NULL, 0);
# user perms template
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_tpl', '1', NULL, NULL, '1', 10, 'group_area_tpl_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_tpl', '2', NULL, NULL, '2', 20, 'group_area_tpl_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_tpl', '3', NULL, NULL, '4', 30, 'group_area_tpl_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_tpl', '5', NULL, NULL, '16', 50, 'group_area_tpl_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_tpl', '6', NULL, NULL, '32', 60, 'group_area_tpl_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_tpl', '12', NULL, NULL, '2048', 120, 'group_area_tpl_12', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'tpl', '1', NULL, NULL, '1', 10, 'group_tpl_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'tpl', '3', NULL, NULL, '4', 40, 'group_tpl_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'tpl', '5', NULL, NULL, '16', 50, 'group_tpl_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'tpl', '6', NULL, NULL, '32', 60, 'group_tpl_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'tpl', '12', NULL, NULL, '2048', 120, 'group_tpl_12', '', 'txt', NULL, NULL, 0);
