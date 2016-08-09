# SQLiteManager Dump
# Version: 0.9.5
# http://sourceforge.net/projects/sqlitemanager/
#
# Database: sqlite_messages.db
# --------------------------------------------------------

#
# Table structure for table: catalogue
#
DROP TABLE catalogue;
CREATE TABLE "catalogue" ("cat_id" INTEGER PRIMARY KEY,"name" VARCHAR NOT NULL,"source_lang" VARCHAR,"target_lang" VARCHAR,"date_created" INT,"date_modified" INT,"author" VARCHAR);

#
# Dumping data for table: catalogue
#
INSERT INTO catalogue VALUES ('1', 'messages.de', '', '', '', '1105265107', '');
INSERT INTO catalogue VALUES ('2', 'index.de', '', '', '', '1105265017', '');
INSERT INTO catalogue VALUES ('3', 'tests.de', '', '', '', '1105265140', '');
# --------------------------------------------------------


#
# Table structure for table: trans_unit
#
DROP TABLE trans_unit;
CREATE TABLE trans_unit ( msg_id INTEGER PRIMARY KEY, cat_id INTEGER NOT NULL DEFAULT '1', id VARCHAR, source TEXT, target TEXT, comments TEXT, date_added INT, date_modified INT, author VARCHAR, translated INT(1) NOT NULL DEFAULT '0' );

#
# Dumping data for table: trans_unit
#
INSERT INTO trans_unit VALUES ('1', '2', '0', 'Hello', 'Hallo', '', '1105264156', '1105264995', '', '0');
INSERT INTO trans_unit VALUES ('2', '2', '1', 'Goodbye', 'Auf Wiedersehen', '', '1105264156', '1105265007', '', '0');
INSERT INTO trans_unit VALUES ('3', '2', '2', 'World', 'Welt', '', '1105264156', '1105265012', '', '0');
INSERT INTO trans_unit VALUES ('4', '2', '3', '{greeting} {name}!, The unix-time is "{time}".', '{greeting} {name}!, der Unix-Zeitstempel ist "{time}".', '', '1105264156', '1105265017', '', '0');
INSERT INTO trans_unit VALUES ('5', '3', '1', 'Goodbye', 'Auf Wiedersehen!', '', '', '1105265135', '', '0');
INSERT INTO trans_unit VALUES ('6', '3', '2', 'World', 'Welt', '', '', '1105265140', '', '0');
INSERT INTO trans_unit VALUES ('7', '1', '0', 'Reload', 'Neu laden', '', '1105264907', '1105265037', '', '0');
INSERT INTO trans_unit VALUES ('8', '1', '1', 'PRADO Translation Editor', 'PRADO Ãœbersetzungseditor', '', '1105264907', '1105265043', '', '0');
INSERT INTO trans_unit VALUES ('9', '1', '2', 'Source:', 'Quelle:', '', '1105264907', '1105265064', '', '0');
INSERT INTO trans_unit VALUES ('10', '1', '3', 'Type:', 'Typ:', '', '1105264907', '1105265069', '', '0');
INSERT INTO trans_unit VALUES ('11', '1', '4', 'Catalogue:', 'Katalog:', '', '1105264907', '1105265074', '', '0');
INSERT INTO trans_unit VALUES ('12', '1', '5', 'Original string', 'UrsprÃ¼ngliche Zeichenkette', '', '1105264907', '1105265080', '', '0');
INSERT INTO trans_unit VALUES ('13', '1', '6', 'Translation', 'Ãœbersetzung', '', '1105264907', '1105265086', '', '0');
INSERT INTO trans_unit VALUES ('14', '1', '7', 'Copyrights 2005 Xiang Wei Zhuo. All right reserved.', 'Copyrights 2005 Xiang Wei Zhuo. Alle Rechte vorbehalten.', '', '1105264908', '1105265094', '', '0');
INSERT INTO trans_unit VALUES ('15', '1', '8', 'Internationlization in PRADO', 'Internationalisierung in PRADO', '', '1105264908', '1105265101', '', '0');
INSERT INTO trans_unit VALUES ('16', '1', '9', 'Update Translation', 'Ã„nderungen Ã¼bernehmen', '', '1105264908', '1105265107', '', '0');
INSERT INTO trans_unit VALUES ('17', '1', '10', 'System error, unable to update translation.', 'Systemfehler, Ãœbersetzung konnte nicht aktualisiert werden.', '', '1105264908', '1105265056', '', '0');
# --------------------------------------------------------
