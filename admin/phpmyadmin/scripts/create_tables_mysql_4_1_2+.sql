-- --------------------------------------------------------
-- SQL Commands to set up the pmadb as described in Documentation.html.
--
-- This file is meant for use with MySQL 4.1.2 and above!
-- For older MySQL releases, please use create_tables.sql
--
-- If you are running one MySQL 4.1.0 or 4.1.1, please create the tables using
-- create_tables.sql and upgrade their collation settings according to our
-- manual.
--                                                 
-- This script expects the user pma to already be existing. If we would put a
-- line here to create him too many users might just use this script and end
-- up with having the same password for the controluser.
--                                                     
-- This user "pma" must be defined in config.inc.php (controluser/controlpass)                         
--                                                  
-- Please don't forget to set up the tablenames in config.inc.php                                 
-- 
-- $Id: create_tables_mysql_4_1_2+.sql,v 1.2 2004/07/18 13:02:51 lem9 Exp $

-- --------------------------------------------------------

-- 
-- Database : `phpmyadmin`
-- 
DROP DATABASE IF EXISTS `phpmyadmin`;
CREATE DATABASE `phpmyadmin`
  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE phpmyadmin;

-- --------------------------------------------------------

-- 
-- Privileges
-- 
GRANT SELECT, INSERT, DELETE, UPDATE ON `phpmyadmin`.* TO
    'pma'@localhost;

-- --------------------------------------------------------

-- 
-- Table structure for table `pma_bookmark`
-- 

CREATE TABLE `pma_bookmark` (
  `id` int(11) NOT NULL auto_increment,
  `dbase` varchar(255) NOT NULL default '',
  `user` varchar(255) NOT NULL default '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL default '',
  `query` text NOT NULL,
  PRIMARY KEY  (`id`)
)
  ENGINE=MyISAM COMMENT='Bookmarks'
  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

-- --------------------------------------------------------

-- 
-- Table structure for table `pma_column_info`
-- 

CREATE TABLE `pma_column_info` (
  `id` int(5) unsigned NOT NULL auto_increment,
  `db_name` varchar(64) NOT NULL default '',
  `table_name` varchar(64) NOT NULL default '',
  `column_name` varchar(64) NOT NULL default '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL default '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL default '',
  `transformation` varchar(255) NOT NULL default '',
  `transformation_options` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`)
)
  ENGINE=MyISAM COMMENT='Column information for phpMyAdmin'
  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

-- --------------------------------------------------------

-- 
-- Table structure for table `pma_history`
-- 

CREATE TABLE `pma_history` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `username` varchar(64) NOT NULL default '',
  `db` varchar(64) NOT NULL default '',
  `table` varchar(64) NOT NULL default '',
  `timevalue` timestamp(14) NOT NULL,
  `sqlquery` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `username` (`username`,`db`,`table`,`timevalue`)
)
  ENGINE=MyISAM COMMENT='SQL history for phpMyAdmin'
  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

-- --------------------------------------------------------

-- 
-- Table structure for table `pma_pdf_pages`
-- 

CREATE TABLE `pma_pdf_pages` (
  `db_name` varchar(64) NOT NULL default '',
  `page_nr` int(10) unsigned NOT NULL auto_increment,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL default '',
  PRIMARY KEY  (`page_nr`),
  KEY `db_name` (`db_name`)
)
  ENGINE=MyISAM COMMENT='PDF relation pages for phpMyAdmin'
  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

-- --------------------------------------------------------

-- 
-- Table structure for table `pma_relation`
-- 

CREATE TABLE `pma_relation` (
  `master_db` varchar(64) NOT NULL default '',
  `master_table` varchar(64) NOT NULL default '',
  `master_field` varchar(64) NOT NULL default '',
  `foreign_db` varchar(64) NOT NULL default '',
  `foreign_table` varchar(64) NOT NULL default '',
  `foreign_field` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`master_db`,`master_table`,`master_field`),
  KEY `foreign_field` (`foreign_db`,`foreign_table`)
)
  ENGINE=MyISAM COMMENT='Relation table'
  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

-- --------------------------------------------------------

-- 
-- Table structure for table `pma_table_coords`
-- 

CREATE TABLE `pma_table_coords` (
  `db_name` varchar(64) NOT NULL default '',
  `table_name` varchar(64) NOT NULL default '',
  `pdf_page_number` int(11) NOT NULL default '0',
  `x` float unsigned NOT NULL default '0',
  `y` float unsigned NOT NULL default '0',
  PRIMARY KEY  (`db_name`,`table_name`,`pdf_page_number`)
)
  ENGINE=MyISAM COMMENT='Table coordinates for phpMyAdmin PDF output'
  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

-- --------------------------------------------------------

-- 
-- Table structure for table `pma_table_info`
-- 

CREATE TABLE `pma_table_info` (
  `db_name` varchar(64) NOT NULL default '',
  `table_name` varchar(64) NOT NULL default '',
  `display_field` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`db_name`,`table_name`)
)
  ENGINE=MyISAM COMMENT='Table information for phpMyAdmin'
  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
