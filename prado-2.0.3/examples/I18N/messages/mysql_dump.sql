# phpMyAdmin SQL Dump
# version 2.5.5-rc2
# http://www.phpmyadmin.net
#
# 
# Database : `i18n_example`
# 

# --------------------------------------------------------

#
# Table structure for table `catalogue`
#

DROP TABLE IF EXISTS `catalogue`;
CREATE TABLE `catalogue` (
  `cat_id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `source_lang` varchar(100) NOT NULL default '',
  `target_lang` varchar(100) NOT NULL default '',
  `date_created` int(11) NOT NULL default '0',
  `date_modified` int(11) NOT NULL default '0',
  `author` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`cat_id`)
) TYPE=MyISAM AUTO_INCREMENT=7 ;

#
# Dumping data for table `catalogue`
#

INSERT INTO `catalogue` VALUES (1, 'messages.pl', '', '', 0, 1105269980, '');
INSERT INTO `catalogue` VALUES (2, 'index.pl', '', '', 0, 1105269834, '');
INSERT INTO `catalogue` VALUES (3, 'tests.pl', '', '', 0, 1105269679, '');

# --------------------------------------------------------

#
# Table structure for table `trans_unit`
#

DROP TABLE IF EXISTS `trans_unit`;
CREATE TABLE `trans_unit` (
  `msg_id` int(11) NOT NULL auto_increment,
  `cat_id` int(11) NOT NULL default '1',
  `id` varchar(255) NOT NULL default '',
  `source` text NOT NULL,
  `target` text NOT NULL,
  `comments` text NOT NULL,
  `date_added` int(11) NOT NULL default '0',
  `date_modified` int(11) NOT NULL default '0',
  `author` varchar(255) NOT NULL default '',
  `translated` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`msg_id`)
) TYPE=MyISAM AUTO_INCREMENT=42 ;

#
# Dumping data for table `trans_unit`
#

INSERT INTO `trans_unit` VALUES (19, 2, '1', 'Hello', 'Witaj', '', 1105269416, 1105269813, '', 0);
INSERT INTO `trans_unit` VALUES (20, 2, '2', 'Goodbye', 'Å»egnaj!', '', 1105269416, 1105269821, '', 0);
INSERT INTO `trans_unit` VALUES (21, 2, '3', 'World', 'Åšwiecie', '', 1105269416, 1105269827, '', 0);
INSERT INTO `trans_unit` VALUES (22, 2, '4', '{greeting} {name}!, The unix-time is "{time}".', '{greeting} {name}!, Czas unixowy  "{time}".', '', 1105269416, 1105269834, '', 0);
INSERT INTO `trans_unit` VALUES (27, 1, '1', 'Reload', 'OdÅ›wieÅ¼', '', 1105269515, 1105269926, '', 0);
INSERT INTO `trans_unit` VALUES (28, 1, '2', 'PRADO Translation Editor', 'Edytor TÅ‚umaczeÅ„ PRADO', '', 1105269515, 1105269887, '', 0);
INSERT INTO `trans_unit` VALUES (29, 1, '3', 'Source:', 'Å¹rÃ³dÅ‚o:', '', 1105269515, 1105269876, '', 0);
INSERT INTO `trans_unit` VALUES (30, 1, '4', 'Type:', 'Typ:', '', 1105269515, 1105269935, '', 0);
INSERT INTO `trans_unit` VALUES (31, 1, '5', 'Catalogue:', 'Katalog:', '', 1105269515, 1105269953, '', 0);
INSERT INTO `trans_unit` VALUES (32, 1, '6', 'Original string', 'ÅaÅ„cuch orginalny', '', 1105269515, 1105269962, '', 0);
INSERT INTO `trans_unit` VALUES (33, 1, '7', 'Translation', 'PrzekÅ‚ad', '', 1105269515, 1105269968, '', 0);
INSERT INTO `trans_unit` VALUES (34, 1, '8', 'Copyrights 2005 Xiang Wei Zhuo. All right reserved.', 'Copyrights 2005 Xiang Wei Zhuo. Wszelkie prawa zastrzeÅ¼one', '', 1105269515, 1105269980, '', 0);
INSERT INTO `trans_unit` VALUES (35, 1, '9', 'Internationlization in PRADO', 'Internacjonalizacja w PRADO', '', 1105269515, 1105269916, '', 0);
INSERT INTO `trans_unit` VALUES (36, 1, '10', 'Update Translation', 'Zaktualizuj PrzekÅ‚ad', '', 1105269516, 1105269906, '', 0);
INSERT INTO `trans_unit` VALUES (37, 1, '11', 'System error, unable to update translation.', 'BÅ‚Ä…d systemowy, nie moÅ¼na zaktualizwaÄ‡ przekÅ‚adu.', '', 1105269516, 1105269897, '', 0);
INSERT INTO `trans_unit` VALUES (38, 3, '1', 'Goodbye', 'Dowidzenia', '', 0, 1105269673, '', 0);
INSERT INTO `trans_unit` VALUES (39, 3, '2', 'World', 'Åšwiecie', '', 0, 1105269679, '', 0);
    