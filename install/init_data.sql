INSERT INTO `users` (`id`, `username`, `userpass`, `salt`, `email`, `nickname`, `points`, `regtime`, `regip`, `logintime`, `loginip`, `level`, `status`) VALUES
(1, 'admin', '7aea7a735ba20676322e4985b1232504', 'fed42f', 'admin@admin.com', '管理员', 300, 1375625719, '127.0.0.1', 1422342407, '127.0.0.1', 99, 1);

INSERT INTO `models` (`id`, `name`, `type`, `data`, `status`) VALUES
(1, '文章', 'info', 'a:1:{s:6:"fields";a:5:{i:0;a:7:{s:4:"name";s:5:"title";s:5:"field";s:5:"title";s:5:"cname";s:6:"标题";s:4:"type";s:6:"system";s:9:"frontshow";i:1;s:4:"must";i:1;s:4:"sort";s:1:"0";}i:1;a:7:{s:4:"name";s:5:"cover";s:5:"field";s:5:"cover";s:5:"cname";s:6:"封面";s:4:"type";s:6:"system";s:9:"frontshow";i:1;s:4:"sort";s:1:"1";s:4:"must";i:0;}i:2;a:7:{s:4:"name";s:6:"author";s:5:"field";s:6:"author";s:5:"cname";s:6:"作者";s:4:"type";s:6:"system";s:4:"sort";s:1:"2";s:9:"frontshow";i:0;s:4:"must";i:0;}i:3;a:7:{s:4:"name";s:7:"summary";s:5:"field";s:2:"p2";s:5:"cname";s:12:"简要信息";s:4:"type";s:5:"mtext";s:4:"sort";s:1:"3";s:9:"frontshow";i:0;s:4:"must";i:0;}i:4;a:7:{s:4:"name";s:7:"content";s:5:"field";s:7:"content";s:5:"cname";s:6:"内容";s:4:"type";s:6:"system";s:9:"frontshow";i:1;s:4:"must";i:1;s:4:"sort";s:1:"4";}}}', 1);

INSERT INTO `settings` (`name`, `value`, `autoload`) VALUES
('actived_plugins', 'a:2:{s:3:"pub";a:1:{i:0;s:8:"admintip";}s:7:"comment";a:1:{i:0;s:13:"artcommentnum";}}', 'yes'),
('admin_menu','a:9:{i:0;a:6:{s:4:"name";s:6:"首页";s:3:"app";s:4:"base";s:3:"mod";s:5:"index";s:5:"fixed";b:1;s:6:"enable";b:1;s:4:"sort";s:1:"0";}i:1;a:5:{s:4:"name";s:6:"评论";s:3:"app";s:7:"comment";s:5:"fixed";b:0;s:6:"enable";b:0;s:4:"sort";s:1:"1";}i:2;a:5:{s:4:"name";s:6:"用户";s:3:"app";s:4:"user";s:5:"fixed";b:0;s:6:"enable";b:0;s:4:"sort";s:1:"2";}i:3;a:6:{s:4:"name";s:6:"内容";s:3:"app";s:3:"cms";s:3:"mod";s:7:"content";s:5:"fixed";b:0;s:6:"enable";b:1;s:4:"sort";s:1:"3";}i:4;a:6:{s:4:"name";s:12:"内容设置";s:3:"app";s:3:"cms";s:3:"mod";s:7:"setting";s:5:"fixed";b:0;s:6:"enable";b:1;s:4:"sort";s:1:"4";}i:5;a:6:{s:4:"name";s:6:"标签";s:3:"app";s:3:"cms";s:3:"mod";s:3:"tag";s:5:"fixed";b:0;s:6:"enable";b:1;s:4:"sort";s:1:"6";}i:6;a:6:{s:4:"name";s:6:"模版";s:3:"app";s:4:"base";s:3:"mod";s:8:"template";s:5:"fixed";b:1;s:6:"enable";b:1;s:4:"sort";s:1:"7";}i:7;a:6:{s:4:"name";s:6:"模型";s:3:"app";s:3:"cms";s:3:"mod";s:5:"model";s:5:"fixed";b:0;s:6:"enable";b:1;s:4:"sort";s:1:"7";}i:8;a:6:{s:4:"name";s:6:"设置";s:3:"app";s:4:"base";s:3:"mod";s:7:"setting";s:5:"fixed";b:1;s:6:"enable";b:1;s:4:"sort";s:1:"8";}}', 'yes'),
('comment_setting', 'a:8:{s:12:"open_comment";s:1:"1";s:11:"allow_guest";s:1:"1";s:11:"allow_reply";s:1:"1";s:13:"allow_support";s:1:"1";s:12:"allow_object";s:1:"1";s:10:"need_audit";s:1:"0";s:16:"comments_perpage";s:2:"10";s:14:"enable_captcha";s:1:"0";}', 'yes'),
('current_theme', 'default', 'yes'),
('default_app', 'base', 'yes'),
('default_company_tpl', '1', 'yes'),
('default_user_level', '1', 'yes'),
('enable_rewrite', '1', 'yes'),
('enable_route', '1', 'yes'),
('enable_wap', '0', 'yes'),
('group_setting', 'a:3:{s:12:"open_comment";s:1:"1";s:12:"allow_create";s:1:"1";s:16:"group_need_audit";s:1:"0";}', 'yes'),
('html_cache_time', '0', 'yes'),
('icp', '苏ICP备05065585号', 'yes'),
('rewrite_style', '1', 'yes'),
('site_seo_description', '', 'yes'),
('site_seo_keywords', '', 'yes'),
('site_seo_title', '', 'yes'),
('site_sub_title', '又一个Meiu站点', 'yes'),
('site_title', '企业站示例', 'yes'),
('stats', '', 'yes'),
('user_fields', 'a:2:{s:6:"extra1";a:2:{s:5:"cname";s:2:"QQ";s:4:"show";i:1;}s:6:"extra2";a:2:{s:5:"cname";s:6:"电话";s:4:"show";i:1;}}', 'yes'),
('user_setting', 'a:4:{s:15:"enable_register";s:1:"1";s:20:"enable_login_captcha";s:1:"0";s:18:"enable_reg_captcha";s:1:"1";s:20:"enable_admin_captcha";s:1:"1";}', 'yes');

INSERT INTO `routes` (`id`, `route`, `params`, `sort`) VALUES
(1, 'cate/{dirname}.html', 'app=cms&m=cate&catetype=index', 0),
(2, 'pages/{dirname}.html', 'app=cms&m=cate&catetype=page', 1),
(3, 'cate/{dirname}/index{:page}.html', 'app=cms&m=cate', 3),
(4, 'info/{path}.html', 'app=cms&m=info', 4),
(5, 'info/{dirname}/{id}.html', 'app=cms&m=info', 5),
(8, 'login', 'app=user&m=login', 50),
(9, 'register', 'app=user&m=register', 50),
(10, 'search', 'app=cms&m=search', 50),
(11, 'sitemap', 'app=cms&m=sitemap', 50);