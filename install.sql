--
-- 表的结构 `__PREFIX__softlib_access_logs`
--

CREATE TABLE IF NOT EXISTS `__PREFIX__softlib_access_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `ip` varchar(255) DEFAULT NULL COMMENT '来源ip',
  `address` varchar(255) DEFAULT NULL COMMENT '来源地址',
  `network` varchar(255) DEFAULT NULL COMMENT '来源网络',
  `createtime` bigint(20) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='访问日志';

--
-- 表的结构 `__PREFIX__softlib_app`
--

CREATE TABLE IF NOT EXISTS `__PREFIX__softlib_app` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `url` varchar(1024) DEFAULT NULL COMMENT '蓝奏云网址',
  `weigh` int(11) DEFAULT NULL COMMENT '权重',
  `enable_switch` tinyint(4) DEFAULT NULL COMMENT '是否启用',
  `createtime` bigint(20) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(20) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='软件列表';

--
-- 表的结构 `__PREFIX__softlib_carousel`
--

CREATE TABLE IF NOT EXISTS `__PREFIX__softlib_carousel` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `type` enum('no','url') DEFAULT NULL COMMENT '类型:no=无,url=网址',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `image` varchar(255) DEFAULT NULL COMMENT '图片',
  `url` varchar(1024) DEFAULT NULL COMMENT '网址',
  `enable_switch` tinyint(4) DEFAULT '1' COMMENT '是否启用',
  `weigh` int(11) DEFAULT NULL COMMENT '权重',
  `createtime` bigint(20) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(20) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='首页轮播图';

--
-- 表的结构 `__PREFIX__softlib_referral`
--

CREATE TABLE IF NOT EXISTS `__PREFIX__softlib_referral` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `type` enum('page','url') DEFAULT NULL COMMENT '类型:page=页面,url=网址',
  `image` varchar(1024) DEFAULT NULL COMMENT '图片',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `content` longtext COMMENT '内容',
  `url` varchar(2083) DEFAULT NULL COMMENT '网址',
  `switch` tinyint(4) DEFAULT NULL COMMENT '开关',
  `createtime` bigint(20) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(20) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='首页推荐';

--
-- 表的结构 `__PREFIX__softlib_report`
--

CREATE TABLE IF NOT EXISTS `__PREFIX__softlib_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cat_id` int(11) NOT NULL COMMENT '分类id',
  `image` varchar(1024) DEFAULT NULL COMMENT '图片',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `content` longtext COMMENT '内容',
  `views` int(11) DEFAULT NULL COMMENT '浏览数',
  `createtime` bigint(20) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(20) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='线报';

--
-- 表的结构 `__PREFIX__softlib_report_cat`
--

CREATE TABLE IF NOT EXISTS `__PREFIX__softlib_report_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `createtime` bigint(20) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(20) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='线报分类';

--
-- 表的结构 `__PREFIX__softlib_version`
--

CREATE TABLE IF NOT EXISTS `__PREFIX__softlib_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `version` varchar(255) DEFAULT NULL COMMENT '版本号',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `content` longtext COMMENT '内容',
  `dow_url` varchar(1024) DEFAULT NULL COMMENT '下载地址',
  `forced_switch` tinyint(4) DEFAULT '1' COMMENT '强制更新',
  `enable_switch` tinyint(4) DEFAULT '1' COMMENT '启用版本',
  `createtime` bigint(20) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(20) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `__PREFIX__softlib_version_pk` (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='版本列表';

