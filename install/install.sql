-- ----------------------------
-- Table structure for album_cate
-- ----------------------------
DROP TABLE IF EXISTS `album_cate`;
CREATE TABLE `album_cate` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `pid` int(4) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL,
  `dirname` varchar(50) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `par_id` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for album_likes
-- ----------------------------
DROP TABLE IF EXISTS `album_likes`;
CREATE TABLE `album_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `album_id` int(11) NOT NULL,
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`,`album_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for album_photos
-- ----------------------------
DROP TABLE IF EXISTS `album_photos`;
CREATE TABLE `album_photos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cate_id` int(11) NOT NULL DEFAULT '0',
  `album_id` int(11) NOT NULL DEFAULT '0',
  `uid` int(11) DEFAULT '0',
  `post_ip` varchar(50) DEFAULT NULL COMMENT '上传图片时的ip地址',
  `name` varchar(100) NOT NULL,
  `path` varchar(255) NOT NULL,
  `sort` int(11) DEFAULT '0',
  `width` int(11) NOT NULL DEFAULT '0',
  `height` int(11) NOT NULL DEFAULT '0',
  `hits` bigint(20) NOT NULL DEFAULT '0',
  `comments_num` int(11) NOT NULL DEFAULT '0',
  `like_num` int(11) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `taken_time` int(11) NOT NULL DEFAULT '0',
  `description` text,
  `exif` longtext,
  `tags` varchar(255) DEFAULT NULL,
  `priv_type` tinyint(4) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `recommended` tinyint(1) NOT NULL DEFAULT '0',
  `recommend_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cate_id` (`cate_id`),
  KEY `uid` (`uid`),
  KEY `album_id` (`album_id`),
  KEY `recommended` (`recommended`),
  KEY `recommend_time` (`recommend_time`),
  KEY `tags` (`tags`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for album_set_photos
-- ----------------------------
DROP TABLE IF EXISTS `album_set_photos`;
CREATE TABLE `album_set_photos` (
  `set_id` int(11) NOT NULL DEFAULT '0',
  `photo_id` int(11) NOT NULL DEFAULT '0',
  `uid` int(11) DEFAULT '0',
  `add_time` int(11) DEFAULT '0',
  PRIMARY KEY (`set_id`,`photo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for album_sets
-- ----------------------------
DROP TABLE IF EXISTS `album_sets`;
CREATE TABLE `album_sets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `uid` int(11) DEFAULT '0',
  `cover_id` int(11) DEFAULT '0',
  `cover_path` varchar(200) DEFAULT NULL,
  `description` text,
  `photos_num` int(11) DEFAULT '0',
  `create_time` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  `priv_type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for album_tag_rels
-- ----------------------------
DROP TABLE IF EXISTS `album_tag_rels`;
CREATE TABLE `album_tag_rels` (
  `tag_id` int(11) NOT NULL,
  `type` enum('album','photo') NOT NULL,
  `rel_id` int(11) NOT NULL,
  PRIMARY KEY (`tag_id`,`type`,`rel_id`),
  KEY `rel_id` (`rel_id`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for album_tags
-- ----------------------------
DROP TABLE IF EXISTS `album_tags`;
CREATE TABLE `album_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `description` varchar(500) NOT NULL,
  `album_num` int(11) NOT NULL,
  `photo_num` int(11) NOT NULL,
  `addtime` int(11) NOT NULL,
  `recommended` tinyint(1) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for albums
-- ----------------------------
DROP TABLE IF EXISTS `albums`;
CREATE TABLE `albums` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `uid` int(11) DEFAULT '0',
  `cate_id` bigint(4) unsigned NOT NULL DEFAULT '0',
  `cover_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `cover_path` varchar(200) DEFAULT NULL,
  `comments_num` int(11) unsigned NOT NULL DEFAULT '0',
  `like_num` int(11) NOT NULL DEFAULT '0',
  `photos_num` int(11) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `up_time` int(11) unsigned NOT NULL DEFAULT '0',
  `tags` varchar(255) DEFAULT NULL,
  `priv_type` tinyint(1) NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `description` longtext,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `enable_comment` tinyint(1) NOT NULL DEFAULT '1',
  `recommended` tinyint(1) NOT NULL DEFAULT '0',
  `recommend_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cover_id` (`cover_id`),
  KEY `cate_id` (`cate_id`),
  KEY `uid` (`uid`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for comments
-- ----------------------------
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mod` varchar(100) NOT NULL COMMENT '所属模块',
  `rel_id` int(11) NOT NULL DEFAULT '0' COMMENT '所属id',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '上级id',
  `uid` int(11) NOT NULL COMMENT '用户id(谁产生了这条数据)',
  `author` varchar(50) NOT NULL COMMENT '发布人',
  `email` varchar(200) NOT NULL COMMENT 'email',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `ip` varchar(50) DEFAULT NULL COMMENT 'ip地址',
  `content` text NOT NULL COMMENT '评论内容',
  `support` int(11) NOT NULL DEFAULT '0' COMMENT '支持',
  `object` int(11) NOT NULL DEFAULT '0' COMMENT '反对',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: 待审核 1:审核通过 2:未通过审核',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `uid` (`uid`),
  KEY `mod` (`mod`,`rel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for labels
-- ----------------------------
DROP TABLE IF EXISTS `labels`;
CREATE TABLE `labels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '标签名称',
  `data` text NOT NULL COMMENT '标签定义',
  `pure_txt` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for routes
-- ----------------------------
DROP TABLE IF EXISTS `routes`;
CREATE TABLE `routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `route` varchar(100) NOT NULL COMMENT '路由',
  `params` text NOT NULL COMMENT '映射参数',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '默认排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for settings
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `name` varchar(100) NOT NULL COMMENT '参数名',
  `value` text NOT NULL COMMENT '值',
  `autoload` enum('yes','no') NOT NULL DEFAULT 'yes' COMMENT '是否自动加载',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sms_codes
-- ----------------------------
DROP TABLE IF EXISTS `sms_codes`;
CREATE TABLE `sms_codes` (
  `mobile` char(11) NOT NULL,
  `code` varchar(32) NOT NULL,
  `lasttime` int(11) NOT NULL,
  `send_count` int(11) NOT NULL,
  PRIMARY KEY (`mobile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sms_logs
-- ----------------------------
DROP TABLE IF EXISTS `sms_logs`;
CREATE TABLE `sms_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile` char(15) NOT NULL,
  `content` varchar(255) NOT NULL,
  `sendtime` int(11) NOT NULL,
  `result` enum('success','failed') DEFAULT NULL,
  `result_content` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for upfiles
-- ----------------------------
DROP TABLE IF EXISTS `upfiles`;
CREATE TABLE `upfiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '文件原名',
  `path` varchar(100) NOT NULL COMMENT '路径',
  `ext` varchar(10) NOT NULL COMMENT '文件后缀',
  `size` int(11) NOT NULL COMMENT '大小，单位字节',
  `isthumb` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是缩略图？1，是',
  `filetype` varchar(20) NOT NULL DEFAULT 'image',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `path` (`path`),
  KEY `filetype` (`filetype`),
  KEY `isthumb` (`isthumb`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL COMMENT '登录名',
  `userpass` varchar(100) NOT NULL COMMENT '密码',
  `salt` varchar(10) NOT NULL COMMENT '加点儿盐',
  `mobile` char(15) NOT NULL COMMENT '手机号',
  `email` varchar(100) NOT NULL COMMENT 'Email',
  `nickname` varchar(100) NOT NULL DEFAULT '' COMMENT '昵称',
  `description` varchar(200) DEFAULT NULL COMMENT '描述/签名',
  `friends` int(11) NOT NULL DEFAULT '0' COMMENT '好友数',
  `followers` int(11) NOT NULL DEFAULT '0' COMMENT '粉丝数',
  `gender` enum('f','m','n') NOT NULL DEFAULT 'n' COMMENT '性别',
  `points` int(11) NOT NULL DEFAULT '0' COMMENT '积分数',
  `regtime` int(11) NOT NULL COMMENT '注册时间',
  `regip` varchar(50) NOT NULL COMMENT '注册ip',
  `logintime` int(11) NOT NULL COMMENT '登录时间',
  `loginip` varchar(50) NOT NULL COMMENT '最后登录ip',
  `level` int(11) NOT NULL DEFAULT '0' COMMENT '用户级别，99为管理员',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '0,停用 1,启用 2,删除',
  `email_actived` tinyint(1) NOT NULL DEFAULT '0',
  `mobile_actived` tinyint(1) NOT NULL DEFAULT '0',
  `facever` int(11) NOT NULL DEFAULT '0' COMMENT '头像版本号',
  `bgver` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users_codes
-- ----------------------------
DROP TABLE IF EXISTS `users_codes`;
CREATE TABLE `users_codes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0',
  `expire_time` int(10) DEFAULT NULL,
  `code` varchar(32) DEFAULT NULL,
  `code_type` varchar(16) DEFAULT NULL,
  `add_time` int(10) DEFAULT NULL,
  `add_ip` varchar(50) DEFAULT NULL,
  `active_time` int(10) DEFAULT NULL,
  `active_ip` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `active_code` (`code`),
  KEY `active_type_code` (`code_type`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users_follow
-- ----------------------------
DROP TABLE IF EXISTS `users_follow`;
CREATE TABLE `users_follow` (
  `uid` int(11) NOT NULL,
  `follow_uid` int(11) NOT NULL COMMENT '关注者UID',
  `follow_time` int(11) NOT NULL,
  PRIMARY KEY (`uid`,`follow_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users_info
-- ----------------------------
DROP TABLE IF EXISTS `users_info`;
CREATE TABLE `users_info` (
  `uid` int(11) NOT NULL,
  `extra1` varchar(200) NOT NULL COMMENT '扩展字段1',
  `extra2` varchar(200) NOT NULL COMMENT '扩展字段2',
  `extra3` varchar(200) NOT NULL COMMENT '扩展字段3',
  `extra4` varchar(200) NOT NULL COMMENT '扩展字段4',
  `extra5` varchar(200) NOT NULL COMMENT '扩展字段5',
  `extra6` varchar(200) NOT NULL COMMENT '扩展字段6',
  `extra7` varchar(200) NOT NULL COMMENT '扩展字段7',
  `extra8` varchar(200) NOT NULL COMMENT '扩展字段8',
  `addtime` int(11) NOT NULL COMMENT '加入时间',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users_point
-- ----------------------------
DROP TABLE IF EXISTS `users_point`;
CREATE TABLE `users_point` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '积分说明',
  `pointkey` varchar(100) NOT NULL COMMENT '积分key',
  `points` int(11) NOT NULL DEFAULT '0' COMMENT '积分数',
  `ac` tinyint(1) NOT NULL COMMENT '0加积分1减积分',
  PRIMARY KEY (`id`),
  KEY `pointkey` (`pointkey`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users_point_log
-- ----------------------------
DROP TABLE IF EXISTS `users_point_log`;
CREATE TABLE `users_point_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `name` varchar(100) NOT NULL COMMENT '积分说明',
  `points` int(11) NOT NULL DEFAULT '0' COMMENT '积分数',
  `ac` tinyint(1) NOT NULL COMMENT '0加积分1减积分',
  `addtime` int(11) NOT NULL COMMENT '积分变化的时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `album_cate`
--

INSERT INTO `album_cate` (`id`, `pid`, `name`, `dirname`, `status`, `sort`) VALUES
(1, 0, '风光', 'sights', 1, 0),
(2, 0, '人像', 'people', 1, 0),
(3, 0, '城市', 'city', 1, 0),
(4, 0, '旅行', 'travel', 1, 0),
(5, 0, '纪实', 'record', 1, 0),
(6, 0, '街拍', 'street', 1, 0),
(7, 0, '人文', 'culture', 1, 0),
(8, 0, '美女', 'beauty', 1, 0),
(9, 0, '建筑', 'building', 1, 0),
(10, 0, '静物', 'still', 1, 0),
(11, 0, '光影', 'light', 1, 0),
(12, 0, '自然', 'nature', 1, 0),
(13, 0, '夜景', 'night', 1, 0),
(14, 0, '植物', 'plant', 1, 0),
(15, 0, '儿童', 'child', 1, 0),
(16, 0, '其他', 'other', 0, 0);


--
-- 转存表中的数据 `routes`
--
INSERT INTO `routes` VALUES (1, 'cate/{dirname}', 'app=album&m=cate', 50),
(2, 'tag/{tag}', 'app=album&m=search', 50),
(3, 'discover', 'app=album&m=index', 50),
(4, 'search/{keyword}', 'app=album&m=search', 50),
(5, 'sets/{id}', 'app=album&m=sets_photos', 50),
(6, 'u/{id}', 'app=space&m=index', 50),
(7, 'u{id}/works', 'app=album&m=space', 50),
(8, 'u{id}/friends', 'app=friend&m=friends', 50),
(9, 'u{id}/followers', 'app=friend&m=followers', 50),
(10, 'u{id}/like', 'app=album&m=space_like', 50),
(12, 'u{id}/profile', 'app=album&m=space_profile', 50),
(13, 'u{id}/sets', 'app=album&m=space_sets', 50),
(14, 'work/{id}', 'app=album&m=album_detail', 50),
(15, 'sets/{set_id}/{id}', 'app=album&m=sets_view', 50);

-- --------------------------------------------------------

--
-- 转存表中的数据 `settings`
--

INSERT INTO `settings` (`name`, `value`, `autoload`) VALUES
('actived_plugins', 'a:3:{s:3:"pub";a:0:{}s:7:"comment";a:2:{i:0;s:13:"artcommentnum";i:1;s:15:"albumcommentnum";}s:5:"album";a:1:{i:0;s:15:"albumcommentnum";}}', 'yes'),
('admin_menu', 'a:6:{i:0;a:6:{s:4:"name";s:6:"首页";s:3:"app";s:4:"base";s:3:"mod";s:5:"index";s:5:"fixed";b:1;s:6:"enable";b:1;s:4:"sort";s:1:"0";}i:1;a:5:{s:4:"name";s:6:"相册";s:3:"app";s:5:"album";s:5:"fixed";b:0;s:6:"enable";b:1;s:4:"sort";s:1:"1";}i:2;a:6:{s:4:"name";s:6:"模版";s:3:"app";s:4:"base";s:3:"mod";s:8:"template";s:5:"fixed";b:1;s:6:"enable";b:1;s:4:"sort";s:1:"1";}i:3;a:5:{s:4:"name";s:6:"评论";s:3:"app";s:7:"comment";s:5:"fixed";b:0;s:6:"enable";b:1;s:4:"sort";s:1:"2";}i:4;a:5:{s:4:"name";s:6:"用户";s:3:"app";s:4:"user";s:5:"fixed";b:0;s:6:"enable";b:1;s:4:"sort";s:1:"3";}i:5;a:6:{s:4:"name";s:6:"设置";s:3:"app";s:4:"base";s:3:"mod";s:7:"setting";s:5:"fixed";b:1;s:6:"enable";b:1;s:4:"sort";s:1:"4";}}', 'yes'),
('agreement_content', '当您申请用户时，表示您已经同意遵守本规章。\n欢迎您加入本站点参与交流和讨论，本站点为社区，为维护网上公共秩序和社会稳定，请您自觉遵守以下条款：\n\n一、不得利用本站危害国家安全、泄露国家秘密，不得侵犯国家社会集体的和公民的合法权益，不得利用本站制作、复制和传播下列信息：\n　（一）煽动抗拒、破坏宪法和法律、行政法规实施的；\n　（二）煽动颠覆国家政权，推翻社会主义制度的；\n　（三）煽动分裂国家、破坏国家统一的；\n　（四）煽动民族仇恨、民族歧视，破坏民族团结的；\n　（五）捏造或者歪曲事实，散布谣言，扰乱社会秩序的；\n　（六）宣扬封建迷信、淫秽、色情、赌博、暴力、凶杀、恐怖、教唆犯罪的；\n　（七）公然侮辱他人或者捏造事实诽谤他人的，或者进行其他恶意攻击的；\n　（八）损害国家机关信誉的；\n　（九）其他违反宪法和法律行政法规的；\n　（十）进行商业广告行为的。\n\n二、互相尊重，对自己的言论和行为负责。\n三、禁止在申请用户时使用相关本站的词汇，或是带有侮辱、毁谤、造谣类的或是有其含义的各种语言进行注册用户，否则我们会将其删除。\n四、禁止以任何方式对本站进行各种破坏行为。\n五、如果您有违反国家相关法律法规的行为，本站概不负责，您的登录信息均被记录无疑，必要时，我们会向相关的国家管理部门提供此类信息。\n六、尊重作品版权，请不要上传未经授权的图片作品。如果我们收到侵权举报，有权直接删除您上传的图片。', 'yes'),
('album_email_notactive_cannotpost', '0', 'yes'),
('album_enable_watermark', '0', 'yes'),
('album_mobile_notactive_cannotpost', '0', 'yes'),
('album_pre_resize_img', '1', 'yes'),
('album_resize_img', '1', 'yes'),
('album_resize_img_h', '1600', 'yes'),
('album_resize_img_w', '1600', 'yes'),
('album_watermark_path', '', 'yes'),
('album_water_mark_pos', '7', 'yes'),
('comment_setting', 'a:8:{s:12:"open_comment";s:1:"1";s:11:"allow_guest";s:1:"0";s:11:"allow_reply";s:1:"1";s:13:"allow_support";s:1:"1";s:12:"allow_object";s:1:"0";s:10:"need_audit";s:1:"0";s:16:"comments_perpage";s:2:"10";s:14:"enable_captcha";s:1:"0";}', 'yes'),
('current_theme', 'default', 'yes'),
('default_app', 'album', 'yes'),
('default_user_level', '1', 'yes'),
('enable_rewrite', '1', 'yes'),
('enable_route', '1', 'yes'),
('enable_wap', '0', 'yes'),
('html_cache_time', '0', 'yes'),
('icp', '', 'yes'),
('mail_setting', 'a:8:{s:8:"protocol";s:4:"none";s:9:"send_name";s:12:"美优相册";s:10:"send_email";s:13:"admin@meiu.cn";s:9:"smtp_host";s:0:"";s:11:"smtp_crypto";s:3:"ssl";s:9:"smtp_port";s:3:"465";s:13:"smtp_username";s:0:"";s:13:"smtp_password";s:0:"";}', 'yes'),
('my_default_app', 'album', 'yes'),
('rewrite_style', '1', 'yes'),
('site_seo_description', '', 'yes'),
('site_seo_keywords', '', 'yes'),
('site_seo_title', '', 'yes'),
('site_sub_title', '又一个MeiuPic站点', 'yes'),
('site_title', '美优相册', 'yes'),
('sms_setting', 'a:4:{s:8:"api_type";s:4:"none";s:7:"api_key";s:0:"";s:4:"sign";s:0:"";s:6:"yzmtpl";s:29:"您的验证码是#验证码#";}', 'yes'),
('stats', '', 'yes'),
('user_fields', 'a:2:{s:6:"extra1";a:2:{s:5:"cname";s:2:"QQ";s:4:"show";i:0;}s:6:"extra2";a:2:{s:5:"cname";s:12:"个人主页";s:4:"show";i:0;}}', 'yes'),
('user_setting', 'a:5:{s:15:"enable_register";s:1:"1";s:20:"enable_login_captcha";s:1:"1";s:18:"enable_reg_captcha";s:1:"1";s:21:"enable_mobile_captcha";s:1:"1";s:20:"enable_admin_captcha";s:1:"1";}', 'yes');

-- --------------------------------------------------------

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`id`, `username`, `userpass`, `salt`, `mobile`, `email`, `nickname`, `description`, `friends`, `followers`, `gender`, `points`, `regtime`, `regip`, `logintime`, `loginip`, `level`, `status`, `email_actived`, `mobile_actived`, `facever`, `bgver`) VALUES
(1, 'admin', '7aea7a735ba20676322e4985b1232504', 'fed42f', '', 'admin@admin.com', '管理员', '我是网站的管理员', 0, 0, 'm', 310, 1375625719, '127.0.0.1', 1523332289, '127.0.0.1', 99, 1, 0, 0, 0, 0);


-- --------------------------------------------------------

--
-- 转存表中的数据 `users_info`
--

INSERT INTO `users_info` (`uid`, `extra1`, `extra2`, `extra3`, `extra4`, `extra5`, `extra6`, `extra7`, `extra8`, `addtime`) VALUES
(1, '', '', '', '', '', '', '', '', 0);

-- --------------------------------------------------------

--
-- 转存表中的数据 `users_point`
--

INSERT INTO `users_point` (`id`, `name`, `pointkey`, `points`, `ac`) VALUES
(1, '登录增加积分', 'user_login', 5, 0),
(2, '注册增加积分', 'user_register', 10, 0);

-- --------------------------------------------------------