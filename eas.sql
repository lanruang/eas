/*
Navicat MySQL Data Transfer

Source Server         : localhost3310
Source Server Version : 50505
Source Host           : localhost:3310
Source Database       : eas

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-05-16 11:02:39
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `department`
-- ----------------------------
DROP TABLE IF EXISTS `department`;
CREATE TABLE `department` (
  `dep_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dep_name` varchar(50) NOT NULL,
  `dep_leader` int(10) unsigned DEFAULT '0',
  `dep_pid` int(10) unsigned DEFAULT '0',
  `sort` tinyint(4) NOT NULL,
  `is_del` tinyint(1) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`dep_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of department
-- ----------------------------
INSERT INTO `department` VALUES ('1', '总经办', '1', '0', '1', '0', '2017-05-09 09:50:37', null);
INSERT INTO `department` VALUES ('2', 'IT部', '2', '0', '2', '0', null, null);
INSERT INTO `department` VALUES ('3', 'java开发', '0', '2', '2', '0', '2017-05-10 05:27:35', null);
INSERT INTO `department` VALUES ('4', '.net开发', '0', '3', '2', '0', '2017-05-10 05:27:32', null);
INSERT INTO `department` VALUES ('5', 'php开发', '0', '3', '1', '0', '2017-05-10 05:27:37', null);

-- ----------------------------
-- Table structure for `node`
-- ----------------------------
DROP TABLE IF EXISTS `node`;
CREATE TABLE `node` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `sort` tinyint(4) NOT NULL DEFAULT '0',
  `icon` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_menu` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of node
-- ----------------------------
INSERT INTO `node` VALUES ('1', '0', '主页', 'main.index', '1', 'fa fa-home', '1', '1', '2016-08-31 15:02:05', '2016-08-31 15:02:08');
INSERT INTO `node` VALUES ('2', '0', '权限管理', '#', '2', 'fa fa-desktop', '1', '1', '2016-08-31 15:12:51', '2016-12-01 07:49:22');
INSERT INTO `node` VALUES ('3', '2', '角色列表', 'role.index', '2', 'fa fa-caret-right', '1', '1', '2016-08-31 15:22:26', '2016-11-25 08:03:06');
INSERT INTO `node` VALUES ('4', '32', '员工列表', 'user.index', '5', 'fa fa-caret-right', '1', '1', '2016-08-31 15:22:34', '2017-05-12 08:56:04');
INSERT INTO `node` VALUES ('5', '2', '权限列表', 'node.index', '1', 'fa fa-caret-right', '1', '1', '2016-08-31 15:50:18', '2016-08-31 15:43:45');
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
INSERT INTO `node` VALUES ('23', '4', '员工列表', 'user.getUser', '1', '', '0', '1', '2016-12-01 09:42:22', '2016-12-01 09:42:22');
INSERT INTO `node` VALUES ('24', '4', '添加员工视图', 'user.addUser', '2', '', '0', '1', '2016-12-01 09:42:39', '2016-12-01 09:44:09');
INSERT INTO `node` VALUES ('25', '4', '添加角色', 'user.createUser', '3', '', '0', '1', '2016-12-06 05:57:30', '2016-12-06 05:59:56');
INSERT INTO `node` VALUES ('26', '4', '编辑角色视图', 'user.editUser', '4', '', '0', '1', '2016-12-06 05:57:53', '2016-12-06 05:59:40');
INSERT INTO `node` VALUES ('27', '4', '编辑员工', 'user.updateUser', '5', '', '0', '1', '2016-12-06 05:58:31', '2016-12-06 05:58:31');
INSERT INTO `node` VALUES ('28', '4', '删除员工', 'user.delUser', '6', '', '0', '1', '2016-12-06 05:58:58', '2016-12-06 05:58:58');
INSERT INTO `node` VALUES ('29', '4', '员工详情', 'user.userInfo', '6', '', '0', '1', '2016-12-06 05:59:21', '2016-12-06 05:59:21');
INSERT INTO `node` VALUES ('30', '4', '修改密码', 'user.editPwd', '7', '', '0', '1', '2016-12-06 06:00:22', '2016-12-06 06:00:22');
INSERT INTO `node` VALUES ('31', '4', '重置密码', 'user.resetPwd', '8', '', '0', '1', '2016-12-06 06:00:43', '2016-12-06 06:00:55');
INSERT INTO `node` VALUES ('32', '0', '公司信息', '#', '4', 'fa fa-cog', '1', '1', '2017-02-20 08:11:24', '2017-05-12 03:39:15');
INSERT INTO `node` VALUES ('33', '32', '公司信息', 'company.index', '1', 'fa fa-caret-right', '1', '1', '2017-02-20 08:45:03', '2017-04-27 02:07:48');
INSERT INTO `node` VALUES ('34', '32', '部门列表', 'department.index', '3', 'fa fa-caret-right', '1', '1', '2017-04-27 03:17:44', '2017-05-12 07:58:00');
INSERT INTO `node` VALUES ('35', '32', '岗位列表', 'positions.index', '4', 'fa fa-caret-right', '1', '1', '2017-05-10 06:39:18', '2017-05-12 08:55:52');
INSERT INTO `node` VALUES ('37', '0', '合同管理', '#', '7', 'fa fa-briefcase', '1', '1', '2017-05-12 03:08:13', '2017-05-12 03:39:36');
INSERT INTO `node` VALUES ('38', '0', '预算管理', '#', '6', 'fa  fa-bar-chart-o', '1', '1', '2017-05-12 03:09:42', '2017-05-12 03:39:28');
INSERT INTO `node` VALUES ('39', '0', '我的工作', '#', '3', 'fa fa-briefcase', '1', '1', '2017-05-12 03:18:54', '2017-05-12 03:18:54');
INSERT INTO `node` VALUES ('40', '0', '流程控制', '#', '5', 'glyphicon glyphicon-refresh', '1', '1', '2017-05-12 03:39:57', '2017-05-12 03:40:12');
INSERT INTO `node` VALUES ('41', '0', '库存管理', '#', '8', 'fa fa-hdd-o', '1', '1', '2017-05-12 03:55:23', '2017-05-12 03:55:23');
INSERT INTO `node` VALUES ('42', '0', '报表管理', '#', '9', 'glyphicon glyphicon-indent-left', '1', '1', '2017-05-12 03:57:44', '2017-05-12 03:57:44');
INSERT INTO `node` VALUES ('46', '32', '科目管理', 'subjects.index', '2', 'fa fa-caret-right', '1', '1', '2017-05-12 08:55:00', '2017-05-12 08:55:00');
INSERT INTO `node` VALUES ('47', '0', '费用管理', '#', '8', 'glyphicon-list-alt', '1', '1', '2017-05-16 02:36:36', '2017-05-16 02:36:36');
INSERT INTO `node` VALUES ('48', '0', '系统管理', '#', '12', 'fa fa-cogs', '1', '1', '2017-05-16 02:38:06', '2017-05-16 02:38:06');
INSERT INTO `node` VALUES ('49', '0', '客户管理', '#', '6', 'fa fa-users', '1', '1', '2017-05-16 02:38:43', '2017-05-16 02:38:43');

-- ----------------------------
-- Table structure for `positions`
-- ----------------------------
DROP TABLE IF EXISTS `positions`;
CREATE TABLE `positions` (
  `pos_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pos_name` varchar(50) NOT NULL,
  `pos_pid` int(10) unsigned DEFAULT '0',
  `sort` tinyint(4) NOT NULL,
  `is_del` tinyint(1) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`pos_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of positions
-- ----------------------------
INSERT INTO `positions` VALUES ('1', '总经理', '0', '1', '0', '2017-05-10 07:26:20', '2017-05-10 07:26:20');
INSERT INTO `positions` VALUES ('2', 'IT部经理', '0', '1', '0', '2017-05-10 07:37:28', '2017-05-10 07:26:34');
INSERT INTO `positions` VALUES ('3', 'php工程师', '2', '1', '0', '2017-05-10 09:56:50', '2017-05-10 07:26:42');

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `role` VALUES ('5', '管理员', '1', '1', '2017-01-17 08:36:37', '2017-01-17 08:36:37');
INSERT INTO `role` VALUES ('6', 'test', '2', '1', '2017-03-07 05:53:27', '2017-04-26 09:55:41');

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
INSERT INTO `role_node` VALUES ('5', '1');
INSERT INTO `role_node` VALUES ('5', '2');
INSERT INTO `role_node` VALUES ('5', '5');
INSERT INTO `role_node` VALUES ('5', '11');
INSERT INTO `role_node` VALUES ('5', '7');
INSERT INTO `role_node` VALUES ('5', '8');
INSERT INTO `role_node` VALUES ('5', '9');
INSERT INTO `role_node` VALUES ('5', '10');
INSERT INTO `role_node` VALUES ('5', '18');
INSERT INTO `role_node` VALUES ('5', '3');
INSERT INTO `role_node` VALUES ('5', '12');
INSERT INTO `role_node` VALUES ('5', '13');
INSERT INTO `role_node` VALUES ('5', '14');
INSERT INTO `role_node` VALUES ('5', '15');
INSERT INTO `role_node` VALUES ('5', '16');
INSERT INTO `role_node` VALUES ('5', '19');
INSERT INTO `role_node` VALUES ('5', '17');
INSERT INTO `role_node` VALUES ('5', '4');
INSERT INTO `role_node` VALUES ('5', '23');
INSERT INTO `role_node` VALUES ('5', '24');
INSERT INTO `role_node` VALUES ('5', '25');
INSERT INTO `role_node` VALUES ('5', '26');
INSERT INTO `role_node` VALUES ('5', '27');
INSERT INTO `role_node` VALUES ('5', '28');
INSERT INTO `role_node` VALUES ('5', '29');
INSERT INTO `role_node` VALUES ('5', '30');
INSERT INTO `role_node` VALUES ('5', '31');
INSERT INTO `role_node` VALUES ('6', '1');
INSERT INTO `role_node` VALUES ('6', '32');
INSERT INTO `role_node` VALUES ('6', '4');
INSERT INTO `role_node` VALUES ('6', '23');
INSERT INTO `role_node` VALUES ('6', '24');
INSERT INTO `role_node` VALUES ('6', '25');
INSERT INTO `role_node` VALUES ('6', '26');
INSERT INTO `role_node` VALUES ('6', '27');
INSERT INTO `role_node` VALUES ('6', '28');
INSERT INTO `role_node` VALUES ('6', '29');
INSERT INTO `role_node` VALUES ('6', '30');
INSERT INTO `role_node` VALUES ('6', '31');

-- ----------------------------
-- Table structure for `subjects`
-- ----------------------------
DROP TABLE IF EXISTS `subjects`;
CREATE TABLE `subjects` (
  `sub_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sub_type` tinyint(1) NOT NULL DEFAULT '0',
  `sub_ip` varchar(255) NOT NULL,
  `sub_name` varchar(255) NOT NULL,
  `sub_pid` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_del` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sub_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of subjects
-- ----------------------------
INSERT INTO `subjects` VALUES ('1', '0', '1000', '资产', '0', '1', '0', '2017-05-12 17:47:36', '2017-05-12 17:47:36');
INSERT INTO `subjects` VALUES ('2', '0', '2000', '负债', '0', '1', '0', '2017-05-12 17:47:35', '2017-05-12 17:47:35');
INSERT INTO `subjects` VALUES ('3', '0', '4000', '权益类', '0', '1', '0', null, null);
INSERT INTO `subjects` VALUES ('4', '0', '6000', '损益', '0', '1', '0', null, null);

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
  `status` tinyint(1) unsigned zerofill NOT NULL,
  `is_del` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `users1_email_unique` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', '超级管理员', 'admin@sh.net', 'resources/views/template/assets/avatars/user.jpg', '4297f44b13955235245b2497399d7a93', '5', '1', '2017-05-16 02:37:22', '1', '0', '2016-05-25 05:56:33', '2017-05-16 02:37:22');
INSERT INTO `users` VALUES ('2', '名IT经理', 'test@sh.net', 'resources/views/template/assets/avatars/user.jpg', 'e10adc3949ba59abbe56e057f20f883e', '6', '0', '2017-05-10 17:27:26', '1', '0', '2016-11-01 15:07:59', '2017-04-27 02:01:45');
INSERT INTO `users` VALUES ('3', '名it员工', 'test@123.net', 'resources/views/template/assets/avatars/user.jpg', 'e10adc3949ba59abbe56e057f20f883e', '0', '0', '2017-05-10 17:57:38', '1', '1', '2017-05-10 08:56:06', '2017-05-10 09:57:38');

-- ----------------------------
-- Table structure for `users_base`
-- ----------------------------
DROP TABLE IF EXISTS `users_base`;
CREATE TABLE `users_base` (
  `user_id` int(10) unsigned zerofill NOT NULL,
  `department` int(10) unsigned NOT NULL DEFAULT '0',
  `positions` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users_base
-- ----------------------------
INSERT INTO `users_base` VALUES ('0000000001', '1', '1', '2017-05-10 17:13:29', '2017-05-10 17:13:29');
INSERT INTO `users_base` VALUES ('0000000002', '2', '2', '2017-05-10 17:28:01', '2017-05-10 17:28:01');
INSERT INTO `users_base` VALUES ('0000000003', '2', '3', '2017-05-10 17:50:20', '2017-05-10 09:50:20');

-- ----------------------------
-- Table structure for `users_info`
-- ----------------------------
DROP TABLE IF EXISTS `users_info`;
CREATE TABLE `users_info` (
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users_info
-- ----------------------------
INSERT INTO `users_info` VALUES ('1', null, null);
INSERT INTO `users_info` VALUES ('2', null, null);
INSERT INTO `users_info` VALUES ('3', '2017-05-10 17:26:50', '2017-05-10 17:26:50');
