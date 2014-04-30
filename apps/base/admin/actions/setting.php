<?php 
defined('IN_MWEB') or die('access denied');

/*二级菜单开始*/
$submenu = array(
    '基本' => array('index'),
    '自定义路由' => array('route','routeadd','routeedit'),
    '后台菜单' => array('menu'),
    '插件扩展' => array('plugin','pluginset'),
    '系统信息及缓存' => array('system')
);
$view->assign('submenu',$submenu);
/*二级菜单结束*/

$act = getGet('a','index');
$view->assign('act',$act);

switch ($act) {
    case 'index':
        $m_setting = M('settings');
        if(isPost()){
            $settings = getPost('setting');
            foreach ($settings as $key => $value) {
                $data = array(
                    'name' => $key,
                    'value' => trim($value),
                    'autoload' => 'yes'
                );
                $m_setting->insert($data,true);
            }
            //刷新缓存
            app('base')->refreshSettings();
            alert('修改设置成功！',true,U('base','setting'));
        }

        $settings_info = app('base')->getAllSettings();
        $view->assign('settings_info',$settings_info);
        $view->display('setting.php');
        break;
    case 'route':
        $page = getGet('page',1);
        $m_route = M('routes');

        if(isPost()){
            $sort = getPost('sort');
            foreach ($sort as $key => $value) {
                $m_route->update($key,array('sort'=>$value));
            }
            app('base')->refreshRoute();
            alert('更新排序成功！',true,U('base','setting','a=route&page='.$page));
        }

        $totalCount = $m_route->count();
        $pageurl = U('base','setting','a=route&page=%page%');

        $pager = new Pager($page,C('pageset.admin',15),$totalCount,$pageurl);
        $pager->config(C('page'));
        $limit = $pager->getLimit();

        $rows = $m_route->findAll(array(
            'start' => $limit['start'],
            'limit' => $limit['limit'],
            'order' => 'sort asc'
        ));
        $view->assign('rows',$rows);

        $view->assign('pagestr',$pager->html());

        $view->display('setting_route.php');
        break;
    case 'routeedit':

        $id = intval(getGet('id'));
        $m_route = M('routes');
        if(isPost()){
            $route = trim(getPost('route'));
            $params = trim(getPost('params'));

            if(empty($route)){
                alert('请输入路由！');
            }
            if(empty($params)){
                alert('请输入映射参数！');
            }
            //判断隐射参数
            parse_str($params,$params_arr);
            if(!isset($params_arr['app']) || !isset($params_arr['m'])){
                alert('映射参数必须包含app和m参数！');
            }


            $data = array(
                'route' => $route,
                'params' => $params
            );

            if($m_route->update($id,$data)){
                app('base')->refreshRoute();
                alert('编辑成功！',true,U('base','setting','a=route'));
            }else{
                alert('编辑失败！');
            }
        }

        $route_info = $m_route->load($id);

        if(!$route_info){
            alert('您要编辑的路由不存在！');
        }

        $view->assign('route_info',$route_info);
        $view->display('route_edit.php');
        break;
     case 'routeadd':
        $m_route = M('routes');

        if(isPost()){
            $route = trim(getPost('route'));
            $params = trim(getPost('params'));

            if(empty($route)){
                alert('请输入路由！');
            }
            if(empty($params)){
                alert('请输入映射参数！');
            }
            //判断隐射参数
            parse_str($params,$params_arr);
            if(!isset($params_arr['app']) || !isset($params_arr['m'])){
                alert('映射参数必须包含app和m参数！');
            }

            $data = array(
                'route'  => $route,
                'params' => $params,
                'sort'   => 50
            );

            if($m_route->insert($data)){
                app('base')->refreshRoute();
                alert('添加路由规则成功！',true,U('base','setting','a=route'));
            }else{
                alert('添加路由规则失败！');
            }
        }

        $route_info = $m_route->loadDefault();

        $view->assign('route_info',$route_info);
        $view->display('route_edit.php');
        break;
    case 'routedel':
        $id = intval(getGet('id'));
        $m_route = M('routes');
        if($m_route->delete($id)){
            app('base')->refreshRoute();
            alert('删除成功！',true,U('base','setting','a=route'));
        }else{
            alert('删除失败！');
        }
        break;
    case 'system':
        $view->display('setting_system.php');
        break;
    case 'clearcache':
        $cache->clean();
        alert('清空缓存成功！',true);
        break;
    case 'menu':
        if(isPost()){
            $sort = getPost('sort');
            $enable = getPost('enable');

            $menuslist = app('base')->getSetting('admin_menu',true);
            foreach ($menuslist as $key => $value) {
                $skey = $value['app'];
                if(isset($value['mod'])){
                    $skey .= '_'.$value['mod'];
                }
                if(isset($value['cid'])){
                    $skey .= '_'.$value['cid'];
                }

                $menuslist[$key]['sort'] = $sort[$skey];
                $menuslist[$key]['enable'] = isset($enable[$skey])?true:false;
            }
            $baseapp = app('base');
            usort($menuslist,'sortFields');
            app('base')->setSetting('admin_menu',$menuslist);

            alert('更新排序成功！',true,'js_reload');
        }
        $menulist = app('base')->getSetting('admin_menu',true);
        $view->assign('menulist',$menulist);
        $view->display('setting_menu.php');
        break;
    case 'menudel':
        $appid = getGet('appid');
        $modid = getGet('modid');
        $cid = getGet('cid');

        $quest_key = $appid.($modid?'_'.$modid:'').($cid?'_'.$cid:'');
        $menuslist = app('base')->getSetting('admin_menu',true);
        foreach ($menuslist as $key => $value) {
            $skey = $value['app'];
            if(isset($value['mod'])){
                $skey .= '_'.$value['mod'];
            }
            if(isset($value['cid'])){
                $skey .= '_'.$value['cid'];
            }
            if($skey == $quest_key && !$value['fixed']){
                unset($menuslist[$key]);
            }
        }
        app('base')->setSetting('admin_menu',$menuslist);
        alert('删除成功！',true,'js_reload');
        break;
    case 'plugin':
        $pluginapp = getGet('pluginapp');
        //读取plugin列表
        $arrApps = dirlist('',ROOT_DIR.'plugins');

        $pluginList = array();
        $appList = array();
        foreach ($arrApps as $value) {
            if($value['filename'] == '..' || $value['type'] != 'dir'){
                continue;
            }
            $app = $value['filename'];
            $appList[] = $app;

            if($pluginapp && $pluginapp!=$app){//筛选了app
                continue;
            }
            //子文件夹
            $plugins = dirlist($app,ROOT_DIR.'plugins');
            foreach ($plugins as $plugin) {
                if($plugin['filename'] == '..' || $plugin['type'] != 'dir'){
                    continue;
                }
                if(!checkPlugin($plugin['filename'],$app)){
                    continue;
                }
                $pluginList[] = getPluginData($plugin['filename'],$app);
            }
        }
        $activedPlugins = app('base')->getSetting('actived_plugins',true);

        $view->assign('pluginapp',$pluginapp);
        $view->assign('appList',$appList);
        $view->assign('activedPlugins',$activedPlugins);
        $view->assign('pluginList',$pluginList);
        $view->display('setting_plugin.php');
        break;
    case 'pluginset':
        $app = getGet('pluginapp');
        $plugin = getGet('plugin');

        require_once ROOT_DIR."plugins/{$app}/{$plugin}/{$plugin}_setting.php";

        if(isPost()){
            if(plugin_setting_save()){
                alert('设置成功！',true,U('base','setting','a=plugin'));
            }else{
                alert('设置失败！');
            }
        }

        $pluginSettingContent = plugin_setting_view();
        $view->assign('pluginSettingContent',$pluginSettingContent);
        $view->display('setting_pluginset.php');
        break;
    case 'pluginactive':
        $app = getGet('pluginapp');
        $plugin = getGet('plugin');
        $status = getGet('status');

        $activedPlugins = app('base')->getSetting('actived_plugins',true);

        if($status == 'inactive' && isset($activedPlugins[$app])){
            //禁用已启用插件
            $key = array_search($plugin, $activedPlugins[$app]);
            unset($activedPlugins[$app][$key]);

            $ret = true;
            app('base')->setSetting('actived_plugins',$activedPlugins);

            alert('禁用成功！',true,'js_reload');
        }elseif($status == 'active'){
            if (isset($activedPlugins[$app]) && in_array($plugin, $activedPlugins[$app])) {
                $ret = true;
            } elseif(true === checkPlugin($plugin,$app)) {
                $activedPlugins[$app][] = $plugin;
                $ret = true;
                app('base')->setSetting('actived_plugins',$activedPlugins);

                alert('启用成功！',true,'js_reload');
            }else{
                $ret = false;
                alert('启用失败！',false);
            }

        }
        break;
}