/*
Navicat MySQL Data Transfer

Source Server         : localhost3310
Source Server Version : 50505
Source Host           : localhost:3310
Source Database       : eas

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-08-22 15:54:38
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `audit_info`
-- ----------------------------
DROP TABLE IF EXISTS `audit_info`;
CREATE TABLE `audit_info` (
  `process_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `process_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `process_app` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `process_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '0',
  `process_text` text,
  `process_users` text,
  `process_user_res` text,
  `process_audit_user` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `created_user` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `status` int(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`process_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of audit_info
-- ----------------------------
INSERT INTO `audit_info` VALUES ('4F51428D9A3C0110B5A80C958CC583ED', 'reimburse', '01312513D5C357069D88F1510F911FB4', '单据号：R2017081815312243053', '', '105A70A981B6032B0EF41101D335EBF6,CCEA58872FD18683E638E047625C17F2', '|105A70A981B6032B0EF41101D335EBF6|,|CCEA58872FD18683E638E047625C17F2|', '0', '8454859EDC79BCD6B5250DF817FF10EA', '1001', '2017-08-18 15:31:31', '2017-08-18 16:34:22');
INSERT INTO `audit_info` VALUES ('8B8ACBBC4428AC41D948E8564EA6E520', 'budget', '600408118A6023AB586DF59FE0EC1C91', '新增预算—21312—312321', '', 'CCEA58872FD18683E638E047625C17F2', '|CCEA58872FD18683E638E047625C17F2|', '0', '8454859EDC79BCD6B5250DF817FF10EA', '1001', '2017-08-18 16:41:15', '2017-08-18 16:41:54');
INSERT INTO `audit_info` VALUES ('BD6778E49CB11FB8737A591A4E118611', 'budgetSum', '30FF3D0C1793D0ADFCFE8B6CB125EA29', '更新预算—123213—123123121231231', '', '105A70A981B6032B0EF41101D335EBF6,CCEA58872FD18683E638E047625C17F2', '|105A70A981B6032B0EF41101D335EBF6|,|CCEA58872FD18683E638E047625C17F2|', '0', '8454859EDC79BCD6B5250DF817FF10EA', '1001', '2017-08-18 16:47:13', '2017-08-18 16:51:20');

-- ----------------------------
-- Table structure for `audit_info_text`
-- ----------------------------
DROP TABLE IF EXISTS `audit_info_text`;
CREATE TABLE `audit_info_text` (
  `audit_text_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `process_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `created_user` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `audit_text` text NOT NULL,
  `audit_sort` tinyint(4) unsigned NOT NULL,
  `audit_res` int(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`audit_text_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of audit_info_text
-- ----------------------------
INSERT INTO `audit_info_text` VALUES ('395524F990BD7D378E632D5882B71CCE', 'BD6778E49CB11FB8737A591A4E118611', 'CCEA58872FD18683E638E047625C17F2', '不同意', '2', '1003', '2017-08-18 16:51:20', '2017-08-18 16:51:20');
INSERT INTO `audit_info_text` VALUES ('4C1DEC5FF96952DD8238B0B7D0F4390D', '8B8ACBBC4428AC41D948E8564EA6E520', 'CCEA58872FD18683E638E047625C17F2', '同意', '1', '1002', '2017-08-18 16:41:54', '2017-08-18 16:41:54');
INSERT INTO `audit_info_text` VALUES ('4FFB701C67A18EC795B8EBDC9D700BD4', '4F51428D9A3C0110B5A80C958CC583ED', '105A70A981B6032B0EF41101D335EBF6', '111111111111111111111', '1', '1002', '2017-08-18 15:33:28', '2017-08-18 15:33:28');
INSERT INTO `audit_info_text` VALUES ('9060490C8D38E082A08A20BB1D82F442', 'BD6778E49CB11FB8737A591A4E118611', '105A70A981B6032B0EF41101D335EBF6', '通过', '1', '1002', '2017-08-18 16:47:31', '2017-08-18 16:47:31');
INSERT INTO `audit_info_text` VALUES ('AF019251123E4BCEF3EC9A7842C0D353', '4F51428D9A3C0110B5A80C958CC583ED', 'CCEA58872FD18683E638E047625C17F2', '123321', '2', '1002', '2017-08-18 16:34:22', '2017-08-18 16:34:22');

-- ----------------------------
-- Table structure for `audit_process`
-- ----------------------------
DROP TABLE IF EXISTS `audit_process`;
CREATE TABLE `audit_process` (
  `audit_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `audit_dep` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `audit_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '0',
  `audit_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `audit_process` text,
  `status` int(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`audit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of audit_process
-- ----------------------------
INSERT INTO `audit_process` VALUES ('38C6960098C5635AA81B996498364357', '0', 'budget', '预算审核流程', 'CCEA58872FD18683E638E047625C17F2', '1', '2017-08-16 16:38:27', '2017-08-16 16:42:42');
INSERT INTO `audit_process` VALUES ('D2C96D0F0B38435B5DD2AC4009EBAD0F', '0', 'reimburse', '报销审核', '105A70A981B6032B0EF41101D335EBF6,CCEA58872FD18683E638E047625C17F2', '1', '2017-08-17 14:36:01', '2017-08-18 15:31:05');
INSERT INTO `audit_process` VALUES ('E19E793C7604C87BBDD4CB55397785C3', '0', 'budgetSum', '测试预算汇总', '105A70A981B6032B0EF41101D335EBF6,CCEA58872FD18683E638E047625C17F2', '1', '2017-08-18 16:46:56', '2017-08-18 16:46:56');

-- ----------------------------
-- Table structure for `budget`
-- ----------------------------
DROP TABLE IF EXISTS `budget`;
CREATE TABLE `budget` (
  `budget_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `budget_ids` text NOT NULL,
  `budget_period` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `budget_sum` tinyint(1) unsigned NOT NULL,
  `create_user` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `budget_num` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `budget_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `budget_start` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `budget_end` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `status` int(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`budget_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of budget
-- ----------------------------
INSERT INTO `budget` VALUES ('30FF3D0C1793D0ADFCFE8B6CB125EA29', 'DD5FDCDBABD7D0C6E6F47EC8965D91E7', 'month', '1', '8454859EDC79BCD6B5250DF817FF10EA', '123213', '123123121231231', '2017-08', '2017-08', '1003', '2017-08-17 12:29:41', '2017-08-18 16:51:20');
INSERT INTO `budget` VALUES ('600408118A6023AB586DF59FE0EC1C91', '', 'day', '0', '8454859EDC79BCD6B5250DF817FF10EA', '21312', '312321', '2017-08-01', '2017-08-31', '1', null, '2017-08-18 16:41:54');
INSERT INTO `budget` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', '', 'month', '0', '8454859EDC79BCD6B5250DF817FF10EA', '201708123', '测试预算', '2017-01', '2017-08', '1', '2017-08-17 12:21:24', '2017-08-17 12:21:24');

-- ----------------------------
-- Table structure for `budget_subject`
-- ----------------------------
DROP TABLE IF EXISTS `budget_subject`;
CREATE TABLE `budget_subject` (
  `budget_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `subject_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `sum_amount` decimal(10,2) NOT NULL,
  `status` int(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of budget_subject
-- ----------------------------
INSERT INTO `budget_subject` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', 'B80F1E6EB61D0C564A1F6760B03C81B7', '8000.00', '1', '2017-08-17 12:20:56', '2017-08-17 12:20:56');
INSERT INTO `budget_subject` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', '955587E0FD804314E4C3ED2409AD4F6A', '800.00', '1', '2017-08-17 12:20:57', '2017-08-17 12:20:57');
INSERT INTO `budget_subject` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', 'AB858DB8E3F001F383BF8B6B6928A42A', '1000.00', '1', '2017-08-17 12:20:58', '2017-08-17 12:20:58');
INSERT INTO `budget_subject` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '3100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');

-- ----------------------------
-- Table structure for `budget_subject_date`
-- ----------------------------
DROP TABLE IF EXISTS `budget_subject_date`;
CREATE TABLE `budget_subject_date` (
  `budget_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `subject_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `budget_date` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `budget_date_str` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `budget_amount` decimal(10,2) unsigned NOT NULL,
  `status` int(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of budget_subject_date
-- ----------------------------
INSERT INTO `budget_subject_date` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', 'B80F1E6EB61D0C564A1F6760B03C81B7', '2017-01', '1483200000', '1000.00', '1', '2017-08-17 12:21:05', '2017-08-17 12:21:05');
INSERT INTO `budget_subject_date` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', 'B80F1E6EB61D0C564A1F6760B03C81B7', '2017-02', '1485878400', '1000.00', '1', '2017-08-17 12:21:06', '2017-08-17 12:21:06');
INSERT INTO `budget_subject_date` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', 'B80F1E6EB61D0C564A1F6760B03C81B7', '2017-03', '1488297600', '1000.00', '11', '2017-08-17 12:21:06', '2017-08-17 12:21:06');
INSERT INTO `budget_subject_date` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', 'B80F1E6EB61D0C564A1F6760B03C81B7', '2017-04', '1490976000', '1000.00', '11', '2017-08-17 12:21:07', '2017-08-17 12:21:07');
INSERT INTO `budget_subject_date` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', 'B80F1E6EB61D0C564A1F6760B03C81B7', '2017-05', '1493568000', '1000.00', '1', '2017-08-17 12:21:07', '2017-08-17 12:21:07');
INSERT INTO `budget_subject_date` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', 'B80F1E6EB61D0C564A1F6760B03C81B7', '2017-06', '1496246400', '1000.00', '1', '2017-08-17 12:21:08', '2017-08-17 12:21:08');
INSERT INTO `budget_subject_date` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', 'B80F1E6EB61D0C564A1F6760B03C81B7', '2017-07', '1498838400', '1000.00', '1', '2017-08-17 12:21:08', '2017-08-17 12:21:08');
INSERT INTO `budget_subject_date` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', 'B80F1E6EB61D0C564A1F6760B03C81B7', '2017-08', '1501516800', '1000.00', '1', '2017-08-17 12:21:08', '2017-08-17 12:21:08');
INSERT INTO `budget_subject_date` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', '955587E0FD804314E4C3ED2409AD4F6A', '2017-01', '1483200000', '100.00', '1', '2017-08-17 12:21:08', '2017-08-17 12:21:08');
INSERT INTO `budget_subject_date` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', '955587E0FD804314E4C3ED2409AD4F6A', '2017-02', '1485878400', '100.00', '1', '2017-08-17 12:21:08', '2017-08-17 12:21:08');
INSERT INTO `budget_subject_date` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', '955587E0FD804314E4C3ED2409AD4F6A', '2017-03', '1488297600', '100.00', '1', '2017-08-17 12:21:09', '2017-08-17 12:21:09');
INSERT INTO `budget_subject_date` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', '955587E0FD804314E4C3ED2409AD4F6A', '2017-04', '1490976000', '100.00', '1', '2017-08-17 12:21:09', '2017-08-17 12:21:09');
INSERT INTO `budget_subject_date` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', '955587E0FD804314E4C3ED2409AD4F6A', '2017-05', '1493568000', '100.00', '1', '2017-08-17 12:21:09', '2017-08-17 12:21:09');
INSERT INTO `budget_subject_date` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', '955587E0FD804314E4C3ED2409AD4F6A', '2017-06', '1496246400', '100.00', '1', '2017-08-17 12:21:09', '2017-08-17 12:21:09');
INSERT INTO `budget_subject_date` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', '955587E0FD804314E4C3ED2409AD4F6A', '2017-07', '1498838400', '100.00', '1', '2017-08-17 12:21:09', '2017-08-17 12:21:09');
INSERT INTO `budget_subject_date` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', '955587E0FD804314E4C3ED2409AD4F6A', '2017-08', '1501516800', '100.00', '1', '2017-08-17 12:21:10', '2017-08-17 12:21:10');
INSERT INTO `budget_subject_date` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-01', '1483200000', '125.00', '1', '2017-08-17 12:21:10', '2017-08-17 12:21:10');
INSERT INTO `budget_subject_date` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-02', '1485878400', '125.00', '1', '2017-08-17 12:21:10', '2017-08-17 12:21:10');
INSERT INTO `budget_subject_date` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-03', '1488297600', '125.00', '1', '2017-08-17 12:21:10', '2017-08-17 12:21:10');
INSERT INTO `budget_subject_date` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-04', '1490976000', '125.00', '1', '2017-08-17 12:21:10', '2017-08-17 12:21:10');
INSERT INTO `budget_subject_date` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-05', '1493568000', '125.00', '1', '2017-08-17 12:21:11', '2017-08-17 12:21:11');
INSERT INTO `budget_subject_date` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-06', '1496246400', '125.00', '1', '2017-08-17 12:21:11', '2017-08-17 12:21:11');
INSERT INTO `budget_subject_date` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-07', '1498838400', '125.00', '1', '2017-08-17 12:21:11', '2017-08-17 12:21:11');
INSERT INTO `budget_subject_date` VALUES ('DD5FDCDBABD7D0C6E6F47EC8965D91E7', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08', '1501516800', '125.00', '1', '2017-08-17 12:21:13', '2017-08-17 12:21:13');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-01', '1501516800', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-02', '1501603200', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-03', '1501689600', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-04', '1501776000', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-05', '1501862400', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-06', '1501948800', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-07', '1502035200', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-08', '1502121600', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-09', '1502208000', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-10', '1502294400', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-11', '1502380800', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-12', '1502467200', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-13', '1502553600', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-14', '1502640000', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-15', '1502726400', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-16', '1502812800', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-17', '1502899200', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-18', '1502985600', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-19', '1503072000', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-20', '1503158400', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-21', '1503244800', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-22', '1503331200', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-23', '1503417600', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-24', '1503504000', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-25', '1503590400', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-26', '1503676800', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-27', '1503763200', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-28', '1503849600', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-29', '1503936000', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-30', '1504022400', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');
INSERT INTO `budget_subject_date` VALUES ('600408118A6023AB586DF59FE0EC1C91', 'AB858DB8E3F001F383BF8B6B6928A42A', '2017-08-31', '1504108800', '100.00', '1', '2017-08-18 16:41:05', '2017-08-18 16:41:54');

-- ----------------------------
-- Table structure for `contract`
-- ----------------------------
DROP TABLE IF EXISTS `contract`;
CREATE TABLE `contract` (
  `cont_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `cont_type` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `cont_class` varchar(8) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `cont_num` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `cont_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `cont_cust` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `cont_start` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `cont_end` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `cont_status` int(4) NOT NULL,
  `cont_amount` decimal(10,0) NOT NULL,
  `cont_remark` text CHARACTER SET utf8 COLLATE utf8_bin,
  `cont_auto` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`cont_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of contract
-- ----------------------------

-- ----------------------------
-- Table structure for `contract_details`
-- ----------------------------
DROP TABLE IF EXISTS `contract_details`;
CREATE TABLE `contract_details` (
  `details_id` char(32) NOT NULL,
  `cont_id` char(32) NOT NULL,
  `cont_num` varchar(255) DEFAULT NULL,
  `cont_name` varchar(255) DEFAULT NULL,
  `cont_charge_type` varchar(255) DEFAULT NULL,
  `cont_start` varchar(255) DEFAULT NULL,
  `cont_end` varchar(255) DEFAULT NULL,
  `cont_status` int(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`details_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of contract_details
-- ----------------------------

-- ----------------------------
-- Table structure for `contract_enclosure`
-- ----------------------------
DROP TABLE IF EXISTS `contract_enclosure`;
CREATE TABLE `contract_enclosure` (
  `enclo_id` char(32) NOT NULL,
  `cont_id` char(32) NOT NULL,
  `enclo_user` char(32) NOT NULL,
  `enclo_url` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`enclo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of contract_enclosure
-- ----------------------------

-- ----------------------------
-- Table structure for `customer`
-- ----------------------------
DROP TABLE IF EXISTS `customer`;
CREATE TABLE `customer` (
  `cust_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `cust_num` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `cust_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `cust_status` int(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`cust_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of customer
-- ----------------------------
INSERT INTO `customer` VALUES ('8E96542276F735B6F08C72CF2B0ED372', '123123123', '2321312321312321321', '0', '2017-08-17 12:44:11', '2017-08-17 12:44:11');

-- ----------------------------
-- Table structure for `department`
-- ----------------------------
DROP TABLE IF EXISTS `department`;
CREATE TABLE `department` (
  `dep_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `dep_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `dep_leader` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '0',
  `dep_pid` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '0',
  `reimburse` tinyint(1) NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `status` int(4) NOT NULL DEFAULT '1',
  `recycle` tinyint(1) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`dep_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of department
-- ----------------------------
INSERT INTO `department` VALUES ('B65D22597684E1DE7F70E7F7344BF048', '技术部', '0', 'FD138675B855A703350A5E344DDB04CD', '0', '1', '1', '0', '2017-08-16 17:23:21', '2017-08-16 17:17:57');
INSERT INTO `department` VALUES ('FD138675B855A703350A5E344DDB04CD', '总经办', 'CCEA58872FD18683E638E047625C17F2', '0', '0', '1', '1', '0', '2017-08-16 17:17:48', '2017-08-16 17:17:48');

-- ----------------------------
-- Table structure for `expense`
-- ----------------------------
DROP TABLE IF EXISTS `expense`;
CREATE TABLE `expense` (
  `expense_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `expense_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `expense_dep` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `expense_user` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `expense_num` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `expense_title` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `expense_date` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `expense_doc_num` int(10) unsigned NOT NULL,
  `expense_amount` decimal(10,2) NOT NULL,
  `expense_status` int(4) NOT NULL,
  `expense_cashier` char(32) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`expense_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of expense
-- ----------------------------
INSERT INTO `expense` VALUES ('01312513D5C357069D88F1510F911FB4', 'reimburse', 'FD138675B855A703350A5E344DDB04CD', '8454859EDC79BCD6B5250DF817FF10EA', 'R2017081815312243053', '', '2017-08-18', '0', '0.00', '203', 'CCEA58872FD18683E638E047625C17F2', '2017-08-18 15:31:22', '2017-08-18 16:34:22');

-- ----------------------------
-- Table structure for `expense_enclosure`
-- ----------------------------
DROP TABLE IF EXISTS `expense_enclosure`;
CREATE TABLE `expense_enclosure` (
  `enclo_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `expense_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `exp_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `enclo_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `enclo_user` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `enclo_url` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`enclo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of expense_enclosure
-- ----------------------------

-- ----------------------------
-- Table structure for `expense_main`
-- ----------------------------
DROP TABLE IF EXISTS `expense_main`;
CREATE TABLE `expense_main` (
  `exp_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `expense_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `exp_remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `budget_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `subject_id_debit` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `subject_id_credit` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `exp_amount` decimal(10,2) NOT NULL,
  `exp_user` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `enclosure` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`exp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of expense_main
-- ----------------------------
INSERT INTO `expense_main` VALUES ('260BB32B47E51B66C8A1B78BC66EE4A8', '01312513D5C357069D88F1510F911FB4', '123123', '', '', '', '12321312.00', '8454859EDC79BCD6B5250DF817FF10EA', '0', '2017-08-18 15:31:25', '2017-08-18 15:31:25');

-- ----------------------------
-- Table structure for `node`
-- ----------------------------
DROP TABLE IF EXISTS `node`;
CREATE TABLE `node` (
  `id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `pid` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `alias` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `sort` tinyint(4) NOT NULL DEFAULT '0',
  `icon` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `is_menu` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_permission` tinyint(1) NOT NULL,
  `status` int(4) NOT NULL DEFAULT '1',
  `is_recycle` tinyint(1) unsigned DEFAULT '0',
  `recycle_name` varchar(60) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `recycle_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of node
-- ----------------------------
INSERT INTO `node` VALUES ('00E9B5673E1C36509A7683DA622C3391', '951E9717135DA00B8993489C67DBEE19', '系统组件-页面跳转', 'component.ctRedirectMsg', '1', 'fa fa-caret-right', '0', '0', '1', '0', '', '', '2017-08-04 14:45:44', '2017-08-04 14:45:44');
INSERT INTO `node` VALUES ('00F7C5E21CB1879BB69806813E91CCFF', '951E9717135DA00B8993489C67DBEE19', '角色列表', 'role.index', '2', 'fa fa-caret-right', '1', '1', '1', '0', null, null, '2016-08-31 15:22:26', '2016-11-25 08:03:06');
INSERT INTO `node` VALUES ('0E29A50F91B020C52DFA529D745A590B', '00F7C5E21CB1879BB69806813E91CCFF', '添加角色列表', 'role.addRole', '2', '', '0', '1', '1', '0', null, null, null, null);
INSERT INTO `node` VALUES ('1109421DF20C61265AA46FD9C45077A2', '2537185117D81CA1D2EBD0C07F986456', '供应商列表', 'supplier.index', '1', 'fa fa-caret-right', '1', '1', '1', '0', '', '', '2017-08-14 15:13:18', '2017-08-14 15:13:18');
INSERT INTO `node` VALUES ('226CBCA5A253A07754F6F9770C893328', 'A316BA61448DEC85FEC78CC562781314', '重置密码', 'user.resetPwd', '8', '', '0', '1', '1', '0', null, null, '2016-12-06 06:00:43', '2016-12-06 06:00:55');
INSERT INTO `node` VALUES ('22DAE2746E22DE8E746D2EB3C78798DD', '4C2880F52A6D3DDB2211B653C4FE315A', '费用报销', 'reimburse.index', '2', 'fa fa-caret-right', '1', '1', '1', '0', '', '', '2017-07-24 10:39:46', '2017-08-15 13:11:22');
INSERT INTO `node` VALUES ('230783C8AD5A7C655BF85DA791A261D9', '0', '主页', 'main.index', '1', 'fa fa-home', '1', '0', '1', '0', '', '', '2016-08-31 15:02:05', '2017-08-04 14:52:02');
INSERT INTO `node` VALUES ('2414456A37E3C9393B4A3C8E8BD6497E', 'A316BA61448DEC85FEC78CC562781314', '修改密码', 'user.editPwd', '7', '', '0', '1', '1', '0', null, null, '2016-12-06 06:00:22', '2016-12-06 06:00:22');
INSERT INTO `node` VALUES ('2537185117D81CA1D2EBD0C07F986456', '0', '供应商管理', '#', '7', 'fa fa-users', '1', '1', '1', '0', '', '', '2017-08-14 14:10:09', '2017-08-14 14:10:31');
INSERT INTO `node` VALUES ('3439B62D7D565B5677E90778C5CD6083', 'A316BA61448DEC85FEC78CC562781314', '员工详情', 'user.userInfo', '6', '', '0', '1', '1', '0', null, null, '2016-12-06 05:59:21', '2016-12-06 05:59:21');
INSERT INTO `node` VALUES ('35B31F424A4B5BAC0390544901A0E63F', '0', '公司信息', '#', '4', 'fa fa-cog', '1', '1', '1', '0', null, null, '2017-02-20 08:11:24', '2017-05-24 07:33:29');
INSERT INTO `node` VALUES ('35E530F648BE101B340B22685BD431B8', '00F7C5E21CB1879BB69806813E91CCFF', '删除角色', 'role.delRole', '6', '', '0', '1', '1', '0', null, null, null, null);
INSERT INTO `node` VALUES ('3727D90E29E32EF84DD9398A9446377D', 'EF3F342A905785F6826C2AB2723DF617', '汇总预算', 'budgetSum.index', '2', 'fa fa-caret-right', '1', '1', '1', '0', '', '', '2017-07-12 14:38:42', '2017-07-12 14:38:42');
INSERT INTO `node` VALUES ('3AD1E0BFBB12C714A9E11C88760597C5', '0', '客户管理', '#', '6', 'fa fa-users', '1', '1', '1', '0', null, null, '2017-05-16 02:38:43', '2017-05-24 07:34:32');
INSERT INTO `node` VALUES ('3FB5FCB07B8975EBD86B5A0B9BDC49D3', '862A577516209FC37DFA54D9445D5A80', '删除权限', 'node.delNode', '6', '', '0', '1', '1', '0', null, null, null, null);
INSERT INTO `node` VALUES ('4035BCADAE2E491E68DEB27CA98CA280', '0', '流程控制', '#', '3', 'glyphicon glyphicon-refresh', '1', '1', '1', '0', null, null, '2017-05-12 03:39:57', '2017-05-24 07:33:32');
INSERT INTO `node` VALUES ('4318745B9BA91A81421CBC783D82747E', '35B31F424A4B5BAC0390544901A0E63F', '科目管理', 'subjects.index', '2', 'fa fa-caret-right', '1', '1', '1', '0', null, null, '2017-05-12 08:55:00', '2017-05-12 08:55:00');
INSERT INTO `node` VALUES ('4A8D9ABD9EA98193F4928FC08A3DE553', '862A577516209FC37DFA54D9445D5A80', '编辑权限视图', 'node.editNode', '4', '', '0', '1', '1', '0', null, null, '2016-10-31 07:35:30', '2016-10-31 07:35:30');
INSERT INTO `node` VALUES ('4C2880F52A6D3DDB2211B653C4FE315A', '0', '费用管理', '#', '9', 'glyphicon glyphicon-list-alt', '1', '1', '1', '0', null, null, '2017-05-16 02:36:36', '2017-05-24 07:34:45');
INSERT INTO `node` VALUES ('535331FC9A1B603D63A8AEFCDD8471A1', '35B31F424A4B5BAC0390544901A0E63F', '岗位列表', 'positions.index', '4', 'fa fa-caret-right', '1', '1', '1', '1', '岗位类', 'positions', '2017-05-10 06:39:18', '2017-06-01 06:59:11');
INSERT INTO `node` VALUES ('57C5272DFD8B98EEBDA98E03E668690B', '0', '报表管理', '#', '11', 'glyphicon glyphicon-indent-left', '1', '1', '1', '0', '', '', '2017-05-12 03:57:44', '2017-08-14 14:11:11');
INSERT INTO `node` VALUES ('5E5F1E23B3F66177D89B75EF993FF4A1', 'CBFE711E744E1FD32F231E50FF5BD23B', '合同列表', 'contract.index', '1', 'fa fa-caret-right', '1', '1', '1', '0', '', '', '2017-08-14 15:18:42', '2017-08-14 15:18:42');
INSERT INTO `node` VALUES ('5ED0DAE106A20613C0A8AD990EF29822', 'D16A60E608271D0F2B7ED4E8869634F4', '流程审核', 'auditMy.index', '2', 'fa fa-caret-right', '1', '1', '1', '0', '', '', '2017-07-02 17:50:14', '2017-08-07 15:14:25');
INSERT INTO `node` VALUES ('5FCE5CD49C7B7E40186872FC05886379', '00F7C5E21CB1879BB69806813E91CCFF', '角色列表', 'role.getRole', '1', '', '0', '1', '1', '0', null, null, null, null);
INSERT INTO `node` VALUES ('6FBA7C260CE33BDE93FAB9CFEE46CBAC', '862A577516209FC37DFA54D9445D5A80', '添加权限', 'node.createNode', '3', '', '0', '1', '1', '0', null, null, '2016-10-28 08:52:00', '2016-10-28 08:52:00');
INSERT INTO `node` VALUES ('73BDACB453D8D752DF5D0DA9F3D913A0', '35B31F424A4B5BAC0390544901A0E63F', '公司信息', 'company.index', '1', 'fa fa-caret-right', '1', '1', '1', '0', null, null, '2017-02-20 08:45:03', '2017-04-27 02:07:48');
INSERT INTO `node` VALUES ('7E8FC93D1C9E2C5109EE64D8632F5B83', 'D16A60E608271D0F2B7ED4E8869634F4', '回收站', 'recycle.index', '1', 'fa fa-caret-right', '0', '1', '1', '0', '', '', '2017-05-31 02:46:48', '2017-07-02 17:50:30');
INSERT INTO `node` VALUES ('841D415E11B88896CED30EE9CA7CB849', '862A577516209FC37DFA54D9445D5A80', '编辑权限', 'node.updateNode', '5', '', '0', '1', '1', '0', null, null, '2016-10-31 07:35:46', '2016-10-31 07:35:46');
INSERT INTO `node` VALUES ('862A577516209FC37DFA54D9445D5A80', '951E9717135DA00B8993489C67DBEE19', '权限列表', 'node.index', '1', 'fa fa-caret-right', '1', '1', '1', '0', null, null, '2016-08-31 15:50:18', '2016-08-31 15:43:45');
INSERT INTO `node` VALUES ('8D63CD4CEA23CDBBF5B25FEF804094A1', 'A316BA61448DEC85FEC78CC562781314', '添加员工视图', 'user.addUser', '2', '', '0', '1', '1', '0', null, null, '2016-12-01 09:42:39', '2016-12-01 09:44:09');
INSERT INTO `node` VALUES ('8F12BBB953D7ED6891D298E2701EA714', 'A316BA61448DEC85FEC78CC562781314', '添加角色', 'user.createUser', '3', '', '0', '1', '1', '0', null, null, '2016-12-06 05:57:30', '2016-12-06 05:59:56');
INSERT INTO `node` VALUES ('9107B05803D06A0453CD9CFD8BAAB586', 'A316BA61448DEC85FEC78CC562781314', '编辑角色视图', 'user.editUser', '4', '', '0', '1', '1', '0', null, null, '2016-12-06 05:57:53', '2016-12-06 05:59:40');
INSERT INTO `node` VALUES ('933EAEE6DC76C1A3F8E0376921A9DD67', '862A577516209FC37DFA54D9445D5A80', '添加权限视图', 'node.addNode', '2', '', '0', '1', '1', '0', null, null, '2016-10-28 08:51:08', '2016-10-28 08:51:08');
INSERT INTO `node` VALUES ('951E9717135DA00B8993489C67DBEE19', '0', '系统管理', '#', '12', 'fa fa-cogs', '1', '1', '1', '0', '', '', '2016-08-31 15:12:51', '2017-08-14 14:11:24');
INSERT INTO `node` VALUES ('A316BA61448DEC85FEC78CC562781314', '35B31F424A4B5BAC0390544901A0E63F', '员工列表', 'user.index', '5', 'fa fa-caret-right', '1', '1', '1', '1', '员工类', 'user', '2016-08-31 15:22:34', '2017-06-01 07:06:29');
INSERT INTO `node` VALUES ('B37C2A1F3EDDCAE321F7ED9542D69F30', '3AD1E0BFBB12C714A9E11C88760597C5', '客户列表', 'customer.index', '1', 'fa fa-caret-right', '1', '1', '1', '0', '', '', '2017-08-14 14:08:10', '2017-08-14 14:10:37');
INSERT INTO `node` VALUES ('B7FB4750CF90C2C8FAD6627378345ECD', '0', '库存管理', '#', '10', 'fa fa-hdd-o', '1', '1', '1', '0', '', '', '2017-05-12 03:55:23', '2017-08-14 14:10:58');
INSERT INTO `node` VALUES ('C3D8D8559C55244DB8E94B3B34AAABF7', '862A577516209FC37DFA54D9445D5A80', '权限列表', 'node.getNode', '1', '', '0', '1', '1', '0', null, null, '2016-11-02 15:18:50', '2016-11-02 15:18:53');
INSERT INTO `node` VALUES ('CB650178ACAE74227624B429B635AAE9', 'A316BA61448DEC85FEC78CC562781314', '编辑员工', 'user.updateUser', '5', '', '0', '1', '1', '0', null, null, '2016-12-06 05:58:31', '2016-12-06 05:58:31');
INSERT INTO `node` VALUES ('CBFE711E744E1FD32F231E50FF5BD23B', '0', '合同管理', '#', '8', 'fa fa-briefcase', '1', '1', '1', '0', '', '', '2017-05-12 03:08:13', '2017-08-14 14:10:22');
INSERT INTO `node` VALUES ('D025B3FB77A126BCCB8D869F2CC57024', '4035BCADAE2E491E68DEB27CA98CA280', '审核流程', 'auditProcess.index', '1', 'fa fa-caret-right', '1', '1', '1', '0', '', '', '2017-06-07 03:53:22', '2017-06-07 03:53:22');
INSERT INTO `node` VALUES ('D16A60E608271D0F2B7ED4E8869634F4', '0', '我的工作', '#', '2', 'fa fa-briefcase', '1', '1', '1', '0', null, null, '2017-05-12 03:18:54', '2017-05-31 02:39:08');
INSERT INTO `node` VALUES ('D2F6AAF86E869B19E6F7C94981100668', '00F7C5E21CB1879BB69806813E91CCFF', '角色详情', 'role.roleInfo', '7', '', '0', '1', '1', '0', null, null, null, null);
INSERT INTO `node` VALUES ('D6178F82870A7465F236DBF2899D8318', '00F7C5E21CB1879BB69806813E91CCFF', '编辑角色', 'role.updateRole', '5', '', '0', '1', '1', '0', null, null, null, null);
INSERT INTO `node` VALUES ('D9F6F4A50D4AAA31E4640E6CD9326DB7', '4C2880F52A6D3DDB2211B653C4FE315A', '报销付款', 'reimbursePay.index', '1', 'fa fa-caret-right', '1', '1', '1', '0', '', '', '2017-08-15 13:11:09', '2017-08-15 13:11:09');
INSERT INTO `node` VALUES ('DB45D9207E46B5405D0F07D1570A32DC', '00F7C5E21CB1879BB69806813E91CCFF', '编辑角色视图', 'role.editRole', '4', '', '0', '1', '1', '0', null, null, null, null);
INSERT INTO `node` VALUES ('DC18D78CD2CCB8C086C4EDD374591BD6', '35B31F424A4B5BAC0390544901A0E63F', '部门列表', 'department.index', '3', 'fa fa-caret-right', '1', '1', '1', '1', '部门类', 'department', '2017-04-27 03:17:44', '2017-06-01 06:58:53');
INSERT INTO `node` VALUES ('E18E6AB5084ECF54A11D15FC6FB59E1B', 'A316BA61448DEC85FEC78CC562781314', '删除员工', 'user.delUser', '6', '', '0', '1', '1', '0', null, null, '2016-12-06 05:58:58', '2016-12-06 05:58:58');
INSERT INTO `node` VALUES ('E61B73A181096553D6A72BE5692A9301', 'D16A60E608271D0F2B7ED4E8869634F4', '消息通知', 'notice.index', '1', 'fa fa-caret-right', '1', '1', '1', '0', '', '', '2017-08-07 15:15:18', '2017-08-07 15:15:18');
INSERT INTO `node` VALUES ('EF3F342A905785F6826C2AB2723DF617', '0', '预算管理', '#', '5', 'fa  fa-bar-chart-o', '1', '1', '1', '0', null, null, '2017-05-12 03:09:42', '2017-05-24 07:33:37');
INSERT INTO `node` VALUES ('F3F3D9FF01BC577E02859F04DD1A5DDD', '00F7C5E21CB1879BB69806813E91CCFF', '添加角色', 'role.createRole', '3', '', '0', '1', '1', '0', null, null, null, null);
INSERT INTO `node` VALUES ('F4A8718D8227FE81BBCCF08CD95A199D', 'A316BA61448DEC85FEC78CC562781314', '员工列表', 'user.getUser', '1', '', '0', '1', '1', '0', null, null, '2016-12-01 09:42:22', '2016-12-01 09:42:22');
INSERT INTO `node` VALUES ('FC4229AA3C87316D9DC1C8168F51DFC5', 'EF3F342A905785F6826C2AB2723DF617', '预算列表', 'budget.index', '1', 'fa fa-caret-right', '1', '1', '1', '0', '', '', '2017-06-13 05:53:46', '2017-06-13 05:53:46');

-- ----------------------------
-- Table structure for `notice`
-- ----------------------------
DROP TABLE IF EXISTS `notice`;
CREATE TABLE `notice` (
  `notice_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `notice_app` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `notice_message` text NOT NULL,
  `notice_user` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `post_user` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `is_see` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`notice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of notice
-- ----------------------------
INSERT INTO `notice` VALUES ('32ED6E2673A6FA556A9A9D3DE4654A6B', '0FDFFED3E801A4C0CC270C516CF3E690', '报销单据：编号R2017081814270397825。已通过审批，等待出纳付款。', '8454859EDC79BCD6B5250DF817FF10EA', '', '1', '2017-08-18 15:10:37', '2017-08-18 15:10:41');
INSERT INTO `notice` VALUES ('505C3E4323F80C2F1CBE62DE0E95AD76', '01312513D5C357069D88F1510F911FB4', '报销单据：编号R2017081815312243053。已通过审批，等待出纳付款。', '8454859EDC79BCD6B5250DF817FF10EA', '', '1', '2017-08-18 16:34:22', '2017-08-18 16:41:24');
INSERT INTO `notice` VALUES ('AB89905BD452DBB1BB96805A266A385E', '0FDFFED3E801A4C0CC270C516CF3E690', '报销单据：编号R2017081814270397825。已通过审批，等待付款。', 'CCEA58872FD18683E638E047625C17F2', '', '0', '2017-08-18 15:10:37', '2017-08-18 15:10:37');
INSERT INTO `notice` VALUES ('C46E7B8EE2265A1082D4437139D7E5C9', '0FDFFED3E801A4C0CC270C516CF3E690', '报销单据：编号R2017081814270397825。出纳已付款，请确认收款。', '8454859EDC79BCD6B5250DF817FF10EA', 'CCEA58872FD18683E638E047625C17F2', '1', '2017-08-18 15:17:58', '2017-08-18 15:20:59');
INSERT INTO `notice` VALUES ('F99A15DD36528D9FE612912E10357C71', '01312513D5C357069D88F1510F911FB4', '报销单据：编号R2017081815312243053。已通过审批，等待付款。', 'CCEA58872FD18683E638E047625C17F2', '', '0', '2017-08-18 16:34:22', '2017-08-18 16:34:22');

-- ----------------------------
-- Table structure for `positions`
-- ----------------------------
DROP TABLE IF EXISTS `positions`;
CREATE TABLE `positions` (
  `pos_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `pos_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `pos_pid` char(32) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '0',
  `sort` tinyint(4) NOT NULL,
  `status` int(4) DEFAULT '1',
  `recycle` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`pos_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of positions
-- ----------------------------
INSERT INTO `positions` VALUES ('40784C72415D91F15FDDB5BDA398DB15', '总经理', '0', '1', '1', '0', '2017-08-16 17:28:01', '2017-08-16 17:28:01');
INSERT INTO `positions` VALUES ('4851D752A37548A2962EFDE6EFD98CC1', '技术总监', '40784C72415D91F15FDDB5BDA398DB15', '1', '1', '0', '2017-08-16 17:28:09', '2017-08-16 17:28:09');
INSERT INTO `positions` VALUES ('772CEE05DEF9E6F309C7C65E484EB7A2', '出纳员', '40784C72415D91F15FDDB5BDA398DB15', '2', '1', '0', '2017-08-16 17:28:19', '2017-08-16 17:29:45');

-- ----------------------------
-- Table structure for `process_audit`
-- ----------------------------
DROP TABLE IF EXISTS `process_audit`;
CREATE TABLE `process_audit` (
  `audit_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `audit_dep` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `audit_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '0',
  `audit_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `audit_process` text,
  `status` int(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`audit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `sort` tinyint(4) NOT NULL DEFAULT '0',
  `status` char(4) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `role` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', '管理员', '1', '1', '2017-08-16 16:18:10', '2017-08-16 16:21:55');

-- ----------------------------
-- Table structure for `role_node`
-- ----------------------------
DROP TABLE IF EXISTS `role_node`;
CREATE TABLE `role_node` (
  `role_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `node_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of role_node
-- ----------------------------
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', '230783C8AD5A7C655BF85DA791A261D9');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', 'D16A60E608271D0F2B7ED4E8869634F4');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', 'E61B73A181096553D6A72BE5692A9301');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', '7E8FC93D1C9E2C5109EE64D8632F5B83');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', '5ED0DAE106A20613C0A8AD990EF29822');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', '4035BCADAE2E491E68DEB27CA98CA280');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', 'D025B3FB77A126BCCB8D869F2CC57024');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', '35B31F424A4B5BAC0390544901A0E63F');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', 'EF3F342A905785F6826C2AB2723DF617');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', 'FC4229AA3C87316D9DC1C8168F51DFC5');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', '3727D90E29E32EF84DD9398A9446377D');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', '3AD1E0BFBB12C714A9E11C88760597C5');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', 'B37C2A1F3EDDCAE321F7ED9542D69F30');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', '2537185117D81CA1D2EBD0C07F986456');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', '1109421DF20C61265AA46FD9C45077A2');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', 'CBFE711E744E1FD32F231E50FF5BD23B');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', '5E5F1E23B3F66177D89B75EF993FF4A1');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', '4C2880F52A6D3DDB2211B653C4FE315A');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', 'D9F6F4A50D4AAA31E4640E6CD9326DB7');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', '22DAE2746E22DE8E746D2EB3C78798DD');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', 'B7FB4750CF90C2C8FAD6627378345ECD');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', '57C5272DFD8B98EEBDA98E03E668690B');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', '951E9717135DA00B8993489C67DBEE19');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', '862A577516209FC37DFA54D9445D5A80');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', 'C3D8D8559C55244DB8E94B3B34AAABF7');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', '933EAEE6DC76C1A3F8E0376921A9DD67');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', '6FBA7C260CE33BDE93FAB9CFEE46CBAC');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', '4A8D9ABD9EA98193F4928FC08A3DE553');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', '841D415E11B88896CED30EE9CA7CB849');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', '3FB5FCB07B8975EBD86B5A0B9BDC49D3');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', '00E9B5673E1C36509A7683DA622C3391');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', '00F7C5E21CB1879BB69806813E91CCFF');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', '5FCE5CD49C7B7E40186872FC05886379');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', '0E29A50F91B020C52DFA529D745A590B');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', 'F3F3D9FF01BC577E02859F04DD1A5DDD');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', 'DB45D9207E46B5405D0F07D1570A32DC');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', 'D6178F82870A7465F236DBF2899D8318');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', '35E530F648BE101B340B22685BD431B8');
INSERT INTO `role_node` VALUES ('A6F7FAF16C38ADF5158C763010D7A880', 'D2F6AAF86E869B19E6F7C94981100668');

-- ----------------------------
-- Table structure for `subjects`
-- ----------------------------
DROP TABLE IF EXISTS `subjects`;
CREATE TABLE `subjects` (
  `sub_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `sub_type` tinyint(1) DEFAULT '0',
  `sub_ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `sub_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `sub_pid` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `status` int(4) NOT NULL DEFAULT '1',
  `sort` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`sub_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of subjects
-- ----------------------------
INSERT INTO `subjects` VALUES ('069198AAA6240ED6006BA1A5BB9AA04E', '1', '1000.1001', '现金', '879E0B158D45AF0D07C47A53E523AC5E', '1', '0', '2017-08-16 17:02:41', '2017-08-16 17:02:41');
INSERT INTO `subjects` VALUES ('12C4D398BCBFE03095C7C85FE22EA3DD', '0', '6000', '损益', '0', '1', '0', '2017-08-16 16:59:57', '2017-08-16 16:59:57');
INSERT INTO `subjects` VALUES ('18977BD2C09A5F0021DAEAB439BA3248', '-1', '2000.2202.02', '预收账款', 'DAF633F3CF1AAC11E2C5C906A1A16E5C', '1', '0', '2017-08-16 17:05:53', '2017-08-16 17:05:53');
INSERT INTO `subjects` VALUES ('28BADA685023ABBE5994D740B9D9F114', '1', '6000.6001', '收入', '12C4D398BCBFE03095C7C85FE22EA3DD', '1', '0', '2017-08-16 17:06:34', '2017-08-16 17:06:34');
INSERT INTO `subjects` VALUES ('29736F5C5F04AC04CF365B5DEEEE80A6', '-1', '6000.6200', '支出', '12C4D398BCBFE03095C7C85FE22EA3DD', '1', '0', '2017-08-16 17:06:48', '2017-08-16 17:06:48');
INSERT INTO `subjects` VALUES ('3A1EB982CF3A97FAAFDBA3D5724FDCB3', '-1', '2000.2202.03', '应付佣金', 'DAF633F3CF1AAC11E2C5C906A1A16E5C', '1', '0', '2017-08-16 17:06:06', '2017-08-16 17:06:06');
INSERT INTO `subjects` VALUES ('3F661AB1498766BE94EFE5E8C693C575', '0', '4000', '权益类', '0', '1', '0', '2017-08-16 16:59:47', '2017-08-16 16:59:47');
INSERT INTO `subjects` VALUES ('6E8214401DCDD60E47FB1989DD8F4CDA', '-1', '6000.6200.01.01', '房租费', 'E57A922C89085C865DA6953CBA6E012E', '1', '0', '2017-08-16 17:08:14', '2017-08-16 17:08:14');
INSERT INTO `subjects` VALUES ('7946EE9089BFD172AE2360A80BFEA025', '1', '1000.1122.01', '合同应收', 'E3A933A6B15A855A1F26A634A553151A', '1', '0', '2017-08-16 17:03:58', '2017-08-16 17:03:58');
INSERT INTO `subjects` VALUES ('879E0B158D45AF0D07C47A53E523AC5E', '0', '1000', '资产', '0', '1', '0', '2017-08-16 17:13:59', '2017-08-16 17:13:59');
INSERT INTO `subjects` VALUES ('8EC6D3583612404470D631D82D4776B0', '1', '1000.1405', '仓库', '879E0B158D45AF0D07C47A53E523AC5E', '1', '0', '2017-08-16 17:03:37', '2017-08-16 17:03:37');
INSERT INTO `subjects` VALUES ('8F7E34440EF09A058535CE4F06B82FDE', '1', '1000.1221', '其他应收款', '879E0B158D45AF0D07C47A53E523AC5E', '1', '0', '2017-08-16 17:03:21', '2017-08-16 17:03:21');
INSERT INTO `subjects` VALUES ('8FE3E1ADE98DA6D7E77915FA0FDC2467', '-1', '6000.6200.02', '操作费用', '29736F5C5F04AC04CF365B5DEEEE80A6', '1', '0', '2017-08-16 17:09:09', '2017-08-16 17:09:09');
INSERT INTO `subjects` VALUES ('955587E0FD804314E4C3ED2409AD4F6A', '-1', '6000.6200.01.01.02', '办公室2105', '6E8214401DCDD60E47FB1989DD8F4CDA', '1', '0', '2017-08-16 17:08:45', '2017-08-16 17:08:45');
INSERT INTO `subjects` VALUES ('AB858DB8E3F001F383BF8B6B6928A42A', '1', '6000.6001.02', '税务代理收入', '28BADA685023ABBE5994D740B9D9F114', '1', '0', '2017-08-16 17:07:15', '2017-08-16 17:07:15');
INSERT INTO `subjects` VALUES ('AFF0C18260B11874DE72AE74AE25D05C', '-1', '2000.2241', '其他应付款', 'CA57493FAEC2B6BD59A43B15B3E86ECE', '1', '0', '2017-08-16 17:05:15', '2017-08-16 17:05:15');
INSERT INTO `subjects` VALUES ('B097F86822E61181B5D81439E95EB95B', '1', '6000.6001.03', '年检代理收入', '28BADA685023ABBE5994D740B9D9F114', '1', '0', '2017-08-16 17:07:28', '2017-08-16 17:07:28');
INSERT INTO `subjects` VALUES ('B80F1E6EB61D0C564A1F6760B03C81B7', '1', '6000.6001.01', '会计代理收入', '28BADA685023ABBE5994D740B9D9F114', '1', '0', '2017-08-16 17:07:01', '2017-08-16 17:07:01');
INSERT INTO `subjects` VALUES ('BAB4C60D3A04364D478FE5E5E41A1D1C', '1', '1000.1122.02', '预付账款', 'E3A933A6B15A855A1F26A634A553151A', '1', '0', '2017-08-16 17:04:19', '2017-08-16 17:04:19');
INSERT INTO `subjects` VALUES ('BF2CC2B48827B800C8A9DF45DFD815B1', '-1', '6000.6200.02.01', '快递费', '8FE3E1ADE98DA6D7E77915FA0FDC2467', '1', '0', '2017-08-16 17:09:23', '2017-08-16 17:09:23');
INSERT INTO `subjects` VALUES ('C604ECE713375A40A4A5364BA0D34FB1', '1', '1000.1221.01', '内部往来', '8F7E34440EF09A058535CE4F06B82FDE', '1', '0', '2017-08-16 17:04:38', '2017-08-16 17:04:38');
INSERT INTO `subjects` VALUES ('CA57493FAEC2B6BD59A43B15B3E86ECE', '0', '2000', '负债', '0', '1', '0', '2017-08-16 16:59:39', '2017-08-16 16:59:39');
INSERT INTO `subjects` VALUES ('CA9C6DBC8661326EDC13E26191024C77', '-1', '6000.6200.01.01.01', '办公室2104', '6E8214401DCDD60E47FB1989DD8F4CDA', '1', '0', '2017-08-16 17:08:30', '2017-08-16 17:08:30');
INSERT INTO `subjects` VALUES ('DAF633F3CF1AAC11E2C5C906A1A16E5C', '-1', '2000.2202', '应付账款', 'CA57493FAEC2B6BD59A43B15B3E86ECE', '1', '0', '2017-08-16 17:05:04', '2017-08-16 17:05:04');
INSERT INTO `subjects` VALUES ('DCE2994F049D91688F30CD1808FBE9F4', '1', '1000.1002', '银行', '879E0B158D45AF0D07C47A53E523AC5E', '1', '0', '2017-08-16 17:02:53', '2017-08-16 17:02:53');
INSERT INTO `subjects` VALUES ('E3A933A6B15A855A1F26A634A553151A', '1', '1000.1122', '应收账款', '879E0B158D45AF0D07C47A53E523AC5E', '1', '0', '2017-08-16 17:03:09', '2017-08-16 17:03:09');
INSERT INTO `subjects` VALUES ('E57A922C89085C865DA6953CBA6E012E', '-1', '6000.6200.01', '基础费用', '29736F5C5F04AC04CF365B5DEEEE80A6', '1', '0', '2017-08-16 17:08:00', '2017-08-16 17:08:00');
INSERT INTO `subjects` VALUES ('E638FC8D0BF74085174D00A069351304', '-1', '2000.2202.01', '合同应付', 'DAF633F3CF1AAC11E2C5C906A1A16E5C', '1', '0', '2017-08-16 17:05:31', '2017-08-16 17:05:31');

-- ----------------------------
-- Table structure for `supplier`
-- ----------------------------
DROP TABLE IF EXISTS `supplier`;
CREATE TABLE `supplier` (
  `supp_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `supp_num` char(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `supp_name` char(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `supp_status` int(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`supp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of supplier
-- ----------------------------

-- ----------------------------
-- Table structure for `sys_assembly`
-- ----------------------------
DROP TABLE IF EXISTS `sys_assembly`;
CREATE TABLE `sys_assembly` (
  `ass_id` char(32) COLLATE utf8_bin NOT NULL,
  `ass_type` varchar(50) COLLATE utf8_bin NOT NULL,
  `ass_text` varchar(50) COLLATE utf8_bin NOT NULL,
  `ass_value` varchar(50) COLLATE utf8_bin NOT NULL,
  `created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ass_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of sys_assembly
-- ----------------------------
INSERT INTO `sys_assembly` VALUES ('32EC256B4CB7F91EEABB7669225882D6', 'contract_type', '会计收入', '32EC256B4CB7F91EEABB7669225882D6', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `sys_assembly` VALUES ('3B6AB1B587733F9AFA7D37C0AEADCE7F', 'contract_type', '其他收入', '3B6AB1B587733F9AFA7D37C0AEADCE7F', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- ----------------------------
-- Table structure for `sys_config`
-- ----------------------------
DROP TABLE IF EXISTS `sys_config`;
CREATE TABLE `sys_config` (
  `sys_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `sys_class` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `sys_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `sys_value` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `sys_remark` text,
  PRIMARY KEY (`sys_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sys_config
-- ----------------------------
INSERT INTO `sys_config` VALUES ('C175246CAD2A902293FE430348E935F1', 'budget', 'subBudget', '12C4D398BCBFE03095C7C85FE22EA3DD', '预算科目ID');
INSERT INTO `sys_config` VALUES ('F9886ACE0B05333039CCE31D63B0C52C', 'reimburse', 'userCashier', 'CCEA58872FD18683E638E047625C17F2', '费用报销核销出纳id');

-- ----------------------------
-- Table structure for `sys_status`
-- ----------------------------
DROP TABLE IF EXISTS `sys_status`;
CREATE TABLE `sys_status` (
  `id` char(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `status` char(4) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `text` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `html` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sys_status
-- ----------------------------
INSERT INTO `sys_status` VALUES ('1', '-1', '常规', '已删除', '<span style=\"color:red;\">已删除</span>');
INSERT INTO `sys_status` VALUES ('10', '1009', '审批流程', '审批中', '<span style=\"color:orange;\">审批中</span>');
INSERT INTO `sys_status` VALUES ('11', '202', '费用报销', '编辑单据', '<span style=\"color:green;\">编辑中</span>');
INSERT INTO `sys_status` VALUES ('12', '203', '费用报销', '出纳付款', '<span style=\"color:orange;\">出纳付款</span>');
INSERT INTO `sys_status` VALUES ('13', '204', '费用报销', '确认收款', '<span style=\"color:orange;\">确认收款</span>');
INSERT INTO `sys_status` VALUES ('14', '200', '费用报销', '拒绝付款', '<span style=\"color:red;\">拒绝付款</span>');
INSERT INTO `sys_status` VALUES ('15', '201', '费用报销', '完结', '<span>完结</span>');
INSERT INTO `sys_status` VALUES ('2', '1', '常规', '使用中', '<span>使用中</span>');
INSERT INTO `sys_status` VALUES ('3', '0', '常规', '已停用', '<span style=\"color:red;\">已停用</span>');
INSERT INTO `sys_status` VALUES ('5', '102', '预算', '更新预算项', '<span style=\"color:green;\">更新预算项</span>');
INSERT INTO `sys_status` VALUES ('6', '1000', '审批流程', '未审批', '<span>未审批</span>');
INSERT INTO `sys_status` VALUES ('7', '1001', '审批流程', '已审批', '<span>已审批</span>');
INSERT INTO `sys_status` VALUES ('8', '1002', '审批流程', '批准', '<span style=\"color:green;\">批准</span>');
INSERT INTO `sys_status` VALUES ('9', '1003', '审批流程', '不批准', '<span style=\"color:red;\">不批准</span>');

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `user_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `user_email` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `user_img` text CHARACTER SET utf8 NOT NULL,
  `password` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `role_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `supper_admin` tinyint(1) NOT NULL DEFAULT '0',
  `last_login` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `status` int(4) NOT NULL,
  `recycle` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('105A70A981B6032B0EF41101D335EBF6', '098765', 'dwqjioq@sh.net', 'resources/views/template/assets/avatars/user.jpg', 'e10adc3949ba59abbe56e057f20f883e', 'A6F7FAF16C38ADF5158C763010D7A880', '0', '2017-08-17 10:20:52', '1', '0', '2017-08-17 10:19:20', '2017-08-17 10:20:52');
INSERT INTO `users` VALUES ('8454859EDC79BCD6B5250DF817FF10EA', '超级管理员', 'admin@sh.net', 'resources/views/template/assets/avatars/user.jpg', '4297f44b13955235245b2497399d7a93', 'A6F7FAF16C', '1', '2017-08-21 14:14:56', '1', '0', '2016-05-25 05:56:33', '2017-08-21 14:14:56');
INSERT INTO `users` VALUES ('CCEA58872FD18683E638E047625C17F2', '总经理user', 'test@sh.net', 'resources/views/template/assets/avatars/user.jpg', 'e10adc3949ba59abbe56e057f20f883e', 'A6F7FAF16C38ADF5158C763010D7A880', '0', '2017-08-17 10:20:57', '1', '0', '2016-11-01 15:07:59', '2017-08-17 10:20:57');

-- ----------------------------
-- Table structure for `users_base`
-- ----------------------------
DROP TABLE IF EXISTS `users_base`;
CREATE TABLE `users_base` (
  `user_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `department` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '0',
  `positions` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users_base
-- ----------------------------
INSERT INTO `users_base` VALUES ('105A70A981B6032B0EF41101D335EBF6', 'FD138675B855A703350A5E344DDB04CD', '40784C72415D91F15FDDB5BDA398DB15', '2017-08-17 10:20:52', '2017-08-17 10:20:52');
INSERT INTO `users_base` VALUES ('8454859EDC79BCD6B5250DF817FF10EA', 'FD138675B855A703350A5E344DDB04CD', '40784C72415D91F15FDDB5BDA398DB15', '2017-08-17 14:37:22', '2017-08-17 14:37:22');
INSERT INTO `users_base` VALUES ('CCEA58872FD18683E638E047625C17F2', 'B65D22597684E1DE7F70E7F7344BF048', '772CEE05DEF9E6F309C7C65E484EB7A2', '2017-08-17 10:20:57', '2017-08-17 10:20:57');

-- ----------------------------
-- Table structure for `users_info`
-- ----------------------------
DROP TABLE IF EXISTS `users_info`;
CREATE TABLE `users_info` (
  `user_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users_info
-- ----------------------------
INSERT INTO `users_info` VALUES ('105A70A981B6032B0EF41101D335EBF6', '2017-08-17 10:19:21', '2017-08-17 10:19:21');
INSERT INTO `users_info` VALUES ('8454859EDC79BCD6B5250DF817FF10EA', '2017-08-16 13:51:14', '2017-08-16 13:51:14');
INSERT INTO `users_info` VALUES ('CCEA58872FD18683E638E047625C17F2', '2017-08-16 13:51:17', '2017-08-16 13:51:17');
