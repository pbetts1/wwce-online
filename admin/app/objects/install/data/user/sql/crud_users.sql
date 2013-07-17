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

 Date: 03/27/2013 01:23:51 AM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `crud_users`
-- ----------------------------
DROP TABLE IF EXISTS `crud_users`;
CREATE TABLE `crud_users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `group_id` bigint(20) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `user_password` varchar(100) DEFAULT NULL,
  `user_first_name` varchar(255) DEFAULT NULL,
  `user_las_name` varchar(255) DEFAULT NULL,
  `user_image` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `user_website` varchar(255) DEFAULT NULL,
  `user_aim` varchar(255) DEFAULT NULL,
  `user_yahoo` varchar(255) DEFAULT NULL,
  `user_skype` varchar(255) DEFAULT NULL,
  `user_info` text,
  `user_manage_flag` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `crud_users`
-- ----------------------------
BEGIN;
INSERT INTO `crud_users` VALUES ('1', '1', 'admin', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'demo', 'admin (Update)', '1354699666-image1.png', 'admin@demo.com', '', '', '', '', '', '0'), ('2', '2', 'suser', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'demo', 'suser', '1354699676-image2.png', 'suser@demo.com', '', '', '', '', '', null), ('3', '3', 'user', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'demo', 'user', '1354699685-image3.png', 'user@demo.com', '', '', '', '', '', '0');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
