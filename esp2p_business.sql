/*
Navicat MySQL Data Transfer

Source Server         : localhost3310
Source Server Version : 50505
Source Host           : localhost:3310
Source Database       : esp2p_business

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-12-01 16:52:42
*/

SET FOREIGN_KEY_CHECKS=0;

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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of node
-- ----------------------------
INSERT INTO `node` VALUES ('1', '0', '主页', 'admin.main.index', '1', 'fa-home', '1', '1', '2016-08-31 15:02:05', '2016-08-31 15:02:08');
INSERT INTO `node` VALUES ('2', '0', '权限管理', '#', '2', 'fa-desktop', '1', '1', '2016-08-31 15:12:51', '2016-12-01 07:49:22');
INSERT INTO `node` VALUES ('3', '2', '角色列表', 'admin.role.index', '2', 'fa-caret-right', '1', '1', '2016-08-31 15:22:26', '2016-11-25 08:03:06');
INSERT INTO `node` VALUES ('4', '2', '用户列表', 'admin.user.index', '3', 'fa-caret-right', '1', '1', '2016-08-31 15:22:34', '2016-11-25 08:02:58');
INSERT INTO `node` VALUES ('5', '2', '权限列表', 'admin.node.index', '1', 'fa-caret-right', '1', '1', '2016-08-31 15:50:18', '2016-08-31 15:43:45');
INSERT INTO `node` VALUES ('7', '5', '添加权限视图', 'admin.node.addNode', '2', '', '0', '1', '2016-10-28 08:51:08', '2016-10-28 08:51:08');
INSERT INTO `node` VALUES ('8', '5', '添加权限', 'admin.node.createNode', '3', '', '0', '1', '2016-10-28 08:52:00', '2016-10-28 08:52:00');
INSERT INTO `node` VALUES ('9', '5', '编辑权限视图', 'admin.node.editNode', '4', '', '0', '1', '2016-10-31 07:35:30', '2016-10-31 07:35:30');
INSERT INTO `node` VALUES ('10', '5', '编辑权限', 'admin.node.updateNode', '5', '', '0', '1', '2016-10-31 07:35:46', '2016-10-31 07:35:46');
INSERT INTO `node` VALUES ('11', '5', '权限列表', 'admin.node.getNode', '1', '', '0', '1', '2016-11-02 15:18:50', '2016-11-02 15:18:53');
INSERT INTO `node` VALUES ('12', '3', '角色列表', 'admin.role.getRole', '1', '', '0', '1', null, null);
INSERT INTO `node` VALUES ('13', '3', '添加角色列表', 'admin.role.addRole', '2', '', '0', '1', null, null);
INSERT INTO `node` VALUES ('14', '3', '添加角色', 'admin.role.createRole', '3', '', '0', '1', null, null);
INSERT INTO `node` VALUES ('15', '3', '编辑角色视图', 'admin.role.editRole', '4', '', '0', '1', null, null);
INSERT INTO `node` VALUES ('16', '3', '编辑角色', 'admin.role.updateRole', '5', '', '0', '1', null, null);
INSERT INTO `node` VALUES ('17', '3', '角色详情', 'admin.role.roleInfo', '7', '', '0', '1', null, null);
INSERT INTO `node` VALUES ('18', '5', '删除权限', 'admin.node.delNode', '6', '', '0', '1', null, null);
INSERT INTO `node` VALUES ('19', '3', '删除角色', 'admin.role.delRole', '6', '', '0', '1', null, null);

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
INSERT INTO `role_node` VALUES ('2', '1');
INSERT INTO `role_node` VALUES ('2', '22');
INSERT INTO `role_node` VALUES ('2', '2');
INSERT INTO `role_node` VALUES ('2', '5');
INSERT INTO `role_node` VALUES ('2', '11');
INSERT INTO `role_node` VALUES ('2', '7');
INSERT INTO `role_node` VALUES ('2', '8');
INSERT INTO `role_node` VALUES ('2', '9');
INSERT INTO `role_node` VALUES ('2', '10');
INSERT INTO `role_node` VALUES ('2', '18');
INSERT INTO `role_node` VALUES ('2', '3');
INSERT INTO `role_node` VALUES ('2', '12');
INSERT INTO `role_node` VALUES ('2', '13');
INSERT INTO `role_node` VALUES ('2', '14');
INSERT INTO `role_node` VALUES ('2', '15');
INSERT INTO `role_node` VALUES ('2', '16');
INSERT INTO `role_node` VALUES ('2', '19');
INSERT INTO `role_node` VALUES ('2', '17');
INSERT INTO `role_node` VALUES ('2', '4');

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
INSERT INTO `users` VALUES ('1', '超级管理员', 'admin@sh.net', 'resources/views/template/admin/assets/avatars/user.jpg', '4297f44b13955235245b2497399d7a93', '0', '1', '2016-12-01 08:22:29', '2016-05-25 05:56:33', '2016-12-01 08:22:29');
INSERT INTO `users` VALUES ('2', 'test', 'test@sh.net', 'resources/views/template/admin/assets/avatars/user.jpg', '4297f44b13955235245b2497399d7a93', '1', '0', '2016-12-01 15:40:32', '2016-11-01 15:07:59', '2016-11-03 09:32:40');
