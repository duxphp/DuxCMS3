# ************************************************************
# Sequel Pro SQL dump
# Version 5438
#
# https://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.25)
# Database: duxcms3
# Generation Time: 2019-03-27 09:06:53 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table dux_article
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_article`;

CREATE TABLE `dux_article` (
  `article_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class_id` int(10) NOT NULL DEFAULT '0' COMMENT '栏目ID',
  `title` varchar(250) DEFAULT '',
  `sub_title` varchar(250) DEFAULT '',
  `keyword` varchar(250) DEFAULT '',
  `description` varchar(250) DEFAULT '',
  `image` varchar(250) DEFAULT '',
  `auth` varchar(50) DEFAULT '',
  `content` text COMMENT '内容',
  `tags_id` varchar(250) DEFAULT '',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  `virtual_view` int(10) NOT NULL DEFAULT '0',
  `view` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`article_id`),
  KEY `class_id` (`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `dux_article` WRITE;
/*!40000 ALTER TABLE `dux_article` DISABLE KEYS */;

INSERT INTO `dux_article` (`article_id`, `class_id`, `title`, `sub_title`, `keyword`, `description`, `image`, `auth`, `content`, `tags_id`, `create_time`, `update_time`, `virtual_view`, `view`, `status`, `sort`)
VALUES
	(1,1,'这是一篇默认的文章','','','欢迎使用DuxCMS作为你的文章管理系统，请开始编写第一篇内容吧...','/theme/default/images/show1.webp','','&lt;p&gt;欢迎使用DuxCMS作为你的文章管理系统，请开始编写第一篇内容吧&lt;/p&gt;','',1553588391,0,0,45,1,0);

/*!40000 ALTER TABLE `dux_article` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table dux_article_class
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_article_class`;

CREATE TABLE `dux_article_class` (
  `class_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) NOT NULL DEFAULT '0' COMMENT '上级栏目',
  `name` varchar(50) DEFAULT '',
  `subname` varchar(250) DEFAULT '',
  `image` varchar(250) DEFAULT '',
  `keyword` varchar(250) DEFAULT '',
  `description` varchar(250) DEFAULT '',
  `tpl_class` varchar(250) DEFAULT '',
  `tpl_content` varchar(250) DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`class_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `dux_article_class` WRITE;
/*!40000 ALTER TABLE `dux_article_class` DISABLE KEYS */;

INSERT INTO `dux_article_class` (`class_id`, `parent_id`, `name`, `subname`, `image`, `keyword`, `description`, `tpl_class`, `tpl_content`, `status`, `sort`)
VALUES
	(1,0,'默认分类','','','','','','',1,0);

/*!40000 ALTER TABLE `dux_article_class` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table dux_page
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_page`;

CREATE TABLE `dux_page` (
  `page_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) NOT NULL DEFAULT '0' COMMENT '上级栏目',
  `name` varchar(250) DEFAULT '',
  `image` varchar(250) DEFAULT '',
  `content` longtext,
  `keyword` varchar(250) DEFAULT '',
  `description` varchar(250) DEFAULT '',
  `tpl` varchar(250) DEFAULT '',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  `virtual_view` int(10) NOT NULL DEFAULT '0',
  `view` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`page_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table dux_site_config
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_site_config`;

CREATE TABLE `dux_site_config` (
  `config_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT '',
  `content` varchar(250) DEFAULT '',
  `description` varchar(250) DEFAULT '',
  PRIMARY KEY (`config_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `dux_site_config` WRITE;
/*!40000 ALTER TABLE `dux_site_config` DISABLE KEYS */;

INSERT INTO `dux_site_config` (`config_id`, `name`, `content`, `description`)
VALUES
	(6,'info_title','DuxCMS内容管理系统','站点标题'),
	(7,'info_keyword','DuxCMS,PHP,CMS,内容管理系统,PHP开源','站点关键词'),
	(8,'info_desc','DuxCMS内容管理系统是一款基于PHP+Mysql的简单内容管理框架','站点描述'),
	(9,'info_copyright','Copyright@2016-2018 www.duxcms.com  All Rights Reserved.','版权信息'),
	(10,'info_email','admin@duxphp.com','站点邮箱'),
	(11,'info_tel','','站点电话'),
	(16,'info_name','DuxCMS内容管理系统','站点名称'),
	(17,'site_status','1','站点状态'),
	(19,'site_error','站定维护中，本次维护预计需要4个小时，请谅解。','关闭说明'),
	(21,'style_primary','#009944',''),
	(22,'style_secondary','#00c5ff',''),
	(23,'style_success','#00de00',''),
	(24,'style_warning','#f27b00',''),
	(25,'style_danger','#ef0000',''),
	(26,'style_nav_icon_selected','#03a84d',''),
	(27,'style_nav_text','#717171',''),
	(28,'style_nav_icon','#c9d6ce',''),
	(29,'style_nav_text_selected','#717171',''),
	(30,'style_member_img','http://lib.a.cuhuibao.com/duxup_d729a7e0f4dfe11db8b518ba765bdd04.png',''),
	(31,'site_wap','https://shop.zjjxzl.cn',''),
	(32,'info_logo','',''),
	(33,'style_login_img','http://lib.a.cuhuibao.com/duxup_e0a12d20015e90a1edcae0c15639df67.png',''),
	(34,'page_theme','default','');

/*!40000 ALTER TABLE `dux_site_config` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table dux_site_diy
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_site_diy`;

CREATE TABLE `dux_site_diy` (
  `diy_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT '',
  `fields` text,
  PRIMARY KEY (`diy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `dux_site_diy` WRITE;
/*!40000 ALTER TABLE `dux_site_diy` DISABLE KEYS */;

INSERT INTO `dux_site_diy` (`diy_id`, `name`, `fields`)
VALUES
	(1,'产品介绍','');

/*!40000 ALTER TABLE `dux_site_diy` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table dux_site_diy_data
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_site_diy_data`;

CREATE TABLE `dux_site_diy_data` (
  `data_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `diy_id` int(10) NOT NULL DEFAULT '0',
  `title` varchar(250) DEFAULT '',
  `image` varchar(250) DEFAULT '',
  `content` text,
  `editor` tinyint(1) NOT NULL DEFAULT '0',
  `expend` text,
  `sort` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`data_id`),
  KEY `diy_id` (`diy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `dux_site_diy_data` WRITE;
/*!40000 ALTER TABLE `dux_site_diy_data` DISABLE KEYS */;

INSERT INTO `dux_site_diy_data` (`data_id`, `diy_id`, `title`, `image`, `content`, `editor`, `expend`, `sort`)
VALUES
	(1,1,'简单','/theme/default/images/info-easy.svg','简单的开发模式与清晰的架构，让您快速上手',0,'',0),
	(2,1,'易用','/theme/default/images/info-use.svg','基于人性化的UI设置，让您的操作得心应手',0,NULL,0),
	(3,1,'强大','/theme/default/images/info-strong.svg','引入composer同时封装基础类库，让您随用随取',0,NULL,0),
	(4,1,'开源','/theme/default/images/info-free.svg','遵循标准的Zlib开源协议，协议内可免费用于商业用途',0,NULL,0);

/*!40000 ALTER TABLE `dux_site_diy_data` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table dux_site_fragment
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_site_fragment`;

CREATE TABLE `dux_site_fragment` (
  `fragment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(10) DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '' COMMENT '描述',
  `content` text NOT NULL COMMENT '内容',
  `editor` tinyint(1) NOT NULL DEFAULT '0' COMMENT '编辑器',
  PRIMARY KEY (`fragment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `dux_site_fragment` WRITE;
/*!40000 ALTER TABLE `dux_site_fragment` DISABLE KEYS */;

INSERT INTO `dux_site_fragment` (`fragment_id`, `label`, `title`, `content`, `editor`)
VALUES
	(1,'','横幅标题','欢迎使用DuxCMS内容管理系统',0),
	(2,'','横幅描述','这是一款面向开发者免费开源的管理框架',0),
	(3,'','首页描述','DuxCMS 是一款基于 PHP + Mysql 构建的轻量级内容管理框架，致力于为开发者提供快速开发所需要的基本架构功能，产品遵循标准开源协议，让您的开发无后顾之忧',0);

/*!40000 ALTER TABLE `dux_site_fragment` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table dux_site_position
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_site_position`;

CREATE TABLE `dux_site_position` (
  `pos_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT '',
  `sort` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pos_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table dux_site_search
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_site_search`;

CREATE TABLE `dux_site_search` (
  `search_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` varchar(20) DEFAULT '',
  `num` int(10) NOT NULL DEFAULT '1',
  `app` varchar(20) DEFAULT '',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  `has_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`search_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table dux_site_tags
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_site_tags`;

CREATE TABLE `dux_site_tags` (
  `tag_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app` varchar(20) DEFAULT '',
  `name` varchar(250) DEFAULT '',
  `quote` int(10) NOT NULL DEFAULT '1',
  `view` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tag_id`),
  KEY `name` (`name`(191)),
  KEY `quote` (`quote`),
  KEY `view` (`view`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table dux_statis_number
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_statis_number`;

CREATE TABLE `dux_statis_number` (
  `num_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `has_id` int(10) NOT NULL DEFAULT '0' COMMENT '关联ID',
  `species` varchar(50) DEFAULT 'mall_sale',
  `date` int(10) NOT NULL DEFAULT '0' COMMENT '日期',
  `inc_num` int(10) NOT NULL DEFAULT '0' COMMENT '增长',
  `dec_num` int(10) NOT NULL DEFAULT '0' COMMENT '减少',
  PRIMARY KEY (`num_id`),
  KEY `user_id` (`user_id`),
  KEY `has_id` (`has_id`),
  KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table dux_statis_views
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_statis_views`;

CREATE TABLE `dux_statis_views` (
  `view_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `date` int(10) NOT NULL DEFAULT '0',
  `has_id` int(10) NOT NULL DEFAULT '0',
  `species` varchar(50) DEFAULT '',
  `type` varchar(20) DEFAULT '',
  `num` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`view_id`),
  KEY `date` (`date`),
  KEY `user_id` (`user_id`),
  KEY `has_id` (`has_id`),
  KEY `species` (`species`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table dux_system_debug
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_system_debug`;

CREATE TABLE `dux_system_debug` (
  `debug_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `platform` varchar(50) DEFAULT '',
  `page` varchar(200) DEFAULT '',
  `content` text,
  `hash` varchar(250) DEFAULT '',
  `num` int(10) NOT NULL DEFAULT '1',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`debug_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table dux_system_file
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_system_file`;

CREATE TABLE `dux_system_file` (
  `file_id` int(10) NOT NULL AUTO_INCREMENT,
  `url` varchar(250) DEFAULT '',
  `original` varchar(250) DEFAULT '',
  `title` varchar(250) DEFAULT '',
  `ext` varchar(20) DEFAULT '',
  `size` int(10) NOT NULL DEFAULT '0',
  `time` int(10) NOT NULL DEFAULT '0',
  `user_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`file_id`),
  KEY `ext` (`ext`),
  KEY `time` (`time`) USING BTREE,
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='上传文件';



# Dump of table dux_system_role
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_system_role`;

CREATE TABLE `dux_system_role` (
  `role_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT '',
  `description` varchar(250) DEFAULT '',
  `purview` text,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `dux_system_role` WRITE;
/*!40000 ALTER TABLE `dux_system_role` DISABLE KEYS */;

INSERT INTO `dux_system_role` (`role_id`, `name`, `description`, `purview`)
VALUES
	(1,'管理员','系统后台管理员','a:93:{i:0;s:21:\"article.Content.index\";i:1;s:19:\"article.Content.add\";i:2;s:20:\"article.Content.edit\";i:3;s:22:\"article.Content.status\";i:4;s:19:\"article.Content.del\";i:5;s:19:\"article.Class.index\";i:6;s:17:\"article.Class.add\";i:7;s:18:\"article.Class.edit\";i:8;s:20:\"article.Class.status\";i:9;s:17:\"article.Class.del\";i:10;s:15:\"page.Page.index\";i:11;s:13:\"page.Page.add\";i:12;s:14:\"page.Page.edit\";i:13;s:16:\"page.Page.status\";i:14;s:13:\"page.Page.del\";i:15;s:17:\"site.Config.index\";i:16;s:15:\"site.Config.tpl\";i:17;s:17:\"site.Search.index\";i:18;s:15:\"site.Search.add\";i:19;s:16:\"site.Search.edit\";i:20;s:15:\"site.Search.del\";i:21;s:19:\"site.Fragment.index\";i:22;s:17:\"site.Fragment.add\";i:23;s:18:\"site.Fragment.edit\";i:24;s:20:\"site.Fragment.status\";i:25;s:17:\"site.Fragment.del\";i:26;s:14:\"site.Diy.index\";i:27;s:12:\"site.Diy.add\";i:28;s:13:\"site.Diy.edit\";i:29;s:15:\"site.Diy.status\";i:30;s:12:\"site.Diy.del\";i:31;s:18:\"site.DiyData.index\";i:32;s:16:\"site.DiyData.add\";i:33;s:17:\"site.DiyData.edit\";i:34;s:19:\"site.DiyData.status\";i:35;s:16:\"site.DiyData.del\";i:36;s:22:\"statis.SiteViews.index\";i:37;s:18:\"system.Index.index\";i:38;s:21:\"system.Index.userData\";i:39;s:19:\"system.Notice.index\";i:40;s:17:\"system.Notice.del\";i:41;s:19:\"system.Update.index\";i:42;s:19:\"system.Config.index\";i:43;s:18:\"system.Config.user\";i:44;s:18:\"system.Config.info\";i:45;s:20:\"system.Config.upload\";i:46;s:25:\"system.ConfigManage.index\";i:47;s:23:\"system.ConfigManage.add\";i:48;s:24:\"system.ConfigManage.edit\";i:49;s:26:\"system.ConfigManage.status\";i:50;s:23:\"system.ConfigManage.del\";i:51;s:22:\"system.ConfigApi.index\";i:52;s:20:\"system.ConfigApi.add\";i:53;s:21:\"system.ConfigApi.edit\";i:54;s:23:\"system.ConfigApi.status\";i:55;s:20:\"system.ConfigApi.del\";i:56;s:25:\"system.ConfigUpload.index\";i:57;s:24:\"system.ConfigUpload.edit\";i:58;s:17:\"system.User.index\";i:59;s:15:\"system.User.add\";i:60;s:16:\"system.User.edit\";i:61;s:18:\"system.User.status\";i:62;s:15:\"system.User.del\";i:63;s:17:\"system.Role.index\";i:64;s:15:\"system.Role.add\";i:65;s:16:\"system.Role.edit\";i:66;s:15:\"system.Role.del\";i:67;s:18:\"system.Debug.index\";i:68;s:16:\"system.Debug.del\";i:69;s:22:\"system.SystemLog.index\";i:70;s:20:\"system.SystemLog.del\";i:71;s:24:\"system.Application.index\";i:72;s:22:\"system.Application.add\";i:73;s:23:\"system.Application.edit\";i:74;s:22:\"system.Application.del\";i:75;s:20:\"tools.SendData.index\";i:76;s:16:\"tools.Send.index\";i:77;s:14:\"tools.Send.add\";i:78;s:15:\"tools.Send.info\";i:79;s:20:\"tools.SendConf.index\";i:80;s:22:\"tools.SendConf.setting\";i:81;s:19:\"tools.SendTpl.index\";i:82;s:17:\"tools.SendTpl.add\";i:83;s:18:\"tools.SendTpl.edit\";i:84;s:17:\"tools.SendTpl.del\";i:85;s:23:\"tools.SendDefault.index\";i:86;s:17:\"tools.Label.index\";i:87;s:17:\"tools.Queue.index\";i:88;s:21:\"tools.QueueConf.index\";i:89;s:25:\"wechat.WechatConfig.index\";i:90;s:23:\"wechat.MenuConfig.index\";i:91;s:26:\"wechat.MiniappConfig.index\";i:92;s:22:\"wechat.AppConfig.index\";}');

/*!40000 ALTER TABLE `dux_system_role` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table dux_system_statistics
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_system_statistics`;

CREATE TABLE `dux_system_statistics` (
  `stat_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` varchar(8) DEFAULT '',
  `web` int(10) NOT NULL DEFAULT '0',
  `api` int(10) NOT NULL DEFAULT '0',
  `mobile` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`stat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table dux_system_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_system_user`;

CREATE TABLE `dux_system_user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) NOT NULL DEFAULT '0',
  `nickname` varchar(20) DEFAULT '',
  `username` varchar(20) DEFAULT '',
  `password` varchar(128) DEFAULT '',
  `avatar` varchar(250) DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `reg_time` int(10) NOT NULL DEFAULT '0',
  `login_time` int(10) NOT NULL DEFAULT '0',
  `login_ip` varchar(50) DEFAULT '',
  `role_ext` varchar(250) DEFAULT '',
  PRIMARY KEY (`user_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `dux_system_user` WRITE;
/*!40000 ALTER TABLE `dux_system_user` DISABLE KEYS */;

INSERT INTO `dux_system_user` (`user_id`, `role_id`, `nickname`, `username`, `password`, `avatar`, `status`, `reg_time`, `login_time`, `login_ip`, `role_ext`)
VALUES
	(1,1,'admin','admin','21232f297a57a5a743894a0e4a801fc3','',1,0,0,'127.0.0.1','');

/*!40000 ALTER TABLE `dux_system_user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table dux_tools_queue
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_tools_queue`;

CREATE TABLE `dux_tools_queue` (
  `queue_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(20) DEFAULT '' COMMENT '关联标记',
  `has_id` int(10) NOT NULL DEFAULT '0' COMMENT '关联ID',
  `target` varchar(250) DEFAULT '' COMMENT '模块',
  `action` varchar(20) DEFAULT '' COMMENT '方法名',
  `layer` varchar(20) DEFAULT '' COMMENT '层',
  `params` text COMMENT '参数',
  `remark` varchar(250) DEFAULT '' COMMENT '备注',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `start_time` int(10) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `run_time` int(10) NOT NULL DEFAULT '0' COMMENT '执行时间',
  `run_num` int(3) NOT NULL DEFAULT '0' COMMENT '运行次数',
  `max_num` int(2) NOT NULL DEFAULT '0' COMMENT '最大次数',
  `message` text COMMENT '返回消息',
  PRIMARY KEY (`queue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table dux_tools_queue_config
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_tools_queue_config`;

CREATE TABLE `dux_tools_queue_config` (
  `config_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT '' COMMENT '类型名',
  `content` text COMMENT '配置内容',
  PRIMARY KEY (`config_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `dux_tools_queue_config` WRITE;
/*!40000 ALTER TABLE `dux_tools_queue_config` DISABLE KEYS */;

INSERT INTO `dux_tools_queue_config` (`config_id`, `name`, `content`)
VALUES
	(1,'lock_time','60'),
	(2,'every_num','5'),
	(3,'retry_num','5'),
	(4,'del_status','1'),
	(5,'status','1');

/*!40000 ALTER TABLE `dux_tools_queue_config` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table dux_tools_send
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_tools_send`;

CREATE TABLE `dux_tools_send` (
  `send_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `receive` varchar(250) DEFAULT '' COMMENT '接收账号',
  `title` varchar(250) DEFAULT '' COMMENT '发送标题',
  `content` text COMMENT '发送内容',
  `param` text COMMENT '附加参数',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发送状态',
  `type` varchar(50) DEFAULT '' COMMENT '发送类型',
  `start_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `stop_time` int(10) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `remark` varchar(250) DEFAULT '' COMMENT '备注',
  `user_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否会员',
  PRIMARY KEY (`send_id`),
  KEY `type` (`type`),
  KEY `start_time` (`start_time`),
  KEY `stop_time` (`stop_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;



# Dump of table dux_tools_send_config
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_tools_send_config`;

CREATE TABLE `dux_tools_send_config` (
  `config_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` varchar(250) DEFAULT '' COMMENT '类型名',
  `setting` text COMMENT '配置内容',
  PRIMARY KEY (`config_id`),
  KEY `type` (`type`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;



# Dump of table dux_tools_send_data
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_tools_send_data`;

CREATE TABLE `dux_tools_send_data` (
  `data_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(50) DEFAULT '',
  `label` varchar(250) DEFAULT '',
  `class` varchar(250) DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(250) DEFAULT '',
  `data` text,
  `url` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`data_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table dux_tools_send_default
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_tools_send_default`;

CREATE TABLE `dux_tools_send_default` (
  `default_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class` varchar(50) DEFAULT '' COMMENT '种类',
  `type` varchar(50) DEFAULT '' COMMENT '类型',
  `tpl` text COMMENT '基础模板',
  PRIMARY KEY (`default_id`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `dux_tools_send_default` WRITE;
/*!40000 ALTER TABLE `dux_tools_send_default` DISABLE KEYS */;

INSERT INTO `dux_tools_send_default` (`default_id`, `class`, `type`, `tpl`)
VALUES
	(1,'sms','alsms',''),
	(2,'mail','email','<figure class=\"table\">\r\n<table style=\"height: 167px;\" width=\"467\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 452.219px;\">\r\n<p>[内容区域]</p>\r\n<p>此为系统邮件，请勿回复<br />请保管好您的邮箱，避免账号被他人盗用</p>\r\n<p>[网站名称] [网址]</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</figure>\r\n<p><br />&nbsp;</p>'),
	(3,'wechat','wechat',''),
	(4,'app','xiaomi',''),
	(5,'mail_tpl','<figure class=\"table\">\r\n<table style=\"height: 167p',''),
	(6,'site','site','');

/*!40000 ALTER TABLE `dux_tools_send_default` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table dux_tools_send_tpl
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_tools_send_tpl`;

CREATE TABLE `dux_tools_send_tpl` (
  `tpl_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(250) DEFAULT '' COMMENT '模板标题',
  `content` text COMMENT '模板内容',
  `time` int(10) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`tpl_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table dux_wechat_app
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_wechat_app`;

CREATE TABLE `dux_wechat_app` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT '',
  `label` varchar(20) DEFAULT '',
  `appid` varchar(100) NOT NULL DEFAULT '',
  `secret` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table dux_wechat_config
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_wechat_config`;

CREATE TABLE `dux_wechat_config` (
  `config_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT '',
  `content` text,
  `description` varchar(250) DEFAULT '',
  PRIMARY KEY (`config_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `dux_wechat_config` WRITE;
/*!40000 ALTER TABLE `dux_wechat_config` DISABLE KEYS */;

INSERT INTO `dux_wechat_config` (`config_id`, `name`, `content`, `description`)
VALUES
	(1,'appid','','AppID'),
	(2,'secret','','AppSecret'),
	(3,'token','','Token'),
	(4,'aeskey','','EncodingAESKey'),
	(7,'message_focus','欢迎关注DuxCMS',''),
	(8,'message_name','DuxCMS',''),
	(10,'mp_name','DuxCMS',''),
	(11,'mp_desc','点击保存到相册可以识别二维码,复制公众号可以到微信进行搜索',''),
	(12,'mp_qrcode','public/images/logo.png','');

/*!40000 ALTER TABLE `dux_wechat_config` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table dux_wechat_menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_wechat_menu`;

CREATE TABLE `dux_wechat_menu` (
  `menu_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) NOT NULL DEFAULT '0',
  `name` varchar(100) DEFAULT '',
  `type` tinyint(1) NOT NULL,
  `sort` int(10) NOT NULL DEFAULT '0',
  `data` text,
  PRIMARY KEY (`menu_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `dux_wechat_menu` WRITE;
/*!40000 ALTER TABLE `dux_wechat_menu` DISABLE KEYS */;

INSERT INTO `dux_wechat_menu` (`menu_id`, `parent_id`, `name`, `type`, `sort`, `data`)
VALUES
	(13,0,'默认菜单',2,0,'{\"type\":\"view\",\"url\":\"http:\\/\\/www.duxphp.com\"}');

/*!40000 ALTER TABLE `dux_wechat_menu` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table dux_wechat_miniapp
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dux_wechat_miniapp`;

CREATE TABLE `dux_wechat_miniapp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT '',
  `label` varchar(20) DEFAULT '',
  `appid` varchar(100) NOT NULL DEFAULT '',
  `secret` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
