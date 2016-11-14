/*
Navicat MySQL Data Transfer

Source Server         : localhost3310
Source Server Version : 50505
Source Host           : localhost:3310
Source Database       : eas

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-11-14 17:51:40
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `company`
-- ----------------------------
DROP TABLE IF EXISTS `company`;
CREATE TABLE `company` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `legal_person` varchar(30) NOT NULL,
  `reg_capital` varchar(100) NOT NULL,
  `reg_date` varchar(10) NOT NULL,
  `operate_date` varchar(30) NOT NULL,
  `business_operate` text NOT NULL,
  `credentials_number` varchar(30) NOT NULL,
  `website_address` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `fax` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of company
-- ----------------------------
INSERT INTO `company` VALUES ('1', '上海test有限公司', '1222', '2223', '333', '4444', '2016-11-01', '2016-11-01至2016-12-31', '512412421421', '6412412421', '7', '7', '9', '0000-00-00 00:00:00', '2016-11-08 10:13:04');

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
  `is_menu` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of node
-- ----------------------------
INSERT INTO `node` VALUES ('1', '0', '主页', 'main.index', '1', 'fa-home', '1', '1', '2016-08-31 15:02:05', '2016-08-31 15:02:08');
INSERT INTO `node` VALUES ('2', '0', '权限管理', '#', '2', 'fa-desktop', '1', '1', '2016-08-31 15:12:51', '2016-08-31 15:12:54');
INSERT INTO `node` VALUES ('3', '2', '角色列表', 'role.index', '3', 'fa-caret-right', '1', '1', '2016-08-31 15:22:26', '2016-10-31 06:34:15');
INSERT INTO `node` VALUES ('4', '6', '用户列表', 'user.index', '1', 'fa-caret-right', '1', '1', '2016-08-31 15:22:34', '2016-11-04 15:15:30');
INSERT INTO `node` VALUES ('5', '2', '权限列表', 'node.index', '1', 'fa-caret-right', '1', '1', '2016-08-31 15:50:18', '2016-08-31 15:43:45');
INSERT INTO `node` VALUES ('6', '0', '员工列表', '#', '4', 'fa-users', '1', '1', '2016-10-25 07:11:41', '2016-11-03 14:57:52');
INSERT INTO `node` VALUES ('7', '5', '添加权限视图', 'node.addNode', '2', '', '0', '1', '2016-10-28 08:51:08', '2016-10-28 08:51:08');
INSERT INTO `node` VALUES ('8', '5', '添加权限', 'node.createNode', '3', '', '0', '1', '2016-10-28 08:52:00', '2016-10-28 08:52:00');
INSERT INTO `node` VALUES ('9', '5', '编辑权限视图', 'node.editNode', '4', '', '0', '1', '2016-10-31 07:35:30', '2016-10-31 07:35:30');
INSERT INTO `node` VALUES ('10', '5', '编辑权限', 'node.updateNode', '5', '', '0', '1', '2016-10-31 07:35:46', '2016-10-31 07:35:46');
INSERT INTO `node` VALUES ('11', '5', '权限列表', 'node.getNode', '1', '', '0', '1', '2016-11-02 15:18:50', '2016-11-02 15:18:53');
INSERT INTO `node` VALUES ('12', '3', '角色列表', 'role.getRole', '1', '', '0', '1', null, null);
INSERT INTO `node` VALUES ('13', '3', '添加角色列表', 'role.addRole', '2', '', '0', '1', null, null);
INSERT INTO `node` VALUES ('14', '3', '添加角色', 'role.createRole', '3', '', '0', '1', null, null);
INSERT INTO `node` VALUES ('15', '3', '编辑角色视图', 'role.editRole', '4', '', '0', '1', null, null);
INSERT INTO `node` VALUES ('16', '3', '编辑角色', 'role.updateRole', '5', '', '0', '1', null, null);
INSERT INTO `node` VALUES ('17', '3', '角色详情', 'role.roleInfo', '7', '', '0', '1', null, null);
INSERT INTO `node` VALUES ('18', '5', '删除权限', 'node.delNode', '6', '', '0', '1', null, null);
INSERT INTO `node` VALUES ('19', '3', '删除角色', 'role.delRole', '6', '', '0', '1', null, null);
INSERT INTO `node` VALUES ('20', '0', '公司信息管理', '#', '3', ' fa-home', '1', '1', '2016-11-03 14:57:11', '2016-11-04 15:33:15');
INSERT INTO `node` VALUES ('21', '20', '公司信息', 'company.index', '1', 'fa-caret-right', '1', '1', '2016-11-04 15:17:22', '2016-11-04 15:31:45');
INSERT INTO `node` VALUES ('22', '20', '部门列表', 'department.index', '2', 'fa-caret-right', '1', '1', '2016-11-04 15:34:22', '2016-11-04 15:36:38');
INSERT INTO `node` VALUES ('23', '20', '岗位列表', 'position.index', '3', 'fa-caret-right', '1', '1', '2016-11-04 15:35:53', '2016-11-04 15:36:45');

-- ----------------------------
-- Table structure for `position`
-- ----------------------------
DROP TABLE IF EXISTS `position`;
CREATE TABLE `position` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `manager` int(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of position
-- ----------------------------

-- ----------------------------
-- Table structure for `role`
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `sort` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `role` VALUES ('1', '管理员', '1', '1', '2016-11-01 07:06:28', '2016-11-03 09:24:21');

-- ----------------------------
-- Table structure for `role_node`
-- ----------------------------
DROP TABLE IF EXISTS `role_node`;
CREATE TABLE `role_node` (
  `role_id` int(10) unsigned NOT NULL,
  `node_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of role_node
-- ----------------------------
INSERT INTO `role_node` VALUES ('1', '1');
INSERT INTO `role_node` VALUES ('1', '2');
INSERT INTO `role_node` VALUES ('1', '5');
INSERT INTO `role_node` VALUES ('1', '11');
INSERT INTO `role_node` VALUES ('1', '7');
INSERT INTO `role_node` VALUES ('1', '8');
INSERT INTO `role_node` VALUES ('1', '9');
INSERT INTO `role_node` VALUES ('1', '10');
INSERT INTO `role_node` VALUES ('1', '18');
INSERT INTO `role_node` VALUES ('1', '3');
INSERT INTO `role_node` VALUES ('1', '12');
INSERT INTO `role_node` VALUES ('1', '13');
INSERT INTO `role_node` VALUES ('1', '14');
INSERT INTO `role_node` VALUES ('1', '15');
INSERT INTO `role_node` VALUES ('1', '16');
INSERT INTO `role_node` VALUES ('1', '19');
INSERT INTO `role_node` VALUES ('1', '17');
INSERT INTO `role_node` VALUES ('1', '6');
INSERT INTO `role_node` VALUES ('1', '4');

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
  `role_id` int(10) unsigned NOT NULL,
  `supper_admin` tinyint(1) NOT NULL DEFAULT '0',
  `last_login` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `users1_email_unique` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', '超级管理员', 'admin@sh.net', 'resources/views/template/default/assets/avatars/user.jpg', '4297f44b13955235245b2497399d7a93', '0', '1', '2016-11-08 10:12:53', '2016-05-25 05:56:33', '2016-11-08 10:12:53');
INSERT INTO `users` VALUES ('2', 'test', 'test@sh.net', 'resources/views/template/default/assets/avatars/user.jpg', '4297f44b13955235245b2497399d7a93', '1', '0', '2016-11-03 09:32:40', '2016-11-01 15:07:59', '2016-11-03 09:32:40');

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
