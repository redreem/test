/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : testbeejee

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-12-29 18:52:37
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `d_task_status`
-- ----------------------------
DROP TABLE IF EXISTS `d_task_status`;
CREATE TABLE `d_task_status` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `status_description` text NOT NULL COMMENT 'статус задачи',
  `status_aliace` varchar(32) NOT NULL COMMENT 'алиас статуса',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of d_task_status
-- ----------------------------
INSERT INTO `d_task_status` VALUES ('1', 'в работе', 'in_progress');
INSERT INTO `d_task_status` VALUES ('2', 'выполнено', 'completed');

-- ----------------------------
-- Table structure for `tasks`
-- ----------------------------
DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(64) NOT NULL COMMENT 'id пользвателя из таблицы users',
  `user_email` varchar(64) NOT NULL,
  `task_status` tinyint(3) unsigned NOT NULL COMMENT 'статус задачи из справочника d_task_status',
  `task_description` text NOT NULL COMMENT 'описание задачи',
  `task_description_edited` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tasks
-- ----------------------------
INSERT INTO `tasks` VALUES ('1', 'asd', 'asd@asd.ru', '1', 'описание 22222 c 123 ddd alert(123); ddd333', '1');
INSERT INTO `tasks` VALUES ('2', 'qwe', 'qwe@qwe.ru', '1', '123 qwe 2 2 привет 123', '0');
INSERT INTO `tasks` VALUES ('5', 'ddd', 'qwe@qwe.ru', '1', 'wqwe alert(123); qwe', '0');
INSERT INTO `tasks` VALUES ('6', 'sss', 'qwe@qwe.ru', '1', '123 &amp;amp;lt;script&amp;amp;gt;alert(222);&amp;amp;lt;/script&amp;amp;gt; 333', '0');
