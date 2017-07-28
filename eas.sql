/*
Navicat MySQL Data Transfer

Source Server         : localhost3310
Source Server Version : 50505
Source Host           : localhost:3310
Source Database       : eas

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-07-28 11:52:48
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `audit_info`
-- ----------------------------
DROP TABLE IF EXISTS `audit_info`;
CREATE TABLE `audit_info` (
  `process_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `process_type` varchar(20) NOT NULL,
  `process_app` int(10) unsigned NOT NULL,
  `process_title` varchar(255) DEFAULT '0',
  `process_text` text,
  `process_users` text NOT NULL,
  `process_user_res` text,
  `process_audit_user` int(10) unsigned NOT NULL,
  `created_user` int(10) unsigned NOT NULL,
  `status` char(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`process_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of audit_info
-- ----------------------------
INSERT INTO `audit_info` VALUES ('2', 'budgetSum', '11', '新增预算—汇总', 'restes', '1', '', '1', '1', '1000', '2017-07-21 14:34:31', '2017-07-24 09:44:03');
INSERT INTO `audit_info` VALUES ('3', 'budget', '12', '更新预算—普通', '更新预算', '1', '', '1', '1', '1000', '2017-07-21 14:50:37', '2017-07-21 16:15:57');

-- ----------------------------
-- Table structure for `audit_info_text`
-- ----------------------------
DROP TABLE IF EXISTS `audit_info_text`;
CREATE TABLE `audit_info_text` (
  `audit_text_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `process_id` int(10) NOT NULL,
  `created_user` int(10) unsigned NOT NULL,
  `audit_text` text NOT NULL,
  `audit_sort` tinyint(4) unsigned NOT NULL,
  `audit_res` char(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`audit_text_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of audit_info_text
-- ----------------------------
INSERT INTO `audit_info_text` VALUES ('3', '2', '1', '', '1', '1002', '2017-07-21 14:48:42', '2017-07-21 14:48:42');
INSERT INTO `audit_info_text` VALUES ('4', '3', '1', '2131231', '1', '1002', '2017-07-21 15:39:26', '2017-07-21 15:39:26');
INSERT INTO `audit_info_text` VALUES ('5', '3', '1', '', '2', '1002', '2017-07-21 15:49:14', '2017-07-21 15:49:14');
INSERT INTO `audit_info_text` VALUES ('6', '3', '1', '', '3', '1002', '2017-07-21 15:53:34', '2017-07-21 15:53:34');
INSERT INTO `audit_info_text` VALUES ('7', '3', '1', '', '4', '1002', '2017-07-21 15:56:22', '2017-07-21 15:56:22');
INSERT INTO `audit_info_text` VALUES ('8', '3', '1', '', '5', '1002', '2017-07-21 15:57:26', '2017-07-21 15:57:26');
INSERT INTO `audit_info_text` VALUES ('9', '3', '1', '', '6', '1002', '2017-07-21 15:58:41', '2017-07-21 15:58:41');
INSERT INTO `audit_info_text` VALUES ('10', '3', '1', '', '7', '1002', '2017-07-21 16:02:39', '2017-07-21 16:02:39');
INSERT INTO `audit_info_text` VALUES ('11', '3', '1', '', '8', '1002', '2017-07-21 16:08:17', '2017-07-21 16:08:17');
INSERT INTO `audit_info_text` VALUES ('12', '3', '1', '', '9', '1002', '2017-07-21 16:09:29', '2017-07-21 16:09:29');
INSERT INTO `audit_info_text` VALUES ('13', '3', '1', '', '10', '1003', '2017-07-21 16:11:40', '2017-07-21 16:11:40');
INSERT INTO `audit_info_text` VALUES ('14', '3', '1', '', '11', '1003', '2017-07-21 16:15:57', '2017-07-21 16:15:57');
INSERT INTO `audit_info_text` VALUES ('15', '4', '1', '123', '1', '1002', '2017-07-21 17:28:54', '2017-07-21 17:28:54');
INSERT INTO `audit_info_text` VALUES ('16', '2', '1', '', '2', '1002', '2017-07-24 09:44:03', '2017-07-24 09:44:03');

-- ----------------------------
-- Table structure for `audit_process`
-- ----------------------------
DROP TABLE IF EXISTS `audit_process`;
CREATE TABLE `audit_process` (
  `audit_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `audit_dep` int(10) unsigned NOT NULL,
  `audit_type` varchar(255) NOT NULL DEFAULT '0',
  `audit_name` varchar(255) NOT NULL,
  `audit_process` text,
  `status` char(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`audit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of audit_process
-- ----------------------------
INSERT INTO `audit_process` VALUES ('1', '0', 'budget', '预算流程', '2,3', '1', '2017-07-20 11:43:49', '2017-07-20 11:43:49');
INSERT INTO `audit_process` VALUES ('2', '0', 'reimburse', '费用报销审核流程', '3,7,2', '1', '2017-07-24 17:21:08', '2017-07-24 17:21:08');

-- ----------------------------
-- Table structure for `budget`
-- ----------------------------
DROP TABLE IF EXISTS `budget`;
CREATE TABLE `budget` (
  `budget_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `budget_ids` text NOT NULL,
  `budget_period` varchar(10) NOT NULL,
  `budget_sum` tinyint(1) unsigned NOT NULL,
  `create_user` int(10) unsigned NOT NULL,
  `budget_num` varchar(255) NOT NULL,
  `budget_name` varchar(255) NOT NULL,
  `budget_start` varchar(10) NOT NULL,
  `budget_end` varchar(10) NOT NULL,
  `status` char(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`budget_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of budget
-- ----------------------------
INSERT INTO `budget` VALUES ('8', '', 'month', '0', '1', 'testSubject1', '测试预算1', '2017-01', '2017-07', '1', '2017-07-20 16:34:05', '2017-07-20 16:34:05');
INSERT INTO `budget` VALUES ('9', '', 'day', '0', '1', 'testSub2', '测试预算2天数', '2017-02-01', '2017-02-28', '1', '2017-07-20 17:07:37', '2017-07-20 17:07:37');
INSERT INTO `budget` VALUES ('11', '8,9,12', 'month', '1', '1', '31232131', '汇总', '2017-01', '2017-07', '1', '2017-07-21 17:47:42', '2017-07-21 17:47:42');
INSERT INTO `budget` VALUES ('12', '', 'year', '0', '1', 'wqeqwewq', '测试预算年', '2016', '2018', '1', '2017-07-21 17:28:54', '2017-07-21 17:28:54');

-- ----------------------------
-- Table structure for `budget_subject`
-- ----------------------------
DROP TABLE IF EXISTS `budget_subject`;
CREATE TABLE `budget_subject` (
  `budget_id` int(10) unsigned NOT NULL,
  `subject_id` int(10) unsigned NOT NULL,
  `sum_amount` decimal(10,2) NOT NULL,
  `status` char(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of budget_subject
-- ----------------------------
INSERT INTO `budget_subject` VALUES ('8', '36', '700.00', '1', '2017-07-21 15:04:41', '2017-07-21 15:04:41');
INSERT INTO `budget_subject` VALUES ('9', '38', '2800.00', '1', '2017-07-21 15:04:39', '2017-07-21 15:04:39');
INSERT INTO `budget_subject` VALUES ('12', '40', '600.00', '1', '2017-07-21 17:28:54', '2017-07-21 17:28:54');
INSERT INTO `budget_subject` VALUES ('12', '41', '300.00', '1', '2017-07-21 17:28:54', '2017-07-21 17:28:54');

-- ----------------------------
-- Table structure for `budget_subject_date`
-- ----------------------------
DROP TABLE IF EXISTS `budget_subject_date`;
CREATE TABLE `budget_subject_date` (
  `budget_id` int(10) unsigned NOT NULL,
  `subject_id` int(10) unsigned NOT NULL,
  `budget_date` varchar(10) DEFAULT NULL,
  `budget_date_str` int(10) unsigned NOT NULL,
  `budget_amount` decimal(10,2) unsigned NOT NULL,
  `status` char(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of budget_subject_date
-- ----------------------------
INSERT INTO `budget_subject_date` VALUES ('8', '36', '2017-01', '1483200000', '100.00', '1', '2017-07-20 16:34:00', '2017-07-20 16:34:00');
INSERT INTO `budget_subject_date` VALUES ('8', '36', '2017-02', '1485878400', '100.00', '1', '2017-07-20 16:33:59', '2017-07-20 16:33:59');
INSERT INTO `budget_subject_date` VALUES ('8', '36', '2017-03', '1488297600', '100.00', '1', '2017-07-20 16:33:59', '2017-07-20 16:33:59');
INSERT INTO `budget_subject_date` VALUES ('8', '36', '2017-04', '1490976000', '100.00', '1', '2017-07-20 16:33:58', '2017-07-20 16:33:58');
INSERT INTO `budget_subject_date` VALUES ('8', '36', '2017-05', '1493568000', '100.00', '1', '2017-07-20 16:33:58', '2017-07-20 16:33:58');
INSERT INTO `budget_subject_date` VALUES ('8', '36', '2017-06', '1496246400', '100.00', '1', '2017-07-20 16:33:58', '2017-07-20 16:33:58');
INSERT INTO `budget_subject_date` VALUES ('8', '36', '2017-07', '1498838400', '100.00', '1', '2017-07-20 16:33:58', '2017-07-20 16:33:58');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-01', '1485878400', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-02', '1485964800', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-03', '1486051200', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-04', '1486137600', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-05', '1486224000', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-06', '1486310400', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-07', '1486396800', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-08', '1486483200', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-09', '1486569600', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-10', '1486656000', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-11', '1486742400', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-12', '1486828800', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-13', '1486915200', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-14', '1487001600', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-15', '1487088000', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-16', '1487174400', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-17', '1487260800', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-18', '1487347200', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-19', '1487433600', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-20', '1487520000', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-21', '1487606400', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-22', '1487692800', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-23', '1487779200', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-24', '1487865600', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-25', '1487952000', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-26', '1488038400', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-27', '1488124800', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('9', '38', '2017-02-28', '1488211200', '100.00', '1', '2017-07-20 17:09:11', '2017-07-20 17:09:11');
INSERT INTO `budget_subject_date` VALUES ('12', '41', '2016', '1451577600', '100.00', '1', '2017-07-21 17:28:54', '2017-07-21 17:28:54');
INSERT INTO `budget_subject_date` VALUES ('12', '41', '2017', '1483200000', '100.00', '1', '2017-07-21 17:28:54', '2017-07-21 17:28:54');
INSERT INTO `budget_subject_date` VALUES ('12', '41', '2018', '1514736000', '100.00', '1', '2017-07-21 17:28:54', '2017-07-21 17:28:54');
INSERT INTO `budget_subject_date` VALUES ('12', '40', '2016', '1451577600', '200.00', '1', '2017-07-21 17:28:54', '2017-07-21 17:28:54');
INSERT INTO `budget_subject_date` VALUES ('12', '40', '2017', '1483200000', '200.00', '1', '2017-07-21 17:28:54', '2017-07-21 17:28:54');
INSERT INTO `budget_subject_date` VALUES ('12', '40', '2018', '1514736000', '200.00', '1', '2017-07-21 17:28:54', '2017-07-21 17:28:54');

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
  `status` char(4) NOT NULL DEFAULT '1',
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
-- Table structure for `expense`
-- ----------------------------
DROP TABLE IF EXISTS `expense`;
CREATE TABLE `expense` (
  `expense_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `expense_type` varchar(20) NOT NULL,
  `expense_dep` int(10) unsigned NOT NULL,
  `expense_user` int(10) unsigned NOT NULL,
  `expense_num` varchar(30) NOT NULL,
  `expense_title` varchar(50) NOT NULL,
  `expense_date` varchar(10) NOT NULL DEFAULT '',
  `expense_doc_num` int(10) unsigned NOT NULL,
  `expense_amount` decimal(10,2) NOT NULL,
  `expense_status` char(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`expense_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of expense
-- ----------------------------
INSERT INTO `expense` VALUES ('6', 'reimburse', '1', '1', 'R2017072616281893215', '', '2017-07-26', '0', '0.00', '202', '2017-07-28 11:52:21', '2017-07-28 11:52:21');

-- ----------------------------
-- Table structure for `expense_main`
-- ----------------------------
DROP TABLE IF EXISTS `expense_main`;
CREATE TABLE `expense_main` (
  `exp_main_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `expense_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`exp_main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of expense_main
-- ----------------------------

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
  `is_permission` tinyint(1) NOT NULL,
  `status` char(4) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `is_recycle` tinyint(1) unsigned DEFAULT '0',
  `recycle_name` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recycle_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of node
-- ----------------------------
INSERT INTO `node` VALUES ('1', '0', '主页', 'main.index', '1', 'fa fa-home', '1', '1', '1', '0', null, null, '2016-08-31 15:02:05', '2017-05-24 07:26:06');
INSERT INTO `node` VALUES ('2', '0', '系统管理', '#', '11', 'fa fa-cogs', '1', '1', '1', '0', null, null, '2016-08-31 15:12:51', '2017-05-24 07:16:33');
INSERT INTO `node` VALUES ('3', '2', '角色列表', 'role.index', '2', 'fa fa-caret-right', '1', '1', '1', '0', null, null, '2016-08-31 15:22:26', '2016-11-25 08:03:06');
INSERT INTO `node` VALUES ('4', '32', '员工列表', 'user.index', '5', 'fa fa-caret-right', '1', '1', '1', '1', '员工类', 'user', '2016-08-31 15:22:34', '2017-06-01 07:06:29');
INSERT INTO `node` VALUES ('5', '2', '权限列表', 'node.index', '1', 'fa fa-caret-right', '1', '1', '1', '0', null, null, '2016-08-31 15:50:18', '2016-08-31 15:43:45');
INSERT INTO `node` VALUES ('7', '5', '添加权限视图', 'node.addNode', '2', '', '0', '1', '1', '0', null, null, '2016-10-28 08:51:08', '2016-10-28 08:51:08');
INSERT INTO `node` VALUES ('8', '5', '添加权限', 'node.createNode', '3', '', '0', '1', '1', '0', null, null, '2016-10-28 08:52:00', '2016-10-28 08:52:00');
INSERT INTO `node` VALUES ('9', '5', '编辑权限视图', 'node.editNode', '4', '', '0', '1', '1', '0', null, null, '2016-10-31 07:35:30', '2016-10-31 07:35:30');
INSERT INTO `node` VALUES ('10', '5', '编辑权限', 'node.updateNode', '5', '', '0', '1', '1', '0', null, null, '2016-10-31 07:35:46', '2016-10-31 07:35:46');
INSERT INTO `node` VALUES ('11', '5', '权限列表', 'node.getNode', '1', '', '0', '1', '1', '0', null, null, '2016-11-02 15:18:50', '2016-11-02 15:18:53');
INSERT INTO `node` VALUES ('12', '3', '角色列表', 'role.getRole', '1', '', '0', '1', '1', '0', null, null, null, null);
INSERT INTO `node` VALUES ('13', '3', '添加角色列表', 'role.addRole', '2', '', '0', '1', '1', '0', null, null, null, null);
INSERT INTO `node` VALUES ('14', '3', '添加角色', 'role.createRole', '3', '', '0', '1', '1', '0', null, null, null, null);
INSERT INTO `node` VALUES ('15', '3', '编辑角色视图', 'role.editRole', '4', '', '0', '1', '1', '0', null, null, null, null);
INSERT INTO `node` VALUES ('16', '3', '编辑角色', 'role.updateRole', '5', '', '0', '1', '1', '0', null, null, null, null);
INSERT INTO `node` VALUES ('17', '3', '角色详情', 'role.roleInfo', '7', '', '0', '1', '1', '0', null, null, null, null);
INSERT INTO `node` VALUES ('18', '5', '删除权限', 'node.delNode', '6', '', '0', '1', '1', '0', null, null, null, null);
INSERT INTO `node` VALUES ('19', '3', '删除角色', 'role.delRole', '6', '', '0', '1', '1', '0', null, null, null, null);
INSERT INTO `node` VALUES ('23', '4', '员工列表', 'user.getUser', '1', '', '0', '1', '1', '0', null, null, '2016-12-01 09:42:22', '2016-12-01 09:42:22');
INSERT INTO `node` VALUES ('24', '4', '添加员工视图', 'user.addUser', '2', '', '0', '1', '1', '0', null, null, '2016-12-01 09:42:39', '2016-12-01 09:44:09');
INSERT INTO `node` VALUES ('25', '4', '添加角色', 'user.createUser', '3', '', '0', '1', '1', '0', null, null, '2016-12-06 05:57:30', '2016-12-06 05:59:56');
INSERT INTO `node` VALUES ('26', '4', '编辑角色视图', 'user.editUser', '4', '', '0', '1', '1', '0', null, null, '2016-12-06 05:57:53', '2016-12-06 05:59:40');
INSERT INTO `node` VALUES ('27', '4', '编辑员工', 'user.updateUser', '5', '', '0', '1', '1', '0', null, null, '2016-12-06 05:58:31', '2016-12-06 05:58:31');
INSERT INTO `node` VALUES ('28', '4', '删除员工', 'user.delUser', '6', '', '0', '1', '1', '0', null, null, '2016-12-06 05:58:58', '2016-12-06 05:58:58');
INSERT INTO `node` VALUES ('29', '4', '员工详情', 'user.userInfo', '6', '', '0', '1', '1', '0', null, null, '2016-12-06 05:59:21', '2016-12-06 05:59:21');
INSERT INTO `node` VALUES ('30', '4', '修改密码', 'user.editPwd', '7', '', '0', '1', '1', '0', null, null, '2016-12-06 06:00:22', '2016-12-06 06:00:22');
INSERT INTO `node` VALUES ('31', '4', '重置密码', 'user.resetPwd', '8', '', '0', '1', '1', '0', null, null, '2016-12-06 06:00:43', '2016-12-06 06:00:55');
INSERT INTO `node` VALUES ('32', '0', '公司信息', '#', '4', 'fa fa-cog', '1', '1', '1', '0', null, null, '2017-02-20 08:11:24', '2017-05-24 07:33:29');
INSERT INTO `node` VALUES ('33', '32', '公司信息', 'company.index', '1', 'fa fa-caret-right', '1', '1', '1', '0', null, null, '2017-02-20 08:45:03', '2017-04-27 02:07:48');
INSERT INTO `node` VALUES ('34', '32', '部门列表', 'department.index', '3', 'fa fa-caret-right', '1', '1', '1', '1', '部门类', 'department', '2017-04-27 03:17:44', '2017-06-01 06:58:53');
INSERT INTO `node` VALUES ('35', '32', '岗位列表', 'positions.index', '4', 'fa fa-caret-right', '1', '1', '1', '1', '岗位类', 'positions', '2017-05-10 06:39:18', '2017-06-01 06:59:11');
INSERT INTO `node` VALUES ('37', '0', '合同管理', '#', '7', 'fa fa-briefcase', '1', '1', '1', '0', null, null, '2017-05-12 03:08:13', '2017-05-24 07:34:17');
INSERT INTO `node` VALUES ('38', '0', '预算管理', '#', '5', 'fa  fa-bar-chart-o', '1', '1', '1', '0', null, null, '2017-05-12 03:09:42', '2017-05-24 07:33:37');
INSERT INTO `node` VALUES ('39', '0', '我的工作', '#', '2', 'fa fa-briefcase', '1', '1', '1', '0', null, null, '2017-05-12 03:18:54', '2017-05-31 02:39:08');
INSERT INTO `node` VALUES ('40', '0', '流程控制', '#', '3', 'glyphicon glyphicon-refresh', '1', '1', '1', '0', null, null, '2017-05-12 03:39:57', '2017-05-24 07:33:32');
INSERT INTO `node` VALUES ('41', '0', '库存管理', '#', '8', 'fa fa-hdd-o', '1', '1', '1', '0', null, null, '2017-05-12 03:55:23', '2017-05-24 07:32:10');
INSERT INTO `node` VALUES ('42', '0', '报表管理', '#', '10', 'glyphicon glyphicon-indent-left', '1', '1', '1', '0', null, null, '2017-05-12 03:57:44', '2017-05-31 02:43:23');
INSERT INTO `node` VALUES ('46', '32', '科目管理', 'subjects.index', '2', 'fa fa-caret-right', '1', '1', '1', '0', null, null, '2017-05-12 08:55:00', '2017-05-12 08:55:00');
INSERT INTO `node` VALUES ('47', '0', '费用管理', '#', '9', 'glyphicon glyphicon-list-alt', '1', '1', '1', '0', null, null, '2017-05-16 02:36:36', '2017-05-24 07:34:45');
INSERT INTO `node` VALUES ('49', '0', '客户管理', '#', '6', 'fa fa-users', '1', '1', '1', '0', null, null, '2017-05-16 02:38:43', '2017-05-24 07:34:32');
INSERT INTO `node` VALUES ('50', '39', '回收站', 'recycle.index', '1', 'fa fa-caret-right', '0', '1', '1', '0', '', '', '2017-05-31 02:46:48', '2017-07-02 17:50:30');
INSERT INTO `node` VALUES ('52', '40', '审核流程', 'auditProcess.index', '1', 'fa fa-caret-right', '1', '1', '1', '0', '', '', '2017-06-07 03:53:22', '2017-06-07 03:53:22');
INSERT INTO `node` VALUES ('53', '38', '预算列表', 'budget.index', '1', 'fa fa-caret-right', '1', '1', '1', '0', '', '', '2017-06-13 05:53:46', '2017-06-13 05:53:46');
INSERT INTO `node` VALUES ('54', '39', '流程审核', 'auditMy.index', '1', 'fa fa-caret-right', '1', '1', '1', '0', '', '', '2017-07-02 17:50:14', '2017-07-02 17:50:38');
INSERT INTO `node` VALUES ('55', '38', '汇总预算', 'budgetSum.index', '2', 'fa fa-caret-right', '1', '1', '1', '0', '', '', '2017-07-12 14:38:42', '2017-07-12 14:38:42');
INSERT INTO `node` VALUES ('56', '47', '费用报销', 'reimburse.index', '1', 'fa fa-caret-right', '1', '1', '1', '0', '', '', '2017-07-24 10:39:46', '2017-07-24 10:39:46');

-- ----------------------------
-- Table structure for `positions`
-- ----------------------------
DROP TABLE IF EXISTS `positions`;
CREATE TABLE `positions` (
  `pos_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pos_name` varchar(50) NOT NULL,
  `pos_pid` int(10) unsigned DEFAULT '0',
  `sort` tinyint(4) NOT NULL,
  `status` char(4) DEFAULT '1',
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
-- Table structure for `role`
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `sort` tinyint(4) NOT NULL DEFAULT '0',
  `status` char(4) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
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
-- Table structure for `status`
-- ----------------------------
DROP TABLE IF EXISTS `status`;
CREATE TABLE `status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` char(4) NOT NULL,
  `type` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of status
-- ----------------------------
INSERT INTO `status` VALUES ('1', '-1', '常规', '已删除');
INSERT INTO `status` VALUES ('2', '1', '常规', '使用中');
INSERT INTO `status` VALUES ('3', '0', '常规', '已停用');
INSERT INTO `status` VALUES ('4', '9', '常规', '审核中');
INSERT INTO `status` VALUES ('5', '102', '预算', '更新预算项');
INSERT INTO `status` VALUES ('6', '1000', '审核流程', '未审核');
INSERT INTO `status` VALUES ('7', '1001', '审核流程', '已审核');
INSERT INTO `status` VALUES ('8', '202', '费用管理', '编辑单据');

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
  `status` char(4) NOT NULL DEFAULT '1',
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
INSERT INTO `subjects` VALUES ('4', '0', '6000', '损益', '0', '1', '0', '1', '2017-06-22 14:05:45', '2017-06-22 14:05:45');
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
-- Table structure for `sys_status`
-- ----------------------------
DROP TABLE IF EXISTS `sys_status`;
CREATE TABLE `sys_status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` char(4) NOT NULL,
  `type` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `html` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sys_status
-- ----------------------------
INSERT INTO `sys_status` VALUES ('1', '-1', '常规', '已删除', '<span style=\"color:red;\">已删除</span>');
INSERT INTO `sys_status` VALUES ('2', '1', '常规', '使用中', '使用中');
INSERT INTO `sys_status` VALUES ('3', '0', '常规', '已停用', '<span style=\"color:red;\">已停用</span>');
INSERT INTO `sys_status` VALUES ('5', '102', '预算', '更新预算项', '<span style=\"color:green;\">更新预算项</span>');
INSERT INTO `sys_status` VALUES ('6', '1000', '审批流程', '未审批', '未审批');
INSERT INTO `sys_status` VALUES ('7', '1001', '审批流程', '已审批', '已审批');
INSERT INTO `sys_status` VALUES ('8', '1002', '审批流程', '批准', '<span style=\"color:green;\">批准</span>');
INSERT INTO `sys_status` VALUES ('9', '1003', '审批流程', '不批准', '<span style=\"color:red;\">不批准</span>');
INSERT INTO `sys_status` VALUES ('10', '1009', '审批流程', '审批中', '审批中');

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
  `status` char(4) COLLATE utf8_unicode_ci NOT NULL,
  `recycle` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `users1_email_unique` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', '超级管理员', 'admin@sh.net', 'resources/views/template/assets/avatars/user.jpg', '4297f44b13955235245b2497399d7a93', '5', '1', '2017-07-28 10:58:34', '1', '0', '2016-05-25 05:56:33', '2017-07-28 10:58:34');
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
INSERT INTO `users_base` VALUES ('0000000001', '1', '1', '2017-06-28 14:35:47', '2017-06-28 14:35:47');
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
