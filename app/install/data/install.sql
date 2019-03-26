# ************************************************************
# Sequel Pro SQL dump
# Version 5438
#
# https://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.25)
# Database: duxcms3
# Generation Time: 2019-03-26 01:54:11 +0000
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
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`class_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `dux_article_class` WRITE;
/*!40000 ALTER TABLE `dux_article_class` DISABLE KEYS */;

INSERT INTO `dux_article_class` (`class_id`, `parent_id`, `name`, `subname`, `image`, `keyword`, `description`, `status`, `sort`)
VALUES
	(1,0,'默认分类','','','','',1,0);

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
	(6,'info_title','DuxCMS','站点标题'),
	(7,'info_keyword','DuxCMS,PHP,CMS,内容管理系统,PHP开源','站点关键词'),
	(8,'info_desc','DuxCMS内容管理系统','站点描述'),
	(9,'info_copyright','Copyright@2016-2018 www.duxcms.com  All Rights Reserved.','版权信息'),
	(10,'info_email','admin@duxphp.com','站点邮箱'),
	(11,'info_tel','','站点电话'),
	(16,'info_name','DuxCMS','站点名称'),
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
	(1,'管理员','系统后台管理员','a:234:{i:0;s:21:\"article.Content.index\";i:1;s:19:\"article.Content.add\";i:2;s:20:\"article.Content.edit\";i:3;s:22:\"article.Content.status\";i:4;s:19:\"article.Content.del\";i:5;s:19:\"article.Class.index\";i:6;s:17:\"article.Class.add\";i:7;s:18:\"article.Class.edit\";i:8;s:20:\"article.Class.status\";i:9;s:17:\"article.Class.del\";i:10;s:22:\"integral.Content.index\";i:11;s:20:\"integral.Content.add\";i:12;s:21:\"integral.Content.edit\";i:13;s:23:\"integral.Content.status\";i:14;s:20:\"integral.Content.del\";i:15;s:20:\"integral.Class.index\";i:16;s:18:\"integral.Class.add\";i:17;s:19:\"integral.Class.edit\";i:18;s:21:\"integral.Class.status\";i:19;s:18:\"integral.Class.del\";i:20;s:20:\"integral.Order.index\";i:21;s:19:\"integral.Order.info\";i:22;s:22:\"integral.Comment.index\";i:23;s:23:\"integral.Comment.status\";i:24;s:26:\"integral.OrderStatis.index\";i:25;s:18:\"mall.Content.index\";i:26;s:16:\"mall.Content.add\";i:27;s:17:\"mall.Content.edit\";i:28;s:19:\"mall.Content.status\";i:29;s:16:\"mall.Content.del\";i:30;s:16:\"mall.Class.index\";i:31;s:14:\"mall.Class.add\";i:32;s:15:\"mall.Class.edit\";i:33;s:17:\"mall.Class.status\";i:34;s:14:\"mall.Class.del\";i:35;s:16:\"mall.Order.index\";i:36;s:15:\"mall.Order.info\";i:37;s:18:\"mall.Comment.index\";i:38;s:19:\"mall.Comment.status\";i:39;s:22:\"mall.SellRanking.index\";i:40;s:19:\"mall.SellList.index\";i:41;s:22:\"marketing.Coupon.index\";i:42;s:20:\"marketing.Coupon.add\";i:43;s:21:\"marketing.Coupon.edit\";i:44;s:23:\"marketing.Coupon.status\";i:45;s:20:\"marketing.Coupon.del\";i:46;s:27:\"marketing.CouponClass.index\";i:47;s:25:\"marketing.CouponClass.add\";i:48;s:26:\"marketing.CouponClass.edit\";i:49;s:28:\"marketing.CouponClass.status\";i:50;s:25:\"marketing.CouponClass.del\";i:51;s:25:\"marketing.CouponLog.index\";i:52;s:23:\"marketing.CouponLog.del\";i:53;s:23:\"member.MemberUser.index\";i:54;s:21:\"member.MemberUser.add\";i:55;s:22:\"member.MemberUser.edit\";i:56;s:24:\"member.MemberUser.status\";i:57;s:21:\"member.MemberUser.del\";i:58;s:24:\"member.MemberGrade.index\";i:59;s:22:\"member.MemberGrade.add\";i:60;s:23:\"member.MemberGrade.edit\";i:61;s:25:\"member.MemberGrade.status\";i:62;s:22:\"member.MemberGrade.del\";i:63;s:23:\"member.MemberReal.index\";i:64;s:23:\"member.MemberReal.check\";i:65;s:23:\"member.MemberRole.index\";i:66;s:21:\"member.MemberRole.add\";i:67;s:22:\"member.MemberRole.edit\";i:68;s:21:\"member.MemberRole.del\";i:69;s:23:\"member.PayAccount.index\";i:70;s:19:\"member.PayLog.index\";i:71;s:20:\"member.PayCash.index\";i:72;s:20:\"member.PayCard.index\";i:73;s:18:\"member.PayCard.add\";i:74;s:19:\"member.PayCard.edit\";i:75;s:18:\"member.PayCard.del\";i:76;s:20:\"member.PayConf.index\";i:77;s:22:\"member.PayConf.setting\";i:78;s:20:\"member.PayBank.index\";i:79;s:18:\"member.PayBank.add\";i:80;s:19:\"member.PayBank.edit\";i:81;s:18:\"member.PayBank.del\";i:82;s:26:\"member.PointsAccount.index\";i:83;s:22:\"member.PointsLog.index\";i:84;s:25:\"member.MemberConfig.index\";i:85;s:23:\"member.MemberConfig.reg\";i:86;s:25:\"member.MemberVerify.index\";i:87;s:26:\"member.MemberVerify.status\";i:88;s:23:\"member.MemberVerify.del\";i:89;s:26:\"member.MemberRanking.index\";i:90;s:24:\"member.MemberTrend.index\";i:91;s:18:\"order.Config.index\";i:92;s:25:\"order.ConfigExpress.index\";i:93;s:25:\"order.ConfigPrinter.index\";i:94;s:23:\"order.ConfigPrinter.add\";i:95;s:24:\"order.ConfigPrinter.edit\";i:96;s:26:\"order.ConfigPrinter.status\";i:97;s:23:\"order.ConfigPrinter.del\";i:98;s:26:\"order.ConfigDelivery.index\";i:99;s:25:\"order.ConfigWaybill.index\";i:100;s:27:\"order.ConfigWaybill.setting\";i:101;s:18:\"order.Parcel.index\";i:102;s:18:\"order.Parcel.print\";i:103;s:19:\"order.Parcel.status\";i:104;s:16:\"order.Parcel.del\";i:105;s:20:\"order.Delivery.index\";i:106;s:20:\"order.Delivery.print\";i:107;s:21:\"order.Delivery.status\";i:108;s:18:\"order.Delivery.del\";i:109;s:19:\"order.Receipt.index\";i:110;s:20:\"order.Receipt.status\";i:111;s:17:\"order.Receipt.del\";i:112;s:19:\"order.Comment.index\";i:113;s:18:\"order.Refund.index\";i:114;s:17:\"order.Refund.info\";i:115;s:16:\"order.Take.index\";i:116;s:14:\"order.Take.add\";i:117;s:15:\"order.Take.edit\";i:118;s:17:\"order.Take.status\";i:119;s:14:\"order.Take.del\";i:120;s:19:\"order.Invoice.index\";i:121;s:20:\"order.Invoice.status\";i:122;s:17:\"order.Invoice.del\";i:123;s:24:\"order.InvoiceClass.index\";i:124;s:22:\"order.InvoiceClass.add\";i:125;s:23:\"order.InvoiceClass.edit\";i:126;s:25:\"order.InvoiceClass.status\";i:127;s:22:\"order.InvoiceClass.del\";i:128;s:23:\"order.OrderStatis.index\";i:129;s:17:\"sale.Config.index\";i:130;s:23:\"sale.ConfigNotice.index\";i:131;s:15:\"sale.User.index\";i:132;s:20:\"sale.UserApply.index\";i:133;s:20:\"sale.UserLevel.index\";i:134;s:16:\"sale.Order.index\";i:135;s:18:\"sale.Account.index\";i:136;s:21:\"sale.AccountLog.index\";i:137;s:22:\"sale.AccountCash.index\";i:138;s:22:\"sale.SaleRanking.index\";i:139;s:21:\"sale.SaleStatis.index\";i:140;s:20:\"sale.SaleTrend.index\";i:141;s:16:\"shop.Brand.index\";i:142;s:14:\"shop.Brand.add\";i:143;s:15:\"shop.Brand.edit\";i:144;s:17:\"shop.Brand.status\";i:145;s:14:\"shop.Brand.del\";i:146;s:21:\"shop.BrandApply.index\";i:147;s:19:\"shop.BrandApply.add\";i:148;s:20:\"shop.BrandApply.edit\";i:149;s:22:\"shop.BrandApply.status\";i:150;s:19:\"shop.BrandApply.del\";i:151;s:24:\"shop.BrandContract.index\";i:152;s:22:\"shop.BrandContract.add\";i:153;s:23:\"shop.BrandContract.edit\";i:154;s:25:\"shop.BrandContract.status\";i:155;s:22:\"shop.BrandContract.del\";i:156;s:15:\"shop.Spec.index\";i:157;s:13:\"shop.Spec.add\";i:158;s:14:\"shop.Spec.edit\";i:159;s:16:\"shop.Spec.status\";i:160;s:13:\"shop.Spec.del\";i:161;s:20:\"shop.SpecGroup.index\";i:162;s:18:\"shop.SpecGroup.add\";i:163;s:19:\"shop.SpecGroup.edit\";i:164;s:21:\"shop.SpecGroup.status\";i:165;s:18:\"shop.SpecGroup.del\";i:166;s:17:\"shop.Config.index\";i:167;s:17:\"site.Config.index\";i:168;s:15:\"site.Config.tpl\";i:169;s:17:\"site.Search.index\";i:170;s:15:\"site.Search.add\";i:171;s:16:\"site.Search.edit\";i:172;s:15:\"site.Search.del\";i:173;s:14:\"site.Tpl.index\";i:174;s:12:\"site.Tpl.add\";i:175;s:13:\"site.Tpl.edit\";i:176;s:12:\"site.Tpl.del\";i:177;s:22:\"statis.SiteViews.index\";i:178;s:18:\"system.Index.index\";i:179;s:21:\"system.Index.userData\";i:180;s:19:\"system.Notice.index\";i:181;s:17:\"system.Notice.del\";i:182;s:19:\"system.Update.index\";i:183;s:19:\"system.Config.index\";i:184;s:18:\"system.Config.user\";i:185;s:18:\"system.Config.info\";i:186;s:20:\"system.Config.upload\";i:187;s:25:\"system.ConfigManage.index\";i:188;s:23:\"system.ConfigManage.add\";i:189;s:24:\"system.ConfigManage.edit\";i:190;s:26:\"system.ConfigManage.status\";i:191;s:23:\"system.ConfigManage.del\";i:192;s:22:\"system.ConfigApi.index\";i:193;s:20:\"system.ConfigApi.add\";i:194;s:21:\"system.ConfigApi.edit\";i:195;s:23:\"system.ConfigApi.status\";i:196;s:20:\"system.ConfigApi.del\";i:197;s:25:\"system.ConfigUpload.index\";i:198;s:24:\"system.ConfigUpload.edit\";i:199;s:17:\"system.User.index\";i:200;s:15:\"system.User.add\";i:201;s:16:\"system.User.edit\";i:202;s:18:\"system.User.status\";i:203;s:15:\"system.User.del\";i:204;s:17:\"system.Role.index\";i:205;s:15:\"system.Role.add\";i:206;s:16:\"system.Role.edit\";i:207;s:15:\"system.Role.del\";i:208;s:18:\"system.Debug.index\";i:209;s:16:\"system.Debug.del\";i:210;s:22:\"system.SystemLog.index\";i:211;s:20:\"system.SystemLog.del\";i:212;s:24:\"system.Application.index\";i:213;s:22:\"system.Application.add\";i:214;s:23:\"system.Application.edit\";i:215;s:22:\"system.Application.del\";i:216;s:20:\"tools.SendData.index\";i:217;s:16:\"tools.Send.index\";i:218;s:14:\"tools.Send.add\";i:219;s:15:\"tools.Send.info\";i:220;s:20:\"tools.SendConf.index\";i:221;s:22:\"tools.SendConf.setting\";i:222;s:19:\"tools.SendTpl.index\";i:223;s:17:\"tools.SendTpl.add\";i:224;s:18:\"tools.SendTpl.edit\";i:225;s:17:\"tools.SendTpl.del\";i:226;s:23:\"tools.SendDefault.index\";i:227;s:17:\"tools.Label.index\";i:228;s:17:\"tools.Queue.index\";i:229;s:21:\"tools.QueueConf.index\";i:230;s:25:\"wechat.WechatConfig.index\";i:231;s:23:\"wechat.MenuConfig.index\";i:232;s:26:\"wechat.MiniappConfig.index\";i:233;s:22:\"wechat.AppConfig.index\";}');

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
