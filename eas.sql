/*
Navicat MySQL Data Transfer

Source Server         : localhost3310
Source Server Version : 50505
Source Host           : localhost:3310
Source Database       : eas

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-10-28 17:53:13
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `migrations`
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('2016_08_22_080506_users', '1');
INSERT INTO `migrations` VALUES ('2016_08_25_053925_user_profile', '1');
INSERT INTO `migrations` VALUES ('2016_08_25_082400_user_info', '1');
INSERT INTO `migrations` VALUES ('2016_08_30_094622_permission', '2');

-- ----------------------------
-- Table structure for `node`
-- ----------------------------
DROP TABLE IF EXISTS `node`;
CREATE TABLE `node` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `sort` tinyint(4) NOT NULL DEFAULT '0',
  `icon` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of node
-- ----------------------------
INSERT INTO `node` VALUES ('1', '0', '主页', 'main.index', '1', 'fa-home', '1', '2016-08-31 15:02:05', '2016-08-31 15:02:08');
INSERT INTO `node` VALUES ('2', '0', '权限管理', '#', '2', 'fa-desktop', '1', '2016-08-31 15:12:51', '2016-08-31 15:12:54');
INSERT INTO `node` VALUES ('3', '2', '角色列表', 'role.index', '2', 'fa-caret-right', '1', '2016-08-31 15:22:26', '2016-08-31 15:22:32');
INSERT INTO `node` VALUES ('4', '6', '用户列表', 'user.index', '1', 'fa-caret-right', '1', '2016-08-31 15:22:34', '2016-10-27 03:19:45');
INSERT INTO `node` VALUES ('5', '2', '节点列表', 'node.index', '1', 'fa-caret-right', '1', '2016-08-31 15:50:18', '2016-08-31 15:43:45');
INSERT INTO `node` VALUES ('6', '0', '员工列表', '#', '3', 'fa-users', '1', '2016-10-25 07:11:41', '2016-10-28 09:50:58');
INSERT INTO `node` VALUES ('7', '5', '添加权限视图', 'node.addNode', '1', '', '1', '2016-10-28 08:51:08', '2016-10-28 08:51:08');
INSERT INTO `node` VALUES ('8', '5', '添加权限', 'node.createNode', '2', '', '1', '2016-10-28 08:52:00', '2016-10-28 08:52:00');

-- ----------------------------
-- Table structure for `role`
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `sort` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of role
-- ----------------------------

-- ----------------------------
-- Table structure for `role_permission`
-- ----------------------------
DROP TABLE IF EXISTS `role_permission`;
CREATE TABLE `role_permission` (
  `role_id` int(10) unsigned NOT NULL,
  `permission_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of role_permission
-- ----------------------------

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_img` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `supper_admin` tinyint(1) NOT NULL DEFAULT '0',
  `last_login` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `users1_email_unique` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', '超级管理员', 'admin@sh.net', 'resources/views/template/default/assets/avatars/user.jpg', '4297f44b13955235245b2497399d7a93', '0', '2016-10-28 09:50:44', '2016-05-25 05:56:33', '2016-10-28 09:50:44');

-- ----------------------------
-- Table structure for `users_info`
-- ----------------------------
DROP TABLE IF EXISTS `users_info`;
CREATE TABLE `users_info` (
  `user_id` int(11) NOT NULL,
  `birth_date` timestamp NULL DEFAULT NULL,
  `nation` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `native_place` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `residence` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `identification_card` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `marital_status` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `political_outlook` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_team` timestamp NULL DEFAULT NULL,
  `date_admission` timestamp NULL DEFAULT NULL,
  `education` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `degree` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `health` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stature` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `weight` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `now_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `family_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bivouacked_card` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(18) COLLATE utf8_unicode_ci DEFAULT NULL,
  `major` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `graduate_school` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `old_word_company` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `special_contact_info` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_trainee` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_graduation` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_salesman` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_assignment` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `junior_date` timestamp NULL DEFAULT NULL,
  `trial_date_start` timestamp NULL DEFAULT NULL,
  `trial_date_end` timestamp NULL DEFAULT NULL,
  `contract_date_start` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of users_info
-- ----------------------------
INSERT INTO `users_info` VALUES ('1', '2016-08-25 17:39:13', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);

-- ----------------------------
-- Table structure for `users_profile`
-- ----------------------------
DROP TABLE IF EXISTS `users_profile`;
CREATE TABLE `users_profile` (
  `user_id` int(11) NOT NULL,
  `department` int(11) NOT NULL,
  `post` int(11) NOT NULL,
  `post_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `direct_leader` int(11) NOT NULL,
  `subordinate` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `office_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `office_tel` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(18) COLLATE utf8_unicode_ci NOT NULL,
  `fax` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `speciality` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hobbies` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of users_profile
-- ----------------------------
INSERT INTO `users_profile` VALUES ('1', '0', '0', '开发工程师', '0', '', '正式员工', '上海', '55555555', '11111111111', '1111111-11', '计算机', '游戏', null, null);
