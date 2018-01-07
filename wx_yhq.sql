# Host: localhost  (Version 5.5.53)
# Date: 2018-01-06 17:59:22
# Generator: MySQL-Front 6.0  (Build 2.20)


#
# Structure for table "wx_app"
#

DROP TABLE IF EXISTS `wx_app`;
CREATE TABLE `wx_app` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) DEFAULT '',
  `password` varchar(100) DEFAULT '',
  `tb_pid` varchar(100) DEFAULT '' COMMENT '淘宝客pid',
  `app_key` char(32) DEFAULT '' COMMENT '本应用授权码',
  `create_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `wx_key` varchar(255) DEFAULT '' COMMENT '微信key',
  `wx_secret` varchar(255) DEFAULT '' COMMENT '微信secret',
  `dtk_key` varchar(255) DEFAULT '' COMMENT '大淘客KEY',
  `enable` tinyint(3) DEFAULT '0' COMMENT '是否启用',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_email` (`email`),
  KEY `index_key` (`app_key`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

#
# Data for table "wx_app"
#

INSERT INTO `wx_app` VALUES (1,'thukyhsu@qq.com','$2y$10$SdKDxfkkoi1Y9BmPEVlxHupo3Zv26Q6ozR9TJf8pTydACakkjIRm6','','1b07005a1582b1efdc5fd97cbe6f8414',1513341689,NULL,1513341689,'','','',1),(2,'1602264241@qq.com','$2y$10$ZzbK0k78xMOxZq3okmN6fumjKN8Xqbb90Xrrc5MQF5Vv0P7.Izx7u','mm_32805119_40744564_164568086','b0548a8e5ad97137a3ba44c7c61fd2d4',1514981627,NULL,1514981627,'wx3d8401f8b9c020f4','768ed0e402667c91450d8ceac0dd8ec8','bs3mmhfk23',1);

#
# Structure for table "wx_user"
#

DROP TABLE IF EXISTS `wx_user`;
CREATE TABLE `wx_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) DEFAULT '0',
  `openid` varchar(50) NOT NULL,
  `nickname` varchar(50) DEFAULT '',
  `extend` varchar(255) DEFAULT '',
  `delete_time` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL COMMENT '注册时间',
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `openid` (`openid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

#
# Data for table "wx_user"
#

INSERT INTO `wx_user` VALUES (1,2,'ojvgG0e5G21hHNuSYSxlKwHtQ3Ro','','',NULL,1514987627,1514987627);
