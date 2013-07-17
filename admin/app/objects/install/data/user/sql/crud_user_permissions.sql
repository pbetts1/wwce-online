/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50144
 Source Host           : localhost
 Source Database       : scrud_v1.4

 Target Server Type    : MySQL
 Target Server Version : 50144
 File Encoding         : utf-8

 Date: 03/27/2013 01:24:23 AM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `crud_user_permissions`
-- ----------------------------
DROP TABLE IF EXISTS `crud_user_permissions`;
CREATE TABLE `crud_user_permissions` (
  `user_id` bigint(20) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `permission_type` tinyint(4) NOT NULL,
  PRIMARY KEY (`user_id`,`table_name`,`permission_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;
