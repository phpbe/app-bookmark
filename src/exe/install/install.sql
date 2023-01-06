SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `bookmark_category` (
  `id` varchar(36) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'uuid()' COMMENT 'UUID',
  `parent_id` varchar(36) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '父ID',
  `name` varchar(300) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '名称',
  `ordering` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `is_enable` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否启用',
  `is_delete` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否已删除',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='分类';

CREATE TABLE `bookmark_group` (
  `id` varchar(36) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'uuid()' COMMENT 'UUID',
  `category_id` varchar(36) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '分类ID',
  `name` varchar(300) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '名称',
  `ordering` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `is_enable` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否启用',
  `is_delete` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否已删除',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='分组';

CREATE TABLE `bookmark_url` (
  `id` varchar(36) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'uuid()' COMMENT 'UUID',
  `group_id` varchar(36) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '分组ID',
  `name` varchar(300) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '名称',
  `url` varchar(300) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '网址',
  `has_account` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否有账号',
  `username` varchar(60) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '账号',
  `password` varchar(60) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '密码',
  `ordering` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `is_enable` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否启用',
  `is_delete` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否已删除',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='网址';


ALTER TABLE `bookmark_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

ALTER TABLE `bookmark_group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

ALTER TABLE `bookmark_url`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`);
