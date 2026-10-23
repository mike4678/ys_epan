/*
 Navicat Premium Data Transfer

 Source Server         : 192.168.2.252
 Source Server Type    : MySQL
 Source Server Version : 80012
 Source Host           : 192.168.2.252:3306
 Source Schema         : sourcepan

 Target Server Type    : MySQL
 Target Server Version : 80012
 File Encoding         : 65001

 Date: 23/10/2026 15:25:57
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for epan_adminsession
-- ----------------------------
DROP TABLE IF EXISTS `epan_adminsession`;
CREATE TABLE `epan_adminsession`  (
  `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `login_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `hashcode` char(9) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ip` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_adminsession
-- ----------------------------

-- ----------------------------
-- Table structure for epan_advertisements
-- ----------------------------
DROP TABLE IF EXISTS `epan_advertisements`;
CREATE TABLE `epan_advertisements`  (
  `advid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `adv_type` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `adv_position` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `params` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `code` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `show_order` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `starttime` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `endtime` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_hidden` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`advid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_advertisements
-- ----------------------------

-- ----------------------------
-- Table structure for epan_announces
-- ----------------------------
DROP TABLE IF EXISTS `epan_announces`;
CREATE TABLE `epan_announces`  (
  `annid` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `subject` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `show_order` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `is_hidden` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `is_expand` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `in_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`annid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_announces
-- ----------------------------

-- ----------------------------
-- Table structure for epan_buddys
-- ----------------------------
DROP TABLE IF EXISTS `epan_buddys`;
CREATE TABLE `epan_buddys`  (
  `bdid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `touserid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `in_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`bdid`) USING BTREE,
  INDEX `userid`(`userid`) USING BTREE,
  INDEX `touserid`(`touserid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of epan_buddys
-- ----------------------------

-- ----------------------------
-- Table structure for epan_categories
-- ----------------------------
DROP TABLE IF EXISTS `epan_categories`;
CREATE TABLE `epan_categories`  (
  `cate_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `cate_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `cate_size` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `show_order` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `is_hidden` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`cate_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_categories
-- ----------------------------

-- ----------------------------
-- Table structure for epan_comments
-- ----------------------------
DROP TABLE IF EXISTS `epan_comments`;
CREATE TABLE `epan_comments`  (
  `cmt_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `file_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `file_key` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `userid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_checked` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `in_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `ip` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`cmt_id`) USING BTREE,
  INDEX `userid`(`userid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_comments
-- ----------------------------

-- ----------------------------
-- Table structure for epan_cp_shortcut
-- ----------------------------
DROP TABLE IF EXISTS `epan_cp_shortcut`;
CREATE TABLE `epan_cp_shortcut`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_cp_shortcut
-- ----------------------------
INSERT INTO `epan_cp_shortcut` VALUES (1, 'item=users&menu=user&action=index', '用户列表');
INSERT INTO `epan_cp_shortcut` VALUES (2, 'item=templates&menu=lang_tpl', '模板名称');
INSERT INTO `epan_cp_shortcut` VALUES (3, 'item=database&menu=tool&action=optimize', '优化数据库');

-- ----------------------------
-- Table structure for epan_datacalls
-- ----------------------------
DROP TABLE IF EXISTS `epan_datacalls`;
CREATE TABLE `epan_datacalls`  (
  `dcid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `c_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `c_value` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`dcid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_datacalls
-- ----------------------------

-- ----------------------------
-- Table structure for epan_disk2user
-- ----------------------------
DROP TABLE IF EXISTS `epan_disk2user`;
CREATE TABLE `epan_disk2user`  (
  `duid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `disk_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `starttime` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `endtime` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`duid`) USING BTREE,
  INDEX `userid`(`userid`) USING BTREE,
  INDEX `diskid`(`disk_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of epan_disk2user
-- ----------------------------

-- ----------------------------
-- Table structure for epan_disks
-- ----------------------------
DROP TABLE IF EXISTS `epan_disks`;
CREATE TABLE `epan_disks`  (
  `disk_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `logo` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `space` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `price` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `expire` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `show_order` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `is_hidden` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`disk_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_disks
-- ----------------------------

-- ----------------------------
-- Table structure for epan_download
-- ----------------------------
DROP TABLE IF EXISTS `epan_download`;
CREATE TABLE `epan_download`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `downtime` int(11) NOT NULL,
  `ip` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `wenjian` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `dx` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_download
-- ----------------------------

-- ----------------------------
-- Table structure for epan_extracts
-- ----------------------------
DROP TABLE IF EXISTS `epan_extracts`;
CREATE TABLE `epan_extracts`  (
  `extract_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `extract_code` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `extract_file_ids` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `extract_total` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `extract_count` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `extract_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `extract_type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `extract_locked` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `in_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`extract_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_extracts
-- ----------------------------

-- ----------------------------
-- Table structure for epan_file2tag
-- ----------------------------
DROP TABLE IF EXISTS `epan_file2tag`;
CREATE TABLE `epan_file2tag`  (
  `ftid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `file_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`ftid`) USING BTREE,
  INDEX `tag_name`(`tag_name`) USING BTREE,
  INDEX `file_id`(`file_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_file2tag
-- ----------------------------

-- ----------------------------
-- Table structure for epan_files
-- ----------------------------
DROP TABLE IF EXISTS `epan_files`;
CREATE TABLE `epan_files`  (
  `file_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `file_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `file_key` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `file_short_url` char(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `file_extension` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `is_image` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `file_mime` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `file_description` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `file_store_path` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `file_real_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `file_md5` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `server_oid` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `store_old` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `file_size` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `thumb_size` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `file_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `file_views` int(10) UNSIGNED NULL DEFAULT 0,
  `file_downs` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `file_last_view` int(10) UNSIGNED NULL DEFAULT 0,
  `file_credit` tinyint(2) UNSIGNED NOT NULL DEFAULT 0,
  `report_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `in_share` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `space_pos` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `good_count` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `bad_count` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `is_locked` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `is_checked` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `is_public` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `userid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `folder_id` bigint(20) NOT NULL DEFAULT 0,
  `cate_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `subcate_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `in_recycle` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `ip` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`file_id`) USING BTREE,
  INDEX `userid`(`userid`) USING BTREE,
  INDEX `folder_id`(`folder_id`) USING BTREE,
  INDEX `server_id`(`server_oid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 25 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_files
-- ----------------------------

-- ----------------------------
-- Table structure for epan_folders
-- ----------------------------
DROP TABLE IF EXISTS `epan_folders`;
CREATE TABLE `epan_folders`  (
  `folder_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) NOT NULL DEFAULT 0,
  `folder_node` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `folder_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `folder_description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `in_recycle` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `in_share` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `folder_order` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `folder_size` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `userid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `in_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `password` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `fileorder` varchar(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '011000',
  PRIMARY KEY (`folder_id`) USING BTREE,
  INDEX `userid`(`userid`) USING BTREE,
  INDEX `parent_id`(`parent_id`) USING BTREE,
  INDEX `folder_node`(`folder_node`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 23 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_folders
-- ----------------------------


-- ----------------------------
-- Table structure for epan_gallery
-- ----------------------------
DROP TABLE IF EXISTS `epan_gallery`;
CREATE TABLE `epan_gallery`  (
  `gal_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `gal_path` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `gal_title` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `go_url` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `gal_target` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `show_order` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `is_hidden` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`gal_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_gallery
-- ----------------------------

-- ----------------------------
-- Table structure for epan_groups
-- ----------------------------
DROP TABLE IF EXISTS `epan_groups`;
CREATE TABLE `epan_groups`  (
  `gid` tinyint(3) UNSIGNED NOT NULL,
  `group_type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `group_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `max_messages` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `max_flow_down` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `max_flow_view` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `max_storage` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `group_file_types` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `max_filesize` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `max_folders` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `max_files` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `can_share` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `secs_loading` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `server_ids` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `jg_yy` int(11) NULL DEFAULT 0,
  `jg_bn` int(11) NULL DEFAULT 0,
  `jg_yn` int(11) NULL DEFAULT 0,
  `jg_ln` int(11) NULL DEFAULT 0,
  `jb` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`gid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_groups
-- ----------------------------
INSERT INTO `epan_groups` VALUES (1, 1, '系统管理员', 0, '0', '0', '2222M', 'jpg,jpeg,bmp,png,gif,zip,rar,txt,doc,dll,db,htm,wav,fla,wmv,swf,mp3,mp4,rmvb,mov,avi,exe,torrent,ani,cur,ico,js,css,chm,ppt,pdf,psd,xls,xml,xsl', '500m', 100, 100, 1, 5, '', 0, 0, 0, 0, 0);
INSERT INTO `epan_groups` VALUES (2, 1, '标准型', 0, '', '', '2000M', 'jpg,jpeg,bmp,png,gif,zip,rar,txt,doc,dll,db,htm,wav,fla,wmv,swf,mp3,mp4,rmvb,mov,avi,exe,torrent,ani,cur,ico,js,css,chm,ppt,pdf,psd,xls,xml,xsl', '', 100, 100, 1, 0, '0', 2, 12, 24, 30, 2);
INSERT INTO `epan_groups` VALUES (3, 1, '个人型', 0, '0', '0', '1000M', 'jpg,jpeg,bmp,png,gif,zip,rar,txt,doc,dll,db,htm,wav,fla,wmv,swf,mp3,mp4,rmvb,mov,avi,exe,torrent,ani,cur,ico,js,css,chm,ppt,pdf,psd,xls,xml,xsl', '0', 100, 100, 1, 0, '0', 1, 6, 10, 20, 1);
INSERT INTO `epan_groups` VALUES (4, 1, '免费型空间', 0, '0', '0', '300M', 'jpg,jpeg,bmp,png,gif,zip,rar,txt,doc,dll,db,htm,wav,fla,wmv,swf,mp3,mp4,rmvb,mov,avi,exe,torrent,ani,cur,ico,js,css,chm,ppt,pdf,psd,xls,xml,xsl', '0', 100, 100, 1, 0, '0', 0, 0, 0, 0, 0);

-- ----------------------------
-- Table structure for epan_invitelog
-- ----------------------------
DROP TABLE IF EXISTS `epan_invitelog`;
CREATE TABLE `epan_invitelog`  (
  `bdid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `touserid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `in_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`bdid`) USING BTREE,
  INDEX `userid`(`userid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of epan_invitelog
-- ----------------------------

-- ----------------------------
-- Table structure for epan_langs
-- ----------------------------
DROP TABLE IF EXISTS `epan_langs`;
CREATE TABLE `epan_langs`  (
  `lang_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `actived` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`lang_name`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_langs
-- ----------------------------

-- ----------------------------
-- Table structure for epan_link
-- ----------------------------
DROP TABLE IF EXISTS `epan_link`;
CREATE TABLE `epan_link`  (
  `link_id` int(11) NOT NULL AUTO_INCREMENT,
  `link_title` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `link_address` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `username` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`link_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_link
-- ----------------------------


-- ----------------------------
-- Table structure for epan_links
-- ----------------------------
DROP TABLE IF EXISTS `epan_links`;
CREATE TABLE `epan_links`  (
  `linkid` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `url` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `logo` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `show_order` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `is_hidden` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`linkid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_links
-- ----------------------------

-- ----------------------------
-- Table structure for epan_log
-- ----------------------------
DROP TABLE IF EXISTS `epan_log`;
CREATE TABLE `epan_log`  (
  `log_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `log_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `log_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `log_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `log_uri` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `log_method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `log_cookie` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `log_detail` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `idx_type`(`log_type`) USING BTREE,
  INDEX `idx_time`(`log_time`) USING BTREE,
  INDEX `idx_ip`(`log_ip`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_log
-- ----------------------------

-- ----------------------------
-- Table structure for epan_lyb
-- ----------------------------
DROP TABLE IF EXISTS `epan_lyb`;
CREATE TABLE `epan_lyb`  (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NULL DEFAULT NULL,
  `ip` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `name` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `data` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `face` int(11) NULL DEFAULT NULL,
  `userspace` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`uid`) USING BTREE,
  INDEX `userspace`(`userspace`(32)) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_lyb
-- ----------------------------


-- ----------------------------
-- Table structure for epan_menu
-- ----------------------------
DROP TABLE IF EXISTS `epan_menu`;
CREATE TABLE `epan_menu`  (
  `uid` int(11) NULL DEFAULT NULL,
  `sid` int(11) NULL DEFAULT NULL,
  `table` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `name` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `url` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_menu
-- ----------------------------
INSERT INTO `epan_menu` VALUES (1, 1, '主要操作', '空间状态', 'ht/index.php');
INSERT INTO `epan_menu` VALUES (1, 2, NULL, '空间升级', 'ht/kjsj.php');
INSERT INTO `epan_menu` VALUES (1, 3, NULL, '空间延期', 'ht/kjyq.php');
INSERT INTO `epan_menu` VALUES (1, 4, NULL, '空间到期提醒', 'javascript:error()');
INSERT INTO `epan_menu` VALUES (2, 1, '账户管理', '账户充值', 'ht/yfk/');
INSERT INTO `epan_menu` VALUES (2, 2, NULL, '充值记录', 'ht/yfk/jlcz.php');
INSERT INTO `epan_menu` VALUES (2, 3, NULL, '消费记录', 'ht/yfk/jlxf.php');
INSERT INTO `epan_menu` VALUES (3, 1, '空间设置', '空间标题', 'ht/kjsd/cg_bt.php');
INSERT INTO `epan_menu` VALUES (3, 2, NULL, '空间语言', 'ht/kjsd/cg_yy.php');
INSERT INTO `epan_menu` VALUES (3, 3, NULL, '首页寄语', 'ht/kjsd/cg_jy.php');
INSERT INTO `epan_menu` VALUES (3, 4, NULL, '访客权限', 'ht/kjsd/qx.php');
INSERT INTO `epan_menu` VALUES (3, 5, NULL, '首页链接', 'ht/kjsd/lj.php');
INSERT INTO `epan_menu` VALUES (3, 6, NULL, '目录排序方式', 'ht/kjsd/mlpx.php');
INSERT INTO `epan_menu` VALUES (3, 7, NULL, '空间风格', 'ht/kjsd/fg.php');
INSERT INTO `epan_menu` VALUES (3, 8, NULL, '设置个人资料', 'ht/zlwh/xgzl.php');
INSERT INTO `epan_menu` VALUES (4, 1, '空间安全', '设置登录密码', 'ht/zlwh/dlmm.php');
INSERT INTO `epan_menu` VALUES (4, 2, NULL, '绑定手机', 'javascript:error()');
INSERT INTO `epan_menu` VALUES (4, 3, NULL, '修改管理密码', 'ht/zlwh/xgmm.php');
INSERT INTO `epan_menu` VALUES (4, 4, NULL, '修改邮箱', 'ht/zlwh/xgem.php');
INSERT INTO `epan_menu` VALUES (4, 5, NULL, '设置密保', 'ht/zlwh/szmb.php');
INSERT INTO `epan_menu` VALUES (4, 6, NULL, '修改密保', 'ht/zlwh/xgmb.php');
INSERT INTO `epan_menu` VALUES (4, 7, NULL, '忘记密码', 'ht/qt/zhmm.php');
INSERT INTO `epan_menu` VALUES (5, 1, '其他', '加密目录密码查询', 'ht/qt/ml.php');
INSERT INTO `epan_menu` VALUES (5, 2, NULL, '留言、文件管理', 'ht/qt/ly.php');
INSERT INTO `epan_menu` VALUES (5, 3, NULL, '空间降级', 'ht/qt/kjjj.php');
INSERT INTO `epan_menu` VALUES (5, 4, NULL, '恢复误删除文件', 'javascript:error()');

-- ----------------------------
-- Table structure for epan_messages
-- ----------------------------
DROP TABLE IF EXISTS `epan_messages`;
CREATE TABLE `epan_messages`  (
  `msgid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `reply_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `userid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `touserid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `is_new` tinyint(1) NOT NULL,
  `in_sendbox` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `is_del` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `is_reply` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `in_time` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`msgid`) USING BTREE,
  INDEX `touserid`(`touserid`) USING BTREE,
  INDEX `userid`(`userid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_messages
-- ----------------------------

-- ----------------------------
-- Table structure for epan_navigations
-- ----------------------------
DROP TABLE IF EXISTS `epan_navigations`;
CREATE TABLE `epan_navigations`  (
  `navid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `text` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `href` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `target` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `position` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `show_order` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`navid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_navigations
-- ----------------------------

-- ----------------------------
-- Table structure for epan_oline
-- ----------------------------
DROP TABLE IF EXISTS `epan_oline`;
CREATE TABLE `epan_oline`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `ip` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `intime` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 60 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_oline
-- ----------------------------

-- ----------------------------
-- Table structure for epan_onlineuser
-- ----------------------------
DROP TABLE IF EXISTS `epan_onlineuser`;
CREATE TABLE `epan_onlineuser`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `dtstamp` int(11) NULL DEFAULT NULL,
  `username` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 10 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_onlineuser
-- ----------------------------


-- ----------------------------
-- Table structure for epan_orders
-- ----------------------------
DROP TABLE IF EXISTS `epan_orders`;
CREATE TABLE `epan_orders`  (
  `order_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pay_method` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `userid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `order_number` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `total_fee` float UNSIGNED NOT NULL DEFAULT 0,
  `pay_status` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `retcode` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `in_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `ip` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`order_id`) USING BTREE,
  INDEX `pay_method`(`pay_method`) USING BTREE,
  INDEX `userid`(`userid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 11 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_orders
-- ----------------------------
INSERT INTO `epan_orders` VALUES (15, 'wx', 10, '20261023073105822347', 111, 'WAIT', 0, 1792711865, '127.0.0.1');

-- ----------------------------
-- Table structure for epan_plugins
-- ----------------------------
DROP TABLE IF EXISTS `epan_plugins`;
CREATE TABLE `epan_plugins`  (
  `plugin_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `actived` tinyint(1) NOT NULL DEFAULT 0,
  `action_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `in_shortcut` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`plugin_name`) USING BTREE,
  INDEX `in_shortcut`(`in_shortcut`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_plugins
-- ----------------------------
INSERT INTO `epan_plugins` VALUES ('api', 0, 0, 0);

-- ----------------------------
-- Table structure for epan_product
-- ----------------------------
DROP TABLE IF EXISTS `epan_product`;
CREATE TABLE `epan_product`  (
  `name` longtext CHARACTER SET gbk COLLATE gbk_chinese_ci NULL,
  `data` int(11) NULL DEFAULT NULL,
  `filemax` int(11) NULL DEFAULT NULL,
  `path` int(11) NULL DEFAULT NULL,
  `downnum` int(11) NULL DEFAULT NULL,
  `pay` int(11) NULL DEFAULT NULL,
  `status` int(11) NULL DEFAULT NULL,
  `code` int(11) NULL DEFAULT NULL,
  `price` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '价格(元/年)',
  `valid_days` int(11) NOT NULL DEFAULT 365 COMMENT '空间有效期(天)'
) ENGINE = InnoDB CHARACTER SET = gbk COLLATE = gbk_chinese_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of epan_product
-- ----------------------------
INSERT INTO `epan_product` VALUES ('免费型', 100, 10, 5, 100, 0, 0, 0, 0.00, 365);
INSERT INTO `epan_product` VALUES ('个人型', 400, 60, 60, 100, 1, 0, 1, 0.00, 365);
INSERT INTO `epan_product` VALUES ('标准型', 800, 100, 100, 500, 1, 0, 2, 0.00, 365);
INSERT INTO `epan_product` VALUES ('增强型', 1000, 150, 150, 800, 1, 0, 3, 0.00, 365);
INSERT INTO `epan_product` VALUES ('企业I型', 5000, 400, 200, 3600, 1, 0, 4, 0.00, 365);
INSERT INTO `epan_product` VALUES ('企业II型', 10000, 700, 360, 6800, 1, 0, 5, 0.00, 365);
INSERT INTO `epan_product` VALUES ('系统管理员', 99999, 99999, 99999, 99999, 1, 1, -1, 0.00, 365);

-- ----------------------------
-- Table structure for epan_replys
-- ----------------------------
DROP TABLE IF EXISTS `epan_replys`;
CREATE TABLE `epan_replys`  (
  `rpid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tid` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `userid` int(10) NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `is_best` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `in_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `ip` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`rpid`) USING BTREE,
  INDEX `tid`(`tid`) USING BTREE,
  INDEX `userid`(`userid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_replys
-- ----------------------------

-- ----------------------------
-- Table structure for epan_reports
-- ----------------------------
DROP TABLE IF EXISTS `epan_reports`;
CREATE TABLE `epan_reports`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `file_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `file_key` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `userid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_new` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `in_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `ip` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `userid`(`userid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_reports
-- ----------------------------

-- ----------------------------
-- Table structure for epan_reqcnt
-- ----------------------------
DROP TABLE IF EXISTS `epan_reqcnt`;
CREATE TABLE `epan_reqcnt`  (
  `ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `cnt` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `t0` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`ip`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_reqcnt
-- ----------------------------

-- ----------------------------
-- Table structure for epan_search_index
-- ----------------------------
DROP TABLE IF EXISTS `epan_search_index`;
CREATE TABLE `epan_search_index`  (
  `searchid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `scope` varchar(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `word` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `search_time` int(10) NOT NULL DEFAULT 0,
  `total_count` smallint(6) NOT NULL DEFAULT 0,
  `file_ids` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ip` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`searchid`) USING BTREE,
  INDEX `userid`(`userid`) USING BTREE,
  INDEX `scope`(`scope`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_search_index
-- ----------------------------

-- ----------------------------
-- Table structure for epan_servers
-- ----------------------------
DROP TABLE IF EXISTS `epan_servers`;
CREATE TABLE `epan_servers`  (
  `server_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `server_oid` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `server_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `server_host` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `server_closed` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `server_store_path` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `server_key` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ftp_host` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ftp_port` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ftp_user` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ftp_pass` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ftp_ssl` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `ftp_pasv` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `ftp_path` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ftp_closed` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`server_id`) USING BTREE,
  INDEX `server_oid`(`server_oid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_servers
-- ----------------------------

-- ----------------------------
-- Table structure for epan_settings
-- ----------------------------
DROP TABLE IF EXISTS `epan_settings`;
CREATE TABLE `epan_settings`  (
  `vars` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`vars`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_settings
-- ----------------------------
INSERT INTO `epan_settings` VALUES ('site_title', 'Source 网盘');
INSERT INTO `epan_settings` VALUES ('phpdisk_url', 'http://pan.csource.com.cn:81/');
INSERT INTO `epan_settings` VALUES ('file_path', 'up');
INSERT INTO `epan_settings` VALUES ('max_file_size', '0');
INSERT INTO `epan_settings` VALUES ('perpage', '20');
INSERT INTO `epan_settings` VALUES ('gzipcompress', '1');
INSERT INTO `epan_settings` VALUES ('allow_access', '1');
INSERT INTO `epan_settings` VALUES ('allow_register', '1');
INSERT INTO `epan_settings` VALUES ('close_access_reason', '系统维护中...');
INSERT INTO `epan_settings` VALUES ('close_register_reason', '系统暂时关闭注册...');
INSERT INTO `epan_settings` VALUES ('meta_keywords', '_网络存储系统');
INSERT INTO `epan_settings` VALUES ('meta_description', '_网络存储系统');
INSERT INTO `epan_settings` VALUES ('site_stat', '');
INSERT INTO `epan_settings` VALUES ('miibeian', '');
INSERT INTO `epan_settings` VALUES ('contact_us', '');
INSERT INTO `epan_settings` VALUES ('credit_convert', '1');
INSERT INTO `epan_settings` VALUES ('user_active', '0');
INSERT INTO `epan_settings` VALUES ('debug_info', '1');
INSERT INTO `epan_settings` VALUES ('open_demo_login', '0');
INSERT INTO `epan_settings` VALUES ('open_share', '1');
INSERT INTO `epan_settings` VALUES ('open_link', '1');
INSERT INTO `epan_settings` VALUES ('open_link_domain', '');
INSERT INTO `epan_settings` VALUES ('report_word', 'bat,sh');
INSERT INTO `epan_settings` VALUES ('encrypt_key', '43U7UIFfZ4zb');
INSERT INTO `epan_settings` VALUES ('register_verycode', '0');
INSERT INTO `epan_settings` VALUES ('login_verycode', '0');
INSERT INTO `epan_settings` VALUES ('open_seo', '1');
INSERT INTO `epan_settings` VALUES ('version_info', '1');
INSERT INTO `epan_settings` VALUES ('email_address', '');
INSERT INTO `epan_settings` VALUES ('email_pwd', 'mima');
INSERT INTO `epan_settings` VALUES ('email_user', '');
INSERT INTO `epan_settings` VALUES ('email_smtp', '');
INSERT INTO `epan_settings` VALUES ('email_port', '25');
INSERT INTO `epan_settings` VALUES ('email_ssl', '0');
INSERT INTO `epan_settings` VALUES ('open_rewrite', '0');
INSERT INTO `epan_settings` VALUES ('credit_invite', '6');
INSERT INTO `epan_settings` VALUES ('credit_reg', '10');
INSERT INTO `epan_settings` VALUES ('credit_login', '2');
INSERT INTO `epan_settings` VALUES ('credit_msg', '1');
INSERT INTO `epan_settings` VALUES ('credit_upload', '3');
INSERT INTO `epan_settings` VALUES ('credit_down', '2');
INSERT INTO `epan_settings` VALUES ('credit_open', '0');
INSERT INTO `epan_settings` VALUES ('login_down_file', '0');
INSERT INTO `epan_settings` VALUES ('online_demo', '0');
INSERT INTO `epan_settings` VALUES ('credit_down_my', '1');
INSERT INTO `epan_settings` VALUES ('filter_extension', 'asp,php,asa,aspx,ascx,dtd,xsd,xsl,xslt,as,wml,java,vtm,vtml,jst,asr,php,php3,php4,php5,vb,vbs,jsf,jsp,pl,cgi,js,html,htm,xhtml,xml,css,shtm,cfm,cfml,shtml,bat,sh');
INSERT INTO `epan_settings` VALUES ('open_thunder', '0');
INSERT INTO `epan_settings` VALUES ('thunder_pid', '');
INSERT INTO `epan_settings` VALUES ('open_flashget', '0');
INSERT INTO `epan_settings` VALUES ('flashget_uid', '');
INSERT INTO `epan_settings` VALUES ('connect_uc', '0');
INSERT INTO `epan_settings` VALUES ('thumb_width', '120');
INSERT INTO `epan_settings` VALUES ('thumb_height', '90');
INSERT INTO `epan_settings` VALUES ('check_comment', '1');
INSERT INTO `epan_settings` VALUES ('secs_loading', '10');
INSERT INTO `epan_settings` VALUES ('true_link', '0');
INSERT INTO `epan_settings` VALUES ('true_link_extension', '');
INSERT INTO `epan_settings` VALUES ('open_verycode', '0');
INSERT INTO `epan_settings` VALUES ('verycode_type', '2');
INSERT INTO `epan_settings` VALUES ('forget_verycode', '0');
INSERT INTO `epan_settings` VALUES ('open_tag', '1');
INSERT INTO `epan_settings` VALUES ('open_comment', '1');
INSERT INTO `epan_settings` VALUES ('open_vote', '1');
INSERT INTO `epan_settings` VALUES ('open_file_url', '1');
INSERT INTO `epan_settings` VALUES ('open_report', '1');
INSERT INTO `epan_settings` VALUES ('viewfile_keyword', '');
INSERT INTO `epan_settings` VALUES ('powered_info', '0');
INSERT INTO `epan_settings` VALUES ('version', '1.0.0');
INSERT INTO `epan_settings` VALUES ('store_true_filename', '0');
INSERT INTO `epan_settings` VALUES ('open_plugins_cp', '1');
INSERT INTO `epan_settings` VALUES ('open_credit_convert', '0');
INSERT INTO `epan_settings` VALUES ('open_multi_server', '0');
INSERT INTO `epan_settings` VALUES ('show_index', '1');
INSERT INTO `epan_settings` VALUES ('show_public', '1');
INSERT INTO `epan_settings` VALUES ('open_email', '1');
INSERT INTO `epan_settings` VALUES ('open_plugins_last', '1');
INSERT INTO `epan_settings` VALUES ('server_status', '0');
INSERT INTO `epan_settings` VALUES ('open_file_preview', '0');
INSERT INTO `epan_settings` VALUES ('open_file_extract_code', '0');
INSERT INTO `epan_settings` VALUES ('open_file_outlink', '0');
INSERT INTO `epan_settings` VALUES ('open_file_shorturl', '0');
INSERT INTO `epan_settings` VALUES ('downfile_directly', '0');
INSERT INTO `epan_settings` VALUES ('open_gallery_index', '1');
INSERT INTO `epan_settings` VALUES ('gallery_type', '1');
INSERT INTO `epan_settings` VALUES ('gallery_size_width', '650');
INSERT INTO `epan_settings` VALUES ('gallery_size_height', '200');
INSERT INTO `epan_settings` VALUES ('open_autoupdate', '0');
INSERT INTO `epan_settings` VALUES ('downfile_stat_time', '3600');
INSERT INTO `epan_settings` VALUES ('create_default_folder', '');
INSERT INTO `epan_settings` VALUES ('default_amount', '10');
INSERT INTO `epan_settings` VALUES ('show_relative_file', '0');
INSERT INTO `epan_settings` VALUES ('wealth_reg', '0');
INSERT INTO `epan_settings` VALUES ('wealth_login', '0');
INSERT INTO `epan_settings` VALUES ('wealth_invite', '0');
INSERT INTO `epan_settings` VALUES ('wealth_msg', '0');
INSERT INTO `epan_settings` VALUES ('wealth_upload', '0');
INSERT INTO `epan_settings` VALUES ('wealth_down', '0');
INSERT INTO `epan_settings` VALUES ('wealth_down_my', '0');
INSERT INTO `epan_settings` VALUES ('exp_reg', '20');
INSERT INTO `epan_settings` VALUES ('exp_login', '2');
INSERT INTO `epan_settings` VALUES ('exp_invite', '6');
INSERT INTO `epan_settings` VALUES ('exp_msg', '1');
INSERT INTO `epan_settings` VALUES ('exp_upload', '3');
INSERT INTO `epan_settings` VALUES ('exp_down', '2');
INSERT INTO `epan_settings` VALUES ('exp_down_my', '1');
INSERT INTO `epan_settings` VALUES ('show_stat_index', '0');
INSERT INTO `epan_settings` VALUES ('invite_register_encode', '0');
INSERT INTO `epan_settings` VALUES ('share_tool', '');
INSERT INTO `epan_settings` VALUES ('file_to_public_checked', '0');
INSERT INTO `epan_settings` VALUES ('secs_for_user', '0');
INSERT INTO `epan_settings` VALUES ('global_secs_loading', '');
INSERT INTO `epan_settings` VALUES ('show_hot_file_right', '0');
INSERT INTO `epan_settings` VALUES ('all_file_share', '0');
INSERT INTO `epan_settings` VALUES ('footer', 'Copyright© 2015-2026 Source Inc.');
INSERT INTO `epan_settings` VALUES ('pay_alipay', '0');
INSERT INTO `epan_settings` VALUES ('server_infomaction', 'The system is under maintenance. Please visit later.<br>');
INSERT INTO `epan_settings` VALUES ('encrypted', 'admin');
INSERT INTO `epan_settings` VALUES ('bugreport', '2');
INSERT INTO `epan_settings` VALUES ('khlb', '上海致逸建筑设计有限公司;\n上海日清景观设计有限公司;\n武汉市建筑设计院建筑空间;\n荆州市晴川建筑设计院有限公司;\n上海新建设建筑设计有限公司;\n上海印象空间数码科技有限公司;\n浙江绿城东方建筑设计有限公司;\n南京水晶石数字科技有限公司;\n上海广亩景观设计咨询有限公司;\n上海现代设计集团;\n浙江昌盛电气有限公司;\n大化县财政局;\n河池市财政局;\n云和县安监局;\n邮储银行上海浦东新区南汇支行;\n上海邮政速递物流公司;\n奉贤区邮政局;\n上海市清洁生产中心;\n湟中县教育局;\n广州市番禺区象贤中学;\n广西武宣县中学;\n广州市花都区教育局花山教育指导中心;\n妮梦衣服饰;\n上海沐海服饰有限公司;\n我衣我秀中国服饰代理分销总站;\n广州市纤婷服饰有限公司;\n风尚服饰批发;\n南方服装网;\n杭州恩娴服饰有限公司;\n千鸿网络鞋店;\n万辉服装;\n轩氏邦服饰;\n酷依家男装网;\n北京华夏顺泽投资集团;\n盐城中南世纪城房地产投资有限公司;\n华润置地工程管理部;\n乐品品牌策划（北京）有限公司;\n广州搏智广告有限公司;\n长春君地房地产开发有限公司;\n广东奥马电器股份有限公司;\n雅善贸易（上海）有限公司;\n上海今尚数码科技有限公司;\n中信建投证券;\n光大证券;\n南通中南新世界中心开发有限公司;\n南京艺景数字科技有限公司;\n上海鼎为软件技术有限公司;');
INSERT INTO `epan_settings` VALUES ('logo', '../images/main.png');
INSERT INTO `epan_settings` VALUES ('site_maintenance', '0');
INSERT INTO `epan_settings` VALUES ('site_maintenance_msg', '系统维护中，请稍后访问。');
INSERT INTO `epan_settings` VALUES ('reg_open', '1');
INSERT INTO `epan_settings` VALUES ('reg_banned_users', '');
INSERT INTO `epan_settings` VALUES ('upload_type_allow', '');
INSERT INTO `epan_settings` VALUES ('upload_type_deny', '');
INSERT INTO `epan_settings` VALUES ('upgrade_enable', '1');
INSERT INTO `epan_settings` VALUES ('upload_size', '15');
INSERT INTO `epan_settings` VALUES ('pay_alipay_key', '');
INSERT INTO `epan_settings` VALUES ('syxx', '公告');
INSERT INTO `epan_settings` VALUES ('contact1', 'Email：www@haic.cc&lt;br/&gt;Email：admin@haic.cc');
INSERT INTO `epan_settings` VALUES ('active_verycode', '0');
INSERT INTO `epan_settings` VALUES ('reportaddress', '');
INSERT INTO `epan_settings` VALUES ('pagedisplay', '20');
INSERT INTO `epan_settings` VALUES ('index_status', '0');
INSERT INTO `epan_settings` VALUES ('index_page', '/template/index.html');
INSERT INTO `epan_settings` VALUES ('index_page_all', '');
INSERT INTO `epan_settings` VALUES ('update_status', 'custom');
INSERT INTO `epan_settings` VALUES ('system_table', '系统;contract;admin.php');
INSERT INTO `epan_settings` VALUES ('upload_frame', 'system;png、jpg、gif;../../images/|module;zip;../../module/|softlogo;png;../../images/|program;zip;../../file/|logoview;png、ico;../../file/| imgview;png、jpg、bmp;../../file/|');
INSERT INTO `epan_settings` VALUES ('MaxCount', '4');
INSERT INTO `epan_settings` VALUES ('VaildCode', '1');
INSERT INTO `epan_settings` VALUES ('AllowDatabase', 'system_log;');
INSERT INTO `epan_settings` VALUES ('BackupFilePath', 'backup');
INSERT INTO `epan_settings` VALUES ('Index_head_New', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\r\n	<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\r\n	<meta name=\"viewport\" content=\"width=device-width, initial-scale=1, user-scalable=no\">\r\n	<meta name=\"description\" content=\"\">\r\n	<meta name=\"author\" content=\"\">\r\n	<title>{site_title} Admin Manager</title>\r\n	<link rel=\"stylesheet\" href=\"../css/style.min.css\">\r\n	<link rel=\"stylesheet\" href=\"../fonts/material-design/css/materialdesignicons.css\">\r\n	<link rel=\"stylesheet\" href=\"../plugin/mCustomScrollbar/jquery.mCustomScrollbar.min.css\">\r\n	<link rel=\"stylesheet\" href=\"../plugin/waves/waves.min.css\">\r\n	<link rel=\"stylesheet\" href=\"../plugin/sweet-alert/sweetalert.css\">\r\n	<link rel=\"stylesheet\" href=\"../css/style-dark.min.css\">\r\n    <script src=\"../js/jquery-1.8.3.min.js\"></script>\r\n    <script src=\"../js/respond.js\"></script>\r\n    <script src=\"../js/global.js\"></script>\r\n    <script src=\"../js/artDialog.js?skin=default\"></script>	\r\n    <script src=\"../js/iframeTools.js\"></script>\r\n    <script type=\"text/javascript\" src=\"../js/jquery-1.8.1.min.js\"></script>\r\n     <script type=\"text/javascript\">\r\n        var $181 = $;\r\n     </script>\r\n</head>\r\n\r\n<body>\r\n<div class=\"main-menu\">\r\n	<header class=\"header\">\r\n		<a href=\"index.php\" class=\"logo\"><i class=\"ico mdi mdi-cube-outline\"></i>MyAdmin</a>\r\n		<div class=\"user\">\r\n			<a class=\"avatar\"><img src=\"../images/mobile.jpg\" alt=\"\"><span class=\"status online\"></span></a>\r\n			<h5 class=\"name\"><a>系统管理员</a></h5>\r\n			<h5 class=\"position\">Administrator</h5>\r\n		</div>\r\n	</header>\r\n	<div class=\"content\">\r\n		<div class=\"navigation\">\r\n			<ul class=\"menu js__accordion\">\r\n				{table_list}\r\n			</ul>\r\n		</div>\r\n	</div>\r\n</div>\r\n<div class=\"fixed-navbar\">\r\n	<div class=\"pull-left\">\r\n		<button type=\"button\" class=\"menu-mobile-button glyphicon glyphicon-menu-hamburger js__menu_mobile\" title=\"菜单\"></button>\r\n		<h1 class=\"page-title\">{table_name}</h1>\r\n	</div>\r\n	<div class=\"pull-right\">\r\n		<a href=\"?/exit\" class=\"ico-item mdi mdi-logout\" title=\"登出\"></a>\r\n	</div>\r\n</div>\r\n<div id=\"wrapper\">\r\n	<div class=\"main-content\">\r\n\r\n\r\n');
INSERT INTO `epan_settings` VALUES ('iptable_version', '2025410');
INSERT INTO `epan_settings` VALUES ('pay_yhzz_bank', '中国工商银行');
INSERT INTO `epan_settings` VALUES ('pay_tenpay', '0');
INSERT INTO `epan_settings` VALUES ('pay_tenpay_key', '');
INSERT INTO `epan_settings` VALUES ('pay_wx', '1');
INSERT INTO `epan_settings` VALUES ('pay_wx_key', '1231231');
INSERT INTO `epan_settings` VALUES ('pay_zx', '0');
INSERT INTO `epan_settings` VALUES ('pay_zx_key', '');
INSERT INTO `epan_settings` VALUES ('pay_yhzz', '1');
INSERT INTO `epan_settings` VALUES ('pay_yhzz_account', '6222022102011607222');
INSERT INTO `epan_settings` VALUES ('pay_yhzz_name', 'XXXX');

-- ----------------------------
-- Table structure for epan_space
-- ----------------------------
DROP TABLE IF EXISTS `epan_space`;
CREATE TABLE `epan_space`  (
  `username` longtext CHARACTER SET gbk COLLATE gbk_chinese_ci NULL,
  `title` longtext CHARACTER SET gbk COLLATE gbk_chinese_ci NULL,
  `Message` longtext CHARACTER SET gbk COLLATE gbk_chinese_ci NULL,
  `line` longtext CHARACTER SET gbk COLLATE gbk_chinese_ci NULL,
  `style` int(11) NULL DEFAULT NULL,
  `pwd` longtext CHARACTER SET gbk COLLATE gbk_chinese_ci NULL,
  `Language` int(11) NULL DEFAULT NULL,
  `login` longtext CHARACTER SET gbk COLLATE gbk_chinese_ci NULL,
  `fk_browse` tinyint(1) NOT NULL DEFAULT 1,
  `fk_download` tinyint(1) NOT NULL DEFAULT 1,
  `fk_upload` tinyint(1) NOT NULL DEFAULT 1
) ENGINE = InnoDB CHARACTER SET = gbk COLLATE = gbk_chinese_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of epan_space
-- ----------------------------


-- ----------------------------
-- Table structure for epan_stats
-- ----------------------------
DROP TABLE IF EXISTS `epan_stats`;
CREATE TABLE `epan_stats`  (
  `vars` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `value` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`vars`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_stats
-- ----------------------------
INSERT INTO `epan_stats` VALUES ('user_folders_count', '0');
INSERT INTO `epan_stats` VALUES ('user_files_count', '0');
INSERT INTO `epan_stats` VALUES ('users_count', '1');
INSERT INTO `epan_stats` VALUES ('users_locked_count', '0');
INSERT INTO `epan_stats` VALUES ('extract_code_count', '0');
INSERT INTO `epan_stats` VALUES ('all_files_count', '0');
INSERT INTO `epan_stats` VALUES ('user_storage_count', '0.0B');
INSERT INTO `epan_stats` VALUES ('public_storage_count', '0.0B');
INSERT INTO `epan_stats` VALUES ('total_storage_count', '0.0B');
INSERT INTO `epan_stats` VALUES ('users_open_count', '1');
INSERT INTO `epan_stats` VALUES ('stat_time', '1433912747');

-- ----------------------------
-- Table structure for epan_tags
-- ----------------------------
DROP TABLE IF EXISTS `epan_tags`;
CREATE TABLE `epan_tags`  (
  `tag_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `tag_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_hidden` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`tag_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_tags
-- ----------------------------

-- ----------------------------
-- Table structure for epan_templates
-- ----------------------------
DROP TABLE IF EXISTS `epan_templates`;
CREATE TABLE `epan_templates`  (
  `tpl_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `actived` tinyint(1) UNSIGNED NOT NULL,
  `tpl_type` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`tpl_name`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_templates
-- ----------------------------
INSERT INTO `epan_templates` VALUES ('admin', 1, 'admin');
INSERT INTO `epan_templates` VALUES ('default', 0, 'user');
INSERT INTO `epan_templates` VALUES ('disk', 1, 'user');

-- ----------------------------
-- Table structure for epan_topics
-- ----------------------------
DROP TABLE IF EXISTS `epan_topics`;
CREATE TABLE `epan_topics`  (
  `tid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `credit` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `subject` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `is_closed` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `in_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `ip` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`tid`) USING BTREE,
  INDEX `userid`(`userid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_topics
-- ----------------------------

-- ----------------------------
-- Table structure for epan_userdata
-- ----------------------------
DROP TABLE IF EXISTS `epan_userdata`;
CREATE TABLE `epan_userdata`  (
  `user` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `setting` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `zsxm` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `tel` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `qq` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `address` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `sfz` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `diskLevel` int(11) NULL DEFAULT NULL,
  `Expire_time` int(11) NULL DEFAULT NULL,
  `is_Administrator` int(11) NULL DEFAULT NULL
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_userdata
-- ----------------------------


-- ----------------------------
-- Table structure for epan_userlinks
-- ----------------------------
DROP TABLE IF EXISTS `epan_userlinks`;
CREATE TABLE `epan_userlinks`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NULL DEFAULT NULL,
  `linkt` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `linku` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_userlinks
-- ----------------------------

-- ----------------------------
-- Table structure for epan_userloginlog
-- ----------------------------
DROP TABLE IF EXISTS `epan_userloginlog`;
CREATE TABLE `epan_userloginlog`  (
  `user` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `lasttime` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `lastip` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_userloginlog
-- ----------------------------


-- ----------------------------
-- Table structure for epan_userreport
-- ----------------------------
DROP TABLE IF EXISTS `epan_userreport`;
CREATE TABLE `epan_userreport`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Contant` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `Address` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `Type` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `Data` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_userreport
-- ----------------------------

-- ----------------------------
-- Table structure for epan_users
-- ----------------------------
DROP TABLE IF EXISTS `epan_users`;
CREATE TABLE `epan_users`  (
  `userid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `gid` tinyint(3) UNSIGNED NULL DEFAULT 0,
  `reset_code` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `is_activated` tinyint(1) NULL DEFAULT 0,
  `is_locked` tinyint(1) NULL DEFAULT 0,
  `last_login_time` int(11) NULL DEFAULT 0,
  `last_login_ip` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `reg_time` int(10) UNSIGNED NULL DEFAULT 0,
  `reg_ip` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `credit` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `wealth` float NOT NULL DEFAULT 0,
  `rank` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `exp` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `accept_pm` tinyint(1) NULL DEFAULT 1,
  `show_email` tinyint(1) NULL DEFAULT 0,
  `space_pos` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `user_file_types` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `user_store_space` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `user_rent_space` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `space_day_credits` float NOT NULL,
  `down_flow_count` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `view_flow_count` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `flow_reset_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `max_flow_down` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `max_flow_view` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `t1` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `t3` varchar(6000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `ly` int(11) NOT NULL DEFAULT 0,
  `xz` int(11) NOT NULL DEFAULT 0,
  `ml` int(11) NOT NULL DEFAULT 0,
  `sc` int(11) NOT NULL DEFAULT 0,
  `mlpx` int(11) NOT NULL DEFAULT 2,
  `kjfg` int(11) NOT NULL DEFAULT 5,
  `zl_te1` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `zl_te3` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `zl_teqq` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `zl_te4` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `zl_tesfz` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `dlmm` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `wen1` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `daan1` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `wen2` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `daan2` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `dqlq` int(11) NULL DEFAULT NULL,
  `allcount` int(11) NOT NULL DEFAULT 0,
  `dqsj` int(11) NULL DEFAULT 0,
  `seo1` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `seo2` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `seo3` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `QueryCode` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`userid`) USING BTREE,
  INDEX `username`(`username`) USING BTREE,
  INDEX `gid`(`gid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 12 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_users
-- ----------------------------

-- ----------------------------
-- Table structure for epan_xiaofei
-- ----------------------------
DROP TABLE IF EXISTS `epan_xiaofei`;
CREATE TABLE `epan_xiaofei`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `xfname` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `je` int(11) NOT NULL,
  `outtime` int(11) NOT NULL,
  `intime` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 12 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of epan_xiaofei
-- ----------------------------

-- ----------------------------
-- Table structure for system_ips
-- ----------------------------
DROP TABLE IF EXISTS `system_ips`;
CREATE TABLE `system_ips`  (
  `iptable` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `type` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of system_ips
-- ----------------------------

-- ----------------------------
-- Table structure for system_log
-- ----------------------------
DROP TABLE IF EXISTS `system_log`;
CREATE TABLE `system_log`  (
  `id` int(11) NULL DEFAULT NULL,
  `method` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `ip` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `data` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `addr` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `time` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of system_log
-- ----------------------------

-- ----------------------------
-- Table structure for system_setting
-- ----------------------------
DROP TABLE IF EXISTS `system_setting`;
CREATE TABLE `system_setting`  (
  `vars` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `备注` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


-- ----------------------------
-- Table structure for zd_adminlist
-- ----------------------------
DROP TABLE IF EXISTS `zd_adminlist`;
CREATE TABLE `zd_adminlist`  (
  `t_e` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `m_e` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `table` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `menu` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `url` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `page` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `count` int(11) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of zd_adminlist
-- ----------------------------
INSERT INTO `zd_adminlist` VALUES ('start', 'index', '系统', '关于', 'index.php', 'system/main.php', 5);
INSERT INTO `zd_adminlist` VALUES ('system', 'setting', '系统', '系统设置', 'index.php?/system/setting', 'system/system.php', 2);
INSERT INTO `zd_adminlist` VALUES ('system', 'info', '系统', '系统信息', 'index.php?/system/info', 'system/info.php', 4);
INSERT INTO `zd_adminlist` VALUES ('system', 'database', '系统', '数据库管理', 'index.php?/system/database', 'system/database.php', 2);
INSERT INTO `zd_adminlist` VALUES ('system', 'upload', '系统', '用户文件管理', 'index.php?/system/upload', 'system/userfiles.php', 1);
INSERT INTO `zd_adminlist` VALUES ('system', 'log', '系统', '系统日志', 'index.php?/system/log', 'system/log.php', 3);
INSERT INTO `zd_adminlist` VALUES ('system', 'pay', '系统', '支付管理', 'index.php?/system/pay', 'system/pay.php', 1);
INSERT INTO `zd_adminlist` VALUES ('system', 'user', '系统', '用户管理', 'index.php?/system/user', 'system/user.php', 1);

-- ----------------------------
-- Table structure for zd_system_ips
-- ----------------------------
DROP TABLE IF EXISTS `zd_system_ips`;
CREATE TABLE `zd_system_ips`  (
  `iptable` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `type` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of zd_system_ips
-- ----------------------------

-- ----------------------------
-- Table structure for zd_system_log
-- ----------------------------
DROP TABLE IF EXISTS `zd_system_log`;
CREATE TABLE `zd_system_log`  (
  `id` int(11) NULL DEFAULT NULL,
  `method` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `ip` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `data` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `addr` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `time` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of zd_system_log
-- ----------------------------


-- ----------------------------
-- Table structure for zd_system_setting
-- ----------------------------
DROP TABLE IF EXISTS `zd_system_setting`;
CREATE TABLE `zd_system_setting`  (
  `vars` varchar(768) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `备注` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  UNIQUE INDEX `idx_vars_unique`(`vars`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of zd_system_setting
-- ----------------------------
INSERT INTO `zd_system_setting` VALUES ('server_status', '0', '系统状态');
INSERT INTO `zd_system_setting` VALUES ('corp', 'Source在线查询中心', '版权');
INSERT INTO `zd_system_setting` VALUES ('version', '1.0.0', '版本号');
INSERT INTO `zd_system_setting` VALUES ('logo', '../images/main.png', 'logo');
INSERT INTO `zd_system_setting` VALUES ('name', 'Source在线查询中心', NULL);
INSERT INTO `zd_system_setting` VALUES ('upload_size', '29', '上传大小(MB)');
INSERT INTO `zd_system_setting` VALUES ('bottom', 'Copyright © 2001-2029 Source. All Rights Reserved.', '页面底部信息');
INSERT INTO `zd_system_setting` VALUES ('server_infomaction', 'The system is under maintenance. Please visit later.<br>', '系统维护显示信息');
INSERT INTO `zd_system_setting` VALUES ('encrypted', 'admin', '密码加密字符串');
INSERT INTO `zd_system_setting` VALUES ('bugreport', '2', 'Bug提交方式');
INSERT INTO `zd_system_setting` VALUES ('reportaddress', '', '外部提交地址');
INSERT INTO `zd_system_setting` VALUES ('pagedisplay', '20', '列表每页显示数量');
INSERT INTO `zd_system_setting` VALUES ('index_status', '0', '是否启用首页模块，0为启用，1为禁用');
INSERT INTO `zd_system_setting` VALUES ('index_page', '/template/index.html', '默认显示首页内容');
INSERT INTO `zd_system_setting` VALUES ('index_page_all', '', '已安装首页模块\r\n模块模板信息：uid;name;addr|uid;name;addr');
INSERT INTO `zd_system_setting` VALUES ('update_status', 'custom', '更新状态，用于判断是否为定制版,null 为通用版，custom为定制版');
INSERT INTO `zd_system_setting` VALUES ('system_table', '系统;contract;admin.php', '后台顶部夹显示部分,格式：name;logo;addr 用|分割');
INSERT INTO `zd_system_setting` VALUES ('upload_frame', 'system;png、jpg、gif;../../images/|module;zip;../../module/|softlogo;png;../../images/|program;zip;../../file/|logoview;png、ico;../../file/| imgview;png、jpg、bmp;../../file/|', '上传框架,格式：框架名;允许文件类型，多个用、隔开;要上传到的目录，多个框架用|分割');
INSERT INTO `zd_system_setting` VALUES ('MaxCount', '4', '后台登陆界面最大尝试次数');
INSERT INTO `zd_system_setting` VALUES ('VaildCode', '1', '后台登陆是否启用验证码，0为启用，1为关闭');
INSERT INTO `zd_system_setting` VALUES ('AllowDatabase', 'system_log;', '可清空的数据库表名');
INSERT INTO `zd_system_setting` VALUES ('BackupFilePath', 'backup', '备份文件存放路径');
INSERT INTO `zd_system_setting` VALUES ('Index_head_New', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\r\n	<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\r\n	<meta name=\"viewport\" content=\"width=device-width, initial-scale=1, user-scalable=no\">\r\n	<meta name=\"description\" content=\"\">\r\n	<meta name=\"author\" content=\"\">\r\n	<title>{corp} Admin Manager</title>\r\n	<link rel=\"stylesheet\" href=\"../css/style.min.css\">\r\n	<link rel=\"stylesheet\" href=\"../fonts/material-design/css/materialdesignicons.css\">\r\n	<link rel=\"stylesheet\" href=\"../plugin/mCustomScrollbar/jquery.mCustomScrollbar.min.css\">\r\n	<link rel=\"stylesheet\" href=\"../plugin/waves/waves.min.css\">\r\n	<link rel=\"stylesheet\" href=\"../plugin/sweet-alert/sweetalert.css\">\r\n	<link rel=\"stylesheet\" href=\"../css/style-dark.min.css\">\r\n    <script src=\"../js/jquery-1.8.3.min.js\"></script>\r\n    <script src=\"../js/respond.js\"></script>\r\n    <script src=\"../js/global.js\"></script>\r\n    <script src=\"../js/artDialog.js?skin=default\"></script>	\r\n    <script src=\"../js/iframeTools.js\"></script>\r\n    <script type=\"text/javascript\" src=\"../js/jquery-1.8.1.min.js\"></script>\r\n     <script type=\"text/javascript\">\r\n        var $181 = $;\r\n     </script>\r\n</head>\r\n\r\n<body>\r\n<div class=\"main-menu\">\r\n	<header class=\"header\">\r\n		<a href=\"index.php\" class=\"logo\"><i class=\"ico mdi mdi-cube-outline\"></i>MyAdmin</a>\r\n		<div class=\"user\">\r\n			<a class=\"avatar\"><img src=\"../images/mobile.jpg\" alt=\"\"><span class=\"status online\"></span></a>\r\n			<h5 class=\"name\"><a>系统管理员</a></h5>\r\n			<h5 class=\"position\">Administrator</h5>\r\n		</div>\r\n	</header>\r\n	<div class=\"content\">\r\n		<div class=\"navigation\">\r\n			<ul class=\"menu js__accordion\">\r\n				{table_list}\r\n			</ul>\r\n		</div>\r\n	</div>\r\n</div>\r\n<div class=\"fixed-navbar\">\r\n	<div class=\"pull-left\">\r\n		<button type=\"button\" class=\"menu-mobile-button glyphicon glyphicon-menu-hamburger js__menu_mobile\" title=\"菜单\"></button>\r\n		<h1 class=\"page-title\">{table_name}</h1>\r\n	</div>\r\n	<div class=\"pull-right\">\r\n		<a href=\"?/exit\" class=\"ico-item mdi mdi-logout\" title=\"登出\"></a>\r\n	</div>\r\n</div>\r\n<div id=\"wrapper\">\r\n	<div class=\"main-content\">\r\n\r\n\r\n', '后台界面头部');
INSERT INTO `zd_system_setting` VALUES ('iptable_version', '2025410', 'ip库版本');
INSERT INTO `zd_system_setting` VALUES ('pay_alipay', '0', '支付宝充值开关(0关/1开)');
INSERT INTO `zd_system_setting` VALUES ('pay_alipay_key', '', '支付宝合作key');
INSERT INTO `zd_system_setting` VALUES ('pay_tenpay', '0', '财付通充值开关(0关/1开)');
INSERT INTO `zd_system_setting` VALUES ('pay_tenpay_key', '', '财付通key');
INSERT INTO `zd_system_setting` VALUES ('pay_wx', '0', '微信充值开关(0关/1开)');
INSERT INTO `zd_system_setting` VALUES ('pay_wx_key', '', '微信key');
INSERT INTO `zd_system_setting` VALUES ('pay_zx', '0', '网银在线充值开关(0关/1开)');
INSERT INTO `zd_system_setting` VALUES ('pay_zx_key', '', '网银在线key');
INSERT INTO `zd_system_setting` VALUES ('pay_yhzz', '0', '银行转账开关(0关/1开)');
INSERT INTO `zd_system_setting` VALUES ('pay_yhzz_bank', '', '银行转账-开户行');
INSERT INTO `zd_system_setting` VALUES ('pay_yhzz_account', '', '银行转账-账号');
INSERT INTO `zd_system_setting` VALUES ('pay_yhzz_name', '', '银行转账-户名');

SET FOREIGN_KEY_CHECKS = 1;
