/*
Navicat MySQL Data Transfer

Source Server         : LocalHost
Source Server Version : 50508
Source Host           : localhost:3306
Source Database       : ria

Target Server Type    : MYSQL
Target Server Version : 50508
File Encoding         : 65001

Date: 2015-02-11 02:12:01
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `login` varchar(64) CHARACTER SET utf8 NOT NULL,
  `password` varchar(64) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`user_id`,`login`),
  UNIQUE KEY `u_id` (`user_id`),
  UNIQUE KEY `u_login` (`login`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'admin', 'admin');
INSERT INTO `users` VALUES ('2', 'test', 'test');
