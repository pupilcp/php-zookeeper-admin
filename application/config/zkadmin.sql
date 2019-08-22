/*

Target Server Type    : MYSQL
Target Server Version : 50642
File Encoding         : 65001

*/

SET FOREIGN_KEY_CHECKS=0;

CREATE DATABASE IF NOT EXISTS zkadmin default charset utf8 COLLATE utf8_general_ci;
USE zkadmin;

-- ----------------------------
-- Table structure for zk_config
-- ----------------------------
DROP TABLE IF EXISTS `zk_config`;
CREATE TABLE `zk_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '配置名',
  `intro` varchar(255) NOT NULL DEFAULT '' COMMENT '配置描述',
  `content` text NOT NULL COMMENT '配置内容',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0：未删除；1：已删除',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `update_user` varchar(32) NOT NULL DEFAULT '' COMMENT '更新的用户名',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='系统配置表';

-- ----------------------------
-- Records of zk_config
-- ----------------------------
INSERT INTO `zk_config` VALUES ('1', 'zookeeper_url', 'zookeeper集群地址，多个服务器地址使用英文逗号分隔，如：192.168.0.1:2181,192.168.0.2:2181', '', '0', '1566288966', '1566383167', 'admin');

-- ----------------------------
-- Table structure for zk_role
-- ----------------------------
DROP TABLE IF EXISTS `zk_role`;
CREATE TABLE `zk_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `is_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0：未激活；1：激活',
  `role_acl` text NOT NULL COMMENT '角色权限',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0：未删除；1：已删除',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_name` (`role_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='角色表';

-- ----------------------------
-- Records of zk_role
-- ----------------------------
INSERT INTO `zk_role` VALUES ('1', 'adminstrator', '1', '', '0', UNIX_TIMESTAMP(now()), UNIX_TIMESTAMP(now()));
INSERT INTO `zk_role` VALUES ('2', 'leader', '1', '[\"node_index\",\"node_createnode\",\"user_index\",\"user_create\",\"user_update\",\"user_active\",\"user_forbid\",\"role_index\",\"role_create\",\"role_update\",\"role_active\",\"role_forbid\",\"config_index\",\"config_create\"]', '0', UNIX_TIMESTAMP(now()), UNIX_TIMESTAMP(now()));
INSERT INTO `zk_role` VALUES ('3', 'developer', '1', '[\"node_index\",\"user_index\",\"role_index\",\"config_index\"]', '0', UNIX_TIMESTAMP(now()), UNIX_TIMESTAMP(now()));

-- ----------------------------
-- Table structure for zk_user
-- ----------------------------
DROP TABLE IF EXISTS `zk_user`;
CREATE TABLE `zk_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `email` varchar(32) NOT NULL DEFAULT '' COMMENT '邮箱',
  `role_id` int(10) NOT NULL DEFAULT '0' COMMENT '角色ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `login_ip` varchar(32) NOT NULL DEFAULT '' COMMENT '登录ip',
  `login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上次登录时间',
  `is_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0：未激活；1：激活',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0：未删除；1：已删除',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of zk_user
-- ----------------------------
INSERT INTO `zk_user` VALUES ('10', 'admin', 'd2e7e7dbb47a41891ba04f34711e8444', 'admin@example.com', '1', UNIX_TIMESTAMP(now()), UNIX_TIMESTAMP(now()), '', UNIX_TIMESTAMP(now()), '1', '0');
