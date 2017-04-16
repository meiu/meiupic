

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



--
-- 表的结构 `labels`
--

CREATE TABLE IF NOT EXISTS `labels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '标签名称',
  `data` text NOT NULL COMMENT '标签定义',
  `pure_txt` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='全局标签表';



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


--
-- 表的结构 `albums`
--

CREATE TABLE IF NOT EXISTS `albums` (
  `id` bigint(20) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `uid` int(11) DEFAULT '0',
  `cate_id` bigint(4) unsigned NOT NULL DEFAULT '0',
  `cover_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `cover_path` varchar(200) DEFAULT NULL,
  `comments_num` int(11) unsigned NOT NULL DEFAULT '0',
  `photos_num` int(11) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `up_time` int(11) unsigned NOT NULL DEFAULT '0',
  `tags` varchar(255) DEFAULT NULL,
  `priv_type` tinyint(1) NOT NULL DEFAULT '0',
  `description` longtext,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `enable_comment` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `album_cate`
--

CREATE TABLE IF NOT EXISTS `album_cate` (
  `id` int(4) NOT NULL,
  `pid` int(4) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL,
  `dirname` varchar(50) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort` int(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `album_photos`
--

CREATE TABLE IF NOT EXISTS `album_photos` (
  `id` bigint(20) NOT NULL,
  `cate_id` int(11) NOT NULL DEFAULT '0',
  `album_id` int(11) NOT NULL DEFAULT '0',
  `uid` int(11) DEFAULT '0',
  `name` varchar(100) NOT NULL,
  `path` varchar(255) NOT NULL,
  `width` int(11) NOT NULL DEFAULT '0',
  `height` int(11) NOT NULL DEFAULT '0',
  `hits` bigint(20) NOT NULL DEFAULT '0',
  `comments_num` int(11) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `taken_time` int(11) NOT NULL DEFAULT '0',
  `description` longtext,
  `exif` longtext,
  `tags` varchar(255) DEFAULT NULL,
  `priv_type` tinyint(4) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `album_tags`
--

CREATE TABLE IF NOT EXISTS `album_tags` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `description` varchar(500) NOT NULL,
  `album_num` int(11) NOT NULL,
  `photo_num` int(11) NOT NULL,
  `addtime` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `album_tag_rels`
--

CREATE TABLE IF NOT EXISTS `album_tag_rels` (
  `tag_id` int(11) NOT NULL,
  `type` enum('album','photo') NOT NULL,
  `rel_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `album_tags`
--
ALTER TABLE `album_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`) USING BTREE;

--
-- Indexes for table `album_tag_rels`
--
ALTER TABLE `album_tag_rels`
  ADD PRIMARY KEY (`tag_id`,`type`,`rel_id`),
  ADD KEY `rel_id` (`rel_id`,`type`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `album_tags`
--
ALTER TABLE `album_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Indexes for table `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cover_id` (`cover_id`),
  ADD KEY `cate_id` (`cate_id`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `album_cate`
--
ALTER TABLE `album_cate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `par_id` (`pid`);

--
-- Indexes for table `album_photos`
--
ALTER TABLE `album_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cate_id` (`cate_id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `album_id` (`album_id`);



--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `albums`
--
ALTER TABLE `albums`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `album_cate`
--
ALTER TABLE `album_cate`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `album_photos`
--
ALTER TABLE `album_photos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;