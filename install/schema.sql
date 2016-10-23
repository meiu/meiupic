
--
-- 表的结构 `admin_dashboard`
--

CREATE TABLE IF NOT EXISTS `admin_dashboard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `image` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `admin_menus`
--

CREATE TABLE IF NOT EXISTS `admin_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `tid` int(11) NOT NULL DEFAULT '0',
  `url` varchar(200) NOT NULL,
  `isdefault` tinyint(1) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `catalogs`
--

CREATE TABLE IF NOT EXISTS `catalogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL COMMENT '上级id',
  `modid` int(11) NOT NULL COMMENT '模型id',
  `dirname` varchar(50) NOT NULL DEFAULT '' COMMENT '目录/虚拟路径',
  `cover` varchar(100) NOT NULL DEFAULT '' COMMENT '封面图片',
  `cata_tpl` varchar(100) NOT NULL COMMENT '栏目列表模版',
  `detail_tpl` varchar(100) NOT NULL DEFAULT '' COMMENT '详情模版',
  `edit_tpl` varchar(100) NOT NULL DEFAULT '' COMMENT '添加/编辑模版',
  `name` varchar(100) NOT NULL COMMENT '分类名',
  `type` enum('list','page','index') NOT NULL DEFAULT 'list' COMMENT 'list栏目列表，page单页面，interact交互，form智能表单',
  `memo` varchar(255) NOT NULL DEFAULT '' COMMENT '分类描述',
  `content` text NOT NULL COMMENT '内容',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '0,隐藏 1,显示',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '默认排序',
  `seo` text NOT NULL COMMENT 'SEO',
  `priv` varchar(255) NOT NULL DEFAULT '' COMMENT '权限',
  `list_pageset` int(11) NOT NULL DEFAULT '15' COMMENT '该分类每页条数',
  `enable_comment` tinyint(1) NOT NULL DEFAULT '1' COMMENT '启用评论',
  `redirect_url` varchar(255) NOT NULL DEFAULT '' COMMENT '跳转地址',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `modid` (`modid`),
  KEY `dirname` (`dirname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='结构目录表';

-- --------------------------------------------------------

--
-- 表的结构 `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mod` varchar(100) NOT NULL COMMENT '所属模块',
  `rel_id` int(11) NOT NULL DEFAULT '0' COMMENT '所属id',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '上级id',
  `uid` int(11) NOT NULL COMMENT '用户id(谁产生了这条数据)',
  `author` varchar(50) NOT NULL COMMENT '发布人',
  `email` varchar(200) NOT NULL COMMENT 'email',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `ip` int(11) NOT NULL COMMENT 'ip地址',
  `content` text NOT NULL COMMENT '评论内容',
  `support` int(11) NOT NULL DEFAULT '0' COMMENT '支持',
  `object` int(11) NOT NULL DEFAULT '0' COMMENT '反对',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: 待审核 1:审核通过 2:未通过审核',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `uid` (`uid`),
  KEY `mod` (`mod`,`rel_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='评论表';

-- --------------------------------------------------------

--
-- 表的结构 `infos`
--

CREATE TABLE IF NOT EXISTS `infos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modid` int(11) NOT NULL COMMENT '模型id',
  `cid` int(11) NOT NULL COMMENT '分类/栏目id',
  `cover` varchar(100) NOT NULL COMMENT '封面图片',
  `title` varchar(200) NOT NULL COMMENT '信息标题',
  `keywords` VARCHAR(150) NOT NULL COMMENT '关键词/标签',
  `path` varchar(50) NOT NULL DEFAULT '' COMMENT '路径/别名',
  `uid` int(11) NOT NULL COMMENT '用户id(谁产生了这条数据)',
  `author` varchar(50) NOT NULL COMMENT '作者',
  `source` varchar(200) NOT NULL COMMENT '来源（名称,url）',
  `addtime` int(11) NOT NULL COMMENT '信息发布的时间',
  `hits` int(11) NOT NULL DEFAULT '0' COMMENT '点击数',
  `comment_num` int(11) NOT NULL DEFAULT '0' COMMENT '评论数',
  `content` text NOT NULL COMMENT '信息的主要内容',
  `status` tinyint(3) NOT NULL DEFAULT '3' COMMENT '状态：0待审，1审核不通过，2审核通过，3发布，4暂停，5删除',
  `redirect_url` varchar(255) NOT NULL DEFAULT '' COMMENT '跳转地址',
  `custom_tpl` varchar(50) NOT NULL DEFAULT '' COMMENT '文章页自定义模版',
  `rid1` int(11) NOT NULL DEFAULT '0' COMMENT '关联id1',
  `rid2` int(11) NOT NULL DEFAULT '0' COMMENT '关联id2',
  `rid3` int(11) NOT NULL DEFAULT '0' COMMENT '关联id3',
  `rid4` int(11) NOT NULL DEFAULT '0' COMMENT '关联id4',
  `p1` varchar(255) NOT NULL DEFAULT '' COMMENT '属性1',
  `p2` varchar(255) NOT NULL DEFAULT '' COMMENT '属性2',
  `p3` varchar(255) NOT NULL DEFAULT '' COMMENT '属性3',
  `p4` varchar(255) NOT NULL DEFAULT '' COMMENT '属性4',
  `p5` varchar(255) NOT NULL DEFAULT '' COMMENT '属性5',
  `p6` varchar(255) NOT NULL DEFAULT '' COMMENT '属性6',
  `p7` varchar(255) NOT NULL DEFAULT '' COMMENT '属性7',
  `p8` varchar(255) NOT NULL DEFAULT '' COMMENT '属性8',
  `p9` varchar(255) NOT NULL DEFAULT '' COMMENT '属性9',
  `p10` varchar(255) NOT NULL DEFAULT '' COMMENT '属性10',
  `p11` varchar(255) NOT NULL DEFAULT '' COMMENT '属性11',
  `p12` varchar(255) NOT NULL DEFAULT '' COMMENT '属性12',
  `p13` varchar(255) NOT NULL DEFAULT '' COMMENT '属性13',
  `p14` varchar(255) NOT NULL DEFAULT '' COMMENT '属性14',
  `p15` varchar(255) NOT NULL DEFAULT '' COMMENT '属性15',
  `p16` varchar(255) NOT NULL DEFAULT '' COMMENT '属性16',
  `p17` varchar(255) NOT NULL DEFAULT '' COMMENT '属性17',
  `p18` varchar(255) NOT NULL DEFAULT '' COMMENT '属性18',
  `p19` varchar(255) NOT NULL DEFAULT '' COMMENT '属性19',
  `p20` varchar(255) NOT NULL DEFAULT '' COMMENT '属性20',
  `t1` text NOT NULL COMMENT '大数据1',
  `t2` text NOT NULL COMMENT '大数据2',
  `t3` text NOT NULL COMMENT '大数据3',
  `t4` text NOT NULL COMMENT '大数据4',
  `seo` text NOT NULL COMMENT 'SEO',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`),
  KEY `modid` (`modid`),
  KEY `uid` (`uid`),
  KEY `rid1` (`rid1`),
  KEY `rid2` (`rid2`),
  KEY `rid3` (`rid3`),
  KEY `rid4` (`rid4`),
  KEY `path` (`path`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='信息表主表';

-- --------------------------------------------------------

--
-- 表的结构 `labels`
--

CREATE TABLE IF NOT EXISTS `labels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '标签名称',
  `data` text NOT NULL COMMENT '标签定义',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='全局标签表';

-- --------------------------------------------------------

--
-- 表的结构 `menus`
--

CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '菜单名称',
  `type` enum('menu','friendlink','banner') NOT NULL DEFAULT 'menu',
  `data` text NOT NULL COMMENT '详细定义',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='菜单表';

-- --------------------------------------------------------

--
-- 表的结构 `models`
--

CREATE TABLE IF NOT EXISTS `models` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '模型名称',
  `type` enum('goods','info','interact','form') NOT NULL DEFAULT 'info' COMMENT 'form表单，info信息，interact互动，goods商品',
  `data` text NOT NULL COMMENT '详细定义',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '0,停用 1,启用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='模型表';

-- --------------------------------------------------------

--
-- 表的结构 `routes`
--

CREATE TABLE IF NOT EXISTS `routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `route` varchar(100) NOT NULL COMMENT '路由',
  `params` text NOT NULL COMMENT '映射参数',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '默认排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='路由表';

-- --------------------------------------------------------

--
-- 表的结构 `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `name` varchar(100) NOT NULL COMMENT '参数名',
  `value` text NOT NULL COMMENT '值',
  `autoload` enum('yes','no') NOT NULL DEFAULT 'yes' COMMENT '是否自动加载',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统设置表';

-- --------------------------------------------------------

--
-- 表的结构 `upfiles`
--

CREATE TABLE IF NOT EXISTS `upfiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '文件原名',
  `path` varchar(100) NOT NULL COMMENT '路径',
  `ext` varchar(10) NOT NULL COMMENT '文件后缀',
  `size` int(11) NOT NULL COMMENT '大小，单位字节',
  `isthumb` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是缩略图？1，是',
  `filetype` enum('image','flash','audio','video','attach') NOT NULL DEFAULT 'image',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `path` (`path`),
  KEY `filetype` (`filetype`),
  KEY `isthumb` (`isthumb`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='附件表';

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL COMMENT '登录名',
  `userpass` varchar(100) NOT NULL COMMENT '密码',
  `salt` varchar(10) NOT NULL COMMENT '加点儿盐',
  `email` varchar(100) NOT NULL COMMENT 'Email',
  `nickname` varchar(100) NOT NULL DEFAULT '' COMMENT '昵称',
  `points` int(11) NOT NULL DEFAULT '0' COMMENT '积分数',
  `regtime` int(11) NOT NULL COMMENT '注册时间',
  `regip` varchar(50) NOT NULL COMMENT '注册ip',
  `logintime` int(11) NOT NULL COMMENT '登录时间',
  `loginip` varchar(50) NOT NULL COMMENT '最后登录ip',
  `level` int(11) NOT NULL DEFAULT '0' COMMENT '用户级别，99为管理员',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '0,停用 1,启用 2,删除',
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户表';

-- --------------------------------------------------------

--
-- 表的结构 `users_info`
--

CREATE TABLE IF NOT EXISTS `users_info` (
  `uid` int(11) NOT NULL,
  `extra1` varchar(200) NOT NULL COMMENT '扩展字段1',
  `extra2` varchar(200) NOT NULL COMMENT '扩展字段2',
  `extra3` varchar(200) NOT NULL COMMENT '扩展字段3',
  `extra4` varchar(200) NOT NULL COMMENT '扩展字段4',
  `extra5` varchar(200) NOT NULL COMMENT '扩展字段5',
  `extra6` varchar(200) NOT NULL COMMENT '扩展字段6',
  `extra7` varchar(200) NOT NULL COMMENT '扩展字段7',
  `extra8` varchar(200) NOT NULL COMMENT '扩展字段8',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表详情表';

-- --------------------------------------------------------

--
-- 表的结构 `users_point`
--

CREATE TABLE IF NOT EXISTS `users_point` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '积分说明',
  `pointkey` varchar(100) NOT NULL COMMENT '积分key',
  `points` int(11) NOT NULL DEFAULT '0' COMMENT '积分数',
  `ac` tinyint(1) NOT NULL COMMENT '0加积分1减积分',
  PRIMARY KEY (`id`),
  KEY `pointkey` (`pointkey`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户积分定义';

-- --------------------------------------------------------

--
-- 表的结构 `users_point_log`
--

CREATE TABLE IF NOT EXISTS `users_point_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `name` varchar(100) NOT NULL COMMENT '积分说明',
  `points` int(11) NOT NULL DEFAULT '0' COMMENT '积分数',
  `ac` tinyint(1) NOT NULL COMMENT '0加积分1减积分',
  `addtime` int(11) NOT NULL COMMENT '积分变化的时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户积分日志';

-- 表的结构 `ads`
--

CREATE TABLE IF NOT EXISTS `ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '广告名称',
  `data` text NOT NULL COMMENT '广告定义',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='广告';

-- --------------------------------------------------------

--
-- 表的结构 `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(3) NOT NULL DEFAULT '0',
  `ref_id` INT NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `cate_id` (`cate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `tag_cates`
--

CREATE TABLE IF NOT EXISTS `tag_cates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `tag_info`
--

CREATE TABLE IF NOT EXISTS `tag_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) NOT NULL,
  `info_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tag_id` (`tag_id`),
  KEY `info_id` (`info_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
