/*
Navicat MySQL Data Transfer

Source Server         : localhost3310
Source Server Version : 50505
Source Host           : localhost:3310
Source Database       : eas

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-06-12 17:54:14
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
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `recycle` tinyint(1) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`dep_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of department
-- ----------------------------
INSERT INTO `department` VALUES ('1', '总经办', '1', '0', '1', '1', '0', '2017-06-02 07:01:15', null);
INSERT INTO `department` VALUES ('2', 'IT部', '3', '1', '2', '1', '0', '2017-06-07 03:42:57', null);
INSERT INTO `department` VALUES ('3', '销售部', '4', '1', '2', '1', '0', '2017-06-07 03:45:21', null);

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
  `is_recycle` tinyint(1) unsigned DEFAULT '0',
  `recycle_name` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recycle_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of node
-- ----------------------------
INSERT INTO `node` VALUES ('1', '0', '主页', 'main.index', '1', 'fa fa-home', '1', '1', '0', null, null, '2016-08-31 15:02:05', '2017-05-24 07:26:06');
INSERT INTO `node` VALUES ('2', '0', '系统管理', '#', '11', 'fa fa-cogs', '1', '1', '0', null, null, '2016-08-31 15:12:51', '2017-05-24 07:16:33');
INSERT INTO `node` VALUES ('3', '2', '角色列表', 'role.index', '2', 'fa fa-caret-right', '1', '1', '0', null, null, '2016-08-31 15:22:26', '2016-11-25 08:03:06');
INSERT INTO `node` VALUES ('4', '32', '员工列表', 'user.index', '5', 'fa fa-caret-right', '1', '1', '1', '员工类', 'user', '2016-08-31 15:22:34', '2017-06-01 07:06:29');
INSERT INTO `node` VALUES ('5', '2', '权限列表', 'node.index', '1', 'fa fa-caret-right', '1', '1', '0', null, null, '2016-08-31 15:50:18', '2016-08-31 15:43:45');
INSERT INTO `node` VALUES ('7', '5', '添加权限视图', 'node.addNode', '2', '', '0', '1', '0', null, null, '2016-10-28 08:51:08', '2016-10-28 08:51:08');
INSERT INTO `node` VALUES ('8', '5', '添加权限', 'node.createNode', '3', '', '0', '1', '0', null, null, '2016-10-28 08:52:00', '2016-10-28 08:52:00');
INSERT INTO `node` VALUES ('9', '5', '编辑权限视图', 'node.editNode', '4', '', '0', '1', '0', null, null, '2016-10-31 07:35:30', '2016-10-31 07:35:30');
INSERT INTO `node` VALUES ('10', '5', '编辑权限', 'node.updateNode', '5', '', '0', '1', '0', null, null, '2016-10-31 07:35:46', '2016-10-31 07:35:46');
INSERT INTO `node` VALUES ('11', '5', '权限列表', 'node.getNode', '1', '', '0', '1', '0', null, null, '2016-11-02 15:18:50', '2016-11-02 15:18:53');
INSERT INTO `node` VALUES ('12', '3', '角色列表', 'role.getRole', '1', '', '0', '1', '0', null, null, null, null);
INSERT INTO `node` VALUES ('13', '3', '添加角色列表', 'role.addRole', '2', '', '0', '1', '0', null, null, null, null);
INSERT INTO `node` VALUES ('14', '3', '添加角色', 'role.createRole', '3', '', '0', '1', '0', null, null, null, null);
INSERT INTO `node` VALUES ('15', '3', '编辑角色视图', 'role.editRole', '4', '', '0', '1', '0', null, null, null, null);
INSERT INTO `node` VALUES ('16', '3', '编辑角色', 'role.updateRole', '5', '', '0', '1', '0', null, null, null, null);
INSERT INTO `node` VALUES ('17', '3', '角色详情', 'role.roleInfo', '7', '', '0', '1', '0', null, null, null, null);
INSERT INTO `node` VALUES ('18', '5', '删除权限', 'node.delNode', '6', '', '0', '1', '0', null, null, null, null);
INSERT INTO `node` VALUES ('19', '3', '删除角色', 'role.delRole', '6', '', '0', '1', '0', null, null, null, null);
INSERT INTO `node` VALUES ('23', '4', '员工列表', 'user.getUser', '1', '', '0', '1', '0', null, null, '2016-12-01 09:42:22', '2016-12-01 09:42:22');
INSERT INTO `node` VALUES ('24', '4', '添加员工视图', 'user.addUser', '2', '', '0', '1', '0', null, null, '2016-12-01 09:42:39', '2016-12-01 09:44:09');
INSERT INTO `node` VALUES ('25', '4', '添加角色', 'user.createUser', '3', '', '0', '1', '0', null, null, '2016-12-06 05:57:30', '2016-12-06 05:59:56');
INSERT INTO `node` VALUES ('26', '4', '编辑角色视图', 'user.editUser', '4', '', '0', '1', '0', null, null, '2016-12-06 05:57:53', '2016-12-06 05:59:40');
INSERT INTO `node` VALUES ('27', '4', '编辑员工', 'user.updateUser', '5', '', '0', '1', '0', null, null, '2016-12-06 05:58:31', '2016-12-06 05:58:31');
INSERT INTO `node` VALUES ('28', '4', '删除员工', 'user.delUser', '6', '', '0', '1', '0', null, null, '2016-12-06 05:58:58', '2016-12-06 05:58:58');
INSERT INTO `node` VALUES ('29', '4', '员工详情', 'user.userInfo', '6', '', '0', '1', '0', null, null, '2016-12-06 05:59:21', '2016-12-06 05:59:21');
INSERT INTO `node` VALUES ('30', '4', '修改密码', 'user.editPwd', '7', '', '0', '1', '0', null, null, '2016-12-06 06:00:22', '2016-12-06 06:00:22');
INSERT INTO `node` VALUES ('31', '4', '重置密码', 'user.resetPwd', '8', '', '0', '1', '0', null, null, '2016-12-06 06:00:43', '2016-12-06 06:00:55');
INSERT INTO `node` VALUES ('32', '0', '公司信息', '#', '4', 'fa fa-cog', '1', '1', '0', null, null, '2017-02-20 08:11:24', '2017-05-24 07:33:29');
INSERT INTO `node` VALUES ('33', '32', '公司信息', 'company.index', '1', 'fa fa-caret-right', '1', '1', '0', null, null, '2017-02-20 08:45:03', '2017-04-27 02:07:48');
INSERT INTO `node` VALUES ('34', '32', '部门列表', 'department.index', '3', 'fa fa-caret-right', '1', '1', '1', '部门类', 'department', '2017-04-27 03:17:44', '2017-06-01 06:58:53');
INSERT INTO `node` VALUES ('35', '32', '岗位列表', 'positions.index', '4', 'fa fa-caret-right', '1', '1', '1', '岗位类', 'positions', '2017-05-10 06:39:18', '2017-06-01 06:59:11');
INSERT INTO `node` VALUES ('37', '0', '合同管理', '#', '7', 'fa fa-briefcase', '1', '1', '0', null, null, '2017-05-12 03:08:13', '2017-05-24 07:34:17');
INSERT INTO `node` VALUES ('38', '0', '预算管理', '#', '5', 'fa  fa-bar-chart-o', '1', '1', '0', null, null, '2017-05-12 03:09:42', '2017-05-24 07:33:37');
INSERT INTO `node` VALUES ('39', '0', '我的工作', '#', '2', 'fa fa-briefcase', '1', '1', '0', null, null, '2017-05-12 03:18:54', '2017-05-31 02:39:08');
INSERT INTO `node` VALUES ('40', '0', '流程控制', '#', '3', 'glyphicon glyphicon-refresh', '1', '1', '0', null, null, '2017-05-12 03:39:57', '2017-05-24 07:33:32');
INSERT INTO `node` VALUES ('41', '0', '库存管理', '#', '8', 'fa fa-hdd-o', '1', '1', '0', null, null, '2017-05-12 03:55:23', '2017-05-24 07:32:10');
INSERT INTO `node` VALUES ('42', '0', '报表管理', '#', '10', 'glyphicon glyphicon-indent-left', '1', '1', '0', null, null, '2017-05-12 03:57:44', '2017-05-31 02:43:23');
INSERT INTO `node` VALUES ('46', '32', '科目管理', 'subjects.index', '2', 'fa fa-caret-right', '1', '1', '0', null, null, '2017-05-12 08:55:00', '2017-05-12 08:55:00');
INSERT INTO `node` VALUES ('47', '0', '费用管理', '#', '9', 'glyphicon glyphicon-list-alt', '1', '1', '0', null, null, '2017-05-16 02:36:36', '2017-05-24 07:34:45');
INSERT INTO `node` VALUES ('49', '0', '客户管理', '#', '6', 'fa fa-users', '1', '1', '0', null, null, '2017-05-16 02:38:43', '2017-05-24 07:34:32');
INSERT INTO `node` VALUES ('50', '39', '回收站', 'recycle.index', '1', 'fa fa-caret-right', '1', '1', '0', null, null, '2017-05-31 02:46:48', '2017-05-31 02:50:39');
INSERT INTO `node` VALUES ('51', '2', '下拉菜单', '#', '3', 'fa fa-caret-right', '1', '1', '0', '', '', '2017-06-05 04:16:26', '2017-06-05 04:16:26');
INSERT INTO `node` VALUES ('52', '40', '审核流程', 'processAudit.index', '1', 'fa fa-caret-right', '1', '1', '0', '', '', '2017-06-07 03:53:22', '2017-06-07 03:53:22');

-- ----------------------------
-- Table structure for `positions`
-- ----------------------------
DROP TABLE IF EXISTS `positions`;
CREATE TABLE `positions` (
  `pos_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pos_name` varchar(50) NOT NULL,
  `pos_pid` int(10) unsigned DEFAULT '0',
  `sort` tinyint(4) NOT NULL,
  `status` tinyint(1) unsigned DEFAULT '1',
  `recycle` tinyint(1) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`pos_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of positions
-- ----------------------------
INSERT INTO `positions` VALUES ('1', '总经理', '0', '1', '1', '0', '2017-06-02 08:21:40', '2017-05-10 07:26:20');
INSERT INTO `positions` VALUES ('2', 'IT部经理', '1', '1', '1', '0', '2017-06-02 08:15:37', '2017-05-10 07:26:34');
INSERT INTO `positions` VALUES ('3', '销售经理', '1', '2', '1', '0', '2017-06-07 03:43:32', '2017-05-10 07:26:42');
INSERT INTO `positions` VALUES ('4', 'php工程师', '2', '1', '1', '0', '2017-06-07 03:44:01', '2017-06-07 03:44:01');
INSERT INTO `positions` VALUES ('5', '硬件维护工程师', '2', '2', '1', '0', '2017-06-07 03:44:23', '2017-06-07 03:44:23');
INSERT INTO `positions` VALUES ('6', '销售员', '3', '1', '1', '0', '2017-06-07 03:44:35', '2017-06-07 03:44:35');

-- ----------------------------
-- Table structure for `process_audit`
-- ----------------------------
DROP TABLE IF EXISTS `process_audit`;
CREATE TABLE `process_audit` (
  `audit_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `audit_dep` int(10) unsigned NOT NULL,
  `audit_type` varchar(255) NOT NULL DEFAULT '0',
  `audit_name` varchar(255) NOT NULL,
  `audit_process` text,
  `status` tinyint(1) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`audit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of process_audit
-- ----------------------------
INSERT INTO `process_audit` VALUES ('1', '0', 'yusuan', '预算流程', '3,2', '1', '2017-06-12 03:25:15', '2017-06-12 03:25:15');
INSERT INTO `process_audit` VALUES ('3', '1', 'yusuan', 'test', '2', '1', '2017-06-12 03:50:17', '2017-06-12 03:50:17');
INSERT INTO `process_audit` VALUES ('4', '0', 'baoxiao', '预算流程', '3,2', '1', '2017-06-12 09:07:45', '2017-06-12 09:28:04');

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
  `sub_type` tinyint(1) DEFAULT '0',
  `sub_ip` varchar(255) NOT NULL,
  `sub_name` varchar(255) NOT NULL,
  `sub_pid` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `sort` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `sub_budget` tinyint(1) unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sub_id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of subjects
-- ----------------------------
INSERT INTO `subjects` VALUES ('1', '0', '1000', '资产', '0', '1', '0', '0', '2017-06-06 11:02:22', '2017-06-06 11:02:22');
INSERT INTO `subjects` VALUES ('2', '0', '2000', '负债', '0', '1', '0', '0', '2017-06-06 11:02:26', '2017-06-06 11:02:26');
INSERT INTO `subjects` VALUES ('3', '0', '4000', '权益类', '0', '1', '0', '0', '2017-06-06 16:27:03', '2017-06-06 16:27:03');
INSERT INTO `subjects` VALUES ('4', '0', '6000', '损益', '0', '1', '0', '0', '2017-06-06 11:02:24', '2017-06-06 11:02:24');
INSERT INTO `subjects` VALUES ('5', '1', '1000.1001', '现金', '1', '1', '0', '0', '2017-06-06 03:04:19', '2017-06-06 03:04:19');
INSERT INTO `subjects` VALUES ('6', '1', '1000.1002', '银行', '1', '1', '0', '0', '2017-06-06 03:04:33', '2017-06-06 03:04:33');
INSERT INTO `subjects` VALUES ('7', '1', '1000.1122', '应收账款', '1', '1', '0', '0', '2017-06-06 03:04:49', '2017-06-06 03:04:49');
INSERT INTO `subjects` VALUES ('8', '1', '1000.1221', '其他应收款', '1', '1', '0', '0', '2017-06-06 03:05:04', '2017-06-06 03:05:04');
INSERT INTO `subjects` VALUES ('9', '1', '1000.1405', '仓库', '1', '1', '0', '0', '2017-06-06 03:05:17', '2017-06-06 03:05:17');
INSERT INTO `subjects` VALUES ('10', '1', '1000.1122.01', '合同应收', '7', '1', '0', '0', '2017-06-06 03:06:52', '2017-06-06 03:06:52');
INSERT INTO `subjects` VALUES ('11', '1', '1000.1122.02', '预付账款', '7', '1', '0', '0', '2017-06-06 03:07:07', '2017-06-06 03:07:07');
INSERT INTO `subjects` VALUES ('12', '1', '1000.1122.99', '应收其他', '7', '1', '0', '0', '2017-06-06 03:07:21', '2017-06-06 03:07:21');
INSERT INTO `subjects` VALUES ('13', '1', '1000.1122.03', '合同开票', '7', '1', '0', '0', '2017-06-06 03:07:38', '2017-06-06 03:07:38');
INSERT INTO `subjects` VALUES ('14', '1', '1000.1122.04', '开票应收', '7', '1', '0', '0', '2017-06-06 03:07:50', '2017-06-06 03:07:50');
INSERT INTO `subjects` VALUES ('15', '1', '1000.1221.01', '内部往来', '8', '1', '0', '0', '2017-06-06 03:08:29', '2017-06-06 03:08:29');
INSERT INTO `subjects` VALUES ('16', '1', '1000.1221.02', '押金', '8', '1', '0', '0', '2017-06-06 03:08:40', '2017-06-06 03:08:40');
INSERT INTO `subjects` VALUES ('17', '1', '1000.1221.03', '客户往来', '8', '1', '0', '0', '2017-06-06 03:08:52', '2017-06-06 03:08:52');
INSERT INTO `subjects` VALUES ('18', '1', '1000.1221.99', '其他应收', '8', '1', '0', '0', '2017-06-06 03:09:02', '2017-06-06 03:09:02');
INSERT INTO `subjects` VALUES ('19', '1', '1000.1221.01.01', '备用金', '15', '1', '0', '0', '2017-06-06 03:14:49', '2017-06-06 03:14:49');
INSERT INTO `subjects` VALUES ('20', '1', '1000.1221.01.01.01', '个人', '19', '1', '0', '0', '2017-06-06 03:15:42', '2017-06-06 03:15:42');
INSERT INTO `subjects` VALUES ('21', '1', '1000.1221.01.01.02', '分所', '19', '1', '0', '0', '2017-06-06 03:15:57', '2017-06-06 03:15:57');
INSERT INTO `subjects` VALUES ('22', '1', '1000.1221.02.01', '公司租房', '16', '1', '0', '0', '2017-06-06 03:16:21', '2017-06-06 03:16:21');
INSERT INTO `subjects` VALUES ('23', '1', '1000.1221.02.02', '宿舍租房', '16', '1', '0', '0', '2017-06-06 03:16:40', '2017-06-06 03:16:40');
INSERT INTO `subjects` VALUES ('24', '-1', '2000.2202', '应付账款', '2', '1', '0', '0', '2017-06-06 03:17:49', '2017-06-06 03:17:49');
INSERT INTO `subjects` VALUES ('25', '-1', '2000.2241', '其他应付款', '2', '1', '0', '0', '2017-06-06 03:18:01', '2017-06-06 03:18:01');
INSERT INTO `subjects` VALUES ('26', '-1', '2000.2202.01', '合同应付', '24', '1', '0', '0', '2017-06-06 03:18:19', '2017-06-06 03:18:19');
INSERT INTO `subjects` VALUES ('27', '-1', '2000.2202.02', '预收账款', '24', '1', '0', '0', '2017-06-06 03:18:31', '2017-06-06 03:18:31');
INSERT INTO `subjects` VALUES ('28', '-1', '2000.2202.03', '应付佣金', '24', '1', '0', '0', '2017-06-06 03:18:41', '2017-06-06 03:18:41');
INSERT INTO `subjects` VALUES ('29', '-1', '2000.2202.04', '合同收票', '24', '1', '0', '0', '2017-06-06 03:18:51', '2017-06-06 03:18:51');
INSERT INTO `subjects` VALUES ('30', '-1', '2000.2202.05', '收票应付', '24', '1', '0', '0', '2017-06-06 03:19:02', '2017-06-06 03:19:02');
INSERT INTO `subjects` VALUES ('31', '-1', '2000.2202.99', '应付其他', '24', '1', '0', '0', '2017-06-06 03:19:14', '2017-06-06 03:19:14');
INSERT INTO `subjects` VALUES ('32', '-1', '2000.2241.01', '内部往来', '25', '1', '0', '0', '2017-06-06 03:21:55', '2017-06-06 03:21:55');
INSERT INTO `subjects` VALUES ('33', '-1', '2000.2241.02', '客户往来', '25', '1', '0', '0', '2017-06-06 03:22:06', '2017-06-06 03:22:06');
INSERT INTO `subjects` VALUES ('34', '-1', '4000.4103', '收支结余', '3', '1', '0', '0', '2017-06-06 03:22:27', '2017-06-06 03:22:27');
INSERT INTO `subjects` VALUES ('35', '-1', '6000.6001', '收入', '4', '1', '0', '0', '2017-06-06 03:23:13', '2017-06-06 03:23:13');
INSERT INTO `subjects` VALUES ('36', '-1', '6000.6001.01', '会计代理收入', '35', '1', '0', '0', '2017-06-06 03:23:37', '2017-06-06 03:23:37');
INSERT INTO `subjects` VALUES ('37', '-1', '6000.6001.02', '税务代理收入', '35', '1', '0', '0', '2017-06-06 03:23:47', '2017-06-06 03:23:47');
INSERT INTO `subjects` VALUES ('38', '-1', '6000.6001.03', '年检代理收入', '35', '1', '0', '0', '2017-06-06 03:24:00', '2017-06-06 03:24:00');
INSERT INTO `subjects` VALUES ('39', '-1', '6000.6001.04', '工商代理收入', '35', '1', '0', '0', '2017-06-06 03:24:14', '2017-06-06 03:24:14');
INSERT INTO `subjects` VALUES ('40', '-1', '6000.6001.05', '人事代理收入', '35', '1', '0', '0', '2017-06-06 03:24:21', '2017-06-06 03:24:21');
INSERT INTO `subjects` VALUES ('41', '-1', '6000.6001.06', '顾问咨询收入', '35', '1', '0', '0', '2017-06-06 03:24:31', '2017-06-06 03:24:31');
INSERT INTO `subjects` VALUES ('42', '-1', '6000.6001.07', '网络查询收入', '35', '1', '0', '0', '2017-06-06 03:24:41', '2017-06-06 03:24:41');
INSERT INTO `subjects` VALUES ('43', '-1', '6000.6001.08', '劳务输出收入', '35', '1', '0', '0', '2017-06-06 03:25:01', '2017-06-06 03:25:01');
INSERT INTO `subjects` VALUES ('44', '-1', '6000.6001.09', '票据代理收入', '35', '1', '0', '0', '2017-06-06 03:25:14', '2017-06-06 03:25:14');
INSERT INTO `subjects` VALUES ('45', '-1', '6000.6001.10', '佣金收入', '35', '1', '0', '0', '2017-06-06 03:25:25', '2017-06-06 03:25:25');
INSERT INTO `subjects` VALUES ('46', '-1', '6000.6001.11', '开票代理', '35', '1', '0', '0', '2017-06-06 03:25:40', '2017-06-06 03:25:40');
INSERT INTO `subjects` VALUES ('47', '-1', '6000.6001.12', '会计电算化收入', '35', '1', '0', '0', '2017-06-06 03:25:50', '2017-06-06 03:25:50');
INSERT INTO `subjects` VALUES ('48', '-1', '6000.6001.13', '内审收入', '35', '1', '0', '0', '2017-06-06 03:26:00', '2017-06-06 03:26:00');
INSERT INTO `subjects` VALUES ('49', '-1', '6000.6001.14', '企业公示收入', '35', '1', '0', '0', '2017-06-06 03:26:10', '2017-06-06 03:26:10');
INSERT INTO `subjects` VALUES ('50', '-1', '6000.6001.15', '会计档案收入', '35', '1', '0', '0', '2017-06-06 03:26:22', '2017-06-06 03:26:22');
INSERT INTO `subjects` VALUES ('51', '-1', '6000.6001.99', '其他收入', '35', '1', '0', '0', '2017-06-06 03:26:37', '2017-06-06 03:26:37');
INSERT INTO `subjects` VALUES ('52', '-1', '6000.6200', '支出', '4', '1', '0', '0', '2017-06-06 03:26:51', '2017-06-06 03:26:51');
INSERT INTO `subjects` VALUES ('53', '-1', '6000.6200.01', '基础费用', '52', '1', '0', '0', '2017-06-06 03:27:15', '2017-06-06 03:27:15');
INSERT INTO `subjects` VALUES ('54', '-1', '6000.6200.01.01', '房租费', '53', '1', '0', '0', '2017-06-06 03:27:33', '2017-06-06 03:27:33');
INSERT INTO `subjects` VALUES ('55', '-1', '6000.6200.01.01.01', '办公室2104', '54', '1', '0', '0', '2017-06-06 03:27:46', '2017-06-06 03:27:46');
INSERT INTO `subjects` VALUES ('56', '-1', '6000.6200.01.01.02', '办公室2105', '54', '1', '0', '0', '2017-06-06 03:28:02', '2017-06-06 03:28:02');
INSERT INTO `subjects` VALUES ('57', '-1', '6000.6200.01.01.03', '宿舍', '54', '1', '0', '0', '2017-06-06 03:28:41', '2017-06-06 03:28:41');

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
  `recycle` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `users1_email_unique` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', '超级管理员', 'admin@sh.net', 'resources/views/template/assets/avatars/user.jpg', '4297f44b13955235245b2497399d7a93', '5', '1', '2017-06-12 03:11:43', '1', '0', '2016-05-25 05:56:33', '2017-06-12 03:11:43');
INSERT INTO `users` VALUES ('2', '总经理user', 'test@sh.net', 'resources/views/template/assets/avatars/user.jpg', 'e10adc3949ba59abbe56e057f20f883e', '6', '0', '2017-06-07 11:46:34', '1', '0', '2016-11-01 15:07:59', '2017-06-07 03:41:18');
INSERT INTO `users` VALUES ('3', 'IT经理user', 'test@123.net', 'resources/views/template/assets/avatars/user.jpg', 'e10adc3949ba59abbe56e057f20f883e', '0', '0', '2017-06-07 11:46:37', '1', '0', '2017-05-10 08:56:06', '2017-06-07 03:41:41');
INSERT INTO `users` VALUES ('4', '销售经理user', 'test@1.net', 'resources/views/template/assets/avatars/user.jpg', 'e10adc3949ba59abbe56e057f20f883e', '0', '0', '2017-06-07 11:46:39', '1', '0', '2017-06-07 03:38:00', '2017-06-07 03:45:12');
INSERT INTO `users` VALUES ('5', 'Php工程师user', 'test@2.net', 'resources/views/template/assets/avatars/user.jpg', 'e10adc3949ba59abbe56e057f20f883e', '0', '0', '2017-06-07 11:46:42', '1', '0', '2017-06-07 03:46:27', '2017-06-07 03:46:27');
INSERT INTO `users` VALUES ('6', '硬件维护工程师user', 'test@3.net', 'resources/views/template/assets/avatars/user.jpg', 'e10adc3949ba59abbe56e057f20f883e', '0', '0', null, '1', '0', '2017-06-07 03:48:10', '2017-06-07 03:48:10');
INSERT INTO `users` VALUES ('7', '销售员1user', 'test@4.net', 'resources/views/template/assets/avatars/user.jpg', 'e10adc3949ba59abbe56e057f20f883e', '0', '0', '2017-06-07 11:48:57', '1', '0', '2017-06-07 03:48:51', '2017-06-07 03:48:51');

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
INSERT INTO `users_base` VALUES ('0000000002', '1', '1', '2017-06-07 11:41:18', '2017-06-07 03:41:18');
INSERT INTO `users_base` VALUES ('0000000003', '2', '2', '2017-06-07 11:41:41', '2017-06-07 03:41:41');
INSERT INTO `users_base` VALUES ('0000000004', '3', '3', '2017-06-07 11:45:12', '2017-06-07 03:45:12');
INSERT INTO `users_base` VALUES ('0000000005', '2', '4', '2017-06-07 03:46:27', '2017-06-07 03:46:27');
INSERT INTO `users_base` VALUES ('0000000006', '2', '5', '2017-06-07 03:48:10', '2017-06-07 03:48:10');
INSERT INTO `users_base` VALUES ('0000000007', '3', '6', '2017-06-07 03:48:51', '2017-06-07 03:48:51');

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
INSERT INTO `users_info` VALUES ('4', '2017-06-07 03:38:00', '2017-06-07 03:38:00');
INSERT INTO `users_info` VALUES ('5', '2017-06-07 03:46:27', '2017-06-07 03:46:27');
INSERT INTO `users_info` VALUES ('6', '2017-06-07 03:48:10', '2017-06-07 03:48:10');
INSERT INTO `users_info` VALUES ('7', '2017-06-07 03:48:51', '2017-06-07 03:48:51');
