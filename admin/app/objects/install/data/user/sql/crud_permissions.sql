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

 Date: 03/27/2013 01:24:32 AM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `crud_permissions`
-- ----------------------------
DROP TABLE IF EXISTS `crud_permissions`;
CREATE TABLE `crud_permissions` (
  `group_id` bigint(20) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `permission_type` tinyint(4) NOT NULL,
  PRIMARY KEY (`group_id`,`table_name`,`permission_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `crud_permissions`
-- ----------------------------
BEGIN;
INSERT INTO `crud_permissions` VALUES ('1', 'articles', '1'), ('1', 'articles', '2'), ('1', 'articles', '3'), ('1', 'articles', '4'), ('1', 'articles', '5'), ('1', 'categories', '1'), ('1', 'categories', '2'), ('1', 'categories', '3'), ('1', 'categories', '4'), ('1', 'categories', '5'), ('1', 'crud_groups', '1'), ('1', 'crud_groups', '2'), ('1', 'crud_groups', '3'), ('1', 'crud_groups', '4'), ('1', 'crud_users', '1'), ('1', 'crud_users', '2'), ('1', 'crud_users', '3'), ('1', 'crud_users', '4'), ('2', 'articles', '1'), ('2', 'articles', '2'), ('2', 'articles', '3'), ('2', 'articles', '4'), ('2', 'categories', '1'), ('2', 'categories', '2'), ('2', 'categories', '3'), ('2', 'categories', '4'), ('2', 'crud_groups', '1'), ('2', 'crud_groups', '2'), ('2', 'crud_groups', '3'), ('2', 'crud_groups', '4'), ('2', 'crud_users', '1'), ('2', 'crud_users', '2'), ('2', 'crud_users', '3'), ('2', 'crud_users', '4'), ('3', 'articles', '4'), ('3', 'categories', '4');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
