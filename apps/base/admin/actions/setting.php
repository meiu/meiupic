<?php 
defined('IN_MWEB') or die('access denied');

class BaseSetting extends Adminbase{
    protected $_submenu = array(
        '基本' => array('index'),
        '自定义路由' => array('route','routeadd','routeedit'),
        '后台菜单' => array('menu'),
        '插件扩展' => array('plugin','pluginset'),
        '系统信息及缓存' => array('system')
    );

    function indexAct(){
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
        $this->view->assign('settings_info',$settings_info);
        $this->view->display('setting.php');
    }

    function routeAct(){
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
        $pager->config(C('adminpage'));
        $limit = $pager->getLimit();

        $rows = $m_route->findAll(array(
            'start' => $limit['start'],
            'limit' => $limit['limit'],
            'order' => 'sort asc'
        ));
        $this->view->assign('rows',$rows);

        $this->view->assign('pagestr',$pager->html());

        $this->view->display('setting_route.php');
    }

    function routeeditAct(){
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

        $this->view->assign('route_info',$route_info);
        $this->view->display('route_edit.php');
    }

    function routeaddAct(){
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

        $this->view->assign('route_info',$route_info);
        $this->view->display('route_edit.php');
    }

    function routedelAct(){
        $id = intval(getGet('id'));
        $m_route = M('routes');
        if($m_route->delete($id)){
            app('base')->refreshRoute();
            alert('删除成功！',true,U('base','setting','a=route'));
        }else{
            alert('删除失败！');
        }
    }

    function systemAct(){
        $this->view->display('setting_system.php');
    }

    function clearcacheAct(){
        $this->cache->clean();
        alert('清空缓存成功！',true);
    }

    function menuAct(){
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
        $this->view->assign('menulist',$menulist);
        $this->view->display('setting_menu.php');
    }

    function menudelAct(){
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
    }

    function pluginAct(){
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

        $this->view->assign('pluginapp',$pluginapp);
        $this->view->assign('appList',$appList);
        $this->view->assign('activedPlugins',$activedPlugins);
        $this->view->assign('pluginList',$pluginList);
        $this->view->display('setting_plugin.php');
    }

    function pluginsetAct(){
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
        $this->view->assign('pluginSettingContent',$pluginSettingContent);
        $this->view->display('setting_pluginset.php');
    }

    function pluginactiveAct(){
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
    }
}